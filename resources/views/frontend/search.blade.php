@extends('layouts.frontend.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="page-banner" style="background: linear-gradient(135deg, #093c1d 0%, #06381a 100%); padding: 35px 0; border-bottom: 3px solid #f59e0b;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <h2 class="text-white fw-bold mb-1" style="font-size: 26px;">
                    <i class="fa fa-search text-warning me-2"></i>Search Results
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 14px;">
                    @if(!empty($keyword))
                        Showing results for "<strong>{{ $keyword }}</strong>"
                    @else
                        Please enter a keyword to search
                    @endif
                </p>
            </div>
            <nav aria-label="breadcrumb" class="mt-2 mt-md-0">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-warning text-decoration-none"><i class="fa fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Search</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Search Results Content Section -->
<section class="py-5" style="background: #f8fafc; min-height: 500px;">
    <div class="container">

        <!-- Search Bar at Top of Page for Refined Search -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 col-md-10">
                <form action="{{ route('frontend.search') }}" method="GET" class="shadow-sm" style="background: #fff; border-radius: 50px; padding: 6px 8px 6px 20px; display: flex; align-items: stretch; border: 2px solid #15803d;">
                    <input type="text" name="search" value="{{ $keyword }}" class="form-control border-0 shadow-none" placeholder="Search stocks, packages, cards..." style="font-size: 15px; outline: none;" required>
                    <button type="submit" class="btn px-4 fw-bold text-dark rounded-pill" style="background: linear-gradient(180deg, #fbb03b 0%, #d97706 100%); white-space: nowrap;">
                        <i class="fa fa-search me-1"></i> Search
                    </button>
                </form>
            </div>
        </div>

        @php
            $totalCount = $stocks->count() + $packages->count() + $cards->count();
        @endphp

        @if($totalCount === 0)
            <div class="text-center py-5 bg-white rounded-4 shadow-sm" style="border: 1px dashed #cbd5e1; border-radius: 16px; padding: 60px 20px;">
                <div style="width: 80px; height: 80px; line-height: 80px; border-radius: 50%; background: #fee2e2; color: #dc2626; margin: 0 auto 20px auto; font-size: 36px;">
                    <i class="fa fa-search"></i>
                </div>
                <h4 class="fw-bold text-dark mb-2">No Results Found!</h4>
                <p class="text-muted mb-4" style="font-size: 15px;">No stocks, packages, or membership cards matched "{{ $keyword }}". Please check your spelling or explore all available stocks.</p>
                <a href="{{ route('frontend.stocks') }}" class="btn btn-success px-4 py-2 rounded-pill fw-bold" style="background: #0c5c2d; border-color: #0c5c2d;">
                    <i class="fa fa-th-large me-1"></i> View All Stocks & Market
                </a>
            </div>
        @else

            <!-- 1. Matching Stocks Section -->
            @if($stocks->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge px-3 py-2 text-uppercase me-2" style="background: #0c5c2d; color: #fff; border-radius: 12px; font-size: 13px;">
                            <i class="fa fa-line-chart text-warning me-1"></i> Stock Products ({{ $stocks->count() }})
                        </span>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 20px;">Matching Stocks</h4>
                    </div>

                    <div class="row g-3">
                        @foreach($stocks as $stock)
                            @php
                                $images = App\Models\StockGallery::where('stock_id', $stock->id)->first();
                                $image_path = $images ? asset('upload/stock_images/' . $images->image) : asset('upload/images/backend/logo/' . setting('logo'));
                                $last_price = StockLastPricing($stock->id);
                                $latest_selling = $last_price ? $last_price->selling_price : 0;
                                $latest_buying = $last_price ? $last_price->buying_price : 0;
                            @endphp
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #fff; border: 1.5px solid #d1fae5; border-radius: 14px;">
                                    <div class="position-relative text-center">
                                        <a href="{{ route('stock.detials', $stock->id) }}" class="d-block">
                                            <img src="{{ $image_path }}" class="card-img-top w-100" alt="{{ $stock->stock_name }}" style="height: 160px; object-fit: cover; display: block;">
                                        </a>
                                        <span class="badge position-absolute top-0 end-0 m-2 px-2 py-1 shadow-sm" style="background: #16a34a; color: #fff; border-radius: 20px; font-size: 10px;">
                                            Live
                                        </span>
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="fw-bold mb-1 text-dark" style="font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $stock->stock_name }}">
                                            {{ $stock->stock_name }}
                                        </h5>
                                        <p class="text-muted mb-2" style="font-size: 12px;">Available: <strong class="text-dark">{{ $stock->stock_quantity }}</strong></p>

                                        <div class="d-flex justify-content-between align-items-center p-2 rounded-3 mb-2" style="background: #f0fdf4; border: 1px solid #bbf7d0; font-size: 12px;">
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 10px;">Selling Price</small>
                                                <strong style="color: #15803d;">BDT {{ number_format($latest_selling) }}</strong>
                                            </div>
                                            <div style="width: 1px; height: 22px; background: #86efac;"></div>
                                            <div>
                                                <small class="text-muted d-block" style="font-size: 10px;">Buying Price</small>
                                                <strong style="color: #0c5c2d;">BDT {{ number_format($latest_buying) }}</strong>
                                            </div>
                                        </div>

                                        <a href="{{ route('stock.detials', $stock->id) }}" class="btn btn-sm w-100 fw-bold" style="background: #0c5c2d; color: #fff; border-radius: 6px;">
                                            Details <i class="fa fa-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2. Matching Packages Section -->
            @if($packages->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge px-3 py-2 text-uppercase me-2" style="background: #d97706; color: #fff; border-radius: 12px; font-size: 13px;">
                            <i class="fa fa-cubes me-1"></i> Membership Packages ({{ $packages->count() }})
                        </span>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 20px;">Matching Packages</h4>
                    </div>

                    <div class="row g-3">
                        @foreach($packages as $pkg)
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden bg-white" style="border: 1px solid #cbd5e1; border-radius: 14px;">
                                    <div class="text-center p-2" style="background: #f8fafc;">
                                        <img src="{{ asset($pkg->image) }}" alt="Package" class="img-fluid w-100 rounded-3" style="max-height: 180px; object-fit: contain;">
                                    </div>
                                    <div class="card-body p-3 text-center">
                                        <h5 class="fw-bold mb-2 text-dark" style="font-size: 15px;">{{ $pkg->title ?: 'Our Package' }}</h5>
                                        <a href="{{ route('/') }}#our-packages" class="btn btn-sm btn-outline-warning fw-bold text-dark px-3 rounded-pill">
                                            View Package <i class="fa fa-chevron-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 3. Matching Cards Section -->
            @if($cards->count() > 0)
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-3">
                        <span class="badge px-3 py-2 text-uppercase me-2" style="background: #2563eb; color: #fff; border-radius: 12px; font-size: 13px;">
                            <i class="fa fa-id-card-o me-1"></i> Membership Cards ({{ $cards->count() }})
                        </span>
                        <h4 class="fw-bold mb-0 text-dark" style="font-size: 20px;">Matching Cards & Privileges</h4>
                    </div>

                    <div class="row g-3">
                        @foreach($cards as $card)
                            <div class="col-md-6 col-lg-4">
                                <div class="card h-100 border-0 shadow-sm rounded-4 p-4 bg-white" style="border-left: 5px solid #2563eb !important; border-radius: 14px;">
                                    <h5 class="fw-bold text-primary mb-1">{{ $card->card_name }}</h5>
                                    @if(!empty($card->card_type))
                                        <span class="badge bg-light text-primary border mb-2">{{ $card->card_type }}</span>
                                    @endif
                                    @if(!empty($card->facilities) && is_array($card->facilities))
                                        <ul class="list-unstyled mb-3" style="font-size: 13px;">
                                            @foreach(array_slice($card->facilities, 0, 4) as $facility)
                                                <li class="mb-1 text-muted"><i class="fa fa-check text-success me-2"></i>{{ $facility }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    <a href="{{ route('/') }}#card-benefits" class="btn btn-sm btn-outline-primary rounded-pill fw-bold mt-auto align-self-start">
                                        View Benefits <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        @endif

    </div>
</section>
@endsection
