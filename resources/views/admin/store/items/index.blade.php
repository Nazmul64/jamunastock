@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-boxes text-success me-2"></i> পণ্য ও মালামাল তালিকা (Items & Inventory Master)
                    </h3>
                    <p class="text-muted mb-0">স্টেশনারি, আইটি পণ্য, ইলেকট্রনিক্স, পরিচ্ছন্নতা সামগ্রী ও অন্যান্য চলমান পণ্যের কেন্দ্রীয় স্টক</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.store.items.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন পণ্য যুক্ত করুন
                    </a>
                    <a href="{{ route('admin.store.items.movements') }}" class="btn btn-outline-info shadow-sm">
                        <i class="fa fa-exchange me-1"></i> স্টক ইন / আউট লেজার
                    </a>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
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

            <!-- KPI Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">সর্বমোট নথিবদ্ধ পণ্য</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $totalItems }} টি</h3>
                            </div>
                            <div class="bg-primary-light rounded-circle p-3 text-primary">
                                <i class="fa fa-cubes fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">বর্তমান স্টক মূল্যায়ন</span>
                                <h3 class="mb-0 fw-bold text-success">৳{{ number_format($totalStockValue, 2) }}</h3>
                            </div>
                            <div class="bg-success-light rounded-circle p-3 text-success">
                                <i class="fa fa-money fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">লো-স্টক সতর্কবার্তা (Reorder)</span>
                                <h3 class="mb-0 fw-bold text-warning">{{ $lowStockCount }} টি</h3>
                            </div>
                            <div class="bg-warning-light rounded-circle p-3 text-warning">
                                <i class="fa fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.store.items.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold">সার্চ (নাম / কোড / বারকোড)</label>
                            <input type="text" name="search" class="form-control" placeholder="আইটেমের নাম, কোড..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">ক্যাটেগরি ফিল্টার</label>
                            <select name="category" class="form-select">
                                <option value="">-- সকল ক্যাটেগরি --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }} ({{ $cat->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-1"></i> ফিল্টার
                            </button>
                            <a href="{{ route('admin.store.items.index') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-refresh"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Items Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> সকল পণ্য ও মালামালের বর্তমান স্টক তালিকা
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>পণ্য কোড</th>
                                    <th>পণ্যের নাম ও বিবরণ</th>
                                    <th>ক্যাটেগরি</th>
                                    <th>স্টোর ও লোকেশন</th>
                                    <th>বর্তমান স্টক</th>
                                    <th>গড় ক্রয়মূল্য</th>
                                    <th>মোট মূল্য</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary font-monospace">{{ $item->item_code }}</span>
                                        @if($item->barcode)
                                            <br><small class="text-muted"><i class="fa fa-barcode"></i> {{ $item->barcode }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $item->name }}</div>
                                        @if($item->brand || $item->model_no)
                                            <small class="text-muted">{{ $item->brand }} {{ $item->model_no ? '('.$item->model_no.')' : '' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fa {{ $item->category->icon ?? 'fa-cube' }} me-1 text-primary"></i>
                                            {{ $item->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div><i class="fa fa-building-o text-muted me-1"></i> {{ $item->store->name ?? 'Default Store' }}</div>
                                        @if($item->location)
                                            <small class="text-muted"><i class="fa fa-map-marker text-danger me-1"></i> {{ $item->location->rack_number ?? $item->location->location_code }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <h5 class="mb-0 fw-bold {{ $item->current_stock <= $item->reorder_level ? 'text-danger' : 'text-success' }}">
                                            {{ $item->current_stock }} {{ $item->unit }}
                                        </h5>
                                        <small class="text-muted">রি-অর্ডার লেভেল: {{ $item->reorder_level }}</small>
                                    </td>
                                    <td>৳{{ number_format($item->avg_purchase_price ?? $item->standard_cost, 2) }}</td>
                                    <td class="fw-bold text-dark">
                                        ৳{{ number_format($item->current_stock * ($item->avg_purchase_price ?? $item->standard_cost), 2) }}
                                    </td>
                                    <td>
                                        @if($item->current_stock <= 0)
                                            <span class="badge bg-danger">স্টক আউট (Out)</span>
                                        @elseif($item->current_stock <= $item->reorder_level)
                                            <span class="badge bg-warning text-dark">লো স্টক (Low)</span>
                                        @else
                                            <span class="badge bg-success">পর্যাপ্ত (Available)</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.store.items.movements', ['item_id' => $item->id]) }}" class="btn btn-outline-info" title="লেজার দেখুন">
                                                <i class="fa fa-history"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">কোনো পণ্য পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if(method_exists($items, 'links'))
                    <div class="card-footer bg-white">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>
@endsection

