@extends('layouts.adminmaster')
@section('title','User | Add Permission')
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
                <h4 class="page-title">Add Permissions</h4>
            </div>

        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.permission.submit') }}" method="POST" class="">
                        {{ csrf_field() }}
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Full Name</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter Full Name" class="form-control p-0 border-0" autocomplete=off>

                            </div>
                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                        </div>
                        <div class="form-group mb-4">
                            <label for="example-email" class="col-md-12 p-0">Email</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off id="example-email">

                            </div>
                            <span class="text-danger">@error('email'){{$message}} @enderror</span>
                        </div>
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Password</label>
                            <div class="col-md-12 border-bottom p-0">
                                <input type="password" name="password" id="password" value="" class="form-control p-0 border-0 password">

                            </div>
                            <span class="text-danger">@error('password'){{$message}} @enderror</span>
                            <div class="col-md-6" id="password-strength-status"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Role</label>
                            <div class="col-md-12 border-bottom p-0">
                                <select class="form-control p-0 border-0" name="role" id="role" required>
                                    <option >Select role</option>
                                    @foreach($role as $val)

                                        <option value="{{$val->id}}">{{$val->name}}</option>

                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Modules</label>
                            <div class="col-md-12 border-bottom p-0">
                                <select class="form-control p-0 border-0" style="width:100%" required multiple name="modules[]" id="modules">

                                    @foreach($modules as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">Permission</label>
                            <div class="col-md-12 border-bottom p-0">
                                <select class="form-control p-0 border-0" style="width:100%" required multiple name="permissions[]" id="permissions">

                                    @foreach($permissions as $val)
                                    <option value="{{$val->id}}">{{$val->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Message</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                        </div>
                                    </div> -->

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
<!-- ============================================================== -->
<!-- End Wrapper -->
<!-- ============================================================== -->

<script>
    $(document).ready(function() {

        $("#permissions,#modules").select2({
            width: 'element'
            , minimumResultsForSearch: Infinity
        });


    });

</script>
@endsection
