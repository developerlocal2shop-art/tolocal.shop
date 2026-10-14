@extends('layouts.adminmaster')
@section('title','Amenity Category | Add Category')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Add Amenity Category</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.amenitycategory.submit') }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Name" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="description" maxlength="500" onkeyup="charCount(this.value,this.id,500)" placeholder="Enter Description" id="description" class="form-control border-0" >{{ old('description') }}</textarea>
                                </div>
                                <span class="text-danger">@error('description'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                            <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}" >
                                <button type="submit" class="btn btn-info">Save</button>
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

@endsection