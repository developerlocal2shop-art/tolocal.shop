@extends('layouts.adminmaster')
@section('title','User')
@section('content')
@php $userr = auth()->guard('webadmin')->user(); @endphp
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
                <h4 class="page-title">Permission managment</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($userr->hasPermission('add') == true)
                    <a href="{{ route('admin.permission.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New</a>
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
                            <h3 class="box-title">Permission List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <form action="{{ route('admin.permission.indexsearch') }}" method="POST" class="">
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
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr style="background: linear-gradient(180deg, rgb(255 255 255) 0%, rgb(217 217 217) 100%);">
                                    <!-- <th class="border-top-0">#</th> -->
                                    <th class="border-top-0">Role</th>

                                    <th class="border-top-0">Access Module</th>
                                    <th class="border-top-0" style="width: 120px !important;">Access for</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0" style="width: 120px !important;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=0; if (count($user)>0){ ?>
                                @foreach($user as $val)
                                @if($val->id != 1)
                                <tr>
                                    <td>
                                        @foreach ($val->roles as $key => $rol)
                                        @if($rol->name != ' ')
                                        <b>{{$rol->name??'User'}}</b>
                                        @else
                                        <b>User</b>
                                        @endif
                                        @endforeach<br><br>
                                        <i style="font-size: 30px;float: left;margin: 0px 10px 0px 0px;" class="fa fa-user" aria-hidden="true"></i>
                                        {{ $val->email??'' }}
                                    </td>

                                    <td>
                                        <ul class="permissionul">
                                            @foreach ($val->modules as $module)
                                            <li>
                                                <i class="fa fa-check" style="color: #74ca74;" aria-hidden="true"></i> {{$module->name?? ''}} <br>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        @foreach ($val->permissions as $permission)
                                        <i class="fa fa-check" style="color: #74ca74;" aria-hidden="true"></i> {{$permission->name?? ''}} <br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($userr->hasPermission('status') == true)
                                        <input data-id="{{$val->id}}" name="userstatus" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ (@$val->status==1) ? 'checked' : '' }} onChange="statusChange('<?php echo $val->status ?>','<?php echo $val->id ?>');">
                                        @endif
                                    </td>
                                    <td>
                                        @if ($userr->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin.permission.edit',$val->id) }}">EDIT</a>
                                        @endif
                                        @if ($userr->hasPermission('delete') == true)
                                        <a style="color: #fff;" class="btn btn-danger btn-xs show_confirm" href="{{ route('admin.permission.destroy',$val->id) }}">DELETE</a>
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
                        {{ $user->appends($query)->links() }}
                        @else
                        {!! $user->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<style>
    .table td,
    .table th {
        padding: 15px 10px 15px 10px;
        border-color: #b1afaf;
    }

    tr th:first-child,
    tr td:first-child {
        width: 200px !important;
        background: radial-gradient(#e7dbdb, transparent);
        word-break: break-word;
        white-space: normal;
    }

    ul.permissionul {
        margin: 0;
        padding: 0;
    }

    .permissionul li {
        list-style: none;
        margin: 0 -1px;
        padding: 0;
        width: 50%;
        float: left;
    }

</style>
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
            , url: baseUrl + '/admin/changeAdminStatus'
            , data: {
                'status': status
                , 'user_id': id
            }
            , success: function(data) {
                console.log(data.success)
            }
        });
    }

</script>
@endsection
