@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-plus-circle text-success me-2"></i> নতুন পণ্য সরবরাহ ও ইনভয়েস এন্ট্রি
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('supplier.supplies.index') }}">সরবরাহ ইতিহাস</a></li>
                        <li class="breadcrumb-item active">নতুন এন্ট্রি</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('supplier.supplies.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="box border-0 shadow-xs rounded-4">
                        <div class="box-header with-border bg-primary py-3 px-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h4 class="box-title text-white mb-0">
                                    <i class="fa fa-file-text-o me-2"></i> সরবরাহ ও ইনভয়েস তথ্য (Procurement Entry)
                                </h4>
                                <span class="badge bg-white text-primary px-3 py-2 rounded-pill">
                                    অ্যাডমিন অনুমোদনের পর স্টক ও গন্তব্য নির্ধারিত হবে
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('supplier.supplies.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="box-body p-4">
                                @if ($errors->any())
                                    <div class="alert alert-danger rounded-3">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <!-- Header Details -->
                                <div class="row g-3 mb-4 p-3 bg-light rounded-3 border">
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">ইনভয়েস / চালান নম্বর <span class="text-danger">*</span></label>
                                        <input type="text" name="invoice_no" class="form-control" placeholder="যেমন: INV-2026-0012" value="{{ old('invoice_no') }}" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">সরবরাহের তারিখ <span class="text-danger">*</span></label>
                                        <input type="date" name="supply_date" class="form-control" value="{{ old('supply_date', date('Y-m-d')) }}" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label fw-bold">চালান ছবি / PDF ফাইল (ঐচ্ছিক)</label>
                                        <input type="file" name="invoice_file" class="form-control" accept="image/*,application/pdf">
                                        <small class="text-muted">JPG, PNG, PDF (সর্বোচ্চ 5MB)</small>
                                    </div>
                                </div>

                                <!-- Product Items Table -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold text-dark mb-0"><i class="fa fa-cubes text-primary me-1"></i> পণ্যের তালিকা (Product Items)</h5>
                                    <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" id="addRowBtn">
                                        <i class="fa fa-plus me-1"></i> আরও পণ্য যোগ করুন
                                    </button>
                                </div>

                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered align-middle" id="itemsTable">
                                        <thead class="table-dark">
                                            <tr>
                                                <th style="width: 40px;">#</th>
                                                <th>পণ্যের নাম <span class="text-danger">*</span></th>
                                                <th>ক্যাটাগরি</th>
                                                <th style="width: 130px;">পরিমাণ (Qty) <span class="text-danger">*</span></th>
                                                <th style="width: 150px;">ইউনিট (Unit) <span class="text-danger">*</span></th>
                                                <th style="width: 140px;">রেট (Rate ৳) <span class="text-danger">*</span></th>
                                                <th style="width: 150px;">মোট মূল্য (৳)</th>
                                                <th style="width: 60px;" class="text-center">মুছুন</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemsBody">
                                            <tr class="item-row">
                                                <td class="row-num fw-bold text-center">1</td>
                                                <td>
                                                    <input type="text" name="items[0][product_name]" class="form-control" placeholder="যেমন: চাল, চিনি, তেল" required>
                                                </td>
                                                <td>
                                                    <select name="items[0][category]" class="form-select">
                                                        <option value="">-- ক্যাটাগরি --</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->name }}">{{ $cat->bn_name ?: $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0.01" name="items[0][quantity]" class="form-control qty-input" placeholder="0" required oninput="recalculateTotals()">
                                                </td>
                                                <td>
                                                    <select name="items[0][unit]" class="form-select" required>
                                                        @foreach($units as $unit)
                                                            <option value="{{ $unit->name }}">{{ $unit->name }} ({{ $unit->bn_name }})</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="number" step="0.01" min="0.01" name="items[0][rate]" class="form-control rate-input" placeholder="0.00" required oninput="recalculateTotals()">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control bg-light fw-bold text-success row-total" readonly value="৳0.00">
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <td colspan="3" class="text-end fw-bold fs-15">সর্বমোট (Grand Total):</td>
                                                <td class="fw-bold text-primary fs-15" id="grandQty">0</td>
                                                <td colspan="2"></td>
                                                <td class="fw-bold text-success fs-16" id="grandTotal">৳0.00</td>
                                                <td></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>

                                <div class="form-group mb-3">
                                    <label class="form-label fw-bold">অতিরিক্ত নোট / বিবরণ (ঐচ্ছিক)</label>
                                    <textarea name="note" class="form-control" rows="3" placeholder="পণ্যের ব্র্যান্ড, প্যাকেজিং বা ডেলিভারি সংক্রান্ত কোনো বিশেষ তথ্য থাকলে লিখুন">{{ old('note') }}</textarea>
                                </div>
                            </div>

                            <div class="box-footer bg-light px-4 py-3 d-flex justify-content-end gap-2 flex-wrap">
                                <button type="submit" name="action_type" value="draft" class="btn btn-outline-secondary rounded-pill px-4">
                                    <i class="fa fa-save me-1"></i> ড্রাফট সেভ করুন
                                </button>
                                <button type="submit" name="action_type" value="submit" class="btn btn-success btn-lg rounded-pill px-4 shadow-xs">
                                    <i class="fa fa-paper-plane me-1"></i> সরবরাহ জমা দিন (Submit Supply)
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    let rowIndex = 1;
    const unitsOptions = `@foreach($units as $unit)<option value="{{ $unit->name }}">{{ $unit->name }} ({{ $unit->bn_name }})</option>@endforeach`;
    const categoriesOptions = `<option value="">-- ক্যাটাগরি --</option>@foreach($categories as $cat)<option value="{{ $cat->name }}">{{ $cat->bn_name ?: $cat->name }}</option>@endforeach`;

    document.getElementById('addRowBtn').addEventListener('click', function () {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="row-num fw-bold text-center"></td>
            <td><input type="text" name="items[${rowIndex}][product_name]" class="form-control" placeholder="পণ্যের নাম" required></td>
            <td><select name="items[${rowIndex}][category]" class="form-select">${categoriesOptions}</select></td>
            <td><input type="number" step="0.01" min="0.01" name="items[${rowIndex}][quantity]" class="form-control qty-input" placeholder="0" required oninput="recalculateTotals()"></td>
            <td><select name="items[${rowIndex}][unit]" class="form-select" required>${unitsOptions}</select></td>
            <td><input type="number" step="0.01" min="0.01" name="items[${rowIndex}][rate]" class="form-control rate-input" placeholder="0.00" required oninput="recalculateTotals()"></td>
            <td><input type="text" class="form-control bg-light fw-bold text-success row-total" readonly value="৳0.00"></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row-btn"><i class="fa fa-trash"></i></button></td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        refreshRowNumbers();
    });

    document.getElementById('itemsBody').addEventListener('click', function (e) {
        const btn = e.target.closest('.remove-row-btn');
        if (btn && !btn.disabled) {
            btn.closest('tr').remove();
            refreshRowNumbers();
            recalculateTotals();
        }
    });

    function refreshRowNumbers() {
        const rows = document.querySelectorAll('#itemsBody .item-row');
        rows.forEach((row, idx) => {
            row.querySelector('.row-num').textContent = idx + 1;
            row.querySelector('.remove-row-btn').disabled = rows.length === 1;
        });
    }

    function recalculateTotals() {
        let totalQty = 0;
        let grandTotal = 0;
        document.querySelectorAll('#itemsBody .item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const rate = parseFloat(row.querySelector('.rate-input').value) || 0;
            const rowSum = qty * rate;
            totalQty += qty;
            grandTotal += rowSum;
            row.querySelector('.row-total').value = '৳' + rowSum.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        });
        document.getElementById('grandQty').textContent = totalQty.toLocaleString('en-US', { maximumFractionDigits: 2 });
        document.getElementById('grandTotal').textContent = '৳' + grandTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }
</script>
@endsection
