<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Surah;
use App\Models\Verse;

class ImportQuran extends Command
{
    protected $signature = 'quran:import {--fetch : Fetch Quran data from api.quran.com and save to database/data/quran.json}';

    protected $description = 'Import the complete Quran into surahs and verses tables (idempotent).';

    public function handle()
    {
        $this->info('Starting Quran import...');

        // prepare local data folder
        $dataPath = base_path('database/data');
        if (!is_dir($dataPath)) {
            mkdir($dataPath, 0755, true);
        }

        $jsonFile = $dataPath . DIRECTORY_SEPARATOR . 'quran.json';

        if ($this->option('fetch')) {
            $this->info('Fetching chapters metadata...');
            $chapResp = Http::get('https://api.quran.com/api/v4/chapters?language=en');
            if (!$chapResp->ok()) {
                $this->error('Failed to fetch chapters metadata: ' . $chapResp->status());
                return 1;
            }

            $chapters = $chapResp->json()['chapters'] ?? null;
            if (!$chapters) {
                $this->error('No chapters found in chapters response.');
                return 1;
            }

            $this->info('Fetching all verses (uthmani)...');
            $versesResp = Http::get('https://api.quran.com/api/v4/quran/verses/uthmani?language=ar&per_page=6236');
            if (!$versesResp->ok()) {
                $this->error('Failed to fetch verses: ' . $versesResp->status());
                return 1;
            }

            $versesData = $versesResp->json()['verses'] ?? null;
            if (!$versesData) {
                $this->error('No verses found in verses response.');
                return 1;
            }

            // build structure
            $surahs = [];
            foreach ($chapters as $chap) {
                $surahs[$chap['id']] = [
                    'number' => $chap['id'],
                    'name_ar' => $chap['name_arabic'] ?? ($chap['name_simple'] ?? ''),
                    'name_en' => $chap['name_simple'] ?? '',
                    'verses_count' => intval($chap['verses_count'] ?? 0),
                    'verses' => [],
                ];
            }

            foreach ($versesData as $v) {
                // verse_key looks like "s:v"
                $key = $v['verse_key'] ?? null;
                $text = $v['text_uthmani'] ?? ($v['text'] ?? '');
                if (!$key) continue;
                [$snum, $vnum] = explode(':', $key);
                $snum = intval($snum);
                $vnum = intval($vnum);

                if (!isset($surahs[$snum])) {
                    // create placeholder if missing
                    $surahs[$snum] = [
                        'number' => $snum,
                        'name_ar' => '',
                        'name_en' => '',
                        'verses_count' => 0,
                        'verses' => [],
                    ];
                }

                $surahs[$snum]['verses'][] = [
                    'number' => $vnum,
                    'text' => $text,
                ];
            }

            // convert to sequential array
            $surahsList = array_values($surahs);

            // basic validation
            if (count($surahsList) !== 114) {
                $this->warn('Warning: fetched surah count is ' . count($surahsList) . ' (expected 114). Proceeding anyway.');
            }

            // save to file
            $payload = ['surahs' => $surahsList];
            file_put_contents($jsonFile, json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
            $this->info('Saved Quran JSON to: ' . $jsonFile);
        }

        if (!file_exists($jsonFile)) {
            $this->error('Quran JSON file not found at ' . $jsonFile . '. Run with --fetch to download first.');
            return 1;
        }

        $this->info('Loading Quran JSON file...');
        $raw = file_get_contents($jsonFile);
        $data = json_decode($raw, true);
        if (!$data || !isset($data['surahs'])) {
            $this->error('Invalid Quran JSON structure.');
            return 1;
        }

        $surahs = $data['surahs'];

        // begin import
        DB::beginTransaction();
        try {
            $totalSurahs = 0;
            $totalVerses = 0;

            foreach ($surahs as $s) {
                $totalSurahs++;
                $sNumber = intval($s['number']);
                $surah = Surah::updateOrCreate(
                    ['number' => $sNumber],
                    [
                        'name_ar' => $s['name_ar'] ?? '',
                        'name_en' => $s['name_en'] ?? '',
                        'verses_count' => intval($s['verses_count'] ?? count($s['verses'] ?? [])),
                    ]
                );

                foreach ($s['verses'] as $v) {
                    $totalVerses++;
                    Verse::updateOrCreate(
                        ['surah_id' => $surah->id, 'verse_number' => intval($v['number'])],
                        ['arabic_text' => $v['text'] ?? '']
                    );
                }
            }

            DB::commit();

            $this->info('Import completed successfully.');
            $this->info('Surahs: ' . $totalSurahs);
            $this->info('Verses: ' . $totalVerses);

            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }
}
