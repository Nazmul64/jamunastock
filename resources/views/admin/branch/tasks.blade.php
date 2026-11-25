@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-tasks text-success me-2"></i> ব্রাঞ্চ পরিচালনা ও টাস্ক শিডিউল (B - Branch Operations)
                    </h3>
                    <small class="text-muted">দৈনিক কাজের শিডিউল, দায়িত্ব বরাদ্দ ও সম্পন্নকরণ ট্র্যাকার</small>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal" data-toggle="modal" data-target="#addTaskModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন টাস্ক যোগ করুন
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
                    <h5 class="box-title fw-bold text-dark mb-0">অপারেশনাল টাস্ক তালিকা</h5>
                    <!-- Filter Controls -->
                    <form method="GET" class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                        <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">-- সকল স্ট্যাটাস --</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>অপেক্ষমাণ (Pending)</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>চলমান (In Progress)</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                        </select>
                        <select name="priority" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                            <option value="">-- সকল অগ্রাধিকার --</option>
                            <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>জরুরি (Urgent)</option>
                            <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>উচ্চ (High)</option>
                            <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>সাধারণ (Medium)</option>
                        </select>
                    </form>
                </div>
                <div class="box-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted fs-12">
                                <tr>
                                    <th class="ps-4" style="width: 60px;">#</th>
                                    <th>টাস্কের শিরোনাম ও বিবরণ</th>
                                    <th>অগ্রাধিকার</th>
                                    <th>দায়িত্বপ্রাপ্ত কর্মী</th>
                                    <th>শেষ সময় (Deadline)</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end pe-4">একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks as $t)
                                    <tr>
                                        <td class="ps-4 fw-bold text-muted">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="fw-bold text-dark fs-14">{{ $t->title }}</div>
                                            <small class="text-muted">{{ $t->description ?: 'কোনো বিবরণ নেই' }}</small>
                                        </td>
                                        <td>
                                            @if($t->priority === 'urgent')
                                                <span class="badge bg-danger text-white rounded-pill px-3 py-1 fw-bold fs-11">জরুরি (Urgent)</span>
                                            @elseif($t->priority === 'high')
                                                <span class="badge bg-warning text-dark rounded-pill px-3 py-1 fw-bold fs-11">উচ্চ (High)</span>
                                            @else
                                                <span class="badge bg-info-light text-info rounded-pill px-3 py-1 fs-11">সাধারণ (Medium)</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($t->assignee)
                                                <div class="fw-semibold text-dark fs-13"><i class="fa fa-user text-muted me-1"></i> {{ $t->assignee->name }}</div>
                                            @else
                                                <span class="text-muted fs-12 italic">বরাদ্দ নেই</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-dark fs-13">{{ $t->due_date ? $t->due_date->format('d M, Y') : 'নির্দিষ্ট নয়' }}</span>
                                        </td>
                                        <td>
                                            @if($t->status === 'completed')
                                                <span class="badge bg-success-light text-success rounded-pill px-3 py-1 fw-bold fs-12">সম্পন্ন</span>
                                            @elseif($t->status === 'in_progress')
                                                <span class="badge bg-warning-light text-warning rounded-pill px-3 py-1 fw-bold fs-12">চলমান</span>
                                            @else
                                                <span class="badge bg-danger-light text-danger rounded-pill px-3 py-1 fw-bold fs-12">অপেক্ষমাণ</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editTaskModal{{ $t->id }}" data-toggle="modal" data-target="#editTaskModal{{ $t->id }}">
                                                <i class="fa fa-pencil me-1"></i> স্ট্যাটাস পরিবর্তন
                                            </button>

                                            <!-- Modal: Update Task Status -->
                                            <div class="modal fade text-start" id="editTaskModal{{ $t->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
                                                        <div class="modal-header bg-primary text-white py-3 px-4">
                                                            <h5 class="modal-title fw-bold mb-0">টাস্ক স্ট্যাটাস আপডেট: {{ $t->title }}</h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
                                                        </div>
                                                        <form action="{{ route('admin.branch.tasks.update', ['id' => $t->id, 'branch_id' => $branch->id]) }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body p-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="fw-bold mb-1">বর্তমান অবস্থা (Status)</label>
                                                                    <select name="status" class="form-select rounded-pill" required>
                                                                        <option value="pending" {{ $t->status === 'pending' ? 'selected' : '' }}>অপেক্ষমাণ (Pending)</option>
                                                                        <option value="in_progress" {{ $t->status === 'in_progress' ? 'selected' : '' }}>চলমান (In Progress)</option>
                                                                        <option value="completed" {{ $t->status === 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                                                        <option value="cancelled" {{ $t->status === 'cancelled' ? 'selected' : '' }}>বাতিল (Cancelled)</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group mb-2">
                                                                    <label class="fw-bold mb-1">কাজ সম্পন্নকরণের মন্তব্য (Completion Notes)</label>
                                                                    <textarea name="completion_notes" class="form-control rounded-3" rows="3" placeholder="কাজের ফলাফল বা অগ্রগতি সম্পর্কে লিখুন...">{{ $t->completion_notes }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light py-3 px-4">
                                                                <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                                                                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">আপডেট সংরক্ষণ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fa fa-info-circle fs-2 text-warning mb-2 d-block"></i>
                                            কোনো অপারেশনাল টাস্ক পাওয়া যায়নি।
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($tasks->hasPages())
                    <div class="box-footer py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2 border-top">
                        <div class="text-muted fs-13">মোট {{ $tasks->total() }} টি টাস্কের মধ্যে {{ $tasks->firstItem() }} থেকে {{ $tasks->lastItem() }} দেখানো হচ্ছে</div>
                        <div>{{ $tasks->links('pagination::bootstrap-5') }}</div>
                    </div>
                @endif
            </div>
        </section>
    </div>
</div>

<!-- Modal: Add New Task -->
<div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-success text-white py-3 px-4">
                <h5 class="modal-title fw-bold mb-0"><i class="fa fa-plus-circle me-2"></i> নতুন অপারেশনাল টাস্ক তৈরি</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.branch.tasks.store', ['branch_id' => $branch->id]) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">টাস্কের শিরোনাম (Title) <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control rounded-pill" placeholder="যেমন: নতুন মাসিক মেম্বারদের তালিকা তৈরি" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <label class="fw-bold mb-1">অগ্রাধিকার (Priority) <span class="text-danger">*</span></label>
                            <select name="priority" class="form-select rounded-pill" required>
                                <option value="medium">সাধারণ (Medium)</option>
                                <option value="high">উচ্চ (High)</option>
                                <option value="urgent">জরুরি (Urgent)</option>
                                <option value="low">কম (Low)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold mb-1">শেষ তারিখ (Deadline)</label>
                            <input type="date" name="due_date" class="form-control rounded-pill">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold mb-1">দায়িত্বপ্রাপ্ত কর্মচারী (Assign To)</label>
                        <select name="assigned_to" class="form-select rounded-pill">
                            <option value="">-- কর্মচারী বাছাই করুন --</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="fw-bold mb-1">কাজের বিবরণ (Description)</label>
                        <textarea name="description" class="form-control rounded-3" rows="3" placeholder="কাজের সুনির্দিষ্ট নির্দেশনা লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light py-3 px-4">
                    <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal" data-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold">টাস্ক সেভ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
