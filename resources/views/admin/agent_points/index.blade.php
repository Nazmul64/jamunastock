@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-map-marker me-2 text-danger"></i> সংগ্রহ এজেন্ট পয়েন্টসমূহ (Agent Points)</h4>
                    <p class="subtitle text-muted mb-0">মাসিক বাজার বিতরণ ও সংগ্রহের জন্য এলাকাভিত্তিক এজেন্ট পয়েন্ট এবং দায়িত্বপ্রাপ্ত এজেন্ট পরিচালনা</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.areas.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa fa-map-o me-1"></i> এলাকা ব্যবস্থাপনা
                    </a>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAgentPointModal">
                        <i class="fa fa-plus me-1"></i> নতুন এজেন্ট পয়েন্ট যুক্ত করুন
                    </button>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-12">

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title">সকল সংগ্রহ এজেন্ট পয়েন্ট তালিকা ({{ count($agent_points) }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>এজেন্ট পয়েন্টের নাম ও কোড</th>
                                            <th>নির্ধারিত এলাকা (Area)</th>
                                            <th>দায়িত্বপ্রাপ্ত এজেন্ট (Agent In-charge)</th>
                                            <th class="text-center">সংযুক্ত কার্ডহোল্ডার</th>
                                            <th>ধারণক্ষমতা</th>
                                            <th>যোগাযোগ</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($agent_points as $key => $point)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <strong class="text-dark fs-15">{{ $point->name }}</strong>
                                                    <div class="font-monospace small text-muted">{{ $point->code ?: ('AP-' . $point->id) }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ $point->areaModel ? $point->areaModel->name : ($point->area ?: 'N/A') }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($point->agentUser)
                                                        <div class="fw-bold text-primary">{{ $point->agentUser->name }}</div>
                                                        <small class="text-muted">{{ $point->agentUser->phone ?: $point->agentUser->email }}</small>
                                                    @else
                                                        <span class="text-danger small"><i class="fa fa-exclamation-triangle"></i> কোনো এজেন্ট নির্ধারিত নেই</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info px-2 py-1 fs-12">{{ $point->card_holders_count ?? 0 }} জন</span>
                                                </td>
                                                <td class="fw-bold text-dark">{{ number_format($point->capacity ?: 1000) }} KG</td>
                                                <td>
                                                    <div>{{ $point->contact_number ?? 'N/A' }}</div>
                                                    <small class="text-muted">{{ Str::limit($point->address, 25) }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $point->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($point->status) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('admin.agent_monitoring.details', $point->id) }}" class="btn btn-xs btn-outline-info me-1" title="বিস্তারিত">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-xs btn-outline-warning me-1" 
                                                            onclick="editAgentPoint({{ json_encode($point) }})" title="এডিট">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <a href="{{ route('admin.agent_points.destroy', $point->id) }}" 
                                                       class="btn btn-xs btn-outline-danger" 
                                                       onclick="return confirm('আপনি কি নিশ্চিত যে এই এজেন্ট পয়েন্টটি মুছে ফেলতে চান?')" title="ডিলিট">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-muted py-4">
                                                    কোনো এজেন্ট পয়েন্ট পাওয়া যায়নি। নতুন যুক্ত করুন।
                                                </td>
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

<!-- Modal: Add / Edit Agent Point -->
<div class="modal fade" id="addAgentPointModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.agent_points.store') }}" method="POST" id="agentPointForm">
                @csrf
                <input type="hidden" name="id" id="agent_point_id">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTitle"><i class="fa fa-map-marker me-2"></i> এজেন্ট পয়েন্ট যুক্ত করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">এজেন্ট পয়েন্টের নাম <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="point_name" required placeholder="যেমন: নওগাঁ সদর সেন্ট্রাল এজেন্ট পয়েন্ট">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">এজেন্ট কোড</label>
                            <input type="text" class="form-control" name="code" id="point_code" placeholder="যেমন: AP-NAO-01">
                            <small class="text-muted">খালি রাখলে স্বয়ংক্রিয় তৈরি হবে</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">নির্ধারিত এলাকা (Area) <span class="text-danger">*</span></label>
                            <select class="form-select" name="area_id" id="point_area_id" required>
                                <option value="">-- এলাকা নির্বাচন করুন --</option>
                                @foreach($areas as $ar)
                                    <option value="{{ $ar->id }}">{{ $ar->name }} ({{ $ar->district }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">দায়িত্বপ্রাপ্ত এজেন্ট ইউজার (Agent User Account)</label>
                        <select class="form-select" name="agent_user_id" id="point_agent_user_id">
                            <option value="">-- এজেন্ট একাউন্ট নির্বাচন করুন (ঐচ্ছিক) --</option>
                            @foreach($agentUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->phone ?: $u->email }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">এই ইউজারের লগইন সরাসরি এই পয়েন্টের পোর্টালে সংযুক্ত হবে</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">যোগাযোগের নাম্বার</label>
                            <input type="text" class="form-control" name="contact_number" id="point_contact" placeholder="যেমন: 01700000000">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">স্টোরেজ ধারণক্ষমতা (KG)</label>
                            <input type="number" class="form-control" name="capacity" id="point_capacity" placeholder="1000">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">ঠিকানা</label>
                        <textarea class="form-control" name="address" id="point_address" rows="2" placeholder="এজেন্ট পয়েন্টের পূর্ণাঙ্গ ঠিকানা..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="point_status" required>
                            <option value="active">Active (সক্রিয়)</option>
                            <option value="inactive">Inactive (নিষ্ক্রিয়)</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa fa-save me-1"></i> সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAgentPoint(point) {
    document.getElementById('modalTitle').innerHTML = '<i class="fa fa-edit me-2"></i> এজেন্ট পয়েন্ট এডিট করুন';
    document.getElementById('agent_point_id').value = point.id;
    document.getElementById('point_name').value = point.name || '';
    document.getElementById('point_code').value = point.code || '';
    document.getElementById('point_area_id').value = point.area_id || '';
    document.getElementById('point_agent_user_id').value = point.agent_user_id || '';
    document.getElementById('point_contact').value = point.contact_number || '';
    document.getElementById('point_capacity').value = point.capacity || 1000;
    document.getElementById('point_address').value = point.address || '';
    document.getElementById('point_status').value = point.status || 'active';
    
    var myModal = new bootstrap.Modal(document.getElementById('addAgentPointModal'));
    myModal.show();
}

document.getElementById('addAgentPointModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('modalTitle').innerHTML = '<i class="fa fa-map-marker me-2"></i> এজেন্ট পয়েন্ট যুক্ত করুন';
    document.getElementById('agentPointForm').reset();
    document.getElementById('agent_point_id').value = '';
});
</script>
@endsection
