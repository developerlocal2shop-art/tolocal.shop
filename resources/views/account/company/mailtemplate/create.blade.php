@extends('layouts.client.vendor')
@section('title','Create Mail Template')
@section('content')

<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Mail</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        <div class="row cus-title">
                <div class="container-fluid">
                    @if(session()->get('vendormailmsg'))
                    <div class="alert alert-danger">
                        {{ session()->get('vendormailmsg') }}
                    </div><br />
                    @endif
                    <div class="card">
                        <div class="card-body">
                            <h4 class="page-title">Mail Template</h4>
                            <form action="{{ route('company.mailtemplate.submit') }}" onsubmit="return submitForm()" id="mailtemplateform" method="POST" class="" enctype="multipart/form-data">
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
    </div>
</main>
   
@endsection
