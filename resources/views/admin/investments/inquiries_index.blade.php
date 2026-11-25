@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-fluid py-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">
                    <i class="fa fa-envelope-open-text text-warning me-2"></i>অনলাইন ইনভেস্টমেন্ট ইনকোয়ারি পাইপলাইন (CRM Leads)
                </h3>
                <p class="text-muted small mb-0">ওয়েবসাইটে ৯টি সেক্টর থেকে আসা আগ্রহীদের আবেদন, কল ট্র্যাকিং, মিটিং এবং অফিসিয়াল চুক্তিতে রূপান্তর</p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fa fa-check-circle fa-lg"></i>
                <div class="fw-medium">{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- CRM Status Tabs (Section 14) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4 p-2">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.investments.inquiries.index') }}" 
                   class="btn btn-sm {{ !request('status') ? 'btn-dark' : 'btn-light' }} rounded-pill px-3">
                    সকল ইনকোয়ারি <span class="badge bg-secondary ms-1">{{ $statusCounts['all'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'new']) }}" 
                   class="btn btn-sm {{ request('status') == 'new' ? 'btn-info text-white' : 'btn-light' }} rounded-pill px-3">
                    নতুন আবেদন <span class="badge bg-info ms-1">{{ $statusCounts['new'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'pending_contact']) }}" 
                   class="btn btn-sm {{ request('status') == 'pending_contact' ? 'btn-warning text-dark' : 'btn-light' }} rounded-pill px-3">
                    ফোন করা বাকি <span class="badge bg-warning ms-1">{{ $statusCounts['pending_contact'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'contacted']) }}" 
                   class="btn btn-sm {{ request('status') == 'contacted' ? 'btn-primary' : 'btn-light' }} rounded-pill px-3">
                    যোগাযোগ হয়েছে <span class="badge bg-primary ms-1">{{ $statusCounts['contacted'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'meeting_scheduled']) }}" 
                   class="btn btn-sm {{ request('status') == 'meeting_scheduled' ? 'btn-indigo text-white' : 'btn-light' }} rounded-pill px-3">
                    মিটিং নির্ধারিত <span class="badge bg-dark ms-1">{{ $statusCounts['meeting_scheduled'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'agreement_pending']) }}" 
                   class="btn btn-sm {{ request('status') == 'agreement_pending' ? 'btn-warning' : 'btn-light' }} rounded-pill px-3">
                    চুক্তি প্রক্রিয়াধীন <span class="badge bg-warning ms-1">{{ $statusCounts['agreement_pending'] }}</span>
                </a>
                <a href="{{ route('admin.investments.inquiries.index', ['status' => 'converted']) }}" 
                   class="btn btn-sm {{ request('status') == 'converted' ? 'btn-success' : 'btn-light' }} rounded-pill px-3">
                    বিনিয়োগ সম্পন্ন (Converted) <span class="badge bg-success ms-1">{{ $statusCounts['converted'] }}</span>
                </a>
            </div>
        </div>

        <!-- Inquiries Table (Section 14 & 16) -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small">
                            <tr>
                                <th>ইনকোয়ারি কোড</th>
                                <th>আবেদনকারী ও ফোন</th>
                                <th>সেক্টর</th>
                                <th>প্রস্তাবিত পরিমাণ</th>
                                <th>মেয়াদ</th>
                                <th>তারিখ</th>
                                <th>পাইপলাইন স্ট্যাটাস</th>
                                <th>দায়িত্বপ্রাপ্ত</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $inq)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.investments.inquiries.show', $inq->id) }}" class="fw-bold text-primary">
                                            {{ $inq->inquiry_code }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $inq->name }}</div>
                                        <div class="small text-muted"><i class="fa fa-phone me-1"></i>{{ $inq->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">{{ $inq->sector->code ?? 'N/A' }}</span>
                                        <div class="small text-muted">{{ Str::limit($inq->sector->name_bn ?? '', 18) }}</div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">৳{{ number_format($inq->proposed_amount) }}</span>
                                    </td>
                                    <td>{{ $inq->duration_years ? $inq->duration_years . ' বছর' : 'N/A' }}</td>
                                    <td>
                                        <small class="text-muted">{{ $inq->created_at->format('d/m/Y h:i A') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $inq->status_badge }} px-2 py-1">
                                            {{ ucfirst(str_replace('_', ' ', $inq->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $inq->assignee->name ?? 'অ্যাসাইন হয়নি' }}</small>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('admin.investments.inquiries.show', $inq->id) }}" class="btn btn-sm btn-light border rounded-pill px-2" title="বিস্তারিত">
                                                <i class="fa fa-eye text-primary"></i>
                                            </a>
                                            @if($inq->status !== 'converted')
                                                <a href="{{ route('admin.investments.inquiries.convert', $inq->id) }}" 
                                                   class="btn btn-sm btn-success rounded-pill px-3 shadow-sm fw-semibold" 
                                                   title="অফিসিয়াল চুক্তিতে রূপান্তর">
                                                    <i class="fa fa-check-double me-1"></i> Convert to Investment
                                                </a>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success small py-2 px-3">
                                                    <i class="fa fa-check me-1"></i> চুক্তিবদ্ধ
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">কোনো ইনকোয়ারি পাওয়া যায়নি।</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($inquiries->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $inquiries->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
