    @php
        $user = auth()->user();
    @endphp
        @php
            $current_uri = request()->segments();
            if(isset($current_uri[0])){
                 $current_uri_0 =  $current_uri[0];
            }else{
                $current_uri_0 = '';
            }
            if(isset($current_uri[1])){
                 $current_uri_1 =  $current_uri[1];
            }else{
                $current_uri_1 = '';
            }

            if(isset($current_uri[2])){
                 $current_uri_2 =  $current_uri[2];
            }else{
                $current_uri_2 = '';
            }
        @endphp

        <a href="#"></a>

        <div class="dropdown text-center mt-1">
            <button class="notranslate {{ (request()->is('company/reports/dashboard')) || request()->is('company/offline-reports') || request()->is('company/allvisitors') || request()->is('company/allreceiptclosing') || request()->is('company/allextra/reports') || $current_uri_1 == 'offline-reports-edit'  ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownStatus" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i>
                 {!! __('message.vendor_menu_status') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownStatus">
                @if ($user->hasModule('reports') == true)
                    <li class="{{ (request()->is('company/reports/dashboard')) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ url('company/reports/dashboard') }}">
                            <i class="fas fa-file me-2 text-primary"></i> {!! __('message.vendor_menu_reports') !!}
                        </a>
                    </li>
                    <li class="{{ (request()->is('company/affiliate/dashboard') || request()->is('company/affiliate/settings')) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('vendor.affiliate.dashboard') }}">
                            <i class="fas fa-handshake me-2 text-primary"></i> Affiliate Dashboard
                        </a>
                    </li>
                @endif
                <li><hr class="dropdown-divider"></li>
                @if (Helper::getFeatureUses('offline-reports') == true)
                    @if ($user->hasModule('offline-reports') == true || auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/offline-reports') || request()->is('company/allvisitors') || request()->is('company/allreceiptclosing') || request()->is('company/allextra/reports') || $current_uri_1 == 'offline-reports-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.offline.reports') }}">
                            <i class="fas fa-archive me-2 text-primary"></i> {!! __('message.vendor_menu_offline_reports') !!}
                        </a>
                    </li>
                    @endif
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2" target="_blank" href="{{ route('listing.details',$directoryUrl->slug??'') }}">
                        <i class="notranslate fas fa-industry me-2 text-danger"></i> {!! __('message.vendor_menu_company_details') !!}
                    </a>
                </li>
            </ul>

            <button class="notranslate {{ (request()->is('company/review')) || request()->is('company/orders') || $current_uri_1 == 'view' || request()->is('company/booking') || $current_uri_1 == 'booking-edit' || request()->is('company/booking') || $current_uri_1 == 'booking-edit'  ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownAction" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i> {!! __('message.vendor_menu_action') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownAction">
                {{-- <li><a class="dropdown-item py-2" href="#"><i class="bi bi-house-door me-2 text-primary"></i>Reviews</a></li> --}}
                <li class="{{ (request()->is('company/review') || $current_uri_1 == 'review-edit' ) ? 'activemenu' : '' }}">
                    <a class="notranslate dropdown-item py-2" href="{{ route('company.review.index') }}">
                        <i class="fas fa-comments me-2 text-primary"></i>
                        {!! __('message.vendor_menu_reviews') !!}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                @if (Helper::getFeatureUses('orders') == true)
                    @if ($user->hasModule('orders') == true)
                    <li class="{{ (request()->is('company/orders') || $current_uri_1 == 'view' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.order.index') }}">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            {!! __('message.vendor_menu_offline_orders') !!}
                        </a>
                    </li>
                    @endif
                @endif
                <li><hr class="dropdown-divider"></li>
                @if (Helper::getFeatureUses('booking') == true)
                    @if ($user->hasModule('booking') == true)
                    <li class="{{ (request()->is('company/booking') || $current_uri_1 == 'booking-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.booking.index') }}">
                            <i class="fas fa-archive me-2 text-danger"></i>
                            {!! __('message.vendor_menu_company_booking') !!}
                            @if($bookingdata > 0)
                            <div class="notification-container">
                                <div class="bell-icon">
                                <i class="fas fa-bell" style=" float: right; margin: -15px 1px 0px 0px; position: absolute; right: 0; "></i>
                                <span class="notification-count">{{ $bookingdata }}</span>
                                </div>
                            </div>
                            @endif
                        </a>
                    </li>
                    @endif
                @endif
            </ul>

            <button class="notranslate {{ (request()->is('company/permission') || request()->is('company/request/newticket') || request()->is('company/permission-create') || $current_uri_1 == 'permission-edit') || request()->is('company/role') || request()->is('company/role-create') || $current_uri_1 == 'role-edit' || request()->is('company/mail') || request()->is('company/add-mail') || request()->is('company/add-mail-template') || $current_uri_1 == 'view-mail' ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownAdministration" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i> {!! __('message.vendor_menu_administration') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownAdministration">
                @if (Helper::getFeatureUses('permission') == true)
                    @if ($user->hasRole('vendor') == true)
                        <li class="{{ (request()->is('company/permission') || request()->is('company/permission-create') || $current_uri_1 == 'permission-edit' ) ? 'activemenu' : '' }}">
                            <a class="notranslate dropdown-item py-2" href="{{ route('company.permission.index') }}">
                                 <i class="fas fa-child me-2 text-primary"></i>
                                 {!! __('message.vendor_menu_staff_permissions') !!}
                            </a>
                        </li>
                    @endif
                @endif
                <li><hr class="dropdown-divider"></li>
                @if (Helper::getFeatureUses('role') == true)
                    @if ($user->hasModule('role') == true)
                    <li class="{{ (request()->is('company/role') || request()->is('company/role-create') || $current_uri_1 == 'role-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.role.index') }}">
                             <i class="fas fa-user-md me-2 text-primary"></i>
                             {!! __('message.vendor_menu_role_permissions') !!}
                        </a>
                    </li>
                    @endif
                @endif
                <li><hr class="dropdown-divider"></li>
                <li class="{{ ( request()->is('company/request/newticket') ) ? 'activemenu' : '' }}">
                    <a class="notranslate dropdown-item py-2" href="{{ route('company.request.newticket') }}">
                        <i class="fas fa-meh me-2 text-primary"></i>
                        {!! __('message.vendor_menu_open_ticket') !!}
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                @if (Helper::getFeatureUses('mail') == true)
                    @if ($user->hasModule('mail') == true)
                    <li class="{{ (request()->is('company/mail') || request()->is('company/add-mail') || request()->is('company/add-mail-template') || $current_uri_1 == 'view-mail' ) ? 'activemenu' : '' }} ">
                    <a class="notranslate dropdown-item py-2" href="{{ route('company.mail.index') }}">
                        <i class="fas fa-envelope me-2 text-danger"></i>
                        {!! __('message.vendor_menu_mail') !!}
                    </a>
                    </li>
                    @endif
                @endif
            </ul>

            <button class="notranslate {{ (request()->is('company-information')) || request()->is('company/trademarks')  ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownContent" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i> {!! __('message.vendor_menu_content') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownContent">
                {{-- <li><a class="dropdown-item py-2" href="#"><i class="bi bi-house-door me-2 text-primary"></i>Company Info</a></li> --}}
                @if (Helper::getFeatureUses('company-info') == true)
                    @if ($user->hasModule('company-info') == true)
                        <li class="{{ (request()->is('company-information')) ? 'activemenu' : '' }}">
                            <a class="notranslate dropdown-item py-2" href="{{ route('editcompanyinformation') }}">
                                <i class="far fa-address-book me-2 text-primary"></i>
                                {!! __('message.vendor_menu_company_info') !!}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        @if (Helper::getFeatureUses('basic') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=basic')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=basic') }}">
                                    {!! __('message.vendor_menu_basic') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('location') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=location')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=location') }}">
                                    {!! __('message.vendor_menu_location') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('type') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=dirtype')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=dirtype') }}">
                                    {!! __('message.vendor_menu_type') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('amenities') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=amenities')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=amenities') }}">
                                    {!! __('message.vendor_menu_aminities') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('media') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=media')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=media') }}">
                                    {!! __('message.vendor_menu_media') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('seo') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=seo')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=seo') }}">
                                    {!! __('message.vendor_menu_seo') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('schedule-all') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=schedule')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=schedule') }}">
                                    {!! __('message.vendor_menu_schedule') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('links') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=links')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=links') }}">
                                    {!! __('message.vendor_menu_links') !!}
                                </a>
                            </li>
                        @endif
                        @if (Helper::getFeatureUses('claim') == true)
                            <li class="subdrop{{ (request()->is('company-information?target=claim')) ? 'activemenu' : '' }}">
                                <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=claim') }}">
                                    {!! __('message.vendor_menu_claim') !!}
                                </a>
                            </li>
                        @endif
                        <li class="subdrop{{ (request()->is('company-information?target=services')) ? 'activemenu' : '' }}">
                            <a class="notranslate dropdown-item py-2" href="{{ url('company-information?target=services') }}">
                                {!! __('message.vendor_menu_services') !!}
                            </a>
                        </li>
                    @endif
                @endif

                @if (Helper::getFeatureUses('trademarks') == true)
                    @if ($user->hasModule('trademarks') == true || auth()->user()->parent_id == 0)
                    <li><hr class="dropdown-divider"></li>
                    <li class="{{ (request()->is('company/trademarks') || request()->is('company/trademarks-category') || request()->is('company/add-trademarks') || request()->is('company/add-trademarks-category') || $current_uri_1 == 'trademarks-category-edit'  ) ? 'activemenu' : '' }}">
                        <a class="dropdown-item py-2" href="{{ route('company.trademarks.index') }}">
                            <i class="far fa-meh me-2 text-danger"></i> Trademark
                        </a>
                    </li>
                    @endif
                @endif

            </ul>

            <button class="notranslate {{ (request()->is('company/shoppingcard')) || request()->is('company/discount/freeproductsdiscount') || request()->is('company/add-shoppingcard') || $current_uri_1 == 'shoppingcard-edit' || request()->is('company/product/offer') || request()->is('company/product/add-offer') || $current_uri_2 == 'offer-edit' || request()->is('company/discount/voucher') || request()->is('company/discount/voucher/add-voucher') || $current_uri_2 == 'voucher' || request()->is('company/discount/giftvoucher') || request()->is('company/discount/giftvoucher/add-giftvoucher') || $current_uri_2 == 'giftvoucher'  ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownCommunicate" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i> {!! __('message.vendor_menu_communicate') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownCommunicate">

                @if (Helper::getFeatureUses('shopping-card') == true)
                    @if ($user->hasModule('shopping-card') == true || auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/shoppingcard') || request()->is('company/add-shoppingcard') || $current_uri_1 == 'shoppingcard-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.shoppingcard') }}">
                        <i class="fas fa-shopping-bag me-2 text-primary"></i>
                        {!! __('message.vendor_menu_shopping_card') !!}
                        </a>
                    </li>
                    @endif
                @endif

                @if (Helper::getFeatureUses('offers') == true)
                    @if ($user->hasModule('offers') == true)
                    <li class="{{ (request()->is('company/product/offer') || request()->is('company/product/add-offer') || $current_uri_2 == 'offer-edit') ? 'activemenu' : '' }} ">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.offer.index') }}">
                            <i class="fas fa-tags me-2 text-primary"></i>
                            {!! __('message.vendor_menu_offers') !!}
                        </a>
                    </li>
                    @endif
                @endif

                @if (Helper::getFeatureUses('coupons') == true)
                    @if ($user->hasModule('coupons') == true)
                    <li class="{{ (request()->is('company/discount/voucher') || request()->is('company/discount/voucher/add-voucher') || $current_uri_2 == 'voucher' ) ? 'activemenu' : '' }} ">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.discount.voucher.index') }}">
                           <i class="fas fa-ticket-alt me-2 text-primary"></i>
                            {!! __('message.vendor_menu_coupons') !!}
                        </a>
                    </li>
                    @endif
                @endif
                @if (Helper::getFeatureUses('gift-voucher') == true)
                    @if ($user->hasModule('gift-voucher') == true)
                        <li class="{{ (request()->is('company/discount/giftvoucher') || request()->is('company/discount/giftvoucher/add-giftvoucher') || $current_uri_2 == 'giftvoucher' ) ? 'activemenu' : '' }}">
                            <a class="notranslate dropdown-item py-2" href="{{ route('company.discount.giftvoucher.indexGiftVoucher') }}">
                            <i class="fas fa-gift me-2 text-primary"></i>
                            {!! __('message.vendor_menu_gift_vouchers') !!}
                            </a>
                        </li>
                    @endif
                @endif
                @if (Helper::getFeatureUses('free-products-discount') == true)
                    @if ($user->hasModule('products-discount') == true)
                        <li class="{{ (request()->is('company/discount/freeproductsdiscount') ) ? 'activemenu' : '' }}">
                            <a class="notranslate dropdown-item py-2" href="{{ route('company.discount.freeproductsdiscount.indexProductDiscount') }}">
                            <i class="fas fa-box me-2 text-danger"></i>
                            {!! __('message.vendor_menu_free_products') !!}
                            </a>
                        </li>
                    @endif
                @endif
            </ul>

            <button class="notranslate {{ (request()->is('company/add-attribute') || request()->is('company/attributes') || request()->is('company/products') || request()->is('company/add-product') || $current_uri_1 == 'product-edit' || $current_uri_1 == 'attribute-edit' || request()->is('company/events') || request()->is('company/add-events') || $current_uri_1 == 'events-edit' || request()->is('company/blog') || request()->is('company/add-blog') || $current_uri_1 == 'blog-edit' || request()->is('company/advertisement') || request()->is('company/add-advertisement') || $current_uri_1 == 'advertisement-edit' || request()->is('company/customer/club')  ) ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownMarketing" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i> {!! __('message.vendor_menu_marketing') !!}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownMarketing">
                {{-- <li><a class="dropdown-item py-2" href="#"><i class="bi bi-house-door me-2 text-primary"></i>Products</a></li> --}}
                @if (Helper::getFeatureUses('products') == true)
                    @if ($user->hasModule('products') == true)
                    <li class="{{ (request()->is('company/add-attribute') || request()->is('company/attributes') || request()->is('company/products') || request()->is('company/add-product') || $current_uri_1 == 'product-edit' || $current_uri_1 == 'attribute-edit') ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.products') }}">
                            <i class="fas fa-tags me-2 text-primary"></i>
                            {!! __('message.vendor_menu_products') !!}
                        </a>
                    </li>
                    @endif
                @endif

                @if (Helper::getFeatureUses('events') == true)
                    @if ($user->hasModule('events') == true || auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/events') || request()->is('company/add-events') || $current_uri_1 == 'events-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.events') }}">
                            <i class="fas fa-calendar me-2 text-primary"></i>
                            {!! __('message.vendor_menu_events') !!}
                        </a>
                    </li>
                    @endif
                @endif
                @if (Helper::getFeatureUses('blog') == true)
                    @if ($user->hasModule('blogs') == true || auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/blog') || request()->is('company/add-blog') || $current_uri_1 == 'blog-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.blog') }}">
                            <i class="fas fa-newspaper me-2 text-primary"></i>
                            {!! __('message.vendor_menu_blogs') !!}
                        </a>
                    </li>
                    @endif
                @endif
                @if (Helper::getFeatureUses('advertisment') == true)
                    @if ($user->hasModule('advertisement') == true || auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/advertisement') || request()->is('company/add-advertisement') || $current_uri_1 == 'advertisement-edit' ) ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.advertisement') }}">
                            <i class="fas fa-bullhorn me-2 text-primary"></i>
                            Competition
                            {{-- {!! __('message.vendor_menu_advertisement') !!} --}}
                        </a>
                    </li>
                    @endif
                @endif

                {{-- <li class="{{ (request()->is('company/questionnaire') || request()->is('company/questionnaire') || $current_uri_1 == 'company/questionnaire' ) ? 'activemenu' : '' }}">
                    <a class="notranslate dropdown-item py-2" href="{{ route('vendor.questionnaire') }}">
                        <i class="fas fa-basketball-ball me-2 text-primary"></i>
                        {!! __('Competition') !!}
                    </a>
                </li> --}}

                @if (Helper::getFeatureUses('customer-club') == true)
                <li class="{{ (request()->is('company/customer/club') ) ? 'activemenu' : '' }}">
                    <a class="notranslate dropdown-item py-2" href="{{ route('company.customer.club') }}">
                       <i class="fas fa-ticket-alt me-2 text-danger"></i>
                       {!! __('message.vendor_menu_customer_club') !!}
                    </a>
                </li>
                @endif

            </ul>

            <button class="notranslate {{ (request()->is('company/packages') ) ? 'active_main_menu' : '' }} btn btn-primary dropdown-toggle px-4 py-2 rounded-pill shadow" type="button" id="dropdownProfile" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-menu-button-wide-fill me-2"></i>
                Member
                {{-- {!! __('message.vendor_menu_profile') !!} --}}
            </button>
            <ul class="dropdown-menu shadow-lg rounded" aria-labelledby="dropdownProfile">
                @if(auth()->user()->parent_id == 0)
                    <li class="{{ (request()->is('company/packages') || $current_uri_0 == 'package') ? 'activemenu' : '' }}">
                        <a class="notranslate dropdown-item py-2" href="{{ route('company.packages') }}">
                            <i class="fas fa-user-md me-2 text-primary"></i>
                            Upgrade Member
                            {{-- {!! __('message.vendor_menu_upgrade_profile') !!} --}}
                        </a>
                    </li>
                @endif

                @if (Helper::getFeatureUses('profile') == true)
                    <li class="{{ (request()->is('account')) ? 'activemenu' : '' }} ">
                        <a class="notranslate dropdown-item py-2" href="{{ route('account') }}">
                            <i class="fas fa-user-circle me-2 text-danger"></i>
                            Member Profile {{-- {!! __('message.vendor_menu_my_profile') !!} --}}
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        <style>
.sub_header_in.sticky_header {
    padding: 20px 0px 0px 0px;
}
.vendor_bedcrub {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin: 20px 0 0 0;
    padding: 8px 16px;
    border-radius: 999px;
    background: #eef4ff;
    color: #1d4ed8;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(29, 78, 216, 0.08);
}

.vendor_bedcrub::before {
    content: "←";
    font-weight: 700;
}

.vendor_bedcrub:hover {
    color: #1d4ed8;
    text-decoration: none;
    background: #e0ebff;
}
.btn-primary {
    padding: 5px 30px !important;
}
a.dropdown-item.py-2 i {
    margin: 0px 5px 0px -10px;
}
.active_main_menu{
    background: #0069d9 !important;
    border: none !important;
}
li.subdrop a {
    font-size: 15px;
    margin: -1px  0px -1px 0px;
}
li.subdrop {
    margin: 0px 0px 0px 20px;
    padding: 0px 0px;
    border-bottom: 1px solid #f1f1eb;
}
.dropdown-menu.show {
    padding: 0px 0px;
}
.dropdown-divider {
    margin: 0px;
}
@media (min-width: 1200px) {
    .container {
        max-width: 1200px !important;
    }
}
@media (min-width: 1700px) and (max-width: 2600px) {
    .container {
        max-width: 1400px !important;
    }
    ul.comp-header li.list-group-item {
        padding-left: 10px !important;
        padding-right: 10px !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
    }
}

.btn-info {
    background-color: #203864;
    border-color: #203864;
}
.filter_sub_blue_header.newClass {
    top: -20px;
}
.notification-container {
  position: relative;
}

.bell-icon {
  position: relative;
  cursor: pointer;
}

.notification-count {
    position: absolute;
    top: -38px;
    right: -9px;
    background-color: red;
    color: white;
    border-radius: 50%;
    padding: 3px 8px;
    font-size: 12px;
    font-weight: bold;
}
</style>

