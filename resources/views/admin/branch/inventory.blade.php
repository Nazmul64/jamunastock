@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-cubes text-info me-2"></i> অফিস মালামাল ও সম্পদ (I - Inventory & Assets)
                    </h3>
                    <small class="text-muted">কম্পিউটার, ইলেকট্রনিক্স, আসবাবপত্র ও স্টেশনারি মালামাল রক্ষণাবেক্ষণ</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-info text-white fw-bold rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal" data-toggle="modal" data-target="#addAssetModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন সম্পদ / মালামাল যোগ
                    </button>
                    <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success rounded-pill px-4 py-2 mb-4">
                    <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                </div>
            @endif

            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="box-title fw-bold text-dark mb-0">শাখার মালামাল ও সম্পদ তালিকা</h5>
                    <form method="GET" class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <select name="category" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">-- সকল ক্যাটাগরি --</option>
                            <option value="computer_electronics" {{ request('category') === 'computer_electronics' ? 'selected' : '' }}>কম্পিউটার ও ইলেকট্রনিক্স</option>
                            <option value="furniture" {{ request('category') === 'furniture' ? 'selected' : '' }}>আসবাবপত্র (Furniture)</option>
                            <option value="appliances" {{ request('category') === 'appliances' ? 'selected' : '' }}>যন্ত্রপাতি ও এসি/ফ্যান</option>
                            <option value="stationary" {{ request('category') === 'stationary' ? 'selected' : '' }}>স্টেশনারি ও অফিস সামগ্রী</option>
                        </select>
                    </form>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">সম্পদ কোড</th>
                                    <th>নাম ও বিবরণ</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>পরিমাণ</th>
                                    <th>ক্রয় মূল্য (টাকা)</th>
                                    <th>অবস্থা (Condition)</th>
                                    <th class="pe-4">অবস্থান / রুম</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $ast)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="font-monospace text-primary fw-bold fs-12">{{ $ast->asset_code }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark fs-14">{{ $ast->name }}</div>
                                            <small class="text-muted">{{ $ast->notes ?: 'কোনো অতিরিক্ত নোট নেই' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $ast->category)) }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold fs-13">{{ $ast->quantity }} টি</span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-dark fs-13">৳{{ number_format($ast->purchase_cost, 2) }}</span>
                                        </td>
                                        <td>
                                            @if($ast->condition === 'good')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-11">ভালো (Good)</span>
                                            @elseif($ast->condition === 'needs_repair')
                                                <span class="badge bg-warning-light text-warning rounded-pill px-3 py-1 fw-bold fs-11">মেরামত প্রয়োজন</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-11">ত্রুটিপূর্ণ / বাতিল</span>
                                            @endif
                                        </td>
                                        <td class="pe-4">
                                            <span class="text-muted fs-12">{{ $ast->location_in_office ?: 'প্রধান অফিস রুম' }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            কোনো মালামাল বা সম্পদ রেকর্ড পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($assets->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $assets->total() }} টির মধ্যে {{ $assets->firstItem() }} থেকে {{ $assets->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $assets->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Asset -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-info text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> নতুন মালামাল / সম্পদ যোগ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.inventory.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">সম্পদের নাম (Item Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="যেমন: HP LaserJet Pro Printer" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category" class="form-select rounded-pill" required>
                                <option value="computer_electronics">কম্পিউটার ও ইলেকট্রনিক্স</option>
                                <option value="furniture">আসবাবপত্র (Furniture)</option>
                                <option value="appliances">যন্ত্রপাতি ও অ্যাপ্লায়েন্স</option>
                                <option value="stationary">স্টেশনারি ও অফিস সামগ্রী</option>
                                <option value="others">অন্যান্য সম্পদ</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">পরিমাণ (Quantity) <span class="text-danger">*</span></label>
                            <input type="number" name="quantity" class="form-control rounded-pill" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">ক্রয় মূল্য (টাকা)</label>
                            <input type="number" step="0.01" name="purchase_cost" class="form-control rounded-pill" placeholder="যেমন: 25000">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">বর্তমান অবস্থা (Condition) <span class="text-danger">*</span></label>
                            <select name="condition" class="form-select rounded-pill" required>
                                <option value="good">ভালো (Good)</option>
                                <option value="fair">মোটামুটি (Fair)</option>
                                <option value="needs_repair">মেরামত প্রয়োজন</option>
                                <option value="damaged">ক্ষতিগ্রস্ত (Damaged)</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">অফিসের অবস্থান (Location in Office)</label>
                        <input type="text" name="location_in_office" class="form-control rounded-pill" placeholder="যেমন: ফ্রন্ট ডেস্ক বা অ্যাকাউন্টস রুম">
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">অতিরিক্ত নোট / বিবরণ</label>
                        <textarea name="notes" class="form-control rounded-3" rows="2" placeholder="মডেল নম্বর, সিরিয়াল বা ওয়ারেন্টি তথ্য..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-info text-white rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
