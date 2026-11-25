@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-money text-success me-2"></i> Accounts → Supplier Payments & Invoice Settlement
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">Supplier Payments</li>
                    </ol>
                </div>
                <a href="{{ route('admin.suppliers.master_dashboard') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Master Accounting</a>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger rounded-3"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">মোট সাপ্লায়ার পাওনা (Total Payable)</small>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalPayable, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">মোট পরিশোধিত (Total Paid)</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalPaid, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-4 col-12">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">মোট বকেয়া (Total Unpaid Due)</small>
                        <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($totalDue, 2) }}</h3>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Left: Create Supplier Payment Form -->
                <div class="col-lg-5 col-12">
                    <div class="bg-white border rounded-4 shadow-xs overflow-hidden">
                        <div class="py-3 px-4 bg-success text-white">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-plus-circle me-2"></i> নতুন Supplier Payment প্রদান</h5>
                        </div>
                        <form action="{{ route('admin.suppliers.payments.store_allocated') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="p-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">সাপ্লায়ার নির্বাচন করুন <span class="text-danger">*</span></label>
                                    <select name="supplier_id" id="paySupplierSelect" class="form-select" required onchange="filterSupplierInvoices()">
                                        <option value="">-- সাপ্লায়ার সিলেক্ট করুন --</option>
                                        @foreach($suppliers as $s)
                                            <option value="{{ $s->id }}">{{ $s->supplierProfile->company_name ?? $s->name }} ({{ $s->phone }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">ইনভয়েস / Supply ID (ঐচ্ছিক - খালি রাখলে FIFO অনুযায়ী সমন্বয় হবে)</label>
                                    <select name="supplier_payable_id" id="payInvoiceSelect" class="form-select">
                                        <option value="">-- স্বয়ংক্রিয় FIFO সমন্বয় --</option>
                                        @foreach($payables->where('due_amount', '>', 0) as $p)
                                            <option value="{{ $p->id }}" data-supplier="{{ $p->supplier_id }}" data-due="{{ $p->due_amount }}">
                                                {{ $p->supply_code }} | INV: {{ $p->invoice_no }} (বকেয়া: ৳{{ number_format($p->due_amount, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01" min="0.01" name="amount" id="payAmountInput" class="form-control" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">পেমেন্ট তারিখ <span class="text-danger">*</span></label>
                                        <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                                        <select name="payment_method" class="form-select" required>
                                            <option value="cash">Cash (নগদ)</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="bkash">bKash</option>
                                            <option value="nagad">Nagad</option>
                                            <option value="rocket">Rocket</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">Transaction ID / Ref</label>
                                        <input type="text" name="transaction_id" class="form-control" placeholder="TxID / Cheque No">
                                    </div>
                                </div>
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">Bank / Wallet নাম</label>
                                        <input type="text" name="bank_name" class="form-control" placeholder="যেমন: Islami Bank / bKash">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-bold small">Payment Receipt (ঐচ্ছিক)</label>
                                        <input type="file" name="attachment" class="form-control" accept="image/*,application/pdf">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold small">নোট</label>
                                    <textarea name="note" class="form-control" rows="2" placeholder="পেমেন্ট সংক্রান্ত মন্তব্য..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">
                                    <i class="fa fa-check-circle me-1"></i> পেমেন্ট রেকর্ড ও ইনভয়েস সমন্বয় করুন
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right: Invoice-wise Payable Status + Recent Payments -->
                <div class="col-lg-7 col-12">
                    <div class="bg-white border rounded-4 shadow-xs overflow-hidden mb-4">
                        <div class="py-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-file-text-o text-primary me-2"></i> ইনভয়েসভিত্তিক পাওনা ও বকেয়া (Paid / Partial / Unpaid)</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="ps-3">Supply / Invoice</th>
                                        <th>সাপ্লায়ার</th>
                                        <th class="text-end">Payable</th>
                                        <th class="text-end">Paid</th>
                                        <th class="text-end">Due</th>
                                        <th class="pe-3 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payables as $pay)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="fw-bold text-primary">{{ $pay->supply_code }}</div>
                                                <small class="text-muted">INV: {{ $pay->invoice_no }}</small>
                                            </td>
                                            <td>{{ $pay->supplier->supplierProfile->company_name ?? $pay->supplier->name }}</td>
                                            <td class="text-end fw-bold">৳{{ number_format($pay->payable_amount, 2) }}</td>
                                            <td class="text-end text-success fw-bold">৳{{ number_format($pay->paid_amount, 2) }}</td>
                                            <td class="text-end text-danger fw-bold">৳{{ number_format($pay->due_amount, 2) }}</td>
                                            <td class="pe-3 text-center">
                                                @if($pay->payment_status === 'paid')
                                                    <span class="badge bg-success rounded-pill">Paid</span>
                                                @elseif($pay->payment_status === 'partial')
                                                    <span class="badge bg-warning text-dark rounded-pill">Partial</span>
                                                @else
                                                    <span class="badge bg-danger rounded-pill">Unpaid</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6" class="text-center py-4 text-muted">কোনো Payable ইনভয়েস পাওয়া যায়নি।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white border rounded-4 shadow-xs overflow-hidden">
                        <div class="py-3 px-4 bg-light border-bottom">
                            <h5 class="mb-0 fw-bold"><i class="fa fa-history text-success me-2"></i> সাম্প্রতিক পেমেন্ট হিস্ট্রি</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Pay No / তারিখ</th>
                                        <th>সাপ্লায়ার</th>
                                        <th>মেথড ও TxID</th>
                                        <th class="text-end pe-3">পরিমাণ (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $pm)
                                    <tr>
                                        <td class="ps-3">
                                            <div class="fw-bold">{{ $pm->payment_no ?: ('#' . $pm->id) }}</div>
                                            <small class="text-muted">{{ $pm->payment_date }}</small>
                                        </td>
                                        <td>{{ $pm->supplier->supplierProfile->company_name ?? $pm->supplier->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ strtoupper($pm->payment_method) }}</span>
                                            @if($pm->transaction_id)<small class="text-muted d-block">TxID: {{ $pm->transaction_id }}</small>@endif
                                        </td>
                                        <td class="text-end pe-3 fw-bold text-success">৳{{ number_format($pm->amount, 2) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-4 text-muted">কোনো পেমেন্ট হিস্ট্রি নেই।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3">{{ $payments->links() }}</div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    function filterSupplierInvoices() {
        const supId = document.getElementById('paySupplierSelect').value;
        const sel = document.getElementById('payInvoiceSelect');
        Array.from(sel.options).forEach(opt => {
            if (!opt.value) return;
            opt.style.display = (!supId || opt.dataset.supplier === supId) ? '' : 'none';
        });
        sel.value = '';
    }
</script>
@endsection
