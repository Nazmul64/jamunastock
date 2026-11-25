@extends('layouts.frontend.app')

@section('title', $activity->title . ' - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 45px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-1 mb-2 bg-warning text-dark fw-bold">{{ $activity->project_code }}</span>
            <h2 style="color: #fff; font-size: 30px; font-weight: 800; margin-bottom: 8px;">{{ $activity->title }}</h2>
            <p style="color: #d1fae5; font-size: 14px; margin: 0;"><i class="fa fa-map-marker-alt me-1"></i>{{ $activity->location ?: 'Bangladesh' }} &bull; Budget: BDT {{ number_format($activity->budget) }}</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="row g-4">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        @if(!empty($activity->featured_image))
                            <img src="{{ asset($activity->featured_image) }}" alt="{{ $activity->title }}" class="img-fluid rounded-3 mb-4 w-100" style="max-height: 420px; object-fit: cover;">
                        @endif

                        <h4 class="fw-bold mb-3" style="color: #063c19;">Project Overview & Background</h4>
                        <div class="text-secondary" style="font-size: 15px; line-height: 1.85; text-align: justify;">
                            {!! nl2br(e($activity->description)) !!}
                        </div>

                        @if(!empty($activity->field_report))
                            <hr class="my-4">
                            <h5 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-clipboard-list text-success me-2"></i>Field Reports & Observations</h5>
                            <div class="p-3 rounded-3" style="background: #f0fdf4; border-left: 4px solid #0b5e28; font-size: 14px; line-height: 1.8; color: #1e293b;">
                                {!! nl2br(e($activity->field_report)) !!}
                            </div>
                        @endif

                        @if(!empty($activity->video_url))
                            <hr class="my-4">
                            <h5 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-video text-danger me-2"></i>Project Video Documentary</h5>
                            <div class="ratio ratio-16x9 rounded-3 overflow-hidden shadow-sm">
                                <iframe src="{{ $activity->video_url }}" title="Project Video" allowfullscreen></iframe>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-lg-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0;">
                        <h5 class="fw-bold mb-3" style="color: #063c19; border-bottom: 2px solid #0b5e28; padding-bottom: 8px;">Project Summary</h5>

                        <ul class="list-unstyled mb-4" style="line-height: 2.2; font-size: 14px;">
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Project Code:</span>
                                <strong>{{ $activity->project_code }}</strong>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Status:</span>
                                @if($activity->category == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @elseif($activity->category == 'ongoing')
                                    <span class="badge bg-warning text-dark">Ongoing</span>
                                @else
                                    <span class="badge bg-info">Planned</span>
                                @endif
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Sector / Type:</span>
                                <span>{{ $activity->sector_type ?: 'Agriculture & Industry' }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Location:</span>
                                <span>{{ $activity->location ?: 'National Level' }}</span>
                            </li>
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Total Budget:</span>
                                <strong class="text-success">BDT {{ number_format($activity->budget) }}</strong>
                            </li>
                            @if($activity->start_date)
                            <li class="d-flex justify-content-between border-bottom py-1">
                                <span class="text-muted">Start Date:</span>
                                <span>{{ $activity->start_date->format('d M, Y') }}</span>
                            </li>
                            @endif
                        </ul>

                        <div class="mb-4">
                            <label class="small text-muted fw-bold d-block mb-1">Progress ({{ $activity->progress_percentage }}%)</label>
                            <div class="progress" style="height: 10px; border-radius: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ $activity->progress_percentage }}%;"></div>
                            </div>
                        </div>

                        <a href="{{ route('corporate.activities') }}" class="btn btn-outline-success w-100 fw-bold" style="border-radius: 20px;">&larr; Back to All Projects</a>
                    </div>

                    <!-- Related Activities -->
                    @if(count($relatedActivities) > 0)
                        <div class="bg-white p-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0;">
                            <h6 class="fw-bold mb-3" style="color: #063c19;">Related Projects in Same Category</h6>
                            @foreach($relatedActivities as $rel)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="me-3" style="width: 55px; height: 55px; min-width: 55px; border-radius: 8px; overflow: hidden; background: #eee;">
                                        @if(!empty($rel->featured_image))
                                            <img src="{{ asset($rel->featured_image) }}" alt="{{ $rel->title }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-success"><i class="fa fa-tractor"></i></div>
                                        @endif
                                    </div>
                                    <div>
                                        <a href="{{ route('corporate.activity_detail', $rel->id) }}" class="fw-bold text-dark text-decoration-none small d-block mb-1 hover-success">
                                            {{ Str::limit($rel->title, 45) }}
                                        </a>
                                        <span class="badge bg-light text-muted small">{{ $rel->project_code }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

</div>
@endsection

