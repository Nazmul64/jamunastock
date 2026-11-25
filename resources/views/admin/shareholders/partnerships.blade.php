@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-handshake text-success me-2"></i>প্রাতিষ্ঠানিক পার্টনারশিপ ও চুক্তি ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">ফ্র্যাঞ্চাইজি, যৌথ খামার ও লজিস্টিক পার্টনারদের তালিকা ও চুক্তি</p>
                </div>
                <div>
                    <button type="button" class="btn btn-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#addPartnershipModal">
                        <i class="fa fa-plus me-1"></i>নতুন পার্টনারশিপ যুক্ত করুন
                    </button>
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
                    <h5 class="box-title">পার্টনারশিপ প্রস্তাবনা ও চুক্তিসমূহ</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>কোড</th>
                                    <th>প্রতিনিধি / প্রতিষ্ঠান</th>
                                    <th>পার্টনারশিপ ধরন</th>
                                    <th>মূলধন অবদান</th>
                                    <th>লাভের অনুপাত (%)</th>
                                    <th>তারিখ</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($partnerships as $pt)
                                    <tr>
                                        <td><strong>{{ $pt->partner_code }}</strong></td>
                                        <td>
                                            <strong class="d-block text-dark">{{ $pt->partner_name }}</strong>
                                            <span class="text-muted small">{{ $pt->business_name ?: '-' }}</span>
                                        </td>
                                        <td><span class="badge bg-light text-dark border">{{ $pt->partnership_type }}</span></td>
                                        <td><strong class="text-success">৳ {{ number_format($pt->capital_contribution) }}</strong></td>
                                        <td>{{ $pt->profit_share_percentage }}%</td>
                                        <td>{{ $pt->created_at ? $pt->created_at->format('d M, Y') : '-' }}</td>
                                        <td>
                                            @if($pt->status == 'active')
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @elseif($pt->status == 'pending')
                                                <span class="badge bg-warning text-dark">পেন্ডিং</span>
                                            @else
                                                <span class="badge bg-secondary">{{ $pt->status }}</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($pt->status == 'pending')
                                                <form action="{{ route('admin.shareholders.partnerships.status', $pt->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="status" value="active">
                                                    <button type="submit" class="btn btn-sm btn-success" title="অনুমোদন করুন">
                                                        <i class="fa fa-check me-1"></i>অনুমোদন
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-5">কোনো পার্টনারশিপ রেকর্ড নেই।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $partnerships->links() }}
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Add Partnership Modal -->
<div class="modal fade" id="addPartnershipModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold">নতুন পার্টনারশিপ চুক্তি যুক্ত করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.shareholders.partnerships.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">অংশীদার / প্রতিনিধির নাম <span class="text-danger">*</span></label>
                        <input type="text" name="partner_name" class="form-control" required placeholder="উদাঃ মোঃ আনিসুর রহমান">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">প্রতিষ্ঠানের নাম</label>
                        <input type="text" name="business_name" class="form-control" placeholder="উদাঃ গ্রিন এগ্রো লিমিটেড">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">পার্টনারশিপের ধরন <span class="text-danger">*</span></label>
                        <select name="partnership_type" class="form-select" required>
                            <option value="Franchise Hub">আঞ্চলিক ফ্র্যাঞ্চাইজি বা সার্ভিস হাব</option>
                            <option value="Agro Farming Joint Venture">যৌথ কৃষি বা খামার প্রকল্প</option>
                            <option value="Cold Storage & Logistics">হিমাগার ও লজিস্টিক পার্টনার</option>
                            <option value="Import & Export Partner">আমদানি-রপ্তানি কনসোর্টিয়াম</option>
                            <option value="Corporate Investment">কর্পোরেট পুঁজি বিনিয়োগ</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">মূলধন অবদান (টাকা)</label>
                        <input type="number" name="capital_contribution" class="form-control" placeholder="উদাঃ ৫,০০,০০০">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">মুনাফা শেয়ারিং (%)</label>
                        <input type="number" step="0.01" name="profit_share_percentage" class="form-control" placeholder="উদাঃ ২০.০০">
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">শর্তাবলী ও বিবরণ</label>
                        <textarea name="terms_and_conditions" class="form-control" rows="2" placeholder="চুক্তির মূল শর্ত..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label font-weight-bold">স্ট্যাটাস <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active">সক্রিয় (Active)</option>
                            <option value="pending">পেন্ডিং (Pending)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-success font-weight-bold">সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

