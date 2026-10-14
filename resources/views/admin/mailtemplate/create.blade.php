@extends('layouts.adminmaster')
@section('title','Mail | Mail')
@section('content')

        <div class="page-wrapper">
           <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Mail Template</h4>
                    </div>
                    
                </div>
            </div>
           <div class="container-fluid">
               <div class="row cus-title">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.mailtemplate.submit') }}" method="POST" class="" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Template Name</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="mail_template_name" value="{{ old('mail_template_name') }}" placeholder="Enter Name" class="form-control p-0 border-0" autocomplete=off> 
                                            
                                        </div>
                                        <span class="text-danger">@error('mail_template_name'){{$message}} @enderror</span>
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Template Design</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <textarea rows="5" id="template-design" maxlength="900" name="template_design" class="ckeditor form-control p-0 border-0">{{ old('template_design') }}</textarea>
                                        </div>
                                        <span class="text-danger">@error('template_design'){{$message}} @enderror</span>
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
            <footer class="footer text-center"> 
                <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
            </footer>
            </div>
        </div>
       
    
@endsection
