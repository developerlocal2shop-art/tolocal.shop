@extends('layouts.client.company')
@section('title','Add New Competition')
@section('content')
<?php
if (isset($data['parent_category'])) {
    $category = $data['parent_category'];
}
?>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Add New Competition</h1>
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
            <div class="card-body">
                <form action="{{ route('company.advertisement.submit')}}" method="POST" class="" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="directoryId" id="directoryId" value="{{auth()->user()->directory_id}}">
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}">

                <div class="row">
                <div class="col-md-6">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Heading<span class="notranslate" style="color:red;">*</span>
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="heading" id="heading" value="{{ old('heading') }}" placeholder="Enter Heading" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('heading'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="price" id="price" value="{{ old('price') }}" placeholder="Enter Price" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('price'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="link" id="link" value="{{ old('link') }}" placeholder="Enter Link" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('link'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="midtext" id="midtext" value="{{ old('midtext') }}" placeholder="Enter Title" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('midtext'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Bottom Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="bottomtext" id="bottomtext" value="{{ old('bottomtext') }}" placeholder="Enter Bottom Text" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('bottomtext'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="description" id="description" value="{{ old('description') }}" placeholder="Enter description" class="form-control border-0">
                                </div>
                                <span class="text-danger">
                                    @error('description')
                                    {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Active Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="active-date" name="start_date" value="{{ old('start_date') }}" class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('start_date'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Expiry Date</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="date" id="expiry-date" name="end_date" value="{{ old('end_date') }}"  class="form-control  border-0" autocomplete=off>
                                </div>
                                <span class="text-danger">@error('end_date'){{$message}} @enderror</span>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 advertisement-infoicon">
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
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here</span>
                                    <input type="file" class="form-control" name="image" onchange="advertisementloadFile(event)">
                                </div>
                                <span class="text-danger">@error('image'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="col-md-6">
                     <br>
                    <h5>Questions <small>(Max 10)</small></h5>
                    <div id="question-wrapper">
                            <div class="form-group question-block">
                                <label>Question 1</label>
                                <div class="input-group">
                                    <input type="text" name="questions[]" class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-danger btn-remove-question">❌</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <button type="button" id="add-question" style="float: right;" class="btn btn-sm btn-secondary mt-2">+ Add Question</button>
                
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
</main>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {


        let maxQuestions = 10;
        let questionWrapper = $('#question-wrapper');

        function updateLabels() {
            questionWrapper.find('.question-block').each(function (index) {
                $(this).find('label').text('Question ' + (index + 1));
            });
        }

        $('#add-question').on('click', function () {
            let count = questionWrapper.find('.question-block').length;

            if (count < maxQuestions) {
                let newQuestion = `
                    <div class="form-group question-block">
                        <label>Question ${count + 1}</label>
                        <div class="input-group">
                            <input type="text" name="questions[]" class="form-control">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-danger btn-remove-question">❌</button>
                            </div>
                        </div>
                    </div>
                `;
                questionWrapper.append(newQuestion);
                updateLabels();
            } else {
                alert('Maximum 10 questions allowed.');
            }
        });

        questionWrapper.on('click', '.btn-remove-question', function () {
            $(this).closest('.question-block').remove();
            updateLabels();
        });


        $('.ckeditor').ckeditor();
    });
    var advertisementloadFile = function(event) {
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
     .imageuploadify {
        padding: 2px !important;
    }
    .thumbnail img {
        width: 70px;
        height: 70px;
    }
    img#preview-thumbnail-img {
        margin: 17px -15px;
    }
    .closebtn {
        display: block;
        position: absolute;
        width: 20px;
        height: 20px;
        top: -9px;
        right: -9px;
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
