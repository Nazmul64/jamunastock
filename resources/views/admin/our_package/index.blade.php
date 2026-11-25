@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">Our Packages (আওয়ার প্যাকেজসমূহ)</h4>
                    <p class="text-muted mb-0">হোমপেজের "আওয়ার প্যাকেজসমূহ" স্লাইডারের ছবি আপলোড, এডিট ও ম্যানেজমেন্ট</p>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <!-- Add New Package Form -->
                <div class="col-xl-4 col-lg-5 mb-4">
                    <div class="box shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title text-dark"><i class="fa fa-plus-circle me-2 text-success"></i> নতুন প্যাকেজ ইমেজ যোগ করুন</h4>
                        </div>
                        <div class="box-body p-4">
                            <form method="POST" action="{{ route('admin.our_packages.store') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="form-group mb-4">
                                    <label class="form-label fw-bold">প্যাকেজ ছবি (Package Image) <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control form-control-lg" name="image" required accept="image/*">
                                    <small class="text-muted d-block mt-1">প্যাকেজের ছবি বা ব্যানার সিলেক্ট করুন (JPG, PNG, WEBP)</small>
                                    @error('image') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <button type="submit" class="btn btn-success w-100 py-2 fs-15 fw-bold shadow-sm" style="border-radius: 8px;">
                                    <i class="fa fa-upload me-1"></i> আপলোড করুন
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Packages List Table -->
                <div class="col-xl-8 col-lg-7">
                    <div class="box shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
                        <div class="box-header with-border bg-light d-flex align-items-center justify-content-between">
                            <h4 class="box-title text-dark mb-0"><i class="fa fa-images me-2 text-info"></i> সকল প্যাকেজ কার্ড ইমেজ ({{ count($packages) }})</h4>
                            <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size: 12px;">হোমপেজে সক্রিয়</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px;" class="text-center">#</th>
                                            <th>কার্ড ছবি (Card Preview)</th>
                                            <th style="width: 180px;">আপলোডের তারিখ</th>
                                            <th class="text-center" style="width: 220px;">অ্যাকশন (Actions)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($packages as $key => $item)
                                        <tr>
                                            <td class="text-center fw-bold text-muted">{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <img src="{{ asset($item->image) }}" alt="Package Image" style="max-width: 170px; max-height: 105px; object-fit: cover; border-radius: 10px; border: 2px solid #cbd5e1; box-shadow: 0 3px 8px rgba(0,0,0,0.08);">
                                                    @if($item->title)
                                                        <div>
                                                            <strong class="d-block text-dark">{{ $item->title }}</strong>
                                                            @if($item->description)
                                                                <small class="text-muted">{{ Str::limit($item->description, 35) }}</small>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td style="font-size: 13px; color: #64748b;">
                                                <i class="fa fa-calendar me-1 text-muted"></i> {{ $item->created_at ? $item->created_at->format('d M, Y - h:i A') : 'N/A' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-inline-flex align-items-center gap-1">
                                                    <!-- Quick Edit Modal Trigger Button -->
                                                    <button type="button" class="btn btn-sm btn-info text-white px-3 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $item->id }}" title="কার্ড এডিট করুন">
                                                        <i class="fa fa-pencil me-1"></i> এডিট
                                                    </button>

                                                    <!-- Direct Edit Page Link -->
                                                    <a href="{{ route('admin.our_packages.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary px-2" title="বিস্তারিত পেজে এডিট">
                                                        <i class="fa fa-external-link"></i>
                                                    </a>

                                                    <!-- Delete Button -->
                                                    <a href="{{ route('admin.our_packages.destroy', $item->id) }}" class="btn btn-sm btn-danger px-3 fw-bold shadow-sm" onclick="return confirm('আপনি কি নিশ্চিত যে এই কার্ড ইমেজটি ডিলিট করতে চান?')" title="ডিলিট করুন">
                                                        <i class="fa fa-trash me-1"></i> ডিলিট
                                                    </a>
                                                </div>

                                                <!-- Quick Edit Modal -->
                                                <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $item->id }}" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content text-start border-0 shadow" style="border-radius: 16px; overflow: hidden;">
                                                            <div class="modal-header bg-light">
                                                                <h5 class="modal-title fw-bold text-dark" id="editModalLabel{{ $item->id }}">
                                                                    <i class="fa fa-edit text-info me-2"></i> প্যাকেজ কার্ড এডিট ও ছবি পরিবর্তন
                                                                </h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" action="{{ route('admin.our_packages.update', $item->id) }}" enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="modal-body p-4">
                                                                    <!-- Current Preview -->
                                                                    <div class="text-center mb-3 p-3 bg-light rounded-3">
                                                                        <small class="text-muted d-block mb-2 fw-bold">বর্তমান ছবি</small>
                                                                        <img src="{{ asset($item->image) }}" alt="Preview" style="max-width: 100%; max-height: 160px; object-fit: contain; border-radius: 8px; border: 1px solid #cbd5e1;">
                                                                    </div>

                                                                    <!-- New Image Input -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">নতুন ছবি আপলোড করুন (ছবি পরিবর্তন করতে চাইলে)</label>
                                                                        <input type="file" class="form-control" name="image" accept="image/*">
                                                                        <small class="text-muted">পরিবর্তন করতে না চাইলে ফাঁকা রাখুন</small>
                                                                    </div>

                                                                    <!-- Title -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">প্যাকেজ টাইটেল (ঐচ্ছিক)</label>
                                                                        <input type="text" class="form-control" name="title" value="{{ $item->title }}" placeholder="যেমন: যমুনা গোল্ডেন কার্ড">
                                                                    </div>

                                                                    <!-- Description -->
                                                                    <div class="mb-3">
                                                                        <label class="form-label fw-bold">সংক্ষিপ্ত বিবরণ (ঐচ্ছিক)</label>
                                                                        <textarea class="form-control" name="description" rows="2" placeholder="কার্ড বা প্যাকেজের বিবরণ...">{{ $item->description }}</textarea>
                                                                    </div>

                                                                    <!-- Order -->
                                                                    <div class="mb-2">
                                                                        <label class="form-label fw-bold">সিরিয়াল / সাজানোর ক্রম (Sort Order)</label>
                                                                        <input type="number" class="form-control" name="sort_order" value="{{ $item->sort_order ?: ($key + 1) }}">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer bg-light">
                                                                    <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">বাতিল</button>
                                                                    <button type="submit" class="btn btn-success px-4 fw-bold shadow-sm">
                                                                        <i class="fa fa-save me-1"></i> আপডেট ও সংরক্ষণ করুন
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5 text-muted">
                                                <i class="fa fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                                                কোনো প্যাকেজ ইমেজ আপলোড করা হয়নি। বামপাশের ফর্ম থেকে ছবি আপলোড করুন।
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
