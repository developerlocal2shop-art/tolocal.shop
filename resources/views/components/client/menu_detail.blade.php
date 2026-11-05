

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
                        {{ ucfirst(__("Customer Club"))}}
                    </a>
                </span>
            </li>
            <?php } ?>

            <li class="user">
                <span>
                    <a href="javascript:void();">
                        <i class="fas fa-contact" aria-hidden="true"></i>
                        {{ ucfirst(__("message.menu_text_contact"))}}
                    </a>
                </span>
                <ul class="contact_us_header">
                    <div class="box_detail booking listing-contact-card">
                        @if(session()->get('status'))
                            <div class="alert alert-success">
                                {{ session()->get('status') }}
                            </div><br />
                        @endif
                        <div class="listing-contact-intro">
                            <span class="listing-contact-intro__icon" aria-hidden="true"><i class="fas fa-comments"></i></span>
                            <div class="listing-contact-intro__copy">
                                <h2>We're here to help</h2>
                                <p>Send your question or booking request and the team will get back to you.</p>
                            </div>
                        </div>
                        @if(!empty($bookingListingData['mainDirectoryDetails']))
                            @php
                                $headerBookingDirectory = $bookingListingData['mainDirectoryDetails'];
                                $headerBookingVerification = data_get($headerBookingDirectory, 'user.is_verified') ?? 'blank';
                                $headerBookingDisabled = in_array($headerBookingVerification, ['default', 'blank'], true);
                                $headerBookingCategory = false;
                                foreach (data_get($bookingListingData, 'categoriesData', []) as $headerBookingCategoryData) {
                                    if (in_array(data_get($headerBookingCategoryData, 'slug'), ['accomendations', 'food-nightlife'], true)) {
                                        $headerBookingCategory = true;
                                        break;
                                    }
                                }
                            @endphp
                            <li class="contact-us-header-content {{ $headerBookingDisabled ? 'notactivate' : '' }}">
                                @if($headerBookingDisabled)
                                    <div class="overlay-content">Not Activated</div>
                                @endif
                                <form id="bookingform-header" class="contact-us-form" action="{{ route('appointment.booking') }}" method="post">
                                    {{ csrf_field() }}

                                    <input type="hidden" name="user_id" value="{{ data_get($headerBookingDirectory, 'user.id', 0) }}">
                                    <input type="hidden" name="requester_id" value="">
                                    <input type="hidden" name="listing_id" value="{{ data_get($headerBookingDirectory, 'id') }}">
                                    <input type="hidden" name="listing_type" value="{{ data_get($headerBookingDirectory, 'feature_type') }}">
                                    <input type="hidden" name="slug" value="clarance-hotel">
                                    <input type="hidden" name="type" value="booking">
                                    <input type="hidden" name="bookingComment" value="">
                                    <input type="hidden" name="directory_id" value="{{ data_get($headerBookingDirectory, 'id') }}">

                                    <div class="form-group">
                                        <input class="form-control required" type="text" name="name" required placeholder="{{ __('message.placeholder_name_text') }}" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control required" type="email" name="email" required placeholder="Enter e-mail address!">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12 p-0"><b><u>Note:</u> No country code!</b></label>
                                        <input class="form-control required" type="tel" name="booking_phone" required placeholder="Enter Valid Phone!">
                                    </div>
                                    @if(data_get($bookingUserData, 'visibility_booking_date_time') == 1)
                                        <div class="form-group">
                                            <input class="form-control required booking-date-input" title="Select date and time for the desired booking" type="text" name="booking_date" id="booking_date_header" required placeholder="Select date and time for the desired booking">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <select class="form-control" name="service">
                                            @foreach(data_get($bookingListingData, 'directory_services', []) as $headerBookingService)
                                                @php $headerBookingServiceName = data_get($headerBookingService, 'name', ''); @endphp
                                                <option value="{{ $headerBookingServiceName }}">{{ $headerBookingServiceName }}</option>
                                            @endforeach
                                            <option value="Contact us Content">Contact us Content</option>
                                        </select>
                                    </div>
                                    @if($headerBookingCategory)
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="adult" placeholder="{{ __('message.placeholder_no_of_adult') }}">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="child" placeholder="{{ __('message.placeholder_no_of_child') }}">
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <textarea rows="2" class="form-control" placeholder="{{ __('message.message_label_text') }}" name="message"></textarea>
                                    </div>
                                    <button type="submit" class="add_top_30 btn_1 full-width purchase submitbtn" {{ $headerBookingDisabled ? 'disabled' : '' }}>
                                        {{ __('message.submit_button') }}
                                    </button>
                                    <span class="respondata" role="status" aria-live="polite"></span>
                                </form>
                                <div class="text-center"><small>{{__("message.contact_us_form_text")}}</small></div>
                            </li>
                        @endif
                    </div>
                </ul>
            </li>

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
        <img src="{{ asset('assets/client/images/company_detail_logo.jpg') }}" alt="Other Local" class="company_details_logo_img hamburger hamburger--spin" id="hamburger">
        <button type="button" class="listing-local-search-trigger" data-toggle="modal" data-target="#listing-local-search" aria-label="Search local listings" title="Search local listings">
            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="m16 16 5 5"></path>
            </svg>
        </button>
        @if (!empty($mainDirectoryD->company_logo))@else
        <h2 class="notranslate logo_normal_center mobile company_title_mobile">
            {{ $mainDirectoryD->title }}
        </h2>
        @endif
    </div>
