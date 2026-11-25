@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-success me-2"></i> নতুন আর্থিক বিল এন্ট্রি (New Financial Bill)
                    </h3>
                    <p class="text-muted mb-0">ক্রয় বিল, ভেন্ডর ইনভয়েস, ইউটিলিটি ও অফিস ব্যয়ের বিস্তারিত বিল প্রস্তুত করুন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bills_vouchers.bills.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> বিল তালিকায় ফিরে যান
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.bills_vouchers.bills.store') }}" enctype="multipart/form-data" id="billForm">
                @csrf

                <!-- 1. Bill Metadata -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-info-circle text-success me-2"></i> সাধারণ তথ্য ও সরবরাহকারী বিবরণ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">বিলের প্রকার (Bill Type) <span class="text-danger">*</span></label>
                                <select name="bill_type" class="form-select fw-bold text-success" required>
                                    <option value="purchase">ক্রয় বিল (Purchase Bill)</option>
                                    <option value="sales">বিক্রয় বিল (Sales Bill)</option>
                                    <option value="service">সার্ভিস বিল (Service Bill)</option>
                                    <option value="utility">ইউটিলিটি বিল (Utility Bill)</option>
                                    <option value="electricity">বিদ্যুৎ বিল (Electricity)</option>
                                    <option value="gas">গ্যাস বিল (Gas)</option>
                                    <option value="water">পানি বিল (Water)</option>
                                    <option value="internet">ইন্টারনেট বিল (Internet)</option>
                                    <option value="rent">অফিস ভাড়া বিল (Rent)</option>
                                    <option value="transport">যাতায়াত ও গাড়ি বিল</option>
                                    <option value="maintenance">মেরামত ও রক্ষণাবেক্ষণ</option>
                                    <option value="salary">বেতন বিল (Salary)</option>
                                    <option value="other">অন্যান্য বিল (Other)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">বিল নম্বর (Bill No) <span class="text-danger">*</span></label>
                                <input type="text" name="bill_no" class="form-control fw-bold bg-light" value="{{ old('bill_no', $suggestedBillNo) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">বিল তারিখ (Bill Date) <span class="text-danger">*</span></label>
                                <input type="date" name="bill_date" class="form-control" value="{{ old('bill_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">পরিশোধের শেষ তারিখ (Due Date)</label>
                                <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d', strtotime('+15 days'))) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">পার্টির ধরণ (Party Type)</label>
                                <select name="party_type" class="form-select">
                                    <option value="supplier">সরবরাহকারী (Supplier / Vendor)</option>
                                    <option value="customer">গ্রাহক (Customer / Buyer)</option>
                                    <option value="utility_authority">ইউটিলিটি কর্তৃপক্ষ (DESCO/WASA/etc.)</option>
                                    <option value="employee">কর্মকর্তা / কর্মচারী (Employee)</option>
                                    <option value="landlord">বাড়ির মালিক (Landlord)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">পার্টি / সরবরাহকারীর নাম <span class="text-danger">*</span></label>
                                <input type="text" name="party_name" class="form-control fw-bold" placeholder="কোম্পানি বা ব্যক্তির নাম" value="{{ old('party_name') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">মোবাইল নম্বর</label>
                                <input type="text" name="party_phone" class="form-control" placeholder="017xxxxxxxx" value="{{ old('party_phone') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">রেফারেন্স বা চালান নং</label>
                                <input type="text" name="reference_no" class="form-control" placeholder="চালান / ইনভয়েস নং" value="{{ old('reference_no') }}">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label fw-bold">ঠিকানা (Party Address)</label>
                                <input type="text" name="party_address" class="form-control" placeholder="ঠিকানা লিখুন" value="{{ old('party_address') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">বিলের স্ক্যান কপি বা ফাইল</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Line Items Table -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-list text-success me-2"></i> বিল আইটেম বা সেবাসমূহ (Bill Items)
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-success" id="addItemBtn">
                            <i class="fa fa-plus me-1"></i> আরেকটি আইটেম যোগ করুন
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="itemsTable">
                                <thead class="bg-light text-center">
                                    <tr>
                                        <th style="width: 30%;">আইটেম বা সেবার নাম <span class="text-danger">*</span></th>
                                        <th style="width: 12%;">পরিমাণ <span class="text-danger">*</span></th>
                                        <th style="width: 10%;">একক</th>
                                        <th style="width: 15%;">একক মূল্য (৳) <span class="text-danger">*</span></th>
                                        <th style="width: 10%;">ভ্যাট / ট্যাক্স (%)</th>
                                        <th style="width: 18%;">মোট (৳)</th>
                                        <th style="width: 5%;">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr class="item-row">
                                        <td>
                                            <input type="text" name="items[0][item_name]" class="form-control fw-bold" placeholder="পণ্যের বা সেবার নাম" required>
                                            <input type="text" name="items[0][description]" class="form-control form-control-sm mt-1 text-muted" placeholder="বিবরণ / স্পেসিফিকেশন">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.01" name="items[0][quantity]" class="form-control qty-input text-center fw-bold" value="1" required>
                                        </td>
                                        <td>
                                            <select name="items[0][unit]" class="form-select form-select-sm">
                                                <option value="Pcs">Pcs</option>
                                                <option value="Kg">Kg</option>
                                                <option value="Month">মাস (Month)</option>
                                                <option value="Set">Set</option>
                                                <option value="Box">Box</option>
                                                <option value="Ltr">Ltr</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="items[0][unit_price]" class="form-control price-input text-end fw-bold" placeholder="0.00" required>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0" max="100" name="items[0][tax_percent]" class="form-control tax-input text-center" value="0">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control item-total text-end fw-bold bg-light" value="0.00" readonly>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. Financial Summary & Total Calculations -->
                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0">অতিরিক্ত নোট ও শর্তাবলী</h6>
                            </div>
                            <div class="card-body">
                                <textarea name="notes" class="form-control" rows="4" placeholder="বিলের বিশেষ বিবরণ বা কোনো নির্দেশিকা..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0 text-dark"><i class="fa fa-calculator text-success me-2"></i> বিল ক্যালকুলেশন ও সর্বমোট</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted fw-bold">সাবটোটাল (Subtotal):</span>
                                    <span class="fw-bold fs-15 text-dark" id="displaySubtotal">৳0.00</span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">বিশেষ ছাড় (Discount ৳):</span>
                                    <div style="width: 160px;">
                                        <input type="number" step="0.01" min="0" name="discount_amount" id="discountInput" class="form-control form-control-sm text-end" value="0.00">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">ট্যাক্স / ভ্যাট (Tax/VAT ৳):</span>
                                    <div style="width: 160px;">
                                        <input type="number" step="0.01" min="0" name="tax_vat_amount" id="taxVatInput" class="form-control form-control-sm text-end" value="0.00">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">অন্যান্য খরচ (Additional Charges ৳):</span>
                                    <div style="width: 160px;">
                                        <input type="number" step="0.01" min="0" name="additional_charges" id="additionalInput" class="form-control form-control-sm text-end" value="0.00">
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-16 fw-bold text-dark">সর্বমোট প্রদেয় বিল (Grand Total):</span>
                                    <span class="fs-18 fw-bold text-success" id="displayGrandTotal">৳0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <a href="{{ route('admin.bills_vouchers.bills.index') }}" class="btn btn-light">
                        <i class="fa fa-times me-1"></i> বাতিল
                    </a>
                    <button type="submit" class="btn btn-success px-5 fw-bold">
                        <i class="fa fa-check-circle me-1"></i> বিল সংরক্ষণ করুন (Save Bill)
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('itemsBody');
    const addItemBtn = document.getElementById('addItemBtn');
    const displaySubtotal = document.getElementById('displaySubtotal');
    const displayGrandTotal = document.getElementById('displayGrandTotal');
    const discountInput = document.getElementById('discountInput');
    const taxVatInput = document.getElementById('taxVatInput');
    const additionalInput = document.getElementById('additionalInput');

    function calculateBill() {
        let subtotal = 0.00;
        let totalAutoTax = 0.00;
        const rows = tableBody.querySelectorAll('tr.item-row');

        rows.forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0.00;
            const price = parseFloat(row.querySelector('.price-input').value) || 0.00;
            const taxPct = parseFloat(row.querySelector('.tax-input').value) || 0.00;

            const itemSub = qty * price;
            const itemTax = (itemSub * taxPct) / 100;
            const itemTot = itemSub + itemTax;

            row.querySelector('.item-total').value = itemTot.toFixed(2);
            subtotal += itemSub;
            totalAutoTax += itemTax;
        });

        displaySubtotal.textContent = '৳' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // Set auto tax if taxVatInput is zero or user hasn't edited manual
        if (parseFloat(taxVatInput.value) === 0 && totalAutoTax > 0) {
            taxVatInput.value = totalAutoTax.toFixed(2);
        }

        const discount = parseFloat(discountInput.value) || 0.00;
        const tax = parseFloat(taxVatInput.value) || 0.00;
        const additional = parseFloat(additionalInput.value) || 0.00;

        const grand = Math.max(0, subtotal - discount + tax + additional);
        displayGrandTotal.textContent = '৳' + grand.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        // Toggle remove buttons
        tableBody.querySelectorAll('.remove-item-btn').forEach(btn => {
            btn.disabled = rows.length <= 1;
        });
    }

    function attachListeners(row) {
        row.querySelector('.qty-input').addEventListener('input', calculateBill);
        row.querySelector('.price-input').addEventListener('input', calculateBill);
        row.querySelector('.tax-input').addEventListener('input', calculateBill);

        const removeBtn = row.querySelector('.remove-item-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (tableBody.querySelectorAll('tr.item-row').length > 1) {
                    row.remove();
                    reindexItems();
                    calculateBill();
                }
            });
        }
    }

    function reindexItems() {
        const rows = tableBody.querySelectorAll('tr.item-row');
        rows.forEach((row, index) => {
            row.querySelector('input[name*="[item_name]"]').name = `items[${index}][item_name]`;
            row.querySelector('input[name*="[description]"]').name = `items[${index}][description]`;
            row.querySelector('.qty-input').name = `items[${index}][quantity]`;
            row.querySelector('select[name*="[unit]"]').name = `items[${index}][unit]`;
            row.querySelector('.price-input').name = `items[${index}][unit_price]`;
            row.querySelector('.tax-input').name = `items[${index}][tax_percent]`;
        });
    }

    addItemBtn.addEventListener('click', function () {
        const index = tableBody.querySelectorAll('tr.item-row').length;
        const newRow = document.createElement('tr');
        newRow.className = 'item-row';
        newRow.innerHTML = `
            <td>
                <input type="text" name="items[${index}][item_name]" class="form-control fw-bold" placeholder="পণ্যের বা সেবার নাম" required>
                <input type="text" name="items[${index}][description]" class="form-control form-control-sm mt-1 text-muted" placeholder="বিবরণ / স্পেসিফিকেশন">
            </td>
            <td>
                <input type="number" step="0.01" min="0.01" name="items[${index}][quantity]" class="form-control qty-input text-center fw-bold" value="1" required>
            </td>
            <td>
                <select name="items[${index}][unit]" class="form-select form-select-sm">
                    <option value="Pcs">Pcs</option>
                    <option value="Kg">Kg</option>
                    <option value="Month">মাস (Month)</option>
                    <option value="Set">Set</option>
                    <option value="Box">Box</option>
                    <option value="Ltr">Ltr</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[${index}][unit_price]" class="form-control price-input text-end fw-bold" placeholder="0.00" required>
            </td>
            <td>
                <input type="number" step="0.01" min="0" max="100" name="items[${index}][tax_percent]" class="form-control tax-input text-center" value="0">
            </td>
            <td>
                <input type="text" class="form-control item-total text-end fw-bold bg-light" value="0.00" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        attachListeners(newRow);
        calculateBill();
    });

    discountInput.addEventListener('input', calculateBill);
    taxVatInput.addEventListener('input', calculateBill);
    additionalInput.addEventListener('input', calculateBill);

    tableBody.querySelectorAll('tr.item-row').forEach(row => attachListeners(row));
    calculateBill();
});
</script>
@endsection
