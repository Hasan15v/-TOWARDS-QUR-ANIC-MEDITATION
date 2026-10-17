@extends('layouts.app')

@section('title', 'Create Removed')

@section('content')
<div class="min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl p-8 shadow">This page has been removed. Create via importer or API only.</div>
</div>
@endsection


@section('title', 'Create Verse')

@section('content')
<div class="min-h-screen bg-[#f6f1e8] px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-medium uppercase tracking-[0.25em] text-[#71817c]">Quran Management</p>
                <h1 class="mt-2 text-3xl font-bold text-[#123a35]">Create Verse</h1>
            </div>

            <a href="{{ route('verses.index') }}"
               class="inline-flex items-center justify-center rounded-full border border-[#123a35]/15 bg-white px-4 py-2 text-sm font-medium text-[#123a35] transition hover:border-[#0b6357] hover:text-[#0b6357]">
                Back to list
            </a>
        </div>

        <div class="rounded-3xl border border-[#123a35]/10 bg-white p-6 shadow-[0_18px_45px_rgba(18,58,53,0.08)] sm:p-8">
            <form action="{{ route('verses.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="surah_id" class="mb-2 block text-sm font-medium text-[#123a35]">Surah</label>
                        <select name="surah_id" id="surah_id" required class="w-full rounded-2xl border border-[#123a35]/15 bg-[#f8f4ee] px-4 py-3 text-[#123a35] outline-none focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10">
                            <option value="">Select surah</option>
                            @foreach($surahs as $s)
                                <option value="{{ $s->id }}" {{ old('surah_id') == $s->id ? 'selected' : '' }}>{{ $s->number }} — {{ $s->name_en }} ({{ $s->name_ar }})</option>
                            @endforeach
                        </select>
                        @error('surah_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="verse_number" class="mb-2 block text-sm font-medium text-[#123a35]">Verse Number</label>
                        <input type="number" name="verse_number" id="verse_number" value="{{ old('verse_number') }}" min="1"
                               class="w-full rounded-2xl border border-[#123a35]/15 bg-[#f8f4ee] px-4 py-3 text-[#123a35] outline-none transition focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10"
                               placeholder="1" required>
                        @error('verse_number')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="arabic_text" class="mb-2 block text-sm font-medium text-[#123a35]">Arabic Text (verse)</label>
                    <textarea name="arabic_text" id="arabic_text" rows="12" required
                              class="w-full rounded-2xl border border-[#123a35]/15 bg-[#f8f4ee] px-4 py-3 text-[#123a35] outline-none transition focus:border-[#0b6357] focus:ring-2 focus:ring-[#0b6357]/10 resize-y">
{{ old('arabic_text') }}
                    </textarea>
                    @error('arabic_text')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('verses.index') }}"
                       class="rounded-full border border-[#123a35]/15 px-5 py-2.5 text-sm font-medium text-[#123a35] transition hover:border-[#123a35]/25 hover:bg-[#f8f4ee]">
                        Cancel
                    </a>
                    <button type="submit"
                            class="rounded-full bg-[#0b6357] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#075148]">
                        Save Verse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
