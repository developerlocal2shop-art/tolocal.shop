<head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>@yield('title')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (app()->environment('production'))
        <meta name="google-analytics-id" content="{{ env('GOOGLE_ANALYTICS_ID') }}">
    @endif
        <script src="{{ asset('js/cookie-consent.js') }}"></script>
    <?php   $currenturl = url()->current();	$currenturl = explode("/",$currenturl);	$arrendvalue = end($currenturl); ?>
    @if (isset($metadata) && !empty($metadata))
        <?php $imagename = Helper::getProductImage($metadata->module_id); ?>
        <meta name="twitter:card" content="summary_large_image" />
        @php  $name_thumbnail = count($imagename) > 0 ? $imagename[0]->name_thumbnail : ''; @endphp
        <?php if($metadata->page_type=="product"){ ?>  <meta property="og:image" content="{{ env('IMAGE_PATH').'images/product/'.$name_thumbnail.'.webp' }}"/> <?php } ?>
        <meta property="og:title" content="{!! $metadata->meta_keyword??'Meta title' !!}" />
        <meta name="twitter:title" content="{!! $metadata->meta_keyword??'Meta title' !!}" />
        <meta name="description" content="{!! $metadata->meta_description??'Meta description' !!}">
        <meta property="og:description" content="{!! $metadata->meta_description??'Meta description' !!}" />
        <meta name="twitter:description" content="{!! $metadata->meta_description??'Meta description' !!}" />
        <meta name="keywords" content="{!! $metadata->meta_keyword??'Meta keywords' !!}">
    @endif
        <meta name="robots" content="index, follow">
        <link rel="shortcut icon" href="{{ asset('assets/client/images/favicon.png') }}" type="image/png">
        <link rel="apple-touch-icon" type="image/x-icon" href="{{ asset('asset/client/images/apple-touch-icon-57x57-precomposed.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{ asset('asset/client/images/apple-touch-icon-72x72-precomposed.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{ asset('asset/client/images/apple-touch-icon-114x114-precomposed.png') }}">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{ asset('asset/client/images/apple-touch-icon-144x144-precomposed.png') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/css/slick.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/client/css/slick-theme.css') }}">
        <link href="{{ asset('assets/client/css/icons/font-awesome/css/fontawesome-all.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- GOOGLE WEB FONT -->
        <!-- BASE CSS -->
        <link href="{{ asset('assets/client/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/vendors.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/style2.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/responsive2.css') }}" rel="stylesheet">
        <link href="{{ URL::asset('assets/css/select2.css') }}" rel="stylesheet">
        <!-- YOUR CUSTOM CSS -->
        <link rel="stylesheet" href="{{ asset('assets/client/css/custom.css') }}">
        @if (request()->routeIs('listing.details') || request()->routeIs('directory.product'))
        <link rel="stylesheet" href="{{ asset('assets/client/css/company_detail_shop.css') }}">
        @endif
        <link rel="stylesheet" href="{{ URL::asset('assets/css/fontawesome-iconpicker.min.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.datetimepicker.css') }}" />
        <link rel='stylesheet' href="{{ asset('assets/client/css/owl.carousel.min.css') }}">
        <link rel='stylesheet' href="{{ asset('assets/client/css/owl.theme.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/client/css/bootstrap-toggle.min.css') }}" >
        <link rel='stylesheet' href="{{ asset('assets/client/css/jquery-ui.css') }}">
        <script src="{{ asset('assets/plugins/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <?php if(!in_array("listings", $currenturl)){?>
        <link rel='stylesheet' href="{{ asset('assets/client/css/lightbox.min.css') }}">
        <link href="{{ asset('assets/client/css/tables.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/client/css/toastr.min.css') }}" rel="stylesheet">
        <!-- SPECIFIC CSS -->
        <link href="{{ asset('assets/client/css/blog.css') }}" rel="stylesheet">
        <!-- Timepicker custom css -->
        <link href="{{ asset('assets/client/css/gijgo.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/jquery.exzoom.css') }}" rel="stylesheet" type="text/css" />
        <script src="{{ asset('assets/client/js/jquery.validate.min.js') }}" @if(Request::is('/')) defer @endif></script>
        <script src="{{ asset('assets/js/jquery.combostars.js') }}" @if(Request::is('/')) defer @endif></script>
        <script src="{{ asset('assets/client/js/axios.min.js') }}"  crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <?php } ?>
        <link href="{{ asset('assets/css/jquery.scrollbar.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ asset('assets/css/aos.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/custom_head.css') }}" />

        <div id="fb-root"></div>
</head>
