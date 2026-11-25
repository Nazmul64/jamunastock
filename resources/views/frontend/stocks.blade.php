@extends('layouts.frontend.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="page-banner" style="background: linear-gradient(135deg, #093c1d 0%, #06381a 100%); padding: 40px 0; border-bottom: 3px solid #f59e0b;">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div>
                <h2 class="text-white fw-bold mb-1" style="font-size: 28px;">
                    <i class="fa fa-line-chart text-warning me-2"></i>Live Stock Market
                </h2>
                <p class="text-white-50 mb-0" style="font-size: 14px;">
                    Live market prices and stock list of all agricultural and commercial products
                </p>
            </div>
            <nav aria-label="breadcrumb" class="mt-2 mt-md-0">
                <ol class="breadcrumb mb-0 bg-transparent p-0">
                    <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-warning text-decoration-none"><i class="fa fa-home"></i> Home</a></li>
                    <li class="breadcrumb-item text-white active" aria-current="page">Stock Market</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<!-- Stock Grid Section -->
<section class="py-5" style="background: #f8fafc; min-height: 500px;">
    <div class="container">

        <!-- Search / Filter Bar -->
        <div class="row justify-content-between align-items-center mb-4 g-3">
            <div class="col-md-6 col-lg-5">
                <form action="{{ route('frontend.stocks') }}" method="GET" class="d-flex shadow-sm rounded-pill overflow-hidden bg-white" style="border: 1.5px solid #15803d; padding: 3px 5px 3px 18px;">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-0 shadow-none" placeholder="Search by stock name..." style="font-size: 14px;">
                    <button type="submit" class="btn px-4 fw-bold text-dark rounded-pill" style="background: linear-gradient(180deg, #fbb03b 0%, #d97706 100%); white-space: nowrap;">
                        <i class="fa fa-search me-1"></i> Search
                    </button>
                </form>
            </div>
            <div class="col-md-6 col-lg-4 text-md-end">
                <span class="badge bg-white text-dark shadow-sm px-3 py-2 border rounded-pill" style="font-size: 13px;">
                    <i class="fa fa-cubes text-success me-1"></i> Total Stocks: <strong>{{ $all_stocks->total() }}</strong>
                </span>
            </div>
        </div>

        @if($all_stocks->count() > 0)
            <div class="row g-3">
                @foreach($all_stocks as $stock)
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
                                    <img src="{{ $image_path }}" class="card-img-top w-100" alt="{{ $stock->stock_name }}" style="height: 165px; object-fit: cover; display: block;">
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

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $all_stocks->links() }}
            </div>
        @else
            <div class="text-center py-5 bg-white rounded-4 shadow-sm" style="border: 1px dashed #cbd5e1; border-radius: 16px; padding: 60px 20px;">
                <i class="fa fa-cubes text-muted mb-3" style="font-size: 48px;"></i>
                <h4 class="fw-bold text-dark mb-2">No Stocks Found!</h4>
                <p class="text-muted mb-0">There are currently no active stock products listed.</p>
            </div>
        @endif

    </div>
</section>
@endsection

