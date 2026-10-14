@extends('layouts.adminmaster')
@section('title','System Setting')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php
if (isset($settingDetails)) {
    $settingDetails = $settingDetails;
}
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Map Setting</h4>
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
            <!-- Column -->

            <div class="card">
                <div class="card-body">
                    <?php if (empty($settingDetails['id'])) { ?>
                        <form action="{{ route('admin.mapsetting.submitMapsetting') }}" method="POST" class="form-horizontal form-material">
                        <?php } else { ?>
                            <form action="{{ route('admin.mapsetting.updateMapsetting') }}" method="POST" class="form-horizontal form-material">
                            <?php } ?>
                            {{ csrf_field() }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Choose your map</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="map_title" onchange="change_map(this.value)">
                                        <option value="openstreetmap" selected="">Openstreet map</option>
                                        <option value="mapbox">Mapbox</option>
                                    </select>
                                    <!-- <input type="text" name="map_title" id="map_title" value="{{ old('map_title', $settingDetails['map_title'] ?? '') }}" placeholder="Enter map title" class="form-control p-0 border-0" required> -->
                                </div>
                                <span class="text-danger">@error('map_title'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4" id="map_access_token_section" style="display: none;">
                                <label class="col-md-12 p-0">Map access token</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="mapaccess_token" value="{{ old('mapaccess_token', $settingDetails['mapaccess_token'] ?? '') }}" placeholder="Enter Map Access Token" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('mapaccess_token'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Contact location (Latitude, Longitude)</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="location" id="location" value="{{ old('location', $settingDetails['location'] ?? '') }}" placeholder="Enter location" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('location'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Max zoom level</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="zoom_level" id="zoom_level" value="{{ old('zoom_level', $settingDetails['zoom_level'] ?? '') }}" placeholder="Enter maximum zoom level" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('zoom_level'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Min zoom level on the listings page</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="listing_page_zoom_level" id="listing_page_zoom_level" value="{{ old('listing_page_zoom_level', $settingDetails['listing_page_zoom_level'] ?? '') }}" placeholder="Enter minimum zoom level on listing page " class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('listing_page_zoom_level'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Min zoom level on the directory page</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="directory_page_zoom_level" id="directory_page_zoom_level" value="{{ old('directory_page_zoom_level', $settingDetails['directory_page_zoom_level'] ?? '') }}" placeholder="Enter minimum zoom level on directory page " class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('directory_page_zoom_level'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <input type="hidden" name="id" id="id" value="{{$settingDetails['id']}}">
                                    @if ($user->hasPermission('update') == true)
                                    <button type="submit" class="btn btn-info">Save</button>
                                    @endif
                                </div>
                            </div>
                            </form>
                </div>
            </div>

            <!-- Column -->
        </div>

    </div>
    <!-- ============================================================== -->
    <!-- End Container fluid  -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- footer -->
    <!-- ============================================================== -->
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
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

<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
</script>
<script type="text/javascript">
  function change_map(map){
    if(map != 'mapbox'){
      $('#map_access_token_section').hide();
    }else{
      $('#map_access_token_section').show();
    }
  }
</script>  
@endsection