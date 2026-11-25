@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h4 class="page-title"><i class="fa fa-chart-line text-success me-2"></i>১০টি প্রধান বিনিয়োগ খাত ব্যবস্থাপনা</h4>
                    <p class="text-muted mb-0">১০টি বিনিয়োগ খাতের সীমা, মেয়াদ, পলিসি ও তথ্য নিয়ন্ত্রণ করুন</p>
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
                    <h5 class="box-title">বিনিয়োগ খাতসমূহের তালিকা (১০টি খাত)</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>নম্বর</th>
                                    <th>খাতের নাম (বাংলা)</th>
                                    <th>ইংরেজি নাম</th>
                                    <th>বিনিয়োগ পরিসর</th>
                                    <th>মেয়াদ</th>
                                    <th>সক্রিয় প্রকল্প</th>
                                    <th>স্ট্যাটাস</th>
                                    <th class="text-end">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sectors as $sec)
                                    <tr>
                                        <td><span class="badge bg-light text-dark fw-bold">{{ $sec->sector_no }}</span></td>
                                        <td><strong class="text-dark">{{ $sec->name_bn }}</strong></td>
                                        <td class="text-muted small">{{ $sec->name_en }}</td>
                                        <td><span class="badge bg-light text-success fw-bold">{{ $sec->investment_range }}</span></td>
                                        <td>{{ $sec->duration }}</td>
                                        <td><span class="badge bg-info">{{ $sec->projects->count() }} টি</span></td>
                                        <td>
                                            @if($sec->is_active)
                                                <span class="badge bg-success">সক্রিয়</span>
                                            @else
                                                <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.investments.sectors.edit', $sec->id) }}" class="btn btn-sm btn-info">
                                                <i class="fa fa-edit me-1"></i>সম্পাদনা
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection

