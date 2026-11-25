@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-truck me-2 text-primary"></i> সেন্ট্রাল স্টক বরাদ্দ ও রুট ডিসপ্যাচ (Product Allocation & Dispatch)</h4>
                    <p class="subtitle text-muted mb-0">সেন্ট্রাল ওয়্যারহাউস থেকে সরাসরি নির্দিষ্ট এজেন্ট পয়েন্টে পণ্য স্থানান্তর ও ডেলিভারি চালান তৈরি</p>
                </div>
                <a href="{{ route('admin.distribution.warehouse_stock') }}" class="btn btn-outline-info btn-sm">
                    <i class="fa fa-cubes me-1"></i> সেন্ট্রাল ওয়্যারহাউস স্টক
                </a>
            </div>
        </div>

        <!-- Main content -->
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

            <div class="row">
                <!-- Left: Create Dispatch Form -->
                <div class="col-xl-5 col-lg-6 mb-4">
                    <div class="box shadow-sm border-top border-primary border-3">
                        <div class="box-header with-border bg-light">
                            <h5 class="box-title text-dark"><i class="fa fa-plus-circle text-primary me-2"></i> নতুন ডিসপ্যাচ চালান তৈরি</h5>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('admin.distribution.allocate.store') }}" method="POST" id="dispatchForm">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label fw-bold">প্রাপক এজেন্ট পয়েন্ট (Agent Point) <span class="text-danger">*</span></label>
                                    <select name="agent_point_id" class="form-select form-select-lg" required>
                                        <option value="">-- এজেন্ট পয়েন্ট নির্বাচন করুন --</option>
                                        @foreach($agentPoints as $pt)
                                            <option value="{{ $pt->id }}">
                                                {{ $pt->name }} ({{ $pt->code ?: ('AP-'.$pt->id) }}) - {{ $pt->areaModel ? $pt->areaModel->name : ($pt->area ?: 'N/A') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">ডিসপ্যাচ তারিখ <span class="text-danger">*</span></label>
                                    <input type="date" name="dispatch_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                </div>

                                <hr>
                                <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                    <span>বরাদ্দকৃত পণ্যসমূহ <span class="text-danger">*</span></span>
                                    <button type="button" class="btn btn-xs btn-outline-primary" onclick="addDispatchItem()">
                                        <i class="fa fa-plus"></i> পণ্য যোগ করুন
                                    </button>
                                </label>

                                <div id="dispatchItemsContainer">
                                    <!-- Row 0 -->
                                    <div class="item-row p-3 mb-2 rounded bg-light border">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-7">
                                                <label class="form-label small text-muted mb-1">পণ্য নির্বাচন</label>
                                                <select name="items[0][product_name]" class="form-select form-select-sm product-select" required onchange="updateStockBadge(this)">
                                                    <option value="">-- পণ্য নির্বাচন --</option>
                                                    @foreach($warehouseStocks as $st)
                                                        <option value="{{ $st->product_name }}" data-stock="{{ $st->available_stock }}" data-unit="{{ $st->unit }}">
                                                            {{ $st->product_name }} (স্টক: {{ $st->available_stock }} {{ $st->unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label small text-muted mb-1">পরিমাণ (KG/ইউনিট)</label>
                                                <input type="number" step="0.1" name="items[0][quantity]" class="form-control form-control-sm" placeholder="0.0" required min="0.1">
                                            </div>
                                            <div class="col-1 text-center pt-4">
                                                <button type="button" class="btn btn-sm text-danger p-0" onclick="removeRow(this)" title="মুছুন"><i class="fa fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3 mt-3">
                                    <label class="form-label fw-bold">চালান মন্তব্য / রুট নোট</label>
                                    <textarea name="notes" class="form-control" rows="2" placeholder="পরিবহন গাড়ির নম্বর, চালকের নাম বা বিশেষ নির্দেশনা"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fs-15 fw-bold">
                                    <i class="fa fa-paper-plane me-1"></i> ডিসপ্যাচ নিশ্চিত ও সেন্ট্রাল স্টক হস্তান্তর
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right: Recent Outbound Dispatches List -->
                <div class="col-xl-7 col-lg-6">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h5 class="box-title text-dark mb-0"><i class="fa fa-history text-success me-2"></i> সাম্প্রতিক ডিসপ্যাচ চালান তালিকা</h5>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>চালান নং</th>
                                            <th>গন্তব্য এজেন্ট পয়েন্ট</th>
                                            <th>তারিখ</th>
                                            <th>পণ্যের তালিকা</th>
                                            <th>অবস্থা</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dispatches as $dsp)
                                        <tr>
                                            <td><span class="badge bg-secondary font-monospace">{{ $dsp->dispatch_no }}</span></td>
                                            <td>
                                                <strong class="text-dark">{{ $dsp->agentPoint ? $dsp->agentPoint->name : 'N/A' }}</strong>
                                                <div class="small text-muted">{{ $dsp->agentPoint && $dsp->agentPoint->areaModel ? $dsp->agentPoint->areaModel->name : '' }}</div>
                                            </td>
                                            <td>{{ $dsp->dispatch_date }}</td>
                                            <td>
                                                @foreach($dsp->items as $it)
                                                    <span class="badge bg-light text-dark border me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                                                @endforeach
                                            </td>
                                            <td>
                                                @if($dsp->status === 'received')
                                                    <span class="badge bg-success"><i class="fa fa-check"></i> প্রাপ্ত হয়েছে</span>
                                                @else
                                                    <span class="badge bg-warning text-dark"><i class="fa fa-clock-o"></i> ট্রানজিটে</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">কোনো ডিসপ্যাচ তৈরি করা হয়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($dispatches->hasPages())
                        <div class="box-footer">
                            {{ $dispatches->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
let rowIndex = 1;
function addDispatchItem() {
    const container = document.getElementById('dispatchItemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row p-3 mb-2 rounded bg-light border';
    newRow.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-7">
                <label class="form-label small text-muted mb-1">পণ্য নির্বাচন</label>
                <select name="items[${rowIndex}][product_name]" class="form-select form-select-sm product-select" required onchange="updateStockBadge(this)">
                    <option value="">-- পণ্য নির্বাচন --</option>
                    @foreach($warehouseStocks as $st)
                        <option value="{{ $st->product_name }}" data-stock="{{ $st->available_stock }}" data-unit="{{ $st->unit }}">
                            {{ $st->product_name }} (স্টক: {{ $st->available_stock }} {{ $st->unit }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-4">
                <label class="form-label small text-muted mb-1">পরিমাণ (KG/ইউনিট)</label>
                <input type="number" step="0.1" name="items[${rowIndex}][quantity]" class="form-control form-control-sm" placeholder="0.0" required min="0.1">
            </div>
            <div class="col-1 text-center pt-4">
                <button type="button" class="btn btn-sm text-danger p-0" onclick="removeRow(this)" title="মুছুন"><i class="fa fa-times"></i></button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    rowIndex++;
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        btn.closest('.item-row').remove();
    } else {
        alert('কমপক্ষে একটি পণ্য বরাদ্দ থাকা আবশ্যক।');
    }
}

function updateStockBadge(select) {
    // Optional indicator
}
</script>
@endsection
