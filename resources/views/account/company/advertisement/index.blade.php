@extends('layouts.client.vendor')
@section('title','Competition | Listing')
@section('content')
@php
$user = auth()->user();

@endphp
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Company's Competition</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">

                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif

                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->
        <div class="white-box competition_listing">
            <div class="row">
                <div class="col-md-7">
                    <h4 class="box-title">  Competitions List </h4>
                </div>
                <div class="col-lg-5 col-sm-5 col-md-5 col-xs-12">
                    <form action="{{ route('company.advertisement.filter') }}" method="post" class="">
                                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-12">
                                <div class="d-md-flex">

                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $searchtext ?? '' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                {{-- <div class="col-md-3"> --}}
                    <?php
                            $getFeatureUsesValue = Helper::getFeatureUsesValue('advertisment');
                            if($getFeatureUsesValue <= count($advertisement)){
                        ?>
                    {{-- <a onclick="alert('Please upgrade profile. you can not add advertisment greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Advertisement</a> --}}
                    <?php }else{ ?>
                    @if ($user->hasPermission('add') == true)
                    {{-- <a href="{{ route('company.addadvertisement')}}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Advertisement</a> --}}
                    @endif
                    <?php } ?>
                {{-- </div> --}}
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">Preview</th>
                            <th class="border-top-0">Heading</th>
                            <th class="border-top-0">Active Date</th>
                            <th class="border-top-0">Expiry Date</th>

                            <th class="border-top-0">Action</th>
                            <th class="border-top-0">Control by Vendor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        $today = date('Y-m-d H:i:s');
                        ?>
                        @foreach($advertisement as $row)
                        <tr>
                            <td><img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" height="50px" width="50px"></td>
                            <td>{{ $row->heading }}</td>
                            <td>{{ \Carbon\Carbon::parse($row->start_date)->format('j F Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($row->end_date)->format('j F Y') }}

                                @php
                                    $today = date('Y-m-d H:i:s');
                                @endphp
                                @if($row->end_date <= $today)
                                    <span class="badge bg-danger text-white">Expired</span>
                                    @else
                                    <span class="badge bg-success text-white">
                                        Active
                                    </span>

                                        @if(Helper::IsCompetitionHasQuestions($row->id) == 0)
                                        <b class="new">
                                            New
                                        </b>
                                        @endif

                                @endif
                                {{-- {{ $row->end_date }}
                                {{ $today }} --}}
                            </td>

                            <td>
                                {{-- @if ($user->hasPermission('edit') == true) --}}
                                <a class="btn btn-primary btn-sm" href="<?php echo url("/company/advertisement-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                {{-- @endif --}}
                                @if ($user->hasPermission('delete') == true)
                                {{-- <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/advertisement-delete", ['id' => @$row->id]); ?>">DELETE</a> --}}
                                @endif
                            </td>
                            <td>
                                @php
                                   $getComStatus = Helper::VendorCompetitionStatus($row->id);
                                @endphp
                                @if($getComStatus && $getComStatus->status === 'Active')
                                    <a class="btn btn-success btn-sm competition_status" data-companyid="{{ auth()->user()->directory_id }}" data-competitionid="{{ @$row->id }}" data-status="Active" style="color: #fff;">
                                        Activated
                                    </a>
                                @else
                                    <a class="btn btn-danger btn-sm competition_status" data-companyid="{{ auth()->user()->directory_id }}" data-competitionid="{{ @$row->id }}" data-status="Deactive">
                                        Deactivated
                                    </a>
                                @endif

                            <td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center cust-page">
            </div>
        </div>

    </div>
    </div>
    </div>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.show_confirm').click(function(e) {
            if (!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });

        $('.competition_status').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const companyId = button.data('companyid');
            const competitionId = button.data('competitionid');
            const currentStatus = button.data('status') || button.text().trim();

            const newStatus = currentStatus === 'Active' ? 'Deactive' : 'Active';

            console.log('Current Status:',  currentStatus);
            console.log('New Status:', newStatus);

            button.prop('disabled', true);

            $.post('{{ route('company.advertisement.status') }}', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                company_id: companyId,
                competition_id: competitionId,
                status: newStatus
            })
            .done(function(response) {
                if (response.success) {
                    button.data('status', newStatus);
                    button.text(newStatus);
                    if (newStatus === 'Active') {
                        button.removeClass('btn-danger').addClass('btn-success');
                    } else {
                        button.removeClass('btn-success').addClass('btn-danger');
                    }
                } else {
                    alert(response.error || 'Unable to update status.');
                }
            })
            .fail(function(xhr) {
                alert(xhr.responseJSON?.message || 'Unable to update status.');
            })
            .always(function() {
                button.prop('disabled', false);
            });
        });
    </script>
</main>
<style>
    @media (max-width: 767px) {
        .d-none {
            display: unset !important;
        }
    }

    .new{
        background: #4e4a4a;
        color: #fff;
        border-radius: 50%;
        padding: 4px;
        font-size: 11px;
        font-weight: 400;
        margin: -13px -6px;
        position: absolute;
    }
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

    /* Main Container */
    .competition_listing {
        background: #ffffff;
        border-radius: 12px;
        padding: 20px 25px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    /* Title */
    .competition_listing .box-title {
        font-size: 20px;
        font-weight: 600;
        color: #2c3e50;
    }

    /* Search Bar */
    .competition_listing .input-group input.form-control {
        border-radius: 8px 0 0 8px;
        border: 1px solid #e0e0e0;
        padding: 8px 12px;
        font-size: 14px;
    }

    .competition_listing .input-group input.form-control:focus {
        border-color: #007bff;
        box-shadow: none;
    }

    .competition_listing .input-group .btn {
        border-radius: 0 8px 8px 0;
        padding: 8px 18px;
        font-weight: 500;
        background: linear-gradient(135deg, #ff9800, #f57c00);
        border: none;
        color: #fff;
    }

    .competition_listing .input-group .btn:hover {
        background: linear-gradient(135deg, #f57c00, #e65100);
    }

    /* Table Styling */
    .competition_listing table {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .competition_listing thead th {
        font-size: 13px;
        text-transform: uppercase;
        color: #7f8c8d;
        font-weight: 600;
        border: none;
    }

    /* Table Rows */
    .competition_listing tbody tr {
        background: #f9fbfd;
        border-radius: 10px;
        transition: all 0.2s ease;
    }

    .competition_listing tbody tr:hover {
        background: #eef5ff;
        transform: translateY(-2px);
    }

    /* Table Cells */
    .competition_listing tbody td {
        vertical-align: middle;
        padding: 12px 10px;
        border-top: none !important;
    }

    /* Image Preview */
    .competition_listing tbody td img {
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #eee;
    }

    /* Heading Text */
    .competition_listing tbody td:nth-child(2) {
        font-weight: 500;
        color: #34495e;
    }

    /* Date Styling */
    .competition_listing tbody td:nth-child(3),
    .competition_listing tbody td:nth-child(4) {
        font-size: 14px;
        color: #555;
    }

    /* Status Badges */
    .competition_listing .badge {
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        margin-left: 6px;
    }

    .competition_listing .badge.bg-success {
        background: #28a745 !important;
    }

    .competition_listing .badge.bg-danger {
        background: #dc3545 !important;
    }

    /* Action Buttons */
    .competition_listing .btn-sm {
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .competition_listing .btn-primary {
        background: #ff9800;
        border: none;
    }

    .competition_listing .btn-primary:hover {
        background: #f57c00;
    }

    .competition_listing .btn-danger {
        background: #e53935;
        border: none;
    }

    .competition_listing .btn-danger:hover {
        background: #c62828;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .competition_listing {
            padding: 15px;
        }

        .competition_listing table {
            font-size: 13px;
        }

        .competition_listing .box-title {
            font-size: 16px;
        }

        .competition_listing .input-group .btn {
            border-radius: 8px;
            margin-top: 5px;
        }
    }

</style>
@endsection
