@extends('layouts.backend.app')

@section('content')
@php
    $totalProducts = $stocks->count();
    $totalAvailableQty = $stocks->sum('available_stock');
    $totalInventoryValue = $stocks->sum(function($st) {
        return $st->available_stock * ($st->distribution_unit_price ?: 0);
    });
    $totalAllocatedQty = $stocks->sum('allocated_stock');
@endphp

<style>
    /* Central Warehouse Stock Modern Styling */
    .stock-kpi-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 22px 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }
    .stock-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    .stock-kpi-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        flex-shrink: 0;
    }
    .stock-kpi-value {
        font-size: 23px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        line-height: 1.2;
    }
    .stock-kpi-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748b;
        margin: 0;
    }

    /* Product Stock Card */
    .product-stock-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
        padding: 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .product-stock-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.09);
        border-color: #cbd5e1;
    }
    .product-stock-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #10b981, #059669);
    }
    .stock-card-rice::before { background: linear-gradient(90deg, #f59e0b, #d97706); }
    .stock-card-pulses::before { background: linear-gradient(90deg, #f97316, #ea580c); }
    .stock-card-oil::before { background: linear-gradient(90deg, #eab308, #ca8a04); }
    .stock-card-sugar::before { background: linear-gradient(90deg, #06b6d4, #0891b2); }
    .stock-card-salt::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
    .stock-card-flour::before { background: linear-gradient(90deg, #10b981, #059669); }

    .prod-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .prod-icon-rice { background: rgba(245, 158, 11, 0.12); color: #d97706; }
    .prod-icon-pulses { background: rgba(249, 115, 22, 0.12); color: #ea580c; }
    .prod-icon-oil { background: rgba(234, 179, 8, 0.12); color: #ca8a04; }
    .prod-icon-sugar { background: rgba(6, 182, 212, 0.12); color: #0891b2; }
    .prod-icon-salt { background: rgba(139, 92, 246, 0.12); color: #7c3aed; }
    .prod-icon-flour { background: rgba(16, 185, 129, 0.12); color: #059669; }

    .stock-stat-box {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 14px;
        border: 1px solid #f1f5f9;
        margin: 12px 0;
    }
    .stock-price-badge {
        background: rgba(16, 185, 129, 0.12);
        color: #065f46;
        border: 1px solid rgba(16, 185, 129, 0.25);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Filter Pills */
    .filter-btn-pill {
        border-radius: 25px;
        padding: 6px 16px;
        font-size: 13px;
        font-weight: 600;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .filter-btn-pill:hover, .filter-btn-pill.active {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                <div>
                    <h4 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes me-2 text-success"></i> সেন্ট্রাল ওয়্যারহাউস লাইভ স্টক ও লেজার
                    </h4>
                    <p class="subtitle text-muted mb-0">সেন্ট্রাল গুদামের সামগ্রিক মজুদ, আগমন, এজেন্ট বরাদ্দ এবং ক্ষতির রিয়েল-টাইম খতিয়ান</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-warning px-3 py-2 fw-bold shadow-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#stockAdjustModal">
                        <i class="fa fa-sliders me-1"></i> স্টক সমন্বয় / ড্যামেজ এন্ট্রি
                    </button>
                    <a href="{{ route('admin.distribution.allocate') }}" class="btn btn-primary px-3 py-2 fw-bold shadow-sm rounded-pill">
                        <i class="fa fa-truck me-1"></i> এজেন্ট পয়েন্টে বরাদ্দ
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm border-0 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2 fs-16"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- 1. Top 4 Responsive Summary KPI Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stock-kpi-card">
                        <div class="stock-kpi-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                            <i class="fa fa-shopping-basket"></i>
                        </div>
                        <div>
                            <div class="stock-kpi-value">{{ $totalProducts }} <span style="font-size: 15px; font-weight: 600; color: #64748b;">আইটেম</span></div>
                            <p class="stock-kpi-label">মোট সক্রিয় পণ্য তালিকা</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stock-kpi-card">
                        <div class="stock-kpi-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                            <i class="fa fa-database"></i>
                        </div>
                        <div>
                            <div class="stock-kpi-value text-primary">{{ number_format($totalAvailableQty, 0) }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">কেজি/লিটার</span></div>
                            <p class="stock-kpi-label">বর্তমান কেন্দ্রীয় মজুদ (Available)</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stock-kpi-card">
                        <div class="stock-kpi-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                            <i class="fa fa-money"></i>
                        </div>
                        <div>
                            <div class="stock-kpi-value text-success">৳ {{ number_format($totalInventoryValue, 0) }}</div>
                            <p class="stock-kpi-label">মোট ওয়্যারহাউস মজুদ সম্পদ মূল্য</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="stock-kpi-card">
                        <div class="stock-kpi-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                            <i class="fa fa-truck"></i>
                        </div>
                        <div>
                            <div class="stock-kpi-value text-purple">{{ number_format($totalAllocatedQty, 0) }} <span style="font-size: 14px; font-weight: 600; color: #64748b;">কেজি/লিটার</span></div>
                            <p class="stock-kpi-label">এজেন্ট পয়েন্টে বরাদ্দকৃত স্টক</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Search & Category Filter Toolbar -->
            <div class="box shadow-sm border-0 mb-4" style="border-radius: 16px;">
                <div class="box-body p-3">
                    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                        <!-- Live Search Input -->
                        <div class="position-relative" style="max-width: 320px; width: 100%;">
                            <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" id="productSearchInput" class="form-control ps-5 rounded-pill" placeholder="পণ্যের নাম দিয়ে খুঁজুন..." style="height: 42px; border-color: #cbd5e1;">
                        </div>

                        <!-- Category Filters -->
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button type="button" class="filter-btn-pill active" data-filter="all">সকল পণ্য ({{ $stocks->count() }})</button>
                            <button type="button" class="filter-btn-pill" data-filter="চাল">চাল ও দানাশস্য</button>
                            <button type="button" class="filter-btn-pill" data-filter="ডাল">ডাল</button>
                            <button type="button" class="filter-btn-pill" data-filter="তেল">তেল</button>
                            <button type="button" class="filter-btn-pill" data-filter="চিনি">চিনি</button>
                            <button type="button" class="filter-btn-pill" data-filter="লবণ">লবণ</button>
                            <button type="button" class="filter-btn-pill" data-filter="আটা">আটা</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Responsive Live Stock Product Cards Grid -->
            <div class="row g-4 mb-4" id="productCardsGrid">
                @forelse($stocks as $st)
                @php
                    $pName = $st->product_name;
                    $unitPrice = $st->distribution_unit_price ?: 0;
                    $itemTotalValue = $st->available_stock * $unitPrice;
                    
                    // Determine theme & icon based on product name
                    $themeClass = 'stock-card-rice';
                    $iconClass = 'prod-icon-rice';
                    $icon = 'fa-leaf';

                    if (str_contains($pName, 'চাল') || str_contains($pName, 'Rice')) {
                        $themeClass = 'stock-card-rice';
                        $iconClass = 'prod-icon-rice';
                        $icon = 'fa-leaf';
                    } elseif (str_contains($pName, 'ডাল') || str_contains($pName, 'Lentil')) {
                        $themeClass = 'stock-card-pulses';
                        $iconClass = 'prod-icon-pulses';
                        $icon = 'fa-cubes';
                    } elseif (str_contains($pName, 'তেল') || str_contains($pName, 'Oil')) {
                        $themeClass = 'stock-card-oil';
                        $iconClass = 'prod-icon-oil';
                        $icon = 'fa-tint';
                    } elseif (str_contains($pName, 'চিনি') || str_contains($pName, 'Sugar')) {
                        $themeClass = 'stock-card-sugar';
                        $iconClass = 'prod-icon-sugar';
                        $icon = 'fa-shopping-basket';
                    } elseif (str_contains($pName, 'লবণ') || str_contains($pName, 'Salt')) {
                        $themeClass = 'stock-card-salt';
                        $iconClass = 'prod-icon-salt';
                        $icon = 'fa-snowflake-o';
                    } elseif (str_contains($pName, 'আটা') || str_contains($pName, 'Flour')) {
                        $themeClass = 'stock-card-flour';
                        $iconClass = 'prod-icon-flour';
                        $icon = 'fa-pagelines';
                    }

                    // Stock health status
                    $isHealthy = $st->available_stock > 1000;
                    $isLow = $st->available_stock > 0 && $st->available_stock <= 1000;
                    $isEmpty = $st->available_stock <= 0;
                @endphp

                <div class="col-12 col-md-6 col-xl-4 product-card-item" data-product-name="{{ strtolower($st->product_name) }}">
                    <div class="product-stock-card {{ $themeClass }}">
                        <div>
                            <!-- Header: Icon, Name & Badges -->
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="prod-icon-box {{ $iconClass }}">
                                        <i class="fa {{ $icon }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold text-dark mb-0 fs-16">{{ $st->product_name }}</h4>
                                        <small class="text-muted">{{ $st->category ?: 'কৃষি ভোগ্যপণ্য' }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-bold" style="font-size: 11px;">
                                    {{ $st->unit }}
                                </span>
                            </div>

                            <!-- Main Stock Available Amount -->
                            <div class="stock-stat-box">
                                <div class="d-flex align-items-baseline justify-content-between mb-1">
                                    <span class="fs-12 text-muted fw-bold">সেন্ট্রাল গুদাম মজুদ:</span>
                                    @if($isHealthy)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="fa fa-check-circle me-1"></i> পর্যাপ্ত স্টক
                                        </span>
                                    @elseif($isLow)
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="fa fa-exclamation-triangle me-1"></i> স্টক সীমিত
                                        </span>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1 rounded-pill" style="font-size: 11px;">
                                            <i class="fa fa-times-circle me-1"></i> স্টক শেষ
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex align-items-baseline gap-2">
                                    <h2 class="fw-bold mb-0 text-dark" style="font-size: 28px;">{{ number_format($st->available_stock, 1) }}</h2>
                                    <span class="fw-bold text-muted fs-14">{{ $st->unit }}</span>
                                </div>
                            </div>

                            <!-- Rates and Monetary Valuation -->
                            <div class="d-flex align-items-center justify-content-between pt-2 mb-3">
                                <div>
                                    <span class="text-muted d-block fs-11 fw-semibold">বিতরণ ইউনিট রেট:</span>
                                    <strong class="text-dark fs-14">৳ {{ number_format($unitPrice, 2) }} / {{ $st->unit }}</strong>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted d-block fs-11 fw-semibold">মোট মজুদ মূল্যমান:</span>
                                    <strong class="text-success fs-15">৳ {{ number_format($itemTotalValue, 2) }}</strong>
                                </div>
                            </div>

                            <!-- Flow Metrics: Allocated vs Distributed -->
                            <div class="p-2 rounded-3 bg-light border mb-3" style="font-size: 12px;">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block fs-11">এজেন্ট বরাদ্দ:</span>
                                        <strong class="text-primary">{{ number_format($st->allocated_stock, 1) }} {{ $st->unit }}</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="text-muted d-block fs-11">বিতরণ সম্পন্ন:</span>
                                        <strong class="text-dark">{{ number_format($st->distributed_stock, 1) }} {{ $st->unit }}</strong>
                                    </div>
                                    @if($st->damaged_stock > 0)
                                    <div class="col-12 mt-1 pt-1 border-top">
                                        <span class="text-danger fw-semibold fs-11">
                                            <i class="fa fa-trash me-1"></i> ক্ষতি / অপচয়: {{ number_format($st->damaged_stock, 1) }} {{ $st->unit }}
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer Action -->
                        <div class="pt-2 border-top mt-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 rounded-pill fw-bold quick-adjust-btn"
                                    data-bs-toggle="modal" data-bs-target="#stockAdjustModal"
                                    data-product="{{ $st->product_name }}">
                                <i class="fa fa-sliders me-1 text-warning"></i> স্টক সমন্বয় বা এন্ট্রি
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info py-5 text-center rounded-4">
                        <i class="fa fa-cubes fa-3x mb-3 text-secondary"></i>
                        <h4 class="fw-bold">কোনো স্টক পাওয়া যায়নি</h4>
                        <p class="text-muted mb-0">সেন্ট্রাল ওয়্যারহাউসে এখনো কোনো পণ্য স্টক এন্ট্রি করা হয়নি।</p>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- 4. Central Stock Ledger / Transactions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="box shadow-sm border-0" style="border-radius: 18px; overflow: hidden;">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center py-3">
                            <h4 class="box-title mb-0 fw-bold text-dark">
                                <i class="fa fa-book text-info me-2"></i> সেন্ট্রাল স্টক ট্রানজেকশন খতিয়ান (Stock Ledger)
                            </h4>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">সর্বশেষ লেনদেন সমূহ</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ট্রানজেকশন নং</th>
                                            <th>তারিখ</th>
                                            <th>লেনদেনের ধরন</th>
                                            <th>পণ্যের নাম</th>
                                            <th class="text-end">পরিমাণ</th>
                                            <th>উৎস (Source)</th>
                                            <th>গন্তব্য (Destination)</th>
                                            <th>মন্তব্য</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transactions as $tx)
                                        <tr>
                                            <td><span class="badge bg-secondary font-monospace">{{ $tx->transaction_no }}</span></td>
                                            <td><i class="fa fa-calendar me-1 text-muted"></i> {{ $tx->transaction_date }}</td>
                                            <td>
                                                @if($tx->transaction_type === 'purchase_receive')
                                                    <span class="badge bg-success rounded-pill px-3 py-1"><i class="fa fa-arrow-down me-1"></i> ক্রয় আগমন</span>
                                                @elseif($tx->transaction_type === 'agent_dispatch')
                                                    <span class="badge bg-primary rounded-pill px-3 py-1"><i class="fa fa-arrow-up me-1"></i> এজেন্ট চালান</span>
                                                @elseif($tx->transaction_type === 'damage_loss')
                                                    <span class="badge bg-danger rounded-pill px-3 py-1"><i class="fa fa-trash me-1"></i> অপচয় / ক্ষতি</span>
                                                @else
                                                    <span class="badge bg-info rounded-pill px-3 py-1"><i class="fa fa-refresh me-1"></i> সমন্বয়</span>
                                                @endif
                                            </td>
                                            <td><strong class="text-dark">{{ $tx->product_name }}</strong></td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($tx->quantity, 1) }} {{ $tx->unit }}</td>
                                            <td>{{ $tx->source ?: '-' }}</td>
                                            <td>{{ $tx->destination ?: '-' }}</td>
                                            <td class="text-muted small">{{ $tx->remarks ?: 'N/A' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5 text-muted">
                                                <i class="fa fa-file-text-o fa-2x mb-2 text-secondary d-block"></i>
                                                কোনো ট্রানজেকশন রেকর্ড পাওয়া যায়নি।
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($transactions->hasPages())
                        <div class="box-footer bg-light py-3">
                            {{ $transactions->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Modal: Adjust Stock or Record Damage -->
<div class="modal fade" id="stockAdjustModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 18px; overflow: hidden;">
            <form action="{{ route('admin.distribution.warehouse_stock.adjust') }}" method="POST">
                @csrf
                <div class="modal-header bg-warning text-dark py-3">
                    <h5 class="modal-title fw-bold"><i class="fa fa-sliders me-2"></i> স্টক সমন্বয় বা অপচয় এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">পণ্যের নাম <span class="text-danger">*</span></label>
                        <select name="product_name" id="modalProductSelect" class="form-select form-select-lg" required>
                            @foreach($stocks as $st)
                                <option value="{{ $st->product_name }}">
                                    {{ $st->product_name }} (মজুদ: {{ number_format($st->available_stock, 1) }} {{ $st->unit }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">অ্যাকশনের ধরন <span class="text-danger">*</span></label>
                        <select name="action_type" class="form-select" required>
                            <option value="stock_in">নতুন স্টক বৃদ্ধি (Manual Stock In)</option>
                            <option value="damage">ক্ষতি / অপচয় বাদ (Damage / Loss Out)</option>
                            <option value="adjustment">সাধারণ সমন্বয় (Audit Adjustment)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">পরিমাণ <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" name="quantity" class="form-control" placeholder="যেমন: 50.0" required min="0.1">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">কারণ বা বিবরণ</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="অপচয় বা সমন্বয়ের কারণ উল্লেখ করুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3">
                    <button type="button" class="btn btn-secondary px-3 rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning px-4 fw-bold shadow-sm rounded-pill">
                        <i class="fa fa-save me-1"></i> স্টক আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Live Product Card Search
    const searchInput = document.getElementById('productSearchInput');
    const cardItems = document.querySelectorAll('.product-card-item');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            cardItems.forEach(card => {
                const name = card.getAttribute('data-product-name');
                if (name.includes(query)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 2. Category Filter Pills
    const filterPills = document.querySelectorAll('.filter-btn-pill');
    filterPills.forEach(pill => {
        pill.addEventListener('click', function() {
            filterPills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            const filter = this.getAttribute('data-filter');
            cardItems.forEach(card => {
                const name = card.getAttribute('data-product-name');
                if (filter === 'all' || name.includes(filter)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });

    // 3. Auto-select Product in Modal
    const modalSelect = document.getElementById('modalProductSelect');
    document.querySelectorAll('.quick-adjust-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const prodName = this.getAttribute('data-product');
            if (modalSelect && prodName) {
                modalSelect.value = prodName;
            }
        });
    });
});
</script>
@endsection
