@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-home text-success me-2"></i> প্রপার্টি ও জমি ব্যবস্থাপনা (P - Property & Real Estate)
                    </h3>
                    <small class="text-muted">অনুমোদিত জমি ও আবাসন প্রজেক্ট, প্লট বুকিং ও রেফারেল কমিশন</small>
                </div>
                <a href="{{ route('admin.branch.dashboard', ['branch_id' => $branch->id]) }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                </a>
            </div>
        </div>

        <section class="content">
            <div class="box border-0 shadow-xs rounded-4 mb-4">
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">অনুমোদিত আবাসন ও প্রপার্টি প্রজেক্ট তালিকা</h5>
                </div>
                <div class="box-body p-4">
                    <div class="row g-4">
                        @forelse($properties as $prop)
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="card h-100 border rounded-4 overflow-hidden shadow-xs">
                                    <div class="position-relative">
                                        <img src="{{ $prop->image_url ?? asset('assets/images/property_default.jpg') }}" class="card-img-top" alt="{{ $prop->title }}" style="height: 180px; object-fit: cover;">
                                        <span class="badge bg-success position-absolute top-0 start-0 m-2 rounded-pill px-3 py-1 fw-bold fs-11">বিক্রয়ের জন্য উন্মুক্ত</span>
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="fw-bold text-dark mb-1 fs-15">{{ $prop->title }}</h5>
                                        <p class="text-muted fs-12 mb-2"><i class="fa fa-map-marker text-danger me-1"></i> {{ $prop->location ?: 'বাংলাদেশ' }}</p>
                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                            <span class="fw-bold text-success fs-16">৳{{ number_format($prop->price ?: 0, 2) }}</span>
                                            <span class="badge bg-warning text-dark fw-bold">কমিশন: ২.৫%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-5 text-muted">
                                <i class="fa fa-home fs-2 text-warning mb-2 d-block"></i>
                                কোনো প্রপার্টি তালিকা পাওয়া যায়নি।
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
