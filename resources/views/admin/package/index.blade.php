@extends('layouts.adminmaster')
@section('title','Package| Listing')
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
                <h4 class="page-title">Package</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    {{-- @if ($user->hasPermission('add') == true)
                        <a href="{{ route('admin.package.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Package</a>
                    @endif --}}
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
                            <h3 class="box-title">Package List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                {{-- <form action="{{ route('admin.events.indexsearch') }}" method="get" class="">
                                    {{ csrf_field() }}
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $eventssearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>

                                </form> --}}
                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Plan</th>
                                    <th class="border-top-0">Price</th>
                                    <!-- <th class="border-top-0">Category</th> -->
                                    <th class="border-top-0">Interval Period</th>
                                    <th class="border-top-0">Thereafter Price</th>
                                    <th class="border-top-0">Created</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($plans as $row)
                                <tr>
                                    <td>{{ json_decode($row->name) }}</td>
                                    <td>{{ $row->currency }} {{ $row->price }}</td>
                                    <td>{{ $row->invoice_period }} {{ $row->invoice_interval }} </td>
                                    <td>
                                        @if($row->signup_fee)
                                        {{ $row->currency }} {{ $row->signup_fee }}
                                        @endif
                                    </td>
                                    <td>{{ $row->created_at }} </td>
                                    <td> @if($row->is_active == 1) Active  @else <span style='color:red;'>Not Active</span> @endif  </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/package-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <?php if($row->is_active == 1){ ?>
                                                <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/package-delete", ['id' => @$row->id]); ?>" >DELETE</a>
                                            <?php } ?>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $plans->appends($query)->links() }}
                        @else
                        {!! $plans->links() !!}
                        @endif
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
