@extends('layouts.agent.app')

@section('title', 'ড্যাশবোর্ড - ' . $agentPoint->name)

@section('content')
<style>
    /* Metric Cards - Modern Dark Theme */
    .metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .metric-card {
        border-radius: 18px;
        padding: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.08);
        position: relative;
        overflow: hidden;
    }

    .metric-card.blue   { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); }
    .metric-card.green  { background: linear-gradient(135deg, #065f46 0%, #047857 100%); }
    .metric-card.purple { background: linear-gradient(135deg, #581c87 0%, #6b21a8 100%); }
    .metric-card.orange { background: linear-gradient(135deg, #9a3412 0%, #c2410c 100%); }

    .metric-info h3 {
        font-size: 26px;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #ffffff;
        font-family: 'Sora', sans-serif;
    }

    .metric-info p {
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
        margin: 0;
        font-weight: 600;
    }

    .metric-icon-box {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.15);
        display: grid;
        place-items: center;
        font-size: 22px;
        color: #ffffff;
    }

    /* Period Filter Pills */
    .filter-pill-container {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    .filter-pill {
        padding: 7px 16px;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 600;
        color: #94a3b8;
        background: #111c44;
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .filter-pill:hover, .filter-pill.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    /* Verification Box */
    .verify-search-box {
        background: linear-gradient(135deg, #111c44 0%, #0e1738 100%);
        border: 1px solid rgba(59, 130, 246, 0.3);
        border-radius: 20px;
        padding: 26px;
        margin-bottom: 24px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
    }

    .member-result-card {
        background: #0c1427;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
    }

    .prod-delivery-table th {
        background: rgba(255, 255, 255, 0.04);
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #94a3b8;
    }

    .prod-delivery-table td {
        padding: 12px 14px;
        font-size: 13.5px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .live-amount-badge {
        font-size: 18px;
        font-weight: 800;
        color: #10b981;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        padding: 8px 18px;
        border-radius: 12px;
        display: inline-block;
    }
</style>

<!-- Section 33.13: Time Period Filter Strip -->
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
    <div class="filter-pill-container">
        <a href="{{ route('agent.dashboard', ['period' => 'today']) }}" class="filter-pill {{ $period === 'today' ? 'active' : '' }}">
            <i class="fa fa-calendar-check-o me-1"></i> আজ (Today)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'yesterday']) }}" class="filter-pill {{ $period === 'yesterday' ? 'active' : '' }}">
            গতকাল (Yesterday)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'this_week']) }}" class="filter-pill {{ $period === 'this_week' ? 'active' : '' }}">
            এই সপ্তাহ (This Week)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'last_week']) }}" class="filter-pill {{ $period === 'last_week' ? 'active' : '' }}">
            গত সপ্তাহ (Last Week)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'this_month']) }}" class="filter-pill {{ $period === 'this_month' ? 'active' : '' }}">
            এই মাস (This Month)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'last_month']) }}" class="filter-pill {{ $period === 'last_month' ? 'active' : '' }}">
            গত মাস (Last Month)
        </a>
        <a href="{{ route('agent.dashboard', ['period' => 'this_year']) }}" class="filter-pill {{ $period === 'this_year' ? 'active' : '' }}">
            এই বছর (This Year)
        </a>
    </div>

    <!-- Custom Date Filter Trigger Button -->
    <button class="btn btn-sm btn-outline-info rounded-pill px-3 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#customDateCollapse">
        <i class="fa fa-calendar me-1"></i> কাস্টম তারিখ নির্বাচন
    </button>
</div>

<!-- Custom Date Filter Collapse -->
<div class="collapse {{ $period === 'custom' ? 'show' : '' }} mb-4" id="customDateCollapse">
    <div class="dark-card p-3">
        <form method="GET" action="{{ route('agent.dashboard') }}" class="row g-2 align-items-end">
            <input type="hidden" name="period" value="custom">
            <div class="col-md-4">
                <label class="small text-muted mb-1">শুরুর তারিখ</label>
                <input type="date" name="from_date" value="{{ request('from_date', $startDate) }}" class="form-control dark-input">
            </div>
            <div class="col-md-4">
                <label class="small text-muted mb-1">শেষ তারিখ</label>
                <input type="date" name="to_date" value="{{ request('to_date', $endDate) }}" class="form-control dark-input">
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa fa-search me-1"></i> ফিল্টার প্রয়োগ করুন</button>
                <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary px-3" title="রিসেট"><i class="fa fa-refresh"></i></a>
            </div>
        </form>
    </div>
</div>

<!-- Section 33.7: 4 Main Financial & Stock KPI Metric Cards -->
<div class="metric-grid">
    <!-- Card 1: Total Market Amount (৳) -->
    <div class="metric-card green">
        <div class="metric-info">
            <h3>৳ {{ number_format($totalMarketAmountFiltered, 2) }}</h3>
            <p>বাজার বিতরণ মূল্য ({{ ucfirst(str_replace('_', ' ', $period)) }})</p>
        </div>
        <div class="metric-icon-box"><i class="fa fa-money"></i></div>
    </div>

    <!-- Card 2: Total Distributed Quantity -->
    <div class="metric-card blue">
        <div class="metric-info">
            <h3>{{ number_format($totalQtyFiltered, 1) }} <small style="font-size: 13px;">KG/Unit</small></h3>
            <p>মোট বিতরণকৃত পণ্য (Quantity)</p>
        </div>
        <div class="metric-icon-box"><i class="fa fa-shopping-basket"></i></div>
    </div>

    <!-- Card 3: Members Served -->
    <div class="metric-card purple">
        <div class="metric-info">
            <h3>{{ number_format($totalMembersServedFiltered) }} / {{ number_format($totalAssignedMembers) }}</h3>
            <p>বাজার গ্রহণকারী সদস্য (Served / Total)</p>
        </div>
        <div class="metric-icon-box"><i class="fa fa-users"></i></div>
    </div>

    <!-- Card 4: Local Remaining Stock -->
    <div class="metric-card orange">
        <div class="metric-info">
            <h3>{{ number_format($totalCurrentStockQty, 1) }} <small style="font-size: 13px;">KG</small></h3>
            <p>পয়েন্টে বর্তমান মজুদ (Remaining Stock)</p>
        </div>
        <div class="metric-icon-box"><i class="fa fa-cubes"></i></div>
    </div>
</div>

<!-- Daily, Weekly, Monthly & Yearly Financial Quick Strip (Section 33.7) -->
<div class="dark-card p-3 mb-4">
    <div class="row text-center g-2">
        <div class="col-md-3 col-6 border-end border-secondary">
            <span class="text-muted small d-block">আজকের বিতরণ (Today)</span>
            <h4 class="fw-bold text-success mb-0">৳ {{ number_format($todayMarketAmount, 2) }}</h4>
        </div>
        <div class="col-md-3 col-6 border-end border-secondary">
            <span class="text-muted small d-block">এই সপ্তাহের বিতরণ (This Week)</span>
            <h4 class="fw-bold text-primary mb-0">৳ {{ number_format($weeklyMarketAmount, 2) }}</h4>
        </div>
        <div class="col-md-3 col-6 border-end border-secondary">
            <span class="text-muted small d-block">চলতি মাসের বিতরণ (This Month)</span>
            <h4 class="fw-bold text-info mb-0">৳ {{ number_format($monthlyMarketAmount, 2) }}</h4>
        </div>
        <div class="col-md-3 col-6">
            <span class="text-muted small d-block">এই বছরের বিতরণ (This Year)</span>
            <h4 class="fw-bold text-warning mb-0">৳ {{ number_format($yearlyMarketAmount, 2) }}</h4>
        </div>
    </div>
</div>

<!-- Middle Section: Verification Engine (Left) & Product Distribution Breakdown (Right) -->
<div class="row">
    <!-- Left: Section 33.4, 33.5, 33.6: Fast Card Verification & Financial Delivery Engine -->
    <div class="col-lg-8 col-12">
        <div class="verify-search-box">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span style="width: 10px; height: 10px; border-radius: 50%; background: #3b82f6; box-shadow: 0 0 10px #3b82f6;"></span>
                    <h5 class="mb-0 fw-bold text-white">সদস্য কার্ড ভেরিফিকেশন ও ডেলিভারি প্রদান (Delivery Engine)</h5>
                </div>
                <span class="badge bg-primary px-3 py-2" style="border-radius: 8px;">Section 33.4 - 33.6</span>
            </div>
            <p class="text-muted small mb-4">
                সদস্যের কার্ড নম্বর (Card Number / Member ID) অথবা মোবাইল নম্বর লিখে সার্চ করুন। সিস্টেম সাথে সাথে অনুমোদিত চাহিদা, টাকার হিসাব ও স্টক যাচাই করবে।
            </p>

            <!-- Search Form -->
            <form id="verifyMemberForm" class="d-flex gap-2">
                <div class="position-relative flex-grow-1">
                    <i class="fa fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <input type="text" id="cardSearchInput" class="form-control dark-input ps-5" placeholder="উদাঃ EK-100258 অথবা 017XXXXXXXX" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary px-4 fw-bold d-flex align-items-center gap-2" id="searchBtn" style="border-radius: 10px;">
                    <i class="fa fa-id-card"></i> যাচাই করুন (Verify)
                </button>
            </form>

            <!-- Loading Spinner -->
            <div id="verifyLoading" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted small">সদস্যের তথ্য ও পণ্যের টাকার দর যাচাই করা হচ্ছে...</p>
            </div>

            <!-- Blocked / Error Alert -->
            <div id="verifyAlertBox" class="mt-3 d-none"></div>

            <!-- Verification Success & Delivery Entry Card -->
            <div id="memberResultCard" class="member-result-card d-none">
                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom border-secondary mb-3 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <img id="resMemberAvatar" src="https://ui-avatars.com/api/?name=Member" alt="Avatar" class="rounded-circle border" style="width: 52px; height: 52px; object-fit: cover;">
                        <div>
                            <h5 class="text-white fw-bold mb-1" id="resMemberName">-</h5>
                            <div class="d-flex gap-2 align-items-center small flex-wrap">
                                <span class="badge bg-success" id="resCardNumber">-</span>
                                <span class="text-muted" id="resPhone">-</span>
                                <span class="badge bg-dark border border-secondary" id="resArea">-</span>
                            </div>
                        </div>
                    </div>
                    <span class="status-pill success"><i class="fa fa-check"></i> অনুমোদিত সদস্য</span>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-white fw-bold mb-0"><i class="fa fa-shopping-basket text-warning me-2"></i>অনুমোদিত মাসিক বাজার ও আর্থিক হিসাব:</h6>
                    <div class="text-end">
                        <small class="text-muted d-block">প্রদেয় মোট বাজার মূল্য:</small>
                        <span class="live-amount-badge" id="liveTotalAmountDisplay">৳ 0.00</span>
                    </div>
                </div>

                <form id="deliverySubmitForm">
                    <input type="hidden" id="verifiedMemberId" name="member_id">
                    <input type="hidden" id="verifiedCardNumber" name="card_number">

                    <div class="table-responsive">
                        <table class="table dark-table prod-delivery-table">
                            <thead>
                                <tr>
                                    <th>পণ্য (Product)</th>
                                    <th class="text-end">অনুমোদিত</th>
                                    <th class="text-end">অবশিষ্ট</th>
                                    <th class="text-end">দর (Unit Rate)</th>
                                    <th class="text-end">পয়েন্ট স্টক</th>
                                    <th style="width: 140px;">প্রদান পরিমাণ (Qty)</th>
                                    <th class="text-end">মূল্য (Amount)</th>
                                </tr>
                            </thead>
                            <tbody id="resItemsTableBody">
                                <!-- Populated dynamically by JS -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <label class="small text-muted mb-1">বিতরণ মন্তব্য / নোট (ঐচ্ছিক):</label>
                        <input type="text" name="remarks" class="form-control dark-input" placeholder="উদাঃ পূর্ণ বাজার গ্রাহককে বুঝিয়ে দেওয়া হয়েছে">
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top border-secondary">
                        <button type="button" class="btn btn-secondary px-3" onclick="resetVerification()">বাতিল (Cancel)</button>
                        <button type="submit" class="btn btn-success px-4 fw-bold" id="confirmDeliveryBtn">
                            <i class="fa fa-check-circle me-1"></i> ডেলিভারি নিশ্চিত করুন (Confirm Delivery)
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Deliveries Feed -->
        <div class="dark-card">
            <div class="dark-card-header d-flex justify-content-between align-items-center">
                <h5 class="dark-card-title mb-0"><i class="fa fa-history text-danger me-2"></i>সাম্প্রতিক ডেলিভারি লেনদেন (Recent Transactions)</h5>
                <a href="{{ route('agent.delivery_history') }}" class="btn btn-sm btn-outline-secondary">সব দেখুন</a>
            </div>

            @if($recentDeliveries->isEmpty())
                <div class="text-center py-4 text-muted">
                    <i class="fa fa-inbox fs-1 mb-2"></i>
                    <p>এখনো কোনো ডেলিভারি রেকর্ড নেই।</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table dark-table mb-0">
                        <thead>
                            <tr>
                                <th>ভাউচার আইডি</th>
                                <th>সদস্যের নাম ও কার্ড</th>
                                <th>পণ্য তালিকা</th>
                                <th class="text-end">বাজার মূল্য</th>
                                <th>তারিখ ও সময়</th>
                                <th class="text-center">রশিদ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentDeliveries as $del)
                            <tr>
                                <td><span class="badge bg-dark border border-secondary font-monospace">{{ $del->delivery_no }}</span></td>
                                <td>
                                    <strong>{{ $del->member_name }}</strong><br>
                                    <small class="text-muted font-monospace">{{ $del->card_number }}</small>
                                </td>
                                <td>
                                    @foreach($del->items as $it)
                                        <span class="badge bg-info bg-opacity-25 text-info me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                                    @endforeach
                                </td>
                                <td class="text-end fw-bold text-success fs-14">
                                    ৳ {{ number_format($del->total_amount, 2) }}
                                </td>
                                <td>
                                    {{ date('d M, Y', strtotime($del->delivery_date)) }}<br>
                                    <small class="text-muted">{{ date('h:i A', strtotime($del->delivery_time)) }}</small>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('agent.receipt', $del->id) }}" target="_blank" class="btn btn-xs btn-outline-info" title="প্রিন্ট রশিদ">
                                        <i class="fa fa-print"></i> রশিদ
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Right: Section 33.7 Product-wise Summary & Weekly Trend -->
    <div class="col-lg-4 col-12">
        <!-- Filtered Product Distribution Breakdown Card -->
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-pie-chart text-success me-2"></i>পণ্যভিত্তিক বিতরণ খতিয়ান</h5>
            </div>
            <div class="p-3">
                @if($productDistributionFiltered->isEmpty())
                    <p class="text-muted text-center py-3 mb-0 small">নির্বাচিত সময়সীমায় কোনো পণ্য বিতরণ হয়নি।</p>
                @else
                    @foreach($productDistributionFiltered as $pDist)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-25">
                        <div>
                            <strong class="text-white d-block">{{ $pDist->product_name }}</strong>
                            <small class="text-muted">দর: ৳{{ number_format($pDist->avg_unit_price, 2) }} / {{ $pDist->unit }}</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-primary fs-13">{{ number_format($pDist->total_qty, 1) }} {{ $pDist->unit }}</span>
                            <div class="fw-bold text-success small mt-1">৳ {{ number_format($pDist->total_amount, 2) }}</div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Weekly Delivery Trend Spline Chart -->
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-line-chart text-info me-2"></i>গত ৭ দিনের বিতরণ ট্রেন্ড (৳)</h5>
            </div>
            <div class="p-3">
                <canvas id="weeklyAgentChart" height="200"></canvas>
            </div>
        </div>

        <!-- Incoming Supplier Supplies (SRD Section 6.1) -->
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-cubes text-info me-2"></i>ইনকামিং সাপ্লাই (Supplier Supplies)</h5>
                <a href="{{ route('agent.supplies.index') }}" class="btn btn-xs btn-outline-info">সব দেখুন</a>
            </div>
            <div class="p-3">
                @forelse($incomingSupplierSupplies ?? [] as $isup)
                <div class="p-3 rounded bg-dark border border-info mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-info font-monospace">{{ $isup->supply_code ?: ('#' . $isup->id) }}</strong>
                        <span class="badge bg-{{ $isup->status_meta['badge'] }}">{{ $isup->status_meta['label'] }}</span>
                    </div>
                    <div class="text-white fw-bold small mb-1">
                        {{ $isup->product_name }} — {{ floatval($isup->approved_quantity ?? $isup->quantity) }} {{ $isup->unit }}
                    </div>
                    <div class="small text-muted mb-2">
                        সাপ্লায়ার: {{ $isup->supplier->supplierProfile->company_name ?? $isup->supplier->name }} |
                        ইনভয়েস: {{ $isup->invoice_no }}
                        @if($isup->expected_delivery_date)
                            | তারিখ: {{ $isup->expected_delivery_date->format('d M, Y') }}
                        @endif
                    </div>
                    <a href="{{ route('agent.supplies.show', $isup->id) }}" class="btn btn-sm btn-success w-100 fw-bold">
                        <i class="fa fa-check-square-o me-1"></i> যাচাই ও রিসিভ করুন
                    </a>
                </div>
                @empty
                <p class="text-muted text-center py-2 mb-0 small">নতুন কোনো সাপ্লায়ার ইনকামিং পণ্য নেই।</p>
                @endforelse
            </div>
        </div>

        <!-- Pending Inbound Dispatches -->
        <div class="dark-card">
            <div class="dark-card-header">
                <h5 class="dark-card-title mb-0"><i class="fa fa-truck text-warning me-2"></i>সেন্ট্রাল থেকে আগত চালান</h5>
                <a href="{{ route('agent.dispatches') }}" class="btn btn-xs btn-outline-warning">সব চালান</a>
            </div>
            <div class="p-3">
                @forelse($pendingDispatches as $dsp)
                <div class="p-3 rounded bg-dark border border-secondary mb-2">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <strong class="text-info font-monospace">{{ $dsp->dispatch_no }}</strong>
                        <span class="badge bg-warning text-dark">ইন-ট্রানজিট</span>
                    </div>
                    <div class="small text-muted mb-2">
                        @foreach($dsp->items as $it)
                            {{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}@if(!$loop->last), @endif
                        @endforeach
                    </div>
                    <form action="{{ route('agent.dispatches.receive', $dsp->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success w-100 fw-bold">
                            <i class="fa fa-check me-1"></i> পণ্য রিসিভ নিশ্চিত করুন
                        </button>
                    </form>
                </div>
                @empty
                <p class="text-muted text-center py-2 mb-0 small">বর্তমানে কোনো নতুন চালান অপেক্ষমাণ নেই।</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let verifiedItems = [];

// 1. Handle Member Verification AJAX
document.getElementById('verifyMemberForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const query = document.getElementById('cardSearchInput').value.trim();
    if (!query) return;

    const loading = document.getElementById('verifyLoading');
    const alertBox = document.getElementById('verifyAlertBox');
    const resultCard = document.getElementById('memberResultCard');

    loading.classList.remove('d-none');
    alertBox.classList.add('d-none');
    resultCard.classList.add('d-none');

    fetch("{{ route('agent.verify_member') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ search_query: query })
    })
    .then(res => res.json().then(data => ({ status: res.status, body: data })))
    .then(({ status, body }) => {
        loading.classList.add('d-none');

        if (status === 200 && body.success) {
            // Success: Populate Member Card & Items
            const member = body.member;
            verifiedItems = body.items;

            document.getElementById('verifiedMemberId').value = member.id;
            document.getElementById('verifiedCardNumber').value = member.card_number;
            document.getElementById('resMemberName').innerText = member.name;
            document.getElementById('resCardNumber').innerText = member.card_number;
            document.getElementById('resPhone').innerText = member.phone || '';
            document.getElementById('resArea').innerText = member.area || '';
            if (member.avatar) {
                document.getElementById('resMemberAvatar').src = member.avatar;
            }

            // Build Items Table
            const tbody = document.getElementById('resItemsTableBody');
            tbody.innerHTML = '';

            verifiedItems.forEach((it, idx) => {
                const maxDeliver = Math.min(it.remaining_quantity, it.available_stock);
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><strong class="text-white">${it.product_name}</strong></td>
                    <td class="text-end">${it.approved_quantity} ${it.unit}</td>
                    <td class="text-end fw-bold text-warning">${it.remaining_quantity} ${it.unit}</td>
                    <td class="text-end text-info fw-bold">৳ ${parseFloat(it.unit_price).toFixed(2)}</td>
                    <td class="text-end ${it.available_stock > 0 ? 'text-success' : 'text-danger'}">
                        ${it.available_stock} ${it.unit}
                    </td>
                    <td>
                        <input type="hidden" name="deliveries[${idx}][order_id]" value="${it.order_id}">
                        <input type="number" step="0.5" min="0" max="${maxDeliver}" 
                               name="deliveries[${idx}][deliver_qty]" 
                               value="${maxDeliver}" 
                               class="form-control form-control-sm dark-input text-center fw-bold deliver-qty-input"
                               data-idx="${idx}"
                               data-price="${it.unit_price}"
                               oninput="calculateTotalAmount()"
                               ${maxDeliver <= 0 ? 'disabled' : ''}>
                    </td>
                    <td class="text-end fw-bold text-success line-amount" id="lineAmount_${idx}">
                        ৳ ${(maxDeliver * it.unit_price).toFixed(2)}
                    </td>
                `;
                tbody.appendChild(tr);
            });

            calculateTotalAmount();
            resultCard.classList.remove('d-none');

        } else {
            // Blocked or Not Found Alert
            alertBox.innerHTML = `
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-0" role="alert">
                    <i class="fa fa-exclamation-triangle fs-4"></i>
                    <div>${body.message || 'যাচাইকরণ ব্যর্থ হয়েছে।'}</div>
                </div>
            `;
            alertBox.classList.remove('d-none');
        }
    })
    .catch(err => {
        loading.classList.add('d-none');
        alertBox.innerHTML = `
            <div class="alert alert-danger mb-0">সার্ভারের সাথে সংযোগ স্থাপন করা যায়নি। অনুগ্রহ করে পুনরায় চেষ্টা করুন।</div>
        `;
        alertBox.classList.remove('d-none');
    });
});

// 2. Real-time Live Calculation of Total Market Collection Value
function calculateTotalAmount() {
    let grandTotal = 0;
    const inputs = document.querySelectorAll('.deliver-qty-input');
    inputs.forEach(input => {
        const qty = parseFloat(input.value) || 0;
        const price = parseFloat(input.dataset.price) || 0;
        const idx = input.dataset.idx;
        const lineTotal = qty * price;
        grandTotal += lineTotal;

        const lineEl = document.getElementById(`lineAmount_${idx}`);
        if (lineEl) {
            lineEl.innerText = `৳ ${lineTotal.toFixed(2)}`;
        }
    });

    document.getElementById('liveTotalAmountDisplay').innerText = `৳ ${grandTotal.toFixed(2)}`;
}

// 3. Confirm Delivery Submission
document.getElementById('deliverySubmitForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const btn = document.getElementById('confirmDeliveryBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> ডেলিভারি প্রসেসিং হচ্ছে...';

    const formData = new FormData(this);

    // Convert FormData to structured JSON payload
    const payload = {
        member_id: formData.get('member_id'),
        card_number: formData.get('card_number'),
        remarks: formData.get('remarks'),
        deliveries: []
    };

    let hasAnyQty = false;
    document.querySelectorAll('.deliver-qty-input').forEach(input => {
        const qty = parseFloat(input.value) || 0;
        const idx = input.dataset.idx;
        const orderId = document.querySelector(`input[name="deliveries[${idx}][order_id]"]`).value;
        if (qty > 0) hasAnyQty = true;

        payload.deliveries.push({
            order_id: orderId,
            deliver_qty: qty
        });
    });

    if (!hasAnyQty) {
        alert('অনুগ্রহ করে কমপক্ষে একটি পণ্যের পরিমাণ এন্ট্রি করুন।');
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check-circle me-1"></i> ডেলিভারি নিশ্চিত করুন (Confirm Delivery)';
        return;
    }

    fetch("{{ route('agent.delivery.store') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check-circle me-1"></i> ডেলিভারি নিশ্চিত করুন (Confirm Delivery)';

        if (data.success) {
            const alertBox = document.getElementById('verifyAlertBox');
            alertBox.innerHTML = `
                <div class="alert alert-success d-flex align-items-center justify-content-between p-3" role="alert">
                    <div>
                        <h5 class="mb-1"><i class="fa fa-check-circle me-2"></i> ${data.message}</h5>
                        <p class="mb-0 small">ভাউচার নম্বর: <strong>${data.delivery_no}</strong> | মোট বাজার মূল্য: <strong>৳ ${parseFloat(data.total_amount).toFixed(2)}</strong></p>
                    </div>
                    <a href="${data.receipt_url}" target="_blank" class="btn btn-light fw-bold text-success px-3">
                        <i class="fa fa-print me-1"></i> রশিদ প্রিন্ট করুন
                    </a>
                </div>
            `;
            alertBox.classList.remove('d-none');
            resetVerification();
            setTimeout(() => window.location.reload(), 3000);
        } else {
            alert(data.message || 'ডেলিভারি সংরক্ষণ করা সম্ভব হয়নি।');
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-check-circle me-1"></i> ডেলিভারি নিশ্চিত করুন (Confirm Delivery)';
        alert('সার্ভার এরর: ' + err.message);
    });
});

function resetVerification() {
    document.getElementById('memberResultCard').classList.add('d-none');
    document.getElementById('cardSearchInput').value = '';
    document.getElementById('cardSearchInput').focus();
    verifiedItems = [];
}

// 4. Render 7-day Weekly Trend Spline Chart
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('weeklyAgentChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'বাজার মূল্য (৳)',
                    data: {!! json_encode($chartAmounts) !!},
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    },
                    y: {
                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    }
});
</script>
@endsection
