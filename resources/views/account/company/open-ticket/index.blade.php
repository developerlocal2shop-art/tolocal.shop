@extends('layouts.client.vendor')
@section('title','Request | For New Ticket')
@section('content')
@php $user = auth()->user(); @endphp
<style>
    #helpicon {
        top:8px;
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #helpiconText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 6%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width:230px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }
    #helpicon:hover:after {
        top: 14px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }

    #helpiconText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #helpicon:hover #helpiconText {
        visibility: visible;
        opacity: 1;
        top: 69%;
        z-index: 99;
    }
</style>
<div class="sub_header_in sticky_header">
    <div class="container-fluid">
        <h1>Company Request New Ticket</h1>
        <x-client.company_account_menu />
    </div>
</div>
<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">

                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {!! session()->get('msg') !!}
                </div><br />
                @endif
                @if(session()->get('error'))
                <div class="alert alert-danger">
                    {!! session()->get('error') !!}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->

        <div class="white-box">
            <div class="table-responsive">
                <div class="col-lg-12 pr-xl-5">
                <div class="main_title_3 open-ticket-helpicon">
                    <span></span>
                    <h2 style="display: flex;">Request Form
                    @if(!empty($instruction->message))
                    <div id="helpicon">
                        <span id="helpiconText">{{$instruction->message}}</span>
                        <span style="background-color: unset; left:8px;"><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                    </div>
                    @endif
                    </h2>
                    <p style="font-size: 15px;">Please fill in the form below to open a new ticket.</p>
                </div>
                	<form id="send_contact_message" action="{{ route('company.submitNewticket') }}" method="POST" name="contactform" >
                    {{ csrf_field() }}
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">Title<span class="notranslate" style="color:red;">*</span></label>
                                <input class="form-control" type="text" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email<span class="notranslate" style="color:red;">*</span></label>
                                <input class="form-control" type="email" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Mobile</label>
                                <input class="form-control" type="text" id="phone" name="phone">
                            </div>
                        </div>
                    </div>
                    <!-- /row -->
                    <div class="form-group">
                        <label for="message">Query<span class="notranslate" style="color:red;">*</span></label>
                        <textarea class="form-control" id="query" name="query" style="height:120px;" required></textarea>
                    </div>

                    <div class=" row">
                        <div class="form-group" style="margin-left: 17px;">
                            <button type="submit" class="btn_1" id="submit_contact">Request Ticket</button>
                        </div>
                    </div>
                </form>
            </div>
            </div>
            <div class="d-flex justify-content-center cust-page">
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- /container -->
</main>

<script>
$(document).ready(function() {

    //  @if (Auth::check())
    //     @php
    //     $user = auth()->user();
    //     @endphp
    //     @if($user->stripe_connect == null)
    //     toastr.error('You will need to connect with <span class="notranslate" > stripe </span> first.')
    //     @endif
    // @endif

});
</script>
@endsection
