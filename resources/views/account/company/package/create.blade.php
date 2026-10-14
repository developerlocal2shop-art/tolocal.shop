@extends('layouts.client.vendor')
@section('title','Package')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Create Package</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
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
                    <h4 class="page-title">Packages</h4>
                    <form action="{{ route('admin.package.submit') }}" method="POST" class="" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                    @foreach($data['languagesData'] as $languagesrow)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                            <span>{{ $languagesrow->name }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content cust-tab-cont">
                                    @foreach($data['languagesData'] as $languagesrow)
                                    <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Package title</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <input type="text" name="plan_title_{{$languagesrow->code}}" id="events-title-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" value="{{ old('plan_title') }}" placeholder="Enter plan title {{ $languagesrow->name }}" class="form-control p-0 border-0">
                                            </div>
                                            <span class="text-danger">@error('plan_title_{{$languagesrow->code}}'){{$message}} @enderror</span>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Content</label>
                                            <div class="col-md-12 border-bottom p-0">
                                                <textarea rows="5" name="plan_content_{{$languagesrow->code}}" class="ckeditor form-control p-0 border-0">{{ old('plan_content') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div class="form-group mb-4 row">

                                        <div class="col-md-6 border-bottom">
                                            <label class="">Price</label>
                                            <input type="text" placeholder="Price" name="price" value="{{ old('price') }}" class="form-control p-0 border-0">
                                        </div>
                                        <div class="col-md-2 border-bottom">
                                            <label class="">Interval</label>
                                            <select type="text" required class="form-control p-0 border-0" name="invoice_interval" value="{{ old('invoice_interval') }}">
                                                <option value="month">Month</option>
                                                <option value="day">Day</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2 border-bottom">
                                            <label class="">Period</label>
                                            <input type="text" required class="form-control p-0 border-0" name="invoice_period" value="{{ old('invoice_period') }}">
                                        </div>
                                    </div>
                                </div>
                                <table id="nomaldays-table" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Modules</th>
                                            <th class="border-top-0">Enable</th>
                                            <th class="border-top-0">Limit</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php foreach($data['packagemodule'] as $key => $value){ ?>
                                        <tr class=" <?= $value->name;?> <?= $value->name.'_row_0';?>">
                                            <td>
                                                @if($value->getparent()) ----- @endif <?php echo $value->name; ?>: </td>
                                            <td> <input class="form-check-input" type="checkbox" name="plan[]" value="{{ $value->name??'' }}"></td>
                                            <td>
                                                <input type="text" placeholder="0" name="limit[]" value="" class="form-control p-0 border-0">
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

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
        </div>
    </div>
    </div>
</main>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
        $("#galleryimgPreview").sortable();
    });

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
            multiImgPreview(this, 'div.galleryimgPreview');
        });
    });

    var eventsloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    $(document).ready(function() {
        // Warning
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            // disable warning
            $(window).off('beforeunload');
        });
    });

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
