<!DOCTYPE html>
<html lang="en">
    
    @include('includes.client.head')

    <body>
    <div id="page">
        <!-- Header -->
        <header class="header menu_fixed">

        <div class="google_translate_mobile">
            {{-- @if ($data['mainDirectoryDetails'])
            <h2 class="notranslate company_d_title_mobile">{{ $data['mainDirectoryDetails']->title }}</h2>
            @endif --}}

            <b id="google_translate_element_mobile"></b>
        </div>
        <div id="logo">
            <div id="home_qr_code" class="home_qr_code">
                {!! QrCode::size(70)->color(50, 0, 130)->generate(url('listings/details', ['id' => $data['mainDirectoryDetails']->slug])) !!} 
                
            </div>
            {{-- <a href="{{ URL::to('/') }}" title="{{ $data['mainDirectoryDetails']->title??'' }}">
                @if (!empty($data['mainDirectoryDetails']->company_logo))
                <img src="{{env('IMAGE_PATH').'/'.$data['mainDirectoryDetails']->company_logo }}" width="auto" height="auto" alt="" class="logo_normal">
                <img src="{{env('IMAGE_PATH').'/'.$data['mainDirectoryDetails']->company_logo }}" width="auto" height="auto" alt="" class="logo_sticky">
                @else
                <img src="{{ asset('assets/images/comp.jpg') }}" width="120" height="65" alt="" class="logo_normal">
                <img src="{{ asset('assets/images/comp.jpg') }}" width="120" height="65" alt="" class="logo_sticky">
                @endif
                
            </a> --}}
        </div> 
       
        <x-ClientmenuListingDetail :mainDirectoryDetails="$data['mainDirectoryDetails']"/>
        </header>

          @yield('content')
          @include('layouts.client.language_popup')

          <x-CClientfooter/>
    </body>
    
</html>