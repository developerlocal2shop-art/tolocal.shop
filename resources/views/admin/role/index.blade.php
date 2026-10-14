@extends('layouts.adminmaster')
@section('title','Admin Role')
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
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Role</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.role.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Role</a>
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
                        <div class="col-md-8">
                            <h3 class="box-title">Role List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <form action="{{ route('admin.role.indexsearch') }}" method="POST" class="">
                                    {{ csrf_field() }}
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>

                                </form>

                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Full Name</th>
                                    <th class="border-top-0">Created</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; $datacount = count($role); if ($datacount>0){ ?>
                                @foreach ($role as $row)
                                @if($row->id != 1)
                                <tr>
                                    <td><?php echo $row->name ?></td>
                                    <td><?php echo $row->created_at ?></td>
                                    <td>
                                    @if ($user->hasPermission('status') == true)
                                    <input data-id="{{@$row->id}}" name="userstatus" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ (@$row->status==1) ? 'checked' : '' }} onChange="statusChange('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                    @endif
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin.role.edit',$row->id) }} ">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="{{ route('admin.role.destroy',$row->id) }}">DELETE</a>
                                        @endif
                                    </td>
                                </tr>
                               @endif
                                @endforeach
                                <?php }else{?>
                                <tr>
                                    <td align="center" colspan="4">No Data Found</td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $role->appends($query)->links() }}
                        @else
                        {!! $role->links() !!}
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
<script type="text/javascript">
    function statusChange(statusdata, id) {
        var status = (statusdata != 0) ? 0 : 1;
        console.log("under statuhhhsChange : ", statusdata);
        console.log("under id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/changeRoleStatus'
            , data: {
                'status': status
                , 'id': id
            }
            , success: function(data) {
                console.log(data.success)
            }
        });
    }

</script>
@endsection
