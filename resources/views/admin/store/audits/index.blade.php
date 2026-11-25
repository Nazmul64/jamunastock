@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-clipboard-check text-success me-2"></i> স্টক অডিট ও ফিজিক্যাল ভেরিফিকেশন (Stock Audits)
                    </h3>
                    <p class="text-muted mb-0">শারীরিক গণনা (Physical Count) বনাম সিস্টেম স্টকের গরমিল (Variance) যাচাই ও অডিট হিস্টোরি</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createAuditModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন স্টক অডিট পরিচালনা
                    </button>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- KPI Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">সম্পন্ন অডিট সংখ্যা</span>
                            <h3 class="mb-0 fw-bold text-primary">{{ $audits->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">গরমিলযুক্ত পণ্যের সংখ্যা (Variance Items)</span>
                            <h3 class="mb-0 fw-bold text-warning">{{ $audits->sum('total_variance_items') }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-danger border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">গরমিল মূল্যের প্রভাব (Variance Value)</span>
                            <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($audits->sum('total_variance_value'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audits Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> সকল স্টক অডিট রিপোর্ট তালিকা
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>অডিট নং</th>
                                    <th>স্টোর / ওয়্যারহাউস</th>
                                    <th>অডিটের তারিখ</th>
                                    <th>অডিটর / পরিদর্শক</th>
                                    <th>যাচাইকৃত আইটেম</th>
                                    <th>গরমিলযুক্ত আইটেম</th>
                                    <th>গরমিলের আর্থিক মান</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>নোট / মন্তব্য</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($audits as $audit)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $audit->audit_no }}</span></td>
                                    <td><span class="fw-bold">{{ $audit->store->name ?? 'সেন্ট্রাল স্টোর' }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($audit->audit_date)->format('d M, Y') }}</td>
                                    <td>
                                        <div class="fw-bold text-dark"><i class="fa fa-user-secret me-1 text-muted"></i> {{ $audit->auditor_name }}</div>
                                    </td>
                                    <td><span class="badge bg-primary-light text-primary fw-bold">{{ $audit->total_items_checked }} টি</span></td>
                                    <td>
                                        <span class="badge {{ $audit->total_variance_items > 0 ? 'bg-danger-light text-danger' : 'bg-success-light text-success' }} fw-bold">
                                            {{ $audit->total_variance_items }} টি
                                        </span>
                                    </td>
                                    <td class="fw-bold {{ $audit->total_variance_value != 0 ? 'text-danger' : 'text-success' }}">
                                        ৳{{ number_format($audit->total_variance_value, 2) }}
                                    </td>
                                    <td>
                                        @if($audit->status == 'approved')
                                            <span class="badge bg-success">অনুমোদিত (Approved)</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ ucfirst($audit->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $audit->notes ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">কোনো স্টক অডিট তথ্য পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Create Audit -->
<div class="modal fade" id="createAuditModal" tabindex="-1" aria-labelledby="createAuditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.audits.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createAuditModalLabel"><i class="fa fa-clipboard-check me-2"></i> নতুন স্টক অডিট তথ্য এন্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোর নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="store_id" class="form-select" required>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->store_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">অডিটের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="audit_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">অডিটরের নাম <span class="text-danger">*</span></label>
                            <input type="text" name="auditor_name" class="form-control" placeholder="অডিটর / কমিটি প্রধান" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">মোট যাচাইকৃত আইটেম</label>
                            <input type="number" name="total_items_checked" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">গরমিল পাওয়া আইটেম</label>
                            <input type="number" name="total_variance_items" class="form-control" value="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">গরমিল আর্থিক মূল্য (৳)</label>
                            <input type="number" step="0.01" name="total_variance_value" class="form-control" value="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">অডিট পর্যবেক্ষণ ও সুপারিশ (Notes)</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="অডিটের সারসংক্ষেপ ও নির্দেশনা"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> অডিট রিপোর্ট সংরক্ষণ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

