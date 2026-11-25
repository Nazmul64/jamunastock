@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-file-text-o text-success me-2"></i> বিল বিস্তারিত: {{ $bill->bill_no }}
                    </h3>
                    <p class="text-muted mb-0">{{ $bill->billTypeBangla() }} | সরবরাহকারী: {{ $bill->party_name }}</p>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    @if($bill->outstanding_amount > 0)
                        <a href="{{ route('admin.bills_vouchers.vouchers.create', ['type' => 'payment', 'bill_id' => $bill->id]) }}" class="btn btn-success shadow-sm">
                            <i class="fa fa-money me-1"></i> পেমেন্ট ভাউচার তৈরি করুন (Pay Bill)
                        </a>
                    @endif
                    <a href="{{ route('admin.bills_vouchers.bills.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> বিল তালিকায় ফিরুন
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

            <!-- 1. Bill Invoice Header -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">বিল নম্বর</span>
                            <h5 class="fw-bold text-success mb-0">{{ $bill->bill_no }}</h5>
                            @if($bill->reference_no)
                                <small class="text-muted">রেফারেন্স: {{ $bill->reference_no }}</small>
                            @endif
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">বিলের প্রকার</span>
                            <span class="badge bg-light text-dark border fs-12">{{ $bill->billTypeBangla() }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">বিলের তারিখ</span>
                            <span class="fw-bold text-dark">{{ date('d M, Y', strtotime($bill->bill_date)) }}</span>
                        </div>
                        <div class="col-md-3 col-6">
                            <span class="text-muted small fw-bold d-block">পেমেন্ট স্ট্যাটাস</span>
                            <div>{!! $bill->paymentStatusBadge() !!}</div>
                        </div>

                        <div class="col-12"><hr class="my-1"></div>

                        <div class="col-md-4">
                            <span class="text-muted small fw-bold d-block">সরবরাহকারী / পার্টি তথ্য:</span>
                            <strong class="fs-15 text-dark">{{ $bill->party_name }}</strong>
                            @if($bill->party_phone)
                                <div class="text-muted small"><i class="fa fa-phone me-1"></i> {{ $bill->party_phone }}</div>
                            @endif
                            @if($bill->party_address)
                                <div class="text-muted small"><i class="fa fa-map-marker me-1"></i> {{ $bill->party_address }}</div>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted small fw-bold d-block">পরিশোধের মেয়াদ (Due Date):</span>
                            @if($bill->due_date)
                                <span class="fw-bold {{ strtotime($bill->due_date) < time() && $bill->outstanding_amount > 0 ? 'text-danger' : 'text-dark' }}">
                                    {{ date('d M, Y', strtotime($bill->due_date)) }}
                                    @if(strtotime($bill->due_date) < time() && $bill->outstanding_amount > 0)
                                        <span class="badge bg-danger ms-1">মেয়াদোত্তীর্ণ</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">নির্দিষ্ট মেয়াদ নেই</span>
                            @endif
                        </div>

                        <div class="col-md-4">
                            <span class="text-muted small fw-bold d-block">সংযুক্তি ফাইল:</span>
                            @if($bill->attachment)
                                <a href="{{ asset($bill->attachment) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">
                                    <i class="fa fa-download me-1"></i> স্ক্যান ফাইল ডাউনলোড / দেখুন
                                </a>
                            @else
                                <span class="text-muted small">কোনো ফাইল যুক্ত নেই</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Items Breakdown Table -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-success me-2"></i> বিল আইটেম বা সেবার বিবরণী
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 35%;">আইটেম বা সেবার নাম</th>
                                    <th style="width: 12%;">পরিমাণ</th>
                                    <th style="width: 15%;">একক মূল্য</th>
                                    <th style="width: 15%;">ভ্যাট / ট্যাক্স</th>
                                    <th style="width: 18%;" class="text-end">মোট টাকা (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bill->items as $idx => $item)
                                    <tr>
                                        <td class="text-center">{{ $idx + 1 }}</td>
                                        <td>
                                            <strong class="text-dark">{{ $item->item_name }}</strong>
                                            @if($item->description)
                                                <div class="text-muted small">{{ $item->description }}</div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ number_format($item->quantity, 2) }} {{ $item->unit }}</td>
                                        <td class="text-end">৳{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-center">
                                            @if($item->tax_percent > 0)
                                                {{ $item->tax_percent }}% (৳{{ number_format($item->tax_amount, 2) }})
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold text-dark">৳{{ number_format($item->total_amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 3. Financial Summary Row -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    @if($bill->notes)
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h6 class="fw-bold mb-0 text-dark">বিশেষ নোট</h6>
                            </div>
                            <div class="card-body">
                                <p class="mb-0 text-muted">{{ $bill->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">সাবটোটাল:</span>
                                <span class="fw-bold">৳{{ number_format($bill->subtotal, 2) }}</span>
                            </div>
                            @if($bill->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-danger">
                                    <span>ছাড় (Discount):</span>
                                    <span>- ৳{{ number_format($bill->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($bill->tax_vat_amount > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">ট্যাক্স / ভ্যাট:</span>
                                    <span>+ ৳{{ number_format($bill->tax_vat_amount, 2) }}</span>
                                </div>
                            @endif
                            @if($bill->additional_charges > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">অন্যান্য চার্জ:</span>
                                    <span>+ ৳{{ number_format($bill->additional_charges, 2) }}</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-2 fs-15">
                                <span class="fw-bold text-dark">সর্বমোট বিল (Grand Total):</span>
                                <span class="fw-bold text-dark">৳{{ number_format($bill->grand_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success fs-15">
                                <span class="fw-bold">পরিশোধিত অর্থ (Paid Amount):</span>
                                <span class="fw-bold">৳{{ number_format($bill->paid_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between fs-16 text-danger">
                                <span class="fw-bold">অবশিষ্ট বকেয়া (Outstanding):</span>
                                <span class="fw-bold">৳{{ number_format($bill->outstanding_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Payment Settlement Ledger (Vouchers Linked) -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom py-3 d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-credit-card text-primary me-2"></i> এই বিল পরিশোধ সংক্রান্ত ভাউচার হিসেব (Settlement History)
                    </h5>
                    @if($bill->outstanding_amount > 0)
                        <a href="{{ route('admin.bills_vouchers.vouchers.create', ['type' => 'payment', 'bill_id' => $bill->id]) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-plus me-1"></i> নতুন পেমেন্ট করুন
                        </a>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ভাউচার নং</th>
                                    <th>প্রকার</th>
                                    <th>তারিখ</th>
                                    <th>পেমেন্ট মেথড</th>
                                    <th class="text-end">এই বিলে বরাদ্দকৃত অর্থ</th>
                                    <th>ভাউচার স্ট্যাটাস</th>
                                    <th class="text-center">ভাউচার দেখুন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bill->paymentAllocations as $alloc)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bills_vouchers.vouchers.show', $alloc->voucher_id) }}" class="fw-bold text-primary">
                                                {{ $alloc->voucher?->voucher_no }}
                                            </a>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $alloc->voucher?->voucherTypeBangla() }}</span></td>
                                        <td>{{ date('d M, Y', strtotime($alloc->voucher?->voucher_date)) }}</td>
                                        <td><span class="badge bg-secondary-light text-secondary text-uppercase">{{ $alloc->voucher?->payment_mode }}</span></td>
                                        <td class="text-end fw-bold text-success">৳{{ number_format($alloc->allocated_amount, 2) }}</td>
                                        <td>{!! $alloc->voucher?->statusBadge() !!}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.bills_vouchers.vouchers.show', $alloc->voucher_id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-eye"></i> বিস্তারিত
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            এখনো কোনো পেমেন্ট ভাউচার এই বিলের বিপরীতে সংযুক্ত হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

