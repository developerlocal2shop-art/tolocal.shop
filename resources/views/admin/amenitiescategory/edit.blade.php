@extends('layouts.adminmaster')
@section('title','Amenity Category | Add Category')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Amenity Category</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.amenitycategory.update', $amenityDetails->id) }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" value="{{ old('name',$amenityDetails->name) }}" placeholder="Enter Name" class="form-control border-0">
                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Description<span style="color:red;">*</span></label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="description" placeholder="Enter Description" id="description" maxlength="500" onkeyup="charCount(this.value,this.id,500)" class="form-control border-0" >{{ old('description',$amenityDetails->description) }}</textarea>
                                </div>
                                <span class="text-danger">@error('description'){{$message}} @enderror</span>
                            </div>
                        </div>                       
                        
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info">Update Category</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>

<script type="text/javascript">
    $(function() {
        $('.icon-picker').iconpicker();
    });
</script>
<script>
    $(document).ready(function() {
        var cattypevalue = $('select#categorytype option:selected').val();
        if (cattypevalue == 2) {
            $("#fields").show();
        } else {
            $("#fields").hide();
        }
        //console.log('cattypevalue',cattypevalue);
        $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
            console.log(categorytype_id);
            $("#category").html('');

            if (categorytype_id == 2) {
                $("#fields").show();
            } else {
                $("#fields").hide();
            }
            $.ajax({
                type: "POST",
                dataType: "json",
                url: baseUrl + '/admin/parent-category',
                data: {
                    'cat_type_id': categorytype_id,
                    _token: '{{csrf_token()}}'
                },
                success: function(result) {
                    $('#category').html('<option value="0">None</option>');
                    $.each(result, function(key, value) {
                        $("#category").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

        });
    });
</script>
@endsection