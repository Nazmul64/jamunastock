@extends('layouts.agent.app')

@section('title', 'নতুন Customer Product Distribution - ' . $agentPoint->name)

@section('content')
<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
    <div>
        <h4 class="fw-bold text-white mb-1"><i class="fa fa-cart-plus text-success me-2"></i>নতুন Customer Product Distribution Entry</h4>
        <p class="text-muted small mb-0">{{ $agentPoint->name }} • একই এন্ট্রিতে একাধিক পণ্য গ্রাহককে বিতরণ করুন (স্টকের চেয়ে বেশি দেওয়া যাবে না)</p>
    </div>
    <a href="{{ route('agent.distributions.index') }}" class="btn btn-sm btn-outline-light">ফিরে যান</a>
</div>

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
@endif
@if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="dark-card">
    <form action="{{ route('agent.distributions.store') }}" method="POST">
        @csrf
        <div class="p-4">
            <!-- Customer Details -->
            <h6 class="text-info fw-bold mb-3"><i class="fa fa-user me-1"></i> গ্রাহকের তথ্য (Customer Information)</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-12">
                    <label class="form-label text-white small fw-bold">নিবন্ধিত গ্রাহক (ঐচ্ছিক)</label>
                    <select name="customer_id" id="registeredCustomerSelect" class="form-select bg-dark text-white border-secondary" onchange="fillCustomerInfo()">
                        <option value="">-- অথবা নিচে হাতে লিখুন --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}" data-name="{{ $c->name }}" data-phone="{{ $c->phone }}" data-code="{{ $c->customer_id ?? $c->username }}">
                                {{ $c->name }} ({{ $c->phone ?? $c->username }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-12">
                    <label class="form-label text-white small fw-bold">Customer Name <span class="text-danger">*</span></label>
                    <input type="text" name="customer_name" id="customerNameInput" class="form-control bg-dark text-white border-secondary" placeholder="যেমন: Rahim Uddin" value="{{ old('customer_name') }}" required>
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label text-white small fw-bold">Customer ID</label>
                    <input type="text" name="customer_code" id="customerCodeInput" class="form-control bg-dark text-white border-secondary" placeholder="CUS-00125" value="{{ old('customer_code') }}">
                </div>
                <div class="col-md-2 col-6">
                    <label class="form-label text-white small fw-bold">Mobile Number</label>
                    <input type="text" name="customer_phone" id="customerPhoneInput" class="form-control bg-dark text-white border-secondary" placeholder="017..." value="{{ old('customer_phone') }}">
                </div>
                <div class="col-md-2 col-12">
                    <label class="form-label text-white small fw-bold">Distribution Date <span class="text-danger">*</span></label>
                    <input type="date" name="distribution_date" class="form-control bg-dark text-white border-secondary" value="{{ old('distribution_date', date('Y-m-d')) }}" required>
                </div>
            </div>

            <!-- Distribution Type & Payment -->
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-12">
                    <label class="form-label text-white small fw-bold">Distribution Type <span class="text-danger">*</span></label>
                    <select name="distribution_type" id="distTypeSelect" class="form-select bg-dark text-white border-secondary" required onchange="togglePaymentFields()">
                        <option value="free">Free Distribution (বিনামূল্যে বিতরণ)</option>
                        <option value="paid">Paid Distribution (নগদ বিক্রয়)</option>
                        <option value="subsidized">Subsidized Distribution (ভর্তুকি মূল্য)</option>
                        <option value="credit">Credit Distribution (বাকিতে বিতরণ)</option>
                    </select>
                </div>
                <div class="col-md-4 col-6 payment-field d-none">
                    <label class="form-label text-white small fw-bold">Payment Status</label>
                    <select name="payment_status" id="paymentStatusSelect" class="form-select bg-dark text-white border-secondary">
                        <option value="not_applicable">Not Applicable (Free)</option>
                        <option value="paid">Paid</option>
                        <option value="partial">Partial</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="col-md-4 col-6 payment-field d-none">
                    <label class="form-label text-white small fw-bold">Paid Amount (৳)</label>
                    <input type="number" step="0.01" min="0" name="paid_amount" class="form-control bg-dark text-white border-secondary" value="0">
                </div>
            </div>

            <!-- Multi-Product Items -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="text-warning fw-bold mb-0"><i class="fa fa-cubes me-1"></i> পণ্যসমূহ নির্বাচন করুন (Available Stock থেকে)</h6>
                <button type="button" class="btn btn-sm btn-primary" id="addDistRowBtn"><i class="fa fa-plus me-1"></i> আরও পণ্য যোগ করুন</button>
            </div>

            @if($stocks->isEmpty())
                <div class="alert alert-warning">আপনার পয়েন্টে বর্তমানে কোনো বিক্রয়যোগ্য/বিতরণযোগ্য স্টক নেই। আগে সাপ্লাই রিসিভ করুন।</div>
            @endif

            <div class="table-responsive mb-4">
                <table class="table dark-table" id="distItemsTable">
                    <thead>
                        <tr>
                            <th>পণ্য (Available Stock) <span class="text-danger">*</span></th>
                            <th style="width: 120px;">Available</th>
                            <th style="width: 130px;">Quantity <span class="text-danger">*</span></th>
                            <th style="width: 130px;">Unit <span class="text-danger">*</span></th>
                            <th style="width: 120px;">Rate (৳)</th>
                            <th style="width: 130px;">Total (৳)</th>
                            <th style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody id="distItemsBody">
                        <tr class="dist-row">
                            <td>
                                <select name="items[0][product_name]" class="form-select form-select-sm bg-dark text-white border-secondary product-select" required onchange="onProductChange(this)">
                                    <option value="">-- পণ্য সিলেক্ট করুন --</option>
                                    @foreach($stocks as $st)
                                        @php $avail = max(0, $st->allocated_quantity - $st->sold_quantity); @endphp
                                        <option value="{{ $st->stock_name }}" data-avail="{{ $avail }}" data-rate="{{ $st->unit_price }}">
                                            {{ $st->stock_name }} (মজুদ: {{ $avail }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><span class="badge bg-info avail-badge">0</span></td>
                            <td><input type="number" step="0.01" min="0.01" name="items[0][quantity]" class="form-control form-control-sm bg-dark text-white border-secondary qty-input" required oninput="recalcDist()"></td>
                            <td>
                                <select name="items[0][unit]" class="form-select form-select-sm bg-dark text-white border-secondary" required>
                                    @foreach($units as $u)
                                        <option value="{{ $u->name }}">{{ $u->name }} ({{ $u->bn_name }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" step="0.01" min="0" name="items[0][rate]" class="form-control form-control-sm bg-dark text-white border-secondary rate-input" value="0" oninput="recalcDist()"></td>
                            <td><input type="text" class="form-control form-control-sm bg-dark text-success fw-bold row-total border-secondary" readonly value="৳0.00"></td>
                            <td><button type="button" class="btn btn-xs btn-outline-danger remove-dist-row" disabled><i class="fa fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end fw-bold text-white">মোট:</td>
                            <td class="fw-bold text-info" id="distGrandQty">0</td>
                            <td colspan="2"></td>
                            <td class="fw-bold text-success fs-6" id="distGrandTotal">৳0.00</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-3">
                <label class="form-label text-white small fw-bold">মন্তব্য / নোট (ঐচ্ছিক)</label>
                <textarea name="note" class="form-control bg-dark text-white border-secondary" rows="2" placeholder="বিতরণ সংক্রান্ত কোনো মন্তব্য থাকলে লিখুন...">{{ old('note') }}</textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success btn-lg px-4 fw-bold" {{ $stocks->isEmpty() ? 'disabled' : '' }}>
                    <i class="fa fa-check-circle me-1"></i> বিতরণ সম্পন্ন ও স্টক আপডেট করুন
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    let distIdx = 1;
    const stockOpts = `<option value="">-- পণ্য সিলেক্ট করুন --</option>@foreach($stocks as $st)@php $avail = max(0, $st->allocated_quantity - $st->sold_quantity); @endphp<option value="{{ $st->stock_name }}" data-avail="{{ $avail }}" data-rate="{{ $st->unit_price }}">{{ $st->stock_name }} (মজুদ: {{ $avail }})</option>@endforeach`;
    const unitOpts = `@foreach($units as $u)<option value="{{ $u->name }}">{{ $u->name }} ({{ $u->bn_name }})</option>@endforeach`;

    function fillCustomerInfo() {
        const sel = document.getElementById('registeredCustomerSelect');
        const opt = sel.options[sel.selectedIndex];
        if (opt.value) {
            document.getElementById('customerNameInput').value = opt.dataset.name || '';
            document.getElementById('customerPhoneInput').value = opt.dataset.phone || '';
            document.getElementById('customerCodeInput').value = opt.dataset.code || '';
        }
    }

    function togglePaymentFields() {
        const type = document.getElementById('distTypeSelect').value;
        const fields = document.querySelectorAll('.payment-field');
        const paySel = document.getElementById('paymentStatusSelect');
        if (type === 'free') {
            fields.forEach(f => f.classList.add('d-none'));
            paySel.value = 'not_applicable';
        } else {
            fields.forEach(f => f.classList.remove('d-none'));
            if (paySel.value === 'not_applicable') paySel.value = type === 'paid' ? 'paid' : 'unpaid';
        }
        recalcDist();
    }

    function onProductChange(select) {
        const row = select.closest('tr');
        const opt = select.options[select.selectedIndex];
        const avail = parseFloat(opt.dataset.avail || 0);
        const rate = parseFloat(opt.dataset.rate || 0);
        row.querySelector('.avail-badge').textContent = avail;
        row.querySelector('.qty-input').max = avail;
        if (document.getElementById('distTypeSelect').value !== 'free') {
            row.querySelector('.rate-input').value = rate;
        }
        recalcDist();
    }

    document.getElementById('addDistRowBtn').addEventListener('click', function () {
        const tbody = document.getElementById('distItemsBody');
        const tr = document.createElement('tr');
        tr.className = 'dist-row';
        tr.innerHTML = `
            <td><select name="items[${distIdx}][product_name]" class="form-select form-select-sm bg-dark text-white border-secondary product-select" required onchange="onProductChange(this)">${stockOpts}</select></td>
            <td><span class="badge bg-info avail-badge">0</span></td>
            <td><input type="number" step="0.01" min="0.01" name="items[${distIdx}][quantity]" class="form-control form-control-sm bg-dark text-white border-secondary qty-input" required oninput="recalcDist()"></td>
            <td><select name="items[${distIdx}][unit]" class="form-select form-select-sm bg-dark text-white border-secondary" required>${unitOpts}</select></td>
            <td><input type="number" step="0.01" min="0" name="items[${distIdx}][rate]" class="form-control form-control-sm bg-dark text-white border-secondary rate-input" value="0" oninput="recalcDist()"></td>
            <td><input type="text" class="form-control form-control-sm bg-dark text-success fw-bold row-total border-secondary" readonly value="৳0.00"></td>
            <td><button type="button" class="btn btn-xs btn-outline-danger remove-dist-row"><i class="fa fa-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
        distIdx++;
        updateRemoveBtns();
    });

    document.getElementById('distItemsBody').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-dist-row');
        if (btn && !btn.disabled) {
            btn.closest('tr').remove();
            updateRemoveBtns();
            recalcDist();
        }
    });

    function updateRemoveBtns() {
        const rows = document.querySelectorAll('#distItemsBody .dist-row');
        rows.forEach(r => r.querySelector('.remove-dist-row').disabled = rows.length === 1);
    }

    function recalcDist() {
        const isFree = document.getElementById('distTypeSelect').value === 'free';
        let gQty = 0, gTot = 0;
        document.querySelectorAll('#distItemsBody .dist-row').forEach(row => {
            const q = parseFloat(row.querySelector('.qty-input').value) || 0;
            const r = isFree ? 0 : (parseFloat(row.querySelector('.rate-input').value) || 0);
            const sum = q * r;
            gQty += q;
            gTot += sum;
            row.querySelector('.row-total').value = '৳' + sum.toFixed(2);
        });
        document.getElementById('distGrandQty').textContent = gQty.toFixed(2);
        document.getElementById('distGrandTotal').textContent = '৳' + gTot.toFixed(2);
    }
</script>
@endsection
