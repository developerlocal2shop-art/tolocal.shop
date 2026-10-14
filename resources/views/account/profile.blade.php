@extends('layouts.client.vendor')
@section('title','Member Profile')
@section('content')
<style>
    input#profile_pic {
        font-size: 100px;
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        height: 100%;
    }
    .filter_sub_blue_header.newClass {
        margin: -15px -29px !important;
    }
    #tooltip {
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
        width: 230px;
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

    #infotooltip {
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
        width: 230px;
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

    .affiliate-request-card {
        background: linear-gradient(135deg, #f8fbff 0%, #eef5ff 100%);
        border: 1px solid #dfeaff;
        border-radius: 18px;
        padding: 15px 15px;
        box-shadow: 0 12px 30px rgba(32, 56, 100, 0.08);
        margin-top: 15px;
    }

    .affiliate-request-card .badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        background: rgba(32, 56, 100, 0.08);
        color: #203864;
        padding: 7px 12px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 12px;
    }

    .affiliate-request-card h4 {
        margin: 0 0 10px;
        color: #1d2d50;
        font-size: 26px;
        font-weight: 700;
    }

    .affiliate-request-card p {
        color: #53627b;
        margin-bottom: 18px;
        line-height: 1.7;
    }

    .affiliate-request-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #203864 0%, #2f5aa8 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 14px 26px;
        font-weight: 700;
        text-decoration: none;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        box-shadow: 0 12px 24px rgba(32, 56, 100, 0.2);
    }

    .affiliate-request-btn:hover {
        text-decoration: none;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 16px 30px rgba(32, 56, 100, 0.25);
    }
</style>

<main>
    <div class="container-fluid">
        <div class="row">
            <x-client.account_menu />

            <div class="col-lg-9">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-lg-12">
                    <div class="sub_header_in sticky_header filter_sub_blue_header">
                        <?php $userData =  $data['userData']; ?>
                        <div class="container">
                            <h1>Member</h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-3 bg-light justify-content-center align-items-center vh-100">


                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <!-- Profile Card -->
                                <div class="profile-card">
                                    <div class="profile-img-container">
                                        @if(Auth::user()->profile_pic)
                                        <img src="{{ env('IMAGE_PATH').'images/'.Auth::user()->profile_pic }}" alt="Profile Image" class="profile-img">
                                        @else
                                        <img src="{{ url('/assets/images/faces/face21.jpg') }}" alt="Profile Image" class="profile-img">
                                        @endif
                                        <span class="camera-icon">
                                            &#128247;
                                        </span>
                                        <form id="profileUploadForm" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="profile_pic" id="profile_pic">
                                        </form>
                                    </div>
                                    <h4 class="mt-2">{{ $userData->name??'' }} {{ $userData->last_name??'' }}</h4>
                                    <span class="admin-badge">
                                        @if(Auth::user()->role_id == 1)
                                        Seller
                                        @else
                                        User
                                        @endif

                                    </span>
                                    <p class="text-muted small">
                                        last visit
                                        {{ Auth::user()->last_login_at
                                            ? Auth::user()->last_login_at->timezone(Auth::user()->timezone ?? 'Asia/Kolkata')
                                                ->format('d-m-Y h:i A') : '' }}
                                    </p>
                                    <a href="{{ url('logout') }}" class="btn btn-primary logout-btn">Log Out</a>
                                </div>

                                <!-- Notifications & Messages -->
                                <div class="info-box d-flex justify-content-between align-items-center mt-3">
                                    @if(count($advertisement) > 0)
                                    <a href="{{ route('user.questionnaire') }}" class="d-flex align-items-center">

                                        <span class="notification-icon">&#128276;🗨️</span>
                                        <span class="ms-2">Answer The Question (<span class="text-success">
                                        {{ count($advertisement) }}
                                        </span>)</span>
                                    </a>
                                    @endif
                                    @if($myPrizesCount > 0)
                                    <a href="{{ route('user.prizes') }}" class="d-flex align-items-center">
                                        <span class="notification-icon">&#128276;🎁</span>
                                        <span class="ms-2">Prizes (<span class="text-danger">
                                            {{ $myPrizesCount }}
                                        </span>)</span>
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="col-lg-9">

                        <div class="row">
                            <div class="col-md-12">

                                @if($subscribe_status)
                                <div class="alert alert-success">{{ $subscribe_status }}</div>
                                @endif

                                @if(session()->get('profilemsg'))
                                <div class="alert alert-success">
                                    {{ session()->get('profilemsg') }}
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
                            </div>
                        </div>
                        <!--Main Area-->
                        <form action="{{ route('account.submitProfile') }}" method="POST" class="profile_data"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{__("message.first_name_text")}}<span
                                                class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="name" value="<?php echo $userData->name; ?>"
                                                placeholder="Name" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('name'){{$message}} @enderror</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{__("message.last_name_text")}}<span
                                                class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="text" name="last_name"
                                                value="<?php echo $userData->last_name; ?>" placeholder="Name"
                                                class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('last_name'){{$message}} @enderror</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="form-group mb-4">
                                        <label class="col-md-12 p-0">{{__("message.email_text")}}<span
                                                class="notranslate" style="color:red;">*</span></label>
                                        <div class="col-md-12 border-bottom p-0">
                                            <input type="hidden" name="preemail" value="{{ Auth::user()->email??'' }}">
                                            <input type="text" name="email" value="{{ Auth::user()->email??'' }}"
                                                placeholder="Email" class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('email'){{$message}} @enderror</span>
                                    </div>
                                    <div class="form-group mb-4 row">

                                        <div class="col-md-6 border-bottom">
                                            <label style="display: flex;"
                                                class="col-md-12 p-0">{{__("message.phone_no_text")}}<span
                                                    class="notranslate" style="color:red;">*</span>
                                                <div id="tooltip">
                                                    <span id="tooltipText"><b><u>This must be a mobile number that can
                                                                receive messages from the booking.</u></b> It can be same as
                                                        the Company number, but can also be diffrent and you may change it
                                                        from the same. In other words, it's you provided login number.
                                                    </span>
                                                    <span><i class="fas fa-info-circle"></i></span>
                                                </div>
                                            </label>
                                            <input type="text" name="phone" value="{{ Auth::user()->phone??'' }}"
                                                placeholder="{{__('message.placeholder_phone_no_text')}}"
                                                class="form-control border-0">
                                        </div>
                                        <span class="text-danger">@error('phone'){{$message}} @enderror</span>

                                        @if(Auth::user()->directory_id > 1)
                                        <div class="col-md-6 border-bottom">
                                            <label style="display: flex;"
                                                class="col-md-12 p-0">{{__("Booking form status")}}<span
                                                    class="notranslate" style="color:red;">*</span>
                                                <div id="tooltip">
                                                    <span id="tooltipText"><b><u>If status is active then booking form is enabled.</u></b> Otherwise, it will be disabled in frontend company detail.
                                                    </span>
                                                    <span><i class="fas fa-info-circle"></i></span>
                                                </div>
                                            </label>
                                            <select name="is_verified" class="form-control border-0">
                                                <option value="valid" @if(Auth::user()->is_verified == 'valid') selected @endif>Active</option>
                                                <option value="" @if(Auth::user()->is_verified == '') selected @endif>Inactive</option>
                                            </select>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="form-group mb-4">
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>

                @if ($affiliate)
                    <div class="affiliate-request-card">
                        <div class="badge">Affiliate Program</div>
                        <h4>Manage your affiliate activity</h4>
                        <p>
                            Your affiliate profile is active with code <strong>{{ $affiliate->affiliate_code }}</strong>.
                            Track clicks, conversions, and your earnings from a single dashboard.
                        </p>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <a href="{{ route('affiliate.dashboard') }}" class="affiliate-request-btn">
                                Open Dashboard
                            </a><br>
                            <span style="margin: 8px;" class="badge rounded-pill {{ $affiliate->status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ $affiliate->status === 'approved' ? 'Approved' : 'Pending Approval' }}
                            </span>
                        </div>
                    </div>
                @else
                    <div class="affiliate-request-card">
                        <div class="badge">Affiliate Program</div>
                        <h4>Grow with our affiliate network</h4>
                        <p>
                            Join our affiliate program and earn commissions by promoting trusted products,
                            services, and partner offers to your audience.
                        </p>
                        <a href="{{ route('affiliate.register') }}" class="affiliate-request-btn">
                            Request Affiliate Program
                        </a>
                    </div>
                @endif
            </div>
            <!-- /row -->
        </div>
    </div>
    <!-- /row -->
    </div>
    <!-- /container -->
</main>
@endsection
