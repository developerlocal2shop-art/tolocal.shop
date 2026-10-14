@extends('layouts.client.vendor')
@section('title','Edit Events')
@section('content')
<?php
if (isset($data['eventsDetails'])) {
    $eventsDetails = $data['eventsDetails'];
}
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Edit Events</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-md-12">
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
            </div>
        </div>

        <!--Main Area-->
        <div class="card">
            <div class="card-body">
                <h2>Events</h2>
                <form action="{{ route('company.events.update', $eventsDetails->id) }}" method="POST" class="" enctype="multipart/form-data" onsubmit="return submitForm()">

                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >

                    <div class="row">
                        <input type="hidden" name="page_type" value="{{ $data['MetaTag']->page_type??'' }}" >
                        <input type="hidden" name="module_id" value="{{ $data['MetaTag']->module_id??'' }}" >
                        <div class="col-md-12">
                            <ul class="nav nav-tabs cust-nav-tab notranslate" role="tablist">
                                @foreach($data['languagesData'] as $languagesrow)
                                <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content cust-tab-cont">
                                @foreach($data['eventsAndTraslationData'] as $eventdatarow)
                                <?php $languagecodenameData = Helper::getLanguagesDataById($eventdatarow->language_id); ?>
                                <div class="tab-pane {{ $eventdatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodenameData->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Event title
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom p-0 notranslate">
                                            <!-- <input type="text" name="events_title_{{$languagecodenameData->code}}" id="events-title-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" value="{{ old('blog_title' ,$eventdatarow->title) }}" placeholder="Enter events title {{ $languagecodenameData->name }}" class="form-control p-0 border-0"> -->
                                            <input type="text" name="events_title_{{$languagecodenameData->code}}" id="events-title-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" value="{{ old('blog_title' ,$eventdatarow->title) }}" placeholder="Enter events title" class="form-control p-0 border-0">
                                            <input type="hidden" name="eventtranlation_{{$languagecodenameData->code}}" value="{{ $eventdatarow->id }}" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('blog_title'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0 notranslate">
                                            <textarea rows="5" name="events_content_{{$languagecodenameData->code}}" class="ckeditor form-control p-0 border-0">{{ old('blog_content', $eventdatarow->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                            </div>

                            <label class="col-md-12 p-0">Meta description</label>
                            <div class="col-md-12 p-0 border-bottom notranslate">
                                <textarea rows="3" name="meta_description" class="form-control"> {{  $eventdatarow->meta_description??'' }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Category<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control select2" data-allow-clear="true" data-placeholder="Select category">
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $eventsDetails->category_id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('category'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">External Link</label>
                                <input type="text" class="form-control notranslate" name="external_link" value="{{ old('external_link', $eventsDetails->externalLink) }}">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">

                    <div class="row">

                        <div class="col-md-6 events-mainimg-infoicon">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Choose image <span class="notranslate" style="color:red;">*</span>(800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="image" onchange="eventsloadFile(event)">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 events-galimg-infoicon" id="multi-gal-image">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Gallery Image<span class="notranslate" style="color:red;">*</span> (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="col-md-12 imageuploadify">

                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload<br>You have to upload multiple images at a time</span>
                                    <input type="hidden" name="thumb_hidden_img" id="thumb_hidden_img" value="">
                                    <input type="file" class="form-control" name="event_gal_image[]" id="eventgal-image" multiple>
                                </div>
                                <span class="text-danger">max 20 pictures allowed!</span>
                                <span class="text-danger">@error('event_gal_image'){{$message}} @enderror</span>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <?php if($eventsDetails->event_image!=""){ ?>
                                    <img src="{{ env('IMAGE_PATH').'files/events/'.$eventsDetails->event_image }}" id="preview-thumbnail-img" alt="blog-img" width="70px" height="70px" />
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="blog-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 galleryimgPreview" id="galleryimgPreview">
                                    @php

                                    $galleryimagename_thumb = $eventsDetails->eventgallery;

                                    @endphp
                                    <?php if (count($galleryimagename_thumb) > 0) { ?>
                                    @foreach($galleryimagename_thumb as $imagenamerow)
                                    <div class="thumbnail" data-thumbimages="{{ $imagenamerow->name_thumbnail }}">
                                        <img src="{{ env('IMAGE_PATH').'files/events/'.$imagenamerow->name_thumbnail }}" data-id="{{ $imagenamerow->id }}" width="70px" height="70px">
                                        <span class="closebtn"><i class="fas fa-trash" style="color:#ff0505; " aria-hidden="true"></i></span>

                                    </div>
                                    @endforeach
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gallery-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>

                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="col-md-12 p-0">
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Location</label>
                            <input type="text" class="form-control notranslate" name="event_location" value="{{ old('event_location', $eventsDetails->location) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Start Date</label>
                                <input type="date" required class="form-control" name="event_start_date" value="{{ old('event_start_date', $eventsDetails->start_date) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Start Time</label>
                                <input type="time" required class="form-control" name="event_start_time" value="{{ old('event_start_time', $eventsDetails->start_time) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">End Date</label>
                                <input type="date" required class="form-control" name="event_end_date" value="{{ old('event_end_date', $eventsDetails->end_date) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">End Time</label>
                                <input type="time" required class="form-control" name="event_end_time" value="{{ old('event_end_time', $eventsDetails->end_time) }}">
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row cus-title">
                        {{-- <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="pay" name="status" value="Active" {{ $eventsDetails->status=='Active' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" class="pay" name="status" value="Inactive" {{ $eventsDetails->status=='Inactive' ? 'checked' : '' }} style="margin-left: 20px;">
                                    <label class="custom-control-label" for="1">Inactive </label>
                                </div>
                            </div>
                        </div> --}}

                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Display event status</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="display_status" id="display_status" class="form-control select2" data-allow-clear="true" data-placeholder="Select display status">
                                        <option value="" {{ ' ' == $eventsDetails->display_status ? 'selected' : '' }}> None</option>
                                        <option value="display_on_vendor_page_only" {{ 'display_on_vendor_page_only' == $eventsDetails->display_status ? 'selected' : '' }}> Display on vendor page only</option>
                                        <option value="display_on_home_page_only" {{ 'display_on_home_page_only' == $eventsDetails->display_status ? 'selected' : '' }}> Display on home page only</option>
                                        <option value="display_both" {{ 'display_both' == $eventsDetails->display_status ? 'selected' : '' }}> Display on both pages</option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('display_status'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>
<script type="text/javascript">

    $(document).ready(function() {
        $("#galleryimgPreview").sortable();
        //$('.ckeditor').ckeditor();
        ClassicEditor
        .create(document.querySelector('.ckeditor'))
        .then(editor => {
            console.log('Editor initialized', editor);
        })
        .catch(error => {
            console.error(error);
        });
    });

    function submitForm() {
        var thumbimages = [];
        $('.galleryimgPreview div').each(function() {
            thumbimages.push($(this).data('thumbimages'));
        });
        $('#thumb_hidden_img').val(thumbimages.join(','));
        return;
    }

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

    $(document).on('click', ".closebtn", function() {
        var val = $(this).closest(".thumbnail");
        var imageid = $(this).closest(".thumbnail").find('img').attr('data-id');
        //alert(imageid);
        var imagevalue = '/company/delete-event-gallery';
        $.ajax({
            url: baseUrl + imagevalue
            , type: "POST"
            , data: {
                id: imageid
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                // console.log('imageid',result);
                if (result == true) {
                    val.remove();
                }

            }
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
<script type="text/javascript">
     function showPosition() {
        if(navigator.geolocation) {
            //navigator.geolocation.getCurrentPosition(showMap, showError);
            navigator.geolocation.getCurrentPosition(function(position) {
                    var positionInfo1 = position.coords.latitude;
                    var positionInfo2 =  position.coords.longitude;
                    document.getElementById("lati").value = positionInfo1;
                    document.getElementById("long").value = positionInfo2;
                    //console.log("lat"+positionInfo1+"log"+positionInfo2);
                    <?php 
                    //} }
                    //$lati ="<script>document.write(positionInfo1)</script>"; 
                    //$long ="<script>document.write(positionInfo2)</script>";            
                    ?>
                });
        } else {
            alert("Sorry, your browser does not support HTML5 geolocation.");
        }
    }
    window.onload = showPosition;
</script>
<style type="text/css">
    .custom-control-label {
        position: unset;
    }

    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: unset;
    }

</style>
<style>
    .select2-container-multi {
        width: 300px
    }

    .thumbnail {
        position: relative;
        display: inline-flex;
        width: 70px;
        height: 70px;
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

    .btn.btn-info {
        background: #2cabe3;
        border-color: #2cabe3;
        color: #fff;
        margin-top: 20px;
        min-width: 120px;
    }

    #tooltip {
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }

</style>
@endsection
