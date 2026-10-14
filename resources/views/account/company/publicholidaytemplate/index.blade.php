@extends('layouts.client.company')
@section('title','Public Holiday')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
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
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Offline Reports</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<div class="container margin_60_35">
    
    <div class="row">
        <x-client.holiday_template_menu/>
        <div class="col-lg-9">
            <div class="">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
                        <h4 class="page-title">Public Holiday Template List</h4>
                    </div>
                    <div class="col-lg-6 col-sm-8 col-md-6 col-xs-12">
                        <div class="d-md-flex">
                            <a href="{{ route('company.template.create') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Template</a>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <div class="white-box">
                <div class="table-responsive">
                    <table id="tbl_results" class="table text-nowrap">
                        <thead>
                            <tr>
                                <th class="border-top-0">Template Name</th>
                                <th class="border-top-0">Status</th>
                                <th class="border-top-0">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; $datacount = count($templatedata); if ($datacount>0){?>
                            @foreach($templatedata as $templatedatarow)
                            <tr>
                                <td>{{ $templatedatarow->name }}</td>
                                <td>
                                <?php /* @if ($user->hasPermission('status') == true) */ ?>
                                <input data-id="{{@$templatedatarow->id}}" class="toggle-class" type="checkbox" onChange="statusChange('<?php echo $templatedatarow->status ?>','<?php echo $templatedatarow->id ?>');" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ (@$templatedatarow->status=="Active") ? 'checked' : '' }}>
                                <?php /* @endif */ ?>
                                </td>
                                <td>
                                    <?php /* @if ($user->hasPermission('edit') == true) */ ?>
                                    <a class="btn btn-primary btn-sm" href="<?php echo url("/company/public/template-edit", ['id' => @$templatedatarow->id]); ?>">EDIT</a>
                                    <?php /* @endif
                                    @if ($user->hasPermission('delete') == true) */ ?>
                                    <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/public/template-delete", ['id' => @$templatedatarow->id]); ?>">DELETE</a>
                                    <?php /* @endif */ ?>
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
                <div class="d-flex justify-content-center cust-page">
        
                </div>
            </div>
        </div>
    </div>

    
</div>


<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>
@endsection
