@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-television me-2 text-primary"></i> সেন্ট্রাল এজেন্ট মনিটরিং ও গ্লোবাল কম্প্যারিজন (Global Agent Monitoring)</h4>
                    <p class="subtitle text-muted mb-0">সকল এজেন্ট পয়েন্টের সদস্য সংখ্যা, প্রাপ্ত স্টক, বিতরণকৃত বাজার মূল্য (৳) এবং পারফরম্যান্স তুলনা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.agents.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-users me-1"></i> এজেন্ট তালিকা
                    </a>
                    <a href="{{ route('admin.distribution.demand') }}" class="btn btn-outline-info btn-sm">
                        <i class="fa fa-calculator me-1"></i> চাহিদা হিসাব
                    </a>
                    <a href="{{ route('admin.distribution.allocate') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-truck me-1"></i> পণ্য বরাদ্দ / ডিসপ্যাচ
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- 4 Summary KPI Cards (Matching Bossman / JM Pharm / FreshCart) -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-primary text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">৳ {{ number_format($totalAllMarketAmount, 2) }}</h2>
                                <span class="fs-13 opacity-75">মোট বাজার বিতরণ মূল্য (Market Value)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-money"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-success text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalAllDelivered, 1) }} <small class="fs-13">KG</small></h2>
                                <span class="fs-13 opacity-75">মোট বিতরণকৃত পণ্য (Total Distributed)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-check-circle"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-info text-white shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-white fw-bold">{{ number_format($totalAllMembers) }}</h2>
                                <span class="fs-13 opacity-75">মোট নির্ধারিত কার্ডহোল্ডার (Members)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-id-card"></i></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="box bg-warning text-dark shadow-sm mb-0">
                        <div class="box-body d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 text-dark fw-bold">{{ number_format($totalAllRemaining, 1) }} <small class="fs-13">KG</small></h2>
                                <span class="fs-13 text-dark opacity-75">এজেন্ট পয়েন্টে অবশিষ্ট স্টক (Remaining)</span>
                            </div>
                            <div class="fs-36 opacity-50"><i class="fa fa-cubes"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agent Distribution Monitoring & Comparison Table (Section 33.8 & 33.15) -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0"><i class="fa fa-map-marker text-danger me-2"></i> এজেন্ট পয়েন্টভিত্তিক বিতরণ ও আর্থিক পারফরম্যান্স তুলনা (Agent Comparison)</h4>
                            <span class="badge bg-primary fs-12">{{ count($monitoringData) }} টি এজেন্ট পয়েন্ট সক্রিয়</span>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>এজেন্ট পয়েন্ট ও কোড</th>
                                            <th>এলাকা (Area)</th>
                                            <th>দায়িত্বপ্রাপ্ত এজেন্ট</th>
                                            <th class="text-center">নির্ধারিত সদস্য</th>
                                            <th class="text-center">বাজার আবেদন</th>
                                            <th class="text-end">বিতরণকৃত পণ্য</th>
                                            <th class="text-end">অবশিষ্ট স্টক</th>
                                            <th class="text-end">মোট বাজার মূল্য (৳)</th>
                                            <th class="text-center" style="width: 140px;">অগ্রগতি</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($monitoringData as $row)
                                        @php
                                            $pt = $row['point'];
                                            $rec = $row['received_stock'];
                                            $del = $row['delivered_stock'];
                                            $percent = $rec > 0 ? min(100, round(($del / $rec) * 100)) : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.agent_monitoring.details', $pt->id) }}" class="fw-bold text-primary fs-15 text-decoration-none">
                                                    {{ $pt->name }}
                                                </a>
                                                <div class="text-muted small font-monospace">{{ $pt->code ?: ('AP-' . $pt->id) }}</div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $pt->areaModel ? $pt->areaModel->name : ($pt->area ?: 'N/A') }}</span>
                                            </td>
                                            <td>
                                                @if($pt->agentUser)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <img src="{{ $pt->agentUser->avatarUrl() }}" alt="Agent" class="rounded-circle border" style="width: 32px; height: 32px; object-fit: cover;">
                                                        <div>
                                                            <a href="{{ route('admin.agents.show', $pt->agentUser->id) }}" class="fw-semibold text-dark text-decoration-none">
                                                                {{ $pt->agentUser->name }}
                                                            </a>
                                                            <div class="small text-muted font-monospace">{{ $pt->agentUser->agent_id ?: '' }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-danger small"><i class="fa fa-exclamation-triangle"></i> ইউজার নির্ধারিত নেই</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info px-2 py-1 fs-12">{{ $row['assigned_members'] }} জন</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark border px-2 py-1 fs-12">{{ $row['market_requests'] }} টি</span>
                                            </td>
                                            <td class="text-end fw-bold text-success">{{ number_format($row['delivered_stock'], 1) }}</td>
                                            <td class="text-end fw-bold {{ $row['remaining_stock'] > 0 ? 'text-warning' : 'text-muted' }}">
                                                {{ number_format($row['remaining_stock'], 1) }}
                                            </td>
                                            <td class="text-end fw-bold text-success fs-14">
                                                ৳ {{ number_format($row['market_amount'], 2) }}
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <small class="text-muted fs-11 mt-1 d-block">{{ $percent }}% সম্পন্ন</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    @if($pt->agentUser)
                                                        <a href="{{ route('admin.agents.show', $pt->agentUser->id) }}" class="btn btn-xs btn-outline-primary" title="এজেন্ট পূর্ণাঙ্গ আর্থিক রিপোর্ট">
                                                            <i class="fa fa-money"></i> অডিট
                                                        </a>
                                                    @endif
                                                    <a href="{{ route('admin.agent_monitoring.details', $pt->id) }}" class="btn btn-xs btn-outline-secondary" title="পয়েন্ট বিবরণ">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4 text-muted">কোনো এজেন্ট পয়েন্ট তৈরি করা হয়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Live Delivery Stream with Amount -->
                <div class="col-12">
                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0"><i class="fa fa-history text-info me-2"></i> সাম্প্রতিক পণ্য ও বাজার মূল্য বিতরণ কার্যকলাপ (Live Delivery Stream)</h4>
                            <a href="{{ route('admin.distribution.deliveries_history') }}" class="btn btn-sm btn-link text-primary text-decoration-none">
                                সম্পূর্ণ ডেলিভারি ইতিহাস দেখুন &rarr;
                            </a>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>রশিদ নং</th>
                                            <th>এজেন্ট পয়েন্ট</th>
                                            <th>কার্ড নাম্বার</th>
                                            <th>সদস্যের নাম</th>
                                            <th class="text-end">বাজার মূল্য (৳)</th>
                                            <th>তারিখ ও সময়</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">রশিদ প্রিন্ট</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentDeliveries as $del)
                                        <tr>
                                            <td><span class="badge bg-secondary font-monospace">{{ $del->delivery_no }}</span></td>
                                            <td><strong class="text-dark">{{ $del->agentPoint->name ?? 'N/A' }}</strong></td>
                                            <td><span class="badge bg-warning text-dark font-monospace">{{ $del->card_number }}</span></td>
                                            <td>{{ $del->member_name }}</td>
                                            <td class="text-end fw-bold text-success">৳ {{ number_format($del->total_amount, 2) }}</td>
                                            <td>{{ $del->delivery_date }} {{ $del->delivery_time ? date('h:i A', strtotime($del->delivery_time)) : '' }}</td>
                                            <td>
                                                <span class="badge {{ $del->status === 'completed' ? 'bg-success' : 'bg-info' }}">
                                                    {{ ucfirst($del->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('agent.delivery.receipt', $del->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary">
                                                    <i class="fa fa-print"></i> রশিদ
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">সাম্প্রতিক কোনো বিতরণ পাওয়া যায়নি।</td>
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
