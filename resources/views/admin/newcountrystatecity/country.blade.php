@extends('layouts.adminmaster')
@section('title','Country')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    
<?php
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Add New Country(Non Existing)</h4>
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

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.new_country_state_city.addcountry') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Country<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input class="form-control" type="text" id="countryname" name="country_name" value="{{old('country_name')}}" placeholder="Enter Country Name">
                                </div>
                                <span class="text-danger">@error('country_name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Country Code<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input class="form-control" type="text" id="countrycode" name="country_code" value="{{old('country_code')}}" placeholder="Enter Country Code">
                                </div>
                                <span class="text-danger">@error('country_code'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Phone Code<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input class="form-control" type="text" id="phonecode" name="phonecode" value="{{old('phonecode')}}" placeholder="Enter Phone Code">
                                </div>
                                <span class="text-danger">@error('phonecode'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                        
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column -->


    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>


@endsection