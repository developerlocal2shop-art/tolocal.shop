@extends('layouts.adminmaster')
@section('title','Permission| Edit')
@section('content')
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Permission managment</h4>
                    </div>

                </div>
                <!-- /.col-lg-12 -->
            </div>

            <div class="container-fluid">

                <div class="row">
                    <!-- Column -->
                     @if(session()->get('msg'))
                    <div class="alert alert-success">
                        {{ session()->get('msg') }}
                    </div><br />
                    @endif
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.permission.update', $data->id) }}" method="POST" class="">
                                {{ csrf_field() }}
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Full Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="name" value="{{ $data->name }}" placeholder="Enter Full Name" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Email</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="email" name="email" value="{{ $data->email }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" name="example-email" id="example-email">
                                            <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                     <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="password" name="password"  class="form-control p-0 border-0">
                                            <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Role</label>
                                        <div class="col-md-12 border-bottom p-0">
                                        @php
                                            $selected = [];
                                            foreach ($data->roles as $rval){
                                                $selected[] = $rval->id;
                                            }

                                            $selected2 = [];
                                            foreach ($data->modules as $mval){
                                                $selected2[] = $mval->id;
                                            }

                                            $selected3 = [];
                                            foreach ($data->permissions as $pval){
                                                $selected3[] = $pval->id;
                                            }
                                        @endphp
                                            <select class="form-control p-0 border-0" name="role" id="role" required>
                                                @foreach($role as $val)
                                                <option value="{{$val->id}}" {{ (in_array($val->id, $selected)) ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Modules</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <select class="form-control p-0 border-0" style="width:100%" multiple name="modules[]" id="modules" required>
                                                @foreach($modules as $val)
                                                <option value="{{$val->id}}" {{ (in_array($val->id, $selected2)) ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Permission</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <select class="form-control p-0 border-0" style="width:100%" multiple name="permissions[]" id="permissions" required>
                                                @foreach($permissions as $val)
                                                <option value="{{$val->id}}" {{ (in_array($val->id, $selected3)) ? 'selected' : '' }}>{{$val->name}}</option>
                                                @endforeach
                                            </select>
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

            </div>

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


    <script>
            $(document).ready(function () {

                $("#permissions,#modules").select2();
                //$("#permissions,#modules").select2({
                   // width: 'element'
                   // , minimumResultsForSearch: Infinity
                //});


                $("#password").on('keyup', function(){
                    var number = /([0-9])/;
                    var alphabets = /([a-zA-Z])/;
                    var special_characters = /([~,!,@,#,$,%,^,&,*,-,_,+,=,?,>,<])/;
                    if ($('#password').val().length < 6) {
                        $('#password-strength-status').removeClass();
                        $('#password-strength-status').addClass('weak-password');
                        $('#password-strength-status').html("Weak Password");
                    } else {
                        if ($('#password').val().length > 6) {
                            $('#password-strength-status').removeClass();
                            $('#password-strength-status').addClass('strong-password');
                            $('#password-strength-status').html("Strong Password");
                        } else {
                            $('#password-strength-status').removeClass();
                            $('#password-strength-status').addClass('medium-password');
                            $('#password-strength-status').html("Medium Password");
                        }
                    }
                });
            });
    </script>
    @endsection
