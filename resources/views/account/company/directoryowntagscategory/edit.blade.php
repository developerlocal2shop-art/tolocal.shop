@extends('layouts.client.company')
@section('title','Directory Own Tags Category | Edit Category')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Directory Own Tags Categories</h1>
        <x-client.company_account_menu />
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="container-fluid">
            @if(session()->get('msg'))
            <div class="alert alert-success">
                {{ session()->get('msg') }}
            </div><br />
            @endif
            <div class="card">
                <div class="card-body">
                    <h4 class="page-title">Edit Directory Own Tags Category</h4>
                    <form action="{{ route('company.directoryowntagscategory.update', $directoryCategoryDetails->id) }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Name</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="name" id="trade-name" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('name',$directoryCategoryDetails->name) }}" placeholder="Enter Name" class="form-control border-0">
                                    </div>
                                    <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Description</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <textarea rows="5" name="description" id="description" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control p-0 border-0">{{ old('description',$directoryCategoryDetails->description) }}</textarea>
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
    </div>
    </div>
</main>
<script type="text/javascript">
    $(document).ready(function() {
        // Warning
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            $(window).off('beforeunload');
        });
    });
</script>
@endsection
