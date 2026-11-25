@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-wrench text-warning me-2"></i> মেনটেনেন্স, মেরামত ও সার্ভিসিং হিসাব (Maintenance Log)
                    </h3>
                    <p class="text-muted mb-0">গাড়ি, আসবাবপত্র, এসি ও সকল অফিস মালামালের সার্ভিসিং, পার্টস পরিবর্তন ও মেরামতের হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#newMaintenanceModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন মেরামত রেকর্ড যুক্ত করুন
                    </button>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.store.maintenances.index') }}" class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6 col-12">
                            <input type="text" name="search" class="form-control" placeholder="সার্ভিস শিরোনাম, ভাউচার নং বা মেকানিকের নাম..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <select name="asset_id" class="form-select">
                                <option value="">সকল মালামাল / যান</option>
                                @foreach($assets as $ast)
                                    <option value="{{ $ast->id }}" {{ request('asset_id') == $ast->id ? 'selected' : '' }}>
                                        [{{ $ast->asset_code }}] {{ $ast->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select">
                                <option value="">সকল স্ট্যাটাস</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>চলমান (In Progress)</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>অপেক্ষমাণ (Pending)</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-6 col-12 d-flex gap-2">
                            <button type="submit" class="btn btn-warning w-100 fw-bold"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            @if(request()->anyFilled(['search', 'asset_id', 'status']))
                                <a href="{{ route('admin.store.maintenances.index') }}" class="btn btn-outline-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Maintenance Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>নং ও তারিখ</th>
                                    <th>মালামাল / সম্পদ</th>
                                    <th>মেরামতের বিবরণ</th>
                                    <th>মেকানিক / গ্যারেজ</th>
                                    <th>পরবর্তী সার্ভিস</th>
                                    <th>খরচ (৳)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>রসিদ / ফাইল</th>
                                    <th class="text-center">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenances as $mnt)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-dark d-block">{{ $mnt->maintenance_no }}</span>
                                            <small class="text-muted">{{ $mnt->service_date ? $mnt->service_date->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>
                                            @if($mnt->asset)
                                                <a href="{{ route('admin.store.assets.show', $mnt->asset->id) }}" class="fw-bold text-primary text-decoration-none">
                                                    <span class="badge bg-secondary me-1">{{ $mnt->asset->asset_code }}</span>
                                                    {{ $mnt->asset->name }}
                                                </a>
                                                @if($mnt->asset->vehicle_reg_no)
                                                    <small class="d-block text-muted">{{ $mnt->asset->vehicle_reg_no }}</small>
                                                @endif
                                            @else
                                                <span class="text-muted">অজ্ঞাত সম্পদ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="text-dark">{{ $mnt->title }}</strong>
                                            @if($mnt->description)
                                                <small class="text-muted d-block">{{ Str::limit($mnt->description, 50) }}</small>
                                            @endif
                                            @if($mnt->voucher_ref)
                                                <span class="badge bg-light text-dark border"><i class="fa fa-tag me-1"></i>{{ $mnt->voucher_ref }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span>{{ $mnt->service_provider ?: '—' }}</span>
                                            @if($mnt->provider_contact)
                                                <small class="text-muted d-block"><i class="fa fa-phone me-1"></i>{{ $mnt->provider_contact }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($mnt->next_service_due)
                                                <span class="badge bg-warning text-dark"><i class="fa fa-clock-o me-1"></i>{{ $mnt->next_service_due->format('d/m/Y') }}</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="fw-bold text-danger fs-6">৳{{ number_format($mnt->cost, 2) }}</td>
                                        <td>{!! $mnt->status_badge !!}</td>
                                        <td>
                                            @if($mnt->receipt_file && file_exists(public_path('upload/store_maintenances/' . $mnt->receipt_file)))
                                                <a href="{{ asset('upload/store_maintenances/' . $mnt->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-info" title="রসিদ ডাউনলোড / দেখুন">
                                                    <i class="fa fa-paperclip"></i> রসিদ
                                                </a>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.store.maintenances.destroy', $mnt->id) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই রেকর্ডটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-wrench fa-3x mb-3 text-secondary d-block"></i>
                                            কোনো মেনটেনেন্স বা মেরামত রেকর্ড পাওয়া যায়নি।
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#newMaintenanceModal">
                                                    <i class="fa fa-plus me-1"></i> নতুন মেরামত রেকর্ড যুক্ত করুন
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($maintenances->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $maintenances->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>

<!-- Add Maintenance Modal -->
<div class="modal fade" id="newMaintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.store.maintenances.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa fa-wrench me-2"></i> নতুন মেনটেনেন্স ও মেরামত রেকর্ড যুক্ত করুন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">মালামাল / সম্পদ নির্বাচন করুন <span class="text-danger">*</span></label>
                            <select name="asset_id" class="form-select" required>
                                <option value="">সম্পদ সিলেক্ট করুন</option>
                                @foreach($assets as $ast)
                                    <option value="{{ $ast->id }}">[{{ $ast->asset_code }}] {{ $ast->name }} ({{ $ast->category_label }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">সার্ভিসিং / মেরামতের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: গাড়ির ইঞ্জিন অয়েল পরিবর্তন, আলমারি লক মেরামত, এসি গ্যাস রিফিল" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">সার্ভিসিংয়ের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="service_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">পরবর্তী সার্ভিস সম্ভাব্য তারিখ (Due Date)</label>
                            <input type="date" name="next_service_due" class="form-control">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">মেরামত খরচ (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="cost" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">ভাউচার / রেফারেন্স নং</label>
                            <input type="text" name="voucher_ref" class="form-control" placeholder="যেমন: VOUCH-2026-0045">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">সার্ভিস প্রোভাইডার / গ্যারেজ / টেকনিশিয়ান</label>
                            <input type="text" name="service_provider" class="form-control" placeholder="দোকান বা মেকানিকের নাম">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">টেকনিশিয়ান / দোকানের ফোন নম্বর</label>
                            <input type="text" name="provider_contact" class="form-control" placeholder="017XXXXXXXX">
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">মেরামতের স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="completed" selected>সম্পন্ন (Completed)</option>
                                <option value="in_progress">চলমান (In Progress)</option>
                                <option value="pending">অপেক্ষমাণ (Pending)</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-bold">কার দ্বারা সম্পন্ন (Performed By)</label>
                            <input type="text" name="performed_by" class="form-control" placeholder="যেমন: ড্রাইভার কাশেম, অফিস সহকারী">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">বিল / মেমোর ছবি বা স্ক্যান কপি</label>
                            <input type="file" name="receipt_file" class="form-control" accept="image/*,application/pdf">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">কাজের বিস্তারিত বিবরণ</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="কী কী পার্টস পরিবর্তন করা হয়েছে বা কী কাজ হয়েছে লিখুন..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-warning fw-bold"><i class="fa fa-save me-1"></i> রেকর্ড সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

