    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-layout="vertical" data-navbarbg="skin5" data-sidebartype="full" data-sidebar-position="absolute" data-header-position="absolute" data-boxed-layout="full">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar" data-navbarbg="skin5">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header" data-logobg="skin6">
                    <!-- ============================================================== -->
                    <!-- Logo -->
                    <!-- ============================================================== -->
                    <a class="navbar-brand" href="{{ route('pages') }}" target="_blank">
                        <!-- Logo icon -->
                        <b class="logo-icon">
                            <!-- Dark Logo icon -->
                            <img src="{{ asset('assets/images/dark_logo.webp') }}" width="165" height="35" alt="homepage" />
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text -->
                        <!-- <span class="logo-text">
                            <img src="{{ asset('assets/plugins/images/logo-text.png') }}" alt="homepage" />
                        </span> -->
                    </a>
                    <!-- ============================================================== -->
                    <!-- End Logo -->
                    <!-- ============================================================== -->
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <a class="nav-toggler waves-effect waves-light text-dark d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">

                    <!-- ============================================================== -->
                    <!-- Right side toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav ms-auto d-flex align-items-center">

                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <?php /* ?><li class=" in">
                            <form role="search" class="app-search d-none d-md-block me-3">
                                <input type="text" placeholder="Search..." class="form-control mt-0">
                                <a href="" class="active">
                                    <i class="fa fa-search"></i>
                                </a>
                            </form>
                        </li><?php */ ?>

                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i>
                                @if(auth()->guard('webadmin')->user()->unreadNotifications->count() != 0)
                                <span class="label label-warning"> {{auth()->guard('webadmin')->user()->unreadNotifications->count()}}</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                @if($notificationRequest)
                                @foreach ($notificationRequest as $notification)
                                @if($notification->read_at == null)
                                <li>
                                    <div class="dropdown-messages-box">

                                        <div class="media-body">

                                            <small class="float-right">
                                               <h4>{{ucwords(Helper::getUserById($notification->data['sender_id'])->name)}}</h4>
                                                @if($notification->created_at->diffInSeconds(Carbon\Carbon::now()) < 60)
                                                {{$notification->created_at->diffInSeconds(Carbon\Carbon::now())}} Seconds Ago
                                                @endif
                                            </small>


                                                    <strong>
                                                        <strong>{{ucwords(Helper::getUserById($notification->data['sender_id'])->name)}}</strong>
                                                        {{$notification->data['message']}}
                                                    </strong>.
                                                    <br>
                                                    <small class="text-muted">
                                                        @if($notification->created_at->diffInHours(Carbon\Carbon::now()) >= 1 && $notification->created_at->diffInHours(Carbon\Carbon::now()) < 24) {{$notification->created_at->diffInHours(Carbon\Carbon::now())}} Hours Ago @endif
                                                    </small>
                                                    <p>

                                                        @if($notification->created_at->diffInHours(Carbon\Carbon::now()) > 24 ) {{$notification->created_at->diffInDays(Carbon\Carbon::now())}} Days Ago
                                                        @endif

                                                    </p>
                                        </div>

                                    </div>
                                </li>
                                <li class="dropdown-divider"></li>
                                @endif
                                @endforeach

                                @if(auth()->guard('webadmin')->user()->unreadNotifications->count() != 0)
                                <a href="{{ route('admin.notifications') }}">
                                    View Notifications
                                </a>
                                @endif
                                @endif

                            </ul>
                        </li>

                        <li>
                            <a class="profile-pic" href="{{ route('admin.editdetails') }}">
                                <img src="{{ env('IMAGE_PATH').'files/admin/'.$adminDetail->image_name }}" alt="user-img" width="36" class="img-circle">
                                <span class="text-white font-medium">Welcome, {{auth()->guard('webadmin')->user()->name;}}</span>
                            </a>
                            
                            
                        </li>
                        <!-- ============================================================== -->
                        <!-- User profile and search -->
                        <!-- ============================================================== -->
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
<style type="text/css">
ul.dropdown-menu.dropdown-messages.show {
    padding: 10px 5px;
}
</style>
