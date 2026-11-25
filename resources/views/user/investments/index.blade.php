@extends('layouts.backend.app')

@section('content')
<div class="content-wrapper">
    <div class="container-full">
        <!-- Content Header -->
        <div class="content-header">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title">My Project Investments & Dividend Accounts</h4>
                    <p class="text-muted mb-0 small">Your invested capital, earned returns, and withdrawal history</p>
                </div>
                <div>
                    <a href="{{ route('investments.projects') }}" class="btn btn-success btn-sm rounded-pill fw-bold">
                        <i class="fa fa-plus me-1"></i>Invest in New Project
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

            <!-- 3 Overview Summary Cards -->
            <div class="row">
                <div class="col-md-4">
                    <div class="box bg-gradient-success text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">Total Active Capital</span>
                            <h2 class="fw-bold mb-0">BDT {{ number_format($totalInvested) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box bg-gradient-warning text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">Total Earned Dividends</span>
                            <h2 class="fw-bold mb-0">BDT {{ number_format($totalEarned) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box bg-gradient-info text-white">
                        <div class="box-body">
                            <span class="text-white-50 small d-block">Paid Dividends</span>
                            <h2 class="fw-bold mb-0">BDT {{ number_format($paidDividends) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Investments Table -->
            <div class="box">
                <div class="box-header with-border">
                    <h5 class="box-title">Investment Portfolio</h5>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Code</th>
                                    <th>Project Name</th>
                                    <th>Sector</th>
                                    <th>Invested Amount</th>
                                    <th>Earned Return</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($investments as $inv)
                                    <tr>
                                        <td><strong>{{ $inv->investment_code }}</strong></td>
                                        <td>{{ $inv->project->project_name ?? 'Custom Project' }}</td>
                                        <td><span class="badge bg-light text-dark">{{ $inv->sector->name ?? ($inv->sector->name_bn ?? '-') }}</span></td>
                                        <td><strong class="text-success">BDT {{ number_format($inv->amount) }}</strong></td>
                                        <td>BDT {{ number_format($inv->return_earned) }}</td>
                                        <td>{{ $inv->investment_date ? $inv->investment_date->format('d M, Y') : '-' }}</td>
                                        <td>
                                            @if($inv->status == 'approved' || $inv->status == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @elseif($inv->status == 'pending')
                                                <span class="badge bg-warning text-dark">Under Review</span>
                                            @elseif($inv->status == 'completed')
                                                <span class="badge bg-info">Completed</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($inv->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">No active investments found currently.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $investments->links() }}
                    </div>
                </div>
            </div>

            <!-- Withdrawal Request Box -->
            <div class="row">
                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Withdrawal Application</h5>
                        </div>
                        <div class="box-body">
                            <form action="{{ route('user.investments.withdraw') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Withdrawal Type <span class="text-danger">*</span></label>
                                    <select name="withdrawal_type" class="form-select" required>
                                        <option value="dividend">Earned Dividend</option>
                                        <option value="profit">Project Profit</option>
                                        <option value="capital">Capital Withdrawal</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Withdrawal Amount (BDT) <span class="text-danger">*</span></label>
                                    <input type="number" name="amount" class="form-control" min="500" required placeholder="e.g. 5000">
                                    <div class="form-text small">Minimum withdrawal amount is BDT 500</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" class="form-select" required>
                                        <option value="bKash">bKash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                        <option value="Bank">Bank Account</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Account / Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" name="account_number" class="form-control" required placeholder="017XXXXXXXX or Bank Details">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Notes / Description</label>
                                    <textarea name="reason" class="form-control" rows="2" placeholder="Write any necessary details..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-success fw-bold">Submit Withdrawal Request</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box">
                        <div class="box-header with-border">
                            <h5 class="box-title">Recent Withdrawal Applications</h5>
                        </div>
                        <div class="box-body">
                            <ul class="list-group list-group-flush">
                                @forelse($withdrawals as $w)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <strong>{{ $w->withdrawal_code }}</strong>
                                            <span class="d-block small text-muted">{{ ucfirst($w->withdrawal_type) }} &bull; {{ $w->payment_method }}</span>
                                        </div>
                                        <div class="text-end">
                                            <span class="d-block fw-bold text-dark">BDT {{ number_format($w->amount) }}</span>
                                            @if($w->status == 'completed' || $w->status == 'approved')
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($w->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($w->status) }}</span>
                                            @endif
                                        </div>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted py-4">No recent applications found.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
