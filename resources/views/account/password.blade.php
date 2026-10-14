@extends('layouts.client.vendor')
@section('title','Member Account')
@section('content')
<style>
.filter_sub_blue_header.newClass {
    margin: -15px -29px !important;
}
</style>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />
            <div class="col-lg-9">
                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header filter_sub_blue_header">
                        <?php $userData =  $data['userData']; ?>
                        <div class="container">
                            <h1>Password Reset</h1>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-md-12">
                        @if(Session::has('error'))
                        <div class="alert alert-danger">
                            {{ Session::get('error') }}
                            @php
                            Session::forget('error');
                            @endphp
                        </div>
                        @endif
                        @if(Session::has('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                            @php
                            Session::forget('success');
                            @endphp
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-3 bg-light justify-content-center align-items-center vh-100">

                        @include('account.profile_pic')
                        
                    </div>

                    <div class="col-lg-9">

                        <!--Main Area-->
                        <form action="{{ route('account.submitPassword') }}" method="POST" class="profile_data" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Current Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="password" value="" placeholder="Current password" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('password'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                
                                    <div class="form-group mb-4 col-md-6">
                                        <label class="col-md-12 p-0">New Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="new_password" value="" placeholder="New password" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('new_password'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4 col-md-6">
                                        <label class="col-md-12 p-0">Confirm Password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="confirm_password" value="" placeholder="Confirm password" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('confirm_password'){{$message}} @enderror</span>
                                    </div>
                                   
                                 <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- /row -->

                    </div>

                    
                </div>
                
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
@endsection
