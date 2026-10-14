@extends('layouts.adminmaster')
@section('title','System Setting')
@section('content')
@php $user = auth()->guard('webadmin')->user(); @endphp
<?php 
if(isset($data['settingDetails'])){
    $settingDetails = $data['settingDetails'];
}
if(isset($data['captchasettingDetails'])){
    $captchasettingDetails = $data['captchasettingDetails'];
}
?>

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">System Setting</h4>
            </div>
            
        </div>
    </div>
    
    <div class="container-fluid">
        @if(session()->get('msg'))
        <div class="alert alert-success">
            {{ session()->get('msg') }}
        </div><br />
        @endif
        
        <div class="row">
            
            <div class="card">
                <div class="card-body">
                    <?php if(empty($settingDetails['id'])){?>
                    <form action="{{ route('admin.systemsetting.submitSystemsetting') }}" method="POST" class="form-horizontal form-material">
                        <?php } else{ ?>
                        <form action="{{ route('admin.systemsetting.updateSystemsetting') }}" method="POST" class="form-horizontal form-material">
                            <?php } ?>
                            {{ csrf_field() }}
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Website title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="website_title" value="{{ old('website_title', $settingDetails['website_title'] ?? '') }}" placeholder="Enter website title" class="form-control p-0 border-0" required>
                                </div>
                                <span class="text-danger">@error('website_title'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">System title</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="system_title" value="{{ old('system_title', $settingDetails['system_title'] ?? '') }}" placeholder="Enter system title" class="form-control p-0 border-0" required>
                                </div>
                                <span class="text-danger">@error('system_title'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">System Email</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="system_email" value="{{ old('system_email', $settingDetails['system_email'] ?? '') }}" placeholder="Enter system email" class="form-control p-0 border-0" required>
                                </div>
                                <span class="text-danger">@error('system_email'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Meta Keyword</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="meta_keyword" class="ckeditor form-control p-0 border-0">{{ old('meta_keyword', $settingDetails['meta_keyword'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Meta Description</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="meta_description" class="ckeditor form-control p-0 border-0">{{ old('meta_description', $settingDetails['meta_description'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Address</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <textarea rows="5" name="address" id="address" class="ckeditor form-control p-0 border-0">{{ old('address', $settingDetails['address'] ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Phone</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $settingDetails['phone'] ?? '') }}" placeholder="Enter phone number" class="form-control p-0 border-0" required="">
                                </div>
                                <span class="text-danger">@error('phone'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Country</label>
                                    @php
                                    $countries = Helper::getCountryAllData();
                                    @endphp
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control select2" name="country" id="country">
                                        <option value="">Select country</option>
                                        @foreach ($countries as $val)
                                            <option value="{{ $val->id }}" {{ $settingDetails['country'] ==  $val->id ? 'selected="selected"' : '' }}>{{ $val->name }}</option>
                                        @endforeach
                                    </select>
                                    <?php /* ?><input type="text" name="country" id="country" value="{{ old('country', $settingDetails['country'] ?? '') }}" placeholder="Enter country" class="form-control p-0 border-0"><?php */ ?>
                                    <!-- <select name="country" id="country" class="form-control select2" data-allow-clear="true" data-placeholder="Select country">
                                        <option value="1" {{ $settingDetails['country'] == '1' ? 'selected="selected"' : '' }}>Afghanistan</option>
                                        <option value="98" {{ $settingDetails['country'] == 98 ? 'selected' : '' }}>India</option>
                                        <option value="229" {{ $settingDetails['country'] == 229 ? 'selected' : '' }}>United Kingdom</option>
                                        <option value="230" {{ $settingDetails['country'] == 230 ? 'selected' : '' }}>United States</option>
                                    </select> -->
                                </div>
                                <span class="text-danger">@error('country'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Timezone</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="timezone" id="timezone" value="{{ old('timezone', $settingDetails['timezone'] ?? '') }}" placeholder="Enter timezone" class="form-control p-0 border-0">
                                    <!-- <select name="timezone" id="timezone" class="form-control select2" data-allow-clear="true" data-placeholder="Select timezone">
                                        <option value="Asia/Kolkata" {{ $settingDetails['timezone'] == "Asia/Kolkata" ? 'selected' : '' }}>Asia/Kolkata</option>
                                        <option value="Europe/London" {{ $settingDetails['timezone'] == "Europe/London" ? 'selected' : '' }}>Europe/London</option>
                                        <option value="Pacific/Marquesas" {{ $settingDetails['timezone'] == "Pacific/Marquesas" ? 'selected' : '' }}>Pacific/Marquesas</option>
                                        <option value="UTC" {{ $settingDetails['timezone'] == "UTC" ? 'selected' : '' }}>UTC</option>
                                    </select> -->
                                </div>
                                <span class="text-danger">@error('timezone'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">System Language</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="system_language" id="system_language" required>
                                        <option value=""> Select Language</option>
                                        <option value="1" {{ $settingDetails['system_language'] == 1 ? 'selected' : '' }}>English</option>
                                    </select>
                                </div>
                                <span class="text-danger">@error('system_language'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Footer Text</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="footer_text" id="footer_text" class="form-control" value="{{ old('footer_text', $settingDetails['footer_text'] ?? '') }}" required="">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Footer Link</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="footer_link" id="footer_link" class="form-control" value="{{ old('footer_link', $settingDetails['footer_link'] ?? '') }}" required="">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Site Key</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="site_key" id="site_key" class="form-control" value="{{ old('site_key', $captchasettingDetails['site_key'] ?? '') }}" required="">
                                </div>
                                <span class="text-danger">@error('site_key'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Security Key</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="secret_key" id="secret_key" class="form-control" value="{{ old('secret_key', $captchasettingDetails['secret_key'] ?? '') }}" required="">
                                </div>
                                <span class="text-danger">@error('secret_key'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <input type="hidden" name="id" id="id" value="{{$settingDetails['id']}}">
                                    <input type="hidden" name="captcha_id" id="captcha_id" value="{{$captchasettingDetails['id']}}">
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
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });

</script>


@endsection
