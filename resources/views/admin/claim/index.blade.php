@extends('layouts.adminmaster')
@section('title','Claim| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
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
                <h4 class="page-title">Claim<?php if(!empty($directoryName)){ echo " for Directory: ".$directoryName[0]->title; } ?></h4>
                <Span></Span>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
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
            .hotel-search { margin-right: 25px; width:200px;}
        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="{{ route('admin.claim.indexsearch') }}" method="get" class="">

                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Claim List</h3>
                        </div>

                            <div class="col-md-4">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ isset($textsearch)?$textsearch:'' }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <input type="hidden" name="directory_id" value="" />
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
                                    <th class="border-top-0">Directory Name</th>
                                    <th class="border-top-0">Claim Subject</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($claim); if ($datacount>0){?>
                                    @foreach($claim as $key => $claimdatarow)
                                    <?php //echo "<pre>"; print_r($bookingdatarow); ?>
                                        <tr>
                                            <!-- <td><?php echo ++$i; ?></td> -->
                                            <td> {{ ($claim->currentpage()-1) * $claim->perpage() + $key + 1 }}</td>
                                            <td>
                                                @php
                                                $directory = Helper::getDirectory($claimdatarow->directory_id);
                                                echo isset($directory[0]->title)?$directory[0]->title:"None";
                                                @endphp
                                            </td>
                                            <td>{{ $claimdatarow->subject }}</td>

                                            <td>
                                            @if ($user->hasPermission('view') == true)
                                                <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/claim/view",['id' => @$claimdatarow->id]); ?>">
                                                VIEW
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
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $claim->appends($query)->links() }}
                        @else
                        {!! $claim->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

@endsection
