@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-archive text-primary me-2"></i> কোম্পানির মালামাল ও সম্পদ তালিকা (All Store Assets)
                    </h3>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের আসবাবপত্র, গাড়ি, খাতাপত্র-স্টেশনারি, ইলেকট্রনিক্স ও স্টোর সামগ্রীর হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.store.assets.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fa fa-plus-circle me-1"></i> নতুন সম্পদ / মালামাল যুক্ত করুন
                    </a>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fa fa-dashboard me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Filter & Search Bar -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <form method="GET" action="{{ route('admin.store.assets.index') }}" class="row g-2 align-items-center">
                        <div class="col-lg-3 col-md-6 col-12">
                            <input type="text" name="search" class="form-control" placeholder="নাম, কোড, মডেল বা গাড়ি নং..." value="{{ request('search') }}">
                        </div>
                        <div class="col-lg-3 col-md-3 col-6">
                            <select name="category" class="form-select">
                                <option value="">সকল ক্যাটাগরি</option>
                                <option value="furniture" {{ request('category') == 'furniture' ? 'selected' : '' }}>আসবাবপত্র (Furniture)</option>
                                <option value="vehicle" {{ request('category') == 'vehicle' ? 'selected' : '' }}>গাড়ি / যানবাহন (Vehicle)</option>
                                <option value="stationery" {{ request('category') == 'stationery' ? 'selected' : '' }}>খাতাপত্র / স্টেশনারি (Stationery)</option>
                                <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>ইলেকট্রনিক্স ও আইটি (Electronics)</option>
                                <option value="equipment" {{ request('category') == 'equipment' ? 'selected' : '' }}>যন্ত্রপাতি ও টুলস (Equipment)</option>
                                <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>অন্যান্য (Other)</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="condition" class="form-select">
                                <option value="">সকল অবস্থা</option>
                                <option value="new" {{ request('condition') == 'new' ? 'selected' : '' }}>নতুন (New)</option>
                                <option value="good" {{ request('condition') == 'good' ? 'selected' : '' }}>ভালো (Good)</option>
                                <option value="fair" {{ request('condition') == 'fair' ? 'selected' : '' }}>মোটামুটি (Fair)</option>
                                <option value="needs_repair" {{ request('condition') == 'needs_repair' ? 'selected' : '' }}>মেরামত প্রয়োজন</option>
                                <option value="damaged" {{ request('condition') == 'damaged' ? 'selected' : '' }}>অকেজো (Damaged)</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6">
                            <select name="status" class="form-select">
                                <option value="">সকল স্ট্যাটাস</option>
                                <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>ব্যবহারাধীন (In Use)</option>
                                <option value="in_store" {{ request('status') == 'in_store' ? 'selected' : '' }}>স্টোরে সংরক্ষিত (In Store)</option>
                                <option value="under_maintenance" {{ request('status') == 'under_maintenance' ? 'selected' : '' }}>মেরামত চলছে</option>
                                <option value="scrapped" {{ request('status') == 'scrapped' ? 'selected' : '' }}>স্ক্র্যাপ (Scrapped)</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-3 col-6 d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100"><i class="fa fa-filter me-1"></i> ফিল্টার</button>
                            @if(request()->anyFilled(['search', 'category', 'condition', 'status']))
                                <a href="{{ route('admin.store.assets.index') }}" class="btn btn-outline-secondary" title="রিসেট"><i class="fa fa-refresh"></i></a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assets Table -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 70px;">ছবি</th>
                                    <th>কোড ও আইটেমের নাম</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>পরিমাণ</th>
                                    <th>বর্তমান মূল্য (৳)</th>
                                    <th>অবস্থান / দায়িত্বপ্রাপ্ত</th>
                                    <th>অবস্থা</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-center" style="width: 170px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($assets as $asset)
                                    <tr>
                                        <td>
                                            @if($asset->image && file_exists(public_path('upload/store_assets/' . $asset->image)))
                                                <img src="{{ asset('upload/store_assets/' . $asset->image) }}" alt="Asset" class="rounded shadow-sm" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px;">
                                                    <i class="fa {{ $asset->category == 'vehicle' ? 'fa-car' : ($asset->category == 'furniture' ? 'fa-chair' : ($asset->category == 'stationery' ? 'fa-book' : 'fa-cube')) }} fa-lg"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.store.assets.show', $asset->id) }}" class="fw-bold text-dark text-decoration-none">
                                                <span class="badge bg-secondary me-1">{{ $asset->asset_code }}</span>
                                                {{ $asset->name }}
                                            </a>
                                            @if($asset->model_no || $asset->vehicle_reg_no)
                                                <small class="d-block text-muted">
                                                    {{ $asset->model_no ? 'মডেল: ' . $asset->model_no : '' }}
                                                    {{ $asset->vehicle_reg_no ? ' | রেজি: ' . $asset->vehicle_reg_no : '' }}
                                                </small>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $asset->category_label }}</span></td>
                                        <td><strong>{{ floatval($asset->quantity) }}</strong> <small class="text-muted">{{ $asset->unit }}</small></td>
                                        <td class="fw-bold text-success">
                                            ৳{{ number_format($asset->current_value, 2) }}
                                            @if($asset->purchase_cost > 0)
                                                <small class="d-block text-muted" style="font-size: 11px;">ক্রয়: ৳{{ number_format($asset->purchase_cost, 2) }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="d-block text-dark fw-bold">{{ $asset->assigned_to ?: 'নির্দিষ্ট ব্যক্তি নেই' }}</span>
                                            <small class="text-muted"><i class="fa fa-map-marker me-1"></i>{{ $asset->location ?: 'প্রধান কার্যালয়' }}</small>
                                        </td>
                                        <td>{!! $asset->condition_badge !!}</td>
                                        <td>{!! $asset->status_badge !!}</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.store.assets.show', $asset->id) }}" class="btn btn-outline-info" title="বিস্তারিত দেখুন">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.store.assets.edit', $asset->id) }}" class="btn btn-outline-primary" title="এডিট করুন">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.store.assets.destroy', $asset->id) }}" class="btn btn-outline-danger" onclick="return confirm('আপনি কি নিশ্চিত যে এই সম্পদ রেকর্ডটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5 text-muted">
                                            <i class="fa fa-archive fa-3x mb-3 text-secondary d-block"></i>
                                            কোনো সম্পদ বা মালামালের রেকর্ড পাওয়া যায়নি।
                                            <div class="mt-2">
                                                <a href="{{ route('admin.store.assets.create') }}" class="btn btn-sm btn-primary">
                                                    <i class="fa fa-plus me-1"></i> নতুন মালামাল যুক্ত করুন
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($assets->hasPages())
                    <div class="card-footer bg-white py-3">
                        {{ $assets->links() }}
                    </div>
                @endif
            </div>

        </section>
    </div>
</div>
@endsection

