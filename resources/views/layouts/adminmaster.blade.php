@php $user = auth()->guard('webadmin')->user(); @endphp
<!DOCTYPE html>
<html dir="ltr" lang="en">
    @include('includes.adminhead')
    
        <body>
            <x-header/>
            <x-sidebar/>
                @yield('content')
            <x-footer/>
        </body>
    
</html>