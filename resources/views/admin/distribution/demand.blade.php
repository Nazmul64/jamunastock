@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-calculator me-2 text-info"></i> মাসিক বাজার পণ্য চাহিদা হিসাব (Demand Calculation)</h4>
                    <p class="subtitle text-muted mb-0">সদস্যদের আবেদন ও অনুমোদিত চাহিদার বিপরীতে সেন্ট্রাল ওয়্যারহাউসের ঘাটতি এবং প্রয়োজনীয় ক্রয় হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.distribution.warehouse_stock') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-cubes me-1"></i> সেন্ট্রাল স্টক দেখুন
                    </a>
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-warning btn-sm">
                        <i class="fa fa-shopping-cart me-1"></i> সাপ্লায়ার থেকে ক্রয় এন্ট্রি
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Formula Banner -->
            <div class="alert alert-light border border-info shadow-sm d-flex align-items-center mb-4" role="alert">
                <i class="fa fa-info-circle text-info fs-24 me-3"></i>
                <div>
                    <strong class="text-dark fs-15">চাহিদা ও ঘাটতি নির্ণয় সূত্র (Standard Procurement Formula):</strong>
                    <div class="text-muted small mt-1">
                        <code>অনুমোদিত চাহিদা (Approved Demand) - সেন্ট্রাল ওয়্যারহাউস স্টক (Central Stock) = প্রয়োজনীয় ক্রয় পরিমাণ (Required Purchase)</code>
                    </div>
                </div>
            </div>

            <!-- Product-wise Demand Table -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0"><i class="fa fa-list-alt text-primary me-2"></i> পণ্যভিত্তিক চাহিদা ও সেন্ট্রাল স্টক সমন্বয়</h4>
                            <span class="badge bg-secondary">{{ count($productsData) }} টি পণ্য তালিকাভুক্ত</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>পণ্যের নাম</th>
                                            <th class="text-center">আবেদন সংখ্যা</th>
                                            <th class="text-end">মোট আবেদনকৃত পরিমাণ</th>
                                            <th class="text-end">অনুমোদিত চাহিদা</th>
                                            <th class="text-end">সেন্ট্রাল বর্তমান স্টক</th>
                                            <th class="text-end">প্রয়োজনীয় ক্রয় (ঘাটতি)</th>
                                            <th class="text-center">অবস্থা (Status)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($productsData as $idx => $p)
                                        <tr>
                                            <td>{{ $idx + 1 }}</td>
                                            <td><strong class="text-dark fs-15">{{ $p['product_name'] }}</strong></td>
                                            <td class="text-center"><span class="badge bg-light text-dark border">{{ $p['total_orders'] }} টি</span></td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($p['total_requested'], 1) }} {{ $p['unit'] }}</td>
                                            <td class="text-end fw-bold text-primary">{{ number_format($p['approved_demand'], 1) }} {{ $p['unit'] }}</td>
                                            <td class="text-end fw-bold text-success">{{ number_format($p['central_stock'], 1) }} {{ $p['unit'] }}</td>
                                            <td class="text-end fw-bold {{ $p['required_purchase'] > 0 ? 'text-danger' : 'text-success' }}">
                                                {{ number_format($p['required_purchase'], 1) }} {{ $p['unit'] }}
                                            </td>
                                            <td class="text-center">
                                                @if($p['required_purchase'] > 0)
                                                    <span class="badge bg-danger">ক্রয় প্রয়োজন</span>
                                                @else
                                                    <span class="badge bg-success">পর্যাপ্ত স্টক</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">এই মুহূর্তে কোনো মাসিক বাজার চাহিদা পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Area-wise Demand Breakdown -->
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light">
                            <h4 class="box-title mb-0"><i class="fa fa-map-marker text-danger me-2"></i> এলাকাতালিকা অনুযায়ী সামগ্রিক চাহিদা বিবরণী</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>এলাকার নাম</th>
                                            <th class="text-center">আবেদনকারী সদস্য সংখ্যা</th>
                                            <th class="text-center">মোট রিকোয়েস্ট সংখ্যা</th>
                                            <th class="text-end">মোট চাহিদা পরিমাণ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($areaDemands as $ad)
                                        <tr>
                                            <td><strong class="text-dark">{{ $ad->request_area ?: 'অনির্দিষ্ট এলাকা' }}</strong></td>
                                            <td class="text-center"><span class="badge bg-info">{{ $ad->total_members }} জন</span></td>
                                            <td class="text-center"><span class="badge bg-secondary">{{ $ad->total_requests }} টি</span></td>
                                            <td class="text-end fw-bold text-dark">{{ number_format($ad->total_qty, 1) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">কোনো এলাকাতালিকা ডাটা নেই।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection
