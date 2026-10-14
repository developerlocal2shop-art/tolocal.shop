@extends('layouts.adminmaster')
@section('title','Package')
@section('content')
<?php

if (isset($data['plansDetails'])) {
    $planData = $data['plansDetails'];
}
?>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Package</h4>
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

                <form action="{{ route('admin.manual.package.update', $planData->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                           
                            <div class="cust-tab-cont">


                                <div class="form-group mb-4 row">

                                    <div class="col-md-6 border-bottom">
                                        <label class="">Vendor</label>
                                        <select required class="form-control p-0 border-0" name="vendor">
                                            <option value="">Please select vendor</option>
                                            @foreach ($data['vendor'] as $vval)
                                            <option value="{{ $vval->directory_id??'' }}" @if($planData->vendor->id == $vval->id) selected @endif>{{ $vval->name??'' }} {{ $vval->last_name??'' }}</option>
                                            @endforeach

                                        </select>
                                    </div>

                                    <br>
                                    <div class="col-md-6 border-bottom">
                                        <label class="">Packages</label>
                                        <select required class="form-control p-0 border-0" name="package">
                                            <option value="">Please select package</option>
                                            @foreach ($data['package'] as $pval)
                                            <option value="{{ $pval->id }}" @if($planData->plan->id == $pval->id) selected @endif>{{ json_decode($pval->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 border-bottom">
                                        <label class="">Transaction ID</label>
                                        <input type="text" placeholder="Transaction ID" value="{{ old('transaction_id', $planData->transaction_id) }}"  class="form-control p-0 border-0" name="transaction_id">

                                    </div>

                                    <div class="col-md-6 border-bottom">
                                        <label class="">Transaction Mode</label>
                                        <input type="text" placeholder="Transaction Mode" value="{{ old('transaction_mode', $planData->transaction_mode) }}" class="form-control p-0 border-0" name="transaction_mode">

                                    </div>


                                </div>

                                <div class="tab-pane">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Notes</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="notes" id="notes" value="{{ old('notes', $planData->notes) }}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" value="{{ old('notes') }}" placeholder="Enter Notes" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('notes'){{$message}} @enderror</span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">

                                <label class="col-md-12 p-0">Status </label>

                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Active" @if($planData->status == 'Active') checked @endif >
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Inactive" @if($planData->status == 'Inactive') checked @endif style="margin-left: 20px;">
                                    <label class="custom-control-label" for="0">Inactive </label>
                                </div>
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

</div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('.plan').click(function() {

            var planid = $(this).data('id');
            var checked = $(this).is(':checked');
            if (checked) {
                $('#limit' + planid).prop('disabled', false);
            } else {
                $('#limit' + planid).prop('disabled', true);
                $('#limit' + planid).val(0);
            }
        });


        $('.ckeditor').ckeditor();
        $("#galleryimgPreview").sortable();
    });

</script>

<script>
    $(function() {
        // Multiple images preview with JavaScript
        var multiImgPreview = function(input, imgPreviewPlaceholder) {
            if (input.files) {
                //console.log( input.files.length);
                $('#preview-gallery-img').hide();
                var filesAmount = input.files.length;
                for (var i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $("<div class=\"thumbnail \">" +
                            "<img src=\"" + event.target.result + "\" width=\"100px\" height=\"100px\" />" +
                            "<br/><span class=\"closebtn\"><i class=\"fas fa-trash\" style=\"color:#ff0505; \" aria-hidden=\"true\"></i></span>" +
                            "</div>").appendTo(imgPreviewPlaceholder);
                        $(".closebtn").click(function() {
                            $(this).parent(".thumbnail").remove();
                        });

                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
        $('#eventgal-image').on('change', function() {
            //document.getElementById("preview-gallery-img").style.display = "none";
            multiImgPreview(this, 'div.galleryimgPreview');

        });
    });

</script>

<script type="text/javascript">
    var eventsloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>
<style type="text/css">
    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
        margin: 1px;
    }

    .thumbnail img {
        width: 70px;
        height: 70px;
    }

    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
        /* background: url(http://icons.iconarchive.com/icons/kyo-tux/delikate/512/Close-icon.png); */
        background-size: 100% 100%;
        background-repeat: no-repeat;
    }

    .table td,
    .table th {
        padding: 5px 10px;
    }

    .form-check-input[type="checkbox"] {
        width: 17px;
        height: 17px;
    }

</style>
@endsection
