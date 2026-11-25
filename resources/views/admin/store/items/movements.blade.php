@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-exchange text-success me-2"></i> স্টক মুভমেন্ট লেজার (Stock Movement & Ledger)
                    </h3>
                    <p class="text-muted mb-0">সকল ইনকামিং মালামাল (Stock In) ও বিতরণকৃত মালামাল (Stock Out / Issue) ট্র্যাকিং</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#stockInModal">
                        <i class="fa fa-arrow-down me-1"></i> স্টক ইন (ক্রয় / রিসিভ)
                    </button>
                    <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#stockOutModal">
                        <i class="fa fa-arrow-up me-1"></i> স্টক আউট (ইস্যু / বিতরণ)
                    </button>
                    <a href="{{ route('admin.store.items.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> পণ্য তালিকা
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

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.store.items.movements') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">পণ্য নির্বাচন করুন</label>
                            <select name="item_id" class="form-select">
                                <option value="">-- সকল পণ্য --</option>
                                @foreach($items as $it)
                                    <option value="{{ $it->id }}" {{ request('item_id') == $it->id ? 'selected' : '' }}>
                                        {{ $it->name }} ({{ $it->item_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">স্টোর ফিল্টার</label>
                            <select name="store_id" class="form-select">
                                <option value="">-- সকল স্টোর --</option>
                                @foreach($stores as $st)
                                    <option value="{{ $st->id }}" {{ request('store_id') == $st->id ? 'selected' : '' }}>
                                        {{ $st->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">মুভমেন্টের ধরন</label>
                            <select name="movement_type" class="form-select">
                                <option value="">-- সকল ধরন --</option>
                                <option value="in" {{ request('movement_type') == 'in' ? 'selected' : '' }}>Stock In (ইন)</option>
                                <option value="out" {{ request('movement_type') == 'out' ? 'selected' : '' }}>Stock Out (আউট)</option>
                                <option value="transfer" {{ request('movement_type') == 'transfer' ? 'selected' : '' }}>Transfer (স্থানান্তর)</option>
                                <option value="adjustment" {{ request('movement_type') == 'adjustment' ? 'selected' : '' }}>Adjustment (সমন্বয়)</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">ফিল্টার</button>
                            <a href="{{ route('admin.store.items.movements') }}" class="btn btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> চলমান স্টক লেনদেন হিস্টোরি (Running Balance)
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>তারিখ ও সময়</th>
                                    <th>রেফারেন্স নং</th>
                                    <th>পণ্যের নাম</th>
                                    <th>স্টোর</th>
                                    <th>লেনদেনের ধরন</th>
                                    <th>পরিমাণ</th>
                                    <th>একক মূল্য</th>
                                    <th>মোট মূল্য</th>
                                    <th>অবশিষ্ট ব্যালেন্স</th>
                                    <th>গ্রহীতা / প্রেরক</th>
                                    <th>মন্তব্য</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($movements as $mov)
                                <tr>
                                    <td class="small">{{ \Carbon\Carbon::parse($mov->movement_date)->format('d M, Y') }}</td>
                                    <td><span class="badge bg-secondary font-monospace">{{ $mov->reference_no ?? 'N/A' }}</span></td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $mov->item->name ?? 'Deleted Item' }}</div>
                                        <small class="text-muted">{{ $mov->item->item_code ?? '' }}</small>
                                    </td>
                                    <td>{{ $mov->store->name ?? 'Central Store' }}</td>
                                    <td>
                                        @if($mov->movement_type == 'in')
                                            <span class="badge bg-success-light text-success fw-bold"><i class="fa fa-arrow-down me-1"></i> Stock In</span>
                                        @elseif($mov->movement_type == 'out')
                                            <span class="badge bg-danger-light text-danger fw-bold"><i class="fa fa-arrow-up me-1"></i> Stock Out</span>
                                        @elseif($mov->movement_type == 'transfer')
                                            <span class="badge bg-info-light text-info fw-bold"><i class="fa fa-random me-1"></i> Transfer</span>
                                        @else
                                            <span class="badge bg-warning-light text-warning fw-bold"><i class="fa fa-adjust me-1"></i> Adjustment</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold fs-6 {{ $mov->movement_type == 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $mov->movement_type == 'in' ? '+' : '-' }}{{ $mov->quantity }} {{ $mov->item->unit ?? '' }}
                                    </td>
                                    <td>৳{{ number_format($mov->unit_price, 2) }}</td>
                                    <td class="fw-bold">৳{{ number_format($mov->total_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-dark fs-6">{{ $mov->running_balance }}</span>
                                    </td>
                                    <td>
                                        @if($mov->issued_to)
                                            <div><i class="fa fa-user me-1 text-muted"></i> {{ $mov->issued_to }}</div>
                                            <small class="text-muted">{{ $mov->department ?? '' }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="small text-muted">{{ $mov->remarks ?? '—' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">কোনো স্টক মুভমেন্ট রেকর্ড পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(method_exists($movements, 'links'))
                    <div class="card-footer bg-white">
                        {{ $movements->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Stock In -->
<div class="modal fade" id="stockInModal" tabindex="-1" aria-labelledby="stockInModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.items.stock_in') }}" method="POST">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="stockInModalLabel"><i class="fa fa-arrow-down me-2"></i> স্টক ইন / রিসিভ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">পণ্য নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="erp_item_id" class="form-select" required>
                            @foreach($items as $it)
                                <option value="{{ $it->id }}">{{ $it->name }} (বর্তমান স্টক: {{ $it->current_stock }} {{ $it->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোর নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="erp_store_id" class="form-select" required>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }} ({{ $st->store_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">পরিমাণ <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity" class="form-control" placeholder="পরিমাণ" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">একক ক্রয়মূল্য (৳)</label>
                            <input type="number" step="0.01" name="unit_price" class="form-control" placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">রেফারেন্স / চালানের নম্বর</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="যেমন: CHALAN-9021 / PO-401">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">মন্তব্য / নোট</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="ক্রয় বা প্রাপ্তির বিবরণ"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-save me-1"></i> স্টক ইন সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Stock Out -->
<div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.items.stock_out') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="stockOutModalLabel"><i class="fa fa-arrow-up me-2"></i> স্টক আউট / ইস্যু করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">পণ্য নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="erp_item_id" class="form-select" required>
                            @foreach($items as $it)
                                <option value="{{ $it->id }}">{{ $it->name }} (বর্তমান মজুদ: {{ $it->current_stock }} {{ $it->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টোর <span class="text-danger">*</span></label>
                        <select name="erp_store_id" class="form-select" required>
                            @foreach($stores as $st)
                                <option value="{{ $st->id }}">{{ $st->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">বিতরণের পরিমাণ <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="quantity" class="form-control" placeholder="পরিমাণ" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">গ্রহীতার নাম <span class="text-danger">*</span></label>
                            <input type="text" name="issued_to" class="form-control" placeholder="কর্মকর্তা / কর্মচারী" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">বিভাগ / শাখা</label>
                            <input type="text" name="department" class="form-control" placeholder="যেমন: আইটি, অ্যাকাউন্টস">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">বিতরণের উদ্দেশ্য (Purpose)</label>
                        <input type="text" name="purpose" class="form-control" placeholder="যেমন: দৈনন্দিন অফিস কার্যক্রম">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ইস্যু স্লিপ / রিকুইজিশন নং</label>
                        <input type="text" name="reference_no" class="form-control" placeholder="যেমন: REQ-0012">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">মন্তব্য</label>
                        <textarea name="remarks" class="form-control" rows="2" placeholder="মন্তব্য"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-paper-plane me-1"></i> ইস্যু অনুমোদন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

