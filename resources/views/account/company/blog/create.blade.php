@extends('layouts.client.vendor')
@section('title','Add New Blog')
@section('content')
<?php
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Add New Blog</h1>
        <x-client.company_account_menu />
    </div>
</div>
<style>
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
            <div class="card-body card-body-mobile-alignment">
                <form action="{{ route('company.blog.submit')}}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >

                    <div class="row">
                    
                        <div class="col-md-12">
                        <h4>Add New Blog</h4>
                        <p>Note : <i class="fas fa-eye" aria-hidden="true"></i> (Your title and Content automatically convert in other language at the time of saving)</p>
                            <?php /* ?>
                                <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                    @foreach($data['languagesData'] as $languagesrow)
                                    <li class="nav-item">
                                        <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                            <span>{{ $languagesrow->name }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            <?php */ ?>
                            <div class="tab-content cust-tab-cont">
                                @foreach($data['languagesData'] as $languagesrow)
                                <div class="tab-pane {{ $languagesrow->id == 1 ? 'active' : '' }}" id="{{ $languagesrow->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Blog title<span class="notranslate" style="color:red;">*</span>
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" data-id="{{$languagesrow->code}}" name="blog_title_{{$languagesrow->code}}" value="{{ old('blog_title') }}" placeholder="Enter blog title {{ $languagesrow->name }}" class="blognametitle seoinputname form-control p-0 border-0"> -->
                                            <input type="text" id="cat-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" data-id="{{$languagesrow->code}}" name="blog_title_{{$languagesrow->code}}" value="{{ old('blog_title') }}" placeholder="Enter blog title in {{ $languagesrow->name }}" class="blognametitle seoinputname form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('blog_title_en'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4 notranslate">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="SEO in {{ $languagesrow->name }}" class="form-control p-0 border-0" autocomplete=off> -->
                                            <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('$oldvalseo') }}" placeholder="SEO" class="form-control p-0 border-0" autocomplete=off>
                                        </div>
                                        <span class="seo_error" class="text-danger"></span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Content in {{ $languagesrow->name }}</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="content-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="blog_content_{{$languagesrow->code}}" class="blogdesc ckeditor form-control p-0 border-0">{{ old('blog_content') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta keywords</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="meta_keywords_{{$languagesrow->code}}" value="{{ old('meta_keywords_'.$languagesrow->code) }}" placeholder="Enter Meta Keywords Name" class="metakey form-control p-0 border-0" autocomplete=off>
                                        </div>
                                        <span class="text-danger">@error('meta_keywords_'.$languagesrow->code){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="metadesc" name="meta_description_{{$languagesrow->code}}" onkeyup="limitWords(this.id)" class="metaDescp form-control p-0 border-0" placeholder="Enter Meta description Name">{{ old('meta_description_'.$languagesrow->code) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 p-0">
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Category<span class="notranslate" style="color:red;">*</span></label>
                            <div class="col-md-12 border-bottom p-0">
                                <select name="category" id="category" class="form-control select2" data-allow-clear="true" data-placeholder="Select category">
                                    @foreach($category as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger">@error('category'){{$message}} @enderror</span>
                        </div>
                    </div>
                    <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 blog-infoicon">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Choose image<span class="notranslate" style="color:red;">*</span> (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>

                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="image" onchange="blogloadFile(event)">
                                </div>
                                <span class="text-danger">@error('image'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
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
        //$('.ckeditor').ckeditor(function() {});
        ClassicEditor
        .create(document.querySelector('.ckeditor'))
        .then(editor => {
            console.log('Editor initialized', editor);
        })
        .catch(error => {
            console.error(error);
        });
    });
    var blogloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    $(document).ready(function() {
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        $(document).on("submit", "form", function(event) {
            $(window).off('beforeunload');
        });
    });

</script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#loader").fadeOut("slow");

        $(".seoinputname").focusout(function() {
            var languagecode = $(this).attr('data-id');
            var nameText = $("#cat-" + languagecode).val();
            console.log("languagecode: ", languagecode);
            console.log("Actual Name: ", nameText);
            if (nameText != "") {
                $.ajax({
                    type: "POST"
                    , dataType: "json"
                    , url: baseUrl + '/company/blogseo'
                    , data: {
                        catname: nameText
                        , directory: 'blog'
                        , _token: '{{csrf_token()}}'
                    }
                    , success: function(result) {
                        console.log(result.seoChangeData);

                        if (result.blogTypeDetails != "") {
                            $("#seo-" + languagecode).val(result.blogTypeDetails);
                        } else {
                            $("#seo-" + languagecode).val(result.seoChangeData);
                        }
                    }
                });
            } else {
                $("#name-" + languagecode).text("Enter Blog Title First!");
            }
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
<script type="text/javascript">

    $('.blognametitle').on('keypress keyup keydown',function(event) {
    // create the event
    var press = jQuery.Event(event.type);
    var code = event.keyCode || event.which;
    press.which = code ;
    // trigger
        $('.metakey').val(this.value);
        $('.metakey').trigger(event.type, {'event': press});
        }); 
    
    $('.metakey').on('keypress keyup keydown',function(event) {
        // sample
        console.log(event.type);
    });

    //5 words limit
    function limitWords(id) {
        var maxWords=6;
        var d=document.getElementById(id);
        if ( d.value.split(' ').length > maxWords ) {
            t=d.value.substring(0,d.value.lastIndexOf(' '));
            d.value=t.substring(0,t.lastIndexOf(' ')+1);
        }
    }
</script>
@endsection
