@extends('layouts.backend.app')

@section('title', 'ব্লগ ক্যাটাগরি ম্যানেজমেন্ট')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <section class="content pt-3">
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1" style="color: #0f172a;">
                    <i class="fa fa-folder-open text-warning me-2"></i>ব্লগ ক্যাটাগরি ম্যানেজমেন্ট (Blog Categories)
                </h4>
                <p class="text-muted small mb-0">ব্লগ পোস্টগুলোকে সুন্দরভাবে সাজাতে ক্যাটাগরি তৈরি ও পরিচালনা করুন।</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fa fa-list me-1"></i>সকল ব্লগ পোস্ট
                </a>
                <a href="{{ route('admin.blogs.create') }}" class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="fa fa-plus-circle me-1"></i>নতুন পোস্ট তৈরি করুন
                </a>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Create Category Form -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-plus-circle text-success me-2"></i>নতুন ক্যাটাগরি যোগ করুন
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.blogs.categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">ক্যাটাগরির নাম <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="যেমন: কৃষি ও খাদ্য নিরাপত্তা" required value="{{ old('name') }}">
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">সংক্ষিপ্ত বিবরণ</label>
                            <textarea name="description" rows="3" class="form-control" placeholder="ক্যাটাগরির লক্ষ্য ও বিষয়বস্তু...">{{ old('description') }}</textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                            <i class="fa fa-save me-1"></i>ক্যাটাগরি সংরক্ষণ করুন
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories List Table -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa fa-th-list text-primary me-2"></i>বিদ্যমান ক্যাটাগরি সমূহ
                    </h5>
                    <span class="badge bg-light text-dark border">মোট: {{ $categories->count() }}টি</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 680px;">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>নাম ও স্লাগ</th>
                                    <th>বিবরণ</th>
                                    <th class="text-center">পোস্ট সংখ্যা</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-end" style="width: 140px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $index => $cat)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $cat->name }}</div>
                                            <small class="text-muted font-monospace">{{ $cat->slug }}</small>
                                        </td>
                                        <td>
                                            <span class="small text-muted">{{ Str::limit($cat->description, 50) ?: '—' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success-subtle text-success px-2 py-1 rounded-pill fw-bold border border-success" style="background-color: #dcfce7; color: #166534;">
                                                {{ $cat->posts_count }}টি পোস্ট
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($cat->status)
                                                <span class="badge bg-success rounded-pill px-2 py-1">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-secondary rounded-pill px-2 py-1">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-circle" data-bs-toggle="modal" data-bs-target="#editModal{{ $cat->id }}" title="এডিট">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                            <form action="{{ route('admin.blogs.categories.destroy', $cat->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('এই ক্যাটাগরিটি মুছে ফেলতে চান?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle" title="মুছে ফেলুন">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content rounded-4 border-0 shadow">
                                                <form action="{{ route('admin.blogs.categories.update', $cat->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fw-bold text-dark"><i class="fa fa-edit text-primary me-2"></i>ক্যাটাগরি সম্পাদনা</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-secondary small">ক্যাটাগরির নাম</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $cat->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-secondary small">বিবরণ</label>
                                                            <textarea name="description" rows="3" class="form-control">{{ $cat->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold text-secondary small">স্ট্যাটাস</label>
                                                            <select name="status" class="form-select">
                                                                <option value="1" {{ $cat->status ? 'selected' : '' }}>সক্রিয় (Active)</option>
                                                                <option value="0" {{ !$cat->status ? 'selected' : '' }}>নিষ্ক্রিয় (Inactive)</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-top">
                                                        <button type="button" class="btn btn-secondary btn-sm rounded-pill" data-bs-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">আপডেট করুন</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            <i class="fa fa-folder-open-o fa-3x mb-2 text-secondary d-block"></i>
                                            কোনো ক্যাটাগরি পাওয়া যায়নি।
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
</div>
        </section>
    </div>
</div>
@endsection
