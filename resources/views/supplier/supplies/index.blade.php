@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-cubes text-primary me-2"></i> আমার সরবরাহকৃত পণ্যের ইতিহাস
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('supplier.dashboard') }}"><i class="mdi mdi-home-outline"></i> ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">সরবরাহ তালিকা</li>
                    </ol>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('supplier.supplies.create') }}" class="btn btn-success rounded-pill px-3">
                        <i class="fa fa-plus-circle me-1"></i> নতুন সরবরাহ এন্ট্রি
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-3">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Filter Bar -->
            <div class="box border-0 shadow-xs rounded-4 mb-3">
                <div class="box-body p-3">
                    <form method="GET" action="{{ route('supplier.supplies.index') }}" class="row g-2 align-items-end">
                        <div class="col-md-3 col-12">
                            <label class="form-label fs-12 fw-bold">অনুসন্ধান (Supply ID / Invoice / পণ্য)</label>
                            <input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="SUP-2026... বা INV...">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label fs-12 fw-bold">স্ট্যাটাস</label>
                            <select name="status" class="form-select form-select-sm">
                                <option value="">সকল স্ট্যাটাস</option>
                                @foreach(\App\Models\SupplierSupply::STATUS_META as $key => $meta)
                                    <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">শুরু তারিখ</label>
                            <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-2 col-6">
                            <label class="form-label fs-12 fw-bold">শেষ তারিখ</label>
                            <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-2 col-6 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fa fa-filter"></i> ফিল্টার</button>
                            <a href="{{ route('supplier.supplies.index') }}" class="btn btn-sm btn-outline-secondary"><i class="fa fa-refresh"></i></a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="box border-0 shadow-xs rounded-4">
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th class="ps-3">Supply ID</th>
                                    <th>তারিখ ও ইনভয়েস</th>
                                    <th>পণ্য ও পরিমাণ</th>
                                    <th>জমাকৃত মূল্য</th>
                                    <th>অনুমোদিত / রিসিভড</th>
                                    <th>গন্তব্য (Agent & Branch)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($supplies as $sup)
                                    @php $meta = $sup->status_meta; @endphp
                                    <tr>
                                        <td class="ps-3">
                                            <a href="{{ route('supplier.supplies.show', $sup->id) }}" class="fw-bold text-primary text-decoration-none">
                                                {{ $sup->supply_code ?: ('#' . $sup->id) }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="fw-bold">{{ $sup->invoice_no }}</div>
                                            <small class="text-muted">{{ date('d M, Y', strtotime($sup->supply_date)) }}</small>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $sup->product_name }}</div>
                                            <small class="text-muted">{{ floatval($sup->quantity) }} {{ $sup->unit }} @ ৳{{ number_format($sup->rate, 2) }}</small>
                                        </td>
                                        <td class="fw-bold">৳{{ number_format($sup->total_amount, 2) }}</td>
                                        <td>
                                            @if($sup->approved_amount)
                                                <div class="fs-12 text-info">অনুমোদিত: <strong>৳{{ number_format($sup->approved_amount, 2) }}</strong></div>
                                            @endif
                                            @if($sup->actual_received_amount > 0)
                                                <div class="fs-12 text-success">রিসিভড: <strong>৳{{ number_format($sup->actual_received_amount, 2) }}</strong></div>
                                            @elseif(!$sup->approved_amount)
                                                <span class="text-muted fs-12">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($sup->assignedAgent || $sup->assignedBranch)
                                                <div class="fs-12 fw-bold text-dark">{{ $sup->assignedBranch->name ?? 'Branch N/A' }}</div>
                                                <small class="text-muted">এজেন্ট: {{ $sup->assignedAgent->name ?? 'N/A' }}</small>
                                            @else
                                                <span class="text-muted fs-12">অপেক্ষমাণ</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $meta['badge'] }} rounded-pill px-3">
                                                <i class="fa {{ $meta['icon'] }} me-1"></i> {{ $meta['label'] }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <div class="btn-group">
                                                <a href="{{ route('supplier.supplies.show', $sup->id) }}" class="btn btn-xs btn-primary" title="বিস্তারিত ও ডেলিভারি তথ্য">
                                                    <i class="fa fa-eye"></i> বিস্তারিত
                                                </a>
                                                @if($sup->invoice_file)
                                                    <a href="{{ asset($sup->invoice_file) }}" target="_blank" class="btn btn-xs btn-outline-info" title="চালান ফাইল">
                                                        <i class="fa fa-paperclip"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5 text-muted">কোনো সরবরাহ রেকর্ড পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3">
                        {{ $supplies->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
