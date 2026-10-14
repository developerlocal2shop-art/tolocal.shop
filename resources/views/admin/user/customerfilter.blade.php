@extends('layouts.adminmaster')
@section('title','User')
@section('content')
@php $userr = auth()->guard('webadmin')->user(); @endphp
    <style>
        .toggle{
            border-radius:20rem !important;
            height:10px !important;
        }
        .toggle.btn {
            min-height: 26px !important;
        }
    </style>
        <div class="page-wrapper">
           <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Customer</h4>
                    </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            @if ($userr->hasPermission('add') == true)
                            <a href="{{ route('admin.user.create') }}" 
                                class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Customer</a>
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
                                <h3 class="box-title">Customer List</h3>
                            </div>
                        </div>
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <!-- <th class="border-top-0">#</th> -->
                                            <th class="border-top-0">Full Name</th>
                                            <th class="border-top-0">Email</th>
                                            <th class="border-top-0">Status</th>
                                            <th class="border-top-0">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i=0; $datacount = count($user); if ($datacount>0){ foreach ($user as $row){ //echo "<pre>"; print_r($row);                                     ?>
                                        <tr>
                                            <!-- <td><?php echo ++$i ?></td> -->
                                            <td><?php echo $row->name ?></td>
                                            <td><?php echo $row->email ?></td>
                                            <td>
                                            @if ($userr->hasPermission('status') == true)
                                            <input data-id="{{@$row->id}}" name="userstatus" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ (@$row->status==1) ? 'checked' : '' }} onChange="statusChange('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                            @endif
                                            </td>
                                            <td>
                                            @if ($userr->hasPermission('edit') == true)
                                                <a class="btn btn-primary btn-xs"  href="<?php echo url("/admin/user-edit",['id' => @$row->id]); ?>" >EDIT</a>
                                            @endif
                                            @if ($userr->hasPermission('delete') == true)
                                                <a class="btn btn-danger btn-xs show_confirm"  href="<?php echo url("/admin/user-delete",['id' => @$row->id]); ?>" >DELETE</a>
                                            @endif
                                            </td>
                                        </tr>
                                    <?php } }else{?>
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
    
    <script type="text/javascript">
        $('.show_confirm').click(function(e) {
            if(!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });
    </script>
    <script type="text/javascript">
        function statusChange(statusdata,id){
            var status = (statusdata!=0)?0:1;
            console.log("under statuhhhsChange : ",statusdata);
            console.log("under id : ",id);
            $.ajax({
                type: "GET",
                dataType: "json",
                url: baseUrl+'/admin/changeStatus',
                data: {'status': status, 'user_id': id},
                success: function(data){
                    console.log(data.success)
                }
            });
        }
           

            // $('input[name="userstatus"]').on('change', function() {
            //     console.log("under change");
            //     var datavar = $(this).prop('checked');
            //     console.log("under datavar: ",datavar);
            // var status = $(this).prop('checked') == true ? 1 : 0; 
            // var user_id = $(this).data('id'); 
      
            //     $.ajax({
            //         type: "GET",
            //         dataType: "json",
            //         url: baseUrl+'/admin/changeStatus',
            //         data: {'status': status, 'user_id': user_id},
            //         success: function(data){
            //             console.log(data.success)
            //         }
            //     });
            // });
    
    
    </script>
@endsection