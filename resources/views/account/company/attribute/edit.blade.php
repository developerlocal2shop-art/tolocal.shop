@extends('layouts.client.vendor')
@section('title','Attribute | Edit Attribute')
@section('content')
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Product's Attribute</h1>
        <x-client.company_account_menu />
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="white-box">
            <div class="card">
                <div class="card-body">
                    <div class="page-breadcrumb bg-white">
                        <div class="row align-items-center">
                            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                                <h4 class="page-title">Edit Attribute</h4>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('company.attribute.update', $AttributeDetails->id) }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Attribute Name<span style="color:red;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="name" value="{{ $AttributeDetails->name }}" placeholder="Enter Category Name" class="form-control p-0 border-0" autocomplete=off>

                                    </div>
                                    <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <textarea rows="5" maxlength="500" id="amenity-desc" onkeyup="charCount(this.value,this.id,500)" name="content" placeholder="Enter Description" class="form-control p-0 border-0">{{ $AttributeDetails->content }}</textarea>
                                    </div>
                                    <span class="text-danger">@error('description'){{$message}} @enderror</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-info">Update</button>
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
    $(function() {
        $('.icon-picker').iconpicker();
    });

</script>
@endsection
