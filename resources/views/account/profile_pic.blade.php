<div class="row justify-content-center">
    <div class="col-md-12">
        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-img-container">
                @if(Auth::user()->profile_pic)
                <img src="{{ env('IMAGE_PATH').'images/'.Auth::user()->profile_pic }}" alt="Member Image" class="profile-img">
                @else
                <img src="{{ url('/assets/images/faces/face21.jpg') }}" alt="Member Image" class="profile-img">
                @endif
            </div>
            <h4 class="mt-2">{{ Auth::user()->name??'' }} {{ Auth::user()->last_name??'' }}</h4>
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

    </div>
</div>