@extends('layouts.adminmaster')
@section('title','Hot Ads | List')
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
                <h4 class="page-title">Hot Ads</h4>
            </div>
                <div class="col-lg-6 col-sm-8 col-md-6 col-xs-12">
                <form action="{{ route('admin.hotads.indexsearch') }}" method="post" class="">
                                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="d-md-flex">
                                
                                    {{-- <select  name="user_type" id="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                      
                                        <option value="admin" @if($user_type == 'admin') selected @endif> Admin </option>
                                    </select> --}}
                                    <input type="hidden"  name="user_type" id="search" value="admin" >
                                
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
                        <a href="{{ route('admin.hotads.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
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
                            <h3 class="box-title">Hot Ads List</h3>
                        </div>
                    </div>

                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Banner</th>
                                    <th class="border-top-0">Heading</th>
                                    <th class="border-top-0">Active Date</th>
                                    <th class="border-top-0">Expiry Date</th>
                                    <th class="border-top-0">User</th>
                                    <th class="border-top-0">Created</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($hotads as $row)
                                <tr>
                                    <td>
                                        @if($row->image_name)
                                            <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" height="50px" width="50px">
                                        @else
                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                        @endif

                                    </td>
                                    <td>{{ Str::words($row->heading, 3, '...') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->start_date)->format('d-F-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->end_date)->format('d-F-Y') }}</td>
                                    <td>
                                        @if($row->company_id == 0 || $row->company_id == '')
                                        <b style="color:#f33155;">Admin</b>
                                        @else
                                        <b style="color:#0a6f0d;">Vendor</b>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}</td>
                                    <td>
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                        {{-- @if($row->status == 1)
                                        <span class="badge badge-success">Active</span>
                                        @else
                                        <span class="badge badge-danger">Inactive</span>
                                        @endif --}}
                                    </td>
                                   
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/hotads-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/hotads-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $hotads->appends($query)->links() }}
                        @else
                        {!! $hotads->links() !!}
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

    function changeStatus(statusdata,id){
        var status = (statusdata!='Inactive')?'Inactive':'Active';
        console.log(status);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeHotAdStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
            }
        });
    }

</script>

@endsection
