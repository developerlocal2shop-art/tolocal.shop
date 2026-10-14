@extends('layouts.client.vendor')
@section('title','Booking| Listing')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    .modal {
        top: 70px;
    }
    .modal-body {
        padding: 2rem;
    }
    .red {
        background: #fff;
    }
    .modal-content {
        background-color: #fff;
    }
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }
    .modal-lg {
        max-width: 550px;
    }
    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }
    #send_contact_message label {
        color: #2c2828 !important;
    }
    form#send_contact_message {
        margin: 0 auto;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Booking list</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->

<div class="modal fade listing_detail_gallery" id="type_galleryModal" role="dialog"  >
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:15px;">
                    Reply of booking request 
                </h5> 
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">×</font></font></span>
                </button>
                
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview2" >

                        

                        <div class="row type_gallery" id="type_gallery_list">
                            

                             <form id="send_contact_message" action="{{ route('appointment.booking.reply') }}" method="POST" name="contactform" >
                                <span class="respondata"></span>
                                {{ csrf_field() }}
                                
                                <input type="hidden"  name="reply_id" id="reply_id">
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                        <b style="color:#262424; font-size:14px;">Reply to: <input type="text" value="{{ Auth::user()->email }}" name="reply_to" style="padding: 0px 5px; width: 40%; border: 1px solid #d3d1d1;"> </b>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">To<span class="notranslate" style="color:red;">*</span></label>
                                            <input class="form-control required" readonly type="text" required="" id="reply_email" name="reply_email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Subject<span class="notranslate" style="color:red;">*</span></label>
                                            <input class="form-control required" type="text" required="" id="subject" name="subject" required>
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- /row -->
                                <div class="form-group">
                                    <label for="message">Query<span class="notranslate" style="color:red;">*</span></label>
                                    <textarea class="form-control required" id="query" name="query" required="" style="height:120px;" required></textarea>
                                </div>

                                <div class=" row">
                                    <div class="form-group" style="margin-left: 17px;">
                                        <button type="submit" class="btn_1 submitbtn" id="submit_contact">Submit</button>
                                    </div>
                                </div>
                            </form>
                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main>
    <div class="container margin_60_35">
<div class="page-wrapper">
    
    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <style>
            .hotel-search { margin-right: 25px; width:200px;}

        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('company.booking.indexsearch') }}" method="get" class="">
                        
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Booking List</h3>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="form-check-input" name="visibility_booking_date_time" id="visibledatetime" value="1" {{ ($userData->visibility_booking_date_time)=='1' ? 'checked' : '' }} >
                                <label class="custom-control-label" for="1"> Visibility of booking date on company detail page.  <b>Customer Reply to:</b> {{ Auth::user()->email }}
                                </label>
                            </div>
                        </div>
                       
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                
                                <div class="input-group mb-3">
                                    <input type="text"  name="searchtext" id="search" value="{{ $bookingsearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    </form>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                    <a class="btn btn-danger d-md-block pull-left ms-3 hidden-xs hidden-sm waves-effect waves-light text-white" id="deleteAllSelectedProducts"  style="margin-top:10px;">Bulk Delete</a>
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0" style="width: 70px;">
                                            <input type="checkbox" id="checkAll" class="checkall"> All
                                    </th>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Name | Email | Phone</th>
                                    <th class="border-top-0">Type </th>
                                    <th class="border-top-0">Booking Date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $i = 0; $datacount = count($bookingdata); if ($datacount>0){
                                $count = $bookingdata->total(); // total records
                                $number = $count - ($bookingdata->currentPage() - 1) * $bookingdata->perPage();
                            ?>
                                @foreach($bookingdata as $bookingdatarow)

                                @php
                                    $getFeatureLinksValue = Helper::getReplyRequestId($bookingdatarow->id);
                                @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids" class="cb-element" value="{!! $bookingdatarow->id !!}" />
                                        </td>
                                        <td>{{ $number-- }} </td>
                                        
                                        <td>
                                            {!! Str::words($bookingdatarow->name, 3, '...') !!}
                                            <br>
                                            {{ $bookingdatarow->email }}
                                            <br>
                                            {{ $bookingdatarow->phone }}
                                        </td>
                                        <td>
                                            @if($bookingdatarow->type == 'booking')
                                            Booking request
                                            @else
                                            Delivery request
                                            @endif
                                        </td>
                                        <td>
                                            <?php
                                            if($bookingdatarow->booking_date){
                                                $bookingdateandtime = explode(" ", $bookingdatarow->booking_date);?>
                                                <table>
                                                    <tr>
                                                        <td style="border-top: 0px">{{ $bookingdateandtime[0]??'' }}</td>
                                                        <td style="border-top: 0px">{{ $bookingdateandtime[1]??'' }}</td>
                                                    </tr>
                                                </table>
                                            <?php }else{  

                                                if($bookingdatarow->created_date){
                                                $creatingDateandTime = explode(" ", $bookingdatarow->created_date);
                                                ?>
                                                <table>
                                                    <tr>
                                                        <td style="border-top: 0px">{{ $creatingDateandTime[0]??'' }}</td>
                                                        <td style="border-top: 0px">{{ $creatingDateandTime[1]??'' }}</td>
                                                    </tr>
                                                </table>
                                            <?php } } ?>
                                        </td>
                                        
                                        <td>
                                            @if($bookingdatarow->status == null)
                                            Pending
                                            @else
                                            {{ $bookingdatarow->status }}
                                            @endif
                                        </td>
                                        <td>
                                        @if ($user->hasPermission('edit') == true)
                                        @if ($bookingdatarow->notify == '0')
                                        <a class="btn btn-warning btn-sm" href="{{ route('company.booking.edit',$bookingdatarow->id) }}">VIEW</a>
                                        @endif
                                            <a class="btn btn-primary btn-sm" href="{{ route('company.booking.edit',$bookingdatarow->id) }}">EDIT</a>
                                            <a class="btn btn-danger btn-sm show_confirm" href="{{ route('company.booking.destroy',$bookingdatarow->id) }}">DELETE</a>
                                        @endif

                                        @if($getFeatureLinksValue == 1)
                                            <a class="btn btn-warning btn-sm booking_replied" data-id="{{ $bookingdatarow->id }}" data-email="{{ $bookingdatarow->email }}"  style="color: #c91969;">
                                                 Replied
                                            </a>
                                         
                                        @else
                                            <a class="btn btn-warning btn-sm booking_reply" data-id="{{ $bookingdatarow->id }}" data-email="{{ $bookingdatarow->email }}" style="color:#2c2828" > 
                                                Reply
                                            </a>
                                         
                                        @endif
                                           
                                        </td>
                                    </tr>
                                @endforeach   

                                

                            <?php }else{ ?> 
                                    <tr>
                                        <td align="center" colspan="4">No Data Found</td>
                                    </tr>
                            <?php } ?>   
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="d-flex justify-content-center cust-page" style="margin: 10px 0px;">
                        @if(isset($query))
                        {{ $bookingdata->appends($query)->links() }}
                        @else
                        {!! $bookingdata->links() !!}
                        @endif
                    </div>
                </div>
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
    $(document).ready(function() {

        // setTimeout(function(){
        //     location.reload();
        // }, 4000);

        $(document).on('click', '.submitbtn', function(e) {
            e.preventDefault();

            $(document).find('.required').removeClass('is-invalid');
            $(document).find('.invalid-feedback').remove();

            var obj = $(this);
            let requestTxt = $(this).attr('process-text');
            requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
            var oldhtml = $(this).html();

            var form = $(this).closest("form");
            var url = form.attr('action');
            var method = form.attr('method');
            var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="">${requestTxt}...</span>`;

            var allformdata = new FormData(form[0]);
            $.ajax({
                type: method
                , url: url
                , headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
                , data: allformdata
                , mimeType: "multipart/form-data"
                , contentType: false
                , cache: false
                , processData: false
                , dataType: "json"
                , beforeSend: function() {
                    obj.attr("disabled", true);
                    obj.html(loader);
                },

                success: function(response) {
                    console.log(response);

                    if (response.status == false) {
                        $.each(response.errors, function(index, value) {
                            form.find('.required').each(function() {
                                var fieldName = $(this).attr('name');
                                if (fieldName == index && value != "") {
                                    $(this).addClass("is-invalid");
                                    $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                    $(this).focus();
                                }
                            });
                        });

                        if (typeof(response.message) != "undefined") {
                            $('.respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');

                            window.setTimeout(function() {
                                $('.respondata').hide();
                            }, 5000);
                        }

                    } else {
                        if (typeof(response.message) != "undefined") {
                            console.log(response.message);
                            $("#send_contact_message")[0].reset();
                            $('.respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');
                            window.setTimeout(function() {
                                $('.respondata').hide();
                                $('#type_galleryModal').modal('hide');
                                window.location.reload();
                            }, 5000);


                        }

                    }
                    obj.attr("disabled", false);
                    obj.html(oldhtml);
                }
                , error: function(err) {}
            });

        });

        $('.booking_reply').click(function(e) {
            var dataemail = $(this).data('email');
            var dataid = $(this).data('id');
            $('#subject').val('');
            $('#query').val('');

            $('#reply_email').val(dataemail);
            $('#reply_id').val(dataid);
            $('#type_galleryModal').modal('show');
            $('#submit_contact').show();
        }); 

        $('.booking_replied').click(function(e) {
            var dataemail = $(this).data('email');
            var dataid = $(this).data('id');
            //alert(dataid);
            $('#type_galleryModal').modal('show');
            $.ajax({
                type: "GET"
                , dataType: "json"
                , url: baseUrl + '/get-replied-data'
                , data: {
                    'dataid': dataid,
                    //'type': 'booking',
                    }
                , success: function(data) {
                    if(data.success == 'true'){
                        $('#reply_email').val(data.data.email);
                        $('#subject').val(data.data.subject);
                        $('#query').val(data.data.query);
                        $('#submit_contact').hide();
                        
                    }
                }
            });
        }); 

        

        $('.modal_header_close_btn').click(function(e) {
            $('#type_galleryModal').modal('hide');
        }); 
        
        $("#visibledatetime").change(function() {
            if (this.checked) {
                //alert("first");
                $.ajax({
                    type: "GET"
                    , dataType: "json"
                    , url: baseUrl + '/changeVisibilityDateTimeStatus'
                    , data: {
                        'status': 1,
                        }
                    , success: function(data) {
                        console.log(data.success);
                    }
                });
                // the checkbox is now checked 
            } else {
               // alert("second");
                $.ajax({
                type: "GET"
                , dataType: "json"
                , url: baseUrl + '/changeVisibilityDateTimeStatus'
                , data: {
                    'status': 0
                    }
                , success: function(data) {
                    console.log(data.success);
                }
            });
                // the checkbox is now no longer checked
            }
        });
    });
        
</script>
<script type="text/javascript">
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $('#deleteAllSelectedProducts').click(function(e){
        e.preventDefault();
        var allids = [];

        $("input:checkbox[name=ids]:checked").each(function(){
            allids.push($(this).val());
        });
        if(allids.length <= 0){
            
            toastr.error('Please select or checked at least one record');
            return false;
        }
        else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            
            if(check==true){
                var selected_values = allids.join(",");
                
                $.ajax({
                    url: baseUrl + '/delete-selected-booking',
                    type: "get",
                    data: {
                        'ids': selected_values,
                        '_token': '{{ csrf_token() }}',
                    },
                    success: function(data){
                        console.log(data.success);
                            if(data['success']){
                            //$(".cb-element").each(function(){
                                // $(this).parents("tr").remove()
                            //});
                            toastr.success(data['success']);
                            location.reload();
                        }else if(data['error']){
                            toastr.error(data['error']);
                        }else{
                            toastr.error("Something went wrong");
                        }
                    }
                });
                $.each(allids, function(key,val){
                    //$('table tr').filter("[data-row-id='" + val + "']").remove();
                });
            }
        }
    });

</script>

@endsection