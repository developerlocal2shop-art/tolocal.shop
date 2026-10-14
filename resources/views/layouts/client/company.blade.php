<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @include('includes.client.head')

    <style>
      .sub_header_in {
          background-color: #2f4a7c !important;
      }
      ul.comp-header li.list-group-item {
          padding-left: 3px;
          padding-right: 3px;
          padding-top: 2px;
          padding-bottom: 2px;
      }
    </style>

    <body>
        <x-client.commonheader/>

          @yield('content')
        <x-client.footer/>


    </body>

</html>
