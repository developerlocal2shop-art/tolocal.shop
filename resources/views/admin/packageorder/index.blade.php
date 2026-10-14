@extends('layouts.adminmaster')
@section('title','Package Order| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
        .d-none{
            display: unset !important;
        }

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
</style>

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Package Order</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
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
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Package Order List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                
                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Directory Name</th>
                                    <th class="border-top-0">User</th>
                                    <th class="border-top-0">Plan Name</th>
                                    <th class="border-top-0">Total Period</th>
                                    <th class="border-top-0">Activation Date</th>
                                    <th class="border-top-0">Expiry Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($allPackageOrder as $row)
                                <tr>
                                    <!-- <td>{{ $row->subscriber_id }}</td> -->
                                    <td>{{ $row->directoryName->title??'' }}</td>
                                    <td>{{ $row->userData[0]->name??'' }}</br>({{$row->userData[0]->email??''}})</td>
                                    <td>{{ trim($row->name, '\"') }}</td>
                                    @php 
                                        $diff = '';
                                        $date = \Carbon\Carbon::parse($row->created_at);
                                        if(isset($row->ends_at)){
                                        $to = \Carbon\Carbon::parse($row->ends_at);
                                        $diff = $date->diffInDays($to);
                                        }
                                    @endphp
                                    @if(!empty($diff))
                                        <td> {{ $diff}} Days</td>
                                    @else
                                        <td></td>
                                    @endif
                                    @if(!empty($row->created_at))
                                        <td>{{ $row->created_at }}</td>
                                    @else
                                    <td>NA</td>
                                    @endif
                                    <td>{{ $row->ends_at }}</td>
                                    
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                    {!! $allPackageOrder->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
