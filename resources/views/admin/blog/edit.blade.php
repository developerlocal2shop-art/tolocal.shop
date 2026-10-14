@extends('layouts.adminmaster')
@section('title','Blog')
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
//  echo "<pre>"; print_r($data['blogAndTraslationData']);die;
?>

<style>
     .cke_notifications_area {
        display: none !important;
    } 
    .cust-tab-cont .tab-pane.active {
        background: none;
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
        transform: translate(-12%, 7%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:200px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #tooltip:hover:after {
        top: 15px;
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
    .errorClass { border:  1px solid red !important; }
</style>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Blog Edit</h4>
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

                <form action="{{ route('admin.blog.updateblog', $blogs->id) }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}

                    <input type="hidden" id="lati" name="lati" >
                    <input type="hidden" id="long" name="long" >

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
                                @foreach($data['blogAndTraslationData'] as $blogdatarow)
                                <?php $languagecodenameData = Helper::getLanguagesDataById($blogdatarow->language_id); ?>
                                <div class="tab-pane {{ $blogdatarow->language_id == 1 ? 'active' : '' }}" id="{{ $languagecodenameData->name }}" role="tabpanel">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Blog title
                                        <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                        </label>
                                        <div class="col-md-12 border-bottom @error('blog_title_'.$languagecodenameData->code) errorClass @enderror">
                                            <input type="text" id="cat-{{$languagecodenameData->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="blog_title_{{$languagecodenameData->code}}" data-id="{{$languagecodenameData->code}}" value="{{ old('blog_title' ,$blogdatarow->title) }}" placeholder="Enter blog title {{ $languagecodenameData->name }}" class="blognametitle seoinputname form-control border-0">
                                            <input type="hidden" name="blogtranlation_{{$languagecodenameData->code}}" value="{{ $blogdatarow->id }}" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('blog_title_'.$languagecodenameData->code){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">SEO</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" id="seo-{{$languagecodenameData->code}}" name="seo_{{$languagecodenameData->code}}" value="{{ $blogdatarow->slug }}" placeholder="Blog SEO in {{ $languagecodenameData->name }}" class="form-control border-0" readonly >
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="blog_content_{{$languagecodenameData->code}}" class="blogdesc ckeditor form-control p-0 border-0">{{ old('blog_content', $blogdatarow->description) }}</textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="page_type" value="{{ $seoAndTraslationData[0]->page_type??'' }}" class="form-control p-0 border-0">
                                    <input type="hidden" name="module_id" value="{{ $seoAndTraslationData[0]->module_id??'' }}" class="form-control p-0 border-0">
                                    <hr><hr>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta keywords</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="meta_keywords_{{$languagecodenameData->code}}" value="{{ old('meta_keywords', $seoAndTraslationData[0]->meta_keyword??'') }}" placeholder="Enter Meta Keywords Name in {{ $languagecodenameData->name }}" class="metakey form-control p-0 border-0" autocomplete=off>
                                            <input type="hidden" name="seolangtranlation_{{$languagecodenameData->code}}" value="{{ $seoAndTraslationData[0]->id??'' }}" class="form-control p-0 border-0">
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Meta description</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="metadesc" name="meta_description_{{$languagecodenameData->code}}" onkeyup="limitWords(this.id)" class="metaDescp form-control p-0 border-0">{{ old('meta_description', $seoAndTraslationData[0]->meta_description??'') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0">Category<span style="color:red;">*</span></label>
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


                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4 cus-title">
                                <label class="col-md-12 p-0" style="display: flex;">Choose image (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="imageuploadify @error('image') errorClass @enderror">
                                <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="image" onchange="blogloadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4 cus-title">
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
                        <!-- <div class="col-md-6">
                            <img src="{{ asset('storage/app/public/blog/'.$blogs->blog_image) }}" />
                        </div> -->
                    </div>

                    <div class="form-group mb-4">
                        <div class="col-sm-12">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column -->


    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>
{{-- <script src="https://cdn.ckeditor.com/4.14.0/standard-all/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor(function() {});
    });
</script> --}}
<script>
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
    var blogloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

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
                    , url: baseUrl + '/admin/blogslug'
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
@endsection
