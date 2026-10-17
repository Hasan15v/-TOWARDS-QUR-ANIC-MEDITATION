{{-- =========================================================
     SIDEBAR TOGGLE (outside the sidebar on purpose)
========================================================= --}}
<button type="button" class="sidebar-toggle" id="sidebarToggle"
        aria-label="Toggle sidebar" aria-expanded="true" aria-controls="hidayahSidebar">
    <span></span>
    <span></span>
    <span></span>
</button>


{{-- =========================================================
     HIDAYAH SIDEBAR
========================================================= --}}
<aside class="hidayah-sidebar" id="hidayahSidebar">

    {{-- BRAND --}}
    <div class="sidebar-brand">

        <div class="brand-logo">
            <span class="logo-ring"></span>
            <span class="logo-star">✦</span>
        </div>

        <div class="brand-info">
            <strong class="brand-subtitle">
                TOWARDS QUR'ANIC<br>
                MEDITATION
            </strong>
            <div class="brand-line"></div>
        </div>

    </div>

{{-- NAVIGATION --}}
<div class="sidebar-section-title">NAVIGATION</div>

<nav class="sidebar-nav">

    <a href="{{ route('dashboard') }}"
       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sidebar-icon">⌂</span>
        <span>Overview</span>
    </a>

    <a href="{{ route('surahs.reader') }}"
       class="sidebar-link {{ request()->routeIs('surahs.reader', 'tafsir.reader.*') ? 'active' : '' }}">
        <span class="sidebar-icon">☷</span>
        <span>Tafsir</span>
    </a>

    <a href="{{ route('bookmarks.index') }}"
       class="sidebar-link {{ request()->routeIs('bookmarks.*') ? 'active' : '' }}">
        <span class="sidebar-icon">♡</span>
        <span>Bookmarks</span>
    </a>

    <a href="{{ route('aboutauthor.index_for_about_author') }}"
       class="sidebar-link {{ request()->routeIs('aboutauthor.*') ? 'active' : '' }}">
        <span class="sidebar-icon">✎</span>
        <span>About the Author</span>
    </a>

</nav>


{{-- ADMIN MANAGEMENT --}}
@if (auth()->user()?->role === 'admin')

    <div class="sidebar-divider"></div>

    <div class="sidebar-section-title admin-title">ADMIN MANAGEMENT</div>

    <nav class="sidebar-nav">

        <a href="{{ route('surahs.index') }}"
           class="sidebar-link {{ request()->routeIs('surahs.*') && ! request()->routeIs('surahs.reader') ? 'active' : '' }}">
            <span class="sidebar-icon">▤</span>
            <span>Surahs</span>
        </a>

        <a href="{{ route('verses.index') }}"
           class="sidebar-link {{ request()->routeIs('verses.*') ? 'active' : '' }}">
            <span class="sidebar-icon">▥</span>
            <span>Verses</span>
        </a>

        <a href="{{ route('admin.tafsir.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.tafsir.*') ? 'active' : '' }}">
            <span class="sidebar-icon">☷</span>
            <span>Tafsir management</span>
        </a>

    </nav>

@endif

    {{-- ADMIN MANAGEMENT --}}
    @if(auth()->check() && auth()->user()->is_admin)

        <div class="sidebar-divider"></div>

        <div class="sidebar-section-title admin-title">ADMIN MANAGEMENT</div>

        <nav class="sidebar-nav">

            <a href="{{ route('admin.surahs.index') }}"
               class="sidebar-link {{ request()->routeIs('admin.surahs.*') ? 'active' : '' }}">
                <span class="sidebar-icon">▤</span>
                <span>Surahs</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">▥</span>
                <span>Verses</span>
            </a>

            <a href="#" class="sidebar-link">
                <span class="sidebar-icon">☷</span>
                <span>Tafsir management</span>
            </a>

        </nav>

    @endif


   
 

</aside>


{{-- =========================================================
     SIDEBAR CSS
========================================================= --}}
<style>

:root { --sidebar-w: 270px; }


/* =====================================================
   SIDEBAR BASE
===================================================== */
.hidayah-sidebar {
    position: fixed;
    top: 0; left: 0; bottom: 0;
    width: var(--sidebar-w);
    z-index: 1000;
    padding: 28px 18px 22px;
    box-sizing: border-box;
    overflow-y: auto;
    overflow-x: hidden;
    display: flex;
    flex-direction: column;
    color: #fff;
    font-family: Inter, Arial, sans-serif;
    background:
        url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.05' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E"),
        radial-gradient(circle at 25% 5%, rgba(227,195,125,.09), transparent 26%),
        radial-gradient(circle at 100% 75%, rgba(11,99,87,.24), transparent 34%),
        linear-gradient(180deg, #073c35 0%, #04352f 45%, #022c28 100%);
    border-right: 1px solid rgba(227,195,125,.10);
    box-shadow: 10px 0 40px rgba(0,0,0,.10);
    transition: transform .3s ease;
    scrollbar-width: thin;
    scrollbar-color: rgba(227,195,125,.25) transparent;
}

.hidayah-sidebar.sidebar-hidden {
    transform: translateX(-100%);
    box-shadow: none;
}

.hidayah-sidebar::before {
    content: "";
    position: absolute;
    width: 220px; height: 220px;
    top: 200px; left: -130px;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.08);
    box-shadow: 0 0 0 30px rgba(227,195,125,.025), 0 0 0 60px rgba(227,195,125,.015);
    pointer-events: none;
}

.hidayah-sidebar::-webkit-scrollbar { width: 6px; }
.hidayah-sidebar::-webkit-scrollbar-thumb {
    background: rgba(227,195,125,.22);
    border-radius: 10px;
}


/* =====================================================
   TOGGLE BUTTON
===================================================== */
.sidebar-toggle {
    position: fixed;
    top: 16px;
    left: calc(var(--sidebar-w) + 12px);
    width: 42px;
    height: 42px;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    border: 1px solid rgba(227,195,125,.38);
    border-radius: 13px;
    background: rgba(7,60,53,.94);
    -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
    box-shadow: 0 8px 22px rgba(2,44,40,.28);
    cursor: pointer;
    z-index: 1100;
    transition: left .3s ease, background .3s ease, border-color .3s ease, box-shadow .3s ease, transform .25s ease;
}

.sidebar-toggle:hover {
    background: #0b5147;
    border-color: rgba(227,195,125,.75);
    box-shadow: 0 10px 26px rgba(2,44,40,.36), 0 0 0 4px rgba(227,195,125,.12);
    transform: translateY(-1px);
}

.sidebar-toggle:focus-visible {
    outline: 2px solid #e3c37d;
    outline-offset: 3px;
}

.sidebar-toggle.sidebar-collapsed { left: 18px; }

.sidebar-toggle span {
    width: 18px;
    height: 2px;
    border-radius: 2px;
    background: linear-gradient(90deg, #e3c37d, #c99a45);
    transition: transform .3s ease, opacity .3s ease, width .3s ease;
}

/* hamburger when closed: shorter middle line */
.sidebar-toggle[aria-expanded="false"] span:nth-child(2) { width: 12px; align-self: flex-start; margin-left: 12px; }

/* X when open */
.sidebar-toggle[aria-expanded="true"] span:first-child { transform: translateY(7px) rotate(45deg); }
.sidebar-toggle[aria-expanded="true"] span:nth-child(2) { opacity: 0; }
.sidebar-toggle[aria-expanded="true"] span:last-child { transform: translateY(-7px) rotate(-45deg); }


/* =====================================================
   PAGE REFLOW (content follows the sidebar)
   "body" in the selector makes these win over the layout rules
===================================================== */
body .hidayah-main {
    margin-left: var(--sidebar-w);
    width: calc(100% - var(--sidebar-w));
    padding-top: 28px;                 /* keeps content clear of the toggle button */
    transition: margin-left .3s ease, width .3s ease, background-color .3s ease, color .3s ease;
}

body.sidebar-collapsed .hidayah-main {
    margin-left: 0;
    width: 100%;
}

/* kept for layouts that still use .main-content */
.main-content {
    margin-left: var(--sidebar-w);
    transition: margin-left .3s ease;
}

body.sidebar-collapsed .main-content { margin-left: 0; }


/* =====================================================
   BRAND
===================================================== */
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 6px 10px 26px;
    margin-bottom: 26px;
    border-bottom: 1px solid rgba(255,255,255,.07);
    animation: brandEnter .9s ease both;
}

.brand-logo {
    position: relative;
    width: 48px;
    height: 48px;
    min-width: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(227,195,125,.5);
    background: radial-gradient(circle, rgba(227,195,125,.18), rgba(227,195,125,.04) 58%, transparent 74%);
    box-shadow: 0 0 22px rgba(227,195,125,.10), inset 0 0 14px rgba(227,195,125,.06);
    animation: logoFloat 4s ease-in-out infinite;
}

.logo-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.16);
    animation: ringPulse 3.5s ease-in-out infinite;
}

.logo-star {
    position: relative;
    z-index: 2;
    color: #e3c37d;
    font-size: 23px;
    line-height: 1;
    text-shadow: 0 0 10px rgba(227,195,125,.45), 0 0 22px rgba(227,195,125,.16);
    animation: starGlow 3s ease-in-out infinite;
}

.brand-info {
    display: flex;
    flex-direction: column;
    justify-content: center;
    min-width: 0;
}

.brand-subtitle {
    display: block;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: 11px;
    line-height: 1.7;
    letter-spacing: .16em;
    font-weight: 500;
    color: #f8f2e8;
    white-space: nowrap;
    text-shadow: 0 1px 10px rgba(255,255,255,.05);
    animation: textReveal 1.2s ease .2s both;
}

.brand-line {
    width: 55px;
    height: 1px;
    margin-top: 8px;
    background: linear-gradient(90deg, #e3c37d, rgba(227,195,125,.35), transparent);
    transform-origin: left;
    animation: lineReveal 1.1s ease .55s both;
}

.sidebar-brand:hover .brand-logo {
    border-color: rgba(227,195,125,.9);
    box-shadow: 0 0 28px rgba(227,195,125,.22), inset 0 0 16px rgba(227,195,125,.1);
    transition: all .35s ease;
}

.sidebar-brand:hover .logo-star {
    color: #f0d99b;
    text-shadow: 0 0 14px rgba(227,195,125,.7), 0 0 28px rgba(227,195,125,.22);
    transition: all .35s ease;
}

.sidebar-brand:hover .brand-line {
    width: 78px;
    transition: width .4s ease;
}


/* =====================================================
   SECTION TITLES
===================================================== */
.sidebar-section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0 10px 12px;
    font-size: 10.5px;
    line-height: 1;
    font-weight: 600;
    letter-spacing: .2em;
    color: rgba(255,255,255,.38);
    text-transform: uppercase;
}

.sidebar-section-title::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, rgba(255,255,255,.12), transparent);
}

.admin-title {
    color: #e3c37d;
    font-weight: 700;
    letter-spacing: .16em;
}

.admin-title::after { background: linear-gradient(90deg, rgba(227,195,125,.35), transparent); }


/* =====================================================
   NAV LINKS
===================================================== */
.sidebar-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sidebar-link {
    position: relative;
    overflow: hidden;
    min-height: 50px;
    padding: 8px 12px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 13px;
    color: rgba(255,255,255,.66);
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    transition: color .3s ease, background .3s ease, transform .3s ease, box-shadow .3s ease;
}

.sidebar-link::before {
    content: "";
    position: absolute;
    left: 0; top: 10px; bottom: 10px;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: linear-gradient(180deg, #e3c37d, #c99a45);
    transform: scaleY(0);
    transition: transform .3s ease;
}

.sidebar-link:hover {
    color: #fff;
    background: rgba(255,255,255,.06);
    transform: translateX(3px);
}

.sidebar-link.active {
    color: #fff;
    background: linear-gradient(90deg, rgba(201,154,69,.22), rgba(255,255,255,.04));
    box-shadow: inset 0 0 0 1px rgba(227,195,125,.14), 0 8px 20px rgba(0,0,0,.14);
    border-left: 0;
    padding-left: 12px;
}

.sidebar-link.active::before { transform: scaleY(1); }

/* icon tile */
.sidebar-icon {
    width: 34px;
    height: 34px;
    border-radius: 11px;
    font-size: 16px;
    color: rgba(255,255,255,.78);
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.06);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: color .3s ease, background .3s ease, border-color .3s ease, transform .3s ease;
}

.sidebar-link:hover .sidebar-icon {
    color: #e3c37d;
    background: rgba(227,195,125,.12);
    border-color: rgba(227,195,125,.25);
    transform: scale(1.06);
}

.sidebar-link.active .sidebar-icon {
    color: #051a14;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    border-color: transparent;
    box-shadow: 0 6px 14px rgba(184,138,68,.35);
}


/* =====================================================
   DIVIDER
===================================================== */
.sidebar-divider {
    height: 1px;
    margin: 20px 10px 18px;
    background: linear-gradient(90deg, transparent, rgba(227,195,125,.28), transparent);
}


/* =====================================================
   DAILY REMEMBRANCE
===================================================== */
.daily-remembrance {
    margin-top: auto;
    padding: 22px 18px 20px;
    min-height: 195px;
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(145deg, rgba(255,255,255,.09), rgba(255,255,255,.025));
    border: 1px solid rgba(227,195,125,.14);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.05), 0 14px 30px rgba(0,0,0,.14);
}

.daily-remembrance::before {
    content: "";
    position: absolute;
    left: 0; right: 0; top: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, #e3c37d, transparent);
    opacity: .7;
}

.daily-remembrance::after {
    content: "۞";
    position: absolute;
    right: -10px;
    bottom: -42px;
    font-family: 'Amiri Quran', 'Amiri', serif;
    font-size: 130px;
    line-height: 1;
    color: rgba(227,195,125,.07);
    pointer-events: none;
}

.remembrance-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 10.5px;
    font-weight: 600;
    letter-spacing: .18em;
    color: #e3c37d;
    margin-bottom: 16px;
}

.remembrance-title::before { content: "✦"; font-size: 10px; }

.remembrance-arabic {
    position: relative;
    z-index: 2;
    font-family: 'Amiri Quran', 'Amiri', serif;
    font-size: 23px;
    line-height: 1.85;
    text-align: right;
    color: #f7f0e4;
    margin-bottom: 12px;
}

.remembrance-english {
    position: relative;
    z-index: 2;
    font-family: "Cormorant Garamond", Georgia, serif;
    font-style: italic;
    font-size: 16px;
    line-height: 1.5;
    color: rgba(255,255,255,.64);
}


/* =====================================================
   ANIMATIONS
===================================================== */
@keyframes brandEnter {
    from { opacity: 0; transform: translateY(-12px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-3px); }
}

@keyframes ringPulse {
    0%, 100% { opacity: .25; transform: scale(1); }
    50%      { opacity: .7;  transform: scale(1.08); }
}

@keyframes starGlow {
    0%, 100% { transform: scale(1) rotate(0deg);   opacity: .85; }
    50%      { transform: scale(1.1) rotate(7deg); opacity: 1; }
}

@keyframes textReveal {
    from { opacity: 0;   transform: translateX(-10px); letter-spacing: .27em; }
    to   { opacity: .92; transform: translateX(0);     letter-spacing: .16em; }
}

@keyframes lineReveal {
    from { opacity: 0; transform: scaleX(0); }
    to   { opacity: 1; transform: scaleX(1); }
}

@media (prefers-reduced-motion: reduce) {
    .hidayah-sidebar *, .hidayah-sidebar *::before, .hidayah-sidebar *::after {
        animation: none !important;
    }
}


/* =====================================================
   MOBILE / TABLET
===================================================== */
@media (max-width: 900px) {

    :root { --sidebar-w: 230px; }

    body .hidayah-main,
    body.sidebar-collapsed .hidayah-main {
        margin-left: 0;
        width: 100%;
        padding-top: 0;               /* pages already leave room for the toggle */
    }

    .main-content,
    body.sidebar-collapsed .main-content { margin-left: 0; }

    .hidayah-sidebar { padding: 24px 14px 18px; box-shadow: 14px 0 50px rgba(0,0,0,.35); }

    .brand-subtitle { font-size: 10px; letter-spacing: .1em; }
}

</style>


{{-- =========================================================
     SIDEBAR JS
========================================================= --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('hidayahSidebar');
    const toggle  = document.getElementById('sidebarToggle');
    if (!sidebar || !toggle) return;

    const isMobile = () => window.matchMedia('(max-width: 900px)').matches;

    const apply = (hidden, save = true) => {
        sidebar.classList.toggle('sidebar-hidden', hidden);
        toggle.classList.toggle('sidebar-collapsed', hidden);
        document.body.classList.toggle('sidebar-collapsed', hidden);
        toggle.setAttribute('aria-expanded', String(!hidden));
        sidebar.toggleAttribute('inert', hidden);

        if (save && !isMobile()) {
            try { localStorage.setItem('sidebarHidden', hidden ? '1' : '0'); } catch (e) {}
        }
    };

    // Initial state: closed on mobile, saved state on desktop
    let saved = false;
    try { saved = localStorage.getItem('sidebarHidden') === '1'; } catch (e) {}
    apply(isMobile() ? true : saved, false);

    toggle.addEventListener('click', () => {
        apply(!sidebar.classList.contains('sidebar-hidden'));
    });

    // Close after choosing a link on mobile
    sidebar.addEventListener('click', (e) => {
        if (isMobile() && e.target.closest('.sidebar-link')) apply(true, false);
    });
});
</script>