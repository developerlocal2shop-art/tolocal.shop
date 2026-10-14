@extends('layouts.client.vendor')
@section('title','Booking')
@section('content')
<style>
    #tooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px !important;
        font-size: 17px;
        font-family: sans-serif;
    }
    #tooltipText {
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
    #tooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
    #infotooltip{
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }
    #infotooltipText {
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
    #infotooltip:hover:after {
        top: 16px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16px;
        margin-left: -7px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }
    #infotooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }
    #infotooltip:hover #infotooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }
</style>
<div class="sub_header_in sticky_header filter_sub_blue_header">
    <div class="container">
        <h1>Company Booking Edit</h1>
        <x-client.company_account_menu />
    </div>
</div>
<?php
if (isset($data['bookingDetails'])) {
    $bookingDetails = $data['bookingDetails'];
}
?>
<main>
    <div class="container margin_60_35">
        <div class="page-wrapper">
            <div class="container-fluid">
                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                <div class="card">
                    <div class="card-body">
                        <span style="display: flex;">
                        	<h4 class="page-title">Booking View</h4>
							<div id="tooltip">
                                <span id="tooltipText">Incoming booking and response from customer</span>
                                <span><i class="fas fa-info-circle" ></i></span>
                            </div>
                        </span>
                        <form action="{{ route('company.booking.update', $bookingDetails->id) }}" method="POST" class="">
                            {{ csrf_field() }}
                            <div class="row">
                                <input type="hidden" name="directory_id" id="directory" value="{{auth()->user()->directory_id}}">
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>Name</strong></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="hidden" name="booking_name" value="{{ $bookingDetails->name }}">
                                            {{ $bookingDetails->name }}
                                        </div>
                                    </div>
                                    <span class="text-danger">@error('booking_name'){{$message}} @enderror</span>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>Email</strong></label>
                                        <div class="col-md-12 border-bottom p-0">

                                            <input type="hidden" name="booking_email" value="{{ $bookingDetails->email }}">
                                            {{ $bookingDetails->email }}
                                        </div>
                                        <span class="text-danger">@error('booking_email'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>Phone No.</strong></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input class="form-control" type="hidden" name="booking_phone_code" value="{{ $bookingDetails->country_code }}">
                                            <input class="form-control" type="hidden" name="booking_phone" value="{{ $bookingDetails->phone }}" readonly>
                                            {{ $bookingDetails->phone }}
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>No. of Adult</strong></label>
                                        <div class="col-md-12 border-bottom p-0">

                                            <input type="hidden" name="adult" value="{{ $bookingDetails->adult }}">
                                            {{ $bookingDetails->adult }}
                                        </div>
                                        <span class="text-danger">@error('adult'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>No. of Child</strong></label>
                                        <div class="col-md-12 border-bottom p-0">

                                            <input type="hidden" name="child" value="{{ $bookingDetails->child }}">
                                            {{ $bookingDetails->child }}
                                        </div>
                                        <span class="text-danger">@error('child'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>Message[User's or Visitor's message for you!]</strong></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="hidden" name="message" value="{{ $bookingDetails->message }}">
                                            {{ $bookingDetails->message }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
	                                <div class="form-group mb-4">
	                                    <label class="col-md-12 p-0" style="display: flex;"><strong>Booking comments for user(before change the status fill the booking comment first for user)</strong>
											<div id="tooltip">
				                                <span id="tooltipText">Here you answer the customer's request, (before you change the status)</span>
				                                <span><i class="fas fa-info-circle" ></i></span>
				                            </div>
	                                    </label>
	                                    <div class="col-md-12 border-bottom p-0">
	                                        <textarea rows="5" name="bookingComment" class="form-control p-0 border-0">{{ old('bookingComment', $bookingDetails->booking_comment ?? '') }}</textarea>
	                                    </div>
	                                </div>
                            	</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0"><strong>Status</strong></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <select class="form-control" name="status" id="statusforuser">
                                                <option value="Pending" {{ "Pending" == $bookingDetails->status ? 'selected' : '' }}> Pending</option>
                                                <option value="Confirm" {{ "Confirm" == $bookingDetails->status ? 'selected' : '' }}> Confirm</option>
                                                <option value="Cancel" {{ "Cancel" == $bookingDetails->status ? 'selected' : '' }}> Cancel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            

                            <div class="form-group mb-4">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-info" style="display: flex;">Update
											<div id="tooltip">
				                                <span id="tooltipText">Your response is sent to the Customer - if you changed the status to Confirm or Cancel.</span>
				                                <span><i class="fas fa-info-circle" ></i></span>
				                            </div>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</main>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });

    $(document).ready(function() {
        $(window).on('beforeunload', function() {
            return "Any changes will be lost";
        });
        // Form Submit
        $(document).on("submit", "form", function(event) {
            $(window).off('beforeunload');
        });
    });
</script>

@endsection
