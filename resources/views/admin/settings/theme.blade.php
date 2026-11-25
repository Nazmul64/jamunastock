@extends('layouts.backend.app')

@section('title', 'ওয়েবসাইট থিম ও কালার কাস্টমাইজেশন')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content pt-3">
<div class="container-fluid">
    <!-- Header Breadcrumb -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1" style="color: #0f172a;">
                    <i class="ti-palette text-success me-2"></i>ওয়েবসাইট থিম ও কালার কাস্টমাইজেশন
                </h4>
                <p class="text-muted small mb-0">ন্যাভবার, বাটন, কার্ড, লাইভ চ্যাট ও ফুটারের কালার সহজে পরিবর্তন করুন।</p>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.settings.theme.reset') }}" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে সমস্ত কালার ডিফল্ট মানে রিসেট করতে চান?');">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="fa fa-refresh me-1"></i>ডিফল্ট রিসেট করুন
                    </button>
                </form>
                <a href="{{ url('/') }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="fa fa-external-link me-1"></i>লাইভ সাইট দেখুন
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.theme.update') }}" method="POST" id="themeForm">
        @csrf
        <div class="row">
            <!-- Left Side: Color Controls Form -->
            <div class="col-lg-7">
                <!-- 1. Navbar & Header Color Settings -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-bars text-success me-2"></i>১. ন্যাভবার ও হেডার কালার (Navbar Colors)
                        </h5>
                        <span class="badge bg-light text-dark border">টপ বার ও ন্যাভবার</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ন্যাভবার ব্যাকগ্রাউন্ড (উপরের অংশ)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_navbar_bg" value="{{ $currentTheme['theme_navbar_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_navbar_bg" id="input_theme_navbar_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_navbar_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #063C19</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ন্যাভবার ব্যাকগ্রাউন্ড (নিচের গ্র্যাডিয়েন্ট)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_navbar_bg_2" value="{{ $currentTheme['theme_navbar_bg_2'] }}" style="width: 50px;">
                                    <input type="text" name="theme_navbar_bg_2" id="input_theme_navbar_bg_2" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_navbar_bg_2'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #053315</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ন্যাভবার বাটন ও হাইলাইট কালার (Home / Search)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_navbar_btn_bg" value="{{ $currentTheme['theme_navbar_btn_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_navbar_btn_bg" id="input_theme_navbar_btn_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_navbar_btn_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #D97706 (গোল্ডেন/অরেঞ্জ)</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ন্যাভবার টেক্সট কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_navbar_text" value="{{ $currentTheme['theme_navbar_text'] }}" style="width: 50px;">
                                    <input type="text" name="theme_navbar_text" id="input_theme_navbar_text" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_navbar_text'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #FFFFFF</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. General Buttons & Action Elements -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-hand-pointer-o text-primary me-2"></i>২. বাটন ও অ্যাকশন কালার (Buttons & Actions)
                        </h5>
                        <span class="badge bg-light text-dark border">ওয়েবসাইটের সব বাটন</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">প্রাইমারি বাটন কালার (Primary Button)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_btn_primary" value="{{ $currentTheme['theme_btn_primary'] }}" style="width: 50px;">
                                    <input type="text" name="theme_btn_primary" id="input_theme_btn_primary" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_btn_primary'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #0C5C2D</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">অ্যাকসেন্ট / গোল্ডেন বাটন কালার (Accent Button)</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_btn_accent" value="{{ $currentTheme['theme_btn_accent'] }}" style="width: 50px;">
                                    <input type="text" name="theme_btn_accent" id="input_theme_btn_accent" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_btn_accent'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #F59E0B</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Cards & Content Borders -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-th-large text-warning me-2"></i>৩. কার্ড ও কন্টেন্ট হেডার (Cards & Headers)
                        </h5>
                        <span class="badge bg-light text-dark border">সেকশন ও প্ল্যান কার্ড</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">কার্ড হেডার / ব্যাজ ব্যাকগ্রাউন্ড</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_card_header_bg" value="{{ $currentTheme['theme_card_header_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_card_header_bg" id="input_theme_card_header_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_card_header_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #064E3B</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">কার্ড ও বর্ডার বর্ডার কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_card_border" value="{{ $currentTheme['theme_card_border'] }}" style="width: 50px;">
                                    <input type="text" name="theme_card_border" id="input_theme_card_border" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_card_border'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #10B981</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Live Chat Widget Customizer -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-comments text-info me-2"></i>৪. লাইভ চ্যাট উইজেট কালার (Live Chat Widget)
                        </h5>
                        <span class="badge bg-light text-dark border">ভাসমান চ্যাটবক্স</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">চ্যাট ফ্লোটিং ট্রিগার বাটন</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_chat_btn_bg" value="{{ $currentTheme['theme_chat_btn_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_chat_btn_bg" id="input_theme_chat_btn_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_chat_btn_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #0C5C2D</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">চ্যাট উইন্ডো হেডার ব্যাকগ্রাউন্ড</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_chat_header_bg" value="{{ $currentTheme['theme_chat_header_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_chat_header_bg" id="input_theme_chat_header_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_chat_header_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #06381A</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">বট মেসেজ বাবল কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_chat_bubble_bg" value="{{ $currentTheme['theme_chat_bubble_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_chat_bubble_bg" id="input_theme_chat_bubble_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_chat_bubble_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #0C5C2D</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">মেসেজ সেন্ড বাটন কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_chat_send_bg" value="{{ $currentTheme['theme_chat_send_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_chat_send_bg" id="input_theme_chat_send_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_chat_send_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #F59E0B</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Footer Colors -->
                <div class="card shadow-sm border-0 rounded-4 mb-4">
                    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fa fa-shoe-prints text-danger me-2"></i>৫. ফুটার কালার (Footer Styling)
                        </h5>
                        <span class="badge bg-light text-dark border">সাইটের নিচের অংশ</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ফুটার ব্যাকগ্রাউন্ড কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_footer_bg" value="{{ $currentTheme['theme_footer_bg'] }}" style="width: 50px;">
                                    <input type="text" name="theme_footer_bg" id="input_theme_footer_bg" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_footer_bg'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #052611</small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-secondary small">ফুটার টেক্সট ও সাবটাইটেল কালার</label>
                                <div class="input-group">
                                    <input type="color" class="form-control form-control-color theme-color-picker" id="picker_theme_footer_text" value="{{ $currentTheme['theme_footer_text'] }}" style="width: 50px;">
                                    <input type="text" name="theme_footer_text" id="input_theme_footer_text" class="form-control text-uppercase font-monospace" value="{{ $currentTheme['theme_footer_text'] }}" required>
                                </div>
                                <small class="text-muted">ডিফল্ট: #CBD5E1</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button Bar -->
                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm fw-bold">
                        <i class="fa fa-save me-2"></i>থিম কালার সংরক্ষণ করুন
                    </button>
                </div>
            </div>

            <!-- Right Side: Live Interactive Preview -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white py-3 rounded-top-4 d-flex align-items-center justify-content-between">
                        <h6 class="fw-bold mb-0">
                            <i class="fa fa-eye text-warning me-2"></i>রিয়েল-টাইম লাইভ প্রিভিউ (Live Preview)
                        </h6>
                        <span class="badge bg-warning text-dark">তাৎক্ষণিক পরিবর্তন দেখুন</span>
                    </div>
                    <div class="card-body p-3 bg-light">
                        <!-- Preview 1: Header / Navbar -->
                        <div class="mb-3">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">ন্যাভবার প্রিভিউ:</small>
                            <div id="pv_navbar" class="p-2 rounded-3 text-white shadow-sm" style="background: linear-gradient(180deg, {{ $currentTheme['theme_navbar_bg'] }} 0%, {{ $currentTheme['theme_navbar_bg_2'] }} 100%);">
                                <div class="d-flex align-items-center justify-content-between mb-2 pb-1 border-bottom border-light border-opacity-25">
                                    <span class="fw-bold fs-14"><i class="fa fa-leaf text-warning me-1"></i> Jamuna Stock</span>
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-warning text-dark px-2 py-1" style="font-size: 11px;">বাংলা</span>
                                        <span class="badge bg-light text-dark px-2 py-1" style="font-size: 11px;">ENG</span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1 overflow-hidden" style="font-size: 11px;">
                                    <span id="pv_nav_home" class="badge px-2 py-1 text-dark fw-bold" style="background: {{ $currentTheme['theme_navbar_btn_bg'] }};">
                                        <i class="fa fa-home"></i> Home
                                    </span>
                                    <span id="pv_nav_link1" class="text-white px-1">About Us</span>
                                    <span id="pv_nav_link2" class="text-white px-1">Projects</span>
                                    <span id="pv_nav_link3" class="text-white px-1">Gallery</span>
                                    <span id="pv_nav_link4" class="text-white px-1 fw-bold">ব্লগ</span>
                                    <span id="pv_nav_link5" class="text-white px-1">Contact</span>
                                </div>
                            </div>
                        </div>

                        <!-- Preview 2: Buttons -->
                        <div class="mb-3">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">বাটন প্রিভিউ:</small>
                            <div class="p-3 bg-white rounded-3 border d-flex flex-wrap gap-2 align-items-center">
                                <button type="button" id="pv_btn_primary" class="btn btn-sm text-white rounded-pill px-3 fw-bold" style="background-color: {{ $currentTheme['theme_btn_primary'] }};">
                                    <i class="fa fa-check me-1"></i> প্রাইমারি বাটন
                                </button>
                                <button type="button" id="pv_btn_accent" class="btn btn-sm text-dark rounded-pill px-3 fw-bold" style="background-color: {{ $currentTheme['theme_btn_accent'] }};">
                                    <i class="fa fa-star me-1"></i> অ্যাকসেন্ট বাটন
                                </button>
                            </div>
                        </div>

                        <!-- Preview 3: Card & Badges -->
                        <div class="mb-3">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">কার্ড ও ইনভেস্টমেন্ট স্কিম প্রিভিউ:</small>
                            <div id="pv_card" class="bg-white rounded-3 p-3 shadow-sm" style="border: 1.5px solid {{ $currentTheme['theme_card_border'] }};">
                                <span id="pv_card_badge" class="badge px-2 py-1 text-white mb-2" style="background-color: {{ $currentTheme['theme_card_header_bg'] }}; font-size: 11px;">
                                    <i class="fa fa-shield me-1"></i> ৩ বছর মেয়াদি স্কিম
                                </span>
                                <h6 class="fw-bold mb-1 text-dark">মাসিক লভ্যাংশ ইনভেস্টমেন্ট স্কিম</h6>
                                <p class="text-muted small mb-2">প্রতি মাসে নির্ধারিত মুনাফা ব্যাংক বা নগদ প্রদান।</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-success fs-16">১৪.০০% বাৎসরিক</span>
                                    <button type="button" id="pv_card_btn" class="btn btn-xs btn-sm text-white rounded-pill px-3" style="background-color: {{ $currentTheme['theme_btn_primary'] }};">আবেদন করুন</button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview 4: Live Chat Widget -->
                        <div class="mb-3">
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">লাইভ চ্যাট উইজেট প্রিভিউ:</small>
                            <div class="bg-white rounded-4 shadow-sm border overflow-hidden position-relative">
                                <div id="pv_chat_header" class="p-2 text-white d-flex align-items-center justify-content-between" style="background-color: {{ $currentTheme['theme_chat_header_bg'] }};">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-white text-dark d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 11px;">
                                            <i class="fa fa-headphones"></i>
                                        </div>
                                        <span class="fw-bold" style="font-size: 12px;">Customer Support</span>
                                    </div>
                                    <span class="badge bg-success" style="font-size: 9px;">Online</span>
                                </div>
                                <div class="p-2 bg-light d-flex flex-column gap-2" style="min-height: 90px;">
                                    <div id="pv_chat_bubble" class="text-white p-2 rounded-3 align-self-start shadow-sm" style="background-color: {{ $currentTheme['theme_chat_bubble_bg'] }}; font-size: 12px; max-width: 80%;">
                                        👋 স্বাগতম! আমি কীভাবে আপনাকে সাহায্য করতে পারি?
                                    </div>
                                </div>
                                <div class="p-2 bg-white border-top d-flex align-items-center gap-2">
                                    <input type="text" class="form-control form-control-sm rounded-pill" placeholder="মেসেজ লিখুন..." readonly>
                                    <button type="button" id="pv_chat_send" class="btn btn-sm rounded-circle text-white d-flex align-items-center justify-content-center" style="background-color: {{ $currentTheme['theme_chat_send_bg'] }}; width: 28px; height: 28px; padding: 0;">
                                        <i class="fa fa-paper-plane" style="font-size: 10px;"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Preview 5: Footer -->
                        <div>
                            <small class="text-muted fw-bold text-uppercase d-block mb-1">ফুটার প্রিভিউ:</small>
                            <div id="pv_footer" class="p-3 rounded-3" style="background-color: {{ $currentTheme['theme_footer_bg'] }}; color: {{ $currentTheme['theme_footer_text'] }}; font-size: 11px;">
                                <div class="fw-bold mb-1 text-warning">Jamuna Stock Market PLC</div>
                                <div id="pv_footer_text" style="color: {{ $currentTheme['theme_footer_text'] }};">
                                    সবার জন্য টেকসই ও নিরাপদ বিনিয়োগের বিশ্বস্ত প্ল্যাটফর্ম।
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fields = [
        'theme_navbar_bg',
        'theme_navbar_bg_2',
        'theme_navbar_btn_bg',
        'theme_navbar_text',
        'theme_btn_primary',
        'theme_btn_accent',
        'theme_card_header_bg',
        'theme_card_border',
        'theme_chat_btn_bg',
        'theme_chat_header_bg',
        'theme_chat_bubble_bg',
        'theme_chat_send_bg',
        'theme_footer_bg',
        'theme_footer_text'
    ];

    function updatePreview() {
        const navBg1 = document.getElementById('input_theme_navbar_bg').value;
        const navBg2 = document.getElementById('input_theme_navbar_bg_2').value;
        const navBtn = document.getElementById('input_theme_navbar_btn_bg').value;
        const navText = document.getElementById('input_theme_navbar_text').value;
        const btnPrim = document.getElementById('input_theme_btn_primary').value;
        const btnAcc = document.getElementById('input_theme_btn_accent').value;
        const cardHdr = document.getElementById('input_theme_card_header_bg').value;
        const cardBdr = document.getElementById('input_theme_card_border').value;
        const chatHdr = document.getElementById('input_theme_chat_header_bg').value;
        const chatBbl = document.getElementById('input_theme_chat_bubble_bg').value;
        const chatSnd = document.getElementById('input_theme_chat_send_bg').value;
        const ftrBg = document.getElementById('input_theme_footer_bg').value;
        const ftrTxt = document.getElementById('input_theme_footer_text').value;

        // Navbar
        document.getElementById('pv_navbar').style.background = `linear-gradient(180deg, ${navBg1} 0%, ${navBg2} 100%)`;
        document.getElementById('pv_nav_home').style.backgroundColor = navBtn;
        ['pv_nav_link1', 'pv_nav_link2', 'pv_nav_link3', 'pv_nav_link4', 'pv_nav_link5'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.color = navText;
        });

        // Buttons
        document.getElementById('pv_btn_primary').style.backgroundColor = btnPrim;
        document.getElementById('pv_btn_accent').style.backgroundColor = btnAcc;
        document.getElementById('pv_card_btn').style.backgroundColor = btnPrim;

        // Cards
        document.getElementById('pv_card').style.borderColor = cardBdr;
        document.getElementById('pv_card_badge').style.backgroundColor = cardHdr;

        // Chat
        document.getElementById('pv_chat_header').style.backgroundColor = chatHdr;
        document.getElementById('pv_chat_bubble').style.backgroundColor = chatBbl;
        document.getElementById('pv_chat_send').style.backgroundColor = chatSnd;

        // Footer
        document.getElementById('pv_footer').style.backgroundColor = ftrBg;
        document.getElementById('pv_footer').style.color = ftrTxt;
        document.getElementById('pv_footer_text').style.color = ftrTxt;
    }

    fields.forEach(field => {
        const picker = document.getElementById('picker_' + field);
        const textInput = document.getElementById('input_' + field);

        if (picker && textInput) {
            picker.addEventListener('input', function () {
                textInput.value = picker.value.toUpperCase();
                updatePreview();
            });

            textInput.addEventListener('input', function () {
                if (/^#[0-9A-F]{6}$/i.test(textInput.value)) {
                    picker.value = textInput.value;
                    updatePreview();
                }
            });
        }
    });

    updatePreview();
});
</script>
@endsection
