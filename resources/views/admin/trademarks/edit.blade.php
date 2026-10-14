@extends('layouts.adminmaster')
@section('title','Trademark')
@section('content')

<?php

if (isset($data['trademarkDetails'])) {
    $trademarkDetails = $data['trademarkDetails'];
}

  //echo "<pre>"; print_r($trademarkDetails);die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Trademark Edit</h4>
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

                <form action="{{ route('admin.trademarks.update', $trademarkDetails->id) }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="trademark_name" id="trademark-name" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('trademark_name', $trademarkDetails->name) }}" placeholder="Enter Trademark Name" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('trademark_name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Choose image (800 X 800)px</label>
                                <div class="imageuploadify">
                                    <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                    <input type="file" class="form-control" name="image" onchange="tradeloadFile(event)">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Preview</label>
                                <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                    <?php if($trademarkDetails->name_thumbnail!=""){ ?>
                                    <img src="{{ env('IMAGE_PATH').'files/trademark/'.$trademarkDetails->name_thumbnail }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="directory-img" width="70px" height="70px" />
                                    <?php } ?>
                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Icon<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="icon_class" class="form-control icon-picker" placeholder="Add Icon" value="{{ $trademarkDetails->icon_class }}" autocomplete="off" required="">
                                </div>
                                 <span class="text-danger">@error('icon_class'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Trademark Category</label>
                                <div class="col-md-12 ">
    
                                    <select class="form-control" name="tradecategory">
                                        <option>None</option>
                                        @foreach($categorydata as $categorysrow)
                                        <option value="{{ $categorysrow->id }}" {{ $categorysrow->id == $trademarkDetails->category_id ? 'selected' : '' }}> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

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
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });

</script>
<script type="text/javascript">
    var tradeloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>

<script type="text/javascript">
    $(function() {
    $('.icon-picker').iconpicker();
    });
</script>

@endsection
