@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-folder-open text-success me-2"></i>বিনিয়োগ প্রকল্প ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">গ্রাহকদের জন্য উন্মুক্ত সকল বিনিয়োগ প্রকল্প ও তহবিলের হিসাব</p>
                </div>
                <div>
                    <a href="{{ route('admin.investments.projects.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-plus me-1"></i>নতুন প্রকল্প তৈরি করুন
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
                    <h5 class="box-title">বিনিয়োগ প্রকল্পের তালিকা</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>প্রকল্পের নাম</th>
                                    <th>খাত</th>
                                    <th>টার্গেট বাজেট</th>
                                    <th>উত্তোলিত তহবিল</th>
                                    <th>বিনিয়োগকারী</th>
                                    <th>অগ্রগতি</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $p)
                                    <tr>
                                        <td><strong>{{ $p->project_id_code }}</strong></td>
                                        <td>
                                            <strong class="text-dark">{{ $p->project_name }}</strong>
                                            <span class="text-muted small d-block">সর্বনিম্ন: ৳ {{ number_format($p->min_investment) }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark">{{ $p->sector->name_bn ?? '-' }}</span></td>
                                        <td>৳ {{ number_format($p->target_budget) }}</td>
                                        <td><strong class="text-success">৳ {{ number_format($p->total_raised) }}</strong></td>
                                        <td>{{ $p->investors_count }} জন</td>
                                        <td style="min-width: 120px;">
                                            <div class="small fw-bold">{{ $p->progress_percentage }}%</div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $p->progress_percentage }}%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($p->status == 'open')
                                                <span class="badge bg-success">উন্মুক্ত</span>
                                            @elseif($p->status == 'ongoing')
                                                <span class="badge bg-warning text-dark">চলমান</span>
                                            @elseif($p->status == 'completed')
                                                <span class="badge bg-info">সম্পন্ন</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $p->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.investments.projects.edit', $p->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.investments.projects.destroy', $p->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এই প্রকল্প মুছে ফেলতে চান?');" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-5">কোনো প্রকল্প পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $projects->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

