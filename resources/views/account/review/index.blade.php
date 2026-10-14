@extends('layouts.client.vendor')
@section('title','Company Reviews Listing')
@section('content')
@php $user = auth()->user(); @endphp
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

<div class="sub_header_in sticky_header ">
    <div class="container">
        <h1>Company Dashboard</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

                   
              
<main>
    <div class="container margin_60_35">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        
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
                                   <div class="input-group mb-3">
                                        <select name="page_type" class="form-control">
                                            <option value="">Select Type</option>
                                            <option value="product" {{ request('page_type') == 'product' ? 'selected' : '' }}>Product</option>
                                            <option value="blog" {{ request('page_type') == 'blog' ? 'selected' : '' }}>Blog</option>
                                            <option value="event" {{ request('page_type') == 'event' ? 'selected' : '' }}>Event</option>
                                        </select>

                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text" name="searchtext" id="search" value="@if(isset($_GET['searchtext'])){{$_GET['searchtext']}}@endif" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                    <th class="border-top-0">Title</th>
                                    <th class="border-top-0">Page</th>

                                    <th class="border-top-0">Content</th>
                                    <th class="border-top-0">Stars</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($review); 
                                if ($datacount>0){
                                ?>
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
                                        if($reviewdata->page == 'event'){
                                            $pName = Helper::getEventId($reviewdata->page_id);
                                            echo $pName->title??"N/A";
                                        }else if($reviewdata->page == 'blog'){
                                            $pName = Helper::getBlogId($reviewdata->page_id);
                                            echo $pName->title??"N/A";
                                        }else if($reviewdata->page == 'product'){
                                            $pName = Helper::getProductId($reviewdata->page_id);
                                            echo $pName->name??"N/A";
                                        }
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
                                        <a class="btn btn-primary btn-sm" href="
                                            <?php echo url("/company/review-edit",['id' => @$reviewdata->id]); ?>">View</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/review-delete",['id' => @$reviewdata->id]); ?>">DELETE</a>
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
</main>

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
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/company/review/changeReviewStatus'
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
