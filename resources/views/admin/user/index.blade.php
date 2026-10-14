@extends('layouts.adminmaster')
@section('title','User')
@section('content')

<style>
    @media (max-width: 767px) {
        .pull-right {
            margin-top: -30px !important;
        }

        .d-none {
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


<!-- Modal For Update Bulk Role ---->
<div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form name="SendBulkMessageFrm" id="SendBulkMessageFrm" action="{{ url('sendBulkMessage') }}" method="post">
                <input type="hidden" name="user_ids" id="user_ids">
                @csrf
                <div class="modal-header cus_header">
                    <h3 class="modal-title"  id="exampleModalLabel">Message</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                           
                            

                            <div class="col-sm-12">
                                   <textarea id="message" name="message" class="ckeditor form-control p-0 border-0" placeholder="Please type message here"></textarea>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer cus_footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="float: right;" id="SendBulkMessage">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">User</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add User</a>
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
                            <h3 class="box-title">User List</h3>

                            <button title="Bulk Change Role" class="btn btn-sm btn-primary" id="bulkMessages" data-toggle="modal" data-target="#exampleModal6" data-id="" data-index="" type="button">
                                <i class="fa fa-snowflake-o" aria-hidden="true"></i>
                                Send Bulk Messages
                            </button>
                            <button title="Show Bulk Vendor's product import file" class="btn btn-sm btn-primary" id="bulkimportfile" style="margin-left: 10px;">
                                Show Bulk vendor's product import file
                            </button>

                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>
                                <form action="{{ route('admin.user.indexsearch') }}" method="POST" class="">
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
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">
                                        <input type="checkbox" id="ckbCheckAll" />
                                    </th>
                                    <th class="border-top-0">Full Name</th>
                                    <th class="border-top-0">Email</th>
                                    <th class="border-top-0">Role</th>
                                    <th class="border-top-0">Staff</th>
                                    
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Date</th>
                                    <th class="border-top-0">Action</th>
                                    <th class="border-top-0">Customer Club Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $i=0; $datacount = count($user); 
                                    if ($datacount>0){
                                        foreach ($user as $row){ 
                                            $getCustomerClub = Helper::getCustomerClubByCusid($row->id);      
                                            if($getCustomerClub){
                                                $getCustomerData = $getCustomerClub;
                                            }else{
                                                $getCustomerData = null;
                                            }                    
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="userid[]" value="{!! $row->id !!}" class="checkBoxClass" id="Checkbox{{ $row->id??'' }}" />
                                    </td>
                                    <td><?php echo $row->name.' '.$row->last_name??'' ?></td>
                                    <td><?php echo $row->email ?></td>
                                    <td>
                                        {{-- @if($row->roles)
                                                @foreach ($row->roles as $key => $rol)
                                                    <b>{{$rol->name?? 'User'}} </b>
                                        @endforeach
                                        @else --}}
                                        @if($row->role_id == 1)
                                        <b>Vendor</b>
                                        @else
                                        <b>User</b>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->role_id == 1)
                                        <a class="btn btn-primary btn-xs" href="{{ route('admin.vendor.staff',$row->id) }}">View</a>
                                        @endif
                                    </td>
                                    
                                    <td><input data-id="{{@$row->id}}" name="userstatus" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ (@$row->status==1) ? 'checked' : '' }} onChange="statusChange('<?php echo $row->status ?>','<?php echo $row->id ?>');"></td>
                                    <td> 
                                        {{ $row->created_at ? $row->created_at->format('F j, Y') : '' }}
                                    </td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/user-edit",['id' => @$row->id]); ?>">EDIT</a>
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/user-delete",['id' => @$row->id]); ?>">DELETE</a>
                                    </td>
                                    <td> 
                                        @if($getCustomerData != null)
                                        <input data-id="{{$getCustomerData->opt_out}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="IN" data-off="OUT" {{ ($getCustomerData->opt_out=='1') ? 'checked' : '' }} onChange="changeStatus('<?php echo $getCustomerData->opt_out ?>','<?php echo $getCustomerData->id ?>');">
                                        @else
                                        N/A
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

    function changeStatus(statusdata, id) {
        
        
        if(confirm("Are you sure you want to change the customer club status?")){
            var opt_out = (statusdata != '1') ? '1' : '0';
            console.log(opt_out);
            $.ajax({
                type: "GET"
                , dataType: "json"
                , url: baseUrl + '/changeCustomerClubStatus'
                , data: {
                    'opt_out': opt_out
                    , 'id': id
                }
                , success: function(data) {
                    console.log(data.success);
                }
            });
        }else{
                //return false;
        }
    }

    $("form#SendBulkMessageFrm").submit(function(e) {
        e.preventDefault();

        var message = CKEDITOR.instances['message'].getData();

        if(message == ''){
            alert('Please enter a message');
            return false;
        }

        //alert(value);
        var form = $("#SendBulkMessageFrm");
        var formData = new FormData(this);
            formData.append('message', message);		
        $.ajax({
            url: form.attr("action")
            , type: 'POST'
            , data: formData
            , beforeSend: function() {
                $('#SendBulkMessage').prop('disabled', true);
                $('#SendBulkMessage').html('Please Wait');
            }
            , success: function(response) {
                var obj = jQuery.parseJSON(response);
                console.log(obj);
                if (obj.status == "success") {

                    toastr.success(obj.msg);

                    $('#SendBulkMessage').prop('disabled', false);
                    $('#SendBulkMessage').html('Send All');

                    $("#SendBulkMessageFrm")[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 9000);
                } else {

                    toastr.error(obj.msg);

                    $('#SendBulkMessage').prop('disabled', false);
                    $('#SendBulkMessage').html('Save');
                }
            }
            , cache: false
            , contentType: false
            , processData: false
        });
    });



    $(document).ready(function() {

        $("#ckbCheckAll").click(function() {
            $(".checkBoxClass").prop('checked', $(this).prop('checked'));
        });


        $("#bulkMessages").click(function() {
            var checkedNum = $('input[name="userid[]"]:checked').length;
            if (!checkedNum) {

                toastr.error('{{__("Please check at least one record!")}}');
                return false;
            } else {
                var selectedUId = new Array();
                $('input[name="userid[]"]:checked').each(function() {
                    selectedUId.push(this.value);
                    $('body #user_ids').val(selectedUId);
                });
            }
        });

        $('#bulkimportfile').click(function(e){
            e.preventDefault();
            var allids = [];

            $('input[name="userid[]"]:checked').each(function(){
                allids.push($(this).val());
                // allids.push($(this).attr('data-id'));
            });
            
            if(allids.length <= 0){
                
                toastr.error('Please select at least one record');
                return false;
            }
            else{
                var check = confirm("Are you sure you want to proceed further!");
                
                if(check==true){
                    var selected_values = allids.join(",");
                    
                    $.ajax({
                        url: baseUrl + '/showbulkimportfile',
                        type: "get",
                        data: {
                            'ids': selected_values,
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(data){
                            console.log(data.success);
                                if(data['success']){
                            
                                toastr.success(data['success']);
                                setTimeout(function () {
                                    location.reload();
                                }, 2000);
                            }else if(data['error']){
                                toastr.error(data['error']);
                            }else{
                                toastr.error("Something went wrong");
                            }
                        }
                    });
                
                }
            }
        });




    });

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
            , url: baseUrl + '/admin/changeStatus'
            , data: {
                'status': status
                , 'user_id': id
            }
            , success: function(data) {
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
