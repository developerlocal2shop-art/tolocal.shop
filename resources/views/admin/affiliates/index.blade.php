@extends('layouts.adminmaster')
@section('title','Affiliate Customers')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }

    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }
</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Affiliate Customers</h4>
                <Span></Span>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="container-fluid">
        <div class="container">

            <h3>
                Affiliate list
            </h3>

            <div class="card mt-3">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Affiliate Code</th>
                                <th>Store Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @forelse($affiliates as $affiliate)

                                <tr>

                                    <td>
                                        {{ $affiliate->id }}
                                    </td>

                                    <td>
                                        {{ $affiliate->user->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $affiliate->user->email ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $affiliate->affiliate_code }}
                                    </td>

                                    <td>
                                        {{ ucfirst( str_replace( '_', ' ', $affiliate->store_type )) }}
                                    </td>

                                    <td>
                                        @php
                                            $statusClass = 'bg-success';
                                            if (($affiliate->status ?? '') === 'pending' || ($affiliate->status ?? '') === 'rejected') {
                                                $statusClass = 'bg-danger';
                                            }
                                        @endphp

                                        <span class="badge {{ $statusClass }}">
                                            {{ $affiliate->status }}
                                        </span>

                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                @php
                                                    $dropdownValue = 'Select';
                                                    if (($affiliate->status ?? '') === 'approved') {
                                                        $dropdownValue = 'Approved';
                                                    } elseif (($affiliate->status ?? '') === 'rejected') {
                                                        $dropdownValue = 'Rejected';
                                                    }
                                                @endphp
                                                {{ $dropdownValue }}
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form method="POST" action="{{ route('admin.affiliates.approve', $affiliate->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-success">
                                                            Approve
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form method="POST" action="{{ route('admin.affiliates.reject', $affiliate->id) }}">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center"
                                    >
                                        No affiliate applications found.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                    {{ $affiliates->links() }}

                </div>

            </div>

        </div>
    </div>

</div>
</div>

@endsection
