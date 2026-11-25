@extends('layouts.agent.app')

@section('title', 'সরবরাহ যাচাই ও রিসিভ - ' . ($supply->supply_code ?? $supply->id))

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1">
            <i class="fa fa-check-square-o text-success me-2"></i> ইনকামিং সাপ্লাই রিসিভিং: {{ $supply->supply_code }}
        </h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • পণ্যের পরিমাণ ও গুণমান যাচাই করে রিসিভ কনফার্ম করুন</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        @php $meta = $supply->status_meta; @endphp
        <span class="badge bg-{{ $meta['badge'] }} fs-6 px-3 py-2 rounded-pill">{{ $meta['label'] }}</span>
        <a href="{{ route('agent.supplies.index') }}" class="btn btn-sm btn-outline-light">ফিরে যান</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="row g-4">
    <!-- Left: Info & Receiving Form -->
    <div class="col-lg-8 col-12">
        <!-- Supplier & Delivery Info -->
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title"><i class="fa fa-truck text-warning"></i> সরবরাহকারী ও ডেলিভারি তথ্য</h5>
            </div>
            <div class="row g-3 small">
                <div class="col-md-4">
                    <span class="text-muted d-block">সাপ্লায়ার:</span>
                    <strong class="text-white fs-6">{{ $supply->supplier->supplierProfile->company_name ?? $supply->supplier->name }}</strong>
                    <div class="text-muted">{{ $supply->supplier->phone }}</div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block">ইনভয়েস ও তারিখ:</span>
                    <strong class="text-white">{{ $supply->invoice_no }}</strong>
                    <div class="text-muted">{{ date('d M, Y', strtotime($supply->supply_date)) }}</div>
                </div>
                <div class="col-md-4">
                    <span class="text-muted d-block">গন্তব্য ব্রাঞ্চ ও স্থান:</span>
                    <strong class="text-info">{{ $supply->assignedBranch->name ?? 'N/A' }}</strong>
                    <div class="text-muted">{{ $supply->destination_location }}</div>
                </div>
                @if($supply->delivery_instructions)
                <div class="col-12">
                    <div class="p-3 rounded-3" style="background: rgba(59, 130, 246, 0.12); border: 1px solid rgba(59, 130, 246, 0.3);">
                        <strong class="text-info d-block mb-1">অ্যাডমিন নির্দেশনা:</strong>
                        <span class="text-white">{{ $supply->delivery_instructions }}</span>
                    </div>
                </div>
                @endif
                @if($supply->invoice_file)
                <div class="col-12">
                    <a href="{{ asset($supply->invoice_file) }}" target="_blank" class="btn btn-sm btn-outline-info">
                        <i class="fa fa-paperclip me-1"></i> সাপ্লায়ারের ইনভয়েস/চালান দেখুন
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Agent Receiving Form (SRD Section 8) -->
        @if(!in_array($supply->status, ['received', 'rejected', 'cancelled']))
        <div class="dark-card mb-4">
            <div class="dark-card-header">
                <h5 class="dark-card-title"><i class="fa fa-check-circle text-success"></i> পণ্য গ্রহণ ও যাচাই ফর্ম (Agent Receiving Form)</h5>
            </div>
            <form action="{{ route('agent.supplies.receive', $supply->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="table-responsive mb-4">
                    <table class="table dark-table">
                        <thead>
                            <tr>
                                <th>পণ্য</th>
                                <th>অ্যাডমিন অনুমোদিত</th>
                                <th style="width: 150px;">বাস্তব রিসিভড Qty</th>
                                <th style="width: 130px;">ক্ষতিগ্রস্ত Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($supply->items->count() > 0)
                                @foreach($supply->items as $item)
                                @php $appQ = $item->approved_quantity ?? $item->quantity; @endphp
                                <tr>
                                    <td>
                                        <strong class="text-white">{{ $item->product_name }}</strong>
                                        <div class="small text-muted">রেট: ৳{{ number_format($item->approved_rate ?? $item->rate, 2) }} / {{ $item->unit }}</div>
                                    </td>
                                    <td class="text-info fw-bold">{{ floatval($appQ) }} {{ $item->unit }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="items[{{ $item->id }}][received_quantity]" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ max(0, $appQ - $item->received_quantity) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="items[{{ $item->id }}][damaged_quantity]" class="form-control form-control-sm bg-dark text-white border-secondary" value="0">
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                @php $appQ = $supply->approved_quantity ?? $supply->quantity; @endphp
                                <tr>
                                    <td>
                                        <strong class="text-white">{{ $supply->product_name }}</strong>
                                        <div class="small text-muted">রেট: ৳{{ number_format($supply->approved_rate ?? $supply->rate, 2) }} / {{ $supply->unit }}</div>
                                    </td>
                                    <td class="text-info fw-bold">{{ floatval($appQ) }} {{ $supply->unit }}</td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="actual_received_quantity" class="form-control form-control-sm bg-dark text-white border-secondary" value="{{ max(0, $appQ - $supply->actual_received_quantity) }}" required>
                                    </td>
                                    <td>
                                        <input type="number" step="0.01" min="0" name="damaged_quantity" class="form-control form-control-sm bg-dark text-white border-secondary" value="0">
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">অ্যাকশন নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="action_type" id="agentActionType" class="form-select bg-dark text-white border-secondary" required onchange="toggleRejectReason()">
                            <option value="confirm_received">Confirm Received (সম্পূর্ণ রিসিভ)</option>
                            <option value="partial_received">Partial Received (আংশিক রিসিভ)</option>
                            <option value="discrepancy">Report Discrepancy (ঘাটতি/ক্ষতি রিপোর্ট)</option>
                            <option value="rejected">Reject Delivery (ডেলিভারি প্রত্যাখ্যান)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">পণ্যের অবস্থা <span class="text-danger">*</span></label>
                        <select name="product_condition" class="form-select bg-dark text-white border-secondary" required>
                            <option value="good">ভালো (Good)</option>
                            <option value="partially_damaged">আংশিক ক্ষতিগ্রস্ত (Partially Damaged)</option>
                            <option value="damaged">ক্ষতিগ্রস্ত (Damaged)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-white small fw-bold">গ্রহণের তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="received_date" class="form-control bg-dark text-white border-secondary" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">রিসিভিং ছবি (ঐচ্ছিক)</label>
                        <input type="file" name="photo" class="form-control bg-dark text-white border-secondary" accept="image/*">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-white small fw-bold">এজেন্ট মন্তব্য (Receiving Note)</label>
                        <input type="text" name="receiving_note" class="form-control bg-dark text-white border-secondary" placeholder="মন্তব্য লিখুন...">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-white small fw-bold">ঘাটতি / অমিল বিবরণ (যদি থাকে)</label>
                        <textarea name="discrepancy_report" class="form-control bg-dark text-white border-secondary" rows="2" placeholder="কম বা ক্ষতিগ্রস্ত পণ্যের বিস্তারিত..."></textarea>
                    </div>
                    <div class="col-12 d-none" id="rejectReasonBox">
                        <label class="form-label text-danger small fw-bold">ডেলিভারি রিজেক্ট করার কারণ <span class="text-danger">*</span></label>
                        <input type="text" name="reject_reason" id="rejectReasonInput" class="form-control bg-dark text-white border-danger" placeholder="কেন ডেলিভারি গ্রহণ করা হচ্ছে না লিখুন...">
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-success btn-lg px-4 fw-bold" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই রিসিভ তথ্য সাবমিট করতে চান? এতে আপনার এজেন্ট স্টক আপডেট হবে।')">
                        <i class="fa fa-check-circle me-1"></i> সাবমিট ও স্টক আপডেট করুন
                    </button>
                </div>
            </form>
        </div>
        @endif

        <!-- Previous Receipts -->
        @if($supply->receipts->count() > 0)
        <div class="dark-card">
            <div class="dark-card-header">
                <h5 class="dark-card-title"><i class="fa fa-history text-info"></i> রিসিভিং রেকর্ডসমূহ</h5>
            </div>
            @foreach($supply->receipts as $rec)
                <div class="p-3 mb-2 rounded-3" style="background: rgba(255,255,255,0.03); border: 1px solid var(--agent-border);">
                    <div class="d-flex justify-content-between mb-1">
                        <strong class="text-white">{{ $rec->receipt_no }}</strong>
                        <span class="badge bg-info">{{ $rec->action_type }}</span>
                    </div>
                    <div class="small text-muted">
                        রিসিভড: <strong class="text-success">{{ floatval($rec->actual_received_quantity) }}</strong> |
                        ক্ষতিগ্রস্ত: <strong class="text-danger">{{ floatval($rec->damaged_quantity) }}</strong> |
                        ঘাটতি: <strong class="text-warning">{{ floatval($rec->missing_quantity) }}</strong> |
                        তারিখ: {{ $rec->received_date }}
                    </div>
                    @if($rec->receiving_note)<div class="small text-white mt-1">{{ $rec->receiving_note }}</div>@endif
                </div>
            @endforeach
        </div>
        @endif
    </div>

    <!-- Right: Status Timeline -->
    <div class="col-lg-4 col-12">
        <div class="dark-card">
            <div class="dark-card-header">
                <h5 class="dark-card-title"><i class="fa fa-clock-o text-primary"></i> স্ট্যাটাস টাইমলাইন</h5>
            </div>
            @foreach($supply->statusHistories as $hist)
                <div class="border-start border-2 border-info ps-3 pb-3 mb-2">
                    <div class="fw-bold text-white small">{{ \App\Models\SupplierSupply::STATUS_META[$hist->to_status]['label'] ?? $hist->to_status }}</div>
                    <div class="small text-muted">{{ $hist->remarks }}</div>
                    <small class="text-muted" style="font-size: 11px;">{{ $hist->created_at->format('d M, Y h:i A') }}</small>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    function toggleRejectReason() {
        const val = document.getElementById('agentActionType').value;
        const box = document.getElementById('rejectReasonBox');
        const input = document.getElementById('rejectReasonInput');
        if (val === 'rejected') {
            box.classList.remove('d-none');
            input.required = true;
        } else {
            box.classList.add('d-none');
            input.required = false;
        }
    }
</script>
@endsection
