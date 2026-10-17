@extends('layouts.app')

@section('content')

<div class="hidayah-reader">

    {{-- ================= HEADER ================= --}}
    <header class="reader-header">

        <div class="ornament ornament-top">
            ❖ ───────── ✦ ───────── ❖
        </div>

        <div class="bismillah">﷽</div>

        <div class="eyebrow">
            QURANIC TAFSIR
        </div>

        <h1>
            {{ $selectedSurah->name_ar }}
        </h1>

        <div class="surah-name-en">
            {{ $selectedSurah->name_en }}
        </div>

        <div class="ornament">
            ❖ ──────── ✦ ──────── ❖
        </div>

    </header>


    {{-- LEVEL SWITCHER --}}
<div class="level-switcher">

    <div class="level-buttons">

        <a href="{{ route('tafsir.reader.show', [
            'surah' => $selectedSurah->slug,
            'level' => 'beginner'
        ]) }}"
           class="level-btn {{ $selectedLevel === 'beginner' ? 'active' : '' }}">

            <span class="level-icon">🌱</span>
            <span>Beginner</span>

        </a>


        <a href="{{ route('tafsir.reader.show', [
            'surah' => $selectedSurah->slug,
            'level' => 'advanced'
        ]) }}"
           class="level-btn {{ $selectedLevel === 'advanced' ? 'active' : '' }}">

            <span class="level-icon">📖</span>
            <span>Advanced</span>

        </a>

    </div>

</div>


    {{-- ================= READER ================= --}}
    <main class="reader-content">

        @forelse($items as $index => $item)

            <article
                class="tafsir-section {{ $index === 0 ? 'active' : '' }}"
                data-section="{{ $index }}"
            >

                {{-- Section heading --}}
                <div class="section-heading">

                    <span class="heading-decoration">۞</span>

                    <div>
                        <span class="section-small">
                            Tafsir
                        </span>

                        <h2>
                            الآيات
                            {{ $item->startVerse->verse_number }}
                            -
                            {{ $item->endVerse->verse_number }}
                        </h2>
                    </div>

                    <span class="heading-decoration">۞</span>

                </div>


                {{-- ================= QURAN ================= --}}
                <section class="quran-card">

                    <div class="quran-top-decoration">
                        ❁
                    </div>

                    <div class="quran-text">

                        @foreach($item->verses as $verse)

                            <span class="ayah">

                                {{ $verse->arabic_text }}

                                <span class="ayah-number">
                                    {{ $verse->verse_number }}
                                </span>

                            </span>

                        @endforeach

                    </div>

                    <div class="quran-bottom-decoration">
                        ❁
                    </div>

                </section>


                {{-- ================= TAFSIR ================= --}}
                <section class="tafsir-card">

                    <div class="tafsir-card-header">

                        <span class="card-icon">
                            ✦
                        </span>

                        <div>
                            <span class="card-label">
                                Interpretation
                            </span>

        
                        </div>

                    </div>


                    <div class="tafsir-body">

                        {!! $item->text !!}

                    </div>

                </section>


                {{-- ================= ACTIONS ================= --}}
                <div class="reader-actions">

                    @if($index > 0)

                        <button
                            type="button"
                            class="reader-btn previous-btn"
                            onclick="showSection({{ $index - 1 }})"
                        >
                            <span>←</span>
                            Previous
                        </button>

                    @else

                        <div></div>

                    @endif


                    {{-- Bookmark --}}
                    <button
                        type="button"
                        class="bookmark-btn"
                        onclick="saveSection({{ $item->id }}, this)"
                    >
                        <span class="bookmark-icon">🔖</span>
                        <span class="bookmark-text">حفظ</span>
                    </button>


                    @if($index < $items->count() - 1)

                        <button
                            type="button"
                            class="reader-btn next-btn"
                            onclick="showSection({{ $index + 1 }})"
                        >
                            Next
                            <span>→</span>
                        </button>

                    @else

                        <div></div>

                    @endif

                </div>


                {{-- Progress --}}
                <div class="section-progress">

                    <span>
                        {{ $index + 1 }}
                    </span>

                    <div class="progress-track">
                        <div
                            class="progress-fill"
                            style="width:
                                {{ (($index + 1) / $items->count()) * 100 }}%"
                        ></div>
                    </div>

                    <span>
                        {{ $items->count() }}
                    </span>

                </div>

            </article>

        @empty

            <div class="empty-reader">

                <div class="empty-icon">
                    ۞
                </div>

                <h2>
                    لا يوجد تفسير
                </h2>

                <p>
                    لم يتم إضافة تفسير لهذه السورة بهذا المستوى بعد.
                </p>

            </div>

        @endforelse

    </main>

</div>


<style>

/* =========================================================
   HIDAYAH TAFSIR READER
========================================================= */

:root {
    --hidayah-green: #123d32;
    --hidayah-green-light: #1b5a49;
    --hidayah-gold: #c7a45a;
    --hidayah-gold-light: #e3c982;
    --hidayah-cream: #faf7ef;
    --hidayah-paper: #fffdf8;
    --hidayah-text: #25332e;
    --hidayah-muted: #78827d;
    --hidayah-border: rgba(199, 164, 90, .28);
}

/* =========================================
   LEVEL SWITCHER
========================================= */

.level-switcher {
    width: min(900px, 92%);
    margin: 25px auto 40px;
    text-align: center;
}

.level-title {
    margin-bottom: 15px;
    color: #78827d;
    font-size: 14px;
    letter-spacing: .5px;
}

.level-buttons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 18px;
}

.level-btn {
    min-width: 170px;
    padding: 14px 28px;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;

    text-decoration: none;

    background: #fffdf8;
    color: #123d32;

    border: 1px solid rgba(199, 164, 90, .35);
    border-radius: 14px;

    font-size: 15px;
    font-weight: 600;

    transition: all .25s ease;

    box-shadow: 0 5px 18px rgba(18, 61, 50, .06);
}

.level-btn:hover {
    transform: translateY(-2px);

    border-color: #c7a45a;

    box-shadow: 0 8px 22px rgba(18, 61, 50, .10);
}

.level-btn.active {
    background: #123d32;
    color: #fff;

    border-color: #c7a45a;

    box-shadow:
        0 8px 25px rgba(18, 61, 50, .20),
        inset 0 0 0 1px rgba(199, 164, 90, .35);
}

.level-icon {
    font-size: 18px;
}

@media (max-width: 600px) {

    .level-buttons {
        flex-direction: column;
        gap: 10px;
    }

    .level-btn {
        width: 100%;
        max-width: 280px;
    }
}
/* =========================================
   SECTION NAVIGATION
========================================= */

.section-navigation {
    width: 100%;
    margin-top: 45px;
    padding-top: 28px;

    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;

    gap: 35px;

    border-top: 1px solid rgba(199, 164, 90, .20);
}

.nav-btn {
    min-height: 64px;

    display: flex;
    align-items: center;

    gap: 14px;

    padding: 12px 20px;

    background: #fffdf8;
    color: #123d32;

    border: 1px solid rgba(199, 164, 90, .30);
    border-radius: 14px;

    cursor: pointer;

    transition: all .25s ease;
}

.nav-btn:hover {
    transform: translateY(-2px);

    border-color: #c7a45a;

    box-shadow: 0 8px 20px rgba(18, 61, 50, .10);
}

.prev-btn {
    justify-self: start;
}

.next-btn {
    justify-self: end;
}

.nav-arrow {
    font-size: 24px;
    color: #c7a45a;
}

.nav-btn small {
    display: block;

    color: #78827d;

    font-size: 11px;

    margin-bottom: 3px;
}

.nav-btn strong {
    display: block;

    font-size: 13px;

    font-weight: 600;
}

.save-btn {
    min-width: 105px;
    height: 48px;

    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;

    border: 1px solid rgba(199, 164, 90, .40);
    border-radius: 12px;

    background: #123d32;
    color: #fff;

    cursor: pointer;

    transition: all .25s ease;
}

.save-btn:hover {
    transform: translateY(-2px);
    background: #1b5a49;
}

.save-btn.saved {
    background: #c7a45a;
    color: #123d32;
}

.nav-placeholder {
    min-height: 1px;
}

@media (max-width: 700px) {

    .section-navigation {
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .save-btn {
        grid-column: 1 / -1;
        grid-row: 1;
        justify-self: center;
    }

    .prev-btn {
        grid-column: 1;
        grid-row: 2;
    }

    .next-btn {
        grid-column: 2;
        grid-row: 2;
    }

    .nav-placeholder {
        display: block;
    }
}
/* =========================================================
   MAIN
========================================================= */

.hidayah-reader {
    width: min(1100px, calc(100% - 30px));
    margin: 35px auto 80px;
    color: var(--hidayah-text);
}


/* =========================================================
   HEADER
========================================================= */

.reader-header {
    text-align: center;
    padding: 35px 20px 25px;
}

.ornament {
    color: var(--hidayah-gold);
    font-size: 17px;
    letter-spacing: 3px;
    margin: 12px 0;
}

.ornament-top {
    opacity: .85;
}

.bismillah {
    font-size: 42px;
    color: var(--hidayah-green);
    margin: 10px 0 15px;
    font-family: "Amiri", "Traditional Arabic", serif;
}

.eyebrow {
    color: var(--hidayah-gold);
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 4px;
}

.reader-header h1 {
    margin: 8px 0 2px;
    font-size: clamp(36px, 6vw, 55px);
    font-family: "Amiri", "Traditional Arabic", serif;
    color: var(--hidayah-green);
}

.surah-name-en {
    color: var(--hidayah-muted);
    font-size: 17px;
    letter-spacing: 2px;
}


/* =========================================================
   LEVEL
========================================================= */

.level-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
    margin: 15px auto 35px;
}

.level-label {
    color: var(--hidayah-muted);
    font-size: 13px;
    font-weight: 600;
}

.level-switcher {
    display: flex;
    gap: 7px;
    padding: 5px;
    background: rgba(18, 61, 50, .06);
    border: 1px solid var(--hidayah-border);
    border-radius: 14px;
}

.level-btn {
    text-decoration: none;
    color: var(--hidayah-green);
    padding: 9px 18px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    transition: .25s ease;
}

.level-btn:hover {
    background: rgba(199, 164, 90, .14);
}

.level-btn.active {
    background: var(--hidayah-green);
    color: white;
    box-shadow: 0 5px 15px rgba(18, 61, 50, .18);
}


/* =========================================================
   SECTION
========================================================= */

.tafsir-section {
    display: none;
    animation: fadeIn .35s ease;
}

.tafsir-section.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(8px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* =========================================================
   SECTION HEADING
========================================================= */

.section-heading {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 18px;
    margin-bottom: 22px;
}

.heading-decoration {
    color: var(--hidayah-gold);
    font-size: 25px;
}

.section-heading {
    text-align: center;
}

.section-small {
    display: block;
    color: var(--hidayah-gold);
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 3px;
    margin-bottom: 3px;
}

.section-heading h2 {
    margin: 0;
    color: var(--hidayah-green);
    font-family: "Amiri", "Traditional Arabic", serif;
    font-size: 27px;
}


/* =========================================================
   QURAN CARD
========================================================= */

.quran-card {
    position: relative;
    background:
        radial-gradient(
            circle at 50% 50%,
            rgba(199, 164, 90, .055),
            transparent 65%
        ),
        var(--hidayah-cream);

    border: 1px solid var(--hidayah-border);
    border-radius: 22px;
    padding: 45px 35px;

    box-shadow:
        0 12px 35px rgba(18, 61, 50, .07);

    text-align: center;
}

.quran-card::before {
    content: "";
    position: absolute;
    inset: 10px;
    border: 1px solid rgba(199, 164, 90, .15);
    border-radius: 16px;
    pointer-events: none;
}

.quran-top-decoration,
.quran-bottom-decoration {
    color: var(--hidayah-gold);
    font-size: 22px;
}

.quran-bottom-decoration {
    margin-top: 20px;
}

.quran-text {
    direction: rtl;
    text-align: center;
    line-height: 2.7;
    font-family: "Amiri", "Traditional Arabic", serif;
    font-size: clamp(25px, 3vw, 34px);
    color: #182b25;
}

.ayah {
    display: inline;
}

.ayah-number {
    display: inline-flex;
    justify-content: center;
    align-items: center;

    width: 31px;
    height: 31px;

    margin: 0 8px;

    border: 1px solid var(--hidayah-gold);
    border-radius: 50%;

    color: var(--hidayah-gold);
    font-family: Arial, sans-serif;
    font-size: 12px;
}


/* =========================================================
   TAFSIR CARD
========================================================= */

.tafsir-card {
    margin-top: 28px;
    background: var(--hidayah-paper);
    border: 1px solid var(--hidayah-border);
    border-radius: 20px;
    overflow: hidden;

    box-shadow:
        0 8px 28px rgba(18, 61, 50, .055);
}

.tafsir-card-header {
    display: flex;
    align-items: center;
    gap: 13px;

    padding: 18px 24px;

    background:
        linear-gradient(
            135deg,
            rgba(18, 61, 50, .055),
            rgba(199, 164, 90, .07)
        );

    border-bottom: 1px solid var(--hidayah-border);
}

.card-icon {
    display: flex;
    justify-content: center;
    align-items: center;

    width: 42px;
    height: 42px;

    border-radius: 12px;

    background: var(--hidayah-green);
    color: var(--hidayah-gold-light);

    font-size: 19px;
}

.card-label {
    display: block;
    color: var(--hidayah-gold);
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 2px;
}

.tafsir-card-header h3 {
    margin: 2px 0 0;
    font-size: 19px;
    color: var(--hidayah-green);
}


/* =========================================================
   STORED HTML
========================================================= */

.tafsir-body {
    padding: 30px;
    line-height: 2;
    font-size: 17px;
}


/* الألوان الموجودة بملفات HTML الأصلية */

.red-text {
    color: #C00000 !important;
}

.green-text {
    color: #008000 !important;
}

.blue-text {
    color: #0066FF !important;
}

.orange-text {
    color: #FF6600 !important;
}

.purple-text {
    color: #800080 !important;
}

.highlight-yellow {
    background-color: #fff2a8;
}


/* =========================================================
   NAVIGATION
========================================================= */

.reader-actions {
    display: grid;
    grid-template-columns: 1fr auto 1fr;
    align-items: center;
    gap: 15px;

    margin-top: 30px;
}

.reader-btn,
.bookmark-btn {
    border: 0;
    cursor: pointer;
    font-family: inherit;
    transition: .25s ease;
}

.reader-btn {
    min-width: 125px;
    padding: 12px 20px;
    border-radius: 12px;

    background: var(--hidayah-green);
    color: white;

    font-size: 14px;
    font-weight: 600;
}

.reader-btn:hover {
    background: var(--hidayah-green-light);
    transform: translateY(-2px);
}

.previous-btn {
    justify-self: start;
}

.next-btn {
    justify-self: end;
}

.bookmark-btn {
    padding: 12px 22px;
    border-radius: 12px;

    background: transparent;
    color: var(--hidayah-gold);

    border: 1px solid var(--hidayah-border);

    font-size: 14px;
}

.bookmark-btn:hover,
.bookmark-btn.saved {
    background: var(--hidayah-gold);
    color: white;
}

.bookmark-icon {
    margin-right: 5px;
}


/* =========================================================
   PROGRESS
========================================================= */

.section-progress {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;

    margin-top: 22px;

    color: var(--hidayah-muted);
    font-size: 12px;
}

.progress-track {
    width: 150px;
    height: 4px;
    overflow: hidden;
    border-radius: 10px;
    background: rgba(18, 61, 50, .1);
}

.progress-fill {
    height: 100%;
    border-radius: inherit;
    background: var(--hidayah-gold);
    transition: width .3s ease;
}


/* =========================================================
   EMPTY
========================================================= */

.empty-reader {
    text-align: center;
    padding: 80px 20px;
}

.empty-icon {
    color: var(--hidayah-gold);
    font-size: 50px;
}

.empty-reader h2 {
    color: var(--hidayah-green);
}


/* =========================================================
   MOBILE
========================================================= */

@media (max-width: 700px) {

    .hidayah-reader {
        width: min(100% - 18px, 1100px);
        margin-top: 15px;
    }

    .reader-header {
        padding-top: 20px;
    }

    .bismillah {
        font-size: 34px;
    }

    .quran-card {
        padding: 35px 18px;
        border-radius: 17px;
    }

    .quran-text {
        font-size: 25px;
        line-height: 2.5;
    }

    .tafsir-body {
        padding: 20px 17px;
        font-size: 16px;
    }

    .reader-actions {
        grid-template-columns: 1fr 1fr;
    }

    .bookmark-btn {
        grid-column: 1 / -1;
        grid-row: 1;
        justify-self: center;
    }

    .previous-btn {
        grid-column: 1;
        grid-row: 2;
    }

    .next-btn {
        grid-column: 2;
        grid-row: 2;
        justify-self: end;
    }

    .level-wrapper {
        flex-direction: column;
    }

}

</style>


<script>

let currentSection = 0;

const sections = document.querySelectorAll('.tafsir-section');


function showSection(index)
{
    if (index < 0 || index >= sections.length) {
        return;
    }

    sections.forEach(section => {
        section.classList.remove('active');
    });

    sections[index].classList.add('active');

    currentSection = index;

    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}


function saveSection(tafsirId, button)
{
    button.classList.toggle('saved');

    const text = button.querySelector('.bookmark-text');

    if (button.classList.contains('saved')) {
        text.textContent = 'تم الحفظ';
    } else {
        text.textContent = 'حفظ';
    }

    /*
     * لاحقاً نربط هذا الـ button
     * بجدول bookmarks في Laravel.
     */

    console.log('Tafsir section:', tafsirId);
}


// أول قسم
if (sections.length > 0) {
    showSection(0);
}

</script>

@endsection