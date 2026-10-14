@extends('layouts.adminmaster')
@section('title','Hot Ads | Add New')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Hot Ads</h4>
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

                <form action="{{ route('admin.hotads.submit') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

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
                                            <label class="col-md-12 p-0"> Heading<span style="color:red;">*</span>
                                            <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                            </label>
                                            <div class="col-md-12 border-bottom @error('heading_'.$languagesrow->code) errorClass @enderror">
                                                <input class="seoinputname form-control border-0" type="text" id="heading-{{$languagesrow->code}}" maxlength="40" onkeyup="charCount(this.value,this.id,40)" name="heading_{{$languagesrow->code}}" data-id="{{$languagesrow->code}}" value="{{ old('heading') }}" placeholder="Enter Heading {{ $languagesrow->name }}">
                                            </div>
                                            <span class="text-danger">@error('heading_'.$languagesrow->code){{$message}} @enderror</span>
                                        </div>
                                        {{-- <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">SEO</label>
                                            <div class="col-md-12 border-bottom ">
                                                <input type="text" readonly id="seo-{{$languagesrow->code}}" name="seo_{{$languagesrow->code}}" value="{{ old('seo_'.$languagesrow->code) }}" placeholder="SEO in {{ $languagesrow->name }}" class="form-control border-0" autocomplete=off>
                                            </div>
                                            <span class="seo_error" class="text-danger"></span>
                                        </div> --}}
                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Description</label>
                                            <div class="col-md-12 border-bottom ">
                                                <textarea rows="5" id="description-{{$languagesrow->code}}" maxlength="900" onkeyup="charCount(this.value,this.id,900)" name="description_{{$languagesrow->code}}" class="blogdesc ckeditor form-control border-0">{{ old('description_'.$languagesrow->code) }}</textarea>
                                            </div>
                                        </div>

                                    </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>


                        <div class="row">
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

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Choose image (2000 X 2000)px</label>
                                    <div class="imageuploadify @error('image') errorClass @enderror">
                                        <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                        <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                        <input type="file" class="form-control" name="image" onchange="loadFile(event)">
                                    </div>
                                    <span class="text-danger">@error('image'){{$message}} @enderror</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Preview</label>
                                    <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Link</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="link" id="link" value="{{ old('link') }}" placeholder="Enter Link" class="form-control border-0">
                                    </div>
                                    <span class="text-danger">@error('link'){{$message}} @enderror</span>
                                </div>
                            </div>

                             <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Short Video</label>

                                    <div class="imageuploadify">
                                        <div class="imageuploadify-overlay" style="display: none;">
                                            <i class="fa fa-video-camera"></i>
                                        </div>

                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>

                                        <span class="imageuploadify-message">
                                            Select or Drag &amp; Drop Your Video Here
                                        </span>

                                        <input type="file"
                                            class="form-control"
                                            name="short_video"
                                            accept="video/*"
                                            onchange="previewShortVideo(this)">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4" id="shortVideoPreview">


                                <span class="text-muted">No video uploaded.</span>

                            </div>

                            <hr>


                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">MP3</label>

                                    <div class="imageuploadify">
                                        <div class="imageuploadify-overlay" style="display: none;">
                                            <i class="fa fa-picture-o"></i>
                                        </div>

                                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>

                                        <span class="imageuploadify-message">
                                            Select or Drag &amp; Drop Your File Here
                                        </span>

                                        <input type="file" class="form-control" name="mp3_file"
                                            accept=".mp3,audio/mpeg" onchange="previewMp3(event)">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Preview</label>

                                    <div class="col-md-12 mb-4" id="mp3Preview">

                                            <span class="text-muted">No MP3 file uploaded.</span>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-check mb-4">
                                    <input type="checkbox" class="form-check-input" id="is_home" name="is_home" value="1">
                                    <label class="form-check-label" for="is_home" style=" margin: 6px;">Display on Home</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group form-check mb-4">
                                    <input type="hidden" name="video_display_on_home" value="no">

                                    <input type="checkbox"
                                        class="form-check-input"
                                        id="video_display_on_home" name="video_display_on_home"
                                        value="yes" {{ old('video_display_on_home') }}
                                    >
                                    <label style="margin: 5px;" class="form-check-label ms-2" for="video_display_on_home">
                                        Display Video on Home
                                    </label>
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
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script>
    function previewShortVideo(input) {

        const file = input.files[0];

        if (!file) {
            return;
        }

        // Validate video
        if (!file.type.startsWith('video/')) {
            alert('Please select a valid video file.');
            input.value = '';
            return;
        }

        // Remove old preview
        const preview = document.getElementById('shortVideoPreview');

        // Release previous object URL if any
        if (preview.dataset.objectUrl) {
            URL.revokeObjectURL(preview.dataset.objectUrl);
        }

        const videoUrl = URL.createObjectURL(file);

        preview.dataset.objectUrl = videoUrl;

        preview.innerHTML = `
            <video
                width="100%"
                height="250"
                controls
                playsinline
                preload="metadata">
                <source src="${videoUrl}" type="${file.type}">
                Your browser does not support this video.
            </video>
        `;
    }

    function previewMp3(event) {
        const file = event.target.files[0];
        if (!file) {  return; }
        const audioUrl = URL.createObjectURL(file);

        document.getElementById('mp3Preview').innerHTML = `
            <audio controls class="w-100">
                <source src="${audioUrl}" type="${file.type}">
                Your browser does not support the audio element.
            </audio>
        `;
    }
</script>
<script type="text/javascript">

    $(document).ready(function() {

       $("#directoryId, #pro-tags").select2({
            placeholder: "Select Option "
            , allowClear: true
        });

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
    });

</script>
<script type="text/javascript">

    var loadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>
<style>
.imageuploadify {
    padding: 2px !important;
}
.active {
    background: #fff;
}
</style>
@endsection
