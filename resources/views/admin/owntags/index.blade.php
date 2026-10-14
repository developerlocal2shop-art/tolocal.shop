@extends('layouts.adminmaster')
@section('title','Tags| Listing')
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

    .hotel-search {
        margin-right: 25px;
        width: 90%;
    }

</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Tags</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.owntags.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Tags</a>
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


        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('admin.owntags.indexsearch') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Tags List</h3>
                            </div>

                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <select name="category" id="category" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select category">
                                                <option value="">None</option>
                                                @foreach($allCategory as $cat)
                                                <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="{{ $searchtxt }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
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
                                    <th class="border-top-0">Tags</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($tags); if ($datacount>0){ ?>
                                @foreach($tags as $rowtag)

                                <tr>
                                    <td><?php echo ++$i; ?></td>
                                    <td>{{ $rowtag->tags }}</td>
                                    <td>
                                        @php
                                        $parentCategoey = Helper::getParentCategory($rowtag->category_id);
                                        echo $parentCategoey[0]->name;
                                        @endphp
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('status') == true)
                                        <input data-id="{{@$rowtag->id}}" class="toggle-class" type="checkbox" onChange="statusChange('<?php echo $rowtag->status ?>','<?php echo $rowtag->id ?>');" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Active" data-off="InActive" {{ (@$rowtag->status=="Active") ? 'checked' : '' }}>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/owntags-edit", ['id' => @$rowtag->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/owntags-delete", ['id' => @$rowtag->id]); ?>">DELETE</a>
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
                        {{ $tags->appends($query)->links() }}
                        @else
                        {!! $tags->links() !!}
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
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });

</script>
<script type="text/javascript">
    function statusChange(statusdata, id) {
        var status = (statusdata == "Active") ? "InActive" : "Active";
        console.log("under statuhhhsChange : ", statusdata);
        console.log("under id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/changeownTagStatus'
            , data: {
                'status': status
                , 'user_id': id
            }
            , success: function(data) {
                console.log(data.success)
            }
        });
    }

    // $(function() {
    //     $('.toggle-class').change(function() {

    //     var status = $(this).prop('checked') == true ? "Active" : "Inactive";
    //     var catTagid = $(this).data('id');

    //         $.ajax({
    //             type: "GET",
    //             dataType: "json",
    //             url: baseUrl+'/admin/changeTagStatus',
    //             data: {'status': status, 'id': catTagid},
    //             success: function(data){
    //                 console.log(data.success)
    //             }
    //         });
    //     })
    // })

</script>

@endsection
