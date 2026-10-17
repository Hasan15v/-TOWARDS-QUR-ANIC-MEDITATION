<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hidayah - Sign In</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }

  /* animated angle for the rotating border light */
  @property --ang { syntax: '<angle>'; initial-value: 0deg; inherits: false; }

  html { -webkit-text-size-adjust: 100%; }

  body {
    min-height: 100vh;
    min-height: 100dvh;
    padding: 24px 16px;
    font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: radial-gradient(circle at 50% 30%, #0e4439 0%, #04140f 60%, #010604 100%);
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow-x: hidden;
    position: relative;
    perspective: 1000px;
  }

  /* ===== Floating Background Particles ===== */
  .bg-particles {
    position: fixed;
    inset: 0;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
  }
  .particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(212, 175, 110, 0.4);
    box-shadow: 0 0 10px rgba(212, 175, 110, 0.6);
    animation: floatUp linear infinite;
  }

  @keyframes floatUp {
    0% { transform: translateY(105vh) scale(0.3); opacity: 0; }
    20% { opacity: 0.8; }
    80% { opacity: 0.8; }
    100% { transform: translateY(-10vh) scale(1.2); opacity: 0; }
  }

  /* ===== Ornament corners (mandala) ===== */
  .mandala {
    position: fixed;
    width: clamp(240px, 34vw, 420px);
    height: clamp(240px, 34vw, 420px);
    background: radial-gradient(circle, rgba(212,175,110,0.25) 0%, transparent 70%);
    opacity: 0;
    pointer-events: none;
    z-index: 2;
  }
  .mandala svg { width:100%; height:100%; filter: drop-shadow(0 0 12px rgba(212,175,110,0.3)); }
  .m-tr { top: -90px; right: -90px; animation: mandalaIn 1.6s ease-out .2s forwards, mandalaSpin 90s linear infinite; }
  .m-bl { bottom: -100px; left: -100px; animation: mandalaIn 1.6s ease-out .5s forwards, mandalaSpinReverse 90s linear infinite; }

  @keyframes mandalaIn {
    from { opacity: 0; transform: scale(.5) rotate(-30deg); }
    to { opacity: 0.85; transform: scale(1) rotate(0deg); }
  }
  @keyframes mandalaSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
  @keyframes mandalaSpinReverse { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }

  /* Corner lines */
  .corner-line {
    position: fixed;
    opacity: 0;
    z-index: 2;
    animation: fadeInLine 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    filter: drop-shadow(0 0 6px rgba(212,175,110,0.4));
    pointer-events: none;
  }
  .cl-tl { top: 28px; left: 28px; animation-delay: .6s; }
  .cl-br { bottom: 28px; right: 28px; animation-delay: .8s; transform: rotate(180deg); }

  @keyframes fadeInLine {
    from { opacity: 0; transform: translateY(-15px) scale(0.9); }
    to { opacity: 0.85; transform: translateY(0) scale(1); }
  }

  /* ===== Frame & Glassmorphism Card ===== */
  .frame-wrap {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 400px;
    margin: auto;
    opacity: 0;
    animation: frameIn 1.1s cubic-bezier(.16, 1, 0.3, 1) .3s forwards;
  }

  @keyframes frameIn {
    from { opacity: 0; transform: scale(.92) translateY(30px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }

  .frame {
    position: relative;
    border: 1px solid rgba(212, 175, 110, 0.45);
    border-radius: 20px;
    padding: 34px 32px 26px;
    background: linear-gradient(145deg, rgba(10, 48, 40, 0.7), rgba(2, 15, 12, 0.88));
    box-shadow:
      0 0 0 1px rgba(212, 175, 110, 0.15),
      0 24px 70px rgba(0, 0, 0, 0.7),
      inset 0 0 35px rgba(212, 175, 110, 0.08);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    width: 100%;
    overflow: hidden;
    transition: transform .25s ease-out;
    will-change: transform;
  }

  .frame::before {
    content: '';
    position: absolute; inset: 8px;
    border: 1px solid rgba(212, 175, 110, 0.18);
    border-radius: 14px;
    pointer-events: none;
  }

  /* Rotating border light (no more diagonal line) */
  .glow-border {
    position: absolute; inset: -1px;
    border-radius: 21px;
    padding: 2px;
    background: conic-gradient(from var(--ang), transparent 0%, transparent 75%, rgba(255, 230, 180, 0.9) 88%, #d4af6e 92%, transparent 98%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    animation: rotateBorder 6s linear infinite;
    pointer-events: none;
  }
  @keyframes rotateBorder { to { --ang: 360deg; } }

  /* Soft halo behind the frame */
  .frame-halo {
    position: absolute; inset: -30px;
    border-radius: 30px;
    background: radial-gradient(circle, rgba(212, 175, 110, 0.18) 0%, transparent 70%);
    z-index: -1;
    animation: haloPulse 4s ease-in-out infinite;
    pointer-events: none;
  }
  @keyframes haloPulse {
    0%, 100% { opacity: .4; transform: scale(0.98); }
    50% { opacity: 0.9; transform: scale(1.05); }
  }

  /* Header Branding */
  .ornament-top {
    text-align: center;
    color: #d4af6e;
    font-size: 26px;
    line-height: 1;
    margin-bottom: 8px;
    filter: drop-shadow(0 0 8px rgba(212, 175, 110, 0.6));
    animation: floatY 3.5s ease-in-out infinite, fadeIn .8s ease-out .5s backwards;
  }
  @keyframes floatY { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }

  h1.brand {
    text-align: center;
    color: #f5ebd9;
    font-size: 23px;
    line-height: 1.3;
    font-weight: 700;
    letter-spacing: 1px;
    margin-bottom: 6px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.4);
    animation: fadeInDown .7s cubic-bezier(0.16, 1, 0.3, 1) .5s backwards;
  }
  @keyframes fadeInDown { from { opacity: 0; transform: translateY(-16px); } to { opacity: 1; transform: translateY(0); } }

  p.subtitle {
    text-align: center;
    color: #9fb8ac;
    font-size: 12.5px;
    margin-bottom: 20px;
    animation: fadeIn .8s ease-out .65s backwards;
  }

  /* Form Elements */
  .form-group {
    margin-bottom: 14px;
    opacity: 0;
    animation: fadeInUp .6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
  }
  .form-group:nth-of-type(1) { animation-delay: .75s; }
  .form-group:nth-of-type(2) { animation-delay: .88s; }
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }

  label {
    display: block;
    color: #cdd9d2;
    font-size: 12.5px;
    font-weight: 500;
    margin-bottom: 6px;
    letter-spacing: .3px;
  }

  .input-wrap { position: relative; }
  .input-wrap .icon {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #6e8b7f; font-size: 15px;
    transition: color 0.3s ease, transform 0.3s ease;
    pointer-events: none;
  }

  input[type=email], input[type=password], input[type=text] {
    width: 100%;
    padding: 11px 40px 11px 40px;
    background: rgba(3, 20, 16, 0.55);
    border: 1px solid rgba(212, 175, 110, 0.25);
    border-radius: 10px;
    color: #f2e6cf;
    font-family: inherit;
    font-size: 14px;
    outline: none;
    transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
  }

  input::placeholder { color: #526e63; }

  input:focus {
    border-color: #d4af6e;
    box-shadow: 0 0 0 3px rgba(212, 175, 110, 0.15), 0 0 25px rgba(212, 175, 110, 0.2);
    background: rgba(3, 20, 16, 0.85);
  }

  /* browser autofill (the white/blue background) */
  input:-webkit-autofill,
  input:-webkit-autofill:hover,
  input:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #05231c inset !important;
    -webkit-text-fill-color: #f2e6cf !important;
    caret-color: #f2e6cf;
    border-color: rgba(212, 175, 110, 0.4);
    transition: background-color 9999s ease-in-out 0s;
  }

  .input-wrap:focus-within .icon {
    color: #d4af6e;
    transform: translateY(-50%) scale(1.15);
  }

  .eye {
    position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
    width: 34px; height: 34px;
    cursor: pointer; color: #6e8b7f;
    transition: all 0.3s ease;
    user-select: none;
    display: flex; align-items: center; justify-content: center;
  }
  .eye:hover { color: #d4af6e; }

  /* Checkbox & Links */
  .row-between {
    display: flex; align-items: center; justify-content: space-between; gap: 10px;
    margin-bottom: 18px;
    opacity: 0; animation: fadeInUp .6s ease-out .98s forwards;
    font-size: 12.5px;
  }

  .checkbox-container {
    display: flex; align-items: center; gap: 8px; color: #9fb8ac; cursor: pointer; user-select: none;
    margin-bottom: 0;
  }

  .checkbox-container input { display: none; }

  .custom-checkmark {
    width: 17px; height: 17px;
    border: 1px solid rgba(212,175,110,0.4);
    border-radius: 4px;
    background: rgba(3,20,16,0.6);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.25s ease;
    flex-shrink: 0;
  }

  .checkbox-container input:checked ~ .custom-checkmark {
    background: #d4af6e;
    border-color: #d4af6e;
    box-shadow: 0 0 10px rgba(212,175,110,0.4);
  }

  .custom-checkmark::after {
    content: '✓'; font-size: 11px; color: #04140f; font-weight: bold; display: none;
  }
  .checkbox-container input:checked ~ .custom-checkmark::after { display: block; }

  .row-between a { color: #d4af6e; text-decoration: none; transition: all 0.25s ease; }
  .row-between a:hover { text-shadow: 0 0 8px rgba(212, 175, 110, 0.5); text-decoration: underline; }

  /* Buttons */
  button.btn-gold {
    width: 100%;
    padding: 12px;
    background: linear-gradient(135deg, #e0ba78 0%, #b88a44 100%);
    border: none;
    border-radius: 10px;
    color: #051a14;
    font-family: inherit;
    font-weight: 700;
    font-size: 14.5px;
    letter-spacing: .5px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    opacity: 0;
    animation: fadeInUp .6s ease-out 1.1s forwards;
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 6px 20px rgba(0,0,0,0.3);
  }

  button.btn-gold::before {
    content: '';
    position: absolute; top: 0; left: -100%;
    width: 100%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
    transition: left 0.6s ease;
  }

  button.btn-gold:hover::before { left: 100%; }
  button.btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(212, 175, 110, 0.4);
    filter: brightness(1.05);
  }
  button.btn-gold:active { transform: translateY(0); }

  .divider {
    display: flex; align-items: center; gap: 14px;
    margin: 16px 0;
    color: #5c766c; font-size: 12px; font-weight: 500;
    opacity: 0; animation: fadeIn .6s ease-out 1.2s forwards;
  }
  .divider::before, .divider::after {
    content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, transparent, rgba(212,175,110,0.3), transparent);
  }

  /* ===== Google button (works for <a> and <button>) ===== */
  .btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 11px 14px;
    border-radius: 10px;
    border: 1px solid rgba(212, 175, 110, 0.35);
    background: rgba(3, 20, 16, 0.55);
    color: #f2e6cf;
    font-family: inherit;
    font-size: 13.5px;
    font-weight: 600;
    letter-spacing: .3px;
    text-decoration: none;
    cursor: pointer;
    opacity: 0;
    animation: fadeIn .6s ease-out 1.25s forwards;
    transition: border-color .3s ease, background .3s ease, transform .3s ease, box-shadow .3s ease;
  }
  .btn-google svg { flex-shrink: 0; }
  .btn-google:hover {
    border-color: #d4af6e;
    background: rgba(212, 175, 110, 0.1);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.25);
    color: #fff;
  }
  .btn-google:active { transform: translateY(0); }

  .footer-link {
    text-align: center;
    margin-top: 18px;
    color: #9fb8ac;
    font-size: 12.5px;
    opacity: 0; animation: fadeIn .6s ease-out 1.35s forwards;
  }
  .footer-link a { color: #d4af6e; font-weight: 600; text-decoration: none; transition: color 0.2s; }
  .footer-link a:hover { text-decoration: underline; color: #f2e6cf; }

  .translation-credit {
    text-align: center;
    margin-top: 16px;
    padding-top: 12px;
    border-top: 1px solid rgba(212, 175, 110, 0.15);
    opacity: 0; animation: fadeIn .8s ease-out 1.45s forwards;
  }
  .translation-credit .t-title {
    font-family: 'Great Vibes', cursive;
    font-size: 22px;
    color: #e9c98f;
    line-height: 1.1;
    text-shadow: 0 0 10px rgba(233, 201, 143, 0.2);
  }
  .translation-credit .t-sub {
    font-family: 'Playfair Display', serif;
    font-style: italic;
    font-size: 10px;
    color: #7f9a8e;
    letter-spacing: .3px;
    margin-top: 2px;
  }
  .translation-credit .t-author {
    font-family: 'Playfair Display', serif;
    font-size: 10.5px;
    color: #d4af6e;
    margin-top: 4px;
    letter-spacing: .5px;
  }

  @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }


  /* =====================================================
     RESPONSIVE
  ===================================================== */

  /* Tablet (iPad portrait & similar) */
  @media (min-width: 641px) and (max-width: 1024px) {
    .frame-wrap { max-width: 420px; }
  }

  /* Short laptop screens */
  @media (max-height: 760px) and (min-width: 641px) {
    body { padding: 14px 16px; }
    .frame { padding: 26px 30px 20px; }
    .ornament-top { font-size: 22px; margin-bottom: 4px; }
    h1.brand { font-size: 21px; }
    p.subtitle { margin-bottom: 14px; }
    .form-group { margin-bottom: 10px; }
    .row-between { margin-bottom: 14px; }
    .divider { margin: 12px 0; }
    .footer-link { margin-top: 12px; }
    .translation-credit { margin-top: 12px; padding-top: 8px; }
    .translation-credit .t-title { font-size: 20px; }
  }

  /* Phones */
  @media (max-width: 640px) {
    body { padding: 16px 12px; align-items: flex-start; }
    .frame-wrap { max-width: 100%; }
    .frame { padding: 28px 20px 22px; border-radius: 18px; }
    h1.brand { font-size: 21px; }

    /* 16px stops iOS from zooming in when an input is focused */
    input[type=email], input[type=password], input[type=text] { font-size: 16px; }

    .corner-line { display: none; }
    .mandala { opacity: .5 !important; }
    .translation-credit .t-title { font-size: 20px; }
  }

  /* Very small phones */
  @media (max-width: 360px) {
    .frame { padding: 24px 16px 18px; }
    h1.brand { font-size: 19px; letter-spacing: .5px; }
    .row-between { flex-direction: column; align-items: flex-start; gap: 8px; }
  }

  /* Landscape phones: allow scroll, keep card compact */
  @media (max-height: 500px) {
    body { align-items: flex-start; }
  }

  @media (prefers-reduced-motion: reduce) {
    *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; }
  }
</style>
</head>
<body>

  <!-- Floating particles container -->
  <div class="bg-particles" id="particles"></div>

  <!-- Background Mandalas -->
  <div class="mandala m-tr">
    <svg viewBox="0 0 200 200">
      <circle cx="100" cy="100" r="90" fill="none" stroke="#d4af6e" stroke-width="0.8" opacity="0.4" stroke-dasharray="4 2"/>
      <circle cx="100" cy="100" r="70" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.3"/>
      <circle cx="100" cy="100" r="50" fill="none" stroke="#d4af6e" stroke-width="0.8" opacity="0.25"/>
      <polygon points="100,10 120,80 190,100 120,120 100,190 80,120 10,100 80,80" fill="none" stroke="#d4af6e" stroke-width="0.5" opacity="0.3"/>
    </svg>
  </div>
  <div class="mandala m-bl">
    <svg viewBox="0 0 200 200">
      <circle cx="100" cy="100" r="90" fill="none" stroke="#d4af6e" stroke-width="0.8" opacity="0.4" stroke-dasharray="4 2"/>
      <circle cx="100" cy="100" r="70" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.3"/>
      <circle cx="100" cy="100" r="50" fill="none" stroke="#d4af6e" stroke-width="0.8" opacity="0.25"/>
      <polygon points="100,10 120,80 190,100 120,120 100,190 80,120 10,100 80,80" fill="none" stroke="#d4af6e" stroke-width="0.5" opacity="0.3"/>
    </svg>
  </div>

  <!-- Corner Ornamental Lines -->
  <svg class="corner-line cl-tl" width="100" height="100" viewBox="0 0 120 120"><path d="M10 100 L10 10 L100 10" stroke="#d4af6e" stroke-width="1.5" fill="none"/><circle cx="10" cy="105" r="3" fill="#d4af6e"/></svg>
  <svg class="corner-line cl-br" width="100" height="100" viewBox="0 0 120 120"><path d="M10 100 L10 10 L100 10" stroke="#d4af6e" stroke-width="1.5" fill="none"/><circle cx="10" cy="105" r="3" fill="#d4af6e"/></svg>

  <!-- Main Card Container -->
  <div class="frame-wrap" id="cardWrap">
    <div class="frame" id="cardFrame">
      <div class="frame-halo"></div>
      <div class="glow-border"></div>

      <div class="ornament-top">✦</div>
      <h1 class="brand">Towards Qur'anic Meditation</h1>
      <p class="subtitle">Continue your journey through the Quran</p>

      @if (session('success'))
        <div style="background:rgba(16,185,129,0.15);border:1px solid #10b981;color:#a7f3d0;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;">
          {{ session('success') }}
        </div>
      @endif
      @if ($errors->any())
        <div style="background:rgba(239,68,68,0.12);border:1px solid #ef4444;color:#fecaca;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:14px;">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('authenticate') }}" method="POST">
        @csrf

        <div class="form-group">
          <label for="email">Email address</label>
          <div class="input-wrap">
            <span class="icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </span>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            </span>
            <input type="password" name="password" id="password" placeholder="Enter your password" required autocomplete="current-password">
            <span class="eye" onclick="togglePwd()" id="eyeBtn" role="button" aria-label="Show or hide password">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            </span>
          </div>
        </div>

        <div class="row-between">
          <label class="checkbox-container">
            <input type="checkbox" name="remember">
            <span class="custom-checkmark"></span>
            Remember me
          </label>
          <a href="#">Forgot password?</a>
        </div>

        <button type="submit" class="btn-gold">Sign in</button>
      </form>

      <div class="divider">or</div>

      <a href="{{ route('google.redirect') }}" class="btn-google">
        <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true"><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/></svg>
        <span>Continue with Google</span>
      </a>

      <div class="footer-link">
        New to Hidayah? <a href="{{ route('register') }}">Create an account</a>
      </div>

      <div class="translation-credit">
        <div class="t-title">The Glorious Qur'ān</div>
        <div class="t-sub">A Multi-Layered Exegetic, Meditative Translation</div>
        <div class="t-author">Basil Q. Muhammad</div>
      </div>
    </div>
  </div>

<script>
  // Background particles (fewer on small screens)
  const particlesContainer = document.getElementById('particles');
  const particleCount = window.innerWidth < 640 ? 14 : 26;

  for (let i = 0; i < particleCount; i++) {
    const particle = document.createElement('div');
    particle.className = 'particle';
    const size = Math.random() * 3 + 1.5;
    particle.style.width = `${size}px`;
    particle.style.height = `${size}px`;
    particle.style.left = `${Math.random() * 100}%`;
    particle.style.animationDuration = `${Math.random() * 12 + 8}s`;
    particle.style.animationDelay = `${Math.random() * 5}s`;
    particlesContainer.appendChild(particle);
  }

  // Password visibility toggle
  function togglePwd(){
    const f = document.getElementById('password');
    const eyeBtn = document.getElementById('eyeBtn');
    if (f.type === 'password') {
      f.type = 'text';
      eyeBtn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>`;
    } else {
      f.type = 'password';
      eyeBtn.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>`;
    }
  }

  // Subtle 3D tilt: only on devices with a real mouse (not phones/tablets)
  if (window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
    const frame = document.getElementById('cardFrame');

    document.addEventListener('mousemove', (e) => {
      const x = (window.innerWidth / 2 - e.clientX) / 80;
      const y = (window.innerHeight / 2 - e.clientY) / 80;
      frame.style.transform = `rotateY(${x}deg) rotateX(${-y}deg)`;
    });

    document.documentElement.addEventListener('mouseleave', () => {
      frame.style.transform = 'rotateY(0deg) rotateX(0deg)';
    });
  }
</script>
</body>
</html>