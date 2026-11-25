@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1">
                        <i class="fa fa-line-chart text-primary me-2"></i> Master Supply, Stock Movements & Accounting Dashboard
                    </h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item active">Master Accounting</li>
                    </ol>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.suppliers.payments.index') }}" class="btn btn-sm btn-success rounded-pill px-3">Supplier Payments</a>
                    <a href="{{ route('admin.suppliers.customer_distributions') }}" class="btn btn-sm btn-primary rounded-pill px-3">Customer Distributions</a>
                </div>
            </div>
        </div>

        <section class="content">
            <!-- Master KPIs -->
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">Total Supplier Payable</small>
                        <h3 class="fw-bold text-primary mb-0 mt-1">৳{{ number_format($totalSupplierPayable, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">Total Supplier Paid</small>
                        <h3 class="fw-bold text-success mb-0 mt-1">৳{{ number_format($totalSupplierPaid, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">Supplier Unpaid Due</small>
                        <h3 class="fw-bold text-danger mb-0 mt-1">৳{{ number_format($totalSupplierDue, 2) }}</h3>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="bg-white border rounded-4 p-3 shadow-xs">
                        <small class="text-muted fw-bold">Total Customer Distribution</small>
                        <h3 class="fw-bold text-info mb-0 mt-1">{{ number_format($totalDistributionQty, 2) }}</h3>
                        <small class="text-muted">৳{{ number_format($totalDistributionAmount, 2) }}</small>
                    </div>
                </div>
            </div>

            <!-- Agent Current Stock Summary -->
            <div class="bg-white border rounded-4 shadow-xs overflow-hidden mb-4">
                <div class="py-3 px-4 bg-light border-bottom">
                    <h5 class="mb-0 fw-bold"><i class="fa fa-cubes text-success me-2"></i> এজেন্টভিত্তিক বর্তমান স্টক (Agent Inventory Balances)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">এজেন্ট</th>
                                <th>পণ্য</th>
                                <th class="text-end">মোট Received</th>
                                <th class="text-end">মোট Distributed</th>
                                <th class="text-end pe-3">অবশিষ্ট Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($agentStocks as $as)
                            <tr>
                                <td class="ps-3 fw-bold">{{ $as->agent->name ?? ('Agent #' . $as->agent_id) }}</td>
                                <td>{{ $as->stock_name }}</td>
                                <td class="text-end text-info fw-bold">{{ floatval($as->allocated_quantity) }}</td>
                                <td class="text-end text-warning fw-bold">{{ floatval($as->sold_quantity) }}</td>
                                <td class="text-end pe-3 text-success fw-bold">{{ floatval(max(0, $as->allocated_quantity - $as->sold_quantity)) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted">কোনো এজেন্ট স্টক পাওয়া যায়নি।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Stock Movements Ledger -->
            <div class="bg-white border rounded-4 shadow-xs overflow-hidden">
                <div class="py-3 px-4 bg-light border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold"><i class="fa fa-exchange text-primary me-2"></i> Stock Movements Ledger (In / Out History)</h5>
                    <form method="GET" action="{{ route('admin.suppliers.master_dashboard') }}" class="d-flex gap-2">
                        <input type="text" name="product" class="form-control form-control-sm" value="{{ request('product') }}" placeholder="পণ্যের নাম...">
                        <select name="movement_type" class="form-select form-select-sm">
                            <option value="">সকল ধরন</option>
                            <option value="supplier_received" {{ request('movement_type') === 'supplier_received' ? 'selected' : '' }}>Supplier Received (+)</option>
                            <option value="customer_distribution" {{ request('movement_type') === 'customer_distribution' ? 'selected' : '' }}>Customer Distribution (-)</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">ফিল্টার</button>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-3">তারিখ</th>
                                <th>এজেন্ট</th>
                                <th>পণ্য</th>
                                <th>ধরন</th>
                                <th class="text-end">পরিমাণ</th>
                                <th class="text-end">অবশিষ্ট ব্যালেন্স</th>
                                <th class="pe-3">রেফারেন্স</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($movements as $mv)
                            <tr>
                                <td class="ps-3">{{ $mv->movement_date }}</td>
                                <td>{{ $mv->agent->name ?? ('Agent #' . $mv->agent_id) }}</td>
                                <td class="fw-bold">{{ $mv->product_name }}</td>
                                <td>
                                    <span class="badge bg-{{ $mv->direction === 'in' ? 'success' : 'warning text-dark' }}">
                                        {{ $mv->movement_type }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-{{ $mv->direction === 'in' ? 'success' : 'danger' }}">
                                    {{ $mv->direction === 'in' ? '+' : '-' }}{{ floatval($mv->quantity) }} {{ $mv->unit }}
                                </td>
                                <td class="text-end fw-bold">{{ floatval($mv->balance_after) }}</td>
                                <td class="pe-3 small text-muted">{{ $mv->reference_code }} • {{ $mv->remarks }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center py-4 text-muted">কোনো Stock Movement রেকর্ড নেই।</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $movements->links() }}</div>
            </div>
        </section>
    </div>
</div>
@endsection
