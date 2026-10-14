
<aside class="left-sidebar" data-sidebarbg="skin6">
@php $user = auth()->guard('webadmin')->user() @endphp
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <!-- User Profile-->
                @if ($user->hasModule('dashboard') == true)
                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link " href="{{ route('admin.dashboard.index') }}" aria-expanded="false">
                            <i class="far fa-clock" aria-hidden="true"></i>
                            <span class="hide-menu">Dashboard</span>
                        </a>
                    </li>
                @endif

                @if ($user->hasModule('reports') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#reports" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Reports</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="reports">
                            <ul class="nav flex-column sub-menu">

                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.reports.dashboard')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;"> Dashboard</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.offline.reports')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Offline Shop Reports</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                <li class="sidebar-item pt-2">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.affiliate.index')}}" aria-expanded="false">
                        <i class="fa fa-suitcase" aria-hidden="trues"></i>
                        @php
                        $affiliate_count = Helper::affiliate_notification();
                        @endphp
                        <span class="hide-menu">Affiliate Customers</span>

                        @if($affiliate_count > 0)
                            <span class="sidebar_notification">
                                <i class="fa fa-bell-o" aria-hidden="true" style="font-size: 15px; color: rgb(229, 238, 229);"></i>
                                {{ $affiliate_count }}
                            </span>
                        @endif

                    </a>
                </li>

                @if ($user->hasModule('users') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.user.index')}}" aria-expanded="false">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span class="hide-menu">Users</span>
                        </a>
                    </li>
                @endif

                 @if ($user->hasModule('blogs') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.blog.index')}}" aria-expanded="false">
                            <i class="fab fa-blogger-b" aria-hidden="true"></i>
                            <span class="hide-menu">Blogs</span>
                        </a>
                    </li>
                @endif

                @if ($user->hasModule('claim') == true || $user->hasRole('administrator') == true)
                <li class="sidebar-item pt-2">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.claim.index')}}" aria-expanded="false">
                        <i class="fa fa-suitcase" aria-hidden="true"></i>
                        @php
                        $claimunread = Helper::notification();
                        @endphp
                        <span class="hide-menu">Claim</span>

                        @if($claimunread > 0)
                        <span class="sidebar_notification">
                            <i class="fa fa-bell-o" aria-hidden="true" style="font-size: 15px; color: rgb(229, 238, 229);"></i>
                            {{ $claimunread }}
                        </span>
                        @endif
                    </a>
                </li>
                @endif

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.ticket.requestList')}}" aria-expanded="false">
                        <i class="fa fa-align-left" aria-hidden="true"></i>
                        <span class="hide-menu">Ticket Request</span>
                    </a>
                </li>

                 @if ($user->hasModule('directory') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#directory" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-sitemap" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Companies</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="directory">
                            <ul class="nav flex-column sub-menu">
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.directory.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Company</span></a></li>
                                {{-- <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.directory.create')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Directory</span></a></li> --}}
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.categories.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Categories </span></a></li>
                                @if ($user->hasModule('tags') == true)
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.tags.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Tags</span></a></li>
                                @endif
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.business.index')}}"><i class="fas fa-building" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Business</span></a></li>

                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.customfields.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Custom Fields</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if ($user->hasModule('categories') == true)
                    {{-- <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#cate" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-globe" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Directory Categories</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="cate">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.categories.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Categories List</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.customfields.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Custom Fields</span></a></li>
                            </ul>
                        </div>
                    </li> --}}
                @endif


                @if ($user->hasModule('products') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#product" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-suitcase" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Products</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="product">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.product.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Product</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.product.create')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Product</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.inventory.index')}}"><i class="fa fa-folder-open" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Inventory</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{ route('admin.productcategories.index')}}"><i class="fa fa-suitcase" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Product Category</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.attributes.index')}}"><i class="fa fa-folder-open" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Attributes</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;">
                                <a class="nav-link" style="color:#54667a;" href="{{route('admin.shipping.index')}}">
                                    <i class="fa fa-folder-open" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Shipping Management</span>
                                </a>
                                </li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.producttags.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Tags</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if ($user->hasModule('orders') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#order" aria-expanded="false" aria-controls="general-pages">
                            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Orders</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="order">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.order.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Orders</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.orderstatus.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Order Status</span></a></li>

                            </ul>
                        </div>
                    </li>
                @endif
                @if ($user->hasModule('reviews') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#review" aria-expanded="false" aria-controls="general-pages">
                            <i class="fa fa-star" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Reviews</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="review">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.review.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Review</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.review.create')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Review</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($user->hasModule('product_offer') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#offer" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Product Offer</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="offer">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.offer.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Product Offer</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.offer.create')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Product Offer</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if ($user->hasModule('shopping-card') == true || $user->hasRole('administrator') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#shoppingcart" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Shopping Card</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="shoppingcart">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.shoppingcard.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Shopping Card</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.shoppingcard.create')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Shopping Card</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif


                @if ($user->hasModule('discount') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#discount" aria-expanded="false" aria-controls="general-pages">
                            <i class="fa fa-gift" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Discounts Managment</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="discount">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" data-toggle="collapse" href="#voucher" aria-expanded="false" aria-controls="general-pages" style="color:#54667a;" href="#"><i class="fa fa-archive" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Coupon</span><i class="fa fa-caret-down" style="right: 12px;"></i></a>
                                    <ul class="nav flex-column sub-menu" id="voucher">

                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.voucher.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">List Coupon</span></a></li>
                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.voucher.create')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Coupon</span></a></li>
                                    </ul>
                                </li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" data-toggle="collapse" href="#giftvoucher" aria-expanded="false" aria-controls="general-pages" style="color:#54667a;" href="#"><i class="fa fa-gift" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Gift Voucher</span><i class="fa fa-caret-down" style="right: 12px;"></i></a>
                                    <ul class="nav flex-column sub-menu" id="giftvoucher">

                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.giftvoucher.indexGiftVoucher')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Gift Voucher List</span></a></li>
                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.giftvoucher.createGiftVoucher')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Gift Voucher</span></a></li>
                                    </ul>
                                </li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" data-toggle="collapse" href="#giftvoucher" aria-expanded="false" aria-controls="general-pages" style="color:#54667a;" href="#"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Free Products Discount</span><i class="fa fa-caret-down" style="right: 12px;"></i></a>
                                    <ul class="nav flex-column sub-menu" id="giftvoucher">

                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.freeproductsdiscount.indexProductDiscount')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Free Products</span></a></li>
                                        <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.discount.freeproductsdiscount.createProductDiscount')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Free Products</span></a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                 @if ($user->hasModule('amenities') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#amenity" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Amenities</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="amenity">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.amenity.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Amenities List</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.amenitycategory.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Amenity Category</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($user->hasModule('trademarks') == true)
                    @php
                    $trademarkNotification = Helper::trademarkNotification();
                    @endphp
                    @if($trademarkNotification > 0)
                    <li class="sidebar-item pt-2">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('admin.pending.trademarks')}}" aria-expanded="false">
                            <i class="fa fa-suitcase" aria-hidden="true"></i>


                            <span class="hide-menu">Pendding Trademarks</span>
                            <i class="fa fa-bell-o" aria-hidden="true" style="font-size: 18px; color: rgb(245, 252, 245);"></i>
                            <span style="margin-top:-10px; margin-left:-12px; color:red;">{{ $trademarkNotification }}</span>


                        </a>
                    </li>
                    @endif
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#trademarks" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-sitemap" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Trademarks</span>
                            <i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="trademarks">
                            <ul class="nav flex-column sub-menu">
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.trademarks.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Trademarks</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.trademarks.create')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add Trademarks</span></a></li>

                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.trademarkcategory.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Trademarks Category</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($user->hasModule('appointment_booking') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.booking.index')}}" aria-expanded="false">
                            <i class="fa fa-align-left" aria-hidden="true"></i>
                            <span class="hide-menu">Appointment booking</span>
                        </a>
                    </li>
                @endif

                @if ($user->hasModule('events') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.events.index')}}" aria-expanded="false">
                            <i class="fas fa-flask" aria-hidden="true"></i>
                            <span class="hide-menu">Events</span>
                        </a>
                    </li>
                @endif
                {{-- @if ($user->hasModule('advertisement') == true) --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.advertise.index')}}" aria-expanded="false">
                            <i class="fa  fa-folder-open" aria-hidden="true"></i>
                            <span class="hide-menu">Competitions</span>
                        </a>
                    </li>
                {{-- @endif
                @if ($user->hasModule('advertisement') == true) --}}
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.hotads.index')}}" aria-expanded="false">
                            <i class="fa  fa-folder-open" aria-hidden="true"></i>
                            <span class="hide-menu">Hot Ads</span>
                        </a>
                    </li>
                {{-- @endif --}}

                @if ($user->hasRole('administrator') == true)
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#packages" aria-expanded="false"  aria-controls="general-pages">

                        <i class="fa fa-object-group" aria-hidden="true"></i>
                        <span class="hide-menu" style="padding-left: 5px;">Packages Managment</span><i class="fa fa-caret-down"></i>
                    </a>
                    <div class="collapse" id="packages">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.package.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Packages</span></a></li>
                            {{-- <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.package.create')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add New package</span></a></li> --}}
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.manual.package.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Manual permission</span></a></li>
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.package.order.history')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Package Order History</span></a></li>
                        </ul>
                    </div>
                </li>

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#permissions" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Permissions Managment</span><i class="fa fa-caret-down"></i>
                        </a>
                        <div class="collapse" id="permissions">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.permission.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Permissions</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.permission.create')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add New</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.role.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Manage Role</span></a></li>


                            </ul>
                        </div>
                    </li>
                @endif

                @if ($user->hasModule('templates') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#temp" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fas fa-address-card" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Holiday Templates</span><i class="fa fa-caret-down" ></i>
                        </a>
                        <div class="collapse" id="temp">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.normaltemplate.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Normal Template</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.holidaytemplate.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Holiday Template</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.exceptionalholidaytemplate.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Exceptional Holiday Template</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if ($user->hasModule('mail') == true)
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#mail" aria-expanded="false" aria-controls="general-pages">
                        <i class="fa fa-star" aria-hidden="true"></i>
                        <span class="hide-menu" style="padding-left: 5px;">Mail</span><i class="fa fa-caret-down" ></i>
                    </a>
                    <div class="collapse" id="mail">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.mail.listing')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">All Mail</span></a></li>
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.mail.create')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Send New</span></a></li>
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.mailtemplate')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Mail Template</span></a></li>
                        </ul>
                    </div>
                </li>
                @endif
                <?php /* ?>
                @if ($user->hasModule('languages') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.languages.index')}}" aria-expanded="false">
                            <i class="fa fa-font" aria-hidden="true"></i>
                            <span class="hide-menu">Languages</span>
                        </a>
                    </li>
                @endif
                <?php */ ?>

                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.defaultimages.index')}}" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span class="hide-menu">Website's Default images</span>
                    </a>
                </li>
                @if ($user->hasRole('administrator') == true)
                <li class="sidebar-item">
                    <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#country-state-city" aria-expanded="false"  aria-controls="general-pages">
                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                        <span class="hide-menu" style="padding-left: 5px;"> Location Management</span><i class="fa fa-caret-down"></i>
                    </a>
                    <div class="collapse" id="country-state-city">
                        <ul class="nav flex-column sub-menu">
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.new_country_state_city.country')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add New Country</span></a></li>
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.new_country_state_city.state')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add New State</span></a></li>
                            <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.new_country_state_city.city')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Add New City</span></a></li>

                        </ul>
                    </div>
                </li>
                @endif
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{route('admin.contact.contactus')}}" aria-expanded="false">
                            <i class="fa fa-align-left" aria-hidden="true"></i>
                            <span class="hide-menu">Contact Us</span>
                        </a>
                    </li>
                @if ($user->hasModule('settings') == true)
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" data-toggle="collapse" href="#setting" aria-expanded="false"  aria-controls="general-pages">
                            <i class="fa fa-cog" aria-hidden="true"></i>
                            <span class="hide-menu" style="padding-left: 5px;">Settings</span><i class="fa fa-caret-down"></i>
                        </a>
                        <div class="collapse" id="setting">
                            <ul class="nav flex-column sub-menu">
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.systemsetting.index')}}"><i class="fa fa-table" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">System Setting</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.frontendsetting.index')}}"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Frontend Setting</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.mapsetting.index')}}"><i class="fa fa-globe" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Map Setting</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.smtpsetting.index')}}"><i class="fa fa-envelope" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">SMTP Setting</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.currency.index')}}"><i class="fas fa-puzzle-piece" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Currency Setting</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.instruction.index')}}"><i class="fas fa-file-alt" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Instruction</span></a></li>
                                <li class="nav-item" style="padding-left: 30px;"> <a class="nav-link" style="color:#54667a;" href="{{route('admin.distance.index')}}"><i class="fas fa-map-signs" aria-hidden="true"></i><span class="hide-menu" style="padding-left: 10px;">Distance Setting</span></a></li>
                            </ul>
                        </div>
                    </li>
                @endif

                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="{{ route('adminLogout') }}" aria-expanded="false">
                            <i class="fa fa-plane" aria-hidden="true"></i>
                            <span class="hide-menu">Logout</span>
                        </a>
                    </li>
            </ul>

        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<style type="text/css">
    .active { background: rgba(0,0,0,0.07); }
    .sidebar_notification {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 21px;
        padding: 2px 6px;
        margin-left: 8px;
        border-radius: 999px;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #ffffff;
        font-size: 10px;
        font-weight: 700;
        line-height: 1;
        box-shadow: 0 4px 10px rgba(220, 38, 38, 0.35);
        vertical-align: middle;
        position: relative;
        top: -8px;
    }
</style>
