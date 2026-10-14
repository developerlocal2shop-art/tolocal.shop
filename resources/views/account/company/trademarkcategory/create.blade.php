@extends('layouts.client.vendor')
@section('title','Trademark Category | Add Category')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Trademark Categories</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>

    <div class="container margin_60_35">
   
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('company.trademarkcategory.submit') }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" id="trade-name" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('name') }}" placeholder="Enter Name" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="description" id="description" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0" >{{ old('description') }}</textarea>
                                </div>
                                <span class="text-danger">@error('description'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                            <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}" >
                                <button type="submit" class="btn btn-info">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
   
</div>
</div>
</main>
@endsection