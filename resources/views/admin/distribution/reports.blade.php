@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-bar-chart me-2 text-primary"></i> সেন্ট্রাল ডিস্ট্রিবিউশন সমন্বিত রিপোর্ট (Reports)</h4>
                    <p class="subtitle text-muted mb-0">এলাকা, এজেন্ট পয়েন্ট ও পণ্যভিত্তিক চাহিদা, সরবরাহ এবং মজুদের পূর্ণাঙ্গ প্রতিবেদন</p>
                </div>
                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                    <i class="fa fa-print me-1"></i> রিপোর্ট প্রিন্ট
                </button>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs nav-fill mb-4 bg-white p-2 rounded shadow-sm">
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ $reportType === 'area_wise' ? 'active text-primary' : 'text-muted' }}" href="{{ route('admin.distribution.reports', ['type' => 'area_wise']) }}">
                        <i class="fa fa-map-marker me-1"></i> এলাকাভিত্তিক রিপোর্ট (Area-wise)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ $reportType === 'agent_wise' ? 'active text-primary' : 'text-muted' }}" href="{{ route('admin.distribution.reports', ['type' => 'agent_wise']) }}">
                        <i class="fa fa-university me-1"></i> এজেন্ট পয়েন্টভিত্তিক রিপোর্ট (Agent-wise)
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold {{ $reportType === 'product_wise' ? 'active text-primary' : 'text-muted' }}" href="{{ route('admin.distribution.reports', ['type' => 'product_wise']) }}">
                        <i class="fa fa-cubes me-1"></i> পণ্যভিত্তিক রিপোর্ট (Product-wise)
                    </a>
                </li>
            </ul>

            <!-- Report Content Based on Type -->
            <div class="box shadow-sm">
                <div class="box-header with-border bg-light">
                    <h4 class="box-title mb-0">
                        @if($reportType === 'area_wise')
                            <i class="fa fa-map-marker text-danger me-2"></i> এলাকাভিত্তিক কার্ডহোল্ডার ও এজেন্ট বণ্টন খতিয়ান
                        @elseif($reportType === 'agent_wise')
                            <i class="fa fa-university text-info me-2"></i> পয়েন্টভিত্তিক ধারণক্ষমতা ও সক্রিয় সদস্য বিবরণী
                        @else
                            <i class="fa fa-cubes text-success me-2"></i> সেন্ট্রাল গুদাম পণ্য মজুদ ও সরবরাহ সারাংশ
                        @endif
                    </h4>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        @if($reportType === 'area_wise')
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>এলাকার নাম</th>
                                        <th>কোড</th>
                                        <th>জেলা / উপজেলা</th>
                                        <th class="text-center">এজেন্ট পয়েন্ট সংখ্যা</th>
                                        <th class="text-center">নিবন্ধিত কার্ডহোল্ডার</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($areas as $idx => $ar)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong class="text-dark fs-15">{{ $ar->name }}</strong></td>
                                        <td><span class="badge bg-secondary font-monospace">{{ $ar->code }}</span></td>
                                        <td>{{ $ar->district }} ({{ $ar->upazila }})</td>
                                        <td class="text-center"><span class="badge bg-info">{{ $ar->agentPoints->count() }} টি</span></td>
                                        <td class="text-center"><span class="badge bg-success">{{ $ar->card_holders_count }} জন</span></td>
                                        <td><span class="badge {{ $ar->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($ar->status) }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="text-center py-4 text-muted">কোনো এলাকা তথ্য পাওয়া যায়নি।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif($reportType === 'agent_wise')
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>পয়েন্টের নাম</th>
                                        <th>পয়েন্ট কোড</th>
                                        <th>এলাকা</th>
                                        <th>ইনচার্জ এজেন্ট</th>
                                        <th>মোবাইল</th>
                                        <th class="text-end">ধারণক্ষমতা</th>
                                        <th>স্ট্যাটাস</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($agentPoints as $idx => $ap)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong class="text-dark fs-15">{{ $ap->name }}</strong></td>
                                        <td><span class="badge bg-secondary font-monospace">{{ $ap->code ?: ('AP-'.$ap->id) }}</span></td>
                                        <td>{{ $ap->areaModel ? $ap->areaModel->name : ($ap->area ?: 'N/A') }}</td>
                                        <td>{{ $ap->agentUser ? $ap->agentUser->name : 'নির্ধারিত নেই' }}</td>
                                        <td>{{ $ap->contact_number ?: ($ap->agentUser ? $ap->agentUser->phone : 'N/A') }}</td>
                                        <td class="text-end fw-bold">{{ number_format($ap->capacity ?: 1000) }} KG</td>
                                        <td><span class="badge {{ $ap->status === 'active' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($ap->status) }}</span></td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="8" class="text-center py-4 text-muted">কোনো এজেন্ট পয়েন্ট তথ্য পাওয়া যায়নি।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @else
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>পণ্যের নাম</th>
                                        <th>পরিমাপক ইউনিট</th>
                                        <th class="text-end">মোট প্রাপ্তি (Received)</th>
                                        <th class="text-end">এজেন্ট চালান (Allocated)</th>
                                        <th class="text-end">বর্তমান মজুদ (Available)</th>
                                        <th class="text-end">অপচয় (Damaged)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $idx => $pr)
                                    <tr>
                                        <td>{{ $idx + 1 }}</td>
                                        <td><strong class="text-dark fs-15">{{ $pr->product_name }}</strong></td>
                                        <td><span class="badge bg-light text-dark border">{{ $pr->unit }}</span></td>
                                        <td class="text-end fw-bold text-dark">{{ number_format($pr->total_received, 1) }}</td>
                                        <td class="text-end fw-bold text-primary">{{ number_format($pr->allocated_stock, 1) }}</td>
                                        <td class="text-end fw-bold text-success">{{ number_format($pr->available_stock, 1) }}</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($pr->damaged_stock, 1) }}</td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="7" class="text-center py-4 text-muted">কোনো পণ্য মজুদ তথ্য পাওয়া যায়নি।</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>

        </section>
    </div>
</div>
@endsection
