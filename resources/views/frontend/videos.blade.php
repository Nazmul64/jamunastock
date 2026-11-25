@extends('layouts.frontend.app')

@section('content')
<style>
/* Modern Public Video Gallery Styling */
.video-page-hero {
    background: linear-gradient(135deg, #093c1d 0%, #052611 100%);
    padding: 55px 0 40px;
    color: #ffffff;
    border-bottom: 3px solid #f59e0b;
    position: relative;
    overflow: hidden;
}
.video-page-hero:before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
}

.ks-video-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
}
.ks-video-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 16px 32px rgba(9, 60, 29, 0.15);
    border-color: #f59e0b;
}

.ks-video-player-wrap {
    width: 100%;
    position: relative;
    background: #000000;
    aspect-ratio: 16 / 9;
    border-top-left-radius: 13px;
    border-top-right-radius: 13px;
    overflow: hidden;
}
.ks-video-player-wrap iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

.ks-video-card-footer {
    padding: 12px 16px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>

<!-- Hero Section -->
<section class="video-page-hero text-center">
    <div class="container">
        <span class="badge rounded-pill bg-warning text-dark px-3 py-2 fw-bold text-uppercase fs-12 mb-3 shadow-sm">
            <i class="fa fa-play-circle me-1"></i> ভিডিও আর্কাইভ ও মিডিয়া
        </span>
        <h1 class="display-6 fw-bold mb-2">আমাদের অফিসিয়াল ভিডিও সেকশন</h1>
        <p class="lead text-light mb-0 fs-16" style="opacity: 0.9;">
            যমুনা স্টক মার্কেট পিএলসি-এর বিভিন্ন ব্যবসায়িক কার্যক্রম, মাঠ পরিদর্শন, প্রকল্প ও প্রাতিষ্ঠানিক ভিডিওসমূহ
        </p>
    </div>
</section>

<!-- Main Video Section (3 Columns: 4, 4, 4) -->
<section class="py-5" style="background: #f8fafc; min-height: 500px;">
    <div class="container">

        <!-- Videos Row (Grid of 3: col-lg-4 col-md-6 col-12) -->
        <div class="row g-4">
            @forelse($videos as $video)
                <!-- Column 4, 4, 4: 3 videos per row, then wraps downwards -->
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="ks-video-card">
                        <!-- 16:9 Responsive Video Frame -->
                        <div class="ks-video-player-wrap">
                            <iframe src="{{ $video->youtube_embed_url }}" 
                                    title="{{ $video->title ?: 'Jamuna Stock Market PLC Video' }}" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    allowfullscreen 
                                    loading="lazy"></iframe>
                        </div>

                        <!-- Card Footer / Title -->
                        <div class="ks-video-card-footer">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger rounded-pill px-2 py-1 fs-11">
                                    <i class="fa fa-youtube-play me-1"></i> YouTube
                                </span>
                                @if($video->title)
                                    <span class="fw-bold text-dark text-truncate fs-14" style="max-width: 200px;" title="{{ $video->title }}">
                                        {{ $video->title }}
                                    </span>
                                @endif
                            </div>
                            <small class="text-muted fs-12">
                                <i class="fa fa-calendar-o me-1"></i> {{ $video->created_at->format('d M, Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="py-5 bg-white rounded-4 shadow-sm p-4">
                        <i class="fa fa-youtube-play fa-4x text-danger mb-3" style="opacity: 0.5;"></i>
                        <h4 class="fw-bold text-dark">আপাতত কোনো ভিডিও পাওয়া যায়নি</h4>
                        <p class="text-muted mb-0">খুব শীঘ্রই আমাদের নতুন ভিডিওসমূহ এখানে প্রকাশিত হবে।</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($videos->hasPages())
            <div class="d-flex justify-content-center mt-4 pt-3">
                {{ $videos->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</section>
@endsection
