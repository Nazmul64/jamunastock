@extends('layouts.frontend.app')

@section('title', $post->title . ' - যমুনা ব্লগ')

@section('content')
<!-- Breadcrumb Header -->
<div class="container-fluid py-4 bg-light border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('/') }}" class="text-decoration-none text-muted"><i class="fa fa-home me-1"></i>হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}" class="text-decoration-none text-muted">ব্লগ ও নলেজ সেন্টার</a></li>
                @if($post->category)
                    <li class="breadcrumb-item"><a href="{{ route('blogs.index', ['category' => $post->category->slug]) }}" class="text-decoration-none text-muted">{{ $post->category->name }}</a></li>
                @endif
                <li class="breadcrumb-item active text-dark fw-bold" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Reading Section -->
<div class="container py-5">
    <div class="row g-5">
        <!-- Main Article Content (Left) -->
        <div class="col-lg-8">
            <article class="bg-white p-4 p-md-5 rounded-4 border shadow-sm">
                <!-- Category & Meta -->
                <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                    @if($post->category)
                        <span class="badge rounded-pill px-3 py-2 text-white shadow-sm" style="background: linear-gradient(135deg, #0c5c2d 0%, #15803d 100%) !important; color: #ffffff !important; font-weight: 700; border: 1.5px solid #34d399; font-size: 12.5px;">
                            <i class="fa fa-tag me-1 text-warning"></i>{{ $post->category->name }}
                        </span>
                    @endif
                    <span class="text-muted small"><i class="fa fa-calendar-o text-warning me-1"></i>{{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                    <span class="text-muted small"><i class="fa fa-eye text-primary me-1"></i>{{ number_format($post->views_count) }} ভিউ</span>
                </div>

                <!-- Title -->
                <h1 class="fw-bold mb-4" style="color: #0f172a; font-size: 28px; line-height: 1.4;">
                    {{ $post->title }}
                </h1>

                <!-- Author Bar -->
                <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 mb-4 flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center fw-bold" style="width: 42px; height: 42px; font-size: 16px;">
                            <i class="fa fa-user"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark fs-14">{{ $post->author_name }}</div>
                            <small class="text-muted">যমুনা অফিশিয়াল রিসার্চ অ্যান্ড পাবলিকেশন সেল</small>
                        </div>
                    </div>

                    <!-- Social Share Buttons -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted small me-1">শেয়ার:</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle" title="Facebook">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-info rounded-circle" title="Twitter / X">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="btn btn-sm btn-outline-success rounded-circle" title="WhatsApp">
                            <i class="fa fa-whatsapp"></i>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" title="LinkedIn">
                            <i class="fa fa-linkedin"></i>
                        </a>
                    </div>
                </div>

                <!-- Featured Image -->
                @if($post->image && file_exists(public_path('uploads/blogs/' . $post->image)))
                    <div class="mb-4 text-center rounded-4 overflow-hidden border">
                        <img src="{{ asset('uploads/blogs/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid w-100" style="max-height: 480px; object-fit: cover;">
                    </div>
                @endif

                <!-- Excerpt / Summary Box -->
                @if($post->summary)
                    <div class="p-3 mb-4 rounded-3 border-start border-4 border-warning bg-light">
                        <p class="lead mb-0 text-dark fw-semibold" style="font-size: 16px; line-height: 1.6;">
                            {{ $post->summary }}
                        </p>
                    </div>
                @endif

                <!-- Article Body Content -->
                <div class="blog-article-content mt-4" style="color: #334155; font-size: 16px; line-height: 1.8;">
                    {!! $post->content !!}
                </div>

                <!-- Bottom Tags & Share Footer -->
                <div class="pt-4 mt-5 border-top d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <span class="text-muted small me-2"><i class="fa fa-folder-open text-warning me-1"></i>ক্যাটাগরি:</span>
                        <a href="{{ route('blogs.index', ['category' => optional($post->category)->slug]) }}" class="badge rounded-pill px-3 py-2 text-decoration-none shadow-sm" style="background: #ecfdf5; color: #047857 !important; border: 1px solid #a7f3d0; font-weight: 700; font-size: 12.5px;">
                            <i class="fa fa-tag text-success me-1"></i>{{ optional($post->category)->name ?: 'সাধারণ' }}
                        </a>
                    </div>

                    <div class="d-flex gap-2">
                        <button onclick="navigator.clipboard.writeText(window.location.href); alert('লিংক কপি করা হয়েছে!');" class="btn btn-sm btn-light border rounded-pill px-3">
                            <i class="fa fa-link me-1"></i>লিংক কপি করুন
                        </button>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @if($relatedPosts->count() > 0)
                <div class="mt-5">
                    <h4 class="fw-bold text-dark mb-4">
                        <i class="fa fa-newspaper-o text-success me-2"></i>সম্পর্কিত অন্যান্য পোস্ট
                    </h4>
                    <div class="row g-4">
                        @foreach($relatedPosts as $relPost)
                            <div class="col-md-6">
                                <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden" style="border: 1px solid #e2e8f0 !important;">
                                    <div style="height: 160px; background: #f1f5f9;">
                                        @if($relPost->image && file_exists(public_path('uploads/blogs/' . $relPost->image)))
                                            <img src="{{ asset('uploads/blogs/' . $relPost->image) }}" alt="{{ $relPost->title }}" class="w-100 h-100 object-fit-cover">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">
                                                <i class="fa fa-image fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-body p-3">
                                        <small class="text-muted"><i class="fa fa-calendar-o me-1"></i>{{ $relPost->published_at ? $relPost->published_at->format('d M, Y') : $relPost->created_at->format('d M, Y') }}</small>
                                        <h6 class="fw-bold mt-1 mb-2">
                                            <a href="{{ route('blogs.show', $relPost->slug) }}" class="text-decoration-none text-dark hover-success">
                                                {{ Str::limit($relPost->title, 55) }}
                                            </a>
                                        </h6>
                                        <a href="{{ route('blogs.show', $relPost->slug) }}" class="small text-success fw-bold text-decoration-none">
                                            বিস্তারিত পড়ুন <i class="fa fa-angle-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Right: Sidebar -->
        <div class="col-lg-4">
            <!-- Categories Widget -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-tags text-success me-2"></i>ক্যাটাগরি সমূহ
                    </h6>
                </div>
                <div class="card-body p-2">
                    <ul class="list-group list-group-flush">
                        @foreach($categories as $cat)
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-3 py-2 rounded-3 hover-bg-light">
                                <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" class="text-decoration-none text-dark fw-semibold small">
                                    <i class="fa fa-angle-right text-warning me-2"></i>{{ $cat->name }}
                                </a>
                                <span class="badge bg-light text-secondary border rounded-pill">{{ $cat->posts_count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Recent Posts Widget -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="border: 1px solid #e2e8f0 !important;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-clock-o text-primary me-2"></i>সাম্প্রতিক পোস্ট
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex flex-column gap-3">
                        @foreach($recentPosts as $rPost)
                            <div class="d-flex align-items-center gap-3 pb-2 border-bottom">
                                @if($rPost->image && file_exists(public_path('uploads/blogs/' . $rPost->image)))
                                    <img src="{{ asset('uploads/blogs/' . $rPost->image) }}" alt="{{ $rPost->title }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 60px; height: 48px;">
                                @else
                                    <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 60px; height: 48px;">
                                        <i class="fa fa-file-text-o"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 min-w-0">
                                    <a href="{{ route('blogs.show', $rPost->slug) }}" class="text-decoration-none text-dark fw-bold small d-block text-truncate">
                                        {{ $rPost->title }}
                                    </a>
                                    <small class="text-muted" style="font-size: 11px;">
                                        <i class="fa fa-calendar-o me-1 text-warning"></i>{{ $rPost->published_at ? $rPost->published_at->format('d M, Y') : $rPost->created_at->format('d M, Y') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 9 Sectors Investment Ad Card -->
            <div class="card border-0 rounded-4 text-white shadow-sm p-4 text-center position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--theme-navbar-bg, #063c19) 0%, #03230e 100%); border: 1.5px solid var(--theme-navbar-btn-bg, #d97706) !important;">
                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-bold" style="font-size: 11.5px;">৯টি ইনভেস্টমেন্ট সেক্টর</span>
                <h5 class="fw-bold mb-2">যমুনা নিরাপদ বিনিয়োগ প্রকল্প</h5>
                <p class="small text-light opacity-80 mb-3">মাসিক, বার্ষিক ও মেগা টার্ম প্রকল্পে অংশ নিয়ে নিশ্চিত মুনাফা অর্জন করুন।</p>
                <a href="{{ url('/investments/showcase') }}" class="btn btn-warning rounded-pill px-4 fw-bold text-dark shadow-sm">
                    <i class="fa fa-arrow-right me-1"></i>সকল স্কিম দেখুন
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.blog-article-content p {
    margin-bottom: 1.25rem;
}
.blog-article-content h2, .blog-article-content h3, .blog-article-content h4, .blog-article-content h5 {
    color: #0f172a;
    font-weight: 700;
    margin-top: 1.8rem;
    margin-bottom: 0.9rem;
}
.blog-article-content img {
    max-width: 100%;
    height: auto;
    border-radius: 12px;
    margin: 1.5rem 0;
}
.hover-success:hover {
    color: var(--theme-btn-primary, #0c5c2d) !important;
}
.hover-bg-light:hover {
    background-color: #f8fafc;
}
</style>
@endsection
