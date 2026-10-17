@extends('layouts.app')

@section('title', 'Create Tafsir')

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
    }

    .tafsir-page {
        min-height: calc(100vh - 80px);
        background:
            radial-gradient(circle at 10% 10%, rgba(198,161,91,.08), transparent 25%),
            radial-gradient(circle at 90% 90%, rgba(11,64,56,.06), transparent 30%),
            var(--cream);
        padding: 50px 20px 80px;
    }

    .tafsir-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header */
    .page-header {
        margin-bottom: 30px;
    }

    .header-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 13px;
        border-radius: 30px;
        background: var(--quran-green-light);
        color: var(--quran-green);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 14px;
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
        margin: 10px 0 0;
        color: var(--muted);
        font-size: 15px;
    }

    /* Main Card */
    .tafsir-card {
        background: rgba(255,255,255,.96);
        border: 1px solid var(--border);
        border-radius: 22px;
        box-shadow:
            0 20px 50px rgba(20, 40, 35, .07),
            0 4px 12px rgba(20, 40, 35, .04);
        overflow: hidden;
    }

    .card-top {
        height: 5px;
        background: linear-gradient(
            90deg,
            var(--quran-green-dark),
            var(--gold),
            var(--quran-green)
        );
    }

    .form-content {
        padding: 34px;
    }

    /* Sections */
    .form-section {
        margin-bottom: 32px;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .section-icon {
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: var(--quran-green-light);
        color: var(--quran-green);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .section-heading h2 {
        margin: 0;
        font-size: 16px;
        color: var(--text);
        font-weight: 700;
    }

    .section-heading span {
        display: block;
        margin-top: 2px;
        font-size: 12px;
        color: var(--muted);
        font-weight: 400;
    }

    /* Fields */
    .field-group {
        margin-bottom: 20px;
    }

    .field-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
        color: #26312e;
        font-size: 13px;
        font-weight: 700;
    }

    .field-label .optional {
        color: #9aa19e;
        font-size: 11px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        height: 48px;
        padding: 0 15px;
        border: 1px solid #ddd9cf;
        border-radius: 11px;
        background: #fff;
        color: var(--text);
        font-size: 14px;
        outline: none;
        transition: .2s ease;
        box-sizing: border-box;
    }

    .form-input:hover {
        border-color: #c8c1b2;
    }

    .form-input:focus {
        border-color: var(--quran-green);
        box-shadow: 0 0 0 4px rgba(11,64,56,.08);
    }

    .form-input::placeholder {
        color: #adb3b0;
    }

    /* Surah field */
    .surah-input-wrapper {
        position: relative;
    }

    .surah-input-wrapper .field-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gold);
        pointer-events: none;
    }

    .surah-input-wrapper .form-input {
        padding-left: 43px;
    }

    /* Ayah Range */
    .ayah-mode {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px 17px;
        background: #f8f8f5;
        border: 1px solid #e9e5db;
        border-radius: 13px;
        margin-bottom: 15px;
    }

    .mode-text strong {
        display: block;
        color: var(--text);
        font-size: 13px;
        margin-bottom: 3px;
    }

    .mode-text span {
        color: var(--muted);
        font-size: 11px;
    }

    .switch {
        position: relative;
        width: 45px;
        height: 25px;
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
        background: #d7d9d5;
        border-radius: 30px;
        transition: .25s ease;
    }

    .slider::before {
        content: "";
        position: absolute;
        width: 19px;
        height: 19px;
        left: 3px;
        top: 3px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0,0,0,.15);
        transition: .25s ease;
    }

    .switch input:checked + .slider {
        background: var(--quran-green);
    }

    .switch input:checked + .slider::before {
        transform: translateX(20px);
    }

    .ayah-fields {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .ayah-field {
        transition: .2s ease;
    }

    .ayah-field.hidden-field {
        display: none;
    }

    .range-separator {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 13px;
    }

    /* Level */
    .level-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .level-option {
        position: relative;
    }

    .level-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .level-card {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 16px;
        border: 1px solid #e2dfd6;
        border-radius: 13px;
        cursor: pointer;
        transition: .2s ease;
        background: #fff;
    }

    .level-card:hover {
        border-color: #bdb5a5;
        transform: translateY(-1px);
    }

    .level-radio {
        width: 19px;
        height: 19px;
        border-radius: 50%;
        border: 2px solid #c6cac7;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .level-radio::after {
        content: "";
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: var(--quran-green);
        transform: scale(0);
        transition: .2s ease;
    }

    .level-option input:checked + .level-card {
        border-color: var(--quran-green);
        background: #f4f8f6;
        box-shadow: 0 5px 15px rgba(11,64,56,.06);
    }

    .level-option input:checked + .level-card .level-radio {
        border-color: var(--quran-green);
    }

    .level-option input:checked + .level-card .level-radio::after {
        transform: scale(1);
    }

    .level-content strong {
        display: block;
        color: var(--text);
        font-size: 14px;
        margin-bottom: 3px;
    }

    .level-content span {
        display: block;
        color: var(--muted);
        font-size: 11px;
    }

    /* Editor */
    .editor-wrapper {
        border: 1px solid #dcd8ce;
        border-radius: 15px;
        overflow: hidden;
        background: #f7f4ee;
        box-shadow: 0 12px 25px rgba(6, 45, 39, .10);
    }

    .editor-toolbar {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
        padding: 12px 14px;
        background: #0f4038;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }

    .toolbar-title {
        color: rgba(255,255,255,.65);
        font-size: 11px;
        font-weight: 600;
        margin-right: 7px;
        letter-spacing: .03em;
    }

    .color-btn {
        width: 29px;
        height: 29px;
        border-radius: 8px;
        border: 2px solid rgba(255,255,255,.15);
        cursor: pointer;
        transition: .2s ease;
        padding: 0;
    }

    .color-btn:hover {
        transform: translateY(-2px);
        border-color: rgba(255,255,255,.65);
    }

    .clear-format {
        margin-left: auto;
        border: 1px solid rgba(255,255,255,.14);
        background: rgba(255,255,255,.05);
        color: rgba(255,255,255,.75);
        border-radius: 8px;
        height: 29px;
        padding: 0 11px;
        cursor: pointer;
        font-size: 11px;
        transition: .2s ease;
    }

    .clear-format:hover {
        background: rgba(255,255,255,.10);
        color: white;
    }

    .editor {
        min-height: 300px;
        max-height: 600px;
        overflow-y: auto;
        padding: 24px;
        color: #1d2d29;
        background:
            radial-gradient(circle at 80% 10%, rgba(198,161,91,.08), transparent 25%),
            linear-gradient(145deg, #fffdf9, #f4efe5);
        font-family: Georgia, "Times New Roman", serif;
        font-size: 17px;
        line-height: 1.9;
        outline: none;
        direction: ltr;
        text-align: left;
    }

    .editor:focus {
        box-shadow: inset 0 0 0 1px rgba(198,161,91,.3);
    }

    .editor:empty::before {
        content: "Write the tafsir content here...";
        color: rgba(255,255,255,.35);
        pointer-events: none;
    }

    .editor::-webkit-scrollbar {
        width: 7px;
    }

    .editor::-webkit-scrollbar-track {
        background: rgba(15,64,56,.06);
    }

    .editor::-webkit-scrollbar-thumb {
        background: rgba(15,64,56,.25);
        border-radius: 10px;
    }

    .editor-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 14px;
        background: #082f2a;
        border-top: 1px solid rgba(255,255,255,.07);
        color: rgba(255,255,255,.42);
        font-size: 10px;
    }

    /* Validation */
    .error-message {
        margin-top: 7px;
        color: var(--danger);
        font-size: 12px;
    }

    /* Footer Actions */
    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 25px;
        border-top: 1px solid #eeeae2;
    }

    .btn {
        height: 46px;
        padding: 0 22px;
        border-radius: 11px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: .2s ease;
    }

    .btn-secondary {
        color: #59615e;
        background: #f5f4f0;
        border: 1px solid #e4e0d7;
    }

    .btn-secondary:hover {
        background: #eceae4;
    }

    .btn-primary {
        color: white;
        background: linear-gradient(
            135deg,
            var(--quran-green),
            var(--quran-green-dark)
        );
        border: 1px solid var(--quran-green);
        box-shadow: 0 7px 18px rgba(11,64,56,.18);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(11,64,56,.25);
    }

    /* Responsive */
    @media (max-width: 700px) {
        .tafsir-page {
            padding: 30px 12px 50px;
        }

        .form-content {
            padding: 22px;
        }

        .page-header h1 {
            font-size: 29px;
        }

        .ayah-fields,
        .level-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column-reverse;
        }

        .form-actions .btn {
            width: 100%;
        }

        .clear-format {
            margin-left: 0;
        }

        .editor {
            min-height: 250px;
            font-size: 16px;
        }
    }
</style>


<div class="tafsir-page">

    <div class="tafsir-container">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="header-badge">
                Tafsir Management
            </div>

            <h1>Create Tafsir</h1>

            <p>
                Add a new interpretation and associate it with one or more Quranic verses.
            </p>
        </div>


        {{-- Main Form Card --}}
        <div class="tafsir-card">

            <div class="card-top"></div>

            <div class="form-content">

                <form method="POST" action="{{ route('admin.tafsir.store') }}">
                    @csrf


                    {{-- Surah Section --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-icon">☾</div>

                            <div>
                                <h2>Surah</h2>
                                <span>Select the Quranic chapter for this tafsir.</span>
                            </div>
                        </div>

                        <div class="field-group">

                            <label class="field-label">
                                <span>Surah Name or Number</span>
                                <span class="optional">Required</span>
                            </label>

                            <div class="surah-input-wrapper">

                                <span class="field-icon">✦</span>

                                <input
                                    type="number"
                                    name="surah_id"
                                    value="{{ old('surah_id') }}"
                                    class="form-input"
                                    placeholder="e.g. 2"
                                    required
                                >

                            </div>

                            @error('surah_id')
                                <div class="error-message">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>


                    {{-- Ayah Section --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-icon">¶</div>

                            <div>
                                <h2>Ayah Range</h2>
                                <span>Choose a single verse or a range of verses.</span>
                            </div>
                        </div>


                        <div class="ayah-mode">

                            <div class="mode-text">
                                <strong>Multiple verses</strong>
                                <span>Enable this when the tafsir covers more than one ayah.</span>
                            </div>

                            <label class="switch">
                                <input
                                    type="checkbox"
                                    id="multiToggle"
                                    {{ old('end_verse_id') ? 'checked' : '' }}
                                >
                                <span class="slider"></span>
                            </label>

                        </div>


                        <div class="ayah-fields">

                            <div class="ayah-field">

                                <label class="field-label">
                                    <span id="fromLabel">Ayah Number</span>
                                </label>

                                <input
                                    name="start_verse_id"
                                    value="{{ old('start_verse_id') }}"
                                    type="number"
                                    min="1"
                                    class="form-input"
                                    placeholder="e.g. 1"
                                    required
                                >

                                @error('start_verse_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror

                            </div>


                            <div
                                class="ayah-field"
                                id="ayahToField"
                                style="{{ old('end_verse_id') ? '' : 'display:none;' }}"
                            >

                                <label class="field-label">
                                    <span>To Ayah</span>
                                </label>

                                <input
                                    name="end_verse_id"
                                    value="{{ old('end_verse_id') }}"
                                    type="number"
                                    min="1"
                                    class="form-input"
                                    placeholder="e.g. 5"
                                >

                                @error('end_verse_id')
                                    <div class="error-message">{{ $message }}</div>
                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- Tafsir Level --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-icon">◈</div>

                            <div>
                                <h2>Tafsir Level</h2>
                                <span>Select the intended reading level.</span>
                            </div>
                        </div>


                        <div class="level-grid">

                            <label class="level-option">

                                <input
                                    type="radio"
                                    name="level"
                                    value="beginner"
                                    {{ old('level', 'beginner') == 'beginner' ? 'checked' : '' }}
                                >

                                <div class="level-card">

                                    <div class="level-radio"></div>

                                    <div class="level-content">
                                        <strong>Beginner</strong>
                                        <span>Simple and accessible explanation</span>
                                    </div>

                                </div>

                            </label>


                            <label class="level-option">

                                <input
                                    type="radio"
                                    name="level"
                                    value="advanced"
                                    {{ old('level') == 'advanced' ? 'checked' : '' }}
                                >

                                <div class="level-card">

                                    <div class="level-radio"></div>

                                    <div class="level-content">
                                        <strong>Advanced</strong>
                                        <span>Detailed and deeper explanation</span>
                                    </div>

                                </div>

                            </label>

                        </div>

                    </div>


                    {{-- Tafsir Editor --}}
                    <div class="form-section">

                        <div class="section-heading">
                            <div class="section-icon">✎</div>

                            <div>
                                <h2>Tafsir Content</h2>
                                <span>Write and format the interpretation below.</span>
                            </div>
                        </div>


                        <div class="editor-wrapper">

                            <div class="editor-toolbar">

                                <span class="toolbar-title">
                                    Text Color
                                </span>

                                <button
                                    type="button"
                                    class="color-btn"
                                    data-color="#436E9E"
                                    title="Blue"
                                    style="background:#4da3ff">
                                </button>

                                <button
                                    type="button"
                                    class="color-btn"
                                    data-color="#763335"
                                    title="Red"
                                    style="background:#d34a4a">
                                </button>


                                <button
                                    type="button"
                                    class="color-btn"
                                    data-color="#f28b4b"
                                    title="Orange"
                                    style="background:#f28b4b">
                                </button>

                                <button
                                    type="button"
                                    class="color-btn"
                                    data-color="#2E622E"
                                    title="Green"
                                    style="background:#76c7a8">
                                </button>

                                <button
                                    type="button"
                                    class="clear-format"
                                    id="clearFormat">
                                    Clear Formatting
                                </button>

                            </div>


                            <div
                                id="editor"
                                class="editor"
                                contenteditable="true"
                                spellcheck="true"
                            >{!! old('text') !!}</div>


                            <div class="editor-footer">
                                <span>Rich text editor</span>
                                <span id="wordCount">0 words</span>
                            </div>

                        </div>


                        <input
                            type="hidden"
                            name="text"
                            id="contentField"
                        >

                        @error('text')
                            <div class="error-message">{{ $message }}</div>
                        @enderror

                    </div>


                    {{-- Actions --}}
                    <div class="form-actions">

                        <a
                            href="{{ route('admin.tafsir.index') }}"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </a>

                        <button
                            class="btn btn-primary"
                            type="submit"
                        >
                            <span>Save Tafsir</span>
                            <span>→</span>
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>

</div>


<script>
(function () {

    const multiToggle = document.getElementById('multiToggle');
    const ayahToField = document.getElementById('ayahToField');
    const fromLabel = document.getElementById('fromLabel');
    const ayahToInput = document.querySelector('input[name="end_verse_id"]');

    function updateAyahMode() {

        if (multiToggle.checked) {

            ayahToField.style.display = 'block';
            fromLabel.textContent = 'From Ayah';

            ayahToInput.required = true;

        } else {

            ayahToField.style.display = 'none';
            fromLabel.textContent = 'Ayah Number';

            ayahToInput.required = false;
            ayahToInput.value = '';

        }
    }

    multiToggle.addEventListener('change', updateAyahMode);


    /* Color toolbar */

    const editor = document.getElementById('editor');
    const contentField = document.getElementById('contentField');
    const colorButtons = document.querySelectorAll('.color-btn');
    const clearButton = document.getElementById('clearFormat');
    const wordCount = document.getElementById('wordCount');


    function applyColor(color) {

        editor.focus();

        const selection = window.getSelection();

        if (!selection || selection.rangeCount === 0) {
            return;
        }

        try {
            document.execCommand('foreColor', false, color);
        } catch (error) {
            console.error(error);
        }
    }


    colorButtons.forEach(button => {

        button.addEventListener('click', function () {

            applyColor(this.dataset.color);

        });

    });


    clearButton.addEventListener('click', function () {

        editor.focus();

        try {
            document.execCommand('removeFormat', false, null);
        } catch (error) {
            console.error(error);
        }

    });


    /* Word counter */

    function updateWordCount() {

        const text = editor.innerText.trim();

        if (!text) {
            wordCount.textContent = '0 words';
            return;
        }

        const words = text.split(/\s+/).filter(Boolean).length;

        wordCount.textContent =
            words + (words === 1 ? ' word' : ' words');
    }


    editor.addEventListener('input', updateWordCount);

    updateWordCount();


    /* Submit */

    const form = editor.closest('form');

    form.addEventListener('submit', function () {

        contentField.value = editor.innerHTML;

    });

})();
</script>

@endsection