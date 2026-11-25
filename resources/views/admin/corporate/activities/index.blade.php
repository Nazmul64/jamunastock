@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-tasks text-success me-2"></i>প্রাতিষ্ঠানিক কার্যক্রম ও প্রকল্প বাস্তবায়ন</h4>
                    <p class="text-muted mb-0">সম্পন্ন, চলমান ও পরিকল্পনাধীন বিভিন্ন উন্নয়ন প্রকল্পের তালিকা ও তদারকি</p>
                </div>
                <div>
                    <a href="{{ route('admin.corporate.activities.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-plus me-1"></i>নতুন কার্যক্রম যুক্ত করুন
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
                <div class="box-header with-border d-flex justify-content-between align-items-center">
                    <h5 class="box-title">কার্যক্রম ও প্রকল্পের তালিকা</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.corporate.activities.index') }}" class="btn btn-xs {{ empty($category) ? 'btn-success' : 'btn-default' }}">সকল</a>
                        <a href="{{ route('admin.corporate.activities.index', ['category' => 'ongoing']) }}" class="btn btn-xs {{ $category == 'ongoing' ? 'btn-success' : 'btn-default' }}">চলমান</a>
                        <a href="{{ route('admin.corporate.activities.index', ['category' => 'completed']) }}" class="btn btn-xs {{ $category == 'completed' ? 'btn-success' : 'btn-default' }}">সম্পন্ন</a>
                        <a href="{{ route('admin.corporate.activities.index', ['category' => 'planned']) }}" class="btn btn-xs {{ $category == 'planned' ? 'btn-success' : 'btn-default' }}">পরিকল্পনাধীন</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>ছবি</th>
                                    <th>শিরোনাম</th>
                                    <th>ক্যাটাগরি</th>
                                    <th>অগ্রগতি</th>
                                    <th>বাজেট</th>
                                    <th>লোকেশন</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $act)
                                    <tr>
                                        <td><strong>{{ $act->project_code }}</strong></td>
                                        <td>
                                            @if($act->featured_image)
                                                <img src="{{ asset($act->featured_image) }}" style="width: 50px; height: 35px; object-fit: cover;" class="rounded">
                                            @else
                                                <div class="bg-light text-muted d-flex align-items-center justify-content-center rounded" style="width: 50px; height: 35px;">
                                                    <i class="fa fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $act->title }}</strong>
                                            <span class="text-muted small">{{ $act->sector_type }}</span>
                                        </td>
                                        <td>
                                            @if($act->category == 'completed')
                                                <span class="badge bg-success">সম্পন্ন</span>
                                            @elseif($act->category == 'ongoing')
                                                <span class="badge bg-warning text-dark">চলমান</span>
                                            @else
                                                <span class="badge bg-info">পরিকল্পনাধীন</span>
                                            @endif
                                        </td>
                                        <td style="min-width: 140px;">
                                            <div class="d-flex justify-content-between small mb-1">
                                                <span>{{ $act->progress_percentage }}%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" style="width: {{ $act->progress_percentage }}%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>৳ {{ number_format($act->budget) }}</strong>
                                        </td>
                                        <td>{{ $act->location ?: '-' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.corporate.activities.edit', $act->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.corporate.activities.destroy', $act->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এটি মুছে ফেলতে চান?');" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">কোনো কার্যক্রম পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

