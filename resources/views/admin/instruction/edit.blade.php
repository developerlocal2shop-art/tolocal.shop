@extends('layouts.adminmaster')
@section('title','Instruction')
@section('content')

<?php

if (isset($data['instructionDetails'])) {
    $instructionDetails = $data['instructionDetails'];
}

//   echo "<pre>"; print_r($currencyDetails);die; 
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Instruction Edit</h4>
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

                <form action="{{ route('admin.instruction.update', $instructionDetails->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row cus-title">
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Section Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="setion_name">
                                        <option value="none"> Select Section</option>
                                        <option value="companyinfo" {{ "companyinfo" == $instructionDetails->section_name ? 'selected' : '' }}> Company Informatiom</option>
                                        <option value="product" {{ "product" == $instructionDetails->section_name ? 'selected' : '' }}> Product</option>
                                        <option value="offer" {{ "offer" == $instructionDetails->section_name ? 'selected' : '' }}> Offer</option>
                                        <option value="shoppingcard" {{ "shoppingcard" == $instructionDetails->section_name ? 'selected' : '' }}> Shopping Card</option>
                                        <option value="event" {{ "event" == $instructionDetails->section_name ? 'selected' : '' }}> Event</option>
                                        <option value="openticket" {{ "openticket" == $instructionDetails->section_name ? 'selected' : '' }}> Open Ticket</option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('setion_name'){{$message}} @enderror</span>
                            </div>
                            
                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="message" class="form-control p-0 border-0">{{ old('message', $instructionDetails->message) }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row cus-title">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Active" {{ $instructionDetails->status=='Active' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Active </label>
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input type="radio" class="pay" name="status" value="Inactive" style="margin-left: 20px;" {{ $instructionDetails->status=='Inactive' ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="1">Inactive </label>
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