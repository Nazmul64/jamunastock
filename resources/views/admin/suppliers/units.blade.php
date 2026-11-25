@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <div class="content-header py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div>
                    <h3 class="page-title fw-bold mb-1"><i class="fa fa-balance-scale text-primary me-2"></i> পণ্যের ইউনিট ও ক্যাটাগরি ম্যানেজমেন্ট</h3>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.suppliers.pending_supplies') }}">সরবরাহ রিকোয়েস্ট</a></li>
                        <li class="breadcrumb-item active">ইউনিট ও ক্যাটাগরি</li>
                    </ol>
                </div>
                <a href="{{ route('admin.suppliers.pending_supplies') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">ফিরে যান</a>
            </div>
        </div>

        <section class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            @endif

            <div class="row g-4">
                <!-- Units -->
                <div class="col-lg-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4">
                        <div class="box-header py-3 px-4 bg-primary text-white">
                            <h5 class="mb-0"><i class="fa fa-cubes me-2"></i> পরিমাপের ইউনিটসমূহ (Product Units)</h5>
                        </div>
                        <div class="box-body p-4">
                            <form action="{{ route('admin.suppliers.units.store') }}" method="POST" class="row g-2 mb-4">
                                @csrf
                                <div class="col-4"><input type="text" name="name" class="form-control form-control-sm" placeholder="ইংরেজি (Kg)" required></div>
                                <div class="col-4"><input type="text" name="bn_name" class="form-control form-control-sm" placeholder="বাংলা (কেজি)"></div>
                                <div class="col-2"><input type="text" name="symbol" class="form-control form-control-sm" placeholder="kg"></div>
                                <div class="col-2"><button type="submit" class="btn btn-sm btn-success w-100">যোগ</button></div>
                            </form>

                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>নাম</th><th>বাংলা</th><th>প্রতীক</th><th>স্ট্যাটাস</th><th>অ্যাকশন</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($units as $u)
                                    <tr>
                                        <td class="fw-bold">{{ $u->name }}</td>
                                        <td>{{ $u->bn_name }}</td>
                                        <td><code>{{ $u->symbol }}</code></td>
                                        <td><span class="badge bg-{{ $u->is_active ? 'success' : 'secondary' }}">{{ $u->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span></td>
                                        <td>
                                            <form action="{{ route('admin.suppliers.units.toggle', $u->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-primary">{{ $u->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="col-lg-6 col-12">
                    <div class="box border-0 shadow-xs rounded-4">
                        <div class="box-header py-3 px-4 bg-info text-white">
                            <h5 class="mb-0"><i class="fa fa-tags me-2"></i> পণ্যের ক্যাটাগরি (Product Categories)</h5>
                        </div>
                        <div class="box-body p-4">
                            <form action="{{ route('admin.suppliers.categories.store') }}" method="POST" class="row g-2 mb-4">
                                @csrf
                                <div class="col-5"><input type="text" name="name" class="form-control form-control-sm" placeholder="Category (Grains)" required></div>
                                <div class="col-5"><input type="text" name="bn_name" class="form-control form-control-sm" placeholder="বাংলা নাম"></div>
                                <div class="col-2"><button type="submit" class="btn btn-sm btn-success w-100">যোগ</button></div>
                            </form>

                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>নাম</th><th>বাংলা নাম</th><th>স্ট্যাটাস</th><th>অ্যাকশন</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $c)
                                    <tr>
                                        <td class="fw-bold">{{ $c->name }}</td>
                                        <td>{{ $c->bn_name }}</td>
                                        <td><span class="badge bg-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}</span></td>
                                        <td>
                                            <form action="{{ route('admin.suppliers.categories.toggle', $c->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-primary">{{ $c->is_active ? 'নিষ্ক্রিয় করুন' : 'সক্রিয় করুন' }}</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
