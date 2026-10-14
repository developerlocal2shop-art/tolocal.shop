@extends('layouts.adminmaster')
@section('title','Business| Listing')
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
    #tbl_results thead tr th {
        min-width: 120px;
    }
    #tbl_results thead tr th #btnsort {
        background: none;
        border: none;
        color: #3e5569;
    }
    #tbl_results thead tr th form {
        width: 20px;
        float: right;
    }
    .hotel-search {
        width: 90%;
    }
</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Business</h4>
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
                    <form action="{{ route('admin.business.indexsearch') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <h3 class="box-title">Business List</h3>
                            </div>
                            <div class="col-md-9">
                                <div class="d-md-flex">
                                    <ol class="breadcrumb ms-auto">
                                        <li><a href="#" class="fw-normal"></a></li>
                                    </ol>
                                    <div class="input-group mb-7">
                                        <div class="hotel-search">

                                            <select name="category" id="category" class="form-control categoryselect " data-allow-clear="true" data-placeholder="Select category">
                                                <option value="">Select Category</option>
                                                @foreach($categorytype as $cat)
                                                <option value="{{ $cat->id }}" {{ $catid == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
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
                    <!-- <p class="text-muted">Add class <code>.table</code></p> -->
                    <div class="table-responsive">
                        <table id="tbl_results" class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th class="border-top-0">Image</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Name

                                        <form action="{{ route('admin.business.indexsearch') }}" name="sortnameform" method="get">
                                            <input type="hidden" id="valuesort" name="sortvalue" value="{{ $sortvaluedata }}" />
                                            <input type="hidden" name="sortby" value="name" />
                                            <button type="submit" class="btnsort" id="btnsort" value="valsort" name="sortbtn">
                                                <i class="fas fa-arrows-alt-v" id="smlltobig"></i>
                                            </button>
                                        </form>

                                    </th>
                                    <th class="border-top-0">City
                                        <form action="{{ route('admin.business.indexsearch') }}" name="sortcityform" method="get">
                                            <input type="hidden" id="valuesort" name="sortvalue" value="{{ $sortvaluedata }}" />
                                            <input type="hidden" name="sortby" value="city" />
                                            <button type="submit" class="btnsort" id="btnsort" value="valsort" name="sortbtn">
                                                <i class="fas fa-arrows-alt-v" id="smlltobig"></i>
                                            </button>
                                        </form>
                                    </th>
                                    <th class="border-top-0">Address
                                        <form action="{{ route('admin.business.indexsearch') }}" name="sortaddressform" method="get">
                                            <input type="hidden" id="valuesort" name="sortvalue" value="{{ $sortvaluedata }}" />
                                            <input type="hidden" name="sortby" value="address" />
                                            <button type="submit" class="btnsort" id="btnsort" value="valsort" name="sortbtn">
                                                <i class="fas fa-arrows-alt-v" id="smlltobig"></i>
                                            </button>
                                        </form>
                                    </th>
                                    <th class="border-top-0">Zip Code
                                        <form action="{{ route('admin.business.indexsearch') }}" name="sortzipcodeform" method="get">
                                            <input type="hidden" id="valuesort" name="sortvalue" value="{{ $sortvaluedata }}" />
                                            <input type="hidden" name="sortby" value="zipcode" />
                                            <button type="submit" class="btnsort" id="btnsort" value="valsort" name="sortbtn">
                                                <i class="fas fa-arrows-alt-v" id="smlltobig"></i>
                                            </button>
                                        </form>
                                    </th>
                                    <th class="border-top-0">Place
                                        <form action="{{ route('admin.business.indexsearch') }}" name="sortplaceform" method="get">
                                            <input type="hidden" id="valuesort" name="sortvalue" value="{{ $sortvaluedata }}" />
                                            <input type="hidden" name="sortby" value="place" />
                                            <button type="submit" class="btnsort" id="btnsort" value="valsort" name="sortbtn">
                                                <i class="fas fa-arrows-alt-v" id="smlltobig"></i>
                                            </button>
                                        </form>
                                    </th>
                                    <th class="border-top-0">Package</th>
                                    <th class="border-top-0">Created On</th>
                                    <th class="border-top-0">Created By</th>
                                    <!-- <th class="border-top-0">Removed</th> -->
                                    <th class="border-top-0">Total Visitors</th>
                                    <th class="border-top-0">Total Likes</th>
                                    <!-- <th class="border-top-0">Total Page Views</th>
                                    <th class="border-top-0">Unique Visitor</th> -->

                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; $datacount = count($directoryDetails); if ($datacount>0){ ?>
                                @foreach($directoryDetails as $row)
                                <tr>
                                    <td>
                                        <!-- <img src="{{ asset('assets/images/dashboard/img_2.jpg') }}" alt="user-img" width="36" class="img-circle"> -->
                                        @php
                                        $imagename_thumb = Helper::getDirectoryImageThumbnail($row->id);
                                        @endphp
                                        @if(!empty($imagename_thumb[0]->name_thumbnail))
                                            <div class="thumbnail">
                                                <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename_thumb[0]->name_thumbnail.'.webp'}}" id="preview-main-img" alt="directory-img" width="50px" height="50px" />
                                            </div>
                                        @else
                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-main-img" alt="directory-img" width="70px" height="70px" />
                                        @endif
                                    </td>
                                    <td>

                                        Tags: <?php echo $tagscount = Helper::getTagsCount($row->id); ?>
                                        <!-- <br>Own Tags: <?php echo $owntagscount = Helper::getOwnTagsCount($row->id); ?> -->
                                        <br>Listing Thumbnail : <?php echo $thumbnailcount = Helper::getImageThumbnailCount($row->id); ?>
                                        <br>Listing Cover Image : <?php echo $covercount = Helper::getImageCoverCount($row->id); ?>
                                        <br>Listing Gallery Image : <?php echo $listingcount = Helper::getImageGalleryCount($row->id); ?>
                                        <!-- <br>Video: -->
                                        <br>Trademark : <?php echo $trademarkcount = Helper::getTrademarkCount($row->id); ?>
                                        <br><h5 style="font-weight:700;">Product : <?php echo $directoryproductCount = Helper::getDirectoryProductCount($row->id); ?></h5>

                                    </td>
                                    <td>{{ $row->title }}</td>
                                    <td>{{ $row->city_name }}</td>
                                    <td>{{ $row->address }}</td>
                                    <td>{{ $row->zipcode }}</td>
                                    <td>{{ $row->place }}</td>
                                    <td>
                                        @php
                                        $packagename = Helper::getDirectoryPackageName($row->id);
                                        @endphp
                                        {{ trim($packagename[0]->name??'', '\"')}}
                                    </td>
                                    <td><?php $created_date = explode(" ", $row->created_at); echo isset($created_date[0])? $created_date[0]:''; ?></td>
                                    <!-- <td><?php $adminName = Helper::getadminName($row->created_by); echo isset($adminName[0]->name)?$adminName[0]->name:''; ?></td> -->
                                    <td><?php $userName = Helper::getUserNameDirId($row->id); ?>
                                        {{$userName->name??''}} {{$userName->last_name??''}}
                                    </td>
                                    <!-- <td></td> -->
                                    <td>
                                        @if(count(Helper::CountVisitorsId('directory',$row->id)) != 0)
                                            {{ count(Helper::CountVisitorsId('directory',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <td>
                                        @if(count(Helper::CountLikeData('directory',$row->id)) != 0)
                                            {{ count(Helper::CountLikeData('directory',$row->id)) }}
                                        @else
                                        {{ 0 }}
                                        @endif
                                    </td>
                                    <!-- <td></td>
                                    <td></td> -->

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


@endsection
