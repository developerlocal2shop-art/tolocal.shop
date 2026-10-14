@extends('layouts.adminmaster')
@section('title','Directory Own Tags|Edit')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

<?php

if (isset($data['tagseditdata'])) {
    $tagsEdit = $data['tagseditdata'];
}
if (isset($data['directoryData'])) {
    $directorydata = $data['directoryData'];
}
//  echo "<pre>"; print_r($data['blogAndTraslationData']);die; 
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Directory Own Tag Edit</h4>
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

                <form action="{{ route('admin.directoryowntags.update', $tagsEdit->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row">
                        
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Directory<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select name="director_id" id="directorid" class="form-control categoryselect select2" data-allow-clear="true" data-placeholder="Select category">
                                        @foreach($directorydata as $dirct)
                                        <option value="{{ $dirct->id }}" {{ $dirct->id == $tagsEdit['directory_id'] ? 'selected' : '' }}>{{ $dirct->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="text-danger">@error('director_id'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Directory Own Tags</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="tags_name" value="{{ $tagsEdit->tag_name }}" placeholder="Enter Tags" class="form-control p-0 border-0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <!-- <label class="col-md-12 p-0">Add to Directory Page</label> -->
                                <div class="col-md-12">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" id="add-tags-to-page" class="form-check-input" name="add_tags_to_page" value="yes" <?php if($tagsEdit->add_to_directory_page=="yes"){ echo 'checked'; } ?>>
                                        <label class="custom-control-label">Add to Directory Page</label>
                                    </div>
                                    <!-- <input type="checkbox" name="add_tags_to_page" value="yes" placeholder="Enter Tags" class="form-control p-0 border-0"> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="createdby" class="form-control" value="{{auth()->guard('webadmin')->user()->id;}}">

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
<script type="text/javascript">
    $(document).ready(function() {
        $('.categoryselect').select2();
    });

    // var check;
    // $("#add-tags-to-page").on("click", function(){
    //     check = $('input[name="add_tags_to_page"]').is(":checked");
    //     alert(check);
    //     if(check!='true') {
    //         alert("Checkbox is checked.");
    //     }
    // }); 
</script>
<script>
    /*$(document).ready(function() {
            $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
             console.log(categorytype_id);
                $("#category").html('');

                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: baseUrl+'/admin/parent-category',
                    data: {'cat_type_id': categorytype_id, _token: '{{csrf_token()}}'},
                    success: function(result){
                        $('#category').html('<option value="0">None</option>'); 
                        $.each(result,function(key,value){
                        $("#category").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });

            });
        });*/
</script>
@endsection