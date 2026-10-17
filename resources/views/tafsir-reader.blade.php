@extends('layouts.app')
@php
    use App\Models\Bookmark;
    use App\Models\ReadingProgress;
    use App\Models\Verse;
@endphp
@section('title', 'Tafsir Reader — Hidayah')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>



*, *::before, *::after { box-sizing: border-box; }

html { scroll-behavior: smooth; }


/* =====================================================
   THEME VARIABLES
===================================================== */
.tafsir-page {
    --rd-bg: #f6f1e8;
    --rd-surface: #ffffff;
    --rd-paper: #fbf8f0;
    --rd-text: #34443d;
    --rd-muted: #71817c;
    --rd-heading: #043b35;
    --rd-border: rgba(18,58,53,.10);
    --rd-shadow: 0 14px 40px rgba(26,49,43,.08);
    --rd-shadow-hover: 0 20px 44px rgba(26,49,43,.14);
    --rd-gold: #c99a45;
    --rd-gold-light: #e3c37d;
    --rd-green-700: #0b6357;
    --rd-green-900: #043b35;
    --rd-hero-bg:
        radial-gradient(circle at 12% 0%, rgba(227,195,125,.14), transparent 35%),
        radial-gradient(circle at 100% 100%, rgba(11,99,87,.55), transparent 45%),
        linear-gradient(135deg, #073c35 0%, #04352f 55%, #022c28 100%);

    --rd-serif: Georgia, "Times New Roman", serif;
    --rd-quran: "Amiri Quran", "Amiri", "Scheherazade New", serif;
    --rd-sans: "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;
}

html.dark-mode .tafsir-page {
    --rd-bg: #101713;
    --rd-surface: #1a251f;
    --rd-paper: #151f1a;
    --rd-text: #d3ddd7;
    --rd-muted: #8fa097;
    --rd-heading: #e6eee9;
    --rd-border: rgba(255,255,255,.08);
    --rd-shadow: 0 14px 40px rgba(0,0,0,.3);
    --rd-shadow-hover: 0 20px 44px rgba(0,0,0,.42);
}


/* =====================================================
   PAGE
===================================================== */
.tafsir-page {
    min-height: 100vh;
    padding: 30px 32px 64px;
    font-family: var(--rd-sans);
    color: var(--rd-text);
    background:
        radial-gradient(circle at 90% 0%, rgba(201,154,69,.10), transparent 26%),
        radial-gradient(circle at 0% 100%, rgba(7,95,83,.06), transparent 30%),
        var(--rd-bg);
}

.tafsir-page a { text-decoration: none; }

.quran-audio { display: none; }


/* =====================================================
   TOP HEADER (breadcrumb + actions)
===================================================== */
.tafsir-header {
    max-width: 1380px;
    margin: 0 auto 18px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.tafsir-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 11px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--rd-muted);
}

.tafsir-breadcrumb strong { color: var(--rd-heading); font-weight: 700; }
.tafsir-breadcrumb .separator { color: var(--rd-gold); font-size: 15px; }

.tafsir-actions { display: flex; gap: 8px; }

.tafsir-page .tafsir-action {
    min-height: 40px;
    padding: 0 18px;
    border-radius: 999px;
    border: 1px solid var(--rd-border);
    background: var(--rd-surface);
    color: var(--rd-heading);
    font-family: var(--rd-sans);
    font-size: 12.5px;
    font-weight: 600;
    cursor: pointer;
    transition: background .25s ease, border-color .25s ease, transform .25s ease, box-shadow .25s ease;
}

.tafsir-page .tafsir-action:hover {
    transform: translateY(-2px);
    border-color: rgba(201,154,69,.5);
    background: rgba(201,154,69,.12);
    box-shadow: 0 8px 20px rgba(0,60,54,.08);
}

.tafsir-page .tafsir-action.theme-toggle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 128px;
}

html.dark-mode .tafsir-page .tafsir-action.theme-toggle { color: #f0d38c; }


/* =====================================================
   TOP AUDIO BAR (reciter)
===================================================== */
.tafsir-page .top-audio-bar {
    max-width: 1380px;
    margin: 0 auto 22px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    border-radius: 20px;
    background: var(--rd-surface);
    border: 1px solid var(--rd-border);
    box-shadow: var(--rd-shadow);
}

html.dark-mode .tafsir-page .top-audio-bar {
    background: var(--rd-surface) !important;
    border-color: var(--rd-border) !important;
}

.audio-bar-left { display: flex; align-items: center; gap: 14px; }

.audio-bar-icon {
    width: 44px;
    height: 44px;
    display: grid;
    place-items: center;
    border-radius: 14px;
    background: linear-gradient(135deg, #0b6357, #043b35);
    color: var(--rd-gold-light);
    font-size: 18px;
    box-shadow: 0 8px 20px rgba(4,59,53,.22);
}

.audio-bar-title {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: var(--rd-heading);
}

.audio-bar-subtitle { margin-top: 3px; font-size: 12px; color: var(--rd-muted); }

.audio-bar-right { display: flex; align-items: center; gap: 8px; }

.tafsir-page .audio-select {
    min-width: 240px;
    height: 44px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid rgba(201,154,69,.4);
    background: var(--rd-paper);
    color: var(--rd-heading);
    font-family: var(--rd-sans);
    font-size: 13px;
    font-weight: 600;
    outline: none;
    cursor: pointer;
    transition: border-color .25s ease, box-shadow .25s ease;
}

.tafsir-page .audio-select:focus {
    border-color: var(--rd-gold);
    box-shadow: 0 0 0 4px rgba(201,154,69,.16);
}


/* =====================================================
   MAIN GRID
===================================================== */
.reader-grid {
    max-width: 1380px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: 300px minmax(0, 1fr);
    gap: 26px;
    align-items: start;
}

.reader-main { min-width: 0; }


/* =====================================================
   READER SIDE PANEL
===================================================== */
.tafsir-page .reader-sidebar {
    position: sticky;
    top: 24px;
    width: auto;
    margin-left: 0;
    max-height: calc(100vh - 48px);
    overflow-y: auto;
    overflow-x: hidden;
    padding: 24px 18px;
    border-radius: 24px;
    color: #fff;
    background: var(--rd-hero-bg);
    border: 1px solid rgba(227,195,125,.16);
    box-shadow: 0 20px 50px rgba(2,44,40,.26);
    scrollbar-width: thin;
    scrollbar-color: rgba(227,195,125,.3) transparent;
}

.reader-sidebar::before {
    content: "";
    position: absolute;
    width: 240px;
    height: 240px;
    right: -130px;
    bottom: -120px;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.1);
    pointer-events: none;
}

.reader-logo {
    text-align: center;
    padding: 4px 0 20px;
    margin-bottom: 18px;
    border-bottom: 1px solid rgba(255,255,255,.09);
}

.reader-logo-symbol {
    width: 50px;
    height: 50px;
    margin: 0 auto 12px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    color: var(--rd-gold-light);
    font-size: 21px;
    border: 1px solid rgba(227,195,125,.45);
    background: radial-gradient(circle, rgba(227,195,125,.18), rgba(227,195,125,.03) 65%);
}

.reader-logo-subtitle {
    color: rgba(255,255,255,.55);
    font-size: 10px;
    letter-spacing: .2em;
    text-transform: uppercase;
    line-height: 1.7;
}

.reader-nav-title {
    padding: 0 6px 10px;
    color: rgba(255,255,255,.4);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .2em;
    text-transform: uppercase;
}

.reader-nav { display: flex; flex-direction: column; gap: 6px; }

.tafsir-page .reader-nav a {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 11px 12px;
    border-radius: 12px;
    color: rgba(255,255,255,.7);
    font-size: 13px;
    transition: background .25s ease, color .25s ease, transform .25s ease;
}

.tafsir-page .reader-nav a:hover {
    color: #fff;
    background: rgba(255,255,255,.07);
    transform: translateX(2px);
}

.tafsir-page .reader-nav a.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(201,154,69,.22), rgba(255,255,255,.05));
    box-shadow: inset 3px 0 0 var(--rd-gold);
}

.reader-nav-icon { width: 20px; text-align: center; font-size: 15px; color: var(--rd-gold-light); }

/* available tafsirs */
.reader-note {
    position: relative;
    z-index: 2;
    margin-top: 22px;
    padding: 18px 16px;
    border-radius: 18px;
    background: linear-gradient(145deg, rgba(255,255,255,.085), rgba(255,255,255,.025));
    border: 1px solid rgba(255,255,255,.08);
}

.reader-note-label {
    margin-bottom: 8px;
    color: var(--rd-gold-light);
    font-size: 10px;
    font-weight: 600;
    letter-spacing: .2em;
    text-transform: uppercase;
}

.reader-note-title {
    margin-bottom: 12px;
    font-family: var(--rd-serif);
    font-size: 22px;
    font-weight: 500;
    color: #fff;
}

.reader-note-text {
    max-height: 340px;
    overflow-y: auto;
    padding-right: 4px;
    color: rgba(255,255,255,.55);
    font-size: 13px;
    line-height: 1.5;
    scrollbar-width: thin;
    scrollbar-color: rgba(227,195,125,.3) transparent;
}

.tafsir-page .reader-note-text a {
    display: block;
    padding: 8px 10px;
    border-radius: 9px;
    font-size: 14px;
    transition: background .2s ease, transform .2s ease;
}

.tafsir-page .reader-note-text a:hover {
    background: rgba(255,255,255,.08);
    color: #fff !important;
    transform: translateX(3px);
}


/* =====================================================
   SURAH HEADER (hero)
===================================================== */
.tafsir-page .surah-header {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 18px 20px;
    margin-bottom: 22px;
    padding: 26px 30px;
    border-radius: 26px;
    color: #fff;
    background: var(--rd-hero-bg);
    border: 1px solid rgba(227,195,125,.16);
    box-shadow: 0 20px 50px rgba(2,44,40,.26);
}

html.dark-mode .tafsir-page .surah-header {
    background: var(--rd-hero-bg) !important;
    border-color: rgba(227,195,125,.16) !important;
}

.surah-header::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.14' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 25%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 25%, #000 100%);
    pointer-events: none;
}

.surah-header::after { display: none; }

.surah-header > * { position: relative; z-index: 2; }

.surah-info { flex: 1 1 300px; display: flex; align-items: center; gap: 18px; }

/* 8-point star number */
.surah-number {
    position: relative;
    isolation: isolate;
    width: 60px;
    height: 60px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    background: none;
    box-shadow: none;
    border-radius: 0;
    font-family: var(--rd-serif);
    font-size: 19px;
    font-weight: 700;
    color: #051a14;
}

.surah-number::before,
.surah-number::after {
    content: "";
    position: absolute;
    inset: 10px;
    z-index: -1;
    border-radius: 8px;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
}

.surah-number::after { transform: rotate(45deg); }

.tafsir-page .surah-title {
    font-family: var(--rd-serif);
    font-size: clamp(26px, 3.4vw, 38px);
    font-weight: 500;
    line-height: 1.15;
    color: #fff;
}

.tafsir-page .surah-meta {
    margin-top: 8px;
    font-size: 13px;
    color: rgba(255,255,255,.66);
}


/* Listen buttons (surah + inline verse) */
.tafsir-page .surah-listen-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    padding: 0 22px;
    border: 0;
    border-radius: 999px;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
    font-family: var(--rd-sans);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(184,138,68,.3);
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease;
}

.tafsir-page .surah-listen-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 26px rgba(184,138,68,.42);
}

.tafsir-page .surah-listen-btn.playing {
    background: #043b35;
    color: var(--rd-gold-light);
    box-shadow: 0 0 0 1.5px var(--rd-gold), 0 8px 22px rgba(0,0,0,.25);
    animation: rdPulse 1.8s ease-in-out infinite;
}

.verse-inline-listen { display: flex; margin: 20px auto 0; width: fit-content; }


/* Beginner / Advanced selector */
.layer-selector {
    display: flex;
    gap: 4px;
    padding: 4px;
    border-radius: 999px;
    background: rgba(3,20,16,.45);
    border: 1px solid rgba(227,195,125,.25);
    backdrop-filter: blur(6px);
}

.tafsir-page .layer-btn {
    padding: 10px 20px;
    border-radius: 999px;
    border: 0;
    background: transparent;
    color: rgba(255,255,255,.7);
    font-family: var(--rd-sans);
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: background .25s ease, color .25s ease;
}

.tafsir-page .layer-btn:hover { color: #fff; }

.tafsir-page .layer-btn.active {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(184,138,68,.35);
}


/* =====================================================
   PROGRESS STATUS LINE
===================================================== */
.tafsir-progress-status {
    margin: 0 0 18px;
    text-align: center;
    color: var(--rd-muted);
    font-size: 12px;
    letter-spacing: .06em;
}

.tafsir-progress-status:empty { display: none; }


/* =====================================================
   TAFSIR STEPS (JS shows / hides these)
===================================================== */
.tafsir-step { display: none; }
.tafsir-step.active { display: block !important; }

.tafsir-step.active .verse-card,
.tafsir-step.active .tafsir-card,
.tafsir-step.active .tafsir-progress-wrap {
    animation: rdRise .55s ease both;
}

.tafsir-step.active .tafsir-card { animation-delay: .1s; }
.tafsir-step.active .tafsir-progress-wrap { animation-delay: .2s; }


/* =====================================================
   VERSE CARD
===================================================== */
.tafsir-page .verse-card {
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    padding: 34px 44px 36px;
    border-radius: 26px;
    background: var(--rd-paper);
    border: 1px solid var(--rd-border);
    box-shadow: var(--rd-shadow);
}

html.dark-mode .tafsir-page .verse-card {
    background: var(--rd-paper) !important;
    border-color: var(--rd-border) !important;
}

/* gold corner brackets */
.verse-card::before,
.verse-card::after {
    content: "";
    position: absolute;
    width: 30px;
    height: 30px;
    border: 1.5px solid var(--rd-gold);
    opacity: .7;
    pointer-events: none;
}

.verse-card::before { top: 12px; left: 12px; border-right: 0; border-bottom: 0; border-top-left-radius: 10px; }
.verse-card::after  { bottom: 12px; right: 12px; border-left: 0; border-top: 0; border-bottom-right-radius: 10px; }

.verse-top {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 24px;
}

.verse-label {
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--rd-green-700);
    font-size: 11.5px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
}

html.dark-mode .verse-label { color: #9fc0ad; }

.verse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--rd-gold);
    box-shadow: 0 0 0 4px rgba(201,154,69,.15);
}


/* bookmark */
.bookmark-wrap { display: flex; align-items: center; gap: 10px; }

.bookmark-form { margin: 0; padding: 0; }

.tafsir-page .bookmark-btn {
    width: 42px;
    height: 42px;
    padding: 0;
    display: grid;
    place-items: center;
    border-radius: 50%;
    border: 1px solid var(--rd-border);
    background: var(--rd-surface);
    color: var(--rd-green-700);
    font-size: 20px;
    cursor: pointer;
    transition: color .25s ease, background .25s ease, border-color .25s ease, transform .25s ease, box-shadow .25s ease;
}

html.dark-mode .tafsir-page .bookmark-btn { color: #9fc0ad; }

.tafsir-page .bookmark-btn:hover { color: var(--rd-gold); transform: translateY(-2px); }
.bookmark-btn:disabled { cursor: wait; opacity: .75; }

.tafsir-page .bookmark-btn.bookmarked {
    background: rgba(201,154,69,.18);
    color: #9a711c;
    border-color: rgba(201,154,69,.45);
    box-shadow: 0 6px 16px rgba(201,154,69,.18);
}

html.dark-mode .tafsir-page .bookmark-btn.bookmarked { color: #e1c27a; }

.bookmark-icon { display: block; line-height: 1; transition: transform .2s ease; }
.bookmark-btn.bookmarked .bookmark-icon { transform: scale(1.1); }

.bookmark-label {
    color: var(--rd-muted);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .12em;
    text-transform: uppercase;
    transition: color .25s ease;
}

.bookmark-wrap:has(.bookmark-btn.bookmarked) .bookmark-label { color: #9a711c; }
html.dark-mode .bookmark-wrap:has(.bookmark-btn.bookmarked) .bookmark-label { color: #e1c27a; }


/* quran text */
.tafsir-page .quran-verse {
    position: relative;
    z-index: 1;
    max-width: 1080px;
    margin: 0 auto;
    direction: rtl;
    text-align: center;
    font-family: var(--rd-quran);
    font-size: clamp(30px, 3vw, 44px);
    line-height: 2.1;
    word-spacing: 4px;
    color: var(--rd-heading);
}

.quran-ayah { display: inline; }

.ayah-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    margin: 0 8px;
    border: 1.5px solid var(--rd-gold);
    border-radius: 50%;
    color: var(--rd-gold);
    font-family: var(--rd-quran);
    font-size: 18px;
    line-height: 1;
    vertical-align: middle;
}

.reference {
    position: relative;
    z-index: 1;
    margin-top: 20px;
    padding-top: 16px;
    text-align: center;
    color: var(--rd-gold);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
}

.reference::before {
    content: "";
    display: block;
    width: 70px;
    height: 1px;
    margin: 0 auto 14px;
    background: linear-gradient(90deg, transparent, var(--rd-gold), transparent);
}


/* =====================================================
   TAFSIR CARD
===================================================== */
.tafsir-page .tafsir-card {
    position: relative;
    overflow: hidden;
    margin-bottom: 20px;
    padding: 32px 40px 28px;
    border-radius: 26px;
    color: var(--rd-text);
    background: var(--rd-surface);
    border: 1px solid var(--rd-border);
    box-shadow: var(--rd-shadow);
}

html.dark-mode .tafsir-page .tafsir-card {
    background: var(--rd-surface) !important;
    border-color: var(--rd-border) !important;
    color: var(--rd-text);
}

.tafsir-card::before {
    content: "";
    position: absolute;
    left: 0; right: 0; top: 0;
    height: 3px;
    background: linear-gradient(90deg, var(--rd-green-700), var(--rd-gold), var(--rd-green-700));
}

/* watermark */
.tafsir-card::after {
    content: "۞";
    position: absolute;
    right: 24px;
    bottom: -46px;
    font-family: var(--rd-quran);
    font-size: 160px;
    color: rgba(201,154,69,.07);
    pointer-events: none;
}

.tafsir-heading {
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 20px;
}

.tafsir-page .tafsir-heading h2 {
    margin: 0;
    font-family: var(--rd-serif);
    font-size: clamp(22px, 2.4vw, 28px);
    font-weight: 500;
    line-height: 1.2;
    color: var(--rd-heading);
}

.layer-badge {
    padding: 7px 14px;
    border-radius: 999px;
    background: rgba(11,99,87,.10);
    border: 1px solid rgba(11,99,87,.16);
    color: var(--rd-green-700);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .14em;
    text-transform: uppercase;
    white-space: nowrap;
}

html.dark-mode .layer-badge {
    background: rgba(227,195,125,.1);
    border-color: rgba(227,195,125,.22);
    color: var(--rd-gold-light);
}


/* tafsir text */
.tafsir-page .tafsir-text {
    position: relative;
    z-index: 2;
    max-width: 950px;
    padding-left: 22px;
    border-left: 3px solid var(--rd-gold);
    font-family: var(--rd-serif);
    font-size: 19px;
    line-height: 1.85;
    color: var(--rd-text);
}

.tafsir-page .tafsir-text p {
    margin: 0 0 14px;
    color: var(--rd-text);
}

.tafsir-page .tafsir-text p:last-child { margin-bottom: 0; }

.tafsir-page .tafsir-text h1,
.tafsir-page .tafsir-text h2,
.tafsir-page .tafsir-text h3,
.tafsir-page .tafsir-text h4 {
    margin: 22px 0 10px;
    font-family: var(--rd-serif);
    font-weight: 600;
    color: var(--rd-heading);
}

.tafsir-text ul, .tafsir-text ol { margin: 0 0 14px; padding-left: 22px; }
.tafsir-text li { margin-bottom: 6px; }
.tafsir-text strong { font-weight: 700; color: inherit; }


/* AI voice */
.tafsir-audio { position: relative; z-index: 3; margin-top: 22px; }

.tafsir-page .audio-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 20px;
    border-radius: 999px;
    border: 1px solid rgba(11,99,87,.25);
    background: rgba(11,99,87,.08);
    color: var(--rd-green-700);
    font-family: var(--rd-sans);
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease;
}

html.dark-mode .tafsir-page .audio-pill {
    background: rgba(255,255,255,.05);
    border-color: rgba(227,195,125,.25);
    color: var(--rd-gold-light);
}

.tafsir-page .audio-pill:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,60,54,.12);
}

.tafsir-page .audio-pill.playing {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    border-color: transparent;
    color: #051a14;
    animation: rdPulse 1.8s ease-in-out infinite;
}


/* source */
.tafsir-source {
    position: relative;
    z-index: 2;
    margin-top: 22px;
    padding-top: 16px;
    border-top: 1px solid var(--rd-border);
    color: var(--rd-muted);
    font-size: 12.5px;
    line-height: 1.6;
}

.tafsir-source strong { color: var(--rd-heading); font-size: 12.5px; }


/* =====================================================
   PREVIOUS / NEXT
===================================================== */
.tafsir-progress-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-top: 6px;
}

.tafsir-page .tafsir-prev-btn,
.tafsir-page .tafsir-next-btn {
    min-height: 48px;
    padding: 0 26px;
    border-radius: 999px;
    font-family: var(--rd-sans);
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: transform .25s ease, box-shadow .25s ease, background .25s ease, opacity .25s ease;
}

.tafsir-page .tafsir-prev-btn {
    border: 1px solid var(--rd-border);
    background: var(--rd-surface);
    color: var(--rd-heading);
}

.tafsir-page .tafsir-prev-btn:not(:disabled):hover {
    transform: translateY(-2px);
    background: rgba(201,154,69,.12);
    border-color: rgba(201,154,69,.45);
}

.tafsir-page .tafsir-next-btn {
    border: 0;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14;
    box-shadow: 0 10px 24px rgba(184,138,68,.32);
}

.tafsir-page .tafsir-next-btn:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(184,138,68,.45);
}

.tafsir-page .tafsir-prev-btn:disabled { opacity: .4; cursor: not-allowed; }
.tafsir-page .tafsir-next-btn:disabled { opacity: .7; cursor: wait; }

.tafsir-next-btn.completed { background: var(--rd-gold); cursor: default; }
.tafsir-next-btn.completed:hover { transform: none; }


/* =====================================================
   EMPTY
===================================================== */
.tafsir-page .empty-state {
    padding: 56px 24px;
    text-align: center;
    border-radius: 24px;
    border: 1px dashed var(--rd-border);
    background: var(--rd-surface);
    color: var(--rd-muted);
    font-family: var(--rd-serif);
    font-size: 20px;
}


/* =====================================================
   TEXT SIZE MODE ("Aa Text" button)
===================================================== */
body.large-text .tafsir-page .quran-verse { font-size: clamp(36px, 3.6vw, 52px); }
body.large-text .tafsir-page .tafsir-text { font-size: 21.5px; }


/* =====================================================
   ORIGINAL TAFSIR WORD COLORS (kept exactly)
===================================================== */
.tafsir-text .red-text    { color: #C00000 !important; }
.tafsir-text .orange-text { color: #FF6600 !important; }
.tafsir-text .green-text  { color: #008000 !important; }
.tafsir-text .blue-text   { color: #0066FF !important; }
.tafsir-text .purple-text { color: #993366 !important; }
.tafsir-text .black-text  { color: #000000 !important; }
.tafsir-text .highlight-yellow { background-color: #FFFF00 !important; color: #000000 !important; }

/* inline colors coming from Word / HTML */
.tafsir-text span[style*="color:#000000"], .tafsir-text span[style*="color: #000000"],
.tafsir-text span[style*="color:#000"],    .tafsir-text span[style*="color: #000"]    { color: #000000 !important; }

.tafsir-text span[style*="color:#C00000"], .tafsir-text span[style*="color: #C00000"],
.tafsir-text span[style*="color:#c00000"], .tafsir-text span[style*="color: #c00000"] { color: #C00000 !important; }

.tafsir-text span[style*="color:#FF6600"], .tafsir-text span[style*="color: #FF6600"],
.tafsir-text span[style*="color:#ff6600"], .tafsir-text span[style*="color: #ff6600"] { color: #FF6600 !important; }

.tafsir-text span[style*="color:#008000"], .tafsir-text span[style*="color: #008000"] { color: #008000 !important; }

.tafsir-text span[style*="color:#0066FF"], .tafsir-text span[style*="color: #0066FF"],
.tafsir-text span[style*="color:#0066ff"], .tafsir-text span[style*="color: #0066ff"] { color: #0066FF !important; }

.tafsir-text span[style*="color:#993366"], .tafsir-text span[style*="color: #993366"] { color: #993366 !important; }

/* dark mode: same colors, brighter so they stay readable */
html.dark-mode .tafsir-text .red-text,
html.dark-mode .tafsir-text span[style*="color:#C00000"], html.dark-mode .tafsir-text span[style*="color: #C00000"],
html.dark-mode .tafsir-text span[style*="color:#c00000"], html.dark-mode .tafsir-text span[style*="color: #c00000"] { color: #ff4d4d !important; }

html.dark-mode .tafsir-text .orange-text,
html.dark-mode .tafsir-text span[style*="color:#FF6600"], html.dark-mode .tafsir-text span[style*="color: #FF6600"],
html.dark-mode .tafsir-text span[style*="color:#ff6600"], html.dark-mode .tafsir-text span[style*="color: #ff6600"] { color: #ff8a3d !important; }

html.dark-mode .tafsir-text .green-text,
html.dark-mode .tafsir-text span[style*="color:#008000"], html.dark-mode .tafsir-text span[style*="color: #008000"] { color: #4caf70 !important; }

html.dark-mode .tafsir-text .blue-text,
html.dark-mode .tafsir-text span[style*="color:#0066FF"], html.dark-mode .tafsir-text span[style*="color: #0066FF"],
html.dark-mode .tafsir-text span[style*="color:#0066ff"], html.dark-mode .tafsir-text span[style*="color: #0066ff"] { color: #4da3ff !important; }

html.dark-mode .tafsir-text .purple-text,
html.dark-mode .tafsir-text span[style*="color:#993366"], html.dark-mode .tafsir-text span[style*="color: #993366"] { color: #d47aa8 !important; }

html.dark-mode .tafsir-text .black-text,
html.dark-mode .tafsir-text span[style*="color:#000000"], html.dark-mode .tafsir-text span[style*="color: #000000"],
html.dark-mode .tafsir-text span[style*="color:#000"],    html.dark-mode .tafsir-text span[style*="color: #000"] { color: #ffffff !important; }

html.dark-mode .tafsir-text .highlight-yellow { background-color: #ffff00 !important; color: #000000 !important; }


/* =====================================================
   ANIMATIONS
===================================================== */
@keyframes rdRise {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes rdPulse {
    0%, 100% { filter: brightness(1); }
    50%      { filter: brightness(1.18); }
}

@media (prefers-reduced-motion: reduce) {
    .tafsir-page *, .tafsir-page *::before, .tafsir-page *::after {
        animation: none !important;
        transition: none !important;
    }
}


/* =====================================================
   RESPONSIVE
===================================================== */

/* laptop / small desktop */
@media (max-width: 1200px) {
    .reader-grid { grid-template-columns: 260px minmax(0, 1fr); gap: 20px; }
    .tafsir-page .verse-card { padding: 30px 30px 32px; }
    .tafsir-page .tafsir-card { padding: 28px 30px 24px; }
}

/* tablet: the side panel moves out of the way */
@media (max-width: 900px) {
    .tafsir-page { padding: 72px 16px 48px; }       /* room for the sidebar toggle */
    .reader-grid { grid-template-columns: 1fr; }
    .tafsir-page .reader-sidebar { display: none; }
    .tafsir-page .top-audio-bar { flex-direction: column; align-items: stretch; }
    .audio-bar-right { width: 100%; }
    .tafsir-page .audio-select { width: 100%; min-width: 0; }
}

/* phones */
@media (max-width: 640px) {
    .tafsir-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .tafsir-actions { width: 100%; }
    .tafsir-page .tafsir-action { flex: 1; padding: 0 10px; }

    .tafsir-page .surah-header { padding: 22px 20px; border-radius: 22px; }
    .surah-info { flex-basis: 100%; }
    .surah-number { width: 52px; height: 52px; font-size: 17px; }
    .tafsir-page .surah-listen-btn { width: 100%; }
    .layer-selector { width: 100%; }
    .tafsir-page .layer-btn { flex: 1; text-align: center; padding: 10px 8px; }

    .tafsir-page .verse-card { padding: 26px 18px 28px; border-radius: 22px; }
    .verse-card::before, .verse-card::after { width: 22px; height: 22px; }
    .verse-label { font-size: 10px; letter-spacing: .1em; }
    .bookmark-label { display: none; }
    .tafsir-page .quran-verse { font-size: 26px; line-height: 2.15; }
    .ayah-number { width: 34px; height: 34px; margin: 0 5px; font-size: 16px; }

    .tafsir-page .tafsir-card { padding: 26px 20px 22px; border-radius: 22px; }
    .tafsir-heading { flex-wrap: wrap; }
    .tafsir-page .tafsir-text { padding-left: 16px; font-size: 17px; line-height: 1.8; }
    body.large-text .tafsir-page .tafsir-text { font-size: 19px; }
    .tafsir-page .audio-pill { width: 100%; justify-content: center; }

    .tafsir-progress-wrap { flex-direction: column-reverse; }
    .tafsir-page .tafsir-prev-btn,
    .tafsir-page .tafsir-next-btn { width: 100%; }
}

/* very small phones */
@media (max-width: 380px) {
    .tafsir-page .tafsir-text { font-size: 16px; }
    .tafsir-page .quran-verse { font-size: 23px; }
}


/* =====================================================
   WHEN THE MAIN SIDEBAR IS CLOSED (X) → FULL WIDTH
   (desktop / large tablets only)
===================================================== */
@media (min-width: 901px) {

    /* the page itself uses the whole width */
    body.sidebar-collapsed .hidayah-main {
        margin-left: 0;
        width: 100%;
    }

    body.sidebar-collapsed .tafsir-page .tafsir-header,
    body.sidebar-collapsed .tafsir-page .top-audio-bar,
    body.sidebar-collapsed .tafsir-page .reader-grid {
        max-width: none;
    }

    /* one column: Color Meaning on top, reading area below */
    body.sidebar-collapsed .tafsir-page .reader-grid {
        grid-template-columns: 1fr;
    }

    /* Color Meaning panel becomes a wide bar */
    body.sidebar-collapsed .tafsir-page .reader-sidebar {
        position: static;
        max-height: none;
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(0, 1fr);
        column-gap: 32px;
        align-items: start;
        padding: 26px 30px;
    }

    body.sidebar-collapsed .reader-logo {
        grid-column: 1 / -1;
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: left;
        padding: 0 0 18px;
    }

    body.sidebar-collapsed .reader-logo-symbol { margin: 0; }

    body.sidebar-collapsed .reader-nav-title,
    body.sidebar-collapsed .reader-nav {
        grid-column: 1;
    }

    /* "Explained Surahs" sits on the right, list in columns */
    body.sidebar-collapsed .reader-note {
        grid-column: 2;
        grid-row: 2 / span 2;
        margin-top: 0;
    }

    body.sidebar-collapsed .reader-note-text {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 2px 10px;
        max-height: 260px;
    }
}
</style>

<div class="tafsir-page">

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <header class="tafsir-header">

        <div class="tafsir-breadcrumb">

            <span class="separator">✦</span>

            <strong>Tafsir Reader</strong>

        </div>

        <div class="tafsir-actions">

    <button
        class="tafsir-action"
        type="button"
        onclick="document.body.classList.toggle('large-text')"
    >
        Aa&nbsp; Text
    </button>

    <button
        class="tafsir-action theme-toggle"
        type="button"
        id="themeToggle"
        aria-label="Toggle dark mode"
    >
        <span id="themeIcon">☾</span>
        <span id="themeText">Dark Mode</span>
    </button>

</div>

    </header>

<script>
(function () {
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const themeText = document.getElementById('themeText');

    function applyTheme(isDark) {
        document.documentElement.classList.toggle('dark-mode', isDark);
        document.body.classList.toggle('dark-mode', isDark);

        if (themeIcon) {
            themeIcon.textContent = isDark ? '☀' : '☾';
        }

        if (themeText) {
            themeText.textContent = isDark
                ? 'Light Mode'
                : 'Dark Mode';
        }

        if (themeToggle) {
            themeToggle.setAttribute(
                'aria-label',
                isDark
                    ? 'Switch to light mode'
                    : 'Switch to dark mode'
            );
        }
    }

    // تحميل الحالة المحفوظة
    const savedTheme = localStorage.getItem('tafsir-theme');

    applyTheme(savedTheme === 'dark');

    // زر التبديل
    if (themeToggle) {
        themeToggle.addEventListener('click', function () {

            const isDark =
                !document.documentElement.classList.contains('dark-mode');

            applyTheme(isDark);

            localStorage.setItem(
                'tafsir-theme',
                isDark ? 'dark' : 'light'
            );
        });
    }
})();
</script>
    {{-- =====================================================
         GLOBAL RECITER
    ====================================================== --}}

    <div class="top-audio-bar">

        <div class="audio-bar-left">

            <div class="audio-bar-icon">
                🔊
            </div>

            <div>

                <div class="audio-bar-title">
                    Quran Reciter
                </div>

                <div class="audio-bar-subtitle">
                    Choose your preferred reciter
                </div>

            </div>

        </div>


        <div class="audio-bar-right">

            <select
                class="audio-select"
                id="globalReciter"
            >

                <option value="Alafasy_128kbps">
                    Mishary Rashid Alafasy
                </option>

                <option value="Abdul_Basit_Murattal_64kbps">
                    Abdul Basit
                </option>

                <option value="MaherAlMuaiqly128kbps">
                    Maher Al-Muaiqly
                </option>

               <option value="Ghamadi">
    Saad Al-Ghamdi
</option>

<option value="AbdullahBasfar">
    Abdullah Basfar
</option>

<option value="AbuBakrAshShaatree">
    Abu Bakr Ash-Shatree
</option>

<option value="AhmedAlAjmi">
    Ahmed Al-Ajmi
</option>

<option value="FaresAbbad">
    Fares Abbad
</option>

<option value="Sudais">
    Abdul Rahman Al-Sudais
</option>

<option value="HaniRifai">
    Hani Al-Rifai
</option>

<option value="Hudhaify">
    Ali Al-Hudhaify
</option>

<option value="NasserAlqatami">
    Nasser Al-Qatami
</option>

<option value="MuhammadAyyoub">
    Muhammad Ayyoub
</option>

            </select>

        </div>

    </div>


    {{-- =====================================================
         READER GRID
    ====================================================== --}}

    <div class="reader-grid">


        {{-- =================================================
             SIDEBAR
        ================================================== --}}

        <aside class="reader-sidebar">

            <div class="reader-logo">

                <div class="reader-logo-symbol">
                    ✦
                </div>


                <div class="reader-logo-subtitle">
                    Towards Qur'anic Meditation
                </div>

            </div>


            <div class="reader-nav-title">
                Reader
            </div>


            <nav class="reader-nav">

                 {{-- Color Meaning --}}
        @include('components.tafsir-color-meaning')


              

            </nav>


            <div class="reader-note">

                <div class="reader-note-label">
                    Available Tafsirs
                </div>

                <div class="reader-note-title">
                    Explained Surahs
                </div>


                <div class="reader-note-text">

                    @forelse ($surahs as $surahOption)

                        <div style="margin-top: 4px;">

                            <a
                                href="{{ route('tafsir.reader.show', $surahOption) }}"
                                style="color: rgba(255,255,255,.78);"
                            >

                                {{ $surahOption->number }}.
                                {{ $surahOption->name_en }}

                            </a>

                        </div>

                    @empty

                        No tafsir available yet.

                    @endforelse

                </div>

            </div>

        </aside>


        {{-- =================================================
             MAIN
        ================================================== --}}

        <main class="reader-main">

            @if (!$selectedSurah)

                <div class="empty-state">
                    No interpreted surah is available yet.
                </div>

            @else


                {{-- =========================================
                     SURAH HEADER
                ========================================== --}}

                <section class="surah-header">

                    <div class="surah-info">

                        <div class="surah-number">
                            {{ $selectedSurah->number }}
                        </div>


                        <div>

                            <div class="surah-title">
                                {{ $selectedSurah->name_en }}
                            </div>

                            <div class="surah-meta">

                                {{ $selectedSurah->name_ar }}

                                <span style="color:var(--gold);">
                                    ·
                                </span>

                                {{ count($items) }}

                                explanation blocks

                            </div>

                        </div>

                    </div>


                    {{-- SURAH LISTEN --}}

                    <button
                        type="button"
                        class="surah-listen-btn"
                        id="surahListenBtn"
                    >
                        ▶ Listen Surah
                    </button>


                    {{-- LAYER SELECTOR --}}

                    <div class="layer-selector">

                        @foreach (['beginner', 'advanced'] as $level)

                            @php
                                $isActive =
                                    $selectedLevel === $level;
                            @endphp

                            <a
                                href="{{ route('tafsir.reader.show', [
                                    'surah' => $selectedSurah,
                                    'level' => $level
                                ]) }}"
                                class="layer-btn {{ $isActive ? 'active' : '' }}"
                            >

                                {{ $level === 'beginner'
                                    ? 'Beginner'
                                    : 'Advanced'
                                }}

                            </a>

                        @endforeach

                    </div>

                </section>


                {{-- =================================================
                     AUDIO
                ================================================== --}}

                <audio
                    id="quranAudio"
                    class="quran-audio"
                    preload="none"
                ></audio>


                {{-- =================================================
                     TAFSIR ITEMS
                ================================================== --}}

                @php

                    /*
                    |--------------------------------------------------------------------------
                    | ترتيب التفسير حسب رقم الآية
                    |--------------------------------------------------------------------------
                    */

                    $items = $items
                        ->sortBy(function ($item) {
                            return $item->startVerse?->verse_number ?? PHP_INT_MAX;
                        })
                        ->values();


                    /*
                    |--------------------------------------------------------------------------
                    | آخر تقدم محفوظ للمستخدم في قاعدة البيانات
                    |--------------------------------------------------------------------------
                    |
                    | verse_id هنا هو ID الحقيقي من جدول verses
                    |
                    */

                    $savedReadingProgress = null;

                    if (auth()->check()) {

                        $savedReadingProgress =
                            \App\Models\ReadingProgress::where(
                                'user_id',
                                auth()->id()
                            )
                            ->where(
                                'surah_id',
                                $selectedSurah->id
                            )
                            ->first();

                    }

                    $savedVerseId =
                        $savedReadingProgress?->verse_id;

                @endphp


                <div
                    id="tafsirProgressStatus"
                    class="tafsir-progress-status"
                ></div>


                @forelse ($items as $item)

                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | أرقام الآيات
                        |--------------------------------------------------------------------------
                        */

                        $start =
                            $item->startVerse?->verse_number ?? 1;

                        $end =
                            $item->endVerse?->verse_number ?? $start;


                        /*
                        |--------------------------------------------------------------------------
                        | IDs الحقيقية للآيات
                        |--------------------------------------------------------------------------
                        */

                        $startVerseId =
                            $item->startVerse?->id;

                        $endVerseId =
                            $item->endVerse?->id;


                        /*
                        |--------------------------------------------------------------------------
                        | الآيات الموجودة داخل نطاق التفسير
                        |--------------------------------------------------------------------------
                        */

                        $verses =
                            \App\Models\Verse::where(
                                'surah_id',
                                $selectedSurah->id
                            )
                            ->whereBetween(
                                'verse_number',
                                [$start, $end]
                            )
                            ->orderBy('verse_number')
                            ->get();


                        /*
                        |--------------------------------------------------------------------------
                        | نص التفسير
                        |--------------------------------------------------------------------------
                        */

                      $rawText = (string) ($item->text ?? '');

$bodyText = $rawText;

if (
    preg_match(
        '/##\s*(Beginner|Advanced)\s*Explanation\s*(.*)/is',
        $rawText,
        $match
    )
) {
    $bodyText = trim($match[2]);
}

if ($bodyText === '') {
    $bodyText = trim($rawText);
}
                        /*
                        |--------------------------------------------------------------------------
                        | Bookmark
                        |--------------------------------------------------------------------------
                        */

$isBookmarked = Bookmark::where('user_id', auth()->id())
    ->where('start_verse_id', $startVerseId)
    ->where('end_verse_id', $endVerseId)
    ->exists();

                        /*
                        |--------------------------------------------------------------------------
                        | التفسير التالي
                        |--------------------------------------------------------------------------
                        */

                        $nextItem =
                            $items->get($loop->index + 1);


                        $nextVerseId =
                            $nextItem?->startVerse?->id;

                    @endphp

<div
    class="tafsir-step {{ $loop->first ? 'active' : '' }}"
    data-step-index="{{ $loop->index }}"
    data-start-verse-id="{{ $startVerseId ?? '' }}"
    data-end-verse-id="{{ $endVerseId ?? '' }}"
>

 <x-tafsir-videos :tafsir="$item" /> 

    {{-- =====================================
         VERSE CARD
    ====================================== --}}

    <article
        class="verse-card"
        data-start="{{ $start }}"
        data-end="{{ $end }}"
        data-start-verse-id="{{ $startVerseId ?? '' }}"
    >

        <div class="verse-top">

            <div class="verse-label">

                <span class="verse-dot"></span>

                {{ $selectedSurah->name_en }}

                ·

                {{ $start }}

                @if ($start !== $end)
                    –{{ $end }}
                @endif

            </div>


            {{-- BOOKMARK --}}

            @auth

                <div class="bookmark-wrap">

                    <form
                        action="{{ route('bookmarks.store') }}"
                        method="POST"
                        class="bookmark-form"
                    >

                        @csrf

                        <input
                            type="hidden"
                            name="start_verse_id"
                            value="{{ $startVerseId }}"
                        >

                        <input
                            type="hidden"
                            name="end_verse_id"
                            value="{{ $endVerseId }}"
                        >

                        <button
                            type="submit"
                            class="bookmark-btn {{ $isBookmarked ? 'bookmarked' : '' }}"
                            aria-label="{{ $isBookmarked ? 'Remove bookmark' : 'Save verse' }}"
                        >

                            <span class="bookmark-icon">

                                {{ $isBookmarked
                                    ? '♥'
                                    : '♡'
                                }}

                            </span>

                        </button>

                    </form>

                    <span class="bookmark-label">

                        {{ $isBookmarked
                            ? 'Saved'
                            : 'Save verse'
                        }}

                    </span>

                </div>

            @endauth

        </div>


        {{-- QURAN TEXT --}}

        <div class="quran-verse">

            @foreach ($verses as $verse)

                <span class="quran-ayah">

                    {{ $verse->arabic_text }}

                    <span class="ayah-number">
                        {{ $verse->verse_number }}
                    </span>

                </span>

            @endforeach

        </div>


        {{-- REFERENCE --}}

        <div class="reference">

            {{ $selectedSurah->name_en }}

            ·

            {{ $selectedSurah->number }}:

            {{ $start }}

            @if ($start !== $end)
                -{{ $end }}
            @endif

        </div>

    </article>


    {{-- =====================================
         TAFSIR CARD
    ====================================== --}}

    <article class="tafsir-card">

        <div class="tafsir-heading">

            <h2>

                {{ ucfirst($item->level) }}
                Explanation

            </h2>

            <span class="layer-badge">

                {{ ucfirst($item->level) }}

            </span>

        </div>


        <div class="tafsir-text">

            {!! $bodyText !!}

        </div>


        {{-- AI AUDIO --}}

        <div class="tafsir-audio">

            <button
                type="button"
                class="audio-pill primary ai-audio-btn"
                data-ai-text="{{ e(strip_tags($bodyText)) }}"
            >

                🔊 AI Voice Explanation

            </button>

        </div>


        <div class="tafsir-source">

            <strong>
                Source:
            </strong>

            Tafsir reference for
            {{ $selectedSurah->name_en }}

        </div>

    </article>


    {{-- =====================================
         NEXT TAFSIR / SAVE PROGRESS
    ====================================== --}}
<div class="tafsir-progress-wrap">

    <button
        type="button"
        class="tafsir-prev-btn"
        {{ $loop->first ? 'disabled' : '' }}
    >
        ← Previous Tafsir
    </button>

    <button
        type="button"
        class="tafsir-next-btn"
        data-next-index="{{ $loop->index + 1 }}"
        data-next-verse-id="{{ $nextVerseId ?? '' }}"
        data-current-verse-id="{{ $startVerseId ?? '' }}"
        data-last="{{ $loop->last ? 'true' : 'false' }}"
    >
        {{ $loop->last ? '✓ Finish Tafsir' : 'Next Tafsir →' }}
    </button>

</div>


</div>
                @empty

                    <div class="empty-state">

                        No tafsir content is available
                        for this level in
                        {{ $selectedSurah->name_en }}.

                    </div>

                @endforelse

            @endif

        </main>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

/* =====================================================
   DARK MODE
===================================================== */

const themeToggle = document.getElementById('themeToggle');
const themeIcon = document.getElementById('themeIcon');
const themeText = document.getElementById('themeText');

function applyTheme(theme) {

    const isDark = theme === 'dark';

    document.documentElement.classList.toggle('dark-mode', isDark);
    document.body.classList.toggle('dark-mode', isDark);

    if (themeIcon) {
        themeIcon.textContent = isDark ? '☀' : '☾';
    }

    if (themeText) {
        themeText.textContent = isDark
            ? 'Light Mode'
            : 'Dark Mode';
    }

    if (themeToggle) {
        themeToggle.setAttribute(
            'aria-label',
            isDark
                ? 'Switch to light mode'
                : 'Switch to dark mode'
        );
    }

    localStorage.setItem('tafsir-theme', theme);
}


/* LOAD SAVED THEME */

const savedTheme =
    localStorage.getItem('tafsir-theme') || 'light';

applyTheme(savedTheme);


/* TOGGLE */

if (themeToggle) {

    themeToggle.addEventListener('click', function () {

        const isDark =
            document.documentElement.classList.contains('dark-mode');

        applyTheme(isDark ? 'light' : 'dark');

    });

}
    /* =====================================================
       ELEMENTS
    ===================================================== */

    /* =====================================================
       ELEMENTS
    ===================================================== */

    const audio =
        document.getElementById('quranAudio');

    const reciter =
        document.getElementById('globalReciter');

    const surahButton =
        document.getElementById('surahListenBtn');


    /* =====================================================
       QURAN AUDIO STATE
    ===================================================== */

    let currentAudioMode = null;

    let currentVerseCard = null;

    let currentSurahIndex = 0;

    let surahPlaylist = [];


    /* =====================================================
       RECITER FOLDERS
    ===================================================== */

  const reciterFolders = {

    Alafasy_128kbps:
        'Alafasy_128kbps',

    Abdul_Basit_Murattal_64kbps:
        'Abdul_Basit_Murattal_64kbps',

    MaherAlMuaiqly128kbps:
        'MaherAlMuaiqly128kbps',

    Ghamadi:
        'Ghamadi_40kbps',

    AbdullahBasfar:
        'Abdullah_Basfar_192kbps',

    AbuBakrAshShaatree:
        'Abu_Bakr_Ash-Shaatree_128kbps',

    AhmedAlAjmi:
        'Ahmed_ibn_Ali_al-Ajamy_128kbps_ketaballah.net',

    FaresAbbad:
        'Fares_Abbad_64kbps',

    Sudais:
        'Abdurrahmaan_As-Sudais_192kbps',

    HaniRifai:
        'Hani_Rifai_192kbps',

    Hudhaify:
        'Hudhaify_128kbps',

    NasserAlqatami:
        'Nasser_Alqatami_128kbps',

    MuhammadAyyoub:
        'Muhammad_Ayyoub_128kbps'
};

    /* =====================================================
       BUILD VERSE PLAYLIST
    ===================================================== */

    function buildVersePlaylist(card) {

        const surah =
            String({{ $selectedSurah->number ?? 1 }})
            .padStart(3, '0');

        const start =
            parseInt(card.dataset.start);

        const end =
            parseInt(card.dataset.end);

        const folder =
            reciterFolders[reciter.value];

        const playlist = [];

        for (
            let verse = start;
            verse <= end;
            verse++
        ) {

            const verseNumber =
                String(verse).padStart(3, '0');

            const url =
                `https://everyayah.com/data/${folder}/${surah}${verseNumber}.mp3`;

            playlist.push(url);

        }

        return playlist;
    }


    /* =====================================================
       RESET QURAN BUTTONS
    ===================================================== */

    function resetQuranButtons() {

        if (surahButton) {

            surahButton.innerHTML =
                '▶ Listen Surah';

            surahButton.classList.remove(
                'playing'
            );

        }

        document
            .querySelectorAll('.verse-inline-listen')
            .forEach(function (button) {

                button.innerHTML =
                    '▶ Listen';

                button.classList.remove(
                    'playing'
                );

            });

    }


    /* =====================================================
       STOP QURAN
    ===================================================== */

    function stopQuran() {

        if (!audio) {
            return;
        }

        audio.pause();

        audio.currentTime = 0;

        audio.removeAttribute('src');

        audio.load();

        currentAudioMode = null;

        currentVerseCard = null;

        currentSurahIndex = 0;

        surahPlaylist = [];

        resetQuranButtons();

    }


    /* =====================================================
       PLAY NEXT
    ===================================================== */

    function playNextVerse() {

        if (
            currentAudioMode !== 'surah' &&
            currentAudioMode !== 'verse'
        ) {
            return;
        }

        if (
            currentSurahIndex >=
            surahPlaylist.length
        ) {

            stopQuran();

            return;
        }

        const url =
            surahPlaylist[currentSurahIndex];

        audio.src = url;

        audio.load();

        audio.play()
            .then(function () {

                if (
                    currentAudioMode === 'surah'
                ) {

                    surahButton.innerHTML =
                        '⏸ Pause Surah';

                    surahButton.classList.add(
                        'playing'
                    );

                }
                else {

                    const activeButton =
                        currentVerseCard
                            ?.querySelector(
                                '.verse-inline-listen'
                            );

                    if (activeButton) {

                        activeButton.innerHTML =
                            '⏸ Pause';

                        activeButton.classList.add(
                            'playing'
                        );

                    }

                }

            })
            .catch(function (error) {

                console.error(
                    'Quran audio error:',
                    error,
                    url
                );

                alert(
                    'Unable to play Quran audio. Please check your internet connection.'
                );

                stopQuran();

            });

    }


    /* =====================================================
       AUDIO ENDED
    ===================================================== */

    if (audio) {

        audio.addEventListener(
            'ended',
            function () {

                currentSurahIndex++;

                playNextVerse();

            }
        );


        audio.addEventListener(
            'error',
            function () {

                console.error(
                    'Audio file failed:',
                    audio.src
                );

            }
        );

    }


    /* =====================================================
       SURAH LISTEN
    ===================================================== */

    if (surahButton) {

        surahButton.addEventListener(
            'click',
            function () {

                stopAiBeforeQuran();


                /* PAUSE */

                if (
                    currentAudioMode === 'surah' &&
                    !audio.paused
                ) {

                    audio.pause();

                    surahButton.innerHTML =
                        '▶ Resume Surah';

                    return;

                }


                /* RESUME */

                if (
                    currentAudioMode === 'surah' &&
                    audio.paused &&
                    audio.src
                ) {

                    audio.play()
                        .then(function () {

                            surahButton.innerHTML =
                                '⏸ Pause Surah';

                        });

                    return;

                }


                /* STOP OLD */

                if (
                    currentAudioMode !== null
                ) {

                    stopQuran();

                }


                const surahNumber =
                    String(
                        {{ $selectedSurah->number ?? 1 }}
                    ).padStart(3, '0');


                const totalVerses =
                    {{ $selectedSurah->verses->count() ?? 0 }};


                const folder =
                    reciterFolders[reciter.value];


                surahPlaylist = [];


                for (
                    let verse = 1;
                    verse <= totalVerses;
                    verse++
                ) {

                    const verseNumber =
                        String(verse).padStart(3, '0');

                    surahPlaylist.push(
                        `https://everyayah.com/data/${folder}/${surahNumber}${verseNumber}.mp3`
                    );

                }


                if (
                    surahPlaylist.length === 0
                ) {

                    alert(
                        'No verses are available for this Surah.'
                    );

                    return;

                }


                currentAudioMode =
                    'surah';

                currentSurahIndex =
                    0;

                currentVerseCard =
                    null;

                playNextVerse();

            }
        );

    }


    /* =====================================================
       VERSE LISTEN
    ===================================================== */

    document
        .querySelectorAll('.verse-card')
        .forEach(function (card) {

            const button =
                document.createElement('button');

            button.type =
                'button';

            button.className =
                'surah-listen-btn verse-inline-listen';

            button.innerHTML =
                '▶ Listen';

            const reference =
                card.querySelector('.reference');

            if (reference) {

                reference.insertAdjacentElement(
                    'afterend',
                    button
                );

            }

            button.addEventListener(
                'click',
                function () {

                    stopAiBeforeQuran();


                    /* PAUSE */

                    if (
                        currentAudioMode === 'verse' &&
                        currentVerseCard === card &&
                        !audio.paused
                    ) {

                        audio.pause();

                        button.innerHTML =
                            '▶ Resume';

                        return;

                    }


                    /* RESUME */

                    if (
                        currentAudioMode === 'verse' &&
                        currentVerseCard === card &&
                        audio.paused &&
                        audio.src
                    ) {

                        audio.play()
                            .then(function () {

                                button.innerHTML =
                                    '⏸ Pause';

                            });

                        return;

                    }


                    /* STOP PREVIOUS */

                    if (
                        currentAudioMode !== null
                    ) {

                        stopQuran();

                    }


                    surahPlaylist =
                        buildVersePlaylist(card);


                    if (
                        surahPlaylist.length === 0
                    ) {

                        return;

                    }


                    currentAudioMode =
                        'verse';

                    currentVerseCard =
                        card;

                    currentSurahIndex =
                        0;

                    playNextVerse();

                }
            );

        });


    /* =====================================================
       RECITER CHANGE
    ===================================================== */

    if (reciter) {

        reciter.addEventListener(
            'change',
            function () {

                if (
                    currentAudioMode !== null
                ) {

                    stopQuran();

                }

            }
        );

    }


    /* =====================================================
       AI VOICE
    ===================================================== */

    let activeAiButton = null;

    let activeUtterance = null;


    /* =====================================================
       RESET AI BUTTON
    ===================================================== */

    function resetAiButton(button) {

        if (!button) {
            return;
        }

        button.innerHTML =
            '🔊 AI Voice Explanation';

        button.classList.remove(
            'playing'
        );

    }


    /* =====================================================
       STOP AI COMPLETELY
    ===================================================== */

    function stopAiVoice() {

        if (
            'speechSynthesis' in window
        ) {

            window.speechSynthesis.cancel();

        }

        if (activeAiButton) {

            resetAiButton(
                activeAiButton
            );

        }

        activeAiButton =
            null;

        activeUtterance =
            null;

    }


    /* =====================================================
       STOP AI BEFORE QURAN
    ===================================================== */

    function stopAiBeforeQuran() {

        if (activeAiButton) {

            stopAiVoice();

        }

    }


    /* =====================================================
       AI BUTTONS
    ===================================================== */

    document
        .querySelectorAll('.ai-audio-btn')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    if (
                        !('speechSynthesis' in window)
                    ) {

                        alert(
                            'Your browser does not support AI voice playback.'
                        );

                        return;

                    }


                    const text =
                        button.dataset.aiText || '';


                    if (
                        !text.trim()
                    ) {

                        return;

                    }


                    /* SAME BUTTON = STOP */

                    if (
                        activeAiButton === button
                    ) {

                        stopAiVoice();

                        return;

                    }


                    /* STOP OTHER AI */

                    stopAiVoice();


                    /* STOP QURAN */

                    if (
                        currentAudioMode !== null
                    ) {

                        stopQuran();

                    }


                    /* CREATE SPEECH */

                    const utterance =
                        new SpeechSynthesisUtterance(
                            text
                        );


                    utterance.lang =
                        'en-US';

                    utterance.rate =
                        0.92;

                    utterance.pitch =
                        1;


                    /* START */

                    utterance.onstart =
                        function () {

                            activeAiButton =
                                button;

                            activeUtterance =
                                utterance;

                            button.innerHTML =
                                '⏹ Stop AI Explanation';

                            button.classList.add(
                                'playing'
                            );

                        };


                    /* END */

                    utterance.onend =
                        function () {

                            if (
                                activeAiButton ===
                                button
                            ) {

                                resetAiButton(
                                    button
                                );

                                activeAiButton =
                                    null;

                                activeUtterance =
                                    null;

                            }

                        };


                    /* ERROR */

                    utterance.onerror =
                        function () {

                            if (
                                activeAiButton ===
                                button
                            ) {

                                resetAiButton(
                                    button
                                );

                                activeAiButton =
                                    null;

                                activeUtterance =
                                    null;

                            }

                        };


                    activeAiButton =
                        button;

                    activeUtterance =
                        utterance;


                    window.speechSynthesis.speak(
                        utterance
                    );

                }
            );

        });


    /* =====================================================
       TAFSIR PROGRESS — DATABASE
    ===================================================== */

    const tafsirSteps =
        Array.from(
            document.querySelectorAll('.tafsir-step')
        );


    const progressStatus =
        document.getElementById(
            'tafsirProgressStatus'
        );


    /* =====================================================
       LARAVEL VALUES
    ===================================================== */

    const savedVerseId =
        @json($savedVerseId ?? null);


    const progressStoreUrl =
        @json(route('reading_progress.store'));


    const selectedSurahId =
        Number(
            @json($selectedSurah->id ?? 0)
        );


    const csrfToken =
        @json(csrf_token());


    /* =====================================================
       CURRENT PROGRESS
    ===================================================== */

    let savedProgress =
        0;


    /* =====================================================
       FIND RESUME TAFSIR
    ===================================================== */

    /*
     * savedVerseId =
     * آخر آية تم إكمالها
     *
     * مثال:
     *
     * Tafsir 1 = 1 → 3
     * Tafsir 2 = 4 → 7
     *
     * إذا savedVerseId هو ID الآية 3
     * نفتح Tafsir 2.
     */

    /* =========================================================
   FIND RESUME TAFSIR
========================================================= */

if (
    savedVerseId !== null &&
    savedVerseId !== undefined &&
    tafsirSteps.length > 0
) {

    const savedId =
        Number(savedVerseId);

    let foundIndex = 0;


    if (!Number.isNaN(savedId)) {

        for (
            let i = 0;
            i < tafsirSteps.length;
            i++
        ) {

            const step =
                tafsirSteps[i];


            const startVerseId =
                Number(
                    step.dataset.startVerseId
                );


            const endVerseId =
                Number(
                    step.dataset.endVerseId
                );


            if (
                Number.isNaN(startVerseId) ||
                Number.isNaN(endVerseId)
            ) {
                continue;
            }


            /*
             * إذا الآية المحفوظة داخل
             * هذا الـ Tafsir
             */
            if (
                savedId >= startVerseId &&
                savedId <= endVerseId
            ) {

                /*
                 * إذا أكمل هذا الـ Tafsir،
                 * افتح الـ Tafsir التالي.
                 */

                foundIndex =
                    i + 1;

                break;
            }


            /*
             * إذا وصلنا إلى Tafsir
             * يبدأ بعد الآية المحفوظة
             */

            if (
                savedId < startVerseId
            ) {

                foundIndex =
                    i;

                break;
            }

        }

    }


    /*
     * لا تتجاوز آخر Section
     */

    if (
        foundIndex >= tafsirSteps.length
    ) {

        foundIndex =
            tafsirSteps.length - 1;

    }


    if (
        foundIndex < 0
    ) {

        foundIndex = 0;

    }


    savedProgress =
        foundIndex;

}
    /* =====================================================
       VALIDATE PROGRESS
    ===================================================== */

    if (
        Number.isNaN(savedProgress) ||
        savedProgress < 0 ||
        savedProgress >= tafsirSteps.length
    ) {

        savedProgress =
            0;

    }


    /* =====================================================
       SHOW TAFSIR STEP
    ===================================================== */

   /* =========================================================
   SHOW TAFSIR STEP
========================================================= */

function showTafsirStep(index) {

    // حماية من index غير صحيح
    if (index < 0) {
        index = 0;
    }

    if (index >= tafsirSteps.length) {
        index = tafsirSteps.length - 1;
    }

    tafsirSteps.forEach((step, i) => {

        const isActive = i === index;

        if (isActive) {

            step.classList.add('active');

            step.style.setProperty('display', 'block', 'important');
            step.style.setProperty('visibility', 'visible', 'important');
            step.style.setProperty('opacity', '1', 'important');

        } else {

            step.classList.remove('active');

            step.style.setProperty('display', 'none', 'important');
            step.style.setProperty('visibility', 'hidden', 'important');
            step.style.setProperty('opacity', '0', 'important');

        }


        /*
        |--------------------------------------------------------------------------
        | Previous button
        |--------------------------------------------------------------------------
        */

        const prevBtn = step.querySelector('.tafsir-prev-btn');

        if (prevBtn) {

            prevBtn.disabled = i === 0;

        }


        /*
        |--------------------------------------------------------------------------
        | Next button
        |--------------------------------------------------------------------------
        */

        const nextBtn = step.querySelector('.tafsir-next-btn');

        if (nextBtn) {

            // مهم جدًا:
            // كل مرة ننتقل إلى Step نرجع الزر لحالته الطبيعية

            nextBtn.disabled = false;

            if (i === tafsirSteps.length - 1) {

                nextBtn.textContent = '✓ Finish Tafsir';

            } else {

                nextBtn.textContent = 'Next Tafsir →';

            }
        }

    });


    savedProgress = index;

    console.log(
        'Showing Tafsir Step:',
        index + 1,
        '/',
        tafsirSteps.length
    );
}

    /* =====================================================
       INITIAL TAFSIR DISPLAY
    ===================================================== */

    showTafsirStep(
        savedProgress
    );
const activeStep = tafsirSteps[savedProgress];

console.log(
    'ACTIVE STEP PARENT:',
    activeStep.parentElement
);

console.log(
    'PARENT DISPLAY:',
    getComputedStyle(activeStep.parentElement).display
);

console.log(
    'PARENT VISIBILITY:',
    getComputedStyle(activeStep.parentElement).visibility
);

console.log(
    'PARENT OPACITY:',
    getComputedStyle(activeStep.parentElement).opacity
);
    /* =====================================================
       SAVE READING PROGRESS
    ===================================================== */

    async function saveReadingProgress(
        verseId
    ) {

        verseId =
            Number(verseId);


        if (
            !verseId ||
            Number.isNaN(verseId)
        ) {

            console.error(
                'Invalid verse ID:',
                verseId
            );

            return false;

        }


        if (!csrfToken) {

            console.error(
                'CSRF token not found.'
            );

            return false;

        }


        if (
            !selectedSurahId ||
            Number.isNaN(selectedSurahId)
        ) {

            console.error(
                'Invalid Surah ID:',
                selectedSurahId
            );

            return false;

        }


        console.log(
            'Saving reading progress:',
            {
                url:
                    progressStoreUrl,

                surah_id:
                    selectedSurahId,

                verse_id:
                    verseId
            }
        );


        try {

            const response =
                await fetch(
                    progressStoreUrl,
                    {

                        method:
                            'POST',


                        headers: {

                            'Content-Type':
                                'application/json',

                            'Accept':
                                'application/json',

                            'X-CSRF-TOKEN':
                                csrfToken,

                            'X-Requested-With':
                                'XMLHttpRequest'

                        },


                        credentials:
                            'same-origin',


                        body:
                            JSON.stringify({

                                surah_id:
                                    selectedSurahId,

                                verse_id:
                                    verseId

                            })

                    }
                );


            const raw =
                await response.text();


            console.log(
                'Progress HTTP status:',
                response.status
            );


            console.log(
                'Progress response:',
                raw
            );


            let data =
                {};


            try {

                data =
                    raw
                        ? JSON.parse(raw)
                        : {};

            }
            catch (error) {

                console.error(
                    'Response is not JSON:',
                    raw
                );

                return false;

            }


            if (!response.ok) {

                console.error(
                    'Progress save failed:',
                    {
                        status:
                            response.status,

                        data:
                            data,

                        raw:
                            raw
                    }
                );

                return false;

            }


            if (!data.success) {

                console.error(
                    'Server rejected progress:',
                    data
                );

                return false;

            }


            console.log(
                'Reading progress saved successfully:',
                data.progress
            );


            return true;

        }
        catch (error) {

            console.error(
                'Reading progress error:',
                error
            );

            return false;

        }

    }


    /* =====================================================
       PREVIOUS + NEXT TAFSIR BUTTONS
    ===================================================== */

    tafsirSteps.forEach(
        function (step, index) {


            /* =================================================
               PREVIOUS BUTTON
            ================================================= */

            const prevButton =
                step.querySelector(
                    '.tafsir-prev-btn'
                );


            if (prevButton) {

                prevButton.addEventListener(
                    'click',
                    function () {

                        const previousIndex =
                            index - 1;


                        /*
                         * إذا كان أول تفسير
                         * لا يوجد شيء قبله.
                         */

                        if (
                            previousIndex < 0
                        ) {

                            return;

                        }


                        /*
                         * الانتقال للتفسير السابق.
                         *
                         * لا نحفظ progress في قاعدة البيانات
                         * لأن الرجوع للخلف مجرد تنقل.
                         */

                        showTafsirStep(
                            previousIndex
                        );


                        if (progressStatus) {

                            progressStatus.textContent =
                                'Moved to previous Tafsir.';

                        }

                    }
                );

            }


            /* =================================================
               NEXT BUTTON
            ================================================= */
/* =================================================
   NEXT BUTTON
================================================= */

const button =
    step.querySelector('.tafsir-next-btn')
    ||
    document.querySelector(
        `.tafsir-step[data-step-index="${index}"] .tafsir-next-btn`
    );

console.log('CURRENT STEP INDEX:', index);
console.log('CURRENT STEP:', step);
console.log('NEXT BUTTON:', button);

if (!button) {

    console.error(
        '❌ Next button not found for step:',
        index
    );

    return;
}

button.disabled = false;

/*
|--------------------------------------------------------------------------
| TEMPORARY DEBUG
|--------------------------------------------------------------------------
*/

button.disabled = false;

console.log(
    'BUTTON DISABLED AFTER:',
    button.disabled
);


/*
|--------------------------------------------------------------------------
| CLICK EVENT
|--------------------------------------------------------------------------
*/

button.addEventListener(
    'click',
    async function () {

        console.log(
            'NEXT BUTTON CLICKED'
        );


        const currentIndex =
            index;


        const currentStep =
            tafsirSteps[currentIndex];


        if (!currentStep) {

            console.error(
                'Current tafsir step not found.'
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | آخر آية في التفسير الحالي
        |--------------------------------------------------------------------------
        */

        const endVerseId =
            Number(
                currentStep.dataset.endVerseId
            );


        console.log(
            'END VERSE ID:',
            endVerseId
        );


        if (
            !endVerseId ||
            Number.isNaN(endVerseId)
        ) {

            console.error(
                'End verse ID not found for current tafsir:',
                currentStep
            );

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | منع الضغط المزدوج
        |--------------------------------------------------------------------------
        */

        const originalText =
            button.textContent;


        button.disabled =
            true;


        button.textContent =
            'Saving...';


        if (progressStatus) {

            progressStatus.textContent =
                'Saving your progress...';

        }


        /*
        |--------------------------------------------------------------------------
        | حفظ Reading Progress
        |--------------------------------------------------------------------------
        */

        console.log(
            'SAVING READING PROGRESS...',
            endVerseId
        );


        const saved =
            await saveReadingProgress(
                endVerseId
            );


        console.log(
            'READING PROGRESS RESULT:',
            saved
        );


        /*
        |--------------------------------------------------------------------------
        | إذا فشل الحفظ
        |--------------------------------------------------------------------------
        */

        if (!saved) {

            button.disabled =
                false;


            button.textContent =
                originalText;


            if (progressStatus) {

                progressStatus.textContent =
                    'Could not save your reading progress. Please try again.';

            }


            console.error(
                'READING PROGRESS SAVE FAILED'
            );


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | هل هذا آخر Tafsir؟
        |--------------------------------------------------------------------------
        */

        const isLastStep =
            currentIndex ===
            tafsirSteps.length - 1;


        console.log(
            'CURRENT INDEX:',
            currentIndex
        );

        console.log(
            'TOTAL TAFSIR STEPS:',
            tafsirSteps.length
        );

        console.log(
            'IS LAST STEP:',
            isLastStep
        );


        /*
        |--------------------------------------------------------------------------
        | Finish Tafsir
        |--------------------------------------------------------------------------
        */

        if (isLastStep) {

            button.disabled =
                false;


            button.textContent =
                '✓ Finished';


            showTafsirStep(
                currentIndex
            );


            savedProgress =
                currentIndex;


            if (progressStatus) {

                progressStatus.textContent =
                    'You have finished this Tafsir. Your reading progress has been saved.';

            }


            console.log(
                'TAFSIR FINISHED'
            );


            return;
        }


        /*
        |--------------------------------------------------------------------------
        | الانتقال إلى Tafsir التالي
        |--------------------------------------------------------------------------
        */

        const nextIndex =
            currentIndex + 1;


        console.log(
            'MOVING TO NEXT TAFSIR:',
            nextIndex
        );


        showTafsirStep(
            nextIndex
        );


        savedProgress =
            nextIndex;


        /*
        |--------------------------------------------------------------------------
        | إعادة تفعيل زر الخطوة الجديدة
        |--------------------------------------------------------------------------
        */

        const nextStep =
            tafsirSteps[nextIndex];


        if (nextStep) {

            const nextButton =
                nextStep.querySelector(
                    '.tafsir-next-btn'
                );


            if (nextButton) {

                nextButton.disabled =
                    false;


                console.log(
                    'NEXT BUTTON ENABLED'
                );

            }

        }

    }
);
});

    /* =====================================================
       BOOKMARK TOGGLE
    ===================================================== */

    document
        .querySelectorAll('.bookmark-form')
        .forEach(function (form) {

            form.addEventListener(
                'submit',
                async function (e) {

                    e.preventDefault();


                    console.log(
                        '=============================='
                    );


                    console.log(
                        'BOOKMARK CLICKED'
                    );


                    const button =
                        form.querySelector(
                            '.bookmark-btn'
                        );


                    const icon =
                        form.querySelector(
                            '.bookmark-icon'
                        );


                    const wrap =
                        form.closest(
                            '.bookmark-wrap'
                        );


                    const label =
                        wrap
                            ? wrap.querySelector(
                                '.bookmark-label'
                            )
                            : null;


                    if (!button) {

                        console.error(
                            'Bookmark error: .bookmark-btn not found'
                        );

                        return;

                    }


                    if (!icon) {

                        console.error(
                            'Bookmark error: .bookmark-icon not found'
                        );

                        return;

                    }


                    if (!wrap) {

                        console.error(
                            'Bookmark error: .bookmark-wrap not found'
                        );

                        return;

                    }


                    if (!label) {

                        console.error(
                            'Bookmark error: .bookmark-label not found'
                        );

                        return;

                    }


                    if (button.disabled) {

                        return;

                    }


                    button.disabled =
                        true;


                    try {

                        const csrfInput =
                            form.querySelector(
                                'input[name="_token"]'
                            );


                        if (!csrfInput) {

                            throw new Error(
                                'CSRF token input not found.'
                            );

                        }


                        const csrfToken =
                            csrfInput.value;


                        if (!csrfToken) {

                            throw new Error(
                                'CSRF token is empty.'
                            );

                        }


                        const response =
                            await fetch(
                                form.action,
                                {

                                    method:
                                        'POST',

                                    headers: {

                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'Accept':
                                            'application/json',

                                        'X-Requested-With':
                                            'XMLHttpRequest'

                                    },

                                    body:
                                        new FormData(
                                            form
                                        )

                                }
                            );


                        let data;


                        try {

                            data =
                                await response.json();

                        }
                        catch (jsonError) {

                            console.error(
                                'JSON ERROR:',
                                jsonError
                            );

                            throw new Error(
                                'Server did not return valid JSON.'
                            );

                        }


                        if (!response.ok) {

                            throw new Error(
                                data.message ||
                                'Bookmark request failed.'
                            );

                        }


                        if (
                            data.bookmarked === true
                        ) {

                            button.classList.add(
                                'bookmarked'
                            );


                            icon.textContent =
                                '♥';


                            label.textContent =
                             'Save verses';


                           button.setAttribute(
    'aria-label',
    'Save verses'
);

                        }
                        else if (
                            data.bookmarked === false
                        ) {

                            button.classList.remove(
                                'bookmarked'
                            );


                            icon.textContent =
                                '♡';


                            label.textContent =
                                'Save verse';


                            button.setAttribute(
                                'aria-label',
                                'Save verse'
                            );

                        }
                        else {

                            throw new Error(
                                'Invalid bookmark response from server.'
                            );

                        }

                    }
                    catch (error) {

                        console.error(
                            'Bookmark error:',
                            error
                        );

                    }
                    finally {

                        button.disabled =
                            false;

                    }

                }
            );

        });

});

</script>

@endsection