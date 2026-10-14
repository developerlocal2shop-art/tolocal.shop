@extends('layouts.client.company')
@section('title','Company products')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    .pagination a, .pagination span {
        color: rgba(0, 0, 0, 0.6);
        font-size: 16px;
        height: 35px;
        line-height: 35px;
        float: left;
        width: 35px;
        text-align: center;
    }
    .product-count {
        margin: 50px auto;
        position: absolute;
        border-top: 1px solid #ccc;
    }
    .modal-content {
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); /* Add shadow effect */
    }
    .modal-content {
        background-color: #000000e8;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Company's Products</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- /sub_header -->
<div class="AJloader" id="AJloader"></div>




<!-- Main page -->
<main>
    <div class="container margin_60_35">

        <div class="modal fade" id="progressModal" tabindex="-1" aria-labelledby="progressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="progressModalLabel">Loading...</h5>
                    </div>
                    <div class="modal-body">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100" 
                                 style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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

        <div class="white-box vendor-product product-infoicon">

            <div class="row">


                <div class="col-md-4 col-xl-6">
                    <h3 class="box-title" style="display: flex;">
                        Products  &nbsp;&nbsp;&nbsp;&nbsp; 
                        <span style="font-size: 15px;margin: 10px 10px;">
                            (<a style="color: #28a745;" href="{{ asset('assets/client/INSTRUCTIONs_For_Product_import.docx') }}"> INSTRUCTIONs for Product import </a> <i class="fas fa-download" aria-hidden="true"></i>  )
                        </span>
                    @if(!empty($instruction->message))
                    <div id="helpicon">
                        <span id="helpiconText">{{$instruction->message}}</span>
                        <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                    </div>
                    @endif
                    </h3>
                </div>
                <div class="col-md-2 col-xl-2">
                    <a href="{{ route('company.shipping.index') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Shipping Management</a>
                
                </div>
                <div class="col-md-2 col-xl-2">
                    <a href="{{ route('company.attributes.index') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Product Attributes</a>
                </div>
                <div class="col-md-2 col-xl-2">
                    <?php
                            $getFeatureUsesValue = Helper::getFeatureUsesValue('products');
                            if($getFeatureUsesValue <= count($productdata)){
                        ?>
                    <a onclick="alert('Please upgrade profile. you can not add products greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white"> Add Product</a>
                    <?php }else{ ?>
                    @if ($user->hasPermission('add') == true)

                        @if($user->stripe_connect == null)
                        <a onclick="alert('You will need to connect with stripe first.')" class="btn btn-primary d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
                            Add Product
                        </a>
                        
                        @else
                            <a href="{{ route('company.addproduct') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">
                            Add Product
                            </a>
                        @endif
                    
                   
                    @endif
                    <?php } ?>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>

                <div class="col-lg-2 col-md-12">
                    @php
                    $getFeatureUsesValueimport = Helper::getFeatureUsesValue('import');
                    $getFeatureUsesValueproducts = Helper::getFeatureUsesValue('products');
                    @endphp
                    <p> Product import limit <b>{{ $getFeatureUsesValueimport }}</b> rows at a time </p> <hr>
                    <p>Total Product limit - <b>{{ $getFeatureUsesValueproducts }}</b> </p>
                </div>
                @php
                    $showImportfile = Helper::showImportFile();

                   // dd($showImportfile);
                @endphp
                @if(!empty($showImportfile))
                <div class="col-lg-3 col-md-7">
                    <form action="{{ route('company.import') }}" id="import_data" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (Helper::getFeatureUses('import') == true)
                        <input type="file" name="file" required class="form-control required" >

                        <button class="btn btn-success btn-sm request_vendor" style="margin: 4px;">Import Data</button>
                        @endif
                    </form>
                    {{-- <div class="progress" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0;"></div>
                    </div> --}}
                    
                </div>
                @endif
                <div class="col-lg-2 col-md-3 import-style">
                    @if (Helper::getFeatureUses('import') == true)
                        <?php
                        if (Auth::user()->import_optional == 1) {
                        ?>
                            <a href="{{ asset('assets/client/format_Import_products.xlsx') }}" title="Download Exc format">
                                <i style="font-size:18px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Extended Sample Format
                            </a>
                        <?php } else{ ?>
                            <a href="{{ asset('assets/client/format_Import_products_basic.xlsx') }}" title="Download Exc format">
                                <i style="font-size:18px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Basic Sample Format
                            </a>
                        <?php } ?>
                    @else
                        <?php
                        if (Auth::user()->import_optional == 1) {
                        ?>
                            <a onclick="alert('Need to upgrade package for import product.')" title="Download Exc format">
                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Extended Sample Format
                            </a>
                        <?php } else{ ?>
                            <a  onclick="alert('Need to upgrade package for import product.')" title="Download Exc format">
                                <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Basic Sample Format
                            </a>
                        <?php } ?>
                    @endif
                </div>


                <div class="col-lg-1 col-md-2">
                    <div id="reportrange" style="background: #fff; cursor: pointer; padding: 10px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>
                </div>
                
                
                <div class="col-lg-4 col-md-12">
                    <form action="{{ route('export') }}" method="get" enctype="multipart/form-data">
                        <input type="text" name="fromdate" style="float: left; width: 49%;" autocomplete="off" value="{{ session('filterdata')['fromdate']??'' }}" id="fromdate" required class="form-control placeholder-lang-css" placeholder="From date">
                    &nbsp;
                        <input type="text" name="todate" style="float: right; width: 50%;" autocomplete="off" value="{{ session('filterdata')['todate']??'' }}" id="todate" required class="form-control placeholder-lang-css" placeholder="To date">
                      <br><br>
                        
                        <button class="btn btn-warning btn-sm">Export Data</button>
                    </form>
                    {{-- <a class="btn btn-warning" href="{{ route('export') }}">Export Data</a> --}}
                </div>
                
                <div class="col-md-12">
                    <form action="{{ route('company.products') }}" method="get" class="" id="searchproductdata" style="display:block;">
                    
                        <div class="d-md-flex">
                            
                            <input type="text" name="searchdate" style="float: left; margin-right:10px;"  autocomplete="off" value="{{ $searchDateAct }}" id="searchdate" class="form-control placeholder-lang-css" placeholder="Search Activation Date" >
                            
                            <input type="text" name="searchdateexpiry" style="float: right;margin-right:10px;" autocomplete="off" value="{{ $searchDateExp }}" id="searchdateexpiry" class="form-control placeholder-lang-css" placeholder="Search Expiry Date" >

                            <select name="status" id="status" class="form-control" data-allow-clear="true">
                                <option value="" >Sort By Status</option>
                                <option value="active" {{ 'active'== $searchstatus ? 'selected':''}}>Active</option>
                                <option value="inactive" {{ 'inactive'== $searchstatus ? 'selected':''}}>Inactive</option>
                            </select>
                            
                            <input type="text"  name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <input class="btn btn-primary" name="search" id="filterPro" type="submit" value="Go!" />
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
           

            <div class="table-responsive">
                <a class="btn btn-danger d-md-block pull-left ms-3 hidden-xs hidden-sm waves-effect waves-light text-white" id="deleteAllSelectedProducts"  style="margin-top:7px;">
                Bulk Delete 
                </a>

                
                @if ($productdata->count() > 0)
                    <a class="btn btn-primary btn-sm d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white total-product"  style="margin-top:10px;">
                        Showing {{ $productdata->firstItem() }} - {{ $productdata->lastItem() }} of {{ $productdata->total() }} products
                    </a>
                @endif

                
                 
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0" style="width: 50px;">
                            <input type="checkbox" id="checkAll" class="checkall">All</th>
                            <th class="border-top-0 notranslate">ID</th>
                            <th class="border-top-0 notranslate"> &nbsp;&nbsp;&nbsp; ENA No.</th>
                            
                            <th class="border-top-0" style="width: 90px;">Name</th>
                            <th class="border-top-0">Product</th>
                            <th class="border-top-0">Stock</th>
                            <th class="border-top-0">Similar Products</th>
                            <th class="border-top-0">Visitors</th>
                            <th class="border-top-0">Likes</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

								$i = 0; $datacount = count($productdata); if ($datacount>0){?>
                        @foreach($productdata as $productdatarow)
                        <tr>
                            <td style="width: 50px;">
                                <input type="checkbox" name="ids" class="cb-element" value="{!! $productdatarow->id !!}" />
                                <a class="" target="_blank" href="<?php echo url("product/detail", ['id' => @$productdatarow->id,'slug' => @$productdatarow->seo_url]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                                
                            </td>
                            <td>{{ $productdatarow->id }}</td>
                            <td>
                                {{ $productdatarow->ena??'N/A' }}
                            </td>
                            
                            <td>{{ $productdatarow->name }}
                            @if($productdatarow->is_free_product == 1)
                                <span class="free_product">Free</span>
                            @endif 
                            </td>
                            <td>
                          
                                <?php if($productdatarow->image[0]->path??''){  ?>
                                <div class="thumbnail"> 
                                    <img src="{{ env('IMAGE_PATH').$productdatarow->image[0]->path.'.webp' }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                                <?php }else{ ?>
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                <?php } ?>
                            </td>
                            <td>{{ $productdatarow->stock }}</td>
                            <td>{{ $productdatarow->equal_product??'N/A' }}</td>
                            <td>
                            @if(count(Helper::CountVisitorsId('product',$productdatarow->id)) != 0)
                                {{ count(Helper::CountVisitorsId('product',$productdatarow->id)) }}
                            @else
                            {{ 0 }}
                            @endif
                            </td>
                            <td>
                            @if(count(Helper::CountLikeData('product',$productdatarow->id)) != 0)
                                {{ count(Helper::CountLikeData('product',$productdatarow->id)) }}
                            @else
                            {{ 0 }}
                            @endif
                            </td>
                            <td>
                                {{-- {{ $productdatarow->status }} --}}
                                <input data-id="{{$productdatarow->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($productdatarow->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $productdatarow->status ?>','<?php echo $productdatarow->id ?>');">
                            </td>

                            <td>
                                @if ($user->hasPermission('edit') == true)
                                <a target="_blank" class="btn btn-primary btn-sm" href="<?php echo url("/company/product-edit", ['id' => @$productdatarow->id]); ?>">EDIT</a>
                                @endif
                                @if ($user->hasPermission('delete') == true)
                                <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/product-delete", ['id' => @$productdatarow->id]); ?>">DELETE</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <?php }else{ ?>
                        <tr>
                            <td align="center" colspan="4">No Data Found</td>
                        </tr>
                        <?php } ?>

                    </div>
                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center vendor-pro-pre-next cust-page">

                        <div class="d-flex justify-content-center cust-page">
                        {{-- @if(isset($query))
                        Total products: {{ $productdata->total() }} - {{ $productdata->appends($query)->links() }}
                        @else
                        Total products: {{ $productdata->total() }} - {!! $productdata->links() !!}
                        @endif --}}

                        @if ($productdata->count() > 0)
                            {{-- <div class="pagination">
                                {{ $productdata->appends(Request::except('page'))->links() }}
                            </div> --}}
                           
                            <div class="d-flex justify-content-center notranslate">
                                <div class="product-count">
                                    Showing {{ $productdata->firstItem() }} - {{ $productdata->lastItem() }} of {{ $productdata->total() }} products
                                </div>
                                {{$productdata->links('pagination::bootstrap-4')}}
                                
                            </div>
                        @else
                            {{-- <p>No products found.</p> --}}
                        @endif
                        

            </div>
        </div>
    </div>


    <script type="text/javascript">
        $('.show_confirm').click(function(e) {
            if (!confirm('Are you sure you want to permanent delete this?')) {
                e.preventDefault();
            }
        });

        $('#filterPro').click(function(e) {
           var searchdate = $('#searchdate').val();
           var searchdateexpiry = $('#searchdateexpiry').val();
           var status = $('#status').val();
           var search = $('#search').val();
           if((searchdate == '') && (searchdateexpiry == '') && (status == '') && (search == '')){
               toastr.error("Please fill at least one field.");
               return false;
           }
        });

        $(function() {

            $(".ranges ul li").click(function() {
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder", "Please select date range");
            });

            var start = moment().subtract(29, 'days');
            var end = moment();

            function cb(start, end) {
                //$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#fromdate').val(start.format('YYYY-MM-DD'));
                $('#todate').val(end.format('YYYY-MM-DD'));
                
            }

            $('#reportrange').daterangepicker({
                singleDatePicker: true
                , showDropdowns: true
                , startDate: start
                , endDate: end
                , ranges: {
                    'Today': [moment(), moment()]
                    , 'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
                    , 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
                    , 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                    , 'This Month': [moment().startOf('month'), moment().endOf('month')]
                    , 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                }
            }, cb);

            //$('input[name="reportrange"]').val('');
            //$('input[name="reportrange"]').attr("placeholder", "Please select date range");
            //cb(start, end);
        });

    </script>

    <script type="text/javascript">
        function changeStatus(statusdata, id) {
            var status = (statusdata != 'Inactive') ? 'Inactive' : 'Active';
            console.log(status);
            //console.log("under statusChange : ",statusdata);
            //console.log("user id : ",id);
            $.ajax({
                type: "GET"
                , dataType: "json"
                , url: baseUrl + '/changeVendorProductStatus'
                , data: {
                    'status': status
                    , 'id': id
                }
                , success: function(data) {
                    console.log(data.success);
                }
            });
        }

    </script>
    <script>
        $(document).ready(function() {


            $(document).on('click', '.request_vendor', function(e) {
                e.preventDefault();
                //$('.AJloader').show();
                $("#progressModal").modal("show");
                //const modal = new bootstrap.Modal(document.getElementById('progressModal'));
                //modal.show();

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
                    type: method,
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: allformdata,
                    mimeType: "multipart/form-data",
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = (evt.loaded / evt.total) * 100;
                                $('.progress-bar').width(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    beforeSend: function() {
                        obj.attr("disabled", true);
                        obj.html(loader);
                        setTimeout(function() {
                            $("#progressModal").modal("show");
                        }, 1000);
                        $('.progress').show();
                    },

                    success: function(response) {
                        //modal.hide();
                        setTimeout(function() {
                            $("#progressModal").modal("hide");
                            $('.AJloader').hide();
                            $('.progress').hide();
                            $('.progress-bar').width('0%');

                        }, 1000);
                        
                        
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
                                toastr.error(response.message);
                            }

                        } else {
                            if (typeof(response.message) != "undefined") {

                                $("#import_data")[0].reset();
                                toastr.success(response.message);

                                setTimeout(function() {
                                    location.reload();
                                }, 3000);

                            }

                        }
                        obj.attr("disabled", false);
                        obj.html(oldhtml);
                    },
                    error: function(err) {
                        $('.progress-bar').width('0%');
                        setTimeout(function() {
                            $("#progressModal").modal("hide");
                            $('.progress').hide();
                        }, 1000);
                    }
                });

            });



             @if (Auth::check())
                @php
                $user = auth()->user();
                @endphp
                @if($user->stripe_connect == null)
                toastr.error('You will need to connect with <span class="notranslate" > stripe </span> first.')
                @endif
            @endif

            $('#fromdate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#todate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});

            $('#searchdate').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            $('#searchdateexpiry').mask('9999-99-99',{placeholder:"yyyy-mm-dd"});
            
                $("#searchdate").datepicker({
                    //defaultDate:fromdate,
                    dateFormat: 'yy-mm-dd'
                });
                $("#searchdateexpiry").datepicker({
                    //defaultDate:todate,
                    dateFormat: 'yy-mm-dd'
                });
            $('.ui-datepicker').addClass('notranslate');

        })
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
                toastr.error('Please select at least one record');
                return false;
            }else{
                var check = confirm("Are you sure you want to permanent delete this records!");
                if(check==true){
                    var selected_values = allids.join(",");
                    $.ajax({
                        url: baseUrl + '/selectedproductsdelete',
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
    </div>
    <!-- /row -->

    <!-- /row -->
    </div>
    <!-- /container -->
</main>
<style>
    @media (max-width: 767px) {
        .d-none {
            display: unset !important;
        }

        .pull-right {
            margin-top: -36px;
        }

        .import-style {
            margin-top: 8px;
        }
    }
    .free_product {
        color: #fff;
        background: #f33155;
        border-radius: 1px 27px;
        padding: 1px 5px;
        margin: -5px 0px;
        position: absolute;
        font-size: 11px;
    }
    input[type=checkbox], input[type=radio] {
        margin-right: 7px;
    }
    #helpicon {
        top:8px;
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #helpiconText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 7%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #helpicon:hover:after {
        top: 14px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }

    #helpiconText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #helpicon:hover #helpiconText {
        visibility: visible;
        opacity: 1;
        top: 62%;
        z-index: 99;
    }
    span.red.invalid-feedback {
        position: absolute;
        bottom: 50px;
        background: #fff !important;
        color: red;
    }
    .total-product { margin: 0px 0px;  }

    tr th:first-child, tr td:first-child {
        background: radial-gradient(#e7dbdb, transparent);
        word-break: break-word;
        white-space: normal;
    }
    
    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #dee2e6;
        background: radial-gradient(#e7dbdb, transparent);
    }
    .table td, .table th {
        padding: 15px 10px 15px 10px;
        border-color: #b1afaf;
    }
    .table {
        width: 100%;
        margin-bottom: 1rem;
        background-color: transparent;
    }
</style>
@endsection
