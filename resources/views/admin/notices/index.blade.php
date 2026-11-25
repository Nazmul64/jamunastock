@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-bullhorn text-success me-2"></i>নোটিশ ও সার্কুলার ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">ওয়েবসাইটে প্রদর্শিত সাধারণ ও জরুরি নোটিশসমূহ পরিচালনা করুন</p>
                </div>
                <div>
                    <a href="{{ route('admin.notices.create') }}" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fa fa-plus me-1"></i>নতুন নোটিশ প্রকাশ করুন
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

            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list-alt text-success me-2"></i>সকল নোটিশের তালিকা
                    </h5>
                    <span class="badge bg-light text-muted border">মোট নোটিশ: {{ $notices->total() }}টি</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="min-width: 820px;">
                            <thead class="table-light">
                                <tr>
                                    <th>তারিখ</th>
                                    <th>শিরোনাম</th>
                                    <th>ধরন</th>
                                    <th>পিন স্ট্যাটাস</th>
                                    <th>সংযুক্তি</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notices as $n)
                                    <tr>
                                        <td>{{ $n->publish_date ? $n->publish_date->format('d M, Y') : '-' }}</td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $n->title }}</strong>
                                            <span class="text-muted small">{{ Str::limit(strip_tags($n->content), 60) }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ ucfirst($n->notice_type) }}</span></td>
                                        <td>
                                            @if($n->is_pinned)
                                                <span class="badge bg-danger"><i class="fa fa-thumbtack me-1"></i>পিন করা</span>
                                            @else
                                                <span class="badge bg-secondary">স্বাভাবিক</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($n->attachment_file)
                                                <a href="{{ asset($n->attachment_file) }}" target="_blank" class="btn btn-xs btn-outline-danger">
                                                    <i class="fa fa-file-pdf me-1"></i>ফাইল
                                                </a>
                                            @else
                                                <span class="text-muted small">নেই</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($n->is_active)
                                                <span class="badge bg-success">প্রকাশিত</span>
                                            @else
                                                <span class="badge bg-secondary">ড্রাফট</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.notices.edit', $n->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('admin.notices.destroy', $n->id) }}" onclick="return confirm('আপনি কি নিশ্চিত এই নোটিশ মুছে ফেলতে চান?');" class="btn btn-sm btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-5">কোনো নোটিশ পাওয়া যায়নি।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($notices->hasPages())
                        <div class="p-3 border-top d-flex justify-content-end">
                            {{ $notices->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

