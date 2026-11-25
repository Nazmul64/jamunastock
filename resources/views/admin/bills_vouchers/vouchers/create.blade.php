@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-pencil-square-o text-primary me-2"></i> নতুন ভাউচার এন্ট্রি (Create Accounting Voucher)
                    </h3>
                    <p class="text-muted mb-0">ডাবল-এন্ট্রি একাউন্টিং নীতি অনুযায়ী ডেবিট ও ক্রেডিট হিসাবের খাত সমন্বয় করে ভাউচার তৈরি করুন</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> ভাউচার তালিকায় ফিরে যান
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

            @if($linkedBill)
                <div class="alert alert-info border-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fa fa-info-circle fa-lg me-2"></i>
                        <strong>বিল পেমেন্ট লিংক:</strong> আপনি বিল নং <strong>{{ $linkedBill->bill_no }}</strong> ({{ $linkedBill->party_name }}) এর বকেয়া পরিশোধ করছেন।
                        মোট বকেয়া: <span class="badge bg-danger fs-14">৳{{ number_format($linkedBill->outstanding_amount, 2) }}</span>
                    </div>
                    <span class="badge bg-dark">{{ $linkedBill->billTypeBangla() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.bills_vouchers.vouchers.store') }}" enctype="multipart/form-data" id="voucherForm">
                @csrf

                @if($linkedBill)
                    <input type="hidden" name="bill_id" value="{{ $linkedBill->id }}">
                    <input type="hidden" name="allocated_amount" id="allocatedAmountInput" value="{{ $linkedBill->outstanding_amount }}">
                @endif

                <!-- 1. Header Information -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-info-circle text-primary me-2"></i> সাধারণ তথ্য ও ভাউচার বিবরণ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">ভাউচার প্রকার (Voucher Type) <span class="text-danger">*</span></label>
                                <select name="voucher_type" id="voucherTypeSelect" class="form-select fw-bold text-primary" required>
                                    <option value="payment" {{ $selectedType === 'payment' ? 'selected' : '' }}>পেমেন্ট ভাউচার (Payment Voucher)</option>
                                    <option value="cash_payment" {{ $selectedType === 'cash_payment' ? 'selected' : '' }}>ক্যাশ পেমেন্ট ভাউচার (CPV)</option>
                                    <option value="bank_payment" {{ $selectedType === 'bank_payment' ? 'selected' : '' }}>ব্যাংক পেমেন্ট ভাউচার (BPV)</option>
                                    <option value="receipt" {{ $selectedType === 'receipt' ? 'selected' : '' }}>রিসিপ্ট ভাউচার (Receipt Voucher)</option>
                                    <option value="cash_receipt" {{ $selectedType === 'cash_receipt' ? 'selected' : '' }}>ক্যাশ রিসিপ্ট ভাউচার (CRV)</option>
                                    <option value="bank_receipt" {{ $selectedType === 'bank_receipt' ? 'selected' : '' }}>ব্যাংক রিসিপ্ট ভাউচার (BRV)</option>
                                    <option value="journal" {{ $selectedType === 'journal' ? 'selected' : '' }}>জার্নাল ভাউচার (Journal Voucher)</option>
                                    <option value="contra" {{ $selectedType === 'contra' ? 'selected' : '' }}>কন্ট্রা ভাউচার (Contra: Cash & Bank)</option>
                                    <option value="expense" {{ $selectedType === 'expense' ? 'selected' : '' }}>খরচ ভাউচার (Expense)</option>
                                    <option value="income" {{ $selectedType === 'income' ? 'selected' : '' }}>আয় ভাউচার (Income)</option>
                                    <option value="debit_note" {{ $selectedType === 'debit_note' ? 'selected' : '' }}>ডেবিট নোট (Debit Note)</option>
                                    <option value="credit_note" {{ $selectedType === 'credit_note' ? 'selected' : '' }}>ক্রেডিট নোট (Credit Note)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">ভাউচার নম্বর (Voucher No) <span class="text-danger">*</span></label>
                                <input type="text" name="voucher_no" class="form-control fw-bold bg-light" value="{{ old('voucher_no', $suggestedVoucherNo) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">ভাউচার তারিখ (Date) <span class="text-danger">*</span></label>
                                <input type="date" name="voucher_date" class="form-control" value="{{ old('voucher_date', date('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">অর্থবছর (Financial Year)</label>
                                <input type="text" name="financial_year" class="form-control" value="{{ old('financial_year', date('Y') . '-' . (date('Y') + 1)) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">পেমেন্ট মেথড (Payment Mode) <span class="text-danger">*</span></label>
                                <select name="payment_mode" class="form-select" required>
                                    <option value="cash">নগদ টাকা (Cash)</option>
                                    <option value="bank">ব্যাংক ট্রান্সফার (Bank Transfer)</option>
                                    <option value="cheque">চেক (Cheque)</option>
                                    <option value="mobile_banking">মোবাইল ব্যাংকিং (bKash/Nagad/Rocket)</option>
                                    <option value="online">অনলাইন পেমেন্ট গেটওয়ে</option>
                                    <option value="adjustment">সমন্বয় / এডজাস্টমেন্ট (Adjustment)</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold" id="payeeLabel">প্রাপক / প্রদানকারী (Pay To / Received From)</label>
                                <input type="text" name="payee_payer_name" class="form-control" placeholder="নাম লিখুন" value="{{ old('payee_payer_name', $linkedBill?->party_name) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">রেফারেন্স নম্বর (Reference / Bill No)</label>
                                <input type="text" name="reference_no" class="form-control" placeholder="যেমন: Bill#123 বা চালান নং" value="{{ old('reference_no', $linkedBill?->bill_no) }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">ব্যাংক হিসাবের নাম (Bank A/C Name)</label>
                                <input type="text" name="bank_account_name" class="form-control" placeholder="যেমন: ডাচ-বাংলা ব্যাংক, A/C #..." value="{{ old('bank_account_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">চেক নম্বর (Cheque No)</label>
                                <input type="text" name="cheque_no" class="form-control" placeholder="চেক নং (যদি থাকে)" value="{{ old('cheque_no') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">চেক তারিখ (Cheque Date)</label>
                                <input type="date" name="cheque_date" class="form-control" value="{{ old('cheque_date') }}">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">ভাউচার সংযুক্তি (Attachment File / Scan Copy)</label>
                                <input type="file" name="attachment" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Double-Entry Accounting Lines Table -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                        <h5 class="card-title fw-bold mb-0 text-dark">
                            <i class="fa fa-exchange text-success me-2"></i> ডাবল এন্ট্রি একাউন্টিং লাইন (Debit & Credit Entries)
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-success" id="addRowBtn">
                            <i class="fa fa-plus me-1"></i> আরেকটি লাইন যোগ করুন
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0" id="entriesTable">
                                <thead class="bg-light">
                                    <tr class="text-center">
                                        <th style="width: 35%;">হিসাবের খাত (Account Head) <span class="text-danger">*</span></th>
                                        <th style="width: 15%;">ধরণ (Entry Type) <span class="text-danger">*</span></th>
                                        <th style="width: 18%;">পরিমাণ (Amount ৳) <span class="text-danger">*</span></th>
                                        <th style="width: 26%;">বিবরণ ও বিবরণী (Line Narration)</th>
                                        <th style="width: 6%;">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody id="entriesBody">
                                    <!-- Row 1: Debit Line -->
                                    <tr class="entry-row">
                                        <td>
                                            <select name="entries[0][account_id]" class="form-select account-select" required>
                                                <option value="">-- হিসাবের খাত বেছে নিন --</option>
                                                @foreach($accounts as $acc)
                                                    <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" data-code="{{ $acc->code }}">
                                                        [{{ $acc->code }}] {{ $acc->name }} ({{ ucfirst($acc->type) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="entries[0][entry_type]" class="form-select entry-type-select fw-bold text-primary" required>
                                                <option value="debit" selected>ডেবিট (Debit Dr.)</option>
                                                <option value="credit">ক্রেডিট (Credit Cr.)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.01" name="entries[0][amount]" class="form-control amount-input text-end fw-bold" placeholder="0.00" value="{{ $linkedBill ? $linkedBill->outstanding_amount : '' }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="entries[0][line_narration]" class="form-control" placeholder="এই লাইনের বিস্তারিত বিবরণ" value="{{ $linkedBill ? 'Payment for Bill #' . $linkedBill->bill_no : '' }}">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Row 2: Credit Line -->
                                    <tr class="entry-row">
                                        <td>
                                            <select name="entries[1][account_id]" class="form-select account-select" required>
                                                <option value="">-- হিসাবের খাত বেছে নিন --</option>
                                                @foreach($accounts as $acc)
                                                    <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" data-code="{{ $acc->code }}" {{ $acc->code == '1010' ? 'selected' : '' }}>
                                                        [{{ $acc->code }}] {{ $acc->name }} ({{ ucfirst($acc->type) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select name="entries[1][entry_type]" class="form-select entry-type-select fw-bold text-success" required>
                                                <option value="debit">ডেবিট (Debit Dr.)</option>
                                                <option value="credit" selected>ক্রেডিট (Credit Cr.)</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" min="0.01" name="entries[1][amount]" class="form-control amount-input text-end fw-bold" placeholder="0.00" value="{{ $linkedBill ? $linkedBill->outstanding_amount : '' }}" required>
                                        </td>
                                        <td>
                                            <input type="text" name="entries[1][line_narration]" class="form-control" placeholder="এই লাইনের বিস্তারিত বিবরণ">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn" disabled>
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="bg-light">
                                    <tr>
                                        <th colspan="2" class="text-end fw-bold">মোট হিসাব (Totals):</th>
                                        <th class="p-2">
                                            <div class="d-flex justify-content-between text-primary small">
                                                <span>মোট ডেবিট:</span>
                                                <strong id="totalDebitText">৳0.00</strong>
                                            </div>
                                            <div class="d-flex justify-content-between text-success small mt-1">
                                                <span>মোট ক্রেডিট:</span>
                                                <strong id="totalCreditText">৳0.00</strong>
                                            </div>
                                        </th>
                                        <th colspan="2">
                                            <div id="balanceStatusBox" class="alert alert-warning py-1 px-2 mb-0 small text-center">
                                                <i class="fa fa-exclamation-triangle me-1"></i> <span id="balanceStatusText">পার্থক্য: ৳0.00</span>
                                            </div>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- 3. General Narration & Submit Actions -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-bold">সার্বিক বিবরণ / সাধারণ নোট (General Narration)</label>
                                <textarea name="general_narration" class="form-control" rows="2" placeholder="ভাউচারের উদ্দেশ্য বা বিস্তারিত বিবরণ লিখুন...">{{ old('general_narration', $linkedBill ? 'Settlement for bill ' . $linkedBill->bill_no : '') }}</textarea>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <a href="{{ route('admin.bills_vouchers.vouchers.index') }}" class="btn btn-light">
                                <i class="fa fa-times me-1"></i> বাতিল
                            </a>
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="save_draft" class="btn btn-outline-primary" id="saveDraftBtn">
                                    <i class="fa fa-save me-1"></i> খসড়া হিসেবে সংরক্ষণ করুন
                                </button>
                                <button type="submit" name="action" value="save_and_post" class="btn btn-success" id="savePostBtn">
                                    <i class="fa fa-check-circle me-1"></i> সেভ ও লেজারে পোস্ট করুন (Save & Post)
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.getElementById('entriesBody');
    const addRowBtn = document.getElementById('addRowBtn');
    const totalDebitText = document.getElementById('totalDebitText');
    const totalCreditText = document.getElementById('totalCreditText');
    const balanceStatusBox = document.getElementById('balanceStatusBox');
    const balanceStatusText = document.getElementById('balanceStatusText');
    const savePostBtn = document.getElementById('savePostBtn');

    // Account Options Template
    const accountOptions = `
        <option value="">-- হিসাবের খাত বেছে নিন --</option>
        @foreach($accounts as $acc)
            <option value="{{ $acc->id }}" data-type="{{ $acc->type }}" data-code="{{ $acc->code }}">
                [{{ $acc->code }}] {{ $acc->name }} ({{ ucfirst($acc->type) }})
            </option>
        @endforeach
    `;

    function updateTotals() {
        let totalDebit = 0.00;
        let totalCredit = 0.00;
        const rows = tableBody.querySelectorAll('tr.entry-row');

        rows.forEach(row => {
            const type = row.querySelector('.entry-type-select').value;
            const amt = parseFloat(row.querySelector('.amount-input').value) || 0.00;
            if (type === 'debit') {
                totalDebit += amt;
            } else if (type === 'credit') {
                totalCredit += amt;
            }
        });

        totalDebitText.textContent = '৳' + totalDebit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        totalCreditText.textContent = '৳' + totalCredit.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const diff = Math.abs(totalDebit - totalCredit);
        if (diff <= 0.01 && (totalDebit > 0 || totalCredit > 0)) {
            balanceStatusBox.className = 'alert alert-success py-1 px-2 mb-0 small text-center';
            balanceStatusBox.innerHTML = '<i class="fa fa-check-circle me-1"></i> <strong>পারফেক্ট! ডেবিট ও ক্রেডিট সমান (Balanced)</strong>';
            if (savePostBtn) savePostBtn.disabled = false;
        } else {
            balanceStatusBox.className = 'alert alert-danger py-1 px-2 mb-0 small text-center';
            balanceStatusBox.innerHTML = '<i class="fa fa-exclamation-circle me-1"></i> <strong>অসামঞ্জস্য! পার্থক্য: ৳' + diff.toFixed(2) + '</strong>';
        }

        // Enable/disable remove button if rows > 2
        const removeButtons = tableBody.querySelectorAll('.remove-row-btn');
        removeButtons.forEach(btn => {
            btn.disabled = rows.length <= 2;
        });
    }

    // Attach listeners
    function attachRowListeners(row) {
        const typeSelect = row.querySelector('.entry-type-select');
        const amtInput = row.querySelector('.amount-input');
        const removeBtn = row.querySelector('.remove-row-btn');

        typeSelect.addEventListener('change', function () {
            if (this.value === 'debit') {
                this.className = 'form-select entry-type-select fw-bold text-primary';
            } else {
                this.className = 'form-select entry-type-select fw-bold text-success';
            }
            updateTotals();
        });

        amtInput.addEventListener('input', updateTotals);

        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                if (tableBody.querySelectorAll('tr.entry-row').length > 2) {
                    row.remove();
                    reindexRows();
                    updateTotals();
                }
            });
        }
    }

    function reindexRows() {
        const rows = tableBody.querySelectorAll('tr.entry-row');
        rows.forEach((row, index) => {
            row.querySelector('.account-select').name = `entries[${index}][account_id]`;
            row.querySelector('.entry-type-select').name = `entries[${index}][entry_type]`;
            row.querySelector('.amount-input').name = `entries[${index}][amount]`;
            row.querySelector('input[name*="[line_narration]"]').name = `entries[${index}][line_narration]`;
        });
    }

    addRowBtn.addEventListener('click', function () {
        const index = tableBody.querySelectorAll('tr.entry-row').length;
        const newRow = document.createElement('tr');
        newRow.className = 'entry-row';
        newRow.innerHTML = `
            <td>
                <select name="entries[${index}][account_id]" class="form-select account-select" required>
                    ${accountOptions}
                </select>
            </td>
            <td>
                <select name="entries[${index}][entry_type]" class="form-select entry-type-select fw-bold text-primary" required>
                    <option value="debit">ডেবিট (Debit Dr.)</option>
                    <option value="credit">ক্রেডিট (Credit Cr.)</option>
                </select>
            </td>
            <td>
                <input type="number" step="0.01" min="0.01" name="entries[${index}][amount]" class="form-control amount-input text-end fw-bold" placeholder="0.00" required>
            </td>
            <td>
                <input type="text" name="entries[${index}][line_narration]" class="form-control" placeholder="এই লাইনের বিস্তারিত বিবরণ">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row-btn">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;
        tableBody.appendChild(newRow);
        attachRowListeners(newRow);
        updateTotals();
    });

    // Initialize existing rows
    tableBody.querySelectorAll('tr.entry-row').forEach(row => attachRowListeners(row));
    updateTotals();
});
</script>
@endsection
