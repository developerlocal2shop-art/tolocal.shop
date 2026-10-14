@extends('layouts.adminmaster')
@section('title','Competition | List')
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
    hr {
        margin: 1px 0;
    }
    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }

</style>

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Competition List</h4>
            </div>
                <div class="col-lg-6 col-sm-8 col-md-6 col-xs-12">
                <form action="{{ route('admin.advertise.indexsearch') }}" method="post" class="">
                                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="d-md-flex">
                                <div class="input-group mb-3">
                                    <select  name="user_type" id="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                      
                                        <option value="admin" @if($user_type == 'admin') selected @endif> Admin </option>
                                        {{-- <option value="vendor" @if($user_type == 'vendor') selected @endif>Vendor </option> --}}
                                    </select>
                                </div>
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
                <div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
                    <div class="d-md-flex">
                        <ol class="breadcrumb ms-auto">
                            <li><a href="#" class="fw-normal"></a></li>
                        </ol>
                        @if ($user->hasPermission('add') == true)
                        <a href="{{ route('admin.advertise.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
                        @endif
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
                        <div class="col-md-5">
                            <h3 class="box-title">Competition List</h3>
                        </div>
                    </div>

                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Preview</th>
                                    <th class="border-top-0">Heading</th>
                                    <th class="border-top-0">Active Date</th>
                                    <th class="border-top-0">Expiry Date</th>
                                    <th class="border-top-0">User</th>
                                    <th class="border-top-0">Assign to seller</th>
                                    <th class="border-top-0">Select winner</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($advertisement as $row)
                                <tr>
                                    <td><img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" height="50px" width="50px"></td>
                                    <td>{{ Str::words($row->heading, 3, '...') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->start_date)->format('Y-m-d') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->end_date)->format('Y-m-d') }}</td>
                                    <td>
                                        @if($row->directory_id == 0 || $row->directory_id == '')
                                        <b style="color:#f33155;">Admin</b>
                                        @else
                                        <b style="color:#0a6f0d;">Vendor</b>
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            $vendorIds = explode(',', $row->adminassignvendor ?? '');
                                        @endphp

                                        @if(!empty($vendorIds) && $vendorIds[0] !== '')
                                            @foreach($vendorIds as $vid)
                                                @php
                                                    $directory = Helper::getDirectoryIdSlug($vid);
                                                @endphp
                                                <span style="font-size:12px;">{{ $directory->title ?? '' }}</span><br><hr>
                                            @endforeach
                                        @endif

                                    </td>
                                    <td>
                                       
                                        <a class="btn btn-success btn-xs" href="<?php echo url("/admin/competition-participants", ['id' => @$row->id]); ?>">View Participants</a>
                                        
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/advertisement-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/advertisement-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $advertisement->appends($query)->links() }}
                        @else
                        {!! $advertisement->links() !!}
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
