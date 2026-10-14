<style>

    .customer_account_menu {
        margin: 100px 0px;
    }
    .sticky-sidebar{
        position: sticky;
        top: 90px;      /* Adjust according to your fixed header height */
        align-self: flex-start;
        z-index: 10;
    }
    .sticky-sidebar{
        position: sticky;
        top: 70px;
    }
    .row,
    .container,
    .customer_account_menu{
        overflow: visible;
    }
    @media (max-width:991px){
        .sticky-sidebar{
            position: static;
        }
    }
    .customer-sidebar {
        background: #fff;
        padding: 20px;
        border: 1px solid #e5e5e5;
        border-radius: 6px;
    }

    .menu-title {
        font-size: 18px;
        font-weight: 500;
        margin: 20px 0 10px;
        color: #222;
    }

    .menu-title:first-child {
        margin-top: 0;
    }

    .customer-sidebar .list-group {
        margin-bottom: 25px;
    }

    .customer-sidebar .list-group-item {
        border: 0;
        padding: 6px 0 6px 20px;
        position: relative;
        background: transparent;
    }

    .customer-sidebar .list-group-item::before {
        content: "➔";
        position: absolute;
        left: 1px;
        color: #000;
        font-size: 15px;
        line-height: 1;
        top: 15px;
    }

    .customer-sidebar .list-group-item a {
        color: #222;
        text-decoration: none;
        font-size: 15px;
    }

    .customer-sidebar .list-group-item.activemenu a {
        color: #e91e63;
        font-weight: 600;
    }

    .customer-sidebar .list-group-item:hover a {
        color: #e91e63;
    }



    .customer_account_menu {
        position: sticky;
        top: 5px;
    }

    .customer_account_menu .list-group {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin: 10px 5px 0px 5px !important;
    }

    .customer_account_menu .list-group-item {
        border: none;
        padding: 10px 20px;
        font-size: 15px;
        font-weight: 400;
        background: #fff;
        transition: all 0.3s ease;
        margin: 0px 5px 5px 10px;
    }

    .customer_account_menu .list-group-item a {
        text-decoration: none;
        color: #444;
        display: block;
        width: 100%;
    }

    /* Hover effect */
    .customer_account_menu .list-group-item:hover {
        background: #f5f7fb;
        padding-left: 22px;
    }

    .customer_account_menu .list-group-item:hover a {
        color: #007bff;
    }

    /* Active menu */
    .customer_account_menu .activemenu {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: #fff;
        border-left: 4px solid #003f7f;
    }

    .customer_account_menu .activemenu a {
        color: #fff;
    }

    /* Optional: add divider spacing */
    .customer_account_menu .list-group-item:not(:last-child) {
        border-bottom: 1px solid #f1f1f1;
    }



    .profile-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .text-muted {
            margin-top: 5px;
        }
        .sub_header_in {
            background-color: #c39922 !important;
        }
        .btn-primary {
            color: #fff;
            background-color: #c39922;
            border-color: #c39922;
        }
        .col-lg-3.sub_header_in2.sticky_header {
            background: #fff;
        }
        .profile-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            position: relative;
        }
        .list-group-item {
            border-bottom: 1px solid rgba(0,0,0,.125) !important;
            border-left: none;
            border-right: none;
            border-top: none;
            margin: 0px 15px;
        }
        .profile-img-container {
            position: relative;
            display: inline-block;
        }
        .camera-icon {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            padding: 5px;
            font-size: 14px;
            cursor: pointer;
            border: 2px solid white;
        }
        .admin-badge {
            background: #e83e8c;
            color: white;
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 20px;
            margin-top: 5px;
        }
        .logout-btn {
            width: 100%;
            font-weight: bold;
        }
        .info-box {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
        .info-box a {
            text-decoration: none;
            color: black;
            font-weight: 500;
        }
        .info-box a span {
            font-weight: bold;
        }
        .notification-icon {
            color: #dc3545;
        }
        .message-icon {
            color: #198754;
        }
        .sub_header_in.sticky_header {
            margin: 90px 0px 20px -20px;
            padding: 6px 4px 10px 0px;
        }
    .activemenu {
        background: #e6e6ef !important;
    }
    li.list-group-item a {
        color: #100f0fd1;
    }
    .sub_header_in h1 {
        color: #fff;
        margin: 1px;
        font-size: 22px;
        padding: 0px 15px;
    }
    .sub_header_in2.sticky_header {
        margin-top: 70px;
        padding: 15px 0px 100px 15px;
    }
    .filter_sub_blue_header.newClass {
        position: fixed;
        z-index: 6;
        width: 100%;
        margin: -15px;
        top: 0px;
        display: block;
        padding: 20px 205px 5px 0px;
    }

    /* Mobile responsive */

    @media (max-width: 768px) {
        .user_que_ans {
            margin-bottom: 20px;
            margin-right: 13px;
            width: auto;
            font-size: 18px;
        }
        .user_que_ans p {
            font-size: 14px;
        }
        .prize_status_for_store {
            flex-direction: column;
            align-items: stretch;
            font-size: 15px;
        }
        .user_competition_list .filter_sub_blue_header h1 {
            font-size: 18px;
            text-align: center;
        }
        .profile_data h4 {
            font-size: 18px;
            border-bottom: 1px solid #ccc;
        }
        .seller_heading {
            font-size: 15px;
        }
        .user_competition_list .filter_sub_blue_header h1 {
            color: #616161;
        }
        .sub_header_in h1 {
            color: #6c4343;
        }
        .customer_account_menu {
            margin: 0px 5px;
        }
        .my_account_toggle {
            padding: 5px 0px !important;
            margin: 75px 5px;
            width: 139px !important;
        }
        .list-group {
            padding: 7px 10px 2px 0px;
        }
         .customer_account_menu {
            position: relative;
            top: 0;
        }
        .customer-sidebar .list-group-item::before {
            top: 10px;
        }
        .customer-sidebar {
            padding: 10px;
        }
        .sub_header_in2.sticky_header {
            margin-top: 60px;
            padding: 15px 5px 8px 5px;
        }
        .menu-title {
            font-size: 15px;
            margin: 10px 0 0px;
            color: #222;
        }
        .customer_account_menu .list-group-item {
            padding: 5px 15px;
            font-size: 15px;
            font-weight: 400;
            margin: 0px 5px 5px 10px;
        }
        .sub_header_in.sticky_header {
            margin: 2px -10px !important;
            padding: 2px 0px 2px 0px;
            background: #f6f6f6 !important;
            width: auto;
        }
        .logout-btn {
            width: 25%;
            font-weight: normal;
            font-size: 16px;
            padding: 1px;
        }
        .profile-card {
            padding: 7px;
        }
        .admin-badge {
            padding: 1px 10px;
            font-size: 14px;
        }
    }
</style>


<div class="col-lg-3 sub_header_in2">
    <button class="btn btn-primary d-lg-none w-100 mb-3 my_account_toggle"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#customerSidebar"
            aria-expanded="false"
            aria-controls="customerSidebar">
        ☰ My Account
    </button>
    <div id="customerSidebar" class="collapse d-lg-block customer_account_menu customer-sidebar">

        {{-- Competition --}}
        <h5 class="menu-title">Competitions and Offers</h5>
        <ul class="list-group mb-4">
            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/questionnaire') activemenu @endif">
                <a href="{{ route('user.questionnaire') }}">Local Competitions</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/shopping-valid-offer') activemenu @endif">
                <a href="{{ url('account/shopping-valid-offer') }}">Shopping Card Offer</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/userprizes') activemenu @endif">
                <a href="{{ route('user.prizes') }}">My Prizes</a>
            </li>

        </ul>

        {{-- Orders & Favourites --}}
        <h5 class="menu-title">Orders and Favourites</h5>
        <ul class="list-group mb-4">
            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/order') activemenu @endif">
                <a href="{{ url('account/order') }}">My Orders</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/wishlist-product') activemenu @endif">
                <a href="{{ url('account/wishlist-product') }}">My Wishlist Products</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/wishlist-directory') activemenu @endif">
                <a href="{{ url('account/wishlist-directory') }}">My Featured Companies</a>
            </li>
        </ul>

        {{-- My Details --}}
        <h5 class="menu-title">My Details</h5>
        <ul class="list-group">
            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account') activemenu @endif">
                <a href="{{ url('account') }}">Profile</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/reset-password') activemenu @endif">
                <a href="{{ url('account/reset-password') }}">Change Password</a>
            </li>

            <li class="list-group-item @if(trim($_SERVER['REQUEST_URI'],'/') == 'account/address') activemenu @endif">
                <a href="{{ url('account/address') }}">Address</a>
            </li>

            <li class="list-group-item @if(request()->routeIs('affiliate.dashboard')) activemenu @endif">
                <a href="{{ route('affiliate.dashboard') }}">Affiliate Dashboard</a>
            </li>
        </ul>

        @if(Auth::user()->role_id == 1)
            <h5 class="menu-title mt-4">Vendor</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="{{ url('company/reports/dashboard') }}">Vendor Dashboard</a>
                </li>
            </ul>
        @endif

    </div>
</div>




{{-- <div class="col-lg-3 sub_header_in2 sticky_header customer_account_menu">
    <div class="">
        <ul class="list-group">
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account') activemenu @endif">
            <a href="{{ url('account') }}">Profile</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/reset-password') activemenu @endif">
               <a href="{{ url('account/reset-password') }}">Change Password</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/address') activemenu @endif">
                <a href="{{ url('account/address') }}">Address</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/order') activemenu @endif">
                <a href="{{ url('account/order') }}">My Orders</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/wishlist-product') activemenu @endif">
                <a href="{{ url('account/wishlist-product') }}">Wishlist Featured Products</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/wishlist-directory') activemenu @endif">
                 <a href="{{ url('account/wishlist-directory') }}">Featured Companies </a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/questionnaire') activemenu @endif">
                <a href="{{ route('user.questionnaire') }}">Current Competitions</a>
            </li>
            <li class="list-group-item @if(trim($_SERVER["REQUEST_URI"],"/") == 'account/userprizes') activemenu @endif">
                <a href="{{ route('user.prizes') }}">Prizes to Use</a>
            </li>
            @if(Auth::user()->role_id == 1)
            <li class="list-group-item">
            <a href="{{ url('company/reports/dashboard') }}">Vendor Dashboard</a>
            </li>
            @endif

        </ul>

    </div>
</div> --}}
