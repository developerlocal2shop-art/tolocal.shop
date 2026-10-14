@extends('layouts.adminmaster')
@section('title','User | Add Role')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Role</h4>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.role.submit') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Role Name</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off>

                            </div>
                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
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


</div>
<!-- ============================================================== -->
<!-- End Page wrapper  -->
<!-- ============================================================== -->
</div>

<script>
    $(document).ready(function() {

    });

</script>
@endsection
