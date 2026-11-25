@extends('layouts.frontend.app')

@section('title', 'Corporate Activities & Project Execution - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Field Progress</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Activities & Project Execution</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Real-time progress, budgets, and field reports of completed, ongoing, and upcoming development projects.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Filter Buttons -->
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
                <a href="{{ route('corporate.activities') }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ empty($category) ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ empty($category) ? 'background: #0b5e28;' : '' }}">All Projects ({{ $counts['all'] }})</a>
                <a href="{{ route('corporate.activities', ['category' => 'ongoing']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $category == 'ongoing' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $category == 'ongoing' ? 'background: #0b5e28;' : '' }}">Ongoing Projects ({{ $counts['ongoing'] }})</a>
                <a href="{{ route('corporate.activities', ['category' => 'completed']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $category == 'completed' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $category == 'completed' ? 'background: #0b5e28;' : '' }}">Completed Projects ({{ $counts['completed'] }})</a>
                <a href="{{ route('corporate.activities', ['category' => 'planned']) }}" class="btn btn-sm px-4 py-2 fw-semibold shadow-sm {{ $category == 'planned' ? 'btn-success text-white' : 'btn-outline-secondary' }}" style="border-radius: 20px; {{ $category == 'planned' ? 'background: #0b5e28;' : '' }}">Planned Projects ({{ $counts['planned'] }})</a>
            </div>

            <!-- Activities Grid -->
            <div class="row g-4 mb-5">
                @forelse($activities as $act)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden bg-white d-flex flex-column" style="border: 1px solid #e2e8f0;">
                            <!-- Image & Badge -->
                            <div class="position-relative" style="height: 200px; overflow: hidden; background: #e2e8f0;">
                                @if(!empty($act->featured_image))
                                    <img src="{{ asset($act->featured_image) }}" alt="{{ $act->title }}" class="w-100 h-100 object-fit-cover">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="background: linear-gradient(135deg, #063c19, #0b5e28); color: #fff;">
                                        <i class="fa fa-tractor fa-3x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 m-3">
                                    @if($act->category == 'completed')
                                        <span class="badge bg-success px-3 py-1 rounded-pill shadow-sm"><i class="fa fa-check-circle me-1"></i>Completed</span>
                                    @elseif($act->category == 'ongoing')
                                        <span class="badge bg-warning text-dark px-3 py-1 rounded-pill shadow-sm"><i class="fa fa-spinner fa-spin me-1"></i>Ongoing</span>
                                    @else
                                        <span class="badge bg-info text-white px-3 py-1 rounded-pill shadow-sm"><i class="fa fa-calendar-alt me-1"></i>Planned</span>
                                    @endif
                                </div>
                                <div class="position-absolute bottom-0 end-0 m-2">
                                    <span class="badge bg-dark bg-opacity-75 text-white px-2 py-1 small">{{ $act->project_code }}</span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <span class="text-success small fw-bold mb-1"><i class="fa fa-map-marker-alt me-1"></i>{{ $act->location ?: 'Bangladesh' }}</span>
                                <h5 class="fw-bold mb-2" style="color: #063c19; font-size: 18px;">{{ $act->title }}</h5>
                                <p class="text-muted small mb-3" style="line-height: 1.7;">
                                    {{ Str::limit($act->description, 100) }}
                                </p>

                                <!-- Progress Bar -->
                                <div class="mb-3 mt-auto">
                                    <div class="d-flex justify-content-between small fw-bold mb-1">
                                        <span class="text-muted">Progress</span>
                                        <span class="text-success">{{ $act->progress_percentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 8px; border-radius: 10px; background: #e2e8f0;">
                                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $act->progress_percentage }}%;" aria-valuenow="{{ $act->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>

                                <!-- Budget & Details Link -->
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div>
                                        <span class="text-muted small d-block">Budget</span>
                                        <strong class="text-dark" style="font-size: 15px;">BDT {{ number_format($act->budget) }}</strong>
                                    </div>
                                    <a href="{{ route('corporate.activity_detail', $act->id) }}" class="btn btn-outline-success btn-sm px-3 py-1 fw-semibold" style="border-radius: 15px;">
                                        Details &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <i class="fa fa-folder-open fa-3x text-secondary mb-3"></i>
                        <h5>No project activity records currently available.</h5>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $activities->links() }}
            </div>

        </div>
    </main>

</div>
@endsection

