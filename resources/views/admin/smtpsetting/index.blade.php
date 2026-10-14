@extends('layouts.adminmaster')
@section('title','System Setting')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php 
if(isset($settingDetails)){
    $settingDetails = $settingDetails;
}
?> 

        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">SMTP Setting</h4>
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
                <!-- ============================================================== -->
                <!-- Three charts -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- Column -->
                    
                        <div class="card">
                            <div class="card-body">
                                <?php if(empty($settingDetails['id'])){?>
                                    <form action="{{ route('admin.smtpsetting.submitSmtpsetting') }}" method="POST" class="form-horizontal form-material">
                                <?php } else{ ?>
                                    <form action="{{ route('admin.smtpsetting.updateSmtpsetting') }}" method="POST" class="form-horizontal form-material">
                                <?php } ?>                                
                                {{ csrf_field() }}
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Smtp protocol</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="smtp_protocol" id="smtp_protocol" value="{{ old('map_title', $settingDetails['smtp_protocol'] ?? '') }}" placeholder="Enter SMTP protocol" class="form-control p-0 border-0" required>                                            
                                        </div>
                                        <span class="text-danger">@error('smtp_protocol'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Smtp host</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="smtp_host" id="smtp_host" value="{{ old('smtp_host', $settingDetails['smtp_host'] ?? '') }}" placeholder="Enter SMTP host" class="form-control p-0 border-0">                                            
                                        </div>
                                        <span class="text-danger">@error('smtp_host'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Smtp port</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="smtp_port" id="smtp_port" value="{{ old('smtp_port', $settingDetails['smtp_port'] ?? '') }}" placeholder="Enter SMTP port" class="form-control p-0 border-0">                                            
                                        </div>
                                        <span class="text-danger">@error('smtp_port'){{$message}} @enderror</span>
                                    </div>                                  

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Smtp user</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="smtp_user" id="smtp_user" value="{{ old('smtp_user', $settingDetails['smtp_user'] ?? '') }}" placeholder="Enter SMTP user " class="form-control p-0 border-0" >                                            
                                        </div>
                                        <span class="text-danger">@error('smtp_user'){{$message}} @enderror</span>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Smtp password</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="smtp_password" id="smtp_password" value="{{ old('smtp_password', $settingDetails['smtp_password'] ?? '') }}" placeholder="Enter SMTP password " class="form-control p-0 border-0" >                                            
                                        </div>
                                        <span class="text-danger">@error('smtp_password'){{$message}} @enderror</span>
                                    </div>                                         
                                    
                                    <div class="form-group mb-4">
                                        <div class="col-sm-12">
                                            <input type="hidden" name="id" id="id" value="{{$settingDetails['id']}}">
                                           @if ($user->hasPermission('update') == true)
                                            <button type="submit" class="btn btn-info">Save</button>
                                          @endif

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
