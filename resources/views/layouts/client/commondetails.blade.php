<!DOCTYPE html>
<html lang="en">
    
    @include('includes.client.head')

    <body>
    <div id="page">
        <!-- Header -->
        <header class="header menu_fixed">

        <div class="google_translate_mobile">
            
            <b id="google_translate_element_mobile"></b>
        </div>
        <div id="logo">
            
            <a href="{{ URL::to('/') }}" >
                
                <img src="{{ asset('assets/client/images/light_logo.png') }}" width="165" height="35" alt="" class="logo_normal">
                <img src="{{ asset('assets/client/images/dark_logo.png') }}" width="165" height="35" alt="" class="logo_sticky"> 
            </a>
        </div> 
       
        <x-ClientmenuListingDetail :mainDirectoryDetails="[]"/>
        </header>

          @yield('content')
          @include('layouts.client.language_popup')

          <x-Clientfooter/>
    </body>
    
</html>