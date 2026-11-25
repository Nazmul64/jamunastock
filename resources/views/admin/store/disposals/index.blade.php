@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold text-dark mb-1">
                        <i class="fa fa-trash text-danger me-2"></i> ক্ষতি, হারানো ও বাতিল সম্পদ ব্যবস্থাপনা (Damage & Disposal Register)
                    </h3>
                    <p class="text-muted mb-0">নষ্ট মালামাল, হারানো সম্পদ, অবলোপন (Write-off) ও স্ক্র্যাপ নিলাম বিক্রয়ের সার্বিক হিসাব</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger shadow-sm" data-bs-toggle="modal" data-bs-target="#createDisposalModal">
                        <i class="fa fa-plus-circle me-1"></i> নতুন ক্ষতি / বাতিল এন্ট্রি
                    </button>
                    <a href="{{ route('admin.store.dashboard') }}" class="btn btn-secondary shadow-sm">
                        <i class="fa fa-arrow-left me-1"></i> ড্যাশবোর্ড
                    </a>
                </div>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- KPI Row -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-danger border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">মোট ক্ষতি ও বাতিল রেকর্ড</span>
                            <h3 class="mb-0 fw-bold text-danger">{{ $disposals->count() }} টি</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-warning border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">আনুমানিক মোট ক্ষতি</span>
                            <h3 class="mb-0 fw-bold text-warning">৳{{ number_format($disposals->sum('estimated_loss'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-success border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">স্ক্র্যাপ / ভাঙারি বিক্রয় আয়</span>
                            <h3 class="mb-0 fw-bold text-success">৳{{ number_format($disposals->sum('scrap_selling_price'), 2) }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm border-0 border-start border-primary border-4">
                        <div class="card-body">
                            <span class="text-muted fw-bold d-block">নিট লাভ / ক্ষতি (Net P&L)</span>
                            <h3 class="mb-0 fw-bold {{ $disposals->sum('net_gain_loss') < 0 ? 'text-danger' : 'text-success' }}">
                                ৳{{ number_format($disposals->sum('net_gain_loss'), 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disposals Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0 text-dark">
                        <i class="fa fa-list text-primary me-2"></i> ক্ষতি, হারানো ও অবলোপনের তালিকা
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>রেকর্ড নং</th>
                                    <th>ঘটনার ধরন</th>
                                    <th>সম্পদ / পণ্যের নাম</th>
                                    <th>পরিমাণ</th>
                                    <th>তারিখ</th>
                                    <th>কারণ ও বিবরণ</th>
                                    <th>বই-মূল্য (Book Value)</th>
                                    <th>স্ক্র্যাপ বিক্রয়মূল্য</th>
                                    <th>দায়িত্বপ্রাপ্ত / তদন্তকারী</th>
                                    <th>স্ট্যাটাস</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($disposals as $disp)
                                <tr>
                                    <td><span class="badge bg-secondary font-monospace">{{ $disp->record_no }}</span></td>
                                    <td>
                                        @if($disp->type == 'damage')
                                            <span class="badge bg-warning text-dark">নষ্ট (Damaged)</span>
                                        @elseif($disp->type == 'lost')
                                            <span class="badge bg-danger">হারানো (Lost)</span>
                                        @elseif($disp->type == 'disposal')
                                            <span class="badge bg-dark">স্ক্র্যাপ নিলাম (Disposal)</span>
                                        @else
                                            <span class="badge bg-info">অবলোপন (Write Off)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($disp->asset)
                                            <div class="fw-bold text-dark">{{ $disp->asset->name }}</div>
                                            <small class="text-muted">{{ $disp->asset->asset_code }}</small>
                                        @elseif($disp->item)
                                            <div class="fw-bold text-dark">{{ $disp->item->name }}</div>
                                            <small class="text-muted">{{ $disp->item->item_code }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold">{{ $disp->quantity }}</td>
                                    <td>{{ \Carbon\Carbon::parse($disp->incident_date)->format('d M, Y') }}</td>
                                    <td>
                                        <div class="small text-dark">{{ Str::limit($disp->reason, 40) }}</div>
                                        @if($disp->disposal_method)
                                            <span class="badge bg-light text-muted border">{{ ucfirst($disp->disposal_method) }}</span>
                                        @endif
                                    </td>
                                    <td>৳{{ number_format($disp->original_book_value, 2) }}</td>
                                    <td class="fw-bold text-success">৳{{ number_format($disp->scrap_selling_price, 2) }}</td>
                                    <td>{{ $disp->responsible_person ?? '—' }}</td>
                                    <td>
                                        @if($disp->status == 'approved')
                                            <span class="badge bg-success">অনুমোদিত</span>
                                        @else
                                            <span class="badge bg-warning text-dark">{{ ucfirst($disp->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">কোনো ক্ষতি বা বাতিলের রেকর্ড নেই।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modal: Create Disposal -->
<div class="modal fade" id="createDisposalModal" tabindex="-1" aria-labelledby="createDisposalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.store.disposals.store') }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="createDisposalModalLabel"><i class="fa fa-trash me-2"></i> ক্ষতি / হারানো / স্ক্র্যাপ নিলাম রেজিস্ট্রি</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ঘটনার ধরন <span class="text-danger">*</span></label>
                            <select name="type" class="form-select" required>
                                <option value="damage">নষ্ট বা ক্ষতিগ্রস্ত (Damage)</option>
                                <option value="lost">হারিয়ে যাওয়া (Lost / Theft)</option>
                                <option value="disposal">বাতিল ও স্ক্র্যাপ নিলাম (Disposal / Scrap)</option>
                                <option value="write_off">হিসাব থেকে অবলোপন (Write-off)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">স্থায়ী সম্পদ নির্বাচন</label>
                            <select name="asset_id" class="form-select">
                                <option value="">-- কোনো স্থায়ী সম্পদ হলে নির্বাচন করুন --</option>
                                @foreach($assets as $ast)
                                    <option value="{{ $ast->id }}">{{ $ast->name }} ({{ $ast->asset_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">চলতি ইনভেন্টরি আইটেম</label>
                            <select name="item_id" class="form-select">
                                <option value="">-- চলমান পণ্য বা স্টেশনারি হলে --</option>
                                @foreach($items as $it)
                                    <option value="{{ $it->id }}">{{ $it->name }} ({{ $it->item_code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পরিমাণ <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="quantity" class="form-control" value="1" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ঘটনা / বাতিলের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="incident_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">বাতিল পদ্ধতি (Disposal Method)</label>
                            <select name="disposal_method" class="form-select">
                                <option value="scrap_sale">ভাঙারি বিক্রয় (Scrap Sale)</option>
                                <option value="auction">উন্মুক্ত নিলাম (Public Auction)</option>
                                <option value="destroy">ধ্বংসকরণ (Destroy)</option>
                                <option value="recycling">পুনর্ব্যবহার / রিসাইক্লিং (Recycling)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">সম্পদের বই-মূল্য (Book Value ৳)</label>
                            <input type="number" step="0.01" name="original_book_value" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">স্ক্র্যাপ বিক্রয়লব্ধ অর্থ (৳)</label>
                            <input type="number" step="0.01" name="scrap_selling_price" class="form-control" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">দায়িত্বপ্রাপ্ত / তদন্তকারী ব্যক্তি</label>
                            <input type="text" name="responsible_person" class="form-control" placeholder="নাম">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">ক্ষতি বা বাতিলের কারণ ও বিস্তারিত <span class="text-danger">*</span></label>
                            <textarea name="reason" class="form-control" rows="3" placeholder="ঘটনার প্রেক্ষাপট ও বিবরণ" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-danger"><i class="fa fa-save me-1"></i> রেকর্ড সংরক্ষণ করুন</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

