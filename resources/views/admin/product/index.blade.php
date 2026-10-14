@extends('layouts.adminmaster')
@section('title','Product| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php $currenturl = url()->current();
$currenturl = explode("/",$currenturl);
if(in_array("directory-product", $currenturl)){
    $arrendvalue = end($currenturl);
    $directoryName = Helper::getDirectory($arrendvalue);
    $directoryProuctCount = Helper::getDirectoryProductCount($arrendvalue);
}else{
    $arrendvalue = "";
}

?>
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

    #status{
        width:58% !important;
    }

    @media(max-width:767px)
    {
        .white-box {
         padding: 10px;
        }

        .hotel-search {
            margin-bottom: 10px;
        }

        select#status {
            margin-bottom: 10px;
        }
    }

    @media(max-width:320px)
    {
        .hotel-search {
            margin-right: 0!important;
            width: 100%!important;
            margin-bottom: 10px;
        }

        #status{
            width:100% !important;
        }
    }
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Product<?php if(!empty($directoryName)){ echo " for Directory: ".$directoryName[0]->title; } ?></h4>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.product.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Product</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
                <form action="{{ route('admin.product.indexsearch') }}" method="get" class="">
                        
                    <div class="row">
                        <div class="col-md-2">
                            <h3 class="box-title">Product List</h3>
                        </div>

                            <div class="col-md-10">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                        <input type="text" name="searchdate"  autocomplete="off" value="{{ $searchDateAct }}" id="searchdate" class="form-control" placeholder="Search Activation Date" >
                                        </div>
                                    </div>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                        <input type="text" name="searchdateexpiry"  autocomplete="off" value="{{ $searchDateExp }}" id="searchdateexpiry" class="form-control" placeholder="Search Expiry Date" >
                                        </div>
                                    </div>
                                    <select name="status" id="status" class="form-control" data-allow-clear="true" >
                                        <option value="" >Sort By Status</option>
                                        <option value="active" {{ 'active'== $searchstatus ? 'selected':''}}>Active</option>
                                        <option value="inactive" {{ 'inactive'== $searchstatus ? 'selected':''}}>Inactive</option>
                                    </select>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ isset($textsearch)?$textsearch:'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                    </form>
                    <div class="table-responsive">
                    <a class="btn btn-danger d-md-block pull-left ms-3 hidden-xs hidden-sm waves-effect waves-light text-white" id="deleteAllSelectedProducts"  style="margin-top:10px;">
                        Bulk Delete 
                    </a>
                    @if(in_array("directory-product", $currenturl))
                        @if($directoryProuctCount > 0 )
                            <a class="btn btn-primary btn-sm d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white"  style="margin-top:10px;">
                            Total Products : {{ $directoryProuctCount }} 
                            </a>
                        @endif
                    @else(Helper::totalAdminProducts() > 0)
                        <a class="btn btn-primary btn-sm d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white"  style="margin-top:10px;">
                        Total Products : {{ Helper::totalAdminProducts() }} 
                        </a>
                    @endif
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0" style="width: 70px;">
                                    <input type="checkbox" id="checkAll" class="checkall"></th>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0 notranslate"> ENA No. </th>
                                    <th class="border-top-0 notranslate"> Type</th>
                                    <th class="border-top-0">Product</th>
                                    
                                    <th class="border-top-0">Purchase</th>
                                    <th class="border-top-0">Sales</th>
                                    <th class="border-top-0">Parent Category</th>
                                    <th class="border-top-0">Stock</th>
                                    {{-- <th class="border-top-0">Similar Products</th> --}}
                                    <th class="border-top-0">Visitors | Likes</th>
                                    <th class="border-top-0">Expiry Date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $defaultCurrency = Helper::defaultCurrencyData();
                            if(session()->has('currency_code')){
                                $currencySymbol = session()->get('currency_code')['currsymbol'];
                                $currencycode = session()->get('currency_code')['currcode'];
                            }else{
                                $currencySymbol = $defaultCurrency[0]->symbol;
                                $currencycode = $defaultCurrency[0]->code;
                            }

                                $i = 0; $datacount = count($productdata); if ($datacount>0){?>
                                @foreach($productdata as $key => $productdatarow)
                                @php
                                $getProductpriceId =  Helper::getProductpriceId($productdatarow->id,$currencycode);
                                //dd($getProductpriceId);
                                @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="ids" class="cb-element" value="{!! $productdatarow->id !!}" />
                                        </td>
                                        <td>
                                            <!-- <?php echo ++$i; ?> -->
                                            {{ ($productdata->currentpage()-1) * $productdata->perpage() + $key + 1 }}
                                            <a class="btn btn-xs" target="_blank" href="<?php echo url("product/detail", ['id' => @$productdatarow->id,'slug' => @$productdatarow->seo_url]); ?>"><i class="fas fa-external-link-alt" aria-hidden="true"></i></a>
                                        </td>
                                        <td>
                                            {{ $productdatarow->ena??'N/A' }} 
                                        </td>
                                        <td>
                                            {{ $productdatarow->product_number_type??'N/A' }}
                                        </td>
                                        <td>
                                            {{ $productdatarow->name }}
                                            @if($productdatarow->is_free_product == 1)
                                                <span class="free_product">Free</span>
                                            @endif<br>

                                            @php 
                                                $imagename_thumb = Helper::getProductImage($productdatarow->id);
                                            @endphp

                                            @if(count($imagename_thumb) > 0)
                                                <div class="thumbnail">
                                                    <!-- <img src="{{ env('IMAGE_PATH').$imagename_thumb[0]->path??'' }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" /> -->
                                                    <img src="{{ env('IMAGE_PATH').'images/product/'.$imagename_thumb[0]->name_thumbnail.'.webp' }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                                </div>
                                            @else
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if($productdatarow->purchase_price)
                                                {{ $currencySymbol }} {{ Helper::numberCurrencyformat($productdatarow->purchase_price??'N/A') }}
                                            @else
                                            N/A
                                            @endif
                                        </td>    
                                        <td>{{ $currencySymbol }} {{ $getProductpriceId->regular_price??'N/A' }}</td>
                                        <td>
                                            @if($productdatarow->cat_id)
                                                @php
                                                $parentcategory = Helper::getproductParentCategory($productdatarow->cat_id);
                                                //echo $parentcategory[0]->name;
                                                echo isset($parentcategory[0]->name)?($parentcategory[0]->name):'None';
                                                @endphp
                                            @else
                                            None
                                            @endif
                                        </td>
                                        
                                        <td>
                                            
                                            @if($productdatarow->stock == '0' || $productdatarow->outofstock=='outofstock')
                                                <b style="color:#e31212">OUT OF STOCK</b>
                                            @elseif($productdatarow->in_store_only=='in_store_only')
                                                <b style="color:#e39b12">IN STORE ONLY</b>
                                            @else
                                                <b style="color:#12e34d">IN STOCK</b>
                                            @endif
                                            @if($productdatarow->stock != '0' && $productdatarow->outofstock!='outofstock')
                                            ( {{ $productdatarow->stock }} )
                                            @endif
                                        </td>
                                        {{-- <td>{{ $productdatarow->equal_product??'N/A' }}</td> --}}
                                        <td>
                                        @if(count(Helper::CountVisitorsId('product',$productdatarow->id)) != 0)
                                            {{ count(Helper::CountVisitorsId('product',$productdatarow->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                        <b style="margin: 0px 5px 5px 46px;">|</b>
                                        @if(count(Helper::CountLikeData('product',$productdatarow->id)) != 0)
                                            {{ count(Helper::CountLikeData('product',$productdatarow->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                        </td>
                                       <td>{{ $productdatarow->closing_date ? \Carbon\Carbon::parse($productdatarow->closing_date)->format('d-m-Y') : 'N/A' }}</td>

                                        <td>
                                            {{-- @if ($user->hasPermission('status') == true) --}}
                                            <input data-id="{{$productdatarow->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($productdatarow->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $productdatarow->status ?>','<?php echo $productdatarow->id ?>');">
                                            {{-- @endif --}}
                                        </td>
                                        
                                        <td>
                                        @if ($user->hasPermission('edit') == true)
                                            <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/product-edit", ['id' => @$productdatarow->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/product-delete", ['id' => @$productdatarow->id]); ?>">DELETE</a>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            <?php }else{ ?>
                                    <tr>
                                        <td align="center" colspan="10">No Data Found</td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $productdata->appends($query)->links() }}
                        @else
                        {!! $productdata->links() !!}
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
        if (!confirm('Are you sure you want to permanent delete this?')) {
            e.preventDefault();
        }
    });
</script>

<script type="text/javascript">
    function changeStatus(statusdata,id){
        var status = (statusdata!='Inactive')?'Inactive':'Active';
        console.log(status);
        //console.log("under statusChange : ",statusdata);
        //console.log("user id : ",id);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeProductStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
            }
        });
    }

</script>
<script>
    $(document).ready(function() {

        $(function() {

            $("#searchdate").datepicker({
                //defaultDate:fromdate,
                dateFormat: 'yy-mm-dd'
            });
            $("#searchdateexpiry").datepicker({
                //defaultDate:todate,
                dateFormat: 'yy-mm-dd'
            });
        });
    })


    /*......Bulk Delete.........*/
    $("#checkAll").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $('#deleteAllSelectedProducts').click(function(e){
        e.preventDefault();
        var allids = [];

        $("input:checkbox[name=ids]:checked").each(function(){
            allids.push($(this).val());
            // allids.push($(this).attr('data-id'));
        });
        //alert(allids);
        if(allids.length <= 0){
            
            toastr.error('Please select at least one record');
            return false;
        }
        else{
            var check = confirm("Are you sure you want to permanent delete this records!");
            
            if(check==true){
                var selected_values = allids.join(",");
                
                $.ajax({
                    url: baseUrl + '/selectedmultipleproductsdelete',
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
