@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-truck text-primary me-2"></i> সরবরাহ বিস্তারিত: {{ $supply->supply_code ?: ('#' . $supply->id) }}
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('supplier.supplies.index') }}">সরবরাহ তালিকা</a></li>
                        <li class="breadcrumb-item active">{{ $supply->supply_code }}</li>
                    </ol>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @php $meta = $supply->status_meta; @endphp
                    <span class="badge bg-{{ $meta['badge'] }} fs-14 px-3 py-2 rounded-pill">
                        <i class="fa {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}
                    </span>
                    <a href="{{ route('supplier.invoice.print', $supply->id) }}" target="_blank" class="btn btn-outline-primary rounded-pill px-3">
                        <i class="fa fa-print me-1"></i> চালান প্রিন্ট
                    </a>
                    <a href="{{ route('supplier.supplies.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row g-4">
                <!-- Left Column: Supply & Items + Delivery Info -->
                <div class="col-lg-8 col-12">
                    <!-- Direct Delivery Information (When Assigned/Approved) -->
                    @if($supply->assignedAgent || $supply->assignedBranch)
                    <div class="box border-0 shadow-xs rounded-4 mb-4 border-start border-4 border-primary">
                        <div class="box-header py-3 px-4 bg-primary-light">
                            <h5 class="fw-bold text-primary mb-0">
                                <i class="fa fa-map-marker me-2"></i> সরাসরি ডেলিভারি গন্তব্য (Direct Delivery Destination)
                            </h5>
                        </div>
                        <div class="box-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block">নির্ধারিত ব্রাঞ্চ (Destination Branch)</small>
                                    <strong class="fs-15 text-dark">{{ $supply->assignedBranch->name ?? 'N/A' }}</strong>
                                    @if($supply->assignedBranch && $supply->assignedBranch->address)
                                        <div class="small text-muted">{{ $supply->assignedBranch->address }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">নির্ধারিত এজেন্ট (Assigned Agent)</small>
                                    <strong class="fs-15 text-dark">{{ $supply->assignedAgent->name ?? 'N/A' }}</strong>
                                    @if($supply->assignedAgent && $supply->assignedAgent->phone)
                                        <div class="small text-primary"><i class="fa fa-phone me-1"></i>{{ $supply->assignedAgent->phone }}</div>
                                    @endif
                                    @if($supply->assignedAgentPoint)
                                        <div class="small text-muted">পয়েন্ট: {{ $supply->assignedAgentPoint->name }}</div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">নির্দিষ্ট স্থান (Destination Location)</small>
                                    <strong>{{ $supply->destination_location ?: ucfirst(str_replace('_', ' ', (string) $supply->destination_type)) }}</strong>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block">সম্ভাব্য পৌঁছানোর তারিখ (Expected Delivery)</small>
                                    <strong class="text-danger">
                                        {{ $supply->expected_delivery_date ? $supply->expected_delivery_date->format('d M, Y') : 'নির্ধারিত নয়' }}
                                    </strong>
                                </div>
                                @if($supply->delivery_instructions)
                                <div class="col-12">
                                    <div class="p-3 bg-light rounded-3 border">
                                        <small class="text-muted fw-bold d-block mb-1">অ্যাডমিনের ডেলিভারি নির্দেশনা:</small>
                                        {{ $supply->delivery_instructions }}
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if(in_array($supply->status, ['assigned', 'approved']))
                                <hr>
                                <form action="{{ route('supplier.supplies.dispatch', $supply->id) }}" method="POST" class="d-flex gap-2 align-items-center flex-wrap">
                                    @csrf
                                    <input type="text" name="dispatch_note" class="form-control form-control-sm flex-grow-1" style="max-width: 360px;" placeholder="গাড়ি/কুরিয়ার বা ডেলিভারি নোট (ঐচ্ছিক)">
                                    <button type="submit" class="btn btn-warning fw-bold rounded-pill px-4" onclick="return confirm('আপনি কি নির্ধারিত গন্তব্যে মালামাল পাঠিয়েছেন?')">
                                        <i class="fa fa-truck me-1"></i> পণ্য পাঠানো হয়েছে (Mark In Transit)
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($supply->status === 'draft')
                    <div class="alert alert-warning d-flex justify-content-between align-items-center flex-wrap gap-2 rounded-4">
                        <span>এই সরবরাহটি বর্তমানে <strong>Draft</strong> হিসেবে আছে। অ্যাডমিন অনুমোদনের জন্য পাঠাতে সাবমিট করুন।</span>
                        <form action="{{ route('supplier.supplies.submit_draft', $supply->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm rounded-pill px-3">এখনই সাবমিট করুন</button>
                        </form>
                    </div>
                    @endif

                    <!-- Product Items Breakdown -->
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h5 class="fw-bold text-dark mb-0"><i class="fa fa-list me-2 text-primary"></i> পণ্যের বিবরণ ও হিসাব</h5>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>পণ্য</th>
                                            <th class="text-center">জমাকৃত Qty</th>
                                            <th class="text-center">অনুমোদিত Qty</th>
                                            <th class="text-center">রিসিভড Qty</th>
                                            <th class="text-end">রেট (৳)</th>
                                            <th class="text-end">মোট মূল্য (৳)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($supply->items->count() > 0)
                                            @foreach($supply->items as $item)
                                            <tr>
                                                <td>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if($item->category)<div class="small text-muted">{{ $item->category }}</div>@endif
                                                </td>
                                                <td class="text-center">{{ floatval($item->quantity) }} {{ $item->unit }}</td>
                                                <td class="text-center text-info fw-bold">
                                                    {{ $item->approved_quantity !== null ? floatval($item->approved_quantity) . ' ' . $item->unit : '-' }}
                                                </td>
                                                <td class="text-center text-success fw-bold">
                                                    {{ $item->received_quantity > 0 ? floatval($item->received_quantity) . ' ' . $item->unit : '-' }}
                                                    @if($item->missing_quantity > 0 || $item->damaged_quantity > 0)
                                                        <div class="small text-danger">ঘাটতি/ক্ষতি: {{ floatval($item->missing_quantity + $item->damaged_quantity) }}</div>
                                                    @endif
                                                </td>
                                                <td class="text-end">৳{{ number_format($item->approved_rate ?? $item->rate, 2) }}</td>
                                                <td class="text-end fw-bold">৳{{ number_format($item->total_amount, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td><strong>{{ $supply->product_name }}</strong></td>
                                                <td class="text-center">{{ floatval($supply->quantity) }} {{ $supply->unit }}</td>
                                                <td class="text-center text-info fw-bold">{{ $supply->approved_quantity !== null ? floatval($supply->approved_quantity) . ' ' . $supply->unit : '-' }}</td>
                                                <td class="text-center text-success fw-bold">{{ $supply->actual_received_quantity > 0 ? floatval($supply->actual_received_quantity) . ' ' . $supply->unit : '-' }}</td>
                                                <td class="text-end">৳{{ number_format($supply->approved_rate ?? $supply->rate, 2) }}</td>
                                                <td class="text-end fw-bold">৳{{ number_format($supply->total_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Accounting Summary Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-white rounded-4 shadow-xs text-center">
                                <small class="text-muted d-block">জমাকৃত মূল্য</small>
                                <strong class="fs-16 text-dark">৳{{ number_format($supply->total_amount, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-white rounded-4 shadow-xs text-center">
                                <small class="text-muted d-block">অনুমোদিত মূল্য</small>
                                <strong class="fs-16 text-info">৳{{ number_format($supply->approved_amount ?? 0, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-white rounded-4 shadow-xs text-center">
                                <small class="text-muted d-block">বাস্তব রিসিভড মূল্য</small>
                                <strong class="fs-16 text-success">৳{{ number_format($supply->actual_received_amount, 2) }}</strong>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="p-3 bg-white rounded-4 shadow-xs text-center">
                                <small class="text-muted d-block">ঘাটতি / অমিল</small>
                                <strong class="fs-16 text-danger">৳{{ number_format($supply->discrepancy_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Invoice Info & Status Timeline -->
                <div class="col-lg-4 col-12">
                    <div class="box border-0 shadow-xs rounded-4 mb-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h5 class="fw-bold mb-0"><i class="fa fa-file-text me-2 text-info"></i> ইনভয়েস ও ডকুমেন্ট</h5>
                        </div>
                        <div class="box-body p-4">
                            <p class="mb-2"><span class="text-muted">Supply ID:</span> <strong>{{ $supply->supply_code }}</strong></p>
                            <p class="mb-2"><span class="text-muted">Invoice No:</span> <strong>{{ $supply->invoice_no }}</strong></p>
                            <p class="mb-2"><span class="text-muted">Supply Date:</span> <strong>{{ date('d M, Y', strtotime($supply->supply_date)) }}</strong></p>
                            @if($supply->note)
                                <p class="mb-3"><span class="text-muted">নোট:</span> {{ $supply->note }}</p>
                            @endif
                            @if($supply->rejection_reason)
                                <div class="alert alert-danger py-2 px-3 small">
                                    <strong>রিজেকশন কারণ:</strong> {{ $supply->rejection_reason }}
                                </div>
                            @endif
                            @if($supply->invoice_file)
                                <a href="{{ asset($supply->invoice_file) }}" target="_blank" class="btn btn-outline-info btn-sm w-100 rounded-pill">
                                    <i class="fa fa-download me-1"></i> ইনভয়েস / চালান দেখুন
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="box border-0 shadow-xs rounded-4">
                        <div class="box-header py-3 px-4 bg-light border-bottom">
                            <h5 class="fw-bold mb-0"><i class="fa fa-history me-2 text-success"></i> স্ট্যাটাস টাইমলাইন</h5>
                        </div>
                        <div class="box-body p-4">
                            @forelse($supply->statusHistories as $hist)
                                <div class="border-start border-2 border-primary ps-3 pb-3 position-relative">
                                    <div class="fw-bold text-dark fs-13">{{ \App\Models\SupplierSupply::STATUS_META[$hist->to_status]['label'] ?? $hist->to_status }}</div>
                                    <div class="small text-muted">{{ $hist->remarks }}</div>
                                    <small class="text-muted fs-11">
                                        {{ $hist->created_at->format('d M, Y h:i A') }}
                                        @if($hist->user) • {{ $hist->user->name }} @endif
                                    </small>
                                </div>
                            @empty
                                <p class="text-muted small mb-0">কোনো হিস্ট্রি পাওয়া যায়নি।</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
