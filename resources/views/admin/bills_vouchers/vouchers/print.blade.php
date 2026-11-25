<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ভাউচার প্রিন্ট - {{ $voucher->voucher_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #111;
        }
        .print-container {
            max-width: 900px;
            margin: 25px auto;
            background: #fff;
            padding: 35px 40px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 2px solid #333;
        }
        .action-bar {
            max-width: 900px;
            margin: 20px auto 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        /* Specific Voucher Form Styling */
        .voucher-title {
            text-align: center;
            font-weight: 800;
            letter-spacing: 0.5px;
        }
        .table-voucher {
            border: 2px solid #222 !important;
            margin-bottom: 15px;
        }
        .table-voucher th, .table-voucher td {
            border: 1.5px solid #333 !important;
            padding: 7px 10px;
            vertical-align: middle;
            font-size: 14px;
        }
        .table-voucher th {
            background-color: #f1f1f1 !important;
            font-weight: 700;
        }
        .crv-header-yellow {
            background-color: #ffeb3b !important;
            padding: 8px 12px;
            font-weight: bold;
        }
        .crv-banner-gold {
            background-color: #ffd54f !important;
            padding: 8px 12px;
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            letter-spacing: 1px;
            border: 1px solid #e0b020;
        }
        .crv-tab-orange {
            background-color: #ff9800 !important;
            color: #fff;
            padding: 5px 12px;
            font-weight: bold;
            display: inline-block;
        }
        .signature-line {
            border-bottom: 1.5px solid #222;
            display: inline-block;
            min-width: 150px;
        }
        .signature-box {
            font-size: 13px;
            line-height: 1.6;
        }
        @media print {
            body {
                background-color: #fff;
                margin: 0;
                padding: 0;
            }
            .action-bar, .no-print {
                display: none !important;
            }
            .print-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                max-width: 100%;
                padding: 20px 25px;
                border: 2px solid #000;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
        }
    </style>
</head>
<body>

    <!-- Top Action Bar (Buttons) -->
    <div class="action-bar no-print">
        <div>
            <a href="{{ route('admin.bills_vouchers.vouchers.show', $voucher->id) }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> ফিরে যান
            </a>
            <span class="badge bg-primary ms-2 fs-14">{{ $voucher->voucher_no }}</span>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary px-4 fw-bold">
                <i class="fa fa-print me-1"></i> প্রিন্ট করুন (Print Slip)
            </button>
        </div>
    </div>

    <!-- Main Printable Paper Container -->
    <div class="print-container">

        @php
            $isReceipt = in_array($voucher->voucher_type, ['receipt', 'cash_receipt', 'bank_receipt', 'income']);
            $isJournal = in_array($voucher->voucher_type, ['journal', 'contra', 'debit_note', 'credit_note']);
            $companyName = $siteSetting->title ?? 'JAMUNA STOCK MARKET PLC';
        @endphp

        {{-- ========================================================================= --}}
        {{-- FORMAT 1: PAYMENT VOUCHER (Matches User Reference Image 1)               --}}
        {{-- ========================================================================= --}}
        @if(!$isReceipt && !$isJournal)
            <div class="text-center mb-2">
                <h2 class="text-decoration-underline fw-bold mb-1" style="letter-spacing: 1px;">Payment Voucher</h2>
                <h5 class="fw-bold text-uppercase mb-3">{{ $companyName }}</h5>
            </div>

            <div class="d-flex justify-content-between mb-3 fs-14">
                <div>
                    <strong>Pay To:</strong> <span class="signature-line" style="min-width: 320px;">&nbsp;{{ $voucher->payee_payer_name ?: 'N/A' }}</span>
                </div>
                <div class="text-end">
                    <div><strong>No:</strong> <span class="signature-line" style="min-width: 160px; text-align: center;">&nbsp;{{ $voucher->voucher_no }}</span></div>
                    <div class="mt-2"><strong>Date:</strong> <span class="signature-line" style="min-width: 160px; text-align: center;">&nbsp;{{ date('d-m-Y', strtotime($voucher->voucher_date)) }}</span></div>
                </div>
            </div>

            <!-- Double Entry Grid -->
            <table class="table table-voucher w-100 mb-3">
                <thead>
                    <tr class="text-center">
                        <th style="width: 20%;">Account Code</th>
                        <th style="width: 44%;">Details</th>
                        <th style="width: 18%;">Debit (৳)</th>
                        <th style="width: 18%;">Credit (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucher->entries as $entry)
                        <tr>
                            <td class="text-center font-monospace fw-bold">{{ $entry->account_code ?: '-' }}</td>
                            <td>
                                <strong>{{ $entry->account_name }}</strong>
                                @if($entry->line_narration)
                                    <div class="text-muted small mt-1">{{ $entry->line_narration }}</div>
                                @endif
                            </td>
                            <td class="text-end fw-bold">
                                {{ $entry->entry_type === 'debit' ? number_format($entry->amount, 2) : '' }}
                            </td>
                            <td class="text-end fw-bold">
                                {{ $entry->entry_type === 'credit' ? number_format($entry->amount, 2) : '' }}
                            </td>
                        </tr>
                    @endforeach

                    {{-- Empty rows filler for paper look if less than 4 entries --}}
                    @for($i = count($voucher->entries); $i < 4; $i++)
                        <tr>
                            <td style="height: 32px;"></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-center fs-14">TOTAL</td>
                        <td class="text-end fs-15">৳{{ number_format($voucher->total_debit, 2) }}</td>
                        <td class="text-end fs-15">৳{{ number_format($voucher->total_credit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Words & References -->
            <div class="mb-3 small">
                <strong>Amount in Words:</strong> <span class="text-decoration-underline">{{ $voucher->amount_in_words }}</span>
            </div>

            <div class="row g-2 mb-4 fs-14">
                <div class="col-6">
                    <strong>Bank Account:</strong> <span class="signature-line" style="min-width: 220px;">&nbsp;{{ $voucher->bank_account_name ?: 'Cash Account' }}</span>
                </div>
                <div class="col-6 text-end">
                    <strong>Cheque No:</strong> <span class="signature-line" style="min-width: 220px;">&nbsp;{{ $voucher->cheque_no ?: 'N/A' }}</span>
                </div>
            </div>

            <!-- Signature Lines (Prepared By, Approved By, Recorded By) -->
            <div class="row mt-5 pt-3 signature-box text-center">
                <div class="col-4">
                    <div class="border-top border-dark pt-1">
                        <strong>Prepared By:</strong> {{ $voucher->preparedByUser?->name ?? 'Accounts' }}<br>
                        <small>Signature & Date</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border-top border-dark pt-1">
                        <strong>Approved By:</strong> {{ $voucher->approvedByUser?->name ?? 'Management' }}<br>
                        <small>Signature & Date</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="border-top border-dark pt-1">
                        <strong>Recorded By:</strong> {{ $voucher->postedByUser?->name ?? 'Auditor' }}<br>
                        <small>Signature & Date</small>
                    </div>
                </div>
            </div>

        {{-- ========================================================================= --}}
        {{-- FORMAT 2: CASH RECEIPT VOUCHER (Matches User Reference Image 2 - CRV)     --}}
        {{-- ========================================================================= --}}
        @elseif($isReceipt)
            <div class="border border-dark mb-3">
                <div class="d-flex justify-content-between align-items-center crv-header-yellow border-bottom border-dark">
                    <span class="fs-15 text-uppercase fw-bold">{{ $companyName }}</span>
                    <div>
                        <span class="me-3"><strong>No. CRV :</strong> <span class="bg-white px-2 py-1 border border-dark fw-bold">{{ $voucher->voucher_no }}</span></span>
                        <span><strong>Date :</strong> {{ date('d-m-Y', strtotime($voucher->voucher_date)) }}</span>
                    </div>
                </div>
                <div class="crv-banner-gold">
                    CASH RECEIPT VOUCHER
                </div>
            </div>

            <div class="mb-2">
                <div class="crv-tab-orange">Received from :</div>
                <div class="border border-dark p-2 fw-bold fs-15 bg-light">
                    {{ $voucher->payee_payer_name ?: 'Valued Customer / Depositor' }}
                </div>
            </div>

            <!-- Top Table: Description & Amount -->
            <table class="table table-voucher w-100 mb-2">
                <thead>
                    <tr class="text-center">
                        <th style="width: 8%;">No.</th>
                        <th style="width: 25%;">Reference NO.</th>
                        <th style="width: 47%;">Description</th>
                        <th style="width: 20%;">Amount (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>{{ $voucher->reference_no ?: $voucher->voucher_no }}</td>
                        <td>{{ $voucher->general_narration ?: 'Cash received against transaction' }}</td>
                        <td class="text-end fw-bold">৳{{ number_format($voucher->total_credit, 2) }}</td>
                    </tr>
                    <tr>
                        <td style="height: 25px;"></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="3" class="text-center">Total</td>
                        <td class="text-end">৳{{ number_format($voucher->total_credit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="border border-dark p-2 mb-2 bg-light">
                <strong>Amount in word :</strong> <span class="fw-bold">{{ $voucher->amount_in_words }}</span>
            </div>

            <!-- Account breakdown table -->
            <table class="table table-voucher w-100 mb-3">
                <thead style="background-color: #ffd54f !important;">
                    <tr class="text-center">
                        <th style="width: 22%;">Account code</th>
                        <th style="width: 44%;">Account name</th>
                        <th style="width: 17%;">Debit (৳)</th>
                        <th style="width: 17%;">Credit (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucher->entries as $entry)
                        <tr>
                            <td class="text-center font-monospace fw-bold">{{ $entry->account_code ?: '-' }}</td>
                            <td>{{ $entry->account_name }}</td>
                            <td class="text-end fw-bold">{{ $entry->entry_type === 'debit' ? number_format($entry->amount, 2) : '' }}</td>
                            <td class="text-end fw-bold">{{ $entry->entry_type === 'credit' ? number_format($entry->amount, 2) : '' }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-center">Total</td>
                        <td class="text-end">৳{{ number_format($voucher->total_debit, 2) }}</td>
                        <td class="text-end">৳{{ number_format($voucher->total_credit, 2) }}</td>
                    </tr>
                </tfoot>
            </table>

            <!-- Signatures Grid with Department Split -->
            <div class="border border-dark">
                <div class="row g-0">
                    <div class="col-4 border-end border-dark">
                        <div class="bg-light p-1 fw-bold border-bottom border-dark text-center small">Accounting department</div>
                        <div class="p-2 d-flex justify-content-around text-center small" style="min-height: 80px; align-items: flex-end;">
                            <div>prepared by,<br>( {{ $voucher->preparedByUser?->name ?? 'Staff' }} )</div>
                            <div>checked by<br>( Finance )</div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="bg-light p-1 fw-bold border-bottom border-dark text-center small">Finance department</div>
                        <div class="p-2 d-flex justify-content-around text-center small" style="min-height: 80px; align-items: flex-end;">
                            <div>Cashier,<br>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
                            <div>checked by,<br>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
                            <div>Approved by,<br>( {{ $voucher->approvedByUser?->name ?? 'Manager' }} )</div>
                            <div>Received by,<br>( &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; )</div>
                        </div>
                    </div>
                </div>
            </div>

        {{-- ========================================================================= --}}
        {{-- FORMAT 3: JOURNAL VOUCHER (Matches User Reference Image 3)               --}}
        {{-- ========================================================================= --}}
        @else
            <div class="text-center mb-3">
                <h3 class="fw-bold mb-1">Journal Voucher</h3>
                <h5 class="fw-bold mb-2">Name of Firm : {{ $companyName }}</h5>
                <div class="d-flex justify-content-between px-3 mt-3 fs-14">
                    <div><strong>Voucher No :</strong> <span class="fw-bold">{{ $voucher->voucher_no }}</span></div>
                    <div><strong>Date :</strong> {{ date('d-m-Y', strtotime($voucher->voucher_date)) }}</div>
                </div>
            </div>

            <!-- Debit Accounts Table -->
            <h6 class="fw-bold mb-1 text-center bg-light py-1 border border-dark">Debit Accounts</h6>
            <table class="table table-voucher w-100 mb-3">
                <thead>
                    <tr class="text-center">
                        <th style="width: 8%;">S. No.</th>
                        <th style="width: 14%;">Code</th>
                        <th style="width: 32%;">Account Name</th>
                        <th style="width: 18%;">Amount (৳)</th>
                        <th style="width: 28%;">Narration (i.e. Explanation)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $debIdx = 1; @endphp
                    @foreach($voucher->entries->where('entry_type', 'debit') as $debitEntry)
                        <tr>
                            <td class="text-center">{{ $debIdx++ }}</td>
                            <td class="text-center font-monospace fw-bold">{{ $debitEntry->account_code ?: '-' }}</td>
                            <td><strong>{{ $debitEntry->account_name }}</strong></td>
                            <td class="text-end fw-bold">৳{{ number_format($debitEntry->amount, 2) }}</td>
                            <td>{{ $debitEntry->line_narration ?: $voucher->general_narration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Credit Accounts Table -->
            <h6 class="fw-bold mb-1 text-center bg-light py-1 border border-dark">Credit Accounts</h6>
            <table class="table table-voucher w-100 mb-3">
                <thead>
                    <tr class="text-center">
                        <th style="width: 8%;">S. No.</th>
                        <th style="width: 14%;">Code</th>
                        <th style="width: 32%;">Account Name</th>
                        <th style="width: 18%;">Amount (৳)</th>
                        <th style="width: 28%;">Narration (i.e. Explanation)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $crdIdx = 1; @endphp
                    @foreach($voucher->entries->where('entry_type', 'credit') as $creditEntry)
                        <tr>
                            <td class="text-center">{{ $crdIdx++ }}</td>
                            <td class="text-center font-monospace fw-bold">{{ $creditEntry->account_code ?: '-' }}</td>
                            <td><strong>{{ $creditEntry->account_name }}</strong></td>
                            <td class="text-end fw-bold">৳{{ number_format($creditEntry->amount, 2) }}</td>
                            <td>{{ $creditEntry->line_narration ?: $voucher->general_narration }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="border border-dark p-2 mb-4 bg-light small">
                <strong>Amount in words:</strong> {{ $voucher->amount_in_words }}
            </div>

            <!-- Signatures -->
            <div class="d-flex justify-content-between align-items-center mt-5 pt-4 px-4 fs-14">
                <div class="text-center">
                    <span class="signature-line" style="min-width: 200px;">&nbsp;{{ $voucher->approvedByUser?->name }}</span><br>
                    <strong>Authorised By :</strong>
                </div>
                <div class="text-center">
                    <span class="signature-line" style="min-width: 200px;">&nbsp;{{ $voucher->preparedByUser?->name }}</span><br>
                    <strong>Prepared By :</strong>
                </div>
            </div>
        @endif

        <div class="text-center text-muted small mt-4 pt-2 border-top border-dashed">
            <em>Printed on {{ date('d M, Y h:i A') }} from Jamuna Stock Market ERP Financial Management System</em>
        </div>
    </div>

</body>
</html>

