@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Main Content Area -->
        <section class="content pt-3">

            <!-- ==========================================
                 ROW 1: ONEDASH 4 TOP KPI CARDS (100% DYNAMIC)
                 ========================================== -->
            <div class="row g-3 mb-4">
                <!-- 1. Total Orders -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="onedash-kpi-card">
                        <div class="onedash-kpi-top">
                            <span class="onedash-kpi-label">Total Orders</span>
                            <span class="onedash-kpi-trend {{ $ordersTrendPositive ? 'positive' : 'negative' }}">{{ $ordersTrendPercent }}</span>
                        </div>
                        <div class="onedash-kpi-bottom">
                            <h3 class="onedash-kpi-value">{{ $totalOrdersFormatted }}</h3>
                            <div class="onedash-sparkline-box">
                                <canvas id="sparklineOrders"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Total Views -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="onedash-kpi-card">
                        <div class="onedash-kpi-top">
                            <span class="onedash-kpi-label">Total Views</span>
                            <span class="onedash-kpi-trend {{ $viewsTrendPositive ? 'positive' : 'negative' }}">{{ $viewsTrendPercent }}</span>
                        </div>
                        <div class="onedash-kpi-bottom">
                            <h3 class="onedash-kpi-value">{{ $totalViewsFormatted }}</h3>
                            <div class="onedash-sparkline-box">
                                <canvas id="sparklineViews"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Revenue -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="onedash-kpi-card">
                        <div class="onedash-kpi-top">
                            <span class="onedash-kpi-label">Revenue</span>
                            <span class="onedash-kpi-trend {{ $revenueTrendPositive ? 'positive' : 'negative' }}">{{ $revenueTrendPercent }}</span>
                        </div>
                        <div class="onedash-kpi-bottom">
                            <h3 class="onedash-kpi-value">{{ $totalRevenueFormatted }}</h3>
                            <div class="onedash-sparkline-box">
                                <canvas id="sparklineRevenue"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Customers -->
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="onedash-kpi-card">
                        <div class="onedash-kpi-top">
                            <span class="onedash-kpi-label">Customers</span>
                            <span class="onedash-kpi-trend {{ $customersTrendPositive ? 'positive' : 'negative' }}">{{ $customersTrendPercent }}</span>
                        </div>
                        <div class="onedash-kpi-bottom">
                            <h3 class="onedash-kpi-value">{{ $totalCustomersFormatted }}</h3>
                            <div class="onedash-sparkline-box">
                                <canvas id="sparklineCustomers"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 ROW 2: ONEDASH REVENUE SPLINE & BY DEVICE
                 ========================================== -->
            <div class="row g-3 mb-4">
                <!-- Left: Revenue Spline Area Chart -->
                <div class="col-xl-8 col-lg-7 col-12">
                    <div class="onedash-card">
                        <div class="onedash-card-header">
                            <h5 class="onedash-card-title">Revenue</h5>
                            <button type="button" class="onedash-more-btn" title="Options">&bull;&bull;&bull;</button>
                        </div>
                        <div style="height: 310px; position: relative;">
                            <canvas id="onedashRevenueChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Right: By Device Donut Chart -->
                <div class="col-xl-4 col-lg-5 col-12">
                    <div class="onedash-card">
                        <div class="onedash-card-header">
                            <h5 class="onedash-card-title">By Device</h5>
                            <button type="button" class="onedash-more-btn" title="Options">&bull;&bull;&bull;</button>
                        </div>
                        <div class="row align-items-center" style="min-height: 290px;">
                            <div class="col-7 position-relative d-flex justify-content-center align-items-center">
                                <div style="width: 190px; height: 190px; position: relative;">
                                    <canvas id="onedashDeviceChart"></canvas>
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                        <h3 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0; line-height: 1;">{{ $deviceTotalVisitorPercent }}%</h3>
                                        <small style="font-size: 11px; color: #64748b; font-weight: 600;">Total Visitors</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="onedash-device-legend">
                                    <div class="onedash-device-item">
                                        <span class="onedash-device-name">
                                            <span style="display:inline-block; width:10px; height:10px; background:#818cf8; border-radius:3px;"></span> Tablet -
                                        </span>
                                        <span class="onedash-device-percent">{{ $deviceTabletPercent }}%</span>
                                    </div>
                                    <div class="onedash-device-item">
                                        <span class="onedash-device-name">
                                            <i class="fa fa-mobile-phone text-primary" style="font-size: 16px;"></i> Mobile -
                                        </span>
                                        <span class="onedash-device-percent">{{ $deviceMobilePercent }}%</span>
                                    </div>
                                    <div class="onedash-device-item">
                                        <span class="onedash-device-name">
                                            <i class="fa fa-desktop text-muted" style="font-size: 13px;"></i> Desktop -
                                        </span>
                                        <span class="onedash-device-percent">{{ $deviceDesktopPercent }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 ROW 3: TRAFFIC SOURCE, MESSAGES/POSTS & VISITORS
                 ========================================== -->
            <div class="row g-3 mb-4">
                <!-- 1. Traffic Source Semi-Radial Ring -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="onedash-card">
                        <div class="onedash-card-header">
                            <h5 class="onedash-card-title">Traffic Source</h5>
                            <button type="button" class="onedash-more-btn" title="Options">&bull;&bull;&bull;</button>
                        </div>
                        <div class="d-flex align-items-center justify-content-center" style="height: 220px; position: relative;">
                            <div style="width: 200px; height: 200px; position: relative;">
                                <canvas id="onedashTrafficChart"></canvas>
                                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                    <small style="font-size: 12px; color: #64748b; font-weight: 600; display: block;">Total Traffic</small>
                                    <h3 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0;">{{ $trafficSourcePercent }}%</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Messages & Total Posts Split Card -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="onedash-card d-flex flex-column justify-content-between">
                        <!-- Messages Upper Half -->
                        <div class="onedash-split-section">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted fs-13 fw-semibold">Messages</span>
                                <button type="button" class="onedash-more-btn">&bull;&bull;&bull;</button>
                            </div>
                            <h3 class="fw-bold mb-2" style="font-size: 26px; color: #3461ff;">{{ $totalMessagesCount }}</h3>
                            <div style="height: 48px; position: relative;">
                                <canvas id="sparklineMessages"></canvas>
                            </div>
                        </div>

                        <!-- Posts Lower Half -->
                        <div class="onedash-split-section">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted fs-13 fw-semibold">Total Posts</span>
                                <button type="button" class="onedash-more-btn">&bull;&bull;&bull;</button>
                            </div>
                            <h3 class="fw-bold mb-2" style="font-size: 26px; color: #3461ff;">{{ $totalPostsCount }}</h3>
                            <div style="height: 48px; position: relative;">
                                <canvas id="sparklinePosts"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Visitors Weekly Bar Chart -->
                <div class="col-xl-4 col-lg-4 col-12">
                    <div class="onedash-card">
                        <div class="onedash-card-header">
                            <h5 class="onedash-card-title">Visitors</h5>
                            <button type="button" class="onedash-more-btn" title="Options">&bull;&bull;&bull;</button>
                        </div>
                        <div style="height: 220px; position: relative;">
                            <canvas id="onedashVisitorsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ==========================================
                 ROW 4: REAL BUSINESS & ERP DATA MODULES
                 (PRESERVING 100% OF APPLICATION FUNCTIONALITY)
                 ========================================== -->
            <div class="card border-0 shadow-none mb-3 bg-transparent">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 16px;">
                        <i class="fa fa-cubes text-primary me-2"></i> কোম্পানির ব্যবসায়িক ডাটা ও অপারেশনাল স্ট্যাটস (Live ERP Overview)
                    </h5>
                    <span class="badge bg-white text-muted border px-3 py-1 shadow-xs fw-semibold">
                        {{ \Carbon\Carbon::now()->format('d M, Y') }}
                    </span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <!-- 1. Total Customers -->
                @if(Auth::user()->hasPermission('user_financial'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">মোট কাস্টমার</div>
                                <div class="onedash-biz-val">{{ count($all_user) }} <span class="fs-13 fw-normal text-muted">জন</span></div>
                                <small class="text-success fw-bold"><i class="fa fa-arrow-up me-1"></i> সক্রিয় গ্রাহক</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #eff4ff; color: #3461ff;">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 2. Officers & Agents -->
                @if(Auth::user()->hasPermission('employee_agent'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">কর্মকর্তা ও এজেন্ট</div>
                                <div class="onedash-biz-val">{{ count($employee) }} <span class="fs-13 fw-normal text-muted">জন</span></div>
                                <small class="text-primary fw-bold"><i class="fa fa-id-badge me-1"></i> রেজিস্টার্ড স্টাফ</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #e0f2fe; color: #0284c7;">
                                <i class="fa fa-user-secret"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 3. Stock Buy -->
                @if(Auth::user()->hasPermission('stock_buy_sell'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">মোট স্টক ক্রয় (BUY)</div>
                                <div class="onedash-biz-val">৳{{ number_format($all_buy, 2) }}</div>
                                <small class="text-purple fw-bold" style="color: #7c3aed;"><i class="fa fa-shopping-bag me-1"></i> অনুমোদিত স্টক</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #f5f3ff; color: #7c3aed;">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Stock Sell -->
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">মোট স্টক বিক্রি (SELL)</div>
                                <div class="onedash-biz-val">৳{{ number_format($all_sell, 2) }}</div>
                                <small class="text-danger fw-bold"><i class="fa fa-line-chart me-1"></i> সেলস ভলিউম</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #fee2e2; color: #dc2626;">
                                <i class="fa fa-line-chart"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 5. Store & Asset ERP -->
                @if(Auth::user()->hasPermission('store_assets'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">স্টোর ও সম্পদ মালামাল</div>
                                <div class="onedash-biz-val">{{ $storeAssetsCount ?? \App\Models\StoreAsset::count() }} <span class="fs-13 fw-normal text-muted">টি</span></div>
                                <small class="text-info fw-bold"><i class="fa fa-cubes me-1"></i> আসবাব, গাড়ি ও আইটি</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #f0fdf4; color: #16a34a;">
                                <i class="fa fa-archive"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 6. Central Finance -->
                @if(Auth::user()->hasPermission('central_finance'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">সেন্ট্রাল ফিন্যান্স লেজার</div>
                                <div class="onedash-biz-val">৳{{ number_format($financeBalance ?? \App\Models\FinanceLedger::sum('amount'), 2) }}</div>
                                <small class="text-danger fw-bold"><i class="fa fa-university me-1"></i> একীভূত ব্যালেন্স</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #fef2f2; color: #b91c1c;">
                                <i class="fa fa-university"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 7. Investment Projects -->
                @if(Auth::user()->hasPermission('investments'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">বিনিয়োগ প্রকল্পসমূহ</div>
                                <div class="onedash-biz-val">{{ $investmentCount ?? \App\Models\InvestmentProject::count() }} <span class="fs-13 fw-normal text-muted">টি</span></div>
                                <small class="text-warning fw-bold"><i class="fa fa-pie-chart me-1"></i> ১০টি প্রধান খাত</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #fef3c7; color: #d97706;">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 8. Monthly Bazaar Packages -->
                @if(Auth::user()->hasPermission('monthly_bazaar'))
                    <div class="col-xl-3 col-md-6 col-12">
                        <div class="onedash-biz-card">
                            <div>
                                <div class="onedash-biz-label">মাসিক বাজার প্যাকেজ</div>
                                <div class="onedash-biz-val">{{ $bazaarPackagesCount ?? \App\Models\MonthlyBazaarPackage::count() }} <span class="fs-13 fw-normal text-muted">টি</span></div>
                                <small class="text-success fw-bold"><i class="fa fa-shopping-basket me-1"></i> অর্ডার ডেলিভারি</small>
                            </div>
                            <div class="onedash-biz-icon-box" style="background: #ecfdf5; color: #059669;">
                                <i class="fa fa-shopping-basket"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- ==========================================
                 ROW 5: ONEDASH QUICK ACTION BUTTONS
                 ========================================== -->
            <div class="onedash-card mb-4">
                <div class="onedash-card-header mb-3">
                    <h5 class="onedash-card-title">
                        <i class="fa fa-bolt text-warning"></i> কুইক অ্যাকশন নেভিগেশন (Quick Shortcuts)
                    </h5>
                </div>
                <div class="row g-3">
                    @if(Auth::user()->hasPermission('store_assets'))
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.store.assets.index') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon"><i class="fa fa-archive"></i></div>
                                <span class="onedash-action-pill-text">মালামাল ও সম্পদ তালিকা</span>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.store.items.movements') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon"><i class="fa fa-exchange"></i></div>
                                <span class="onedash-action-pill-text">স্টক ইন / আউট লেজার</span>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.store.vehicles.index') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon"><i class="fa fa-car"></i></div>
                                <span class="onedash-action-pill-text">যানবাহন ও পরিবহন বহর</span>
                            </a>
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.store.vouchers.index') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon"><i class="fa fa-file-text-o"></i></div>
                                <span class="onedash-action-pill-text">বিল, ভাউচার ও মেমো</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasPermission('central_finance'))
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.finance.index') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon text-danger"><i class="fa fa-university"></i></div>
                                <span class="onedash-action-pill-text">সেন্ট্রাল ফিন্যান্স লেজার</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasPermission('investments'))
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.investments.projects.index') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon text-warning"><i class="fa fa-line-chart"></i></div>
                                <span class="onedash-action-pill-text">বিনিয়োগ প্রকল্পসমূহ</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasPermission('monthly_bazaar'))
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.monthly_bazaar.orders') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon text-success"><i class="fa fa-shopping-basket"></i></div>
                                <span class="onedash-action-pill-text">মাসিক বাজার অর্ডার</span>
                            </a>
                        </div>
                    @endif

                    @if(Auth::user()->hasPermission('roles_permissions'))
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
                            <a href="{{ route('admin.roles.matrix') }}" class="onedash-action-pill">
                                <div class="onedash-action-pill-icon text-primary"><i class="fa fa-shield"></i></div>
                                <span class="onedash-action-pill-text">RBAC পারমিশন ম্যাট্রিক্স</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Floating Accessibility Button (from Onedash Screenshot) -->
<div class="onedash-floating-accessibility" title="Accessibility Mode">
    <i class="fa fa-universal-access"></i>
</div>
@endsection

@section('script')
<script>
document.addEventListener("DOMContentLoaded", function () {
    // -------------------------------------------------------------
    // Helper: Mini Line Sparkline Generator
    // -------------------------------------------------------------
    function createLineSparkline(canvasId, dataPoints) {
        const el = document.getElementById(canvasId);
        if (!el) return;
        const ctx = el.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataPoints.map((_, i) => i),
                datasets: [{
                    data: dataPoints,
                    borderColor: '#3461ff',
                    borderWidth: 2.2,
                    tension: 0.45,
                    pointRadius: 0,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false } }
            }
        });
    }

    // -------------------------------------------------------------
    // Helper: Mini Bar Sparkline Generator
    // -------------------------------------------------------------
    function createBarSparkline(canvasId, dataPoints) {
        const el = document.getElementById(canvasId);
        if (!el) return;
        const ctx = el.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dataPoints.map((_, i) => i),
                datasets: [{
                    data: dataPoints,
                    backgroundColor: '#3461ff',
                    borderRadius: 3,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                scales: { x: { display: false }, y: { display: false } }
            }
        });
    }

    // 1. Sparklines for Top 4 KPI Cards (100% Dynamic)
    createLineSparkline('sparklineOrders', @json($sparkOrders));
    createBarSparkline('sparklineViews', @json($sparkViews));
    createLineSparkline('sparklineRevenue', @json($sparkRevenue));
    createBarSparkline('sparklineCustomers', @json($sparkCustomers));

    // 2. Row 2 Left: Large Onedash Revenue Spline Area Chart (100% Dynamic)
    const revenueCanvas = document.getElementById('onedashRevenueChart');
    if (revenueCanvas) {
        const ctxRev = revenueCanvas.getContext('2d');
        const gradientBlue = ctxRev.createLinearGradient(0, 0, 0, 300);
        gradientBlue.addColorStop(0, 'rgba(52, 97, 255, 0.28)');
        gradientBlue.addColorStop(1, 'rgba(52, 97, 255, 0.00)');

        const monthlyLabels = @json($monthLabels);
        const monthlyData = @json($monthlyRevenueData);
        const maxVal = Math.max(...monthlyData, 10);
        const yMax = Math.ceil(maxVal * 1.2);
        const yStep = Math.max(1, Math.ceil(yMax / 5));

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Revenue (৳)',
                    data: monthlyData,
                    borderColor: '#3461ff',
                    borderWidth: 3,
                    backgroundColor: gradientBlue,
                    fill: true,
                    tension: 0.45,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#3461ff',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        titleFont: { size: 12, weight: '700' },
                        bodyFont: { size: 13, weight: '600' },
                        callbacks: {
                            label: function(context) {
                                return ' ৳ ' + Number(context.parsed.y).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter', size: 12, weight: '500' } }
                    },
                    y: {
                        min: 0,
                        max: yMax,
                        ticks: { 
                            stepSize: yStep, 
                            color: '#94a3b8', 
                            font: { family: 'Inter', size: 12 },
                            callback: function(value) {
                                return '৳' + Number(value).toLocaleString();
                            }
                        },
                        grid: { color: '#f1f5f9' },
                        border: { dash: [4, 4] }
                    }
                }
            }
        });
    }

    // 3. Row 2 Right: Onedash By Device Donut Chart (100% Dynamic)
    const deviceCanvas = document.getElementById('onedashDeviceChart');
    if (deviceCanvas) {
        const ctxDev = deviceCanvas.getContext('2d');
        new Chart(ctxDev, {
            type: 'doughnut',
            data: {
                labels: ['Tablet', 'Mobile', 'Desktop'],
                datasets: [{
                    data: [{{ $deviceTabletPercent }}, {{ $deviceMobilePercent }}, {{ $deviceDesktopPercent }}],
                    backgroundColor: [
                        '#818cf8', // Tablet Light Indigo
                        '#3461ff', // Mobile Royal Blue
                        '#cbd5e1'  // Desktop Light Slate
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                cutout: '78%'
            }
        });
    }

    // 4. Row 3 Left: Onedash Traffic Source Radial Ring (100% Dynamic)
    const trafficCanvas = document.getElementById('onedashTrafficChart');
    if (trafficCanvas) {
        const ctxTraf = trafficCanvas.getContext('2d');
        new Chart(ctxTraf, {
            type: 'doughnut',
            data: {
                labels: ['Traffic', 'Remaining'],
                datasets: [{
                    data: [{{ $trafficSourcePercent }}, {{ 100 - $trafficSourcePercent }}],
                    backgroundColor: ['#3461ff', '#e2e8f0'],
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { enabled: false } },
                cutout: '80%',
                rotation: -90,
                circumference: 360
            }
        });
    }

    // 5. Row 3 Middle: Messages & Posts Sparklines (100% Dynamic)
    createLineSparkline('sparklineMessages', @json($sparkMessages));
    createLineSparkline('sparklinePosts', @json($sparkPosts));

    // 6. Row 3 Right: Onedash Visitors Stacked Weekly Bar Chart (100% Dynamic)
    const visitorsCanvas = document.getElementById('onedashVisitorsChart');
    if (visitorsCanvas) {
        const ctxVis = visitorsCanvas.getContext('2d');
        const weeklyBaseData = @json($weeklyVisitorsBase);
        const weeklyTopData = @json($weeklyVisitorsTop);
        const maxStack = Math.max(...weeklyBaseData.map((v, i) => v + (weeklyTopData[i] || 0)), 5);
        const yStackMax = Math.ceil(maxStack * 1.25);
        const yStackStep = Math.max(1, Math.ceil(yStackMax / 5));

        new Chart(ctxVis, {
            type: 'bar',
            data: {
                labels: ['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'],
                datasets: [
                    {
                        label: 'Staff Actions',
                        data: weeklyBaseData,
                        backgroundColor: '#3461ff',
                        borderRadius: 4,
                        barThickness: 16
                    },
                    {
                        label: 'User Actions',
                        data: weeklyTopData,
                        backgroundColor: '#bfdbfe',
                        borderRadius: 4,
                        barThickness: 16
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 10,
                        titleFont: { size: 12, weight: '700' },
                        bodyFont: { size: 13, weight: '600' }
                    }
                },
                scales: {
                    x: {
                        stacked: true,
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { family: 'Inter', size: 12 } }
                    },
                    y: {
                        stacked: true,
                        min: 0,
                        max: yStackMax,
                        ticks: { stepSize: yStackStep, color: '#94a3b8', font: { family: 'Inter', size: 12 } },
                        grid: { color: '#f1f5f9' },
                        border: { dash: [4, 4] }
                    }
                }
            }
        });
    }
});
</script>
@endsection
