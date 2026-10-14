@extends('layouts.adminmaster')
@section('title','System Setting')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php
if(isset($settingDetails)){
    $settingDetails = $settingDetails;
}
?>

<style>
    .cus-title label {
        font-weight: bold !important;
        color: #203864 !important;
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
</style>

<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Frontend Setting</h4>
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

        <div class="row">
            <!-- Column -->

            <div class="card cus-title">
                <div class="card-body">
                    <?php if(empty($settingDetails['id'])){?>
                    <form action="{{ route('admin.frontendsetting.submitFrontendsetting') }}" method="POST" class="form-horizontal form-material" enctype="multipart/form-data">
                        <?php } else{ ?>
                        <form action="{{ route('admin.frontendsetting.updateFrontendsetting') }}" method="POST" class="form-horizontal form-material" enctype="multipart/form-data">
                            <?php } ?>
                            {{ csrf_field() }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Banner Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="banner_title" id="banner_title" value="{{ old('banner_title', $settingDetails['banner_title'] ?? '') }}" placeholder="Enter banner title" class="form-control p-0 border-0" required>
                                </div>
                                <span class="text-danger">@error('banner_title'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Banner Sub Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="banner_sub_title" id="banner_sub_title" value="{{ old('banner_sub_title', $settingDetails['banner_sub_title'] ?? '') }}" placeholder="Enter banner sub title" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('system_title'){{$message}} @enderror</span>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Slogan</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="slogan" id="slogan" value="{{ old('slogan', $settingDetails['slogan'] ?? '') }}" placeholder="Enter slogan" class="form-control p-0 border-0">
                                        </div>
                                        <span class="text-danger">@error('slogan'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Banner/Video</label>
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="pay" name="bannertype" value="banner" {{ $settingDetails['bannertype']=="banner" ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="1">Banner </label>
                                        </div>
                                        <div class="custom-control custom-checkbox">
                                            <input type="radio" class="pay" name="bannertype" value="video" {{ $settingDetails['bannertype']=="video" ? 'checked' : '' }} style="margin-left: 20px;">
                                            <label class="custom-control-label" for="1">Video </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Choose Logo Image</label>
                                        <input type="file" class="form-control" name="logo_image" onchange="logoloadFile(event)">
                                    </div>
                                    <span class="text-danger">@error('logo_image'){{$message}} @enderror</span>
                                </div>
                                <div class="col-md-6 bannerImg">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Choose banner Image</label>
                                        <input type="file" class="form-control" name="banner_image" onchange="bannerloadFile(event)">
                                    </div>
                                    <span class="text-danger">@error('banner_image'){{$message}} @enderror</span>
                                </div>
                                <div class="col-md-6 bannervideo">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Choose Banner Video</label>
                                        <input type="file" class="form-control" name="banner_video">
                                    </div>
                                    <span class="text-danger">@error('banner_video'){{$message}} @enderror</span>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Preview</label>
                                        <div class="col-md-12 logoPreview mb-4" id="logoPreview">
                                            <?php if(empty($settingDetails['id'])){?>
                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-logo-img" alt="Logo-img" width="70px" height="70px" />
                                            <?php } else{ ?>
                                            <img src="{{ env('IMAGE_PATH').'files/logo/'.$settingDetails['logo_name_thumbnail'] }}" id="preview-logo-img" alt="Logo-img" width="70px" height="70px" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 bannerImg">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Preview</label>
                                        <div class="col-md-12 bannerPreview mb-4" id="bannerPreview">
                                            <?php if(empty($settingDetails['id'])){?>
                                            <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-banner-img" alt="Banner-img" width="70px" height="70px" />
                                            <?php } else{ ?>
                                            <img src="{{ env('IMAGE_PATH').'files/banner/'.$settingDetails['banner_name_thumbnail'] }}" id="preview-banner-img" alt="Banner-img" width="70px" height="70px" />
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-10 p-0">Cookie Note</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="cookie_note" id="cookie_note" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('cookie_note', $settingDetails['cookie_note'] ?? '') }}" placeholder="Enter cookie note" class="form-control p-0 border-0" required>
                                    <label class="col-md-2">Active</label><input type="checkbox" name="active" id="active" value="1" {{ $settingDetails['active'] == '1' ? 'checked' : '' }} required>
                                </div>
                                <span class="text-danger">@error('cookie_note'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Cookie Policy</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="cookie_policy" id="cookie_policy" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="ckeditor form-control p-0 border-0" required>{{ old('cookie_policy', $settingDetails['cookie_policy'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">About Us</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="about_us" id="about_us" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="ckeditor form-control p-0 border-0">{{ old('about_us', $settingDetails['about_us'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Terms and Condition</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="terms_and_condition" id="terms_and_condition" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="ckeditor form-control p-0 border-0">{{ old('terms_and_condition', $settingDetails['terms_and_condition'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Privacy Policy</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="privacy_policy" id="privacy_policy" maxlength="900" onkeyup="charCount(this.value,this.id,900)" class="ckeditor form-control p-0 border-0">{{ old('privacy_policy', $settingDetails['privacy_policy'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">FAQ</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="faq" id="faq" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="ckeditor form-control p-0 border-0">{{ old('faq', $settingDetails['faq'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Facebook Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="facebook_link" id="facebook_link" value="{{ old('facebook_link', $settingDetails['facebook_link'] ?? '') }}" placeholder="Enter facebook link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('facebook_link'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Twitter Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="twitter_link" id="twitter_link" value="{{ old('twitter_link', $settingDetails['twitter_link'] ?? '') }}" placeholder="Enter twitter link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('twitter_link'){{$message}} @enderror</span>
                            </div>


                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Linkedin Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="linkedin_link" id="linkedin_link" value="{{ old('linkedin_link', $settingDetails['linkedin_link'] ?? '') }}" placeholder="Enter linkedin link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('linkedin_link'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Google Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="google_link" id="google_link" value="{{ old('google_link', $settingDetails['google_link'] ?? '') }}" placeholder="Enter google link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('google_link'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Instagram Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="instagram_link" id="instagram_link" value="{{ old('instagram_link', $settingDetails['instagram_link'] ?? '') }}" placeholder="Enter instagram link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('instagram_link'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Pinterest Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="pinterest_link" id="pinterest_link" value="{{ old('pinterest_link', $settingDetails['pinterest_link'] ?? '') }}" placeholder="Enter instagram link" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('pinterest_link'){{$message}} @enderror</span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="panel-heading cust-tab-head">
                                        <h4 class="box-title">Tax</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Tax value</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="taxval" id="taxval" value="{{ old('taxval', $settingDetails['taxval'] ?? '') }}" placeholder="Enter tax" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('taxval'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Flat Rate</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="flat_rate" id="flat_rate" value="{{ old('flat_rate', $settingDetails['flat_rate'] ?? '') }}" placeholder="Enter Flat Rate" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('flat_rate'){{$message}} @enderror</span>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Shipping Value</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="shipping_value" id="shipping" value="{{ old('shipping_value', $settingDetails['shipping_value'] ?? '') }}" placeholder="Enter Shipping Value" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('shipping'){{$message}} @enderror</span>
                            </div>
        <?php /* --------------  Code add for Set the value of commission globally --------------------------------- */ ?>
                            <div class="row mb-4">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="panel-heading cust-tab-head">
                                        <h4 class="box-title">Platform Commission(Default value set by admin)</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Commission value(in percentage)
                                    <div id="tooltip">
                                        <span id="tooltipText">No need of % sign insert in input box. Only enter numeric value.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="commission_val" id="commissionval" value="{{ old('commission_val', $settingDetails['commission_default_value'] ?? '') }}" placeholder="Enter Commission in percentage" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('commission_val'){{$message}} @enderror</span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="panel-heading cust-tab-head">
                                        <h4 class="box-title">Default competition expiry</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Default competition will be expire in Day (For vendor or user)
                                    <div id="tooltip">
                                        <span id="tooltipText">No need of % sign insert in input box. Only enter numeric value.</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="default_competition_expiry" id="default_competition_expiry" value="{{ old('default_competition_expiry', $settingDetails['default_competition_expiry'] ?? '') }}" placeholder="Enter default competition val in day" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('default_competition_expiry'){{$message}} @enderror</span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="panel-heading cust-tab-head">
                                        <h4 class="box-title">Hide/Show Vendor's contact</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Vendor Contacts
                                    <div id="tooltip">
                                        <span id="tooltipText">Hide/Show Vendor's contact</span>
                                        <span><i class="fas fa-info-circle"></i></span>
                                    </div>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="hide_show_vendor_contact" class="form-control p-0 border-0">
                                        <option @if($settingDetails['hide_show_vendor_contact'] == 'no') selected @endif> No</option>
                                        <option @if($settingDetails['hide_show_vendor_contact'] == 'yes') selected @endif> Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <input type="hidden" name="id" id="id" value="{{$settingDetails['id']}}">
                                     @if ($user->hasPermission('update') == true)
                                    <button type="submit" class="btn btn-info">Save</button>
                                    @endif
                                </div>
                            </div>
                        </form>
                </div>
            </div>

            <!-- Column -->
        </div>

    </div>

    <footer class="footer text-center">

    </footer>

</div>

</div>
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/44.0.0/ckeditor5.css">
<script src="https://cdn.ckeditor.com/ckeditor5/44.0.0/ckeditor5.umd.js"></script>
<script type="text/javascript">
   
        // $(document).ready(function() {
        //     if (typeof CKEDITOR !== 'undefined') {
        //         $('.ckeditor').each(function() {
        //             CKEDITOR.replace($(this).attr('id'));
        //         });
        //     } else {
        //         console.error("CKEditor is not loaded.");
        //     }
        // });

        const {
            ClassicEditor,
            Essentials,
            Paragraph,
            Bold,
            Italic,
            Font
        } = CKEDITOR;

        // Define the configuration for CKEditor
        const editorConfig = {
            licenseKey: 'eyJhbGciOiJFUzI1NiJ9.eyJleHAiOjE3NjU2NzAzOTksImp0aSI6IjJjZDljNzczLTVkM2ItNDc1YS1iMjQ4LTgwMGY4ZTUyZDFhMiIsInVzYWdlRW5kcG9pbnQiOiJodHRwczovL3Byb3h5LWV2ZW50LmNrZWRpdG9yLmNvbSIsImRpc3RyaWJ1dGlvbkNoYW5uZWwiOlsiY2xvdWQiLCJkcnVwYWwiXSwiZmVhdHVyZXMiOlsiRFJVUCIsIkJPWCJdLCJ2YyI6IjkxMjVjOThkIn0.dxzQPC2UBV3RRJReBOPJ3k5F8GB4Q9gM9yyr0_wAmelMlIkIFcRxehFFzziAmkfFza__CUysvGFfGgx1st40Yg',
            plugins: [Essentials, Paragraph, Bold, Italic, Font],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
            ]
        };

        // Select all elements by IDs or a specific class
        const editorElements = document.querySelectorAll('#about_us, #cookie_policy, #terms_and_condition, #privacy_policy, #faq');

        // Loop through the selected elements and initialize CKEditor
        editorElements.forEach(element => {
            ClassicEditor
                .create(element, editorConfig)
                .then(editor => {
                    console.log('Editor initialized for:', element.id);
                    // Optionally store editor instances globally if needed
                    window[element.id + '_editor'] = editor;
                })
                .catch(error => {
                    console.error('Error initializing editor for:', element.id, error);
                });
        });



</script>
<script type="text/javascript">
    var logoloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-logo-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };
    var bannerloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-banner-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>
<script>
    $(document).ready(function() {
        var bannerType = $('input[name=bannertype]:checked').val();
        if (bannerType != "") {
            if (bannerType == "banner") {
                $(".bannerImg").show();
                $(".bannervideo").hide();
            } else if (bannerType == "video") {
                $(".bannerImg").hide();
                $(".bannervideo").show();
            } else {
                $(".bannerImg").show();
                $(".bannervideo").hide();
            }
        } else {
            $(".bannerImg").hide();
            $(".bannervideo").hide();
        }
        // console.log("Radio selected: ",discountoffer)

        $("input[name=bannertype]").change(function() {
            if ($(this).val() == "banner") {
                $(".bannerImg").show();
                $(".bannervideo").hide();
            } else if ($(this).val() == "video") {
                $(".bannerImg").hide();
                $(".bannervideo").show();
            } else {
                $(".bannerImg").show();
                $(".bannervideo").hide();
            }

        });
    });

</script>
@endsection
