@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-building text-success me-2"></i> স্টোর ও ওয়্যারহাউস ব্যবস্থাপনা (Stores & Locations)
                    </h3>
                    <p class="text-muted mb-0">সকল কেন্দ্রীয় স্টোর, শাখা গোডাউন এবং রেক/শেলফ/বিন লোকেশন রেজিস্ট্রি</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addStoreModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন স্টোর যুক্ত করুন
                    </button>
                    <button type="button" class="btn btn-outline-info shadow-sm" data-bs-toggle="modal" data-bs-target="#addLocationModal">
                        <i class="fa fa-map-marker me-1"></i> নতুন রেক/শেলফ লোকেশন
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

            <!-- Summary KPI -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">মোট স্টোর / গোডাউন</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $stores->count() }} টি</h3>
                            </div>
                            <div class="bg-primary-light rounded-circle p-3 text-primary">
                                <i class="fa fa-building fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">সক্রিয় স্টোর</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $stores->where('status', 'active')->count() }} টি</h3>
                            </div>
                            <div class="bg-success-light rounded-circle p-3 text-success">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-info border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">নথিভুক্ত রেক ও শেলফ লোকেশন</span>
                                <h3 class="mb-0 fw-bold text-info">{{ $stores->sum(function($s){ return $s->locations->count(); }) }} টি</h3>
                            </div>
                            <div class="bg-info-light rounded-circle p-3 text-info">
                                <i class="fa fa-cubes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stores List -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> স্টোর ও ওয়্যারহাউস তালিকা
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>স্টোরের নাম</th>
                                    <th>ধরন</th>
                                    <th>ম্যানেজার ও যোগাযোগ</th>
                                    <th>ঠিকানা / অবস্থান</th>
                                    <th>রেক ও শেলফ সংখ্যা</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stores as $st)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $st->store_code }}</span></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $st->name }}</div>
                                        <small class="text-muted">{{ $st->notes ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark text-capitalize border">
                                            {{ str_replace('_', ' ', $st->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div><i class="fa fa-user me-1 text-muted"></i> {{ $st->manager_name ?? '—' }}</div>
                                        <small class="text-muted"><i class="fa fa-phone me-1"></i> {{ $st->phone ?? '—' }}</small>
                                    </td>
                                    <td>{{ $st->address ?? 'হেড অফিস' }}</td>
                                    <td>
                                        <span class="badge bg-info-light text-info fw-bold">
                                            {{ $st->locations->count() }} টি লোকেশন
                                        </span>
                                        @if($st->locations->count() > 0)
                                            <div class="small text-muted mt-1">
                                                @foreach($st->locations->take(3) as $loc)
                                                    <span class="badge bg-light text-dark border">{{ $loc->rack_number ?? $loc->location_code }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($st->status == 'active')
                                            <span class="badge bg-success">সক্রিয় (Active)</span>
                                        @else
                                            <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">কোনো স্টোর পাওয়া যায়নি।</td>
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

<!-- Modal: Add Store -->
<div class="modal fade" id="addStoreModal" tabindex="-1" aria-labelledby="addStoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.stores.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addStoreModalLabel"><i class="fa fa-building me-2"></i> নতুন স্টোর / গোডাউন যুক্ত করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোরের কোড <span class="text-danger">*</span></label>
                        <input type="text" name="store_code" class="form-control" placeholder="যেমন: STR-MAIN, STR-IT" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোরের নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: সেন্ট্রাল ওয়্যারহাউস" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোরের ধরন</label>
                        <select name="type" class="form-select">
                            <option value="main">Main Warehouse (কেন্দ্রীয়)</option>
                            <option value="sub_store">Sub Store (উপ-শাখা)</option>
                            <option value="department">Department Store (বিভাগীয়)</option>
                            <option value="transit">Transit Store (ট্রানজিট)</option>
                            <option value="quarantine">Quarantine / QA Store</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ম্যানেজার / দায়িত্বপ্রাপ্ত</label>
                            <input type="text" name="manager_name" class="form-control" placeholder="নাম">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ফোন নম্বর</label>
                            <input type="text" name="phone" class="form-control" placeholder="০১XXXXXXXXX">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ঠিকানা / ফ্লোর</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="লোকেশন বা ফ্লোরের বিবরণ"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Location -->
<div class="modal fade" id="addLocationModal" tabindex="-1" aria-labelledby="addLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.stores.locations.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="addLocationModalLabel"><i class="fa fa-map-marker me-2"></i> রেক / শেলফ / বিন লোকেশন তৈরি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোর নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="erp_store_id" class="form-select" required>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->store_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">লোকেশন কোড <span class="text-danger">*</span></label>
                        <input type="text" name="location_code" class="form-control" placeholder="যেমন: LOC-R01-S02" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">ফ্লোর / কক্ষ</label>
                            <input type="text" name="floor" class="form-control" placeholder="২য় তলা / রুম ২০৩">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">রেক নম্বর (Rack)</label>
                            <input type="text" name="rack_number" class="form-control" placeholder="যেমন: RACK-A">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">শেলফ নম্বর (Shelf)</label>
                            <input type="text" name="shelf_number" class="form-control" placeholder="যেমন: SHELF-03">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বিন নম্বর (Bin)</label>
                            <input type="text" name="bin_number" class="form-control" placeholder="যেমন: BIN-12">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">বিবরণ / ক্যাপাসিটি নোট</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="মন্তব্য"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-info text-white"><i class="fa fa-save me-1"></i> সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

