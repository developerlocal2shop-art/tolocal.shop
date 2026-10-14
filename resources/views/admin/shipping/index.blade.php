@extends('layouts.adminmaster')
@section('title','Shipping| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
    .toggle.btn {
        min-width: 90px;
    }
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

    .btn-danger {
        color: #fff !important;
    }

    .hotel-search {
        margin-right: 25px;
        width: 90%;
    }
    .col-md-4 {
        padding: 3px;
    }
    .row {
        align-items: center;
    }
    .modal-dialog {
        max-width: 647px;
    }
</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                <h4 class="page-title">Shipping Management </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    {{-- @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.shipping.create') }}" class="btn btn-info d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Amenity</a>
                    @endif --}}
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
                    <form action="{{ route('admin.shipping.indexsearch') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Shipping List</h3>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>

                                    
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Country</th>
                                    <th class="border-top-0">Code</th>
                                    <th class="border-top-0">Cities</th>
                                    {{-- <th class="border-top-0">Charges</th>
                                    <th class="border-top-0">Status</th> --}}

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($countries as $key => $row)
                                <tr>
                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($countries->currentpage()-1) * $countries->perpage() + $key + 1 }}</td>
                                    <td>{{ $row->name??'' }}</td>
                                    <td>{{ $row->code??'' }}</td>
                                    <td>
                                        @php
                                        $cities = $row->cities;
                                        $states = $row->states;
                                        @endphp

                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal{{ $i }}">
                                            Update shipping rate
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title" id="exampleModalLabel">{{ $row->name??'' }}</h2>

                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>

                                                    <span class="respondata"></span>

                                                    <form id="bookingform" class="contact-us-form" action="{{ route('admin.shipping.update') }}" method="post">
                                                    {{ csrf_field() }}

                                                        <div class="modal-body">

                                                        <div class="row">
                                                            <div class="col-md-12" ><h4 style="font-weight: bold;">States </h4></div>
                                                             <select class="form-control pt-2 required" required="" id="shipping_state" name="shipping_state" style="margin: 9px 0px 20px 20px; width: 90%;">
                                                                <option value="">Select State</option>
                                                                @foreach ($states as $val)
                                                                    <option value="{{ $val->id??'' }}">{{ $val->name??'' }}</option>
                                                                @endforeach
                                                             </select><br>
                                                             
                                                            <span class="cities_res_head row" style="display:none;">
                                                                <div class="col-md-3" ><h4 style="font-weight: bold;">Cities </h4></div>
                                                                <div class="col-md-3" ><h4 style="font-weight: bold;">Shipping Rate </h4></div>
                                                                <div class="col-md-4" ><h4 style="font-weight: bold;">Default Delivery Days </h4></div>
                                                                
                                                                <div class="col-md-2" ><h4 style="font-weight: bold;">Status </h4></div><hr>
                                                            </span>
                                                            
                                                            <span class="cities_res row">

                                                            </span>

                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary submitbtn">Save changes</button>
                                                        </div>

                                                    </form>

                                                </div>
                                            </div>
                                        </div>


                                    </td>
                                    <td>

                                    

                                        {{--</td>
                                     <td>
                                    @if ($user->hasPermission('status') == true)
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->id ?>','<?php echo $row->id ?>');">
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/amenity-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                        @endif --}}
                                        {{-- @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/amenity-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                    @endif 
                                    </td>--}}
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        
                        {!! $countries->links() !!}
                        

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
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>
<script type="text/javascript">
    function changeStatus(statusdata, id) {
        var status = (statusdata != '0') ? '0' : '1';
        console.log(status);
        
        //console.log("under statusChange : ",statusdata);
        //console.log("user id : ",id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/changeShippingStatus'
            , data: {
                'status': status
                , 'id': id
            }
            , success: function(data) {
                console.log(data.success);
            }
        });
    }


    $(document).on('change', '#shipping_state', function(e){
            e.preventDefault();

            var state_id = $(this).val();
            //alert(state_id);
            $.ajax({
                type: "POST",
                //, dataType: "json", 
                url: baseUrl + '/admin/get-cities-by-state', 
                data: {
                    'state_id': state_id,
                    _token: '{{csrf_token()}}'
                }, 
                success: function(data) {

                    $('.cities_res').html(' ');

                    if(data.cities.length == 0){
                            $('.cities_res').html('<p>No data!</p>');
                            $('.cities_res_head').hide();
                    }else{

                            $.each(data.cities, function(key, value) {
                                //alert(data.cities.length);
                                
                                    $('.cities_res_head').show();

                                    if(value.status == 1){ var checked = 'checked'; }else{ var checked = ''; }
                                    $(".cities_res").append(`
                                        <input type="hidden" name="city_id[]" id="city_id" value="` + value.id + `" >
                                        <div class="col-md-3">
                                        ` + value.name + ` :
                                        </div>
                                        <div class="col-md-3">
                                        <input type="number" name="shipping_charge[]" id="shipping_charge" value="` + value.shipping_amount + `" placeholder="Enter shipping charge" class="form-control" autocomplete=off> 
                                        </div>
                                        <div class="col-md-4">
                                        <input type="number" name="default_delivery_day[]" id="default_delivery_day" value="` + value.default_delivery_day + `" placeholder="Enter default delivery day" class="form-control" autocomplete=off> 
                                        </div>
                                        <div class="col-md-2">
                                        <input data-id="{{$val->id??''}}" name="status" data-toggle='toggle' class='toggle-class statususer' type="checkbox" data-onstyle='success' data-offstyle='danger' data-on="Active" data-off="InActive" ` + checked + ` onChange="changeStatus('` + value.status + `','` + value.id + `');">
                                        </div>
                                        <br>
                                        <hr>
                                    `);
                                
                            });

                    }
                    
                    console.log(data.cities.length);

                }
            });

    });


     $(document).on('click', '.submitbtn', function(e) {
                e.preventDefault();

                $(document).find('.required').removeClass('is-invalid');
                $(document).find('span.red').remove();

                var obj = $(this);
                let requestTxt = $(this).attr('process-text');
                requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
                var oldhtml = $(this).html();

                var form = $(this).closest("form");
                var url = form.attr('action');
                var method = form.attr('method');
                var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
    <span class="">${requestTxt}...</span>`;

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
                        //console.log(response);

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

                                //$('.respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');
                                toastr.success(response.message);

                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 5000);
                            }

                        } else {

                            if (typeof(response.message) != "undefined") {
                                //console.log(response.message);
                                //$("#bookingform")[0].reset();

                                toastr.success(response.message);
                                //$('.respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');
                               
                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 5000);

                            }

                        }
                        obj.attr("disabled", false);
                        obj.html(oldhtml);
                    }
                    , error: function(err) {}
                });

            });



</script>

@endsection
