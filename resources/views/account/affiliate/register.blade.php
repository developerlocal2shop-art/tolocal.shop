@extends('layouts.client.vendor')
@section('title', 'Affiliate Registration | ToLocal.shop')

@section('content')

<style>

    .bg-success {
        background-color: #28a745 !important;
        color: #fff;
    }
    .affiliate-submit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 220px;
        padding: 14px 28px;
        border: none;
        border-radius: 12px;
        background: linear-gradient(135deg, #203864 0%, #2d5ea8 100%);
        color: #ffffff;
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.02em;
        box-shadow: 0 12px 24px rgba(32, 56, 100, 0.18);
        transition: all 0.25s ease;
    }

    .affiliate-submit-btn:hover {
        background: linear-gradient(135deg, #182d52 0%, #244c92 100%);
        color: #ffffff;
        transform: translateY(-1px);
        box-shadow: 0 16px 32px rgba(32, 56, 100, 0.24);
    }

    .affiliate-submit-btn:disabled {
        opacity: 0.8;
        cursor: not-allowed;
        transform: none;
    }

    .affiliate-form-actions {
        margin-top: 10px;
    }
</style>

<main>
    <div class="container-fluid">
        <div class="row">

            <x-client.account_menu />

            <div class="col-lg-9">

                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header">
                        <div class="container">
                            <h1>Affiliate Registration</h1>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">


                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(empty($affiliate))
                            <form method="POST" action="{{ route('affiliate.register.store') }}" id="affiliateForm">
                                @csrf

                                <input type="hidden" name="store_type" value="online">
                                <input type="hidden" name="commission_type" value="percentage">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', Auth::user()->name ?? '') }}" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', Auth::user()->email ?? '') }}" readonly>
                                    </div>
                                </div>


                                <div class="affiliate-form-actions">
                                    <button type="submit" class="affiliate-submit-btn" id="affiliateSubmitBtn">
                                        Join Affiliate Program
                                    </button>
                                </div>

                            </form>
                        @endif

                            @if (!empty($affiliate) && $affiliate->affiliate_code)
                                <div class="alert alert-info mb-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        Already joined! Your affiliate code: <strong>{{ $affiliate->affiliate_code }}</strong>
                                        <span class="ms-2 badge {{ $affiliate->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($affiliate->status ?? 'pending') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('affiliate.dashboard') }}" class="btn btn-sm btn-primary">Open Dashboard</a>
                                </div>
                            @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('affiliateForm');
        const submitBtn = document.getElementById('affiliateSubmitBtn');

        if (!form || !submitBtn) {
            return;
        }

        form.addEventListener('submit', function (event) {
            const confirmed = confirm('Are you sure you want to submit your affiliate program request?');

            if (!confirmed) {
                event.preventDefault();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });
    });
</script>
@endsection
