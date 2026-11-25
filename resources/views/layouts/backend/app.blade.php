<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    @php
        $site_name = setting('title') ?: config('app.name', 'JAMUNA STOCK MARKET PLC');
        $site_favicon = setting('favicon') ? asset('upload/images/backend/logo/' . setting('favicon')) : (setting('logo') ? asset('upload/images/backend/logo/' . setting('logo')) : asset('assets/images/logo.png'));
        $site_logo = setting('logo') ? asset('upload/images/backend/logo/' . setting('logo')) : asset('assets/images/logo.png');
    @endphp

    <link rel="icon" href="{{ $site_favicon }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ $site_favicon }}" type="image/x-icon">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $site_name }} - এডমিন প্যানেল</title>



    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/perfect-scrollbar/css/perfect-scrollbar.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/morris.js/morris.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.carousel.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/OwlCarousel2/dist/assets/owl.theme.default.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/horizontal-timeline/css/horizontal-timeline.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/flexslider/flexslider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/prism/prism.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/datatable/datatables.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/Magnific-Popup-master/dist/magnific-popup.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/gallery/css/animated-masonry-gallery.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/lightbox-master/dist/ekko-lightbox.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/jvectormap/lib2/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/sweetalert/sweetalert.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-markdown-master/css/bootstrap-markdown.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/dropzone/dropzone.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/select2/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-daterangepicker/daterangepicker.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-select/dist/css/bootstrap-select.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/raty-master/lib/jquery.raty.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/ion-rangeSlider/css/ion.rangeSlider.skinModern.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/gridstack/gridstack.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/jquery-toast-plugin-master/src/jquery.toast.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/nestable/nestable.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/bootstrap-switch/switch.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/c3/c3.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/chartist-js-develop/chartist.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-slider/slider.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/flat/blue.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/iCheck/all.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_plugins/pace/pace.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/fullcalendar/fullcalendar.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/weather-icons/weather-icons.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/vendor_components/FlipClock-master/compiled/flipclock.css">
    {{-- /*# sourceMappingURL=vendors_css.css.map */ --}}


    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('assets/main/css/color_theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style_rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/font-awesome.css">
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/font-awesome/css/all.min"> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/Ionicons/css/ionicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/themify-icons/themify-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/linea-icons/linea.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/glyphicons/glyphicon.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/flag-icon-css/css/flag-icon.css">
    <link rel="stylesheet"
        href="{{ asset('assets/plugin') }}/icons/material-design-iconic-font/css/materialdesignicons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/cryptocoins-master/cryptocoins.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/weather-icons/css/weather-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/iconsmind/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/icons/icomoon/style.css">
    <link rel="stylesheet" href="{{ asset('assets/plugin') }}/vendor_components/animate/animate.css">
    <link rel="stylesheet" href="{{ asset('assets/main/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/main/css/skin_color.css') }}">
    <!-- Premium Modern Admin Custom Styles -->
    <link rel="stylesheet" href="{{ asset('assets/main/css/modern_admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .main-header .logo {
            width: 115px !important;
        }
    </style>
</head>

<body class="hold-transition light-skin sidebar-mini theme-warning fixed">

    <div class="wrapper">
        <div id="loader"></div>

        <header class="main-header">
            <!-- Brand Logo & Sidebar Toggle -->
            <div class="logo-box d-flex align-items-center justify-content-between px-3">
                @php
                    $brandHome = (Auth::check() && (Auth::user()->role === 'supplier' || Auth::user()->hasRole('supplier'))) ? route('supplier.dashboard') : route('home');
                @endphp
                <a href="{{ $brandHome }}" class="onedash-brand d-flex align-items-center gap-2 text-decoration-none">
                    @php
                        $adminLogo = setting('logo');
                        $logoUrl = null;
                        if (!empty($adminLogo)) {
                            if (file_exists(public_path('upload/images/backend/logo/' . $adminLogo))) {
                                $logoUrl = asset('upload/images/backend/logo/' . $adminLogo);
                            } elseif (file_exists(public_path($adminLogo))) {
                                $logoUrl = asset($adminLogo);
                            } elseif (file_exists(public_path('upload/logo/' . $adminLogo))) {
                                $logoUrl = asset('upload/logo/' . $adminLogo);
                            }
                        }
                    @endphp
                    @if($logoUrl)
                        <img src="{{ $logoUrl }}" alt="{{ setting('title') ?: 'Logo' }}" style="max-height: 44px; max-width: 175px; width: auto; object-fit: contain;">
                    @else
                        <div class="onedash-logo-icon" style="width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #059669, #10b981); display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 18px;">
                            <i class="fa fa-leaf"></i>
                        </div>
                        <span class="onedash-brand-name" style="font-size: 15px; font-weight: 800; color: #0f172a; letter-spacing: -0.3px;">{{ setting('title') ?: config('app.name', 'JAMUNA STOCK') }}</span>
                    @endif
                </a>

                <button type="button" class="onedash-hamburger-btn push-btn" data-toggle="push-menu" role="button" title="Toggle Sidebar">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Onedash Header Navbar -->
            <nav class="navbar navbar-static-top d-flex align-items-center justify-content-between px-3">
                <!-- Search Box -->
                <div class="onedash-search-box d-none d-md-block">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Type here to search">
                </div>

                @if (Auth::user()->role == "employee")
                    <div class="d-none d-lg-flex align-items-center gap-2">
                        <span class="text-danger fw-bold fs-12">{{ route('/') }}/register?ref={{ Auth::user()->username }}</span>
                        <button id="myButton2" class="btn btn-xs btn-success rounded-pill px-2">Copy Link</button>
                    </div>
                @endif

                <!-- Right Action Icons & User Widget -->
                <div class="d-flex align-items-center gap-2 gap-md-3">
                    @if(Auth::check() && Auth::user()->hasPermission('supplier_management'))
                        @php
                            $adminUnreadNotifs = \App\Models\SystemNotification::where('target_role', 'admin')->where('is_read', false)->orderByDesc('id')->take(8)->get();
                            $adminUnreadNotifCount = \App\Models\SystemNotification::where('target_role', 'admin')->where('is_read', false)->count();
                            $adminPendingSupplyCount = \App\Models\SupplierSupply::where('status', 'pending')->count();
                        @endphp
                        <!-- Admin Supply Notification Bell -->
                        <div class="dropdown">
                            <a href="#" class="onedash-nav-icon-link position-relative dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" title="সাপ্লাই নোটিফিকেশন">
                                <i class="fa fa-bell {{ $adminUnreadNotifCount > 0 ? 'text-danger' : 'text-secondary' }}"></i>
                                @if($adminUnreadNotifCount > 0)
                                    <span class="onedash-badge-count bg-danger">{{ $adminUnreadNotifCount }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 p-0" style="width: 340px; max-width: 92vw; border-radius: 12px; margin-top: 8px; overflow: hidden;">
                                <div class="px-3 py-2 bg-primary text-white d-flex justify-content-between align-items-center">
                                    <strong class="fs-13"><i class="fa fa-bell me-1"></i> সাপ্লাই নোটিফিকেশন ({{ $adminUnreadNotifCount }})</strong>
                                    <a href="{{ route('admin.suppliers.notifications') }}" class="text-white small text-decoration-underline">সব দেখুন</a>
                                </div>
                                <div style="max-height: 300px; overflow-y: auto;">
                                    @forelse($adminUnreadNotifs as $anotif)
                                        <a href="{{ route('admin.suppliers.notifications.read', $anotif->id) }}" class="dropdown-item py-2 px-3 border-bottom text-wrap">
                                            <div class="fw-bold fs-12 text-dark">{{ $anotif->title }}</div>
                                            <div class="small text-muted">{{ Str::limit($anotif->message, 80) }}</div>
                                            <small class="text-primary fs-11">{{ $anotif->created_at->diffForHumans() }}</small>
                                        </a>
                                    @empty
                                        <div class="p-3 text-center text-muted small">নতুন কোনো নোটিফিকেশন নেই</div>
                                    @endforelse
                                </div>
                                @if($adminUnreadNotifCount > 0)
                                    <form action="{{ route('admin.suppliers.notifications.read_all') }}" method="POST" class="p-2 bg-light text-center border-top">
                                        @csrf
                                        <button type="submit" class="btn btn-xs btn-outline-success rounded-pill px-3">সবগুলো পঠিত করুন</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if(Auth::check() && Auth::user()->role == "user")
                        @php
                            $headerCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                        @endphp
                        <a href="{{ route('my.cart') }}" class="onedash-nav-icon-link position-relative" title="My Cart">
                            <i class="fa fa-shopping-cart text-success"></i>
                            <span class="onedash-badge-count {{ $headerCartCount > 0 ? '' : 'd-none' }}">{{ $headerCartCount }}</span>
                        </a>
                    @endif

                    <!-- User Profile Dropdown -->
                    <div class="dropdown">
                        <div class="onedash-user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar ? (file_exists(public_path('upload/user_images/' . Auth::user()->avatar)) ? asset('upload/user_images/' . Auth::user()->avatar) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80') : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}"
                                class="onedash-avatar-img" alt="Avatar">
                            <div class="onedash-user-info d-none d-md-block">
                                <span class="onedash-user-name">{{ Str::limit(Auth::user()->name ?: 'Jhon Deo', 16) }}</span>
                                <span class="onedash-user-role">{{ Auth::user()->roleTitle() ?: 'HR Manager' }}</span>
                            </div>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 animated flipInX" style="border-radius: 12px; margin-top: 8px;">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                    <i class="ti-user text-muted me-2"></i> প্রোফাইল (Profile)
                                </a>
                            </li>
                            @if (Auth::user()->role == "user")
                                <li>
                                    <a class="dropdown-item py-2 d-flex align-items-center justify-content-between" href="{{ route('my.cart') }}">
                                        <div><i class="ti-shopping-cart text-muted me-2"></i> মাই কার্ট</div>
                                        <span class="badge bg-danger rounded-pill">{{ \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti-power-off me-2"></i> লগআউট (Logout)
                                </a>
                            </li>
                        </ul>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </nav>
        </header>


        <aside class="main-sidebar">
            <!-- sidebar-->
            <section class="sidebar position-relative">
                <div class="multinav">
                    <div class="multinav-scroll" style="height: 100%;">


                        <!-- sidebar menu-->
                        <ul class="sidebar-menu" data-widget="tree">
                            @if(!Auth::user()->isSupplier())
                            <!-- ================================= Common sidebar menu  Item-->
                            <li class="{{ (optional(Request::route())->getName() == 'home') ? 'menu-open' : '' }}">
                                <a href="{{ route('home') }}">
                                    <i class="fa fa-home"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <!-- ================================= /Common sidebar menu  Item-->

                            <!-- ================================= A-Z BRANCH DIRECTOR SYSTEM ================================= -->
                            @if(Auth::user()->isStaffOrAdmin() || Auth::user()->role === 'branch_director' || Auth::user()->isBranchDirector())
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.branch.')) ? 'active menu-open' : '' }}">
                                <a href="javascript:void(0);">
                                    <i class="fa fa-university text-warning"></i>
                                    <span class="fw-bold">A–Z ব্রাঞ্চ ম্যানেজমেন্ট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.dashboard') }}"><i class="fa fa-tachometer text-primary"></i> ব্রাঞ্চ ড্যাশবোর্ড</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.profile') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.profile') }}"><i class="fa fa-building-o text-success"></i> ব্রাঞ্চ প্রোফাইল (A)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.tasks') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.tasks') }}"><i class="fa fa-tasks text-info"></i> ব্রাঞ্চ অপারেশন ও টাস্ক (B)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.commissions') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.commissions') }}"><i class="fa fa-percent text-warning"></i> কমিশন ব্যবস্থাপনা (C)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.daily_reports') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.daily_reports') }}"><i class="fa fa-file-text-o text-danger"></i> দৈনিক রিপোর্টিং (D)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.employees') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.employees') }}"><i class="fa fa-users text-primary"></i> কর্মচারী ও দায়িত্ব (E/J)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.cashbook') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.cashbook') }}"><i class="fa fa-book text-success"></i> ক্যাশবুক ও অর্থায়ন (F)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.attendance') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.attendance') }}"><i class="fa fa-calendar-check-o text-primary"></i> বায়োমেট্রিক ও হাজিরা (H)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.inventory') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.inventory') }}"><i class="fa fa-cubes text-secondary"></i> মালামাল ও সম্পদ (I)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.kpi') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.kpi') }}"><i class="fa fa-line-chart text-success"></i> কেপিআই ও উন্নয়ন (G/K)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.membership') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.membership') }}"><i class="fa fa-id-card-o text-warning"></i> মেম্বারশিপ ও কার্ড (M)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.business_leads') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.business_leads') }}"><i class="fa fa-briefcase text-info"></i> নতুন ব্যবসা উন্নয়ন (N/X)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.property') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.property') }}"><i class="fa fa-home text-success"></i> প্রপার্টি ও জমি (P)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.sales') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.sales') }}"><i class="fa fa-shopping-basket text-primary"></i> ভোগ্যপণ্য ও সেলস (S)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.complaints') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.complaints') }}"><i class="fa fa-shield text-danger"></i> জিরো টলারেন্স সেল (Z)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.documents') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.documents') }}"><i class="fa fa-folder-open text-muted"></i> লিগ্যাল ডকুমেন্টস (L)</a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.branch.policy_az') ? 'active' : '' }}">
                                        <a href="{{ route('admin.branch.policy_az') }}"><i class="fa fa-book text-warning"></i> A–Z পূর্ণাঙ্গ নীতিমালা</a>
                                    </li>
                                </ul>
                            </li>
                            @endif
                            @endif

                        <!-- ================================= Admin sidebar menu-->
                        @if (Auth::user()->isStaffOrAdmin())

                            @if(Auth::user()->hasPermission('card_numbers'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.card_numbers.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('admin.card_numbers.index') }}">
                                    <i class="fa fa-barcode text-primary"></i>
                                    <span>Number Generator</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('our_packages'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.our_packages.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.our_packages.index') }}">
                                    <i class="ti-package text-success"></i>
                                    <span>Our Packages</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('our_packages') || Auth::user()->role_id == 1)
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.card_benefits')) ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.card_benefits.index') }}">
                                    <i class="ti-id-badge text-warning"></i>
                                    <span>কার্ড সুবিধাসমূহ</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('contact_messages'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.contact_messages.index') ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.contact_messages.index') }}">
                                    <i class="ti-email text-info"></i>
                                    <span>কন্টাক্ট বার্তা</span>
                                    @php
                                        $sidebarUnreadCount = \App\Models\ContactMessage::where('status', 'unread')->count();
                                    @endphp
                                    @if($sidebarUnreadCount > 0)
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">{{ $sidebarUnreadCount }}</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'super_admin' || Auth::user()->hasPermission('careers'))
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.careers')) ? 'active menu-open' : '' }}">
                                <a href="{{ route('admin.careers.index') }}">
                                    <i class="fa fa-briefcase text-success"></i>
                                    <span>ক্যারিয়ার আবেদন</span>
                                    @php
                                        $sidebarCareerCount = \App\Models\CareerApplication::where('status', 'pending')->count();
                                    @endphp
                                    @if($sidebarCareerCount > 0)
                                        <span class="pull-right-container">
                                            <span class="label label-success pull-right">{{ $sidebarCareerCount }}</span>
                                        </span>
                                    @endif
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('corporate'))
                            <!-- 1. CORPORATE & BOARD MANAGEMENT -->
                            <li class="header">কর্পোরেট ও পরিচালনা পর্ষদ</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.corporate.') || Str::startsWith(optional(Request::route())->getName(), 'admin.team_members')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-users text-success"></i>
                                    <span>পরিচালনা পর্ষদ ও কর্পোরেট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.corporate.') || Str::startsWith(optional(Request::route())->getName(), 'admin.team_members')) ? 'd-block' : '' }}">
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.team_members')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.team_members.index') }}">
                                            <i class="ti-more"></i>
                                            <span>টিম মেম্বার (Our Team)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.corporate.board')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.corporate.board.index') }}">
                                            <i class="ti-more"></i>
                                            <span>পর্ষদ সদস্য তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.corporate.profile.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.corporate.profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>পরিচিতি ও বাণী</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.corporate.activities')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.corporate.activities.index') }}">
                                            <i class="ti-more"></i>
                                            <span>প্রকল্প কার্যক্রম ও অগ্রগতি</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('investments'))
                            <!-- 2. ENTERPRISE 9-SECTOR INVESTMENT MANAGEMENT -->
                            <li class="header">এন্টারপ্রাইজ বিনিয়োগ (৯টি সেক্টর)</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.investments.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cubes text-warning"></i>
                                    <span>৯টি ইনভেস্টমেন্ট সেক্টর</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.investments.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.investments.central.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.central.dashboard') }}">
                                            <i class="ti-dashboard text-success"></i>
                                            <span class="fw-bold text-success">কেন্দ্রীয় নিয়ন্ত্রণ ড্যাশবোর্ড</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.investments.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.create') }}">
                                            <i class="ti-plus text-primary"></i>
                                            <span>নতুন বিনিয়োগ এন্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.investments.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.index') }}">
                                            <i class="ti-files"></i>
                                            <span>সকল অফিসিয়াল চুক্তিপত্র</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.investments.inquiries')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.inquiries.index') }}">
                                            <i class="ti-email text-warning"></i>
                                            <span>অনলাইন ইনকোয়ারি পাইপলাইন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.investments.receipts.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.receipts.index') }}">
                                            <i class="ti-receipt text-info"></i>
                                            <span>মানি রসিদ রেজিস্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.investments.ledger.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.investments.ledger.index') }}">
                                            <i class="ti-book text-danger"></i>
                                            <span>ফাইন্যান্সিয়াল লেজার খাতা</span>
                                        </a>
                                    </li>
                                    <!-- 9 Individual Sectors Direct Access -->
                                    <li class="header text-muted py-1" style="font-size: 11px; padding-left: 15px;">৯টি স্বতন্ত্র সেক্টর</li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'fmcg') }}"><i class="ti-angle-right"></i> ০১. Consumer Goods (FMCG)</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'real-estate') }}"><i class="ti-angle-right"></i> ০২. Housing / Real Estate</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'fisheries') }}"><i class="ti-angle-right"></i> ০৩. Fisheries (মৎস্য খামার)</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'livestock') }}"><i class="ti-angle-right"></i> ০৪. Livestock Farming (গবাদিপশু)</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'farmhouse') }}"><i class="ti-angle-right"></i> ০৫. Farmhouse (ফার্মহাউজ)</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'plot-trading') }}"><i class="ti-angle-right"></i> ০৬. Plot Buying & Selling</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'resort') }}"><i class="ti-angle-right"></i> ০৭. Resort (ইকো রিসোর্ট)</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'priority-investment') }}"><i class="ti-angle-right"></i> ০৮. Priority Investment</a></li>
                                    <li><a href="{{ route('admin.investments.sector.dashboard', 'import-export') }}"><i class="ti-angle-right"></i> ০৯. Import & Export</a></li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('shareholders'))
                            <!-- 3. SHAREHOLDERS & PARTNERSHIPS -->
                            <li class="header">শেয়ারহোল্ডার ও পার্টনারশিপ</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.shareholders.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-user-shield text-info"></i>
                                    <span>শেয়ারহোল্ডার ও পার্টনারশিপ</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.shareholders.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.shareholders.settings.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.shareholders.settings.index') }}">
                                            <i class="ti-more"></i>
                                            <span>শেয়ার ক্যাপিটাল সেটিংস</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.shareholders.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.shareholders.index') }}">
                                            <i class="ti-more"></i>
                                            <span>শেয়ারহোল্ডার তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.shareholders.partnerships.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.shareholders.partnerships.index') }}">
                                            <i class="ti-more"></i>
                                            <span>প্রাতিষ্ঠানিক পার্টনারশিপ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('central_finance'))
                            <!-- 4. CENTRAL FINANCE & UNIFIED ACCOUNTS -->
                            <li class="header">সেন্ট্রাল ফিন্যান্স</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.finance.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-university text-danger"></i>
                                    <span>সেন্ট্রাল ফিন্যান্স ও অ্যাকাউন্ট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.finance.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.finance.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.finance.index') }}">
                                            <i class="ti-more"></i>
                                            <span>আয়-ব্যয় সেন্ট্রাল লেজার</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.finance.unified_accounts') ? 'active' : '' }}">
                                        <a href="{{ route('admin.finance.unified_accounts') }}">
                                            <i class="ti-more"></i>
                                            <span>একীভূত হিসাব (Unified Accounts)</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <!-- ENTERPRISE BILLS & VOUCHERS MANAGEMENT (ডাবল-এন্ট্রি একাউন্টিং ও ভাউচার) -->
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.bills_vouchers.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-file-text-o text-success"></i>
                                    <span>বিল ও ভাউচার ব্যবস্থাপনা</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.bills_vouchers.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.dashboard') }}">
                                            <i class="ti-dashboard"></i>
                                            <span>ড্যাশবোর্ড ও সামারি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.vouchers.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.vouchers.index') }}">
                                            <i class="ti-receipt"></i>
                                            <span>সকল ভাউচার (Vouchers)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.vouchers.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.vouchers.create') }}">
                                            <i class="ti-plus"></i>
                                            <span>নতুন ভাউচার তৈরি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.bills.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.bills.index') }}">
                                            <i class="ti-files"></i>
                                            <span>বিল তালিকা (Bills)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.bills.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.bills.create') }}">
                                            <i class="ti-file"></i>
                                            <span>নতুন বিল এন্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.chart_of_accounts') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.chart_of_accounts') }}">
                                            <i class="ti-layout-grid2"></i>
                                            <span>চার্ট অব অ্যাকাউন্টস (COA)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.reports.general_ledger') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.reports.general_ledger') }}">
                                            <i class="ti-book"></i>
                                            <span>জেনারেল লেজার (GL)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.bills_vouchers.reports.trial_balance') ? 'active' : '' }}">
                                        <a href="{{ route('admin.bills_vouchers.reports.trial_balance') }}">
                                            <i class="ti-bar-chart"></i>
                                            <span>রেওয়ামিল (Trial Balance)</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('notices'))
                            <!-- 5. NOTICES MANAGEMENT -->
                            <li class="header">নোটিশ ও সার্কুলার</li>
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.notices.')) ? 'active' : '' }}">
                                <a href="{{ route('admin.notices.index') }}">
                                    <i class="fa fa-bullhorn text-warning"></i>
                                    <span>নোটিশ ও সার্কুলার</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('store_assets'))
                            <!-- 6. STORE & ASSET MANAGEMENT ERP (স্টোর, ইনভেন্টরি, সম্পদ, গাড়ি, মেনটেনেন্স, অবচয়, অডিট, ভাউচার) -->
                            <li class="header">স্টোর ও সম্পদ ব্যবস্থাপনা ERP</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.store.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cubes text-info"></i>
                                    <span>স্টোর ও সম্পদ ERP</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.store.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.dashboard') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.dashboard') }}">
                                            <i class="ti-dashboard"></i>
                                            <span>ড্যাশবোর্ড ও ওভারভিউ</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.stores.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.stores.index') }}">
                                            <i class="ti-home"></i>
                                            <span>স্টোর ও লোকেশন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.items.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.items.index') }}">
                                            <i class="ti-package"></i>
                                            <span>পণ্য মাস্টার তালিকা</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.items.movements') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.items.movements') }}">
                                            <i class="ti-exchange-vertical"></i>
                                            <span>স্টক ইন / আউট লেজার</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.assets.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.assets.index') }}">
                                            <i class="ti-archive"></i>
                                            <span>স্থায়ী সম্পদ রেজিস্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.store.vehicles')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.vehicles.index') }}">
                                            <i class="ti-car"></i>
                                            <span>যানবাহন ও পরিবহন বহর</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.work_orders.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.work_orders.index') }}">
                                            <i class="ti-settings"></i>
                                            <span>ওয়ার্ক অর্ডার ও AMC</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.depreciations.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.depreciations.index') }}">
                                            <i class="ti-stats-down"></i>
                                            <span>সম্পদের অবচয় হিসাব</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.audits.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.audits.index') }}">
                                            <i class="ti-check-box"></i>
                                            <span>স্টক অডিট ভেরিফিকেশন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.disposals.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.disposals.index') }}">
                                            <i class="ti-trash"></i>
                                            <span>ক্ষতি ও স্ক্র্যাপ নিলাম</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.vouchers.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.vouchers.index') }}">
                                            <i class="ti-receipt"></i>
                                            <span>বিল ও ভাউচার</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.vouchers.cash_memos') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.vouchers.cash_memos') }}">
                                            <i class="ti-wallet"></i>
                                            <span>ক্যাশ মেমো ও মানি রিসিট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.store.reports') ? 'active' : '' }}">
                                        <a href="{{ route('admin.store.reports') }}">
                                            <i class="ti-bar-chart"></i>
                                            <span>সার্বিক ERP রিপোর্ট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('live_chat'))
                            <li class="header">লাইভ চ্যাট ম্যানেজমেন্ট</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.chat.index') ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-comments text-warning"></i>
                                    <span>লাইভ চ্যাট সাপোর্ট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.chat.index') ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'user') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'user']) }}">
                                            <i class="ti-user text-primary"></i>
                                            <span>ইউজার চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'employee') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'employee']) }}">
                                            <i class="ti-id-badge text-info"></i>
                                            <span>এমপ্লয়ী চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'supplier') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'supplier']) }}">
                                            <i class="ti-truck text-success"></i>
                                            <span>সাপ্লায়ার চ্যাট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.chat.index' && request('role') == 'agent') ? 'active' : '' }}">
                                        <a href="{{ route('admin.chat.index', ['role' => 'agent']) }}">
                                            <i class="ti-headphone-alt text-warning"></i>
                                            <span>এজেন্ট চ্যাট</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('stock_management'))
                            <li class="header">Stock Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.stock.index') || (optional(Request::route())->getName() == 'admin.stock.allStock') || (optional(Request::route())->getName() == 'admin.stock_preset.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.stock.index') || (optional(Request::route())->getName() == 'admin.stock.allStock') || (optional(Request::route())->getName() == 'admin.stock_preset.index') ? 'd-block' : '' }}" >
                                    <li class="">
                                        <a href="{{ route('admin.stock.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Add Stock</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock.allStock') }}">
                                            <i class="ti-more"></i>
                                            <span>All Stock</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock_preset.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Stock Presets</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('set_stock_price'))
                            <li class="{{ (optional(Request::route())->getName() == 'admin.stock.list')? 'menu-open' : '' }}">
                                <a href="{{ route('admin.stock.list') }}">
                                    <span>Set Stock Price</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('stock_buy_sell'))
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.stock.buyrequest.list') || (optional(Request::route())->getName() == 'admin.stock.sellrequest.list')|| (optional(Request::route())->getName() == 'admin.buy.stock.list')|| (optional(Request::route())->getName() == 'admin.sell.stock.list') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Stock Buy/Sell</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.stock.buyrequest.list') || (optional(Request::route())->getName() == 'admin.stock.sellrequest.list')|| (optional(Request::route())->getName() == 'admin.buy.stock.list')|| (optional(Request::route())->getName() == 'admin.sell.stock.list') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.stock.buyrequest.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Request for Buy</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.stock.sellrequest.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Request for Sell</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.buy.stock.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Buy List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.sell.stock.list') }}">
                                            <i class="ti-more"></i>
                                            <span>Sell List</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('monthly_bazaar') || Auth::user()->isStaffOrAdmin())
                            @php
                                $routeName = optional(Request::route())->getName() ?? '';
                                $isDistributionActive = in_array($routeName, [
                                    'admin.agent_monitoring',
                                    'admin.agent_monitoring.index',
                                    'admin.agent_monitoring.details',
                                    'admin.distribution.allocate',
                                    'admin.distribution.demand',
                                    'admin.distribution.reports',
                                    'admin.distribution.warehouse_stock',
                                    'admin.distribution.deliveries_history',
                                    'admin.warehouse.stock',
                                    'admin.agent_deliveries.index',
                                    'admin.monthly_bazaar.orders',
                                    'admin.monthly_bazaar.index',
                                    'admin.monthly_bazaar.distribution_reports',
                                    'admin.monthly_closing.index',
                                    'admin.areas.index',
                                    'admin.agent_points.index',
                                ]) || Str::startsWith($routeName, [
                                    'admin.distribution.',
                                    'admin.monthly_bazaar.',
                                    'admin.monthly_closing.',
                                    'admin.areas.',
                                    'admin.agent_points.',
                                    'admin.agents.',
                                    'admin.agent_monitoring.'
                                ]);
                            @endphp
                            <li class="header">মাসিক বাজার ও সেন্ট্রাল ডিস্ট্রিবিউশন</li>
                            <li class="treeview {{ $isDistributionActive ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-cubes text-success"></i>
                                    <span>ডিস্ট্রিবিউশন ইকোসিস্টেম</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ $isDistributionActive ? 'd-block' : '' }}">
                                    <li class="{{ in_array($routeName, ['admin.agent_monitoring', 'admin.agent_monitoring.index', 'admin.agent_monitoring.details']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.agent_monitoring') }}">
                                            <i class="fa fa-dashboard text-warning"></i>
                                            <span>এজেন্ট পয়েন্ট মনিটরিং</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.distribution.allocate']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.distribution.allocate') }}">
                                            <i class="fa fa-truck text-info"></i>
                                            <span>এরিয়া ও পয়েন্ট ডিস্ট্রিবিউশন</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.distribution.demand']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.distribution.demand') }}">
                                            <i class="fa fa-calculator text-primary"></i>
                                            <span>পণ্যভিত্তিক মোট চাহিদা</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.monthly_bazaar.orders']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_bazaar.orders') }}">
                                            <i class="fa fa-shopping-basket text-success"></i>
                                            <span>অর্ডার রিকোয়েস্ট ও অনুমোদন</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.warehouse.stock', 'admin.distribution.warehouse_stock']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.warehouse.stock') }}">
                                            <i class="fa fa-database text-warning"></i>
                                            <span>সেন্ট্রাল ওয়্যারহাউস স্টক</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.agent_deliveries.index', 'admin.distribution.deliveries_history']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.agent_deliveries.index') }}">
                                            <i class="fa fa-list-alt text-danger"></i>
                                            <span>এজেন্ট ডেলিভারি হিস্ট্রি</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.distribution.reports']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.distribution.reports') }}">
                                            <i class="fa fa-bar-chart text-info"></i>
                                            <span>ডিস্ট্রিবিউশন অডিট রিপোর্ট</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.monthly_closing.index']) || Str::startsWith($routeName, 'admin.monthly_closing.') ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_closing.index') }}">
                                            <i class="fa fa-lock text-danger"></i>
                                            <span>মাসিক বাজার ক্লোজিং</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.areas.index']) || Str::startsWith($routeName, 'admin.areas.') ? 'active' : '' }}">
                                        <a href="{{ route('admin.areas.index') }}">
                                            <i class="fa fa-map text-primary"></i>
                                            <span>ডাইনামিক এলাকা (Areas)</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.agent_points.index']) || Str::startsWith($routeName, 'admin.agent_points.') ? 'active' : '' }}">
                                        <a href="{{ route('admin.agent_points.index') }}">
                                            <i class="fa fa-map-marker text-success"></i>
                                            <span>সংগ্রহ এজেন্ট পয়েন্টসমূহ</span>
                                        </a>
                                    </li>
                                    <li class="{{ Str::startsWith($routeName, 'admin.agents.') ? 'active' : '' }}">
                                        <a href="{{ route('admin.agents.index') }}">
                                            <i class="fa fa-users text-primary"></i>
                                            <span>এজেন্ট ব্যবস্থাপনা (Agents)</span>
                                        </a>
                                    </li>
                                    <li class="{{ in_array($routeName, ['admin.monthly_bazaar.index']) ? 'active' : '' }}">
                                        <a href="{{ route('admin.monthly_bazaar.index') }}">
                                            <i class="fa fa-th-large text-muted"></i>
                                            <span>প্যাকেজ ক্যাটালগ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('user_financial'))
                            <li class="header">User Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'alluser') || (optional(Request::route())->getName() == 'admin.deposit.index') || (optional(Request::route())->getName() == 'admin.reports.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>User & Financial</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'alluser') || (optional(Request::route())->getName() == 'admin.deposit.index') || (optional(Request::route())->getName() == 'admin.reports.index') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('alluser') }}">
                                            <i class="ti-more"></i>
                                            <span>All Customer List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.deposit.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Deposit Requests</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.reports.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Reports & Analytics</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('withdraw'))
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.all.withdraw.request') || (optional(Request::route())->getName() == 'admin.all.withdraw') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Withdraw</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.all.withdraw.request') || (optional(Request::route())->getName() == 'admin.all.withdraw') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.all.withdraw.request') }}">
                                            <i class="ti-more"></i>
                                            <span>Withdraw Request</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.all.withdraw') }}">
                                            <i class="ti-more"></i>
                                            <span>All Withdraw</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('hrm_management'))
                            <li class="header">HRM Management System</li>
                            <li class="treeview {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="ti-user text-danger"></i>
                                    <span>HRM সিস্টেম</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_contains(optional(Request::route())->getName() ?? '', 'admin.hrm') ? 'd-block' : '' }}">
                                    <li><a href="{{ route('admin.hrm.dashboard') }}"><i class="ti-dashboard text-success"></i> <span>HRM ড্যাশবোর্ড</span></a></li>
                                    
                                    @if(Auth::user()->hasPermission('hrm.employees.manage'))
                                    <li><a href="{{ route('admin.hrm.employees.index') }}"><i class="ti-id-badge text-info"></i> <span>কর্মী ডিরেক্টরি & H2R</span></a></li>
                                    <li><a href="{{ route('admin.hrm.departments.index') }}"><i class="ti-layers text-primary"></i> <span>ডিপার্টমেন্টসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.designations.index') }}"><i class="ti-medall text-warning"></i> <span>পদবীসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.branches.index') }}"><i class="ti-location-pin text-info"></i> <span>ব্রাঞ্চসমূহ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.shifts.index') }}"><i class="ti-time text-secondary"></i> <span>শিফট শিডিউল</span></a></li>
                                    @endif
                                    
                                    @if(Auth::user()->hasPermission('hrm.attendances.manage'))
                                    <li class="header" style="font-size: 10px; padding: 6px 15px 3px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">বায়োমেট্রিক ও হাজিরা</li>
                                    <li><a href="{{ route('admin.hrm.biometric.devices') }}"><i class="ti-server text-success"></i> <span>বায়োমেট্রিক ডিভাইস</span></a></li>
                                    <li><a href="{{ route('admin.hrm.biometric.enrollment') }}"><i class="ti-hand-point-up text-primary"></i> <span>ফিঙ্গারপ্রিন্ট ম্যাপিং</span></a></li>
                                    <li><a href="{{ route('admin.hrm.biometric.events') }}"><i class="ti-pulse text-danger"></i> <span>লাইভ পাঞ্চ স্ট্রিম</span></a></li>
                                    <li><a href="{{ route('admin.hrm.attendance.index') }}"><i class="ti-alarm-clock text-danger"></i> <span>দৈনিক উপস্থিতি</span></a></li>
                                    <li><a href="{{ route('admin.hrm.attendance.monthly') }}"><i class="ti-calendar text-info"></i> <span>মাসিক উপস্থিতি রেজিস্টার</span></a></li>
                                    <li><a href="{{ route('admin.hrm.attendance.corrections') }}"><i class="ti-check-box text-warning"></i> <span>হাজিরা সংশোধন</span></a></li>
                                    <li><a href="{{ route('admin.hrm.attendance.policy') }}"><i class="ti-settings text-secondary"></i> <span>উপস্থিতি পলিসি</span></a></li>
                                    @endif
                                    
                                    <li class="header" style="font-size: 10px; padding: 6px 15px 3px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">অন্যান্য মডিউল & পোর্টাল</li>
                                    @if(Auth::user()->hasPermission('hrm.leaves.manage'))
                                    <li><a href="{{ route('admin.hrm.leave.index') }}"><i class="ti-calendar text-dark"></i> <span>ছুটি অনুমোদন</span></a></li>
                                    @endif
                                    @if(Auth::user()->hasPermission('hrm.payroll.manage'))
                                    <li><a href="{{ route('admin.hrm.payroll.index') }}"><i class="ti-money text-success"></i> <span>পে-রোল & বেতন</span></a></li>
                                    <li><a href="{{ route('admin.hrm.loans.index') }}"><i class="ti-hand-point-right text-warning"></i> <span>লোন & এডভান্স</span></a></li>
                                    @endif
                                    @if(Auth::user()->hasPermission('hrm.employees.manage'))
                                    <li><a href="{{ route('admin.hrm.recruitment.index') }}"><i class="ti-briefcase text-info"></i> <span>নিয়োগ ও রিক্রুটমেন্ট</span></a></li>
                                    <li><a href="{{ route('admin.hrm.performance.index') }}"><i class="ti-star text-warning"></i> <span>পারফরম্যান্স রিভিউ</span></a></li>
                                    <li><a href="{{ route('admin.hrm.assets.index') }}"><i class="ti-desktop text-secondary"></i> <span>কোম্পানি অ্যাসেট</span></a></li>
                                    <li><a href="{{ route('admin.hrm.announcements.index') }}"><i class="ti-announcement text-danger"></i> <span>ঘোষণা & নোটিশ</span></a></li>
                                    @endif
                                    @if(Auth::user()->hasPermission('hrm.reports.view'))
                                    <li><a href="{{ route('admin.hrm.reports.index') }}"><i class="ti-bar-chart text-primary"></i> <span>এইচআর রিপোর্টস</span></a></li>
                                    @endif
                                    <li><a href="{{ route('hrm.ess.dashboard') }}" target="_blank"><i class="ti-new-window text-success"></i> <span>সেলফ-সার্ভিস পোর্টাল (ESS)</span></a></li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('employee_agent'))
                            <li class="header">Employee Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'admin.employee.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.show') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Employee / Agent</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'admin.employee.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.index') || (optional(Request::route())->getName() == 'admin.agent_ledger.show') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('admin.employee.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Employee List</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.agent_ledger.index') }}">
                                            <i class="ti-more"></i>
                                            <span>বিপণন এজেন্ট লাইভ হিসাব</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('roles_permissions'))
                            <!-- ROLES & SECURITY MANAGEMENT -->
                            <li class="header">রোল ও এক্সেস সিকিউরিটি</li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.roles.')) ? 'active menu-open' : '' }}">
                                <a href="#">
                                    <i class="fa fa-shield text-danger"></i>
                                    <span>রোল ও পারমিশন কন্ট্রোল</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.roles.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.roles.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles.index') }}">
                                            <i class="ti-more"></i>
                                            <span>রোল ডিরেক্টরি ও তৈরি</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.roles.matrix') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles.matrix') }}">
                                            <i class="ti-more"></i>
                                            <span>পারমিশন ম্যাট্রিক্স</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.roles.user_assignment') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles.user_assignment') }}">
                                            <i class="ti-more"></i>
                                            <span>ইউজার রোল অ্যাসাইন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.roles.simulator') ? 'active' : '' }}">
                                        <a href="{{ route('admin.roles.simulator') }}">
                                            <i class="ti-more"></i>
                                            <span>পারমিশন সিমুলেটর</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <li class="header">ব্লগ ও পাবলিকেশন (Blog & Media)</li>
                            <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.videos.')) ? 'active' : '' }}">
                                <a href="{{ route('admin.videos.index') }}">
                                    <i class="fa fa-youtube-play text-danger"></i>
                                    <span class="fw-bold">আমাদের ভিডিও সেকশন</span>
                                </a>
                            </li>
                            <li class="treeview {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.blogs.')) ? 'menu-open active' : '' }}">
                                <a href="#">
                                    <i class="fa fa-newspaper-o text-warning"></i>
                                    <span>ব্লগ ম্যানেজমেন্ট</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (Str::startsWith(optional(Request::route())->getName(), 'admin.blogs.')) ? 'd-block' : '' }}">
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.blogs.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.blogs.index') }}">
                                            <i class="ti-more"></i>
                                            <span>সকল ব্লগ পোস্ট</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.blogs.create') ? 'active' : '' }}">
                                        <a href="{{ route('admin.blogs.create') }}">
                                            <i class="ti-more"></i>
                                            <span>নতুন পোস্ট লিখুন</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.blogs.categories.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.blogs.categories.index') }}">
                                            <i class="ti-more"></i>
                                            <span>ব্লগ ক্যাটাগরি সমূহ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            @if(Auth::user()->hasPermission('settings'))
                            <li class="header">Settings Managment</li>
                            <li class="treeview {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'setting.index')||(optional(Request::route())->getName() == 'setting.slider')||(optional(Request::route())->getName() == 'admin.our_packages.index')||(optional(Request::route())->getName() == 'setting.offer_banner')||(optional(Request::route())->getName() == 'profile.index')||(optional(Request::route())->getName() == 'admin.payment.index') ? 'd-block' : '' }}">
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.settings.theme.')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.settings.theme.index') }}">
                                            <i class="ti-palette text-success"></i>
                                            <span>কালার ও থিম সেটিংস</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Site & Contact Setting</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.index') }}#about_us_full_text">
                                            <i class="ti-more"></i>
                                            <span>Pages (About, Privacy, Terms)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.gallery.')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.gallery.index') }}">
                                            <i class="ti-more"></i>
                                            <span>ফটো গ্যালারি (Photo Gallery)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.videos.')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.videos.index') }}">
                                            <i class="ti-more"></i>
                                            <span>ভিডিও সেকশন (Video Section)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('setting.slider') }}">
                                            <i class="ti-more"></i>
                                            <span>Site Slider</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'admin.our_packages.index') ? 'active' : '' }}">
                                        <a href="{{ route('admin.our_packages.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Our Packages (আওয়ার প্যাকেজসমূহ)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (Str::startsWith(optional(Request::route())->getName(), 'admin.card_benefits')) ? 'active' : '' }}">
                                        <a href="{{ route('admin.card_benefits.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Card Benefits (কার্ড সুবিধাসমূহ)</span>
                                        </a>
                                    </li>
                                    <li class="{{ (optional(Request::route())->getName() == 'setting.offer_banner') ? 'active' : '' }}">
                                        <a href="{{ route('setting.offer_banner') }}">
                                            <i class="ti-more"></i>
                                            <span>Offer Banner (অফার ব্যানার)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.feature.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Feature Boxes (কেন যমুনা পরিবার)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'admin.payment.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.payment.index') }}">
                                    <i class="ti-more"></i>
                                    <span>পেমেন্ট মেথড</span>
                                </a>
                            </li>
                            @endif

                            @if(Auth::user()->hasPermission('supplier_management'))
                            @php
                                $sidebarNotifCount = $adminUnreadNotifCount ?? \App\Models\SystemNotification::where('target_role', 'admin')->where('is_read', false)->count();
                                $sidebarPendingCount = $adminPendingSupplyCount ?? \App\Models\SupplierSupply::where('status', 'pending')->count();
                            @endphp
                            <li class="header">Supplier Management</li>
                            <li class="treeview {{ str_starts_with((string) optional(Request::route())->getName(), 'admin.suppliers.') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>সাপ্লায়ার ব্যবস্থাপনা</span>
                                    @if(($sidebarNotifCount + $sidebarPendingCount) > 0)
                                        <span class="badge bg-danger rounded-pill ms-1">{{ $sidebarNotifCount > 0 ? $sidebarNotifCount : $sidebarPendingCount }}</span>
                                    @endif
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ str_starts_with((string) optional(Request::route())->getName(), 'admin.suppliers.') ? 'd-block' : '' }}">
                                    <li class="{{ optional(Request::route())->getName() == 'admin.suppliers.notifications' ? 'active' : '' }}">
                                        <a href="{{ route('admin.suppliers.notifications') }}">
                                            <i class="ti-bell text-warning"></i>
                                            <span>সাপ্লাই নোটিফিকেশন</span>
                                            @if($sidebarNotifCount > 0)
                                                <span class="badge bg-danger rounded-pill float-end">{{ $sidebarNotifCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.index') }}">
                                            <i class="ti-more"></i>
                                            <span>সকল সাপ্লায়ার</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.create') }}">
                                            <i class="ti-more"></i>
                                            <span>নতুন সাপ্লায়ার যোগ করুন</span>
                                        </a>
                                    </li>
                                    <li class="{{ optional(Request::route())->getName() == 'admin.suppliers.pending_supplies' ? 'active' : '' }}">
                                        <a href="{{ route('admin.suppliers.pending_supplies') }}">
                                            <i class="ti-more"></i>
                                            <span>পণ্য সরবরাহ ভেরিফিকেশন</span>
                                            @if($sidebarPendingCount > 0)
                                                <span class="badge bg-warning text-dark rounded-pill float-end">{{ $sidebarPendingCount }}</span>
                                            @endif
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.units.index') }}">
                                            <i class="ti-more"></i>
                                            <span>ইউনিট ও ক্যাটাগরি</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.audit_logs') }}">
                                            <i class="ti-more"></i>
                                            <span>সাপ্লাই অডিট লগ</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.payments.index') }}">
                                            <i class="ti-money text-success"></i>
                                            <span>সাপ্লায়ার পেমেন্ট (Accounts)</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.customer_distributions') }}">
                                            <i class="ti-share text-info"></i>
                                            <span>গ্রাহক বিতরণ মনিটরিং</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.master_dashboard') }}">
                                            <i class="ti-bar-chart text-primary"></i>
                                            <span>মাস্টার অ্যাকাউন্টিং ও স্টক</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('admin.suppliers.reports') }}">
                                            <i class="ti-more"></i>
                                            <span>সাপ্লায়ার রিপোর্টস</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                        <!-- ================================= /Admin sidebar menu-->

                        <!-- ================================= User sidebar menu-->
                        @elseif (Auth::user()->role == "user")
                            <li class="{{ (optional(Request::route())->getName() == 'stock.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('stock.index') }}">
                                    
                                    <span>Stock</span>
                                </a>
                            </li>
                            <li class="header">User Managment</li>


                            <li class="treeview {{ (optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                    <span>My Stock</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'userbuystocklist')||(optional(Request::route())->getName() == 'usersellstocklist') ? 'd-block' : '' }}">

                                    <li class="">
                                        <a href="{{ route('userbuystocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>Buy list</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('usersellstocklist') }}">
                                            <i class="ti-more"></i>
                                            <span>Sell List</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                    <span>মাসিক বাজার</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'user.monthly_bazaar.index') || (optional(Request::route())->getName() == 'user.monthly_bazaar.my_orders') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('user.monthly_bazaar.index') }}">
                                            <i class="ti-more"></i>
                                            <span>মাসিক বাজার প্যাকেজ</span>
                                        </a>
                                    </li>
                                    <li class="">
                                        <a href="{{ route('user.monthly_bazaar.my_orders') }}">
                                            <i class="ti-more"></i>
                                            <span>আমার অর্ডারসমূহ</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'user.deposit.index') ? 'menu-open' : '' }}">
                                <a href="{{ route('user.deposit.index') }}">
                                    
                                    <span>Deposit Money (ডিপোজিট)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'user.wallet.ledger') ? 'menu-open' : '' }}">
                                <a href="{{ route('user.wallet.ledger') }}">
                                    
                                    <span>Wallet Ledger (ওয়ালেট খতিয়ান)</span>
                                </a>
                            </li>

                            <li class="{{ (optional(Request::route())->getName() == 'my.cart') ? 'menu-open' : '' }}">
                                <a href="{{ route('my.cart') }}" class="d-flex align-items-center justify-content-between">
                                    <span>My Cart</span>
                                    @php
                                        $sidebarCartCount = \App\Models\UserCart::where('user_id', Auth::id())->sum('quantity');
                                    @endphp
                                    <span class="badge bg-danger rounded-pill user-cart-badge {{ $sidebarCartCount > 0 ? '' : 'd-none' }}" style="font-size: 11px; padding: 3px 8px;">{{ $sidebarCartCount }}</span>
                                </a>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'withdraw.index')||(optional(Request::route())->getName() == 'withdraw.form') ? 'menu-open' : '' }}">
                                <a href="#">
                                    
                                        <span>Withdraw</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'withdraw.index')||(optional(Request::route())->getName() == 'withdraw.form') ? 'd-block' : '' }}">
                                    <li>
                                        <a href="{{ route('withdraw.index') }}">
                                            <i class="ti-more"></i>
                                            <span>Withdraw History</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('withdraw.form') }}">
                                            <i class="ti-more"></i>
                                            <span>Send Request</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li class="{{ (optional(Request::route())->getName() == 'payment.index') ? 'active' : '' }}">
                                <a href="{{ route('payment.index') }}">
                                    <i class="ti-more"></i>
                                    <span>পেমেন্ট মেথড</span>
                                </a>
                            </li>

                            <li class="treeview {{ (optional(Request::route())->getName() == 'profile.index') ? 'menu-open' : '' }}">
                                <a href="#">
                                    <span>Settings</span>
                                    <span class="pull-right-container">
                                        <i class="fa fa-angle-right pull-right"></i>
                                    </span>
                                </a>
                                <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'profile.index') ? 'd-block' : '' }}">
                                    <li class="">
                                        <a href="{{ route('profile.index') }}">
                                            <i class="ti-more"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        <!-- ================================ /User sidebar menu -->
                        @elseif (Auth::user()->role == "supplier" || Auth::user()->hasRole('supplier'))

                        <!-- ================================ Supplier sidebar menu ================================ -->
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.dashboard') ? 'active menu-open' : '' }}">
                            <a href="{{ route('supplier.dashboard') }}">
                                <i class="fa fa-tachometer text-primary"></i>
                                <span>সাপ্লায়ার ড্যাশবোর্ড</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.index') ? 'active menu-open' : '' }}">
                            <a href="{{ route('supplier.supplies.index') }}">
                                <i class="fa fa-cubes text-success"></i>
                                <span>আমার সরবরাহকৃত পণ্য</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.supplies.create') ? 'active menu-open' : '' }}">
                            <a href="{{ route('supplier.supplies.create') }}">
                                <i class="fa fa-plus-circle text-info"></i>
                                <span>নতুন চালান / পণ্য এন্ট্রি</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'supplier.statement') ? 'active menu-open' : '' }}">
                            <a href="{{ route('supplier.statement') }}">
                                <i class="fa fa-file-text-o text-warning"></i>
                                <span>অ্যাকাউন্ট স্টেটমেন্ট ও বিল</span>
                            </a>
                        </li>
                        <li class="{{ (optional(Request::route())->getName() == 'profile.index') ? 'active menu-open' : '' }}">
                            <a href="{{ route('profile.index') }}">
                                <i class="ti-user text-secondary"></i>
                                <span>প্রোফাইল সেটিংস</span>
                            </a>
                        </li>
                        @else

                        <!-- ================================ Employee sidebar menu -->
                        <li class="{{ (optional(Request::route())->getName() == 'employee.stock_ledger.index') ? 'menu-open' : '' }}">
                            <a href="{{ route('employee.stock_ledger.index') }}">
                                
                                <span>আমার লাইভ স্টক ও হিসাব</span>
                            </a>
                        </li>

                        <li class="treeview {{ (optional(Request::route())->getName() == 'my.referal') ? 'menu-open' : '' }}">
                            <a href="#">
                                
                                <span>Referal User</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'my.referal') ? 'd-block' : '' }}">
                                <li class="">
                                    <a href="{{ route('my.referal') }}">
                                        <i class="ti-more"></i>
                                        <span>My Referal</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="treeview {{ (optional(Request::route())->getName() == 'profile.index') || (optional(Request::route())->getName() == 'profile.business') ? 'menu-open' : '' }}">
                            <a href="#">
                                
                                <span>Settings</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-right pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu {{ (optional(Request::route())->getName() == 'profile.index') || (optional(Request::route())->getName() == 'profile.business') ? 'd-block' : '' }}">

                                <li class="">
                                    <a href="{{ route('profile.index') }}">
                                        <i class="ti-more"></i>
                                        <span>My Profile</span>
                                    </a>
                                </li>

                                <li class="">
                                    <a href="{{ route('profile.business') }}">
                                        <i class="ti-more"></i>
                                        <span>Business Profile</span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- ================================= /Employee sidebar menu -->
                        @endif

                    </ul>

                        <div class="sidebar-widgets">
                            <div class="copyright text-start m-25">
                                <p class="text-center">
                                    <strong class="d-block">
                                        <a href="{{ url('/') }}">{{ setting('title') ?: 'Jamuna Stock Market PLC' }}</a>
                                    </strong>
                                    © {{ date('Y') }} All Rights Reserved
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </aside>

        @yield('content')

        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->


    <!-- Page Content overlay -->

    <!-- Vendor JS -->
    <script src="{{ asset('assets/main/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/main/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/plugin/icons/feather-icons/feather.min.js') }}"></script>



    <script src="{{ asset('assets/plugin') }}/vendor_components/apexcharts-bundle/data.js"></script>
    <script src="{{ asset('assets/plugin') }}/vendor_components/apexcharts-bundle/dist/apexcharts.js"></script>

    <script src="{{ asset('assets/plugin') }}/vendor_components/jquery-steps-master/build/jquery.steps.js"></script>
    <script src="{{ asset('assets/plugin') }}/vendor_components/jquery-validation-1.17.0/dist/jquery.validate.min.js">
    </script>


    <!-- Crypto Tokenizer Admin App -->
    <script src="{{ asset('assets/main/js') }}/template.js"></script>
    <script src="{{ asset('assets/main/js') }}/pages/steps.js"></script>



    <!-- Toastr Notification CSS & JS -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "4000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        @if(Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            switch(type){
                case 'info':
                    toastr.info("{{ Session::get('message') }}");
                    break;
                case 'success':
                    toastr.success("{{ Session::get('message') }}");
                    break;
                case 'warning':
                    toastr.warning("{{ Session::get('message') }}");
                    break;
                case 'error':
                    toastr.error("{{ Session::get('message') }}");
                    break;
                default:
                    toastr.success("{{ Session::get('message') }}");
                    break;
            }
        @endif

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}");
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}");
        @endif

        @if(Session::has('info'))
            toastr.info("{{ Session::get('info') }}");
        @endif

        @if(Session::has('status'))
            toastr.success("{{ Session::get('status') }}");
        @endif

        window.updateCartBadgeCount = function(count) {
            var countNum = parseInt(count) || 0;
            document.querySelectorAll('.user-cart-badge').forEach(function(badge) {
                badge.textContent = countNum;
                if (countNum > 0) {
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            });
        };
    </script>

    <script>
        const myDiv = document.getElementById('myDiv2');
         const myButton = document.getElementById('myButton2');

         if(myButton && myDiv){
             myButton.addEventListener('click', () => {
             const textToCopy = myDiv.textContent;
             navigator.clipboard.writeText(textToCopy)
                 .then(() => {
                     toastr.success('Link Copy Successfully');
                 })
                 .catch((err) => {
                     toastr.error('Failed to copy link');
                 });
             });
         }
     </script>


    <!-- WEDASH Theme Customizer Slide-out Drawer -->
    <div class="theme-drawer-overlay" id="themeOverlay"></div>
    <div class="theme-drawer" id="themeDrawer">
        <div class="theme-drawer-header">
            <div class="theme-drawer-title">
                <i class="fa fa-paint-brush text-primary me-2"></i> থিম কালার কাস্টমাইজ
            </div>
            <button type="button" id="closeThemeDrawer" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <p class="text-muted" style="font-size: 13px; margin-bottom: 16px;">এডমিন প্যানেলের পছন্দসই কালার থিম নির্বাচন করুন:</p>
        <div class="theme-swatch-list">
            <div class="theme-swatch-item active" data-theme-name="blue">
                <div class="swatch-color-bubble" style="background: #3461ff;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Onedash Royal Blue (Default)</strong>
                    <small class="text-muted">অনড্যাশ ব্লু সিগনেচার</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="emerald">
                <div class="swatch-color-bubble" style="background: #10b981;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Jamuna Emerald Green</strong>
                    <small class="text-muted">যমুনা পরিবার গ্রিন</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="fuchsia">
                <div class="swatch-color-bubble" style="background: #d946ef;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Magenta Pink</strong>
                    <small class="text-muted">মেজেন্টা পিঙ্ক স্টাইল</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="purple">
                <div class="swatch-color-bubble" style="background: #8b5cf6;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Deep Purple</strong>
                    <small class="text-muted">লাক্সারি পারপল</small>
                </div>
            </div>
            <div class="theme-swatch-item" data-theme-name="dark">
                <div class="swatch-color-bubble" style="background: #0f172a;"></div>
                <div>
                    <strong class="d-block" style="font-size: 13.5px; color: #0f172a;">Midnight Dark</strong>
                    <small class="text-muted">ডিপ ডার্ক নাইট মোড</small>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const savedTheme = localStorage.getItem('admin_theme') || 'blue';
            setAdminTheme(savedTheme);

            const gearBtn = document.getElementById('openThemeSettings');
            const closeBtn = document.getElementById('closeThemeDrawer');
            const overlay = document.getElementById('themeOverlay');
            const drawer = document.getElementById('themeDrawer');

            function openDrawer() {
                if (drawer && overlay) {
                    drawer.classList.add('active');
                    overlay.classList.add('active');
                }
            }

            function closeDrawer() {
                if (drawer && overlay) {
                    drawer.classList.remove('active');
                    overlay.classList.remove('active');
                }
            }

            if (gearBtn) gearBtn.addEventListener('click', openDrawer);
            if (closeBtn) closeBtn.addEventListener('click', closeDrawer);
            if (overlay) overlay.addEventListener('click', closeDrawer);

            document.querySelectorAll('.theme-swatch-item').forEach(item => {
                item.addEventListener('click', function () {
                    const theme = this.getAttribute('data-theme-name');
                    setAdminTheme(theme);
                });
            });

            function setAdminTheme(theme) {
                if (theme === 'blue') {
                    document.body.removeAttribute('data-theme');
                } else {
                    document.body.setAttribute('data-theme', theme);
                }
                localStorage.setItem('admin_theme', theme);
                document.querySelectorAll('.theme-swatch-item').forEach(el => {
                    if (el.getAttribute('data-theme-name') === theme) {
                        el.classList.add('active');
                    } else {
                        el.classList.remove('active');
                    }
                });
            }
        });
    </script>

    <script>
        // Intelligent Admin Sidebar State & Scroll Position Preservation
        (function() {
            function handleSidebarPosition() {
                var scrollEl = document.querySelector('.multinav-scroll') || document.querySelector('.main-sidebar');
                if (!scrollEl) return;

                // 1. Keep track of user's scroll position when scrolling
                scrollEl.addEventListener('scroll', function() {
                    sessionStorage.setItem('admin_sidebar_scroll', scrollEl.scrollTop);
                }, { passive: true });

                // 2. Remember position on menu click
                document.querySelectorAll('.sidebar-menu a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        sessionStorage.setItem('admin_sidebar_scroll', scrollEl.scrollTop);
                    });
                });

                // 3. Ensure any active submenu item has its parent expanded
                var activeLi = document.querySelector('.sidebar-menu li.active');
                if (activeLi) {
                    var parentTreeview = activeLi.closest('.treeview');
                    if (parentTreeview) {
                        parentTreeview.classList.add('active', 'menu-open');
                        var subMenu = parentTreeview.querySelector('.treeview-menu');
                        if (subMenu) {
                            subMenu.style.display = 'block';
                            subMenu.classList.add('d-block');
                        }
                    }
                }

                // 4. Restore scroll or scroll active item into viewport
                var savedPos = sessionStorage.getItem('admin_sidebar_scroll');
                setTimeout(function() {
                    if (activeLi) {
                        var rect = activeLi.getBoundingClientRect();
                        var containerRect = scrollEl.getBoundingClientRect();
                        // If active item is not cleanly visible in the container viewport, scroll to it
                        if (rect.top < containerRect.top + 40 || rect.bottom > containerRect.bottom - 40) {
                            activeLi.scrollIntoView({ block: 'center', behavior: 'auto' });
                            return;
                        }
                    }

                    if (savedPos !== null) {
                        scrollEl.scrollTop = parseInt(savedPos, 10);
                    }
                }, 120);
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', handleSidebarPosition);
            } else {
                handleSidebarPosition();
            }
        })();
    </script>

    @yield('script')

    @include('components.chat-widget')

</body>

</html>
