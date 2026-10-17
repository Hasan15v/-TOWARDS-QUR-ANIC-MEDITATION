<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;

class ImportTafsirWord extends Command
{
    protected $signature = 'tafsir:import {file}';

    protected $description = 'Import multi-surah Quran Tafsir Word document';

    public function handle()
    {
        $file = $this->argument('file');

        if (!file_exists($file)) {
            $this->error("File not found: {$file}");
            return self::FAILURE;
        }

        $this->info('Loading Word document...');

        try {
            $phpWord = IOFactory::load($file);
        } catch (\Throwable $e) {
            $this->error('Could not read Word document.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        /*
        |--------------------------------------------------------------------------
        | Collect all Word text
        |--------------------------------------------------------------------------
        */

        $lines = [];

        foreach ($phpWord->getSections() as $section) {

            foreach ($section->getElements() as $element) {

                $text = $this->extractElementText($element);

                if ($text === '') {
                    continue;
                }

                $text = $this->cleanText($text);

                if ($text !== '') {
                    $lines[] = $text;
                }
            }
        }

        $this->info('Reading document structure...');

        /*
        |--------------------------------------------------------------------------
        | Parse Surahs
        |--------------------------------------------------------------------------
        */

        $surahs = [];

        $currentSurah = null;
        $currentSurahNumber = null;
        $currentSurahName = null;
        $currentVerseNumber = null;
        $currentVerseText = '';

        $currentTafsirNumber = null;
        $currentTafsirText = '';

        $mode = null;

        foreach ($lines as $line) {

            /*
            |--------------------------------------------------------------------------
            | Detect Chapter
            |--------------------------------------------------------------------------
            */

            if (preg_match('/^Chapter\s+(\d+)$/i', $line, $match)) {

                /*
                 * Save previous tafsir/verse/surah before starting another chapter.
                 */

                $this->saveCurrentTafsir(
                    $surahs,
                    $currentSurah,
                    $currentVerseNumber,
                    $currentTafsirNumber,
                    $currentTafsirText
                );

                $this->saveCurrentVerse(
                    $surahs,
                    $currentSurah,
                    $currentVerseNumber,
                    $currentVerseText
                );

                $currentSurah = null;
                $currentSurahNumber = (int) $match[1];
                $currentSurahName = null;

                $currentVerseNumber = null;
                $currentVerseText = '';

                $currentTafsirNumber = null;
                $currentTafsirText = '';

                $mode = 'surah';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Detect Surah name
            |--------------------------------------------------------------------------
            */

            if (preg_match(
                '/^Sūrah\(t\)\s+(.+)$/u',
                $line,
                $match
            )) {

                $currentSurahName = trim($match[1]);

                /*
                 * Normalize name.
                 */

                $currentSurah = $this->normalizeSurahName(
                    $currentSurahName
                );

                $this->info(
                    "Found Surah {$currentSurahNumber}: {$currentSurahName}"
                );

                $mode = 'header';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Detect verse range
            |
            | Example:
            |
            | al-Fajr 89:1-14
            |
            |--------------------------------------------------------------------------
            */

            if (preg_match(
                '/^(.+?)\s+(\d+):(\d+)(?:-(\d+))?$/u',
                $line,
                $match
            )) {

                $mode = 'quran';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Detect Tafsir marker
            |
            | Example:
            |
            | (1)
            | (2)
            | (3)
            |
            |--------------------------------------------------------------------------
            */

            if (preg_match('/^\((\d+)\)\s*(.*)$/u', $line, $match)) {

                /*
                 * Save previous tafsir.
                 */

                if (
                    $currentTafsirNumber !== null &&
                    $currentTafsirText !== ''
                ) {
                    $this->saveTafsirRecord(
                        $surahs,
                        $currentSurah,
                        $currentTafsirNumber,
                        $currentTafsirText
                    );
                }

                $currentTafsirNumber = (int) $match[1];

                $currentTafsirText = trim($match[2]);

                $mode = 'tafsir';

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Arabic Quran text
            |--------------------------------------------------------------------------
            */

            if ($mode === 'quran') {

                /*
                 * Ignore obvious document/header text.
                 */

                if ($this->isDocumentNoise($line)) {
                    continue;
                }

                $currentVerseText .= ' ' . $line;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Tafsir continuation
            |--------------------------------------------------------------------------
            */

            if ($mode === 'tafsir') {

                /*
                 * Ignore document headers.
                 */

                if ($this->isDocumentNoise($line)) {
                    continue;
                }

                $currentTafsirText .= ' ' . $line;

                continue;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Save last records
        |--------------------------------------------------------------------------
        */

        if (
            $currentTafsirNumber !== null &&
            $currentTafsirText !== ''
        ) {
            $this->saveTafsirRecord(
                $surahs,
                $currentSurah,
                $currentTafsirNumber,
                $currentTafsirText
            );
        }

        $this->saveCurrentVerse(
            $surahs,
            $currentSurah,
            $currentVerseNumber,
            $currentVerseText
        );

        /*
        |--------------------------------------------------------------------------
        | Show result
        |--------------------------------------------------------------------------
        */

        $this->newLine();

        $this->info('======================================');
        $this->info('Document parsed successfully.');
        $this->info('======================================');

        foreach ($surahs as $surahName => $data) {

            $this->line(
                $surahName .
                ' → ' .
                count($data['verses']) .
                ' verses'
            );
        }

        $this->newLine();

        /*
        |--------------------------------------------------------------------------
        | Import into database
        |--------------------------------------------------------------------------
        */

        $this->info('Importing into database...');

        DB::transaction(function () use ($surahs) {

            foreach ($surahs as $surahName => $data) {

                /*
                |--------------------------------------------------------------------------
                | Find Surah
                |--------------------------------------------------------------------------
                */

                $surah = DB::table('surhes')
                    ->where('name_en', $surahName)
                    ->first();

                if (!$surah) {

                    $this->warn(
                        "Surah not found in database: {$surahName}"
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Import verses
                |--------------------------------------------------------------------------
                */

                foreach ($data['verses'] as $verseNumber => $verseData) {

                    $verseText = trim(
                        $verseData['text'] ?? ''
                    );

                    if ($verseText === '') {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Find existing verse
                    |--------------------------------------------------------------------------
                    */

                    $verse = DB::table('verses')
                        ->where('surah_id', $surah->id)
                        ->where('verse_number', $verseNumber)
                        ->first();

                    if (!$verse) {

                        $verseId = DB::table('verses')->insertGetId([
                            'surah_id' => $surah->id,
                            'verse_number' => $verseNumber,
                            'arabic_text' => $verseText,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);

                    } else {

                        $verseId = $verse->id;

                        DB::table('verses')
                            ->where('id', $verseId)
                            ->update([
                                'arabic_text' => $verseText,
                                'updated_at' => now(),
                            ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Import Tafsir
                    |--------------------------------------------------------------------------
                    */

                    if (!empty($verseData['tafsir'])) {

                        $tafsirText = trim(
                            $verseData['tafsir']
                        );

                        /*
                        | Beginner by default.
                        |
                        | Change this later if the Word file
                        | contains separate levels.
                        */

                        DB::table('tafsirs')->updateOrInsert(
                            [
                                'surah_id' => $surah->id,
                                'start_verse_id' => $verseId,
                                'level' => 'beginner',
                            ],
                            [
                                'end_verse_id' => $verseId,
                                'text' => $tafsirText,
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    }
                }
            }
        });

        $this->newLine();
        $this->info('======================================');
        $this->info('Import completed successfully!');
        $this->info('======================================');

        return self::SUCCESS;
    }


    /*
    |--------------------------------------------------------------------------
    | Extract text from Word element
    |--------------------------------------------------------------------------
    */

    private function extractElementText($element): string
    {
        if ($element instanceof Text) {

            return (string) $element->getText();
        }

        if ($element instanceof TextRun) {

            $result = '';

            foreach ($element->getElements() as $child) {

                if ($child instanceof Text) {

                    $result .= $child->getText();
                }
            }

            return $result;
        }

        return '';
    }


    /*
    |--------------------------------------------------------------------------
    | Clean Word text
    |--------------------------------------------------------------------------
    */

    private function cleanText(string $text): string
    {
        $text = str_replace(
            ["\r", "\n", "\t"],
            ' ',
            $text
        );

        $text = preg_replace(
            '/\s+/u',
            ' ',
            $text
        );

        return trim($text);
    }


    /*
    |--------------------------------------------------------------------------
    | Detect document headers/noise
    |--------------------------------------------------------------------------
    */

    private function isDocumentNoise(string $text): bool
    {
        if (
            stripos(
                $text,
                'The Glorious Qur’ān: A Multi-Layered'
            ) !== false
        ) {
            return true;
        }

        return false;
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize Surah names
    |--------------------------------------------------------------------------
    */

    private function normalizeSurahName(string $name): string
    {
        return trim($name);
    }


    /*
    |--------------------------------------------------------------------------
    | Save Tafsir in temporary structure
    |--------------------------------------------------------------------------
    */

    private function saveTafsirRecord(
        array &$surahs,
        ?string $surahName,
        ?int $tafsirNumber,
        string $text
    ): void {

        if (!$surahName || !$tafsirNumber) {
            return;
        }

        if (!isset($surahs[$surahName])) {

            $surahs[$surahName] = [
                'verses' => [],
            ];
        }

        if (!isset($surahs[$surahName]['verses'][$tafsirNumber])) {

            $surahs[$surahName]['verses'][$tafsirNumber] = [
                'text' => '',
                'tafsir' => '',
            ];
        }

        $surahs[$surahName]['verses'][$tafsirNumber]['tafsir'] =
            trim($text);
    }


    /*
    |--------------------------------------------------------------------------
    | Save current verse
    |--------------------------------------------------------------------------
    */

    private function saveCurrentVerse(
        array &$surahs,
        ?string $surahName,
        ?int &$verseNumber,
        string &$verseText
    ): void {

        if (!$surahName) {
            return;
        }

        if ($verseText === '') {
            return;
        }

        /*
         * At this stage we don't know the exact verse number
         * from the Arabic OCR-like Word text.
         *
         * The number will be matched later using the tafsir
         * markers.
         */

        if (!isset($surahs[$surahName])) {

            $surahs[$surahName] = [
                'verses' => [],
            ];
        }

        $verseNumber++;

        $surahs[$surahName]['verses'][$verseNumber] = [
            'text' => trim($verseText),
            'tafsir' => '',
        ];

        $verseText = '';
    }
}