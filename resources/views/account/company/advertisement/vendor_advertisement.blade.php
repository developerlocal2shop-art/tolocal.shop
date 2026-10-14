@extends('layouts.client.vendor')
@section('title',"Company's Competition")
@section('content')

@php $user = auth()->user(); @endphp
<style>
    @media (max-width: 767px) {
        .pull-right{
            margin-top: -30px !important;
        }
        .d-none{
            display: unset !important;
        }

    }
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

<div class="sub_header_in sticky_header">
    <div class="container">
        <h1>Company's Competition</h1>
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<main>
    <div class="container margin_60_35">
           
                <div class="row">

                    <div class="col-md-12">
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
                    

                    <div class="col-lg-9">
                        <h4 class="box-title"> Competition Answer The Question </h4>
                    </div>
                    <div class="col-lg-3">
                        <a class="col-md-10 btn btn-primary" href="{{ route('vendor.prizes') }}" >
                            🎁 Prizes List
                        </a> 
                    </div>

                    <div class="col-lg-12">
                        <hr>
                        
                            @foreach ($data as $index => $adv)
                               
                                @if(count($adv->questions) > 0)
                                 <h5 class="user_que_ans">
                                    {{ $index+1 }} - {{ Str::words($adv->heading, 5, '...') }}<br>
                                    <span style="font-size: 15px; color:#c39922;">
                                        @if(empty($adv->directory_id) || $adv->directory_id == 0)
                                            Admin
                                        @else
                                        @php
                                           $getDirectoryIdSlug = Helper::getDirectoryIdSlug($adv->directory_id);
                                        @endphp
                                            Seller: {{ $getDirectoryIdSlug->title??'' }}
                                        @endif
                                        <br>
                                        <a style="color:rgb(218, 72, 32)">Competition Valid Until: {{ \Carbon\Carbon::parse($adv->start_date)->addDays(5)->format('Y-m-d') }}</a>
                                        <hr>
                                        <a class="btn btn-primary" href="{{ route('advertisement.company.questionnaire','advertisementid='.$adv->id) }}">
                                            View Answer 
                                        </a>
                                    </span>
                                    
                                 </h5>
                                 
                                @endif
                            @endforeach

                        <br>
                              
                    </div>
                    
                   
                    
                </div>
                
            
        
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
@endsection
