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

    .category-header {
        cursor: pointer;
    }

    .category-toggle-icon {
        display: inline-block;
        width: 16px;
        text-align: center;
        margin-right: 8px;
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
                    <a href="{{ route('admin.tags.create') }}" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Tags</a>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">

        @if(session('msg'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('msg') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                    <form action="{{ route('admin.tags.indexsearch') }}" method="get" class="">

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
                                            <select name="sort" id="sort" class="form-control" data-allow-clear="true">
                                                <option value="">Sort By</option>
                                                <option value="name">Name</option>
                                                <!-- <option value="catery" >Category</option> -->
                                            </select>
                                        </div>
                                    </div>
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
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5%">S.No</th>
                                    <th width="10%">Tag Id</th>
                                    <th width="25%">Tag</th>
                                    <th width="25%">Created At</th>
                                    <th width="15%">Status</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                @php
                                    $sr = ($tags->currentPage() - 1) * $tags->perPage() + 1;
                                    $currentCategory = '';
                                @endphp

                                @forelse($tags as $rowtag)

                                    @php
                                    $categoryKey = $rowtag->group_category_id ?? ($rowtag->category_id !== null && $rowtag->category_id !== '' ? $rowtag->category_id : 'none');
                                    $categoryName = $rowtag->group_category_name ?? (Helper::getParentCompanyCategory($rowtag->category_id)[0]->name ?? 'No Category');
                                @endphp

                                @if($currentCategory != $categoryKey)

                                        @php
                                            $currentCategory = $categoryKey;
                                        @endphp

                                        <tr class="table-primary category-header" data-category="{{ $categoryKey }}">
                                            <td colspan="6">
                                                <strong class="category-toggle-text">
                                                    <span class="category-toggle-icon">−</span>
                                                    {{ $categoryName }}
                                                </strong>
                                            </td>
                                        </tr>

                                    @endif

                                    <tr class="tag-row category-{{ $categoryKey }}">
                                        <td>{{ $sr++ }}</td>
                                        <td style="color:orange;">
                                            {{ $rowtag->id }}
                                        </td>
                                        <td>
                                            {{ $rowtag->tags }}
                                        </td>
                                        <td>
                                            {{ $rowtag->created_at }}
                                        </td>
                                        <td>
                                            @if($user->hasPermission('status'))
                                                <input
                                                    data-id="{{ $rowtag->id }}"
                                                    class="toggle-class"
                                                    type="checkbox"
                                                    onchange="statusChange('{{ $rowtag->status }}','{{ $rowtag->id }}')"
                                                    data-onstyle="success"
                                                    data-offstyle="danger"
                                                    data-toggle="toggle"
                                                    data-on="Active"
                                                    data-off="Inactive"
                                                    {{ $rowtag->status == 'Active' ? 'checked' : '' }}>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->hasPermission('edit'))
                                                <a class="btn btn-primary btn-xs"
                                                href="{{ url('/admin/tags-edit',['id'=>$rowtag->id]) }}">
                                                    EDIT
                                                </a>
                                            @endif

                                            @if($user->hasPermission('delete'))
                                                <a class="btn btn-danger btn-xs show_confirm"
                                                href="{{ url('/admin/tags-delete',['id'=>$rowtag->id]) }}">
                                                    DELETE
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="6" class="text-center">
                                            No Data Found
                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>
                    </div>

                    <div class="d-flex justify-content-center cust-page">
                        {{ $tags->withQueryString()->links() }}
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
<script>
    function statusChange(statusdata, id) {
        var status = (statusdata == "Active") ? "InActive" : "Active";
        console.log("under statuhhhsChange : ", statusdata);
        console.log("under id : ", id);
        $.ajax({
            type: "GET"
            , dataType: "json"
            , url: baseUrl + '/admin/changeTagStatus'
            , data: {
                'status': status
                , 'user_id': id
            }
            , success: function(data) {
                console.log(data.success)
            }
        });
    }

    $(document).ready(function() {
        $('.category-header').on('click', function() {
            var categoryKey = $(this).data('category');
            var targetClass = '.category-' + categoryKey;
            var isVisible = $(targetClass).first().is(':visible');

            $(targetClass).toggle(!isVisible);
            $(this).find('.category-toggle-icon').text(isVisible ? '+' : '−');
        });
    });

</script>

@endsection
