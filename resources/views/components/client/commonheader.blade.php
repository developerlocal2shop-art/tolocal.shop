<div id="page">
    <header class="header_in is_sticky menu_fixed">
        <div class="container-fluid" style="padding: 0px 25px;">

            <div class="google_translate_mobile">
                <b id="google_translate_element_mobile"></b>
            </div>

            <div id="logo" class="logo_wrapper">
                <a href="{{ URL::to('/') }}">
                <span class="company_details_logo_head">
                    <img src="{{ asset('assets/images/adobe_icon.png') }}" height="auto" alt="" class="company_details_logo_img">
                </span>
                    <img src="{{ URL::asset('assets/client/images/dark_logo.webp') }}" width="165" height="35" alt="" class="logo_sticky setr">
                </a>
                <p id="city_name" class="logo_city_name"> {{ session()->get('filter_city') }}</p>
            </div>

            <x-Clientmenu />
        </div>
    </header>
    <form id="latlongform" action="{{url('/')}}" enctype="multipart/form-data" method="get">
        @csrf
        <input type="hidden" id="lati" name="lati" >
        <input type="hidden" id="long" name="long" >
    </form>
 <style>
.main-menu ul li.nav-item a.nav-link.dropdown-toggle{color:#444445}nav#menu.main-menu{margin:10px 5px 0 0}.logo_wrapper{display:flex;flex-direction:column;align-items:flex-start}.logo_head_a{display:flex;align-items:center;gap:8px}.logo_city_name{margin:0 0 10px 78px;color:#dc3545;font-size:32px;font-weight:600;line-height:0;letter-spacing:1px;font-family:emoji}header.sticky .logo_city_name{color:#444}p#staticBackdropLabel{color:#040303;font-weight:400;font-size:16px!important}.modal-dialog{max-width:500px}header.is_sticky.mm-slideout.header_s{position:absolute}span.company_details_logo_head img{width:61px;height:auto;margin:4px 0 -10px 10px;background:#fff;border-radius:25%;border:1px solid #1f3864;padding:1px}header.header_in.is_sticky{padding:3px 0 10px}div#staticBackdrop{margin:100px auto;max-width:275px;background-color:transparent;border-radius:26px;backdrop-filter:blur(55px);justify-content:center;align-items:center;height:125px;padding:1px 20px}#staticBackdrop .goog-te-gadget-simple{background-color:#fff;border-left:1px solid #d5d5d5;border-top:1px solid #9b9b9b;border-bottom:1px solid #e8e8e8;border-right:1px solid #d5d5d5;font-size:10pt;display:inline-block;padding-top:1px;padding-bottom:2px;cursor:pointer;text-align:center}.modal-content{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;pointer-events:auto;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0;background-color:transparent;color:#fff;padding:1px 4px}.google_translate_element_home .goog-te-gadget-simple{width:100%}.pop_home_btn{cursor:pointer}@media (min-width:1700px) and (max-width:2600px){.logo_city_name{margin:1px 0 0 76px;font-size:35px;letter-spacing:2px}.main-menu>ul>li span>a{font-size:20px}.container{max-width:1600px}}@media screen and (max-width:767px){header.header.sticky{padding:35px 0 0 10px}header.header_in.is_sticky{padding:25px 0 10px}#logo{top:0}#logo img{width:auto;height:25px;margin:12px 46px 4px -45px!important}header.sticky .logo_city_name{margin:22px 7px}.logo_city_name{display:unset;font-size:18px;width:auto;padding:9px 26px}header.header_in #logo a{width:100%;z-index:0}.text-center{padding-left:0}span#home_qr_code{float:right;margin:0!important;display:none}.sub_header_in.sticky_header{margin-top:50px}button.rounded-pill{margin:4px 0;font-size:13px;text-align:left;padding:4px 5px!important}.google_translate_element_home{display:block!important}div#staticBackdrop{margin:75px auto;max-height:215px;padding:1rem}span.company_details_logo_head img{float:none!important}.google_translate_mobile{float:right;margin:0}iframe{height:193px!important}header.header{padding:10px 10px 0}.goog-te-gadget-simple span{display:inherit!important}.goog-te-gadget-simple{width:100px}}
</style>

