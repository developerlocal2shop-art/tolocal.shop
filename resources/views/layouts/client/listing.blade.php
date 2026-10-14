<!DOCTYPE html>
<html lang="en">
    @include('includes.client.head')
   
    <body>
        <x-client.listingheader/>
          @yield('content')
          <x-Clientfooter/>
    </body>
    
</html>