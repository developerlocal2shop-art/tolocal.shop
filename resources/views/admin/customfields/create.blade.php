@extends('layouts.adminmaster')
@section('title','Category | Add Category Custom Fields')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Add Category Custom Fields</h4>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.customfields.submitcustfield') }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Label</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="label" class="form-control" value="{{ old('label') }}" placeholder="Enter Label" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter Name" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Type</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="type" id="type">
                                        <option value="text"> Text </option>
                                        <option value="number"> Number </option>
                                        <option value="date"> Date </option>
                                        <option value="time"> Time </option>
                                        <option value="textarea"> Textarea </option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('type'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Add Custom Fields</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script type="text/javascript">
    $(function() {
        $('.icon-picker').iconpicker();
    });
</script>


@endsection