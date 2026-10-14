@extends('layouts.adminmaster')
@section('title','State')
@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    
<?php
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>

<style>
    .bootstrap-tagsinput {
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075);
        display: block;
        padding: 4px 6px;
        color: #555;
        vertical-align: middle;
        border-radius: 4px;
        max-width: 100%;
        line-height: 22px;
        cursor: text;
    }
    .bootstrap-tagsinput input {
        border: none;
        box-shadow: none;
        outline: none;
        background-color: transparent;
        padding: 0 6px;
        margin: 0;
        width: auto;
        max-width: inherit;
    }   
</style>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 class="page-title">Add New State</h4>
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

                <form action="{{ route('admin.new_country_state_city.addstate') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Country<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    @php
                                    $countries = Helper::getCountryAllData();
                                    @endphp
                                    <select class="form-control select2" name="countryname" id="countryname">
                                        <option value="">Select country</option>
                                        @foreach ($countries as $val)
                                        <option value="{{ $val->id }}">{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('countryname'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">State<span style="color:red;">*</span>(insert multiple state via press Enter)</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input class="form-control" type="text" data-role="tagsinput" name="statename" value="{{old('statename')}}" placeholder="Enter State">
                                </div>
                                <span class="text-danger">@error('statename'){{$message}} @enderror</span>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('#countryname').select2();
    });
</script>
@endsection