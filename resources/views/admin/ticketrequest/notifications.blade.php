@extends('layouts.adminmaster')
@section('title','Ticket Request| Listing')
@section('content')
<style>
    .toggle {
        border-radius: 20rem !important;
        height: 10px !important;
    }

    .toggle.btn {
        min-height: 26px !important;
    }

    .btn-danger {
        color: #fff !important;
    }
</style>

<?php

//echo "<pre>"; print_r($data);
//die;
?>
<div class="page-wrapper">
    <div class="page-breadcrumb bg-white">
        <div class="row align-items-center">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Notifications</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <div class="d-md-flex">
                    <ol class="breadcrumb ms-auto">
                        <li><a href="#" class="fw-normal"></a></li>
                    </ol>
                    <!-- <a href="" class="btn btn-info  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Edit Booking</a> -->
                </div>
            </div>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <div class="container-fluid">
        @if(session()->get('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div><br />
        @endif

        <style>
            .hotel-search { margin-right: 25px; width:200px;}

        </style>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box">
                
					<ul>
            @foreach ($notifications as $notification)
            @if($notification->read_at == null)
            <li>

                    {{-- <p>{{ $val->users_map_usergroup->name }} invited your Group </p> Accept --}}
                    <div class="msg-content">
                        <h4>{{ucwords(Helper::getUserById($notification->data['sender_id'])->name)}}</h4>
                        <p>
                            <strong>{{ucwords(Helper::getUserById($notification->data['sender_id'])->name)}}</strong>
                            {{$notification->data['message']}}
                        </p>

                    <p>
                            @if($notification->created_at->diffInSeconds(Carbon\Carbon::now()) < 60)
                            {{$notification->created_at->diffInSeconds(Carbon\Carbon::now())}} Seconds Ago
                            @endif
                        </p>
                    <p>
                            @if($notification->created_at->diffInMinutes(Carbon\Carbon::now()) < 60  && $notification->created_at->diffInMinutes(Carbon\Carbon::now()) > 1)
                            {{$notification->created_at->diffInMinutes(Carbon\Carbon::now())}} Minutes Ago
                            @endif
                        </p>
                        <p>
                            @if($notification->created_at->diffInHours(Carbon\Carbon::now()) >= 1 && $notification->created_at->diffInHours(Carbon\Carbon::now()) < 24)
                            {{$notification->created_at->diffInHours(Carbon\Carbon::now())}} Hours Ago
                            @endif
                        </p>

                       <p>

                            @if($notification->created_at->diffInHours(Carbon\Carbon::now()) > 24 )  {{$notification->created_at->diffInDays(Carbon\Carbon::now())}} Days Ago
                            @endif

                       </p>
                       <a href="{{ url('admin/accept',[$notification->id,$notification->data['sender_id'],$notification->data['ticket_id']]) }}"><h6>Accept</h6></a>
                    </div>


            </li>
            @endif
            @endforeach
			<hr>
        </ul>
				
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script type="text/javascript">
    $('.show_confirm').click(function(e) {
        if (!confirm('Are you sure you want to delete this?')) {
            e.preventDefault();
        }
    });
</script>


@endsection
