@extends('layouts.client.vendor')
@section('title','User Competitions')
@section('content')
<style>
    .prizes_btn {
            margin: 5px 30px !important;
        }
    .sub_header_in.sticky_header {
        padding: 30px 4px 10px 0px;
    }
    .filter_sub_blue_header.newClass {
        margin: -15px -29px !important;
    }
    .user_que_ans {
        background: #f9f9f9;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px 15px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        float: left;
        margin: 0px 15px;
        width: 430px;
    }
    .user_competition_list {
        padding: 10px 0;
    }

    /* Header */
    .user_competition_list .filter_sub_blue_header {
        background: linear-gradient(90deg, #007bff, #0056b3);
        padding: 18px 20px;
        border-radius: 10px;
        margin: 77px 0px 15px 0px !important;
    }

    .user_competition_list .filter_sub_blue_header h1 {
        color: #fff;
        font-size: 22px;
        margin: 0;
        font-weight: 600;
    }

    /* Competition Card */
    .user_competition_list .competition_card {
        background: #fff;
        border-radius: 12px;
        padding: 18px 20px;
        margin-bottom: 18px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
        border-left: 4px solid #007bff;
    }

    .user_competition_list .competition_card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    /* Title */
    .user_competition_list .competition_title {
        font-size: 17px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    /* Meta info */
    .user_competition_list .competition_meta {
        font-size: 14px;
        color: #777;
        margin-bottom: 10px;
    }

    /* Seller */
    .user_competition_list .competition_meta span {
        color: #c39922;
        font-weight: 500;
    }

    /* Date */
    .user_competition_list .competition_date {
        font-size: 13px;
        color: #e5533d;
        margin-bottom: 12px;
        display: block;
    }

    /* Button */
    .user_competition_list .btn-participate {
        background: linear-gradient(90deg, #28a745, #218838);
        border: none;
        padding: 8px 18px;
        font-size: 14px;
        border-radius: 6px;
        color: #fff;
        transition: 0.3s;
    }

    .user_competition_list .btn-participate:hover {
        background: linear-gradient(90deg, #218838, #1e7e34);
    }

    /* Prize Link */
    .user_competition_list .prize_box {
        margin-top: 20px;
        padding: 14px;
        background: #fff8e6;
        border-radius: 10px;
        text-align: center;
        font-weight: 500;
    }

    .user_competition_list .prize_box a {
        color: #d68910;
        text-decoration: none;
        font-size: 15px;
    }

    @media screen and (max-width:767px){
        .btn-primary {
            padding: 0px 25px !important;
        }
    }
    @media screen and (max-width: 1024px) {
        .btn-primary {
            padding: 0px 25px !important;
        }
    }
</style>
<!-- /sub_header -->
<!-- Main page -->
<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9 user_competition_list">
                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header filter_sub_blue_header">
                        <?php //$userData =  $data['userData']; ?>
                        <div class="container">
                            <h1>Competition Answer The Question</h1>
                        </div>
                    </div>
                </div>
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
                        @if(count($data) > 0)

                            @foreach ($data as $index => $adv)
                                @if(count($adv->questions) > 0)
                                 <h4 class="user_que_ans">
                                    {{ $index+1 }} - {{ Str::words($adv->heading, 50, '...') }}<br>
                                    <span style="font-size: 15px; color:#c39922;">
                                        {{-- Seller: {{ $adv->competitionVendors->vendor_name??'' }} --}}
                                        <br><hr>
                                        <a style="color:rgb(218, 72, 32)">Competition Valid Until: {{ \Carbon\Carbon::parse($adv->end_date)->format('d F Y') }}</a>

                                        <a class="btn btn-primary" href="{{ route('advertisement.user.questionnaire','advertisementid='.$adv->id) }}">
                                            Participate
                                        </a>
                                    </span>
                                 </h4>
                                 @else
                                <h5 class="user_que_ans">No Competitions Found</h5>
                                @endif
                            @endforeach

                        @endif

                        <br>

                    </div>


                    @if($countPrizes > 0)
                        <div class="list-group-item prizes_btn">
                            @if(auth()->user()->directory_id)
                                <a href="{{ url('company/vendorprizes') }}">🎁 Prizes List</a>
                            @else
                                <a href="{{ route('user.prizes') }}">🎁 Prizes List</a>
                            @endif
                        </div>
                    @endif


                </div>

            </div>

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</main>
@endsection
