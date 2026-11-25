@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title"><i class="fa fa-map-o me-2 text-primary"></i> ডাইনামিক এলাকা ব্যবস্থাপনা (Area Management)</h4>
                    <p class="subtitle text-muted mb-0">সিস্টেমের সকল বিভাগ, জেলা ও উপজেলার আওতাধীন বিতরণ এলাকা পরিচালনা</p>
                </div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAreaModal">
                    <i class="fa fa-plus-circle me-1"></i> নতুন এলাকা যোগ করুন
                </button>
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
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="box shadow-sm">
                        <div class="box-header with-border bg-light d-flex justify-content-between align-items-center">
                            <h4 class="box-title mb-0">সকল নিবন্ধিত এলাকা ({{ $areas->total() }})</h4>
                        </div>
                        <div class="box-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>এলাকার কোড</th>
                                            <th>এলাকার নাম</th>
                                            <th>জেলা / উপজেলা</th>
                                            <th>এজেন্ট পয়েন্ট</th>
                                            <th>সংযুক্ত কার্ডহোল্ডার</th>
                                            <th>স্ট্যাটাস</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($areas as $key => $area)
                                        <tr>
                                            <td>{{ $areas->firstItem() + $key }}</td>
                                            <td><span class="badge bg-secondary font-monospace">{{ $area->code }}</span></td>
                                            <td><strong class="text-dark fs-15">{{ $area->name }}</strong></td>
                                            <td>
                                                <div>{{ $area->district ?: 'N/A' }}</div>
                                                <small class="text-muted">{{ $area->upazila ?: '' }}</small>
                                            </td>
                                            <td><span class="badge bg-info">{{ $area->agent_points_count }} পয়েন্ট</span></td>
                                            <td><span class="badge bg-success">{{ $area->card_holders_count }} জন সদস্য</span></td>
                                            <td>
                                                <span class="badge {{ $area->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($area->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-primary me-1" onclick="editArea({{ json_encode($area) }})">
                                                    <i class="fa fa-pencil"></i> এডিট
                                                </button>
                                                <form action="{{ route('admin.areas.destroy', $area->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই এলাকাটি মুছে ফেলতে চান?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fa fa-trash"></i> ডিলিট
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">কোনো এলাকা তৈরি করা হয়নি। "নতুন এলাকা যোগ করুন" বাটনে ক্লিক করুন।</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($areas->hasPages())
                        <div class="box-footer">
                            {{ $areas->links() }}
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Area Modal -->
<div class="modal fade" id="addAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.areas.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-map-marker me-2"></i> নতুন এলাকা যোগ করুন</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">এলাকার নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="যেমন: নওগাঁ সদর, বগুড়া শহর" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">এলাকার কোড (ঐচ্ছিক)</label>
                        <input type="text" name="code" class="form-control" placeholder="যেমন: AREA-NAO-01">
                        <small class="text-muted">খালি রাখলে সিস্টেম স্বয়ংক্রিয়ভাবে কোড তৈরি করবে</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">বিভাগ</label>
                            <input type="text" name="division" class="form-control" placeholder="যেমন: রাজশাহী">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">জেলা</label>
                            <input type="text" name="district" class="form-control" placeholder="যেমন: নওগাঁ">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">উপজেলা / থানা</label>
                            <input type="text" name="upazila" class="form-control" placeholder="যেমন: নওগাঁ সদর">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ইউনিয়ন / ওয়ার্ড</label>
                            <input type="text" name="union_ward" class="form-control" placeholder="যেমন: ১ নং ওয়ার্ড">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">বিস্তারিত ঠিকানা</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="অফিস বা আঞ্চলিক কেন্দ্রের ঠিকানা"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fa fa-save me-1"></i> এলাকা সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Area Modal -->
<div class="modal fade" id="editAreaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="POST" id="editAreaForm">
                @csrf
                @method('PUT')
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title"><i class="fa fa-pencil me-2"></i> এলাকার তথ্য সম্পাদনা করুন</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">এলাকার নাম <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">এলাকার কোড</label>
                        <input type="text" name="code" id="edit_code" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">বিভাগ</label>
                            <input type="text" name="division" id="edit_division" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">জেলা</label>
                            <input type="text" name="district" id="edit_district" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">উপজেলা / থানা</label>
                            <input type="text" name="upazila" id="edit_upazila" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">ইউনিয়ন / ওয়ার্ড</label>
                            <input type="text" name="union_ward" id="edit_union_ward" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">বিস্তারিত ঠিকানা</label>
                        <textarea name="address" id="edit_address" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্ট্যাটাস</label>
                        <select name="status" id="edit_status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                    <button type="submit" class="btn btn-warning fw-bold"><i class="fa fa-save me-1"></i> আপডেট করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editArea(area) {
    document.getElementById('editAreaForm').action = "{{ url('admin/areas') }}/" + area.id;
    document.getElementById('edit_name').value = area.name || '';
    document.getElementById('edit_code').value = area.code || '';
    document.getElementById('edit_division').value = area.division || '';
    document.getElementById('edit_district').value = area.district || '';
    document.getElementById('edit_upazila').value = area.upazila || '';
    document.getElementById('edit_union_ward').value = area.union_ward || '';
    document.getElementById('edit_address').value = area.address || '';
    document.getElementById('edit_status').value = area.status || 'active';
    var editModal = new bootstrap.Modal(document.getElementById('editAreaModal'));
    editModal.show();
}
</script>
@endsection
