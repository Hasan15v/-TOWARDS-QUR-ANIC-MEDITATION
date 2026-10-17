@extends('layouts.app')

@section('title', $surah->name_en . ' - Surah Details')

@section('content')
<div class="min-h-screen bg-[#f6f1e8] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.25em] text-[#71817c]">Quran Management</p>
                <h1 class="mt-2 text-3xl font-bold text-[#123a35]">{{ $surah->name_en }}</h1>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-[#0b6357] px-4 py-2 text-sm font-medium text-[#0b6357] transition hover:bg-[#0b6357] hover:text-white">&larr; Back to Dashboard</a>
                <a href="{{ route('surahs.index') }}" class="rounded-full border border-[#123a35]/15 px-4 py-2 text-sm text-[#123a35] hover:bg-[#f8f4ee]">&larr; Back to Surahs</a>
            </div>
        </div>

        <div class="rounded-[28px] border border-[#123a35]/10 bg-white p-6 shadow-[0_18px_45px_rgba(18,58,53,0.08)] sm:p-8">
            <div class="mb-8 flex flex-wrap items-center gap-4 border-b border-[#123a35]/10 pb-6">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-[#0b6357] text-lg font-bold text-white">{{ $surah->number }}</div>
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-[#71817c]">Surah</p>
                    <h2 class="mt-1 text-2xl font-bold text-[#123a35]">{{ $surah->name_en }} <span class="text-[#71817c]">({{ $surah->name_ar }})</span></h2>
                </div>
            </div>

            <div class="mb-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-2xl bg-[#f8f4ee] p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#71817c]">Verse count</p>
                    <p class="mt-2 text-2xl font-bold text-[#123a35]">{{ $surah->verses_count }}</p>
                </div>
                <div class="rounded-2xl bg-[#f8f4ee] p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#71817c]">English name</p>
                    <p class="mt-2 text-lg font-semibold text-[#123a35]">{{ $surah->name_en }}</p>
                </div>
                <div class="rounded-2xl bg-[#f8f4ee] p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-[#71817c]">Arabic name</p>
                    <p class="mt-2 text-lg font-semibold text-[#123a35]">{{ $surah->name_ar }}</p>
                </div>
            </div>

            <div>
                <div class="mb-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-[#123a35]">Verses</h3>
                    <span class="text-sm text-[#71817c]">{{ $surah->verses->count() }} listed</span>
                </div>

                <div class="space-y-3">
                    @forelse ($surah->verses as $verse)
                        <div class="rounded-2xl border border-[#123a35]/10 bg-[#f8f4ee] p-4">
                            <div class="mb-2 flex items-center justify-between gap-4">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#0b6357] text-xs font-bold text-white">{{ $verse->verse_number }}</span>
                                <span class="text-xs uppercase tracking-[0.2em] text-[#71817c]">Verse</span>
                            </div>
                            <p class="text-base leading-8 text-[#123a35]">{{ $verse->arabic_text ?? $verse->text }}</p>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-[#123a35]/15 bg-[#f8f4ee] p-6 text-center text-sm text-[#71817c]">
                            No verses are available for this surah yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
