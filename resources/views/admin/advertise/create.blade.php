@extends('layouts.adminmaster')
@section('title','Competition')
@section('content')

<?php

//  echo "<pre>"; print_r($blogs);die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Competition</h4>
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

                <form action="{{ route('admin.advertise.submit') }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                <div class="row">
                <div class="col-md-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Heading
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="heading" id="heading" value="{{ old('heading') }}" placeholder="Enter Heading" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('heading'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                           @php

                                $allDirectory = Helper::getAllDirectory();
                            @endphp

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Assign Company</label>
                                <div class="col-md-12 border-bottom p-0 productselectdir">
                                    <select class="form-control" name="company_id[]" id="directoryId" multiple style="width: 100%;">
                                        <option value="0">None</option>
                                        @foreach($allDirectory as $directoryrow)
                                            <option value="{{ $directoryrow->id }}">
                                                {{ $directoryrow->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('company_id'){{ $message }}@enderror</span>
                            </div>

                        </div>

                        <div class="col-md-2">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount type</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="discount_type" id="discount_type" class="form-control border-0">
                                        <option value="Fixed" {{ old('discount_type') == 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="Percentage" {{ old('discount_type') == 'Percentage' ? 'selected' : '' }}>Percentage</option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('discount_type'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Amount</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="number" name="price" id="price" value="{{ old('price') }}" placeholder="Enter Price" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('price'){{$message}} @enderror</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Discount Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="discount_text" id="discount_text" value="{{ old('discount_text') }}" placeholder="Enter Discount Text" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('discount_text'){{$message}} @enderror</span>
                            </div>
                        </div>

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
                                <label class="col-md-12 p-0"> Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="bottomtext" id="bottomtext" value="{{ old('bottomtext') }}" placeholder="Enter Bottom Text" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('bottomtext'){{$message}} @enderror</span>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="link" id="link" value="{{ old('link') }}" placeholder="Enter Link" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('link'){{$message}} @enderror</span>
                            </div>
                        </div> --}}

                    </div>
                    <div class="row">



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

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Choose image (800 X 800)px</label>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here</span>

                                    <input type="file" class="form-control" name="image" onchsange="advertisementloadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                </div>
                            </div>
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
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
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

        $('.ckeditor').ckeditor();
    });

</script>
<script type="text/javascript">
    var advertisementloadFile = function(event) {
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
</style>
@endsection
