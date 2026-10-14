@extends('layouts.adminmaster')
@section('title','Review | Add Review')
@section('content')

        <style>            
            #password-strength-status {
                padding: 5px 10px;
                color: #FFFFFF;
                border-radius: 4px;
                margin-top: 5px;
            }

            .medium-password {
                background-color: #b7d60a;
                border: #BBB418 1px solid;
            }

            .weak-password {
                background-color: #ce1d14;
                border: #AA4502 1px solid;
            }

            .strong-password {
                background-color: #12CC1A;
                border: #0FA015 1px solid;
            }
        </style>        
           <div class="page-wrapper">
           <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Review</h4>
                    </div>
                    
                </div>
            </div>
           <div class="container-fluid">
               <div class="row">
                        <div class="card">
                            
                            <div class="card-body">
                                <form action="{{ route('admin.review.updateReview', $reviewData->id) }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Page</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="page" value="{{ $reviewData->page }}" placeholder="Enter Page" class="form-control p-0 border-0" autocomplete=off> 
                                            
                                        </div>
                                        <span class="text-danger">@error('page'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="content" id="review-comment" maxlength="200" onkeyup="charCount(this.value,this.id,200)" placeholder="Enter Content" class="ckeditor short-dis form-control p-0 border-0">{{ $reviewData->content }}</textarea>
                                            
                                        </div>
                                        <span class="text-danger">@error('content'){{$message}} @enderror</span>

                                        @if(count($reviewData->photos) > 0)
                                        <label for="example-email" class="col-md-12 p-0">Photos</label>
                                            @foreach ($reviewData->photos as $photosval)
                                                <a data-lightbox="allGalleryImages">
                                                    <img data-id="{{ $photosval->id }}" src="{{ env('IMAGE_PATH') }}files/reviews_photo/{{ $photosval->photos }}" class="previewBlocks gtype_img" style="width: 90px; height: 102px; margin: 5px 5px;">
                                                </a>
                                            @endforeach
                                        @endif
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-info">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    
                    <!-- Column -->
                </div>
                
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <footer class="footer text-center"> 
                <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
            </footer>
            <!-- ============================================================== -->
            <!-- End footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    
    
@endsection
