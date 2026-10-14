<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('includes.client.head')
   
    <body>
        <x-client.commonheader/>
        
          @yield('content')
        <x-CClientfooter/>
    </body>
    
</html>