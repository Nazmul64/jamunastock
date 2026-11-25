<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ setting('title') ?: config('app.name', 'JAMUNA STOCK MARKET PLC') }}</title>

    <!-- Standard Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ setting('favicon') ? asset('upload/images/backend/logo/' . setting('favicon')) : (setting('logo') ? asset('upload/images/backend/logo/' . setting('logo')) : asset('assets/images/logo.png')) }}" />

    <!-- For iPhone 4 Retina display: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-114x114-precomposed.png">

    <!-- For iPad: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-72x72-precomposed.html">

    <!-- For iPhone: -->
    <link rel="apple-touch-icon-precomposed"
        href="{{ asset('frontend/assets') }}/images/apple-touch-icon-57x57-precomposed.png">

    <!-- Library - Google Font Familys -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/revolution/css/settings.css">

    <!-- Library -->
    <link href="{{ asset('frontend/assets') }}/css/lib.css" rel="stylesheet">
    <link href="{{ asset('frontend/assets') }}/css/flags.css" rel="stylesheet">

    <!-- Custom - Common CSS -->
    <link rel="stylesheet" href="{{ asset('frontend/assets') }}/css/rtl.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/assets') }}/css/style.css">
    <link id="color" href="{{ asset('frontend/assets') }}/css/color-schemes/green.css" rel="stylesheet" />
    <style>
        :root {
            --theme-navbar-bg: {{ setting('theme_navbar_bg') ?: '#063c19' }};
            --theme-navbar-bg-2: {{ setting('theme_navbar_bg_2') ?: '#053315' }};
            --theme-navbar-btn-bg: {{ setting('theme_navbar_btn_bg') ?: '#d97706' }};
            --theme-navbar-text: {{ setting('theme_navbar_text') ?: '#ffffff' }};
            --theme-btn-primary: {{ setting('theme_btn_primary') ?: '#0c5c2d' }};
            --theme-btn-accent: {{ setting('theme_btn_accent') ?: '#f59e0b' }};
            --theme-card-header-bg: {{ setting('theme_card_header_bg') ?: '#064e3b' }};
            --theme-card-border: {{ setting('theme_card_border') ?: '#10b981' }};
            --theme-chat-btn-bg: {{ setting('theme_chat_btn_bg') ?: '#0c5c2d' }};
            --theme-chat-header-bg: {{ setting('theme_chat_header_bg') ?: '#06381a' }};
            --theme-chat-bubble-bg: {{ setting('theme_chat_bubble_bg') ?: '#0c5c2d' }};
            --theme-chat-send-bg: {{ setting('theme_chat_send_bg') ?: '#f59e0b' }};
            --theme-footer-bg: {{ setting('theme_footer_bg') ?: '#052611' }};
            --theme-footer-text: {{ setting('theme_footer_text') ?: '#cbd5e1' }};

            --jamuna-green-dark: var(--theme-navbar-bg);
            --jamuna-green-primary: var(--theme-btn-primary);
            --jamuna-green-light: #16a34a;
            --jamuna-gold-primary: var(--theme-btn-accent);
            --jamuna-gold-dark: var(--theme-navbar-btn-bg);
            --jamuna-red: #dc2626;
        }
        body, html {
            font-family: 'Hind Siliguri', 'Poppins', sans-serif !important;
            background-color: #f8fafc;
            overflow-x: hidden !important;
            max-width: 100vw !important;
            width: 100% !important;
            position: relative;
        }
        .rev_slider > ul li.slider-shape .slotholder::before {
            display: none !important;
            background: transparent !important;
            opacity: 0 !important;
        }
        @media (min-width: 1200px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                max-width: 1440px !important;
            }
        }

        /* --- Top Jamuna Header Branding --- */
        .jamuna-top-header {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 0;
            position: relative;
        }
        .jamuna-logo-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
        }
        .jamuna-logo-img {
            max-height: 75px;
            width: auto;
            object-fit: contain;
        }
        .jamuna-center-slogan {
            text-align: center;
        }
        .jamuna-center-slogan h4 {
            font-size: 20px;
            font-weight: 700;
            color: #0c5c2d;
            margin: 0 0 3px 0;
            letter-spacing: 0.5px;
        }
        .jamuna-center-slogan p {
            font-size: 14.5px;
            font-weight: 600;
            color: #334155;
            margin: 0;
        }
        .jamuna-center-slogan p span.dot {
            color: #0c5c2d;
            margin: 0 6px;
            font-weight: bold;
        }
        .jamuna-right-slogan {
            text-align: right;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }
        .jamuna-right-text h4 {
            font-size: 19px;
            font-weight: 700;
            color: #0c5c2d;
            margin: 0 0 3px 0;
        }
        .jamuna-right-text p {
            font-size: 18px;
            font-weight: 700;
            color: #dc2626;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .jamuna-leaf-icon {
            font-size: 32px;
            color: #16a34a;
            animation: pulse-leaf 2.5s infinite ease-in-out;
        }
        @keyframes pulse-leaf {
            0%, 100% { transform: scale(1) rotate(0deg); }
            50% { transform: scale(1.1) rotate(5deg); }
        }

        /* --- Jamuna Main Navigation Bar (Red Box in Image) --- */
        .jamuna-navbar-wrapper {
            background: linear-gradient(180deg, #0e5e2e 0%, #094721 50%, #053316 100%);
            border-top: 2px solid #22c55e;
            border-bottom: 2px solid #03210d;
            box-shadow: 0 4px 14px rgba(5, 51, 22, 0.35);
            padding: 0;
            position: relative;
            z-index: 1000;
        }
        /* ----------------------------------------------------
           NEW 2-ROW HEADER & NAVBAR (Exact Match to User Reference 2)
           Row 1: Logo | Big Search Bar | Language | Dark Mode | Auth Buttons
           Row 2: White Navbar with all links & Land & Properties [New]
           ---------------------------------------------------- */
        .ks-main-header {
            background: linear-gradient(90deg, #06381a 0%, #093c1d 50%, #06381a 100%);
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1040;
        }
        .ks-logo-img {
            max-height: 52px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        /* Centered Big Pill Search Bar */
        .ks-search-wrapper {
            position: relative;
            width: 100%;
            max-width: 620px;
            margin: 0 auto;
        }
        .ks-search-form {
            display: flex;
            align-items: stretch;
            background: #ffffff;
            border-radius: 50px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            border: 2px solid #f59e0b;
            height: 42px;
            transition: all 0.3s ease;
        }
        .ks-search-form:focus-within {
            border-color: #fbbf24;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.35);
        }
        .ks-search-input {
            border: none;
            outline: none;
            font-size: 14px;
            color: #1e293b;
            background: transparent;
            padding: 0 20px;
            flex: 1;
            width: 100%;
            font-family: inherit;
        }
        .ks-search-input::placeholder {
            color: #94a3b8;
            font-size: 13.5px;
            font-weight: 400;
        }
        .ks-search-btn {
            background: linear-gradient(180deg, #fbb03b 0%, #d97706 100%);
            border: none;
            outline: none;
            padding: 0 22px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #0f172a;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border-radius: 0 50px 50px 0;
            transition: all 0.25s ease;
            white-space: nowrap;
        }
        .ks-search-btn:hover {
            background: linear-gradient(180deg, #fcd34d 0%, #f59e0b 100%);
            color: #000000;
        }

        /* AJAX Live Search Dropdown */
        .ks-live-search-dropdown {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
            border: 1px solid #cbd5e1;
            z-index: 1060;
            max-height: 420px;
            overflow-y: auto;
            padding: 6px 0;
        }
        .ks-live-item {
            display: flex;
            align-items: center;
            padding: 10px 16px;
            color: #1e293b;
            text-decoration: none;
            transition: background 0.15s ease;
            border-bottom: 1px solid #f1f5f9;
        }
        .ks-live-item:last-child {
            border-bottom: none;
        }
        .ks-live-item:hover {
            background: #f0fdf4;
            color: #0c5c2d;
        }
        .ks-live-thumb {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            object-fit: cover;
            margin-right: 12px;
            flex-shrink: 0;
            border: 1px solid #e2e8f0;
        }
        .ks-live-info {
            flex: 1;
            min-width: 0;
        }
        .ks-live-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ks-live-meta {
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ks-live-price {
            font-size: 13.5px;
            font-weight: 800;
            color: #15803d;
            margin-left: 10px;
            flex-shrink: 0;
        }

        /* Top Header Action Buttons */
        .ks-lang-pill {
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 50px;
            padding: 4px 10px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .ks-lang-pill:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }
        .ks-lang-badge {
            background: #ffffff;
            color: #06381a;
            padding: 1px 7px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
        }
        .ks-theme-toggle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.35);
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .ks-theme-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #fde047;
        }
        /* --- Top Bar Social Media Buttons --- */
        .top-social-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.08);
            color: #cbd5e1 !important;
            font-size: 13px;
            text-decoration: none !important;
            border: 1px solid rgba(255, 255, 255, 0.14);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .top-social-btn:hover {
            transform: translateY(-2px);
            color: #ffffff !important;
        }
        .top-social-btn.fb:hover {
            background: #1877f2;
            border-color: #1877f2;
            box-shadow: 0 4px 10px rgba(24, 119, 242, 0.45);
        }
        .top-social-btn.tw:hover {
            background: #1da1f2;
            border-color: #1da1f2;
            box-shadow: 0 4px 10px rgba(29, 161, 242, 0.45);
        }
        .top-social-btn.in:hover {
            background: #0a66c2;
            border-color: #0a66c2;
            box-shadow: 0 4px 10px rgba(10, 102, 194, 0.45);
        }
        .top-social-btn.insta:hover {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);
            border-color: #d6249f;
            box-shadow: 0 4px 10px rgba(214, 36, 159, 0.45);
        }
        .top-social-btn.yt:hover {
            background: #ff0000;
            border-color: #ff0000;
            box-shadow: 0 4px 10px rgba(255, 0, 0, 0.45);
        }

        /* --- Auth Buttons Group (Spacing & Design) --- */
        .ks-auth-actions-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .ks-auth-actions-group > * {
            margin-right: 12px;
        }
        .ks-auth-actions-group > *:last-child {
            margin-right: 0 !important;
        }

        .ks-btn-login {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: #042410 !important;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #f59e0b;
            box-shadow: 0 3px 8px rgba(217, 119, 6, 0.3);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }
        .ks-btn-login:hover {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: #042410 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(245, 158, 11, 0.45);
        }

        .ks-btn-register {
            background: #ffffff;
            color: #06381a !important;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #ffffff;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }
        .ks-btn-register:hover {
            background: #f8fafc;
            color: #06381a !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(255, 255, 255, 0.3);
        }

        .ks-btn-logout {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5 !important;
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1.5px solid rgba(239, 68, 68, 0.45);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            white-space: nowrap;
        }
        .ks-btn-logout:hover {
            background: #dc2626;
            color: #ffffff !important;
            border-color: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(220, 38, 38, 0.4);
        }

        /* ----------------------------------------------------
           ROW 2: JAMUNA GREEN NAVBAR (Exact from User Screenshot)
           [ Home (Golden) ] | About Us | Our Products | Our Projects | Investor Relations | Membership | Media Center | Contact Us
           ---------------------------------------------------- */
        /* --- Premium Sticky Header & Navbar Container (User Request) --- */
        .ks-sticky-header-container {
            width: 100%;
            z-index: 1045 !important;
            transition: all 0.25s ease-in-out;
        }
        .ks-sticky-header-container.is-sticky {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 99999 !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.45) !important;
            animation: slideDownHeader 0.25s ease-out;
        }
        @keyframes slideDownHeader {
            from { transform: translateY(-100%); }
            to { transform: translateY(0); }
        }
        .ks-sticky-header-container.is-sticky .ks-main-header {
            padding: 8px 0 !important;
            background: linear-gradient(90deg, #052c15 0%, #083318 50%, #052c15 100%) !important;
        }
        .ks-sticky-header-container.is-sticky .ks-logo-img {
            max-height: 44px !important;
        }

        .ks-navbar-bar {
            background: linear-gradient(180deg, var(--theme-navbar-bg, #063c19) 0%, #0b5e28 50%, var(--theme-navbar-bg-2, #053315) 100%) !important;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            border-bottom: 2px solid #04240f;
            box-shadow: 0 4px 12px rgba(4, 36, 15, 0.4);
            position: relative;
            z-index: 1030;
            overflow: visible !important;
        }
        .ks-nav-list {
            display: flex;
            align-items: stretch;
            list-style: none;
            margin: 0;
            padding: 0;
            flex-wrap: nowrap !important;
            overflow: visible !important;
            width: 100%;
            justify-content: space-between;
        }
        .ks-nav-list > li {
            display: flex;
            align-items: stretch;
            position: relative;
            flex-shrink: 0;
        }

        /* Golden Home Button (Leftmost) */
        .ks-nav-link.jamuna-home-btn {
            background: linear-gradient(180deg, #ffe066 0%, var(--theme-navbar-btn-bg, #f59e0b) 50%, var(--theme-navbar-btn-bg, #d97706) 100%) !important;
            color: #0f172a !important;
            font-weight: 800 !important;
            font-size: 13.5px !important;
            padding: 10px 14px !important;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5), 0 2px 5px rgba(0, 0, 0, 0.25);
            transition: all 0.2s ease;
            white-space: nowrap;
        }
        .ks-nav-link.jamuna-home-btn:hover {
            background: linear-gradient(180deg, #fff380 0%, #fbbf24 50%, #f59e0b 100%) !important;
            color: #000000 !important;
        }
        .ks-nav-link.jamuna-home-btn i {
            font-size: 15px;
        }

        /* Regular Green Nav Links */
        .ks-nav-link {
            color: var(--theme-navbar-text, #ffffff) !important;
            font-weight: 700 !important;
            font-size: 13px !important;
            padding: 10px 8px !important;
            display: inline-flex;
            align-items: center;
            text-decoration: none !important;
            white-space: nowrap;
            transition: all 0.2s ease;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
        }
        .ks-nav-link:hover,
        .ks-nav-list > li.active .ks-nav-link:not(.jamuna-home-btn),
        .ks-nav-list > li.is-open .ks-nav-link:not(.jamuna-home-btn) {
            color: #fde047 !important;
            background: rgba(255, 255, 255, 0.12);
            text-shadow: 0 0 10px rgba(253, 224, 71, 0.5);
        }

        /* Golden Vertical Separators between Links */
        .ks-nav-list > li:not(:last-child):not(:first-child)::after {
            content: '';
            position: absolute;
            right: 0;
            top: 25%;
            height: 50%;
            width: 1px;
            background: linear-gradient(180deg, rgba(234, 179, 8, 0.2), #eab308 50%, rgba(234, 179, 8, 0.2));
        }

        /* Navbar Dropdown Menus - Must Pop Out Downwards Cleanly */
        .ks-nav-list > li.ks-dropdown {
            position: relative;
        }
        .ks-nav-list > li.ks-dropdown .ks-dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            min-width: 260px;
            background: linear-gradient(180deg, var(--theme-navbar-bg, #063c19) 0%, #04240f 100%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-top: 3px solid var(--theme-navbar-btn-bg, #d97706);
            border-radius: 0 0 10px 10px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.7);
            padding: 8px 0;
            z-index: 999999 !important;
            margin-top: 0;
        }
        .ks-nav-list > li.ks-dropdown .ks-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -15px;
            left: 0;
            right: 0;
            height: 15px;
            background: transparent;
        }
        .ks-nav-list > li.ks-dropdown:hover > .ks-dropdown-menu,
        .ks-nav-list > li.ks-dropdown.is-open > .ks-dropdown-menu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }
        .ks-dropdown-menu a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 18px;
            color: #ffffff !important;
            font-size: 13.5px !important;
            font-weight: 600 !important;
            text-decoration: none !important;
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            white-space: nowrap;
        }
        .ks-dropdown-menu a:last-child {
            border-bottom: none;
        }
        .ks-dropdown-menu a:hover {
            background: #0b5e28;
            color: #fde047 !important;
            padding-left: 22px;
        }
        .ks-dropdown-menu a i {
            width: 18px;
            color: #fbbf24;
            font-size: 14px;
        }

        /* ====================================================
           PREMIUM ANIMATED MOBILE TOGGLE BUTTON & DRAWER
           ==================================================== */
        .ks-mobile-toggle {
            display: none;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%);
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
            outline: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            width: 46px;
            height: 42px;
            position: relative;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .ks-mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.28);
            border-color: #fbb03b;
            box-shadow: 0 0 14px rgba(251, 176, 59, 0.45);
        }
        .ks-toggle-bars {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 22px;
            height: 17px;
            margin: auto;
            pointer-events: none;
        }
        .ks-toggle-bars .bar {
            display: block;
            height: 2.5px;
            width: 100%;
            background: #ffffff;
            border-radius: 3px;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: center;
        }
        /* Morph into 'X' when active */
        .ks-mobile-toggle.is-active {
            background: rgba(220, 38, 38, 0.22);
            border-color: #fbb03b;
            box-shadow: 0 0 16px rgba(251, 176, 59, 0.5);
        }
        .ks-mobile-toggle.is-active .ks-toggle-bars .bar-1 {
            transform: translateY(7.25px) rotate(45deg);
            background: #fbb03b;
        }
        .ks-mobile-toggle.is-active .ks-toggle-bars .bar-2 {
            opacity: 0;
            transform: scaleX(0);
        }
        .ks-mobile-toggle.is-active .ks-toggle-bars .bar-3 {
            transform: translateY(-7.25px) rotate(-45deg);
            background: #fbb03b;
        }

        /* ====================================================
           RESPONSIVE MOBILE VIEWPORT (max-width: 991.98px)
           ==================================================== */
        @media (max-width: 991.98px) {
            body, html {
                font-size: 15px !important;
                line-height: 1.6 !important;
                overflow-x: hidden !important;
                max-width: 100vw !important;
                width: 100% !important;
            }
            .ks-main-header {
                padding: 10px 0 !important;
                width: 100% !important;
                max-width: 100vw !important;
                overflow-x: hidden !important;
            }
            .ks-main-header .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .ks-logo-img {
                max-height: 42px !important;
                max-width: 185px !important;
                width: auto !important;
                object-fit: contain !important;
            }
            .ks-mobile-toggle {
                display: flex !important;
                align-items: center;
                justify-content: center;
                margin-left: auto !important;
                flex-shrink: 0 !important;
            }
            .ks-navbar-bar {
                display: none !important;
            }
        }

        /* --- Off-Canvas Mobile Drawer & Backdrop (Left-to-Right Slide) --- */
        .ks-drawer-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            z-index: 99998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease, visibility 0.35s ease;
        }
        .ks-drawer-backdrop.is-open {
            opacity: 1;
            visibility: visible;
        }

        .ks-mobile-drawer {
            position: fixed;
            top: 0;
            left: 0;
            width: 320px;
            max-width: 86vw;
            height: 100%;
            height: 100vh;
            height: 100dvh;
            background: linear-gradient(180deg, #031c0c 0%, #063c19 50%, #03200e 100%);
            z-index: 99999;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 12px 0 35px rgba(0, 0, 0, 0.65);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        .ks-mobile-drawer.is-open {
            transform: translateX(0);
        }

        .ks-drawer-close-btn {
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .ks-drawer-close-btn:hover {
            background: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
        }

        .ks-drawer-menu-list > li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.07);
        }
        .ks-drawer-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 13px 16px;
            color: #f8fafc !important;
            text-decoration: none !important;
            font-size: 15px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .ks-drawer-link:hover,
        .ks-drawer-link.active {
            background: rgba(245, 158, 11, 0.15);
            color: #fbb03b !important;
        }

        .ks-dd-chevron {
            transition: transform 0.3s ease;
            font-size: 14px;
        }
        .ks-drawer-dropdown.is-open .ks-dd-chevron {
            transform: rotate(180deg);
            color: #fbb03b;
        }

        .ks-drawer-sub-menu {
            display: none;
            background: rgba(0, 0, 0, 0.25);
            border-left: 3px solid #f59e0b;
            border-radius: 6px;
            margin: 4px 10px 10px 14px;
            padding: 6px 0;
        }
        .ks-drawer-dropdown.is-open .ks-drawer-sub-menu {
            display: block;
        }
        .ks-drawer-sub-menu li a {
            display: block;
            padding: 9px 16px;
            color: #cbd5e1 !important;
            text-decoration: none !important;
            font-size: 13.5px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        .ks-drawer-sub-menu li a:hover {
            color: #fde047 !important;
            background: rgba(255, 255, 255, 0.05);
        }

        /* Hide slider arrows & overlay caption boxes */
        .tparrows, .tp-bullets, .tp-caption {
            display: none !important;
        }

        /* Mobile responsive fix for gallery box - 2 items per row */
        @media (max-width: 575.98px) {
            .gallery-list {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-left: -6px !important;
                margin-right: -6px !important;
            }
            .gallery-list .gallery-box,
            .portfolio-section .gallery-box {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                width: 50% !important;
                padding-left: 6px !important;
                padding-right: 6px !important;
                margin-bottom: 12px !important;
                float: left !important;
            }
            .gallery-box .gallery-content {
                position: relative !important;
                overflow: hidden !important;
                border-radius: 8px !important;
                width: 100% !important;
            }
            .gallery-box .gallery-content img,
            .gallery-box .gallery-content i img {
                width: 100% !important;
                height: 160px !important;
                object-fit: cover !important;
                border-radius: 8px !important;
                display: block !important;
            }
        }

        @media (min-width: 576px) and (max-width: 767.98px) {
            .gallery-list {
                display: flex !important;
                flex-wrap: wrap !important;
                margin-left: -8px !important;
                margin-right: -8px !important;
            }
            .gallery-list .gallery-box,
            .portfolio-section .gallery-box {
                flex: 0 0 50% !important;
                max-width: 50% !important;
                width: 50% !important;
                padding-left: 8px !important;
                padding-right: 8px !important;
                margin-bottom: 16px !important;
                float: left !important;
            }
            .gallery-box .gallery-content img,
            .gallery-box .gallery-content i img {
                width: 100% !important;
                height: 200px !important;
                object-fit: cover !important;
                border-radius: 8px !important;
                display: block !important;
            }
        }
        /* ====================================================
           PREMIUM JAMUNA BRAND LOGO PRELOADER CSS
           ==================================================== */
        .jamuna-site-preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: radial-gradient(circle at center, #06381a 0%, #031c0c 60%, #010f06 100%);
            z-index: 9999999 !important;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.6s ease;
        }
        .jamuna-preloader-content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 30px;
        }
        .jamuna-preloader-glow-ring {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 220px;
            height: 220px;
            margin-top: -140px;
            margin-left: -110px;
            border-radius: 50%;
            border: 2px dashed rgba(245, 158, 11, 0.35);
            animation: jamuna-spin-clockwise 10s linear infinite;
            pointer-events: none;
        }
        .jamuna-preloader-glow-ring.ring-secondary {
            width: 250px;
            height: 250px;
            margin-top: -155px;
            margin-left: -125px;
            border: 1.5px solid rgba(34, 197, 94, 0.25);
            animation: jamuna-spin-counter 14s linear infinite;
        }
        @keyframes jamuna-spin-clockwise {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        @keyframes jamuna-spin-counter {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }
        .jamuna-preloader-logo-box {
            position: relative;
            z-index: 2;
            padding: 16px 28px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1.5px solid rgba(245, 158, 11, 0.5);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.5), 0 0 30px rgba(245, 158, 11, 0.3);
            animation: jamuna-logo-pulse 2.2s ease-in-out infinite alternate;
        }
        .jamuna-preloader-logo-img {
            max-height: 75px;
            max-width: 260px;
            width: auto;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.6));
            display: block;
        }
        @keyframes jamuna-logo-pulse {
            0% {
                transform: translateY(0) scale(0.97);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4), 0 0 20px rgba(245, 158, 11, 0.25);
            }
            100% {
                transform: translateY(-5px) scale(1.02);
                box-shadow: 0 18px 40px rgba(0, 0, 0, 0.6), 0 0 40px rgba(245, 158, 11, 0.5);
            }
        }
        .jamuna-preloader-bar-track {
            position: relative;
            z-index: 2;
            width: 200px;
            height: 5px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 10px;
            margin-top: 28px;
            overflow: hidden;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.4);
        }
        .jamuna-preloader-bar-fill {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 45%;
            background: linear-gradient(90deg, #f59e0b, #fbbf24, #22c55e, #f59e0b);
            background-size: 200% 100%;
            border-radius: 10px;
            box-shadow: 0 0 10px #f59e0b;
            animation: jamuna-bar-slide 1.6s infinite ease-in-out;
        }
        @keyframes jamuna-bar-slide {
            0% { left: -50%; width: 40%; }
            50% { left: 25%; width: 60%; }
            100% { left: 105%; width: 35%; }
        }
        .jamuna-preloader-text-wrap {
            position: relative;
            z-index: 2;
            margin-top: 16px;
        }
        .jamuna-preloader-title {
            display: block;
            font-size: 16px;
            font-weight: 800;
            color: #fbb03b;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .jamuna-preloader-subtitle {
            margin: 0;
            font-size: 13px;
            font-weight: 500;
            color: #cbd5e1;
            letter-spacing: 0.3px;
            animation: jamuna-subtext-blink 1.8s infinite ease-in-out;
        }
        @keyframes jamuna-subtext-blink {
            0%, 100% { opacity: 0.65; }
            50% { opacity: 1; }
        }
    </style>
</head>

<body data-offset="200" data-spy="scroll" data-target=".ownavigation">
    <!-- ==========================================
         PREMIUM JAMUNA BRAND LOGO PRELOADER
         ========================================== -->
    @php
        $preloaderLogo = setting('logo');
        $hasPreloaderLogo = (!empty($preloaderLogo) && file_exists(public_path('upload/images/backend/logo/' . $preloaderLogo)));
    @endphp
    <div id="site-loader" class="jamuna-site-preloader">
        <div class="jamuna-preloader-content">
            <!-- Glowing Orbit Rings around Logo -->
            <div class="jamuna-preloader-glow-ring"></div>
            <div class="jamuna-preloader-glow-ring ring-secondary"></div>

            <!-- Logo Card Box -->
            <div class="jamuna-preloader-logo-box">
                @if($hasPreloaderLogo)
                    <img src="{{ asset('upload/images/backend/logo/' . $preloaderLogo) }}" alt="{{ setting('title') ?: 'Logo' }}" class="jamuna-preloader-logo-img">
                @else
                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                        <i class="fa fa-leaf text-success" style="font-size: 38px;"></i>
                    </div>
                @endif
            </div>

            <!-- Animated Gold & Green Progress Bar -->
            <div class="jamuna-preloader-bar-track">
                <div class="jamuna-preloader-bar-fill"></div>
            </div>

            <!-- Slogan & Loading Text -->
            <div class="jamuna-preloader-text-wrap">
                <span class="jamuna-preloader-title">Jamuna Stock Market PLC</span>
                <p class="jamuna-preloader-subtitle">
                    <i class="fa fa-circle-o-notch fa-spin me-1 text-warning"></i> Loading, please wait...
                </p>
            </div>
        </div>
    </div><!-- Preloader /- -->

    <!-- Top Header Bar -->
    <div class="top-header-bar d-none d-md-block py-2" style="background: #031a0c; color: #cbd5e1; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="container" style="padding-left: 20px; padding-right: 20px;">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left: Contact Details -->
                <div class="d-flex align-items-center flex-wrap" style="font-size: 13px;">
                    @if(setting('phone1'))
                        <a href="tel:{{ setting('phone1') }}" class="text-light text-decoration-none d-inline-flex align-items-center" style="margin-right: 24px;">
                            <i class="fa fa-phone text-warning" style="margin-right: 8px; margin-left: 2px; font-size: 14px;"></i> <span>{{ setting('phone1') }}</span>
                        </a>
                    @endif
                    @if(setting('email1'))
                        <a href="mailto:{{ setting('email1') }}" class="text-light text-decoration-none d-inline-flex align-items-center" style="margin-right: 24px;">
                            <i class="fa fa-envelope text-warning" style="margin-right: 8px; font-size: 14px;"></i> <span>{{ setting('email1') }}</span>
                        </a>
                    @endif
                    @if(setting('address1'))
                        <span class="text-light opacity-75 d-inline-flex align-items-center" style="margin-right: 24px;">
                            <i class="fa fa-map-marker text-warning" style="margin-right: 8px; font-size: 14px;"></i> <span>{{ setting('address1') }}</span>
                        </span>
                    @endif
                </div>

                <!-- Right: Styled Social Media Icons -->
                <div class="top-social-wrapper d-flex align-items-center" style="gap: 8px;">
                    <span class="text-light opacity-75 me-1 d-none d-lg-inline" style="font-size: 12px; font-weight: 500;">Follow Us:</span>
                    <a href="{{ setting('facbook_link') ?: 'https://facebook.com' }}" target="_blank" title="Facebook" class="top-social-btn fb">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="{{ setting('twitter_link') ?: 'https://twitter.com' }}" target="_blank" title="Twitter / X" class="top-social-btn tw">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="{{ setting('linkedin_link') ?: 'https://linkedin.com' }}" target="_blank" title="LinkedIn" class="top-social-btn in">
                        <i class="fa fa-linkedin"></i>
                    </a>
                    <a href="https://instagram.com" target="_blank" title="Instagram" class="top-social-btn insta">
                        <i class="fa fa-instagram"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" title="YouTube" class="top-social-btn yt">
                        <i class="fa fa-youtube-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ==========================================
         TOP ROW: BRAND LOGO + CENTER BIG SEARCH + ACTIONS
         (Desktop: Full Row | Mobile: Logo + Toggle Button Only)
         ========================================== -->
        <!-- ==========================================
         PREMIUM STICKY HEADER & NAVBAR CONTAINER
         (Desktop: Logo + Search + Auth + Full Menu | Mobile: Sticky Header)
         ========================================== -->
    <div class="ks-sticky-header-container" id="ksStickyHeaderContainer">
        <header class="ks-main-header">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <!-- Left: Logo (Loaded from Admin Panel Setting) -->
                <div class="d-flex align-items-center">
                    <a href="{{ route('/') }}" class="d-inline-block text-decoration-none" title="{{ setting('title') ?: 'Home' }}">
                        @php
                            $headerLogo = setting('logo');
                        @endphp
                        @if(!empty($headerLogo) && file_exists(public_path('upload/images/backend/logo/' . $headerLogo)))
                            <img src="{{ asset('upload/images/backend/logo/' . $headerLogo) }}" alt="{{ setting('title') ?: 'Logo' }}" class="ks-logo-img">
                        @else
                            <span class="brand-text-logo fw-bold" style="font-size: 22px; color: #ffffff; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa fa-leaf text-warning" style="font-size: 20px;"></i>
                                <span>{{ setting('title') ?: 'Jamuna Stock Market PLC' }}</span>
                            </span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Center: Big Pill Search Form with Live AJAX Dropdown -->
                <div class="ks-search-wrapper flex-grow-1 mx-lg-4 mx-md-2 d-none d-lg-block">
                    <form action="{{ route('frontend.search') }}" method="GET" class="ks-search-form" id="globalSearchForm">
                        <input type="text" name="search" id="globalSearchInput" class="ks-search-input" placeholder="{{ __('Search stocks, packages, cards...') }}" autocomplete="off" value="{{ request('search') }}" required>
                        <button type="submit" class="ks-search-btn" title="{{ __('Search') }}">
                            <i class="fa fa-search"></i> {{ __('Search') }}
                        </button>
                    </form>
                    <!-- Live Search Dropdown Popup -->
                    <div id="liveSearchResults" class="ks-live-search-dropdown shadow-lg d-none"></div>
                </div>

                <!-- Desktop Language Switcher (Default: ENG | Active: Golden Pill) -->
                @php
                    $currentLocale = app()->getLocale() ?: session('locale', 'en');
                @endphp
                <div class="ks-lang-switcher-wrap d-none d-lg-flex align-items-center ms-lg-3 me-lg-2">
                    <div class="btn-group p-1" style="background: rgba(0, 0, 0, 0.25); border: 1.5px solid rgba(255, 255, 255, 0.25); border-radius: 50px;">
                        <a href="{{ route('lang.switch', 'bn') }}" 
                           class="btn btn-sm px-3 py-1 rounded-pill" 
                           style="{{ $currentLocale === 'bn' ? 'background: linear-gradient(180deg, #ffe066 0%, #f59e0b 100%) !important; color: #000000 !important; font-weight: 800; box-shadow: 0 2px 6px rgba(0,0,0,0.3);' : 'color: #ffffff; opacity: 0.85; font-weight: 600;' }} font-size: 12px; text-decoration: none; transition: all 0.2s;">
                            বাংলা
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="btn btn-sm px-3 py-1 rounded-pill" 
                           style="{{ $currentLocale === 'en' ? 'background: linear-gradient(180deg, #ffe066 0%, #f59e0b 100%) !important; color: #000000 !important; font-weight: 800; box-shadow: 0 2px 6px rgba(0,0,0,0.3);' : 'color: #ffffff; opacity: 0.85; font-weight: 600;' }} font-size: 12px; text-decoration: none; transition: all 0.2s;">
                            ENG
                        </a>
                    </div>
                </div>

                <!-- Desktop Right: Login / Register / Dashboard (Separated with Spacing) -->
                <div class="ks-auth-actions-group d-none d-lg-flex align-items-center ms-lg-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="ks-btn-login" title="{{ __('Dashboard') }}">
                            <i class="fa fa-user-circle"></i> {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ks-btn-logout" title="{{ __('Logout') }}">
                            <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="ks-btn-login" title="{{ __('Login') }}">
                            <i class="fa fa-sign-in"></i> {{ __('Login') }}
                        </a>
                        <a href="{{ route('register') }}" class="ks-btn-register" title="{{ __('Register') }}">
                            <i class="fa fa-user-plus"></i> {{ __('Register') }}
                        </a>
                    @endauth
                </div>

                <!-- Mobile Only: Animated Hamburger Toggle Button on Top Right -->
                <button class="ks-mobile-toggle d-lg-none" id="ksMobileToggleBtn" type="button" onclick="openJamunaMobileDrawer()" aria-label="Toggle navigation">
                    <div class="ks-toggle-bars">
                        <span class="bar bar-1"></span>
                        <span class="bar bar-2"></span>
                        <span class="bar bar-3"></span>
                    </div>
                </button>
            </div>
        </div>
    </header>

        <!-- ==========================================
         ROW 2: JAMUNA GREEN NAVBAR (Desktop Only)
         ========================================== -->
    <nav class="ks-navbar-bar d-none d-lg-block">
        <div class="container">
            <div class="d-flex align-items-stretch justify-content-between flex-grow-1">

                <!-- Navigation Links -->
                <ul class="ks-nav-list flex-grow-1">
                    <!-- 1. Golden Home Button -->
                    <li class="ks-anim-item {{ Route::is('/') ? 'active' : '' }}">
                        <a href="{{ route('/') }}" class="ks-nav-link jamuna-home-btn" title="{{ __('Home') }}">
                            <i class="fa fa-home"></i> <span>{{ __('Home') }}</span>
                        </a>
                    </li>
                    <!-- 2. About Us (Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Route::is('about') || Route::is('corporate.*') ? 'active' : '' }}">
                        <a href="{{ route('about') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('About Us') }}">
                            <span><i class="fa fa-building text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('About Us') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow">
                            <a href="{{ route('about') }}">
                                <i class="fa fa-building"></i> {{ __('Company Profile & Vision') }}
                            </a>
                            <a href="{{ route('team.members') }}">
                                <i class="fa fa-users"></i> {{ __('Our Team Members') }}
                            </a>
                            <a href="{{ route('corporate.board') }}">
                                <i class="fa fa-user-tie"></i> {{ __('Board of Directors & Management') }}
                            </a>
                            <a href="{{ route('corporate.speeches') }}">
                                <i class="fa fa-comment-dots"></i> {{ __('Leadership Messages') }}
                            </a>
                            <a href="{{ route('career') }}">
                                <i class="fa fa-briefcase"></i> {{ __('Career & Circulars') }}
                            </a>
                        </div>
                    </li>

                    <!-- 3. Team Members -->
                    <li class="ks-anim-item {{ Route::is('team.members') || Route::is('our.team') ? 'active' : '' }}">
                        <a href="{{ route('team.members') }}" class="ks-nav-link" title="{{ __('Our Team') }}">
                            <span><i class="fa fa-users text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Our Team') }}</span>
                        </a>
                    </li>

                    <!-- 4. Our Products (Links to Stock Market) -->
                    <li class="ks-anim-item {{ Route::is('frontend.stocks') ? 'active' : '' }}">
                        <a href="{{ route('frontend.stocks') }}" class="ks-nav-link" title="{{ __('Our Products') }}">
                            <span><i class="fa fa-shopping-basket text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Our Products') }}</span>
                        </a>
                    </li>

                    <!-- 5. Our Projects (Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Route::is('corporate.activities*') ? 'active' : '' }}">
                        <a href="{{ route('corporate.activities') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('Our Projects') }}">
                            <span><i class="fa fa-tasks text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Our Projects') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow">
                            <a href="{{ route('corporate.activities') }}">
                                <i class="fa fa-tasks"></i> {{ __('All Projects & Activities') }}
                            </a>
                            <a href="{{ route('corporate.activities', ['category' => 'ongoing']) }}">
                                <i class="fa fa-spinner"></i> {{ __('Ongoing Projects') }}
                            </a>
                            <a href="{{ route('corporate.activities', ['category' => 'completed']) }}">
                                <i class="fa fa-check-circle"></i> {{ __('Completed Projects') }}
                            </a>
                            <a href="{{ route('corporate.activities', ['category' => 'planned']) }}">
                                <i class="fa fa-calendar-alt"></i> {{ __('Upcoming Projects') }}
                            </a>
                        </div>
                    </li>

                    <!-- 6. Investment Sectors (9 Sectors Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Request::is('investment*') ? 'active' : '' }}">
                        <a href="{{ url('/investments/showcase') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('Investment Sectors') }}">
                            <span><i class="fa fa-cubes text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Investment Sectors') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow" style="min-width: 290px;">
                            <a href="{{ url('/investments/showcase') }}" class="fw-bold border-bottom pb-2 mb-1 text-success">
                                <i class="fa fa-th-large text-warning"></i> {{ __('Overview of 9 Sectors') }}
                            </a>
                            <a href="{{ url('/investment/fmcg') }}">
                                <i class="fa fa-shopping-basket text-success"></i> {{ __('01. Consumer Goods (FMCG)') }}
                            </a>
                            <a href="{{ url('/investment/real-estate') }}">
                                <i class="fa fa-building text-primary"></i> {{ __('02. Housing / Real Estate') }}
                            </a>
                            <a href="{{ url('/investment/fisheries') }}">
                                <i class="fa fa-fish text-info"></i> {{ __('03. Fisheries') }}
                            </a>
                            <a href="{{ url('/investment/livestock') }}">
                                <i class="fa fa-paw text-warning"></i> {{ __('04. Livestock Farming') }}
                            </a>
                            <a href="{{ url('/investment/farmhouse') }}">
                                <i class="fa fa-home text-success"></i> {{ __('05. Farmhouse') }}
                            </a>
                            <a href="{{ url('/investment/plot-trading') }}">
                                <i class="fa fa-map-marked-alt text-danger"></i> {{ __('06. Plot Buying & Selling') }}
                            </a>
                            <a href="{{ url('/investment/resort') }}">
                                <i class="fa fa-umbrella-beach text-teal"></i> {{ __('07. Resort') }}
                            </a>
                            <a href="{{ url('/investment/priority-investment') }}">
                                <i class="fa fa-star text-warning"></i> {{ __('08. Priority-Based Investment') }}
                            </a>
                            <a href="{{ url('/investment/import-export') }}">
                                <i class="fa fa-ship text-primary"></i> {{ __('09. Import & Export') }}
                            </a>
                        </div>
                    </li>

                    <!-- 7. Investor Relations (Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Route::is('investments.*') || Route::is('shareholders.*') ? 'active' : '' }}">
                        <a href="{{ route('investments.sectors') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('Investor Relations') }}">
                            <span><i class="fa fa-line-chart text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Investor Relations') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow">
                            <a href="{{ route('investments.sectors') }}">
                                <i class="fa fa-chart-pie"></i> {{ __('Top 10 Investment Sectors') }}
                            </a>
                            <a href="{{ route('investments.projects') }}">
                                <i class="fa fa-seedling"></i> {{ __('Active Investment Projects') }}
                            </a>
                            <a href="{{ route('shareholders.index') }}">
                                <i class="fa fa-user-shield"></i> {{ __('Shareholder Profile & Rules') }}
                            </a>
                            <a href="{{ route('shareholders.partnerships') }}">
                                <i class="fa fa-handshake"></i> {{ __('Institutional Partnerships') }}
                            </a>
                        </div>
                    </li>

                    <!-- 8. Membership (Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Route::is('membership.*') ? 'active' : '' }}">
                        <a href="{{ route('membership.cards') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('Membership') }}">
                            <span><i class="fa fa-id-card text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Membership') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow">
                            <a href="{{ route('membership.cards') }}#sme-card">
                                <i class="fa fa-id-card"></i> {{ __('Jamuna SME Card') }}
                            </a>
                            <a href="{{ route('membership.cards') }}#gold-card">
                                <i class="fa fa-crown"></i> {{ __('Jamuna SME Gold Card') }}
                            </a>
                            <a href="{{ route('membership.cards') }}#property-card">
                                <i class="fa fa-home"></i> {{ __('Jamuna Property Card') }}
                            </a>
                        </div>
                    </li>

                    <!-- 9. Career -->
                    <li class="ks-anim-item {{ Route::is('career') ? 'active' : '' }}">
                        <a href="{{ route('career') }}" class="ks-nav-link" title="{{ __('Career') }}">
                            <span><i class="fa fa-briefcase text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Career') }}</span>
                        </a>
                    </li>

                    <!-- 10. Media Center (Dropdown) -->
                    <li class="ks-anim-item ks-dropdown {{ Route::is('notices.*') || Route::is('gallery') || Route::is('videos.*') ? 'active' : '' }}">
                        <a href="{{ route('notices.index') }}" class="ks-nav-link" data-mobile-toggle="dropdown" title="{{ __('Media Center') }}">
                            <span><i class="fa fa-bullhorn text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Media Center') }}</span>
                            <i class="fa fa-angle-down ks-dropdown-arrow ms-1 small" style="opacity: 0.85;"></i>
                        </a>
                        <div class="ks-dropdown-menu shadow">
                            <a href="{{ route('notices.index') }}">
                                <i class="fa fa-bullhorn"></i> {{ __('Notices & Circular Board') }}
                            </a>
                            <a href="{{ route('gallery') }}">
                                <i class="fa fa-images"></i> {{ __('Official Photo Gallery') }}
                            </a>
                            <a href="{{ route('videos.index') }}">
                                <i class="fa fa-youtube-play text-danger"></i> আমাদের অফিসিয়াল ভিডিও
                            </a>
                        </div>
                    </li>

                    <!-- 11. Photo Gallery -->
                    <li class="ks-anim-item {{ Route::is('gallery') ? 'active' : '' }}">
                        <a href="{{ route('gallery') }}" class="ks-nav-link" title="{{ __('Gallery') }}">
                            <span><i class="fa fa-images text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Gallery') }}</span>
                        </a>
                    </li>

                    <!-- 12. Blog -->
                    <li class="ks-anim-item {{ Request::is('blog*') ? 'active' : '' }}">
                        <a href="{{ route('blogs.index') }}" class="ks-nav-link" title="{{ __('Blog') }}">
                            <span><i class="fa fa-newspaper-o text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Blog') }}</span>
                        </a>
                    </li>

                    <!-- 13. Contact Us -->
                    <li class="ks-anim-item {{ Route::is('contact') ? 'active' : '' }}">
                        <a href="{{ route('contact') }}" class="ks-nav-link" title="{{ __('Contact Us') }}">
                            <span><i class="fa fa-envelope text-warning me-2 d-lg-none" style="font-size: 15px;"></i>{{ __('Contact Us') }}</span>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    </div>
    <!-- Invisible Spacer for Fixed Sticky Header to Prevent Layout Jitter -->
    <div id="ksStickyHeaderSpacer" style="display: none;"></div>

    <!-- ==========================================
         OFF-CANVAS MOBILE DRAWER & BACKDROP
         ========================================== -->
    <div class="ks-drawer-backdrop d-lg-none" id="ksDrawerBackdrop" onclick="closeJamunaMobileDrawer()"></div>

    <div class="ks-mobile-drawer d-lg-none" id="ksMobileDrawer">
        <!-- Drawer Header with Logo & Close Button -->
        <div class="ks-drawer-header d-flex align-items-center justify-content-between p-3" style="background: rgba(0, 0, 0, 0.35); border-bottom: 1px solid rgba(255, 255, 255, 0.1);">
            <a href="{{ route('/') }}" class="d-inline-flex align-items-center text-decoration-none">
                @if(!empty($headerLogo) && file_exists(public_path('upload/images/backend/logo/' . $headerLogo)))
                    <img src="{{ asset('upload/images/backend/logo/' . $headerLogo) }}" alt="{{ setting('title') ?: 'Logo' }}" style="max-height: 38px; max-width: 170px; object-fit: contain;">
                @else
                    <span class="fw-bold text-white fs-18"><i class="fa fa-leaf text-warning me-1"></i> {{ setting('title') ?: 'Jamuna Stock' }}</span>
                @endif
            </a>
            <button type="button" class="ks-drawer-close-btn" onclick="closeJamunaMobileDrawer()" aria-label="Close menu">
                <i class="fa fa-times"></i>
            </button>
        </div>

        <!-- Drawer Search Box -->
        <div class="p-3" style="background: rgba(0,0,0,0.18); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-white-50 small fw-bold"><i class="fa fa-language text-warning me-1"></i> {{ __('Language') ?? 'Language' }}</span>
                <div class="btn-group p-1" style="background: rgba(0, 0, 0, 0.35); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 50px;">
                    <a href="{{ route('lang.switch', 'bn') }}" 
                       class="btn btn-sm px-3 py-1 rounded-pill" 
                       style="{{ $currentLocale === 'bn' ? 'background: linear-gradient(180deg, #ffe066 0%, #f59e0b 100%) !important; color: #000000 !important; font-weight: 800;' : 'color: #ffffff; opacity: 0.85; font-weight: 600;' }} font-size: 11px; text-decoration: none;">
                        বাংলা
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="btn btn-sm px-3 py-1 rounded-pill" 
                       style="{{ $currentLocale === 'en' ? 'background: linear-gradient(180deg, #ffe066 0%, #f59e0b 100%) !important; color: #000000 !important; font-weight: 800;' : 'color: #ffffff; opacity: 0.85; font-weight: 600;' }} font-size: 11px; text-decoration: none;">
                        ENG
                    </a>
                </div>
            </div>
            <form action="{{ route('frontend.search') }}" method="GET" class="position-relative">
                <input type="text" name="search" class="form-control rounded-pill pe-5 ps-3" placeholder="{{ __('Search stocks, packages, cards...') }}" style="background: rgba(255,255,255,0.95); border: 1px solid #f59e0b; font-size: 14px; height: 42px;">
                <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-1 rounded-circle" style="background: #0c5c2d; color: #fff; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center; border: none;">
                    <i class="fa fa-search" style="font-size: 13px;"></i>
                </button>
            </form>
        </div>

        <!-- User / Auth Quick Buttons -->
        <div class="p-3" style="background: rgba(0,0,0,0.25); border-bottom: 1px solid rgba(255, 255, 255, 0.08);">
            @auth
                <div class="d-flex align-items-center justify-content-between">
                    <span class="text-white-50 small"><i class="fa fa-user me-1 text-warning"></i> {{ Auth::user()->name ?: 'User' }}</span>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-warning text-dark fw-bold rounded-pill px-3 py-1" style="font-size: 13px;">
                            <i class="fa fa-tachometer-alt me-1"></i> {{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-drawer').submit();" class="btn btn-sm btn-outline-light rounded-pill px-2 py-1" style="font-size: 13px;" title="{{ __('Logout') }}">
                            <i class="fa fa-sign-out"></i>
                        </a>
                    </div>
                </div>
                <form id="logout-form-drawer" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            @else
                <div class="d-flex gap-2">
                    <a href="{{ route('login') }}" class="btn btn-sm text-dark fw-bold rounded-pill w-50 py-2 d-flex align-items-center justify-content-center" style="font-size: 14px; background: linear-gradient(135deg, #f59e0b, #fbbf24); border: none;">
                        <i class="fa fa-sign-in me-1"></i> {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-outline-light rounded-pill w-50 py-2 d-flex align-items-center justify-content-center" style="font-size: 14px; border: 1.5px solid rgba(255,255,255,0.4);">
                        <i class="fa fa-user-plus me-1"></i> {{ __('Register') }}
                    </a>
                </div>
            @endauth
        </div>

        <!-- Drawer Navigation Links -->
        <div class="ks-drawer-nav-body flex-grow-1 p-2" style="overflow-y: auto;">
            <ul class="list-unstyled mb-0 ks-drawer-menu-list">
                <!-- 1. Home -->
                <li>
                    <a href="{{ route('/') }}" class="ks-drawer-link {{ Route::is('/') ? 'active' : '' }}">
                        <span><i class="fa fa-home me-2 text-warning"></i> {{ __('Home') }}</span>
                    </a>
                </li>

                <!-- 2. About Us (Accordion) -->
                <li class="ks-drawer-dropdown {{ Route::is('about') || Route::is('corporate.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-info-circle me-2 text-warning"></i> {{ __('About Us') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ route('about') }}"><i class="fa fa-building me-2"></i> {{ __('Company Profile & Vision') }}</a></li>
                        <li><a href="{{ route('team.members') }}"><i class="fa fa-users me-2"></i> {{ __('Our Team Members') }}</a></li>
                        <li><a href="{{ route('corporate.board') }}"><i class="fa fa-user-tie me-2"></i> {{ __('Board of Directors & Management') }}</a></li>
                        <li><a href="{{ route('corporate.speeches') }}"><i class="fa fa-comment-dots me-2"></i> {{ __('Leadership Messages') }}</a></li>
                        <li><a href="{{ route('career') }}"><i class="fa fa-briefcase me-2"></i> {{ __('Career & Circulars') }}</a></li>
                    </ul>
                </li>

                <!-- 3. Our Team -->
                <li>
                    <a href="{{ route('team.members') }}" class="ks-drawer-link {{ Route::is('team.members') ? 'active' : '' }}">
                        <span><i class="fa fa-users me-2 text-warning"></i> {{ __('Our Team') }}</span>
                        <span class="badge bg-warning text-dark small" style="font-size: 10px; font-weight: 700;">PRO</span>
                    </a>
                </li>

                <!-- 4. Our Products (Links to Stock Market) -->
                <li>
                    <a href="{{ route('frontend.stocks') }}" class="ks-drawer-link {{ Route::is('frontend.stocks') ? 'active' : '' }}">
                        <span><i class="fa fa-shopping-basket me-2 text-warning"></i> {{ __('Our Products') }}</span>
                    </a>
                </li>

                <!-- 5. Our Projects (Accordion) -->
                <li class="ks-drawer-dropdown {{ Route::is('corporate.activities*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-briefcase me-2 text-warning"></i> {{ __('Our Projects') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ route('corporate.activities') }}"><i class="fa fa-tasks me-2"></i> {{ __('All Projects & Activities') }}</a></li>
                        <li><a href="{{ route('corporate.activities', ['category' => 'ongoing']) }}"><i class="fa fa-spinner me-2"></i> {{ __('Ongoing Projects') }}</a></li>
                        <li><a href="{{ route('corporate.activities', ['category' => 'completed']) }}"><i class="fa fa-check-circle me-2"></i> {{ __('Completed Projects') }}</a></li>
                        <li><a href="{{ route('corporate.activities', ['category' => 'planned']) }}"><i class="fa fa-calendar me-2"></i> {{ __('Upcoming Projects') }}</a></li>
                    </ul>
                </li>

                <!-- 5.5 Investment Sectors (9 Sectors Dropdown) -->
                <li class="ks-drawer-dropdown {{ Request::is('investment*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-cubes me-2 text-warning"></i> {{ __('Investment Sectors') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ url('/investments/showcase') }}"><i class="fa fa-th-large me-2"></i> {{ __('Overview of 9 Sectors') }}</a></li>
                        <li><a href="{{ url('/investment/fmcg') }}"><i class="fa fa-shopping-basket me-2"></i> {{ __('01. Consumer Goods (FMCG)') }}</a></li>
                        <li><a href="{{ url('/investment/real-estate') }}"><i class="fa fa-building me-2"></i> {{ __('02. Housing / Real Estate') }}</a></li>
                        <li><a href="{{ url('/investment/fisheries') }}"><i class="fa fa-fish me-2"></i> {{ __('03. Fisheries') }}</a></li>
                        <li><a href="{{ url('/investment/livestock') }}"><i class="fa fa-paw me-2"></i> {{ __('04. Livestock Farming') }}</a></li>
                        <li><a href="{{ url('/investment/farmhouse') }}"><i class="fa fa-home me-2"></i> {{ __('05. Farmhouse') }}</a></li>
                        <li><a href="{{ url('/investment/plot-trading') }}"><i class="fa fa-map-marked-alt me-2"></i> {{ __('06. Plot Buying & Selling') }}</a></li>
                        <li><a href="{{ url('/investment/resort') }}"><i class="fa fa-umbrella-beach me-2"></i> {{ __('07. Resort') }}</a></li>
                        <li><a href="{{ url('/investment/priority-investment') }}"><i class="fa fa-star me-2"></i> {{ __('08. Priority-Based Investment') }}</a></li>
                        <li><a href="{{ url('/investment/import-export') }}"><i class="fa fa-ship me-2"></i> {{ __('09. Import & Export') }}</a></li>
                    </ul>
                </li>

                <!-- 6. Investor Relations (Accordion) -->
                <li class="ks-drawer-dropdown {{ Route::is('investments.*') || Route::is('shareholders.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-line-chart me-2 text-warning"></i> {{ __('Investor Relations') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ route('investments.sectors') }}"><i class="fa fa-pie-chart me-2"></i> {{ __('Top 10 Investment Sectors') }}</a></li>
                        <li><a href="{{ route('investments.projects') }}"><i class="fa fa-tree me-2"></i> {{ __('Active Investment Projects') }}</a></li>
                        <li><a href="{{ route('shareholders.index') }}"><i class="fa fa-user-secret me-2"></i> {{ __('Shareholder Profile & Rules') }}</a></li>
                        <li><a href="{{ route('shareholders.partnerships') }}"><i class="fa fa-handshake-o me-2"></i> {{ __('Institutional Partnerships') }}</a></li>
                    </ul>
                </li>

                <!-- 7. Membership (Accordion) -->
                <li class="ks-drawer-dropdown {{ Route::is('membership.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-id-card me-2 text-warning"></i> {{ __('Membership') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ route('membership.cards') }}#sme-card"><i class="fa fa-id-card me-2"></i> {{ __('Jamuna SME Card') }}</a></li>
                        <li><a href="{{ route('membership.cards') }}#gold-card"><i class="fa fa-trophy me-2"></i> {{ __('Jamuna SME Gold Card') }}</a></li>
                        <li><a href="{{ route('membership.cards') }}#property-card"><i class="fa fa-home me-2"></i> {{ __('Jamuna Property Card') }}</a></li>
                    </ul>
                </li>

                <!-- 8. Career -->
                <li>
                    <a href="{{ route('career') }}" class="ks-drawer-link {{ Route::is('career') ? 'active' : '' }}">
                        <span><i class="fa fa-briefcase me-2 text-warning"></i> {{ __('Career') }}</span>
                    </a>
                </li>

                <!-- 9. Media Center (Accordion) -->
                <li class="ks-drawer-dropdown {{ Route::is('notices.*') || Route::is('gallery') || Route::is('videos.*') ? 'active' : '' }}">
                    <a href="javascript:void(0)" class="ks-drawer-link d-flex align-items-center justify-content-between" onclick="toggleDrawerDropdown(this)">
                        <span><i class="fa fa-bullhorn me-2 text-warning"></i> {{ __('Media Center') }}</span>
                        <i class="fa fa-angle-down ks-dd-chevron"></i>
                    </a>
                    <ul class="ks-drawer-sub-menu list-unstyled">
                        <li><a href="{{ route('notices.index') }}"><i class="fa fa-bullhorn me-2"></i> {{ __('Notices & Circular Board') }}</a></li>
                        <li><a href="{{ route('gallery') }}"><i class="fa fa-images me-2"></i> {{ __('Official Photo Gallery') }}</a></li>
                        <li><a href="{{ route('videos.index') }}"><i class="fa fa-youtube-play me-2 text-danger"></i> আমাদের অফিসিয়াল ভিডিও</a></li>
                    </ul>
                </li>

                <!-- 10. Photo Gallery -->
                <li>
                    <a href="{{ route('gallery') }}" class="ks-drawer-link {{ Route::is('gallery') ? 'active' : '' }}">
                        <span><i class="fa fa-images me-2 text-warning"></i> {{ __('Gallery') }}</span>
                    </a>
                </li>

                <!-- 11. Blog -->
                <li>
                    <a href="{{ route('blogs.index') }}" class="ks-drawer-link {{ Request::is('blog*') ? 'active' : '' }}">
                        <span><i class="fa fa-newspaper-o me-2 text-warning"></i> {{ __('Blog') }}</span>
                    </a>
                </li>

                <!-- 12. Contact Us -->
                <li>
                    <a href="{{ route('contact') }}" class="ks-drawer-link {{ Route::is('contact') ? 'active' : '' }}">
                        <span><i class="fa fa-envelope me-2 text-warning"></i> {{ __('Contact Us') }}</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Drawer Footer -->
        <div class="p-3 mt-auto" style="background: rgba(0, 0, 0, 0.5); border-top: 1px solid rgba(255, 255, 255, 0.12);">
            @if(setting('phone1'))
            <div class="mb-2">
                <a href="tel:{{ setting('phone1') }}" class="text-white text-decoration-none small d-flex align-items-center" style="font-size: 13.5px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(245, 158, 11, 0.15); margin-right: 10px; flex-shrink: 0;">
                        <i class="fa fa-phone text-warning" style="font-size: 13px;"></i>
                    </span>
                    <span>{{ setting('phone1') }}</span>
                </a>
            </div>
            @endif
            @if(setting('email1'))
            <div class="mb-2">
                <a href="mailto:{{ setting('email1') }}" class="text-white text-decoration-none small d-flex align-items-center" style="font-size: 13.5px;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: rgba(245, 158, 11, 0.15); margin-right: 10px; flex-shrink: 0;">
                        <i class="fa fa-envelope text-warning" style="font-size: 13px;"></i>
                    </span>
                    <span>{{ setting('email1') }}</span>
                </a>
            </div>
            @endif
            <div class="d-flex align-items-center mt-3 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <span class="text-white-50 small" style="margin-right: 12px; font-size: 12px; font-weight: 600;">{{ __('Follow Us') ?? 'Follow Us' }}:</span>
                <div class="d-flex align-items-center">
                    <a href="{{ setting('facbook_link') ?: 'https://facebook.com' }}" target="_blank" title="Facebook" style="display: inline-flex; width: 34px; height: 34px; border-radius: 50%; align-items: center; justify-content: center; background: #1877f2; color: #ffffff !important; margin-right: 8px; text-decoration: none; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="{{ setting('twitter_link') ?: 'https://twitter.com' }}" target="_blank" title="Twitter / X" style="display: inline-flex; width: 34px; height: 34px; border-radius: 50%; align-items: center; justify-content: center; background: #1da1f2; color: #ffffff !important; margin-right: 8px; text-decoration: none; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="{{ setting('linkedin_link') ?: 'https://linkedin.com' }}" target="_blank" title="LinkedIn" style="display: inline-flex; width: 34px; height: 34px; border-radius: 50%; align-items: center; justify-content: center; background: #0a66c2; color: #ffffff !important; margin-right: 8px; text-decoration: none; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <i class="fa fa-linkedin"></i>
                    </a>
                    <a href="https://youtube.com" target="_blank" title="YouTube" style="display: inline-flex; width: 34px; height: 34px; border-radius: 50%; align-items: center; justify-content: center; background: #ff0000; color: #ffffff !important; text-decoration: none; font-size: 14px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <i class="fa fa-youtube-play"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>



    @yield("content")
    <!-- Footer Main -->
    <footer class="container-fluid no-left-padding no-right-padding footer-main footer-section2">
        <!-- Main Footer Widgets (4 Columns) -->
        <div class="container-fluid no-left-padding no-right-padding footer-widget" style="background: linear-gradient(180deg, #093c1d 0%, #052611 100%); padding-top: 55px; padding-bottom: 35px; border-top: 3px solid #f59e0b;">
            <div class="container">
                <div class="row">
                    <!-- Column 1: About & Logo -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_about">
                            <a class="ftr-logo d-inline-block mb-3 text-decoration-none" href="{{ route('/') }}" title="{{ setting('title') ?: 'Home' }}">
                                @php
                                    $footerLogo = setting('logo');
                                @endphp
                                @if(!empty($footerLogo) && file_exists(public_path('upload/images/backend/logo/' . $footerLogo)))
                                    <img src="{{ asset('upload/images/backend/logo/' . $footerLogo) }}" alt="{{ setting('title') ?: 'Jamuna Stock Market PLC' }}" style="max-height: 65px; width: auto; max-width: 100%; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));">
                                @else
                                    <span class="fw-bold" style="font-size: 20px; color: #f59e0b; letter-spacing: 0.5px; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa fa-leaf text-success"></i> {{ setting('title') ?: 'Jamuna Stock Market PLC' }}
                                    </span>
                                @endif
                            </a>
                            <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-top: 15px; margin-bottom: 20px;">
                                {{ __('Jamuna Stock Market PLC - A trusted platform for sustainable development and shared prosperity through public participation.') }}
                            </p>
                        </aside>
                    </div>

                    <!-- Column 2: Quick Links -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_links">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #f59e0b; display: inline-block; padding-bottom: 5px;">{{ __('Quick Links') }}</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                <li style="margin-bottom: 8px;"><a href="{{ route('/') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('Home') }}</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('about') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('About Us') }}</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('team.members') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('Our Team') }}</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('frontend.stocks') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('Our Products') }}</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('career') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('Career') }}</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('privacy') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> Privacy Policy</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('terms') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> Terms & Conditions</a></li>
                                <li style="margin-bottom: 8px;"><a href="{{ route('contact') }}" style="color: #cbd5e1; text-decoration: none; font-size: 14px; transition: color 0.2s;"><i class="fa fa-angle-right text-warning me-2"></i> {{ __('Contact Us') }}</a></li>
                            </ul>
                        </aside>
                    </div>

                    <!-- Column 3: Contact Info & Support -->
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4 mb-lg-0">
                        <aside class="widget widget_contact_info">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #f59e0b; display: inline-block; padding-bottom: 5px;">{{ __('Contact Details') }}</h3>
                            <ul style="list-style: none; padding: 0; margin: 0; color: #cbd5e1; font-size: 14px;">
                                <li style="margin-bottom: 12px; display: flex; align-items: flex-start;">
                                    <i class="fa fa-map-marker text-warning me-2" style="font-size: 16px; margin-top: 3px; margin-right: 10px;"></i>
                                    <span>{{ setting('address1') }} {{ setting('address2') }}</span>
                                </li>
                                <li style="margin-bottom: 12px; display: flex; align-items: center;">
                                    <i class="fa fa-phone text-warning me-2" style="font-size: 16px; margin-right: 10px;"></i>
                                    <span>{{ setting('phone1') }} / {{ setting('phone2') }}</span>
                                </li>
                                <li style="margin-bottom: 12px; display: flex; align-items: center;">
                                    <i class="fa fa-envelope text-warning me-2" style="font-size: 16px; margin-right: 10px;"></i>
                                    <span>{{ setting('email1') }}</span>
                                </li>
                            </ul>
                        </aside>
                    </div>

                    <!-- Column 4: Social Media Icons -->
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <aside class="widget widget_social_media">
                            <h3 class="widget-title" style="color: #fff; font-size: 18px; font-weight: 700; margin-bottom: 20px; border-bottom: 2px solid #f59e0b; display: inline-block; padding-bottom: 5px;">Social Media</h3>
                            <p style="color: #a0aab5; font-size: 13.5px; line-height: 1.5; margin-bottom: 18px;">Connect with us on social media and stay updated with our latest news.</p>
                            
                            <style>
                                .ftr-social-icons {
                                    display: flex;
                                    align-items: center;
                                    gap: 12px;
                                    flex-wrap: wrap;
                                    padding: 0;
                                    margin: 0;
                                }
                                .ftr-social-icon-btn {
                                    width: 42px;
                                    height: 42px;
                                    display: inline-flex;
                                    align-items: center;
                                    justify-content: center;
                                    border-radius: 50%;
                                    color: #fff !important;
                                    font-size: 18px;
                                    text-decoration: none !important;
                                    box-shadow: 0 4px 10px rgba(0,0,0,0.25);
                                    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                                }
                                .ftr-social-icon-btn:hover {
                                    transform: translateY(-4px) scale(1.08);
                                }
                                .ftr-social-icon-btn.fb { background: #1877f2; }
                                .ftr-social-icon-btn.fb:hover { box-shadow: 0 6px 15px rgba(24, 119, 242, 0.45); }
                                
                                .ftr-social-icon-btn.insta { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
                                .ftr-social-icon-btn.insta:hover { box-shadow: 0 6px 15px rgba(214, 36, 159, 0.45); }
                                
                                .ftr-social-icon-btn.tw { background: #1da1f2; }
                                .ftr-social-icon-btn.tw:hover { box-shadow: 0 6px 15px rgba(29, 161, 242, 0.45); }
                                
                                .ftr-social-icon-btn.in { background: #0a66c2; }
                                .ftr-social-icon-btn.in:hover { box-shadow: 0 6px 15px rgba(10, 102, 194, 0.45); }

                                .ftr-social-icon-btn.yt { background: #ff0000; }
                                .ftr-social-icon-btn.yt:hover { box-shadow: 0 6px 15px rgba(255, 0, 0, 0.45); }
                            </style>

                            <div class="ftr-social-icons">
                                @if(setting('facbook_link'))
                                <a href="{{ setting('facbook_link') }}" target="_blank" class="ftr-social-icon-btn fb" title="Facebook">
                                    <i class="fa fa-facebook"></i>
                                </a>
                                @endif

                                <a href="https://instagram.com" target="_blank" class="ftr-social-icon-btn insta" title="Instagram">
                                    <i class="fa fa-instagram"></i>
                                </a>

                                @if(setting('twitter_link'))
                                <a href="{{ setting('twitter_link') }}" target="_blank" class="ftr-social-icon-btn tw" title="Twitter">
                                    <i class="fa fa-twitter"></i>
                                </a>
                                @endif

                                @if(setting('linkedin_link'))
                                <a href="{{ setting('linkedin_link') }}" target="_blank" class="ftr-social-icon-btn in" title="LinkedIn">
                                    <i class="fa fa-linkedin"></i>
                                </a>
                                @endif

                                <a href="https://youtube.com" target="_blank" class="ftr-social-icon-btn yt" title="YouTube">
                                    <i class="fa fa-youtube-play"></i>
                                </a>
                            </div>
                        </aside>
                    </div>

                </div>
            </div>
        </div><!-- Top Footer /- -->

        <!-- Bottom Footer -->
        <div class="container-fluid no-left-padding no-right-padding bottom-footer" style="background: #03170a; padding: 18px 0; border-top: 1px solid rgba(255,255,255,0.08);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p style="color: #94a3b8; font-size: 14px; margin: 0;">
                            © {{ date('Y') }} <a href="{{ route('/') }}" style="color: #fbb03b; text-decoration: none; font-weight: 600;">Jamuna Stock Market PLC</a>. All Rights Reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-center text-md-end mt-2 mt-md-0">
                        <p style="color: #86efac; font-size: 13px; margin: 0;">
                            Production through Participation • Self-Reliance • Sustainable Development
                        </p>
                    </div>
                </div>
            </div>
        </div><!-- Bottom Footer /- -->

    </footer>
    <!-- Footer Main /- -->

    <!-- JQuery v1.12.4 -->
    <script src="{{ asset('frontend/assets') }}/js/jquery-1.12.4.min.js"></script>

    <!-- Library - Js -->
    <script src="{{ asset('frontend/assets') }}/js/popper.min.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/lib.js"></script>
    <script src="{{ asset('frontend/assets') }}/js/jquery.flagstrap.min.js"></script>

    <!-- REVOLUTION JS FILES -->
    <script type="text/javascript" src="{{ asset('frontend/assets') }}/revolution/js/jquery.themepunch.tools.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/jquery.themepunch.revolution.min.js"></script>

    <!-- SLIDER REVOLUTION 5.0 EXTENSIONS  (Load Extensions only on Local File Systems !  The following part can be removed on Server for On Demand Loading) -->
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.layeranimation.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.migration.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.navigation.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.slideanims.min.js">
    </script>
    <script type="text/javascript"
        src="{{ asset('frontend/assets') }}/revolution/js/extensions/revolution.extension.video.min.js"></script>

    <!-- Library - Theme JS -->
    <script src="{{ asset('frontend/assets') }}/js/functions.js"></script>

    <!-- Global Live Search AJAX Script (Desktop & Mobile) -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupLiveSearch(inputId, resultsId) {
                const searchInput = document.getElementById(inputId);
                const searchResults = document.getElementById(resultsId);
                let debounceTimer;

                if (!searchInput || !searchResults) return;

                searchInput.addEventListener('input', function () {
                    const query = this.value.trim();
                    clearTimeout(debounceTimer);

                    if (query.length < 1) {
                        searchResults.innerHTML = '';
                        searchResults.classList.add('d-none');
                        return;
                    }

                    debounceTimer = setTimeout(function () {
                        fetch('{{ route("api.live_search") }}?q=' + encodeURIComponent(query))
                            .then(function (res) { return res.json(); })
                            .then(function (data) {
                                if (!Array.isArray(data) || data.length === 0) {
                                    searchResults.innerHTML = '<div class="p-3 text-center text-muted" style="font-size: 13.5px;"><i class="fa fa-info-circle me-1"></i> No results found for "' + query + '"</div>';
                                    searchResults.classList.remove('d-none');
                                    return;
                                }

                                let html = '';
                                data.forEach(function (item) {
                                    html += '<a href="' + item.url + '" class="ks-live-item">' +
                                        '<img src="' + item.image + '" alt="' + item.title + '" class="ks-live-thumb">' +
                                        '<div class="ks-live-info">' +
                                            '<div class="ks-live-title">' + item.title + '</div>' +
                                            '<div class="ks-live-meta">' +
                                                '<span class="badge ' + item.badge_class + '" style="font-size: 10px;">' + item.type + '</span>' +
                                                '<span>' + item.subtitle + '</span>' +
                                            '</div>' +
                                        '</div>' +
                                        (item.price ? '<div class="ks-live-price">' + item.price + '</div>' : '') +
                                    '</a>';
                                });

                                html += '<div class="p-2 text-center border-top bg-light">' +
                                    '<a href="{{ route("frontend.search") }}?search=' + encodeURIComponent(query) + '" class="btn btn-sm btn-link text-success fw-bold text-decoration-none" style="font-size: 13px;">' +
                                        'View all results for "' + query + '" <i class="fa fa-arrow-right ms-1"></i>' +
                                    '</a>' +
                                '</div>';

                                searchResults.innerHTML = html;
                                searchResults.classList.remove('d-none');
                            })
                            .catch(function (err) {
                                console.error('Search error:', err);
                            });
                    }, 250);
                });

                // Hide dropdown on click outside
                document.addEventListener('click', function (e) {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('d-none');
                    }
                });

                // Show dropdown if input is focused and has content
                searchInput.addEventListener('focus', function () {
                    if (this.value.trim().length >= 1 && searchResults.children.length > 0) {
                        searchResults.classList.remove('d-none');
                    }
                });
            }

            setupLiveSearch('globalSearchInput', 'liveSearchResults');
            setupLiveSearch('mobileSearchInput', 'mobileSearchResults');

            // ----------------------------------------------------
            // Bulletproof Desktop Navbar Dropdowns (Always Open & Show on Hover)
            // ----------------------------------------------------
            
            // ----------------------------------------------------
            // Smooth Sticky Header & Navbar Behavior (Fixed on Scroll with Zero Layout Shift)
            // ----------------------------------------------------
            var stickyHeader = document.getElementById('ksStickyHeaderContainer');
            var stickySpacer = document.getElementById('ksStickyHeaderSpacer');
            if (stickyHeader) {
                var updateStickyState = function () {
                    if (window.scrollY > 40) {
                        if (stickySpacer) {
                            stickySpacer.style.height = stickyHeader.offsetHeight + 'px';
                            stickySpacer.style.display = 'block';
                        }
                        stickyHeader.classList.add('is-sticky');
                    } else {
                        stickyHeader.classList.remove('is-sticky');
                        if (stickySpacer) {
                            stickySpacer.style.display = 'none';
                        }
                    }
                };
                window.addEventListener('scroll', updateStickyState, { passive: true });
                window.addEventListener('resize', function () {
                    if (stickyHeader.classList.contains('is-sticky') && stickySpacer) {
                        stickySpacer.style.height = stickyHeader.offsetHeight + 'px';
                    }
                });
                updateStickyState();
            }
            var navDropdowns = document.querySelectorAll('.ks-nav-list > li.ks-dropdown');
            navDropdowns.forEach(function (dd) {
                var menu = dd.querySelector('.ks-dropdown-menu');
                if (!menu) return;

                var closeTimer = null;
                dd.addEventListener('mouseenter', function () {
                    if (closeTimer) clearTimeout(closeTimer);
                    dd.classList.add('is-open');
                });

                dd.addEventListener('mouseleave', function () {
                    closeTimer = setTimeout(function () {
                        dd.classList.remove('is-open');
                    }, 250);
                });

                var link = dd.querySelector('.ks-nav-link');
                if (link) {
                    link.addEventListener('click', function (e) {
                        if (window.innerWidth >= 992) {
                            dd.classList.toggle('is-open');
                        }
                    });
                }
            });
        });

        // ----------------------------------------------------
        // Modern Off-Canvas Mobile Drawer Toggle (Slides in from Left)
        // ----------------------------------------------------
        function openJamunaMobileDrawer() {
            var drawer = document.getElementById('ksMobileDrawer');
            var backdrop = document.getElementById('ksDrawerBackdrop');
            var btn = document.getElementById('ksMobileToggleBtn');
            if (drawer && backdrop) {
                drawer.classList.add('is-open');
                backdrop.classList.add('is-open');
                if (btn) btn.classList.add('is-active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeJamunaMobileDrawer() {
            var drawer = document.getElementById('ksMobileDrawer');
            var backdrop = document.getElementById('ksDrawerBackdrop');
            var btn = document.getElementById('ksMobileToggleBtn');
            if (drawer && backdrop) {
                drawer.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                if (btn) btn.classList.remove('is-active');
                document.body.style.overflow = '';
            }
        }

        function toggleDrawerDropdown(btn) {
            var parent = btn.closest('.ks-drawer-dropdown');
            if (parent) {
                var wasOpen = parent.classList.contains('is-open');
                // Close other open sibling dropdowns in drawer
                document.querySelectorAll('.ks-drawer-menu-list > .ks-drawer-dropdown.is-open').forEach(function(other) {
                    if (other !== parent) other.classList.remove('is-open');
                });
                if (!wasOpen) {
                    parent.classList.add('is-open');
                } else {
                    parent.classList.remove('is-open');
                }
            }
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeJamunaMobileDrawer();
            }
        });

        // Fast & Safe Preloader Dismiss
        (function() {
            function removeJamunaLoader() {
                var loader = document.getElementById('site-loader');
                if (loader && loader.style.display !== 'none') {
                    loader.style.opacity = '0';
                    setTimeout(function() {
                        loader.style.display = 'none';
                    }, 500);
                }
            }
            window.addEventListener('load', removeJamunaLoader);
            setTimeout(removeJamunaLoader, 2500); // 2.5s maximum fallback
        })();
    </script>

    @include('components.chat-widget')

</body>

</html>
