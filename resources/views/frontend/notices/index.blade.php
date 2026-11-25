@extends('layouts.frontend.app')

@section('title', 'Notices & Circular Board - Jamuna Stock Market PLC')

@section('content')
<div class="main-container">

    <!-- Page Banner -->
    <div class="container-fluid no-left-padding no-right-padding page-banner" style="background: linear-gradient(135deg, #063c19 0%, #0b5e28 50%, #04240f 100%); padding: 50px 0; border-bottom: 3px solid #d97706;">
        <div class="container text-center text-white">
            <span class="badge px-3 py-2 mb-2" style="background: rgba(217, 119, 6, 0.25); color: #fbbf24; border: 1px solid #d97706; font-size: 13px; font-weight: 600;">Official Circulars</span>
            <h2 style="color: #fff; font-size: 34px; font-weight: 800; margin-bottom: 10px;">Notices & Circular Board</h2>
            <p style="color: #d1fae5; font-size: 15px; margin: 0; max-width: 700px; margin: 0 auto;">Latest administrative circulars, dividend announcements, and corporate updates from Jamuna Stock Market PLC.</p>
        </div>
    </div>

    <main class="site-main py-5" style="background: #f8fafc;">
        <div class="container">

            <!-- Pinned Urgent Notices (if any) -->
            @if(count($pinnedNotices) > 0)
                <div class="mb-5">
                    <h5 class="fw-bold mb-3" style="color: #063c19;"><i class="fa fa-thumbtack text-danger me-2"></i>Urgent & Pinned Notices</h5>
                    <div class="row g-3">
                        @foreach($pinnedNotices as $pn)
                            <div class="col-md-6">
                                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white" style="border-left: 5px solid #dc2626 !important; border: 1px solid #fee2e2;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-danger small px-2 py-1"><i class="fa fa-bell me-1"></i>Urgent</span>
                                        <span class="text-muted small">{{ $pn->publish_date ? $pn->publish_date->format('d M, Y') : date('d M, Y') }}</span>
                                    </div>
                                    <h5 class="fw-bold mb-2" style="font-size: 17px;">
                                        <a href="{{ route('notices.show', $pn->id) }}" class="text-decoration-none text-dark hover-success">
                                            {{ $pn->title }}
                                        </a>
                                    </h5>
                                    <p class="text-muted small mb-3" style="line-height: 1.7;">
                                        {{ Str::limit(strip_tags($pn->content), 120) }}
                                    </p>
                                    <a href="{{ route('notices.show', $pn->id) }}" class="text-danger fw-bold small text-decoration-none">
                                        Read Full Notice &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Regular Notice Table -->
            <div class="bg-white p-4 rounded-4 shadow-sm" style="border: 1px solid #e2e8f0;">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h5 class="fw-bold mb-0" style="color: #063c19;">General Notice Directory</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('notices.index') }}" class="btn btn-sm {{ empty($type) ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">All</a>
                        <a href="{{ route('notices.index', ['type' => 'announcement']) }}" class="btn btn-sm {{ $type == 'announcement' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">Announcements</a>
                        <a href="{{ route('notices.index', ['type' => 'project_update']) }}" class="btn btn-sm {{ $type == 'project_update' ? 'btn-success' : 'btn-outline-secondary' }} rounded-pill px-3">Project Updates</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 140px;">Publish Date</th>
                                <th>Title</th>
                                <th style="width: 130px;">Category</th>
                                <th style="width: 120px;" class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notices as $n)
                                <tr>
                                    <td class="text-muted small">
                                        <i class="fa fa-calendar-alt text-success me-1"></i>
                                        {{ $n->publish_date ? $n->publish_date->format('d M, Y') : '-' }}
                                    </td>
                                    <td>
                                        <a href="{{ route('notices.show', $n->id) }}" class="fw-bold text-dark text-decoration-none hover-success d-block">
                                            {{ $n->title }}
                                        </a>
                                        <span class="text-muted small">{{ Str::limit(strip_tags($n->content), 70) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ ucfirst($n->notice_type) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ route('notices.show', $n->id) }}" class="btn btn-outline-success btn-sm px-3 rounded-pill fw-semibold">
                                            Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-5">
                                        No notices found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $notices->links() }}
                </div>
            </div>

        </div>
    </main>

</div>
@endsection

