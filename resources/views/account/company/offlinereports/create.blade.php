@extends('layouts.adminmaster')
@section('title','Review | Add Review')
@section('content')

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
                                <form action="{{ route('admin.review.submitReview') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Page</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="page" value="" placeholder="Enter Page" class="form-control p-0 border-0" autocomplete=off> 
                                            
                                        </div>
                                        <span class="text-danger">@error('page'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" name="content" id="review-comment" maxlength="200" onkeyup="charCount(this.value,this.id,200)" placeholder="Enter Content" class="short-dis form-control p-0 border-0"></textarea>
                                            
                                        </div>
                                        <span class="text-danger">@error('content'){{$message}} @enderror</span>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-info">Save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    
                </div>
                
            </div>
           
        </div>
        
    </div>
    
 <script type="text/javascript">
$(document).ready(function () {
    // Warning
    $(window).on('beforeunload', function(){
        return "Any changes will be lost";
    });
    // Form Submit
    $(document).on("submit", "form", function(event){
        // disable warning
        $(window).off('beforeunload');
    });
});
</script>   
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
@endsection
