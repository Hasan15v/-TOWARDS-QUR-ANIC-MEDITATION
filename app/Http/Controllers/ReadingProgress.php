<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReadingProgress as ReadingProgressModel;

class ReadingProgress extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $readingProgresses=ReadingProgressModel::orderBy('id','desc')->paginate(15);
        return view('reading_progress.index',compact('readingProgresses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reading_progress.create');
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request)
{
    try {

        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to save reading progress.'
            ], 401);
        }

        $validated = $request->validate([
            'surah_id' => [
                'required',
                'integer',
                'exists:surhes,id',
            ],

            'verse_id' => [
                'required',
                'integer',
                'exists:verses,id',
            ],
        ]);

        $progress = ReadingProgressModel::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'surah_id' => $validated['surah_id'],
            ],
            [
                'verse_id' => $validated['verse_id'],
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Reading progress saved successfully.',
            'progress' => $progress,
        ], 200);

    } catch (\Illuminate\Validation\ValidationException $e) {

        return response()->json([
            'success' => false,
            'message' => 'Validation failed.',
            'errors' => $e->errors(),
        ], 422);

    } catch (\Throwable $e) {

        \Log::error('Reading Progress Error', [
            'user_id' => auth()->id(),
            'surah_id' => $request->surah_id,
            'verse_id' => $request->verse_id,
            'error' => $e->getMessage(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Server error while saving reading progress.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $readingProgress=ReadingProgressModel::findOrFail($id);
        return view('reading_progress.show',compact('readingProgress'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $readingProgress=ReadingProgressModel::findOrFail($id);
        return view('reading_progress.edit',compact('readingProgress'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'surah_id' => 'required|integer',
            'verse_id' => 'required|integer',
        ]);

        $readingProgress=ReadingProgressModel::findOrFail($id);
        $readingProgress->update($validated);

        return redirect()->route('reading_progress.index')->with('success', 'Update Reading Progress successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $readingProgress=ReadingProgressModel::findOrFail($id);
        $readingProgress->delete();

        return redirect()->route('reading_progress.index')->with('success', 'Delete Reading Progress successfully');
    }
};
