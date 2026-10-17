@extends('layouts.app')

@section('content')

<section class="about-page">

    {{-- =====================================================
         HERO
    ====================================================== --}}
    <header class="about-hero">

        <div class="hero-text">
            <span class="about-eyebrow">About</span>

            <h1 class="about-title">About the Author</h1>

            <div class="about-line"></div>

            <p class="hero-sub">
                Qur’anic tafsīr &nbsp;·&nbsp; English literature &nbsp;·&nbsp; Translation
            </p>

            <div class="hero-chips">
                <small>Taught in</small>
                <span>Libya</span>
                <span>Jordan</span>
                <span>Malaysia</span>
                <span>Iraq</span>
            </div>
        </div>

        <div class="hero-emblem" aria-hidden="true">
            <span class="ring ring-1"></span>
            <span class="ring ring-2"></span>
            <span class="emblem-core">✦</span>
        </div>

    </header>


    {{-- =====================================================
         STATS
    ====================================================== --}}
    <div class="about-stats">

        <div class="stat">
            <strong>27</strong>
            <span>Years teaching</span>
        </div>

        <div class="stat">
            <strong>12</strong>
            <span>Universities</span>
        </div>

        <div class="stat">
            <strong>4</strong>
            <span>Countries</span>
        </div>

        <div class="stat">
            <strong>1980</strong>
            <span>Began studying tafsīr</span>
        </div>

    </div>


    {{-- =====================================================
         BODY
    ====================================================== --}}
    <div class="about-grid">

        <article class="about-card about-text">

            <p>
                Born in Frankfurt, Germany, in 1965 to two Iraqi physicians, I spent my early childhood in England
                before returning with my family to Iraq. By then, I was bilingual and an avid reader in both Arabic
                and English.
            </p>

            <p>
                In early adolescence, I consciously rediscovered my Islamic identity and began a sustained commitment
                to learning and practicing the religion. My study of Qur’anic tafsīr in particular began in 1980 and
                has continued ever since, encompassing the study of dozens of works of Qur’anic exegesis.
            </p>

            <p>
                Academically, I hold a BA in English Language and Literature (1987), an MA in English Literature
                (1993), and a PhD in English Language and Literature (2004). Over the course of 27 years, I taught
                English language and literature at twelve universities across four countries: Libya, Jordan,
                Malaysia, and Iraq.
            </p>

            <p>
                During my years in Malaysia, I also taught ‘aqīdah and Qur’anic tafsīr at Islamic centres. This
                experience proved pivotal. It compelled me to develop a translation methodology that could convey not
                only the multiple semantic layers of the Qur’anic text, but also its rhetorical force and meditative
                depth. In late 2006, this effort crystallized into my ongoing project:
                <em>The Glorious Qur’an: A Multi-Layered, Exegetic, Meditative Translation</em>.
            </p>

        </article>


        <aside class="about-card about-timeline">

            <h2 class="card-title">Timeline</h2>

            <ul>
                <li><b>1965</b><span>Born in Frankfurt, Germany</span></li>
                <li><b>1980</b><span>Began studying Qur’anic tafsīr</span></li>
                <li><b>1987</b><span>BA in English Language and Literature</span></li>
                <li><b>1993</b><span>MA in English Literature</span></li>
                <li><b>2004</b><span>PhD in English Language and Literature</span></li>
                <li><b>2006</b><span>Started the Multi-Layered, Exegetic, Meditative Translation project</span></li>
            </ul>

        </aside>

    </div>


    {{-- =====================================================
         PROJECT HIGHLIGHT
    ====================================================== --}}
    <div class="about-project">

        <span class="project-label">Ongoing project &nbsp;·&nbsp; since late 2006</span>

        <h2 class="project-name">
            The Glorious Qur’an:
            <em>A Multi-Layered, Exegetic, Meditative Translation</em>
        </h2>

    </div>

</section>


<style>

/* =====================================================
   THEME VARIABLES (light + dark)
===================================================== */
.about-page {
    --ab-surface: #ffffff;
    --ab-text: #34443d;
    --ab-muted: #6f7f77;
    --ab-heading: #073c35;
    --ab-border: rgba(7,60,53,.10);
    --ab-shadow: 0 14px 40px rgba(7,60,53,.08);
    --ab-gold: #c99a45;
    --ab-gold-light: #e3c37d;

    padding: 32px 32px 64px;
    max-width: 1180px;
    margin: 0 auto;
}

html.dark-mode .about-page {
    --ab-surface: #1a251f;
    --ab-text: #cbd6cf;
    --ab-muted: #8fa097;
    --ab-heading: #e6eee9;
    --ab-border: rgba(255,255,255,.075);
    --ab-shadow: 0 14px 40px rgba(0,0,0,.3);
}


/* =====================================================
   HERO
===================================================== */
.about-hero {
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 32px;
    padding: 56px 56px 84px;
    border-radius: 26px;
    color: #fff;
    background:
        radial-gradient(circle at 12% 0%, rgba(227,195,125,.14), transparent 35%),
        radial-gradient(circle at 100% 100%, rgba(11,99,87,.55), transparent 45%),
        linear-gradient(135deg, #073c35 0%, #04352f 55%, #022c28 100%);
    box-shadow: 0 20px 50px rgba(2,44,40,.28);
    animation: aboutRise .7s ease both;
}

/* geometric pattern, fades in from the right */
.about-hero::before {
    content: "";
    position: absolute;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.16' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 15%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 15%, #000 100%);
    pointer-events: none;
}

.hero-text { position: relative; z-index: 2; max-width: 560px; }

.about-page .about-eyebrow {
    display: block;
    margin-bottom: 14px;
    font-size: 11px;
    letter-spacing: .24em;
    text-transform: uppercase;
    color: var(--ab-gold-light);
}

.about-page .about-title {
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(32px, 5vw, 52px);
    font-weight: 500;
    line-height: 1.12;
    color: #fff;
}

.about-line {
    width: 80px;
    height: 1px;
    margin: 20px 0 18px;
    background: linear-gradient(90deg, #d8b86a, rgba(227,195,125,.25), transparent);
}

.about-page .hero-sub {
    margin: 0 0 26px;
    font-size: 14px;
    letter-spacing: .04em;
    color: rgba(255,255,255,.66);
}

.hero-chips {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px;
}

.hero-chips small {
    margin-right: 6px;
    font-size: 11px;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.45);
}

.hero-chips span {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12.5px;
    color: #f7f0e4;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(227,195,125,.28);
    backdrop-filter: blur(4px);
}


/* emblem */
.hero-emblem {
    position: relative;
    z-index: 2;
    flex-shrink: 0;
    width: 190px;
    height: 190px;
    display: grid;
    place-items: center;
}

.hero-emblem .ring {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.35);
}

.hero-emblem .ring-1 { inset: 0; animation: aboutPulse 4s ease-in-out infinite; }
.hero-emblem .ring-2 { inset: -22px; border-color: rgba(227,195,125,.14); animation: aboutPulse 4s ease-in-out 1s infinite; }

.emblem-core {
    width: 112px;
    height: 112px;
    display: grid;
    place-items: center;
    border-radius: 50%;
    font-size: 48px;
    color: #e3c37d;
    background: radial-gradient(circle, rgba(227,195,125,.2), rgba(227,195,125,.04) 65%);
    border: 1px solid rgba(227,195,125,.5);
    text-shadow: 0 0 20px rgba(227,195,125,.5);
    animation: aboutFloat 5s ease-in-out infinite;
}


/* =====================================================
   STATS (overlap the hero)
===================================================== */
.about-stats {
    position: relative;
    z-index: 3;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin: -44px 28px 32px;
    animation: aboutRise .7s ease .12s both;
}

.about-page .stat {
    padding: 20px 22px;
    border-radius: 18px;
    background: var(--ab-surface);
    border: 1px solid var(--ab-border);
    border-top: 2px solid var(--ab-gold);
    box-shadow: var(--ab-shadow);
    transition: transform .3s ease;
}

.about-page .stat:hover { transform: translateY(-3px); }

.about-page .stat strong {
    display: block;
    font-family: Georgia, serif;
    font-size: 32px;
    font-weight: 500;
    line-height: 1.1;
    color: var(--ab-heading);
}

.about-page .stat span {
    display: block;
    margin-top: 6px;
    font-size: 12.5px;
    color: var(--ab-muted);
}


/* =====================================================
   BODY GRID
===================================================== */
.about-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.65fr) minmax(0, 1fr);
    gap: 24px;
    align-items: start;
    animation: aboutRise .7s ease .22s both;
}

.about-card {
    position: relative;
    overflow: hidden;
    padding: 36px;
    border-radius: 22px;
    background: var(--ab-surface);
    border: 1px solid var(--ab-border);
    box-shadow: var(--ab-shadow);
}

/* gold accent edge */
.about-text::before {
    content: "";
    position: absolute;
    left: 0; top: 36px; bottom: 36px;
    width: 3px;
    border-radius: 0 3px 3px 0;
    background: linear-gradient(180deg, var(--ab-gold), transparent);
}

.about-page .about-text p {
    margin: 0 0 22px;
    font-family: "Cormorant Garamond", Georgia, serif;
    font-size: 20px;
    line-height: 1.8;
    color: var(--ab-text);
}

.about-page .about-text p:last-child { margin-bottom: 0; }

.about-page .about-text p:first-child::first-letter {
    float: left;
    margin: 6px 12px 0 0;
    font-family: Georgia, serif;
    font-size: 64px;
    line-height: .85;
    color: var(--ab-gold);
}

.about-page .about-text em {
    font-style: italic;
    color: var(--ab-heading);
    background: linear-gradient(transparent 62%, rgba(201,154,69,.28) 62%);
}


/* =====================================================
   TIMELINE
===================================================== */
.about-timeline { position: sticky; top: 24px; }

.about-page .card-title {
    margin: 0 0 24px;
    font-family: Inter, Arial, sans-serif;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: var(--ab-gold);
}

.about-timeline ul {
    list-style: none;
    margin: 0;
    padding: 0 0 0 22px;
    border-left: 1px solid rgba(201,154,69,.35);
}

.about-timeline li {
    position: relative;
    margin-bottom: 22px;
}

.about-timeline li:last-child { margin-bottom: 0; }

.about-timeline li::before {
    content: "";
    position: absolute;
    left: -28px;
    top: 4px;
    width: 11px;
    height: 11px;
    border-radius: 50%;
    background: var(--ab-surface);
    border: 2px solid var(--ab-gold);
    transition: background .25s ease, transform .25s ease;
}

.about-timeline li:hover::before { background: var(--ab-gold); transform: scale(1.2); }

.about-page .about-timeline b {
    display: block;
    font-family: Georgia, serif;
    font-size: 17px;
    font-weight: 500;
    color: var(--ab-heading);
}

.about-page .about-timeline li span {
    display: block;
    margin-top: 3px;
    font-size: 13px;
    line-height: 1.55;
    color: var(--ab-muted);
}


/* =====================================================
   PROJECT HIGHLIGHT
===================================================== */
.about-project {
    position: relative;
    overflow: hidden;
    margin-top: 24px;
    padding: 40px 44px;
    border-radius: 22px;
    text-align: center;
    background:
        radial-gradient(circle at 50% 0%, rgba(227,195,125,.14), transparent 55%),
        linear-gradient(135deg, #073c35, #022c28);
    border: 1px solid rgba(227,195,125,.28);
    box-shadow: 0 16px 40px rgba(2,44,40,.22);
    animation: aboutRise .7s ease .32s both;
}

.about-project::before,
.about-project::after {
    content: "";
    position: absolute;
    width: 220px;
    height: 220px;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.14);
    pointer-events: none;
}

.about-project::before { left: -110px; top: -110px; }
.about-project::after  { right: -110px; bottom: -110px; }

.about-page .project-label {
    position: relative;
    display: block;
    margin-bottom: 14px;
    font-size: 11px;
    letter-spacing: .22em;
    text-transform: uppercase;
    color: var(--ab-gold-light);
}

.about-page .project-name {
    position: relative;
    margin: 0;
    font-family: Georgia, 'Times New Roman', serif;
    font-size: clamp(20px, 2.8vw, 30px);
    font-weight: 500;
    line-height: 1.4;
    color: #fff;
}

.about-page .project-name em {
    display: block;
    margin-top: 4px;
    font-style: italic;
    color: var(--ab-gold-light);
}


/* =====================================================
   ANIMATIONS
===================================================== */
@keyframes aboutRise {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
}

@keyframes aboutFloat {
    0%, 100% { transform: translateY(0); }
    50%      { transform: translateY(-5px); }
}

@keyframes aboutPulse {
    0%, 100% { opacity: .4; transform: scale(1); }
    50%      { opacity: 1;  transform: scale(1.04); }
}

@media (prefers-reduced-motion: reduce) {
    .about-page *, .about-page *::before { animation: none !important; transition: none !important; }
}


/* =====================================================
   RESPONSIVE
===================================================== */
@media (max-width: 1100px) {
    .about-grid { grid-template-columns: 1fr; }
    .about-timeline { position: static; }
}

@media (max-width: 900px) {
    .about-page { padding: 72px 16px 48px; }   /* room for the sidebar toggle */
    .about-hero { padding: 36px 24px 72px; border-radius: 20px; }
    .hero-emblem { display: none; }
    .about-stats { grid-template-columns: repeat(2, 1fr); margin: -40px 12px 24px; gap: 12px; }
    .about-card { padding: 26px 22px; }
    .about-page .about-text p { font-size: 18.5px; }
    .about-project { padding: 30px 22px; }
}


/* =====================================================
   WHEN THE MAIN SIDEBAR IS CLOSED (X) → FULL WIDTH
===================================================== */
.about-page {
    transition: max-width .3s ease, padding .3s ease;
}

@media (min-width: 901px) {

    body.sidebar-collapsed .about-page {
        max-width: 100%;
        padding-left: 48px;
        padding-right: 48px;
    }

    /* text card gets a bit more room next to the timeline */
    body.sidebar-collapsed .about-grid {
        grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
    }
}
</style>

@endsection