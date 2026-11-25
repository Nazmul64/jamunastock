@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-history me-2 text-primary"></i> সকল এজেন্ট পয়েন্টের বিতরণ ইতিহাস (Delivery History)</h4>
                    <p class="subtitle text-muted mb-0">কেন্দ্রীয়ভাবে সকল সদস্যকে পণ্য হস্তান্তরের বিস্তারিত লগ ও অডিট ট্রেইল</p>
                </div>
                <div>
                    <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-success btn-sm">
                        <i class="fa fa-file-excel-o me-1"></i> এক্সেল / CSV ডাউনলোড
                    </a>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <!-- Filter Box -->
            <div class="box shadow-sm mb-4">
                <div class="box-body">
                    <form method="GET" action="{{ route('admin.distribution.deliveries_history') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">এজেন্ট পয়েন্ট</label>
                            <select name="agent_point_id" class="form-select form-select-sm">
                                <option value="">-- সকল এজেন্ট পয়েন্ট --</option>
                                @foreach($agentPoints as $pt)
                                    <option value="{{ $pt->id }}" {{ request('agent_point_id') == $pt->id ? 'selected' : '' }}>
                                        {{ $pt->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">কার্ড নাম্বার</label>
                            <input type="text" name="card_number" value="{{ request('card_number') }}" class="form-control form-control-sm" placeholder="কার্ড নম্বর দিয়ে খুঁজুন">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">শুরুর তারিখ</label>
                            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">শেষ তারিখ</label>
                            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-2 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="fa fa-search me-1"></i> ফিল্টার</button>
                            <a href="{{ route('admin.distribution.deliveries_history') }}" class="btn btn-light btn-sm border" title="রিসেট"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Deliveries Table -->
            <div class="box shadow-sm">
                <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                    <h5 class="box-title mb-0">মোট বিতরণ রেকর্ড: {{ $deliveries->total() }} টি</h5>
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
                                    <th>বিতরণকৃত পণ্য ও পরিমাণ</th>
                                    <th>তারিখ ও সময়</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center">রশিদ প্রিন্ট</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deliveries as $del)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $del->delivery_no }}</span></td>
                                    <td>
                                        <strong class="text-dark">{{ $del->agentPoint ? $del->agentPoint->name : 'N/A' }}</strong>
                                        <div class="small text-muted">{{ $del->agentPoint && $del->agentPoint->areaModel ? $del->agentPoint->areaModel->name : '' }}</div>
                                    </td>
                                    <td><span class="badge bg-warning text-dark font-monospace">{{ $del->card_number }}</span></td>
                                    <td>{{ $del->member_name }}</td>
                                    <td>
                                        @foreach($del->items as $it)
                                            <span class="badge bg-light text-dark border me-1">{{ $it->product_name }}: {{ $it->quantity }} {{ $it->unit }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <div>{{ $del->delivery_date }}</div>
                                        <small class="text-muted">{{ $del->delivery_time ? date('h:i A', strtotime($del->delivery_time)) : '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $del->status === 'completed' ? 'bg-success' : 'bg-info' }}">
                                            {{ ucfirst($del->status) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('agent.delivery.receipt', $del->id) }}" target="_blank" class="btn btn-xs btn-outline-secondary" title="রশিদ প্রিন্ট">
                                            <i class="fa fa-print"></i> রশিদ
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-muted">কোনো ডেলিভারি হিস্ট্রি পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($deliveries->hasPages())
                <div class="box-footer">
                    {{ $deliveries->links() }}
                </div>
                @endif
            </div>

        </section>
    </div>
</div>
@endsection
