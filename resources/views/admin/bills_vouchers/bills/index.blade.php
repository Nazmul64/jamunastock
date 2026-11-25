@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-files-o text-success me-2"></i> আর্থিক বিল রেজিস্টার (Financial Bills Management)
                    </h3>
                    <p class="text-muted mb-0">ক্রয় বিল, বিক্রয় বিল, ইউটিলিটি, অফিস ভাড়া, মেরামত ও অন্যান্য বিল ট্র্যাকিং</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.bills_vouchers.bills.create') }}" class="btn btn-success shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন বিল এন্ট্রি করুন
                    </a>
                    <a href="{{ route('admin.bills_vouchers.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
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

            <!-- Summary Top Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm p-3 border-start border-primary border-4">
                        <span class="text-muted small fw-bold">মোট বিল সংখ্যা</span>
                        <h4 class="fw-bold text-primary mb-0 mt-1">{{ $allBillsCount }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm p-3 border-start border-danger border-4">
                        <span class="text-muted small fw-bold">সম্পূর্ণ অপরিশোধিত (Unpaid)</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ $unpaidCount }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm p-3 border-start border-warning border-4">
                        <span class="text-muted small fw-bold">আংশিক পরিশোধ (Partial)</span>
                        <h4 class="fw-bold text-warning mb-0 mt-1">{{ $partialCount }} টি</h4>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="card border-0 shadow-sm p-3 border-start border-success border-4">
                        <span class="text-muted small fw-bold">সম্পূর্ণ পরিশোধিত (Paid)</span>
                        <h4 class="fw-bold text-success mb-0 mt-1">{{ $paidCount }} টি</h4>
                    </div>
                </div>
            </div>

            <!-- Filter Search -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.bills_vouchers.bills.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-muted">সার্চ (বিল নং / পার্টি / রেফারেন্স)</label>
                            <input type="text" name="search" class="form-control" placeholder="যেমন: BILL-2026, গ্রীন ট্রেডার্স..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">বিলের প্রকার</label>
                            <select name="bill_type" class="form-select">
                                <option value="all">সকল প্রকার</option>
                                <option value="purchase" {{ request('bill_type') == 'purchase' ? 'selected' : '' }}>ক্রয় বিল (Purchase)</option>
                                <option value="sales" {{ request('bill_type') == 'sales' ? 'selected' : '' }}>বিক্রয় বিল (Sales)</option>
                                <option value="utility" {{ request('bill_type') == 'utility' ? 'selected' : '' }}>ইউটিলিটি বিল</option>
                                <option value="electricity" {{ request('bill_type') == 'electricity' ? 'selected' : '' }}>বিদ্যুৎ বিল</option>
                                <option value="rent" {{ request('bill_type') == 'rent' ? 'selected' : '' }}>অফিস ভাড়া বিল</option>
                                <option value="maintenance" {{ request('bill_type') == 'maintenance' ? 'selected' : '' }}>মেরামত বিল</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold small text-muted">পেমেন্ট স্ট্যাটাস</label>
                            <select name="payment_status" class="form-select">
                                <option value="all">সকল স্ট্যাটাস</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>পরিশোধহীন (Unpaid)</option>
                                <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>আংশিক পরিশোধ (Partial)</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>পরিশোধিত (Paid)</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-fill">
                                <i class="fa fa-filter me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.bills_vouchers.bills.index') }}" class="btn btn-outline-secondary">
                                রিসেট
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Bills Table -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>বিল নং ও রেফারেন্স</th>
                                    <th>প্রকার</th>
                                    <th>পার্টি / সরবরাহকারী</th>
                                    <th>বিল তারিখ</th>
                                    <th>পরিশোধের শেষ তারিখ</th>
                                    <th class="text-end">মোট বিল (৳)</th>
                                    <th class="text-end">পরিশোধ (৳)</th>
                                    <th class="text-end">বকেয়া (৳)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center" style="width: 140px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bills as $bill)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.bills_vouchers.bills.show', $bill->id) }}" class="fw-bold text-success">
                                                {{ $bill->bill_no }}
                                            </a>
                                            @if($bill->reference_no)
                                                <div class="text-muted fs-11">রেফ: {{ $bill->reference_no }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $bill->billTypeBangla() }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $bill->party_name }}</strong>
                                            @if($bill->party_phone)
                                                <div class="text-muted fs-11">{{ $bill->party_phone }}</div>
                                            @endif
                                        </td>
                                        <td>{{ date('d M, Y', strtotime($bill->bill_date)) }}</td>
                                        <td>
                                            @if($bill->due_date)
                                                <span class="{{ strtotime($bill->due_date) < time() && $bill->outstanding_amount > 0 ? 'text-danger fw-bold' : '' }}">
                                                    {{ date('d M, Y', strtotime($bill->due_date)) }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end fw-bold">৳{{ number_format($bill->grand_total, 2) }}</td>
                                        <td class="text-end fw-bold text-success">৳{{ number_format($bill->paid_amount, 2) }}</td>
                                        <td class="text-end fw-bold text-danger">৳{{ number_format($bill->outstanding_amount, 2) }}</td>
                                        <td>{!! $bill->paymentStatusBadge() !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.bills_vouchers.bills.show', $bill->id) }}" class="btn btn-outline-secondary" title="বিস্তারিত">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @if($bill->outstanding_amount > 0)
                                                    <a href="{{ route('admin.bills_vouchers.vouchers.create', ['type' => 'payment', 'bill_id' => $bill->id]) }}" class="btn btn-outline-success" title="পেমেন্ট ভাউচার তৈরি">
                                                        <i class="fa fa-money"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-5 text-muted">
                                            <i class="fa fa-file-text-o fa-3x mb-3 text-muted opacity-50 d-block"></i>
                                            কোনো বিল রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($bills->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        {{ $bills->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

