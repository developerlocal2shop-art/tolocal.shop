<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('includes.client.head')
    <body>
        <x-Clientheader/>
          @yield('content')
        <x-Clientfooter/>
    </body>
</html>