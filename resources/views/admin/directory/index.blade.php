@extends('layouts.adminmaster')
@section('title','Company| Listing')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php $directoryDetails = $data['directoryDetails'] ;

if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>
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
<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Company List</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('admin.directory.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add New Company</a>
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
                    <form action="{{ route('admin.directory.indexsearch') }}" method="POST" class="">
                        {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-1">
                            <h3 class="box-title">Companies  </h3>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group mb-4 cus-title">
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control categoryselect select2" onChange="getSubCategory(this.value)" data-allow-clear="true" data-placeholder="Select category">
                                        <option value="">Choose Category</option>
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" @if (isset($_REQUEST['category'])) @if($cat->id == $_REQUEST['category']) selected @endif @endif >{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4 cus-title">
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="subcategory" id="subcategory" class="form-control categoryselect select2" onChange="getTagsSubcatId(this.value)" data-allow-clear="true" data-placeholder="Select sub-category">
                                        <option value="">Choose sub category</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-4 cus-title">
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="tagsid" id="tagsid" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select tags">
                                        <option value="">Choose tags</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">

                            <div class="form-group mb-4 cus-title">
                                <div class="border-bottom p-0">
                                    <select name="status" id="status" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Status">
                                        <option value="">Status</option>
                                        <option value="Active" @if (isset($_REQUEST['status'])) @if('Active' == $_REQUEST['status']) selected @endif @endif>Active</option>
                                        <option value="Inactive" @if (isset($_REQUEST['status'])) @if('Inactive' == $_REQUEST['status']) selected @endif @endif>InActive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4 cus-title">
                            <div class="input-group mb-3">
                                <input type="text" style="height: 30px;" name="searchtext" id="search" value="@if(isset($_REQUEST['searchtext'])){{ $_REQUEST['searchtext'] }}@endif" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <input class="btn btn-primary" style="height: 30px; line-height: 2px;" name="search" type="submit" value="Filter!" />
                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                </form>
                    <!-- <h3 class="box-title">Directory List</h3> -->
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        @if ($directoryDetails->count() > 0)
					<div class="d-flex notranslate">
						<p class="text-right" style="">
							  Showing {{ $directoryDetails->firstItem() }} - {{ $directoryDetails->lastItem() }} of {{ $directoryDetails->total() }} companies
						</p>
					</div>
					@endif
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Title</th>
                                    <th class="border-top-0">Location</th>
                                    <th class="border-top-0">Author</th>
                                    <th class="border-top-0">Category</th>
                                    <th class="border-top-0">Visitors</th>
                                    <th class="border-top-0">Likes</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Created</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i=0;
                                ?>
                                @foreach($directoryDetails as $key => $directoryrow)
                                <tr>

                                    <td> {{ ($directoryDetails->currentpage()-1) * $directoryDetails->perpage() + $key + 1 }}</td>
                                    <td>
                                        {{ Str::limit($directoryrow->title, 17, '...') }}
                                    </td>
                                    <td>
                                    {{ $directoryrow->country_id??'' }}
                                    </td>
                                    <td> {{ Str::limit(Helper::getUserNameDirId($directoryrow->id)->name??'Admin', 12, '...') }}  </td>
                                    <td>
                                         {{ Helper::getCat_by_directoryId($directoryrow->id) }}
                                    </td>
                                    <td>
                                    @if(count(Helper::CountVisitorsId('directory',$directoryrow->id)) != 0)
                                        {{ count(Helper::CountVisitorsId('directory',$directoryrow->id)) }}
                                    @else
                                    {{ 0 }}
                                    @endif
                                    </td>
                                    <td>
                                    @if(count(Helper::CountLikeData('directory',$directoryrow->id)) != 0)
                                        {{ count(Helper::CountLikeData('directory',$directoryrow->id)) }}
                                    @else
                                    {{ 0 }}
                                    @endif

                                    </td>
                                    <td>
                                        <input data-id="{{$directoryrow->id}}" name="status" data-toggle="toggle" class="toggle-class statususer" type="checkbox" data-onstyle="success" data-offstyle="danger" data-on="Active" data-off="InActive" {{ ($directoryrow->status=='Active') ? 'checked' : '' }} onChange="changeStatus('<?php echo $directoryrow->status ?>','<?php echo $directoryrow->id ?>');">
                                        {{-- {{ $directoryrow->status }} --}}
                                    </td>
                                    <td>
                                    {{ \Carbon\Carbon::parse($directoryrow->created_at)->format('d F Y') }}

                                    </td>
                                    <td>
                                        @if ($user->hasPermission('edit') == true)
                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-edit",['id' => @$directoryrow->directory_id]); ?>">EDIT</a>
                                        @endif
                                        @if ($user->hasPermission('delete') == true)
                                        <a class="btn btn-danger btn-xs show_confirm" href="<?php echo url("/admin/directory-delete",['id' => @$directoryrow->directory_id]); ?>">DELETE</a>
                                        @endif

                                        @php
                                            $is_competition = Helper::QuestionsByVendorId($directoryrow->directory_id);
                                        @endphp
                                        @if($is_competition !== null)
                                             <a  href="<?php echo url("/admin/competition-participants",['id' => @$is_competition->advertisement_id]); ?>" class="btn btn-primary btn-xs" target="_blank">
                                                COMPETITION
                                            </a>
                                        @endif

                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-offer",['id' => @$directoryrow->directory_id]); ?>">OFFER</a>
                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-product",['id' => @$directoryrow->directory_id]); ?>">PRODUCT</a>
                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-voucher",['id' => @$directoryrow->directory_id]); ?>">COUPON</a>
                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-giftvoucher",['id' => @$directoryrow->directory_id]); ?>">GIFT VOUCHER</a>
                                        <a class="btn btn-primary btn-xs" target="_blank" href="<?php echo url("/admin/directory-orders",['id' => @$directoryrow->directory_id]); ?>">ORDERS</a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                    <div class="d-flex justify-content-center cust-page">
                        @if(isset($query))
                        {{ $directoryDetails->appends($query)->links() }}
                        @else
                        {!! $directoryDetails->links() !!}
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
        console.log(status);
        //console.log("under statusChange : ",statusdata);
        //console.log("user id : ",id);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: baseUrl+'/admin/changeDirectoryStatus',
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


    $(document).ready(function() {
        $('.categoryselect').select2();

        @if (isset($_REQUEST['category']))
             var catid = "{{ $_REQUEST['category'] }}";

             @if (isset($_REQUEST['subcategory']))
             var subcategory = "{{ $_REQUEST['subcategory'] }}";
             @else
             var subcategory = '';
             @endif

             @if (isset($_REQUEST['tagsid']))
             var tagsidp = "{{ $_REQUEST['tagsid'] }}";
             @else
             var tagsidp = '';
             @endif

                $("#subcategory").html('');
                $("#tagsid").html('');
                $.ajax({
                    url: baseUrl + '/admin/get-subcategories-by-categoryid',
                    type: "POST",
                    data: { category_id: catid, _token: '{{csrf_token()}}' },
                    dataType: 'json',
                    success: function(result) {
                        $("#subcategory").append('<option value="">Select subcategory</option>');
                        $.each(result.subcat, function(key, value) {
                            $("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });

                    }
                });

                $.ajax({
                    url: baseUrl + '/admin/get-tags-by-subcategoryid',
                    type: "POST",
                    data: { subcategory_id: subcategory, _token: '{{csrf_token()}}' },
                    dataType: 'json',
                    success: function(result) {

                        $.each(result.tags, function(key, value) {
                            $("#tagsid").append('<option value="">Select tag</option>');
                            $("#tagsid").append('<option value="' + value.id + '">' + value.tags + '</option>');
                        });

                    }
                });

                setTimeout(
                function()
                {
                    $('#subcategory').val(subcategory).trigger('change');

                }, 1000);
                setTimeout(
                function()
                {
                    $('#tagsid').val(tagsidp).trigger('change');
                }, 2000);


        @endif

    });

    function getSubCategory(cat_id) {
        $("#subcategory").html('');
        $("#tagsid").html('');
        $.ajax({
            url: baseUrl + '/admin/get-subcategories-by-categoryid',
            type: "POST",
            data: { category_id: cat_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {
                $("#subcategory").append('<option value="">Select subcategory</option>');
                $.each(result.subcat, function(key, value) {
                    $("#subcategory").append('<option value="' + value.id + '">' + value.name + '</option>');
                });

            }
        });
    }

    function getTagsSubcatId(subCat_id) {
        $("#tagsid").html('');
        $.ajax({
            url: baseUrl + '/admin/get-tags-by-subcategoryid',
            type: "POST",
            data: { subcategory_id: subCat_id, _token: '{{csrf_token()}}' },
            dataType: 'json',
            success: function(result) {

                $.each(result.tags, function(key, value) {
                    $("#tagsid").append('<option value="">Select tag</option>');
                    $("#tagsid").append('<option value="' + value.id + '">' + value.tags + '</option>');
                });

            }
        });
    }
</script>

@endsection
