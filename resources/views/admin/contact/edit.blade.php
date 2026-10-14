@extends('layouts.adminmaster')
@section('title','Booking')
@section('content')

<?php

if (isset($data['bookingDetails'])) {
    $bookingDetails = $data['bookingDetails'];
}

//   echo "<pre>"; print_r($currencyDetails);die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Booking Edit</h4>
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

        <div class="card">
            <div class="card-body">

                <form action="{{ route('admin.booking.update', $bookingDetails->id) }}" method="POST" class="">

                    {{ csrf_field() }}
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Directory</label>
                                {{-- <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="directory">

                                    <option value="1" {{ "1" == $bookingDetails->directory_id ? 'selected' : '' }} > Listing</option>
                                    <option value="2" {{ "2" == $bookingDetails->directory_id ? 'selected' : '' }} > Directory two</option>
                                    <option value="3" {{ "3" == $bookingDetails->directory_id ? 'selected' : '' }} > Directory three</option>

                                    </select>
                                </div> --}}
                                @php
                                $directoryname = Helper::getDirectory($bookingDetails->directory_id);
                                @endphp
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="directory"  value="{{ old('directory',$directoryname[0]->title) }}" placeholder="Enter Booking Name" class="form-control p-0 border-0" readonly/>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="booking_name" id="currency-name" value="{{ old('currency_name',$bookingDetails->name) }}" placeholder="Enter Booking Name" class="form-control p-0 border-0">
                                </div>
                            </div>
                            <span class="text-danger">@error('booking_name'){{$message}} @enderror</span>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Email</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="email" name="booking_email" id="booking-email" value="{{ old('booking_email',$bookingDetails->email) }}" placeholder="Enter Currency Symbol" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('booking_email'){{$message}} @enderror</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Adult</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="adult" id="adult" value="{{ old('adult',$bookingDetails->adult) }}" placeholder="Enter Currency Symbol" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('adult'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Child</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <input type="text" name="child" id="child" value="{{ old('child',$bookingDetails->child) }}" placeholder="Enter Currency Symbol" class="form-control p-0 border-0">
                                </div>
                                <span class="text-danger">@error('child'){{$message}} @enderror</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Message</label>
                                    <div class="col-md-12 border-bottom p-0">
                                        <textarea rows="5" name="message" class="form-control p-0 border-0">{{ old('message', $bookingDetails->message ?? '') }}</textarea>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="col-md-12 border-bottom p-0">
                                    <select class="form-control" name="status">

                                    <option value="Pending" {{ "Pending" == $bookingDetails->status ? 'selected' : '' }} > Pending</option>
                                    <option value="Confirm" {{ "Confirm" == $bookingDetails->status ? 'selected' : '' }} > Confirm</option>
                                    <option value="Cancel" {{ "Cancel" == $bookingDetails->status ? 'selected' : '' }} > Cancel</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

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
    <footer class="footer text-center">
        <!-- 2021 © Ample Admin brought to you by <a
                    href="https://www.wrappixel.com/">wrappixel.com</a> -->
    </footer>
</div>
</div>
<script src="//cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.ckeditor').ckeditor();
    });
</script>


@endsection
