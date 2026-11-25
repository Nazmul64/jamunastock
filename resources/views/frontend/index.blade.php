@extends('layouts.frontend.app')

@section('content')

<div class="main-container">

    <main class="site-main">

        <style>
            /* --- Jamuna Hero Slider Section Styling --- */
            .jamuna-hero-section {
                padding: 0;
                margin: 0;
                background: #ffffff;
                border-bottom: 1px solid #d1fae5;
                position: relative;
                overflow: hidden;
            }
            /* Dynamic Jamuna Hero Carousel */
            .jamuna-hero-banner {
                position: relative;
                width: 100%;
                margin: 0 auto;
                background: #ffffff;
            }
            .jamuna-slider-img {
                width: 100% !important;
                height: auto !important;
                max-height: none !important;
                object-fit: contain !important;
                display: block;
            }
            .jamuna-carousel-btn {
                position: absolute;
                width: 44px;
                height: 44px;
                top: 50%;
                transform: translateY(-50%);
                border-radius: 50%;
                background: rgba(6, 56, 26, 0.8);
                border: 1.5px solid #f59e0b;
                color: #ffffff !important;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                font-size: 16px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.35);
                text-decoration: none !important;
                z-index: 10;
            }
            .jamuna-carousel-btn.prev-btn {
                left: 18px;
            }
            .jamuna-carousel-btn.next-btn {
                right: 18px;
            }
            .jamuna-hero-banner:hover .jamuna-carousel-btn {
                opacity: 1;
            }
            .jamuna-carousel-btn:hover {
                background: #0c5c2d;
                border-color: #fbb03b;
                transform: translateY(-50%) scale(1.1);
                color: #fbb03b !important;
                box-shadow: 0 6px 16px rgba(245, 158, 11, 0.45);
            }
            .jamuna-carousel-indicators {
                position: absolute;
                bottom: 12px;
                left: 0;
                right: 0;
                display: flex;
                justify-content: center;
                padding-left: 0;
                list-style: none;
                margin-bottom: 0;
                gap: 6px;
                z-index: 10;
            }
            .jamuna-carousel-indicators li {
                width: 12px;
                height: 12px;
                border-radius: 50%;
                background-color: rgba(255, 255, 255, 0.65);
                border: 2px solid #06381a;
                cursor: pointer;
                transition: all 0.3s ease;
                margin: 0 3px;
                display: inline-block;
            }
            .jamuna-carousel-indicators li.active {
                background-color: #f59e0b;
                border-color: #042410;
                transform: scale(1.25);
                box-shadow: 0 0 8px rgba(245, 158, 11, 0.8);
            }
            .jamuna-carousel-caption {
                background: rgba(6, 56, 26, 0.8);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(245, 158, 11, 0.45);
                border-radius: 14px;
                padding: 12px 24px;
                bottom: 25px;
                max-width: 650px;
                margin: 0 auto;
                left: 50%;
                transform: translateX(-50%);
                box-shadow: 0 6px 20px rgba(0,0,0,0.35);
            }
            .jamuna-caption-title {
                color: #fbb03b;
                font-size: 20px;
                font-weight: 800;
                margin-bottom: 4px;
                letter-spacing: 0.5px;
            }
            .jamuna-caption-desc {
                color: #f8fafc;
                font-size: 13.5px;
                margin-bottom: 0;
                line-height: 1.4;
            }

            @media (max-width: 767.98px) {
                .jamuna-carousel-btn {
                    opacity: 0.85 !important;
                    width: 34px;
                    height: 34px;
                    font-size: 13px;
                }
                .jamuna-carousel-btn.prev-btn { left: 8px; }
                .jamuna-carousel-btn.next-btn { right: 8px; }
                .jamuna-carousel-indicators { bottom: 6px; }
                .jamuna-carousel-indicators li { width: 9px; height: 9px; }
            }
            @media (max-width: 767.98px) {
                .stock-card-img {
                    height: 140px !important;
                }
                /* Section Headings & Text Scale on Mobile */
                .section-header h2,
                .section-header h3 {
                    font-size: 22px !important;
                    line-height: 1.35 !important;
                    margin-top: 4px !important;
                }
                .section-header p {
                    font-size: 13.5px !important;
                    line-height: 1.5 !important;
                    margin-bottom: 0 !important;
                }
                .section-header .badge {
                    font-size: 12px !important;
                    padding: 5px 12px !important;
                }
                .section-header {
                    margin-bottom: 16px !important;
                }
                .our-packages-section,
                .card-benefits-section,
                .why-choose-section,
                .jamuna-live-stocks-section {
                    padding-top: 22px !important;
                    padding-bottom: 22px !important;
                }
                .ourPackagesSwiper,
                .cardBenefitsSwiper {
                    padding-bottom: 22px !important;
                }
            }
        </style>

        @php
            $activeSliders = [];
            for ($i = 1; $i <= 10; $i++) {
                $imgName = setting('slider' . $i . '_img');
                if (!empty($imgName) && file_exists(public_path('upload/slider/' . $imgName))) {
                    $activeSliders[] = [
                        'id' => $i,
                        'img' => asset('upload/slider/' . $imgName),
                        'text' => setting('slider' . $i . '_text'),
                        'description' => setting('slider' . $i . '_description'),
                    ];
                }
            }
        @endphp

        @if(!empty($activeSliders) && count($activeSliders) > 0)
        <!-- Jamuna Hero Dynamic Slider Section (Database Driven - Appears ONLY when sliders are present) -->
        <section class="jamuna-hero-section">
            <div class="jamuna-hero-banner">
                <div id="jamunaHeroCarousel" class="carousel slide" data-ride="carousel" data-bs-ride="carousel" data-interval="4000">
                    @if(count($activeSliders) > 1)
                    <!-- Carousel Indicators -->
                    <ol class="carousel-indicators jamuna-carousel-indicators">
                        @foreach($activeSliders as $idx => $s)
                            <li data-target="#jamunaHeroCarousel" data-bs-target="#jamunaHeroCarousel" data-slide-to="{{ $idx }}" data-bs-slide-to="{{ $idx }}" class="{{ $idx === 0 ? 'active' : '' }}"></li>
                        @endforeach
                    </ol>
                    @endif

                    <!-- Carousel Slides -->
                    <div class="carousel-inner">
                        @foreach($activeSliders as $idx => $s)
                            <div class="carousel-item {{ $idx === 0 ? 'active' : '' }}" data-interval="4000">
                                <img src="{{ $s['img'] }}" alt="{{ $s['text'] ?: (setting('title') ?: 'Slide') }}" class="d-block w-100 img-fluid jamuna-slider-img">
                                @if(!empty($s['text']) || !empty($s['description']))
                                <div class="carousel-caption jamuna-carousel-caption d-none d-md-block">
                                    @if(!empty($s['text']))
                                        <h3 class="jamuna-caption-title">{{ $s['text'] }}</h3>
                                    @endif
                                    @if(!empty($s['description']))
                                        <p class="jamuna-caption-desc">{{ $s['description'] }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if(count($activeSliders) > 1)
                    <!-- Navigation Arrows -->
                    <a class="carousel-control-prev jamuna-carousel-btn prev-btn" href="#jamunaHeroCarousel" role="button" data-slide="prev" data-bs-slide="prev" title="Previous Slide">
                        <i class="fa fa-chevron-left"></i>
                    </a>
                    <a class="carousel-control-next jamuna-carousel-btn next-btn" href="#jamunaHeroCarousel" role="button" data-slide="next" data-bs-slide="next" title="Next Slide">
                        <i class="fa fa-chevron-right"></i>
                    </a>
                    @endif
                </div>
            </div>
        </section>

        @if(count($activeSliders) > 1)
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if (typeof jQuery !== 'undefined' && typeof jQuery.fn.carousel === 'function') {
                    jQuery('#jamunaHeroCarousel').carousel({
                        interval: 4000,
                        pause: 'hover',
                        wrap: true
                    });
                }
            });
        </script>
        @endif
        @endif

        @php
            $stocks = App\Models\Stock::latest()->take(6)->get();
        @endphp
        @if($stocks->count() > 0)
        <!-- Live Agriculture Stock Market Section (Only Shown if Stocks Uploaded from Admin) -->
        <section class="jamuna-live-stocks-section" style="background: #f8fafc; padding-top: 32px; padding-bottom: 30px; border-bottom: 1px solid #e2e8f0;">
            <div class="container">
                <div class="text-center mb-3">
                    <span class="badge px-4 py-2 text-uppercase mb-2 shadow-sm" style="background: #0c5c2d; color: #fff; border-radius: 20px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; border: 1px solid #16a34a;">
                        <i class="fa fa-line-chart me-1 text-warning"></i> Market Update
                    </span>
                    <h2 class="fw-bold mb-1" style="font-size: 28px; color: #06381a; font-weight: 800;">Live Jamuna Stock & Market Rates</h2>
                    <p class="text-muted" style="max-width: 650px; margin: 0 auto; font-size: 14.5px; color: #64748b;">Monitor daily real-time buying and selling prices and make informed decisions.</p>
                </div>

                <div class="row">
                    @foreach($stocks as $stock)
                        @php
                            $images = App\Models\StockGallery::where('stock_id', $stock->id)->first();
                            $image_path = $images ? asset('upload/stock_images/'.$images->image) : asset('upload/images/backend/logo/'.setting('logo'));
                            $last_price = StockLastPricing($stock->id);
                            $latest_selling = $last_price ? $last_price->selling_price : 0;
                            $latest_buying = $last_price ? $last_price->buying_price : 0;
                        @endphp
                        <div class="col-6 col-md-6 col-lg-4 mb-3 px-2 px-md-3">
                            <div class="card h-100 border-0 shadow-sm rounded-4" style="transition: all 0.3s ease; background: #fff; border-radius: 16px; overflow: hidden; border: 1.5px solid #d1fae5;">
                                <div class="position-relative">
                                    <a href="{{ route('stock.detials', $stock->id) }}" class="d-block text-center">
                                        <img src="{{ $image_path }}" class="card-img-top w-100" alt="{{ $stock->stock_name }}" style="display: block;">
                                    </a>
                                    <span class="badge position-absolute top-0 end-0 m-2 m-md-3 px-2 px-md-3 py-1 py-md-2 shadow-sm" style="background: #16a34a; color: #fff; border-radius: 20px; font-weight: 600; font-size: 11px;">
                                        <i class="fa fa-line-chart me-1"></i> Live
                                    </span>
                                </div>
                                <div class="card-body p-2 p-md-3">
                                    <h4 class="fw-bold mb-1 mb-md-2 text-dark" title="{{ $stock->stock_name }}" style="font-size: 15px; font-weight: 700; color: #06381a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">{{ $stock->stock_name }}</h4>
                                    <p class="text-muted mb-2" style="font-size: 12px;">Available: <span class="fw-bold text-dark">{{ $stock->stock_quantity }}</span></p>

                                    <div class="d-flex justify-content-between align-items-center p-2 p-md-3 rounded-3 mb-2 mb-md-3" style="background: #f0fdf4; border-radius: 10px; border: 1px solid #bbf7d0;">
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 10px;">Selling Price</small>
                                            <strong style="font-size: 14px; color: #15803d;">BDT {{ number_format($latest_selling) }}</strong>
                                        </div>
                                        <div style="width: 1px; height: 26px; background: #86efac;"></div>
                                        <div>
                                            <small class="text-muted d-block" style="font-size: 10px;">Buying Price</small>
                                            <strong style="font-size: 14px; color: #0c5c2d;">BDT {{ number_format($latest_buying) }}</strong>
                                        </div>
                                    </div>

                                    <a href="{{ route('stock.detials', $stock->id) }}" class="btn w-100 fw-bold py-1 py-md-2" style="border: 1.5px solid #0c5c2d; color: #0c5c2d; background: transparent; border-radius: 8px; font-size: 12px; text-decoration: none; display: block; text-align: center; transition: all 0.2s ease;">
                                        Details <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('frontend.stocks') }}" class="btn btn-lg px-4 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #0e5e2e, #06381a); border: 2px solid #f59e0b; font-size: 15px; font-weight: 700; padding: 10px 30px; border-radius: 30px; color: #fff; text-decoration: none; display: inline-block;">
                        View All Stocks & Market Rates <i class="fa fa-chevron-right ms-2 text-warning"></i>
                    </a>
                </div>
            </div>
        </section>
        @endif

        <!-- Our Packages Slider Section -->
        @php
            $our_packages = \App\Models\OurPackage::where('status', 1)->latest()->get();
        @endphp
        <div id="our-packages" class="container-fluid no-left-padding no-right-padding our-packages-section" style="padding-top: 35px; padding-bottom: 25px; background: #ffffff; border-top: 1px solid #d1fae5; border-bottom: 1px solid #d1fae5;">
            <div class="container">
                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-3">
                    <span class="badge px-4 py-2 text-uppercase mb-2 shadow-sm" style="background: #0c5c2d; color: #fff; border-radius: 20px; font-size: 13px; font-weight: 700; letter-spacing: 0.5px; border: 1px solid #16a34a;">
                        <i class="fa fa-cubes me-1 text-warning"></i> Membership Packages
                    </span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #06381a; margin-top: 6px;"><span style="color: #0c5c2d;">Our </span>Packages</h3>
                    <p style="color: #64748b; font-size: 14.5px; margin-bottom: 0;">Explore our special membership process and packages.</p>
                </div>

                @if(count($our_packages) > 0)
                <div class="swiper ourPackagesSwiper" style="padding-bottom: 25px; position: relative;">
                    <div class="swiper-wrapper">
                        @foreach($our_packages as $pkg)
                        <div class="swiper-slide">
                            <div class="package-card shadow-sm rounded-4 overflow-hidden bg-white" style="border: 1px solid #cbd5e1; border-radius: 14px; transition: transform 0.3s ease, box-shadow 0.3s ease;">
                                <div class="package-img-box" style="position: relative; overflow: hidden; background: #f1f5f9; text-align: center;">
                                    <img src="{{ asset($pkg->image) }}" alt="Our Package Image" class="img-fluid w-100" style="max-width: 100%; height: auto; border-radius: 14px; display: block;">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- Pagination Dots Only -->
                    <div class="swiper-pagination"></div>
                </div>
                @else
                <div class="text-center py-5 bg-white rounded-4 shadow-sm" style="border: 2px dashed #cbd5e1; border-radius: 16px;">
                    <i class="fa fa-images text-muted mb-3" style="font-size: 48px;"></i>
                    <h5 style="color: #475569; font-weight: 600;">Please upload 'Our Packages' images from Admin Panel</h5>
                    <p style="color: #94a3b8; font-size: 14px;">Upload images from Admin Sidebar > Our Packages to display them in this slider.</p>
                </div>
                @endif
            </div>
        </div>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.querySelector('.ourPackagesSwiper')) {
                    var swiper = new Swiper(".ourPackagesSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: true,
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 3,
                                spaceBetween: 30,
                            },
                        },
                    });
                }
            });
        </script>

        <!-- Krishi SME Card & Gold Card Benefits Section -->
        @php
            $card_benefits = \App\Models\CardBenefit::where('status', 1)->orderBy('order_num', 'asc')->get();
        @endphp
        @if(count($card_benefits) > 0)
        <div id="card-benefits" class="container-fluid no-left-padding no-right-padding card-benefits-section" style="padding-top: 35px; padding-bottom: 25px; background: linear-gradient(180deg, #f0fdf4 0%, #edf2f7 100%); border-bottom: 1px solid #d1fae5;">
            <div class="container">
                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-3">
                    <span class="badge px-4 py-2 text-uppercase fw-bold mb-2 shadow-sm" style="background: #0c5c2d; color: #ffffff; font-size: 13px; letter-spacing: 1px; border-radius: 30px; border: 1px solid #16a34a;">
                        <i class="fa fa-id-card me-1 text-warning"></i> Investor Relations & Membership Cards
                    </span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #06381a; margin-top: 6px;">
                        <span style="color: #0c5c2d;">Card</span> Benefits & Guidelines
                    </h3>
                    <p style="color: #64748b; font-size: 14.5px; max-width: 680px; margin: 0 auto;">
                        Exclusive privileges, investment policies and special offers of Jamuna SME and Gold Cards
                    </p>
                </div>

                <!-- Card Slider / Grid Container -->
                <div class="swiper cardBenefitsSwiper" style="padding-bottom: 25px; position: relative;">
                    <div class="swiper-wrapper">
                        @foreach($card_benefits as $cardItem)
                        @php
                            $isGold = $cardItem->card_color_theme == 'gold' || $cardItem->card_type == 'gold';
                            $isRed = $cardItem->card_color_theme == 'red' || $cardItem->card_type == 'red';
                            
                            $themeBorder = $isGold ? '#eab308' : ($isRed ? '#ef4444' : '#16a34a');
                            $themeGradient = $isGold ? 'linear-gradient(135deg, #92400e 0%, #d97706 50%, #f59e0b 100%)' : ($isRed ? 'linear-gradient(135deg, #7f1d1d 0%, #dc2626 50%, #ef4444 100%)' : 'linear-gradient(135deg, #06381a 0%, #0c5c2d 50%, #16a34a 100%)');
                            $badgeBg = $isGold ? '#fef3c7' : ($isRed ? '#fee2e2' : '#dcfce7');
                            $badgeColor = $isGold ? '#92400e' : ($isRed ? '#991b1b' : '#0c5c2d');
                            $btnGradient = $isGold ? 'linear-gradient(135deg, #d97706, #b45309)' : ($isRed ? 'linear-gradient(135deg, #dc2626, #991b1b)' : 'linear-gradient(135deg, #0e5e2e, #06381a)');
                        @endphp
                        <div class="swiper-slide" style="height: auto;">
                            <div class="card-benefit-box h-100 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column" style="border: 2px solid {{ $themeBorder }}; border-radius: 22px; transition: all 0.35s ease; position: relative;">
                                <!-- Top Card Banner -->
                                <div class="card-top-header p-4 text-white position-relative" style="background: {{ $themeGradient }};">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge px-3 py-1 fw-bold shadow-sm" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border-radius: 20px; font-size: 13px;">
                                            <i class="fa fa-star me-1"></i> {{ $cardItem->badge_text ?: ($isGold ? 'Gold Membership' : 'Standard Membership') }}
                                        </span>
                                        @if($cardItem->validity)
                                        <span class="text-white-50 small fw-bold">
                                            <i class="fa fa-calendar-alt me-1"></i> Validity: {{ $cardItem->validity }}
                                        </span>
                                        @endif
                                    </div>
                                    <h4 class="fw-bold mb-1 text-white" style="font-size: 22px;">{{ $cardItem->card_name }}</h4>
                                    @if($cardItem->card_number_sample)
                                        <span class="badge bg-black bg-opacity-25 text-white font-monospace small px-2 py-1">{{ $cardItem->card_number_sample }}</span>
                                    @endif
                                </div>

                                <!-- Card Image Preview -->
                                @if((!empty($cardItem->image) && file_exists(public_path($cardItem->image))) || (!empty($cardItem->brochure_image) && file_exists(public_path($cardItem->brochure_image))))
                                <div class="card-visual-box text-center p-3 position-relative" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                                    @if(!empty($cardItem->image) && file_exists(public_path($cardItem->image)))
                                        <img src="{{ asset($cardItem->image) }}" alt="{{ $cardItem->card_name }}" class="img-fluid rounded-3 shadow" style="max-height: 190px; object-fit: contain; transform: perspective(800px) rotateX(4deg); transition: transform 0.3s ease;">
                                    @endif

                                    @if(!empty($cardItem->brochure_image) && file_exists(public_path($cardItem->brochure_image)))
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-dark px-3 py-1" style="font-size: 12px; border-radius: 20px;" data-bs-toggle="modal" data-bs-target="#brochureModal{{ $cardItem->id }}" data-toggle="modal" data-target="#brochureModal{{ $cardItem->id }}">
                                            <i class="fa fa-eye me-1"></i> View Official Brochure
                                        </button>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                <!-- Card Body & Facilities List -->
                                <div class="card-benefit-body p-4 d-flex flex-column flex-grow-1">
                                    @if($cardItem->short_description)
                                        <p class="text-muted small mb-3" style="line-height: 1.6; border-left: 3px solid {{ $themeBorder }}; padding-left: 12px;">
                                            {{ $cardItem->short_description }}
                                        </p>
                                    @endif

                                    <!-- Key Metrics / Specification Badges -->
                                    <div class="row g-2 mb-3">
                                        @if($cardItem->card_fee)
                                        <div class="col-12">
                                             <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-receipt text-primary me-1"></i> Card Fee:</small>
                                                <span class="badge bg-white text-dark border small fw-bold">{{ $cardItem->card_fee }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if(!empty($cardItem->investment_limit) && trim($cardItem->investment_limit) !== '')
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-chart-line text-secondary me-1"></i> Investment Limit:</small>
                                                <span class="badge bg-white text-dark border small fw-bold" style="background: #ffffff !important; color: #1e293b !important; border: 1px solid #cbd5e1 !important; font-size: 12.5px; font-weight: 600;">{{ $cardItem->investment_limit }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($cardItem->monthly_profit)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border">
                                                <small class="fw-bold text-dark d-block mb-1"><i class="fa fa-coins text-warning me-1"></i> Dividend Policy:</small>
                                                <span class="text-secondary small">{{ $cardItem->monthly_profit }}</span>
                                            </div>
                                        </div>
                                        @endif

                                        @if($cardItem->withdrawal_notice)
                                        <div class="col-12">
                                            <div class="p-2 rounded bg-light border d-flex align-items-center justify-content-between">
                                                <small class="fw-bold text-dark"><i class="fa fa-clock text-danger me-1"></i> Withdrawal Notice:</small>
                                                <span class="text-danger small fw-bold">{{ $cardItem->withdrawal_notice }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Rules / Facilities List -->
                                    <div class="facilities-container flex-grow-1 mb-4">
                                        <h6 class="fw-bold text-dark mb-3" style="font-size: 15px;">
                                            <i class="fa fa-list-check me-1 text-primary"></i> Rules & Special Privileges:
                                        </h6>
                                        @if(is_array($cardItem->facilities) && count($cardItem->facilities) > 0)
                                            <ul class="list-unstyled mb-0">
                                                @foreach($cardItem->facilities as $facility)
                                                    <li class="d-flex align-items-start mb-2 pb-1" style="font-size: 13.5px; color: #334155; line-height: 1.55;">
                                                        <i class="fa fa-check-circle me-2 mt-1 text-success flex-shrink-0" style="font-size: 14px;"></i>
                                                        <span>{{ $facility }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p class="text-muted small">No rules or privileges listed.</p>
                                        @endif
                                    </div>

                                    <!-- Action Button -->
                                    <div class="mt-auto pt-3 border-top">
                                        <a href="{{ url($cardItem->action_button_url ?: '/register') }}" class="btn w-100 py-2 text-white fw-bold shadow-sm rounded-3 d-flex align-items-center justify-content-center" style="background: {{ $btnGradient }}; border: none; font-size: 15px; transition: all 0.3s ease;">
                                            <span>{{ $cardItem->action_button_text ?: 'Apply for Card' }}</span>
                                            <i class="fa fa-arrow-right ms-2"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for full brochure preview -->
                        @if($cardItem->brochure_image && file_exists(public_path($cardItem->brochure_image)))
                        <div class="modal fade" id="brochureModal{{ $cardItem->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                                    <div class="modal-header py-3 text-white" style="background: {{ $themeGradient }};">
                                        <h5 class="modal-title fw-bold text-white"><i class="fa fa-file-image me-2"></i> {{ $cardItem->card_name }} - Official Brochure</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-0 text-center bg-dark">
                                        <img src="{{ asset($cardItem->brochure_image) }}" alt="Brochure" class="img-fluid w-100" style="max-height: 85vh; object-fit: contain;">
                                    </div>
                                    <div class="modal-footer bg-light py-2">
                                        <a href="{{ asset($cardItem->brochure_image) }}" download class="btn btn-sm btn-primary">
                                            <i class="fa fa-download me-1"></i> Download Brochure
                                        </a>
                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @endforeach
                    </div>
                    <!-- Pagination Dots -->
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (document.querySelector('.cardBenefitsSwiper')) {
                    new Swiper(".cardBenefitsSwiper", {
                        slidesPerView: 1,
                        spaceBetween: 25,
                        loop: {{ count($card_benefits) > 2 ? 'true' : 'false' }},
                        autoplay: {
                            delay: 5000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            768: {
                                slidesPerView: 2,
                                spaceBetween: 25,
                            },
                            1024: {
                                slidesPerView: 2,
                                spaceBetween: 30,
                            },
                        },
                    });
                }
            });
        </script>
        @endif

        <!-- Why Choose Section -->
        <style>
            .why-choose-card {
                background: #ffffff;
                border: 1px solid #e2e8f0;
                border-radius: 18px;
                padding: 28px 24px;
                height: 100%;
                display: flex;
                flex-direction: column;
                transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
                position: relative;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
            }
            .why-choose-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #0c5c2d, #f59e0b);
                opacity: 0;
                transition: opacity 0.35s ease;
            }
            .why-choose-card:hover {
                transform: translateY(-8px);
                box-shadow: 0 20px 30px -10px rgba(12, 92, 45, 0.22), 0 8px 12px -6px rgba(0, 0, 0, 0.06);
                border-color: rgba(12, 92, 45, 0.4);
            }
            .why-choose-card:hover::before {
                opacity: 1;
            }
            .why-choose-card .feature-img-wrapper {
                width: 75px;
                height: 75px;
                border-radius: 16px;
                background: #f0fdf4;
                border: 1px solid #bbf7d0;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 20px;
                padding: 10px;
                transition: all 0.35s ease;
            }
            .why-choose-card:hover .feature-img-wrapper {
                background: #dcfce7;
                transform: scale(1.08) rotate(-2deg);
                border-color: #86efac;
            }
            .why-choose-card .feature-img-wrapper img {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                display: block;
            }
            .why-choose-card .feature-title {
                font-size: 19px;
                font-weight: 700;
                color: #06381a;
                margin-bottom: 12px;
                transition: color 0.3s ease;
            }
            .why-choose-card:hover .feature-title {
                color: #0c5c2d;
            }
            .why-choose-card .feature-desc {
                color: #64748b;
                font-size: 14.5px;
                line-height: 1.65;
                margin-bottom: 0;
            }
        </style>

        <div class="container-fluid no-left-padding no-right-padding why-choose-section why-choose-style-1" style="padding-top: 35px; padding-bottom: 35px; background: #f8fafc; border-bottom: 1px solid #d1fae5;">
            <!-- Container -->
            <div class="container">

                <!-- Section Header -->
                <div class="section-header section-header-1 text-center mb-4">
                    <span class="badge px-4 py-2 text-uppercase fw-bold mb-2 shadow-sm" style="background: #0c5c2d; color: #fff; font-size: 13px; letter-spacing: 1px; border-radius: 30px; border: 1px solid #16a34a;">
                        <i class="fa fa-gem me-1 text-warning"></i> Our Highlights
                    </span>
                    <h3 style="font-size: 28px; font-weight: 800; color: #06381a; margin-top: 6px;">
                        <span style="color: #0c5c2d;">Why </span>Choose Jamuna Stock Market?
                    </h3>
                    <p style="color: #64748b; font-size: 14.5px; max-width: 650px; margin: 0 auto;">
                        Our trusted services, modern facilities and reliable platform for agriculture and business sectors.
                    </p>
                </div><!-- Section Header /- -->

                @php
                    $all_features = App\Models\Feature::all();
                @endphp

                @if(count($all_features) > 0)
                <div class="row g-4 justify-content-center">
                    @foreach($all_features as $feature_box)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                @if(!empty($feature_box->image) && file_exists(public_path($feature_box->image)))
                                    <img src="{{ asset($feature_box->image) }}" alt="{{ $feature_box->title }}">
                                @else
                                    <i class="fa {{ $feature_box->icon ?: 'fa-check-circle' }}" style="font-size: 32px; color: {{ $feature_box->color ?? '#0c5c2d' }};"></i>
                                @endif
                            </div>
                            <h4 class="feature-title">{{ $feature_box->title }}</h4>
                            <p class="feature-desc">{{ $feature_box->description }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="row g-4 justify-content-center">
                    <!-- Default sample features if none created yet -->
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-shield" style="font-size: 32px; color: #0c5c2d;"></i>
                            </div>
                            <h4 class="feature-title">100% Safe & Trusted</h4>
                            <p class="feature-desc">All transactions and operations on our platform are maintained with the highest level of security.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-line-chart" style="font-size: 32px; color: #16a34a;"></i>
                            </div>
                            <h4 class="feature-title">Smart Stock Monitoring</h4>
                            <p class="feature-desc">Easily view daily and weekly real-time stock price updates and market performance analytics.</p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        <div class="why-choose-card">
                            <div class="feature-img-wrapper">
                                <i class="fa fa-headphones" style="font-size: 32px; color: #f59e0b;"></i>
                            </div>
                            <h4 class="feature-title">24/7 Dedicated Support</h4>
                            <p class="feature-desc">Our experienced support team is always available around the clock to provide any assistance.</p>
                        </div>
                    </div>
                </div>
                @endif

            </div><!-- Container /- -->
        </div><!-- Why Choose Section /- -->











    </main>

</div>

<script>
    (function() {
        function initHeroCarousel() {
            var carousel = document.getElementById('jamunaHeroCarousel');
            if (!carousel) return;

            // 1. Try jQuery & Bootstrap carousel plugin
            if (typeof jQuery !== 'undefined' && typeof jQuery.fn.carousel === 'function') {
                jQuery('#jamunaHeroCarousel').carousel({
                    interval: 4000,
                    pause: 'hover',
                    wrap: true
                });
                return;
            }

            // 2. Try Bootstrap 5 Vanilla Carousel
            if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
                new bootstrap.Carousel(carousel, {
                    interval: 4000,
                    wrap: true
                });
                return;
            }

            // 3. Robust Vanilla JS Carousel Fallback
            var items = carousel.querySelectorAll('.carousel-item');
            var indicators = carousel.querySelectorAll('.jamuna-carousel-indicators li');
            if (items.length <= 1) return;

            var current = 0;
            var timer = null;

            function goToSlide(n) {
                items[current].classList.remove('active');
                if (indicators[current]) indicators[current].classList.remove('active');

                current = (n + items.length) % items.length;

                items[current].classList.add('active');
                if (indicators[current]) indicators[current].classList.add('active');
            }

            function startTimer() {
                stopTimer();
                timer = setInterval(function() {
                    goToSlide(current + 1);
                }, 4000);
            }

            function stopTimer() {
                if (timer) clearInterval(timer);
            }

            carousel.addEventListener('mouseenter', stopTimer);
            carousel.addEventListener('mouseleave', startTimer);

            var prev = carousel.querySelector('.prev-btn');
            var next = carousel.querySelector('.next-btn');

            if (prev) {
                prev.addEventListener('click', function(e) {
                    e.preventDefault();
                    goToSlide(current - 1);
                    startTimer();
                });
            }

            if (next) {
                next.addEventListener('click', function(e) {
                    e.preventDefault();
                    goToSlide(current + 1);
                    startTimer();
                });
            }

            indicators.forEach(function(dot, idx) {
                dot.addEventListener('click', function() {
                    goToSlide(idx);
                    startTimer();
                });
            });

            startTimer();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHeroCarousel);
        } else {
            initHeroCarousel();
        }
    })();
</script>

@endsection
