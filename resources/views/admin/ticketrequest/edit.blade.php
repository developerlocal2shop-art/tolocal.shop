@extends('layouts.adminmaster')
@section('title','Ticket Request View')
@section('content')

<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Ticket Request View</h4>
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

                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Directory</label>
                                
                                @php
                                $directoryname = Helper::getDirectory($bookingDetails->directory_id);
                                @endphp
                                <div class="col-md-12 border-bottom p-0">
                                    {{ $directoryname[0]->title }}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Title</label>
                                <div class="col-md-12 border-bottom p-0">
                                {{ $bookingDetails->title }}
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Name</label>
                                <div class="col-md-12 border-bottom p-0">
                                {{ $bookingDetails->vendor->name??'' }} {{ $bookingDetails->vendor->last_name??'' }}
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Email</label>
                                <div class="col-md-12 border-bottom p-0">
                                {{ $bookingDetails->email }}
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Phone</label>
                                <div class="col-md-12 border-bottom p-0">
                                    {{ $bookingDetails->phone??"" }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="col-md-12 p-0">Status</label>
                                <div class="col-md-12 border-bottom p-0">
                                    {{ $bookingDetails->status }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                       
                        <div class="col-md-12">
                                <div class="form-group mb-4">
                                    <label class="col-md-12 p-0">Message</label>
                                    <div class="col-md-12 border-bottom p-0">
                                    <p>{!! $bookingDetails->query !!}</p>
                                       
                                    </div>
                                </div>
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
