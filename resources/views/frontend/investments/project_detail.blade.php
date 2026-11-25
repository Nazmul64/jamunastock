@extends('layouts.frontend.app')

@section('title', $project->project_name . ' - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 45px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-1 mb-2 bg-warning text-dark fw-bold">{{ $project->project_id_code }}</span>
            <h2 style="color: #fff; font-size: 30px; font-weight: 800; margin-bottom: 8px;">{{ $project->project_name }}</h2>
            <p style="color: #d1fae5; font-size: 14px; margin: 0;"><i class="fa fa-map-marker-alt me-1"></i>{{ $project->field_location ?: 'Field Project' }} &bull; Sector: {{ $project->sector->name_en ?: ($project->sector->name_bn ?? 'General Sector') }}</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm mb-4" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Main Project Info -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        @if(!empty($project->banner_image))
                            <img src="{{ asset($project->banner_image) }}" alt="{{ $project->project_name }}" class="img-fluid rounded-3 mb-4 w-100" style="max-height: 400px; object-fit: cover;">
                        @endif

                        <h4 class="fw-bold mb-3" style="color: #063c19;">Project Overview & Objectives</h4>
                        <div class="text-secondary" style="font-size: 15px; line-height: 1.85; text-align: justify;">
                            {!! nl2br(e($project->description)) !!}
                        </div>

                        <hr class="my-4">

                        <h5 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-chart-line text-success me-2"></i>Expected Return & Risk Policy</h5>
                        <div class="p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #0b5e28; font-size: 14px; line-height: 1.8; color: #1e293b;">
                            {{ $project->expected_roi_policy ?: 'All project dividends are distributed proportionally based on audited harvests, market commodity rates, and financial reports. Robust management safeguards capital preservation and reliable yield distribution.' }}
                        </div>
                    </div>
                </div>

                <!-- Investment Widget Sidebar -->
                <div class="col-lg-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0; border-top: 4px solid #0b5e28;">
                        <h5 class="fw-bold mb-3" style="color: #063c19;">Investment Summary & Participation</h5>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between small fw-bold mb-1">
                                <span class="text-muted">Total Raised</span>
                                <span class="text-success">{{ $project->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $project->progress_percentage }}%;"></div>
                            </div>
                            <div class="d-flex justify-content-between text-muted small mt-1">
                                <span>BDT {{ number_format($project->total_raised) }}</span>
                                <span>Target: BDT {{ number_format($project->target_budget) }}</span>
                            </div>
                        </div>

                        <ul class="list-unstyled mb-4 small" style="line-height: 2.2;">
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Project Code:</span>
                                <strong>{{ $project->project_id_code }}</strong>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Min. Investment:</span>
                                <strong class="text-success">BDT {{ number_format($project->min_investment) }}</strong>
                            </li>
                            @if($project->max_investment > 0)
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Max. Investment:</span>
                                <strong>BDT {{ number_format($project->max_investment) }}</strong>
                            </li>
                            @endif
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Duration:</span>
                                <strong>{{ $project->duration }}</strong>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Total Investors:</span>
                                <span>{{ $project->investors_count }}</span>
                            </li>
                        </ul>

                        <!-- Investment Form -->
                        @if(Auth::check())
                            <form action="{{ route('investments.invest_post') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Investment Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control rounded-3" min="{{ $project->min_investment }}" max="{{ $project->max_investment > 0 ? $project->max_investment : 10000000 }}" required value="{{ $project->min_investment }}">
                                    <div class="form-text small">Minimum BDT {{ number_format($project->min_investment) }}</div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select rounded-3" required>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Bank Deposit">Bank Deposit</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Transaction ID (TrxID) <span class="text-danger">*</span></label>
                                    <input type="text" name="transaction_id" class="form-control rounded-3" required placeholder="e.g. 8KJL93N...">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Payment Receipt / Screenshot (Optional)</label>
                                    <input type="file" name="payment_proof" class="form-control rounded-3" accept="image/*,.pdf">
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2 fw-bold shadow-sm rounded-pill" style="background: #0b5e28;">
                                    Submit Investment Request
                                </button>
                            </form>
                        @else
                            <div class="alert alert-light border text-center p-3 rounded-3">
                                <i class="fa fa-lock fa-2x text-muted mb-2"></i>
                                <p class="small text-muted mb-3">Please log in to your account to invest in this project.</p>
                                <a href="{{ route('login') }}" class="btn btn-success btn-sm rounded-pill px-4 fw-bold" style="background: #0b5e28;">Login &rarr;</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

