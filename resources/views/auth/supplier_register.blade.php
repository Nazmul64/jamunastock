@extends('layouts.auth.app')

@section('title', (setting('title') ?: 'JAMUNA STOCK MARKET PLC') . ' - সাপ্লায়ার রেজিস্ট্রেশন (Supplier Registration)')

@section('content')

<style>
/* Supplier Register - Volt Interactive Robot Theme */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&display=swap');

:root {
  --paper: #f4efe5;
  --ink: #23252d;
  --accent: #ff6b4b;
  --accent-deep: #e5532f;
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
  max-width: 680px;
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
  background: var(--teal);
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
  50%      { transform: scale(1.18); box-shadow: 0 0 12px var(--teal); }
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

.robot.is-turned .head {
  transform: rotateY(180deg);
}

.robot.is-spinning .head {
  animation: spin-around .9s cubic-bezier(.4, 0, .2, 1);
}

@keyframes spin-around {
  0%   { transform: rotateY(0deg); }
  100% { transform: rotateY(360deg); }
}

/* Ears */
.ear {
  position: absolute;
  top: 26px;
  width: 9px;
  height: 24px;
  background: var(--yellow);
  border: var(--line);
  border-radius: 4px;
}

.ear--l { left: -9px; }
.ear--r { right: -9px; }

/* Faces */
.face {
  position: absolute;
  inset: 0;
  backface-visibility: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: inherit;
}

.face--front { z-index: 2; }

.face--back {
  transform: rotateY(180deg);
  background: var(--screen);
}

/* Visor & Eyes */
.visor {
  position: relative;
  width: 82px;
  height: 54px;
  background: var(--screen);
  border-radius: 17px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  overflow: hidden;
}

.eyes {
  display: flex;
  gap: 16px;
  transform: translate(var(--lx, 0px), var(--ly, 0px));
  transition: transform .08s ease-out;
}

.eye {
  position: relative;
  width: 15px;
  height: 22px;
  background: var(--eye);
  border-radius: 8px;
  transition: height .15s ease, transform .15s ease, border-radius .15s ease;
}

.eye::after {
  content: '';
  position: absolute;
  top: 3px;
  right: 3px;
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background: var(--screen);
  opacity: .35;
}

.eyes.blink .eye {
  height: 2px !important;
  margin: 10px 0;
}

/* Mood States */
.robot[data-mood="happy"] .eye,
.robot[data-mood="excited"] .eye,
.robot[data-mood="success"] .eye,
.robot[data-mood="pressed"] .eye {
  height: 13px;
  border-radius: 8px 8px 0 0;
  background: transparent;
  border: 3.5px solid var(--eye);
  border-bottom: none;
  transform: translateY(2px);
}

.robot[data-mood="happy"] .eye::after,
.robot[data-mood="excited"] .eye::after,
.robot[data-mood="success"] .eye::after,
.robot[data-mood="pressed"] .eye::after {
  display: none;
}

.cheek {
  position: absolute;
  bottom: 8px;
  width: 9px;
  height: 4px;
  border-radius: 50%;
  background: var(--accent);
  opacity: 0;
  transition: opacity .3s;
}

.cheek--l { left: 9px; }
.cheek--r { right: 9px; }

.robot[data-mood="happy"] .cheek,
.robot[data-mood="excited"] .cheek,
.robot[data-mood="success"] .cheek,
.robot[data-mood="shy"] .cheek,
.robot[data-mood="pressed"] .cheek {
  opacity: .85;
}

.mouth {
  position: absolute;
  bottom: 8px;
  width: 10px;
  height: 4px;
  border-radius: 0 0 6px 6px;
  background: var(--eye);
  opacity: 0;
  transition: opacity .2s, transform .2s;
}

.robot[data-mood="happy"] .mouth,
.robot[data-mood="excited"] .mouth,
.robot[data-mood="success"] .mouth {
  opacity: 1;
  transform: scaleY(1.3);
}

/* Back Face (Not Looking Panel) */
.panel {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 5px;
  padding: 8px;
  width: 100%;
}

.panel-lights { display: flex; gap: 5px; }

.panel-lights i {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #ff4757;
  animation: blinky 1s infinite alternate;
}

.panel-lights i:nth-child(2) { background: #ffa502; animation-delay: .2s; }
.panel-lights i:nth-child(3) { background: #2ed573; animation-delay: .4s; }

@keyframes blinky {
  from { opacity: .25; }
  to   { opacity: 1; }
}

.meter {
  display: flex;
  gap: 3px;
  padding: 3px 6px;
  background: rgba(255, 255, 255, .1);
  border-radius: 4px;
}

.meter i {
  width: 10px;
  height: 4px;
  background: rgba(255, 255, 255, .25);
  border-radius: 1px;
  transition: background .2s;
}

.meter i.on { background: var(--teal); }

.panel-label {
  font-family: monospace;
  font-size: 8px;
  font-weight: 700;
  letter-spacing: .5px;
  color: #fff;
  margin: 0;
  opacity: .8;
}

/* Speech Bubble */
.bubble {
  position: absolute;
  bottom: calc(100% + 22px);
  left: 50%;
  transform: translateX(-50%);
  background: var(--shell);
  border: var(--line);
  border-radius: 16px;
  padding: 7px 15px;
  font-size: 13px;
  font-weight: 700;
  white-space: nowrap;
  box-shadow: 0 4px 0 var(--ink);
  pointer-events: none;
  z-index: 10;
  max-width: 320px;
  text-align: center;
}

.bubble::after {
  content: '';
  position: absolute;
  bottom: -7px;
  left: 50%;
  transform: translateX(-50%);
  width: 0;
  height: 0;
  border-left: 6px solid transparent;
  border-right: 6px solid transparent;
  border-top: 7px solid var(--ink);
}

.bubble.pop { animation: pop .22s cubic-bezier(.3, 1.4, .65, 1); }

@keyframes pop {
  0%   { transform: translateX(-50%) scale(.85); }
  100% { transform: translateX(-50%) scale(1); }
}

/* Card Container */
.card {
  position: relative;
  width: 100%;
  background: var(--shell);
  border: var(--line);
  border-radius: 28px;
  padding: 36px 36px 34px;
  box-shadow: 8px 8px 0 var(--ink);
  z-index: 2;
  box-sizing: border-box;
}

/* Robot Hands */
.hand {
  position: absolute;
  top: -14px;
  width: 32px;
  height: 22px;
  background: var(--shell);
  border: var(--line);
  border-radius: 12px 12px 6px 6px;
  box-shadow: 0 3px 0 var(--ink);
  z-index: 3;
}

.hand::after {
  content: '';
  position: absolute;
  bottom: 3px;
  left: 50%;
  transform: translateX(-50%);
  width: 14px;
  height: 2px;
  background: var(--ink);
  border-radius: 1px;
}

.hand--l { left: 60px; }
.hand--r { right: 60px; }

/* Robot Feet */
.foot {
  position: absolute;
  bottom: -18px;
  width: 62px;
  height: 34px;
  background: var(--shell);
  border: var(--line);
  border-radius: 10px 10px 14px 14px;
  box-shadow: inset 0 -9px 0 rgba(35, 37, 45, .12);
  z-index: -1;
}

.foot--l { left: 90px; }
.foot--r { right: 90px; }

/* Brand Badge */
.brand-badge-box {
  text-align: center;
  margin-bottom: 12px;
}

.brand-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: rgba(46, 196, 182, 0.12);
  border: 1.5px solid var(--teal);
  padding: 5px 15px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
  color: var(--teal-dark);
  letter-spacing: 0.2px;
}

.brand-dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: var(--teal);
  box-shadow: 0 0 7px var(--teal);
}

.title {
  font-size: 22px;
  font-weight: 800;
  letter-spacing: -.3px;
  text-align: center;
  margin-bottom: 4px;
  color: var(--ink);
}

.subtitle {
  font-size: 13.5px;
  font-weight: 600;
  color: var(--muted);
  text-align: center;
  margin: 0 0 22px 0;
}

/* Alert Boxes */
.alert-box {
  padding: 12px 16px;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 700;
  margin-bottom: 20px;
  text-align: left;
  border: var(--line);
  box-shadow: 3px 3px 0 rgba(35, 37, 45, 0.9);
}

.alert-box--error {
  background: #fee2e2;
  color: #991b1b;
  border-color: #dc2626;
}

.alert-box--error ul {
  margin: 0;
  padding-left: 18px;
}

.alert-box--error li {
  margin-bottom: 3px;
}

.alert-box--success {
  background: #d1fae5;
  color: #065f46;
  border-color: #059669;
}

/* Form Layout & Fields */
.form-row {
  display: flex;
  flex-wrap: wrap;
  margin-left: -8px;
  margin-right: -8px;
}

.form-col-6 {
  flex: 0 0 50%;
  max-width: 50%;
  padding-left: 8px;
  padding-right: 8px;
  margin-bottom: 14px;
  box-sizing: border-box;
}

.form-col-12 {
  flex: 0 0 100%;
  max-width: 100%;
  padding-left: 8px;
  padding-right: 8px;
  margin-bottom: 14px;
  box-sizing: border-box;
}

.field-label {
  display: block;
  font-size: 12.5px;
  font-weight: 700;
  color: var(--ink);
  margin-bottom: 6px;
}

.req-star {
  color: var(--accent-deep);
  font-weight: 800;
}

.field {
  display: flex;
  align-items: center;
  gap: 10px;
  height: 48px;
  padding: 0 14px;
  background: var(--field);
  border: 2px solid rgba(35, 37, 45, .22);
  border-radius: 12px;
  transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
  cursor: text;
  box-sizing: border-box;
  width: 100%;
}

.field-textarea-box {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 14px;
  background: var(--field);
  border: 2px solid rgba(35, 37, 45, .22);
  border-radius: 12px;
  transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
  box-sizing: border-box;
  width: 100%;
}

.field:focus-within, .field-textarea-box:focus-within {
  border-color: var(--ink);
  box-shadow: 3px 3px 0 var(--teal);
  transform: translate(-1px, -1px);
}

.field--error, .field-textarea-box--error {
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

.field:focus-within .field-icon, .field-textarea-box:focus-within .field-icon {
  fill: var(--ink);
}

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

.field-textarea-box textarea {
  flex: 1;
  min-width: 0;
  border: none;
  outline: none;
  background: transparent;
  font: inherit;
  font-size: 13.5px;
  font-weight: 600;
  color: var(--ink);
  resize: vertical;
}

.field-error-text {
  display: block;
  font-size: 11.5px;
  font-weight: 700;
  color: #dc2626;
  margin-top: 4px;
}

/* Peek Button */
.peek {
  background: none;
  border: none;
  padding: 0;
  width: 24px;
  height: 24px;
  display: grid;
  place-items: center;
  cursor: pointer;
  color: var(--muted);
  border-radius: 6px;
  transition: color .18s, transform .18s;
  flex: none;
}

.peek:hover {
  color: var(--ink);
  transform: scale(1.1);
}

.peek svg {
  width: 18px;
  height: 18px;
  fill: currentColor;
}

/* Submit Button */
.btn-volt {
  position: relative;
  width: 100%;
  height: 54px;
  margin-top: 10px;
  background: var(--accent);
  color: #fff;
  border: var(--line);
  border-radius: 14px;
  font: inherit;
  font-size: 14.5px;
  font-weight: 800;
  letter-spacing: .4px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  cursor: pointer;
  box-shadow: 0 5px 0 var(--ink);
  transition: transform .12s cubic-bezier(.2, .9, .3, 1.2), box-shadow .12s ease, background-color .18s;
  user-select: none;
  overflow: hidden;
}

.btn-volt:hover {
  background: var(--accent-deep);
  transform: translateY(-2px);
  box-shadow: 0 7px 0 var(--ink);
}

.btn-volt:active {
  transform: translateY(4px);
  box-shadow: 0 1px 0 var(--ink);
}

.btn-bolt {
  font-size: 18px;
  line-height: 1;
  display: inline-block;
  opacity: .4;
  transform: scale(.8);
  transition: opacity .18s, transform .22s cubic-bezier(.3, 1.6, .5, 1);
}

.btn-volt:hover .btn-bolt, .btn-volt:focus-visible .btn-bolt {
  opacity: 1;
  transform: none;
  animation: bolt-jitter .4s ease-in-out infinite;
}

@keyframes bolt-jitter {
  0%, 100% { transform: rotate(-7deg) scale(1); }
  50%      { transform: rotate(7deg) scale(1.2); }
}

.btn-volt.is-success { background: var(--teal); }

/* Card Shake */
.card.shake { animation: shake .4s ease; }
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-7px); }
  50% { transform: translateX(6px); }
  75% { transform: translateX(-3px); }
}

/* Confetti */
.confetti {
  position: absolute;
  width: 10px;
  height: 10px;
  top: 0;
  left: 0;
  border-radius: 2px;
  pointer-events: none;
  z-index: 100;
}

/* Card Footer Links */
.volt-footer {
  margin-top: 22px;
  padding-top: 18px;
  border-top: 2px dashed rgba(35, 37, 45, 0.15);
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.volt-signup-text {
  font-size: 13px;
  font-weight: 600;
  color: var(--muted);
  margin: 0;
}

.volt-link {
  color: var(--teal-dark);
  font-weight: 800;
  text-decoration: none;
}

.volt-link:hover { text-decoration: underline; }

.home-return-link {
  font-size: 12px;
  font-weight: 700;
  color: var(--ink);
  opacity: 0.65;
  text-decoration: none;
  transition: opacity .18s;
  display: inline-block;
}

.home-return-link:hover {
  opacity: 1;
  text-decoration: underline;
}

/* Responsive */
@media (max-width: 640px) {
  .scene { padding-top: 75px; padding-left: 14px; padding-right: 14px; }
  .stage { max-width: 100%; }
  .card { padding: 30px 18px 28px; }
  .form-col-6 { flex: 0 0 100%; max-width: 100%; }
  .foot--l { left: 45px; }
  .foot--r { right: 45px; }
  .hand--l { left: 35px; }
  .hand--r { right: 35px; }
  .bubble { max-width: 240px; font-size: 12px; }
}

@media (prefers-reduced-motion: reduce) {
  .robot { animation: none; }
  .antenna-tip, .panel-lights i { animation: none; }
  .head { transition-duration: .01s; }
  .btn-volt:hover .btn-bolt { animation: none; }
  .is-spinning .head { animation: none; }
  .bubble.pop { animation: none; }
  .eyes { transition: none; }
}
</style>

<div class="scene">
  <main class="stage" id="stage">

    <!-- Robot Character -->
    <div class="robot" id="robot" data-mood="idle">

      <div class="bubble" id="bubble" role="status" aria-live="polite">
        <span id="bubbleText">Welcome partner! Let's get your business on board.</span>
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
              <span class="mouth"></span>
            </div>
          </div>

          <!-- Back Face (NOT LOOKING Panel) -->
          <div class="face face--back">
            <div class="panel">
              <span class="panel-lights"><i></i><i></i><i></i></span>
              <div class="meter" id="meter">
                <i></i><i></i><i></i><i></i>
              </div>
              <p class="panel-label" id="panelLabel">NOT LOOKING</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Registration Card Form -->
    <form class="card" id="form" method="POST" action="{{ route('supplier.register.post') }}" novalidate>
      @csrf

      <span class="hand hand--l" aria-hidden="true"></span>
      <span class="hand hand--r" aria-hidden="true"></span>

      <!-- Brand Header Tag -->
      <div class="brand-badge-box">
        <span class="brand-badge">
          <span class="brand-dot"></span> Supplier Partner Registration
        </span>
      </div>

      <h1 class="title" style="margin-bottom: 3px;">{{ setting('title') ?: 'JAMUNA STOCK MARKET PLC' }}</h1>
      <p class="subtitle" style="margin: 0 0 20px 0;">Supplier Account Registration</p>

      <!-- Validation Errors Display -->
      @if($errors->any())
        <div class="alert-box alert-box--error" id="laravelErrorBox">
          <ul class="mb-0">
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

      <div class="form-row">
        <!-- Company / Business Name -->
        <div class="form-col-6">
          <label class="field-label" for="company_name">Company / Business Name <span class="req-star">*</span></label>
          <label class="field @error('company_name') field--error @enderror" for="company_name">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M20 6h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-6 0h-4V4h4v2z"/>
            </svg>
            <input id="company_name" name="company_name" type="text" value="{{ old('company_name') }}"
                   placeholder="e.g. Satata Agro Traders" required autofocus>
          </label>
          @error('company_name')
            <span class="field-error-text">{{ $message }}</span>
          @enderror
        </div>

        <!-- Contact Person Name -->
        <div class="form-col-6">
          <label class="field-label" for="name">Contact Person Name <span class="req-star">*</span></label>
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

        <!-- Mobile Number -->
        <div class="form-col-6">
          <label class="field-label" for="phone">Mobile Number <span class="req-star">*</span></label>
          <label class="field @error('phone') field--error @enderror" for="phone">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
            </svg>
            <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                   placeholder="017XXXXXXXX" required>
          </label>
          @error('phone')
            <span class="field-error-text">{{ $message }}</span>
          @enderror
        </div>

        <!-- Email Address -->
        <div class="form-col-6">
          <label class="field-label" for="email">Email Address (Optional)</label>
          <label class="field @error('email') field--error @enderror" for="email">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   placeholder="company@domain.com">
          </label>
          @error('email')
            <span class="field-error-text">{{ $message }}</span>
          @enderror
        </div>

        <!-- District / Thana -->
        <div class="form-col-12">
          <label class="field-label" for="district_thana">District / Thana</label>
          <label class="field" for="district_thana">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
            </svg>
            <input id="district_thana" name="district_thana" type="text" value="{{ old('district_thana') }}"
                   placeholder="e.g. Dhaka, Dhanmondi / Naogaon, Sadar">
          </label>
        </div>

        <!-- Full Address -->
        <div class="form-col-12">
          <label class="field-label" for="address">Full Business Address</label>
          <div class="field-textarea-box">
            <svg class="field-icon" style="margin-top: 3px;" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
            </svg>
            <textarea id="address" name="address" rows="2" placeholder="Enter detailed office or warehouse address">{{ old('address') }}</textarea>
          </div>
        </div>

        <!-- Password -->
        <div class="form-col-6">
          <label class="field-label" for="password">Password <span class="req-star">*</span></label>
          <label class="field @error('password') field--error @enderror" for="password">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            <input id="password" name="password" type="password"
                   placeholder="Minimum 6 characters" required>
            <button class="peek" id="togglePass" type="button" aria-label="Show password" aria-pressed="false">
              <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 5c-5 0-9.3 3.1-11 7.5C2.7 16.9 7 20 12 20s9.3-3.1 11-7.5C21.3 8.1 17 5 12 5Zm0 12.5a5 5 0 1 1 5-5 5 5 0 0 1-5 5Zm0-8a3 3 0 1 0 3 3 3 3 0 0 0-3-3Z"/>
              </svg>
            </button>
          </label>
          @error('password')
            <span class="field-error-text">{{ $message }}</span>
          @enderror
        </div>

        <!-- Confirm Password -->
        <div class="form-col-6">
          <label class="field-label" for="password_confirmation">Confirm Password <span class="req-star">*</span></label>
          <label class="field" for="password_confirmation">
            <svg class="field-icon" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
            </svg>
            <input id="password_confirmation" name="password_confirmation" type="password"
                   placeholder="Retype password" required>
          </label>
        </div>
      </div>

      <!-- Submit Button -->
      <button class="btn-volt" id="submitBtn" type="submit">
        <span class="btn-bolt" aria-hidden="true">⚡</span>
        <span class="btn-label" id="btnLabel">REGISTER SUPPLIER ACCOUNT</span>
      </button>

      <!-- Feet -->
      <span class="foot foot--l" aria-hidden="true"></span>
      <span class="foot foot--r" aria-hidden="true"></span>

      <!-- Footer & Portal Links -->
      <div class="volt-footer">
        <p class="volt-signup-text">
          Already have an account? <a href="{{ route('supplier.login') }}" class="volt-link">Sign In Here</a>
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
// Supplier Register - Interactive Robot Experience
document.addEventListener('DOMContentLoaded', function () {
  const $ = (s) => document.querySelector(s);

  const robot = $('#robot');
  const eyes = $('#eyes');
  const bubble = $('#bubble');
  const bubbleText = $('#bubbleText');
  const meter = $('#meter');
  const meterBars = [...meter.children];
  const panelLabel = $('#panelLabel');
  const form = $('#form');
  const companyI = $('#company_name');
  const nameI = $('#name');
  const phoneI = $('#phone');
  const passI = $('#password');
  const passConfI = $('#password_confirmation');
  const peekBtn = $('#togglePass');
  const btn = $('#submitBtn');
  const btnLabel = $('#btnLabel');
  const head3d = $('.head3d');
  const errorBox = $('#laravelErrorBox');

  const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;
  const PHONE_RE = /^(\+?88)?01[3-9]\d{8}$/;

  let done = false;
  let lastSaid = '';

  const pick = (arr) => arr[Math.floor(Math.random() * arr.length)];

  function setMood(mood) {
    if (!done) robot.dataset.mood = mood;
  }

  function say(text) {
    if (text === lastSaid) return;
    lastSaid = text;
    bubbleText.textContent = text;
    bubble.classList.remove('pop');
    void bubble.offsetWidth;
    bubble.classList.add('pop');
  }

  function look(x, y) {
    eyes.style.setProperty('--lx', `${x}px`);
    eyes.style.setProperty('--ly', `${y}px`);
  }

  function tilt(ry, rx) {
    head3d.style.setProperty('--ry', `${ry}deg`);
    head3d.style.setProperty('--rx', `${rx}deg`);
  }

  function followTyping(input) {
    const ratio = Math.min(input.value.length / 22, 1);
    look(-6 + 12 * ratio, 5);
    tilt(-5 + 10 * ratio, -8);
  }

  function turnAway(on) {
    robot.classList.toggle('is-turned', on);
  }

  // Initial check for Laravel validation errors
  if (errorBox) {
    setMood('watching');
    say('Please check the registration details below.');
    form.classList.add('shake');
  }

  // Company Name Focus
  companyI.addEventListener('focus', () => {
    turnAway(false);
    setMood('watching');
    say("Enter your business or trade company name!");
    followTyping(companyI);
  });
  companyI.addEventListener('input', () => {
    followTyping(companyI);
    if (companyI.value.trim().length > 3) {
      setMood('happy');
      say(`${companyI.value.trim()}. Sounds like a great company!`);
    }
  });

  // Contact Person Focus
  nameI.addEventListener('focus', () => {
    turnAway(false);
    setMood('watching');
    say("Who is the authorized contact person?");
    followTyping(nameI);
  });

  // Phone Focus
  phoneI.addEventListener('focus', () => {
    turnAway(false);
    setMood('watching');
    say("Your mobile number for order dispatch & notifications.");
    followTyping(phoneI);
  });
  phoneI.addEventListener('input', () => {
    followTyping(phoneI);
    const cleaned = phoneI.value.replace(/[- ]/g, '');
    if (PHONE_RE.test(cleaned)) {
      setMood('happy');
      say("Valid Bangladesh mobile number. Verified!");
    }
  });

  // Password Input - Robot turns head around
  [passI, passConfI].forEach(input => {
    input.addEventListener('focus', () => {
      setMood('shy');
      turnAway(true);
      look(0, 0);
      tilt(0, 0);
      say("Setting a secure password? *turns around*");
      panelLabel.textContent = 'NOT LOOKING';
    });

    input.addEventListener('blur', (e) => {
      if (e.relatedTarget === peekBtn) return;
      if (document.activeElement !== passI && document.activeElement !== passConfI) {
        turnAway(false);
      }
    });
  });

  passI.addEventListener('input', () => {
    const v = passI.value;
    let score = 0;
    if (v.length >= 6) score++;
    if (/[a-z]/.test(v) && /[A-Z]/.test(v)) score++;
    if (/\d/.test(v)) score++;
    if (/[^a-zA-Z0-9]/.test(v) || v.length >= 10) score++;
    if (v.length > 0 && score === 0) score = 1;

    meter.dataset.lvl = score;
    meterBars.forEach((bar, i) => bar.classList.toggle('on', i < score));
    panelLabel.textContent = v.length === 0
      ? 'NOT LOOKING'
      : ['NOT LOOKING', 'TOO SHORT', 'GETTING THERE', 'STRONG', 'FORT KNOX'][score];
  });

  // Password Peek Toggle
  peekBtn.addEventListener('click', () => {
    const show = passI.type === 'password';
    passI.type = show ? 'text' : 'password';
    passConfI.type = show ? 'text' : 'password';
    peekBtn.setAttribute('aria-pressed', String(show));
    peekBtn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    if (show) say("Password revealed. Don't worry, still looking away!");
    passI.focus();
  });

  // Button Hover / Hype
  function hype(on) {
    if (done) return;
    if (on && robot.classList.contains('is-pressed')) return;
    robot.classList.toggle('is-hyped', on);
    if (on) {
      turnAway(false);
      setMood('excited');
      say("Ready to join the platform? Hit the button!");
    } else {
      setMood('idle');
      say("Looking good! We're almost ready.");
    }
  }

  btn.addEventListener('mouseenter', () => hype(true));
  btn.addEventListener('mouseleave', () => hype(false));
  btn.addEventListener('focus', () => hype(true));
  btn.addEventListener('blur', () => hype(false));

  // Closed happy eyes on click press
  let pressTimer;
  btn.addEventListener('pointerdown', () => {
    clearTimeout(pressTimer);
    robot.classList.add('is-pressed');
    robot.dataset.mood = 'pressed';
    say("Registering your partner account...");
  });

  function releasePress() {
    clearTimeout(pressTimer);
    pressTimer = setTimeout(() => {
      robot.classList.remove('is-pressed');
      if (robot.dataset.mood === 'pressed') {
        robot.dataset.mood = done ? 'success' : 'excited';
      }
    }, 340);
  }

  btn.addEventListener('pointerup', releasePress);
  btn.addEventListener('pointercancel', releasePress);
  btn.addEventListener('pointerleave', () => {
    if (robot.classList.contains('is-pressed')) releasePress();
  });

  // Form Submit
  form.addEventListener('submit', (e) => {
    if (done) return;

    const complaints = [];
    if (!companyI.value.trim()) {
      complaints.push(["Please provide your company name.", companyI]);
    } else if (!nameI.value.trim()) {
      complaints.push(["Please provide contact person name.", nameI]);
    } else if (!phoneI.value.trim()) {
      complaints.push(["Please enter a valid mobile number.", phoneI]);
    } else if (!passI.value) {
      complaints.push(["A secure password is required.", passI]);
    } else if (passI.value.length < 6) {
      complaints.push(["Password must be at least 6 characters.", passI]);
    } else if (passI.value !== passConfI.value) {
      complaints.push(["Passwords do not match!", passConfI]);
    }

    if (complaints.length) {
      e.preventDefault();
      const [msg, field] = complaints[0];
      setTimeout(() => { say(msg); setMood('watching'); }, 300);
      form.classList.remove('shake');
      void form.offsetWidth;
      form.classList.add('shake');
      field.focus();
      return;
    }

    // Validation passed -> Victory sequence, confetti, then submit!
    e.preventDefault();
    done = true;
    turnAway(false);
    robot.classList.remove('is-hyped');

    setTimeout(() => {
      robot.dataset.mood = 'success';
      say("Registration in progress! Welcome aboard!");
      btn.classList.add('is-success');
      btnLabel.textContent = 'REGISTERING ACCOUNT... ✓';
      look(0, 0);
      tilt(0, 0);

      if (!reduceMotion) {
        robot.classList.add('is-spinning');
        setTimeout(() => robot.classList.remove('is-spinning'), 950);
        confetti();
      }

      setTimeout(() => {
        form.submit();
      }, 700);
    }, 350);
  });

  // Natural Blinking Loop
  (function blinkLoop() {
    setTimeout(() => {
      if (robot.dataset.mood !== 'success' && !robot.classList.contains('is-turned')) {
        eyes.classList.add('blink');
        setTimeout(() => eyes.classList.remove('blink'), 150);
      }
      blinkLoop();
    }, 2600 + Math.random() * 2600);
  })();

  // Mouse Tracking
  let rafPending = false;
  document.addEventListener('mousemove', (e) => {
    const active = document.activeElement;
    if (done || (active && active.tagName === 'INPUT')) return;
    if (rafPending) return;
    rafPending = true;
    requestAnimationFrame(() => {
      rafPending = false;
      const rect = robot.getBoundingClientRect();
      const cx = rect.left + rect.width / 2;
      const cy = rect.top + rect.height / 2;
      const dx = Math.max(-1, Math.min(1, (e.clientX - cx) / 260));
      const dy = Math.max(-1, Math.min(1, (e.clientY - cy) / 260));
      look(dx * 7, dy * 6);
      if (!robot.classList.contains('is-turned')) tilt(dx * 12, -dy * 9);
    });
  });

  // Confetti
  function confetti() {
    const colors = ['#2ec4b6', '#ff6b4b', '#ffc53d', '#23252d', '#fffdf8'];
    const origin = btn.getBoundingClientRect();
    const host = document.querySelector('.scene');
    const hostRect = host.getBoundingClientRect();
    const ox = origin.left - hostRect.left + origin.width / 2;
    const oy = origin.top - hostRect.top;

    for (let i = 0; i < 70; i++) {
      const bit = document.createElement('span');
      bit.className = 'confetti';
      bit.style.background = pick(colors);
      if (Math.random() > .5) bit.style.borderRadius = '50%';
      host.appendChild(bit);

      const angle = -Math.PI / 2 + (Math.random() - .5) * 1.6;
      const speed = 240 + Math.random() * 380;
      const tx = Math.cos(angle) * speed;
      const ty = Math.sin(angle) * speed;

      bit.animate(
        [
          { transform: `translate(${ox}px, ${oy}px) rotate(0deg) scale(1)`, opacity: 1 },
          { transform: `translate(${ox + tx}px, ${oy + ty + 320}px) rotate(${540 * (Math.random() > .5 ? 1 : -1)}deg) scale(.6)`, opacity: 0 }
        ],
        { duration: 1100 + Math.random() * 700, easing: 'cubic-bezier(.15,.6,.35,1)' }
      ).onfinish = function () { bit.remove(); };
    }
  }
});
</script>

@endsection
