@extends('layouts.backend.app')

@section('content')
<style>
/* Modern Gallery Admin Styling */
.gallery-admin-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.25s ease;
    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}
.gallery-admin-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    border-color: #3b82f6;
}
.gallery-thumb-wrap {
    height: 190px;
    width: 100%;
    background: #f1f5f9;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
.gallery-thumb-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.gallery-admin-card:hover .gallery-thumb-wrap img {
    transform: scale(1.05);
}
.gallery-card-body {
    padding: 14px 16px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Upload Dropzone Styling */
.upload-dropzone {
    border: 2px dashed #93c5fd;
    background: #eff6ff;
    border-radius: 16px;
    padding: 30px 20px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
}
.upload-dropzone:hover {
    border-color: #2563eb;
    background: #dbeafe;
}
.preview-thumb-box {
    width: 70px;
    height: 70px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
    display: inline-block;
    margin: 4px;
    position: relative;
}
.preview-thumb-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>

<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div class="me-auto">
                    <h3 class="page-title text-dark fw-bold mb-1">
                        <i class="fa fa-images text-primary me-2"></i> ফটো গ্যালারি ম্যানেজমেন্ট (Photo Gallery)
                    </h3>
                    <div class="d-inline-block align-items-center">
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">গ্যালারি ম্যানেজমেন্ট</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-toggle="modal" data-target="#uploadModal" data-bs-toggle="modal" data-bs-target="#uploadModal">
                        <i class="fa fa-cloud-upload-alt me-1"></i> একাধিক ছবি আপলোড করুন
                    </button>
                    <a href="{{ route('gallery') }}" target="_blank" class="btn btn-outline-success rounded-pill px-3">
                        <i class="fa fa-external-link-alt me-1"></i> লাইভ গ্যালারি দেখুন
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fs-3 me-3 text-success"></i>
                        <div>
                            <strong class="d-block">সফল হয়েছে!</strong>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fs-3 me-3 text-danger"></i>
                        <div>
                            <strong class="d-block">সতর্কবার্তা!</strong>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fs-3 me-3 text-danger"></i>
                        <div>
                            <strong class="d-block">আপলোডে ত্রুটি!</strong>
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-dismiss="alert" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Storage Info Banner -->
            <div class="card border-0 shadow-sm rounded-4 mb-4" style="background: linear-gradient(135deg, #f0fdf4, #f8fafc); border-left: 4px solid #16a34a !important;">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-folder-open text-success fs-2 me-3"></i>
                            <div>
                                <strong class="text-dark">স্টোরেজ লোকেশন:</strong>
                                <span class="badge bg-dark font-monospace text-warning ms-1">public/uploads/gallery</span>
                                <p class="mb-0 text-muted small mt-1">
                                    অ্যাডমিন প্যানেল থেকে আপলোড করা সমস্ত ছবি স্বয়ংক্রিয়ভাবে এই ডিরেক্টরিতে অপ্টিমাইজ হয়ে সংরক্ষিত হচ্ছে এবং ফ্রন্টএন্ডে রিয়েল-টাইম প্রদর্শিত হচ্ছে।
                                </p>
                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="text-center px-3 py-1 bg-white rounded-3 border">
                                <small class="text-muted d-block">সর্বমোট ছবি</small>
                                <strong class="text-primary fs-5">{{ $totalImages }}</strong>
                            </div>
                            <div class="text-center px-3 py-1 bg-white rounded-3 border">
                                <small class="text-muted d-block">সক্রিয় (Active)</small>
                                <strong class="text-success fs-5">{{ $activeImages }}</strong>
                            </div>
                            <div class="text-center px-3 py-1 bg-white rounded-3 border">
                                <small class="text-muted d-block">লুকানো (Inactive)</small>
                                <strong class="text-danger fs-5">{{ $inactiveImages }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Card -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.gallery.index') }}">
                        <div class="row g-2 align-items-center">
                            <div class="col-lg-5 col-md-6 col-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 rounded-start-pill"><i class="fa fa-search text-muted"></i></span>
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control border-start-0 rounded-end-pill" placeholder="ছবির শিরোনাম দিয়ে খুঁজুন...">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-6">
                                <select name="category" class="form-select rounded-pill" onchange="this.form.submit()">
                                    <option value="all">সব ক্যাটাগরি (All Categories)</option>
                                    @foreach($categories as $cKey => $cLabel)
                                        <option value="{{ $cKey }}" {{ request('category') == $cKey ? 'selected' : '' }}>{{ $cLabel }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-3 col-6">
                                <select name="status" class="form-select rounded-pill" onchange="this.form.submit()">
                                    <option value="">সব স্ট্যাটাস</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>সক্রিয়</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>নিষ্ক্রিয়</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-12 text-end">
                                <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline-secondary rounded-pill w-100">
                                    <i class="fa fa-redo me-1"></i> রিসেট
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bulk Delete Form (Self-contained, NO NESTED FORMS) -->
            <form id="bulkDeleteForm" action="{{ route('admin.gallery.bulk_delete') }}" method="POST">
                @csrf
            </form>

            <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                <div class="d-flex align-items-center">
                    <input type="checkbox" id="selectAllCheckbox" class="form-check-input me-2" style="width: 18px; height: 18px; cursor: pointer;">
                    <label for="selectAllCheckbox" class="mb-0 fw-semibold text-muted" style="cursor: pointer;">সব সিলেক্ট করুন</label>
                </div>
                <button type="submit" form="bulkDeleteForm" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('আপনি কি নিশ্চিত যে নির্বাচিত ছবিগুলো মুছে ফেলতে চান?')">
                    <i class="fa fa-trash me-1"></i> নির্বাচিতগুলো মুছুন (Bulk Delete)
                </button>
            </div>

            <!-- Gallery Grid -->
            <div class="row g-4">
                @forelse($galleries as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-12 mb-3">
                        <div class="gallery-admin-card">
                            <!-- Selection Checkbox on Top Left (Tied to bulkDeleteForm) -->
                            <div style="position: absolute; top: 10px; left: 10px; z-index: 10;">
                                <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" form="bulkDeleteForm" class="item-checkbox form-check-input shadow" style="width: 20px; height: 20px; cursor: pointer;">
                            </div>

                            <!-- Status Badge on Top Right -->
                            <div style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                                @if($item->is_active)
                                    <span class="badge bg-success shadow-sm rounded-pill px-2 py-1">
                                        <i class="fa fa-check me-1"></i> সক্রিয়
                                    </span>
                                @else
                                    <span class="badge bg-danger shadow-sm rounded-pill px-2 py-1">
                                        <i class="fa fa-eye-slash me-1"></i> নিষ্ক্রিয়
                                    </span>
                                @endif
                            </div>

                            <!-- Thumbnail -->
                            <div class="gallery-thumb-wrap">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                            </div>

                            <!-- Card Body -->
                            <div class="gallery-card-body">
                                <div>
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <span class="badge bg-light text-dark border small">
                                            {{ $categories[$item->category] ?? ucfirst($item->category) }}
                                        </span>
                                        <small class="text-muted">{{ $item->created_at ? $item->created_at->format('d M, Y') : '' }}</small>
                                    </div>
                                    <h6 class="fw-bold text-dark text-truncate mb-2" title="{{ $item->title }}">
                                        {{ $item->title ?: 'Untitled Image' }}
                                    </h6>
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex align-items-center justify-content-between pt-2 border-top mt-2">
                                    <a href="{{ route('admin.gallery.toggle', $item->id) }}" class="btn btn-sm btn-outline-{{ $item->is_active ? 'warning' : 'success' }} rounded-pill px-2 py-1" title="{{ $item->is_active ? 'হাইড করুন' : 'পাবলিশ করুন' }}">
                                        <i class="fa fa-{{ $item->is_active ? 'eye-slash' : 'eye' }}"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2 py-1" data-toggle="modal" data-target="#editModal_{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#editModal_{{ $item->id }}" title="সম্পাদনা">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <a href="{{ $item->image_url }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-1" title="ফুল ছবি দেখুন">
                                        <i class="fa fa-expand"></i>
                                    </a>

                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1" onclick="if(confirm('আপনি কি নিশ্চিত যে এই ছবিটি মুছে ফেলতে চান?')) { document.getElementById('single_delete_{{ $item->id }}').submit(); }" title="মুছে ফেলুন">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="card p-5 text-center shadow-sm rounded-4">
                            <i class="fa fa-images text-muted fs-1 mb-3"></i>
                            <h4>গ্যালারিতে এখনো কোনো ছবি যোগ করা হয়নি!</h4>
                            <p class="text-muted">উপরে থাকা "একাধিক ছবি আপলোড করুন" বাটনে ক্লিক করে একের পর এক ছবি গ্যালারিতে আপলোড করুন।</p>
                            <div>
                                <button type="button" class="btn btn-primary rounded-pill px-4" data-toggle="modal" data-target="#uploadModal" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fa fa-cloud-upload-alt me-1"></i> ছবি আপলোড শুরু করুন
                                </button>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($galleries->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $galleries->links() }}
                </div>
            @endif
        </section>
    </div>
</div>

<!-- Hidden Standalone Single Delete Forms (Placed outside grid, NO nesting) -->
@foreach($galleries as $item)
    <form id="single_delete_{{ $item->id }}" action="{{ route('admin.gallery.destroy', $item->id) }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>
@endforeach

<!-- Standalone Edit Modals (Placed outside grid, NO nesting) -->
@foreach($galleries as $item)
    <div class="modal fade" id="editModal_{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <form action="{{ route('admin.gallery.update', $item->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-light">
                        <h5 class="modal-title fw-bold text-dark">
                            <i class="fa fa-edit text-primary me-2"></i> ছবির তথ্য সম্পাদনা
                        </h5>
                        <button type="button" class="btn-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-3">
                            <img src="{{ $item->image_url }}" alt="Preview" style="max-height: 120px; border-radius: 8px;" class="shadow-sm border">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ছবির শিরোনাম / ক্যাপশন:</label>
                            <input type="text" name="title" value="{{ $item->title }}" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">ক্যাটাগরি:</label>
                            <select name="category" class="form-select">
                                @foreach($categories as $cKey => $cLabel)
                                    <option value="{{ $cKey }}" {{ $item->category == $cKey ? 'selected' : '' }}>{{ $cLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">সর্ট অর্ডার:</label>
                            <input type="number" name="sort_order" value="{{ $item->sort_order }}" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-3" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">সংরক্ষণ করুন</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Upload Multiple Images Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data" id="galleryUploadForm">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fa fa-cloud-upload-alt me-2"></i> গ্যালারিতে ছবি আপলোড করুন (Multiple Image Upload)
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-7 col-12">
                            <label class="form-label fw-bold">ক্যাপশন / শিরোনাম (ঐচ্ছিক):</label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: বার্ষিক সাধারণ সভা ২০২৬, প্রধান কার্যালয় ইত্যাদি...">
                            <small class="text-muted">খালি রাখলে স্বয়ংক্রিয়ভাবে ছবির আসল নাম থেকে তৈরি হবে।</small>
                        </div>
                        <div class="col-md-5 col-12">
                            <label class="form-label fw-bold">অ্যালবাম / ক্যাটাগরি নির্বাচন করুন:</label>
                            <select name="category" class="form-select">
                                @foreach($categories as $cKey => $cLabel)
                                    <option value="{{ $cKey }}">{{ $cLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Dropzone Area -->
                    <div class="upload-dropzone" onclick="document.getElementById('multiImageInput').click();">
                        <i class="fa fa-images text-primary fs-1 mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">এখানে ক্লিক করে এক বা একাধিক ছবি সিলেক্ট করুন</h5>
                        <p class="text-muted small mb-2">JPG, JPEG, PNG, WEBP, GIF (প্রতিটি সর্বোচ্চ ১০ মেগাবাইট)</p>
                        <span class="btn btn-outline-primary btn-sm rounded-pill px-3">
                            <i class="fa fa-folder-open me-1"></i> ছবি নির্বাচন করুন
                        </span>
                        <input type="file" name="images[]" id="multiImageInput" multiple accept="image/*" class="d-none" required>
                    </div>

                    <!-- Instant Preview Box -->
                    <div id="previewContainer" class="mt-3 p-3 bg-light rounded-3 border d-none">
                        <strong class="text-dark d-block mb-2">
                            <i class="fa fa-check-circle text-success me-1"></i> নির্বাচিত ছবিসমূহ (<span id="selectedCountBadge">0</span> টি):
                        </strong>
                        <div id="previewThumbs" class="d-flex flex-wrap"></div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-dismiss="modal" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 shadow" id="btnUploadSubmit">
                        <i class="fa fa-upload me-1"></i> আপলোড সম্পন্ন করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const multiInput = document.getElementById('multiImageInput');
    const previewContainer = document.getElementById('previewContainer');
    const previewThumbs = document.getElementById('previewThumbs');
    const countBadge = document.getElementById('selectedCountBadge');

    // Multi-image instant preview
    if (multiInput) {
        multiInput.addEventListener('change', function () {
            previewThumbs.innerHTML = '';
            const files = this.files;

            if (files.length > 0) {
                previewContainer.classList.remove('d-none');
                countBadge.textContent = files.length;

                Array.from(files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const thumbDiv = document.createElement('div');
                            thumbDiv.className = 'preview-thumb-box shadow-xs';
                            thumbDiv.innerHTML = `<img src="${e.target.result}" title="${file.name}">`;
                            previewThumbs.appendChild(thumbDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            } else {
                previewContainer.classList.add('d-none');
            }
        });
    }

    // Select all checkbox toggle
    const selectAll = document.getElementById('selectAllCheckbox');
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            itemCheckboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });
    }
});
</script>
@endsection
