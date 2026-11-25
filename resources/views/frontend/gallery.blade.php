@extends('layouts.frontend.app')

@section('content')
<style>
/* Modern Public Gallery Styling - Uncropped True Dimensions */
.gallery-page-hero {
    background: linear-gradient(135deg, #093c1d 0%, #052611 100%);
    padding: 55px 0 40px;
    color: #ffffff;
    border-bottom: 3px solid #f59e0b;
    position: relative;
    overflow: hidden;
}
.gallery-page-hero:before {
    content: "";
    position: absolute;
    top: -50px;
    right: -50px;
    width: 250px;
    height: 250px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(245, 158, 11, 0.15) 0%, transparent 70%);
}

.gallery-filter-nav {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
    margin-bottom: 35px;
}
.gallery-filter-btn {
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 14px;
    font-weight: 600;
    border: 1.5px solid #e2e8f0;
    background: #ffffff;
    color: #475569;
    text-decoration: none !important;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
}
.gallery-filter-btn:hover {
    border-color: #093c1d;
    color: #093c1d;
    background: #f0fdf4;
    transform: translateY(-2px);
}
.gallery-filter-btn.active {
    background: #093c1d !important;
    color: #ffffff !important;
    border-color: #093c1d !important;
    box-shadow: 0 4px 12px rgba(9, 60, 29, 0.25);
}

/* Gallery Card - Original Image Dimensions (Zero Cropping) */
.ks-gallery-card {
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    box-shadow: 0 3px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.ks-gallery-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.12);
    border-color: #f59e0b;
}

/* Image Wrap: 100% Original Proportions, No Fixed Height, No Cropping */
.ks-gallery-img-wrap {
    width: 100%;
    height: auto;
    background: #ffffff;
    position: relative;
    cursor: pointer;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ks-gallery-img-wrap img {
    width: 100%;
    height: auto;
    max-height: none;
    object-fit: contain;
    display: block;
    transition: transform 0.3s ease;
}
.ks-gallery-card:hover .ks-gallery-img-wrap img {
    transform: scale(1.02);
}

.ks-gallery-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(9, 60, 29, 0.35);
    opacity: 0;
    transition: opacity 0.25s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}
.ks-gallery-card:hover .ks-gallery-overlay {
    opacity: 1;
}
.ks-gallery-zoom-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #f59e0b;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}

/* Clean Footer: Date Only, No ugly titles or labels */
.ks-gallery-info {
    padding: 10px 16px;
    background: #ffffff;
    border-top: 1px solid #f1f5f9;
}
</style>

<div class="main-container">
    <!-- Hero / Page Banner -->
    <section class="gallery-page-hero text-center">
        <div class="container position-relative">
            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill mb-2 fw-bold text-uppercase">
                <i class="fa fa-camera-retro me-1"></i> চিত্রমালা ও অ্যালবাম
            </span>
            <h1 class="fw-bold mb-2 text-white" style="font-size: 32px;">ফটো গ্যালারি (Photo Gallery)</h1>
            <p class="text-white-50 mb-0 mx-auto" style="max-width: 650px; font-size: 15px;">
                যমুনা স্টক মার্কেট পিএলসি-এর বিভিন্ন কর্পোরেট কার্যক্রম, প্রকল্প ভিজিট, সেমিনার ও সাফল্যের সোনালী মুহূর্তসমূহ।
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="site-main py-5" style="background: #f8fafc; min-height: 500px;">
        <div class="container">
            <!-- Category Filter Buttons -->
            <div class="gallery-filter-nav">
                @foreach($categories as $cKey => $cLabel)
                    @if($cKey !== 'general')
                        <a href="{{ route('gallery', ['category' => $cKey]) }}" class="gallery-filter-btn {{ $category == $cKey ? 'active' : '' }}">
                            {{ $cLabel }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Photos Grid (3 Cards Per Row on Desktop, Full Image Visibility) -->
            <div class="row g-4">
                @forelse($galleries as $image)
                    <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-4">
                        <div class="ks-gallery-card">
                            <!-- Image Wrap: 100% Original Size, Never Cropped -->
                            <div class="ks-gallery-img-wrap" onclick="openPhotoModal('{{ $image->image_url }}', '{{ $image->created_at ? $image->created_at->format('d M, Y') : '' }}')">
                                <img src="{{ $image->image_url }}" alt="Gallery Image" loading="lazy">
                                <div class="ks-gallery-overlay">
                                    <div class="ks-gallery-zoom-icon">
                                        <i class="fa fa-search-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <!-- Clean Footer: Only Date & Zoom Button (No Card1, Card2 or unwanted text) -->
                            <div class="ks-gallery-info d-flex align-items-center justify-content-between">
                                <span class="text-muted small">
                                    <i class="fa fa-calendar-alt text-warning me-1"></i> {{ $image->created_at ? $image->created_at->format('d M, Y') : '' }}
                                </span>
                                <span class="text-success small fw-semibold" style="cursor: pointer;" onclick="openPhotoModal('{{ $image->image_url }}', '{{ $image->created_at ? $image->created_at->format('d M, Y') : '' }}')">
                                    <i class="fa fa-expand me-1"></i> বড় করে দেখুন
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Fallback if any StockGallery images exist -->
                    @if(isset($stockGalleries) && $stockGalleries->count() > 0)
                        @foreach($stockGalleries as $sImg)
                            <div class="col-xl-4 col-lg-4 col-md-6 col-12 mb-4">
                                <div class="ks-gallery-card">
                                    <div class="ks-gallery-img-wrap" onclick="openPhotoModal('{{ asset('upload/stock_images/' . $sImg->image) }}', '{{ $sImg->created_at ? $sImg->created_at->format('d M, Y') : '' }}')">
                                        <img src="{{ asset('upload/stock_images/' . $sImg->image) }}" alt="Stock Gallery" loading="lazy">
                                        <div class="ks-gallery-zoom-icon">
                                            <i class="fa fa-search-plus"></i>
                                        </div>
                                    </div>
                                    <div class="ks-gallery-info d-flex align-items-center justify-content-between">
                                        <span class="text-muted small">
                                            <i class="fa fa-calendar-alt text-warning me-1"></i> {{ $sImg->created_at ? $sImg->created_at->format('d M, Y') : '' }}
                                        </span>
                                        <span class="text-success small fw-semibold">
                                            <i class="fa fa-expand me-1"></i> বড় করে দেখুন
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 py-5 text-center">
                            <div class="card p-5 border-0 shadow-sm rounded-4 text-center mx-auto" style="max-width: 550px;">
                                <i class="fa fa-images text-muted fs-1 mb-3" style="opacity: 0.5;"></i>
                                <h4 class="fw-bold text-dark mb-1">এই ক্যাটাগরিতে এখনো কোনো ছবি নেই!</h4>
                                <p class="text-muted small mb-0">অ্যাডমিন প্যানেল থেকে খুব শীঘ্রই নতুন ছবি আপলোড করা হবে। অন্য কোনো ক্যাটাগরি দেখতে উপরের ফিল্টারে ক্লিক করুন।</p>
                            </div>
                        </div>
                    @endif
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($galleries) && $galleries->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $galleries->appends(['category' => $category])->links() }}
                </div>
            @endif
        </div>
    </main>
</div>

<!-- Fullscreen Photo Modal / Lightbox -->
<div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg overflow-hidden" style="background: #0f172a;">
            <div class="modal-header border-0 py-2 px-3 text-white" style="background: rgba(0,0,0,0.5);">
                <div class="d-flex align-items-center">
                    <span class="badge bg-warning text-dark me-2">ছবি প্রিভিউ</span>
                    <small class="text-white-50" id="modalPhotoDate"></small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-2 text-center" style="background: #000000; min-height: 350px; display: flex; align-items: center; justify-content: center;">
                <img src="" id="modalPhotoImg" alt="Enlarged" style="max-height: 85vh; max-width: 100%; object-fit: contain; width: auto; height: auto; border-radius: 6px;">
            </div>
            <div class="modal-footer border-0 py-2 px-3 justify-content-between" style="background: rgba(0,0,0,0.5);">
                <a href="" id="modalDownloadBtn" download class="btn btn-sm btn-outline-warning rounded-pill px-3">
                    <i class="fa fa-download me-1"></i> ছবি ডাউনলোড করুন
                </a>
                <button type="button" class="btn btn-sm btn-secondary rounded-pill px-4" data-bs-dismiss="modal" data-dismiss="modal">
                    বন্ধ করুন
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openPhotoModal(url, date) {
    document.getElementById('modalPhotoImg').src = url;
    document.getElementById('modalPhotoDate').textContent = date || '';
    document.getElementById('modalDownloadBtn').href = url;

    // Trigger modal (support Bootstrap 4 & 5)
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        var myModal = new bootstrap.Modal(document.getElementById('photoModal'));
        myModal.show();
    } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
        jQuery('#photoModal').modal('show');
    }
}
</script>
@endsection
