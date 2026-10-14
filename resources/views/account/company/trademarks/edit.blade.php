@extends('layouts.client.vendor')
@section('title','Trademark')
@section('content')

<?php
if (isset($data['trademarkDetails'])) {
    $trademarkDetails = $data['trademarkDetails'];
}
?>
<div class="sub_header_in sticky_header filter_sub_blue_header">

    <div class="container">
        <h1>Trademark Categories</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<style>
    #tooltip{
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

    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body card-body-mobile-alignment">

            <h4 class="page-title">Trademark Edit</h4>

                <form action="{{ route('company.trademarks.update', $trademarkDetails->id) }}" method="POST" class="" enctype="multipart/form-data">

                    {{ csrf_field() }}
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->user()->id;}}">

                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Trademark Category
                                <a class="btn btn-xs" target="_blank" title="Google Translation" href="<?php echo url("https://translate.google.se/"); ?>"><i class="fa fa-language" aria-hidden="true" style="font-size:22px; color: #203864;"></i></a>
                                </label>
                                <div class="">

                                    <select class="form-control" name="tradecategory">
                                        <option>None</option>
                                        @foreach($categorydata as $categorysrow)
                                        <option value="{{ $categorysrow->id }}" {{ $categorysrow->id == $trademarkDetails->category_id ? 'selected' : '' }}> {{ $categorysrow->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group mb-4" style=" margin: 16px 15px;  ">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" onkeyup="charCount(this.value, this.id, 50); checkTrademark(this.value)" name="trademark_name" id="trademark-name" maxlength="50" onkeyup="charCount(this.value,this.id,50)" value="{{ old('trademark_name', $trademarkDetails->name) }}" placeholder="Enter Trademark Name" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger" id="trademark-error">@error('trademark_name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4 trademark-infoicon">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0" style="display: flex;">Choose image (800 X 800)px
                                    <div id="tooltip">
                                        <span id="tooltipText">Best result with (800 X 800)px - other dimensions are acceptable but visualization can be poor.</span>
                                        <span><i class="fas fa-info-circle" ></i></span>
                                    </div>
                                </label>
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

                    </div>

                    <div class="form-group mb-4">
                        <div class="col-sm-12">
                            <button type="submit" id="trade_btn" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Column -->


    </div>

</div>
</div>
</main>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">

    function checkTrademark(name) {
        if (name.length < 3) {
            $('#trademark-error').text('');
            return;
        }
        var id = "{{ $trademarkDetails->id }}";
        $.ajax({
            url: "{{ route('check.trademark') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                trademark_name: name,
                id: id
            },
            success: function(response) {
                if (response.exists) {
                    $('#trade_btn').prop('disabled', true);
                    $('#trademark-error').html('<b style="color:#d72121;">This trademark name is already taken.</b>');
                } else {
                    $('#trade_btn').prop('disabled', false);
                    $('#trademark-error').html('<b style="color:#18d118;"> Available</b>');
                }
            }
        });
    }

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

@endsection
