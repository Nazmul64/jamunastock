@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-users text-success me-2"></i>পরিচালনা পর্ষদ ও কর্মকর্তা ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">প্রতিষ্ঠানের শীর্ষ পরিচালনা পর্ষদ ও কর্মকর্তাদের তালিকা ও প্রোফাইল</p>
                </div>
                <div>
                    <a href="{{ route('admin.corporate.board.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-plus me-1"></i>নতুন সদস্য যুক্ত করুন
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="fa fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title">পরিচালনা পর্ষদের সদস্য তালিকা</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ক্রম</th>
                                    <th>ছবি</th>
                                    <th>নাম</th>
                                    <th>পদবী</th>
                                    <th>ভূমিকা (Role)</th>
                                    <th>বাণী</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $m)
                                    <tr>
                                        <td>#{{ $m->order_num }}</td>
                                        <td>
                                            @if($m->photo)
                                                <img src="{{ asset($m->photo) }}" alt="{{ $m->name }}" class="rounded-circle object-fit-cover" style="width: 45px; height: 45px;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px;">
                                                    <i class="fa fa-user"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $m->name_bn ?: $m->name }}</strong>
                                            <span class="text-muted small">{{ $m->name }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $m->designation_bn ?: $m->designation }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ ucfirst($m->role_type) }}</span>
                                        </td>
                                        <td>
                                            @if($m->speech_message)
                                                <span class="badge bg-success"><i class="fa fa-check me-1"></i>সংরক্ষিত</span>
                                            @else
                                                <span class="badge bg-secondary">নেই</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($m->is_active)
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.corporate.board.edit', $m->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.corporate.board.destroy', $m->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এই সদস্যকে মুছে ফেলতে চান?');" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">কোনো সদস্য পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $members->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

