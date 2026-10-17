@extends('layouts.app')

@section('title', 'Tafsir Management')

@section('content')

<style>
    :root {
        --quran-green: #0b4038;
        --quran-green-dark: #07342e;
        --quran-green-light: #e8f1ee;
        --gold: #c6a15b;
        --gold-light: #ead9ae;
        --cream: #faf8f3;
        --border: #e5e0d5;
        --text: #17201e;
        --muted: #78817d;
        --danger: #c94b4b;
        --blue: #477bb5;
    }

    .tafsir-page {
        min-height: calc(100vh - 80px);
        background:
            radial-gradient(
                circle at 8% 8%,
                rgba(198,161,91,.08),
                transparent 25%
            ),
            radial-gradient(
                circle at 92% 92%,
                rgba(11,64,56,.06),
                transparent 30%
            ),
            var(--cream);

        padding: 50px 20px 80px;
    }

    .tafsir-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ========================================
       PAGE HEADER
    ======================================== */

    .page-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 30px;
    }

    .header-left {
        min-width: 0;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 7px 13px;
        border-radius: 30px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;

        margin-bottom: 13px;
    }

    .header-badge::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--gold);
    }

    .page-header h1 {
        margin: 0;
        color: var(--quran-green-dark);

        font-size: 36px;
        line-height: 1.2;
        font-weight: 700;

        letter-spacing: -.03em;
    }

    .page-header p {
        margin: 9px 0 0;

        color: var(--muted);
        font-size: 14px;
    }

    /* ========================================
       CREATE BUTTON
    ======================================== */

    .create-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 9px;

        height: 46px;
        padding: 0 19px;

        color: #fff;
        background: linear-gradient(
            135deg,
            var(--quran-green),
            var(--quran-green-dark)
        );

        border: 1px solid var(--quran-green);
        border-radius: 11px;

        font-size: 13px;
        font-weight: 700;

        text-decoration: none;

        box-shadow:
            0 7px 18px rgba(11,64,56,.16);

        transition: .2s ease;
        white-space: nowrap;
    }

    .create-btn:hover {
        transform: translateY(-2px);

        box-shadow:
            0 11px 25px rgba(11,64,56,.23);
    }

    .plus-icon {
        width: 22px;
        height: 22px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 7px;

        background: rgba(255,255,255,.13);

        font-size: 16px;
        line-height: 1;
    }

    /* ========================================
       SUCCESS MESSAGE
    ======================================== */

    .success-message {
        display: flex;
        align-items: center;
        gap: 11px;

        padding: 13px 16px;
        margin-bottom: 22px;

        border: 1px solid #cfe3d9;
        border-radius: 12px;

        background: #edf7f2;
        color: #24634f;

        font-size: 13px;
        font-weight: 600;

        animation: slideDown .35s ease;
    }

    .success-icon {
        width: 25px;
        height: 25px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 50%;

        background: #d6ecdf;
        color: #24634f;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ========================================
       STAT BAR
    ======================================== */

    .stats-bar {
        display: grid;
        grid-template-columns: repeat(3, 1fr);

        gap: 14px;

        margin-bottom: 24px;
    }

    .stat-card {
        display: flex;
        align-items: center;
        gap: 13px;

        padding: 16px 18px;

        background: rgba(255,255,255,.82);

        border: 1px solid var(--border);
        border-radius: 14px;

        box-shadow:
            0 5px 15px rgba(20,40,35,.035);
    }

    .stat-icon {
        width: 40px;
        height: 40px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 17px;
    }

    .stat-info strong {
        display: block;

        color: var(--text);
        font-size: 17px;
        line-height: 1.2;
    }

    .stat-info span {
        display: block;

        margin-top: 3px;

        color: var(--muted);
        font-size: 10px;
    }

    /* ========================================
       TAFSIR LIST
    ======================================== */

    .list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;

        margin-bottom: 13px;

        padding: 0 3px;
    }

    .list-header h2 {
        margin: 0;

        color: var(--text);

        font-size: 15px;
        font-weight: 700;
    }

    .list-count {
        color: var(--muted);
        font-size: 11px;
    }

    .tafsir-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    /* ========================================
       TAFSIR CARD
    ======================================== */

    .tafsir-card {
        position: relative;

        background: rgba(255,255,255,.97);

        border: 1px solid var(--border);
        border-radius: 17px;

        overflow: hidden;

        box-shadow:
            0 8px 25px rgba(20,40,35,.045);

        transition:
            transform .2s ease,
            box-shadow .2s ease,
            border-color .2s ease;
    }

    .tafsir-card:hover {
        transform: translateY(-2px);

        border-color: #d7d0c1;

        box-shadow:
            0 14px 32px rgba(20,40,35,.075);
    }

    .card-accent {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;

        width: 4px;

        background: linear-gradient(
            180deg,
            var(--gold),
            var(--quran-green)
        );
    }

    .card-content {
        padding: 20px 21px 20px 25px;
    }

    .card-main {
        display: flex;
        justify-content: space-between;
        gap: 25px;
    }

    /* ========================================
       CARD INFORMATION
    ======================================== */

    .tafsir-info {
        flex: 1;
        min-width: 0;
    }

    .tafsir-topline {
        display: flex;
        align-items: center;
        gap: 9px;

        margin-bottom: 11px;
    }

    .surah-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;

        padding: 6px 10px;

        border-radius: 8px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 11px;
        font-weight: 700;
    }

    .surah-badge::before {
        content: "☾";

        font-size: 11px;
    }

    .level-badge {
        display: inline-flex;
        align-items: center;

        padding: 6px 10px;

        border-radius: 8px;

        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .level-beginner {
        background: #f4ecd9;
        color: #8a6b2d;
    }

    .level-advanced {
        background: #e7eef7;
        color: #456b92;
    }

    .tafsir-title {
        margin: 0 0 5px;

        color: var(--text);

        font-size: 17px;
        font-weight: 700;
    }

    .ayah-info {
        display: flex;
        align-items: center;
        gap: 7px;

        color: var(--muted);

        font-size: 12px;
    }

    .ayah-icon {
        color: var(--gold);
        font-size: 13px;
    }

    /* ========================================
       PREVIEW
    ======================================== */

    .tafsir-preview {
        margin-top: 14px;
        padding: 12px 14px;

        background: #faf9f5;

        border: 1px solid #eeeae1;
        border-radius: 10px;

        color: #69726e;

        font-family: Georgia, "Times New Roman", serif;
        font-size: 12px;
        line-height: 1.65;

        max-width: 750px;

        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    /* ========================================
       ACTIONS
    ======================================== */

    .card-actions {
        display: flex;
        align-items: flex-start;
        gap: 7px;

        flex-shrink: 0;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;

        height: 34px;
        padding: 0 10px;

        border-radius: 8px;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition: .2s ease;
        cursor: pointer;
    }

    .view-btn {
        color: var(--quran-green);
        background: var(--quran-green-light);
        border: 1px solid #d6e5df;
    }

    .view-btn:hover {
        background: #dcebe5;
    }

    .edit-btn {
        color: #876a2f;
        background: #f8f1df;
        border: 1px solid #eadfca;
    }

    .edit-btn:hover {
        background: #f1e7ce;
    }

    .delete-btn {
        color: #b34444;
        background: #fff0f0;
        border: 1px solid #f0d5d5;
    }

    .delete-btn:hover {
        background: #fbe2e2;
    }

    /* ========================================
       EMPTY STATE
    ======================================== */

    .empty-state {
        padding: 65px 25px;

        text-align: center;

        background: rgba(255,255,255,.9);

        border: 1px solid var(--border);
        border-radius: 18px;

        box-shadow:
            0 8px 25px rgba(20,40,35,.04);
    }

    .empty-icon {
        width: 62px;
        height: 62px;

        margin: 0 auto 16px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 18px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 25px;
    }

    .empty-state h3 {
        margin: 0 0 7px;

        color: var(--text);

        font-size: 17px;
    }

    .empty-state p {
        margin: 0 auto 20px;

        max-width: 380px;

        color: var(--muted);

        font-size: 12px;
        line-height: 1.6;
    }

    /* ========================================
       PAGINATION
    ======================================== */

    .pagination-wrapper {
        margin-top: 25px;

        display: flex;
        justify-content: center;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: center;
    }
/* ========================================
   SEARCH PANEL
======================================== */

.search-panel {
    margin-bottom: 25px;

    padding: 18px 20px;

    background: rgba(255,255,255,.88);

    border: 1px solid var(--border);
    border-radius: 16px;

    box-shadow:
        0 7px 22px rgba(20,40,35,.04);
}


/* Search title */

.search-title {
    display: flex;
    align-items: center;
    gap: 11px;

    margin-bottom: 16px;
}

.search-title-icon {
    width: 36px;
    height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    background: var(--quran-green-light);
    color: var(--quran-green);

    font-size: 17px;
}

.search-title strong {
    display: block;

    color: var(--text);

    font-size: 13px;
    font-weight: 700;
}

.search-title span {
    display: block;

    margin-top: 2px;

    color: var(--muted);

    font-size: 10px;
}


/* Search fields */

.search-fields {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

.search-field {
    flex: 1;
    min-width: 0;
}

.search-field label {
    display: block;

    margin-bottom: 6px;

    color: var(--text);

    font-size: 10px;
    font-weight: 700;
}

.input-wrap {
    position: relative;
}

.input-icon {
    position: absolute;

    left: 12px;
    top: 50%;

    transform: translateY(-50%);

    color: var(--gold);

    font-size: 13px;

    pointer-events: none;
}

.search-field input {
    width: 100%;
    height: 40px;

    padding: 0 12px 0 33px;

    background: #fff;

    border: 1px solid var(--border);
    border-radius: 9px;

    color: var(--text);

    font-family: inherit;
    font-size: 12px;

    outline: none;

    transition:
        border-color .2s ease,
        box-shadow .2s ease,
        background .2s ease;
}

.search-field input::placeholder {
    color: #a2aaa6;
}

.search-field input:focus {
    border-color: var(--quran-green);

    box-shadow:
        0 0 0 3px rgba(11,64,56,.08);
}


/* Search button */

.search-btn {
    height: 40px;

    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;

    padding: 0 17px;

    border: 1px solid var(--quran-green);
    border-radius: 9px;

    background:
        linear-gradient(
            135deg,
            var(--quran-green),
            var(--quran-green-dark)
        );

    color: #fff;

    font-size: 11px;
    font-weight: 700;

    cursor: pointer;

    white-space: nowrap;

    box-shadow:
        0 5px 14px rgba(11,64,56,.13);

    transition: .2s ease;
}

.search-btn:hover {
    transform: translateY(-1px);

    box-shadow:
        0 8px 18px rgba(11,64,56,.20);
}

.search-btn span {
    font-size: 15px;
}


/* Clear */

.clear-btn {
    height: 40px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 13px;

    border: 1px solid #eadfca;
    border-radius: 9px;

    background: #f8f1df;

    color: #876a2f;

    font-size: 11px;
    font-weight: 700;

    text-decoration: none;

    white-space: nowrap;

    transition: .2s ease;
}

.clear-btn:hover {
    background: #f1e7ce;
}


/* Search result text */

.search-result-info {
    display: flex;
    align-items: center;
    gap: 6px;

    margin-top: 12px;

    color: var(--muted);

    font-size: 10px;
}

.search-result-info strong {
    color: var(--quran-green);
}


/* ========================================
   SEARCH RESPONSIVE
======================================== */

@media (max-width: 700px) {

    .search-fields {
        flex-wrap: wrap;
    }

    .search-field {
        flex: 1 1 calc(50% - 5px);
    }

    .search-btn,
    .clear-btn {
        flex: 1;
    }
}


@media (max-width: 450px) {

    .search-panel {
        padding: 16px;
    }

    .search-fields {
        flex-direction: column;
        align-items: stretch;
    }

    .search-field {
        width: 100%;
    }

    .search-btn,
    .clear-btn {
        width: 100%;
    }
}
    /* ========================================
       RESPONSIVE
    ======================================== */

    @media (max-width: 800px) {

        .page-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .create-btn {
            width: 100%;
        }

        .stats-bar {
            grid-template-columns: 1fr;
        }

        .card-main {
            flex-direction: column;
        }

        .card-actions {
            padding-top: 3px;
        }
    }

    @media (max-width: 550px) {

        .tafsir-page {
            padding: 30px 12px 55px;
        }

        .page-header h1 {
            font-size: 29px;
        }

        .card-content {
            padding: 18px 17px 18px 21px;
        }

        .tafsir-topline {
            flex-wrap: wrap;
        }

        .card-actions {
            width: 100%;
        }

        .action-btn {
            flex: 1;
        }
    }
</style>


<div class="tafsir-page">

    <div class="tafsir-container">


        {{-- ========================================
             PAGE HEADER
        ======================================== --}}

        <div class="page-header">

            <div class="header-left">

                <div class="header-badge">
                    Tafsir Management
                </div>

                <h1>Tafsirs</h1>

                <p>
                    Manage Quranic interpretations and organize them by reading level.
                </p>

            </div>


            <a
                href="{{ route('admin.tafsir.create') }}"
                class="create-btn"
            >
                <span class="plus-icon">+</span>
                Create Tafsir
            </a>



        </div>

        {{-- ========================================
     SEARCH
======================================== --}}

<form
    method="GET"
    action="{{ route('admin.tafsir.index') }}"
    class="search-panel"
>

    <div class="search-title">
        <div class="search-title-icon">
            ⌕
        </div>

        <div>
            <strong>Search Tafsir</strong>

            <span>
                Find interpretations by Surah or Ayah
            </span>
        </div>
    </div>


    <div class="search-fields">

        {{-- Surah Search --}}

        <div class="search-field">

            <label for="surah">
                Surah Number
            </label>

            <div class="input-wrap">

                <span class="input-icon">
                    ◈
                </span>

                <input
                    type="number"
                    id="surah"
                    name="surah"
                    min="1"
                    max="114"
                    value="{{ request('surah') }}"
                    placeholder="e.g. 2"
                >

            </div>

        </div>


        {{-- Ayah Search --}}

        <div class="search-field">

            <label for="ayah">
                Ayah Number
            </label>

            <div class="input-wrap">

                <span class="input-icon">
                    ✧
                </span>

                <input
                    type="number"
                    id="ayah"
                    name="ayah"
                    min="1"
                    value="{{ request('ayah') }}"
                    placeholder="e.g. 255"
                >

            </div>

        </div>


        {{-- Search Button --}}

        <button
            type="submit"
            class="search-btn"
        >
            <span>⌕</span>
            Search
        </button>


        {{-- Clear Button --}}

        @if(request('surah') || request('ayah'))

            <a
                href="{{ route('admin.tafsir.index') }}"
                class="clear-btn"
            >
                Clear
            </a>

        @endif

    </div>

</form>

@if(request('surah') || request('ayah'))

    <div class="search-result-info">

        <span>Showing results for:</span>

        @if(request('surah'))
            <strong>
                Surah {{ request('surah') }}
            </strong>
        @endif

        @if(request('surah') && request('ayah'))
            <span>•</span>
        @endif

        @if(request('ayah'))
            <strong>
                Ayah {{ request('ayah') }}
            </strong>
        @endif

    </div>

@endif


        {{-- ========================================
             SUCCESS MESSAGE
        ======================================== --}}

        @if(session('success'))

            <div class="success-message">

                <div class="success-icon">
                    ✓
                </div>

                <span>
                    {{ session('success') }}
                </span>

            </div>

        @endif


        {{-- ========================================
             STATISTICS
        ======================================== --}}

        <div class="stats-bar">

            <div class="stat-card">

                <div class="stat-icon">
                    ✦
                </div>

                <div class="stat-info">

                    

                    <span>
                        Total Tafsirs
                    </span>
                    <strong>{{ $totalTafsirs }}</strong>


                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ◈
                </div>

                <div class="stat-info">

                       <strong>{{ $beginnerTafsirs }}</strong>

                    <span>
                        Beginner Tafsirs
                    </span>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon">
                    ◆
                </div>

                <div class="stat-info">

                        <strong>{{ $advancedTafsirs }}</strong>


                    <span>
                        Advanced Tafsirs
                    </span>

                </div>

            </div>

        </div>


        {{-- ========================================
             LIST HEADER
        ======================================== --}}

        @if($tafsirs->count())

            <div class="list-header">

                <h2>
                    Tafsir Library
                </h2>

                <span class="list-count">
                    {{ $tafsirs->count() }} shown
                </span>

            </div>


            {{-- ========================================
                 TAFSIR LIST
            ======================================== --}}

            <div class="tafsir-list">

                @foreach($tafsirs as $tafsir)

                    <div class="tafsir-card">

                        <div class="card-accent"></div>

                        <div class="card-content">

                            <div class="card-main">


                                {{-- Information --}}

                                <div class="tafsir-info">


                                    <div class="tafsir-topline">

                                        <span class="surah-badge">
                                            Surah #{{ $tafsir->surah_id }}
                                        </span>


                                        @if($tafsir->level === 'beginner')

                                            <span class="level-badge level-beginner">
                                                Beginner
                                            </span>

                                        @else

                                            <span class="level-badge level-advanced">
                                                Advanced
                                            </span>

                                        @endif

                                    </div>


                                    <h3 class="tafsir-title">
                                        Quranic Interpretation
                                    </h3>


                                    <div class="ayah-info">

                                        <span class="ayah-icon">
                                            ✧
                                        </span>

                                        <span>

                                           @if($tafsir->endVerse)
    Verses
    {{ $tafsir->startVerse->verse_number }}
    –
    {{ $tafsir->endVerse->verse_number }}
@elseif($tafsir->startVerse)
    Verse
    {{ $tafsir->startVerse->verse_number }}
@endif

                                        </span>

                                    </div>


                                    {{-- Tafsir preview --}}

                                    @if($tafsir->text)

                                        <div class="tafsir-preview">
                                            {{ strip_tags($tafsir->text) }}
                                        </div>

                                    @endif

                                </div>


                                {{-- Actions --}}

                                <div class="card-actions">

                                    <a
                                        href="{{ route('admin.tafsir.show', $tafsir) }}"
                                        class="action-btn view-btn"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="{{ route('admin.tafsir.edit', $tafsir) }}"
                                        class="action-btn edit-btn"
                                    >
                                        Edit
                                    </a>

            <a href="{{ route('admin.tafsirs.videos.index', $tafsir) }}"
   style="
        display:inline-block;
        background:#198754;
        color:white;
        padding:8px 14px;
        border-radius:6px;
        text-decoration:none;
        font-size:14px;
   ">
    🎥 Manage Videos
</a>
                                    <form
                                        method="POST"
                                        action="{{ route('admin.tafsir.destroy', $tafsir) }}"
                                        style="display:inline;"
                                        onsubmit="return confirm('Are you sure you want to delete this tafsir?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="action-btn delete-btn"
                                        >
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            {{-- Pagination --}}

            <div class="pagination-wrapper">
                {{ $tafsirs->links() }}
            </div>


        @else


            {{-- ========================================
                 EMPTY STATE
            ======================================== --}}

            <div class="empty-state">

                <div class="empty-icon">
                    ✦
                </div>

                <h3>
                    No Tafsirs Yet
                </h3>

                <p>
                    Your tafsir library is currently empty.
                    Create your first interpretation to get started.
                </p>

                <a
                    href="{{ route('tafsirs.create') }}"
                    class="create-btn"
                >
                    <span class="plus-icon">+</span>
                    Create Your First Tafsir
                </a>

            </div>

        @endif

    </div>

</div>

@endsection