<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tafsir;
use App\Models\TafsirVideo;
use Illuminate\Http\Request;

class TafsirVideoController extends Controller
{
    public function index(Tafsir $tafsir)
    {
        $videos = $tafsir->videos()->get();

        return view('admin.tafsirs-videos.index', compact(
            'tafsir',
            'videos'
        ));
    }

    public function create(Tafsir $tafsir)
    {
        return view('admin.tafsirs-videos.create', compact('tafsir'));
    }

    public function store(Request $request, Tafsir $tafsir)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['required', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $tafsir->videos()->create($validated);

        return redirect()
            ->route('admin.tafsirs.videos.index', $tafsir)
            ->with('success', 'YouTube video added successfully.');
    }

    public function edit(TafsirVideo $video)
    {
        $tafsir = $video->tafsir;

        return view('admin.tafsir-videos.edit', compact(
            'tafsir',
            'video'
        ));
    }

    public function update(Request $request, TafsirVideo $video)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['required', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $video->update($validated);

        return redirect()
            ->route('admin.tafsirs.videos.index', $video->tafsir)
            ->with('success', 'YouTube video updated successfully.');
    }

    public function destroy(TafsirVideo $video)
    {
        $tafsir = $video->tafsir;

        $video->delete();

        return redirect()
            ->route('admin.tafsirs.videos.index', $tafsir)
            ->with('success', 'YouTube video deleted successfully.');
    }
}