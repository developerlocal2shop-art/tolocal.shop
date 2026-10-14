@extends('layouts.adminmaster')
@section('title','Distance Setting| Listing')
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
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Distance</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    
                    <a href="{{ route('admin.distance.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Distance</a>
                    
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
        <?php //echo "<pre>"; print_r($distancedata); echo "</pre>"; ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                <form action="" method="get" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-8">
                            <h3 class="box-title">Distance List</h3>
                        </div>

                            

                    </div>
                    </form>
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Distance</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; $datacount = count($distancedata); if ($datacount>0){?>
                                @foreach($distancedata as $key => $distancedatarow)

                                <?php if($distancedatarow->default_distance=="yes"){ ?>
                                    <tr bgcolor="#dfecc4">
                                <?php }else{ ?>
                                    <tr>
                                <?php } ?>
                                        <td>
                                            <?php /* echo ++$i; */ ?>
                                            {{ ($distancedata->currentpage()-1) * $distancedata->perpage() + $key + 1 }}
                                        </td>
                                        <td><?php if($distancedatarow->default_distance=="yes"){ ?><span style="font-weight: 1000;">{{ $distancedatarow->to_distance }}</span> <?php }else{ ?>{{ $distancedatarow->to_distance }}<?php }?></td>
                                        <td style="padding-left: 25px;"><?php if($distancedatarow->default_distance=="yes"){ ?><span style="font-weight: 1000;">{{ $distancedatarow->status }}</span> <?php }else{ ?>{{ $distancedatarow->status }}<?php }?></td>
                                        
                                        <td>
                                            <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/distance-edit", ['id' => @$distancedatarow->id]); ?>">EDIT</a>
                                            <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/distance-delete", ['id' => @$distancedatarow->id]); ?>">DELETE</a>
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
                    <?php /* ?>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $distancedata->appends($query)->links() }}
                        @else
                        {!! $distancedata->links() !!}
                        @endif
                    </div>
                    <?php */ ?>
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
