<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hidayah - Create Account</title>
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital@0;1&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<style>
  * { margin:0; padding:0; box-sizing:border-box; }

  body{
    min-height:100vh;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
    background: radial-gradient(circle at 20% 10%, #0d3d33 0%, #04140f 55%, #010805 100%);
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    position:relative;
    padding:40px 0;
  }

  .mandala{
    position:fixed;
    width:420px;height:420px;
    background: radial-gradient(circle, rgba(212,175,110,0.35) 0%, transparent 70%);
    opacity:0;
  }
  .mandala svg{ width:100%; height:100%; }
  .m-tr{ top:-140px; right:-140px; }
  .m-bl{ bottom:-160px; left:-160px; }
  .mandala{ animation-name: mandalaIn, mandalaSpin; animation-duration:1.6s,60s; animation-timing-function:ease-out,linear; animation-iteration-count:1,infinite; animation-fill-mode:forwards; }
  .m-tr{ animation-delay:.2s,0s;}
  .m-bl{ animation-delay:.5s,0s;}
  @keyframes mandalaIn{ from{ opacity:0; transform:scale(.6) rotate(-25deg);} to{ opacity:1; transform:scale(1) rotate(0deg);} }
  @keyframes mandalaSpin{ from{transform:rotate(0deg);} to{transform:rotate(360deg);} }

  .corner-line{ position:fixed; opacity:0; animation: fadeInLine 1s ease-out forwards; }
  .cl-tl{ top:40px; left:40px; animation-delay:.6s; }
  .cl-br{ bottom:40px; right:40px; animation-delay:.8s; transform:rotate(180deg);}
  @keyframes fadeInLine{ from{opacity:0; transform:translateY(-10px);} to{opacity:.85; transform:translateY(0);} }

  .frame-wrap{
    position:relative; z-index:10;
    opacity:0;
    animation: frameIn 1s cubic-bezier(.2,.8,.2,1) .3s forwards;
  }
  @keyframes frameIn{ from{ opacity:0; transform:scale(.85) translateY(40px);} to{ opacity:1; transform:scale(1) translateY(0);} }

  .frame{
    position:relative;
    border:1.5px solid rgba(212,175,110,0.55);
    border-radius:18px;
    padding:48px 46px;
    background: linear-gradient(160deg, rgba(10,50,42,0.55), rgba(2,15,12,0.75));
    box-shadow: 0 0 0 6px rgba(4,20,16,0.4), 0 25px 80px rgba(0,0,0,0.6), inset 0 0 40px rgba(212,175,110,0.06);
    backdrop-filter: blur(6px);
    width: 460px;
    max-width:92vw;
  }
  .frame::before{
    content:''; position:absolute; inset:10px;
    border:1px solid rgba(212,175,110,0.25); border-radius:12px; pointer-events:none;
  }
  /* Rotating light traveling around the frame border */
  .glow-border{
    position:absolute; inset:-2px; border-radius:20px; padding:2px;
    background: conic-gradient(from 0deg, transparent 0%, transparent 78%, rgba(255,241,209,0.95) 86%, #d4af6e 90%, transparent 94%, transparent 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude;
    animation: rotateBorder 5s linear infinite;
    pointer-events:none;
  }
  @keyframes rotateBorder{ to{ transform:rotate(360deg); } }

  /* Soft breathing glow behind the whole frame */
  .frame-halo{
    position:absolute; inset:-40px; border-radius:30px;
    background: radial-gradient(circle, rgba(212,175,110,0.18) 0%, transparent 70%);
    z-index:-1;
    animation: haloPulse 3.5s ease-in-out infinite;
    pointer-events:none;
  }
  @keyframes haloPulse{ 0%,100%{ opacity:.5; transform:scale(1);} 50%{ opacity:1; transform:scale(1.08);} }

  /* Sparkle dot orbiting the corners */
  .frame-sparkle{
    position:absolute; width:6px; height:6px; border-radius:50%;
    background:#f2e6cf; box-shadow:0 0 8px 2px rgba(212,175,110,0.9);
    animation: sparkleMove 5s linear infinite;
  }
  @keyframes sparkleMove{
    0%{ top:0; left:0; }
    25%{ top:0; left:100%; }
    50%{ top:100%; left:100%; }
    75%{ top:100%; left:0; }
    100%{ top:0; left:0; }
  }

  .ornament-top{ text-align:center; color:#d4af6e; font-size:34px; margin-bottom:6px; animation: floatY 3.5s ease-in-out infinite, fadeIn .8s ease-out .5s backwards; }
  @keyframes floatY{ 0%,100%{transform:translateY(0);} 50%{transform:translateY(-6px);} }
  @keyframes fadeIn{ from{opacity:0;} to{opacity:1;} }

  h1.brand{ text-align:center; color:#f2e6cf; font-size:30px; font-weight:700; letter-spacing:1px; margin-bottom:4px; animation: fadeInDown .7s ease-out .5s backwards; }
  @keyframes fadeInDown{ from{opacity:0; transform:translateY(-14px);} to{opacity:1; transform:translateY(0);} }

  p.subtitle{ text-align:center; color:#9fb8ac; font-size:14px; margin-bottom:26px; animation: fadeIn .8s ease-out .65s backwards; }

  .alert-box{
    padding:12px 16px; border-radius:8px; font-size:13px; margin-bottom:18px;
    animation: fadeInUp .5s ease-out;
  }
  .alert-error{ background:rgba(239,68,68,0.12); border:1px solid #ef4444; color:#fecaca; }

  .form-group{ margin-bottom:18px; opacity:0; animation: fadeInUp .55s ease-out forwards; }
  .form-group:nth-of-type(1){animation-delay:.68s;}
  .form-group:nth-of-type(2){animation-delay:.76s;}
  .form-group:nth-of-type(3){animation-delay:.84s;}
  .form-group:nth-of-type(4){animation-delay:.92s;}
  @keyframes fadeInUp{ from{opacity:0; transform:translateY(16px);} to{opacity:1; transform:translateY(0);} }

  label{ display:block; color:#cdd9d2; font-size:13px; margin-bottom:8px; letter-spacing:.3px; }

  .input-wrap{ position:relative; }
  .input-wrap span{ position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#6e8b7f; font-size:15px; }
  input[type=email], input[type=password], input[type=text]{
    width:100%; padding:12px 14px 12px 40px;
    background:rgba(3,20,16,0.6);
    border:1px solid rgba(212,175,110,0.25);
    border-radius:8px; color:#f2e6cf; font-size:14px; outline:none;
    transition:all .3s ease;
  }
  input::placeholder{ color:#5c766c; }
  input:focus{
    border-color:#d4af6e;
    box-shadow:0 0 0 3px rgba(212,175,110,0.15), 0 0 20px rgba(212,175,110,0.25);
    background:rgba(3,20,16,0.85);
  }

  .error-text{ color:#f87171; font-size:12px; margin-top:6px; display:block; animation: shake .4s ease-in-out; }
  @keyframes shake{ 0%,100%{transform:translateX(0);} 25%{transform:translateX(-4px);} 75%{transform:translateX(4px);} }

  .pwd-strength{ height:4px; border-radius:2px; margin-top:8px; background:rgba(255,255,255,0.08); overflow:hidden; }
  .pwd-strength-bar{ height:100%; width:0%; transition:all .3s ease; border-radius:2px; }

  .terms-row{
    display:flex; align-items:flex-start; gap:10px;
    font-size:12.5px; color:#9fb8ac; margin-bottom:24px;
    opacity:0; animation:fadeInUp .55s ease-out 1s forwards;
  }
  .terms-row a{ color:#d4af6e; text-decoration:none; }
  .terms-row input{ margin-top:2px; }

  button.btn-gold{
    width:100%; padding:14px;
    background:linear-gradient(135deg,#d4af6e,#a97e3e);
    border:none; border-radius:8px; color:#0a1f19;
    font-weight:700; font-size:15px; letter-spacing:.5px; cursor:pointer;
    position:relative; overflow:hidden;
    opacity:0; animation:fadeInUp .55s ease-out 1.08s forwards;
    transition:transform .25s ease, box-shadow .25s ease;
  }
  button.btn-gold::before{
    content:''; position:absolute; top:0; left:-100%; width:100%; height:100%;
    background:rgba(255,255,255,0.35); transition:left .5s ease;
  }
  button.btn-gold:hover::before{ left:100%; }
  button.btn-gold:hover{ transform:translateY(-3px); box-shadow:0 12px 28px rgba(212,175,110,0.35); }

  .footer-link{ text-align:center; margin-top:26px; color:#9fb8ac; font-size:13px; opacity:0; animation:fadeIn .6s ease-out 1.2s forwards; }
  .footer-link a{ color:#d4af6e; font-weight:600; text-decoration:none; }
  .footer-link a:hover{ text-decoration:underline; }

  .translation-credit{
    text-align:center; margin-top:20px; padding-top:14px;
    border-top:1px solid rgba(212,175,110,0.18);
    opacity:0; animation:fadeIn .8s ease-out 1.3s forwards;
  }
  .translation-credit .t-title{ font-family:'Great Vibes', cursive; font-size:24px; color:#e9c98f; line-height:1.2; }
  .translation-credit .t-sub{ font-family:'Playfair Display', serif; font-style:italic; font-size:10.5px; color:#7f9a8e; letter-spacing:.4px; margin-top:2px; }
  .translation-credit .t-author{ font-family:'Playfair Display', serif; font-size:11px; color:#d4af6e; margin-top:6px; letter-spacing:.5px; }

  .eye{ position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#6e8b7f; }
  .eye:hover{ color:#d4af6e; }
</style>
</head>
<body>

  <div class="mandala m-tr">
    <svg viewBox="0 0 200 200"><circle cx="100" cy="100" r="90" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.4"/><circle cx="100" cy="100" r="70" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.3"/><circle cx="100" cy="100" r="50" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.25"/></svg>
  </div>
  <div class="mandala m-bl">
    <svg viewBox="0 0 200 200"><circle cx="100" cy="100" r="90" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.4"/><circle cx="100" cy="100" r="70" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.3"/><circle cx="100" cy="100" r="50" fill="none" stroke="#d4af6e" stroke-width="1" opacity="0.25"/></svg>
  </div>

  <svg class="corner-line cl-tl" width="120" height="120"><path d="M10 100 L10 10 L100 10" stroke="#d4af6e" stroke-width="1.5" fill="none"/><circle cx="10" cy="105" r="3" fill="#d4af6e"/></svg>
  <svg class="corner-line cl-br" width="120" height="120"><path d="M10 100 L10 10 L100 10" stroke="#d4af6e" stroke-width="1.5" fill="none"/><circle cx="10" cy="105" r="3" fill="#d4af6e"/></svg>

  <div class="frame-wrap">
    <div class="frame">
      <div class="frame-halo"></div>
      <div class="glow-border"></div>
      <div class="frame-sparkle"></div>

      <div class="ornament-top">✦</div>
      <h1 class="brand">Towards Qur'anic Meditation</h1>
      <p class="subtitle">Begin your journey through the Quran</p>

      @if ($errors->any())
        <div class="alert-box alert-error">
          @foreach ($errors->all() as $error)
            <div>• {{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form action="{{ route('users.store') }}" method="POST" id="registerForm">
        @csrf

        <div class="form-group">
          <label>Full Name</label>
          <div class="input-wrap">
            <span>👤</span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
          </div>
        </div>

        <div class="form-group">
          <label>Email address</label>
          <div class="input-wrap">
            <span>✉</span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
          </div>
        </div>

        <div class="form-group">
          <label>Password</label>
          <div class="input-wrap">
            <span>🔒</span>
            <input type="password" name="password" id="password" placeholder="At least 8 characters" oninput="checkStrength()" required>
            <span class="eye" onclick="togglePwd('password')">👁</span>
          </div>
          <div class="pwd-strength"><div class="pwd-strength-bar" id="strengthBar"></div></div>
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <div class="input-wrap">
            <span>✓</span>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password" required>
            <span class="eye" onclick="togglePwd('password_confirmation')">👁</span>
          </div>
        </div>

        <label class="terms-row">
          <input type="checkbox" name="terms" required>
          <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
        </label>

        <button type="submit" class="btn-gold">Create Account</button>
      </form>

      <div class="footer-link">
        Already have an account? <a href="{{ route('login') }}">Sign in</a>
      </div>

      <div class="translation-credit">
        <div class="t-title">The Glorious Qur'ān</div>
        <div class="t-sub">A Multi-Layered Exegetic, Meditative Translation</div>
        <div class="t-author">Basil Q. Muhammad</div>
      </div>
    </div>
  </div>

<script>
function togglePwd(id){
  const f = document.getElementById(id);
  f.type = f.type === 'password' ? 'text' : 'password';
}

function checkStrength(){
  const val = document.getElementById('password').value;
  const bar = document.getElementById('strengthBar');
  let score = 0;
  if(val.length >= 8) score++;
  if(val.length >= 12) score++;
  if(/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
  if(/[0-9]/.test(val)) score++;
  if(/[^a-zA-Z0-9]/.test(val)) score++;

  const colors = ['#ef4444','#ef4444','#f59e0b','#3b82f6','#10b981','#10b981'];
  const widths = [10,25,50,75,100,100];
  bar.style.width = widths[score] + '%';
  bar.style.background = colors[score];
}

document.getElementById('registerForm').addEventListener('submit', function(e){
  const p = document.getElementById('password').value;
  const c = document.getElementById('password_confirmation').value;
  if(p !== c){
    e.preventDefault();
    alert('Passwords do not match.');
  }
});
</script>
</body>
</html>