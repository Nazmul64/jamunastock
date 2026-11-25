@extends('layouts.frontend.app')

@section('title', ($sector->name_en ?: $sector->name_bn) . ' - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-1 mb-2 bg-warning text-dark fw-bold">Sector No: {{ $sector->sector_no }}</span>
            <h2 style="color: #fff; font-size: 32px; font-weight: 800; margin-bottom: 8px;">{{ $sector->name_en ?: $sector->name_bn }}</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0;">{{ $sector->name_en }} &bull; Investment Range: {{ $sector->investment_range }}</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">
                <!-- Main Sector Info & Projects -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        <h4 class="fw-bold mb-3" style="color: #063c19;">Sector Overview & Potential</h4>
                        <div class="text-secondary" style="font-size: 15px; line-height: 1.85; text-align: justify;">
                            {!! nl2br(e($sector->description)) !!}
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-handshake text-success me-2"></i>Dividend & Risk Management Policy</h5>
                        <div class="p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #0b5e28; font-size: 14px; line-height: 1.8; color: #1e293b;">
                            {{ $sector->profit_loss_policy ?: 'All project returns are governed by actual yields, verified sales proceeds, and audited financial statements. The institution employs high standard operational controls to protect capital and distribute timely dividends.' }}
                        </div>
                    </div>

                    <!-- Projects inside Sector -->
                    <div class="bg-white p-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h4 class="fw-bold mb-0" style="color: #063c19;">Active Projects in this Sector</h4>
                            <span class="badge bg-success">{{ $sector->projects->count() }} Projects</span>
                        </div>

                        @forelse($sector->projects as $p)
                            <div class="card border rounded-3 p-3 mb-3 shadow-none hover-shadow">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-7">
                                        <span class="badge bg-light text-muted small mb-1">{{ $p->project_id_code }}</span>
                                        <h5 class="fw-bold mb-1" style="color: #063c19;">
                                            <a href="{{ route('investments.project_detail', $p->id) }}" class="text-decoration-none text-dark hover-success">
                                                {{ $p->project_name }}
                                            </a>
                                        </h5>
                                        <p class="text-muted small mb-2"><i class="fa fa-map-marker-alt me-1"></i>{{ $p->field_location ?: 'Field Project' }} &bull; Duration: {{ $p->duration }}</p>
                                        <div class="small">
                                            <span class="text-muted">Min. Investment:</span> <strong>BDT {{ number_format($p->min_investment) }}</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-md-end">
                                        <div class="mb-2">
                                            <span class="text-muted small">Target: BDT {{ number_format($p->target_budget) }}</span>
                                            <div class="progress mt-1" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: {{ $p->progress_percentage }}%;"></div>
                                            </div>
                                        </div>
                                        <a href="{{ route('investments.project_detail', $p->id) }}" class="btn btn-outline-success btn-sm px-3 rounded-pill fw-bold">
                                            Invest Now &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="fa fa-seedling fa-2x mb-2 text-secondary"></i>
                                <p class="small mb-0">No new projects are currently open in this sector. Please check back soon.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Sidebar Other Sectors -->
                <div class="col-lg-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        <h5 class="fw-bold mb-3" style="color: #063c19; border-bottom: 2px solid #0b5e28; padding-bottom: 8px;">Investment Brief</h5>
                        <ul class="list-unstyled mb-4 small" style="line-height: 2.2;">
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Sector Code:</span>
                                <strong>{{ $sector->sector_no }}</strong>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Investment Range:</span>
                                <strong>{{ $sector->investment_range }}</strong>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Duration:</span>
                                <strong>{{ $sector->duration }}</strong>
                            </li>
                        </ul>
                        <a href="{{ route('investments.sectors') }}" class="btn btn-outline-success w-100 rounded-pill fw-bold">&larr; Back to Top 10 Sectors</a>
                    </div>

                    <!-- Other Sectors List -->
                    <div class="bg-white p-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0;">
                        <h6 class="fw-bold mb-3" style="color: #063c19;">Other Popular Investment Sectors</h6>
                        <ul class="list-unstyled mb-0">
                            @foreach($otherSectors as $os)
                                <li class="mb-2 pb-2 border-bottom">
                                    <a href="{{ route('investments.sector_detail', $os->id) }}" class="text-decoration-none text-dark small d-flex align-items-center justify-content-between hover-success">
                                        <span><i class="fa fa-angle-right text-success me-2"></i>{{ $os->name_en ?: $os->name_bn }}</span>
                                        <span class="badge bg-light text-muted">{{ $os->sector_no }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>
@endsection

