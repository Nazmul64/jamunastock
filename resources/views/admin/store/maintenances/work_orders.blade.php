@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-wrench text-warning me-2"></i> মেইনটেনেন্স ও ওয়ার্ক অর্ডার (Maintenance & Work Orders)
                    </h3>
                    <p class="text-muted mb-0">গাড়ি, এসি, জেনারেটর, আসবাবপত্র ও বৈদ্যুতিক সরঞ্জামের নিয়মিত সার্ভিসিং ও মেরামত ওয়ার্ক অর্ডার</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createWorkOrderModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ওয়ার্ক অর্ডার তৈরি
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
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">মোট ওয়ার্ক অর্ডার</span>
                            <h3 class="mb-0 fw-bold text-primary">{{ $workOrders->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">চলমান ও প্রক্রিয়াধীন</span>
                            <h3 class="mb-0 fw-bold text-warning">{{ $workOrders->whereIn('status', ['requested', 'approved', 'in_progress'])->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">সম্পন্ন (Completed)</span>
                            <h3 class="mb-0 fw-bold text-success">{{ $workOrders->where('status', 'completed')->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-danger border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">মোট মেরামত ব্যয়</span>
                            <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($workOrders->where('status', 'completed')->sum('total_cost'), 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Work Orders Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> মেইনটেনেন্স ওয়ার্ক অর্ডারের তালিকা
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>WO নং</th>
                                    <th>বিভাগ / সরঞ্জাম</th>
                                    <th>কাজের শিরোনাম ও বিবরণ</th>
                                    <th>সার্ভিস প্রোভাইডার</th>
                                    <th>যন্ত্রাংশ খরচ</th>
                                    <th>মজুরি / লেবার</th>
                                    <th>সর্বমোট খরচ</th>
                                    <th>অগ্রাধিকার</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($workOrders as $wo)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $wo->work_order_no }}</span></td>
                                    <td>
                                        <span class="badge bg-light text-dark border text-uppercase">{{ $wo->category }}</span>
                                        @if($wo->is_amc)
                                            <span class="badge bg-info-light text-info ms-1">AMC</span>
                                        @endif
                                        @if($wo->asset)
                                            <div class="small text-muted mt-1">{{ $wo->asset->name }} ({{ $wo->asset->asset_code }})</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $wo->title }}</div>
                                        <small class="text-muted">{{ Str::limit($wo->findings_and_action ?? '', 45) }}</small>
                                    </td>
                                    <td>{{ $wo->service_provider ?? 'ইন-হাউস টেকনিশিয়ান' }}</td>
                                    <td>৳{{ number_format($wo->spare_parts_cost, 2) }}</td>
                                    <td>৳{{ number_format($wo->labor_cost, 2) }}</td>
                                    <td class="fw-bold text-danger fs-6">৳{{ number_format($wo->total_cost, 2) }}</td>
                                    <td>
                                        @if($wo->priority == 'emergency')
                                            <span class="badge bg-danger">জরুরি (Emergency)</span>
                                        @elseif($wo->priority == 'high')
                                            <span class="badge bg-warning text-dark">উচ্চ (High)</span>
                                        @else
                                            <span class="badge bg-light text-dark border">স্বাভাবিক</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($wo->status == 'completed')
                                            <span class="badge bg-success">সম্পন্ন (Completed)</span>
                                        @elseif($wo->status == 'in_progress')
                                            <span class="badge bg-primary">চলমান (In Progress)</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ ucfirst($wo->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">কোনো মেইনটেনেন্স ওয়ার্ক অর্ডার পাওয়া যায়নি।</td>
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

<!-- Modal: Create Work Order -->
<div class="modal fade" id="createWorkOrderModal" tabindex="-1" aria-labelledby="createWorkOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.work_orders.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createWorkOrderModalLabel"><i class="fa fa-wrench me-2"></i> নতুন মেইনটেনেন্স ওয়ার্ক অর্ডার তৈরি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ওয়ার্ক অর্ডার নং <span class="text-danger">*</span></label>
                            <input type="text" name="work_order_no" class="form-control font-monospace" value="WO-{{ date('Ymd-His') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মেরামতের ক্ষেত্র / ক্যাটেগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="vehicle">যানবাহন (Vehicle)</option>
                                <option value="ac">শীতাতপ নিয়ন্ত্রণ (Air Conditioner)</option>
                                <option value="electrical">বৈদ্যুতিক ও জেনারেটর (Electrical / Generator)</option>
                                <option value="furniture">আসবাবপত্র (Furniture)</option>
                                <option value="machinery">মেশিনারি ও কম্পিউটার (Machinery / IT)</option>
                                <option value="building">ভবন ও ফ্লোর সংস্কার (Building / Civil)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">সম্পর্কিত সম্পদ / ইকুইপমেন্ট</label>
                            <select name="store_asset_id" class="form-select">
                                <option value="">-- কোনো নির্দিষ্ট সম্পদ নির্বাচন করুন (ঐচ্ছিক) --</option>
                                @foreach($assets as $ast)
                                    <option value="{{ $ast->id }}">{{ $ast->name }} ({{ $ast->asset_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label fw-bold">কাজের শিরোনাম / সারসংক্ষেপ <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="যেমন: সেন্ট্রাল এসি রুটিন গ্যাস রিফিল ও ওয়াশ" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">অগ্রাধিকার (Priority)</label>
                            <select name="priority" class="form-select">
                                <option value="medium">স্বাভাবিক (Medium)</option>
                                <option value="high">উচ্চ (High)</option>
                                <option value="emergency">জরুরি (Emergency)</option>
                                <option value="low">কম (Low)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">সার্ভিস প্রোভাইডার / মেকানিক</label>
                            <input type="text" name="service_provider" class="form-control" placeholder="যেমন: কুল কেয়ার ইঞ্জিনিয়ারিং">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">যন্ত্রাংশ খরচ (৳)</label>
                            <input type="number" step="0.01" name="spare_parts_cost" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">লেবার / সার্ভিস চার্জ (৳)</label>
                            <input type="number" step="0.01" name="labor_cost" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">নির্ধারিত তারিখ</label>
                            <input type="date" name="scheduled_date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বর্তমান স্ট্যাটাস</label>
                            <select name="status" class="form-select">
                                <option value="completed">সম্পন্ন (Completed)</option>
                                <option value="in_progress">চলমান (In Progress)</option>
                                <option value="requested">আবেদিত (Requested)</option>
                                <option value="approved">অনুমোদিত (Approved)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বার্ষিক রক্ষণাবেক্ষণ চুক্তি (AMC)?</label>
                            <select name="is_amc" class="form-select">
                                <option value="0">না (No)</option>
                                <option value="1">হ্যাঁ - AMC ভুক্ত (Annual Maintenance)</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">কাজের বিস্তারিত ও পর্যবেক্ষণ (Findings & Action)</label>
                            <textarea name="findings_and_action" class="form-control" rows="2" placeholder="মেরামতের কাজের বিবরণ"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> ওয়ার্ক অর্ডার সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

