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
        </div> 

        <x-ClientmenuProDetail :mainProductDetails="$data['productDetails']"/>
        </header>

          @yield('content')
          @include('layouts.client.language_popup')

          <x-CClientfooter/>
    </body>
    
</html>