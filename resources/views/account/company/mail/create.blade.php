@extends('layouts.client.vendor')
@section('title','Create Mail')
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
                        <h4 class="page-title">Mail</h4>

                        <form action="{{ route('company.mail.submit') }}" id="mailtemplateform" method="POST" class="" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">To</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="email" name="to_mail" value="{{ old('to_mail') }}" placeholder="Enter e-mail address" required class="form-control p-0 border-0" autocomplete=off>

                                </div>
                                <span class="text-danger">@error('name'){{$message}} @enderror</span>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Subject</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="subject_mail" value="{{ old('subject_mail') }}" required placeholder="Enter Subject" class="form-control p-0 border-0">
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Select Template</label>
                                <div class="col-md-12 p-0">
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

</main>


<script type="text/javascript">
    function getMailTemplateOptions(seltemp) {
        var selobj = seltemp.value;
        $.ajax({
            url: baseUrl + '/company/get-maildata-by-templateId'
            , type: "POST"
            , data: {
                template_id: selobj
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                //alert(result.templatemaildata);
                if (selobj == 0) {
                    CKEDITOR.instances['mail_content_data'].setData(" ");
                }
                $.each(result.templatemaildata, function(key, value) {
                    CKEDITOR.instances['mail_content_data'].setData(value.template_design);
                });


            }
        });
    }

</script>

@endsection
