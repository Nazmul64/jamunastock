@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">আমার অ্যাকাউন্ট স্টেটমেন্ট ও ট্রানজেকশন হিস্ট্রি</h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">অ্যাকাউন্ট স্টেটমেন্ট</li>
                    </ol>
                </div>
                <div>
                    <a href="{{ route('supplier.statement.print') }}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3">
                        <i class="fa fa-print me-1"></i> প্রিন্ট / PDF স্টেটমেন্ট
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-2 col-6">
                    <div class="box bg-secondary-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">প্রারম্ভিক জের</small>
                            <h4 class="fw-bold mb-0 mt-1">৳{{ number_format($openingBalance, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="box bg-primary-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">মোট জমাকৃত</small>
                            <h4 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalSubmitted ?? $totalSupply, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="box bg-info-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">রিসিভড প্রাপ্য</small>
                            <h4 class="fw-bold text-info mb-0 mt-1">৳{{ number_format($totalSupply, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="box bg-warning-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">ঘাটতি (Discrepancy)</small>
                            <h4 class="fw-bold text-warning mb-0 mt-1">৳{{ number_format($totalDiscrepancy ?? 0, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="box bg-success-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">মোট পরিশোধিত</small>
                            <h4 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalPaid, 2) }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="box bg-danger-light rounded-4 mb-0">
                        <div class="box-body text-center p-3">
                            <small class="text-muted fw-bold d-block">বর্তমান বকেয়া</small>
                            <h4 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($currentBalance, 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ledger Table -->
            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-header with-border py-3 px-4 bg-light">
                    <h4 class="box-title fw-bold mb-0">বিস্তারিত লেজার বিবরণী (Actual Received & Payment Ledger)</h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">তারিখ</th>
                                    <th>রেফারেন্স</th>
                                    <th>বিবরণ</th>
                                    <th class="text-end">সরবরাহ / পাওনা (+)</th>
                                    <th class="text-end">প্রাপ্ত পেমেন্ট (-)</th>
                                    <th class="text-end pe-3">ব্যালেন্স (৳)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ledger as $row)
                                    <tr>
                                        <td class="ps-3">{{ $row['date'] }}</td>
                                        <td><span class="badge bg-info">{{ $row['ref'] }}</span></td>
                                        <td>
                                            <strong>{{ $row['type'] }}</strong><br>
                                            <small class="text-muted">{{ $row['description'] }}</small>
                                        </td>
                                        <td class="text-end text-danger fw-bold">
                                            {{ $row['debit'] > 0 ? '৳' . number_format($row['debit'], 2) : '-' }}
                                        </td>
                                        <td class="text-end text-success fw-bold">
                                            {{ $row['credit'] > 0 ? '৳' . number_format($row['credit'], 2) : '-' }}
                                        </td>
                                        <td class="text-end fw-bold pe-3">৳{{ number_format($row['balance'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">কোনো ট্রানজেকশন বিবরণী পাওয়া যায়নি</td>
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
