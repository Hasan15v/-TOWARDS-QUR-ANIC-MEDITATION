<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tafsir;

class Tafsirs extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tafsirs=Tafsir::orderBy('id','desc')->paginate(15);
        return view('tafsirs.index',compact('tafsirs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tafsirs.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'surah_id' => 'required|integer|unique:surhes',
            'start_verse_id' => 'required|integer|unique:verses',
            'end_verse_id' => 'required|integer|unique:verses',
            'level' => 'required|in:beginner,advanced',
            'text' => 'required|string|min:3',
        ]);

        Tafsir::create($validated);

        return redirect()->route('tafsirs.index')->with('success', 'Add Tafsir successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tafsir=Tafsir::findOrFail($id);
        return view('tafsirs.show',compact('tafsir'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tafsir=Tafsir::findOrFail($id);
        return view('tafsirs.edit',compact('tafsir'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'surah_id' => 'required|integer|unique:surhes,surah_id,'.$id,
            'start_verse_id' => 'required|integer|unique:verses,start_verse_id,'.$id,
            'end_verse_id' => 'required|integer|unique:verses,end_verse_id,'.$id,
            'level' => 'required|in:beginner,advanced',
            'text' => 'required|string|min:3',
        ]);

        $tafsir=Tafsir::findOrFail($id);
        $tafsir->update($validated);

        return redirect()->route('tafsirs.index')->with('success', 'Update Tafsir successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tafsir=Tafsir::findOrFail($id);
        $tafsir->delete();

        return redirect()->route('tafsirs.index')->with('success', 'Delete Tafsir successfully');
    }
    
};
