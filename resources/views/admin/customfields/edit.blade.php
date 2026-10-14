@extends('layouts.adminmaster')
@section('title','Category | Add Category')
@section('content')

<?php $catcustfieldDetails = $data['catcustfieldDetails']; ?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Edit Category Configuration</h4>
            </div>
        </div>
    </div>
    <div class="container-fluid">

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.customfields.updatecustfield', $catcustfieldDetails->id) }}" method="POST" class="">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Label</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="label" class="form-control" value="{{ $catcustfieldDetails->label }}" placeholder="Enter Label" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="name" class="form-control" value="{{ $catcustfieldDetails->name }}" placeholder="Enter Name" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Type</label>
                                <div class="col-md-12 border-bottom p-0">
                                   
                                    <?php $v1="text";$v2="number";$v3="date";$v4="time";$v5="textarea"; ?>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="text" <?php echo  $v1 == $catcustfieldDetails->type ? 'selected' : '' ?>> Text </option>
                                        <option value="number" <?php echo  $v2 == $catcustfieldDetails->type ? 'selected' : '' ?>> Number</option>
                                        <option value="date" <?php echo  $v3 == $catcustfieldDetails->type ? 'selected' : '' ?>> Date </option>
                                        <option value="time" <?php echo $v4 == $catcustfieldDetails->type ? 'selected' : '' ?>> Time </option>
                                        <option value="textarea" <?php echo  $v5 == $catcustfieldDetails->type ? 'selected' : '' ?>> Textarea </option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('type'){{$message}} @enderror</span>
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
        $('#categorytype').on('change', function() {
            var categorytype_id = this.value;
            console.log(categorytype_id);
            $("#category").html('');

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