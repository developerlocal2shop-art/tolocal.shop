@extends('layouts.adminmaster')
@section('title','Blog| Listing')
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
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Blog</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                     @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.blog.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Blog</a>
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
                <form action="{{ route('admin.blog.indexsearch') }}" method="post" class="">
                                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="box-title">Blog List</h3>
                        </div>
                        <div class="col-md-9">
                            <div class="d-md-flex">
                                <ol class="breadcrumb ms-auto">
                                    <li><a href="#" class="fw-normal"></a></li>
                                </ol>

                                    <div class="input-group mb-7">
                                        <div class="hotel-search">
                                            <select name="category" id="category" placeholder="Select Category" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select category">
                                                <option value="" selected disabled>Select Category</option>
                                                @foreach($parent_category as $cat)
                                                    <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group mb-3">
                                        <input type="text"  name="searchtext" id="search" value="{{ $blogsearch }}" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
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
                                    <th class="border-top-0">Image</th>
                                    <th class="border-top-0">Title</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Visitors</th>
                                    <th class="border-top-0">Likes</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                @foreach($blogs as $key => $row)
                                <tr>

                                    <!-- <td><?php echo ++$i; ?></td> -->
                                    <td> {{ ($blogs->currentpage()-1) * $blogs->perpage() + $key + 1 }}</td>
                                    <td><img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" height="50px" width="50px"></td>
                                    <td>{{ $row->title }}</td>
                                    <td>
                                        @php
                                        $parentCategoey = Helper::getParentCategory($row->category_id);
                                        // echo $parentCategoey[0]->name;
                                        echo isset($parentCategoey[0]->name)?$parentCategoey[0]->name:"None";
                                        @endphp
                                    </td>
                                    <td>
                                        @if(count(Helper::CountVisitorsId('blog',$row->id)) != 0)
                                            {{ count(Helper::CountVisitorsId('blog',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(count(Helper::CountLikeData('blog',$row->id)) != 0)
                                            {{ count(Helper::CountLikeData('blog',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <td>
                                        <input data-id="{{$row->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($row->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $row->status ?>','<?php echo $row->id ?>');">
                                        {{-- {{ $row->status }} --}}
                                    </td>
                                    <td>
                                    @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" href="<?php echo url("/admin/blog-edit", ['id' => @$row->id]); ?>">EDIT</a>
                                    @endif
                                    @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/blog-delete", ['id' => @$row->id]); ?>">DELETE</a>
                                    @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $blogs->appends($query)->links() }}
                        @else
                        {!! $blogs->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">

    function changeStatus(statusdata,id){
        var status = (statusdata!='Inactive')?'Inactive':'Active';
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeBlogStatus',
            data: {'status': status, 'id': id},
            success: function(data){
                console.log(data.success);
            }
        });
    }

    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>

@endsection
