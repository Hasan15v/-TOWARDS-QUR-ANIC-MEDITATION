@extends('layouts.app')
@section('title', 'View Tafsir')

@section('content')

<style>
  /* ================================
     PAGE
  ================================= */
  .tafsir-page {
    min-height: calc(100vh - 80px);
    padding: 45px 20px 70px;

    background:
      radial-gradient(
        circle at 10% 10%,
        rgba(198,161,91,.08),
        transparent 25%
      ),
      radial-gradient(
        circle at 90% 90%,
        rgba(11,64,56,.06),
        transparent 30%
      ),
      linear-gradient(
        135deg,
        #faf8f3 0%,
        #f4efe5 100%
      );

    position: relative;
    overflow: hidden;
  }

  /* Decorative glow */
  .tafsir-page::before {
    content: "";
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;

    background: rgba(198,161,91,.06);
    filter: blur(20px);

    top: -180px;
    right: -120px;

    pointer-events: none;
  }

  .tafsir-page::after {
    content: "";
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;

    background: rgba(11,64,56,.04);
    filter: blur(20px);

    bottom: -130px;
    left: -100px;

    pointer-events: none;
  }


  /* ================================
     MAIN WRAPPER
  ================================= */
  .tafsir-view-wrap {
    position: relative;
    z-index: 1;

    max-width: 1000px;
    margin: 0 auto;
  }


  /* ================================
     HEADER
  ================================= */
  .tafsir-header {
    display: flex;
    justify-content: space-between;
    align-items: center;

    gap: 25px;
    margin-bottom: 24px;
  }

  .tafsir-title-area {
    position: relative;
  }

  .tafsir-kicker {
    display: flex;
    align-items: center;
    gap: 9px;

    color: #d6a84f;

    font-family: "DM Sans", sans-serif;
    font-size: 0.72rem;
    font-weight: 700;

    text-transform: uppercase;
    letter-spacing: 2px;

    margin-bottom: 8px;
  }

  .tafsir-kicker span {
    width: 25px;
    height: 1px;
    background: #d6a84f;
    opacity: 0.8;
  }

  .tafsir-title {
    margin: 0;

    color: #07342e;

    font-family: Georgia, serif;
    font-size: 2rem;
    font-weight: 500;

    letter-spacing: -0.5px;
  }

  .tafsir-title strong {
    color: #0b6357;
    font-weight: 700;
  }

  .tafsir-meta {
    margin-top: 8px;

    color: rgba(220, 239, 231, 0.62);

    font-family: "DM Sans", sans-serif;
    font-size: 0.86rem;
  }


  /* ================================
     BUTTONS
  ================================= */
  .tafsir-actions {
    display: flex;
    align-items: center;
    gap: 9px;
  }

  .tafsir-actions a {
    text-decoration: none;
    font-family: "DM Sans", sans-serif;

    transition:
      transform 0.2s ease,
      background 0.2s ease,
      box-shadow 0.2s ease;
  }

  .edit-btn {
    padding: 10px 18px;

    color: #17342d;

    background: #d6a84f;

    border-radius: 9px;

    font-size: 0.86rem;
    font-weight: 700;

    box-shadow: 0 6px 18px rgba(214, 168, 79, 0.16);
  }

  .edit-btn:hover {
    background: #e3bb67;
    transform: translateY(-2px);
    box-shadow: 0 9px 22px rgba(214, 168, 79, 0.22);
  }

  .back-btn {
    padding: 9px 15px;

    color: #52645e;

    background: #fff;

    border: 1px solid #dcd8ce;

    border-radius: 9px;

    font-size: 0.86rem;
  }

  .back-btn:hover {
    color: #0b4038;
    background: #f4efe5;
    transform: translateY(-2px);
  }


  /* ================================
     TAFSIR CARD
  ================================= */
  .tafsir-card {
    position: relative;

    background: rgba(255,255,255,.96);

    border: 1px solid #e5e0d5;

    border-radius: 20px;

    padding: 4px;

    box-shadow:
      0 20px 50px rgba(20, 40, 35, .07),
      0 4px 12px rgba(20, 40, 35, .04);
  }


  /* Gold top line */
  .tafsir-card::before {
    content: "";

    position: absolute;

    top: 0;
    left: 12%;
    right: 12%;

    height: 2px;

    background:
      linear-gradient(
        90deg,
        transparent,
        #d6a84f,
        transparent
      );

    opacity: 0.65;
  }


  /* ================================
     TEXT AREA
  ================================= */
  .tafsir-box {
    position: relative;

    padding: 34px 38px;

    min-height: 230px;

    border-radius: 17px;

    /*
      أخضر فاتح حتى تظهر الألوان الموجودة
      داخل HTML الخاص بالنص
    */
    background:
      radial-gradient(
        circle at 85% 10%,
        rgba(214, 168, 79, 0.07),
        transparent 30%
      ),
      linear-gradient(145deg, #fffdf9, #f4efe5);

    /*
      اللون الافتراضي فقط للنصوص التي ليس لها لون
    */
    color: #1d2d29;

    font-family: "DM Sans", sans-serif;

    line-height: 1.9;

    overflow-wrap: break-word;
  }


  /* ================================
     TEXT ELEMENTS
  ================================= */

  .tafsir-box p {
    margin: 0 0 15px;
    line-height: 1.95;
    font-size: 1.04rem;
  }

  .tafsir-box div {
    line-height: 1.95;
    margin-bottom: 7px;
  }

  .tafsir-box span {
    line-height: inherit;
  }

  .tafsir-box strong,
  .tafsir-box b {
    font-weight: 700;
  }

  .tafsir-box em,
  .tafsir-box i {
    font-style: italic;
  }


  /* ================================
     HEADINGS
  ================================= */

  .tafsir-box h1,
  .tafsir-box h2,
  .tafsir-box h3,
  .tafsir-box h4 {
    margin-top: 24px;
    margin-bottom: 13px;

    color: #0b4038;

    font-family: Georgia, serif;
    line-height: 1.4;
  }

  .tafsir-box h1 {
    font-size: 1.7rem;
  }

  .tafsir-box h2 {
    font-size: 1.45rem;
  }

  .tafsir-box h3 {
    font-size: 1.25rem;
  }


  /* ================================
     LISTS
  ================================= */

  .tafsir-box ul,
  .tafsir-box ol {
    margin: 14px 0;
    padding-left: 28px;

    line-height: 1.9;
  }


  /* ================================
     BLOCKQUOTE
  ================================= */

  .tafsir-box blockquote {
    margin: 20px 0;

    padding: 14px 20px;

    background: rgba(0, 0, 0, 0.13);

    border-left: 3px solid #d6a84f;

    border-radius: 0 10px 10px 0;

    color: #52645e;
  }


  /* ================================
     LINKS
  ================================= */

  .tafsir-box a {
    color: #0b6357;
    text-decoration: underline;
    text-underline-offset: 3px;
  }

  .tafsir-box a:hover {
    color: #075148;
  }


  /* ================================
     HR
  ================================= */

  .tafsir-box hr {
    border: 0;

    border-top:
      1px solid #dcd8ce;

    margin: 25px 0;
  }


  /* ================================
     FOOTER DECORATION
  ================================= */

  .tafsir-footer-decoration {
    display: flex;
    align-items: center;
    justify-content: center;

    gap: 12px;

    margin-top: 20px;

    color: rgba(214, 168, 79, 0.65);

    font-size: 13px;
  }

  .tafsir-footer-decoration::before,
  .tafsir-footer-decoration::after {
    content: "";

    width: 55px;
    height: 1px;

    background:
      linear-gradient(
        90deg,
        transparent,
        rgba(214, 168, 79, 0.4)
      );
  }

  .tafsir-footer-decoration::after {
    background:
      linear-gradient(
        90deg,
        rgba(214, 168, 79, 0.4),
        transparent
      );
  }


  /* ================================
     MOBILE
  ================================= */

  @media (max-width: 700px) {

    .tafsir-page {
      padding: 28px 14px 45px;
    }

    .tafsir-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .tafsir-title {
      font-size: 1.65rem;
    }

    .tafsir-actions {
      width: 100%;
    }

    .tafsir-actions a {
      flex: 1;
      text-align: center;
    }

    .tafsir-box {
      padding: 25px 20px;
    }

    .tafsir-box p,
    .tafsir-box div {
      font-size: 0.98rem;
    }

    .tafsir-card {
      border-radius: 16px;
    }

    .tafsir-box {
      border-radius: 13px;
    }
  }
</style>

<div class="tafsir-page">

  <div class="tafsir-view-wrap">

<!-- HEADER -->
<div class="tafsir-header">

  <div class="tafsir-title-area">

    <div class="tafsir-kicker">
      <span></span>
      QURANIC TAFSIR
    </div>

    <h1 class="tafsir-title">
      Tafsir —
      <strong>{{ $tafsir->surah_id }}</strong>
    </h1>

    <div class="tafsir-meta">
      Ayah:
      {{ $tafsir->start_verse_id }}
      {{ $tafsir->end_verse_id ? ' — '.$tafsir->end_verse_id : '' }}

      <span style="margin: 0 7px;">·</span>

      Level:
      {{ $tafsir->level ?? '-' }}
    </div>

  </div>


  <div class="tafsir-actions">

    <a
      href="{{ route('admin.tafsir.edit', $tafsir) }}"
      class="edit-btn"
    >
      Edit
    </a>

    <a
      href="{{ route('admin.tafsir.index') }}"
      class="back-btn"
    >
      Back
    </a>

  </div>

</div>


<!-- TAFSIR -->
<div class="tafsir-card">

  <div class="tafsir-box">

    {!! $tafsir->text !!}

  </div>

</div>


<div class="tafsir-footer-decoration">
  ✦
</div>

  </div>

</div>

@endsection
