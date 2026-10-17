@extends('layouts.app')

@section('title', 'Interpreted Surahs')

@section('content')

@php
    $completedIds = $completedSurahIds ?? [];
    $hasProgress  = isset($completedSurahIds) && auth()->check();
    $totalSurahs  = $surahs->count();
    $doneSurahs   = $hasProgress ? $surahs->whereIn('id', $completedIds)->count() : 0;
    $percent      = $totalSurahs > 0 ? round(($doneSurahs / $totalSurahs) * 100) : 0;
@endphp

<style>

/* =====================================================
   THEME VARIABLES
===================================================== */
.sr-page {
    --sr-surface: #ffffff;
    --sr-surface-soft: #fbf8f2;
    --sr-text: #123a35;
    --sr-muted: #71817c;
    --sr-heading: #043b35;
    --sr-border: rgba(18,58,53,.10);
    --sr-shadow: 0 12px 34px rgba(26,49,43,.07);
    --sr-shadow-hover: 0 22px 44px rgba(26,49,43,.14);
    --sr-gold: #c99a45;
    --sr-gold-light: #e3c37d;
    --sr-green-700: #0b6357;
    --sr-green-900: #043b35;

    padding: 32px 32px 64px;
    max-width: 1240px;
    margin: 0 auto;
}

html.dark-mode .sr-page {
    --sr-surface: #1a251f;
    --sr-surface-soft: #17221c;
    --sr-text: #cbd6cf;
    --sr-muted: #8fa097;
    --sr-heading: #e6eee9;
    --sr-border: rgba(255,255,255,.075);
    --sr-shadow: 0 12px 34px rgba(0,0,0,.28);
    --sr-shadow-hover: 0 22px 44px rgba(0,0,0,.4);
}


/* =====================================================
   HERO
===================================================== */
.sr-hero {
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
    animation: srRise .6s ease both;
}

.sr-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.15' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 20%, #000 100%);
    pointer-events: none;
}

.sr-hero-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
}

.sr-page .sr-eyebrow {
    display: block;
    margin-bottom: 12px;
    font-size: 11px;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--sr-gold-light);
}

.sr-page .sr-title {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(30px, 4.4vw, 46px);
    font-weight: 500;
    line-height: 1.12;
    color: #fff;
}

.sr-page .sr-sub {
    margin: 12px 0 0;
    font-size: 14px;
    color: rgba(255,255,255,.62);
}

.sr-page .sr-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    border-radius: 999px;
    border: 1px solid rgba(227,195,125,.35);
    background: rgba(255,255,255,.06);
    color: #f7f0e4;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    backdrop-filter: blur(4px);
    transition: background .25s ease, border-color .25s ease, transform .25s ease;
}

.sr-page .sr-back:hover {
    background: rgba(227,195,125,.14);
    border-color: var(--sr-gold-light);
    transform: translateX(-2px);
}


/* ===== SEARCH ===== */
.sr-search-row {
    position: relative;
    z-index: 2;
    margin-top: 30px;
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.sr-search {
    position: relative;
    flex: 1 1 380px;
    max-width: 560px;
}

.sr-search svg.sr-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sr-gold-light);
    pointer-events: none;
}

.sr-search input {
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

.sr-search input::placeholder { color: rgba(247,240,228,.45); }

.sr-search input:focus {
    border-color: var(--sr-gold-light);
    background: rgba(3,20,16,.75);
    box-shadow: 0 0 0 4px rgba(227,195,125,.14);
}

.sr-search input::-webkit-search-cancel-button { display: none; }

.sr-search .sr-clear {
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

.sr-search .sr-clear:hover { background: rgba(255,255,255,.2); }
.sr-search.has-value .sr-clear { display: grid; }

.sr-search kbd {
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

.sr-search.has-value kbd { display: none; }


/* ===== FILTER CHIPS ===== */
.sr-chips { display: flex; gap: 8px; flex-wrap: wrap; }

.sr-chip {
    padding: 9px 16px;
    border-radius: 999px;
    border: 1px solid rgba(227,195,125,.28);
    background: rgba(255,255,255,.05);
    color: rgba(255,255,255,.75);
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s ease, color .2s ease, border-color .2s ease;
}

.sr-chip:hover { background: rgba(227,195,125,.12); color: #fff; }

.sr-chip.is-active {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    border-color: transparent;
    color: #051a14;
}


/* ===== PROGRESS ===== */
.sr-progress {
    position: relative;
    z-index: 2;
    margin-top: 26px;
    max-width: 560px;
}

.sr-progress-top {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 12px;
    letter-spacing: .06em;
    color: rgba(255,255,255,.6);
}

.sr-progress-top b { color: var(--sr-gold-light); font-weight: 600; }

.sr-bar {
    height: 6px;
    border-radius: 999px;
    background: rgba(255,255,255,.1);
    overflow: hidden;
}

.sr-bar span {
    display: block;
    height: 100%;
    width: 0;
    border-radius: inherit;
    background: linear-gradient(90deg, #b88a44, #e3c37d);
    animation: srBar 1s ease .3s forwards;
}


/* =====================================================
   RESULTS BAR
===================================================== */
.sr-results-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 26px 4px 16px;
    font-size: 13px;
    color: var(--sr-muted);
}

.sr-results-bar b { color: var(--sr-heading); font-weight: 600; }


/* =====================================================
   GRID + CARDS
===================================================== */
.sr-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 18px;
}

.sr-page .sr-card {
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    gap: 16px;
    padding: 20px 20px 16px;
    border-radius: 22px;
    background: var(--sr-surface);
    border: 1px solid var(--sr-border);
    box-shadow: var(--sr-shadow);
    color: var(--sr-text);
    text-decoration: none;
    opacity: 0;
    animation: srRise .5s ease forwards;
    animation-delay: calc(min(var(--i, 0), 18) * 28ms + .15s);
    transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
}

.sr-card[hidden] { display: none; }

.sr-card::before {
    content: "";
    position: absolute;
    left: 0; right: 0; top: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--sr-gold), transparent);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .4s ease;
}

.sr-card::after {
    content: "";
    position: absolute;
    width: 140px; height: 140px;
    right: -70px; bottom: -80px;
    border-radius: 50%;
    border: 1px solid rgba(201,154,69,.16);
    pointer-events: none;
    transition: transform .5s ease;
}

.sr-page .sr-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--sr-shadow-hover);
    border-color: rgba(201,154,69,.45);
}

.sr-card:hover::before { transform: scaleX(1); }
.sr-card:hover::after  { transform: scale(1.25); }

.sr-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}

/* 8-point star badge */
.sr-badge {
    position: relative;
    width: 54px;
    height: 54px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
}

.sr-badge::before,
.sr-badge::after {
    content: "";
    position: absolute;
    inset: 9px;
    border-radius: 7px;
    background: linear-gradient(135deg, var(--sr-green-700), var(--sr-green-900));
    transition: background .3s ease;
}

.sr-badge::after { transform: rotate(45deg); }

.sr-badge span {
    position: relative;
    z-index: 2;
    font-family: Georgia, serif;
    font-size: 15px;
    font-weight: 700;
    color: var(--sr-gold-light);
}

.sr-card.is-done .sr-badge::before,
.sr-card.is-done .sr-badge::after {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
}

.sr-card.is-done .sr-badge span { color: #051a14; }

.sr-page .sr-ar {
    font-family: 'Amiri Quran', 'Amiri', 'Scheherazade New', serif;
    font-size: 27px;
    line-height: 1.4;
    color: var(--sr-heading);
    text-align: right;
}

.sr-page .sr-name {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 19px;
    font-weight: 500;
    line-height: 1.3;
    color: var(--sr-heading);
}

.sr-page .sr-sections {
    margin-top: 4px;
    font-size: 12.5px;
    color: var(--sr-muted);
}

.sr-card-foot {
    margin-top: auto;
    padding-top: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    border-top: 1px solid var(--sr-border);
}

.sr-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 12px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    background: rgba(11,99,87,.10);
    color: var(--sr-green-700);
    border: 1px solid rgba(11,99,87,.14);
}

html.dark-mode .sr-pill {
    background: rgba(227,195,125,.1);
    color: var(--sr-gold-light);
    border-color: rgba(227,195,125,.2);
}

.sr-card.is-done .sr-pill {
    background: rgba(201,154,69,.16);
    color: #8a6420;
    border-color: rgba(201,154,69,.35);
}

html.dark-mode .sr-card.is-done .sr-pill { color: var(--sr-gold-light); }

.sr-arrow {
    width: 32px;
    height: 32px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    border: 1px solid var(--sr-border);
    color: var(--sr-muted);
    font-size: 15px;
    transition: background .3s ease, color .3s ease, transform .3s ease, border-color .3s ease;
}

.sr-card:hover .sr-arrow {
    background: var(--sr-gold);
    border-color: var(--sr-gold);
    color: #fff;
    transform: translateX(3px);
}


/* =====================================================
   EMPTY STATES
===================================================== */
.sr-empty {
    display: none;
    padding: 56px 20px;
    text-align: center;
    border-radius: 22px;
    border: 1px dashed var(--sr-border);
    background: var(--sr-surface);
    color: var(--sr-muted);
}

.sr-empty.is-visible { display: block; }

.sr-empty .sr-empty-icon {
    display: block;
    margin-bottom: 10px;
    font-size: 30px;
    color: var(--sr-gold);
}

.sr-page .sr-empty strong {
    display: block;
    margin-bottom: 4px;
    font-size: 16px;
    color: var(--sr-heading);
}

.sr-empty button {
    margin-top: 16px;
    padding: 9px 18px;
    border-radius: 999px;
    border: 1px solid var(--sr-border);
    background: transparent;
    color: var(--sr-heading);
    font-family: inherit;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .2s ease;
}

.sr-empty button:hover { background: rgba(201,154,69,.14); }


/* =====================================================
   ANIMATIONS
===================================================== */
@keyframes srRise {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes srBar {
    to { width: var(--w, 0%); }
}

@media (prefers-reduced-motion: reduce) {
    .sr-page *, .sr-page *::before, .sr-page *::after {
        animation-duration: .01ms !important;
        animation-delay: 0s !important;
        transition: none !important;
    }
}


/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 900px) {
    .sr-page { padding: 72px 14px 48px; }          /* room for the sidebar toggle */
    .sr-hero { padding: 28px 20px 30px; border-radius: 20px; }
    .sr-search { flex-basis: 100%; max-width: none; }
    .sr-search kbd { display: none; }
    .sr-grid { grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px; }
}

@media (max-width: 520px) {
    .sr-grid { grid-template-columns: 1fr; }
    .sr-search input { font-size: 16px; }          /* stops iOS zoom on focus */
}

/* =====================================================
   WHEN THE MAIN SIDEBAR IS CLOSED (X) → FULL WIDTH
===================================================== */
.sr-page {
    transition: max-width .3s ease, padding .3s ease;
}

@media (min-width: 901px) {

    body.sidebar-collapsed .sr-page {
        max-width: 100%;
        padding-left: 48px;
        padding-right: 48px;
    }

    /* more cards per row now that there is extra space */
    body.sidebar-collapsed .sr-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }
}
</style>


<div class="sr-page">

    {{-- =====================================================
         HERO + SEARCH
    ====================================================== --}}
    <header class="sr-hero">

        <div class="sr-hero-top">

            <div>
                <span class="sr-eyebrow">Quran Tafsir</span>
                <h1 class="sr-title">Interpreted Surahs</h1>
                <p class="sr-sub">
                    {{ $totalSurahs }} {{ \Illuminate\Support\Str::plural('surah', $totalSurahs) }} with tafsir available
                </p>
            </div>

            <a href="{{ route('dashboard') }}" class="sr-back">
                <span aria-hidden="true">←</span> Back to dashboard
            </a>

        </div>


        @if (! $surahs->isEmpty())

            <div class="sr-search-row">

                <div class="sr-search" id="srSearchBox">
                    <svg class="sr-search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>

                    <input
                        type="search"
                        id="srSearch"
                        placeholder="Search by surah name… e.g. Al-Baqarah"
                        autocomplete="off"
                        spellcheck="false"
                        aria-label="Search surahs by English name"
                    >

                    <button type="button" class="sr-clear" id="srClear" aria-label="Clear search">×</button>
                    <kbd>/</kbd>
                </div>

                @if ($hasProgress)
                    <div class="sr-chips" id="srChips">
                        <button type="button" class="sr-chip is-active" data-filter="all">All</button>
                        <button type="button" class="sr-chip" data-filter="done">Completed</button>
                        <button type="button" class="sr-chip" data-filter="open">Open</button>
                    </div>
                @endif

            </div>


            @if ($hasProgress)
                <div class="sr-progress">
                    <div class="sr-progress-top">
                        <span>Your progress</span>
                        <span><b>{{ $doneSurahs }}</b> / {{ $totalSurahs }} completed</span>
                    </div>
                    <div class="sr-bar"><span style="--w: {{ $percent }}%"></span></div>
                </div>
            @endif

        @endif

    </header>


    {{-- =====================================================
         SURAHS
    ====================================================== --}}
    @if ($surahs->isEmpty())

        <div class="sr-empty is-visible" style="margin-top: 24px;">
            <span class="sr-empty-icon">✦</span>
            <strong>No interpreted surahs yet</strong>
            No interpreted surahs are available yet.
        </div>

    @else

        <div class="sr-results-bar">
            <span id="srCount">Showing <b>{{ $totalSurahs }}</b> of {{ $totalSurahs }} surahs</span>
        </div>

        <div class="sr-grid" id="srGrid">

            @foreach ($surahs as $surah)

                @php $isDone = in_array($surah->id, $completedIds); @endphp

                <a
                    href="{{ route('tafsir.reader.show', ['surah' => $surah->slug]) }}"
                    class="sr-card {{ $isDone ? 'is-done' : '' }}"
                    style="--i: {{ $loop->index }}"
                    data-name="{{ $surah->name_en }}"
                    data-ar="{{ $surah->name_ar }}"
                    data-num="{{ $surah->number }}"
                    data-status="{{ $isDone ? 'done' : 'open' }}"
                >

                    <div class="sr-card-top">

                        <div class="sr-badge"><span>{{ $surah->number }}</span></div>

                        <div class="sr-ar" dir="rtl" lang="ar">{{ $surah->name_ar }}</div>

                    </div>

                    <div>
                        <h2 class="sr-name">{{ $surah->name_en }}</h2>
                        <div class="sr-sections">
                            {{ $surah->tafsir->count() }}
                            {{ \Illuminate\Support\Str::plural('section', $surah->tafsir->count()) }}
                        </div>
                    </div>

                    <div class="sr-card-foot">

                        @if ($isDone)
                            <span class="sr-pill">✓ Completed</span>
                        @else
                            <span class="sr-pill">Open</span>
                        @endif

                        <span class="sr-arrow" aria-hidden="true">→</span>

                    </div>

                </a>

            @endforeach

        </div>


        {{-- shown when nothing matches the search --}}
        <div class="sr-empty" id="srEmpty">
            <span class="sr-empty-icon">✦</span>
            <strong>No surah found</strong>
            <span id="srEmptyText">Try a different spelling.</span>
            <br>
            <button type="button" id="srReset">Clear search</button>
        </div>

    @endif

</div>


<script>
(function () {

    const input   = document.getElementById('srSearch');
    const grid    = document.getElementById('srGrid');
    if (!input || !grid) return;

    const box     = document.getElementById('srSearchBox');
    const clear   = document.getElementById('srClear');
    const count   = document.getElementById('srCount');
    const empty   = document.getElementById('srEmpty');
    const emptyTx = document.getElementById('srEmptyText');
    const reset   = document.getElementById('srReset');
    const chips   = document.querySelectorAll('.sr-chip');
    const cards   = Array.from(grid.querySelectorAll('.sr-card'));
    const total   = cards.length;

    let status = 'all';

    // "Al-Baqarah", "al baqarah", "An-Nisa'" → "albaqarah", "annisa"
    const norm = (s) => (s || '')
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f\u064b-\u065f\u0670]/g, '')
        .replace(/[^a-z0-9\u0600-\u06ff]/g, '');

    // pre-compute search keys once
    cards.forEach((card) => {
        card._name = norm(card.dataset.name);
        card._ar   = norm(card.dataset.ar);
        card._num  = card.dataset.num;
    });

    function apply() {

        const raw = input.value.trim();
        const q   = norm(raw);
        let shown = 0;

        cards.forEach((card) => {

            const matchText = !q
                || card._name.includes(q)
                || card._ar.includes(q)
                || card._num === q;

            const matchStatus = status === 'all' || card.dataset.status === status;

            const visible = matchText && matchStatus;

            card.hidden = !visible;
            if (visible) shown++;

        });

        box.classList.toggle('has-value', raw.length > 0);

        count.innerHTML = 'Showing <b>' + shown + '</b> of ' + total + ' surahs';

        const none = shown === 0;
        empty.classList.toggle('is-visible', none);
        grid.style.display = none ? 'none' : '';

        if (none) {
            emptyTx.textContent = raw
                ? 'Nothing matches “' + raw + '”. Try a different spelling.'
                : 'No surahs in this filter yet.';
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
        status = 'all';
        chips.forEach((c) => c.classList.toggle('is-active', c.dataset.filter === 'all'));
        apply();
        input.focus();
    });

    chips.forEach((chip) => {
        chip.addEventListener('click', () => {
            status = chip.dataset.filter;
            chips.forEach((c) => c.classList.toggle('is-active', c === chip));
            apply();
        });
    });

    // "/" focuses the search, Esc clears it
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

        // Enter opens the first visible result
        if (e.key === 'Enter' && document.activeElement === input) {
            const first = cards.find((c) => !c.hidden);
            if (first) window.location.href = first.href;
        }
    });

})();
</script>

@endsection