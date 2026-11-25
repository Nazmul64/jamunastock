@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-briefcase text-success me-2"></i>ক্যারিয়ার ও চাকরির আবেদন (Career Applications)</h4>
                    <p class="text-muted mb-0">ওয়েবসাইট ক্যারিয়ার ফরম থেকে জমাকৃত সকল আবেদনকারীর তালিকা ও সিভি</p>
                </div>
                @if($counts['pending'] > 0)
                    <div>
                        <span class="badge bg-warning fs-6 px-3 py-2 text-dark font-weight-bold">
                            <i class="fa fa-bell me-1"></i> {{ $counts['pending'] }} টি নতুন পেন্ডিং আবেদন
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <section class="content">

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

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- Filters & Search Header -->
                        <div class="card-header border-bottom d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <a href="{{ route('admin.careers.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    সকল ({{ $counts['total'] }})
                                </a>
                                <a href="{{ route('admin.careers.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
                                    পেন্ডিং ({{ $counts['pending'] }})
                                </a>
                                <a href="{{ route('admin.careers.index', ['status' => 'reviewed']) }}" class="btn btn-sm {{ request('status') == 'reviewed' ? 'btn-info' : 'btn-outline-info' }}">
                                    পর্যালোচিত ({{ $counts['reviewed'] }})
                                </a>
                                <a href="{{ route('admin.careers.index', ['status' => 'shortlisted']) }}" class="btn btn-sm {{ request('status') == 'shortlisted' ? 'btn-success' : 'btn-outline-success' }}">
                                    শর্টলিস্টেড ({{ $counts['shortlisted'] }})
                                </a>
                                <a href="{{ route('admin.careers.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-danger' : 'btn-outline-danger' }}">
                                    রিজেক্টেড ({{ $counts['rejected'] }})
                                </a>
                            </div>

                            <form action="{{ route('admin.careers.index') }}" method="GET" class="d-flex align-items-center" style="max-width: 320px;">
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div class="input-group">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="নাম, পদ, ফোন বা ঠিকানা দিয়ে খুঁজুন...">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Table Body -->
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 50px;">#</th>
                                            <th>প্রার্থী (Candidate)</th>
                                            <th>ফোন ও ইমেইল</th>
                                            <th>পদ (Position)</th>
                                            <th>ঠিকানা / লোকেশন</th>
                                            <th>সিভি (Resume)</th>
                                            <th>স্ট্যাটাস</th>
                                            <th>আবেদনের তারিখ</th>
                                            <th class="text-end" style="min-width: 140px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($applications as $key => $app)
                                            <tr class="{{ $app->status === 'pending' ? 'table-warning' : '' }}">
                                                <td>{{ $applications->firstItem() + $key }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-circle bg-success text-white me-2 text-center d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-weight: bold; min-width: 38px;">
                                                            {{ strtoupper(substr($app->first_name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $app->full_name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div><i class="fa fa-phone text-success me-1"></i> <a href="tel:{{ $app->phone }}" class="text-decoration-none fw-bold">{{ $app->phone }}</a></div>
                                                    @if($app->email)
                                                        <div class="text-muted small"><i class="fa fa-envelope text-info me-1"></i> {{ $app->email }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border px-2 py-1">
                                                        <i class="fa fa-briefcase text-secondary me-1"></i> {{ $app->position ?? 'General' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 180px;" title="{{ $app->address }}">
                                                        <i class="fa fa-map-marker text-danger me-1"></i> {{ $app->address }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.careers.download', $app->id) }}" class="btn btn-sm btn-outline-success" title="সিভি ডাউনলোড করুন">
                                                        <i class="fa fa-download me-1"></i> সিভি ডাউনলোড
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($app->status === 'pending')
                                                        <span class="badge bg-warning text-dark"><i class="fa fa-clock-o me-1"></i> পেন্ডিং</span>
                                                    @elseif($app->status === 'reviewed')
                                                        <span class="badge bg-info"><i class="fa fa-eye me-1"></i> পর্যালোচিত</span>
                                                    @elseif($app->status === 'shortlisted')
                                                        <span class="badge bg-success"><i class="fa fa-check me-1"></i> শর্টলিস্টেড</span>
                                                    @elseif($app->status === 'rejected')
                                                        <span class="badge bg-danger"><i class="fa fa-times me-1"></i> রিজেক্টেড</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-muted small">{{ $app->created_at->format('d M, Y') }}</span>
                                                    <div class="text-muted small">{{ $app->created_at->format('h:i A') }}</div>
                                                </td>
                                                <td class="text-end">
                                                    <button type="button" class="btn btn-sm btn-info text-white" onclick="viewCareerModal({{ $app->id }})" title="বিস্তারিত দেখুন">
                                                        <i class="fa fa-eye"></i> বিস্তারিত
                                                    </button>
                                                    <a href="{{ route('admin.careers.destroy', $app->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই আবেদনটি মুছে ফেলতে চান?');" title="মুছে ফেলুন">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center py-5 text-muted">
                                                    <i class="fa fa-folder-open-o fs-1 d-block mb-3 text-secondary"></i>
                                                    কোনো আবেদন পাওয়া যায়নি।
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination Footer -->
                        @if($applications->hasPages())
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    মোট {{ $applications->total() }} টির মধ্যে {{ $applications->firstItem() }} থেকে {{ $applications->lastItem() }} টি দেখানো হচ্ছে
                                </div>
                                <div>
                                    {{ $applications->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </section>
    </div>
</div>

<!-- Modal for Viewing & Updating Application -->
<div class="modal fade" id="careerDetailModal" tabindex="-1" aria-labelledby="careerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white" id="careerDetailModalLabel"><i class="fa fa-id-card-o me-2"></i>আবেদনকারীর বিস্তারিত তথ্য</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalLoading" class="text-center py-5">
                    <div class="spinner-border text-success" role="status">
                        <span class="visually-hidden">লোড হচ্ছে...</span>
                    </div>
                    <p class="mt-2 text-muted">তথ্য লোড করা হচ্ছে...</p>
                </div>

                <div id="modalContent" style="display: none;">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="text-muted small d-block">প্রার্থীর নাম (Candidate Name)</label>
                            <h5 id="modalCandidateName" class="fw-bold text-dark mb-0">-</h5>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">আবেদনকৃত পদ (Position)</label>
                            <h6 id="modalPosition" class="fw-bold text-success mb-0">-</h6>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">ফোন নম্বর (Phone)</label>
                            <p id="modalPhone" class="fw-bold mb-0">-</p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small d-block">ইমেইল (Email)</label>
                            <p id="modalEmail" class="mb-0">-</p>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block">বর্তমান ঠিকানা ও লোকেশন (Location / Address)</label>
                            <div id="modalAddress" class="p-3 bg-light rounded border text-dark">-</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block">কভার লেটার / আবেদনকারীর মন্তব্য (Cover Letter / Notes)</label>
                            <div id="modalNotes" class="p-3 bg-light rounded border text-dark" style="white-space: pre-line;">-</div>
                        </div>
                        <div class="col-12">
                            <label class="text-muted small d-block">আপলোডকৃত সিভি ফাইল (Attached Resume)</label>
                            <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded border">
                                <div>
                                    <i class="fa fa-file-pdf-o text-danger fs-4 me-2"></i>
                                    <span id="modalOriginalFilename" class="fw-bold">-</span>
                                </div>
                                <div>
                                    <a id="modalDownloadBtn" href="#" class="btn btn-sm btn-success" target="_blank">
                                        <i class="fa fa-download me-1"></i> ডাউনলোড করুন
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Status Update Form -->
                    <form id="updateStatusForm" onsubmit="submitStatusUpdate(event)">
                        @csrf
                        <input type="hidden" id="modalApplicationId" name="application_id" value="">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="modalStatusSelect" class="form-label fw-bold">আবেদনের স্ট্যাটাস পরিবর্তন করুন</label>
                                <select id="modalStatusSelect" name="status" class="form-select">
                                    <option value="pending">পেন্ডিং (Pending)</option>
                                    <option value="reviewed">পর্যালোচিত (Reviewed)</option>
                                    <option value="shortlisted">শর্টলিস্টেড (Shortlisted)</option>
                                    <option value="rejected">রিজেক্টেড (Rejected)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="modalAdminNotes" class="form-label fw-bold">এডমিন নোট / মন্তব্য (Admin Notes)</label>
                                <input type="text" id="modalAdminNotes" name="admin_notes" class="form-control" placeholder="যেমন: ইন্টারভিউয়ের জন্য ডাকা হয়েছে">
                            </div>
                            <div class="col-12 text-end mt-3">
                                <button type="submit" id="modalUpdateBtn" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> স্ট্যাটাস সংরক্ষণ করুন
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function viewCareerModal(id) {
    const modalEl = document.getElementById('careerDetailModal');
    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();

    document.getElementById('modalLoading').style.display = 'block';
    document.getElementById('modalContent').style.display = 'none';

    fetch("{{ url('admin/careers/show') }}/" + id)
        .then(res => res.json())
        .then(response => {
            if(response.status === 'success') {
                const data = response.data;
                document.getElementById('modalApplicationId').value = data.id;
                document.getElementById('modalCandidateName').innerText = data.full_name;
                document.getElementById('modalPosition').innerText = data.position;
                document.getElementById('modalPhone').innerHTML = '<a href="tel:' + data.phone + '" class="text-decoration-none text-success"><i class="fa fa-phone me-1"></i>' + data.phone + '</a>';
                document.getElementById('modalEmail').innerHTML = data.email !== 'N/A' ? '<a href="mailto:' + data.email + '" class="text-decoration-none text-info"><i class="fa fa-envelope me-1"></i>' + data.email + '</a>' : '<span class="text-muted">প্রদান করা হয়নি</span>';
                document.getElementById('modalAddress').innerText = data.address;
                document.getElementById('modalNotes').innerText = data.notes;
                document.getElementById('modalOriginalFilename').innerText = data.original_filename;
                document.getElementById('modalDownloadBtn').href = "{{ url('admin/careers/download') }}/" + data.id;
                
                document.getElementById('modalStatusSelect').value = data.status;
                document.getElementById('modalAdminNotes').value = data.admin_notes;

                document.getElementById('modalLoading').style.display = 'none';
                document.getElementById('modalContent').style.display = 'block';
            }
        })
        .catch(err => {
            alert('তথ্য লোড করতে সমস্যা হয়েছে।');
            bsModal.hide();
        });
}

function submitStatusUpdate(e) {
    e.preventDefault();
    const id = document.getElementById('modalApplicationId').value;
    const status = document.getElementById('modalStatusSelect').value;
    const adminNotes = document.getElementById('modalAdminNotes').value;
    const btn = document.getElementById('modalUpdateBtn');

    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-1"></i> আপডেট হচ্ছে...';

    fetch("{{ url('admin/careers/update-status') }}/" + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            status: status,
            admin_notes: adminNotes
        })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-save me-1"></i> স্ট্যাটাস সংরক্ষণ করুন';
        if(data.status === 'success') {
            alert(data.message);
            location.reload();
        } else {
            alert('ত্রুটি দেখা দিয়েছে।');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-save me-1"></i> স্ট্যাটাস সংরক্ষণ করুন';
        alert('ত্রুটি দেখা দিয়েছে। আবার চেষ্টা করুন।');
    });
}
</script>
@endsection

