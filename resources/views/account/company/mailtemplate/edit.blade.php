@extends('layouts.adminmaster')
@section('title','User| Edit')
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
                        <h4 class="page-title">User</h4>
                    </div>
                    <!-- <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <div class="d-md-flex">
                            <ol class="breadcrumb ms-auto">
                                <li><a href="#" class="fw-normal"></a></li>
                            </ol>
                            <a href="{{ route('admin.user.create') }}" target="_blank"
                                class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add User</a>
                        </div>
                    </div> -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Three charts -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->

                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.user.updateUser', $singleUserData->id) }}" method="POST" class="">
                                {{ csrf_field() }}
                                <div class="row cus-title">
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">First Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="name" value="{{ $singleUserData->name }}" placeholder="Enter First Name" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">Last Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="last_name" value="{{ $singleUserData->last_name }}" placeholder="Enter Last Name" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('last_name'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-6 form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">Email</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="email" name="email" value="{{ $singleUserData->email }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" name="example-email" id="example-email">
                                            <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 form-group mb-4">
                                        <label class="col-md-12 p-0">Phone No</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="phone" placeholder="123 456 7890" value="{{ $singleUserData->phone }}" placeholder="Enter Phone number" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('phone'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row cus-title">
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">Country</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="country" value="{{ $singleUserData->country }}" placeholder="Enter Country" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('country'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">State</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="state" value="{{ $singleUserData->state }}" placeholder="Enter State" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('state'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group mb-4">
                                        <label class="col-md-12 p-0">City</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="city" value="{{ $singleUserData->city }}" placeholder="Enter City" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('city'){{$message}} @enderror</span>
                                        </div>
                                    </div>
                                </div>

                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="password" name="password" value="{{ $singleUserData->password }}" class="form-control p-0 border-0">
                                            <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                        </div>
                                    </div> -->
                                    <!--  -->
                                    <!-- <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Message</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" class="form-control p-0 border-0"></textarea>
                                        </div>
                                    </div> -->

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
