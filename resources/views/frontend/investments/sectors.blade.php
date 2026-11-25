@extends('layouts.frontend.app')

@section('title', 'Top 10 Investment Sectors - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Sustainable & Safe Investment</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Top 10 Investment Sectors</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">List of profitable and sustainable sectors combining agriculture, fisheries, livestock, greenhouse farming, real estate, and trade.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Regulatory Disclaimer Notice -->
            <div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 mb-5" style="background: #fffbeb; border-left: 5px solid #d97706 !important;">
                <div class="d-flex align-items-start">
                    <i class="fa fa-balance-scale text-warning fa-2x me-3 mt-1"></i>
                    <div>
                        <h5 class="fw-bold mb-1" style="color: #92400e;">Regulatory Guidelines & Dividend Policy</h5>
                        <p class="small mb-0 text-secondary" style="line-height: 1.8;">
                            Jamuna Stock Market PLC does not guarantee fixed returns. Dividends are distributed proportionally based on actual production yields, market commodity rates, and annual audited financial statements. Modern technology and active risk governance are applied to every project.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sectors Grid -->
            <div class="row g-4 mb-5">
                @foreach($sectors as $sec)
                    <div class="col-lg-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden p-4" style="border: 1px solid #e2e8f0; border-top: 4px solid #0b5e28 !important;">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; background: #dcfce7; color: #0b5e28; font-size: 20px; font-weight: bold;">
                                        {{ $sec->sector_no }}
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-0" style="color: #063c19; font-size: 19px;">{{ $sec->name_en ?: $sec->name_bn }}</h5>
                                        <span class="text-muted small">Sector #{{ $sec->sector_no }}</span>
                                    </div>
                                </div>
                                <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill small fw-bold" style="background: #dcfce7; color: #166534 !important;">Active Sector</span>
                            </div>

                            <p class="text-secondary small mb-3" style="line-height: 1.75; text-align: justify;">
                                {{ $sec->description ?: 'Managed under modern integrated supervision ensuring capital preservation, transparency, and operational accountability.' }}
                            </p>

                            <!-- Key Specs Table -->
                            <div class="bg-light p-3 rounded-3 mb-3 small" style="border: 1px solid #eef2f6;">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <span class="text-muted d-block">Investment Range:</span>
                                        <strong class="text-dark">{{ $sec->investment_range }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <span class="text-muted d-block">Project Duration:</span>
                                        <strong class="text-dark">{{ $sec->duration }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Profit Loss Policy Note -->
                            @if(!empty($sec->profit_loss_policy))
                                <div class="mb-3 small text-muted">
                                    <i class="fa fa-chart-line text-success me-1"></i> <strong>Dividend Policy:</strong> {{ $sec->profit_loss_policy }}
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                <a href="{{ route('investments.projects', ['sector_id' => $sec->id]) }}" class="btn btn-outline-success btn-sm px-3 py-1 rounded-pill fw-semibold">
                                    View Active Projects ({{ $sec->projects->count() }})
                                </a>
                                <a href="{{ route('investments.sector_detail', $sec->id) }}" class="btn btn-success btn-sm px-4 py-1 rounded-pill fw-bold" style="background: #0b5e28;">
                                    Sector Profile &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Call to Action -->
            <div class="text-center p-5 rounded-4 text-white position-relative shadow" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 100%); border: 2px solid #d97706;">
                <h3 class="fw-bold mb-2">Interested in Investing in a Specific Project?</h3>
                <p class="text-light small mb-4" style="max-width: 600px; margin: 0 auto 20px;">Monitor the progress and target budgets of all active projects and participate seamlessly through your online account.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('investments.projects') }}" class="btn btn-warning px-4 py-2 fw-bold text-dark rounded-pill shadow-sm">View All Projects &rarr;</a>
                    <a href="{{ route('shareholders.index') }}" class="btn btn-outline-light px-4 py-2 fw-bold rounded-pill">Explore Shareholder Privileges</a>
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

