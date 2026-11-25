@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fa fa-folder-open text-success me-2"></i>
                    @if($selectedSector)
                        {{ $selectedSector->name_bn }} - বিনিয়োগ তালিকা
                    @else
                        সকল সেক্টরের সার্বিক বিনিয়োগ তালিকা (Enterprise Investments)
                    @endif
                </h3>
                <p class="text-muted small mb-0">অফিসিয়াল চুক্তিনামা, কিস্তি আদায় ও রসিদ ট্র্যাকিং</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.investments.create', $selectedSector ? ['slug' => $selectedSector->slug] : []) }}" 
                   class="btn btn-success fw-semibold rounded-pill px-3 shadow-sm">
                    <i class="fa fa-plus-circle me-1"></i> নতুন বিনিয়োগ
                </a>
            </div>
        </div>

        <!-- Filter Box -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-3">
            <form action="{{ $selectedSector ? route('admin.investments.index', ['slug' => $selectedSector->slug]) : route('admin.investments.index') }}" method="GET">
                <div class="row g-2 align-items-center">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fa fa-search text-muted"></i></span>
                            <input type="text" name="search" class="form-control border-start-0" 
                                   placeholder="বিনিয়োগ কোড, চুক্তি নং বা গ্রাহকের নাম..." value="{{ request('search') }}">
                        </div>
                    </div>
                    @if(!$selectedSector)
                        <div class="col-md-3">
                            <select name="sector_id" class="form-select">
                                <option value="">-- সকল সেক্টর --</option>
                                @foreach($sectors as $sec)
                                    <option value="{{ $sec->id }}" {{ request('sector_id') == $sec->id ? 'selected' : '' }}>
                                        {{ $sec->sector_no }}. {{ $sec->name_bn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">-- সকল স্ট্যাটাস --</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (চলমান)</option>
                            <option value="matured" {{ request('status') == 'matured' ? 'selected' : '' }}>Matured (মেয়াদ উত্তীর্ণ)</option>
                            <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Settled (নিষ্পত্তিকৃত)</option>
                            <option value="early_settled" {{ request('status') == 'early_settled' ? 'selected' : '' }}>Early Settled (মেয়াদপূর্ব উত্তোলন)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill">
                            <i class="fa fa-filter me-1"></i> ফিল্টার
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Investments Table -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>বিনিয়োগ কোড</th>
                                <th>চুক্তি নম্বর</th>
                                <th>গ্রাহক ও ফোন</th>
                                <th>সেক্টর</th>
                                <th>পরিমাণ</th>
                                <th>মেয়াদকাল</th>
                                <th>মুনাফার হার ও প্ল্যান</th>
                                <th>পরিশোধিত লাভ</th>
                                <th>স্ট্যাটাস</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $inv)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.investments.show', $inv->id) }}" class="fw-bold text-primary">
                                            {{ $inv->investment_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $inv->agreement_number }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $inv->customer->name ?? 'N/A' }}</div>
                                        <small class="text-muted">{{ $inv->customer->phone ?? '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $inv->sector->code ?? 'N/A' }}</span>
                                        <div class="small text-muted">{{ Str::limit($inv->sector->name_bn ?? '', 20) }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">৳{{ number_format($inv->amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div>{{ $inv->duration_years }} বছর</div>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($inv->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($inv->maturity_date)->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <div><span class="fw-bold text-primary">{{ $inv->profit_rate }}%</span> ({{ ucfirst($inv->profit_frequency) }})</div>
                                        <small class="text-muted">{{ ucfirst($inv->profit_type) }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-info">৳{{ number_format($inv->paid_profit_total, 2) }}</span>
                                        <div class="small text-warning">বাকি: ৳{{ number_format($inv->profit_due, 2) }}</div>
                                    </td>
                                    <td>
                                        @if($inv->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success">সক্রিয়</span>
                                        @elseif($inv->status === 'early_settled')
                                            <span class="badge bg-danger bg-opacity-10 text-danger">মেয়াদপূর্ব উত্তোলন</span>
                                        @elseif($inv->status === 'settled')
                                            <span class="badge bg-secondary text-white">নিষ্পত্তিকৃত</span>
                                        @else
                                            <span class="badge bg-light text-dark border">{{ ucfirst($inv->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.investments.show', $inv->id) }}" class="btn btn-sm btn-light border rounded-pill px-3">
                                            <i class="fa fa-eye text-primary me-1"></i> বিস্তারিত
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5 text-muted">কোনো বিনিয়োগ রেকর্ড পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($investments->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $investments->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
