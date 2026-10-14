@extends('layouts.client.vendor')
@section('title', 'Affiliate Dashboard')
@section('content')
@php
    $defaultCurrency = Helper::defaultCurrencyData();
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}
@endphp
<div class="sub_header_in sticky_header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="mb-0">Affiliate Marketing Dashboard</h1>
            </div>
        </div>
        <x-client.company_account_menu />
    </div>
</div>

<main>
    <div class="container margin_60_35">
        <div class="row g-4 mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 dashboard-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-uppercase small mb-2 fw-semibold text-muted">Program Status</p>
                                <h3 class="mb-0 {{ strtolower($stats['program_status']) === 'disabled' ? 'text-danger' : 'text-success' }}">{{ $stats['program_status'] }}</h3>
                            </div>
                            <a href="{{ url('company/affiliate/settings') }}">
                            <div class="icon-wrap {{ strtolower($stats['program_status']) === 'disabled' ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success' }}">

                                <i class="fas {{ strtolower($stats['program_status']) === 'disabled' ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>

                            </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 dashboard-card blue">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-uppercase small mb-2 fw-semibold text-muted">Commission Type</p>
                                <h3 class="mb-0">{{ $stats['commission_type'] }}</h3>
                            </div>

                            @if($stats['commission_type'] == 'Percentage')
                                <div class="icon-wrap bg-primary-subtle text-primary">
                                    <i class="fas fa-percent"></i>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 dashboard-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-uppercase small mb-2 fw-semibold text-muted">Commission Rate</p>
                                <h3 class="mb-0">
                                    {{ $stats['commission_rate'] }}
                                    @if($stats['commission_type'] == 'Percentage')
                                    %
                                    @endif
                                </h3>
                            </div>
                            <div class="icon-wrap bg-warning-subtle text-warning">
                                <i class="fas fa-chart-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100 dashboard-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-uppercase small mb-2 fw-semibold text-muted">Estimated Earnings</p>
                                <h3 class="mb-0">
                                    {{ $currencySymbol }}{{ number_format((float) $stats['earnings'], 2) }}
                                </h3>
                            </div>
                            <div class="icon-wrap bg-danger-subtle text-danger">
                                <i class="fas fa-wallet"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Performance Overview</h5>
                        <span class="badge bg-light text-dark rounded-pill px-3 py-2">Live</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 text-center">
                            <div class="col-md-4">
                                <div class="metric-box">
                                    <div class="metric-value text-primary">{{ $stats['clicks'] }}</div>
                                    <div class="metric-label">Affiliate Clicks</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metric-box">
                                    <div class="metric-value text-success">{{ $stats['orders'] }}</div>
                                    <div class="metric-label">Qualified Orders</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="metric-box">
                                    <div class="metric-value text-warning">{{ $stats['pending'] }}</div>
                                    <div class="metric-label">Pending Payouts</div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="alert alert-info border-0 rounded-4 mb-0 bg-light-primary">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <div>
                                        Affiliate tracking is active. Update your commission settings and share your affiliate links to start generating sales.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3 px-4">
                        <h5 class="mb-0 fw-bold">Quick Actions</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-grid gap-3">
                            <a href="{{ route('vendor.affiliate.settings') }}" class="btn btn-outline-primary rounded-pill text-start" style="margin: 10px 0px 10px 0px;">
                                <i class="fas fa-sliders-h me-2"></i>Manage Affiliate Settings
                            </a>
                            {{-- <a href="#" class="btn btn-outline-secondary rounded-pill text-start disabled">
                                <i class="fas fa-share-alt me-2"></i>Share Referral Links
                            </a> --}}
                            <a href="#" class="btn btn-outline-success rounded-pill text-start disabled">
                                <i class="fas fa-file-invoice-dollar me-2"></i>View Payouts
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .dashboard-card {
        border-radius: 18px;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.08) !important;
    }

    .icon-wrap {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        font-size: 22px;
    }

    .metric-box {
        background: #f8fafc;
        border: 1px solid #edf2f7;
        border-radius: 18px;
        padding: 24px 16px;
    }

    .metric-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.2;
    }

    .metric-label {
        color: #64748b;
        font-size: 0.9rem;
        margin-top: 8px;
    }

    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.08) !important;
    }

</style>

@endsection
