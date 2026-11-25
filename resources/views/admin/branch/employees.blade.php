@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-users text-primary me-2"></i> কর্মচারী ও দায়িত্ব বণ্টন (E/J - Employee & Job Responsibilities)
                    </h3>
                    <small class="text-muted">ব্রাঞ্চের অধীনস্থ কর্মকর্তা-কর্মচারী তালিকা, পদবি ও দায়িত্ব অর্পণ</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addEmployeeModal" data-toggle="modal" data-target="#addEmployeeModal">
                        <i class="fa fa-user-plus me-1"></i> নতুন কর্মচারী যুক্ত করুন
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
                <div class="box-header py-3 px-4 bg-light border-bottom">
                    <h5 class="box-title fw-bold text-dark mb-0">{{ $branch->name }} — কর্মকর্তা ও কর্মচারী তালিকা</h5>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4">কর্মচারী</th>
                                    <th>লগইন ইমেইল ও ফোন</th>
                                    <th>পদবি ও রোল</th>
                                    <th>অর্পিত দায়িত্ব (Job Responsibility)</th>
                                    <th>যোগদানের তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $emp)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar-sm rounded-circle bg-primary-light text-primary d-flex align-items-center justify-content-center fw-bold fs-14" style="width: 38px; height: 38px;">
                                                    {{ strtoupper(substr($emp->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark fs-14">{{ $emp->name }}</div>
                                                    <small class="text-muted">আইডি: #{{ $emp->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-dark fs-13"><i class="fa fa-envelope text-muted me-1"></i> {{ $emp->email }}</div>
                                            @if($emp->phone)
                                                <div class="text-muted fs-12"><i class="fa fa-phone text-muted me-1"></i> {{ $emp->phone }}</div>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-primary-light text-primary rounded-pill px-3 py-1 fw-bold fs-11">
                                                {{ ucfirst($emp->role) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-dark fs-12">{{ $emp->remarks ?: 'সাধারণ ডেস্ক দায়িত্ব' }}</div>
                                        </td>
                                        <td>
                                            <span class="text-muted fs-12">{{ $emp->joining_date ? date('d M, Y', strtotime($emp->joining_date)) : $emp->created_at->format('d M, Y') }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-success-light text-success rounded-pill px-2 py-1 fs-11">সক্রিয়</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            এই ব্রাঞ্চে এখনো কোনো অতিরিক্ত কর্মচারী যুক্ত করা হয়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($employees->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $employees->total() }} জনের মধ্যে {{ $employees->firstItem() }} থেকে {{ $employees->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $employees->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add Employee -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-primary text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-user-plus me-2"></i> নতুন কর্মকর্তা/কর্মচারী নিবন্ধন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.employees.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">পুরো নাম (Full Name) <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control rounded-pill" placeholder="যেমন: মো: কামরুল ইসলাম" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">লগইন ইমেইল <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control rounded-pill" placeholder="kamrul@jamunastock.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">মোবাইল ফোন</label>
                            <input type="text" name="phone" class="form-control rounded-pill" placeholder="017xxxxxxxx">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">রোল / পদবি <span class="text-danger">*</span></label>
                            <select name="role_slug" class="form-select rounded-pill" required>
                                <option value="employee">কর্মকর্তা (Employee)</option>
                                <option value="staff">স্টাফ (Staff)</option>
                                <option value="manager">সহকারী ম্যানেজার</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">লগইন পাসওয়ার্ড <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control rounded-pill" placeholder="ন্যূনতম ৬ অক্ষর" minlength="6" required>
                        </div>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">অর্পিত দায়িত্ব ও কর্মবিবরণী (Job Responsibility - J)</label>
                        <textarea name="remarks" class="form-control rounded-3" rows="3" placeholder="এই কর্মচারীর দৈনিক দায়িত্ব, কাউন্টার ডিউটি বা কর্মক্ষেত্রের বিবরণ লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
