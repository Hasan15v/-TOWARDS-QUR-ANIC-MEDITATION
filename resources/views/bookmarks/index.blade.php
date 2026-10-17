@extends('layouts.app')

@section('title', 'Bookmarks')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

@php
    $totalBookmarks = $bookmarks->total();
@endphp

<style>

/* =====================================================
   THEME VARIABLES
===================================================== */
.bm-page {
    --bm-surface: #ffffff;
    --bm-text: #36534d;
    --bm-muted: #71817c;
    --bm-heading: #043b35;
    --bm-border: rgba(18,58,53,.10);
    --bm-shadow: 0 12px 34px rgba(26,49,43,.07);
    --bm-shadow-hover: 0 22px 44px rgba(26,49,43,.14);
    --bm-gold: #c99a45;
    --bm-gold-light: #e3c37d;
    --bm-green-700: #0b6357;
    --bm-green-900: #043b35;

    padding: 32px 32px 64px;
    max-width: 1240px;
    margin: 0 auto;
}

html.dark-mode .bm-page {
    --bm-surface: #1a251f;
    --bm-text: #bccac3;
    --bm-muted: #8fa097;
    --bm-heading: #e6eee9;
    --bm-border: rgba(255,255,255,.075);
    --bm-shadow: 0 12px 34px rgba(0,0,0,.28);
    --bm-shadow-hover: 0 22px 44px rgba(0,0,0,.4);
}


/* =====================================================
   HERO
===================================================== */
.bm-hero {
    position: relative;
    overflow: hidden;
    padding: 40px 44px 44px;
    border-radius: 26px;
    color: #fff;
    background:
        radial-gradient(circle at 12% 0%, rgba(227,195,125,.14), transparent 35%),
        radial-gradient(circle at 100% 100%, rgba(11,99,87,.55), transparent 45%),
        linear-gradient(135deg, #073c35 0%, #04352f 55%, #022c28 100%);
    box-shadow: 0 20px 50px rgba(2,44,40,.26);
    animation: bmRise .6s ease both;
}

.bm-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.15' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
    pointer-events: none;
}

.bm-hero-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.bm-page .bm-eyebrow {
    display: block;
    margin-bottom: 12px;
    font-size: 11px;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--bm-gold-light);
}

.bm-page .bm-title {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(30px, 4.4vw, 46px);
    font-weight: 500;
    line-height: 1.12;
    color: #fff;
}

.bm-page .bm-sub {
    margin: 12px 0 0;
    font-size: 14px;
    color: rgba(255,255,255,.62);
}

.bm-count {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 10px 18px 10px 12px;
    border-radius: 999px;
    border: 1px solid rgba(227,195,125,.35);
    background: rgba(255,255,255,.06);
    backdrop-filter: blur(4px);
    color: #f7f0e4;
    font-size: 13.5px;
    font-weight: 600;
    white-space: nowrap;
}

.bm-count-icon {
    width: 30px;
    height: 30px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
    font-size: 14px;
}


/* ===== SEARCH ===== */
.bm-search-row {
    position: relative;
    z-index: 2;
    margin-top: 30px;
}

.bm-search {
    position: relative;
    max-width: 560px;
}

.bm-search svg.bm-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--bm-gold-light);
    pointer-events: none;
}

.bm-search input {
    width: 100%;
    height: 52px;
    padding: 0 84px 0 48px;
    border-radius: 16px;
    border: 1px solid rgba(227,195,125,.3);
    background: rgba(3,20,16,.5);
    color: #f7f0e4;
    font-family: inherit;
    font-size: 15px;
    outline: none;
    backdrop-filter: blur(6px);
    transition: border-color .25s ease, box-shadow .25s ease, background .25s ease;
}

.bm-search input::placeholder { color: rgba(247,240,228,.45); }

.bm-search input:focus {
    border-color: var(--bm-gold-light);
    background: rgba(3,20,16,.75);
    box-shadow: 0 0 0 4px rgba(227,195,125,.14);
}

.bm-search input::-webkit-search-cancel-button { display: none; }

.bm-search .bm-clear {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    width: 28px;
    height: 28px;
    display: none;
    place-items: center;
    border: 0;
    border-radius: 50%;
    background: rgba(255,255,255,.1);
    color: #f7f0e4;
    font-size: 15px;
    line-height: 1;
    cursor: pointer;
    transition: background .2s ease;
}

.bm-search .bm-clear:hover { background: rgba(255,255,255,.2); }
.bm-search.has-value .bm-clear { display: grid; }

.bm-search kbd {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    padding: 2px 8px;
    border-radius: 6px;
    border: 1px solid rgba(255,255,255,.2);
    font-family: inherit;
    font-size: 11px;
    color: rgba(255,255,255,.5);
    pointer-events: none;
}

.bm-search.has-value kbd { display: none; }


/* =====================================================
   SUCCESS MESSAGE
===================================================== */
.bm-success {
    margin-top: 20px;
    padding: 13px 18px;
    border-radius: 14px;
    background: rgba(11,99,87,.10);
    border: 1px solid rgba(11,99,87,.2);
    color: var(--bm-green-700);
    font-size: 13.5px;
}

html.dark-mode .bm-success { color: #9fd0b3; background: rgba(100,170,135,.12); }


/* =====================================================
   RESULTS BAR
===================================================== */
.bm-results-bar {
    margin: 26px 4px 16px;
    font-size: 13px;
    color: var(--bm-muted);
}

.bm-results-bar b { color: var(--bm-heading); font-weight: 600; }


/* =====================================================
   GRID + CARDS
===================================================== */
.bm-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(420px, 1fr));
    gap: 18px;
}

.bm-page .bm-card {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 22px 24px 18px;
    border-radius: 22px;
    background: var(--bm-surface);
    border: 1px solid var(--bm-border);
    box-shadow: var(--bm-shadow);
    color: var(--bm-text);
    text-decoration: none;
    opacity: 0;
    animation: bmRise .5s ease forwards;
    animation-delay: calc(min(var(--i, 0), 12) * 40ms + .15s);
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}

.bm-card[hidden] { display: none; }

/* gold edge */
.bm-card::before {
    content: "";
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, var(--bm-gold), var(--bm-green-700));
    opacity: .8;
}

/* watermark */
.bm-card::after {
    content: "۞";
    position: absolute;
    right: 14px;
    bottom: -44px;
    font-family: 'Amiri Quran', serif;
    font-size: 130px;
    color: rgba(201,154,69,.07);
    pointer-events: none;
    transition: transform .5s ease;
}

.bm-page .bm-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--bm-shadow-hover);
    border-color: rgba(201,154,69,.45);
}

.bm-card:hover::after { transform: scale(1.12) rotate(8deg); }

.bm-card-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 14px;
}

/* 8-point star badge */
.bm-badge {
    position: relative;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
}

.bm-badge::before,
.bm-badge::after {
    content: "";
    position: absolute;
    inset: 9px;
    border-radius: 7px;
    background: linear-gradient(135deg, var(--bm-green-700), var(--bm-green-900));
}

.bm-badge::after { transform: rotate(45deg); }

.bm-badge span {
    position: relative;
    z-index: 2;
    font-family: Georgia, serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--bm-gold-light);
}

.bm-surah { flex: 1; min-width: 0; }

.bm-page .bm-surah-name {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 19px;
    font-weight: 500;
    line-height: 1.3;
    color: var(--bm-heading);
}

.bm-page .bm-surah-ar {
    margin-top: 2px;
    font-family: 'Amiri Quran', 'Amiri', serif;
    font-size: 17px;
    color: var(--bm-muted);
}

.bm-verse-chip {
    flex-shrink: 0;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    white-space: nowrap;
    background: rgba(11,99,87,.10);
    color: var(--bm-green-700);
    border: 1px solid rgba(11,99,87,.14);
}

html.dark-mode .bm-verse-chip {
    background: rgba(227,195,125,.1);
    color: var(--bm-gold-light);
    border-color: rgba(227,195,125,.2);
}

.bm-divider {
    position: relative;
    z-index: 2;
    height: 1px;
    background: linear-gradient(90deg, rgba(201,154,69,.35), var(--bm-border), transparent);
}

.bm-page .bm-text {
    position: relative;
    z-index: 2;
    margin: 0;
    padding-left: 22px;
    font-family: 'Cormorant Garamond', Georgia, serif;
    font-style: italic;
    font-size: 19.5px;
    line-height: 1.6;
    color: var(--bm-text);
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bm-page .bm-text::before {
    content: "“";
    position: absolute;
    left: 0;
    top: -6px;
    font-family: Georgia, serif;
    font-style: normal;
    font-size: 34px;
    line-height: 1;
    color: var(--bm-gold);
}

.bm-card-foot {
    position: relative;
    z-index: 2;
    margin-top: auto;
    padding-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border-top: 1px solid var(--bm-border);
}

.bm-date {
    font-size: 11.5px;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: var(--bm-muted);
}

.bm-read {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--bm-green-700);
    transition: gap .25s ease;
}

html.dark-mode .bm-read { color: var(--bm-gold-light); }

.bm-arrow {
    width: 30px;
    height: 30px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    border: 1px solid var(--bm-border);
    color: var(--bm-muted);
    font-size: 14px;
    transition: background .3s ease, color .3s ease, border-color .3s ease, transform .3s ease;
}

.bm-card:hover .bm-read { gap: 11px; }

.bm-card:hover .bm-arrow {
    background: var(--bm-gold);
    border-color: var(--bm-gold);
    color: #fff;
    transform: translateX(2px);
}


/* =====================================================
   EMPTY STATES
===================================================== */
.bm-empty {
    position: relative;
    overflow: hidden;
    margin-top: 24px;
    padding: 64px 24px;
    text-align: center;
    border-radius: 24px;
    border: 1px dashed var(--bm-border);
    background: var(--bm-surface);
    color: var(--bm-muted);
}

.bm-empty::after {
    content: "۞";
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -45%);
    font-family: 'Amiri Quran', serif;
    font-size: 220px;
    color: rgba(201,154,69,.06);
    pointer-events: none;
}

.bm-empty > * { position: relative; z-index: 2; }

.bm-empty-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    background: rgba(201,154,69,.14);
    color: var(--bm-gold);
    font-size: 28px;
}

.bm-page .bm-empty h2 {
    margin: 0 0 6px;
    font-family: Georgia, serif;
    font-size: 24px;
    font-weight: 500;
    color: var(--bm-heading);
}

.bm-page .bm-empty p {
    max-width: 420px;
    margin: 0 auto 20px;
    font-size: 14px;
    line-height: 1.7;
    color: var(--bm-muted);
}

.bm-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 22px;
    border-radius: 999px;
    border: none;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(184,138,68,.28);
    transition: transform .25s ease, box-shadow .25s ease;
}

.bm-page a.bm-btn { color: #051a14; }

.bm-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(184,138,68,.4);
}

.bm-btn.is-ghost {
    background: transparent;
    border: 1px solid var(--bm-border);
    color: var(--bm-heading);
    box-shadow: none;
}

.bm-empty.is-search { display: none; }
.bm-empty.is-search.is-visible { display: block; }


/* =====================================================
   PAGINATION
===================================================== */
.bm-pagination { margin-top: 28px; }

.bm-pagination nav svg { width: 18px; height: 18px; }


/* =====================================================
   ANIMATIONS
===================================================== */
@keyframes bmRise {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (prefers-reduced-motion: reduce) {
    .bm-page *, .bm-page *::before, .bm-page *::after {
        animation-duration: .01ms !important;
        animation-delay: 0s !important;
        transition: none !important;
    }
}


/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 1100px) {
    .bm-grid { grid-template-columns: 1fr; }
}

@media (max-width: 900px) {
    .bm-page { padding: 72px 14px 48px; }          /* room for the sidebar toggle */
    .bm-hero { padding: 28px 20px 30px; border-radius: 20px; }
    .bm-search { max-width: none; }
    .bm-search kbd { display: none; }
}

@media (max-width: 520px) {
    .bm-card { padding: 20px 18px 16px; }
    .bm-card-top { flex-wrap: wrap; }
    .bm-verse-chip { order: 3; }
    .bm-search input { font-size: 16px; }          /* stops iOS zoom on focus */
    .bm-page .bm-text { font-size: 18px; }
    .bm-card-foot { flex-direction: column; align-items: flex-start; }
}


/* =====================================================
   WHEN THE MAIN SIDEBAR IS CLOSED (X) → FULL WIDTH
===================================================== */
.bm-page {
    transition: max-width .3s ease, padding .3s ease;
}

@media (min-width: 901px) {

    body.sidebar-collapsed .bm-page {
        max-width: 100%;
        padding-left: 48px;
        padding-right: 48px;
    }

    /* cards use the extra space: 3 per row on wide screens */
    body.sidebar-collapsed .bm-grid {
        grid-template-columns: repeat(auto-fill, minmax(440px, 1fr));
    }
}
</style>


<div class="bm-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <header class="bm-hero">

        <div class="bm-hero-top">

            <div>
                <span class="bm-eyebrow">Personal Library</span>
                <h1 class="bm-title">Bookmarks</h1>
                <p class="bm-sub">Your saved Quran verses and reflections.</p>
            </div>

            @if ($bookmarks->count())
                <div class="bm-count">
                    <span class="bm-count-icon">♥</span>
                    <span>
                        {{ $totalBookmarks }}
                        {{ $totalBookmarks == 1 ? 'Saved Verse' : 'Saved Verses' }}
                    </span>
                </div>
            @endif

        </div>


        @if ($bookmarks->count())

            <div class="bm-search-row">

                <div class="bm-search" id="bmSearchBox">

                    <svg class="bm-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    <input
                        type="search"
                        id="bmSearch"
                        placeholder="Search by surah name or verse text…"
                        autocomplete="off"
                        spellcheck="false"
                        aria-label="Search bookmarks"
                    >

                    <button type="button" class="bm-clear" id="bmClear" aria-label="Clear search">×</button>
                    <kbd>/</kbd>

                </div>

            </div>

        @endif

    </header>


    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}
    @if (session('success'))
        <div class="bm-success">{{ session('success') }}</div>
    @endif


    {{-- =====================================================
         BOOKMARKS
    ====================================================== --}}
    @if ($bookmarks->count())

        <div class="bm-results-bar">
            <span id="bmCount">
                Showing <b>{{ $bookmarks->count() }}</b> of {{ $bookmarks->count() }}
                {{ $bookmarks->hasPages() ? 'bookmarks on this page' : 'bookmarks' }}
            </span>
        </div>

        <div class="bm-grid" id="bmGrid">

            @foreach ($bookmarks as $bookmark)

                @php
                    $startVerse = $bookmark->startVerse;
                    $endVerse   = $bookmark->endVerse ?? $startVerse;

                    $surah = $startVerse?->surah;

                    $a = $startVerse?->verse_number;
                    $b = $endVerse?->verse_number ?? $a;

                    if ($a !== null && $b !== null) {
                        $from = min($a, $b);
                        $to   = max($a, $b);

                        $savedVerses = \App\Models\Verse::where('surah_id', $surah?->id)
                            ->whereBetween('verse_number', [$from, $to])
                            ->orderBy('verse_number')
                            ->get();
                    } else {
                        $from = $to = null;
                        $savedVerses = collect();
                    }

                    $verseText = $savedVerses
                        ->map(fn ($v) => $v->english_text ?? $v->text ?? $v->arabic_text ?? '')
                        ->filter()
                        ->implode(' … ');

                    $surahName = $surah->name_en ?? $surah->name ?? 'Quran';
                    $surahAr   = $surah->name_ar ?? null;
                    $surahNum  = $surah->number ?? null;
                @endphp


                <a
                    href="{{ route('bookmarks.show', $bookmark->id) }}"
                    class="bm-card"
                    style="--i: {{ $loop->index }}"
                    data-surah="{{ $surahName }}"
                    data-ar="{{ $surahAr }}"
                    data-text="{{ $verseText }}"
                >

                    {{-- TOP --}}
                    <div class="bm-card-top">

                        <div class="bm-badge">
                            <span>{{ $surahNum ?? '✦' }}</span>
                        </div>

                        <div class="bm-surah">
                            <h2 class="bm-surah-name">{{ $surahName }}</h2>

                            @if ($surahAr)
                                <div class="bm-surah-ar" dir="rtl" lang="ar">{{ $surahAr }}</div>
                            @endif
                        </div>

                        <div class="bm-verse-chip">
                            @if ($from !== null && $to !== null)
                                {{ $from == $to ? 'Verse ' . $from : 'Verses ' . $from . '–' . $to }}
                            @else
                                Saved Verses
                            @endif
                        </div>

                    </div>

                    <div class="bm-divider"></div>

                    {{-- VERSE TEXT --}}
                    <p class="bm-text">
                        {{ $verseText !== '' ? $verseText : 'Verse text unavailable.' }}
                    </p>

                    {{-- FOOTER --}}
                    <div class="bm-card-foot">

                        <div class="bm-date">
                            Saved {{ $bookmark->created_at?->format('M d, Y') }}
                        </div>

                        <div class="bm-read">
                            Read Tafsir
                            <span class="bm-arrow" aria-hidden="true">→</span>
                        </div>

                    </div>

                </a>

            @endforeach

        </div>


        {{-- nothing matches the search --}}
        <div class="bm-empty is-search" id="bmEmpty">
            <div class="bm-empty-icon">✦</div>
            <h2>No bookmark found</h2>
            <p id="bmEmptyText">Try a different surah name or word.</p>
            <button type="button" class="bm-btn is-ghost" id="bmReset">Clear search</button>
        </div>


        {{-- PAGINATION --}}
        @if ($bookmarks->hasPages())
            <div class="bm-pagination">
                {{ $bookmarks->links() }}
            </div>
        @endif


    @else

        {{-- EMPTY STATE --}}
        <div class="bm-empty">

            <div class="bm-empty-icon">♡</div>

            <h2>No Bookmarks Yet</h2>

            <p>
                Save verses while reading the Quran and they will appear here
                for quick access to their tafsir.
            </p>

            <a href="{{ route('surahs.reader') }}" class="bm-btn">
                Explore the Quran <span aria-hidden="true">→</span>
            </a>

        </div>

    @endif

</div>


<script>
(function () {

    const input = document.getElementById('bmSearch');
    const grid  = document.getElementById('bmGrid');
    if (!input || !grid) return;

    const box     = document.getElementById('bmSearchBox');
    const clear   = document.getElementById('bmClear');
    const count   = document.getElementById('bmCount');
    const empty   = document.getElementById('bmEmpty');
    const emptyTx = document.getElementById('bmEmptyText');
    const reset   = document.getElementById('bmReset');
    const cards   = Array.from(grid.querySelectorAll('.bm-card'));
    const total   = cards.length;
    const suffix  = count.textContent.includes('on this page') ? 'bookmarks on this page' : 'bookmarks';

    // "Al-Baqarah" / "al baqarah" → "albaqarah"
    const norm = (s) => (s || '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f\u064b-\u065f\u0670]/g, '')
        .replace(/[^a-z0-9\u0600-\u06ff]/g, '');

    cards.forEach((card) => {
        card._key = norm(card.dataset.surah) + '|' + norm(card.dataset.ar) + '|' + norm(card.dataset.text);
    });

    function apply() {

        const raw = input.value.trim();
        const q   = norm(raw);
        let shown = 0;

        cards.forEach((card) => {
            const visible = !q || card._key.includes(q);
            card.hidden = !visible;
            if (visible) shown++;
        });

        box.classList.toggle('has-value', raw.length > 0);

        count.innerHTML = 'Showing <b>' + shown + '</b> of ' + total + ' ' + suffix;

        const none = shown === 0;
        empty.classList.toggle('is-visible', none);
        grid.style.display = none ? 'none' : '';

        if (none) {
            emptyTx.textContent = 'Nothing matches “' + raw + '”. Try a different surah name or word.';
        }
    }

    input.addEventListener('input', apply);

    clear.addEventListener('click', () => {
        input.value = '';
        apply();
        input.focus();
    });

    reset.addEventListener('click', () => {
        input.value = '';
        apply();
        input.focus();
    });

    document.addEventListener('keydown', (e) => {

        const typing = /^(input|textarea|select)$/i.test(document.activeElement.tagName);

        if (e.key === '/' && !typing) {
            e.preventDefault();
            input.focus();
        }

        if (e.key === 'Escape' && document.activeElement === input) {
            input.value = '';
            apply();
        }

        if (e.key === 'Enter' && document.activeElement === input) {
            const first = cards.find((c) => !c.hidden);
            if (first) window.location.href = first.href;
        }
    });

})();
</script>

@endsection