<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="wrappixel, admin dashboard, html css dashboard, web dashboard, bootstrap 5 admin, bootstrap 5, css3 dashboard, bootstrap 5 dashboard, Ample lite admin bootstrap 5 dashboard, frontend, responsive bootstrap 5 admin template, Ample admin lite dashboard bootstrap 5 dashboard template">
    <meta name="description" content="Ample Admin Lite is powerful and clean admin dashboard template, inpired from Bootstrap Framework">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title')</title>
    <link rel="canonical" href="https://www.wrappixel.com/templates/ample-admin-lite/" />
    <!-- Favicon icon -->

    <link rel="shortcut icon" href="{{ asset('assets/client/images/favicon.png') }}" type="image/x-icon">
    <!-- Custom CSS -->

    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/css/style.min.css') }}" rel="stylesheet">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <link href="{{ URL::asset('assets/css/select2.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/css/custom.css') }}" rel="stylesheet">

    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
   <!-- Toastr -->
    <link href="{{ asset('assets/client/css/toastr.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="{{ asset('assets/css/daterangepicker.css') }}" />



    <style>
        #main-wrapper[data-layout=horizontal] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul, #main-wrapper[data-layout=vertical] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul {
            background: #313f57 !important;
            color: #fff;
        }
        .btn.btn-info {
            background: #2f4a7c;
        }
        #main-wrapper[data-layout=horizontal] .topbar .navbar-collapse[data-navbarbg=skin5], #main-wrapper[data-layout=horizontal] .topbar[data-navbarbg=skin5], #main-wrapper[data-layout=vertical] .topbar .navbar-collapse[data-navbarbg=skin5], #main-wrapper[data-layout=vertical] .topbar[data-navbarbg=skin5] {
            background: #313f57;
            padding: 2px 0px;
        }
        .btn-primary {
            background-color: #1f54b5;
        }
        .scroll-sidebar {
            background: #313f57;
        }
        .sidebar-item .active {
            background: #163365 !important;
        }
        #main-wrapper[data-layout=horizontal] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul .sidebar-item .sidebar-link, #main-wrapper[data-layout=vertical] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul .sidebar-item .sidebar-link {
            color: #fff;
        }

        #main-wrapper[data-layout=horizontal] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul .sidebar-item .sidebar-link i, #main-wrapper[data-layout=vertical] .left-sidebar[data-sidebarbg=skin6] .sidebar-nav ul .sidebar-item .sidebar-link i {
            color: #fff;
        }

        span.hide-menu {
            color: #fff;
        }
        .topbar .top-navbar .navbar-header .navbar-brand {
            background: #e6e6e6;
        }

        .fa {
            color: #fff;
        }

        span.counter {
            font-size: 20px;
            font-weight: bold;
            float: right;
        }

        .analytics-info form {
            display: inline-block;
            float: right;
        }

        .analytics-info .list-inline {
            padding-left: 0;
            margin-left: -5px;
            list-style: none;
            display: inline-block;
            width: 100%;
        }

        .list-inline li {
            display: inline-block;
            vertical-align: top;
            width: 32%;
        }




        .imageuploadify i {
            font-size: 40px;
        }

        span.imageuploadify-message {
            font-size: 15px;
            border-top: 1px solid #3AA0FF;
            border-bottom: 1px solid #3AA0FF;
            padding: 5px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .imageuploadify {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .imageuploadify .btn {
            display: block;
            color: #3AA0FF;
            border-color: #3AA0FF;
            border-radius: 1em;
            margin: 0;
            width: 100%;
        }

        .imageuploadify input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .imageuploadify {
            border: 2px dashed #d2d2d2;
            position: relative;
            min-width: 250px;
            max-width: 1000px;
            margin: auto;
            display: flex;
            flex-direction: column;
            text-align: center;
            background-color: #fff;
            color: #3aa0ff;
            padding: 20px;
        }

        .imageuploadify-overlay {
            z-index: 10;
            width: 100%;
            height: 100%;
            position: absolute;
            flex-direction: column;
            top: 0;
            left: 0;
            display: none;
            font-size: 7em;
            background-color: rgba(242, 242, 242, 0.7);
            text-align: center;
            pointer-events: none;
        }

        .imageuploadify-overlay i {
            z-index: 10;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .imageuploadify-overlay2 {
            z-index: 10;
            width: 100%;
            height: 100%;
            position: absolute;
            flex-direction: column;
            top: 0;
            left: 0;
            display: none;
            font-size: 7em;
            background-color: rgba(242, 242, 242, 0.7);
            text-align: center;
            pointer-events: none;
        }

        .imageuploadify-overlay2 i {
            z-index: 10;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .imageuploadify-overlay3 {
            z-index: 10;
            width: 100%;
            height: 100%;
            position: absolute;
            flex-direction: column;
            top: 0;
            left: 0;
            display: none;
            font-size: 7em;
            background-color: rgba(242, 242, 242, 0.7);
            text-align: center;
            pointer-events: none;
        }

        .imageuploadify-overlay3 i {
            z-index: 10;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }


        .imageuploadify .imageuploadify-images-list {
            display: inline-block;
        }

        .imageuploadify .imageuploadify-images-list i {
            display: block;
            font-size: 7em;
            text-align: center;
            margin-top: 0.5em;
            padding-bottom: 12px;
        }

        .imageuploadify .imageuploadify-images-list span.imageuploadify-message {
            font-size: 24px;
            border-top: 1px solid #3aa0ff;
            border-bottom: 1px solid #3aa0ff;
            padding: 10px;
            display: inline-block;
        }

        .imageuploadify .imageuploadify-images-list button.btn-default {
            display: block;
            color: #3aa0ff;
            border-color: #3aa0ff;
            border-radius: 1em;
            margin: 25px auto;
            width: 100%;
            max-width: 500px;
        }

        .imageuploadify .imageuploadify-images-list .imageuploadify-container {
            width: 100px;
            height: 100px;
            position: relative;
            overflow: hidden;
            margin-bottom: 1em;
            float: left;
            border-radius: 12px;
            box-shadow: 0 0 4px 0 #888;
        }

        .imageuploadify .imageuploadify-images-list .imageuploadify-container button.btn-danger {
            position: absolute;
            top: 3px;
            right: 3px;
            width: 20px;
            height: 20px;
            border-radius: 15px;
            font-size: 10px;
            line-height: 1.42;
            padding: 2px 0;
            text-align: center;
            z-index: 3;
        }

        .imageuploadify .imageuploadify-images-list .imageuploadify-container img {
            height: 100px;
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, -50%);
            width: auto;
        }

        .imageuploadify .imageuploadify-images-list .imageuploadify-container .imageuploadify-details {
            position: absolute;
            top: 0;
            padding-top: 20px;
            width: 100%;
            height: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.5);
            z-index: 2;
            opacity: 0;
        }

        .imageuploadify .imageuploadify-images-list .imageuploadify-container .imageuploadify-details span {
            display: block;
        }

        .AJloader {
            position: fixed;
            width: 100%;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.4);
            z-index: 9999;
            display: none;
        }

        #loader {
            position: fixed;
            left: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            z-index: 9999;
            background: url("{{ asset('assets/client/images/loader.gif') }}") 50% 50% no-repeat rgb(249, 249, 249);
            background-size: 100px;
        }

    </style>


</head>
