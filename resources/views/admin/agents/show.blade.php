@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-user-secret me-2 text-primary"></i> {{ $agent->name }} — পূর্ণাঙ্গ মার্কেট রিপোর্ট ও ডেলিভারি অডিট</h4>
                    <p class="subtitle text-muted mb-0">
                        এজেন্ট আইডি: <strong class="text-dark font-monospace">{{ $agent->agent_id ?: ('AGT-'.$agent->id) }}</strong> | 
                        পয়েন্ট: <strong class="text-dark">{{ $point ? $point->name : 'N/A' }}</strong> | 
                        এলাকা: <strong class="text-dark">{{ $point && $point->areaModel ? $point->areaModel->name : ($agent->area ? $agent->area->name : 'N/A') }}</strong>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                        <i class="fa fa-print me-1"></i> রিপোর্ট প্রিন্ট
                    </button>
                    <a href="{{ route('admin.agents.edit', $agent->id) }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-pencil me-1"></i> এডিট
                    </a>
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> এজেন্ট তালিকা
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Agent Profile Summary Header Card -->
            <div class="box shadow-sm mb-4 border-top border-primary border-3">
                <div class="box-body">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            <img src="{{ $agent->avatarUrl() }}" alt="{{ $agent->name }}" class="rounded-circle border border-3 border-light shadow-sm" style="width: 90px; height: 90px; object-fit: cover;">
                        </div>
                        <div class="col-md">
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h3 class="fw-bold text-dark mb-0">{{ $agent->name }}</h3>
                                <span class="badge bg-secondary font-monospace">{{ $agent->agent_id ?: ('AGT-'.$agent->id) }}</span>
                                <span class="badge {{ $agent->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($agent->status) }}</span>
                            </div>
                            <div class="text-muted fs-13 d-flex flex-wrap gap-3">
                                <span><i class="fa fa-phone me-1 text-primary"></i> {{ $agent->phone }}</span>
                                <span><i class="fa fa-envelope me-1 text-info"></i> {{ $agent->email }}</span>
                                <span><i class="fa fa-map-marker me-1 text-danger"></i> {{ $agent->district }} ({{ $agent->upazila }})</span>
                                <span><i class="fa fa-calendar me-1 text-secondary"></i> যোগদান: {{ $agent->joining_date ? date('d M, Y', strtotime($agent->joining_date)) : 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-auto text-md-end mt-3 mt-md-0">
                            <span class="text-muted fs-12 d-block">নির্ধারিত কার্ডহোল্ডার সংখ্যা</span>
                            <span class="badge bg-info fs-16 px-3 py-2">{{ number_format($assignedMembersCount) }} জন সদস্য</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4 Main Financial Metric Cards (Finexy / FreshCart / JM Pharm style) -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-primary text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">৳ {{ number_format($totalMarketAmount, 2) }}</h2>
                                <span class="fs-13 opacity-75">ফিল্টারকৃত বাজার মূল্য (Total Value)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-money"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-success text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalProductQty, 1) }} <small class="fs-13">KG/ইউনিট</small></h2>
                                <span class="fs-13 opacity-75">মোট বিতরণকৃত পণ্য (Distributed Qty)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-shopping-basket"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-info text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalMembersServed) }} / {{ number_format($assignedMembersCount) }}</h2>
                                <span class="fs-13 opacity-75">বাজার সংগ্রহকারী সদস্য (Served / Total)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-users"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-warning text-dark shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-dark fw-bold">{{ number_format($remainingStock, 1) }} <small class="fs-13">KG</small></h2>
                                <span class="fs-13 text-dark opacity-75">পয়েন্টের বর্তমান মজুদ (Remaining Stock)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-cubes"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daily, Weekly, Monthly & Yearly Financial Quick Summary Strip (Section 33.9) -->
            <div class="box shadow-sm mb-4">
                <div class="box-body p-3">
                    <div class="row text-center g-2">
                        <div class="col-md-3 col-6 border-end">
                            <span class="text-muted fs-12 d-block">আজকের বাজার মূল্য (Today)</span>
                            <h4 class="fw-bold text-success mb-0">৳ {{ number_format($todayMarketAmount, 2) }}</h4>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <span class="text-muted fs-12 d-block">এই সপ্তাহের বাজার (This Week)</span>
                            <h4 class="fw-bold text-primary mb-0">৳ {{ number_format($weeklyMarketAmount, 2) }}</h4>
                        </div>
                        <div class="col-md-3 col-6 border-end">
                            <span class="text-muted fs-12 d-block">চলতি মাসের বাজার (This Month)</span>
                            <h4 class="fw-bold text-info mb-0">৳ {{ number_format($monthlyMarketAmount, 2) }}</h4>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted fs-12 d-block">এই বছরের মোট বাজার (This Year)</span>
                            <h4 class="fw-bold text-dark mb-0">৳ {{ number_format($yearlyMarketAmount, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 33.13: Date Filter Bar -->
            <div class="box shadow-sm mb-4">
                <div class="box-header with-border bg-light">
                    <h6 class="box-title mb-0"><i class="fa fa-filter text-primary me-2"></i> তারিখ ও ট্রানজেকশন ফিল্টারিং (Date & Transaction Filter)</h6>
                </div>
                <div class="box-body">
                    <form method="GET" action="{{ route('admin.agents.show', $agent->id) }}" class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">সময়সীমা (Period)</label>
                            <select name="period" class="form-select form-select-sm" onchange="toggleCustomDates(this.value)">
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>সব সময় (All Time)</option>
                                <option value="today" {{ $period === 'today' ? 'selected' : '' }}>আজ (Today)</option>
                                <option value="yesterday" {{ $period === 'yesterday' ? 'selected' : '' }}>গতকাল (Yesterday)</option>
                                <option value="this_week" {{ $period === 'this_week' ? 'selected' : '' }}>এই সপ্তাহ (This Week)</option>
                                <option value="last_week" {{ $period === 'last_week' ? 'selected' : '' }}>গত সপ্তাহ (Last Week)</option>
                                <option value="this_month" {{ $period === 'this_month' ? 'selected' : '' }}>এই মাস (This Month)</option>
                                <option value="last_month" {{ $period === 'last_month' ? 'selected' : '' }}>গত মাস (Last Month)</option>
                                <option value="this_year" {{ $period === 'this_year' ? 'selected' : '' }}>এই বছর (This Year)</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>কাস্টম তারিখ (Custom Date Range)</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="fromDateCol" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                            <label class="form-label small fw-bold">শুরুর তারিখ</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2" id="toDateCol" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                            <label class="form-label small fw-bold">শেষ তারিখ</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Transaction ID / কার্ড নম্বর</label>
                            <input type="text" name="search_tx" value="{{ request('search_tx') }}" class="form-control form-control-sm" placeholder="EK-DLV-... বা কার্ড নম্বর">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fa fa-search me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.agents.show', $agent->id) }}" class="btn btn-light btn-sm border" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Section 33.10: Product-wise Market Report Table -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h5 class="box-title mb-0"><i class="fa fa-cubes text-info me-2"></i> পণ্যভিত্তিক বিতরণ ও আর্থিক মূল্যায়ন (Product-wise Market Report)</h5>
                            <span class="badge bg-primary">মোট {{ count($productBreakdown) }} টি আইটেম</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>পণ্যের নাম</th>
                                            <th class="text-end">মোট বিতরণকৃত পরিমাণ</th>
                                            <th>ইউনিট</th>
                                            <th class="text-end">গড় বিক্রয় হার (Unit Price)</th>
                                            <th class="text-end">মোট বাজার মূল্য (Total Amount)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $sumProductAmount = 0; $sumProductQty = 0; @endphp
                                        @forelse($productBreakdown as $idx => $p)
                                        @php 
                                            $sumProductAmount += $p->total_amount; 
                                            $sumProductQty += $p->total_qty;
                                        @endphp
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td><strong class="text-dark fs-15">{{ $p->product_name }}</strong></td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($p->total_qty, 1) }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $p->unit }}</span></td>
                                            <td class="text-end fw-bold text-primary">৳ {{ number_format($p->avg_unit_price, 2) }}</td>
                                            <td class="text-end fw-bold text-success fs-15">৳ {{ number_format($p->total_amount, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">নির্বাচিত সময়সীমায় কোনো পণ্য বিতরণের রেকর্ড পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    @if(count($productBreakdown) > 0)
                                    <tfoot class="table-light fw-bold">
                                        <tr>
                                            <td colspan="2" class="text-end">সর্বমোট (Total):</td>
                                            <td class="text-end">{{ number_format($sumProductQty, 1) }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end text-success fs-16">৳ {{ number_format($sumProductAmount, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 33.11 & 33.12: Card Holder-wise Delivery & Transaction History Table -->
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h5 class="box-title mb-0"><i class="fa fa-history text-secondary me-2"></i> সদস্যভিত্তিক বিতরণ লেনদেন ইতিহাস (Member Delivery Transactions)</h5>
                            <span class="badge bg-secondary">মোট রেকর্ড: {{ $deliveries->total() }}</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Transaction ID</th>
                                            <th>কার্ডহোল্ডারের নাম</th>
                                            <th>কার্ড নাম্বার</th>
                                            <th>বিতরণকৃত পণ্য ও পরিমাণ</th>
                                            <th class="text-end">মোট বাজার মূল্য (৳)</th>
                                            <th>তারিখ ও সময়</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">রশিদ প্রিন্ট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($deliveries as $del)
                                        <tr>
                                            <td>
                                                <span class="badge bg-secondary font-monospace">{{ $del->delivery_no }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-dark">{{ $del->member_name }}</strong>
                                                <div class="small text-muted">{{ $del->cardHolder ? $del->cardHolder->phone : '' }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning text-dark font-monospace">{{ $del->card_number }}</span>
                                            </td>
                                            <td>
                                                @foreach($del->items as $it)
                                                    <span class="badge bg-light text-dark border me-1 mb-1">
                                                        {{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}
                                                        @if($it->unit_price > 0)
                                                            (৳{{ number_format($it->total_amount ?: ($it->quantity * $it->unit_price), 0) }})
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="text-end fw-bold text-success fs-14">
                                                ৳ {{ number_format($del->total_amount, 2) }}
                                            </td>
                                            <td>
                                                <div>{{ $del->delivery_date }}</div>
                                                <small class="text-muted">{{ $del->delivery_time ? date('h:i A', strtotime($del->delivery_time)) : '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge {{ $del->status === 'completed' ? 'bg-success' : 'bg-info' }}">
                                                    {{ ucfirst($del->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('agent.receipt', $del->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="রশিদ প্রিন্ট">
                                                    <i class="fa fa-print"></i> রশিদ
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">কোনো ডেলিভারি লেনদেন পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($deliveries->hasPages())
                        <div class="box-footer">
                            {{ $deliveries->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<script>
function toggleCustomDates(val) {
    const fromCol = document.getElementById('fromDateCol');
    const toCol = document.getElementById('toDateCol');
    if (val === 'custom') {
        fromCol.style.display = 'block';
        toCol.style.display = 'block';
    } else {
        fromCol.style.display = 'none';
        toCol.style.display = 'none';
    }
}
</script>
@endsection
