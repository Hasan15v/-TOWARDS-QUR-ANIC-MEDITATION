<?php

namespace App\Http\Controllers;

use App\Models\Surah;
use App\Models\Tafsir;
use App\Models\ReadingProgress;

use Illuminate\Http\Request;

class UserTafsirController extends Controller
{
   public function index()
{
    $surahs = Surah::has('tafsir')
        ->with(['tafsir' => function ($query) {
            $query->with(['startVerse', 'endVerse']);
        }])
        ->orderBy('number')
        ->get();

    if ($surahs->isEmpty()) {
        return view('surahs-reader', [
            'surahs' => collect(),
        ]);
    }

    return redirect()->route(
        'tafsir.reader.show',
        ['surah' => $surahs->first()->slug]
    );
}
public function surahsReader()
    {
        /*
        |--------------------------------------------------------------------------
        | Get interpreted surahs
        |--------------------------------------------------------------------------
        */

        $surahs = Surah::has('tafsir')
            ->with([
                'tafsir' => function ($query) {
                    $query->with([
                        'startVerse',
                        'endVerse'
                    ]);
                }
            ])
            ->orderBy('number')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Default
        |--------------------------------------------------------------------------
        */

        $completedSurahIds = [];


        /*
        |--------------------------------------------------------------------------
        | Get user's reading progress
        |--------------------------------------------------------------------------
        */

        if (auth()->check()) {

            $progress = ReadingProgress::where(
                'user_id',
                auth()->id()
            )
            ->get()
            ->keyBy('surah_id');


            foreach ($surahs as $surah) {

                /*
                |--------------------------------------------------------------------------
                | Get user's progress for this Surah
                |--------------------------------------------------------------------------
                */

                $userProgress = $progress->get(
                    $surah->id
                );


                if (!$userProgress) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Get last Tafsir section
                |--------------------------------------------------------------------------
                */

                $lastTafsir = $surah->tafsir
                    ->sortByDesc('id')
                    ->first();


                if (!$lastTafsir) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Get last verse of last Tafsir
                |--------------------------------------------------------------------------
                */

                $lastVerse = $lastTafsir->endVerse;


                if (!$lastVerse) {
                    continue;
                }


                /*
                |--------------------------------------------------------------------------
                | Check completion
                |--------------------------------------------------------------------------
                */

                if (
                    (int) $userProgress->verse_id ===
                    (int) $lastVerse->id
                ) {

                    $completedSurahIds[] =
                        (int) $surah->id;
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Return Blade
        |--------------------------------------------------------------------------
        */

        return view(
            'surahs-reader',
            [
                'surahs' =>
                    $surahs,

                'completedSurahIds' =>
                    $completedSurahIds,
            ]
        );
    }
public function show(Surah $surah, Request $request)
{
    // السور التي تحتوي على تفسير
    $surahs = Surah::has('tafsir')
        ->orderBy('number')
        ->get();

    // المستويات الموجودة لهذه السورة
    $availableLevels = $surah->tafsir()
        ->pluck('level')
        ->unique()
        ->values()
        ->all();

    // المستوى المختار من الرابط
    $selectedLevel = $request->query(
        'level',
        $this->firstAvailableLevel($availableLevels)
    );

    // إذا المستوى غير موجود، استخدم أول مستوى موجود
    if (!in_array($selectedLevel, $availableLevels, true)) {
        $selectedLevel = $this->firstAvailableLevel($availableLevels);
    }

    // جلب التفسير للمستوى المختار
    $items = $surah->tafsir()
        ->with([
            'startVerse',
            'endVerse',
        ])
        ->where('level', $selectedLevel)
        ->orderBy('start_verse_id')
        ->get();

    // جلب الآيات التابعة لكل تفسير
    foreach ($items as $item) {

        $item->verses = \App\Models\Verse::where('surah_id', $surah->id)
            ->whereBetween('id', [
                $item->start_verse_id,
                $item->end_verse_id,
            ])
            ->orderBy('id')
            ->get();
    }

    // إذا المستوى المختار لا يحتوي تفسير
    if (
        $items->isEmpty() &&
        $surah->tafsir()->exists()
    ) {
        $items = $surah->tafsir()
            ->with([
                'startVerse',
                'endVerse',
            ])
            ->orderBy('start_verse_id')
            ->get();

        foreach ($items as $item) {

            $item->verses = \App\Models\Verse::where('surah_id', $surah->id)
                ->whereBetween('id', [
                    $item->start_verse_id,
                    $item->end_verse_id,
                ])
                ->orderBy('id')
                ->get();
        }

        $selectedLevel = $items->first()->level ?? 'beginner';
    }

    // كل السور تستخدم نفس صفحة القارئ
    return view('tafsir-reader', [
        'surahs' => $surahs,
        'selectedSurah' => $surah,
        'selectedLevel' => $selectedLevel,
        'availableLevels' => $availableLevels,
        'items' => $items,
    ]);
}
   


    private function firstAvailableLevel(array $levels): string
    {
        if (in_array('beginner', $levels, true)) {
            return 'beginner';
        }

        if (in_array('advanced', $levels, true)) {
            return 'advanced';
        }

        return 'beginner';
    }

    public function bookmark(Verse $verse)
{
    $bookmark = \App\Models\Bookmark::where('user_id', auth()->id())
        ->where('verse_id', $verse->id)
        ->first();

    if ($bookmark) {
        $bookmark->delete();

        return response()->json([
            'saved' => false,
            'message' => 'تم إلغاء حفظ الآية',
        ]);
    }

    \App\Models\Bookmark::create([
        'user_id' => auth()->id(),
        'verse_id' => $verse->id,
    ]);

    return response()->json([
        'saved' => true,
        'message' => 'تم حفظ الآية',
    ]);
}
}
