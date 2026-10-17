@extends('layouts.app')
@php
    $user = auth()->user();
    $userName = $user?->name ?? '';
    $userInitial = strtoupper(mb_substr($userName, 0, 1));
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Qur'anic Meditation — Dashboard</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Amiri+Quran&family=Cormorant+Garamond:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>

/* ================= ROOT ================= */
:root {
    --green-950: #032f2b; --green-900: #043b35; --green-850: #06443d;
    --green-800: #075148; --green-700: #0b6357;
    --cream: #f6f1e8; --cream-2: #fbf8f2; --cream-3: #eee7da;
    --gold: #c99a45; --gold-light: #e3c37d; --gold-deep: #9a702f;
    --text: #123a35; --muted: #71817c;
    --border: rgba(18,58,53,.10);
    --serif: Georgia, "Times New Roman", serif;
    --sans: "Inter", system-ui, -apple-system, "Segoe UI", sans-serif;
    --quran: "Amiri Quran", "Amiri", serif;
    --shadow: 0 14px 38px rgba(26,49,43,.08);
    --shadow-hover: 0 22px 52px rgba(26,49,43,.14);
    --page-bg: var(--cream);
    --page-card: #ffffff;
    --paper: #fbf8f0;
    --transition: .35s cubic-bezier(.16,1,.3,1);
    --green-main: #075148;
    --hero-bg:
        radial-gradient(circle at 12% 0%, rgba(227,195,125,.14), transparent 35%),
        radial-gradient(circle at 100% 100%, rgba(11,99,87,.55), transparent 45%),
        linear-gradient(135deg, #073c35 0%, #04352f 55%, #022c28 100%);
}

/* ================= DARK MODE ================= */
body.dark-mode {
    --cream: #101b19; --cream-2: #162522; --cream-3: #20322e;
    --text: #e3ebe6; --muted: #9eaea8;
    --border: rgba(232,238,233,.09);
    --page-bg: #101b19;
    --page-card: #1a2823;
    --paper: #152320;
    --shadow: 0 18px 45px rgba(0,0,0,.26);
    --shadow-hover: 0 25px 60px rgba(0,0,0,.36);
    --green-main: #9fc0b5;
}

/* ================= RESET ================= */
* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }
body {
    min-height: 100vh;
    background:
        radial-gradient(circle at 85% 0%, rgba(201,154,69,.10), transparent 28%),
        radial-gradient(circle at 0% 100%, rgba(7,95,83,.06), transparent 30%),
        var(--page-bg);
    background-attachment: fixed;
    color: var(--text);
    font-family: var(--sans);
    -webkit-font-smoothing: antialiased;
    text-rendering: optimizeLegibility;
    transition: background-color .35s ease, color .35s ease;
}
button, input { font: inherit; }
button { cursor: pointer; }
a { text-decoration: none; color: inherit; }
a:focus-visible, button:focus-visible { outline: 2px solid var(--gold); outline-offset: 3px; }

/* ================= APP ================= */
.app { display: flex; min-height: 100vh; }

/* ================= SIDEBAR ================= */
.sidebar {
    width: 270px; min-height: 100vh;
    position: fixed; left: 0; top: 0; bottom: 0; z-index: 100;
    display: flex; flex-direction: column;
    padding: 28px 20px; color: white;
    background:
        radial-gradient(circle at 25% 5%, rgba(227,195,125,.07), transparent 24%),
        radial-gradient(circle at 100% 75%, rgba(11,99,87,.20), transparent 32%),
        linear-gradient(180deg, #073c35 0%, #04352f 45%, #022c28 100%);
    border-right: 1px solid rgba(255,255,255,.045);
    box-shadow: 8px 0 35px rgba(0,0,0,.07);
    overflow-y: auto;
}
.sidebar::before {
    content: ""; position: absolute;
    width: 220px; height: 220px; top: 180px; left: -130px;
    border-radius: 50%;
    border: 1px solid rgba(227,195,125,.08);
    box-shadow: 0 0 0 30px rgba(227,195,125,.025), 0 0 0 60px rgba(227,195,125,.015);
    pointer-events: none;
}

/* ================= LOGO ================= */
.logo {
    display: flex; align-items: center; gap: 14px;
    padding: 5px 8px 38px; position: relative;
    transform-origin: left center;
    animation: logoEntrance 1.1s cubic-bezier(.16,1,.3,1) both;
}
.logo-symbol {
    width: 48px; height: 48px; min-width: 48px;
    border-radius: 50%; display: grid; place-items: center;
    position: relative; color: var(--gold-light);
    font-size: 23px; line-height: 1;
    border: 1px solid rgba(227,195,125,.55);
    background: radial-gradient(circle at 50% 45%, rgba(227,195,125,.20), rgba(227,195,125,.07) 48%, rgba(255,255,255,.025) 72%, transparent 100%);
    box-shadow: 0 0 15px rgba(227,195,125,.10), 0 0 35px rgba(227,195,125,.06), inset 0 0 16px rgba(227,195,125,.07);
    flex-shrink: 0; z-index: 2;
    animation: symbolPulse 4s ease-in-out 1s infinite;
}
.logo-symbol::before {
    content: ""; position: absolute; inset: -4px; border-radius: 50%;
    border: 1px solid transparent;
    border-top-color: rgba(227,195,125,.9);
    border-right-color: rgba(227,195,125,.25);
    filter: drop-shadow(0 0 6px rgba(227,195,125,.45));
    animation: symbolOrbit 7s linear infinite;
}
.logo-symbol::after {
    content: ""; position: absolute; inset: -11px; border-radius: 50%;
    background: radial-gradient(circle, rgba(227,195,125,.13), transparent 68%);
    opacity: .35; z-index: -1;
    animation: symbolAura 4s ease-in-out 1s infinite;
}
.logo-text { display: flex; flex-direction: column; justify-content: center; min-width: 0; }
.logo-title {
    font-family: var(--serif); font-size: 17px; line-height: 1.25;
    font-weight: 500; letter-spacing: .12em; text-transform: uppercase;
    color: #f8f2e8; max-width: 175px; position: relative; white-space: normal;
    text-shadow: 0 2px 14px rgba(255,255,255,.05);
    animation: titleEntrance 1.15s cubic-bezier(.16,1,.3,1) .08s both;
}
.logo-title span { display: block; }
.logo-text::after {
    content: ""; display: block; width: 58px; height: 1px; margin-top: 9px;
    transform-origin: left center;
    background: linear-gradient(90deg, var(--gold-light), rgba(227,195,125,.28), transparent);
    opacity: .85;
    animation: lineEntrance 1s cubic-bezier(.16,1,.3,1) .3s both;
    transition: width .4s ease, opacity .3s ease;
}
.logo:hover .logo-symbol {
    border-color: rgba(227,195,125,.82);
    box-shadow: 0 0 24px rgba(227,195,125,.22), 0 0 50px rgba(227,195,125,.12), inset 0 0 22px rgba(227,195,125,.12);
}
.logo:hover .logo-title { color: #fffaf0; }
.logo:hover .logo-text::after { width: 78px; opacity: 1; }

@keyframes logoEntrance {
    from { opacity: 0; transform: translateY(18px) scale(.92); filter: blur(7px); }
    to   { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
}
@keyframes titleEntrance {
    from { opacity: 0; transform: translateX(-18px) scaleX(.94); filter: blur(5px); letter-spacing: .2em; }
    to   { opacity: 1; transform: translateX(0) scaleX(1); filter: blur(0); letter-spacing: .12em; }
}
@keyframes lineEntrance {
    from { opacity: 0; transform: scaleX(0); filter: blur(2px); }
    to   { opacity: .85; transform: scaleX(1); filter: blur(0); }
}
@keyframes symbolPulse {
    0%, 100% { box-shadow: 0 0 15px rgba(227,195,125,.10), 0 0 35px rgba(227,195,125,.06), inset 0 0 16px rgba(227,195,125,.07); }
    50%      { box-shadow: 0 0 22px rgba(227,195,125,.20), 0 0 45px rgba(227,195,125,.11), inset 0 0 21px rgba(227,195,125,.11); }
}
@keyframes symbolOrbit { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes symbolAura {
    0%, 100% { opacity: .25; transform: scale(.9); }
    50%      { opacity: .60; transform: scale(1.1); }
}
@keyframes rise {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* ================= NAVIGATION ================= */
.nav-title {
    font-size: 11px; text-transform: uppercase; letter-spacing: .15em;
    color: rgba(255,255,255,.34); padding: 0 12px 12px;
}
.navigation { display: flex; flex-direction: column; gap: 5px; }
.navigation a {
    display: flex; align-items: center; gap: 13px;
    padding: 13px 16px; border-radius: 12px;
    color: rgba(255,255,255,.63); font-size: 14px;
    position: relative; overflow: hidden;
    transition: color .3s ease, background .3s ease, transform .3s ease;
}
.navigation a::before {
    content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
    background: var(--gold); transform: scaleY(0); transition: transform .3s ease;
}
.navigation a:hover { color: white; background: rgba(255,255,255,.055); transform: translateX(2px); }
.navigation a.active {
    color: white;
    background: linear-gradient(90deg, rgba(201,154,69,.18), rgba(255,255,255,.055));
    box-shadow: inset 0 0 0 1px rgba(227,195,125,.10), 0 6px 18px rgba(0,0,0,.12);
}
.navigation a.active::before { transform: scaleY(1); }
.nav-icon {
    width: 20px; text-align: center; font-size: 17px;
    color: rgba(255,255,255,.72);
    transition: color .3s ease, transform .3s ease;
}
.navigation a:hover .nav-icon { color: var(--gold-light); transform: scale(1.08); }
.admin-nav-title {
    margin: 18px 12px 8px; padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,.10);
    color: var(--gold-light); font-size: 11px; font-weight: 700;
    letter-spacing: .12em; text-transform: uppercase;
}

/* ================= MAIN ================= */
.main {
    margin-left: 270px; width: calc(100% - 270px);
    min-height: 100vh; padding: 26px 40px 60px;
    max-width: 1500px;
}

/* ================= TOPBAR ================= */
.topbar {
    height: 56px; display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 34px;
}
.mobile-menu {
    display: none; width: 44px; height: 44px;
    border: 1px solid var(--border); background: var(--page-card);
    border-radius: 14px; color: var(--text); font-size: 20px;
    box-shadow: var(--shadow);
    transition: transform .25s ease, border-color .25s ease;
}
.mobile-menu:hover { transform: translateY(-2px); border-color: rgba(201,154,69,.4); }
.top-actions { display: flex; align-items: center; gap: 10px; }

.theme-toggle {
    height: 44px; padding: 0 16px 0 10px;
    border: 1px solid var(--border); border-radius: 999px;
    background: var(--page-card); color: var(--text);
    display: flex; align-items: center; gap: 10px;
    font-size: 12.5px; font-weight: 600;
    box-shadow: var(--shadow);
    transition: border-color .3s ease, transform .25s ease, box-shadow .25s ease;
}
.theme-toggle:hover { transform: translateY(-2px); border-color: rgba(201,154,69,.45); }
.theme-toggle-icon {
    width: 26px; height: 26px; border-radius: 50%;
    display: grid; place-items: center;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14; font-size: 14px;
}
.theme-toggle-text { letter-spacing: .02em; }

.icon-button {
    width: 44px; height: 44px;
    border: 1px solid var(--border); background: var(--page-card);
    border-radius: 50%; display: grid; place-items: center;
    color: var(--text); font-size: 17px;
    box-shadow: var(--shadow);
    transition: transform .25s ease, border-color .25s ease;
}
.icon-button:hover { transform: translateY(-2px); border-color: rgba(201,154,69,.4); }

.user {
    height: 52px; padding: 5px 6px 5px 18px;
    border: 1px solid var(--border); border-radius: 999px;
    background: var(--page-card);
    display: flex; align-items: center; gap: 12px;
    box-shadow: var(--shadow);
    transition: border-color .3s ease, transform .3s ease;
}
.user:hover { transform: translateY(-2px); border-color: rgba(201,154,69,.3); }
.user-info { text-align: right; }
.user-name { font-size: 14px; font-weight: 600; }
.user-status { margin-top: 2px; font-size: 11px; color: var(--muted); }
.user-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    display: grid; place-items: center;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14; font-family: var(--serif); font-size: 19px; font-weight: 700;
    box-shadow: 0 5px 15px rgba(184,138,68,.3);
}

/* ================= HERO ================= */
.hero {
    display: grid;
    grid-template-columns: minmax(0, 1.08fr) minmax(0, .92fr);
    gap: 40px;
    margin-bottom: 32px;
    align-items: start;
}

.hero-text {
    padding: 6px 0 10px 2px;
    animation: rise .9s cubic-bezier(.16,1,.3,1) both;
}
.eyebrow {
    display: inline-flex; align-items: center; gap: 10px;
    color: var(--gold-deep); font-size: 12px; text-transform: uppercase;
    letter-spacing: .22em; font-weight: 700; margin-bottom: 16px;
}
.eyebrow::before {
    content: ""; width: 34px; height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold));
}
body.dark-mode .eyebrow { color: var(--gold-light); }

.hero h1 {
    font-family: var(--serif); font-size: clamp(40px, 4.6vw, 68px);
    line-height: 1.02; font-weight: 500; letter-spacing: -.03em; color: #0a332f;
}
body.dark-mode .hero h1 { color: #e3ece7; }
.hero h1 span {
    display: block; margin-top: 8px;
    background: linear-gradient(90deg, #9a702f, #c99a45 55%, #b88a44);
    -webkit-background-clip: text; background-clip: text;
    -webkit-text-fill-color: transparent; color: transparent;
}
body.dark-mode .hero h1 span {
    background: linear-gradient(90deg, #e3c37d, #f0d99b 55%, #e3c37d);
    -webkit-background-clip: text; background-clip: text;
}
.gold-line {
    width: 80px; height: 3px; margin: 26px 0 28px; border-radius: 10px;
    background: linear-gradient(90deg, var(--gold), var(--gold-light));
    box-shadow: 0 0 15px rgba(201,154,69,.2);
}

/* ---- long description ---- */
.hero-description {
    font-size: 16px;
    line-height: 1.85;
    color: var(--text);
}
body.dark-mode .hero-description { color: #b9cfc6; }

.hero-description h3 {
    display: flex; align-items: baseline; gap: 10px;
    margin: 34px 0 12px;
    font-family: var(--serif);
    font-size: 22px; font-weight: 500; line-height: 1.3;
    color: var(--green-main);
}
.hero-description h3::before {
    content: "✦"; flex-shrink: 0;
    font-size: 13px; color: var(--gold);
}
.hero-description h3:first-child { margin-top: 0; }

.hero-description p { margin: 0 0 16px; }

.hero-description strong { color: var(--green-main); font-weight: 700; }

/* numbered levels */
.hero-description ol {
    list-style: none; counter-reset: lvl;
    margin: 20px 0 22px; padding: 0;
}
.hero-description ol > li {
    counter-increment: lvl;
    position: relative;
    margin-bottom: 14px;
    padding: 18px 20px 16px 68px;
    border-radius: 18px;
    background: var(--page-card);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
    transition: transform .3s ease, border-color .3s ease, box-shadow .3s ease;
}
.hero-description ol > li:hover {
    transform: translateY(-2px);
    border-color: rgba(201,154,69,.35);
    box-shadow: var(--shadow-hover);
}
.hero-description ol > li::before {
    content: counter(lvl);
    position: absolute; left: 18px; top: 18px;
    width: 36px; height: 36px; border-radius: 50%;
    display: grid; place-items: center;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14; font-family: var(--serif); font-size: 17px; font-weight: 700;
    box-shadow: 0 6px 14px rgba(184,138,68,.3);
}
.hero-description ol > li > strong { display: block; font-size: 15.5px; color: var(--text); }
.hero-description li > p { margin: 6px 0 0; font-size: 15px; line-height: 1.75; color: var(--muted); }

/* bullet list */
.hero-description ul { margin: 0 0 18px; padding-left: 22px; }
.hero-description ul > li { margin-bottom: 14px; padding-left: 4px; }
.hero-description ul > li::marker { color: var(--gold); font-size: 1.1em; }

/* collapsible */
.hero-description.collapsible {
    position: relative;
    max-height: 420px;
    overflow: hidden;
    transition: max-height .6s cubic-bezier(.16,1,.3,1);
}
.hero-description.collapsible::after {
    content: "";
    position: absolute; left: 0; right: 0; bottom: 0;
    height: 160px;
    background: linear-gradient(180deg, transparent, var(--page-bg) 88%);
    pointer-events: none;
    transition: opacity .3s ease;
}
.hero-description.collapsible.expanded::after { opacity: 0; }

.see-more-btn {
    margin-top: 16px;
    display: inline-flex; align-items: center; gap: 10px;
    padding: 12px 22px; border-radius: 999px;
    background: transparent; color: var(--gold-deep);
    border: 1px solid rgba(201,154,69,.55);
    font-size: 13px; font-weight: 700; letter-spacing: .04em;
    transition: background .25s ease, color .25s ease, transform .25s ease, box-shadow .25s ease;
}
body.dark-mode .see-more-btn { color: var(--gold-light); }
.see-more-btn:hover {
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    border-color: transparent; color: #051a14;
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(184,138,68,.3);
}
.see-more-arrow { transition: transform .35s ease; }
.see-more-btn[aria-expanded="true"] .see-more-arrow { transform: rotate(180deg); }

/* levels table */
.translation-levels-table {
    width: 100%;
    margin: 18px 0 30px;
    border: 1px solid var(--border);
    border-radius: 18px;
    overflow: hidden;
    font-size: 14px;
    background: var(--page-card);
    box-shadow: var(--shadow);
}
.translation-level-row {
    display: grid;
    grid-template-columns: 1fr 2fr 70px;
    border-bottom: 1px solid var(--border);
    transition: background .25s ease;
}
.translation-level-row:last-child { border-bottom: 0; }
.translation-level-row:not(.translation-level-header):hover { background: rgba(201,154,69,.07); }
.translation-level-row > div { padding: 13px 16px; line-height: 1.55; }
.translation-level-row > div:last-child {
    text-align: center; font-weight: 700; color: var(--gold-deep);
}
body.dark-mode .translation-level-row > div:last-child { color: var(--gold-light); }
.translation-level-header {
    font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase;
    color: #f7f0e4;
    background: linear-gradient(135deg, #073c35, #04352f);
}
.translation-level-header > div:last-child { color: var(--gold-light); }

/* sample ayah */
.sample-ayah {
    position: relative;
    margin: 20px 0 28px;
    padding: 30px 28px 24px;
    border-radius: 22px;
    background: var(--paper);
    border: 1px solid var(--border);
    box-shadow: var(--shadow);
}
.sample-ayah::before, .sample-ayah::after {
    content: ""; position: absolute; width: 26px; height: 26px;
    border: 1.5px solid var(--gold); opacity: .7; pointer-events: none;
}
.sample-ayah::before { top: 12px; left: 12px; border-right: 0; border-bottom: 0; border-top-left-radius: 8px; }
.sample-ayah::after  { bottom: 12px; right: 12px; border-left: 0; border-top: 0; border-bottom-right-radius: 8px; }

.arabic-ayah {
    direction: rtl; text-align: center;
    font-family: var(--quran);
    font-size: 34px; line-height: 2;
    margin-bottom: 22px;
    color: var(--green-900);
}
body.dark-mode .arabic-ayah { color: #eef4f0; }

.sample-level { margin-top: 18px; padding-top: 16px; border-top: 1px dashed var(--border); }
.sample-level strong {
    display: block; margin-bottom: 8px;
    font-size: 12px; letter-spacing: .1em; text-transform: uppercase;
    color: var(--gold-deep);
}
body.dark-mode .sample-level strong { color: var(--gold-light); }
.sample-level p { margin-bottom: 0; font-family: var(--serif); font-size: 17px; line-height: 1.75; }

.hero-closing {
    margin-top: 28px !important;
    padding-top: 22px;
    border-top: 1px solid var(--border);
    font-family: var(--serif);
    font-size: 19px;
    font-style: italic;
    color: var(--green-main);
}

/* ================= HERO COVER ================= */
.project-cover {
    position: sticky;
    top: 24px;
    align-self: start;
    width: 100%;
    height: 640px;
    min-height: 0;
    overflow: hidden;
    border-radius: 28px;
    border: 1px solid rgba(227,195,125,.28);
    background:
        radial-gradient(circle at 80% 0%, rgba(227,195,125,.18), transparent 40%),
        linear-gradient(145deg, #0b4a41, #062f2a);
    box-shadow: var(--shadow-hover);
    animation: rise .9s cubic-bezier(.16,1,.3,1) .12s both;
}
.project-cover::before, .project-cover::after {
    content: ""; position: absolute; z-index: 3; width: 34px; height: 34px;
    border: 1.5px solid var(--gold-light); opacity: .75; pointer-events: none;
}
.project-cover::before { top: 14px; left: 14px; border-right: 0; border-bottom: 0; border-top-left-radius: 12px; }
.project-cover::after  { top: 14px; right: 14px; border-left: 0; border-bottom: 0; border-top-right-radius: 12px; }

.project-cover img {
    position: absolute;
    inset: 22px;
    width: calc(100% - 44px);
    height: calc(100% - 44px);
    object-fit: contain;
    object-position: center top;
    border-radius: 10px;
    transition: transform .7s cubic-bezier(.16,1,.3,1);
}
.project-cover:hover img { transform: scale(1.03); }
.project-cover-overlay {
    position: absolute; inset: 0; pointer-events: none;
    background: linear-gradient(180deg, transparent 55%, rgba(3,32,28,.85) 100%);
}
.project-cover-label {
    position: absolute; left: 26px; right: 26px; bottom: 22px; z-index: 2;
    display: flex; align-items: center; gap: 12px;
    color: var(--gold-light); font-size: 12px; font-weight: 700;
    text-transform: uppercase; letter-spacing: .2em;
}
.project-cover-label::after {
    content: ""; flex: 1; height: 1px;
    background: linear-gradient(90deg, rgba(227,195,125,.5), transparent);
}

/* ================= CARDS ================= */
.cards {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 22px; margin-bottom: 26px;
}
.card {
    background: var(--page-card); border: 1px solid var(--border);
    border-radius: 24px; box-shadow: var(--shadow);
    padding: 28px; min-height: 210px;
    position: relative; overflow: hidden;
    transition: border-color .35s ease, box-shadow .35s ease, transform .35s cubic-bezier(.16,1,.3,1);
}
.card::before {
    content: ""; position: absolute; width: 170px; height: 170px;
    right: -85px; top: -85px; border-radius: 50%;
    border: 1px solid rgba(201,154,69,.18);
    box-shadow: 0 0 0 28px rgba(201,154,69,.04);
    pointer-events: none;
}
.card:hover { transform: translateY(-4px); border-color: rgba(201,154,69,.3); box-shadow: var(--shadow-hover); }
.card-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .18em;
    color: var(--gold-deep); margin-bottom: 18px;
}
.card-title::before { content: "✦"; font-size: 11px; color: var(--gold); }
body.dark-mode .card-title { color: var(--gold-light); }

/* Journey */
.journey {
    background: var(--hero-bg);
    color: white; border-color: rgba(227,195,125,.2);
}
.journey::after {
    content: ""; position: absolute; inset: 0; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cg fill='none' stroke='%23e3c37d' stroke-opacity='.12' stroke-width='1'%3E%3Crect x='15' y='15' width='30' height='30'/%3E%3Crect x='15' y='15' width='30' height='30' transform='rotate(45 30 30)'/%3E%3C/g%3E%3C/svg%3E");
    -webkit-mask-image: linear-gradient(90deg, transparent 35%, #000 100%);
            mask-image: linear-gradient(90deg, transparent 35%, #000 100%);
}
.journey > * { position: relative; z-index: 2; }
.journey:hover { border-color: rgba(227,195,125,.4); }
.journey .card-title { color: var(--gold-light); }
.journey-content { display: flex; align-items: center; justify-content: space-between; gap: 18px; }
.journey h2 { font-family: var(--serif); font-size: 34px; font-weight: 500; margin-bottom: 8px; }
.journey p { color: rgba(255,255,255,.7); font-size: 14.5px; line-height: 1.6; max-width: 270px; }
.reward { margin-top: 22px; color: var(--gold-light); font-size: 13px; letter-spacing: .02em; }

/* progress ring: set  style="--p: {{ $quranProgress }}"  on .progress for a live value */
.progress {
    --p: 15;
    width: 122px; height: 122px; border-radius: 50%;
    background: conic-gradient(var(--gold-light) calc(var(--p) * 1%), rgba(255,255,255,.12) 0);
    display: grid; place-items: center; flex-shrink: 0;
    box-shadow: 0 0 30px rgba(227,195,125,.12);
}
.progress-inner {
    width: 94px; height: 94px; border-radius: 50%; background: #05352f;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    border: 1px solid rgba(227,195,125,.15);
}
.progress-number { font-family: var(--serif); font-size: 28px; color: white; line-height: 1; }
.progress-label { margin-top: 4px; font-size: 9.5px; letter-spacing: .1em; color: rgba(255,255,255,.55); text-transform: uppercase; }

/* Continue */
.continue h2 { font-family: var(--serif); font-size: 34px; font-weight: 500; color: var(--green-900); line-height: 1.15; }
body.dark-mode .continue h2 { color: #e3ece7; }
.verse-number { color: var(--muted); font-size: 14px; margin-top: 6px; }
.progress-bar {
    width: 100%; height: 8px; border-radius: 20px;
    background: var(--cream-3); margin-top: 24px; overflow: hidden;
}
.progress-bar span {
    display: block; width: 42%; height: 100%; border-radius: inherit;
    background: linear-gradient(90deg, #b88a44, #e3c37d);
    position: relative;
}
.progress-bar span::after {
    content: ""; position: absolute; right: 0; top: 50%;
    transform: translateY(-50%); width: 12px; height: 12px; border-radius: 50%;
    background: #fff; box-shadow: 0 0 0 3px rgba(201,154,69,.5);
}
.continue-bottom { margin-top: 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
.last-read { font-size: 12.5px; color: var(--muted); }
.read-btn {
    background: linear-gradient(135deg, #e0ba78, #b88a44); color: #051a14;
    padding: 12px 22px; border-radius: 999px; font-size: 13px; font-weight: 700;
    box-shadow: 0 8px 20px rgba(184,138,68,.3);
    transition: transform .25s ease, box-shadow .25s ease;
}
.read-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(184,138,68,.42); }

/* ================= PROJECT SHOWCASE (video) ================= */
.project-showcase {
    display: grid; grid-template-columns: 1fr;
    margin-bottom: 26px;
    position: relative; overflow: hidden; border-radius: 28px;
    background: var(--hero-bg);
    border: 1px solid rgba(227,195,125,.2);
    box-shadow: 0 20px 50px rgba(2,44,40,.26);
    transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease;
}
.project-showcase::before {
    content: ""; position: absolute; width: 320px; height: 320px;
    right: -140px; top: -160px; border-radius: 50%;
    border: 1px solid rgba(227,195,125,.12);
    box-shadow: 0 0 0 36px rgba(227,195,125,.03), 0 0 0 72px rgba(227,195,125,.018);
    pointer-events: none;
}
.project-showcase:hover { transform: translateY(-3px); box-shadow: 0 28px 62px rgba(2,44,40,.32); }

.project-media {
    padding: 30px 34px 34px;
    position: relative;
    z-index: 2;
    width: 100%;
    grid-column: 1 / -1;
}
.project-video-wrapper { width: 100%; max-width: 980px; margin: 0 auto; }
.project-video-header { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; }
.project-video-icon {
    width: 42px; height: 42px; min-width: 42px; display: grid; place-items: center;
    border-radius: 50%;
    background: linear-gradient(135deg, #e0ba78, #b88a44);
    color: #051a14; font-size: 14px;
    box-shadow: 0 8px 20px rgba(184,138,68,.35);
}
.project-video-title { font-family: var(--serif); font-size: 24px; font-weight: 500; color: white; line-height: 1.2; }
.project-video-subtitle { margin-top: 3px; font-size: 13.5px; color: rgba(255,255,255,.58); }
.project-video {
    position: relative; width: 100%; aspect-ratio: 16 / 9;
    overflow: hidden; border-radius: 18px; background: #021f1c;
    border: 1px solid rgba(227,195,125,.3);
    box-shadow: 0 18px 44px rgba(0,0,0,.4);
}
.project-video iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }

/* ================= STATS ================= */
.stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 26px; }
.stat {
    min-height: 108px; display: flex; align-items: center; gap: 18px;
    padding: 20px 24px; border-radius: 22px;
    background: var(--page-card);
    border: 1px solid var(--border);
    border-top: 2px solid var(--gold);
    box-shadow: var(--shadow);
    position: relative; overflow: hidden;
    transition: transform .35s cubic-bezier(.16,1,.3,1), box-shadow .35s ease, border-color .35s ease;
}
.stat::before {
    content: ""; position: absolute; width: 110px; height: 110px;
    right: -50px; top: -50px; border-radius: 50%;
    background: radial-gradient(circle, rgba(201,154,69,.16), transparent 70%);
    pointer-events: none;
}
.stat:hover { transform: translateY(-4px); box-shadow: var(--shadow-hover); }
.stat-icon {
    width: 54px; height: 54px; min-width: 54px; border-radius: 16px;
    display: grid; place-items: center; font-size: 19px; color: var(--green-900);
    background: linear-gradient(145deg, rgba(7,81,72,.12), rgba(7,81,72,.05));
    border: 1px solid rgba(7,81,72,.1);
    transition: transform .35s ease, background .35s ease, color .35s ease;
}
body.dark-mode .stat-icon { background: rgba(227,195,125,.08); border-color: rgba(227,195,125,.16); color: var(--gold-light) !important; }
.stat:hover .stat-icon {
    transform: scale(1.08) rotate(-4deg);
    background: linear-gradient(145deg, rgba(201,154,69,.2), rgba(201,154,69,.08));
    color: #a4772f;
}
.stat-number {
    font-family: var(--serif);
    font-size: 38px; line-height: 1; font-weight: 500; letter-spacing: -.02em;
    color: var(--green-900);
    transition: color .3s ease;
}
body.dark-mode .stat-number { color: #e3ece7; }
.stat:hover .stat-number { color: var(--gold-deep); }
.stat-name { margin-top: 8px; font-size: 13px; line-height: 1; font-weight: 500; color: var(--muted); letter-spacing: .02em; }
.stat:nth-child(1) .stat-icon { color: #075148; }
.stat:nth-child(2) .stat-icon { color: #b08335; background: linear-gradient(145deg, rgba(201,154,69,.16), rgba(201,154,69,.06)); }
.stat:nth-child(3) .stat-icon { color: #356c62; }

/* ================= BOTTOM ================= */
.bottom { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; align-items: start; }

.activity {
    background: var(--page-card); border: 1px solid var(--border);
    border-radius: 24px; padding: 28px; box-shadow: var(--shadow);
    position: relative; overflow: hidden;
    transition: border-color .35s ease, box-shadow .35s ease;
}
.activity:hover { border-color: rgba(201,154,69,.3); box-shadow: var(--shadow-hover); }
.activity-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
.activity-header h2 { font-family: var(--serif); font-size: 28px; font-weight: 500; }
.view-all {
    font-size: 12.5px; font-weight: 600; color: var(--gold-deep);
    border: 1px solid rgba(201,154,69,.45); padding: 8px 16px; border-radius: 999px;
    transition: background .25s ease, color .25s ease, transform .25s ease;
}
body.dark-mode .view-all { color: var(--gold-light); }
.view-all:hover { background: linear-gradient(135deg, #e0ba78, #b88a44); border-color: transparent; color: #051a14; transform: translateY(-1px); }
.activity-item {
    display: flex; align-items: center; gap: 14px;
    padding: 16px 10px; border-radius: 14px;
    border-bottom: 1px solid var(--border);
    transition: transform .3s ease, background .3s ease;
}
.activity-item:hover { transform: translateX(3px); background: rgba(201,154,69,.07); }
.activity-item:last-child { border-bottom: none; }
.activity-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #0b6357, #043b35); color: var(--gold-light);
    display: grid; place-items: center; font-size: 15px; flex-shrink: 0;
    box-shadow: 0 6px 14px rgba(7,81,72,.18);
}
.activity-info { flex: 1; min-width: 0; }
.activity-name {
    font-size: 14.5px; font-weight: 600; color: var(--text);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.activity-time {
    margin-top: 4px; font-size: 12.5px; color: var(--muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.activity-arrow {
    width: 30px; height: 30px; border-radius: 50%; display: grid; place-items: center;
    border: 1px solid var(--border); color: var(--muted); font-size: 14px;
    transition: transform .25s ease, color .25s ease, background .25s ease, border-color .25s ease;
}
.activity-item:hover .activity-arrow { transform: translateX(3px); background: var(--gold); border-color: var(--gold); color: #fff; }

/* Quran banner (full width) */
.quran-banner {
    grid-column: 1 / -1; min-height: 270px;
    border-radius: 28px; overflow: hidden; position: relative;
    background:
        radial-gradient(circle at 15% 100%, rgba(227,195,125,.14), transparent 40%),
        linear-gradient(90deg, #043d36 0%, #075247 55%, rgba(7,82,71,.45) 100%);
    border: 1px solid rgba(227,195,125,.2);
    box-shadow: 0 20px 50px rgba(2,44,40,.22);
    transition: transform .35s ease, box-shadow .35s ease;
}
.quran-banner:hover { transform: translateY(-3px); box-shadow: 0 28px 60px rgba(2,44,40,.3); }
.quran-pattern {
    position: absolute; inset: 0; opacity: .07;
    background-image: repeating-radial-gradient(circle at 0 0, transparent 0, transparent 25px, #e3c37d 26px, transparent 27px);
}
.banner-content { position: relative; z-index: 2; width: 56%; padding: 42px; }
.banner-label {
    display: inline-flex; align-items: center; gap: 10px;
    color: var(--gold-light); font-size: 11.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .2em;
}
.banner-label::before { content: "✦"; }
.banner-content h2 {
    margin-top: 14px; font-family: var(--serif); color: white;
    font-size: clamp(30px, 3.2vw, 42px); line-height: 1.1; font-weight: 500;
}
.banner-content p {
    margin-top: 16px; color: rgba(255,255,255,.7);
    font-family: var(--serif); font-size: 18px; line-height: 1.6; max-width: 440px;
}
.banner-button {
    display: inline-flex; align-items: center; margin-top: 26px; padding: 14px 26px; border-radius: 999px;
    background: linear-gradient(135deg, #e0ba78, #b88a44); color: #051a14; font-size: 13.5px; font-weight: 700;
    box-shadow: 0 10px 24px rgba(184,138,68,.35);
    transition: transform .25s ease, box-shadow .25s ease;
}
.banner-button:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(184,138,68,.5); }
.quran-image {
    position: absolute; right: -20px; bottom: -15px; width: 52%; height: 108%;
    object-fit: cover; opacity: .82;
    -webkit-mask-image: linear-gradient(90deg, transparent, black 32%);
    mask-image: linear-gradient(90deg, transparent, black 32%);
    transition: transform .7s cubic-bezier(.16,1,.3,1), opacity .5s ease;
}
.quran-banner:hover .quran-image { transform: scale(1.035); opacity: .9; }

/* ================= MOBILE OVERLAY ================= */
.overlay {
    display: none; position: fixed; inset: 0;
    background: rgba(0,0,0,.5); backdrop-filter: blur(3px); z-index: 90;
}

/* ================= RESPONSIVE ================= */

/* laptop / tablet landscape: hero becomes one column */
@media (max-width: 1150px) {
    .main { padding-left: 26px; padding-right: 26px; }
    .hero { grid-template-columns: 1fr; gap: 26px; }
    .project-cover {
        position: relative; top: auto; order: -1;
        height: 460px; max-width: 520px; margin: 0 auto;
    }
    .bottom { grid-template-columns: 1fr; }
}

/* tablet: sidebar becomes off-canvas */
@media (max-width: 850px) {
    .sidebar { transform: translateX(-100%); transition: transform .35s cubic-bezier(.16,1,.3,1); }
    .sidebar.open { transform: translateX(0); }
    .overlay.show { display: block; }
    .main { margin-left: 0; width: 100%; padding: 20px 18px 50px; }
    .mobile-menu { display: grid; place-items: center; }
    .topbar { margin-bottom: 22px; }
    .cards { grid-template-columns: 1fr; }
    .stats { grid-template-columns: 1fr 1fr; }
    .stats .stat:last-child { grid-column: 1 / -1; }
    .banner-content { width: 100%; padding: 30px 26px; }
    .quran-image { opacity: .22; width: 90%; }
    .project-media { padding: 22px 18px 24px; }
    .user-info { display: none; }
    .user { padding-left: 6px; }
}

/* phones */
@media (max-width: 650px) {
    .theme-toggle { width: 44px; padding: 0; justify-content: center; }
    .theme-toggle-text { display: none; }
    .icon-button { display: none; }
    .topbar { height: 48px; }

    .hero h1 { font-size: 40px; }
    .project-cover { height: 380px; border-radius: 22px; }

    .hero-description { font-size: 15.5px; }
    .hero-description ol > li { padding: 16px 16px 14px 58px; }
    .hero-description ol > li::before { left: 14px; top: 16px; width: 32px; height: 32px; font-size: 15px; }
    .arabic-ayah { font-size: 28px; }
    .sample-ayah { padding: 26px 18px 20px; }
    .translation-level-row { grid-template-columns: 1fr 1.5fr 48px; }
    .translation-level-row > div { padding: 11px 10px; font-size: 13px; }

    .journey-content { flex-direction: column; align-items: flex-start; }
    .card { padding: 24px 22px; }
    .journey h2, .continue h2 { font-size: 30px; }
    .continue-bottom { flex-direction: column; align-items: stretch; }
    .read-btn { text-align: center; }

    .stats { grid-template-columns: 1fr; }
    .stats .stat:last-child { grid-column: auto; }
    .stat { min-height: 92px; }

    .activity { padding: 22px 18px; }
    .activity-name { white-space: normal; }
    .project-video-title { font-size: 20px; }
}

/* ================= REDUCE MOTION ================= */
@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        scroll-behavior: auto !important;
        transition-duration: .01ms !important;
    }
}

</style>
</head>

<body>
<div class="app">

    <!-- ================= SIDEBAR ================= -->
    

    <div class="overlay" id="overlay"></div>

    <!-- ================= MAIN ================= -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">

            <button class="mobile-menu" id="menuButton" type="button" aria-label="Open navigation">☰</button>

            <div></div>

            <div class="top-actions">

                <button class="theme-toggle" id="themeToggle" type="button" aria-label="Switch to dark mode">
                    <span class="theme-toggle-icon" id="themeIcon">☾</span>
                    <span class="theme-toggle-text" id="themeLabel">Dark Mode</span>
                </button>

                <button class="icon-button" type="button" aria-label="More options">♧</button>

                <div class="user">
                    <div class="user-info">
                        <div class="user-name">{{ $userName }}</div>
                        <div class="user-status">Keep going, you're doing great.</div>
                    </div>
                    <div class="user-avatar">{{ $userInitial }}</div>
                </div>

            </div>
        </header>

        <!-- HERO -->
        <section class="hero">

            <div class="hero-text">
                <div class="eyebrow">Your Quranic Journey</div>

                <h1>
                    Assalamu Alaikum,
                    <span>{{ $userName }}</span>
                </h1>
                <div class="gold-line"></div>
          <div class="hero-description collapsible" id="heroDescription">

    <h3>A Multi-Layered, Exegetic, Meditative Translation of the Glorious Qur’ān</h3>

    <p>
        The Qur’ān is not merely a text to be translated. It is a Divine discourse whose
        words, structures and contexts carry more than any single English equivalent can hold.
    </p>

    <p>
        Translations usually choose one English expression for an Arabic word and leave the
        remaining shades of meaning to footnotes, commentary, or the reader’s knowledge of
        Arabic and tafsīr (Qur’ānic exegesis). This translation takes a different approach:
        the explanation travels with the text itself.
    </p>


    <h3>One Translation, Four Layers of Meaning</h3>

    <p>
        Through different fonts and colours, the translation can be read at four levels,
        set together yet remaining distinct. Each level builds upon the one before it,
        progressively widening the reader’s access to the meaning conveyed by the Qur’ānic text.
    </p>

    <ol>
        <li>
            <strong>Denotative level</strong>
            <p>
                A near-literal rendition, much like the main body of most translations,
                though often giving several denotative meanings in succession. It is the
                briefest and most superficial level: it conveys what the Arabic words denote,
                but cannot by itself convey their full connotations and implications.
            </p>
        </li>

        <li>
            <strong>Basic language-level rendering</strong>
            <p>
                The denotative text together with meanings implied by classical Arabic usage:
                what an early Arab listener, or anyone well versed in classical Arabic, would
                understand through the language itself. This level therefore seeks to convey
                not merely what the Arabic words denote, but what they communicate through
                their usage, constructions, conventions and rhetorical forms.
            </p>
        </li>

        <li>
            <strong>Standard exegetic level</strong>
            <p>
                This adds meanings established through the revelation itself: related Qur’ānic
                passages, authentic Ḥadīths of the Prophet ﷺ that explain the text, its
                occasion of revelation where relevant, and its immediate context.
            </p>
        </li>

        <li>
            <strong>Exegetic meditative level</strong>
            <p>
                This adds a holistic reading within the whole of revelation and its major
                concepts. It draws upon the commentary and insights of the Companions,
                their Followers and later authoritative exegetes, together with carefully
                integrated exegetic and cohesive insertions that make the logic and unity
                of the text more visible.
            </p>
        </li>
    </ol>

    <p>
        The higher levels (3 and 4) are therefore not commentary unrelated to the Qur’ānic
        text, nor commentary imposed upon it. They are grounded in the exegetical tradition
        and in the Qur’ān’s own interconnected discourse, while allowing the reader to
        contemplate meanings that a conventional translation may leave beyond the translated text.
    </p>


    <h3>Exegetic and Meditative</h3>

    <p>
        The translation is exegetic because the meaning of a Qur’ānic expression is determined
        not by its dictionary definition alone, but by its context, grammar, Qur’ānic usage
        and rhetorical function. It seeks to convey what an expression means in its particular
        context, while never presenting an explanation as though it were part of the Qur’ānic
        wording itself.
    </p>

    <p>
        It is meditative because the upper levels are designed for the reflective reader.
        Level 3 shows what the revelation itself establishes or implies, while Level 4 opens
        up deeper exegetical insights and the connections between the Qur’ān’s meanings,
        allowing the reader to pause over the text rather than merely absorb its surface meaning.
    </p>


    <h3>Method and Sources</h3>

    <ul>
        <li>
            <strong>Creed and reading.</strong>
            The text is explained in accordance with the creed of the Prophet ﷺ and his
            Companions, particularly regarding Allāh’s names, attributes and actions and
            matters of the unseen (ghayb). The Arabic text follows the Ḥafṣ ʿan ʿĀṣim riwāyah.
        </li>

        <li>
            <strong>Sources.</strong>
            The exegetic content draws chiefly on more than twenty-five authoritative tafsīrs.
            Older sources include al-Ṭabarī, al-Qurṭubī, Ibn Kathīr, al-Biqāʿī, al-Baghawī
            and Abū l-Suʿūd. More recent sources include al-ʿUthaymīn, al-Jazāʾirī,
            al-Shanqīṭī, al-Saʿdī, Ibn ʿĀshūr, al-Tafsīr al-Muyassar and al-Tafsīr al-Mukhtaṣar.
            Lectures by contemporary scholars and researchers of Qur’ānic iʿjāz (inimitability)
            have also contributed.
        </li>

        <li>
            <strong>Ḥadīth.</strong>
            Only authentic (ṣaḥīḥ) Ḥadīths are relied upon. They are not cited directly,
            as the work is intended for the general reader rather than specialist scholars.
        </li>

        <li>
            <strong>Exclusions.</strong>
            Isrāʾīliyyāt (narratives of Jewish and Christian origin) are disregarded altogether.
            Jurisprudential matters are kept broad and minimal, and points of juristic disagreement
            are avoided. Occasions of revelation are used where needed, but are not highlighted
            unless strictly necessary.
        </li>

        <li>
            <strong>Multiple senses.</strong>
            The literal and figurative senses available in Arabic at the time of revelation
            are all considered, provided they do not contradict the Qur’ānic message or the
            understanding of the master exegetes. Where appropriate, multiple senses are
            integrated into broader units of meaning, presented together or in succession.
            Where exegetes differ, the rendering follows what best fits the context.
        </li>

        <li>
            <strong>Structure.</strong>
            Sūrahs are divided into passages according to meaning, each preceded by its Arabic
            text, with attention to the unity within each passage and the connections between passages.
        </li>
    </ul>


    <h3>The English</h3>

    <p>
        The English is formal, clear and free of archaic or Biblical usage. It is freed from
        word-for-word literality and often uses shorter sentences than the Arabic.
    </p>

    <p>
        It makes generous use of adjectives and adverbs to carry the semantic richness of the
        Arabic, accepting a degree of density where this is necessary for precision. It makes
        no attempt to imitate the inimitable style of the Divine text.
    </p>


    <h3>The Text Carries Its Explanation</h3>

    <p>
        The translation carries its explanation within the text itself, without relying on
        footnotes. Where a brief clarification cannot be naturally woven into the flow, it is
        set in parentheses. These parentheses, together with their contents, follow the same
        font and colour system as the rest of the text.
    </p>


    <h3>The Arabic Remains the Qur’ān</h3>

    <p>
        The Arabic stands beside every passage because this translation is neither equivalent
        to the Qur’ān nor a substitute for it. The Arabic remains the revealed text; the English
        is an attempt to convey its meanings. Its presence also enables Arabic readers to connect
        what they learn with the original wording and to benefit from the reward of reciting it.
    </p>


    <h3>What the Fonts and Colours Signify</h3>

    <div class="translation-levels-table">

        <div class="translation-level-row translation-level-header">
            <div>Font and colour</div>
            <div>What it signals</div>
            <div>Level</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Black bold italics</strong></div>
            <div>Denotative equivalents, closest to the literal</div>
            <div>1</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Black thin italics</strong></div>
            <div>
                Nouns for pronouns (or the reverse), and adverbial substitutions,
                for clarity or to avoid repetition
            </div>
            <div>1</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Orange bold italics</strong></div>
            <div>
                The beginning of an alternative, parallel denotation of the same word,
                marked on a single word or punctuation mark
            </div>
            <div>1</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Maroon italics</strong></div>
            <div>
                Meanings implied within classical Arabic usage: denotative implications
                (as with oaths, imperatives and exclamations) and commonly understood connotations
            </div>
            <div>2</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Green roman</strong></div>
            <div>
                Extra-textual implications from revelation: parallel Qur’ānic passages,
                authentic Ḥadīths, occasions of revelation and context
            </div>
            <div>3</div>
        </div>

        <div class="translation-level-row">
            <div><strong>Blue roman</strong></div>
            <div>
                Exegetic additions derived from the inferences of the Companions,
                their Followers and later exegetes, together with cohesive insertions
            </div>
            <div>4</div>
        </div>

    </div>


    <h3>How to Read This Translation</h3>

    <p>
        The reader is free to choose the level of interpretation that best suits their purpose
        by following the different font types and colours.
    </p>

    <p>
        It is strongly recommended, however, that the text be read at one of two principal
        levels: the basic language-level rendering or the exegetic-level rendering.
    </p>

    <p>
        The basic language-level rendering is attained by reading the denotative text—
        comprising the denotative equivalents (black bold italics), nominal-pronominal
        substitutions and adverbial replacements (black thin italics), and parallel denotative
        markers (orange bold italics)—together with the implied and connotative meanings
        (maroon italics). This seeks to reflect the meaning understood by Arab listeners at
        the time of revelation, and by readers well versed in classical Arabic, through the
        language itself.
    </p>

    <p>
        The exegetic-level rendering is attained by reading the whole text in all its font types
        and colours. It includes the basic language-level text together with the extra-textual
        implications from revelation (green roman) and the extended exegetic and cohesive
        insertions (blue roman).
    </p>


    <h3>The Layers at Work: A Sample Āyah (Sūrat al-Naṣr, 110:1)</h3>

    <div class="sample-ayah">
        <div class="arabic-ayah">
            إِذَا جَاءَ نَصْرُ ٱللَّهِ وَٱلْفَتْحُ
        </div>

        <div class="sample-level">
            <strong>The basic language-level rendering (levels 1 and 2 only):</strong>

            <p>
                When the utmost, divine support (which is a solemn promise of almighty Allāh’s)
                comes to pass, together with the grand triumph, the ascendency, and the conquest…
            </p>
        </div>

        <div class="sample-level">
            <strong>The exegetic-level rendering (all levels, 1–4):</strong>

            <p>
                When the utmost, divine support against unbelievers (which is a solemn,
                long-predestined promise of almighty Allāh’s, the invincible master of absolute
                sovereignty, and the omnipotent subjugator over everything in existence) comes
                to pass, with your conclusive supremacy over your enemies, O messenger Muḥammad,
                together with the grand triumph, the ascendency of Islām, and its culmination
                with the conquest of Makkah and beyond…
            </p>
        </div>
    </div>


    <p class="hero-closing">
        This is an invitation not simply to read the Qur’ān in English, but to reflect upon
        its Divine message with greater depth, precision and awareness.
    </p>
</div> 
   <button type="button" class="see-more-btn" id="seeMoreBtn"
            aria-expanded="false" aria-controls="heroDescription">
        <span id="seeMoreText">See more</span>
        <span class="see-more-arrow">↓</span>
    </button>
</div> 
            <div class="project-cover">
                <img
                    src="{{ asset('images/quran-project-cover.png') }}"
                    alt="The Glorious Quran — Multi-Layered Exegetic Meditative Translation"
                >
                <div class="project-cover-overlay"></div>
                <div class="project-cover-label">The Glorious Quran</div>
            </div>

        </section>

        <!-- CARDS -->
        <section class="cards">

    <article class="card journey">
        <div class="card-title">Your Quran Journey</div>

        <div class="journey-content">
            <div>
                <h2>Keep going.</h2>
                <p>Your consistency brings you closer to the Quran every day.</p>
                <div class="reward">✦ Every letter is a reward.</div>
            </div>

            <div class="progress">
                <div class="progress-inner">
                    <div class="progress-number">{{ $quranProgress }}%</div>
                    <div class="progress-label">Completed</div>
                </div>
            </div>
        </div>
    </article>


    <article class="card continue">
        <div class="card-title">Continue Reading</div>

        @if($lastReading)

            <h2>
                Surah {{ $lastReading->verse->surah->name_en}}
            </h2>

            <div class="verse-number">
                Verse {{ $lastReading->verse->verse_number}}
            </div>

            <div class="progress-bar">
                <span style="width: {{ $quranProgress }}%;"></span>
            </div>

            <div class="continue-bottom">

                <div class="last-read">
                    Last read · {{ $lastReading->created_at->diffForHumans() }}
                </div>

                <a
                    href="{{ route('surahs.reader') }}"
                    class="read-btn"
                >
                    Continue Reading →
                </a>

            </div>

        @else

            <h2>Start Your Journey</h2>

            <div class="verse-number">
                No reading progress yet.
            </div>

            <div class="continue-bottom">

                <div class="last-read">
                    Start reading the Quran today.
                </div>

                <a
                    href="{{ route('surahs.reader') }}"
                    class="read-btn"
                >
                    Read Quran →
                </a>

            </div>

        @endif

    </article>

</section>

        <!-- PROJECT SHOWCASE -->
        <section class="project-showcase">

            <div class="project-media">
                <div class="project-video-wrapper">

                    <div class="project-video-header">
                        <span class="project-video-icon">▶</span>
                        <div>
                            <div class="project-video-title">About the Project</div>
                            <div class="project-video-subtitle">Watch the explanatory video</div>
                        </div>
                    </div>

                    <div class="project-video">
                        <iframe
                            src="https://www.youtube.com/embed/zRG1kEZ-pW4"
                            title="What is the Multi-Layered, Exegetic, Meditative Translation of the Qur'an?"
                            loading="lazy"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen>
                        </iframe>
                    </div>

                </div>
            </div>

        </section>

        <!-- STATS -->
        <section class="stats">

            <div class="stat">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21.5z"/>
                        <path d="M4 5.5v16"/><path d="M8 7h8"/><path d="M8 11h7"/>
                    </svg>
                </div>
                <div>
                 <div class="stat-number">{{ $versesRead }}</div>
                 <div class="stat-name">Verses read</div>
                </div>
            </div>

            <div class="stat">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3c1.5 2.5 4.5 4.5 4.5 8.5A4.5 4.5 0 1 1 7.5 11c0-2.3 1.3-4.2 3-5.8"/>
                        <path d="M12 13c1.1 1 1.5 2 1.5 3"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-number">{{ $readingDays }}</div>
                    <div class="stat-name">Reading streak</div>
                </div>
            </div>

            <div class="stat">
                <div class="stat-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 12v8a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-8"/>
                        <path d="M12 3v13"/><path d="m7 8 5-5 5 5"/>
                    </svg>
                </div>
                <div>
                    <div class="stat-number" id="bookmarkStatCount">{{ $bookmarksCount }}</div>
                    <div class="stat-name">Bookmarked</div>
                </div>
            </div>

        </section>

        <!-- BOTTOM -->
        <section class="bottom">

    <!-- BOOKMARKS -->
    <article class="activity bookmark-panel">
        <div class="activity-header">
            <h2>Bookmarks</h2>
            <a href="{{ route('bookmarks.index') }}" class="view-all">Open all</a>
        </div>

        <div id="dashboardBookmarks"></div>
    </article>


    <!-- RECENT ACTIVITY -->
    <article class="activity">
        <div class="activity-header">
            <h2>Recent Activity</h2>
            <a href="#" class="view-all">View all</a>
        </div>

        @if($lastReading)

            <div class="activity-item">
                <div class="activity-icon">▢</div>

                <div class="activity-info">
                    <div class="activity-name">
                        Read Surah {{ $lastReading->verse->surah->name }},
                        Verse {{ $lastReading->verse->verse_number }}
                    </div>

                    <div class="activity-time">
                        {{ $lastReading->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="activity-arrow">→</div>
            </div>

        @else

            <div class="activity-item">
                <div class="activity-icon">▢</div>

                <div class="activity-info">
                    <div class="activity-name">
                        No reading activity yet
                    </div>

                    <div class="activity-time">
                        Start reading the Quran
                    </div>
                </div>

                <div class="activity-arrow">→</div>
            </div>

        @endif

    </article>


    <!-- QURAN BANNER -->
    <article class="quran-banner">

        <div class="quran-pattern"></div>

        <div class="banner-content">

            <div class="banner-label">
                The Holy Quran
            </div>

            <h2>
                A book to return to, every day.
            </h2>

            <p>
                Open the Qur'an to revisit your reading,
                or begin a quiet moment with the words of Allah.
            </p>

            <a
                href="{{ route('surahs.reader') }}"
                class="banner-button"
            >
                Continue Journey →
            </a>

        </div>

        <img
            class="quran-image"
            src="https://images.unsplash.com/photo-1609599006353-e629aaabfeae?auto=format&fit=crop&w=1000&q=85"
            alt="Holy Quran"
            loading="lazy"
        >

    </article>

</section>

    </main>
</div>

<script>

    /* ================= SEE MORE ================= */
const heroDesc = document.getElementById("heroDescription");
const seeMoreBtn = document.getElementById("seeMoreBtn");
const seeMoreText = document.getElementById("seeMoreText");
const COLLAPSED_HEIGHT = 420; // لازم يطابق max-height بالـ CSS

if (heroDesc && seeMoreBtn) {
    // إذا النص أقصر من الحد، ما نحتاج الزر
    if (heroDesc.scrollHeight <= COLLAPSED_HEIGHT + 40) {
        heroDesc.classList.remove("collapsible");
        seeMoreBtn.style.display = "none";
    }

    seeMoreBtn.addEventListener("click", () => {
        const expanding = !heroDesc.classList.contains("expanded");

        if (expanding) {
            heroDesc.style.maxHeight = heroDesc.scrollHeight + "px";
            heroDesc.classList.add("expanded");
            // بعد انتهاء الحركة نحرر الارتفاع حتى ما ينقص المحتوى عند تغيير حجم الشاشة
            setTimeout(() => { heroDesc.style.maxHeight = "none"; }, 650);
        } else {
            heroDesc.style.maxHeight = heroDesc.scrollHeight + "px";
            requestAnimationFrame(() => {
                heroDesc.classList.remove("expanded");
                heroDesc.style.maxHeight = COLLAPSED_HEIGHT + "px";
            });
            heroDesc.closest(".hero-text").scrollIntoView({ behavior: "smooth", block: "start" });
        }

        seeMoreBtn.setAttribute("aria-expanded", String(expanding));
        seeMoreText.textContent = expanding ? "See less" : "See more";
    });
}
/* ================= MOBILE SIDEBAR ================= */
const menuButton = document.getElementById("menuButton");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

if (menuButton && sidebar && overlay) {
    menuButton.addEventListener("click", () => {
        sidebar.classList.toggle("open");
        overlay.classList.toggle("show");
    });
    overlay.addEventListener("click", () => {
        sidebar.classList.remove("open");
        overlay.classList.remove("show");
    });
}

/* ================= DARK MODE ================= */
const themeToggle = document.getElementById("themeToggle");
const themeIcon = document.getElementById("themeIcon");
const themeLabel = document.getElementById("themeLabel");

function applyDashboardTheme(isDark) {
    document.body.classList.toggle("dark-mode", isDark);
    if (themeIcon) themeIcon.textContent = isDark ? "☀" : "☾";
    if (themeLabel) themeLabel.textContent = isDark ? "Light Mode" : "Dark Mode";
    if (themeToggle) {
        themeToggle.setAttribute("aria-label", isDark ? "Switch to light mode" : "Switch to dark mode");
    }
}

applyDashboardTheme(localStorage.getItem("hidayah-dashboard-theme") === "dark");

if (themeToggle) {
    themeToggle.addEventListener("click", () => {
        const isDark = !document.body.classList.contains("dark-mode");
        applyDashboardTheme(isDark);
        localStorage.setItem("hidayah-dashboard-theme", isDark ? "dark" : "light");
    });
}

/* ================= BOOKMARKS ================= */
const bookmarkKey = "hidayah-bookmarks";

function getBookmarks() {
    try {
        return JSON.parse(localStorage.getItem(bookmarkKey) || "[]");
    } catch (error) {
        return [];
    }
}

function escapeHTML(value) {
    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function renderDashboardBookmarks() {
    const container = document.getElementById("dashboardBookmarks");
    const count = document.getElementById("bookmarkStatCount");

    if (!container) return;

    const bookmarks = getBookmarks();
    const items = bookmarks.slice(0, 3);

    if (count) count.textContent = String(bookmarks.length);

    if (!items.length) {
        container.innerHTML = `
            <div class="activity-item">
                <div class="activity-icon">♡</div>
                <div class="activity-info">
                    <div class="activity-name">No saved bookmarks yet.</div>
                    <div class="activity-time">Save a verse from the Tafsir reader to see it here.</div>
                </div>
                <div class="activity-arrow">→</div>
            </div>`;
        return;
    }

    container.innerHTML = items.map((item) => {
        const detail = item.text ? escapeHTML(item.text.substring(0, 90)) : "Saved verse";
        const label = item.title
            ? escapeHTML(item.title)
            : item.id ? escapeHTML(item.id) : "Saved verse";

        return `
            <div class="activity-item">
                <div class="activity-icon">♡</div>
                <div class="activity-info">
                    <div class="activity-name">${label}</div>
                    <div class="activity-time">${detail}</div>
                </div>
                <div class="activity-arrow">→</div>
            </div>`;
    }).join("");
}

renderDashboardBookmarks();
window.addEventListener("storage", renderDashboardBookmarks);


</script>

</body>
</html>