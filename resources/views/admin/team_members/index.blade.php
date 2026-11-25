@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-users text-success me-2"></i>টিম মেম্বার ব্যবস্থাপনা (Team Members)</h4>
                    <p class="text-muted mb-0">টিম মেম্বারদের ছবি, নাম ও পদবী পরিচালনা করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.team_members.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-plus me-1"></i>নতুন টিম মেম্বার যুক্ত করুন
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
                    <h5 class="box-title">টিম মেম্বার তালিকা</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">ক্রম</th>
                                    <th style="width: 80px;">ছবি</th>
                                    <th>নাম</th>
                                    <th>পদবী</th>
                                    <th style="width: 100px;">স্ট্যাটাস</th>
                                    <th class="text-end" style="width: 140px;">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $m)
                                    <tr>
                                        <td>#{{ $m->order_num }}</td>
                                        <td>
                                            @if($m->photo && file_exists(public_path($m->photo)))
                                                <img src="{{ asset($m->photo) }}" alt="{{ $m->name }}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid #10b981;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 50px; height: 50px; border: 2px solid #cbd5e1;">
                                                    <i class="fa fa-user fa-lg"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="d-block text-dark" style="font-size: 15px;">{{ $m->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning-subtle text-warning-emphasis px-3 py-1 rounded-pill" style="background: #fef3c7; color: #92400e !important; font-size: 13px;">{{ $m->designation }}</span>
                                        </td>
                                        <td>
                                            @if($m->is_active)
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-secondary">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.team_members.edit', $m->id) }}" class="btn btn-sm btn-info me-1" title="এডিট">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.team_members.destroy', $m->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('আপনি কি নিশ্চিতভাবে এই টিম মেম্বার মুছে ফেলতে চান?');" title="ডিলিট">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">বর্তমানে কোনো টিম মেম্বার যুক্ত নেই।</td>
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
