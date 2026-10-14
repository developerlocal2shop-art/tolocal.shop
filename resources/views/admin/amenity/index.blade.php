@extends('layouts.adminmaster')
@section('title','Amenity| Listing')
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
    .hotel-search { margin-right: 25px; width:90%;}
</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                <h4 class="page-title">Amenities  </h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.amenity.create') }}" class="btn btn-info d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Amenity</a>
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
        <!-- ============================================================== -->
        <!-- Three charts -->
        <!-- ============================================================== -->
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('admin.amenity.indexsearch') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Amenity List</h3>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <select name="sort" id="sort" class="form-control" data-allow-clear="true">
                                                <option value="" >Sort By</option>
                                                <option value="name" >Name</option>
                                                <option value="catery" >Category</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <select name="category" id="category" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                                                <option value="" >None</option>
                                                @foreach($catdata as $cat)
                                                <option value="{{ $cat['id'] }}" {{ $catid == $cat['id'] ? 'selected' : '' }}>{{ $cat['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
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
                    <!-- <h3 class="box-title">Amenity List</h3> -->
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Icon</th>
                                    <th class="border-top-0">Amenity Name</th>
                                    <th class="border-top-0">Amenity Category</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($amenities as $key => $row)
                                <tr>
                                    <?php //echo "<pre>"; print_r($row);
                                    ?>
                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($amenities->currentpage()-1) * $amenities->perpage() + $key + 1 }}</td>
                                    <td><i class="{{ $row->icon_class }}" aria-hidden="true"></i></td>
                                    <td>{{ $row->title }}</td>
                                    <td>
                                        @php
                                        $amenityCategoey = Helper::getAmenityCategory($row->category_id);

                                        echo isset($amenityCategoey[0]->name)?$amenityCategoey[0]->name:"None";
                                        @endphp
                                    </td>
                                    <td>
                                    @if ($user->hasPermission('status') == true)
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                    @endif
                                    </td>
                                    <td>
                                    @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/amenity-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    @endif
                                    @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/amenity-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $amenities->appends($query)->links() }}
                        @else
                        {!! $amenities->links() !!}
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <!-- <footer class="footer text-center"> 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a>
            </footer>-->
    <!-- ============================================================== -->
    <!-- End footer -->
    <!-- ============================================================== -->
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
    function changeStatus(statusdata,id){
        var status = (statusdata!='Inactive')?'Inactive':'Active';
        console.log(status);
        //console.log("under statusChange : ",statusdata);
        //console.log("user id : ",id);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeAmenityStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
            }
        });
    }

</script>

@endsection
