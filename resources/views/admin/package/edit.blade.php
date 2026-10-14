@extends('layouts.adminmaster')
@section('title','Package')
@section('content')
<?php

if (isset($data['plansDetails'])) {
    $planData = $data['plansDetails'];
}
?>

<style>
    .cke_notifications_area {
        display: none !important;
    }
    .cust-tab-cont .tab-pane.active {
        background: none;
    }
</style>

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



                <form action="{{ route('admin.package.update', $planData->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                @foreach($data['languagesData'] as $languagesrow)
                                <!-- <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li> -->
                                @endforeach
                            </ul>
                            <div class="tab-content cust-tab-cont">
                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Package title</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="plan_title_{{$languagesrow->code}}" id="events-title-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" value="{{ old('plan_title', json_decode($planData->name) ) }}" placeholder="Enter plan title {{ $languagesrow->name }}" class="form-control p-0 border-0">
                                            <input type="hidden" name="plantranlation_{{$languagesrow->code}}" value="{{ $planData->id }}" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('plan_title_{{$languagesrow->code}}'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="plan_content_{{$languagesrow->code}}" class="ckeditor form-control p-0 border-0">{{ old('plan_content', json_decode($planData->description) ) }} {{ old('plan_content') }}</textarea>
                                        </div>
                                    </div>

                                </div>
                                @endforeach
                                <div class="form-group mb-4 row">

                                    <div class="col-md-6 border-bottom">
                                        <label class="">Price</label>
                                        {{ $planData->currency??'' }} <input type="text" placeholder="Price" name="price" value="{{ old('price', $planData->price) }}" class="form-control p-0 border-0">
                                    </div>


                                    <div class="col-md-2 border-bottom">
                                        <label class="">Interval</label>
                                        <select type="text" required class="form-control p-0 border-0" name="invoice_interval" value="{{ old('invoice_interval', $planData->invoice_interval) }}">
                                            <option value="month" @if($planData->invoice_interval == 'month') selected @endif>Months</option>
                                            {{-- <option value="day" @if($planData->invoice_interval == 'day') selected @endif>Day</option> --}}
                                        </select>
                                    </div>
                                    <div class="col-md-2 border-bottom">
                                        <label class="">Period</label>
                                        <input type="text" required class="form-control p-0 border-0" name="invoice_period" value="{{ old('invoice_period', $planData->invoice_period) }}">
                                    </div>
                                    <div class="col-md-2 border-bottom">
                                        <label class="">Thereafter Price</label>
                                        @if($planData->signup_fee)
                                        {{ $planData->currency }}
                                        @endif
                                         <input type="text" placeholder="After Period Price" name="signup_fee" value="{{ old('signup_fee', $planData->signup_fee) }}" class="form-control p-0 border-0">
                                    </div>

                                </div>
                            </div>

                            <table id="nomaldays-table" class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Modules</th>
                                        <th class="border-top-0">Enable</th>
                                        <th class="border-top-0">Limit</th>
                                        {{-- <th class="border-top-0">Unlimited</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $fea_data = [];
                                    $fea_value;
                                    @endphp
                                    @foreach($data['plansDetails']->planfeatures as $key => $fea_val)
                                    @php
                                        $fea_data[$key] = $fea_val->slug;
                                        $fea_value[$fea_val->slug] = $fea_val->value;
                                    @endphp

                                    @endforeach
                                    <?php
                                    //dd($data['packagemodule']);
                                    foreach($data['packagemodule'] as $key => $value){
                                    ?>
                                    <tr class=" <?=$value->name?> <?=$value->name.'_row_0';?>">
                                        <td>
                                            @if($value->getparent()) ----- @endif <?php echo $value->name; ?>: </td>
                                        <td>
                                            <input class="form-check-input plan" type="checkbox" name="plan[]" id="plan{{ $value->id }}" data-id="{{ $value->id }}" value="{{ $value->name??'' }}" <?php if( in_array($value->slug, $fea_data) ){echo "checked";}?>>
                                        </td>
                                        <td>
                                            <input type="text" value="<?php if( in_array($value->slug, $fea_data) ){echo $fea_value[$value->slug]; }?>" placeholder="0" id="limit{{ $value->id }}" name="limit[]" <?php if( !in_array($value->slug, $fea_data) ){echo "disabled";}?> class="form-control p-0 border-0">
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="row cus-title">
                        <div class="col-md-6">
                            <div class="form-group mb-4">

                                <label class="col-md-12 p-0">Status </label>

                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="1" @if($planData->is_active == 1) checked @endif >
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="0" @if($planData->is_active == 0) checked @endif style="margin-left: 20px;">
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
<script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

         $('.ckeditor').ckeditor(function() {
            // This function will be called repeatedly (as many times as many elements have the 'editors' class).
        });

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
