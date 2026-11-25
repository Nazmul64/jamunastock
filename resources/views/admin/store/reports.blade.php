@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-bar-chart text-info me-2"></i> স্টোর, সম্পদ ও আর্থিক অডিট রিপোর্ট (Store & Asset Audit Report)
                    </h3>
                    <p class="text-muted mb-0">নির্দিষ্ট তারিখ অনুযায়ী মেনটেনেন্স, মেরামত, বিল, ভাউচার, ক্যাশ মেমো ও মানি রিসিটের হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-print me-1"></i> রিপোর্ট প্রিন্ট করুন
                    </button>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Date Filter Card -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.store.reports') }}" class="row g-2 align-items-center">
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold mb-1">শুরুর তারিখ (Start Date)</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label fw-bold mb-1">শেষের তারিখ (End Date)</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 col-12 d-flex align-items-end gap-2 pt-md-4">
                            <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fa fa-search me-1"></i> রিপোর্ট দেখুন</button>
                            <a href="{{ route('admin.store.reports') }}" class="btn btn-outline-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Financial Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block mb-1">মেনটেনেন্স ও মেরামত ব্যয়</span>
                            <h3 class="mb-0 fw-bold text-warning">৳{{ number_format($totalMaintenanceCost, 2) }}</h3>
                            <small class="text-muted">{{ $maintenances->count() }} টি সার্ভিসিং রেকর্ড</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-danger border-4 h-100">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block mb-1">ডেবিট ভাউচার ও বিল ব্যয়</span>
                            <h3 class="mb-0 fw-bold text-danger">৳{{ number_format($totalDebitVouchers + $totalBills, 2) }}</h3>
                            <small class="text-muted">ভাউচার: ৳{{ number_format($totalDebitVouchers, 2) }} | বিল: ৳{{ number_format($totalBills, 2) }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block mb-1">ক্যাশ মেমো কেনাকাটা ব্যয়</span>
                            <h3 class="mb-0 fw-bold text-primary">৳{{ number_format($totalCashMemos, 2) }}</h3>
                            <small class="text-muted">দোকান ও স্টোর ক্যাশ মেমো</small>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-12">
                    <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block mb-1">মানি রিসিট মোট প্রাপ্তি (আয়)</span>
                            <h3 class="mb-0 fw-bold text-success">+৳{{ number_format($totalMoneyReceipts, 2) }}</h3>
                            <small class="text-muted">স্ক্র্যাপ/মালামাল বিক্রয় ও জমা</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Maintenance Report Table -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold text-dark mb-0">
                        <i class="fa fa-wrench text-warning me-2"></i> নির্বাচিত মেয়াদের মেনটেনেন্স ও সার্ভিসিং হিসেব
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>নম্বর ও তারিখ</th>
                                    <th>মালামাল / সম্পদ</th>
                                    <th>মেরামতের বিষয়</th>
                                    <th>মেকানিক / গ্যারেজ</th>
                                    <th>খরচ (৳)</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($maintenances as $mnt)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $mnt->maintenance_no }}</span>
                                            <small class="text-muted d-block">{{ $mnt->service_date ? $mnt->service_date->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>
                                            <strong>{{ $mnt->asset ? $mnt->asset->name : 'অজ্ঞাত' }}</strong>
                                            @if($mnt->asset && $mnt->asset->vehicle_reg_no)
                                                <small class="text-muted d-block">{{ $mnt->asset->vehicle_reg_no }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $mnt->title }}</td>
                                        <td>{{ $mnt->service_provider ?: '—' }}</td>
                                        <td class="fw-bold text-danger">৳{{ number_format($mnt->cost, 2) }}</td>
                                        <td>{!! $mnt->status_badge !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">এই মেয়াদে কোনো মেরামত ব্যয় পাওয়া যায়নি</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Vouchers & Memos Report Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold text-dark mb-0">
                        <i class="fa fa-file-text-o text-success me-2"></i> নির্বাচিত মেয়াদের বিল, ভাউচার, ক্যাশ মেমো ও মানি রিসিট
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>নম্বর ও তারিখ</th>
                                    <th>ধরন</th>
                                    <th>শিরোনাম</th>
                                    <th>ভেন্ডর / গ্রাহক</th>
                                    <th>পেমেন্ট মাধ্যম</th>
                                    <th>টাকার পরিমাণ (৳)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center">প্রিন্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vouchers as $v)
                                    <tr>
                                        <td>
                                            <span class="fw-bold">{{ $v->voucher_no }}</span>
                                            <small class="text-muted d-block">{{ $v->date ? $v->date->format('d/m/Y') : '' }}</small>
                                        </td>
                                        <td>{!! $v->type_badge !!}</td>
                                        <td>
                                            <strong>{{ $v->title }}</strong>
                                            <small class="text-muted d-block">{{ $v->category_label }}</small>
                                        </td>
                                        <td>{{ $v->vendor_customer_name ?: '—' }}</td>
                                        <td>{{ strtoupper($v->payment_method) }}</td>
                                        <td class="fw-bold {{ in_array($v->type, ['money_receipt', 'credit_voucher']) ? 'text-success' : 'text-danger' }}">
                                            {{ in_array($v->type, ['money_receipt', 'credit_voucher']) ? '+' : '-' }}৳{{ number_format($v->amount, 2) }}
                                        </td>
                                        <td>{!! $v->status_badge !!}</td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.store.vouchers.print', $v->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">এই মেয়াদে কোনো ভাউচার বা মেমো পাওয়া যায়নি</td>
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

