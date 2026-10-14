@extends('layouts.client.vendor')
@section('title', 'Affiliate Dashboard')
@section('content')


<div class="sub_header_in sticky_header ">
    <div class="container">
        <x-client.company_account_menu />
    </div>
</div>

<main>
    <a class="vendor_bedcrub" href="{{ route('vendor.affiliate.dashboard') }}">Affiliate Dashboard</a>
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white" style="background: #2f4a7c !important; color: #fff !important;">
                        <h5 class="mb-0" style="color: #fff !important;">Affiliate Settings</h5>
                    </div>
                    <div class="card-body">

                        @if (session()->has('success_msg'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success_msg') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('vendor.affiliate.settings.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="enabled" class="form-label">Affiliate Program Status</label>
                                <select name="enabled" id="enabled" class="form-control">
                                    <option value="1" {{ old('enabled', $setting->enabled ? 1 : 0) == 1 ? 'selected' : '' }}>Enabled</option>
                                    <option value="0" {{ old('enabled', $setting->enabled ? 1 : 0) == 0 ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="commission_type" class="form-label">Commission Type</label>
                                <select name="commission_type" id="commission_type" class="form-control">
                                    <option value="percentage" {{ old('commission_type', $setting->commission_type ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                    <option value="fixed" {{ old('commission_type', $setting->commission_type ?? 'percentage') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="commission_rate" class="form-label">Commission Rate</label>
                                <input
                                    type="number"
                                    name="commission_rate"
                                    id="commission_rate"
                                    class="form-control"
                                    min="0"
                                    step="0.01"
                                    value="{{ old('commission_rate', $setting->commission_rate ?? 0) }}"
                                >
                            </div>

                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
