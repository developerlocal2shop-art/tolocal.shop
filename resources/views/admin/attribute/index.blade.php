@extends('layouts.adminmaster')
@section('title','Attribute| Listing')
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
    .hotel-search { margin-right: 25px; width:90%;}
</style>
<div class="loader" id="loader"></div>
<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                <h4 class="page-title">Attribute List  </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    {{-- @if ($user->hasPermission('add') == true) --}}
                    <a href="{{ route('admin.attribute.create') }}" class="btn btn-info d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Attribute</a>
                    {{-- @endif --}}
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
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Attribute Name</th>
                                    <th class="border-top-0">Attribute Content</th>
                                    <th class="border-top-0">User</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($attribute as $key => $row)
                                <tr>
                                   
                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($attribute->currentpage()-1) * $attribute->perpage() + $key + 1 }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>{{ $row->content }}</td>
                                    <td>
                                    @if($row->directory_id == 0)
                                        <span style="color:red">Admin</span>
                                    @else
                                        <span style="color:#707cd2">Vendor</span>
                                    @endif
                                    </td>
                                    <td>
                                    {{-- @if ($user->hasPermission('status') == true) --}}
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='1') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                    {{-- @endif --}}
                                    </td>
                                    <td>
                                    {{-- @if ($user->hasPermission('edit') == true) --}}
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin.attribute.edit',$row->id) }}">EDIT</a>
                                    {{-- @endif
                                    @if ($user->hasPermission('delete') == true) --}}
                                        <a class="btn btn-danger btn-xs show_confirm" href="{{ route('admin.attribute.destroy',$row->id) }}">DELETE</a>
                                    {{-- @endif --}}
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $attribute->appends($query)->links() }}
                        @else
                        {!! $attribute->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

<script type="text/javascript">
    $(document).ready(function() {
	    $("#loader").fadeOut("slow");
    });
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>
<script type="text/javascript">
    function changeStatus(statusdata,id){
        var status = (statusdata!='0')?'0':'1';
        console.log(status);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeAttributeStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        });
    }

</script>

@endsection
