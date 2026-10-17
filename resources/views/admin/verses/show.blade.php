@extends('layouts.app')

@section('title', 'Verse ' . $verse->verse_number)

@section('content')
<div class="min-h-screen bg-[#f6f1e8] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <div class="mb-8">
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-sm text-[#0b6357] underline">&larr; Back to Dashboard</a>
                <a href="{{ route('verses.index') }}" class="text-sm text-[#0b6357] underline">&larr; Back to Verses</a>
            </div>
            <h1 class="mt-4 text-3xl font-bold text-[#123a35]">
                Verse {{ $verse->verse_number }}
            </h1>
            <p class="mt-1 text-sm text-[#71817c]">
                {{ optional($verse->surah)->name_en ?? 'Quran' }}
            </p>
        </div>

        <article class="rounded-2xl border border-[#e9e9e9] bg-white p-6 shadow-sm">
            <p class="text-right font-serif text-3xl leading-loose text-[#123a35]" dir="rtl">
                {!! nl2br(e($verse->arabic_text ?? $verse->text)) !!}
            </p>
        </article>
    </div>
</div>
@endsection