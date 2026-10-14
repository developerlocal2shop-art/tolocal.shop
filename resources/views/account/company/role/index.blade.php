@extends('layouts.client.vendor')
@section('title','Vendor Role')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Role</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -40px !important;
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
<main>

    <div class="container margin_60_35">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {!! session()->get('msg') !!}
        </div><br />
        @endif

        <div class="white-box">
            <div class="row">
                <div class="col-md-4">
                    <h3 class="box-title">Role List</h3>
                </div>
                <div class="col-md-4">
                    <div class="d-md-flex">
                        {{-- <form action="{{ route('company.role.indexsearch') }}" method="POST" class="">
                            {{ csrf_field() }}
                            <div class="input-group mb-3">
                                <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                </div>
                            </div>
                        </form> --}}
                    </div>
                </div>
                <div class="col-md-4">
                    

                    <?php
                        $getFeatureUsesValue = Helper::getFeatureUsesValue('role');
                        if($getFeatureUsesValue <= count($role)){
                    ?> 
                    <a onclick="alert('Please upgrade profile. you can not add role greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Role</a>
                    <?php }else{ ?> 
                            @if ($user->hasPermission('add') == true)
                            <a href="{{ route('company.role.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Role</a>
                            @endif
                    <?php } ?>

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
                                <a class="btn btn-primary btn-sm" href="{{ route('company.role.edit',$row->id) }} ">EDIT</a>
                                @endif
                                @if ($user->hasPermission('delete') == true)
                                <a class="btn btn-danger btn-sm show_confirm" href="{{ route('company.role.destroy',$row->id) }}">DELETE</a>
                                @endif
                            </td>
                        </tr>
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
</main>

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
            , url: baseUrl + '/company/changeRoleStatus'
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
