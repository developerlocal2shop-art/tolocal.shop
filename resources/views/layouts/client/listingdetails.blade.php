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
                <div id="home_qr_code" class="home_qr_code">
                    {!! QrCode::size(70)->color(50, 0, 130)->generate(url('listings/details', ['id' => $data['mainDirectoryDetails']->slug])) !!}
                </div>
            </div>
            <x-ClientmenuListingDetail :mainDirectoryDetails="$data['mainDirectoryDetails']"/>
        </header>
          @yield('content')
          @include('layouts.client.language_popup')
          <x-CClientfooter/>
    </body>

</html>
