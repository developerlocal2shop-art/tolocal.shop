@extends('layouts.client.vendor')
@section('title','Blog | Listing')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Company's Blog</h1>
        <x-client.company_account_menu />
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->
        <div class="white-box">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="box-title">Blog List </h3>
                </div>
                <div class="col-md-4">
                    <?php
                            $getFeatureUsesValue = Helper::getFeatureUsesValue('blog');
                            if($getFeatureUsesValue <= count($blogdata)){
                        ?>
                    <a onclick="alert('Please upgrade member account. you can not add blog greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Blog</a>
                    <?php }else{ ?>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('company.addblog')}}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Blog</a>
                    @endif
                    <?php } ?>
                </div>
            </div>
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
                        <?php $i = 0; $datacount = count($blogdata); if ($datacount>0){?>
                        @foreach($blogdata as $blogdatarow)
                        <tr>
                            <td><?php echo ++$i; ?></td>
                            <td><img src="{{ env('IMAGE_PATH').'images/blog/'.$blogdatarow->blog_image.'.webp' }}" height="70px" width="70px"></td>
                            <td>{{ $blogdatarow->title }}</td>
                            <td>
                                @php
                                $parentCategoey = Helper::getParentCategory($blogdatarow->category_id);
                                echo $parentCategoey[0]->name;
                                @endphp
                            </td>
                            <td>
                                @if(count(Helper::CountVisitorsId('blog',$blogdatarow->id)) != 0)
                                    {{ count(Helper::CountVisitorsId('blog',$blogdatarow->id)) }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td>
                                @if(count(Helper::CountLikeData('blog',$blogdatarow->id)) != 0)
                                    {{ count(Helper::CountLikeData('blog',$blogdatarow->id)) }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td>
                                <input data-id="{{$blogdatarow->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($blogdatarow->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $blogdatarow->status ?>','<?php echo $blogdatarow->id ?>');">

                            </td>
                            <td>
                                @if ($user->hasPermission('edit') == true)
                                <a class="btn btn-primary btn-sm" href="<?php echo url("/company/blog-edit", ['id' => @$blogdatarow->id]); ?>">EDIT</a>
                                @endif
                                @if ($user->hasPermission('delete') == true)
                                <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/blog-delete", ['id' => @$blogdatarow->id]); ?>">DELETE</a>
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

    <script type="text/javascript">

        function changeStatus(statusdata, id) {
            var status = (statusdata != 'Inactive') ? 'Inactive' : 'Active';
            $.ajax({
                type: "GET"
                , dataType: "json"
                , url: baseUrl + '/changeBlogStatus'
                , data: {
                    'status': status
                    , 'id': id
                }
                , success: function(data) {
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
    <style>
        @media (max-width: 767px) {
            .d-none {
                display: unset !important;
            }

            .pull-right {
                margin-top: -40px;
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
</main>

@endsection
