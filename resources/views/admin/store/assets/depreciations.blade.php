@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-calculator text-success me-2"></i> স্থায়ী সম্পদের অবচয় ব্যবস্থাপনা (Asset Depreciation Engine)
                    </h3>
                    <p class="text-muted mb-0">স্থায়ী সম্পদের সরলরৈখিক (Straight Line) ও ক্রমহ্রাসমান (Declining Balance) অবচয় হিসাব ও নিট বুক ভ্যালু</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#calcDepModal">
                        <i class="fa fa-calculator me-1"></i> অবচয় নির্ধারণ ও পোস্টিং করুন
                    </button>
                    <a href="{{ route('admin.store.assets.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> স্থায়ী সম্পদ রেজিস্ট্রি
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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- KPI Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">মোট অবচয় এন্ট্রি</span>
                            <h3 class="mb-0 fw-bold text-primary">{{ $depreciations->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-danger border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">সর্বমোট পুঞ্জীভূত অবচয় (Accumulated)</span>
                            <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($depreciations->sum('depreciation_amount'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">বর্তমান বই-মূল্য (Net Book Value)</span>
                            <h3 class="mb-0 fw-bold text-success">৳{{ number_format($assets->sum('current_value'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Depreciation Schedule Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> সম্পদের অবচয় তফসিল ও বার্ষিক হিসাব
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>অর্থবছর</th>
                                    <th>সম্পদের নাম ও কোড</th>
                                    <th>পদ্ধতি (Method)</th>
                                    <th>অবচয় হার</th>
                                    <th>প্রারম্ভিক মূল্য</th>
                                    <th>চলতি বছরের অবচয়</th>
                                    <th>পুঞ্জীভূত অবচয়</th>
                                    <th>বছর শেষে নিট বুক ভ্যালু</th>
                                    <th>পোস্টিং তারিখ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($depreciations as $dep)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $dep->fiscal_year }}</span></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $dep->asset->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $dep->asset->asset_code ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-capitalize">
                                            {{ str_replace('_', ' ', $dep->method) }}
                                        </span>
                                    </td>
                                    <td>{{ $dep->depreciation_rate }}%</td>
                                    <td>৳{{ number_format($dep->opening_book_value, 2) }}</td>
                                    <td class="fw-bold text-danger">৳{{ number_format($dep->depreciation_amount, 2) }}</td>
                                    <td class="text-muted">৳{{ number_format($dep->accumulated_depreciation, 2) }}</td>
                                    <td class="fw-bold text-success fs-6">৳{{ number_format($dep->closing_book_value, 2) }}</td>
                                    <td class="small">{{ \Carbon\Carbon::parse($dep->calculated_date)->format('d M, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">কোনো অবচয় রেকর্ড তৈরি করা হয়নি। অবচয় নির্ধারণ বাটনে ক্লিক করুন।</td>
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

<!-- Modal: Calculate Depreciation -->
<div class="modal fade" id="calcDepModal" tabindex="-1" aria-labelledby="calcDepModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.depreciations.calculate') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="calcDepModalLabel"><i class="fa fa-calculator me-2"></i> অবচয় নির্ধারণ ও স্বয়ংক্রিয় হিসাব</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">অর্থবছর (Fiscal Year) <span class="text-danger">*</span></label>
                        <input type="text" name="fiscal_year" class="form-control font-monospace" value="{{ date('Y').'-'.(date('Y')+1) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">সম্পদ নির্বাচন করুন</label>
                        <select name="store_asset_id" class="form-select">
                            <option value="all">-- সকল স্থায়ী সম্পদ একযোগে (Bulk All) --</option>
                            @foreach($assets as $ast)
                                <option value="{{ $ast->id }}">{{ $ast->name }} (বর্তমান মূল্য: ৳{{ number_format($ast->current_value, 2) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">অবচয় নির্ধারণ পদ্ধতি (Depreciation Method) <span class="text-danger">*</span></label>
                        <select name="method" class="form-select" required>
                            <option value="straight_line">সরলরৈখিক পদ্ধতি (Straight Line Method)</option>
                            <option value="declining_balance">ক্রমহ্রাসমান জের পদ্ধতি (Declining Balance Method)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">বার্ষিক অবচয় শতকরা হার (%) <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" name="rate" class="form-control" value="10" required>
                        <small class="text-muted">আসবাবপত্র ১০%, ইলেকট্রনিক্স ২০%, গাড়ি ১৫% ইত্যাদি</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i> অবচয় হিসাব ও পোস্ট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

