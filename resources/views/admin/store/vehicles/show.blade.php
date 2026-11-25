@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-car text-success me-2"></i> গাড়ির প্রোফাইল ও লগ (Vehicle Profile & Logs)
                    </h3>
                    <p class="text-muted mb-0">{{ $vehicle->vehicle_number }} (রেজিস্ট্রেশন: {{ $vehicle->registration_number }})</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addTripModal">
                        <i class="fa fa-road me-1"></i> নতুন ট্রিপ এন্ট্রি
                    </button>
                    <button type="button" class="btn btn-warning shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#addFuelModal">
                        <i class="fa fa-tint me-1"></i> ফুয়েল / গ্যাস লগ এন্ট্রি
                    </button>
                    <a href="{{ route('admin.store.vehicles.index') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> সকল গাড়ির তালিকা
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

            <!-- Vehicle Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">বর্তমান ওডোমিটার</span>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($vehicle->current_odometer) }} কি.মি.</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-info border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">মোট ট্রিপ অতিক্রান্ত দূরত্ব</span>
                            <h3 class="mb-0 fw-bold text-info">{{ number_format($totalTripKm) }} কি.মি.</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">সর্বমোট ফুয়েল খরচ</span>
                            <h3 class="mb-0 fw-bold text-warning">৳{{ number_format($totalFuelCost, 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">ড্রাইভার ও স্ট্যাটাস</span>
                            <h5 class="mb-0 fw-bold text-success">{{ $vehicle->assigned_driver ?? 'Unassigned' }}</h5>
                            <small class="text-muted">{{ $vehicle->driver_phone ?? '' }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Details Details Box -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-info-circle text-primary me-2"></i> যানবাহনের প্রযুক্তিগত ও আইনি কাগজপত্র
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="text-muted small d-block">যানবাহনের ধরন ও মেক</span>
                            <span class="fw-bold">{{ ucfirst($vehicle->vehicle_type) }} | {{ $vehicle->brand }} ({{ $vehicle->model_year }})</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">জ্বালানির ধরন ও ধারণ ক্ষমতা</span>
                            <span class="fw-bold text-uppercase">{{ $vehicle->fuel_type }} ({{ $vehicle->fuel_capacity }} লিটার)</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">ইঞ্জিন ও চেসিস নং</span>
                            <span class="fw-bold font-monospace">{{ $vehicle->engine_no ?? 'N/A' }} / {{ $vehicle->chassis_no ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted small d-block">বর্তমান স্ট্যাটাস</span>
                            <span class="badge bg-success fs-6">{{ ucfirst($vehicle->status) }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">ফিটনেস মেয়াদোত্তীর্ণ</span>
                            <span class="fw-bold text-dark">{{ $vehicle->fitness_expiry ? \Carbon\Carbon::parse($vehicle->fitness_expiry)->format('d F, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">ট্যাক্স টোকেন মেয়াদ</span>
                            <span class="fw-bold text-dark">{{ $vehicle->tax_token_expiry ? \Carbon\Carbon::parse($vehicle->tax_token_expiry)->format('d F, Y') : 'N/A' }}</span>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small d-block">ইন্স্যুরেন্স / বীমা মেয়াদ</span>
                            <span class="fw-bold text-dark">{{ $vehicle->insurance_expiry ? \Carbon\Carbon::parse($vehicle->insurance_expiry)->format('d F, Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Trip Log -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-dark">
                                <i class="fa fa-road text-primary me-2"></i> সাম্প্রতিক ট্রিপ লগ (Trip Logs)
                            </h5>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addTripModal">+ ট্রিপ</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>তারিখ</th>
                                            <th>গন্তব্য ও উদ্দেশ্য</th>
                                            <th>ওডোমিটার (কি.মি.)</th>
                                            <th>দূরত্ব</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vehicle->trips as $trip)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($trip->trip_date)->format('d M, Y') }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $trip->start_location }} <i class="fa fa-long-arrow-right text-muted"></i> {{ $trip->destination }}</div>
                                                <small class="text-muted">{{ $trip->purpose }} ({{ $trip->passenger_or_cargo }})</small>
                                            </td>
                                            <td>{{ number_format($trip->start_odometer) }} - {{ number_format($trip->end_odometer) }}</td>
                                            <td><span class="badge bg-primary">{{ number_format($trip->distance_km) }} KM</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">কোনো ট্রিপ লগ পাওয়া যায়নি।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Fuel Log -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-bold mb-0 text-dark">
                                <i class="fa fa-tint text-warning me-2"></i> ফুয়েল ও গ্যাস রিফিল লগ (Fuel Logs)
                            </h5>
                            <button class="btn btn-sm btn-warning text-dark" data-bs-toggle="modal" data-bs-target="#addFuelModal">+ ফুয়েল লগ</button>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>তারিখ</th>
                                            <th>পাম্প / স্টেশন</th>
                                            <th>পরিমাণ ও রেট</th>
                                            <th>মোট খরচ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($vehicle->fuelLogs as $fuel)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($fuel->fuel_date)->format('d M, Y') }}</td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $fuel->filling_station ?? 'Fuel Pump' }}</div>
                                                <small class="text-muted">স্লিপ: {{ $fuel->slip_no ?? '—' }} | ওডো: {{ number_format($fuel->odometer_reading) }}</small>
                                            </td>
                                            <td>{{ $fuel->quantity_liters }} লিটার @ ৳{{ number_format($fuel->rate_per_liter, 2) }}</td>
                                            <td class="fw-bold text-danger">৳{{ number_format($fuel->total_cost, 2) }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-3 text-muted">কোনো ফুয়েল লগ রেকর্ড পাওয়া যায়নি।</td>
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

<!-- Modal: Add Trip -->
<div class="modal fade" id="addTripModal" tabindex="-1" aria-labelledby="addTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.vehicles.trips.store') }}" method="POST">
                @csrf
                <input type="hidden" name="erp_vehicle_id" value="{{ $vehicle->id }}">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="addTripModalLabel"><i class="fa fa-road me-2"></i> ট্রিপ লগ এন্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="trip_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ড্রাইভারের নাম</label>
                        <input type="text" name="driver_name" class="form-control" value="{{ $vehicle->assigned_driver }}">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">শুরুর অবস্থান <span class="text-danger">*</span></label>
                            <input type="text" name="start_location" class="form-control" placeholder="যেমন: হেড অফিস" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">গন্তব্য <span class="text-danger">*</span></label>
                            <input type="text" name="destination" class="form-control" placeholder="যেমন: নরসিংদী শাখা" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">শুরুর ওডোমিটার (KM) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="start_odometer" class="form-control" value="{{ $vehicle->current_odometer }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">শেষের ওডোমিটার (KM) <span class="text-danger">*</span></label>
                            <input type="number" step="0.1" name="end_odometer" class="form-control" placeholder="{{ $vehicle->current_odometer + 50 }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ট্রিপের উদ্দেশ্য</label>
                        <input type="text" name="purpose" class="form-control" placeholder="যেমন: কর্মকর্তা পরিদর্শন / মালামাল পরিবহন">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">যাত্রী বা বহনকৃত মালামাল</label>
                        <input type="text" name="passenger_or_cargo" class="form-control" placeholder="যাত্রী / মালামালের বিবরণ">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> ট্রিপ লগ সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Add Fuel -->
<div class="modal fade" id="addFuelModal" tabindex="-1" aria-labelledby="addFuelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.vehicles.fuel.store') }}" method="POST">
                @csrf
                <input type="hidden" name="erp_vehicle_id" value="{{ $vehicle->id }}">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="addFuelModalLabel"><i class="fa fa-tint me-2"></i> ফুয়েল / গ্যাস লগ এন্ট্রি</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">তারিখ <span class="text-danger">*</span></label>
                        <input type="date" name="fuel_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">জ্বালানির ধরন <span class="text-danger">*</span></label>
                            <select name="fuel_type" class="form-select" required>
                                <option value="{{ $vehicle->fuel_type }}">{{ ucfirst($vehicle->fuel_type) }}</option>
                                <option value="octane">Octane</option>
                                <option value="diesel">Diesel</option>
                                <option value="cng">CNG</option>
                                <option value="petrol">Petrol</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">পরিমাণ (লিটার / ঘনমিটার) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity_liters" class="form-control" placeholder="যেমন: 40" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">প্রতি লিটার রেট (৳) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="rate_per_liter" class="form-control" placeholder="যেমন: 130" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ওডোমিটার রিডিং (KM)</label>
                            <input type="number" step="0.1" name="odometer_reading" class="form-control" value="{{ $vehicle->current_odometer }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ফুয়েলিং স্টেশন / পাম্পের নাম</label>
                        <input type="text" name="filling_station" class="form-control" placeholder="যেমন: পদ্মা সিএনজি অ্যান্ড ফিলিং স্টেশন">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ক্যাশ মেমো / মানি রিসিট নং</label>
                        <input type="text" name="slip_no" class="form-control" placeholder="যেমন: SLIP-9871">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning text-dark"><i class="fa fa-save me-1"></i> ফুয়েল লগ সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

