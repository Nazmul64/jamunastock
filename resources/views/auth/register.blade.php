@extends('layouts.auth.app')

@section('title', 'মেম্বার রেজিস্ট্রেশন (Member Registration) - ' . (setting('title') ?: 'JAMUNA STOCK MARKET PLC'))

@section('content')

<style>
/* Customer Register - Volt Interactive Robot Theme */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');

:root {
  --paper: #f4efe5;
  --ink: #23252d;
  --accent: #10b981;
  --accent-deep: #059669;
  --teal: #2ec4b6;
  --teal-dark: #0f766e;
  --yellow: #ffc53d;
  --shell: #fffdf8;
  --screen: #23252d;
  --eye: #fffdf8;
  --muted: #9a948a;
  --field: #faf7f0;
  --line: 2px solid var(--ink);
  --turn: .65s cubic-bezier(.5, -0.3, .25, 1.35);
}

* { box-sizing: border-box; }

body, body.hold-transition {
  font-family: 'Sora', 'Segoe UI', system-ui, sans-serif !important;
  color: var(--ink) !important;
  background-color: var(--paper) !important;
  background-image: radial-gradient(rgba(35, 37, 45, .08) 1.2px, transparent 1.3px) !important;
  background-size: 24px 24px !important;
  margin: 0 !important;
  padding: 0 !important;
  min-height: 100vh !important;
  overflow-x: hidden;
}

.scene {
  min-height: 100vh;
  min-height: 100svh;
  display: grid;
  place-items: center;
  padding: 90px 20px 60px;
  position: relative;
  width: 100%;
}

.stage {
  display: flex;
  flex-direction: column;
  align-items: center;
  position: relative;
  width: 100%;
  max-width: 580px;
}

/* Robot Character */
.robot {
  position: relative;
  z-index: 1;
  margin-bottom: -16px;
  animation: float 4s ease-in-out infinite alternate;
  perspective: 800px;
}

@keyframes float {
  from { transform: translateY(0); }
  to   { transform: translateY(-5px); }
}

/* Antenna */
.antenna {
  position: absolute;
  top: -32px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  flex-direction: column;
  align-items: center;
  z-index: 2;
}

.antenna-rod {
  width: 4px;
  height: 22px;
  background: var(--ink);
  border-radius: 2px;
}

.antenna-tip {
  width: 15px;
  height: 15px;
  border-radius: 50%;
  background: var(--accent);
  border: var(--line);
  order: -1;
  margin-bottom: -2px;
  position: relative;
  animation: bob 2.4s ease-in-out infinite;
}

.antenna-tip::after {
  content: '';
  position: absolute;
  top: 2px;
  left: 3px;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: #ffffff;
  opacity: .75;
}

@keyframes bob {
  0%, 100% { transform: scale(1); }
  50%      { transform: scale(1.18); box-shadow: 0 0 12px var(--accent); }
}

/* 3D Head */
.head3d {
  transform-style: preserve-3d;
  transition: transform .25s ease-out;
  transform: rotateY(var(--ry, 0deg)) rotateX(var(--rx, 0deg));
}

.head {
  position: relative;
  width: 108px;
  height: 80px;
  background: var(--shell);
  border: var(--line);
  border-radius: 26px 26px 20px 20px;
  box-shadow: 0 6px 0 var(--ink);
  transform-style: preserve-3d;
  transition: transform var(--turn);
}

.ear {
  position: absolute;
  top: 26px;
  width: 12px;
  height: 24px;
  background: var(--shell);
  border: var(--line);
  border-radius: 6px;
}

.ear--l { left: -14px; }
.ear--r { right: -14px; }

.face {
  position: absolute;
  inset: 0;
  border-radius: 24px 24px 18px 18px;
  backface-visibility: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.face--front { z-index: 2; }
.face--back {
  background: var(--shell);
  transform: rotateY(180deg);
  display: grid;
  place-items: center;
}

.is-turned .head { transform: rotateY(180deg); }

.visor {
  width: 82px;
  height: 52px;
  background: var(--screen);
  border-radius: 14px;
  position: relative;
  display: grid;
  place-items: center;
  overflow: hidden;
  box-shadow: inset 0 0 10px rgba(0, 0, 0, .4);
}

.eyes {
  display: flex;
  gap: 16px;
  transform: translate(var(--lx, 0px), var(--ly, 0px));
  transition: transform .12s ease-out;
}

.eye {
  width: 13px;
  height: 19px;
  background: var(--eye);
  border-radius: 7px;
  position: relative;
  animation: blink 4.2s infinite;
  box-shadow: 0 0 8px rgba(255, 255, 255, .8);
}

.cheek {
  position: absolute;
  bottom: 6px;
  width: 10px;
  height: 4px;
  background: var(--yellow);
  border-radius: 3px;
  opacity: 0.85;
}
.cheek--l { left: 9px; }
.cheek--r { right: 9px; }

@keyframes blink {
  0%, 94%, 98%, 100% { transform: scaleY(1); }
  96%                { transform: scaleY(0.08); }
}

/* Hands */
.hand {
  position: absolute;
  top: -15px;
  width: 32px;
  height: 24px;
  background: var(--shell);
  border: var(--line);
  border-radius: 14px 14px 6px 6px;
  box-shadow: 0 4px 0 var(--ink);
  z-index: 3;
}
.hand--l { left: 24px; }
.hand--r { right: 24px; }

/* Feet */
.foot {
  position: absolute;
  bottom: -15px;
  width: 44px;
  height: 20px;
  background: var(--shell);
  border: var(--line);
  border-radius: 6px 6px 14px 14px;
  box-shadow: 0 4px 0 var(--ink);
  z-index: -1;
}
.foot--l { left: 35px; }
.foot--r { right: 35px; }

/* Speech Bubble */
.bubble {
  position: absolute;
  bottom: 96px;
  left: 50%;
  transform: translateX(-50%);
  background: var(--shell);
  border: var(--line);
  border-radius: 16px;
  padding: 8px 16px;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
  box-shadow: 4px 4px 0 var(--ink);
  pointer-events: none;
  z-index: 10;
}

.bubble::after {
  content: '';
  position: absolute;
  bottom: -9px;
  left: 50%;
  transform: translateX(-50%);
  border-width: 9px 8px 0;
  border-style: solid;
  border-color: var(--ink) transparent;
}

/* Registration Card */
.card {
  position: relative;
  background: var(--shell);
  border: var(--line);
  border-radius: 28px;
  padding: 34px 30px 28px;
  width: 100%;
  box-shadow: 8px 10px 0 var(--ink);
  box-sizing: border-box;
}

.brand-badge-box {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 8px;
}

.brand-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 12px;
  border-radius: 20px;
  background: rgba(16, 185, 129, 0.12);
  border: 1px solid var(--accent);
  color: var(--accent-deep);
  font-size: 11px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.brand-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--accent);
}

.title {
  font-size: 21px;
  font-weight: 800;
  text-align: center;
  margin: 0 0 2px 0;
  color: var(--ink);
  letter-spacing: -0.5px;
}

.subtitle {
  font-size: 13px;
  font-weight: 600;
  color: var(--muted);
  text-align: center;
  margin: 0 0 16px 0;
}

.bonus-pill {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #f0fdf4;
  border: 1.5px dashed var(--accent);
  border-radius: 12px;
  padding: 8px 14px;
  font-size: 12.5px;
  font-weight: 700;
  color: var(--accent-deep);
  margin-bottom: 18px;
}

/* Input Fields */
.field-label {
  display: block;
  font-size: 12px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 5px;
}

.req-star { color: #dc2626; }

.field {
  display: flex;
  align-items: center;
  gap: 10px;
  height: 48px;
  padding: 0 14px;
  margin-bottom: 14px;
  background: var(--field);
  border: 2px solid rgba(35, 37, 45, .22);
  border-radius: 12px;
  transition: all .18s ease;
  box-sizing: border-box;
}

.field:focus-within {
  border-color: var(--ink);
  box-shadow: 3px 3px 0 var(--accent);
  transform: translate(-1px, -1px);
}

.field--error {
  border-color: #ef4444 !important;
  background: #fff5f5 !important;
}

.field-icon {
  width: 18px;
  height: 18px;
  fill: var(--muted);
  flex: none;
  transition: fill .18s ease;
}

.field:focus-within .field-icon { fill: var(--ink); }

.field input {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  background: transparent;
  font: inherit;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--ink);
}

.field input::placeholder { color: var(--muted); font-weight: 500; font-size: 13px; }

.peek {
  border: none;
  background: none;
  padding: 4px;
  cursor: pointer;
  border-radius: 8px;
  display: grid;
  place-items: center;
}
.peek svg { width: 18px; height: 18px; fill: var(--muted); }
.peek:hover svg { fill: var(--ink); }

/* Submit Button */
.btn-volt {
  position: relative;
  width: 100%;
  height: 50px;
  margin-top: 8px;
  border: var(--line);
  border-radius: 12px;
  background: var(--accent);
  color: #fff;
  font: inherit;
  font-size: 14px;
  font-weight: 800;
  letter-spacing: 0.5px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  box-shadow: 4px 5px 0 rgba(35, 37, 45, .9);
  transition: all .18s cubic-bezier(.34, 1.56, .64, 1);
}

.btn-volt:hover {
  transform: translate(-2px, -2px);
  background: var(--accent-deep);
  box-shadow: 7px 8px 0 rgba(35, 37, 45, .9);
}

.btn-volt:active {
  transform: translate(3px, 4px);
  box-shadow: 0 0 0 rgba(35, 37, 45, .9);
}

/* Footer Links */
.volt-footer {
  margin-top: 18px;
  padding-top: 14px;
  border-top: 2px dashed rgba(35, 37, 45, 0.15);
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.volt-signup-text {
  font-size: 12px;
  font-weight: 600;
  color: var(--muted);
  margin: 0;
}

.volt-link {
  color: var(--accent-deep);
  font-weight: 800;
  text-decoration: none;
}
.volt-link:hover { text-decoration: underline; }

.home-return-link {
  font-size: 12px;
  font-weight: 700;
  color: var(--ink);
  opacity: 0.6;
  text-decoration: none;
}
.home-return-link:hover { opacity: 1; text-decoration: underline; }

/* Alert Box */
.alert-box {
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 12.5px;
  font-weight: 700;
  margin-bottom: 16px;
  border: var(--line);
  box-shadow: 2px 2px 0 rgba(35, 37, 45, 0.9);
}
.alert-box--error { background: #fee2e2; color: #991b1b; border-color: #dc2626; }
.alert-box--success { background: #d1fae5; color: #065f46; border-color: #059669; }

.field-error-text {
  display: block;
  font-size: 11px;
  font-weight: 700;
  color: #dc2626;
  margin-top: -10px;
  margin-bottom: 10px;
  padding-left: 4px;
}
</style>

<div class="scene">
  <main class="stage" id="stage">

    <!-- Robot Character -->
    <div class="robot" id="robot" data-mood="idle">
      <div class="bubble" id="bubble" role="status" aria-live="polite">
        <span id="bubbleText">Welcome! Enter your 12-digit card number to register.</span>
      </div>

      <div class="antenna" aria-hidden="true">
        <span class="antenna-rod"></span>
        <span class="antenna-tip"></span>
      </div>

      <div class="head3d" aria-hidden="true">
        <div class="head" id="head">
          <span class="ear ear--l"></span>
          <span class="ear ear--r"></span>

          <!-- Front Face -->
          <div class="face face--front">
            <div class="visor">
              <div class="eyes" id="eyes">
                <span class="eye eye--l"></span>
                <span class="eye eye--r"></span>
              </div>
              <span class="cheek cheek--l"></span>
              <span class="cheek cheek--r"></span>
            </div>
          </div>

          <!-- Back Face (NOT LOOKING) -->
          <div class="face face--back">
            <span style="font-size: 11px; font-weight: 800; color: var(--muted);">NOT LOOKING</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Registration Card Form -->
    <form class="card" id="form" method="POST" action="{{ route('register.post') }}" novalidate>
      @csrf

      <span class="hand hand--l" aria-hidden="true"></span>
      <span class="hand hand--r" aria-hidden="true"></span>

      <!-- Brand Header Tag -->
      <div class="brand-badge-box">
        <span class="brand-badge">
          <span class="brand-dot"></span> Member Card Registration
        </span>
      </div>

      <h1 class="title">{{ setting('title') ?: 'JAMUNA STOCK MARKET PLC' }}</h1>
      <p class="subtitle">Create your card holder membership account</p>

      <div class="bonus-pill">
        <i class="fa fa-gift" style="font-size: 16px;"></i> Instant BDT 300 Bonus Membership Credit!
      </div>

      <!-- Errors -->
      @if($errors->any())
        <div class="alert-box alert-box--error" id="laravelErrorBox">
          <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="alert-box alert-box--success">
          {{ session('success') }}
        </div>
      @endif

      <!-- 12-Digit Card Number -->
      <div>
        <label class="field-label" for="card_number">12-Digit Admin Card Number <span class="req-star">*</span></label>
        <label class="field @error('card_number') field--error @enderror" for="card_number">
          <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
          </svg>
          <input id="card_number" name="card_number" type="text" maxlength="12" value="{{ old('card_number') }}"
                 placeholder="Enter 12-Digit Member Card Number" required autofocus>
        </label>
        @error('card_number')
          <span class="field-error-text">{{ $message }}</span>
        @enderror
      </div>

      <!-- Full Name -->
      <div>
        <label class="field-label" for="name">Full Name <span class="req-star">*</span></label>
        <label class="field @error('name') field--error @enderror" for="name">
          <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
          <input id="name" name="name" type="text" value="{{ old('name') }}"
                 placeholder="Enter your full name" required>
        </label>
        @error('name')
          <span class="field-error-text">{{ $message }}</span>
        @enderror
      </div>

      <!-- Phone Number -->
      <div>
        <label class="field-label" for="phone_number">Mobile Phone Number <span class="req-star">*</span></label>
        <label class="field @error('phone_number') field--error @enderror" for="phone_number">
          <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
          </svg>
          <input id="phone_number" name="phone_number" type="tel" value="{{ old('phone_number') }}"
                 placeholder="01XXXXXXXXX" required>
        </label>
        @error('phone_number')
          <span class="field-error-text">{{ $message }}</span>
        @enderror
      </div>

      <!-- Passwords in 2 columns -->
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
        <!-- Password -->
        <div>
          <label class="field-label" for="password">Password <span class="req-star">*</span></label>
          <label class="field @error('password') field--error @enderror" for="password">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <input id="password" name="password" type="password" placeholder="Min 6 chars" required>
            <button class="peek" id="togglePass" type="button" aria-label="Show password">
              <svg viewBox="0 0 24 24"><path d="M12 5c-5 0-9.3 3.1-11 7.5C2.7 16.9 7 20 12 20s9.3-3.1 11-7.5C21.3 8.1 17 5 12 5Zm0 12.5a5 5 0 1 1 5-5 5 5 0 0 1-5 5Zm0-8a3 3 0 1 0 3 3 3 3 0 0 0-3-3Z"/></svg>
            </button>
          </label>
          @error('password')
            <span class="field-error-text">{{ $message }}</span>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div>
          <label class="field-label" for="password_confirmation">Confirm Password <span class="req-star">*</span></label>
          <label class="field" for="password_confirmation">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
            </svg>
            <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Retype pass" required>
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <button class="btn-volt" id="submitBtn" type="submit">
        <span class="btn-bolt" aria-hidden="true">⚡</span>
        <span class="btn-label" id="btnLabel">REGISTER CARD ACCOUNT</span>
      </button>

      <!-- Feet -->
      <span class="foot foot--l" aria-hidden="true"></span>
      <span class="foot foot--r" aria-hidden="true"></span>

      <!-- Footer & Portal Links -->
      <div class="volt-footer">
        <p class="volt-signup-text">
          Already have an account? <a href="{{ route('login') }}" class="volt-link">Sign In Here</a>
        </p>
        <p class="volt-signup-text">
          Are you a supplier? <a href="{{ route('supplier.register') }}" class="volt-link">Supplier Registration</a>
        </p>
        <div>
          <a href="{{ url('/') }}" class="home-return-link">
            ← Back to Home
          </a>
        </div>
      </div>
    </form>
  </main>
</div>

<script>
// Customer Register - Interactive Robot Experience
document.addEventListener('DOMContentLoaded', function () {
  const $ = (s) => document.querySelector(s);

  const robot = $('#robot');
  const eyes = $('#eyes');
  const bubble = $('#bubble');
  const bubbleText = $('#bubbleText');
  const form = $('#form');
  const cardI = $('#card_number');
  const nameI = $('#name');
  const phoneI = $('#phone_number');
  const passI = $('#password');
  const passConfI = $('#password_confirmation');
  const peekBtn = $('#togglePass');
  const head3d = $('.head3d');

  function say(text) {
    bubbleText.textContent = text;
    bubble.classList.remove('pop');
    void bubble.offsetWidth;
    bubble.classList.add('pop');
  }

  function look(x, y) {
    if (eyes) {
      eyes.style.setProperty('--lx', `${x}px`);
      eyes.style.setProperty('--ly', `${y}px`);
    }
  }

  function tilt(ry, rx) {
    if (head3d) {
      head3d.style.setProperty('--ry', `${ry}deg`);
      head3d.style.setProperty('--rx', `${rx}deg`);
    }
  }

  function turnAway(on) {
    if (robot) robot.classList.toggle('is-turned', on);
  }

  if (cardI) {
    cardI.addEventListener('focus', () => {
      turnAway(false);
      tilt(0, -6);
      look(0, 5);
      say("Type your 12-digit Admin Member Card Number!");
    });
    cardI.addEventListener('input', () => {
      if (cardI.value.length === 12) {
        say("Nice! 12 digits card verified.");
      }
    });
  }

  if (nameI) {
    nameI.addEventListener('focus', () => {
      turnAway(false);
      tilt(0, -6);
      say("What's your full name, member?");
    });
  }

  if (phoneI) {
    phoneI.addEventListener('focus', () => {
      turnAway(false);
      tilt(0, -6);
      say("We'll send notifications to this mobile number!");
    });
  }

  if (passI) {
    passI.addEventListener('focus', () => {
      turnAway(true);
      say("A secret password? I'm looking away!");
    });
    passI.addEventListener('blur', () => {
      if (document.activeElement !== passConfI) {
        turnAway(false);
      }
    });
  }

  if (passConfI) {
    passConfI.addEventListener('focus', () => {
      turnAway(true);
      say("Confirming password... Still not looking!");
    });
    passConfI.addEventListener('blur', () => {
      turnAway(false);
    });
  }

  if (peekBtn) {
    peekBtn.addEventListener('click', () => {
      const isPass = passI.type === 'password';
      passI.type = isPass ? 'text' : 'password';
      say(isPass ? "Revealing password! Shhh!" : "Hidden again!");
    });
  }
});
</script>
@endsection
