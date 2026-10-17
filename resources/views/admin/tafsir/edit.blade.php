@extends('layouts.app')

@section('title', 'Edit Tafsir')

@section('content')

<style>
    :root {
        --quran-green: #0b4038;
        --quran-green-dark: #07342e;
        --quran-green-light: #e8f1ee;

        --gold: #c6a15b;
        --gold-light: #ead9ae;

        --cream: #faf8f3;
        --cream-dark: #f4efe5;

        --border: #e4ded2;
        --border-light: #eee9df;

        --text: #17201e;
        --muted: #7a827e;

        --red: #bd4b4b;
    }

    /* =========================================
       PAGE
    ========================================= */

    .edit-tafsir-page {
        min-height: calc(100vh - 80px);

        background:
            radial-gradient(
                circle at 7% 8%,
                rgba(198,161,91,.09),
                transparent 24%
            ),
            radial-gradient(
                circle at 94% 90%,
                rgba(11,64,56,.07),
                transparent 28%
            ),
            var(--cream);

        padding: 48px 20px 80px;
    }

    .edit-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* =========================================
       HEADER
    ========================================= */

    .edit-header {
        margin-bottom: 28px;
    }

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        color: var(--muted);

        font-size: 12px;
        font-weight: 600;

        text-decoration: none;

        margin-bottom: 17px;

        transition: .2s ease;
    }

    .back-link:hover {
        color: var(--quran-green);
    }

    .back-arrow {
        font-size: 15px;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;

        padding: 7px 13px;

        border-radius: 30px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 10px;
        font-weight: 800;

        letter-spacing: .09em;
        text-transform: uppercase;

        margin-bottom: 12px;
    }

    .header-badge::before {
        content: "";

        width: 7px;
        height: 7px;

        border-radius: 50%;

        background: var(--gold);
    }

    .edit-header h1 {
        margin: 0;

        color: var(--quran-green-dark);

        font-size: 34px;
        line-height: 1.2;

        font-weight: 700;

        letter-spacing: -.03em;
    }

    .edit-header p {
        margin: 9px 0 0;

        color: var(--muted);

        font-size: 13px;
    }

    /* =========================================
       MAIN CARD
    ========================================= */

    .edit-card {
        background: rgba(255,255,255,.94);

        border: 1px solid var(--border);

        border-radius: 20px;

        box-shadow:
            0 12px 35px rgba(25,45,40,.055);

        overflow: hidden;
    }

    /* =========================================
       FORM SECTION
    ========================================= */

    .form-section {
        padding: 25px 28px;

        border-bottom: 1px solid var(--border-light);
    }

    .form-section:last-child {
        border-bottom: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;

        margin-bottom: 19px;
    }

    .section-icon {
        width: 38px;
        height: 38px;

        flex-shrink: 0;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 11px;

        background: var(--quran-green-light);
        color: var(--quran-green);

        font-size: 16px;
    }

    .section-title h2 {
        margin: 0;

        color: var(--text);

        font-size: 14px;
        font-weight: 800;
    }

    .section-title p {
        margin: 3px 0 0;

        color: var(--muted);

        font-size: 10px;
    }

    /* =========================================
       LABELS
    ========================================= */

    .field-label {
        display: block;

        margin-bottom: 8px;

        color: #25312e;

        font-size: 11px;
        font-weight: 800;

        letter-spacing: .02em;
    }

    .field-label span {
        color: var(--red);
    }

    /* =========================================
       INPUT
    ========================================= */

    .input-wrapper {
        position: relative;
    }

    .form-input {
        width: 100%;
        height: 46px;

        padding: 0 14px;

        border: 1px solid #dcd6ca;
        border-radius: 11px;

        background: #fff;

        color: var(--text);

        font-size: 13px;

        outline: none;

        transition:
            border-color .2s ease,
            box-shadow .2s ease,
            background .2s ease;
    }

    .form-input:hover {
        border-color: #c9c0b1;
    }

    .form-input:focus {
        border-color: var(--quran-green);

        box-shadow:
            0 0 0 3px rgba(11,64,56,.08);
    }

    .number-input {
        max-width: 180px;
    }

    /* =========================================
       AYAH AREA
    ========================================= */

    .ayah-mode {
        display: flex;
        align-items: center;
        justify-content: space-between;

        padding: 14px 15px;

        margin-bottom: 17px;

        border: 1px solid var(--border);

        border-radius: 12px;

        background: #fbfaf7;
    }

    .mode-info {
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .mode-icon {
        width: 32px;
        height: 32px;

        display: flex;
        align-items: center;
        justify-content: center;

        border-radius: 9px;

        background: #f2ead8;

        color: #8d6d30;

        font-size: 13px;
    }

    .mode-info strong {
        display: block;

        color: var(--text);

        font-size: 12px;
    }

    .mode-info span {
        display: block;

        margin-top: 2px;

        color: var(--muted);

        font-size: 9px;
    }

    /* =========================================
       TOGGLE
    ========================================= */

    .switch {
        position: relative;

        width: 45px;
        height: 24px;

        flex-shrink: 0;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;

        inset: 0;

        cursor: pointer;

        background: #d9dcd9;

        border-radius: 30px;

        transition: .25s;
    }

    .slider::before {
        content: "";

        position: absolute;

        width: 18px;
        height: 18px;

        left: 3px;
        top: 3px;

        border-radius: 50%;

        background: white;

        box-shadow: 0 2px 5px rgba(0,0,0,.15);

        transition: .25s;
    }

    .switch input:checked + .slider {
        background: var(--quran-green);
    }

    .switch input:checked + .slider::before {
        transform: translateX(21px);
    }

    /* =========================================
       VERSE FIELDS
    ========================================= */

    .verse-fields {
        display: flex;
        gap: 14px;
        align-items: flex-end;
    }

    .verse-field {
        flex: 1;
        max-width: 230px;
    }

    .single-verse {
        display: block;
    }

    .multi-verse {
        display: none;
    }

    .multi-verse.active {
        display: flex;
    }

    .single-verse.hidden {
        display: none;
    }

    /* =========================================
       LEVEL SELECTOR
    ========================================= */

    .level-grid {
        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 13px;
    }

    .level-option {
        position: relative;
    }

    .level-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .level-label {
        display: block;

        padding: 16px;

        border: 1px solid var(--border);

        border-radius: 13px;

        background: #fff;

        cursor: pointer;

        transition: .2s ease;
    }

    .level-label:hover {
        border-color: #c9bfae;

        transform: translateY(-1px);
    }

    .level-top {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .radio-circle {
        width: 18px;
        height: 18px;

        border: 1.5px solid #b9bdb9;

        border-radius: 50%;

        position: relative;
    }

    .radio-circle::after {
        content: "";

        position: absolute;

        width: 8px;
        height: 8px;

        left: 50%;
        top: 50%;

        transform: translate(-50%, -50%) scale(0);

        border-radius: 50%;

        background: var(--quran-green);

        transition: .18s ease;
    }

    .level-name {
        color: var(--text);

        font-size: 12px;
        font-weight: 800;
    }

    .level-description {
        margin: 8px 0 0 28px;

        color: var(--muted);

        font-size: 10px;
        line-height: 1.5;
    }

    .level-option input:checked + .level-label {
        border-color: var(--quran-green);

        background:
            linear-gradient(
                135deg,
                #f4f9f7,
                #fff
            );

        box-shadow:
            0 5px 15px rgba(11,64,56,.07);
    }

    .level-option input:checked
    + .level-label
    .radio-circle {
        border-color: var(--quran-green);
    }

    .level-option input:checked
    + .level-label
    .radio-circle::after {
        transform: translate(-50%, -50%) scale(1);
    }

    /* =========================================
       EDITOR
    ========================================= */

    .editor-description {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        margin-bottom: 11px;
    }

    .editor-description p {
        margin: 0;

        color: var(--muted);

        font-size: 10px;
        line-height: 1.5;
    }

    .editor-status {
        flex-shrink: 0;

        padding: 5px 9px;

        border-radius: 7px;

        background: var(--quran-green-light);

        color: var(--quran-green);

        font-size: 9px;
        font-weight: 700;
    }

    /* =========================================
       TOOLBAR
    ========================================= */

    .editor-box {
        overflow: hidden;

        border: 1px solid #d7d2c8;

        border-radius: 15px;

        background: var(--quran-green-dark);

        box-shadow:
            0 12px 28px rgba(7,52,46,.10);
    }

    .editor-toolbar {
        display: flex;
        align-items: center;
        gap: 7px;

        padding: 10px 12px;

        background:
            rgba(0,0,0,.13);

        border-bottom: 1px solid rgba(255,255,255,.08);

        flex-wrap: wrap;
    }

    .toolbar-label {
        color: rgba(255,255,255,.55);

        font-size: 9px;
        font-weight: 700;

        margin-right: 3px;
    }

    .color-btn {
        width: 28px;
        height: 28px;

        padding: 0;

        border-radius: 8px;

        border: 2px solid rgba(255,255,255,.12);

        cursor: pointer;

        transition:
            transform .18s ease,
            border-color .18s ease;
    }

    .color-btn:hover {
        transform: translateY(-2px);

        border-color: rgba(255,255,255,.7);
    }

    .toolbar-divider {
        width: 1px;
        height: 22px;

        margin: 0 4px;

        background: rgba(255,255,255,.13);
    }

    .clear-btn {
        height: 28px;

        padding: 0 10px;

        border: 1px solid rgba(255,255,255,.12);

        border-radius: 8px;

        background: rgba(255,255,255,.05);

        color: rgba(255,255,255,.75);

        font-size: 9px;
        font-weight: 700;

        cursor: pointer;

        transition: .18s ease;
    }

    .clear-btn:hover {
        background: rgba(255,255,255,.1);

        color: #fff;
    }

    /* =========================================
       EDITOR CONTENT
    ========================================= */

    .editor {
        min-height: 300px;

        padding: 22px;

        color: #1d2d29;

        background:
            radial-gradient(
                circle at 80% 10%,
                rgba(198,161,91,.06),
                transparent 25%
            ),
            linear-gradient(
                180deg,
                #fffdf9 0%,
                #f4efe5 100%
            );

        font-family:
            Georgia,
            "Times New Roman",
            serif;

        font-size: 16px;

        line-height: 1.8;

        outline: none;

        overflow: auto;
    }

    .editor:focus {
        box-shadow:
            inset 0 0 0 1px rgba(198,161,91,.18);
    }

    .editor p {
        margin: 0 0 12px;
    }

    .editor:empty::before {
        content: "Write the tafsir here...";

        color: #a19a8d;

        pointer-events: none;
    }

    /* =========================================
       FORM ERRORS
    ========================================= */

    .error-message {
        margin-top: 7px;

        color: var(--red);

        font-size: 10px;
        font-weight: 600;
    }

    /* =========================================
       FOOTER ACTIONS
    ========================================= */

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;

        gap: 15px;

        padding: 20px 28px;

        background: #fcfbf8;

        border-top: 1px solid var(--border-light);
    }

    .action-left {
        color: var(--muted);

        font-size: 10px;
    }

    .action-right {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .cancel-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        height: 42px;

        padding: 0 16px;

        border: 1px solid #ddd7cc;

        border-radius: 10px;

        background: #fff;

        color: #646d69;

        font-size: 11px;
        font-weight: 700;

        text-decoration: none;

        transition: .2s ease;
    }

    .cancel-btn:hover {
        background: #f7f5f0;

        color: var(--text);
    }

    .save-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        height: 42px;

        padding: 0 19px;

        border: 1px solid var(--quran-green);

        border-radius: 10px;

        background:
            linear-gradient(
                135deg,
                var(--quran-green),
                var(--quran-green-dark)
            );

        color: white;

        font-size: 11px;
        font-weight: 800;

        cursor: pointer;

        box-shadow:
            0 6px 15px rgba(11,64,56,.15);

        transition: .2s ease;
    }

    .save-btn:hover {
        transform: translateY(-1px);

        box-shadow:
            0 9px 20px rgba(11,64,56,.22);
    }

    .save-icon {
        font-size: 14px;
    }

    /* =========================================
       RESPONSIVE
    ========================================= */

    @media (max-width: 700px) {

        .edit-tafsir-page {
            padding: 30px 12px 60px;
        }

        .edit-header h1 {
            font-size: 28px;
        }

        .form-section {
            padding: 21px 18px;
        }

        .level-grid {
            grid-template-columns: 1fr;
        }

        .verse-fields {
            flex-direction: column;
            align-items: stretch;
        }

        .verse-field {
            max-width: none;
        }

        .number-input {
            max-width: none;
        }

        .form-actions {
            padding: 17px 18px;

            flex-direction: column;
            align-items: stretch;
        }

        .action-right {
            width: 100%;
        }

        .cancel-btn,
        .save-btn {
            flex: 1;
        }

        .editor {
            min-height: 260px;
        }
    }

    @media (max-width: 450px) {

        .ayah-mode {
            align-items: flex-start;
        }

        .editor-description {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


<div class="edit-tafsir-page">

    <div class="edit-container">


        {{-- =========================================
             HEADER
        ========================================= --}}

        <div class="edit-header">

            <a
                href="{{ route('admin.tafsir.index') }}"
                class="back-link"
            >
                <span class="back-arrow">←</span>
                Back to Tafsirs
            </a>

            <div class="header-badge">
                Tafsir Management
            </div>

            <h1>
                Edit Tafsir
            </h1>

            <p>
                Update the Quranic interpretation and reading level.
            </p>

        </div>


        {{-- =========================================
             FORM
        ========================================= --}}

        <form
            method="POST"
            action="{{ route('admin.tafsir.update', $tafsir) }}"
            id="tafsirForm"
        >

            @csrf
            @method('PUT')


            <div class="edit-card">


                {{-- =====================================
                     SURAH & VERSE
                ===================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-icon">
                            ✦
                        </div>

                        <div class="section-title">

                            <h2>
                                Quran Reference
                            </h2>

                            <p>
                                Select the Surah and verses this tafsir explains.
                            </p>

                        </div>

                    </div>


                    {{-- Surah --}}

                    <div style="margin-bottom: 20px;">

                        <label
                            for="surah_id"
                            class="field-label"
                        >
                            Surah <span>*</span>
                        </label>

                        <div class="input-wrapper">

                            <input
                                id="surah_id"
                                name="surah_id"
                                value="{{ old('surah_id', $tafsir->surah_id) }}"
                                type="number"
                                min="1"
                                max="114"
                                class="form-input"
                                placeholder="Enter Surah number"
                                required
                            >

                        </div>

                        @error('surah_id')

                            <div class="error-message">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- Multiple verses toggle --}}

                    <div class="ayah-mode">

                        <div class="mode-info">

                            <div class="mode-icon">
                                ✧
                            </div>

                            <div>

                                <strong>
                                    Multiple verses
                                </strong>

                                <span>
                                    Enable this if the tafsir covers more than one verse.
                                </span>

                            </div>

                        </div>


                        <label class="switch">

                            <input
                                type="checkbox"
                                id="multiToggle"
                                {{ old('end_verse_id') !== null
                                    ? (old('end_verse_id') != old('start_verse_id') ? 'checked' : '')
                                    : ($tafsir->end_verse_id != $tafsir->start_verse_id ? 'checked' : '') }}
                            >

                            <span class="slider"></span>

                        </label>

                    </div>


                    {{-- Single verse --}}

                    <div
                        id="singleVerse"
                        class="single-verse {{ old('end_verse_id', $tafsir->end_verse_id) ? 'hidden' : '' }}"
                    >

                        <label
                            for="single_verse_id"
                            class="field-label"
                        >
                            Verse Number <span>*</span>
                        </label>

                        <input
                            id="single_verse_id"
                            type="number"
                            min="1"
                            class="form-input number-input"
                            placeholder="Verse number"
                            value="{{ old('start_verse_id', $tafsir->start_verse_id) }}"
                        >

                    </div>


                    {{-- Multiple verses --}}

                    <div
                        id="multiVerse"
                        class="verse-fields multi-verse {{ old('end_verse_id', $tafsir->end_verse_id) ? 'active' : '' }}"
                    >

                        <div class="verse-field">

                            <label
                                for="start_verse_id"
                                class="field-label"
                            >
                                From Verse <span>*</span>
                            </label>

                            <input
                                id="start_verse_id"
                                name="start_verse_id"
                                type="number"
                                min="1"
                                class="form-input"
                                placeholder="Starting verse"
                                value="{{ old('start_verse_id', $tafsir->start_verse_id) }}"
                            >

                        </div>


                        <div class="verse-field">

                            <label
                                for="end_verse_id"
                                class="field-label"
                            >
                                To Verse <span>*</span>
                            </label>

                            <input
                                id="end_verse_id"
                                name="end_verse_id"
                                type="number"
                                min="1"
                                class="form-input"
                                placeholder="Ending verse"
                                value="{{ old('end_verse_id', $tafsir->end_verse_id) }}"
                            >

                        </div>

                    </div>


                    @error('start_verse_id')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror


                    @error('end_verse_id')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =====================================
                     TAFSIR LEVEL
                ===================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-icon">
                            ◈
                        </div>

                        <div class="section-title">

                            <h2>
                                Reading Level
                            </h2>

                            <p>
                                Choose the complexity level of this interpretation.
                            </p>

                        </div>

                    </div>


                    <div class="level-grid">


                        {{-- Beginner --}}

                        <div class="level-option">

                            <input
                                type="radio"
                                id="level_beginner"
                                name="level"
                                value="beginner"
                                {{ old('level', $tafsir->level) === 'beginner' ? 'checked' : '' }}
                            >

                            <label
                                for="level_beginner"
                                class="level-label"
                            >

                                <div class="level-top">

                                    <span class="radio-circle"></span>

                                    <span class="level-name">
                                        Beginner
                                    </span>

                                </div>

                                <p class="level-description">
                                    Simple and accessible explanation for readers who are new to Quranic interpretation.
                                </p>

                            </label>

                        </div>


                        {{-- Advanced --}}

                        <div class="level-option">

                            <input
                                type="radio"
                                id="level_advanced"
                                name="level"
                                value="advanced"
                                {{ old('level', $tafsir->level) === 'advanced' ? 'checked' : '' }}
                            >

                            <label
                                for="level_advanced"
                                class="level-label"
                            >

                                <div class="level-top">

                                    <span class="radio-circle"></span>

                                    <span class="level-name">
                                        Advanced
                                    </span>

                                </div>

                                <p class="level-description">
                                    Detailed interpretation intended for readers seeking deeper Quranic understanding.
                                </p>

                            </label>

                        </div>

                    </div>


                    @error('level')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =====================================
                     TAFSIR EDITOR
                ===================================== --}}

                <div class="form-section">

                    <div class="section-heading">

                        <div class="section-icon">
                            ✎
                        </div>

                        <div class="section-title">

                            <h2>
                                Tafsir Content
                            </h2>

                            <p>
                                Edit and format the interpretation.
                            </p>

                        </div>

                    </div>


                    <div class="editor-description">

                        <span class="editor-status">
                            Rich Text Editor
                        </span>

                    </div>


                    <div class="editor-box">


                        {{-- Toolbar --}}

                        <div class="editor-toolbar">

                            <span class="toolbar-label">
                                TEXT COLOR
                            </span>


                            <button
                                type="button"
                                class="color-btn"
                               data-color="#436E9E"
                                title="Blue"
                                style="background:#1e90ff"
                            ></button>


                            <button
                                type="button"
                                class="color-btn"
                                data-color="#763335"
                                title="Red"
                                style="background:#b82d2d"
                            ></button>

                            <button
                                type="button"
                                class="color-btn"
                                data-color="#ff8c42"
                                title="Orange"
                                style="background:#ff8c42"
                            ></button>


                            <button
                                type="button"
                                class="color-btn"
                                data-color="#34ec34"
                                title="Green"
                                style="background:#0b3d38"
                            ></button>


                            <span class="toolbar-divider"></span>


                            <button
                                type="button"
                                class="clear-btn"
                                id="clearFormat"
                            >
                                Clear Formatting
                            </button>

                        </div>


                        {{-- Editor --}}

                        <div
                            id="editor"
                            class="editor"
                            contenteditable="true"
                        >{!! old('text', $tafsir->text) !!}</div>

                    </div>


                    <input
                        type="hidden"
                        name="text"
                        id="contentField"
                    >


                    @error('text')

                        <div class="error-message">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- =====================================
                     ACTIONS
                ===================================== --}}

                <div class="form-actions">

                    <div class="action-left">
                        Changes will be saved to this tafsir.
                    </div>


                    <div class="action-right">

                        <a
                            href="{{ route('admin.tafsir.index') }}"
                            class="cancel-btn"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="save-btn"
                        >
                            <span class="save-icon">✓</span>
                            Save Changes
                        </button>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

(function () {

    const form = document.getElementById('tafsirForm');

    const multiToggle = document.getElementById('multiToggle');

    const singleVerse = document.getElementById('singleVerse');

    const multiVerse = document.getElementById('multiVerse');

    const singleVerseInput =
        document.getElementById('single_verse_id');

    const startVerseInput =
        document.getElementById('start_verse_id');

    const endVerseInput =
        document.getElementById('end_verse_id');

    const editor =
        document.getElementById('editor');

    const contentField =
        document.getElementById('contentField');

    const colorButtons =
        document.querySelectorAll('.color-btn');

    const clearButton =
        document.getElementById('clearFormat');


    /* =========================================
       VERSE MODE
    ========================================= */

    function updateVerseMode() {

        if (multiToggle.checked) {

            singleVerse.classList.add('hidden');

            multiVerse.classList.add('active');

            singleVerseInput.removeAttribute('name');

            startVerseInput.setAttribute('name', 'start_verse_id');

            endVerseInput.setAttribute('name', 'end_verse_id');

        } else {

            singleVerse.classList.remove('hidden');

            multiVerse.classList.remove('active');

            singleVerseInput.setAttribute('name', 'start_verse_id');

            startVerseInput.removeAttribute('name');

            endVerseInput.removeAttribute('name');

        }

    }


    multiToggle.addEventListener(
        'change',
        updateVerseMode
    );


    updateVerseMode();


    /* =========================================
       COLOR EDITOR
    ========================================= */

    function applyColor(color) {

        editor.focus();

        const selection =
            window.getSelection();

        if (!selection) {
            return;
        }


        try {

            document.execCommand(
                'styleWithCSS',
                false,
                true
            );

            document.execCommand(
                'foreColor',
                false,
                color
            );

        } catch (error) {

            console.error(error);

        }

    }


    colorButtons.forEach(function (button) {

        button.addEventListener(
            'mousedown',
            function (event) {

                event.preventDefault();

            }
        );


        button.addEventListener(
            'click',
            function () {

                applyColor(
                    button.dataset.color
                );

            }
        );

    });


    /* =========================================
       CLEAR FORMAT
    ========================================= */

    clearButton.addEventListener(
        'click',
        function () {

            editor.focus();

            try {

                document.execCommand(
                    'removeFormat',
                    false,
                    null
                );

            } catch (error) {

                console.error(error);

            }

        }
    );


    /* =========================================
       SUBMIT
    ========================================= */

    form.addEventListener(
        'submit',
        function () {

            /*
             * If single verse mode is active,
             * copy the single verse value into
             * start_verse_id before submitting.
             */

            if (!multiToggle.checked) {

                startVerseInput.value =
                    singleVerseInput.value;

            }


            /*
             * Remove end verse when
             * tafsir covers only one verse.
             */

            if (!multiToggle.checked) {

                endVerseInput.removeAttribute('name');

            }


            /*
             * Save HTML editor content.
             */

            contentField.value =
                editor.innerHTML;

        }
    );


})();

</script>

@endsection