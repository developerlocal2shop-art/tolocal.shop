@extends('layouts.client.vendor')
@section('title','Vendor Prizes')
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
    .user_que_ans {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 15px;
        margin-right: 5px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card {
        border-radius: 0;
        padding: 0px 10px;
    }

</style>
<!-- /sub_header -->
<div class="sub_header_in sticky_header">
    <div class="container">
        <x-client.company_account_menu />
    </div>
    <!-- /container -->
</div>
<!-- Main page -->
<main>
    <div class="container margin_60_35">
        <div class="row">
            <div class="col-lg-12">
                
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

                    <div class="col-lg-11">
                        @if (count($data) > 0)
                             <h3>Vendor Prizes</h3>
                            <div class="user_que_ans">
                                @foreach ($data as $prize)
                                @php
                                  //$getCompany = Helper::getDirectoryIdSlug($prize->advertisement->directory_id);
                                @endphp
                                    <div class="card">
                                        <h4>🎁 {{ $prize->advertisement->heading??'' }}</h4>
                                        <p><b>Prize description:</b> {{ $prize->prize_description??'' }}</p>
                                        <p><b>Discount :</b> {{ $prize->advertisement->price??"" }} %</p>
                                        
                                        <p><b>Valid Until:</b> {{ optional($prize->advertisement)->end_date ? \Carbon\Carbon::parse($prize->advertisement->end_date)->format('d M Y') : '-' }}</p>

                                        @if($prize->voucher_code)
                                            <p>Prize Code:</b> <strong>{{ $prize->voucher_code }}</strong> 
                                            @if($prize->status == 0)
                                                <b style="color:brown">Used</b>
                                            @endif
                                            </p>
                                            <p>Don't miss out! Apply the code before it expires. Redeem on online shop cart </p>
                                        @endif
                                        <hr>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                           
                    </div>

                </div>
                
            </div>
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
@endsection
