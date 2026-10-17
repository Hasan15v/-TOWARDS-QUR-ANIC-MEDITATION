<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Verse;
use App\Models\Surah;
use App\Models\Tafsir;
use Illuminate\Support\Facades\DB;


class Verses extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $surahQuery = request('surah');
        $verseQuery = request('verse');

        $query = Verse::with('surah');

        $selectedSurah = null;
        if ($surahQuery) {
            // try to find surah by number
            $selectedSurah = Surah::where('number', intval($surahQuery))->first();
            if ($selectedSurah) {
                $query->where('surah_id', $selectedSurah->id);
            } else {
                // if numeric but not found, return empty
                $query->whereRaw('0=1');
            }
        }

        if ($verseQuery && $selectedSurah) {
            $v = trim($verseQuery);
            // allow range like 1-4
            if (strpos($v, '-') !== false) {
                [$start, $end] = array_map('intval', explode('-', $v, 2));
                if ($start > $end) { [$start, $end] = [$end, $start]; }
                $query->whereBetween('verse_number', [$start, $end]);
            } elseif (is_numeric($v)) {
                $query->where('verse_number', intval($v));
            }
        }

        $verses = $query->orderBy('verse_number','asc')->paginate(20)->appends(request()->query());

        return view('admin.verses.index', compact('verses','selectedSurah'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $surahs = Surah::orderBy('number')->get();
        return view('admin.verses.create', compact('surahs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $baseRules = [
            'surah_id' => 'required|integer|exists:surhes,id',
            'verse_number' => 'required|integer|unique:verses,verse_number',
            'arabic_text' => 'required|string|min:1',
        ];

        // validate base verse data first
        $validated = $request->validate($baseRules, [
            'surah_id.exists' => 'Selected surah does not exist.',
            'verse_number.unique' => 'This verse number already exists.',
            'arabic_text.required' => 'Verse text is required.',
        ]);

        DB::beginTransaction();
        try {
            $verse = Verse::create($validated);

            // Handle optional tafsir block
            if ($request->filled('add_tafsir') || $request->filled('tafsir_text')) {
                $tafsirRules = [
                    'tafsir_start' => 'required|integer|min:1',
                    'tafsir_end' => 'required|integer|min:1',
                    'tafsir_text' => 'required|string|min:3',
                    'tafsir_level' => 'nullable|in:beginner,advanced',
                ];

                $tafsirValidated = $request->validate($tafsirRules, [
                    'tafsir_start.required' => 'Start verse number is required for tafsir.',
                    'tafsir_end.required' => 'End verse number is required for tafsir.',
                    'tafsir_text.required' => 'Tafsir text is required.',
                ]);

                // ensure start <= end
                if (intval($tafsirValidated['tafsir_start']) > intval($tafsirValidated['tafsir_end'])) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['tafsir_start' => 'Start verse must be less than or equal to end verse.']);
                }

                // find corresponding verse IDs within the same surah
                $start = Verse::where('surah_id', $validated['surah_id'])
                    ->where('verse_number', $tafsirValidated['tafsir_start'])
                    ->first();
                $end = Verse::where('surah_id', $validated['surah_id'])
                    ->where('verse_number', $tafsirValidated['tafsir_end'])
                    ->first();

                if (!$start || !$end) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['tafsir_start' => 'Start and/or end verse not found in selected surah. Make sure those verses exist.']);
                }

                Tafsir::create([
                    'surah_id' => $validated['surah_id'],
                    'start_verse_id' => $start->id,
                    'end_verse_id' => $end->id,
                    'level' => $tafsirValidated['tafsir_level'] ?? 'beginner',
                    'text' => $tafsirValidated['tafsir_text'],
                ]);
            }

            DB::commit();

            return redirect()->route('verses.index')->with('success', 'Verse has been added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Unable to save verse: '.$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $verse=Verse::findOrFail($id);
        return view('verses.show',compact('verse'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $verse=Verse::findOrFail($id);
        return view('verses.edit',compact('verse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'surah_id' => 'required|integer|exists:surhes,id',
            'verse_number' => 'required|integer|unique:verses,verse_number,'.$id,
            'arabic_text' => 'required|string|min:1',
        ],[
            'surah_id.exists' => 'Selected surah does not exist.',
            'verse_number.unique' => 'This verse number already exists.',
            'arabic_text.required' => 'Verse text is required.',
        ]);

        $verse=Verse::findOrFail($id);
        $verse->update($validated);

        return redirect()->route('verses.index')->with('success', 'Update Verse successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $verse=Verse::findOrFail($id);
        $verse->delete();

        return redirect()->route('verses.index')->with('success', 'Delete Verse successfully');
    }
}
