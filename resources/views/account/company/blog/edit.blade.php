@extends('layouts.client.vendor')
@section('title','Edit Blog')
@section('content')
<?php
if (isset($data['blogDetails'])) {
    $blogs = $data['blogDetails'];
}
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
if (isset($data['seoAndTraslationData'])) {
    $seoAndTraslationData = $data['seoAndTraslationData'];
}
?>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Edit Blog</h1>
        <x-client.company_account_menu />
    </div>
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
            <div class="card-body card-body-mobile-alignment">
                <form action="{{ route('company.blog.update', $blogs->id )}}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >

                    <div class="row">
                    
                        <div class="col-md-12">
                        <h4>Edit Blog</h4>
                            <ul class="nav nav-tabs cust-nav-tab" role="tablist">
                                @foreach($data['languagesData'] as $languagesrow)
                                <li class="nav-item">
                                    <a class="nav-link {{ $languagesrow->id == 1 ? 'active' : '' }}" data-bs-toggle="tab" href="#{{ $languagesrow->name }}" role="tab">
                                        <span>{{ $languagesrow->name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            <div class="tab-content cust-tab-cont notranslate">
                                @foreach($data['blogAndTraslationData'] as $blogdatarow)
                                <?php $languagecodenameData = Helper::getLanguagesDataById($blogdatarow->language_id); ?>
                                <div class="tab-pane {{ $blogdatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodenameData->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Blog title
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" id="cat-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="blog_title_{{$languagecodenameData->code}}" data-id="{{$languagecodenameData->code}}" value="{{ old('blog_title' ,$blogdatarow->title) }}" placeholder="Enter blog title {{ $languagecodenameData->name }}" class="blognametitle seoinputname form-control p-0 border-0"> -->
                                            <input type="text" id="cat-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="blog_title_{{$languagecodenameData->code}}" data-id="{{$languagecodenameData->code}}" value="{{ old('blog_title' ,$blogdatarow->title) }}" placeholder="Enter blog title" class="blognametitle seoinputname form-control p-0 border-0">
                                            <input type="hidden" name="blogtranlation_{{$languagecodenameData->code}}" value="{{ $blogdatarow->id }}" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('blog_title'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4 notranslate">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <!-- <input type="text" id="seo-{{$languagecodenameData->code}}" name="seo_{{$languagecodenameData->code}}" value="{{ $blogdatarow->slug }}" placeholder="Blog SEO in {{ $languagecodenameData->name }}" class="form-control p-0 border-0" readonly > -->
                                            <input type="text" id="seo-{{$languagecodenameData->code}}" name="seo_{{$languagecodenameData->code}}" value="{{ $blogdatarow->slug }}" placeholder="Blog SEO" class="form-control p-0 border-0" readonly >
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0 notranslate">
                                            <textarea rows="5" name="blog_content_{{$languagecodenameData->code}}" class="blogdesc ckeditor form-control p-0 border-0">{{ old('blog_content', $blogdatarow->description) }}</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="page_type" value="{{ $seoAndTraslationData[0]->page_type??'' }}" class="form-control p-0 border-0">
                                    <input type="hidden" name="module_id" value="{{ $seoAndTraslationData[0]->module_id??'' }}" class="form-control p-0 border-0">
                                    
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-group mb-4 col-md-12">
                            <label class="col-md-12 p-0">Meta keywords</label>
                            <div class="col-md-12 border-bottom p-0 notranslate">
                                <input type="text" name="meta_keywords_en" value="{{ old('meta_keywords_en', $seoAndTraslationData[0]->meta_keyword??'') }}" placeholder="Enter Meta Keywords Name" class="metakey form-control p-0 border-0" autocomplete=off>
                                
                            </div>
                        </div>

                        <div class="form-group mb-4 col-md-12">
                            <label class="col-md-12 p-0">Meta description</label>
                            <div class="col-md-12 border-bottom p-0 notranslate">
                                <textarea rows="5" id="metadesc" name="meta_description_en" onkeyup="limitWords(this.id)" class="metaDescp form-control p-0 border-0">{{ old('meta_description_en', $seoAndTraslationData[0]->meta_description??'') }}</textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Category<span class="notranslate" style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="category" id="category" class="form-control select2" data-allow-clear="true" data-placeholder="Select category">
                                        @foreach($category as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $blogs['category_id'] ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('category'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id}}">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 blog-infoicon">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Choose image (800 X 800)px
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
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <?php if($blogs->blog_image!=""){ ?>
                                    <img src="{{ env('IMAGE_PATH').'images/blog/'.$blogs->blog_image.'.webp' }}" id="preview-thumbnail-img" alt="blog-img" width="70px" height="70px" />
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="blog-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-primary">Save</button>
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
<script type="text/javascript">
    $(document).ready(function() {

        $("#loader").fadeOut("slow");

        $(".seoinputname-bk").focusout(function() {
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
