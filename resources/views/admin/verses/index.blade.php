@extends('layouts.app')

@section('title', 'Verses')

@section('content')
<div class="min-h-screen bg-[#f6f1e8] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.25em] text-[#71817c]">Quran Management</p>
                <h1 class="mt-2 text-3xl font-bold text-[#123a35]">Verses</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-full border border-[#0b6357] px-4 py-2 text-sm font-medium text-[#0b6357] transition hover:bg-[#0b6357] hover:text-white">&larr; Back to Dashboard</a>

        </div>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('verses.index') }}" class="mb-4 flex items-center gap-3" id="surah-search-form">
            <input type="number" name="surah" id="surah" value="{{ request('surah') }}" placeholder="Search by surah number"
                   class="w-40 rounded-full border border-[#123a35]/10 bg-white px-4 py-2 text-sm outline-none focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10" />
            <a href="{{ route('verses.index') }}" id="clear-surah" class="rounded-full border border-[#123a35]/15 px-4 py-2 text-sm text-[#123a35] hover:bg-[#f8f4ee]">Clear</a>
        </form>

        @if(isset($selectedSurah) && $selectedSurah)
            <div class="mb-4 rounded-2xl border border-[#e6e6e6] bg-white px-4 py-3 text-sm text-[#123a35]">
                <strong>Surah:</strong> {{ $selectedSurah->number }} — {{ $selectedSurah->name_en }} ({{ $selectedSurah->name_ar }})
                <form method="GET" action="{{ route('verses.index') }}" class="mt-3 flex items-center gap-3">
                    <input type="hidden" name="surah" value="{{ $selectedSurah->number }}">
                    <input type="text" name="verse" value="{{ request('verse') }}" placeholder="Search verse or range (e.g. 1 or 1-4)"
                           class="w-64 rounded-full border border-[#123a35]/10 bg-white px-4 py-2 text-sm outline-none focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10" />
                    <button type="submit" class="rounded-full bg-[#0b6357] px-4 py-2 text-sm font-semibold text-white hover:bg-[#075148]">Filter</button>
                    <a href="{{ route('verses.index', ['surah' => $selectedSurah->number]) }}" class="rounded-full border border-[#123a35]/15 px-4 py-2 text-sm text-[#123a35] hover:bg-[#f8f4ee]">Clear</a>
                </form>
            </div>
        @endif

        <div class="overflow-hidden rounded-3xl border border-[#123a35]/10 bg-white shadow-[0_18px_45px_rgba(18,58,53,0.08)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#123a35]/10 text-left">
                    <thead class="bg-[#f8f4ee] text-[#123a35]">
                        <tr>
                            <th class="px-6 py-4 text-sm font-semibold">ID</th>
                            <th class="px-6 py-4 text-sm font-semibold">Surah</th>
                            <th class="px-6 py-4 text-sm font-semibold">Verse #</th>
                            <th class="px-6 py-4 text-sm font-semibold">Arabic Text</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#123a35]/10 bg-white">
                        @forelse ($verses as $verse)
                            <tr class="transition hover:bg-[#f6f1e8]/60">
                                <td class="px-6 py-4 text-sm font-medium text-[#123a35]">{{ $verse->id }}</td>
                                <td class="px-6 py-4 text-sm text-[#123a35]">{{ optional($verse->surah)->name_en ?? '—' }} ({{ optional($verse->surah)->number ?? '—' }})</td>
                                <td class="px-6 py-4 text-sm text-[#123a35]">{{ $verse->verse_number }}</td>
                                <td class="px-6 py-4 text-sm text-[#123a35]"><div class="whitespace-pre-wrap">{{ $verse->arabic_text ?? $verse->text }}</div></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-[#71817c]">
                                    No verses found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($verses->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $verses->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

<script>
(function(){
    const form = document.getElementById('surah-search-form');
    const input = document.getElementById('surah');
    let timer = null;
    if (!input) return;
    input.addEventListener('input', function(){
        clearTimeout(timer);
        timer = setTimeout(function(){
            form.submit();
        }, 350);
    });
    input.addEventListener('keydown', function(e){
        if (e.key === 'Enter') { e.preventDefault(); clearTimeout(timer); form.submit(); }
    });
    const clear = document.getElementById('clear-surah');
    if (clear) {
        clear.addEventListener('click', function(e){ e.preventDefault(); input.value=''; form.submit(); });
    }
})();
</script>
