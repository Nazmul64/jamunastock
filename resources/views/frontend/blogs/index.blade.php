@extends('layouts.frontend.app')

@section('title', 'ব্লগ ও নলেজ সেন্টার - যমুনা স্টক মার্কেট পিএলসি')

@section('content')
<!-- Page Header Banner -->
<div class="container-fluid py-5 text-white" style="background: linear-gradient(135deg, var(--theme-navbar-bg, #063c19) 0%, #031e0c 100%); border-bottom: 3px solid var(--theme-navbar-btn-bg, #d97706);">
    <div class="container text-center py-3">
        <span class="badge px-3 py-2 rounded-pill mb-2 fw-bold" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; font-size: 13px;">
            <i class="fa fa-newspaper-o me-1"></i> ব্লগ, তথ্য ও গবেষণা
        </span>
        <h1 class="fw-bold display-5 mb-3" style="color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
            যমুনা ব্লগ ও নলেজ সেন্টার
        </h1>
        <p class="lead mx-auto text-light opacity-90" style="max-width: 720px; font-size: 16px;">
            কৃষিভিত্তিক অর্থনীতি, নিরাপদ বিনিয়োগ কৌশল, FMCG মার্কেট ও টেকসই উৎপাদন ব্যবস্থার সর্বশেষ খবরাখবর ও গবেষণাধর্মী বিশ্লেষণ।
        </p>

        <!-- Search Bar Inside Banner -->
        <div class="row justify-content-center mt-4">
            <div class="col-md-7 col-lg-5">
                <form action="{{ route('blogs.index') }}" method="GET">
                    <div class="input-group shadow-lg rounded-pill overflow-hidden p-1 bg-white">
                        <input type="text" name="search" class="form-control border-0 ps-3" placeholder="ব্লগ পোস্ট খুঁজুন..." value="{{ request('search') }}" style="outline: none; box-shadow: none;">
                        <button class="btn btn-warning px-4 rounded-pill fw-bold text-dark" type="submit">
                            <i class="fa fa-search me-1"></i> অনুসন্ধান
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="container py-5">
    <!-- Category Filter Pills -->
    <div class="d-flex align-items-center gap-2 overflow-auto pb-3 mb-4" style="white-space: nowrap; scrollbar-width: none;">
        <a href="{{ route('blogs.index') }}" class="btn btn-sm rounded-pill px-4 py-2 fw-bold {{ !request('category') ? 'btn-success shadow-sm' : 'btn-outline-secondary' }}" style="{{ !request('category') ? 'background-color: var(--theme-btn-primary, #0c5c2d); border-color: var(--theme-btn-primary, #0c5c2d);' : '' }}">
            <i class="fa fa-th me-1"></i> সকল পোস্ট
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('blogs.index', ['category' => $cat->slug]) }}" class="btn btn-sm rounded-pill px-3 py-2 fw-semibold {{ request('category') === $cat->slug ? 'btn-success shadow-sm' : 'btn-outline-secondary' }}" style="{{ request('category') === $cat->slug ? 'background-color: var(--theme-btn-primary, #0c5c2d); border-color: var(--theme-btn-primary, #0c5c2d);' : '' }}">
                {{ $cat->name }} <span class="badge bg-light text-dark ms-1 rounded-pill">{{ $cat->posts_count }}</span>
            </a>
        @endforeach
    </div>

    @if(request('search'))
        <div class="alert alert-light border rounded-3 d-flex align-items-center justify-content-between mb-4">
            <div>
                <i class="fa fa-info-circle text-primary me-2"></i><strong>"{{ request('search') }}"</strong> অনুসন্ধানের ফলাফল:
            </div>
            <a href="{{ route('blogs.index') }}" class="btn btn-sm btn-outline-danger rounded-pill">ফিল্টার মুছুন</a>
        </div>
    @endif

    <div class="row g-4">
        <!-- Left: Blog Posts Grid -->
        <div class="col-lg-8">
            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-12 col-md-6">
                        <div class="card h-100 border-0 rounded-4 shadow-sm overflow-hidden blog-hover-card" style="transition: all 0.3s ease; border: 1px solid #e2e8f0 !important; background: #ffffff;">
                            <!-- Featured Image -->
                            <div class="position-relative overflow-hidden blog-card-img-wrap" style="height: 210px; background-color: #f1f5f9;">
                                @if($post->image && file_exists(public_path('uploads/blogs/' . $post->image)))
                                    <img src="{{ asset('uploads/blogs/' . $post->image) }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover" style="transition: transform 0.4s ease;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-secondary">
                                        <i class="fa fa-newspaper-o fa-3x opacity-50"></i>
                                    </div>
                                @endif

                                @if($post->category)
                                    <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-1 shadow" style="background: linear-gradient(135deg, #064e3b 0%, #047857 100%) !important; color: #ffffff !important; font-size: 11.5px; font-weight: 700; border: 1px solid rgba(255, 255, 255, 0.4); z-index: 2;">
                                        <i class="fa fa-tag text-warning me-1"></i>{{ $post->category->name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                    @if($post->category)
                                        <a href="{{ route('blogs.index', ['category' => $post->category->slug]) }}" class="badge rounded-pill px-3 py-1 text-decoration-none" style="background: #ecfdf5; color: #047857 !important; border: 1px solid #a7f3d0; font-size: 11.5px; font-weight: 700;">
                                            <i class="fa fa-tag text-success me-1"></i>{{ $post->category->name }}
                                        </a>
                                    @endif
                                    <div class="d-flex align-items-center gap-3 text-muted small ms-auto">
                                        <span><i class="fa fa-calendar-o text-warning me-1"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : $post->created_at->format('d M, Y') }}</span>
                                        <span><i class="fa fa-eye text-primary me-1"></i> {{ number_format($post->views_count) }}</span>
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-2">
                                    <a href="{{ route('blogs.show', $post->slug) }}" class="text-decoration-none text-dark hover-success" style="line-height: 1.45; font-size: 17px;">
                                        {{ Str::limit($post->title, 65) }}
                                    </a>
                                </h5>

                                <p class="text-muted small mb-4 flex-grow-1" style="line-height: 1.65;">
                                    {{ Str::limit($post->summary ?: strip_tags($post->content), 110) }}
                                </p>

                                <div class="pt-3 border-top d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center gap-2" style="min-width: 0; flex: 1;">
                                        <div class="rounded-circle bg-light border d-flex align-items-center justify-content-center text-success fw-bold flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        <span class="small text-secondary fw-semibold text-truncate" style="max-width: 170px;" title="{{ $post->author_name }}">{{ $post->author_name ?: 'যমুনা টিম' }}</span>
                                    </div>
                                    <a href="{{ route('blogs.show', $post->slug) }}" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold flex-shrink-0">
                                        পড়ুন <i class="fa fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="p-5 bg-white rounded-4 border shadow-sm">
                            <i class="fa fa-folder-open-o fa-4x text-muted mb-3 d-block"></i>
                            <h4 class="fw-bold text-secondary">কোনো ব্লগ পোস্ট পাওয়া যায়নি</h4>
                            <p class="text-muted">এই মুহূর্তে কোনো পোস্ট প্রকাশিত নেই অথবা অনুসন্ধানে কিছু মেলেনি।</p>
                            <a href="{{ route('blogs.index') }}" class="btn btn-success rounded-pill px-4 mt-2">
                                সকল পোস্ট দেখুন
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $posts->links('pagination::bootstrap-5') }}
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
                                    <img src="{{ asset('uploads/blogs/' . $rPost->image) }}" alt="{{ $rPost->title }}" class="rounded-3 object-fit-cover flex-shrink-0" style="width: 65px; height: 50px;">
                                @else
                                    <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center text-muted flex-shrink-0" style="width: 65px; height: 50px;">
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
.blog-hover-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.blog-hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.12) !important;
}
.blog-hover-card:hover img {
    transform: scale(1.05);
}
.hover-success:hover {
    color: var(--theme-btn-primary, #0c5c2d) !important;
}
.hover-bg-light:hover {
    background-color: #f8fafc;
}
@media (max-width: 575.98px) {
    .blog-card-img-wrap {
        height: 180px !important;
    }
    .blog-hover-card .card-body {
        padding: 1.15rem !important;
    }
}
</style>
@endsection
