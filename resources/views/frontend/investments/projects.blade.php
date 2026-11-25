@extends('layouts.frontend.app')

@section('title', 'Active Investment Projects - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Direct Project Investment</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Active Investment Projects</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Field-based progress, opportunities, and transparent investment options in Jamuna Stock Market PLC projects.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Sector Filter Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <a href="{{ route('investments.projects') }}" class="btn btn-sm px-3 py-2 fw-semibold shadow-sm {{ empty($sectorId) ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ empty($sectorId) ? 'background: #0b5e28;' : '' }}">All Projects</a>
                @foreach($sectors as $sec)
                    <a href="{{ route('investments.projects', ['sector_id' => $sec->id]) }}" class="btn btn-sm px-3 py-2 fw-semibold shadow-sm {{ $sectorId == $sec->id ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $sectorId == $sec->id ? 'background: #0b5e28;' : '' }}">{{ $sec->name_en ?: $sec->name_bn }}</a>
                @endforeach
            </div>

            <!-- Projects Grid -->
            <div class="row g-4 mb-5">
                @forelse($projects as $p)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm bg-white overflow-hidden d-flex flex-column" style="border: 1px solid #e2e8f0;">
                            <!-- Banner Image -->
                            <div class="position-relative" style="height: 190px; overflow: hidden; background: #e2e8f0;">
                                @if(!empty($p->banner_image))
                                    <img src="{{ asset($p->banner_image) }}" alt="{{ $p->project_name }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #063c19, #0b5e28); color: #fff;">
                                        <i class="fa fa-chart-pie fa-3x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-success px-3 py-1 rounded-pill small shadow-sm">{{ $p->sector->name_en ?: ($p->sector->name_bn ?? 'Investment Sector') }}</span>
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75 text-white small">{{ $p->project_id_code }}</span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <h5 class="fw-bold mb-2" style="color: #063c19; font-size: 18px;">
                                    <a href="{{ route('investments.project_detail', $p->id) }}" class="text-decoration-none text-dark hover-success">
                                        {{ $p->project_name }}
                                    </a>
                                </h5>
                                <p class="text-muted small mb-3" style="line-height: 1.7;">
                                    {{ Str::limit($p->description, 95) }}
                                </p>

                                <!-- Progress & Stats -->
                                <div class="mb-3 mt-auto">
                                    <div class="d-flex justify-content-between small fw-bold mb-1">
                                        <span class="text-muted">Raised: BDT {{ number_format($p->total_raised) }}</span>
                                        <span class="text-success">{{ $p->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 10px;">
                                        <div class="progress-bar bg-success" style="width: {{ $p->progress_percentage }}%;"></div>
                                    </div>
                                </div>

                                <div class="bg-light p-2 rounded-3 small mb-3" style="font-size: 13px;">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Target Budget:</span>
                                        <strong>BDT {{ number_format($p->target_budget) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Min. Investment:</span>
                                        <strong class="text-success">BDT {{ number_format($p->min_investment) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Duration:</span>
                                        <span>{{ $p->duration }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('investments.project_detail', $p->id) }}" class="btn btn-success w-100 py-2 fw-bold shadow-sm" style="border-radius: 20px; background: #0b5e28;">
                                    Project Details & Invest &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fa fa-folder-open fa-3x text-secondary mb-3"></i>
                        <h5>No projects found.</h5>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $projects->links() }}
            </div>

        </div>
    </main>

</div>
@endsection

