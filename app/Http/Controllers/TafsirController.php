<?php

namespace App\Http\Controllers;

use App\Models\Tafsir;
use App\Models\Verse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class TafsirController extends Controller
{
    
public function index(Request $request)
{
    $query = Tafsir::with(['startVerse', 'endVerse']);

    // Search by Surah
    if ($request->filled('surah')) {
        $query->where('surah_id', $request->surah);
    }

    // Search by Ayah number
    if ($request->filled('ayah')) {
        $ayah = (int) $request->ayah;

        $query->whereHas('startVerse', function ($q) use ($ayah) {
            $q->where('verse_number', '<=', $ayah);
        });

        $query->whereHas('endVerse', function ($q) use ($ayah) {
            $q->where('verse_number', '>=', $ayah);
        });
    }

    $tafsirs = $query
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $totalTafsirs = Tafsir::count();

$beginnerTafsirs = Tafsir::where('level', 'beginner')->count();

$advancedTafsirs = Tafsir::where('level', 'advanced')->count();

return view('admin.tafsir.index', compact(
    'tafsirs',
    'totalTafsirs',
    'beginnerTafsirs',
    'advancedTafsirs'
));
}
    public function create()
    {
        return view('admin.tafsir.create');
    }

  public function store(Request $request)
{
    $data = $this->validatedData($request);

    // start_verse_id and end_verse_id coming from Blade
    // are actually verse numbers inside the selected surah.
    $startVerse = Verse::where('surah_id', $data['surah_id'])
        ->where('verse_number', $data['start_verse_id'])
        ->first();

    $endVerse = Verse::where('surah_id', $data['surah_id'])
        ->where('verse_number', $data['end_verse_id'])
        ->first();

    if (!$startVerse) {
        throw ValidationException::withMessages([
            'start_verse_id' => 'The selected starting ayah does not exist in this surah.',
        ]);
    }

    if (!$endVerse) {
        throw ValidationException::withMessages([
            'end_verse_id' => 'The selected ending ayah does not exist in this surah.',
        ]);
    }

    // Convert ayah numbers to actual database IDs
    $data['start_verse_id'] = $startVerse->id;
    $data['end_verse_id'] = $endVerse->id;

    $this->ensureVersesAreAvailable($data);

    Tafsir::create($data);

    return redirect()
        ->route('admin.tafsir.index')
        ->with('success', 'Tafsir created successfully.');
}

    public function show(Tafsir $tafsir)
    {
        $tafsir->load(['surah', 'startVerse', 'endVerse']);

        return view('admin.tafsir.show', compact('tafsir'));
    }

    public function edit(Tafsir $tafsir)
    {
        return view('admin.tafsir.edit', compact('tafsir'));
    }

    public function update(Request $request, Tafsir $tafsir)
    {
        $data = $this->validatedData($request);
        // A single-ayah tafsir still needs an end verse ID because the
        // database column is required.

      $data = $this->normalizeVerseRange($data);

    $tafsir->update($data);


        return redirect()->route('admin.tafsir.show', $tafsir)->with('success', 'Tafsir updated.');
    }

    public function destroy(Tafsir $tafsir)
    {
        $tafsir->delete();

        return redirect()->route('admin.tafsir.index')->with('success', 'Tafsir deleted.');
    }

private function validatedData(Request $request): array
{
    $validator = Validator::make($request->all(), [
        'surah_id' => ['required', 'integer', 'exists:surhes,id'],
        'start_verse_id' => ['required', 'integer', 'min:1'],
        'end_verse_id' => ['nullable', 'integer', 'min:1'],
        'level' => ['required', 'in:beginner,advanced'],
        'text' => ['required', 'string', 'min:3'],
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages(
            $validator->errors()->toArray()
        );
    }

    return $validator->validated();
}
    private function normalizeVerseRange(array $data): array
    {
        $data['end_verse_id'] = $data['end_verse_id'] ?? $data['start_verse_id'];

        if (!empty($data['end_verse_id']) && $data['end_verse_id'] < $data['start_verse_id']) {
            [$data['start_verse_id'], $data['end_verse_id']] = [
                $data['end_verse_id'],
                $data['start_verse_id'],
            ];
        }

        return $data;
    }

    private function ensureVersesAreAvailable(array $data, ?Tafsir $ignore = null): void
    {
        $start = Verse::whereKey($data['start_verse_id'])->first();
        $end = Verse::whereKey($data['end_verse_id'])->first();

        if (!$start || !$end || $start->surah_id !== (int) $data['surah_id'] || $end->surah_id !== (int) $data['surah_id']) {
            throw ValidationException::withMessages([
                'start_verse_id' => 'The selected verses must belong to the selected surah.',
            ]);
        }

        $from = min($start->verse_number, $end->verse_number);
        $to = max($start->verse_number, $end->verse_number);

        $query = DB::table('tafsirs as t')
            ->join('verses as start_v', 'start_v.id', '=', 't.start_verse_id')
            ->join('verses as end_v', 'end_v.id', '=', 't.end_verse_id')
            ->where('t.surah_id', $data['surah_id'])
            ->where('start_v.verse_number', '<=', $to)
            ->where('end_v.verse_number', '>=', $from)
            ->where('level', $data['level'])
            ->select(
                't.id',
                'start_v.verse_number as existing_from',
                'end_v.verse_number as existing_to'
            );

        if ($ignore) {
            $query->where('t.id', '!=', $ignore->id);
        }

        $conflict = $query->first();

        if ($conflict) {
            $existingFrom = min($conflict->existing_from, $conflict->existing_to);
            $existingTo = max($conflict->existing_from, $conflict->existing_to);

            throw ValidationException::withMessages([
                'start_verse_id' => "These verses are already explained. Existing tafsir ID: {$conflict->id} (ayahs {$existingFrom}–{$existingTo}). Any overlapping ayah is not allowed.",
                'end_verse_id' => 'Choose a verse range that does not overlap an existing tafsir.',
            ]);
        }
    }
}
