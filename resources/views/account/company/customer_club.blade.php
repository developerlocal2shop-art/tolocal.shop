@extends('layouts.client.vendor')
@section('title','Customer Club | Listing')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Company's Customer Club</h1>
        <x-client.company_account_menu />
    </div>
</div>
<style>
    #view_all_redeem_offers{
        max-height: 400px !important; overflow-y: auto !important;
    }
    .list-group-item {
        padding: 10px;
        color: #383737;
    }
    div#cc_messages li {
        color: #524a4a;
    }
    .modal-content {
        background-color: #fff;
        margin: 90px 0px;
    }
    div#cke_notifications_area_message {
        display: none !important;
    }
</style>

<div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <form name="SendBulkMessageFrm" id="SendBulkMessageFrm" action="{{ url('company/sendBulkMessage') }}" method="post">
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

<div class="modal fade" id="view_messages_model" class="modal fade listing_detail_gallery" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header cus_header">
                    <h6   id="exampleModalLabel">All Messages</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="cc_messages">
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view_all_redeem_offers_model" class="modal fade listing_detail_gallery" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header cus_header">
                    <h6   id="exampleModalLabel">All offers</h6>
                    <button type="button" class="close redeem_close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view_all_redeem_offers" style="max-height: 400px !important; overflow-y: auto !important;">
                </div>
        </div>
    </div>
</div>

<div class="modal fade" id="redeem_form_model" class="modal fade listing_detail_gallery" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <div class="modal-header cus_header">
                    <h3 id="exampleModalLabel">Redeem</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form name="redeem_form" id="redeem_form" action="{{ route('redeem.voucher') }}" method="post">

                        <input type="hidden"  id="offeremail_id" name="offeremail_id" >
                        <input type="hidden"  id="offer_type" name="offer_type" >

                        @csrf
                        <div class="modal-header cus_header">
                            <h6 class="modal-title"  id="exampleModalLabel">Redeem Your Gift Voucher</h6>

                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="voucher_code" class="mb-1" style="color: #2d2e2c;">Voucher Email <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-3" id="redeem_email" name="redeem_email" readonly >

                                <label for="voucher_code" class="mb-1" style="color: #2d2e2c;">Voucher Code <span class="text-danger">*</span></label>
                                <input type="text" class="form-control mb-3" id="voucher_code" name="voucher_code" readonly >

                                <label for="voucher_code" class="mb-1" style="color: #2d2e2c;">Total Purchaged Amount  <span class="text-danger">*</span> <span style="font-size:14px; font-family: sans-serif;">Minimum Purchage Amount <b id="minimum_amount"> </b> </span></label>
                                <input type="text" class="form-control mb-3" id="total_purchaged_amount" name="total_purchaged_amount"  >

                                <label for="message" class="mb-1" style="color: #2d2e2c;">Message</label>
                                <textarea id="voucher_message" name="voucher_message" class="form-control" rows="3" placeholder="Please type your message here"></textarea>
                            </div>

                        </div>
                        <div class="modal-footer cus_footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" style="float: right;" id="redeem_btn">Redeem</button>
                        </div>
                    </form>

                </div>
        </div>
    </div>
</div>

<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->
        <div class="white-box">
            <div class="row">
                <div class="col-md-7">
                    <button title="Bulk Change Role" class="btn btn-sm btn-primary" id="bulkMessages" data-toggle="modal" data-target="#exampleModal6" data-id="" data-index="" type="button">
                        <i class="fa fa-comment-o" aria-hidden="true"></i>
                        Send Bulk Messages
                    </button>
                    <a href="{{ route('company.customer.club.export', request()->only('searchtext')) }}" class="btn btn-sm btn-success ml-2" id="exportExcel">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        Export with Excel
                    </a>
                    <h3 class="box-title">Customer Club List </h3>
                </div>
                <div class="col-md-3">

                    <div class="input-group mb-3" style="display: flex;">
                       <form action="{{ route('company.customer.club') }}" method="get" class="">
                        <div class="input-group-append">
                            <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                        </div>
                        </form>

                    </div>
                </div>
                <div class="col-md-2">
                    <span id="home_qr_code" class="home_qr_code">
                            {!! QrCode::size(60)->color(50, 0, 130)->generate(url('company/customer/club')) !!}
                        </span>
                </div>
                <div class="col-md-12">
                    <p class="box-title">List <b>{{ $getCustomerValue }}</b> Customer Club members based on the selected package. </p>
                </div>


            </div>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">
                                <input type="checkbox" id="ckbCheckAll" />
                            </th>
                            <th class="border-top-0">S.No</th>
                            <th class="border-top-0">Name | Phone | Email</th>
                            <th class="border-top-0">Location</th>
                            <th class="border-top-0">Message</th>
                            <th class="border-top-0">Join Date</th>

                            <th class="border-top-0">IN/OUT</th>
                            <th class="border-top-0">All Messages</th>
                            <th class="border-top-0">Redeem Status</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $i = 0;
                            $datacount = count($customerclubdata);
                        @endphp

                        @if($datacount > 0)
                            @foreach($customerclubdata as $crow)
                                @php
                                    $country = Helper::getCountryName($crow->country);
                                    $state = Helper::getStateName($crow->state);
                                    //$users = Helper::getUserById($crow->customer_id);
                                    //$seller = Helper::getUserNameDirId($crow->seller_id);


                                    // Handle city gracefully
                                    if (is_numeric($crow->city)) {
                                        $cityObj = Helper::getCityName($crow->city);
                                        $city = $cityObj && isset($cityObj->name) ? $cityObj->name : '';
                                    } else {

                                        $city = $crow->city;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="userid[]" value="{!! $crow->customer_id !!}" class="checkBoxClass" id="Checkbox{{ $crow->customer_id??'' }}" />
                                    </td>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        <b>{{ $crow->fname }} {{ $crow->lname }}</b><br>
                                        {{ $crow->phone }}<br>
                                        {{ $crow->email }}
                                    </td>
                                    <td>
                                        {{ $city }}, <br>
                                        {{ $state->name ?? '' }},<br>
                                        {{ $country->name ?? '' }},  {{ $crow->zipcode }}
                                    </td>
                                    <td>{{ Str::words($crow->message, 4, '...') }}</td>
                                    {{-- <td>{{ $seller->name??'' }} {{ $seller->last_name??'' }}</td>
                                    <td>{{ $users->name??'' }} {{ $users->last_name??'' }}</td> --}}
                                    <td>{{ $crow->joined_at }}</td>
                                    <td>
                                        <input data-id="{{$crow->opt_out}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="IN" data-off="OUT" {{ ($crow->opt_out=='1') ? 'checked' : '' }} onChange="changeStatus('<?php echo $crow->opt_out ?>','<?php echo $crow->id ?>');">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm view_messages" data-email="{{ $crow->email }}" id="view_messages" data-dismiss="modal" aria-label="Close">
                                        View Messages
                                        </button>
                                    </td>
                                   <td>
                                        {{-- <button type="button" class="btn btn-sm btn-success redeem_form_model" data-email="{{ $crow->email }}" id="redeem_form_model" data-dismiss="modal" aria-label="Close">
                                        Redeem Gift Voucher
                                        </button> --}}

                                        <button type="button" class="btn btn-sm btn-success redeem_all_offers_model" data-email="{{ $crow->email }}" id="redeem_all_offers_model" data-dismiss="modal" aria-label="Close">
                                        Redeem All Offers
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm delete_cc" style="background: #dc3545; color:#fff;" data-email="{{ $crow->email }}" id="delete_cc" >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td align="center" colspan="12">No Data Found</td>
                            </tr>
                        @endif
                    </tbody>


                </table>
            </div>
            <div class="d-flex justify-content-center cust-page">

            </div>
        </div>
    </div>
    </div>
    </div>

    <script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>


    <script type="text/javascript">

    $("form#SendBulkMessageFrm").submit(function(e) {
        e.preventDefault();
        var message = CKEDITOR.instances['message'].getData();
        if(message == ''){
            alert('Please enter a message');
            return false;
        }
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

    $("form#redeem_form").submit(function(e) {
        e.preventDefault();
        var message = $('#voucher_message').val();
        var voucher_code = $('#voucher_code').val();
        var total_purchaged_amount = $('#total_purchaged_amount').val();
        if(voucher_code == ''){
            alert('Please enter voucher code');
            return false;
        }
        if(total_purchaged_amount == ''){
            alert('Please enter total purchaged amount');
            return false;
        }
        if(message == ''){
            alert('Please enter message');
            return false;
        }



        var form = $("#redeem_form");
        var formData = new FormData(this);
        $.ajax({
            url: form.attr("action")
            , type: 'POST'
            , data: formData
            , beforeSend: function() {
                $('#redeem_btn').prop('disabled', true);
                $('#redeem_btn').html('Please Wait');
            }
            , success: function(response) {
                var obj = jQuery.parseJSON(response);
                console.log(obj);
                if (obj.status == "success") {
                    toastr.success(obj.msg);
                    $('#redeem_btn').prop('disabled', false);
                    $('#redeem_btn').html('Redeem');
                    //$("#redeem_form")[0].reset();
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    toastr.error(obj.msg);
                    $('#redeem_btn').prop('disabled', false);
                    $('#redeem_btn').html('Save');
                }
            }
            , cache: false
            , contentType: false
            , processData: false
        });
    });

    $(document).ready(function() {

        $(document).on('click', '.redeem_form_model_btn', function () {

            var data_email = $(this).data('email');
            var data_code = $(this).data('offercode');
            var offeremail_id = $(this).data('offeremailid');
            var offertype = $(this).data('offertype');
            var offerid = $(this).data('offerid');

                $('#redeem_email').val(data_email);
                $('#voucher_code').val(data_code);
                $('#offeremail_id').val(offeremail_id);
                $('#offer_type').val(offertype);

                let modalElement = document.getElementById('redeem_form_model');
                $(modalElement).modal('show');

                $.ajax({
                url: '{{ route("get.offers.by.type") }}',
                type: 'GET',
                data: { offertype: offertype, offerid: offerid },
                success: function (response) {
                    //console.log('ggg - ', response.offers_data);
                    if (response.status === "success") {
                        $('#minimum_amount').html(response.offers_data);
                        //$.each(response.messages, function (index, val) {  });
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                error: function (xhr) {
                    toastr.error("Something went wrong.");
                }
            });

        });

        $(".redeem_all_offers_model").click(function () {
            $('#view_all_redeem_offers').html('');
            $('#view_all_redeem_offers_model').modal('show');
            var data_email = $(this).data('email');
            //var offer_type = $(this).data('offertype');
            $.ajax({
                url: '{{ route("get.all.redeem.offers.customer.club") }}',
                type: 'GET',
                data: { email: data_email },
                success: function (response) {
                    console.log('ggg - ', response.messages);
                    //   var msg = response.messages;
                    // let related = msg.offer ?? msg.giftvoucher ?? msg.shoppingcard;

                    if (response.status === "success") {
                        $.each(response.messages, function (index, val) {

                            if(val.offer_type == 'offers'){
                                var valoffer_type = `Offers`;
                            }else if(val.offer_type == 'gift_voucher'){
                                var valoffer_type = `Gift voucher`;
                            }else{
                                var valoffer_type = `Shopping card`;
                            }

                            if(val.redeem_status){
                                var buttonn = `<button type="button" class="btn btn-sm btn-warning"   data-dismiss="modal" aria-label="Close">
                                        ${val.redeem_status}
                                        </button>`;
                            }else{
                                var buttonn = `<button type="button" class="btn btn-sm btn-success redeem_form_model_btn" data-offertype="${val.offer_type}" data-offerid="${val.offer_id}"  data-offeremailid="${val.id}" data-offercode="${val.offer_code}" data-email="${val.user_email}" id="redeem_form_model_btn" data-dismiss="modal" aria-label="Close">
                                        Redeem Gift Voucher
                                        </button>`;
                            }

                            $('#view_all_redeem_offers').append(
                                `<li class="list-group-item">
                                        <b>${val.user_email}  </b>
                                        <p>Offer Type - ${valoffer_type}  </p>
                                        <p>Offer Code - ${val.offer_code}  </p>
                                        ${buttonn}
                                </li>`
                            );
                        });
                    } else {
                        toastr.error(response.msg);
                    }
                },
                error: function (xhr) {
                    toastr.error("Something went wrong.");
                }
            });
        });

        $(".view_messages").click(function () {
            $('#cc_messages').html('');
            $('#view_messages_model').modal('show');

            var data_email = $(this).data('email');

            $.ajax({
                url: '{{ route("get.all.customer.club.messages") }}',
                type: 'GET',
                data: { email: data_email },
                success: function (response) {
                    if (response.status === "success") {

                        // Handle success - populate modal content
                        $.each(response.messages, function (index, val) {
                            $('#cc_messages').append(
                                `<li class="list-group-item"><b>${val.fname} ${val.lname} - </b> ${val.message}</li>`
                            );
                        });
                        console.log(response.messages);
                    } else {
                        toastr.error(response.msg);
                    }
                },
                error: function (xhr) {
                    toastr.error("Something went wrong.");
                }
            });
        });

        $(".delete_cc").click(function () {

            var x = confirm("Are you sure you want to delete?");
            if(!x){
                return false;
            }

            var data_email = $(this).data('email');
            $.ajax({
                url: '{{ route("delete.customer.club") }}',
                type: 'GET',
                data: { email: data_email },
                success: function (response) {
                    if (response.status === "success") {
                        toastr.error(response.messages);
                        location.reload();
                    } else {
                        toastr.error("Something went wrong.");
                    }
                },
                error: function (xhr) {
                    toastr.error("Something went wrong.");
                }
            });

        });

        $(".close").click(function() {
             $('#view_messages_model').modal('hide');
             $('#redeem_form_model').modal('hide');
        });
        $(".redeem_close").click(function() {
             $('#view_all_redeem_offers_model').modal('hide');
        });

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

    });

        function changeStatus(statusdata, id) {
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
        }

        $('.show_confirm').click(function(e) {
            if (!confirm('Are you sure you want to delete this?')) {
                e.preventDefault();
            }
        });

    </script>
    <style>
        @media (max-width: 767px) {
            .d-none {
                display: unset !important;
            }

            .pull-right {
                margin-top: -40px;
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
</main>

@endsection
