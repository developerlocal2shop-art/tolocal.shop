<?php $cartdata = count(session()->get('cart', []));  ?>
<a href="#menu" class="btn_mobile">
    <div class="hamburger hamburger--spin" id="hamburger">
        <div class="hamburger-box">
            <div class="hamburger-inner"></div>
        </div>
    </div>
</a>
<nav id="menu" class="main-menu" >
    <ul>
            @if ($mainDirectoryD)
                @if (!empty($mainDirectoryD->company_logo))
                    <img src="{{env('IMAGE_PATH').'/'.$mainDirectoryD->company_logo }}" height="auto" alt="" class="logo_normal_center"> <br>
                <style>
                    .company_details_logo_img { cursor: pointer; width: 60px !important; float: left; margin: 12px 15px !important;
                    }
                </style>
                @else
                <style>
                    .company_details_logo_img { cursor: pointer; width: 60px !important; float: left; margin: 12px 15px !important; }
                </style>
                <h2 class="notranslate company_d_title">{{ $mainDirectoryD->title }}</h2>
                @endif
            @endif

            <li style="display:none;" class="company_detail_menu title-text-main notranslate">
                <span><a href="{{ URL::to('/') }}">{{__("message.menu_text_home")}}</a></span>
            </li>
            <li style="display:none;" class="company_detail_menu nav-item dropdown">
                <span>
                    <a class="nav-link dropdown-toggle desktop title-text-main notranslate" id="navbarDropdownMenuLink" href="{{ route('listing') }}">
                        Companies <span class="caret"></span>
                    </a>
                    <span class="nav-link dropdown-toggle mobile" id="navbarDropdown" aria-haspopup="true" href="{{ route('listing') }}">
                        {{__("message.menu_text_listing")}}
                    </span>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <div class="container2">
                            <div class="row">
                                <div class="col-md-12">
                                    <a class="nav-link mobile" href="{{ route('listing') }}" style=" color: #1f2d3d !important; margin: -6px 70px -20px 1px; text-align: center; text-decoration: underline; padding: 0;">
                                        {{__("message.menu_text_listing")}}
                                        <hr>
                                    </a>
                                    <?php foreach($data['categories'] as $parentcat){ ?>
                                        @php
                                            $count_p_cat = Helper::getExistingCatByDirectory($parentcat->id);
                                        @endphp
                                        @if($count_p_cat > 0)
                                        <div class="menu-list">
                                            <a href="{{ url('listings?cat='.$parentcat->id.'') }}">
                                                <h5> <span> <i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i> </span> {{  ($parentcat->title!='')?$parentcat->title:$parentcat->name }} </h5>
                                            </a>
                                        <ul>
                                        <?php
                                        foreach($data['subcategories'] as $subcat){

                                         $count_s_cat = Helper::getExistingSubCatByDirectory($subcat->id);
                                        if($count_s_cat > 0){
                                        if($subcat->parent_id == $parentcat->id){
                                        ?>
                                            <li>
                                                <a href="{{ url('listings?subcat='.$subcat->id.'') }}">
                                                    <span>
                                                        @if($subcat->icon_class != '')
                                                            <i class="{{ $subcat->icon_class }}" aria-hidden="true"></i>
                                                            @else
                                                            <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                        @endif
                                                    </span>
                                                    {{ ($subcat->title!='')?$subcat->title:$subcat->name }}
                                                </a>
                                            </li>
                                            <?php } } }  ?>
                                        </ul>
                                        </div>
                                        @endif
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <!--  /.container  -->
                    </div>
                </span>
            </li>


            <li style="display:none;" class="company_detail_menu"><span><a class="notranslate" href="{{ route('blog') }}">{{ ucfirst(__("message.menu_text_blog"))}}</a></span></li>
            <li style="display:none;" class="company_detail_menu"><span><a class="notranslate" href="{{ route('event') }}">{{ ucfirst(__("message.menu_text_event")) }}</a></span></li>
            <li style="display:none;" class="company_detail_menu"><span><a class="notranslate" href="{{ route('contact') }}">{{ ucfirst(__("message.menu_text_contact"))}}</a></span></li>

        @if(session()->has("is_lang"))
            <li class="languageselectbox">
            <b id="google_translate_element"></b>
            </li>
        @endif
        <li>
            <?php $defaultCurrency = Helper::defaultCurrencyData();  ?>
            <select class="notranslate menucurrency-alignment" onchange="changeCurrency(this.value)">
                <?php foreach($getAllCurrency as $row){ ?>
                <option {{session()->has('currency_code')?(session()->get('currency_code')['currcode']==$row['code']?'selected':''):($defaultCurrency[0]->code==$row['code']?'selected':'')}} value="{{ $row['code']}}">{{ $row['code']}}</option>
                <?php } ?>
            </select>
        </li>

        <?php
        $user = auth()->guard('web')->check();
        if($user==1){ ?>
        <li class="user"><span><a href="javascript:void(0)"><i class="fas fa-user" aria-hidden="true"></i></a></span>
            <ul>
                @if(Auth::user()->role_id == 0)
                <li class="title-text-main"><a href="{{ route('account') }}">{{__("message.menu_text_myaccount")}}</a></li>
                @else
                <li class="title-text-main"><a href="{{ url('company/reports/dashboard') }}">{{__("message.menu_text_myaccount")}}</a></li>
                @endif
                <li class="title-text-main"><a href="{{ route('Logout') }}">{{__("message.menu_text_logout")}}</a></li>
            </ul>
        </li>
        <li class="user mwishlist">
            <span>
                <a href="{{ route('wishlist') }}"> <i style="color: #dc3545;" class="fa fa-heart" aria-hidden="true"></i> </a>
            </span>
        </li>
        <?php }else{ ?>
        <li class="user">
            <span>
                <a href="javascript:void();">
                <i class="fas fa-user" aria-hidden="true"></i> Account
                </a>
            </span>
            <ul class="before_logged_in">
                {{-- <li class="title-text-main">
                    <h5>Hello Member</h5>
                    <p>To access your Tolocal.shop account</p>
                    <a href="{{ route('Login') }}" class="rounded"><span class="btn_home_align before_logged_button">Login</span></a>
                </li> --}}
                <li class="title-text-main">
                    <h5>Hello Member</h5>
                    <p>To access your Tolocal.shop account</p>
                    <a href="{{ route('login') }}" class="rounded">
                        <span class="btn_home_align before_logged_button">
                         <i class="fa fa-user"></i> Member Login
                        </span>
                    </a>
                    <a href="{{ route('VendorLogin') }}" class="rounded">
                        <span class="btn_home_align before_logged_button">
                         <i class="fa fa-user"></i> Vendor Login
                        </span>
                    </a>
                </li>
            </ul>
        </li>
        <li class="user mwishlist">
            <span>
                <a href="{{ route('wishlist') }}"> <i style="color: #dc3545;" class="fa fa-heart" aria-hidden="true"></i> </a>
            </span>
        </li>
        <?php } ?>

        {{-- @if($cartdata > 0) --}}
            <li>
                <span>
                    <a href="{{ route('cart.list') }}">
                         <i class="fas fa-cart-plus" aria-hidden="true" style="float: right;margin: -13px -4px 0px 0px;position: absolute;right: 0px;font-size: 19px;"></i>
                    @if($cartdata != 0)
                    <span class="notification-count"><?php echo $cartdata!=0?$cartdata:0 ?></span>
                    @endif
                    </a>
                </span>
            </li>
        {{-- @endif --}}
        <?php
        $isCompanyPermission = Helper::checkCompanyPermissionById($mainDirectoryD->id);
        if ($isCompanyPermission && $isCompanyPermission->pluck('name')->contains('customer-club')) {
        ?>
        <li><span>
            <a class="notranslate customer_club" style="cursor: pointer;" data-toggle="modal" data-target="#customer_club">
                {{ ucfirst(__("CUSTOMER CLUB"))}}
            </a></span>
        </li>
        <?php } ?>

        <li class="user notification">

            @if (Auth::check())
            <span>
                <a href="javascript:void(0)">
                    @if(auth()->guard('web')->user()->unreadNotifications->count() != 0)
                    <i class="fas fa-bell"></i>
                    <span> {{ auth()->guard('web')->user()->unreadNotifications->count() }}</span>
                </a>
            </span>
            @endif
            @if($notificationRequest)
            <ul class="sub-menu">
                @foreach ($notificationRequest as $notification)
                @if($notification->read_at == null)
                <li>
                    <a href="{{ url('company/accept',[$notification->id,$notification->data['sender_id'],$notification->data['ticket_id']]) }}">
                        <div class="msg-content">
                            <p>
                                <strong>Admin</strong>
                                {!! $notification->data['message'] !!}
                            </p>
                            <p>
                                @if($notification->created_at->diffInSeconds(Carbon\Carbon::now()) < 60) {{$notification->created_at->diffInSeconds(Carbon\Carbon::now())}} Seconds Ago @endif </p>
                                    <p>
                                        @if($notification->created_at->diffInMinutes(Carbon\Carbon::now()) < 60 && $notification->created_at->diffInMinutes(Carbon\Carbon::now()) > 1)
                                            {{$notification->created_at->diffInMinutes(Carbon\Carbon::now())}} Minutes Ago
                                            @endif
                                    </p>
                                    <p>
                                    @if($notification->created_at->diffInHours(Carbon\Carbon::now()) >= 1 && $notification->created_at->diffInHours(Carbon\Carbon::now()) < 24) {{$notification->created_at->diffInHours(Carbon\Carbon::now())}} Hours Ago @endif </p>
                                        <p>
                                            @if($notification->created_at->diffInHours(Carbon\Carbon::now()) > 24 ) {{$notification->created_at->diffInDays(Carbon\Carbon::now())}} Days Ago
                                            @endif
                                        </p>
                                        <hr>
                        </div>
                    </a>
                </li>
                @endif
                @endforeach
                @if(auth()->guard('web')->user()->unreadNotifications->count() != 0)
                <a href="{{ route('notifications') }}" class="view-notification-button">
                    View Notifications
                </a>
                @endif
            </ul>
            @endif
            @endif
        </li>


    </ul>
</nav>
<b class="locally">
<span class="company_details_logo">
    @if (!empty($mainDirectoryD->company_logo))
    <img src="{{env('IMAGE_PATH').'/'.$mainDirectoryD->company_logo }}" height="auto" alt="" class="logo_normal_center mobile">
    @endif
    <img src="{{ asset('assets/client/images/company_detail_logo.jpg') }}" height="auto" alt="" class="company_details_logo_img">
</span>
</b>
<span class="locally_text">
    @if (!empty($mainDirectoryD->company_logo))@else
    <h2 class="notranslate logo_normal_center mobile company_title_mobile">{{ $mainDirectoryD->title }}</h2>
    @endif
    Shop locally for a vibrant local trade
</span>

<style type="text/css">
.company_detail_menu .dropdown-menu::-webkit-scrollbar-track{background:#f5f5f5;border-radius:10px}.company_detail_menu .dropdown-menu::-webkit-scrollbar-thumb:hover{background:#999}.menu-list h5 span{top:-8px;left:0;background:0 0;border:none}.company_detail_menu .nav-link.desktop{padding:0 0 5px 18px}.company_detail_menu .nav-link.desktop:hover{color:#fff!important;background:rgba(255,255,255,.12);border-radius:6px}.company_detail_menu .nav-link.desktop::after{content:"";position:absolute;left:15px;bottom:4px;width:0;height:2px;background:#fff;transition:.3s}.menu-list ul li a{white-space:nowrap!important}.company_detail_menu:hover .nav-link.desktop::after{width:calc(100% - 30px)}.company_detail_menu .dropdown-menu{width:100%;left:0!important;right:0;top:100%;border:none;padding:35px 30px;background:#fff;box-shadow:0 12px 40px rgba(0,0,0,.08);display:none;overflow:hidden;z-index:999;min-height:130px;max-height:80vh;overflow-y:auto!important;overflow-x:hidden!important;scrollbar-width:thin!important;scrollbar-color:#c1c1c1 #f5f5f5}@keyframes fadeInMenu{from{opacity:0;transform:translateY(12px)}to{opacity:1;transform:translateY(0)}}.company_detail_menu .container2{width:100%}.company_detail_menu .col-md-12{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:30px}.company_detail_menu .menu-list{padding:10px;border-right:1px solid #f1f1f1}.company_detail_menu .menu-list:last-child{border-right:none}.company_detail_menu .menu-list h5{font-size:14px;font-weight:500;color:#1f2d3d;margin:-15px 0 15px;display:flex;align-items:center;gap:10px;transition:.3s}.company_detail_menu .menu-list h5 i{width:35px;height:35px;background:linear-gradient(135deg,#dc3545,#dc3545);color:#fff;border-radius:50%;font-size:16px;display:flex;align-items:center;justify-content:center;transition:.3s;margin:-3px -2px}.company_detail_menu .menu-list:hover h5 i{transform:rotate(-8deg) scale(1.08)}.company_detail_menu .menu-list ul{padding:5px 40px!important;margin:0!important;list-style:none}.company_detail_menu .menu-list ul li{margin-bottom:10px}.company_detail_menu .menu-list ul li a{display:flex;align-items:center;gap:10px;color:#555;font-size:14px;font-weight:500;text-decoration:none;padding:5px 0!important;border-radius:8px;transition:.25s}.company_detail_menu .menu-list ul li a i{font-size:13px;width:16px;color:#7a7a7a}.company_detail_menu .menu-list ul li a:hover{background:#f5f7ff;color:#dc3545;transform:translateX(5px)}.company_detail_menu .menu-list ul li a:hover i{color:#dc3545}.company_detail_menu .dropdown-menu::-webkit-scrollbar{width:6px}.company_detail_menu .dropdown-menu::-webkit-scrollbar-thumb{background:#d6d6d6;border-radius:20px}@media (max-width:991px){.company_detail_menu .dropdown-menu{position:static!important;width:100%;padding:20px 15px;border-radius:12px;box-shadow:none}.company_detail_menu .col-md-12{grid-template-columns:1fr;gap:20px}.company_detail_menu .menu-list{border-right:none;border-bottom:1px solid #eee;padding-bottom:15px}.company_detail_menu .menu-list:last-child{border-bottom:none}.company_detail_menu .menu-list h5{font-size:16px}.company_detail_menu .menu-list ul li a{font-size:14px}}.modal-header .close{margin:-1rem 0 0 auto}.main-menu>ul ul:before{left:10%}i.fa.fa-user{margin:0 5px}.main-menu ul li:hover>ul{margin-left:-110px;margin-top:7px;width:222px;background:#ccc}.before_logged_in li a{padding:0 10px!important}.before_logged_in .title-text-main{padding:0 0 15px;background:#f5f5f5}.main-menu ul,.main-menu ul li,.main-menu ul ul{margin:0!important}div#company_imp_tags_div span a{font-size:13px}.locally{text-align:left;float:right;margin:-93px 174px;color:#555;font-size:16px;font-family:monospace}.mwishlist{margin:0 1px 0 -5px}.main-menu .dropdown-menu{border-top:1px solid #eee;background-color:#fff!important}.before_logged_in li{width:205px}.before_logged_in li p{padding:8px;color:#000;font-size:10px;margin:-5px 0 6px}.before_logged_in li h5{padding:8px;font-size:15px;margin:0}.before_logged_button{padding:8px 10px!important;border:none;background:#2f4a7c;outline:0;cursor:pointer;display:inline-block;text-decoration:none;color:#fff;font-weight:600;text-align:center;line-height:1;border-radius:25px!important}.mm-counter,.mobile,.mobile.company_title_mobile{display:none!important}.btn_home_align.before_logged_button a{color:#fff}.mobile{float:left;margin:-10px -16px -10px -15px;color:#ccc!important}.desktop{display:block!important}@media (max-width:768px){.dropdown-toggle::after{content:"Open List";display:inline-block;font-weight:500;color:#fff;transition:.3s;border-top:0 solid!important;position:absolute}.dropdown-toggle.active::after{content:"Close List";color:#fff}.dropdown-toggle{position:relative;display:inline-flex;align-items:center;padding-right:32px!important;transition:color .3s}.dropdown-toggle:hover::before{border-color:#fff}.dropdown-toggle:hover{color:#fff}.dropdown-toggle::before{content:"";position:absolute;right:18px;top:49%;width:6px;height:6px;border-right:2px solid #cfcfcf;border-bottom:2px solid #cfcfcf;transform:translateY(-60%) rotate(45deg);transition:.25s;cursor:pointer;padding:0;box-sizing:content-box}.mobile.company_title_mobile{font-size:26px;color:#000!important;width:100%;padding:11px 0 6px 30px;display:block!important;font-family:fantasy}.locally{float:none;margin:-29px 0 0;color:#555;font-size:13px;font-family:monospace;display:block;font-weight:700;width:320px}.dropdown-toggle{margin:-10px -17px}.mwishlist{margin:-4px 0 16px 4px!important;border-bottom:1px solid rgba(0,0,0,.15);border-top:1px solid rgba(0,0,0,.15)}}@media only screen and (min-width:992px){.main-menu ul ul{left:0;margin:7px -2px!important;min-width:135px}}@media only screen and (max-width:991px){.before_logged_in li h5,.before_logged_in li p{color:#fff}i.fas.fa-cart-plus{position:relative!important;float:left!important}.main_title_3 h2,.main_title_3 h3{margin-top:10px}.btn_home_align.before_logged_button{padding:7px 30px;margin:0;color:#fff}.mobile{display:block!important}.desktop{display:none!important}.main-menu ul ul{left:22px;min-width:136px}.menu-list{display:block;width:100%}}.menu-list h5{white-space:inherit}.notification-count{position:absolute;top:-28px;right:-13px;background-color:#dc3545;color:#fff;border-radius:50%;padding:2px 5px;font-size:10px;font-weight:700}
</style>
