@extends('layouts.adminmaster')
@section('title','Normal Template| Listing')
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

<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-md-2">
                <h4 class="page-title">Normal Template</h4>
            </div>
            <div class="col-md-4">
                {{-- <div class="d-md-flex"> --}}
                    {{-- <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol> --}}
                    <a href="{{ route('admin.normaltemplate.create') }}" class="btn btn-info">Add New </a>
                {{-- </div> --}}
            </div>

            <div class="col-md-2 import-style">
                <a href="{{ asset('assets/client/format_Import_normaltemplate.xlsx') }}" title="Download Exc format">
                    <i style="font-size:25px;color: #e31212;" class="fa fa-download" aria-hidden="true"></i> Sample Format
                </a>
            </div>
             
            <div class="col-md-4">
                <form action="{{ route('admin.import.normaltemp') }}" method="POST" enctype="multipart/form-data">
                    @csrf                                
                    <input type="file" name="file" required="required" style="float: left;width: 70%; margin: -2px 5px;" class="form-control">
                    <button class="btn btn-success btn-sm">Import Data</button>
                </form>
            </div>
              
        <!-- /.col-lg-12 -->
    </div>

    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Normal Template List</h3>
                        </div>
                        <div class="col-md-4">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>

                            </div>
                        </div>
                    </div>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table id="tbl_results" class="table text-nowrap">
                            <thead>
                                <tr>
                                    <!-- <th class="border-top-0">#</th> -->
                                    <th class="border-top-0">Template Name</th>
                                    <th class="border-top-0">Status</th>
                                    <!-- <th class="border-top-0">Open Time</th>
                                    <th class="border-top-0">Close Time</th> -->
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($templatedata); if ($datacount>0){?>
                                @foreach($templatedata as $templatedatarow)
                                <tr>
                                    <td>{{ $templatedatarow->name }}</td>
                                    <td>
                                        @if ($user->hasPermission('status') == true)
                                        <input data-id="{{@$templatedatarow->id}}" class="toggle-class" type="checkbox" onChange="statusChange('<?php echo $templatedatarow->status ?>','<?php echo $templatedatarow->id ?>');" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ (@$templatedatarow->status=="Active") ? 'checked' : '' }}>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/normal-template-edit", ['id' => @$templatedatarow->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/normal-template-delete", ['id' => @$templatedatarow->id]); ?>">DELETE</a>
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
                    <div class="d-flex justify-content-center cust-page">

                    </div>
                </div>
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
