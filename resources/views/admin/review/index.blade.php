@extends('layouts.adminmaster')
@section('title','Review| Listing')
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
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Review</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.review.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Review</a>
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
                    <form action="" method="get" class="">

                        <div class="row">
                            <div class="col-md-6">
                                <h3 class="box-title">Review List</h3>
                            </div>

                            <div class="col-md-6">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-3">
                                        <select name="page_type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="product" {{ request('page_type') == 'product' ? 'selected' : '' }}>Product</option>
                                            <option value="blog" {{ request('page_type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                            <option value="event" {{ request('page_type') == 'event' ? 'selected' : '' }}>Event</option>
                                        </select>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">User</th>
                                    <th class="border-top-0">Product name</th>
                                    <th class="border-top-0">Page</th>

                                    <th class="border-top-0">Content</th>
                                    <th class="border-top-0">Stars</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($review); if ($datacount>0){?>
                                @foreach($review as $key => $reviewdata)
                                <tr>
                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($review->currentpage()-1) * $review->perpage() + $key + 1 }}</td>
                                    <td>
                                        @php
                                        $userName = Helper::getUserName($reviewdata->user_id);
                                        echo isset($userName[0]->name)?$userName[0]->name: "" ;
                                        echo "&nbsp;";

                                        echo isset($userName[0]->name)?$userName[0]->last_name:" " ;
                                        @endphp
                                    </td>
                                    <td>
                                        @php
                                        $pName = Helper::getProductId($reviewdata->page_id);
                                        echo $pName->name??"N/A";
                                        @endphp
                                    </td>

                                    <td>{{ $reviewdata->page }}</td>
                                    <td>{{ strip_tags($reviewdata->content) }}</td>
                                    <td>
                                     @for($i=1; $i<=$reviewdata->star; $i++)
                                            <i class="fa fa-star d-star"></i>
                                        @endfor

                                    </td>
                                    <td>
                                        @if ($user->hasPermission('status') == true)
                                        <input data-id="{{$reviewdata->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($reviewdata->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $reviewdata->status ?>','<?php echo $reviewdata->id ?>');">
                                        @endif
                                    </td>
                                    <td style="width: 15%;">
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="
                                            <?php echo url("/admin/review-edit",['id' => @$reviewdata->id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/review-delete",['id' => @$reviewdata->id]); ?>">DELETE</a>
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
                        {{ $review->appends($query)->links() }}
                        @else
                        {!! $review->links() !!}
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
    function changeStatus(statusdata, id) {
        var status = (statusdata != 'Inactive') ? 'Inactive' : 'Active';
        console.log(status);
        console.log("under statusChange : ", statusdata);
        console.log("user id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/review/changeReviewStatus'
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

@endsection
