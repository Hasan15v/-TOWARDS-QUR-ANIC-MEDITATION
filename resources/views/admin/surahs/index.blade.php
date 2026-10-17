@extends('layouts.app')

@section('title', 'Surahs')

@section('content')
<div class="min-h-screen bg-[#f6f1e8] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-6xl">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.25em] text-[#71817c]">Quran Management</p>
                <h1 class="mt-2 text-3xl font-bold text-[#123a35]">Surahs</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="rounded-full border border-[#0b6357] px-4 py-2 text-sm font-medium text-[#0b6357] transition hover:bg-[#0b6357] hover:text-white">&larr; Back to Dashboard</a>
        </div>

        <form method="GET" action="{{ route('surahs.index') }}" class="mb-6 flex items-center gap-3" id="surah-search-form">
            <input type="search" name="q" id="q" value="{{ request('q') }}" placeholder="Search by number or name (Arabic/English)"
                   aria-label="Search surahs by number or name"
                   class="w-full max-w-md rounded-full border border-[#123a35]/10 bg-white px-4 py-2 text-sm outline-none focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10" />
            <a href="{{ route('surahs.index') }}" id="clear-search" class="rounded-full border border-[#123a35]/15 px-4 py-2 text-sm text-[#123a35] hover:bg-[#f8f4ee]">Clear</a>
        </form>

        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <script>
            (function() {
                const form = document.getElementById('surah-search-form');
                const input = document.getElementById('q');
                let timer = null;

                if (!input) return;

                input.addEventListener('input', function() {
                    clearTimeout(timer);
                    timer = setTimeout(function() { form.submit(); }, 350);
                });

                const clear = document.getElementById('clear-search');
                if (clear) { clear.addEventListener('click', function(e){ e.preventDefault(); input.value=''; form.submit(); }); }

                input.addEventListener('keydown', function(e) { if (e.key === 'Enter') { e.preventDefault(); clearTimeout(timer); form.submit(); } });
            })();
        </script>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($surahs as $surah)
                <article class="rounded-2xl border border-[#e9e9e9] bg-white p-5 shadow-sm transition hover:shadow-lg">
                    <div class="flex items-start gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#0b6357] text-white font-semibold">{{ $surah->number }}</div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-[#123a35]">{{ $surah->name_en }}</h3>
                                <span class="text-sm text-[#71817c]">{{ $surah->verses_count }} verses</span>
                            </div>
                            <p class="mt-2 text-sm text-[#123a35]">{{ $surah->name_ar }}</p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('surahs.show', $surah->id) }}" class="rounded-full border border-[#0b6357] px-3 py-1.5 text-xs font-medium text-[#0b6357] transition hover:bg-[#0b6357] hover:text-white">View</a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center text-sm text-[#71817c]">No surahs found.</div>
            @endforelse
        </div>

        @if ($surahs->hasPages())
            <div class="mt-6 flex justify-center">
                {{ $surahs->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
