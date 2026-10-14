@extends('layouts.adminmaster')
@section('title','Distance')
@section('content')
 
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Add Distance</h4>
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

                <form action="{{ route('admin.distance.submit') }}" method="POST" class="" >

                    {{ csrf_field() }}
                    <div class="row cus-title">

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12">Set As Default Distance</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_distance" value="yes" id="pay">
                                    <label class="custom-control-label" for="1">Yes </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="default_distance" value="no" style="margin-left: 20px;" id="nonpay" checked="">
                                    <label class="custom-control-label" for="1">No </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Distance</label>
                                <input type="text" class="form-control" name="to_distance" value="{{ old('to_distance') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Active" >
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Inactive" style="margin-left: 20px;" checked="">
                                    <label class="custom-control-label" for="1">Inactive </label>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    
                    
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info btn-block">Save</button>
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
        $('.categoryselect').select2();
    });
</script>
@endsection