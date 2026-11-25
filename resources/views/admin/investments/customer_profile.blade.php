@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('admin.investments.central.dashboard') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <!-- Global Customer Profile & Multi-Sector Portfolio Breakdown (Section 3) -->
        <div class="row g-4">
            <!-- KYC Profile Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100">
                    <div class="text-center border-bottom pb-4 mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fa fa-user-tie fa-3x"></i>
                        </div>
                        <span class="badge bg-light text-dark border px-3 py-1 mb-2">{{ $customer->customer_code }}</span>
                        <h4 class="fw-bold mb-1 text-dark">{{ $customer->name }}</h4>
                        <div class="text-muted"><i class="fa fa-phone me-1"></i>{{ $customer->phone }}</div>
                    </div>

                    <div class="small">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">পিতার নাম:</span>
                            <span class="fw-bold text-dark">{{ $customer->father_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">মাতার নাম:</span>
                            <span class="fw-bold text-dark">{{ $customer->mother_name ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">এনআইডি নম্বর:</span>
                            <span class="fw-bold text-dark">{{ $customer->nid_number ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">পেশা:</span>
                            <span class="fw-bold text-dark">{{ $customer->occupation ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">বর্তমান ঠিকানা:</span>
                            <span class="fw-medium text-end">{{ $customer->present_address ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">নমিনি:</span>
                            <span class="fw-bold text-dark">{{ $customer->nominee_name ?? 'N/A' }} ({{ $customer->nominee_relation ?? '' }})</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">নমিনির ফোন:</span>
                            <span class="fw-bold text-dark">{{ $customer->nominee_phone ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Multi-Sector Portfolio Table -->
            <div class="col-lg-8">
                <!-- Summary Bar -->
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3"><i class="fa fa-chart-pie text-success me-2"></i>গ্রাহকের সার্বিক মাল্টি-সেক্টর বিনিয়োগ হিসাব (Overall Portfolio)</h5>
                    <div class="row g-3 text-center">
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small">সর্বমোট বিনিয়োগ মূলধন</span>
                                <h3 class="fw-bold text-success my-1">৳{{ number_format($customer->investments->sum('amount'), 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small">মোট লাভ গ্রহণ</span>
                                <h3 class="fw-bold text-info my-1">৳{{ number_format($customer->investments->sum('paid_profit_total'), 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded-3">
                                <span class="text-muted small">সক্রিয় সেক্টর সংখ্যা</span>
                                <h3 class="fw-bold text-primary my-1">{{ $customer->investments->pluck('sector_id')->unique()->count() }}টি খাত</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sector-wise Investment Accounts List (Section 3 Example: Rahim Ahmed) -->
                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0 text-dark">সেক্টরভিত্তিক পৃথক অ্যাকাউন্ট ও চুক্তিপত্রসমূহ</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light text-muted small">
                                    <tr>
                                        <th>বিনিয়োগ কোড</th>
                                        <th>সেক্টর</th>
                                        <th>মূলধন</th>
                                        <th>মেয়াদ</th>
                                        <th>মুনাফার হার</th>
                                        <th>পরিশোধিত লাভ</th>
                                        <th>অবস্থা</th>
                                        <th class="text-end">অ্যাকশন</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customer->investments as $inv)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.investments.show', $inv->id) }}" class="fw-bold text-primary">
                                                    {{ $inv->investment_code }}
                                                </a>
                                                <div class="small text-muted">{{ $inv->agreement_number }}</div>
                                            </td>
                                            <td>
                                                <strong>{{ $inv->sector->name_bn }}</strong>
                                                <div class="small text-muted">{{ $inv->sector->code }}</div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success fs-6">৳{{ number_format($inv->amount, 2) }}</span>
                                            </td>
                                            <td>{{ $inv->duration_years }} বছর</td>
                                            <td>{{ $inv->profit_rate }}% ({{ ucfirst($inv->profit_frequency) }})</td>
                                            <td><span class="fw-bold text-info">৳{{ number_format($inv->paid_profit_total, 2) }}</span></td>
                                            <td>
                                                <span class="badge bg-success bg-opacity-10 text-success">{{ ucfirst($inv->status) }}</span>
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.investments.show', $inv->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                                    হিসাব দেখুন
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">কোনো চুক্তি নেই।</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
