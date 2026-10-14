@extends('layouts.adminmaster')
@section('title','Edit Admin Detail')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp

<style>
    .cus-title label {
        font-weight: bold !important;
        color: #203864;
    }

</style>


<div class="page-wrapper">

    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Admin Detail</h4>
            </div>

        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="container-fluid">

        @if ($user->hasPermission('view') == true)
       
            <div class="row">
                <!-- Column -->
                <div class="col-lg-4 col-xlg-3 col-md-12">
                    <div class="white-box">
                        <div class="user-bg">
                        <img src="{{ env('IMAGE_PATH').'files/admin/'.$adminDetail->image_name }}" alt="admin-img" width="100%"/>    
                        <!-- <img width="100%" alt="user" src="plugins/images/large/img1.jpg"> -->
                            <div class="overlay-box">
                                <div class="user-content">
                                    <a href="javascript:void(0)"><img src="{{ env('IMAGE_PATH').'files/admin/'.$adminDetail->image_name }}" class="thumb-lg img-circle" alt="img"></a>
                                    <h4 class="text-white mt-2">{{ $adminDetail->name }}</h4>
                                    <h5 class="text-white mt-2">{{ $adminDetail->email }}</h5>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- Column -->
                <!-- Column -->
                <div class="col-lg-8 col-xlg-9 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form class="form-horizontal form-material" action="{{ route('admin.update', $adminDetail->id) }}" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Full Name</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" placeholder="Johnathan Doe" value="{{ $adminDetail->name }}" name="admin_name" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="form-group mb-4 cus-title">
                                    <label for="example-email" class="col-md-12 p-0">Email</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="email" placeholder="johnathan@admin.com" value="{{ $adminDetail->email }}" name="admin_email" class="form-control p-0 border-0" name="example-email" id="example-email">
                                    </div>
                                </div>
                                <div class="form-group mb-4 cus-title">
                                    <label class="col-md-12 p-0">Password(if you want to change enter new)</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <input type="text" name="admin_password" class="form-control p-0 border-0">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group mb-4 cus-title">
                                            <label class="col-md-12 p-0" style="display: flex;">Choose image </label>
                                            <div class="imageuploadify">
                                            <div class="imageuploadify-overlay" style="display: none;"><i class="fa fa-picture-o"></i></div>
                                                <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                                <span class="imageuploadify-message">Select or Drag&amp;Drop Your File(s) Here To Upload</span>

                                            <input type="file" class="form-control" name="image" onchange="adminloadFile(event)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-4 cus-title">
                                            <label class="col-md-12 p-0">Preview</label>
                                            <div class="col-md-12 thumbnailimgPreview mb-4" id="thumbnailimgPreview">
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-thumbnail-img" alt="blog-img" width="70px" height="70px" />
                                                
                                            </div>

                                        </div>

                                    </div>
                                    
                                </div>
                                <?php /* ?><div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Message</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label class="col-sm-12">Select Country</label>

                                    <div class="col-sm-12 border-bottom">
                                        <select class="form-select shadow-none p-0 border-0 form-control-line">
                                            <option>London</option>
                                            <option>India</option>
                                            <option>Usa</option>
                                            <option>Canada</option>
                                            <option>Thailand</option>
                                        </select>
                                    </div>
                                </div><?php */ ?>
                                <div class="form-group mb-4">
                                    <div class="col-sm-12">
                                        <button class="btn btn-success">Update Profile</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Column -->
            </div>

        @endif
        
    </div>
</div>



</div>

</div>

</div>
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->


<script type="text/javascript">
    var adminloadFile = function(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview-thumbnail-img');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    };

</script>
@endsection
