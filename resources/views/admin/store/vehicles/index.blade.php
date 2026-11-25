@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-car text-success me-2"></i> যানবাহন ও পরিবহন ব্যবস্থাপনা (Fleet Management)
                    </h3>
                    <p class="text-muted mb-0">কোম্পানির সকল গাড়ি, ড্রাইভার, ফুয়েল লগ, ট্রিপ রেকর্ড এবং ফিটনেস/ট্যাক্স টোকেন নবায়ন এলার্ট</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addVehicleModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন যানবাহন যুক্ত করুন
                    </button>
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
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">মোট যানবাহন (Fleet)</span>
                                <h3 class="mb-0 fw-bold text-primary">{{ $totalVehicles }} টি</h3>
                            </div>
                            <div class="bg-primary-light rounded-circle p-3 text-primary">
                                <i class="fa fa-truck fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">সক্রিয় যানবাহন</span>
                                <h3 class="mb-0 fw-bold text-success">{{ $activeCount }} টি</h3>
                            </div>
                            <div class="bg-success-light rounded-circle p-3 text-success">
                                <i class="fa fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-info border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">বর্তমানে ট্রিপে আছে</span>
                                <h3 class="mb-0 fw-bold text-info">{{ $inTripCount }} টি</h3>
                            </div>
                            <div class="bg-info-light rounded-circle p-3 text-info">
                                <i class="fa fa-road fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted fw-bold d-block">নবায়ন সতর্কবার্তা (Expiry)</span>
                                <h3 class="mb-0 fw-bold text-warning">
                                    {{ $vehicles->filter(function($v) {
                                        $now = \Carbon\Carbon::now();
                                        $soon = \Carbon\Carbon::now()->addDays(30);
                                        return ($v->fitness_expiry && $v->fitness_expiry <= $soon) ||
                                               ($v->tax_token_expiry && $v->tax_token_expiry <= $soon) ||
                                               ($v->insurance_expiry && $v->insurance_expiry <= $soon);
                                    })->count() }} টি
                                </h3>
                            </div>
                            <div class="bg-warning-light rounded-circle p-3 text-warning">
                                <i class="fa fa-bell fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicles Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> সকল যানবাহনের তালিকা ও স্ট্যাটাস
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>গাড়ির নম্বর ও রেজিস্ট্রেশন</th>
                                    <th>ব্র্যান্ড ও মডেল</th>
                                    <th>ড্রাইভার ও মোবাইল</th>
                                    <th>জ্বালানি ও ওডোমিটার</th>
                                    <th>ফিটনেস মেয়াদ</th>
                                    <th>ট্যাক্স টোকেন</th>
                                    <th>বীমা মেয়াদ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th>একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($vehicles as $veh)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark fs-6">{{ $veh->vehicle_number }}</div>
                                        <small class="text-muted font-monospace">{{ $veh->registration_number }}</small>
                                    </td>
                                    <td>
                                        <div><span class="badge bg-light text-dark border">{{ ucfirst($veh->vehicle_type) }}</span></div>
                                        <div class="small fw-bold text-dark mt-1">{{ $veh->brand }} {{ $veh->model_year ? '('.$veh->model_year.')' : '' }}</div>
                                    </td>
                                    <td>
                                        <div><i class="fa fa-user me-1 text-muted"></i> {{ $veh->assigned_driver ?? '—' }}</div>
                                        <small class="text-muted"><i class="fa fa-phone me-1"></i> {{ $veh->driver_phone ?? '—' }}</small>
                                    </td>
                                    <td>
                                        <div><span class="badge bg-secondary-light text-secondary text-uppercase">{{ $veh->fuel_type }}</span></div>
                                        <small class="text-dark fw-bold mt-1 d-block">{{ number_format($veh->current_odometer) }} কি.মি.</small>
                                    </td>
                                    <td>
                                        @if($veh->fitness_expiry)
                                            @php
                                                $fitDiff = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($veh->fitness_expiry), false);
                                            @endphp
                                            <span class="badge {{ $fitDiff < 0 ? 'bg-danger' : ($fitDiff <= 30 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ \Carbon\Carbon::parse($veh->fitness_expiry)->format('d M, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($veh->tax_token_expiry)
                                            @php
                                                $taxDiff = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($veh->tax_token_expiry), false);
                                            @endphp
                                            <span class="badge {{ $taxDiff < 0 ? 'bg-danger' : ($taxDiff <= 30 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ \Carbon\Carbon::parse($veh->tax_token_expiry)->format('d M, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($veh->insurance_expiry)
                                            @php
                                                $insDiff = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($veh->insurance_expiry), false);
                                            @endphp
                                            <span class="badge {{ $insDiff < 0 ? 'bg-danger' : ($insDiff <= 30 ? 'bg-warning text-dark' : 'bg-success') }}">
                                                {{ \Carbon\Carbon::parse($veh->insurance_expiry)->format('d M, Y') }}
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($veh->status == 'active')
                                            <span class="badge bg-success">সক্রিয় (Active)</span>
                                        @elseif($veh->status == 'in_trip')
                                            <span class="badge bg-info">ট্রিপে আছে (In Trip)</span>
                                        @elseif($veh->status == 'under_maintenance')
                                            <span class="badge bg-warning text-dark">মেরামতে (Maintenance)</span>
                                        @else
                                            <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.store.vehicles.show', $veh->id) }}" class="btn btn-sm btn-outline-primary" title="বিস্তারিত, ট্রিপ ও ফুয়েল লগ">
                                            <i class="fa fa-dashboard me-1"></i> লগ ও ডিটেইলস
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">কোনো যানবাহনের তথ্য পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Vehicle -->
<div class="modal fade" id="addVehicleModal" tabindex="-1" aria-labelledby="addVehicleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.vehicles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addVehicleModalLabel"><i class="fa fa-car me-2"></i> নতুন যানবাহন রেজিস্ট্রেশন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">গাড়ির নম্বর (Name / Identifier) <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_number" class="form-control" placeholder="যেমন: ঢাকা মেট্রো-গ ১২-৩৪৫৬" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">রেজিস্ট্রেশন নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="registration_number" class="form-control" placeholder="যেমন: REG-DM-123456" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">যানবাহনের ধরন <span class="text-danger">*</span></label>
                            <select name="vehicle_type" class="form-select" required>
                                <option value="microbus">Microbus / Noah</option>
                                <option value="car">Sedan Car / Premio</option>
                                <option value="pickup">Pickup / Delivery Van</option>
                                <option value="motorcycle">Motorcycle</option>
                                <option value="truck">Truck / Lorry</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ব্র্যান্ড ও মেক</label>
                            <input type="text" name="brand" class="form-control" placeholder="যেমন: Toyota, Mahindra">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মডেল সাল</label>
                            <input type="text" name="model_year" class="form-control" placeholder="যেমন: 2021">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">জ্বালানির ধরন <span class="text-danger">*</span></label>
                            <select name="fuel_type" class="form-select" required>
                                <option value="octane">Octane (অকটেন)</option>
                                <option value="diesel">Diesel (ডিজেল)</option>
                                <option value="petrol">Petrol (পেট্রোল)</option>
                                <option value="cng">CNG</option>
                                <option value="electric">Electric (বিদ্যুৎ)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ফুয়েল ট্যাংক ক্যাপাসিটি (Ltr)</label>
                            <input type="number" step="0.01" name="fuel_capacity" class="form-control" placeholder="60">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বর্তমান ওডোমিটার রিডিং (KM)</label>
                            <input type="number" step="0.1" name="current_odometer" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">নিযুক্ত ড্রাইভারের নাম</label>
                            <input type="text" name="assigned_driver" class="form-control" placeholder="ড্রাইভারের নাম">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ড্রাইভারের ফোন নম্বর</label>
                            <input type="text" name="driver_phone" class="form-control" placeholder="০১XXXXXXXXX">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ফিটনেস মেয়াদোত্তীর্ণ তারিখ</label>
                            <input type="date" name="fitness_expiry" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ট্যাক্স টোকেন মেয়াদ</label>
                            <input type="date" name="tax_token_expiry" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বীমা / ইন্স্যুরেন্স মেয়াদ</label>
                            <input type="date" name="insurance_expiry" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ইঞ্জিন নম্বর</label>
                            <input type="text" name="engine_no" class="form-control" placeholder="ইঞ্জিন নং">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">চেসিস নম্বর</label>
                            <input type="text" name="chassis_no" class="form-control" placeholder="চেসিস নং">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">গাড়ির ছবি</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">মন্তব্য / নোট</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="নোট"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> গাড়ি সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

