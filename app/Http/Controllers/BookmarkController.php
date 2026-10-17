<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\bookmark as BookmarkModel;

class BookmarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $bookmarks = BookmarkModel::with([
        'startVerse.surah',
        'endVerse'
    ])
    ->where('user_id', auth()->id())
    ->orderBy('id', 'desc')
    ->paginate(15);

    return view('bookmarks.index', compact('bookmarks'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */

   public function store(Request $request)
    {
        $request->validate([
            'start_verse_id' => ['required', 'integer', 'exists:verses,id'],
            'end_verse_id' => ['required', 'integer', 'exists:verses,id'],
        ]);

        $userId = auth()->id();

        // Check if bookmark already exists
        $bookmark = BookmarkModel::where('user_id', $userId)
            ->where('start_verse_id', $request->start_verse_id)
            ->where('end_verse_id', $request->end_verse_id)
            ->first();

        // If already bookmarked → delete it
        if ($bookmark) {
            $bookmark->delete();

            return response()->json([
                'success' => true,
                'bookmarked' => false,
                'message' => 'Bookmark removed.',
            ]);
        }

        // If not bookmarked → create it
        BookmarkModel::create([
            'user_id' => $userId,
            'start_verse_id' => $request->start_verse_id,
            'end_verse_id' => $request->end_verse_id,
        ]);

        return response()->json([
            'success' => true,
            'bookmarked' => true,
            'message' => 'Bookmark saved.',
        ]);
    }


    /**
     * Display the specified resource.
     */
  public function show(string $id)
{
    $bookmark = BookmarkModel::with([
        'startVerse.surah',
        'endVerse'
    ])->findOrFail($id);

    return view('bookmarks.show', compact('bookmark'));
}
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
     
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $bookmark=BookmarkModel::findOrFail($id);
        $bookmark->delete();

        return redirect()->route('bookmarks.index')->with('success', 'Delete Bookmark successfully');
    }
}
