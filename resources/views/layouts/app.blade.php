<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Hidayah - Quran Tafseer')
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    {{-- =====================================================
         APPLY SAVED THEME BEFORE PAGE PAINT
    ====================================================== --}}

    <script>
        (function () {

            try {

                if (
                    localStorage.getItem('tafsir-theme') === 'dark'
                ) {

                    document.documentElement.classList.add(
                        'dark-mode'
                    );

                }

            } catch (e) {}

        })();
    </script>

    @stack('styles')

</head>


<body>

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    @include('layouts.sidebar')


    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <main class="hidayah-main">

        @yield('content')

    </main>


    @stack('scripts')


    {{-- =====================================================
         GLOBAL THEME SYSTEM
    ====================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const themeToggle =
                    document.getElementById('themeToggle');

                const themeIcon =
                    document.getElementById('themeIcon');

                const themeLabel =
                    document.getElementById('themeLabel');


                function updateThemeUI() {

                    const isDark =
                        document.documentElement.classList.contains(
                            'dark-mode'
                        );


                    if (themeIcon) {

                        themeIcon.textContent =
                            isDark ? '☀' : '☾';

                    }


                    if (themeLabel) {

                        themeLabel.textContent =
                            isDark
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


                if (themeToggle) {

                    themeToggle.addEventListener(
                        'click',
                        function () {

                            const isDark =
                                document.documentElement.classList.toggle(
                                    'dark-mode'
                                );


                            try {

                                localStorage.setItem(
                                    'tafsir-theme',
                                    isDark
                                        ? 'dark'
                                        : 'light'
                                );

                            } catch (e) {}


                            updateThemeUI();

                        }
                    );

                }


                updateThemeUI();

            }
        );

    </script>

</body>

</html>


<style>

/* =========================================================
   MAIN CONTENT
========================================================= */

.hidayah-main {

    margin-left: 270px;

    min-height: 100vh;

    width: calc(100% - 270px);

}


/* =========================================================
   GLOBAL THEME TRANSITION
========================================================= */

html,
body,
.hidayah-main {

    transition:
        background-color .3s ease,
        color .3s ease;

}


/* =========================================================
   DARK MODE — GLOBAL
========================================================= */

html.dark-mode {

    color-scheme: dark;

}


html.dark-mode body {

    background:
        #101713;

    color:
        #e4ebe6;

}


/* Main area */

html.dark-mode .hidayah-main {

    background:
        #101713;

}


/* =========================================================
   COMMON WHITE BACKGROUNDS
========================================================= */

html.dark-mode .card,
html.dark-mode .panel,
html.dark-mode .box,
html.dark-mode .modal,
html.dark-mode .stat-card,
html.dark-mode .tafsir-card,
html.dark-mode .verse-card,
html.dark-mode .empty-state,
html.dark-mode .search-panel,
html.dark-mode .top-audio-bar,
html.dark-mode .surah-header {

    background:
        #1a251f !important;

    border-color:
        rgba(255,255,255,.075) !important;

    color:
        #e2eae4;

}


/* =========================================================
   COMMON TEXT
========================================================= */

html.dark-mode h1,
html.dark-mode h2,
html.dark-mode h3,
html.dark-mode h4,
html.dark-mode h5,
html.dark-mode h6 {

    color:
        #e6eee9;

}


html.dark-mode p {

    color:
        #aab7b0;

}


html.dark-mode label {

    color:
        #c7d1cb;

}


/* =========================================================
   INPUTS
========================================================= */

html.dark-mode input,
html.dark-mode textarea,
html.dark-mode select {

    background:
        #17221c !important;

    color:
        #e2eae5 !important;

    border-color:
        rgba(255,255,255,.11) !important;

}


html.dark-mode input::placeholder,
html.dark-mode textarea::placeholder {

    color:
        #718078 !important;

}


html.dark-mode input:focus,
html.dark-mode textarea:focus,
html.dark-mode select:focus {

    border-color:
        #b99951 !important;

    box-shadow:
        0 0 0 3px rgba(201,154,69,.10) !important;

}


html.dark-mode option {

    background:
        #17221c;

    color:
        #e2eae5;

}


/* =========================================================
   COMMON BORDERS
========================================================= */

html.dark-mode table,
html.dark-mode th,
html.dark-mode td {

    border-color:
        rgba(255,255,255,.08) !important;

}


html.dark-mode hr {

    border-color:
        rgba(255,255,255,.08);

}


/* =========================================================
   LINKS
========================================================= */

html.dark-mode a {

    color:
        #cbb16e;

}


/* =========================================================
   COMMON LIGHT BACKGROUNDS
========================================================= */

html.dark-mode [class*="bg-white"],
html.dark-mode [class*="bg-gray-50"],
html.dark-mode [class*="bg-gray-100"] {

    background-color:
        #1a251f !important;

}


html.dark-mode [class*="text-gray-500"],
html.dark-mode [class*="text-gray-600"],
html.dark-mode [class*="text-gray-700"] {

    color:
        #9ba9a1 !important;

}


html.dark-mode [class*="text-gray-800"],
html.dark-mode [class*="text-gray-900"] {

    color:
        #e2eae5 !important;

}


/* =========================================================
   TAILWIND SHADOWS
========================================================= */

html.dark-mode [class*="shadow"] {

    --tw-shadow-color:
        rgba(0,0,0,.25);

}


/* =========================================================
   SIDEBAR THEME BUTTON
========================================================= */

.theme-toggle {

    width: 100%;

    min-height: 45px;

    padding: 0 14px;

    margin-top: 15px;

    display: flex;

    align-items: center;

    gap: 14px;

    border-radius: 12px;

    border:
        1px solid rgba(245,239,213,.10);

    background:
        rgba(245,239,213,.055);

    color:
        rgba(245,239,213,.78);

    font-family:
        Inter,
        Arial,
        sans-serif;

    font-size: 13px;

    font-weight: 500;

    cursor: pointer;

    transition:
        background .25s ease,
        color .25s ease,
        border-color .25s ease,
        transform .2s ease;

}


.theme-toggle:hover {

    background:
        rgba(245,239,213,.10);

    border-color:
        rgba(227,195,125,.22);

    color:
        #f7f3df;

    transform:
        translateX(2px);

}


.theme-toggle-icon {

    width: 18px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    color:
        #d8b86a;

    font-size: 16px;

}


.theme-toggle-text {

    flex: 1;

    text-align: left;

}


/* =========================================================
   DARK MODE BUTTON
========================================================= */

html.dark-mode .theme-toggle {

    background:
        rgba(216,184,106,.08);

    border-color:
        rgba(216,184,106,.18);

    color:
        #eee8d5;

}


html.dark-mode .theme-toggle:hover {

    background:
        rgba(216,184,106,.13);

    border-color:
        rgba(216,184,106,.30);

}


/* =========================================================
   SUCCESS / ALERTS
========================================================= */

html.dark-mode .success-message {

    background:
        rgba(52,110,83,.18) !important;

    border-color:
        rgba(100,170,135,.20) !important;

    color:
        #a9d2ba !important;

}


html.dark-mode .success-icon {

    background:
        rgba(100,170,135,.15) !important;

    color:
        #9fd0b3 !important;

}


/* =========================================================
   SCROLLBAR
========================================================= */

html.dark-mode ::-webkit-scrollbar {

    width: 8px;
    height: 8px;

}


html.dark-mode ::-webkit-scrollbar-track {

    background:
        #101713;

}


html.dark-mode ::-webkit-scrollbar-thumb {

    background:
        #33453b;

    border-radius:
        10px;

}


html.dark-mode ::-webkit-scrollbar-thumb:hover {

    background:
        #486052;

}


/* =========================================================
   MOBILE
========================================================= */
.hidayah-main {
    margin-left: var(--sidebar-w, 270px);
    width: calc(100% - var(--sidebar-w, 270px));
    min-height: 100vh;
    transition: margin-left .3s ease, width .3s ease;
}

body.sidebar-collapsed .hidayah-main {
    margin-left: 0;
    width: 100%;
}

@media (max-width: 900px) {
    .hidayah-main {
        margin-left: 0;
        width: 100%;
    }
}

</style>