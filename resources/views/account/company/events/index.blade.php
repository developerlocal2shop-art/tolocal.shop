@extends('layouts.client.vendor')
@section('title','Events | Listing')
@section('content')
@php $user = auth()->user(); @endphp
<div class="sub_header_in sticky_header">
    <div class="container">
       
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>

<main>
    <div class="container margin_60_35">
        <div class="">
            <div class="col-lg-12">

                @if(session()->get('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div><br />
                @endif
                @if(Session::has('error'))
                <div class="alert alert-danger">
                    {{ Session::get('error') }}
                    @php
                    Session::forget('error');
                    @endphp
                </div>
                @endif

                @if(Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                    @php
                    Session::forget('success');
                    @endphp
                </div>
                @endif
            </div>
        </div>
        <!--Main Area-->

        <div class="white-box">
            <div class="row">
                <div class="col-md-8 events-helpicon">
                    <h3 class="box-title" style="display: flex;">Events List 
                    @if(!empty($instruction->message))
                    <div id="helpicon">
                        <span id="helpiconText">{{$instruction->message}}</span>
                        <span><i class="fa fa-question-circle" aria-hidden="true" style="font-size: 20px;"></i></span>
                    </div>
                    @endif
                    </h3>
                </div>
                <div class="col-md-4">
                    <?php
                            $getFeatureUsesValue = Helper::getFeatureUsesValue('events');
                            if($getFeatureUsesValue <= count($eventsdata)){
                        ?>
                    <a onclick="alert('Please upgrade member account. you can not add events greater than '+ {{ $getFeatureUsesValue }});" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Events</a>
                    <?php }else{ ?>
                    @if ($user->hasPermission('add') == true)
                    <a href="{{ route('company.addevents') }}" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Add Events</a>
                    @endif
                    <?php } ?>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">Image</th>
                            <th class="border-top-0">Title</th>
                            <th class="border-top-0">Visitors</th>
                            <th class="border-top-0">Likes</th>
                            <th class="border-top-0">Date</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; $datacount = count($eventsdata); if ($datacount>0){?>
                        @foreach($eventsdata as $eventsdatarow)
                        <tr>
                            <td><img src="{{ env('IMAGE_PATH').'files/events/'.$eventsdatarow->event_image }}" height="50px" width="50px"></td>
                            <td>{{ $eventsdatarow->title }}</td>
                            <td>
                                @if(count(Helper::CountVisitorsId('event',$eventsdatarow->id)) != 0)
                                    {{ count(Helper::CountVisitorsId('event',$eventsdatarow->id)) }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            <td>
                                @if(count(Helper::CountLikeData('event',$eventsdatarow->id)) != 0)
                                    {{ count(Helper::CountLikeData('event',$eventsdatarow->id)) }}
                                @else
                                {{ 0 }}
                                @endif
                            </td>
                            
                            <td><b>
                                <?php echo date('d M Y', strtotime($eventsdatarow->start_date));?> : <?php echo $eventsdatarow->start_time;?>
								- <?php echo date('d M Y', strtotime($eventsdatarow->end_date));?> : <?php echo $eventsdatarow->end_time;?>
                            </b></td>
                            <td>{{ $eventsdatarow->status }}</td>
                            <td>
                                @if ($user->hasPermission('edit') == true)
                                <a class="btn btn-primary btn-sm" href="<?php echo url("/company/events-edit", ['id' => @$eventsdatarow->id]); ?>">EDIT</a>
                                @endif
                                @if ($user->hasPermission('delete') == true)
                                <a class="btn btn-danger btn-sm show_confirm" href="<?php echo url("/company/events-delete", ['id' => @$eventsdatarow->id]); ?>">DELETE</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        <?php }else{ ?>
                        <tr>
                            <td align="center" colspan="4">No Data Found</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
            <div class="d-flex justify-content-center cust-page">

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

    </div>
    </div>
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

    @media (max-width: 767px) {

        .d-none {
            display: unset !important;
        }

        .pull-right {
            margin-top: -40px;
        }

    }
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
        transform: translate(-12%, 5%);
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
        top: 62%;
        z-index: 99;
        width:220px;
    }
</style>
</main>

@endsection
