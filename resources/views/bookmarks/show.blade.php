@extends('layouts.app')

@section('title', 'Saved Ayah')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

@php

    /* ---------- saved range ---------- */
    $startVerse = $bookmark->startVerse;
    $endVerse   = $bookmark->endVerse ?? $startVerse;
    $surah      = $startVerse?->surah;

    $from = $to = null;
    $savedVerses = collect();
    $tafsirs     = collect();

    if ($startVerse && $surah) {

        $a = $startVerse->verse_number;
        $b = $endVerse?->verse_number ?? $a;

        $from = min($a, $b);
        $to   = max($a, $b);

        /* verses inside the saved range */
        $savedVerses = \App\Models\Verse::where('surah_id', $surah->id)
            ->whereBetween('verse_number', [$from, $to])
            ->orderBy('verse_number')
            ->get();

        /* tafsirs that overlap the saved range */
        $tafsirs = \App\Models\Tafsir::with(['startVerse', 'endVerse'])
            ->where('surah_id', $surah->id)
            ->whereHas('startVerse', fn ($q) => $q->where('verse_number', '<=', $to))
            ->whereHas('endVerse',   fn ($q) => $q->where('verse_number', '>=', $from))
            ->orderByRaw("
                CASE
                    WHEN level = 'beginner' THEN 1
                    WHEN level = 'advanced' THEN 2
                    ELSE 3
                END
            ")
            ->get();
    }

    $rangeLabel = $from === null ? '—' : ($from == $to ? $from : $from . '–' . $to);

    /* plain text used by the Copy button */
    $copyText = $savedVerses
        ->map(fn ($v) => $v->arabic_text . ' ﴿' . $v->verse_number . '﴾')
        ->implode(' ');

    if ($surah && $copyText !== '') {
        $copyText .= "\n— " . ($surah->name_en ?? '') . ' ' . $rangeLabel;
    }

@endphp


<style>

/* =====================================================
   THEME VARIABLES
===================================================== */
.bkv-page {
    --bkv-surface: #ffffff;
    --bkv-paper: #fbf8f0;
    --bkv-text: #34443d;
    --bkv-muted: #71817c;
    --bkv-heading: #043b35;
    --bkv-border: rgba(18,58,53,.10);
    --bkv-shadow: 0 14px 40px rgba(26,49,43,.08);
    --bkv-gold: #c99a45;
    --bkv-gold-light: #e3c37d;
    --bkv-green-700: #0b6357;
    --bkv-green-900: #043b35;
    --bkv-ar-size: 34px;

    padding: 32px 32px 64px;
    max-width: 1020px;
    margin: 0 auto;
}

html.dark-mode .bkv-page {
    --bkv-surface: #1a251f;
    --bkv-paper: #151f1a;
    --bkv-text: #cbd6cf;
    --bkv-muted: #8fa097;
    --bkv-heading: #e6eee9;
    --bkv-border: rgba(255,255,255,.075);
    --bkv-shadow: 0 14px 40px rgba(0,0,0,.3);
}


/* =====================================================
   HERO
===================================================== */
.bkv-hero {
    position: relative;
    overflow: hidden;
    padding: 36px 42px;
    border-radius: 26px;
    color: #fff;
    background:
        radial-gradient(circle at 12% 0%, rgba(227,195,125,.14), transparent 35%),
        radial-gradient(circle at 100% 100%, rgba(11,99,87,.55), transparent 45%),
        linear-gradient(135deg, #073c35 0%, #04352f 55%, #022c28 100%);
    box-shadow: 0 20px 50px rgba(2,44,40,.26);
    animation: bkvRise .6s ease both;
}

.bkv-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.15' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
    pointer-events: none;
}

.bkv-back {
    position: relative;
    z-index: 2;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 22px;
    padding: 8px 16px;
    border-radius: 999px;
    border: 1px solid rgba(227,195,125,.35);
    background: rgba(255,255,255,.06);
    color: #f7f0e4;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    backdrop-filter: blur(4px);
    transition: background .25s ease, border-color .25s ease, transform .25s ease;
}

.bkv-page a.bkv-back { color: #f7f0e4; }

.bkv-back:hover {
    background: rgba(227,195,125,.14);
    border-color: var(--bkv-gold-light);
    transform: translateX(-2px);
}

.bkv-hero-row {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
}

.bkv-page .bkv-eyebrow {
    display: block;
    margin-bottom: 10px;
    font-size: 11px;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--bkv-gold-light);
}

.bkv-page .bkv-title {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(28px, 4.2vw, 44px);
    font-weight: 500;
    line-height: 1.15;
    color: #fff;
}

.bkv-page .bkv-title-ar {
    margin-top: 6px;
    font-family: 'Amiri Quran', 'Amiri', serif;
    font-size: clamp(22px, 3vw, 30px);
    color: var(--bkv-gold-light);
}

.bkv-meta {
    margin-top: 14px;
    font-size: 13px;
    color: rgba(255,255,255,.6);
}

.bkv-meta b { color: #f7f0e4; font-weight: 600; }

/* range badge */
.bkv-range {
    flex-shrink: 0;
    min-width: 108px;
    padding: 14px 20px;
    text-align: center;
    border-radius: 20px;
    border: 1px solid rgba(227,195,125,.45);
    background: rgba(3,20,16,.35);
    backdrop-filter: blur(6px);
}

.bkv-range small {
    display: block;
    font-size: 10.5px;
    letter-spacing: .2em;
    text-transform: uppercase;
    color: rgba(255,255,255,.55);
}

.bkv-range strong {
    display: block;
    margin-top: 6px;
    font-family: Georgia, serif;
    font-size: 28px;
    font-weight: 500;
    line-height: 1;
    color: var(--bkv-gold-light);
}


/* =====================================================
   CARDS
===================================================== */
.bkv-card {
    position: relative;
    overflow: hidden;
    margin-top: 24px;
    border-radius: 24px;
    background: var(--bkv-surface);
    border: 1px solid var(--bkv-border);
    box-shadow: var(--bkv-shadow);
    animation: bkvRise .6s ease .12s both;
}

.bkv-card + .bkv-card { animation-delay: .24s; }

.bkv-card::before {
    content: "";
    position: absolute;
    left: 0; right: 0; top: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--bkv-green-700), var(--bkv-gold), var(--bkv-green-700));
}

.bkv-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    flex-wrap: wrap;
    padding: 24px 32px 0;
}

.bkv-page .bkv-label {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
    font-family: Inter, Arial, sans-serif;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--bkv-gold);
}

.bkv-label span {
    width: 26px;
    height: 26px;
    display: grid;
    place-items: center;
    border-radius: 8px;
    background: rgba(201,154,69,.14);
    font-size: 13px;
    letter-spacing: 0;
}


/* ===== TOOLS ===== */
.bkv-tools { display: flex; gap: 8px; }

.bkv-tool {
    min-width: 38px;
    height: 36px;
    padding: 0 12px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border-radius: 10px;
    border: 1px solid var(--bkv-border);
    background: transparent;
    color: var(--bkv-heading);
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s ease, border-color .2s ease, color .2s ease;
}

.bkv-tool:hover {
    background: rgba(201,154,69,.14);
    border-color: rgba(201,154,69,.5);
}

.bkv-tool.is-done {
    background: var(--bkv-gold);
    border-color: var(--bkv-gold);
    color: #fff;
}


/* =====================================================
   QURAN PASSAGE
===================================================== */
.bkv-quran-wrap { padding: 20px 32px 32px; }

.bkv-quran-box {
    position: relative;
    padding: 34px 32px;
    border-radius: 18px;
    background: var(--bkv-paper);
    border: 1px solid var(--bkv-border);
}

/* corner ornaments */
.bkv-quran-box::before,
.bkv-quran-box::after {
    content: "";
    position: absolute;
    width: 26px;
    height: 26px;
    border: 1.5px solid var(--bkv-gold);
    opacity: .7;
}

.bkv-quran-box::before { top: 10px; left: 10px; border-right: 0; border-bottom: 0; border-top-left-radius: 8px; }
.bkv-quran-box::after  { bottom: 10px; right: 10px; border-left: 0; border-top: 0; border-bottom-right-radius: 8px; }

.bkv-page .bkv-quran-text {
    direction: rtl;
    text-align: right;
    font-family: 'Amiri Quran', 'Amiri', 'Scheherazade New', serif;
    font-size: var(--bkv-ar-size);
    line-height: 2.3;
    word-spacing: 4px;
    color: var(--bkv-heading);
    transition: font-size .2s ease;
}

.bkv-ayah-no {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 1.25em;
    height: 1.25em;
    margin: 0 .3em;
    border: 1.5px solid var(--bkv-gold);
    border-radius: 50%;
    color: var(--bkv-gold);
    font-family: 'Amiri', serif;
    font-size: .5em;
    line-height: 1;
    vertical-align: middle;
}

.bkv-empty {
    padding: 26px;
    text-align: center;
    color: var(--bkv-muted);
}


/* =====================================================
   TAFSIR
===================================================== */
.bkv-tabs {
    display: inline-flex;
    gap: 6px;
    padding: 5px;
    border-radius: 999px;
    background: rgba(18,58,53,.06);
}

html.dark-mode .bkv-tabs { background: rgba(255,255,255,.05); }

.bkv-tab {
    padding: 8px 18px;
    border: 0;
    border-radius: 999px;
    background: transparent;
    color: var(--bkv-muted);
    font-family: inherit;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: background .2s ease, color .2s ease;
}

.bkv-tab:hover { color: var(--bkv-heading); }

.bkv-tab.is-active {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
}

.bkv-level {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(11,99,87,.10);
    border: 1px solid rgba(11,99,87,.14);
    color: var(--bkv-green-700);
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .04em;
    text-transform: capitalize;
}

html.dark-mode .bkv-level {
    background: rgba(227,195,125,.1);
    border-color: rgba(227,195,125,.2);
    color: var(--bkv-gold-light);
}

.bkv-panel { padding: 22px 32px 34px; }
.bkv-panel[hidden] { display: none; }

.bkv-panel-box {
    border-left: 3px solid var(--bkv-gold);
    padding: 2px 0 2px 24px;
}

.bkv-page .bkv-tafsir-text {
    margin-top: 16px;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 18.5px;
    line-height: 1.9;
    color: var(--bkv-text);
}

.bkv-page .bkv-tafsir-text p {
    margin: 0 0 16px;
    color: var(--bkv-text);
}

.bkv-page .bkv-tafsir-text p:last-child { margin-bottom: 0; }

.bkv-page .bkv-tafsir-text h1,
.bkv-page .bkv-tafsir-text h2,
.bkv-page .bkv-tafsir-text h3,
.bkv-page .bkv-tafsir-text h4 {
    margin: 22px 0 10px;
    font-family: Georgia, serif;
    font-weight: 600;
    color: var(--bkv-heading);
}

.bkv-tafsir-text strong { color: inherit; }
.bkv-tafsir-text ul, .bkv-tafsir-text ol { margin: 0 0 16px; padding-left: 22px; }
.bkv-tafsir-text li { margin-bottom: 6px; }

/* original tafsir colors */
.bkv-tafsir-text .red-text    { color: #C00000 !important; }
.bkv-tafsir-text .orange-text { color: #FF6600 !important; }
.bkv-tafsir-text .green-text  { color: #008000 !important; }
.bkv-tafsir-text .blue-text   { color: #0066FF !important; }
.bkv-tafsir-text .purple-text { color: #993366 !important; }
.bkv-tafsir-text .black-text  { color: #1a1a1a !important; }
.bkv-tafsir-text .highlight-yellow { background-color: #FFFF00 !important; color: #000000 !important; }

html.dark-mode .bkv-tafsir-text .red-text    { color: #ff4d4d !important; }
html.dark-mode .bkv-tafsir-text .orange-text { color: #ff8a3d !important; }
html.dark-mode .bkv-tafsir-text .green-text  { color: #4caf70 !important; }
html.dark-mode .bkv-tafsir-text .blue-text   { color: #4da3ff !important; }
html.dark-mode .bkv-tafsir-text .purple-text { color: #d47aa8 !important; }
html.dark-mode .bkv-tafsir-text .black-text  { color: #e6eee9 !important; }


/* =====================================================
   ACTIONS
===================================================== */
.bkv-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 24px;
    padding: 18px 24px;
    border-radius: 20px;
    background: var(--bkv-surface);
    border: 1px solid var(--bkv-border);
    box-shadow: var(--bkv-shadow);
    animation: bkvRise .6s ease .34s both;
}

.bkv-saved { font-size: 13px; color: var(--bkv-muted); }
.bkv-saved b { color: var(--bkv-heading); font-weight: 600; }

.bkv-btn-row { display: flex; gap: 10px; flex-wrap: wrap; }

.bkv-btn {
    display: inline-flex;
    align-items: center;
    gap: 9px;
    padding: 12px 22px;
    border-radius: 999px;
    border: 1px solid var(--bkv-border);
    background: transparent;
    color: var(--bkv-heading);
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease;
}

.bkv-btn:hover { background: rgba(201,154,69,.12); }

.bkv-btn.is-primary {
    border: none;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    box-shadow: 0 8px 20px rgba(184,138,68,.28);
}

.bkv-page a.bkv-btn.is-primary { color: #051a14; }

.bkv-btn.is-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(184,138,68,.4);
}


/* =====================================================
   ANIMATION
===================================================== */
@keyframes bkvRise {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (prefers-reduced-motion: reduce) {
    .bkv-page *, .bkv-page *::before, .bkv-page *::after {
        animation-duration: .01ms !important;
        animation-delay: 0s !important;
        transition: none !important;
    }
}


/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 900px) {
    .bkv-page { padding: 72px 14px 48px; }          /* room for the sidebar toggle */
    .bkv-hero { padding: 26px 20px; border-radius: 20px; }
    .bkv-card { border-radius: 20px; }
    .bkv-card-head { padding: 20px 20px 0; }
    .bkv-quran-wrap { padding: 16px 14px 22px; }
    .bkv-quran-box { padding: 28px 18px; }
    .bkv-panel { padding: 18px 20px 26px; }
    .bkv-page { --bkv-ar-size: 28px; }
}

@media (max-width: 560px) {
    .bkv-hero-row { flex-direction: column; align-items: flex-start; }
    .bkv-range { align-self: flex-start; }
    .bkv-panel-box { padding-left: 16px; }
    .bkv-page .bkv-tafsir-text { font-size: 17px; line-height: 1.8; }
    .bkv-actions { flex-direction: column; align-items: stretch; }
    .bkv-btn-row { flex-direction: column; }
    .bkv-btn { justify-content: center; }
    .bkv-tabs { width: 100%; }
    .bkv-tab { flex: 1; }
}

</style>


<div class="bkv-page" id="bkvPage">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <header class="bkv-hero">

        <a href="{{ route('bookmarks.index') }}" class="bkv-back">
            <span aria-hidden="true">←</span> Back to Bookmarks
        </a>

        <div class="bkv-hero-row">

            <div>
                <span class="bkv-eyebrow">Saved Ayah</span>

                <h1 class="bkv-title">{{ $surah?->name_en ?? 'Unknown Surah' }}</h1>

                @if ($surah?->name_ar)
                    <div class="bkv-title-ar" dir="rtl" lang="ar">{{ $surah->name_ar }}</div>
                @endif

                <div class="bkv-meta">
                    Saved on <b>{{ $bookmark->created_at?->format('M d, Y') ?? 'Unknown date' }}</b>
                </div>
            </div>

            <div class="bkv-range">
                <small>Āyahs</small>
                <strong>{{ $rangeLabel }}</strong>
            </div>

        </div>

    </header>


    {{-- =====================================================
         QURAN PASSAGE
    ====================================================== --}}
    <section class="bkv-card">

        <div class="bkv-card-head">

            <h2 class="bkv-label"><span>۞</span> Qur’anic Passage</h2>

            @if ($savedVerses->count())
                <div class="bkv-tools">
                    <button type="button" class="bkv-tool" id="bkvSmaller" aria-label="Smaller text">A−</button>
                    <button type="button" class="bkv-tool" id="bkvLarger" aria-label="Larger text">A+</button>
                    <button type="button" class="bkv-tool" id="bkvCopy" data-copy="{{ $copyText }}">Copy</button>
                </div>
            @endif

        </div>

        <div class="bkv-quran-wrap">
            <div class="bkv-quran-box">

                @if ($savedVerses->count())

                    <div class="bkv-quran-text" id="bkvQuranText">
                        @foreach ($savedVerses as $verse)
                            <span>{{ $verse->arabic_text }}</span><span class="bkv-ayah-no">{{ $verse->verse_number }}</span>
                        @endforeach
                    </div>

                @else

                    <div class="bkv-empty">Ayah text is not available.</div>

                @endif

            </div>
        </div>

    </section>


    {{-- =====================================================
         TAFSIR
    ====================================================== --}}
    @if ($tafsirs->count())

        <section class="bkv-card">

            <div class="bkv-card-head">

                <h2 class="bkv-label"><span>☷</span> Tafsir</h2>

                @if ($tafsirs->count() > 1)
                    <div class="bkv-tabs" role="tablist">
                        @foreach ($tafsirs as $tafsir)
                            <button
                                type="button"
                                class="bkv-tab {{ $loop->first ? 'is-active' : '' }}"
                                role="tab"
                                data-target="bkvPanel{{ $loop->index }}"
                            >
                                @if ($tafsir->level === 'beginner')
                                    🌱 Beginner
                                @elseif ($tafsir->level === 'advanced')
                                    📖 Advanced
                                @else
                                    {{ ucfirst($tafsir->level) }}
                                @endif
                            </button>
                        @endforeach
                    </div>
                @endif

            </div>


            @foreach ($tafsirs as $tafsir)

                @php
                    $rawText  = (string) ($tafsir->text ?? '');
                    $bodyText = $rawText;

                    /* remove the generated heading if it exists */
                    if (preg_match('/##\s*(Beginner|Advanced)\s*Explanation\s*(.*)/is', $rawText, $match)) {
                        $bodyText = trim($match[2]);
                    }

                    if ($bodyText === '') {
                        $bodyText = trim($rawText);
                    }
                @endphp

                <div class="bkv-panel" id="bkvPanel{{ $loop->index }}" @if (! $loop->first) hidden @endif>

                    <div class="bkv-panel-box">

                        @if ($tafsirs->count() === 1)
                            <span class="bkv-level">
                                @if ($tafsir->level === 'beginner')
                                    🌱 Beginner
                                @elseif ($tafsir->level === 'advanced')
                                    📖 Advanced
                                @else
                                    {{ ucfirst($tafsir->level) }}
                                @endif
                            </span>
                        @endif

                        <div class="bkv-tafsir-text">
                            {!! $bodyText !!}
                        </div>

                    </div>

                </div>

            @endforeach

        </section>

    @endif


    {{-- =====================================================
         ACTIONS
    ====================================================== --}}
    <div class="bkv-actions">

        <div class="bkv-saved">
            Saved on <b>{{ $bookmark->created_at?->format('M d, Y') ?? 'Unknown date' }}</b>
        </div>

        <div class="bkv-btn-row">

            <a href="{{ route('bookmarks.index') }}" class="bkv-btn">
                <span aria-hidden="true">←</span> All Bookmarks
            </a>

            @if ($surah)
                <a
                    href="{{ route('tafsir.reader.show', [
                        'surah' => $surah->slug,
                        'level' => $tafsirs->first()?->level ?? 'beginner'
                    ]) }}"
                    class="bkv-btn is-primary"
                >
                    Open in Tafsir Reader <span aria-hidden="true">→</span>
                </a>
            @endif

        </div>

    </div>

</div>


<script>
(function () {

    const page = document.getElementById('bkvPage');

    /* ---------- Arabic text size ---------- */
    const MIN = 22, MAX = 52, STEP = 3;
    const smaller = document.getElementById('bkvSmaller');
    const larger  = document.getElementById('bkvLarger');

    let size = parseInt(getComputedStyle(page).getPropertyValue('--bkv-ar-size'), 10) || 34;

    try {
        const saved = parseInt(localStorage.getItem('bkv-ar-size'), 10);
        if (saved >= MIN && saved <= MAX) size = saved;
    } catch (e) {}

    function setSize(v) {
        size = Math.max(MIN, Math.min(MAX, v));
        page.style.setProperty('--bkv-ar-size', size + 'px');
        try { localStorage.setItem('bkv-ar-size', size); } catch (e) {}
    }

    if (smaller && larger) {
        setSize(size);
        smaller.addEventListener('click', () => setSize(size - STEP));
        larger.addEventListener('click',  () => setSize(size + STEP));
    }

    /* ---------- Copy ---------- */
    const copyBtn = document.getElementById('bkvCopy');

    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {

            const text = copyBtn.dataset.copy || '';
            let ok = false;

            try {
                await navigator.clipboard.writeText(text);
                ok = true;
            } catch (e) {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try { ok = document.execCommand('copy'); } catch (err) {}
                document.body.removeChild(ta);
            }

            if (ok) {
                const old = copyBtn.textContent;
                copyBtn.textContent = 'Copied ✓';
                copyBtn.classList.add('is-done');
                setTimeout(() => {
                    copyBtn.textContent = old;
                    copyBtn.classList.remove('is-done');
                }, 1600);
            }
        });
    }

    /* ---------- Tafsir tabs ---------- */
    const tabs = document.querySelectorAll('.bkv-tab');

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {

            tabs.forEach((t) => t.classList.toggle('is-active', t === tab));

            document.querySelectorAll('.bkv-panel').forEach((panel) => {
                panel.hidden = panel.id !== tab.dataset.target;
            });
        });
    });

})();
</script>

@endsection