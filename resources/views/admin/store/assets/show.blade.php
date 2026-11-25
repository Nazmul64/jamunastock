@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-info-circle text-primary me-2"></i> সম্পদের বিস্তারিত প্রোফাইল ({{ $asset->asset_code }})
                    </h3>
                    <p class="text-muted mb-0">{{ $asset->name }} - এর পূর্ণাঙ্গ হিসাব, অবস্থান ও সার্ভিসিং রেকর্ড</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                        <i class="fa fa-wrench me-1"></i> নতুন সার্ভিসিং / মেরামত রেকর্ড
                    </button>
                    <a href="{{ route('admin.store.assets.edit', $asset->id) }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-edit me-1"></i> এডিট করুন
                    </a>
                    <a href="{{ route('admin.store.assets.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> তালিকা
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

            <div class="row g-4">
                <!-- Left: Asset Info Card -->
                <div class="col-lg-5 col-12">
                    <div class="card shadow-sm border-0 rounded-3 mb-4">
                        <div class="card-header bg-white py-3 border-bottom">
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="badge bg-secondary fs-6">{{ $asset->asset_code }}</span>
                                <div>
                                    {!! $asset->condition_badge !!}
                                    {!! $asset->status_badge !!}
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($asset->image && file_exists(public_path('upload/store_assets/' . $asset->image)))
                                <div class="text-center mb-4">
                                    <img src="{{ asset('upload/store_assets/' . $asset->image) }}" alt="{{ $asset->name }}" class="img-fluid rounded shadow-sm border" style="max-height: 200px; width: auto; object-fit: cover;">
                                </div>
                            @endif

                            <h4 class="fw-bold text-dark mb-1">{{ $asset->name }}</h4>
                            <span class="badge bg-light text-dark border mb-3">{{ $asset->category_label }}</span>

                            <ul class="list-group list-group-flush mb-0">
                                @if($asset->model_no)
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">মডেল / স্পেসিফিকেশন:</span>
                                        <strong>{{ $asset->model_no }}</strong>
                                    </li>
                                @endif
                                @if($asset->vehicle_reg_no)
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">গাড়ি রেজি. / সিরিয়াল নং:</span>
                                        <strong class="text-primary">{{ $asset->vehicle_reg_no }}</strong>
                                    </li>
                                @endif
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">পরিমাণ ও একক:</span>
                                    <strong>{{ floatval($asset->quantity) }} {{ $asset->unit }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">বর্তমান বাজারমূল্য:</span>
                                    <strong class="text-success fs-5">৳{{ number_format($asset->current_value, 2) }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">ক্রয়মূল্য:</span>
                                    <span>৳{{ number_format($asset->purchase_cost, 2) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">ক্রয়ের তারিখ:</span>
                                    <span>{{ $asset->purchase_date ? $asset->purchase_date->format('d F, Y') : '—' }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">বর্তমান অবস্থান:</span>
                                    <strong><i class="fa fa-map-marker text-danger me-1"></i>{{ $asset->location ?: 'প্রধান কার্যালয়' }}</strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-muted">দায়িত্বপ্রাপ্ত ব্যক্তি:</span>
                                    <strong><i class="fa fa-user text-info me-1"></i>{{ $asset->assigned_to ?: 'অফিস পুল / স্টোর' }}</strong>
                                </li>
                                @if($asset->vendor_name)
                                    <li class="list-group-item d-flex justify-content-between px-0">
                                        <span class="text-muted">সরবরাহকারী দোকান:</span>
                                        <span>{{ $asset->vendor_name }} ({{ $asset->vendor_contact ?: '—' }})</span>
                                    </li>
                                @endif
                            </ul>

                            @if($asset->notes)
                                <div class="mt-3 p-3 bg-light rounded">
                                    <small class="text-muted fw-bold d-block mb-1">নোট ও বিবরণ:</small>
                                    <p class="mb-0 text-dark small" style="white-space: pre-line;">{{ $asset->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right: Maintenance & Servicing History -->
                <div class="col-lg-7 col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold text-dark mb-0">
                                <i class="fa fa-wrench text-warning me-2"></i> মেনটেনেন্স, মেরামত ও সার্ভিসিং হিস্ট্রি
                            </h5>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
                                <i class="fa fa-plus me-1"></i> মেরামত যুক্ত
                            </button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>নং ও তারিখ</th>
                                            <th>সার্ভিস বিবরণ</th>
                                            <th>মেকানিক / গ্যারেজ</th>
                                            <th>খরচ (৳)</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>ডকুমেন্ট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($asset->maintenances as $mnt)
                                            <tr>
                                                <td>
                                                    <span class="fw-bold text-dark d-block">{{ $mnt->maintenance_no }}</span>
                                                    <small class="text-muted">{{ $mnt->service_date ? $mnt->service_date->format('d/m/Y') : '' }}</small>
                                                </td>
                                                <td>
                                                    <strong class="text-dark">{{ $mnt->title }}</strong>
                                                    @if($mnt->description)
                                                        <small class="text-muted d-block">{{ Str::limit($mnt->description, 50) }}</small>
                                                    @endif
                                                    @if($mnt->next_service_due)
                                                        <small class="text-warning d-block"><i class="fa fa-clock-o me-1"></i>পরবর্তী সার্ভিস: {{ $mnt->next_service_due->format('d/m/Y') }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span>{{ $mnt->service_provider ?: '—' }}</span>
                                                    @if($mnt->provider_contact)
                                                        <small class="text-muted d-block">{{ $mnt->provider_contact }}</small>
                                                    @endif
                                                </td>
                                                <td class="fw-bold text-danger">৳{{ number_format($mnt->cost, 2) }}</td>
                                                <td>{!! $mnt->status_badge !!}</td>
                                                <td>
                                                    @if($mnt->receipt_file && file_exists(public_path('upload/store_maintenances/' . $mnt->receipt_file)))
                                                        <a href="{{ asset('upload/store_maintenances/' . $mnt->receipt_file) }}" target="_blank" class="btn btn-sm btn-outline-info" title="রসিদ দেখুন">
                                                            <i class="fa fa-paperclip"></i> রসিদ
                                                        </a>
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="fa fa-check-circle fa-3x mb-2 text-success d-block"></i>
                                                    এই সম্পদে এখনো কোনো মেরামতের প্রয়োজন পড়েনি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($asset->maintenances->count() > 0)
                            <div class="card-footer bg-light py-2 text-end">
                                <span class="fw-bold text-dark me-2">মোট মেরামত ব্যয়:</span>
                                <span class="fw-bold text-danger fs-5">৳{{ number_format($asset->maintenances->sum('cost'), 2) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Maintenance Modal -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('admin.store.maintenances.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="asset_id" value="{{ $asset->id }}">

                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold"><i class="fa fa-wrench me-2"></i> নতুন মেনটেনেন্স ও মেরামত রেকর্ড</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold">মালামাল / যান</label>
                            <input type="text" class="form-control bg-light" value="{{ $asset->name }} ({{ $asset->asset_code }})" readonly>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold">সার্ভিসিং / মেরামতের শিরোনাম <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: গাড়ির ইঞ্জিন অয়েল পরিবর্তন, এসি গ্যাস রিফিল" required>
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
                            <input type="text" name="voucher_ref" class="form-control" placeholder="যেমন: VOUCH-0045">
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

