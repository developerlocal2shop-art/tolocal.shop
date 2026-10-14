@extends('layouts.adminmaster')
@section('title','Trademark Category | Edit Category')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Trademark Category</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.trademarkcategory.update', $tradeDetails->id) }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" id="trade-name" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('name',$tradeDetails->name) }}" placeholder="Enter Name" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="description" id="description" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0" >{{ old('description',$tradeDetails->description) }}</textarea>
                                </div>
                                <span class="text-danger">@error('description'){{$message}} @enderror</span>
                            </div>
                        </div>                       
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Update Category</button>
                                </div>
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