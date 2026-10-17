<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingProgress;
use App\Models\Bookmark;
use App\Models\Verse;
class DasahboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->id();

        /*
        |--------------------------------------------------------------------------
        | Verses Read
        |--------------------------------------------------------------------------
        */

       $totalVerses = Verse::count();

$completedVerses = 0;

$progressRecords = ReadingProgress::where('user_id', $userId)
    ->with('verse')
    ->get()
    ->groupBy('surah_id');

foreach ($progressRecords as $surahId => $records) {

    $lastRecord = $records->sortByDesc(function ($record) {
        return $record->verse?->verse_number ?? 0;
    })->first();

    if (!$lastRecord || !$lastRecord->verse) {
        continue;
    }

    $lastVerseNumber = $lastRecord->verse->verse_number;

    $surahTotalVerses = Verse::where('surah_id', $surahId)->count();

    $completedVerses += min($lastVerseNumber, $surahTotalVerses);
}

$versesRead = $completedVerses;

$quranProgress = ceil($totalVerses > 0
    ? round(($completedVerses / $totalVerses) * 100, 1)
    : 0);
        

    $tafsirsRead = ReadingProgress::where('reading_progress.user_id', $userId)
    ->whereExists(function ($query) {
        $query->selectRaw('1')
            ->from('tafsirs')
            ->whereColumn('tafsirs.start_verse_id', 'reading_progress.verse_id')
            ->orWhereColumn('tafsirs.end_verse_id', 'reading_progress.verse_id');
    })
    ->distinct('verse_id')
    ->count('verse_id');


        /*
        |--------------------------------------------------------------------------
        | Bookmarks
        |--------------------------------------------------------------------------
        */

        $bookmarksCount = Bookmark::where('user_id', $userId)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Reading Days
        |--------------------------------------------------------------------------
        */

        $readingDays = ReadingProgress::where('user_id', $userId)
            ->selectRaw('DATE(created_at) as reading_date')
            ->distinct()
            ->get()
             ->count();

        /*
        |--------------------------------------------------------------------------
        | Quran Progress
        |--------------------------------------------------------------------------
        */

    
    $lastReading = ReadingProgress::with(['verse.surah'])
        ->where('user_id', $userId)
        ->latest('created_at')
        ->first();

        return view('dashboard', compact(
            'versesRead',
            'tafsirsRead',
            'bookmarksCount',
            'readingDays',
            'quranProgress',
            'totalVerses',
            'lastReading'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */

      public function index_for_about_author()
    {
        return view('aboutauthor');
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
