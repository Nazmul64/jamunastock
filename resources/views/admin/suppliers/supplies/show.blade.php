@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-clipboard text-primary me-2"></i> সরবরাহ বিবরণ ও অ্যাসাইনমেন্ট ({{ $supply->supply_code ?: ('#' . $supply->id) }})
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pending_supplies') }}">সরবরাহ রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">{{ $supply->supply_code }}</li>
                    </ol>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    @php $meta = $supply->status_meta; @endphp
                    <span class="badge bg-{{ $meta['badge'] }} fs-14 px-3 py-2 rounded-pill">
                        <i class="fa {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}
                    </span>
                    <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    <strong>সফল!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    <strong>ত্রুটি!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger rounded-3">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left: Supplier, Invoice, Items & Approval/Assignment Form -->
                <div class="col-lg-8 col-12">
                    <!-- Supplier & Invoice Info -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6 col-12 d-flex">
                            <div class="bg-white border rounded-4 shadow-xs w-100" style="height: auto !important; overflow: visible !important;">
                                <div class="py-2 px-3 bg-light border-bottom rounded-top-4">
                                    <h6 class="fw-bold mb-0"><i class="fa fa-building text-primary me-1"></i> সাপ্লায়ার তথ্য</h6>
                                </div>
                                <div class="p-3">
                                    <p class="mb-1"><strong>{{ $supply->supplier->supplierProfile->company_name ?? $supply->supplier->name }}</strong></p>
                                    <p class="mb-1 small text-muted">নাম: {{ $supply->supplier->name }}</p>
                                    <p class="mb-1 small text-muted">মোবাইল: {{ $supply->supplier->phone ?? 'N/A' }}</p>
                                    <p class="mb-1 small text-muted">ইমেইল: {{ $supply->supplier->email ?? 'N/A' }}</p>
                                    <p class="mb-0 small fw-bold text-primary">সাপ্লায়ার আইডি: {{ $supply->supplier->supplierProfile->supplier_code ?? ('#' . $supply->supplier_id) }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-12 d-flex">
                            <div class="bg-white border rounded-4 shadow-xs w-100" style="height: auto !important; overflow: visible !important;">
                                <div class="py-2 px-3 bg-light border-bottom rounded-top-4">
                                    <h6 class="fw-bold mb-0"><i class="fa fa-file-text text-info me-1"></i> ইনভয়েস ও সরবরাহ তথ্য</h6>
                                </div>
                                <div class="p-3">
                                    <p class="mb-1">Supply ID: <strong>{{ $supply->supply_code }}</strong></p>
                                    <p class="mb-1 small">ইনভয়েস নং: <strong>{{ $supply->invoice_no }}</strong></p>
                                    <p class="mb-1 small">সরবরাহ তারিখ: <strong>{{ date('d M, Y', strtotime($supply->supply_date)) }}</strong></p>
                                    @if($supply->note)
                                        <p class="mb-2 small text-muted">নোট: {{ $supply->note }}</p>
                                    @endif
                                    @if($supply->invoice_file)
                                        <a href="{{ asset($supply->invoice_file) }}" target="_blank" class="btn btn-xs btn-info rounded-pill px-3 mt-1">
                                            <i class="fa fa-paperclip me-1"></i> ইনভয়েস / চালান দেখুন
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Approval & Agent/Branch Assignment Form (Section 5.1) -->
                    @if(!in_array($supply->status, ['received', 'rejected', 'cancelled']))
                    <div class="bg-white border rounded-4 shadow-xs mb-4 border-top border-4 border-success" style="height: auto !important; overflow: visible !important;">
                        <div class="py-3 px-4 bg-success-light border-bottom" style="display: block !important;">
                            <h5 class="fw-bold text-success mb-1">
                                <i class="fa fa-check-circle me-2"></i> অ্যাডমিন অনুমোদন ও এজেন্ট / ব্রাঞ্চ অ্যাসাইনমেন্ট
                            </h5>
                            <small class="text-muted d-block">অনুমোদনের সময় নির্দিষ্ট এজেন্ট ও ব্রাঞ্চ নির্বাচন করা বাধ্যতামূলক। এজেন্ট রিসিভ কনফার্ম করলে স্টক আপডেট হবে।</small>
                        </div>
                        <form action="{{ route('admin.suppliers.supplies.approve_assign', $supply->id) }}" method="POST">
                            @csrf
                            <div class="p-4">
                                <!-- Items Quantity & Rate Review -->
                                <h6 class="fw-bold mb-2">পণ্যের পরিমাণ ও রেট যাচাই</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>পণ্য</th>
                                                <th>জমাকৃত Qty</th>
                                                <th style="width: 150px;">অনুমোদিত Qty</th>
                                                <th style="width: 140px;">অনুমোদিত Rate (৳)</th>
                                                <th>জমাকৃত মোট</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($supply->items->count() > 0)
                                                @foreach($supply->items as $item)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $item->product_name }}</strong>
                                                        <div class="small text-muted">{{ $item->unit }}</div>
                                                    </td>
                                                    <td>{{ floatval($item->quantity) }} {{ $item->unit }}</td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0" name="items[{{ $item->id }}][approved_quantity]" class="form-control form-control-sm" value="{{ $item->approved_quantity ?? $item->quantity }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0" name="items[{{ $item->id }}][approved_rate]" class="form-control form-control-sm" value="{{ $item->approved_rate ?? $item->rate }}" required>
                                                    </td>
                                                    <td class="fw-bold">৳{{ number_format($item->total_amount, 2) }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td><strong>{{ $supply->product_name }}</strong> ({{ $supply->unit }})</td>
                                                    <td>{{ floatval($supply->quantity) }} {{ $supply->unit }}</td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0.01" name="approved_quantity" class="form-control form-control-sm" value="{{ $supply->approved_quantity ?? $supply->quantity }}" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" step="0.01" min="0.01" name="approved_rate" class="form-control form-control-sm" value="{{ $supply->approved_rate ?? $supply->rate }}" required>
                                                    </td>
                                                    <td class="fw-bold">৳{{ number_format($supply->total_amount, 2) }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Assignment Fields -->
                                <div class="row g-3">
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">এজেন্ট নির্বাচন করুন (Assign Agent) <span class="text-danger">*</span></label>
                                        <select name="assigned_agent_id" class="form-select" required>
                                            <option value="">-- এজেন্ট সিলেক্ট করুন --</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ $supply->assigned_agent_id == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->name }} ({{ $agent->phone }})
                                                    @if($agent->agentPoint) - [{{ $agent->agentPoint->name }}] @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">গন্তব্য ব্রাঞ্চ নির্বাচন (Destination Branch) <span class="text-danger">*</span></label>
                                        <select name="assigned_branch_id" class="form-select" required>
                                            <option value="">-- ব্রাঞ্চ সিলেক্ট করুন --</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ $supply->assigned_branch_id == $branch->id ? 'selected' : '' }}>
                                                    {{ $branch->name }} ({{ $branch->district ?? $branch->address }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">গন্তব্যের ধরন (Destination Type) <span class="text-danger">*</span></label>
                                        <select name="destination_type" class="form-select" required>
                                            <option value="branch" {{ $supply->destination_type === 'branch' ? 'selected' : '' }}>Branch (ব্রাঞ্চ)</option>
                                            <option value="warehouse" {{ $supply->destination_type === 'warehouse' ? 'selected' : '' }}>Warehouse (ওয়্যারহাউস)</option>
                                            <option value="distribution_center" {{ $supply->destination_type === 'distribution_center' ? 'selected' : '' }}>Distribution Center</option>
                                            <option value="agent_location" {{ $supply->destination_type === 'agent_location' ? 'selected' : '' }}>Agent Location</option>
                                            <option value="specific_address" {{ $supply->destination_type === 'specific_address' ? 'selected' : '' }}>Specific Address</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">নির্দিষ্ট স্থান / ঠিকানা</label>
                                        <input type="text" name="destination_location" class="form-control" value="{{ $supply->destination_location }}" placeholder="যেমন: ঢাকা মেইন ওয়্যারহাউস">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সম্ভাব্য ডেলিভারি তারিখ</label>
                                        <input type="date" name="expected_delivery_date" class="form-control" value="{{ $supply->expected_delivery_date ? $supply->expected_delivery_date->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">ডেলিভারি নির্দেশনা (Delivery Instructions)</label>
                                        <textarea name="delivery_instructions" class="form-control" rows="2" placeholder="সাপ্লায়ার ও এজেন্টের জন্য নির্দেশনা...">{{ $supply->delivery_instructions }}</textarea>
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label fw-bold">অ্যাডমিন নোট (Admin Note)</label>
                                        <textarea name="admin_note" class="form-control" rows="2" placeholder="অভ্যন্তরীণ মন্তব্য...">{{ $supply->admin_note }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-light px-4 py-3 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top rounded-bottom-4">
                                <button type="submit" class="btn btn-success btn-lg rounded-pill px-4">
                                    <i class="fa fa-check-circle me-1"></i>
                                    {{ $supply->assigned_agent_id ? 'আপডেট ও পুনঃঅ্যাসাইন করুন' : 'অনুমোদন ও অ্যাসাইন করুন' }}
                                </button>
                            </div>
                        </form>
                    </div>
                    @endif

                    <!-- Agent Receiving & Discrepancy Details (If Received) -->
                    @if($supply->receipts->count() > 0)
                    <div class="box border-0 shadow-xs rounded-4 mb-4 border-top border-4 border-info">
                        <div class="box-header py-3 px-4 bg-info-light">
                            <h5 class="fw-bold text-info mb-0"><i class="fa fa-check-square-o me-2"></i> এজেন্ট রিসিভিং ও ভেরিফিকেশন রিপোর্ট</h5>
                        </div>
                        <div class="box-body p-4">
                            @foreach($supply->receipts as $rec)
                            <div class="border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between flex-wrap mb-2">
                                    <strong>রিসিট নং: {{ $rec->receipt_no }}</strong>
                                    <span class="badge bg-primary">{{ strtoupper($rec->action_type) }}</span>
                                </div>
                                <div class="row g-2 small">
                                    <div class="col-md-3"><strong>অনুমোদিত:</strong> {{ floatval($rec->approved_quantity) }}</div>
                                    <div class="col-md-3 text-success"><strong>রিসিভড:</strong> {{ floatval($rec->actual_received_quantity) }}</div>
                                    <div class="col-md-3 text-danger"><strong>ক্ষতিগ্রস্ত:</strong> {{ floatval($rec->damaged_quantity) }}</div>
                                    <div class="col-md-3 text-warning"><strong>নিখোঁজ/ঘাটতি:</strong> {{ floatval($rec->missing_quantity) }}</div>
                                </div>
                                @if($rec->receiving_note)
                                    <div class="mt-2 small text-muted"><strong>নোট:</strong> {{ $rec->receiving_note }}</div>
                                @endif
                                @if($rec->discrepancy_report)
                                    <div class="mt-1 small text-danger"><strong>অমিল রিপোর্ট:</strong> {{ $rec->discrepancy_report }}</div>
                                @endif
                                @if($rec->photo_path)
                                    <div class="mt-2">
                                        <a href="{{ asset($rec->photo_path) }}" target="_blank" class="btn btn-xs btn-outline-info">রিসিভিং ছবি দেখুন</a>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right: Accounting Summary, Reject/Cancel Actions, Timeline & Audit -->
                <div class="col-lg-4 col-12">
                    <!-- Accounting Summary (Section 16) -->
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h6 class="fw-bold mb-0"><i class="fa fa-calculator text-success me-1"></i> অ্যাকাউন্টিং সারসংক্ষেপ</h6>
                        </div>
                        <div class="box-body p-3">
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">জমাকৃত মূল্য (Submitted):</span>
                                <strong>৳{{ number_format($supply->total_amount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">অনুমোদিত মূল্য (Approved):</span>
                                <strong class="text-info">৳{{ number_format($supply->approved_amount ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">রিসিভড মূল্য (Actual Received):</span>
                                <strong class="text-success">৳{{ number_format($supply->actual_received_amount, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span class="text-muted">ঘাটতি / অমিল (Discrepancy):</span>
                                <strong class="text-danger">৳{{ number_format($supply->discrepancy_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Reject / Cancel Assignment Actions -->
                    @if(!in_array($supply->status, ['received', 'rejected', 'cancelled']))
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h6 class="fw-bold text-danger mb-0"><i class="fa fa-exclamation-triangle me-1"></i> বাতিল / প্রত্যাখ্যান</h6>
                        </div>
                        <div class="box-body p-3">
                            @if($supply->assigned_agent_id && !in_array($supply->status, ['in_transit', 'partially_received']))
                                <form action="{{ route('admin.suppliers.supplies.cancel_assignment', $supply->id) }}" method="POST" class="mb-3">
                                    @csrf
                                    <label class="form-label small fw-bold">অ্যাসাইনমেন্ট বাতিলের কারণ</label>
                                    <input type="text" name="cancel_reason" class="form-control form-control-sm mb-2" placeholder="কারণ লিখুন..." required>
                                    <button type="submit" class="btn btn-warning btn-sm w-100 rounded-pill">অ্যাসাইনমেন্ট বাতিল করুন</button>
                                </form>
                            @endif

                            <form action="{{ route('admin.suppliers.supply.reject_post', $supply->id) }}" method="POST">
                                @csrf
                                <label class="form-label small fw-bold">সরবরাহ প্রত্যাখ্যান (Reject) কারণ</label>
                                <input type="text" name="rejection_reason" class="form-control form-control-sm mb-2" placeholder="রিজেক্ট করার কারণ লিখুন..." required>
                                <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই সরবরাহটি রিজেক্ট করতে চান?')">
                                    <i class="fa fa-times-circle me-1"></i> সরবরাহ রিজেক্ট করুন
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif

                    <!-- Status History -->
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h6 class="fw-bold mb-0"><i class="fa fa-history text-primary me-1"></i> স্ট্যাটাস ইতিহাস</h6>
                        </div>
                        <div class="box-body p-3">
                            @forelse($supply->statusHistories as $hist)
                                <div class="border-start border-2 border-primary ps-3 pb-2 mb-2">
                                    <div class="fw-bold fs-12">{{ \App\Models\SupplierSupply::STATUS_META[$hist->to_status]['label'] ?? $hist->to_status }}</div>
                                    <div class="small text-muted">{{ $hist->remarks }}</div>
                                    <small class="text-muted fs-11">{{ $hist->created_at->format('d M, Y h:i A') }} {{ $hist->user ? '• ' . $hist->user->name : '' }}</small>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">কোনো হিস্ট্রি নেই।</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
