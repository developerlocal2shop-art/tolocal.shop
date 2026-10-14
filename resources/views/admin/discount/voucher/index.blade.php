@extends('layouts.adminmaster')
@section('title','Voucher| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php $currenturl = url()->current();
$currenturl = explode("/",$currenturl);
if(in_array("directory-voucher", $currenturl)){
    $arrendvalue = end($currenturl);
    $directoryName = Helper::getDirectory($arrendvalue);
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

</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Voucher<?php if(!empty($directoryName)){ echo " for Directory: ".$directoryName[0]->title; } ?></h4>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.discount.voucher.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Voucher</a>
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

        <style>
            .hotel-search {
                margin-right: 25px;
                width: 200px;
            }

        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('admin.discount.voucher.indexsearch') }}" method="get" class="">

                        <div class="row">
                            <div class="col-md-2">
                                <h3 class="box-title">Voucher List</h3>
                            </div>
                            <div class="col-md-1">
                                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span></span> <i class="fa fa-caret-down"></i>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <input type="text" title="Search Activation Date" name="searchdate" id="searchdate" value="{{ isset($searchDateAct)?$searchDateAct:'' }}" class="form-control" placeholder="Search Activation Date" aria-label="Search" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <input type="text" title="Search Expiry Date" name="searchdateexpiry" id="searchdateexpiry" value="{{ isset($searchDateExp)?$searchDateExp:'' }}" class="form-control" placeholder="Search Expiry Date" aria-label="Search" aria-describedby="basic-addon2">
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $data }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input type="hidden" name="directory_id" value="{{ $arrendvalue }}" />
                                            <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Voucher Name</th>
                                    <th class="border-top-0">Voucher Code</th>
                                    <th class="border-top-0">Discount Type</th>
                                    <th class="border-top-0">Discount Value</th>
                                    <th class="border-top-0">Activation Date</th>
                                    <th class="border-top-0">Expiry Date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($voucher); if ($datacount>0){?>
                                @foreach($voucher as $key => $voucherdata)
                                <tr>
                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($voucher->currentpage()-1) * $voucher->perpage() + $key + 1 }}</td>
                                    <td>{{ $voucherdata->voucher_name }}</td>
                                    <td>{{ $voucherdata->voucher_code }}</td>
                                    <td>{{ $voucherdata->discount_type }}</td>
                                    <td>{{ $voucherdata->discount_value }}</td>
                                    <td>{{ $voucherdata->activation_date }}</td>
                                    <td>{{ $voucherdata->expiry_date }}</td>
                                    <td>{{ $voucherdata->status }}</td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/discount/voucher/voucher-edit", ['id' => @$voucherdata->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/discount/voucher/voucher-delete",['id' => @$voucherdata->id]); ?>">DELETE</a>
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
                        {{ $voucher->appends($query)->links() }}
                        @else
                        {!! $voucher->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>



<script type="text/javascript">

        $(document).ready(function() {

            $(function() {

                $( "#searchdate" ).datepicker({
                    dateFormat: 'yy-mm-dd'
                    //defaultDate:"YYYY-MM-DD"
                });
                $( "#searchdateexpiry" ).datepicker({
                    //defaultDate:"YYYY-MM-DD"
                    dateFormat: 'yy-mm-dd'
                });
            });
        })

		$(function() {

            $(".ranges ul li").click(function() {
                $('input[name="reportrange"]').val('');
                $('input[name="reportrange"]').attr("placeholder","Please select date range");
            });

			var start = moment().subtract(29, 'days');
			var end = moment();

			function cb(start, end) {
				//$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#searchdate').val(start.format('YYYY-MM-DD'));
                $('#searchdateexpiry').val(end.format('YYYY-MM-DD'));
                searchdate
			}

			$('#reportrange').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
				startDate: start,
				endDate: end,
				ranges: {
				   'Today': [moment(), moment()],
				   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				   'This Month': [moment().startOf('month'), moment().endOf('month')],
				   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			}, cb);

            $('input[name="reportrange"]').val('');
            $('input[name="reportrange"]').attr("placeholder","Please select date range");

			//cb(start, end);

		});

</script>
<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>


@endsection
