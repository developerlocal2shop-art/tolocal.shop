
<?php $cartdata = count(session()->get('cart', []));  ?>
    <a href="#menu" class="btn_mobile">
        <div class="hamburger hamburger--spin" id="hamburger">
            <div class="hamburger-box">
                <div class="hamburger-inner"></div>
            </div>
        </div>
    </a>
    <div class="google_translate_mobile city_name_mobile">
        <b id="google_translate_element_mobile"></b>
        <div class="logo_wrapper">
            <p class="logo_city_name">{{ session()->get('filter_city') }}</p>
        </div>
    </div>

    <div class="city_name_desktop">
        @if ($mainDirectoryD)
            @if (!empty($mainDirectoryD->company_logo))
                <img src="{{ env('IMAGE_PATH') . $mainDirectoryD->company_logo }}" height="auto" alt="" class="logo_normal_center">
            @else
                <h2 class="notranslate company_d_title">{{ $mainDirectoryD->title }}</h2>
            @endif
        @endif

        <div class="logo_wrapper">
            <p class="logo_city_name">{{ session()->get('filter_city') }}</p>
        </div>
    </div>

    <nav id="menu" class="main-menu listing-detail-menu-hidden">
        <ul>

            <li class="google_translate_mobile">
                <b id="google_translate_element_mobile"></b>
            </li>
            <li class="company_detail_menu title-text-main notranslate">
                <span><a href="{{ URL::to('/') }}">{{__("message.menu_text_home")}}</a></span>
            </li>
            <li class="company_detail_menu nav-item dropdown">
                <a class="nav-link dropdown-toggle desktop title-text-main notranslate" id="navbarDropdownMenuLink" href="{{ route('listing') }}">
                    Companies <span class="caret"></span>
                </a>
                <a class="nav-link dropdown-toggle mobile" id="navbarDropdown" aria-haspopup="true" href="{{ route('listing') }}">
                    {{__("message.menu_text_listing")}}
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <div class="container2">
                        <div class="row">
                            <div class="col-md-12">
                                <a class="nav-link mobile" href="{{ route('listing') }}" style="color: #1f2d3d; text-align: center; text-decoration: underline;">
                                    {{__("message.menu_text_listing")}}
                                </a>
                                <?php foreach($data['categories'] as $parentcat){ ?>
                                @php
                                    $count_p_cat = Helper::getExistingCatByDirectory($parentcat->id);
                                @endphp
                                @if($count_p_cat > 0)
                                <div class="menu-list">
                                    <a href="{{ url('listings?cat='.$parentcat->id) }}">
                                        <h5><span><i class="{{ $parentcat->icon_class }}" aria-hidden="true"></i></span> {{ $parentcat->title != '' ? $parentcat->title : $parentcat->name }}</h5>
                                    </a>
                                    <ul>
                                        <?php foreach($data['subcategories'] as $subcat) {
                                            $count_s_cat = Helper::getExistingSubCatByDirectory($subcat->id);
                                            if ($count_s_cat > 0 && $subcat->parent_id == $parentcat->id) { ?>
                                                <li>
                                                    <a href="{{ url('listings?subcat='.$subcat->id) }}">
                                                        <span>
                                                            @if($subcat->icon_class != '')
                                                                <i class="{{ $subcat->icon_class }}" aria-hidden="true"></i>
                                                            @else
                                                                <i class="fa fa-caret-right" aria-hidden="true"></i>
                                                            @endif
                                                        </span>
                                                        {{ $subcat->title != '' ? $subcat->title : $subcat->name }}
                                                    </a>
                                                </li>
                                            <?php } } ?>
                                    </ul>
                                </div>
                                @endif
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="company_detail_menu"><span><a class="notranslate" href="{{ route('blog') }}">{{ ucfirst(__("message.menu_text_blog"))}}</a></span></li>
            <li class="company_detail_menu"><span><a class="notranslate" href="{{ route('event') }}">{{ ucfirst(__("message.menu_text_event")) }}</a></span></li>
            <li class="company_detail_menu"><span><a class="notranslate" href="{{ route('contact') }}">{{ ucfirst(__("message.menu_text_contact"))}}</a></span></li>
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
            <li class="user">
                <span><a href="javascript:void(0)"><i class="fas fa-user" aria-hidden="true"></i></a></span>
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
                        <i class="fas fa-cart-plus" aria-hidden="true" style="float: right;margin: 5px 0px 0px 0px;position: absolute;right: 0px;font-size: 19px;"></i>
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
            <li>
                <span>
                    <a class="notranslate customer_club" style="cursor: pointer;" data-toggle="modal" data-target="#customer_club">
                        {{ ucfirst(__("CUSTOMER CLUB"))}}
                    </a>
                </span>
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
                    <li>
                        <a href="{{ route('notifications') }}" class="view-notification-button">View Notifications</a>
                    </li>
                    @endif
                </ul>
                @endif
                @endif
            </li>
        </ul>

    </nav>

    <div class="listing-local-promo">
        <p class="listing-local-promo__text">Find MORE local shopping and relaxation HERE:</p>
        <img src="{{ asset('assets/client/images/company_detail_logo.jpg') }}" alt="Other Local" class="company_details_logo_img">
        @if (!empty($mainDirectoryD->company_logo))@else
        <h2 class="notranslate logo_normal_center mobile company_title_mobile">
            {{ $mainDirectoryD->title }}
        </h2>
        @endif
    </div>
