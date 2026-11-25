@extends('layouts.backend.app')

@section('content')
<style>
/* Official Printable Money Receipt Styling */
@media print {
    body * {
        visibility: hidden;
    }
    #printableReceipt, #printableReceipt * {
        visibility: visible;
    }
    #printableReceipt {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        margin: 0;
        padding: 20px;
        background: #fff;
    }
    .no-print {
        display: none !important;
    }
    @page {
        size: A4;
        margin: 15mm;
    }
}

.receipt-container {
    max-width: 850px;
    margin: 0 auto;
    background: #ffffff;
    border: 2px solid #0f766e;
    border-radius: 12px;
    position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.receipt-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 80px;
    font-weight: 900;
    color: rgba(15, 118, 110, 0.04);
    text-transform: uppercase;
    pointer-events: none;
    z-index: 1;
    white-space: nowrap;
}

.receipt-header-banner {
    background: linear-gradient(135deg, #044e3b 0%, #065f46 100%);
    color: #ffffff;
    padding: 24px 30px;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.receipt-table th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 600;
    font-size: 13px;
    border-color: #e2e8f0;
}
.receipt-table td {
    font-size: 13.5px;
    border-color: #e2e8f0;
}

.signature-box {
    border-top: 1.5px dashed #64748b;
    padding-top: 8px;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    min-width: 180px;
}
</style>

<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Print & Action Toolbar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <a href="{{ route('admin.investments.show', $receipt->investment_id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> বিনিয়োগ হিসেবে ফিরে যান
                </a>
                <a href="{{ route('admin.investments.receipts.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 ms-2">
                    <i class="fa fa-list me-1"></i> সকল রসিদ
                </a>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm" onclick="window.print()">
                    <i class="fa fa-print me-1"></i> A4 প্রিন্ট করুন
                </button>
                @if(!$receipt->is_void)
                    <button type="button" class="btn btn-outline-danger fw-semibold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#voidModal">
                        <i class="fa fa-ban me-1"></i> রসিদ বাতিল (Void)
                    </button>
                @endif
            </div>
        </div>

        @if($receipt->is_void)
            <div class="alert alert-danger rounded-4 p-3 mb-4 d-flex align-items-center gap-3 no-print">
                <i class="fa fa-exclamation-triangle fa-2x"></i>
                <div>
                    <h6 class="fw-bold mb-0">সতর্কতা: এই রসিদটি বাতিল (VOID) করা হয়েছে!</h6>
                    <small>বাতিলের কারণ: {{ $receipt->void_reason }} • বাতিলকারী: {{ $receipt->voidedByUser->name ?? 'Admin' }} ({{ \Carbon\Carbon::parse($receipt->voided_at)->format('d M Y, h:i A') }})</small>
                </div>
            </div>
        @endif

        @if($receipt->print_count > 1)
            <div class="alert alert-info rounded-pill py-2 px-3 mb-4 text-center small no-print">
                <i class="fa fa-info-circle me-1"></i> এটি রসিদের পুনঃমুদ্রণ কপি (Reprint #{{ $receipt->print_count }})। সর্বশেষ প্রিন্ট: {{ \Carbon\Carbon::parse($receipt->last_printed_at)->format('d M Y, h:i A') }}
            </div>
        @endif

        <!-- OFFICIAL RECEIPT CONTAINER -->
        <div class="receipt-container" id="printableReceipt">
            <!-- Watermark -->
            <div class="receipt-watermark">
                {{ $receipt->is_void ? 'VOID / CANCELLED' : 'OFFICIAL RECEIPT' }}
            </div>

            <!-- Top Header -->
            <div class="receipt-header-banner">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-white mb-1">যমুনা স্টক মার্কেট পিএলসি</h3>
                        <p class="mb-0 text-white-50 small">JAMUNA STOCK MARKET PLC • কৃষি ও বাণিজ্যিক এন্টারপ্রাইজ ইনভেস্টমেন্ট</p>
                        <small class="text-white-50">প্রধান কার্যালয়: ঢাকা, বাংলাদেশ • হেল্পলাইন: ০১৯১৫-৭৫২৭২৪ • www.jamunastock.com</small>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-warning text-dark px-3 py-2 fs-6 fw-bold text-uppercase mb-1">
                            @if($receipt->receipt_type === 'investment_deposit')
                                অফিশিয়াল বিনিয়োগ মানি রসিদ
                            @elseif($receipt->receipt_type === 'profit_payout')
                                মুনাফা পরিশোধ রসিদ (Profit Payout)
                            @else
                                মূলধন ফেরত রসিদ (Settlement Receipt)
                            @endif
                        </div>
                        <div class="text-white-50 small">অফিসিয়াল কপি ও গ্রাহক রেকর্ড</div>
                    </div>
                </div>
            </div>

            <!-- Receipt Meta Bar -->
            <div class="p-4 border-bottom bg-light">
                <div class="row g-2 align-items-center">
                    <div class="col-sm-6">
                        <span class="text-muted small">রসিদ নম্বর (Receipt No):</span>
                        <div class="fs-5 fw-bold text-success">{{ $receipt->receipt_number }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end">
                        <span class="text-muted small">ইস্যুর তারিখ ও সময়:</span>
                        <div class="fw-bold text-dark">{{ $receipt->created_at->format('d F, Y - h:i A') }}</div>
                    </div>
                    <div class="col-sm-6 border-top pt-2">
                        <span class="text-muted small">বিনিয়োগ হিসাব নম্বর (Investment Code):</span>
                        <div class="fw-bold text-primary">{{ $receipt->investment->investment_code }}</div>
                    </div>
                    <div class="col-sm-6 text-sm-end border-top pt-2">
                        <span class="text-muted small">চুক্তিনামা রেফারেন্স (Agreement No):</span>
                        <div class="fw-bold text-dark">{{ $receipt->investment->agreement_number }}</div>
                    </div>
                </div>
            </div>

            <!-- Main Body Info -->
            <div class="p-4 p-md-5">
                <!-- 1. CUSTOMER INFORMATION -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3" style="letter-spacing: 0.5px;">
                        <i class="fa fa-user me-2 text-primary"></i>গ্রাহকের পরিচিতি তথ্য (Investor Details)
                    </h6>
                    <div class="row g-2 small">
                        <div class="col-sm-6">
                            <span class="text-muted">পূর্ণ নাম (Full Name):</span>
                            <span class="fw-bold text-dark ms-2">{{ $receipt->customer->name }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">গ্রাহক কোড (Customer ID):</span>
                            <span class="fw-bold text-dark ms-2">{{ $receipt->customer->customer_code }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">মোবাইল নম্বর (Phone):</span>
                            <span class="fw-bold text-dark ms-2">{{ $receipt->customer->phone }}</span>
                        </div>
                        <div class="col-sm-6">
                            <span class="text-muted">জাতীয় পরিচয়পত্র (NID):</span>
                            <span class="fw-bold text-dark ms-2">{{ $receipt->customer->nid_number ?? 'N/A' }}</span>
                        </div>
                        <div class="col-12">
                            <span class="text-muted">বর্তমান ঠিকানা (Address):</span>
                            <span class="fw-medium text-dark ms-2">{{ $receipt->customer->present_address ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- 2. INVESTMENT & PAYMENT PARTICULARS -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase text-muted border-bottom pb-2 mb-3" style="letter-spacing: 0.5px;">
                        <i class="fa fa-hand-holding-usd me-2 text-success"></i>বিনিয়োগ ও লেনদেনের হিসাব বিবরণী (Financial Particulars)
                    </h6>
                    <table class="table table-bordered receipt-table align-middle">
                        <tbody>
                            <tr>
                                <th width="35%">বিনিয়োগের খাত (Sector)</th>
                                <td class="fw-bold text-dark">{{ $receipt->sector->name_bn }} ({{ $receipt->sector->code }})</td>
                            </tr>
                            <tr>
                                <th>পরিশোধের ধরণ (Transaction Purpose)</th>
                                <td>
                                    @if($receipt->receipt_type === 'investment_deposit')
                                        নতুন মূলধন বিনিয়োগ জমা (Initial Principal Investment)
                                    @elseif($receipt->receipt_type === 'profit_payout')
                                        মুনাফা কিস্তি পরিশোধ (Profit Installment Payout)
                                    @else
                                        মূলধন ফেরত / সেটেলমেন্ট (Principal Settlement Refund)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>চুক্তির মেয়াদকাল (Duration)</th>
                                <td>{{ $receipt->investment->duration_years }} বছর ({{ \Carbon\Carbon::parse($receipt->investment->start_date)->format('d M Y') }} থেকে {{ \Carbon\Carbon::parse($receipt->investment->maturity_date)->format('d M Y') }} পর্যন্ত)</td>
                            </tr>
                            <tr>
                                <th>মুনাফার হার ও প্ল্যান (Profit Rate & Plan)</th>
                                <td>{{ $receipt->investment->profit_rate }}% বাৎসরিক (মুনাফা গ্রহণ: {{ ucfirst($receipt->investment->profit_frequency) }})</td>
                            </tr>
                            <tr class="table-light">
                                <th class="fs-6 text-dark">গ্রহণের মোট পরিমাণ (Amount Received)</th>
                                <td class="fs-5 fw-bold text-success">৳{{ number_format($receipt->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>কথায় (Amount in Words)</th>
                                <td class="fw-bold text-dark">{{ $receipt->amount_in_words ?: 'BDT ' . number_format($receipt->amount) . ' Taka Only' }}</td>
                            </tr>
                            <tr>
                                <th>অর্থ পরিশোধের মাধ্যম (Payment Method)</th>
                                <td>{{ $receipt->payment_method }} {{ $receipt->transaction_ref ? '• রেফ: ' . $receipt->transaction_ref : '' }}</td>
                            </tr>
                            <tr>
                                <th>অর্থ গ্রহণকারী কর্মকর্তা (Received By)</th>
                                <td>{{ $receipt->received_by ?: 'Accounts Authority' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Terms and Condition Notice -->
                <div class="p-3 bg-light rounded-3 mb-5 small text-muted border" style="font-size: 11.5px; line-height: 1.5;">
                    <strong>শর্তাবলী ও অফিসিয়াল বিজ্ঞপ্তি:</strong><br>
                    ১. এই রসিদটি যমুনা স্টক মার্কেট পিএলসি-এর মূল কম্পিউটার সার্ভার থেকে স্বয়ংক্রিয়ভাবে প্রস্তুতকৃত।<br>
                    ২. উভয় পক্ষের স্বাক্ষরিত অফিসিয়াল চুক্তিপত্রের শর্তাবলী এবং কোম্পানির ইনভেস্টমেন্ট ও লাভ বণ্টন পলিসি অনুযায়ী লেনদেন কার্যকর হবে।<br>
                    ৩. মেয়াদের পূর্বে টাকা উত্তোলনের ক্ষেত্রে চুক্তিপত্র অনুসারে লাভ কর্তন ও প্রারম্ভিক নিষ্পত্তি নীতি প্রযোজ্য।<br>
                    ৪. রসিদ নম্বরহীন বা সিল-স্বাক্ষরবিহীন কোনো রসিদ গ্রহণযোগ্য হবে না।
                </div>

                <!-- Dual Signatures (Section 8) -->
                <div class="d-flex justify-content-between align-items-end pt-4" style="margin-top: 40px;">
                    <div class="text-center">
                        <div class="signature-box">
                            গ্রাহক / বিনিয়োগকারীর স্বাক্ষর<br>
                            <span class="text-muted small fw-normal">Customer Signature</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="signature-box">
                            অনুমোদিত কর্মকর্তার স্বাক্ষর ও সিল<br>
                            <span class="text-muted small fw-normal">Authorized Signature & Seal</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Footer Bar -->
            <div class="p-3 border-top bg-light text-center small text-muted rounded-bottom">
                Jamuna Stock Market PLC Enterprise Investment System • All Rights Reserved. System Verification Code: {{ md5($receipt->receipt_number . $receipt->created_at) }}
            </div>
        </div>

    </div>
</div>

<!-- Void Modal -->
<div class="modal fade" id="voidModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold text-danger">রসিদ বাতিল (Void Receipt) নিশ্চিতকরণ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.investments.receipts.void', $receipt->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small mb-3">
                        সতর্কতা: একবার রসিদ বাতিল করা হলে তা পরিবর্তন করা যাবে না। অডিট ট্রেইলে বাতিলের কারণ এবং আপনার আইডি স্থায়ীভাবে সংরক্ষিত হবে।
                    </p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">বাতিল করার সুনির্দিষ্ট কারণ <span class="text-danger">*</span></label>
                        <textarea name="void_reason" class="form-control" rows="3" placeholder="উদাঃ ভুল পরিমাণ এন্ট্রি বা চেক প্রত্যাখ্যাত..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold">বাতিল (Void) সম্পন্ন করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
