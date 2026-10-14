@extends('layouts.adminmaster')
@section('title','Mail | Mail')
@section('content')

        <div class="page-wrapper">
           <div class="page-breadcrumb bg-white">
                <div class="row align-items-center">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Mail</h4>
                    </div>

                </div>
            </div>
           <div class="container-fluid">
           @if(session()->get('adminEmailmsg'))
            <div class="alert alert-success">
                {{ session()->get('adminEmailmsg') }}
            </div><br />
            @endif
               <div class="row cus-title">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('send.mail.using.mailchimp.sendMail') }}" method="POST" class="" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12 p-0">Select Company City</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <select name="selectedcity" id="selected-city" class="form-control select2" data-allow-clear="true" data-placeholder="Select category">
                                                <option value="none">None</option>
                                                @foreach($city as $value)
                                                <?php
                                                if(!is_numeric($value->city_id)){ ?>
                                                    <option value="{{ $value->city_id }}">{{ $value->city_id }}</option>
                                                <?php } ?>

                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-md-12 p-0">Select Postcode</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <select name="selectedpostcode" id="selected-postcode" class="form-control" data-allow-clear="true" data-placeholder="Select Postcode">
                                                <option value="none">None</option>
                                                @foreach($postcode as $value)
                                                <?php
                                                if($value->zipcode){ ?>
                                                    <option value="{{ $value->zipcode }}">{{ $value->zipcode }}</option>
                                                <?php } ?>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>




                                    <div class="form-group mb-4" style="display:none;">
                                        <label class="col-md-12 p-0">To</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="to_mail" value="{{ old('to_mail') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off>

                                        </div>
                                        <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                    </div>
                                    <!-- <div class="form-group mb-4">
                                        <label for="example-email" class="col-md-12 p-0">CC</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="email" name="cc_mail" value="{{ old('cc_mail') }}" placeholder="Enter e-mail address" class="form-control p-0 border-0" autocomplete=off id="example-email">

                                        </div>
                                        <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                    </div> -->

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">Subject</label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="subject_mail" value="{{ old('subject_mail') }}" placeholder="Enter Subject" class="form-control p-0 border-0">
                                        </div>
                                    </div>

                                        <div class="form-group mb-4">
                                            <label class="col-md-12 p-0">Select Template</label>
                                            <div class="col-md-12 ">
                                                <select class="form-control" name="template_mail" id="mailtemplateid" onChange="getMailTemplateOptions(this)">
                                                    <option value="0">None</option>
                                                    @foreach($mailtemplatedata as $mailtemplatedatarow)
                                                        <option value="{{ $mailtemplatedatarow->id }}"> {{ $mailtemplatedatarow->template_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    <div class="form-group mb-4">
                                        <label id="label-content" class="col-md-12 p-0">Content</label>
                                        <div class="col-md-12 border-bottom p-0" id="mailcontentdiv">
                                            <textarea id="mail_content_data" rows="5" maxlength="900" name="content_mail" class="ckeditor form-control p-0 border-0">{{ old('content_mail') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group mb-4">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-info">Send</button>
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
       <script type="text/javascript">

    $(document).ready(function() {
        $('#selected-city').select2();
    });

           function getMailTemplateOptions(seltemp) {
            var selobj = seltemp.value;
            //$("#mailcontentdiv").remove();
            //CKEDITOR.replace( 'mail_content_data' );
            $.ajax({
                url: baseUrl + '/admin/get-maildata-by-templateId',
                type: "POST",
                data: {
                    template_id: selobj, _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    //alert(result.templatemaildata);
                    if(selobj==0){
                        CKEDITOR.instances['mail_content_data'].setData( " " );
                    }
                    $.each(result.templatemaildata, function(key, value) {
                        CKEDITOR.instances['mail_content_data'].setData( value.template_design );
                    });


                }
            });
        }

       </script>

@endsection
