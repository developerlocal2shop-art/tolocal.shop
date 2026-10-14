@extends('layouts.client.listingdetails')
@if (isset($metadata) && !empty($metadata))
    @if (!empty($metadata->meta_title))
    @section('title', $metadata->meta_title.' - Company')
    @else
    @section('title', $data['mainDirectoryDetails']->title)
    @endif
@else
    @section('title', $data['mainDirectoryDetails']->title)
@endif

@section('content')


@if ($data['mainDirectoryDetails']->company_color)
<style>
    .btn-custom {
        color: #fff;
        border-radius: 5px;
        font-weight: bold;
        background-color: {{ $data['mainDirectoryDetails']->company_color }};
    }
    .mm-slideout {
        padding: 1px !important;
        background: {{ $data['mainDirectoryDetails']->company_color??'#e0a800' }};

    }
    header.header_in .main-menu > ul > li span > a, header.sticky .main-menu > ul > li span > a{
        color: #fff !important;
    }
    span.locally_text {
        color: #fff !important;
    }
    .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed span, b#google_translate_element div.goog-te-gadget-simple a.goog-te-menu-value:hover {
        color: #000;
    }
</style>
@else
<style>
    .btn-custom {
        background-color: #004dda;
        color: #fff;
        border-radius: 5px;
        font-weight: bold;
    }

</style>
@endif

<div class="loader" id="loader"></div>
<div class="AJloader" id="AJloader"></div>
<?php

    $useragent=$_SERVER['HTTP_USER_AGENT'];
    $ismobiledevice = 0;
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
    {
        $ismobiledevice = 1;
    }
    $user_ip = getenv('REMOTE_ADDR');
    $geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);

    if ($user_ip == '127.0.0.1' || $user_ip == '::1' || $user_ip == 'localhost') {
        $timezone = 'Asia/Kolkata';
    } elseif ($geo && isset($geo["timezone"])) {
        $timezone = $geo["timezone"];
    } else {
        // fallback if API fails or timezone not found
        //$timezone = 'UTC';
        $timezone = 'Europe/Stockholm';
    }

    if($user_ip=='127.0.0.1' || $user_ip=='::1' || $user_ip=='localhost' ){
        $countryCode    = 'IN';
        $state          = 'National Capital Territory of Delhi';
        $state_array    = explode(" ",$state);
        $city           = 'Delhi';
    }else{
        $countryCode    = $geo["countryCode"]??'';
        $state          = $geo["regionName"]??'';
        $state_array    = explode(" ",$state);
        $city           = $geo["city"]??'';
    }

    if(in_array("Capital", $state_array)){
        $state = $city;
    }else{
        $state = $state;
    }

    date_default_timezone_set($timezone);
    $info = getdate();

    $date = $info['mday'];
    $month = $info['mon'];
    $year = $info['year'];
    $hour = $info['hours'];
    $min = $info['minutes'];
    $sec = $info['seconds'];

    $current_date_time = sprintf('%02d:%02d', $info['hours'], $info['minutes']);

    $currentUrl = url()->current();
    $currentUrl = explode("/",$currentUrl);
    $defaultCurrency = Helper::defaultCurrencyData();
    if(session()->has('currency_code')){
        $currencySymbol = session()->get('currency_code')['currsymbol'];
        $currencycode = session()->get('currency_code')['currcode'];
    }else{
        $currencySymbol = $defaultCurrency[0]->symbol;
        $currencycode = $defaultCurrency[0]->code;
    }
    $bannerImage = isset($data['bannerImage'][0]->name_thumbnail)?$data['bannerImage'][0]->name_thumbnail:"";
    $templateandScheduleDaysData = isset($data['templateandScheduleDaysData'])?$data['templateandScheduleDaysData']:array();

    $wislistProductId=array();
        foreach($wishlists as $wishid){
            $wislistProductId[]=$wishid['product_id'];
        }

    $wislistdirectoryId=array();
    foreach($wishlists_dir as $wishidr){
        $wislistdirectoryId[]=$wishidr->directory_id;
    }
    $serverDay = strtolower(date("D"));
?>

<form id="latlongform" action="{{route('pages')}}" enctype="multipart/form-data" method="get">
    @csrf
    <input type="hidden" id="lati" name="lati" > <input type="hidden" id="long" name="long" >
</form>

<!-- Main page -->
<!-- Public Holiday Modal -->
<div class="modal fade"  id="public_holiday_model" tabindex="-1" role="dialog" style="height: 80%!important; top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Public Holiday or All Exception Holiday</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" tabs-left" id="modal-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                    </a>
                        <li class="nav-item">
                            <a class="active" href="#publicHoliday" data-toggle="tab">Public Holidays</a>
                        </li>
                        <li class="nav-item">
                            <a href="#exceptionholiday" data-toggle="tab">Exception Holidays</a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content" id="tab-content">
                    <div class="tab-pane active" id="publicHoliday">
                        <div class="row">
                            <div class="col-md-12" style="overflow-x: auto;">
                                @if(!empty($data['pubholidayData']))
                                <table>
                                    <tr>
                                        <th class="notranslate" style="padding-right: 25px;">Date</th>
                                        <th class="notranslate" style="padding-right: 25px;">Day</th>
                                        <th class="notranslate" style="padding-right: 25px;">Opening</th>
                                        <th class="notranslate" style="padding-right: 25px;">Closing</th>
                                        <th class="notranslate" style="padding-right: 25px;">Holiday note</th>
                                        {{-- <th class="notranslate" style="padding-right: 25px;">Status</th> --}}
                                    </tr>
                                    <?php
                                    $currentDate = date('Y-m-d');
                                    foreach($data['pubholidayData'] as $publicdata){
                                        if ($publicdata->custom_date >= $currentDate) {
                                    $openpuplictimeholiday =  json_decode($publicdata->custom_time);
                                    ?>
                                    <tr>
                                        <td class="notranslate" style="padding-right: 25px;">{{ $publicdata->custom_date }}</td>
                                        <td style="padding-right: 25px; text-transform: capitalize;">{{ $publicdata->day??'' }}</td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda;">{{ $publicdata->note }}</span></td>
                                        {{-- <td style="padding-right: 30px;"><span style="color: #004dda;">
                                            <b>
                                            @if($publicdata->closed == 1 || $publicdata->closed == 'Yes')
                                                Closed
                                            @else
                                                Open
                                            @endif
                                            </b>
                                        </span>
                                        </td> --}}
                                    </tr>
                                    <?php } } ?>
                                </table>
                                @else
                                <div class="text-center">No Data Found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="exceptionholiday">
                        <div class="row">
                            <div class="col-md-12" style="overflow-x: auto;">
                                @if(!empty($data['execholidayData']))
                                <table class="table table-striped table-bordered table-hover table-sm">
                                    <tr class="thead-dark">
                                        <th class="notranslate" style="padding-right: 25px;">Date</th>
                                        <th class="notranslate" style="padding-right: 25px;">Day</th>
                                        <th class="notranslate" style="padding-right: 25px;">Opening</th>
                                        <th class="notranslate" style="padding-right: 25px;">Closing</th>
                                        <th class="notranslate" style="padding-right: 25px;">Holiday note</th>
                                        <th class="notranslate" style="padding-right: 25px;">Status</th>
                                    </tr>
                                    <?php
                                    $currentDate = date('Y-m-d');
                                    foreach($data['execholidayData'] as $execptionholidata){
                                    if ($execptionholidata->custom_date >= $currentDate) {
                                    $openexecptimeholiday =  json_decode($execptionholidata->custom_time); ?>
                                    <tr>
                                        <td class="notranslate" style="padding-right: 25px;">{{ $execptionholidata->custom_date??'' }}</td>
                                        <td style="padding-right: 25px;">{{ $execptionholidata->day??'' }}</td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table class="table"><?php foreach($openexecptimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table class="table"><?php foreach($openexecptimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda;">{{ $execptionholidata->note }}</span></td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda;">
                                            <b>
                                            @if($execptionholidata->closed == 1 || $execptionholidata->closed == 'Yes')
                                                Closed
                                            @else
                                                Open
                                            @endif
                                            </b>
                                        </span>
                                        </td>
                                    </tr>
                                    <?php }} ?>
                                </table>
                                @else
                                <div class="text-center">No Data Found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- we offer and About us MODAL -->
<div class="modal fade"  id="we_offer_details_model" tabindex="-1" role="dialog" style="height: 80%!important; top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">We Offer or About Us</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class=" tabs-left" id="modal-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" style="margin: 0px 20px 10px 0px; font-size: 18px;">
                            <a class="active"  href="#weoffer" data-toggle="tab">We Offer</a>

                            <span class="notranslate">||</span>
                        </li>
                        <li class="nav-item" style="margin: 0px 0px 0px -15px; font-size: 18px;">
                            <a href="#aboutuslisting"  data-toggle="tab">About Us</a>
                            <span class="notranslate">||</span>
                        </li>
                        <li class="nav-item" style="margin: 0px 0px 0px 5px; font-size: 18px;">
                            <a href="#aboutuslisting_us"  data-toggle="tab">Us</a>
                        </li>
                    </ul>

                </div>
                <div class="tab-content" id="tab-content">
                    <div class="tab-pane active" id="weoffer">
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer))
                                <div class="post-content textpopup show-more-heightpopup">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->we_offer }}</div>
                                    <!-- @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer) > 200)
                                    <div class="show-morebtn" style="cursor:pointer;">Read More</div>
                                    @endif -->
                                @else
                                <div class="text-center">No Data Found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="aboutuslisting">
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->about_us))
                                    <div class="post-content textpopup-aboutus show-more-heightpopup-aboutus">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->about_us }}</div>
                                    <!-- @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->about_us) > 200)
                                    <div class="read-morebtn" style="cursor:pointer;">Read More</div>
                                    @endif -->
                                @else
                                <div class="text-center">No Data Found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="aboutuslisting_us">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="text show-more-height">
                                    {{ $data['mainDirectoryDetails']->directoryLanguage[0]->description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade listing_detail_gallery" id="type_galleryModal" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Using only modal-xl class -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gallery Images</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview row" id="type_gallery_list">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade listing_detail_gallery" id="type_pdf_Modal" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- Using only modal-xl class -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">PDF Files</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview row" id="type_pdf_list">


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Image Modal -->
<div class="modal fade listing_detail_gallery"  id="allgalleryimages_model" tabindex="-1" role="dialog" style="top:50px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">All Gallery Images</h5>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 listgallerypreview" id="listgallerypreview">

                    <div class="row">
                        <?php
                        if(!empty($data['allgalleryImageChunk'])){ ?>
                        @foreach($data['allgalleryImageChunk'] as $rowgalleryrest)
                            @foreach($rowgalleryrest as $rowgallery)
                            <div class="thumbnail existing-img col-md-4 col-6" >
                                @if($rowgallery->name_thumbnail)
                                <a href="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}" data-lightbox="allGalleryImages">
                                    <img src="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}" class="img-fluid" alt="" >
                                </a>

                                @else
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" id="preview-gal-img" alt="directory-img" class="previewBlocks" />
                                @endif
                            </div>
                            @endforeach
                        @endforeach
                        <?php } ?>
                        <input type="hidden" id="lastpositiongallValue" value="<?php echo isset($last_position->position)?$last_position->position:'-1'  ?>" />
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade type_galleryModal" id="customer_club" tabindex="-1" role="dialog" style="top:55px;" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal_dialogcc modal-xl"> <!-- Using only modal-xl class -->
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px 0px 0px 0px;">
                <h4 class="text-center">Join Our Customer Club</h4>
                <button type="button" class="close modal_header_close_btn" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 customer_clubModal row" id="customer_clubModal">
                        <p>Want to be in the Customer Club and want to be able to receive offers from the Seller</p>

                        <div class="customer-club-form-container">
                            <span class="cc_respondata"></span>
                            @php
                                $user = auth()->user();
                            @endphp
                            <form id="customer_club_form" action="{{ route('customerclub.reg') }}" method="post">
                                {{ csrf_field() }}

                                <input type="hidden" name="cc_user_id" id="cc_user_id" value="@auth('web'){{ auth()->id() }}@endauth">
                                <input type="hidden" name="cc_directory_id" value="{{ $data['mainDirectoryDetails']->id }}">

                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="customer_club_fname" class="form-label">First Name</label>
                                        <input type="text" class="form-control input-sm required"
                                            value="{{ $user ? $user->name : '' }}"
                                            id="customer_club_fname"
                                            name="customer_club_name"
                                            required=""
                                            placeholder="Enter Name" >
                                    </div>
                                    <div class="col-md-6">
                                        <label for="customer_club_lname" class="form-label">Last Name</label>
                                        <input type="text" class="form-control input-sm required"
                                            value="{{ $user ? $user->last_name : '' }}"
                                            id="customer_club_lname"
                                            name="customer_club_lname"
                                            placeholder="Enter Last Name" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="customer_club_phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control input-sm required"
                                            value="{{ $user ? $user->phone : '' }}"
                                            id="customer_club_phone"
                                            name="customer_club_phone"
                                            placeholder="Enter Phone" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="customer_club_email" class="form-label">Email</label>
                                        <input type="email"
                                            @if($user)  readonly @endif
                                            class="form-control input-sm required"
                                            value="{{ $user ? $user->email : '' }}"
                                            id="customer_club_email"
                                            name="customer_club_email"
                                            placeholder="Enter e-mail address" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="customer_club_email" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control input-sm required" id="customer_club_postal_code" name="customer_club_postal_code" placeholder="Enter Postal Code" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Country<span style="color:red;">*</span></label>
                                            @php
                                            $countries = Helper::getCountryAllData();
                                            @endphp
                                            <select class="form-control input-sm required" name="country" id="country" required="">
                                                <option value="">Select Country</option>
                                                @foreach ($countries as $val)
                                                <option value="{{ $val->id }}">{{ $val->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group formlabel_state">
                                            <label for="name" class="form-label">State<span style="color:red;">*</span></label>
                                            <select name="state" id="state" class="form-control input-sm required" required="">
                                                <option value="">Select State</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="city_name" style="display: none">
                                            <label>{{__("message.city_text")}}<span style="color:red;">*</span></label>
                                            <input class="form-control" type="text" id="cityname" name="city-name" value="" >
                                        </div>
                                        <div class="form-group" id="city_dropdown">
                                            <label style="display: inline-flex;">{{__("message.city_text")}}<span class="notranslate"
                                                    style="color:red;">*</span>
                                            </label>
                                            <select class="form-control input-sm required" name="city" id="city">
                                                <option value="">Select City</option>

                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_club_message" class="form-label">Message</label>
                                    <textarea class="form-control formtextarea_cc" id="customer_club_message" name="customer_club_message" rows="4" placeholder="Your message here..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-custom w-100 cc_submitbtn">Click to join, welcome!</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<main>
    <?php if(!empty($data['directory_media_cover'])){ ?>
        <div id="listing_banner" class="owl-carousel owl-theme">
            <?php foreach($data['directory_media_cover'] as $rowgallery){ ?>
            @if( count($data['directory_media_cover']) == 1)
                <div class="item">
                    <div class="hero_in shop_detail">
                     <img src="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}">
                    </div>
                </div>
                <div class="item">
                    <div class="hero_in shop_detail" >
                    <img src="{{env('IMAGE_PATH').'files/default_images/'.$data['default_image_listing_banner']->image_name }}">
                    </div>
                </div>
            @else
               <div class="item">
                    <div class="hero_in shop_detail" >
                    <img src="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}">
                    </div>
                </div>

            @endif
            <?php } ?>
        </div>
    <?php }else{ ?>
    <div id="" class="common_banner">
        <div class="hero_in shop_detail">
                <img src="{{env('IMAGE_PATH').'files/default_images/'.$data['default_image_listing_banner']->image_name }}" alt="">
        </div>
    </div>
    <?php } ?>

    <div class="container-fluid  row">
        <div class="col-md-5">
            <?php
            $totalpublicschedule = count($data['publicScheduleData']);
            $totalexceptionschedule = count($data['exceptionScheduleData']);
            ?>
            <nav class="secondary_nav sticky_horizontal_2 sticky-listingdetails-header" style="background: #f8f8f8;">
                <div class="container-fluid">
                    <ul class="clearfix">
                        {{-- <li>
                            <a href="#description" class="active">

                            <p style="font-size: 13px; font-weight: 400; color: #333;">
                                <i class="fas fa-hand-o-right" style="color: #ffc107;" aria-hidden="true"></i>
                                Note : All automatically translated
                            </p>
                            </a>
                        </li> --}}

                        {{-- <li><a href="#reviews">Reviews</a></li> --}}
                        <?php
                        $openCloseTags = 0;
                        if($totalexceptionschedule>0){

                            $totalexeschedule = $data['exceptionScheduleData'][0]->custom_time;
                            $totaleceholiday = json_decode($totalexeschedule);
                            foreach($totaleceholiday as $exeholi){
                                $exeopentime = explode(":",$exeholi->open_time);
                                $execlosetime = explode(":",$exeholi->close_time);
                                if($exeholi->open_time<=$current_date_time && $exeholi->close_time>=$current_date_time){
                                    $openCloseTags=1;   break;
                                }else{
                                    $openCloseTags=0;
                                }
                            }

                            foreach($totaleceholiday as $exeholi){ ?>
                            <li>
                                <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block">
                                    <i class="fas fa-external-link-alt" aria-hidden="true"></i> <?php echo $exeholi->open_time ; ?> - <?php echo $exeholi->close_time; ?></button>
                                <input type="hidden" class="get_endtime" name="get_endtime" value="<?php echo $exeholi->close_time; ?>">
                            </li>
                        <?php } ?>

                        <?php if($openCloseTags>0){ ?>
                            <li>
                                <button id="down-to-schedule_c" class="btn btn-success btn-sm btn-block">Open</button>
                            </li>
                        <?php }else{ ?>
                            <li>
                                <button id="down-to-schedule_c" class="btn btn-warning btn-sm btn-block"><?php echo __('message.closed_text'); ?></button>
                            </li>
                        <?php } ?>

                        <?php }else if($totalpublicschedule>0){
                            $totalpublicscheduledata = $data['publicScheduleData'][0]->custom_time;
                            $totalholiday = json_decode($totalpublicscheduledata);
                            foreach($totalholiday as $pubholi){
                                $publicopentime = explode(":",$pubholi->open_time);
                                $publicclosetime = explode(":",$pubholi->close_time);
                                if($pubholi->open_time<=$current_date_time && $pubholi->close_time>=$current_date_time){
                                    $openCloseTags=1;
                                    break;
                                }else{
                                    $openCloseTags=0;
                                }

                            }
                            foreach($totalholiday as $pubhd){ ?>
                                <li>
                                    <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block"><i class="fas fa-external-link-alt" aria-hidden="true"></i> <?php echo $pubhd->open_time ; ?> - <?php echo $pubhd->close_time; ?></button>
                                    <input type="hidden" class="get_endtime" name="get_endtime" value="<?php echo $pubhd->close_time; ?>">
                                </li>
                            <?php } ?>

                            <?php if($openCloseTags>0){ ?>
                                <li> <button id="down-to-schedule_c" class="btn btn-success btn-sm btn-block"> Open</button> </li>
                            <?php }else{ ?>
                                <li>  <button id="down-to-schedule_c" class="btn btn-warning btn-sm btn-block"><?php echo __('message.closed_text'); ?></button> </li>
                            <?php } ?>

                        <?php }else{


                            foreach($templateandScheduleDaysData as $days => $val){
                                $days_abb = substr($days, 0, 3);
                                if($days_abb==$serverDay){
                                    $normalOpenTime = explode(":",$val[0]['open_time']);
                                    $normalCloseTime = explode(":",$val[0]['close_time']);
                                    foreach($val as $norholi){
                                        $normalopentime = explode(":",$norholi['open_time']);
                                        $normalclosetime = explode(":",$norholi['close_time']);

                                        //if($normalopentime[0]<=$hour && $normalclosetime[0]>=$hour){
                                        if($norholi['open_time']<=$current_date_time && $norholi['close_time']>=$current_date_time){
                                            $openCloseTags=1;
                                            break;
                                        }else{
                                            $openCloseTags=0;
                                        }

                                    }

                                    foreach($val as $schd){?>
                                        <li>
                                            <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block">
                                                <i class="fas fa-external-link-alt" aria-hidden="true"></i> <?php echo $schd['open_time'] ; ?> - <?php echo $schd['close_time']; ?></button>
                                            <input type="hidden" class="get_endtime" name="get_endtime" value="<?php echo $schd['close_time']; ?>">
                                        </li>
                                    <?php } ?>

                                    <?php if($openCloseTags>0){ ?>
                                        <li>
                                            <button id="down-to-schedule_c" class="btn btn-success btn-sm btn-block">Open</button>
                                        </li>
                                    <?php }else{ ?>
                                        <li>
                                            <button id="down-to-schedule_c" class="btn btn-warning btn-sm btn-block"><?php echo __('message.closed_text'); ?></button>
                                        </li>
                                    <?php } ?>
                            <?php } } ?>
                        <?php } ?>
                        <?php if($ismobiledevice==1){ ?>
                            <li>
                                <span class="headercontact"><button id="down-to-contact" class="btn btn-success btn-sm btn-block">Contact Us</button></span>
                            </li>
                        <?php } ?>

                    </ul>

                </div>
                <?php if($ismobiledevice==1){ ?>
                <button class="direction_a btn-block" id="direction_anch" >
                    <img  class="direction" src="{{ url('/') }}/assets/client/images/direction2.png" width="10%" alt="">
                </button>
                <?php } ?>
            </nav>
        </div>
        <div class="col-md-2">
        <?php if(!empty($data['directoryProductData'])){ ?>
            <a class="visit_the_shop btn-block" target="_blank" href="<?php echo url('listings-product', ['id' => $data['mainDirectoryDetails']->slug]); ?>">
                <i class="fas fa-external-link-alt" aria-hidden="true"></i> Visit the Shop ({{ count($data['directoryProductData']) }})
            </a>
        <?php } ?>
        </div>
        <div class="col-md-4">
            @if($data['address'][0]->address)
                <a class="address relatedprovisitbtn visit_the_shop btn-block" id="down-to-map" href="javascript:void(0);">{{ $data['address'][0]->address }}</a>
            @endif
        </div>

        <div class="col-md-1 functions_reload_div" style="padding: 10px;">
            @if( last(request()->segments()) == 'rydholms-dammodehus' )
            <span id="functions_reload">
                <a target="_blank" href="{{ url('/listings/details/rydholms-dammodehus') }}" style="#201919 !important;" class="btn-sm functions_reload">
                    <i class="fa fa-refresh" aria-hidden="true"></i> <?php echo __('Reload'); ?>
                </a>
            </span>
            @endif
        </div>
    </div>

    <div class="container">

        <!-- START Tags DATA -->
        <div class="add_bottom_15">
            @if(count($data['related_tags']) > 0)

                <div class="row">
                    <h2 style="padding-left: 13px;">Menu:</h2>
                </div>

                <div id="company_imp_tags_div">
                    @if(count($data['imp_ptags']) > 0)
                        @foreach ($data['imp_ptags'] as $key => $tagval)
                            <span class="loc_open mr-2 notranslate" style="background-color: #b5b9b7; color: #000 !important; border: 1px solid #b5b9b7; padding: 3px 6px; border-radius: 4px; display: inline-flex; align-items: center;">
                                <a href="{{ url('listings-product/'.$data['mainDirectoryDetails']->slug.'?tags='.$key??'') }}" target="_blank" class="company_imp_tags2"  style="color: #000; text-decoration: none; margin-right: 5px;">
                                    {{ $tagval }}
                                </a>
                                @if(auth()->check())
                                    @if(auth()->user()->directory_id == $data['mainDirectoryDetails']->id)
                                        <i class="fa fa-times tag_delete" data-impid="{{ $key }}" style="cursor:pointer;"></i>
                                    @endif
                                @endif
                            </span>
                        @endforeach
                    @endif
                </div>
                @if(count($data['imp_ptags']) > 0)
                    @if(auth()->check())
                        @if(auth()->user()->directory_id == $data['mainDirectoryDetails']->id)
                            <button id="btn_defaulttags" style="border: 1px solid #ccc; float: right;">See More <i class="fas fa-chevron-down"></i></button>
                        @endif
                    @endif
                @endif
                <hr>
                <div class="defaulttags" style="display: @if(count($data['imp_ptags']) > 0)none;@endif">
                    <?php
                    foreach($data['related_tags'] as $tagkey => $tagstitle){
                    ?>
                    <span class="loc_open mr-2 notranslate" style="background-color: #b5b9b7;color: #000!important;border: 1px solid #b5b9b7;">
                        <a target="_blank" class="company_imp_tags" data-companyid="{{ $data['mainDirectoryDetails']->id }}" data-id="{{ $tagkey??'' }}" data-title="{{ $tagstitle??'' }}" style="color: #000;">
                            {{ $tagstitle??'' }}
                        </a>
                    </span>
                    <?php } ?>
                </div>
            @endif
        </div>
        <!-- END TAGS DATA -->

        @if(count($data['related_tags']) > 0)
            <form action="{{ route('directory.product',request()->id) }}" method="GET">
                <div class="row no-gutters custom-search-input-2 inner">
                    <div class="col-lg-11 col-md-11">
                        <div class="form-group">
                            <input class="form-control" name="search_string" id="search_string" type="text" value="" placeholder="{{__("message.header_search_text")}}(Products, ENA)">
                            <i class="fa fa-microphone" style="color: #fe0536;" aria-hidden="true" id="start-recording"></i>
                        </div>
                    </div>

                    <div class="col-lg-1 col-md-1">
                        <input type="submit" class="btn_search_mobile_submit" value="{{__("message.header_search_text_search_btn")}}" >
                    </div>
                </div>
            </form>
        @endif


    </div>

    <div class="container-fluid row">



        <div class="col-md-12">

                    <?php
                        if (isset($data['contactData'])) {
                            $contactData = $data['contactData'];
                    ?>
                                @if(!empty($contactData[0]->facebook) || (!empty($contactData[0]->twitter)) || (!empty($contactData[0]->linkedin)) || (!empty($contactData[0]->pinterest)) || (!empty($contactData[0]->whatsapp)) || (!empty($contactData[0]->instagram)) || (!empty($contactData[0]->tiktok)) || (!empty($contactData[0]->youtube)) || (!empty($contactData[0]->snapchat)))

                                <div class="link-css-mobile">
                                    <div class="col-md-12">
                                        @if($contactData[0]->facebook)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->facebook??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fa fa-facebook" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if($contactData[0]->twitter)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->twitter??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Twitter
                                            </a>
                                        @endif
                                        @if($contactData[0]->linkedin)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->linkedin??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fa fa-linkedin" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if($contactData[0]->pinterest)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->pinterest??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fa fa-pinterest" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if($contactData[0]->whatsapp)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->whatsapp??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Whatsapp
                                            </a>
                                        @endif
                                        @if($contactData[0]->instagram)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->instagram??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            <i class="fa fa-instagram" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if($contactData[0]->tiktok)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->tiktok??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            X
                                            </a>
                                        @endif
                                        @if($contactData[0]->youtube)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->youtube??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                             <i class="fa fa-youtube" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                        @if($contactData[0]->snapchat)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->snapchat??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Snapchat
                                            </a>
                                        @endif


                                    </div>
                                </div>
                                @endif
                    <?php } ?>

                    <?php if(!empty($data['website_links'])){ ?>

                        <div class="row mb-1 link-css-mobile">
                            <div class="col-md-12">
                                <a class="add_bottom_15 link-headings">{{__("message.website_links")}}</a>
                                @foreach($data['website_links'] as $websitelinks)
                                <a href="{{ $websitelinks->link }}" target="_blank" class="btn btn-info btn-sm">{{ $websitelinks->name }} </a>
                                @endforeach
                            </div>
                        </div>
                    <?php } ?>


            <div class="add_bottom_15 likebtn_alignment" style="margin-bottom: 0px;">
                <span class="loc_open mr-2">
                    <a href="{{ route('listing') }}" style="color: #32a067;">
                        {{__("message.menu_text_listing")}}>
                    </a>
                </span>
                <span class="loc_open mr-2">
                    <a style="color: #32a067;">
                        <?php echo end($currentUrl); ?>>
                    </a>
                </span>
                <span class="mr-2" >
                    <div class="postmeta">
                        <ul>
                            <li>
                                <?php //echo "<pre>"; print_r($event); ?>
                                <input type="hidden" name="type_item" id="type_items" value="directory">
                                <input type="hidden" name="country_code" id="country_code" value="{{$countryCode}}">
                                <input type="hidden" name="state" id="state" value="{{$state}}">
                                <input type="hidden" name="city" id="city" value="{{$city}}">
                                <a href="javascript:void(0);" id="listinglikedtotal" class="notranslate" title="Like Listing" productid="{{ $data['mainDirectoryDetails']->directoryLanguage[0]->directory_id }}" onclick="addToLikeCounter(this, <?php echo $data['mainDirectoryDetails']->directoryLanguage[0]->directory_id ?>)">
                                    <i class="fa fa-thumbs-up notranslate" aria-hidden="true"></i>
                                    (
                                        <span class="notranslate">
                                            @if(count(Helper::CountLikeData('directory',$data['mainDirectoryDetails']->directoryLanguage[0]->directory_id)) != 0)
                                                {{ count(Helper::CountLikeData('directory',$data['mainDirectoryDetails']->directoryLanguage[0]->directory_id)) }}
                                            @else
                                                {{ 0 }}
                                            @endif
                                        </span>
                                    ) Like
                                </a>
                            </li>
                        </ul>
                    </div>
                </span>
            </div>
        </div>



        <?php if(!empty($data['galleryImageChunk'])){ ?>
        <div class="container-fluid">
            <div id="sploffer" class="owl-carousel owl-theme listing_gllary_img gallimageslength">
                <?php foreach($data['galleryImageChunk'] as $rowgalleryres){
                    foreach($rowgalleryres as $rowgallery){
                    ?>
                <div class="item">
                    <div class="strip grid">
                        <figure>
                        @if(!empty($rowgallery->name_thumbnail))
                            <a href="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}" data-lightbox="topGalleryList">
                                <img src="{{env('IMAGE_PATH').'images/directory/'.$rowgallery->name_thumbnail.'.webp' }}" class="" alt="" width="400" height="266">
                            </a>
                        @else
                            <a href="{{ asset('assets/plugins/images/placeholder.png') }}" data-lightbox="topGalleryList">
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="" alt="" width="400" height="266">
                            </a>
                        @endif
                        </figure>
                    </div>
                </div>
                <?php } } ?>
            </div>
        </div>
        <?php } ?>
        <div class="col-md-12">
            <div class="listing_see_all">
                @if(count($data['galleryImage']) > 1)
                    <div class="see_all" data-toggle="modal" data-target="#allgalleryimages_model">See All</div>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <section id="description">

                    <!-- Trademark Gallery -->
                    <?php if(!empty($data['trademarkData'][0]->trademark_id)){ ?>
                    <h5 class="add_bottom_15">{{__("message.trademark_title")}} ({{ count($data['trademarkData']) }})</h5>
                    <div id="trademark" class="owl-carousel owl-theme">
                        <?php foreach($data['trademarkData'] as $rowtrademark){
                            $imagename = Helper::getTrademarkImage($rowtrademark->trademark_id); ?>
                        <div class="item">
                            <div class="strip grid">
                                <figure style="height: 100px;">
                                    <?php if(!empty($imagename)){ ?>
                                        <a href="{{ route('listingbytrademark',$rowtrademark->trademark_id??'') }}"><img src="{{env('IMAGE_PATH').'files/trademark/'.$imagename[0]->name_thumbnail }}" class="" alt="Trademark image" width="100" height="100"></a>
                                    <?php }else{ ?>
                                        <a href="{{ route('listingbytrademark',$rowtrademark->trademark_id??'') }}"><img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="" alt="Trademark image" width="100" height="100"></a>
                                    <?php } ?>

                                </figure>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php } ?>

                    <?php if(!empty($data['offerData'])){ ?>
                        <h5 class="add_bottom_15">{{__("message.offer_title")}}({{ count($data['offerData']) }})</h5>

                        <div id="offerdata" class="owl-carousel owl-theme">
                            <?php foreach($data['offerData'] as $row){ ?>
                                <?php
                                $siteOfferUrlFacebook = url('share/offer-facebook-share/'.$row->id);
                                $discritionOffer = ($row->title!='')?$row->title:$row->title;
                                $siteOfferUrlTwitter = url('offer/detail/'.$row->slug);
                                ?>
                                <div class="item">

                                    <div class="co-sm-4 share-tooltip-desktop share-tooltip" >
                                        <span><img class="image-responsive share-icon-gap" src="{{ asset('assets/plugins/images/social-share/share-icon.png') }}"></span>
                                        <span class = "share-tooltiptext">
                                            <a class = "fb-share share-icon-gap" href="javascript:void(0);" onclick="fb_share('{{ $siteOfferUrlFacebook }}', '{{ $discritionOffer }}')">
                                                <img class="image-responsive" title="facebook" src="{{ asset('assets/plugins/images/social-share/facebook-icon.png') }}">
                                            </a>
                                            <a class="fb-share share-icon-gap" href="http://twitter.com/share?text={{ $row->title }}&url={{ $siteOfferUrlTwitter }}" target="_blank">
                                                <img class="image-responsive" title="twitter" src="{{ asset('assets/plugins/images/social-share/rwitter-icon.png') }}">
                                            </a>
                                            <?php if($ismobiledevice==1){ ?>
                                                <a class="fb-share hidden-md hidden-lg" href="whatsapp://send?text={{ $siteOfferUrlTwitter }}" data-action="share/whatsapp/share">
                                                    <img class="image-responsive" title="whatsaap" src="{{ asset('assets/plugins/images/social-share/whatsapp-icon.png') }}" width="29px" height="29px">
                                                </a>
                                            <?php } ?>
                                        </span>
                                    </div>

                                    <div class="strip grid">
                                        <figure style="height: 360px;">
                                            <?php
                                                $imagename = Helper::getOfferimage($row->id);
                                                $getOfferGallery = Helper::getOfferGallery($row->id);
                                                if(!empty($row->video)){
                                            ?>
                                            <a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>">
                                                <video width="400" height="320" style="opacity: 1;" controls autoplay muted loop>
                                                    <source src="{{ env('IMAGE_PATH').'files/offer/'.$row->video }}" type="video/mp4">
                                                </video>
                                            </a>
                                            <?php }else{
                                                if(!empty($imagename)){ ?>
                                                <a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>">
                                                    <img src="{{ env('IMAGE_PATH').'images/offer/'.$imagename[0]->name_thumbnail.'.webp' }}"  alt="" width="400" height="266">
                                                </a>
                                                <?php }else{ ?>
                                                <a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}"  alt="" width="400" height="266"></a>
                                            <?php } } ?>

                                            <small>
                                                {{ $row->title }}
                                                @if($row->discount_selectedvalue == 'pricediscount')
                                                    <i class="p-discount"> -{{ $row->discount_firstvalue }} </i>
                                                @elseif($row->discount_selectedvalue == 'percentagediscount')
                                                    <i class="p-discount"> -{{ $row->discount_firstvalue }}% </i>
                                                @elseif($row->discount_selectedvalue == 'new')
                                                    <i class="p-discount"> New </i>
                                                @endif
                                            </small>
                                        </figure>
                                        <div class="wrapper">
                                            <h3>{{ $row->title }}</h3><br>
                                            <p class="mt-1">{{ substr(strip_tags($row->description),0,48) }}...</p>
                                        </div>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>

                        @if(count($data['offerData']) > 2)
                            <a class="offer-see-all-btn" href="{{ route('directory.hotoffers', $data['mainDirectoryDetails']->id??0 ) }}">
                                <div class="see_all">
                                See All
                                </div>
                            </a>
                        @endif
                    <?php } ?>

                    <!--------------Shopping card section---------->
                    <?php if(!empty($data['shoppingcardData'])){ ?>
                        <div class="main_title_2" style="text-align: left;">
                        <h5 class="add_bottom_15">Shopping Card ({{ count($data['shoppingcardData']) }})</h5>
                        </div>
                        <div id="shopcarddata" class="owl-carousel owl-theme">
                            <?php foreach($data['shoppingcardData'] as $row){ ?>
                                <?php
                                $siteUrlFacebook = url('share/shoping-card/'.$row->id);
                                $discritionShoppingCard = ($row->title!='')?$row->title:$row->title;
                                $siteUrlTwitter = url('shopping-card/detail/'.$row->slug);
                                ?>
                            <div class="item">

                            <div class="co-sm-4 share-tooltip-desktop share-tooltip" >
                                <span><img class="image-responsive share-icon-gap" src="{{ asset('assets/plugins/images/social-share/share-icon.png') }}"></span>
                                <span class = "share-tooltiptext">
                                    <a class = "fb-share share-icon-gap" href="javascript:void(0);" onclick="fb_share('{{ $siteUrlFacebook }}', '{{ $discritionShoppingCard }}')">
                                        <img class="image-responsive" title="facebook" src="{{ asset('assets/plugins/images/social-share/facebook-icon.png') }}">
                                    </a>
                                    <a class="fb-share share-icon-gap" href="http://twitter.com/share?text={{ $row->title }}&url={{ $siteUrlTwitter }}" target="_blank">
                                        <img class="image-responsive" title="twitter" src="{{ asset('assets/plugins/images/social-share/rwitter-icon.png') }}">
                                    </a>
                                    <?php if($ismobiledevice==1){ ?>
                                        <a class="fb-share hidden-md hidden-lg" href="whatsapp://send?text={{ $siteUrlTwitter }}" data-action="share/whatsapp/share">
                                            <img class="image-responsive" title="whatsaap" src="{{ asset('assets/plugins/images/social-share/whatsapp-icon.png') }}" width="29px" height="29px">
                                        </a>
                                    <?php } ?>
                                </span>
                            </div>

                                <div class="strip grid">
                                    <figure style="height: 360px;">
                                        <?php $imagename = Helper::getShoppingcardimage($row->id); ?>
                                        <?php if(!empty($imagename)){ ?>
                                        <a href="<?php echo url('shopping-card/detail', ['id' => $row->slug]); ?>"><img src="{{ env('IMAGE_PATH').'images/shoppingcard/'.$imagename[0]->name_thumbnail.'.webp' }}"  alt="" width="400" height="266"></a>
                                        <?php }else{ ?>
                                        <a href="<?php echo url('shopping-card/detail', ['id' => $row->slug]); ?>"><img src="{{ asset('assets/plugins/images/placeholder.png') }}"  alt="" width="400" height="266"></a>
                                        <!-- <img src="{{ asset('assets/plugins/images/placeholder.png') }}" alt="" > -->
                                        <?php } ?>
                                        <small>
                                            {{ $row->title }}
                                            @if($row->discount_selectedvalue == 'pricediscount')
                                                <i class="p-discount"> -{{ $row->discount_firstvalue }} </i>
                                            @elseif($row->discount_selectedvalue == 'percentagediscount')
                                                <i class="p-discount"> -{{ $row->discount_firstvalue }}% </i>
                                            @elseif($row->discount_selectedvalue == 'new')
                                                <i class="p-discount"> New </i>
                                            @endif
                                        </small>
                                    </figure>
                                    <div class="wrapper">
                                        <h3>{{ $row->title }}</h3><br>
                                        <p class="mt-1">{{ substr(strip_tags($row->description),0,48) }}...</p>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        @if(count($data['shoppingcardData']) > 2)
                            <a class="offer-see-all-btn" href="{{ route('directory.shoppingcards', $data['mainDirectoryDetails']->id??0 ) }}">
                                <div class="see_all">
                                See All
                                </div>
                            </a>
                        @endif
                    <?php } ?>


                    <?php if(!empty($data['eventdata'])){ ?>
                        @if(count($data['eventdata']) > 0)
                        <div class="main_title_2" style="text-align: left;">
                        <h5 class="add_bottom_15">{{__("Events")}}({{ count($data['eventdata']) }})</h5>
                        </div>
                        @endif

                        <div id="eventdata" class="owl-carousel owl-theme">
                            <?php foreach($data['eventdata'] as $row){   ?>
                                <div class="item">
                                    <div class="strip grid">
                                        <figure style="height: 360px;">
                                            <?php
                                                //$imagename = Helper::getOfferimage($row->id);
                                                //$getOfferGallery = Helper::getOfferGallery($row->id);

                                                if(!empty($row->event_image)){ ?>
                                                <a href="<?php echo url('event/details', ['id' => $row->slug]); ?>">
                                                    <img src="{{ env('IMAGE_PATH').'files/events/'.$row->event_image }}"  alt="" width="400" height="266">
                                                </a>
                                                <?php }else{ ?>
                                                <a href="<?php echo url('event/details', ['id' => $row->slug]); ?>">
                                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}"  alt="" width="400" height="266"></a>
                                            <?php } ?>

                                        </figure>
                                        <div class="wrapper">
                                            <h3>{{ $row->title }}</h3><br>
                                            <p class="mt-1">{{ substr(strip_tags($row->content),0,48) }}...</p>
                                        </div>
                                    </div>

                                </div>
                            <?php } ?>
                        </div>

                        @if(count($data['eventdata']) > 2)
                            <a class="offer-see-all-btn" href="{{ url('events') }}">
                                <div class="see_all">
                                See All
                                </div>
                            </a>
                        @endif
                    <?php } ?>


                    <?php if(!empty($data['adevertiseData'])){ ?>
                    <div class="container-fluid sec-bg  margin_80_55 adevertiseDatad competition_div">
                        @if(count($data['adevertiseData']) > 0)
                            <div class="main_title_2">
                                <div class="viewallcatbtn-align" style="text-align: left;">
                                    <h5 class="add_bottom_15">
                                           {{__("Competition")}} ( {{ count($data['adevertiseData']) }} )
                                    </h5>
                                </div>
                            </div>

                            <div id="banner-foot" class="owl-carousel owl-theme hot_add_home">
                                <?php
                                foreach($data['adevertiseData'] as $row){
                                ?>

                                    <div class="item" data-aos="zoom-out-up" data-aos-duration="1000">
                                        <div class="strip grid">
                                            <figure class="imageads">
                                                <?php if(!empty($row->image_name)){ ?>
                                                <a href="{{ route('competition.detail',$row->id) }}"><img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" class="img-fluid" alt="" width="400" height="266"></a>
                                                <?php }else{ ?>
                                                <a href="{{ route('competition.detail',$row->id) }}"><img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266"></a>
                                                <?php } ?>
                                                    <small> {{ $row->heading }}</small>


                                                    @php
                                                        //$price = str_replace(',', '', $row->price);
                                                    @endphp


                                                        <small class="adsprice">
                                                            <i class="p-discount">
                                                                @if(!is_null($row->price) && $row->price > 0)
                                                                    <span class="discount-price">
                                                                         {{ $currencySymbol }}{{ number_format($row->price, 2) }}
                                                                    </span>
                                                                @else
                                                                    {{ $row->discount_text??'' }}
                                                                @endif

                                                                {{-- {{ $currencySymbol }} {{ number_format((float)$price, 2, '.', '') }} --}}
                                                            </i>
                                                        </small>


                                                    {{-- @if($row->price != '')
                                                        <small class="adsprice">
                                                            <i class="p-discount">
                                                                {{ $currencySymbol }} {{ number_format($row->price, 2, '.', '') }}
                                                            </i>
                                                        </small>
                                                    @endif --}}
                                                    @if($row->mid_text != '')
                                                    <a href="{{ route('competition.detail',$row->id) }}" target="_blank">
                                                    <small class="adsmidtext">
                                                        {{ $row->mid_text }}
                                                    </small>
                                                    </a>
                                                    @endif
                                            </figure>
                                            <h6 style="text-transform: uppercase;font-weight: 600; font-size: 13px; margin: 5px;padding: 5px;">
                                                {{ $row->bottom_text??'' }}
                                            </h6>
                                            <p style=" margin: -5px 5px 5px 15px">
                                                {{ $row->description??'' }}
                                            </p>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>

                        @endif
                    </div>
                    <?php } ?>

                   @if(count($data['blogData']) > 0)
                    <div class="container-fluid sec-bg adevertiseDatad">

                        <div class="main_title_2">
                            <div class="viewallcatbtn-align" style="text-align: left;">
                                <h5 class="add_bottom_15">
                                    {{ __("Blogs") }} ( {{ count($data['blogData']) }} )
                                </h5>
                            </div>
                        </div>

                        <div id="blogData" class="owl-carousel owl-theme hot_add_home">

                            @foreach($data['blogData'] as $row)
                            <div class="item" data-aos="zoom-out-up" data-aos-duration="1000">
                                <div class="strip grid">

                                    <figure class="imageads">
                                        <a href="{{ url('blog/details/'.$row->slug) }}" target="_blank">
                                            @if(!empty($row->blog_image))
                                                <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}"
                                                    class="img-fluid"
                                                    alt="{{ $row->title }}"
                                                    width="400" height="266">
                                            @else
                                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}"
                                                    class="img-fluid"
                                                    alt="No image"
                                                    width="400" height="266">
                                            @endif
                                        </a>

                                        <small>{{ Str::limit(strip_tags($row->title), 20) }}</small>

                                        <small class="adsmidtext">
                                            {{ date('j F, Y', strtotime($row->created_at)) }}
                                        </small>
                                    </figure>

                                    <p style="text-transform: uppercase;font-weight: 600; font-size: 13px; margin: 5px;padding: 5px;">
                                        {{ Str::limit(strip_tags($row->content), 42) }}
                                    </p>

                                </div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                    @endif


                    <?php if(!empty($data['assortment_links'])){ ?>
                        <hr>
                        <h5 class="add_bottom_15 link-headings">{{__("message.assortment_links")}}</h5>
                        <div class="row mb-1 link-css-mobile">
                            <div class="col-md-12">
                                @foreach($data['assortment_links'] as $assortmentlinks)
                                <a href="{{ $assortmentlinks->link }}" target="_blank" class="btn btn-info btn-sm">{{ $assortmentlinks->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    <?php } ?>


                    <hr>
                    <?php if(!empty($data['amenityData'])){ ?>
                            <!-- <h5 class="add_bottom_15">{{__("message.filters_amenities_subtitle")}}</h5> -->
                            <?php
                            if (isset($data['amenityData'])) {
                                $amenitydataidNew		= (isset($data['amenityData'][0]->amenities_id) ? $data['amenityData'][0]->amenities_id : "");
                                $amenitydataidExisting 	= (isset($data['amenityData'][1]->amenities_id) ? $data['amenityData'][1]->amenities_id : "");
                                $amenityDataNew 		= explode(',', $amenitydataidNew);
                                $amenityDataExisting 	= explode(',', $amenitydataidExisting);
                                $amenityData            = array_merge($amenityDataExisting,$amenityDataNew);
                            }
                            ?>

                        <div class="row add_bottom_30">

                        @if($ismobiledevice != 1)
                            <style>

                            </style>
                        @endif


                        <?php //if($ismobiledevice == 1){ ?>

                            <ul style="padding: 3px 0px 0px 30px; width:100%" class="row">
                                    <?php $anemitycounter = 1;
                                    foreach($data['amenity'] as $amenityrow){
                                        if(in_array($amenityrow->id, $amenityData)){
                                            if($anemitycounter > 3){
                                                $hiddencls = "hidden-amenities hidden";
                                            }else{
                                                $hiddencls = "";
                                            }
                                    ?>
                                    <div class="col-md-4">
                                        <div class="{{ $hiddencls }} amenities_div">
                                            <?php if($amenityrow->icon_class!=""){ ?>
                                                <li style="padding-top: 5px;" class="amenities_li"><i class="{{ $amenityrow->icon_class }}" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                            <?php }else{ ?>
                                                <li style="padding-top: 5px;" class="amenities_li"><i class="fas fa-check" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php $anemitycounter++; }  } ?>
                                    <span style="float: left; width: 100%;"><a href="javascript::" id="amenity-toggle-btn" style="color: #004dda !important;" onclick="showToggle(this, 'hidden-amenities')">{{__("message.show_more")}}</a></span>
                            </ul>

                        <?php //}else{ ?>

                            {{-- @foreach($data['amenity'] as $amenityrow)
                            <?php //if(in_array($amenityrow->id, $amenityData)){ ?>
                                <div class="col-md-4">
                                    <ul class="">
                                        <?php //if($amenityrow->icon_class!=""){ ?>
                                            <li><i class="{{ $amenityrow->icon_class }}" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                        <?php //}else{ ?>
                                            <li><i class="fas fa-check" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                        <?php //} ?>
                                    </ul>
                                </div>
                            <?php //} ?>
                            @endforeach --}}
                        <?php //} ?>

                    </div>
                    <?php } ?>

                    <div class="opening" id="schedule_sec">
                        <?php if($openCloseTags!=1){ ?>
                            <div class="ribbon">
                                <span class="closed">{{__("message.closed_text")}}</span>
                            </div>
                        <?php }else{ ?>
                            <div class="ribbon">
                                <span class="open">Open</span>
                            </div>
                        <?php } ?>


                        <span class="holiday-public" data-toggle="modal" data-target="#public_holiday_model">
                            <i class="fa fa-calendar pub-holiday-css" title="All Public Holiday or All Exception Holiday"></i>
                        </span>
                        <i class="fa fa-clock-o clock-css" aria-hidden="true"></i>
                        <h4>Opening Hours</h4>

                        <div class="row">
                            <div class="col-md-6">
                                <table class="public-holiday-alignment-mobile">
                                    <?php
                                    $totalpublicschedule = count($data['publicScheduleData']);
                                    foreach($templateandScheduleDaysData as $days => $val){
                                        $days_abb = substr($days, 0, 3);
                                        if($days=="monday" || $days=="tuesday" || $days=="wednesday" || $days=="thursday"){
                                    ?>
                                    <?php $j=0; foreach($val as $opnclosetime){ ?>
                                    <tr>
                                        <?php if($j==0){ ?>
                                        <td class="notranslate" style="padding-right: 25px;">{{ ucfirst($days) }}</td>
                                        <?php }else{ ?>
                                        <td class="notranslate" style="padding-right: 25px;"></td>
                                        <?php } ?>
                                        <?php if($days_abb==$serverDay){
                                            if($totalexceptionschedule>0){
                                                if($j==0){
                                            ?>
                                            <td colspan="3" style="text-align: center;color: #004dda;">Today's new time mentioned below!</td>
                                            <?php } ?>
                                        <?php }elseif($totalpublicschedule>0){ if($j==0){ ?>
                                            <td colspan="3" style="text-align: center;color: #004dda;">Today's new time mentioned below!</td>
                                        <?php } }else{ ?>
                                            <td class="notranslate" style="text-align: right;">{{ $opnclosetime['open_time'] }}</td>
                                            <td class="notranslate">-</td>
                                            <td class="notranslate" style="text-align: left;">{{ $opnclosetime['close_time'] }}</td>
                                        <?php } }else{ ?>
                                            <td class="notranslate" style="text-align: right;">{{ $opnclosetime['open_time'] }}</td>
                                            <td class="notranslate">-</td>
                                            <td class="notranslate" style="text-align: left;">{{ $opnclosetime['close_time'] }}</td>
                                        <?php } ?>
                                    </tr>
                                    <?php $j++; } ?>
                                    <?php } } ?>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="public-holiday-alignment-mobile">
                                    <?php foreach($templateandScheduleDaysData as $days => $val){
                                        $days_abb = substr($days, 0, 3);
                                        if($days=="friday" || $days=="saturday" || $days=="sunday"){
                                    ?>
                                    <?php $j=0;foreach($val as $opnclosetime){ ?>
                                    <tr>
                                        <?php if($j==0){ ?>
                                        <td class="notranslate" style="padding-right: 25px;">{{ ucfirst($days) }}</td>
                                        <?php }else{ ?>
                                        <td class="notranslate" style="padding-right: 25px;"></td>
                                        <?php } ?>
                                        <?php if($days_abb==$serverDay){
                                            if($totalexceptionschedule>0){
                                                if($j==0){
                                            ?>
                                            <td colspan="3" style="text-align: center;color: #004dda;">Today's new time mentioned below!</td>
                                            <?php } ?>
                                        <?php }elseif($totalpublicschedule>0){ if($j==0){ ?>
                                            <td colspan="3" style="text-align: center;color: #004dda;">Today's new time mentioned below!</td>
                                        <?php } }else{ ?>
                                            <td class="notranslate" style="text-align: right;">{{ $opnclosetime['open_time'] }}</td>
                                            <td class="notranslate">-</td>
                                            <td class="notranslate" style="text-align: left;">{{ $opnclosetime['close_time'] }}</td>
                                        <?php } }else{ ?>
                                            <td class="notranslate" style="text-align: right;">{{ $opnclosetime['open_time'] }}</td>
                                            <td class="notranslate" >-</td>
                                            <td class="notranslate" style="text-align: left;">{{ $opnclosetime['close_time'] }}</td>
                                        <?php } ?>

                                    </tr>
                                    <?php $j++; } ?>
                                    <?php } } ?>
                                </table>

                            </div>
                        </div>
                        <?php if($totalexceptionschedule>0){ ?>
                        <div class="row">
                            <div class="col-md-12 extra-time" style="overflow-x: auto;">
                                <table class="table">
                                    <?php foreach($data['exceptionScheduleData'] as $excepdata){
                                    $openexcetimeholiday =  json_decode($excepdata->custom_time); ?>
                                    <tr>
                                        <td style="padding-right: 25px;">{{ $excepdata->custom_date }}</td>
                                        <td style="text-align: right;">
                                            <table><?php foreach($openexcetimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>
                                        <td style="text-align: right;">
                                            <table><?php foreach($openexcetimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 25px;"><span style="color: #004dda;"><b>Note: </b>{{ $excepdata->note }}</span></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <?php }else if($totalpublicschedule>0){ ?>
                        <!-- <h4>Public Holiday Opening Hours</h4> -->
                        <div class="row">
                            <div class="col-md-12 extra-time">
                                <table>
                                    <?php foreach($data['publicScheduleData'] as $publicdata){
                                    $openpuplictimeholiday =  json_decode($publicdata->custom_time); ?>
                                    <tr>
                                        <td style="padding-right: 25px;">{{ $publicdata->custom_date }}</td>
                                        <td style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>

                                        <td style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda;"><b>Note: </b>{{ $publicdata->note }}</span></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                            </div>
                        </div>
                        <?php }else{} ?>

                        <?php /* --------------------- START EXTRA TIME AND COMMENT UPCOMING HOLIDAY DETAILS ------------------------- */ ?>

                        <div class="row">

                            <div class="col-md-8">
                                @if($data['next_threedata_exception_and_public'])
                                <div class="schedulenewtable">
                                    <table class="table-schedule-new">
                                        <span style="color: #004dda;"><b>Opening hours changes below.</b></span>
                                        <?php foreach($data['next_threedata_exception_and_public'] as $public_exception_data){
                                        $openpuplictimeholiday =  json_decode($public_exception_data->custom_time); ?>
                                        <tr>
                                            <td>{{ $public_exception_data->custom_date }}</td>
                                            <td>
                                                <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                            </td>
                                            <td>
                                                <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                            </td>
                                            <td><span style="color: #004dda;"><b>Note: </b>{{ $public_exception_data->note }}</span></td>
                                        </tr>
                                        <?php } ?>
                                    </table>
                                </div>
                                @endif
                            </div>

                            <div class="col-md-4">
                               @if($data['mainDirectoryDetails']->schedule_normal_comment)
                               <hr>
                                {{-- <h5> <i class="fa fa-comment-o" aria-hidden="true"></i> Note </h5>  --}}
                                <label class="col-md-12 p-0">{{ $data['mainDirectoryDetails']->schedule_normal_comment??'' }}</label>
                               @endif

                            </div>
                        </div>

                        <?php /* --------------------- END EXTRA TIME AND COMMENT UPCOMING HOLIDAY DETAILS -------------------------*/ ?>
                    </div>

                    <br>
                    @if((!empty($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer)) && (!empty($data['mainDirectoryDetails']->directoryLanguage[0]->about_us)))

                    <div class="row">
                        <span class="we-offer-details mobile-offerabout" id="weoffer-mobile">
                            <div class="col-md-12">
                                <div class="tabs-left" id="modal-tabs textaddtabs">
                                    <ul class="nav nav-tabs " id="tablistaddbtn" role="tablist" style="float: left;">
                                        <li class="nav-item" style="margin: 0px 20px 10px 0px; font-size: 18px;">
                                            <a class="active textshow"  href="#weoffermobile" data-toggle="tab">We Offer</a>
                                            And
                                        </li>
                                        <li class="nav-item" style="margin: 0px 0px 0px -15px; font-size: 18px;">
                                            <a class="textshow" href="#aboutuslistingmobile"  data-toggle="tab" >About Us</a>
                                            And
                                        </li>
                                        <li class="nav-item" style="margin: 0px 0px 0px 5px; font-size: 18px;">
                                            <a class="textshow" href="#us_mobile"  data-toggle="tab" >Us</a>
                                        </li>
                                    </ul>

                                </div>

                                <div class="tab-content" id="tab-content" style="display: inline-block;width: 100%;padding-top: 10px;">

                                    <div class="tab-pane active" id="weoffermobile">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer))
                                                    <div class="post-content textweoffer limit_we_offer" style="display:block">
                                                        {{ \Illuminate\Support\Str::words($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer, 17, '...') }}
                                                    </div>
                                                    <div class="post-content textweoffer1" style="display:none">
                                                        {{ $data['mainDirectoryDetails']->directoryLanguage[0]->we_offer }}
                                                    </div>
                                                    @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer) > 21)
                                                        {{-- <div class="show-more-weoffer" data-toggle="modal" data-target="#we_offer_details_model" style="cursor:pointer;">Read More</div> --}}
                                                        <div class="show-more-weoffer limit_we_offer_read_more" style="cursor:pointer;">Read More</div>
                                                    @endif
                                                @else
                                                    <div class="text-center">No Data Found</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="aboutuslistingmobile">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->about_us))

                                                    <div class="post-content textweoffer limit_about" style="display:block">
                                                        {{ \Illuminate\Support\Str::words($data['mainDirectoryDetails']->directoryLanguage[0]->about_us, 17, '...') }}
                                                    </div>
                                                    <div class="post-content textweoffer2" style="display:none">
                                                        {{ $data['mainDirectoryDetails']->directoryLanguage[0]->about_us }}
                                                    </div>
                                                    @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->about_us) > 18)
                                                        <div class="show-more-weoffer limit_about_read_more" style="cursor:pointer;">Read More</div>
                                                    @endif
                                                @else
                                                <div class="text-center">No Data Found</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane" id="us_mobile">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->description))

                                                    <div class="post-content textweoffer limit_us" style="display:block">
                                                        {{ \Illuminate\Support\Str::words($data['mainDirectoryDetails']->directoryLanguage[0]->description, 17, '...') }}
                                                    </div>
                                                    <div class="post-content textweoffer3" style="display:none">
                                                        {{ $data['mainDirectoryDetails']->directoryLanguage[0]->description }}
                                                    </div>
                                                    @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->description) > 18)
                                                        <div class="show-more-weoffer limit_us_read_more" style="cursor:pointer;">Read More</div>
                                                    @endif
                                                @else
                                                <div class="text-center">No Data Found</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </span>
                    </div>
                    @endif

                    <span class="desktop-offerabout we-offer-details" data-toggle="modal" data-target="#we_offer_details_model">
                    <button class="btn btn-sm" style="background: rgb(249 95 9); color:#fff;" title="We offer Details">
                        We Offer || About Us
                    </button>
                    </span>


                <div id="accordion" class="tree-stuc">
                    <!-- Listing Type Wise Inner Page -->
                    <?php
                    $category_id = isset($data['directoryTypeData'][0]->category_id)?$data['directoryTypeData'][0]->category_id:"";
                    if(!empty($category_id)){
                    ?>
                    <h5>
                    <?php
                        $typeName = Helper::getTypeCategory($category_id);
                    ?>
                    </h5>
                    <?php
                        $reverseData = array_reverse($data['directoryTypeData']);
                        $numberofblock = isset($reverseData[0]->block_num)?$reverseData[0]->block_num+1:"";
                        if(!empty($data['directoryTypeDataPosition'])){
                            $dtp_i=0;
                            foreach($data['directoryTypeDataPosition'] as $key => $positionvaluedata){

                                if($data['directoryTypeData'][$positionvaluedata->block_num]->column_value!=""){
                                $getDirectoryTypeGalleries = Helper::getDirectoryTypeGalleries($positionvaluedata->category_id,$positionvaluedata->block_num, $positionvaluedata->directory_id);
                                $getDirectoryTypePdf = Helper::getDirectoryTypePdf($positionvaluedata->category_id,$positionvaluedata->block_num, $positionvaluedata->directory_id);
                    ?>
                                <div class="room_type first schedulenewtable2 <?php if($dtp_i > 0){ ?> schedulenewtable_more <?php } ?>" style="display: <?php if($dtp_i > 0){ ?> none; <?php } ?>">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a role="button" data-toggle="collapse" href="#collapse_dd-<?php echo $positionvaluedata->block_num; ?>" aria-expanded="true" aria-controls="collapse_dd-<?php echo $positionvaluedata->block_num; ?>" class="toggle-btn @if($positionvaluedata->block_num != 0) collapsed @endif">
                                                <?php
                                                    foreach($data['directoryTypeData'] as $key => $typerow){
                                                        if ($typerow->block_num == $positionvaluedata->block_num && strtolower($typerow->column_name) == 'item') {
                                                        echo "<h4 style='background: #ebeff6; width: 100%;padding: 5px 10px; border-bottom: 1px solid rgba(0, 0, 0, .1);'>". htmlspecialchars($typerow->column_value, ENT_QUOTES, 'UTF-8') . "</h4>";
                                                    }
                                                    }
                                                ?>
                                                <span class="dropdown-icon">
                                                    <i class="fas fa-chevron-down"></i>
                                                </span>
                                            </a>
                                        </div>

                                        {{-- <div role="button" data-toggle="collapse" href="#collapse_dd-{{ $positionvaluedata->block_num }}" aria-expanded="true" aria-controls="collapse-1"> --}}
                                            <div id="collapse_dd-<?php echo $positionvaluedata->block_num; ?>" class="collapse row" aria-labelledby="collapse_dd-<?php echo $positionvaluedata->block_num; ?>" data-parent=".schedulenewtable2">
                                            <div class="col-md-4">
                                                <?php if($data['directoryTypeimage'][$positionvaluedata->block_num]->image_name!=""){ ?>
                                                    <img src="{{ env('IMAGE_PATH').'files/directorytypeimage/'.$data['directoryTypeimage'][$positionvaluedata->block_num]->image_name }}" class="img-fluid" alt="">
                                                <?php }else{ ?>
                                                    <img src="{{ asset('assets/client/images/ca79c3f27e26aa3d727ba906a4e21d64.jpg') }}" class="img-fluid" alt="">
                                                <?php } ?>
                                                @if(count($getDirectoryTypeGalleries) > 0)

                                                    <span style="color: #4fa94f;" onClick="view_type_gallery_button({{ $positionvaluedata->category_id }},{{ $positionvaluedata->directory_id }},{{ $positionvaluedata->block_num }})" class="closebtn see_more_gallery_type" id="view_type_gallery_button">
                                                        <i style="margin: 0px 5px;" class="fa fa-eye" aria-hidden="true"></i> See More
                                                    </span>
                                                @else
                                                    @if(count($getDirectoryTypePdf) > 0)
                                                        <span style="color: #dc3545" onClick="view_type_pdf_button({{ $positionvaluedata->category_id }},{{ $positionvaluedata->directory_id }},{{ $positionvaluedata->block_num }})" class="closebtn see_more_gallery_type" id="view_type_pdf_button">
                                                            <i style="margin: 0px 5px;" class="fa fa-eye" aria-hidden="true"></i> More PDF
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-md-8">
                                                <ul class="hotel_facilities">
                                                    <?php foreach($data['directoryTypeData'] as $typerow){
                                                        if($typerow->block_num == $positionvaluedata->block_num){
                                                            if($typerow->column_name!="position" && $typerow->column_name!="see_more_link"){ ?>
                                                            <li><i class="fas fa-hand-point-right" aria-hidden="true"></i>{{ " ".$typerow->column_value }}</li>
                                                        <?php  }else{ ?>
                                                            <?php if($typerow->column_name == "see_more_link" && $typerow->column_value != ""){ ?>
                                                            <br>
                                                                <span class="loc_open mr-2" style="background-color: #b5b9b7;color: #000!important;border: 1px solid #b5b9b7;border-radius: 50px;">
                                                                    <a target="_blank" href="{{ $typerow->column_value }}" style="color: #000;"><i class="fas fa-external-link-alt" aria-hidden="true"></i>  more link</a>
                                                                </span>
                                                        <?php
                                                            }
                                                            }
                                                            } }
                                                        ?>
                                                </ul>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        <?php $dtp_i++;  } } }else{ ?>
                            <?php if($numberofblock!=0 ){
                                for($i=0;$i<$numberofblock;$i++){
                                    if($data['directoryTypeData'][$i]->column_value!=""){ ?>
                                    <div class="room_type first">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <?php if($data['directoryTypeimage'][$i]->image_name!=""){ ?>
                                                    <img src="{{ env('IMAGE_PATH').'files/directorytypeimage/'.$data['directoryTypeimage'][$i]->image_name }}" class="img-fluid" alt="">
                                                <?php } else{ ?>
                                                <img src="{{ asset('assets/client/images/ca79c3f27e26aa3d727ba906a4e21d64.jpg') }}" class="img-fluid" alt="">
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-8">

                                                <ul class="hotel_facilities">
                                                    <?php foreach($data['directoryTypeData'] as $typerow){
                                                        if($typerow->block_num == $i){
                                                            if($typerow->column_name!="position" && $typerow->column_name!="see_more_link"){ ?>
                                                            <?php /* ?><li><label>{{ $typerow->label }}</label>: {{ $typerow->column_value }}</li><?php */ ?>
                                                            <li><i class="fas fa-hand-point-right" aria-hidden="true"></i>{{ " ".$typerow->column_value }}</li>
                                                    <?php  }else{ ?>
                                                        <?php if($typerow->column_name == "see_more_link" && $typerow->column_value != ""){ ?>
                                                            <li>
                                                                <span class="loc_open mr-2" style="background-color: #b5b9b7;color: #000!important;border: 1px solid #b5b9b7;border-radius: 50px;">
                                                                    <a target="_blank" href="{{ $typerow->column_value }}" style="color: #000;"><i class="fas fa-external-link-alt" aria-hidden="true"></i>  more link</a>
                                                                </span>
                                                            </li>
                                                        <?php } ?>
                                                    <?php } } } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                            <?php  } } } ?>
                        <?php } } //die; ?>
                        </div>
                        @if(count($data['directoryTypeData']) > 0)
                            <a id="more_type" class="more_type">
                                See More <i class="fas fa-chevron-down"></i>
                            </a>
                        @endif


                        <br>
                        <?php /* if($numberofblock!=0 ){
                            for($i=0;$i<$numberofblock;$i++){
                                if($data['directoryTypeData'][$i]->column_value!=""){ ?>
                                <div class="room_type first">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <?php if($data['directoryTypeimage'][$i]->image_name!=""){ ?>
                                                <img src="{{ env('IMAGE_PATH').'files/directorytypeimage/'.$data['directoryTypeimage'][$i]->image_name }}" class="img-fluid" alt="">
                                            <?php } else{ ?>
                                            <img src="{{ asset('assets/client/images/ca79c3f27e26aa3d727ba906a4e21d64.jpg') }}" class="img-fluid" alt="">
                                            <?php } ?>
                                        </div>
                                        <div class="col-md-8">

                                            <ul class="hotel_facilities">
                                                <?php foreach($data['directoryTypeData'] as $typerow){
                                                    if($typerow->block_num == $i){
                                                        if($typerow->column_name!="position"){ ?>
                                                        <?php /* ?><li><label>{{ $typerow->label }}</label>: {{ $typerow->column_value }}</li><?php */ ?>
                                                        <?php /* ?><li><i class="fas fa-hand-point-right" aria-hidden="true"></i>{{ " ".$typerow->column_value }}</li>
                                                <?php  } } } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                        <?php  } } }else{ ?>
                            <h4>No Data</h4>
                        <?php }  } */ //die; ?>





                    <!-- Video File Base On Package-->

                    <?php $vediolink = isset($data['mediaData'][0]->video_url)?$data['mediaData'][0]->video_url:"";
                    if(!empty($vediolink)){
                    ?>
                    <hr>

                    <h3>{{__("message.video_text")}}  </h3>
                    <div class="" style="text-align: center;">

                        <link rel="stylesheet" href="assets/client/images/plyr/plyr.css">
                        <div class="plyr__video-embed" id="player">
                        {{-- {{ $vediolink??'' }} --}}
                            <?php if($vediolink!=""){ ?>
                            <iframe width="100%" height="500" data-consent-category="marketing" data-consent-src="<?php echo e($vediolink) ?>?autoplay=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            <?php }else{ ?>
                            <iframe width="100%" height="500" data-consent-category="marketing" data-consent-src="https://player.vimeo.com/video/214125425?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <hr id="locationmap">
                    <!-- <h3>{{__("message.location")}}</h3> -->
                    <div id="map" class="map-full map-layout single-listing-map" style="z-index: 50;">
                        <a class="mobile_direction_a btn-block "  target="_blank" href="https://www.google.com/maps/dir//<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>/@<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>,16z" style="margin: -35px 10px; display:none;">
                            <img  class="direction" src="{{ url('/') }}/assets/client/images/direction.jpg" width="10%" alt="">
                        </a>
                        <iframe data-consent-category="functional" data-consent-src="https://maps.google.com/maps?q=<?php echo e($data['address'][0]->latitude); ?>,<?php echo e($data['address'][0]->longitude); ?>&amp;hl=es;z=14&amp;output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <!-- End Map -->
                </section>
                <!-- /section -->
                <!-- Section Of Review Starts -->
                <section id="reviews" style="display:none">
                    <h2>Reviews</h2>
                    <!-- Ratings starts -->
                    <div class="reviews-container add_bottom_30">
                        <div class="row">
                            <div class="col-lg-3">
                                <div id="review_summary">
                                    <strong>5.0</strong>
                                    <em>
                                        Awesome </em>
                                    <small>Based on 2 Reviews</small>
                                </div>
                            </div>
                            <div class="col-lg-9">
                                <!-- Rating Progeress Bar -->
                                <div class="row">
                                    <div class="col-lg-10 col-9">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3"><small><strong>1 Stars</strong></small></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 col-9">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3"><small><strong>2 Stars</strong></small></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 col-9">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3"><small><strong>3 Stars</strong></small></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 col-9">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3"><small><strong>4 Stars</strong></small></div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-10 col-9">
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-3"><small><strong>5 Stars</strong></small></div>
                                </div>
                            </div>
                        </div>
                        <!-- /row -->
                    </div>
                    <!-- Ratings ends -->
                    <div class="reviews-container">
                        <!-- Single Review Starts -->
                        <div class="review-box clearfix">

                            <figure class="rev-thumb"><img src="{{ asset('assets/client/images/user.png') }}" alt=""></figure>
                            <div class="rev-content">
                                <div class="rating">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                                <div class="rev-info">
                                    Jane Roie – Wed, 15-May-2019:
                                </div>
                                <div class="rev-text">
                                    <p>
                                        Beautiful room, comfortable bed, excellent location, great breakfast, the owner is the perfect host, parking provided for the car, soft towels, good quality sheets, complimentary water, charming hotel.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="review-box clearfix">
                            <figure class="rev-thumb"><img src="{{ asset('assets/client/images/user.png') }}" alt=""></figure>
                            <div class="rev-content">
                                <div class="rating">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </div>
                                <div class="rev-info">
                                    Jane Roie – Wed, 15-May-2019:
                                </div>
                                <div class="rev-text">
                                    <p>
                                        Beautiful, elegant rooms. Fabulous wooden beams. Very comfortable, clean and spacious. High ceilings. Great bathroom. Gated off-street parking. Perfectly positioned to wander into the old town centre on foot. Everything you want to see within easy walking distance. Expensive but worth the price.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!-- Single Review Endss -->
                    </div>
                    <!-- /review-container -->
                </section>

                <div class="row" style="display:none">
                    <div class="col-12">
                        <a href="login.html" class="btn btn-warning float-right">Login to review</a>
                    </div>
                    <div class="col-12 mt-1">
                        <a href="login.html" class="btn btn-sm btn-warning float-right">Claim this listing</a>
                    </div>
                </div>
                <br />
                <!-- Leave a review ends -->
            </div>

            <!-- Contact Form Base On Package-->
                <?php
                    $notActivateForm = 0;
                    $validPhone = $data['mainDirectoryDetails']->user->is_verified ?? "blank";
                    if($validPhone=="default" || $validPhone=="blank"){
                        $disbl = "disabled";
                    }else{
                        $disbl = "";
                        $notActivateForm = 1;
                    }
                    //dd($notActivateForm);
                ?>
            <aside class="col-lg-4 " id="sidebar">

                <?php if($notActivateForm==0){ ?>
                <div class="notactivate">
                    <div class="overlay-content">Not Activated</div>
                <?php } ?>

                    <div class="box_detail booking ">
                        @if(session()->get('status'))
                        <div class="alert alert-success">
                            {{ session()->get('status') }}
                        </div><br />
                        @endif

                        @php $dir_cat = ''; @endphp
                        @foreach ($data['categoriesData'] as $key => $catdata)
                            @php
                                if("accomendations" == $catdata->slug || "food-nightlife" == $catdata->slug){ $dir_cat = 1; }
                            @endphp
                        @endforeach

                        <form id="bookingform" class="contact-us-form" action="{{ route('appointment.booking') }}" method="post"  >
                            {{ csrf_field() }}

                            <input type="hidden" name="user_id" value="{{ $data['mainDirectoryDetails']->user?$data['mainDirectoryDetails']->user->id:0 }}">
                            <input type="hidden" name="requester_id" value="">
                            <input type="hidden" name="listing_id" value="{{ $data['mainDirectoryDetails']->id }}">
                            <input type="hidden" name="listing_type" value="{{ $data['mainDirectoryDetails']->feature_type }}">
                            <input type="hidden" name="slug" value="clarance-hotel">
                            <input type="hidden" name="type" value="booking">
                            <input type="hidden" name="bookingComment" value="">
                            <div class="price">
                                <span class="contact">{{__("message.contact_us_title")}}</span>
                                <span class="respondata"></span>

                                <div class="score"><span>Awesome<em>2 Reviews</em></span><strong>5.0</strong></div>
                            </div>

                            <div class="form-group" id="input-dates">
                                <input type="hidden" name="directory_id" id="directory_ids" value="{{ $data['mainDirectoryDetails']->id }}">

                                <input class="form-control required" type="text" name="name" required="" placeholder="{{__("message.placeholder_name_text")}}" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <input class="form-control required" type="email" name="email" required="" placeholder="Enter e-mail address!">
                            </div>
                            <div class="form-group">
                                <label class="col-md-12 p-0"><b><u>Note:</u> No country code!</b></label>
                                <input class="form-control required" type="tel" name="booking_phone" required="" placeholder="Enter Valid Phone!">
                            </div>
                            <?php if(isset($userData->visibility_booking_date_time) && $userData->visibility_booking_date_time==1){ ?>
                                <div class="form-group">
                                    <input class="form-control required" title="Select date and time for the desired booking" type="text" name="booking_date" id="booking_date" required="" placeholder="Select date and time for the desired booking">
                                </div>
                            <?php } ?>
                            <div class="form-group">
                            <select class="form-control" name="service">
                                @foreach ($data['directory_services'] as $ds_val)
                                @php $serviceName = data_get($ds_val, 'name', ''); @endphp
                                <option value="{{ $serviceName }}">{{ $serviceName }}</option>
                                @endforeach
                                <option value="Contact us Content">Contact us Content</option>
                            </select>

                            </div>

                            @if($dir_cat == 1)
                                <div class="form-group">
                                    <input class="form-control" type="number" value="" name="adult" placeholder="{{__("message.placeholder_no_of_adult")}}">
                                </div>
                                <div class="form-group">
                                    <input class="form-control" type="number" value="" name="child" placeholder="{{__("message.placeholder_no_of_child")}}">
                                </div>
                            @endif

                            <div class="form-group">
                                <label class="col-md-12 p-0">{{__("message.message_label_text")}}</label>
                                <textarea rows="5" class="form-control" name="message"></textarea>
                            </div>

                            <button type="submit" class="add_top_30 btn_1 full-width purchase submitbtn" {{ $disbl }}>{{__("message.submit_button")}}</button>
                        </form>

                        <a style="display:none;" href="javascript:" onclick="addToWishList('6')" class="btn_1 full-width outline wishlist" id="btn-wishlist"><i class="fa fa-heart" aria-hidden="true"></i> Add to wishlist</a>
                        <div class="text-center"><small>{{__("message.contact_us_form_text")}}</small></div>
                    </div>

                <?php if($notActivateForm==0){ ?>
                </div>
                <?php } ?>
                <ul class="share-buttons">
                    <li>
                        <?php
                            $title=urlencode($data['mainDirectoryDetails']->title);
                            $url=urlencode(url()->current());
                            $image=urlencode(url(env('IMAGE_PATH').'images/directory/'.$bannerImage.'.webp'));
                        ?>
                        <a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title;?>&amp;p[url]=<?php echo $url; ?>&amp;&p[images][0]=<?php echo $image;?>', 'sharer', 'toolbar=0,status=0,width=548,height=325');" target="_parent" class="fb-share" href="javascript: void(0)">
                            <i class="fa fa-facebook" aria-hidden="true"></i> Share
                        </a>
                    </li>
                </ul>


                <hr>
                    <div class="row mb-3" >
                        <div class="col-12  listing-social-btn">
                            <!-- <h5 class="mb-3">{{__("message.agent_details")}}</h5> -->
                            <?php /* ?>
                                    <div class="row mb-1">
                                        <div class="col-md-12">
                                            <a href="javascript:void(0)">
                                                <img src="{{ asset('assets/client/images/user.png') }}" alt="" class="float-left mr-3" width="80">
                                            </a>

                                            <p class="m-0 pt-3"><a href="javascript:void(0)" class="">{{ isset($contactData[0]->name)?$contactData[0]->name:'' }}</a></p>
                                            <p><small>{{__("message.listing_count")}}</small></p>
                                        </div>
                                    </div>
                            <?php */ ?>
                            <a href="{{ isset($contactData[0]->website)?$contactData[0]->website:'' }}" target="blank" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-globe" aria-hidden="true"></i>
                                {{__("message.website_contactdata")}}
                            </a>

                            @if($data['frontend_setting']->hide_show_vendor_contact == 'yes')

                            <a href="mailto:{{ isset($contactData[0]->email)?$contactData[0]->email:'' }}" target="" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-envelope" aria-hidden="true"></i>
                                {{__("message.email_us_contactdata")}}
                            </a>
                            <?php
                                $phoneActivateorNot = 0;
                                $validPhoneno = $data['mainDirectoryDetails']->user->is_verified ?? "blank";
                                if($validPhoneno=="default" || $validPhoneno=="blank"){
                                    $disabl = "disabled";
                                    $phoneActivateorNot = 0;
                                }else{
                                    $disabl = "";
                                    $phoneActivateorNot = 1;
                                }
                            ?>
                            <?php if($ismobiledevice==1){ ?>
                                <a href="tel:+{{ isset($contactData[0]->phone)?$contactData[0]->phone:'' }}" target="" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-phone" aria-hidden="true"></i>{{__("message.call_now_contactdata")}}</a>
                            <?php }else{ if($phoneActivateorNot!=1){ ?>
                                    <a href="javascript:void(0);" target="" style="pointer-events: none;cursor: default;background-color:#b5b9b7;" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-phone" aria-hidden="true"></i>Call </a>
                                <?php }else{ ?>
                                    <a href="javascript:void(0);" target="" class="btn_1 full-width outline wishlist social-button" style="width: 158px !important;" id="btn-wishlist-social"><i class="fa fa-phone" aria-hidden="true"></i>Call at - {{ isset($contactData[0]->phone)?$contactData[0]->phone:'' }}</a>
                            <?php } }?>
                            @endif

                        </div>
                    </div>

                <?php
                $user = auth()->guard('web')->check();
                if($user==1){
                    if($data['mainDirectoryDetails']->id==Auth::user()->directory_id){
                ?>
                    <form action="{{ route('company.booking.indexsearch') }}" method="get" class="">
                    <div class="row">
                        <div class="col-md-5">
                            <h5 class="box-title">Booking List</h5>

                        </div>

                        <div class="col-md-7">
                            <div class="d-md-flex">

                                <div class="input-group mb-3">
                                    <input type="text"  name="searchtext" id="search" value="" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <input class="btn btn-primary" name="search" type="submit" value="Go!" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </form>
                    <div class="table-wrapper">
                    <table class="table text-nowrap">
                            <thead>
                                <tr>

                                    <th class="border-top-0">S.No</th>
                                    <th class="border-top-0">Name | Email | Phone</th>
                                    <th class="border-top-0">Booking Date</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            //dd($data['bookingdata']);
                            $i = 1; $datacount = count($data['bookingdata']);
                            if ($datacount>0){
                            ?>
                                @foreach($data['bookingdata'] as $bookingdatarow)

                                @php
                                    $getFeatureLinksValue = Helper::getReplyRequestId($bookingdatarow->id);
                                @endphp
                                    <tr>
                                        <td>{{ $i++ }} </td>

                                        <td>
                                            {!! Str::words($bookingdatarow->name, 3, '...') !!}
                                            <br>
                                            {{ $bookingdatarow->email }}
                                            <br>
                                            {{ $bookingdatarow->phone }}
                                        </td>

                                        <td>
                                            <?php
                                            if($bookingdatarow->booking_date){
                                                $bookingdateandtime = explode(" ", $bookingdatarow->booking_date);?>

                                                        {{ $bookingdateandtime[0]??'' }}:
                                                        {{ $bookingdateandtime[1]??'' }}

                                            <?php }else{

                                                if($bookingdatarow->created_date){
                                                $creatingDateandTime = explode(" ", $bookingdatarow->created_date);
                                                ?>
                                                        {{ $creatingDateandTime[0]??'' }}:
                                                        {{ $creatingDateandTime[1]??'' }}

                                            <?php } } ?>
                                        </td>

                                        <td>
                                            @if($bookingdatarow->status == null)
                                            Pending
                                            @else
                                            {{ $bookingdatarow->status }}
                                            @endif
                                        </td>
                                        <td>

                                        @if ($bookingdatarow->notify == '0')
                                        <a class="btn btn-warning btn-sm" href="{{ route('company.booking.edit',$bookingdatarow->id) }}">VIEW</a>
                                        @endif
                                            <a class="btn btn-primary btn-sm" href="{{ route('company.booking.edit',$bookingdatarow->id) }}">EDIT</a>
                                            <a class="btn btn-danger btn-sm show_confirm" href="{{ route('company.booking.destroy',$bookingdatarow->id) }}">DELETE</a>

                                        </td>
                                    </tr>
                                @endforeach



                            <?php }else{ ?>
                                    <tr>
                                        <td align="center" colspan="4">No Data Found</td>
                                    </tr>
                            <?php } ?>
                            </tbody>
                    </table>
                </div>
                <?php } } ?>
            </aside>
        </div>
        <!-- /row -->
    </div>

    <!-- /container -->

    <?php
        $arrayCountRelatedProduct = count($data['relatedProductData']);
        if($arrayCountRelatedProduct>0){
    ?>
    <div class="container-fluid">
        <div class="main_title_2">
            <h2>{{__("message.related_product")}}</h2>
        </div>
        <div id="product" class="owl-carousel owl-theme listing_related_product">
            <?php
            foreach($data['relatedProductData'] as $productrow){
              $productprice = Helper::getProductprice($productrow->id,$currencycode);
              $validityOfOffer = Helper::getValidityOfOffer($productrow->id);
              //echo "<pre>"; print_r($validityOfOffer);
              $productcurrency = isset($productprice[0]->currency_code)?$productprice[0]->currency_code:"";
              $productSalesPrice = isset($productprice[0]->sales_price)?$productprice[0]->sales_price:0.00;
              $productRegularPrice = isset($productprice[0]->regular_price)?$productprice[0]->regular_price:0.00;

              if(!empty($validityOfOffer)){
                if($productRegularPrice>0 && $productSalesPrice>0){
                  $priceGoestoCart = $productSalesPrice;
                }else{
                  $priceGoestoCart = $productRegularPrice;
                }

              }else{
                $priceGoestoCart = $productRegularPrice;
              }
          ?>
            <div class="item product_listing">
                <div class="strip grid">
                    <?php
                    $getNewShopping = Helper::getNewShopping($productrow->id);
                    $getNewShoppingOffer = Helper::getNewShoppingOffer($productrow->id);
                    $getNewOffer = Helper::getNewOffer($productrow->id);
                    $getProductOffers = Helper::getProductOffers($productrow->id);
                    ?>
                    @if($getNewOffer == 1 || $getNewShopping == 1)
                        <img src="{{ asset('assets/client/images/new-offer.png') }}" class="img-fluid offericon newicon" alt="" width="400" height="266">
                    @endif
                    @if($getProductOffers == 1 || $getNewShoppingOffer == 1)
                            <img src="{{ asset('assets/client/images/sho_offer.png') }}" class="img-fluid offericon new-shopping-offer" alt="" width="400" height="266">
                    @endif
                    <figure>
                        <input class="product-wishlist" type="hidden" value="{{ $productrow->id }}" name="product_id" id="product_id">
                        <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">

                        <?php $imagename = Helper::getProductImage($productrow->id); ?>
                        <a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>" class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')" <?php } ?>>
                            <div class="read_more">

                                {{-- <form action="{{ route('add.to.cart',$productrow->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" value="{{ $productrow->id }}" name="id">
                                    <input type="hidden" value="{{ $productrow->product_lang_name }}" name="name">
                                    <input type="hidden" value="{{ $priceGoestoCart }}" name="price">
                                    <input type="hidden" value="{{ $productRegularPrice }}" name="regularprice">
                                    <input type="hidden" value="{{ isset($imagename[0]->name_thumbnail)?$imagename[0]->name_thumbnail:'' }}" name="image">
                                    <input type="hidden" value="1" name="quantity">

                                    @if ($productrow->stock > 0 && !$productrow->outofstock == 'outofstock')
                                        <button class="btn "><i class="fa fa-shopping-bag"></i>{{__("message.add_to_cart_btn")}}</button>

                                    @else
                                        <span style=" color:red;">Out Of Stock</span>

                                    @endif
                                </form> --}}
                                <button class="btn "><i class="fa fa-eye"></i> View More </button>
                            </div>
                            <!-- <div class="read_more"><span> Add To Cart</span></div> -->
                            <div class="pro-icn">
                                {{-- <a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a> --}}
                                <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="w-c-q-icn" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                                    <?php
                                    if(!in_array($productrow->id, $wislistProductId)){ ?>
                                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                                        <?php }else{ ?>
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                    <?php } ?>
                                </a>
                                {{-- <a href="" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a> --}}
                            </div>
                        </a>
                        <!-- <small>Hotel</small> -->
                    </figure>
                    <div class="wrapper featured-body">
                        <h3 class="ellipsis"><a href="<?php echo url("product/detail", ['id' => @$productrow->id,'slug' => @$productrow->seo_url]); ?>">{{ $productrow->product_lang_name }}</a></h3>
                        <!-- <small>Lille, France</small> -->
                        <p class="ellipsis">{{ strip_tags($productrow->short_content) }}</p>
                    </div>

                    <ul class="featured-footer mb-0">
                        <?php if(!empty($validityOfOffer)){  ?>
                        <li>
                            <?php if($productSalesPrice>0){ ?>
                            <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productSalesPrice }}</span></div>
                            <?php } ?>
                            <?php if($productRegularPrice>0 && $productSalesPrice==0){?>
                            <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                            <?php }else if($productRegularPrice>0){ ?>
                            <div class="pro-price"><span class="old-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                            <?php } ?>
                        </li>
                        <?php }else{ ?>
                        <li>
                            <div class="pro-price"><span class="new-price notranslate">{{ $currencySymbol." ".$productRegularPrice }}</span></div>
                        </li>
                        <?php } ?>

                        <li style="display:none;">
                            <div class="score"><span> Awesome <em> 2 Reviews </em> </span><strong>5.0</strong></div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php } ?>
        </div>
        @if(count($data['relatedProductData']) > 5)
            <div class="container-fluid">
                <div class="btn_home_align"><a href="<?php echo url('listings-product', ['id' => $data['mainDirectoryDetails']->slug]); ?>" class="btn_1 rounded">{{__("message.view_all")}}</a></div>
            </div>
            <br>
        @endif
    </div>
    <?php } ?>

     <?php if(count($most_viewed_listings) > 0){ ?>
    <div class="container-fluid  margin_80_55">

        <div class="main_title_2">
            <span><em></em></span>
            <a class="btn_1 rounded btn-sm" id="click_viewed_listings" style="color: #fff;font-weight: 500; padding: 5px 10px; margin: 10px 0px;">
                Click for Most Viewed Listings
            </a>
        </div>
        <div class="most_viewed_listings_div" style="display: none;">
        <div class="main_title_2">

            <h2>Most Viewed Listings</h2>
        </div>
        <div id="sploffer" class="owl-carousel owl-theme listing_gllary_img most_viewed_listings">
            <?php foreach($most_viewed_listings as $row){ ?>
			<?php $imagename = Helper::getDirectoryimage($row->id);
			$getDirectorySlug = Helper::getDirectorySeoSlug($row->id);

			?>
            <div class="item">
                <div class="strip grid">
                    <figure>
                    <input class="product-wishlist" type="hidden" value="{{ $row->id }}" name="product_id" id="product_id">
                    <input type="hidden" name="user_id" id="user_id" value="{{isset(Auth()->user()->id)?Auth()->user()->id:0}}">
                        <a href="javascript:void(0);" productid="{{ $row->id }}" class="wishlist-icon" onclick="addDirToWishList(this, <?php echo $row->id ?>)">
                            <?php
                            if(!in_array($row->id, $wislistdirectoryId)){ ?>
                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <?php }else{ ?>
                                <i class="fa fa-heart" aria-hidden="true"></i>
                            <?php } ?>
                        </a>
                    <span class="featured-tag-grid">
                    @if($row->feature_type == 'superfeatured')
						Super featured
					@else
						Featured
					@endif
                    </span>
					<?php if(!empty($imagename)){ ?>
                        <a href="<?php echo url('listings/details', ['id' => $getDirectorySlug->slug??'']); ?>">
                            <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}" class="img-fluid" alt="" width="400" height="266">
                        </a>
					<?php }else{ ?>
						<a href="<?php echo url('listings/details', ['id' => $getDirectorySlug->slug??'']); ?>">
                            <img src="{{ asset('assets/client/images/98008ded21258aa9c869d4f64a6ba7b6.jpg') }}" class="img-fluid" alt="" width="400" height="266">
                        </a>
					<?php } ?>

                    </figure>
                    <p class="featured-footer mb-0" style="text-align: center;">
							<a>{{ $row->title}}</a>
					</p>
                </div>
            </div>
            <?php } ?>
        </div>
        </div>

    </div>
    <?php } ?>

    <script src="https://mozilla.github.io/pdf.js/build/pdf.mjs" type="module"></script>

    <script>

        // Google tag event for vendor page view
        @if(app()->environment('production'))
            gtag('event', 'vendor_page_view', {
                vendor_id: '{{ $data["mainDirectoryDetails"]->id }}',
                entry_source: '{{ url()->previous() }}'
            });
        @endif

        $('.limit_we_offer_read_more').on("click", function() {
            $('.limit_we_offer').attr('style', 'display:none !important;');
            $('.limit_we_offer_read_more').hide();
            $('.textweoffer1').show();
        });
        $('.limit_about_read_more').on("click", function() {
            $('.limit_about').attr('style', 'display:none !important;');
            $('.limit_about_read_more').hide();
            $('.textweoffer2').show();
        });
        $('.limit_us_read_more').on("click", function() {
            $('.limit_us').attr('style', 'display:none !important;');
            $('.limit_us_read_more').hide();
            $('.textweoffer3').show();
        });

    $('#btn_defaulttags').on('click', function() {
        const $tags = $('.defaulttags');
        $tags.toggle(); // Show/hide tags

        // Update button text
        if ($tags.is(':visible')) {
            $(this).html('See Less <i class="fas fa-chevron-up"></i>');
        } else {
            $(this).html('See More <i class="fas fa-chevron-down"></i>');
        }
    });

    $('#more_type').on('click', function() {
        const $tags = $('.schedulenewtable_more');
        $tags.toggle(); // Show/hide tags
        // Update button text
        if ($tags.is(':visible')) {
            $(this).html('See Less <i class="fas fa-chevron-up"></i>');
        } else {
            $(this).html('See More <i class="fas fa-chevron-down"></i>');
        }
    });



    $('.tag_delete').on('click', function() {
        var imp_tag_id = $(this).data('impid');
        var company_id = $('#directory_ids').val();
        $.ajax({
                url: baseUrl + '/delete-imppro-tagsbyid',
                type: "POST",
                dataType: "json",
                data: {
                    imp_tag_id: imp_tag_id,
                    company_id:company_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(result) {
                console.log('Result:', result);

                if (result.status === true) {

                    alert(result.message || 'Something went wrong');
                    // const container = $("#company_imp_tags_div");
                    // container.empty();
                    // const baseListingUrl = `{{ url('listings-product/' . $data['mainDirectoryDetails']->slug) }}`;
                    // $.each(result.data, function(_, value) {
                    //     container.append(`
                    //         <span class="loc_open mr-2 notranslate"
                    //             style="background-color: #b5b9b7; color: #000 !important; border: 1px solid #b5b9b7; padding: 3px 6px; border-radius: 4px; display: inline-flex; align-items: center;">
                    //             <a href="${baseListingUrl}?tags=${value.ptag_id}"  target="_blank"
                    //             class="company_imp_tags2"
                    //             data-companyid="${value.company_id}"
                    //             data-id="${value.ptag_id}"
                    //             data-title="${value.ptag_name}" style="color: #000; text-decoration: none; margin-right: 5px;">
                    //                 ${value.ptag_name}
                    //             </a>
                    //             <i class="fa fa-times tag_delete"
                    //             data-impid="${value.ptag_id}"
                    //             style="cursor:pointer;"></i>
                    //         </span>
                    //     `);
                    // });
                    setTimeout(function(){
                        window.location.reload(); // you can pass true to reload function to ignore the client cache and reload from the server
                    },1000);

                } else {
                    alert(result.message || 'Something went wrong');
                }
            }
        });

    });

    $('.company_imp_tags').on('click', function() {

            var tag_id = $(this).data('id');
            var tag_companyid = $(this).data('companyid');
            var tag_title = $(this).data('title');
            var company_id = $('#directory_ids').val();

        $.ajax({
                url: baseUrl + '/getvendor-imppro-tagsbyid',
                type: "POST",
                dataType: "json",
                data: {
                    tag_id: tag_id,
                    tag_title: tag_title,
                    company_id:company_id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(result) {
                console.log('Result:', result);

                if (result.status === true) {
                    const container = $("#company_imp_tags_div");
                    container.empty();

                    const baseListingUrl = `{{ url('listings-product/' . $data['mainDirectoryDetails']->slug) }}`;

                    $.each(result.data, function(_, value) {
                        container.append(`
                            <span class="loc_open mr-2 notranslate"
                                style="background-color: #b5b9b7; color: #000 !important; border: 1px solid #b5b9b7; padding: 3px 6px; border-radius: 4px; display: inline-flex; align-items: center;">
                                <a href="${baseListingUrl}?tags=${value.ptag_id}"  target="_blank"
                                class="company_imp_tags2"
                                data-companyid="${value.company_id}"
                                data-id="${value.ptag_id}"
                                data-title="${value.ptag_name}" style="color: #000; text-decoration: none; margin-right: 5px;">
                                    ${value.ptag_name}
                                </a>
                                <i class="fa fa-times tag_delete(this)"
                                data-impid="${value.ptag_id}"
                                style="cursor:pointer;"></i>
                            </span>
                        `);
                    });
                } else {
                    alert(result.message || 'Something went wrong');
                }
            }
        });
    });

    $('#country').on('change', function() {
        var country_id = this.value;
        $("#state").html('');
        $.ajax({
            url: baseUrl + '/get-countrybyid'
            , type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result2) {
                // $('#countrycode').prop('readonly', true);
                // $('#countrycode').val(result2.phonecode);
                // $('#countrycode_m').prop('readonly', true);
                // $('#countrycode_m').val(result2.phonecode);
            }
        });

        $.ajax({
            url: baseUrl + '/get-states-by-country'
            , type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                $('#state').html('<option value="">Select State</option>');
                $.each(result.states, function(key, value) {
                    $("#state").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    });

    $('#state').on('change', function() {
            var state_id = this.value;
            $("#city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
                    if(result.cities.length== 0){
                        document.getElementById('city_name').style.display = "block";
                        document.getElementById('city_dropdown').style.display = "none";
                    }else{
                        document.getElementById('city_name').style.display = "none";
                        document.getElementById('city_dropdown').style.display = "block";

                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                }
            });
    });



        document.addEventListener('DOMContentLoaded', () => {
            const toggleButtons = document.querySelectorAll('.description_toggle');

            toggleButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const content = button.previousElementSibling; // Target the content
                    const isExpanded = content.classList.contains('expanded');

                    if (isExpanded) {
                        content.classList.remove('expanded');
                        button.textContent = 'Read More';
                    } else {
                        content.classList.add('expanded');
                        button.textContent = 'Read Less';
                    }
                });
            });
        });

        function view_type_gallery_button(cat_id,directory_id,block_num) {
            $('.AJloader').show();
                $.ajax({
                    url: baseUrl + '/get-directory-type-galleries',
                    type: "POST",
                    data: {
                        cat_id: cat_id,
                        directory_id: directory_id,
                        block_num: block_num,
                        _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        $('.AJloader').hide();

                        $("#type_gallery_list").html('');
                        $('#type_galleryModal').modal('show');

                        $.each(result.DirectoryTypeGallery, function(key, value) {
                            $("#type_gallery_list").append(`

                            <div class="image-thumbnail existing-img col-md-2 col-2" id="gtype_key_${value.id}">
                                <a href="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" data-lightbox="allGalleryImages">
                                    <img data-id="${value.id}" src="{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.image_name}" class="previewBlocks gtype_img" style="width: 150px; height: auto; border: 1px solid #ccc; padding:5px; margin: 5px 5px;">
                                </a>
                            </div>

                            `);
                        });
                    }
                });
        };

        function view_type_pdf_button(cat_id,directory_id,block_num) {
            $('.AJloader').show();
                $.ajax({
                    url: baseUrl + '/get-directory-type-pdf',
                    type: "POST",
                    data: {
                        cat_id: cat_id,
                        directory_id: directory_id,
                        block_num: block_num,
                        _token: '{{csrf_token()}}'
                    }
                    , dataType: 'json'
                    , success: function(result) {
                        $('.AJloader').hide();

                        $("#type_pdf_list").html('');
                        $('#type_pdf_Modal').modal('show');

                        // $.each(result.DirectoryTypePdf, function(key, value) {

                        //     let url = `{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.pdf_name}?embedded=true`;
                        //         // If absolute URL from the remote server is provided, configure the CORS
                        //         // header on that server.

                        //         // Loaded via <script> tag, create shortcut to access PDF.js exports.
                        //         var { pdfjsLib } = globalThis;
                        //         // The workerSrc property shall be specified.
                        //         pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.mjs';
                        //         var pdfDoc = null,
                        //             pageNum = 1,
                        //             pageRendering = false,
                        //             pageNumPending = null,
                        //             scale = 0.8,
                        //             canvas = document.getElementById('the-canvas'),
                        //             ctx = canvas.getContext('2d');

                        //         function renderPage(num) {
                        //             pageRendering = true;
                        //             // Using promise to fetch the page
                        //             pdfDoc.getPage(num).then(function(page) {
                        //             var viewport = page.getViewport({scale: scale});
                        //             canvas.height = viewport.height;
                        //             canvas.width = viewport.width;

                        //             // Render PDF page into canvas context
                        //             var renderContext = {
                        //                 canvasContext: ctx,
                        //                 viewport: viewport
                        //             };
                        //             var renderTask = page.render(renderContext);
                        //             // Wait for rendering to finish
                        //             renderTask.promise.then(function() {
                        //                 pageRendering = false;
                        //                 if (pageNumPending !== null) {
                        //                 // New page rendering is pending
                        //                 renderPage(pageNumPending);
                        //                 pageNumPending = null;
                        //                 }
                        //             });
                        //             });

                        //         }

                        //         //const url = `${env('IMAGE_PATH')}files/directorytypeimage/${value.pdf_name}?embedded=true`;
                        //         pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
                        //             pdfDoc = pdfDoc_;
                        //             document.getElementById('page_count').textContent;
                        //             // Initial/first page rendering
                        //             renderPage(pageNum);
                        //         });

                        //     $("#type_pdf_list").append(`

                        //     <div class="col-md-12 gallerytype_pdf" data-id="${value.id}" data-position="${value.position}" data-gallerytypeimages="${value.pdf_name}" id="pdf_type_key_${key}">

                        //             <div>
                        //             <span id="page_count"></span>
                        //             </div>
                        //             <canvas id="the-canvas"></canvas>

                        //     </div>

                        //     `);

                        // });

                        $.each(result.DirectoryTypePdf, function(key, value) {

                        let url = `{{ env('IMAGE_PATH') }}files/directorytypeimage/${value.pdf_name}?embedded=true`;

                        console.log('url', url);

                        var { pdfjsLib } = globalThis;
                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.mjs';

                        var pdfDoc = null,
                            pageNum = 1,
                            pageRendering = false,
                            pageNumPending = null,
                            scale = 3.2;

                        // Function to render a PDF page
                        function renderPage(num, canvas, ctx) {
                            pageRendering = true;

                            pdfDoc.getPage(num).then(function(page) {
                                var viewport = page.getViewport({ scale: scale });
                                canvas.height = viewport.height;
                                canvas.width = viewport.width;

                                var renderContext = {
                                    canvasContext: ctx,
                                    viewport: viewport
                                };
                                var renderTask = page.render(renderContext);

                                // Wait for rendering to finish
                                renderTask.promise.then(function() {
                                    pageRendering = false;
                                    if (pageNumPending !== null) {
                                        renderPage(pageNumPending, canvas, ctx);
                                        pageNumPending = null;
                                    }
                                });
                            });

                            // Update page counters
                            document.getElementById(`page_num_${key}`).textContent = num;
                        }

                        // Queue render for a new page if rendering is already in progress
                        function queueRenderPage(num, canvas, ctx) {
                            if (pageRendering) {
                                pageNumPending = num;
                            } else {
                                renderPage(num, canvas, ctx);
                            }
                        }

                        // Previous page button logic
                        function onPrevPage(canvas, ctx) {
                            if (pageNum <= 1) {
                                return;
                            }
                            pageNum--;
                            queueRenderPage(pageNum, canvas, ctx);
                        }

                        // Next page button logic
                        function onNextPage(canvas, ctx) {
                            if (pageNum >= pdfDoc.numPages) {
                                return;
                            }
                            pageNum++;
                            queueRenderPage(pageNum, canvas, ctx);
                        }

                        // Fetch the PDF document and render it once the document is loaded
                        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
                            pdfDoc = pdfDoc_;

                            // Append the HTML content and ensure the canvas is in the DOM
                            $("#type_pdf_list").append(`
                                <div class="col-md-12 gallerytype_pdf" data-id="${value.id}" data-position="${value.position}" id="pdf_type_key_${key}">
                                    <div>
                                        <button class="previous_pdf" id="prev_${key}">Previous</button>
                                        <button class="next_pdf" id="next_${key}">Next</button>
                                        &nbsp; &nbsp;
                                        <span>Page: <span id="page_num_${key}"></span> / <span> Total: ${pdfDoc.numPages}</span></span>
                                    </div>

                                    <canvas class="canvas_pdf"  id="the-canvas-${key}"></canvas>
                                </div>
                            `);

                            // Access the newly appended canvas
                            var canvas = document.getElementById(`the-canvas-${key}`);
                            var ctx = canvas.getContext('2d');

                            // Initial page rendering
                            renderPage(pageNum, canvas, ctx);

                            // Add event listeners for the dynamically created buttons
                            document.getElementById(`prev_${key}`).addEventListener('click', function() {
                                onPrevPage(canvas, ctx);
                            });
                            document.getElementById(`next_${key}`).addEventListener('click', function() {
                                onNextPage(canvas, ctx);
                            });
                        }).catch(function(error) {
                            console.error("Error loading PDF:", error);
                        });
                    });





                    }
                });
        };

        function showToggle(elem, selector) {
            $('.'+selector).slideToggle();
            console.log($(elem).text());
            if($(elem).text() === "Show more")
            {
                $(elem).text('Show less');
            }
            else
            {
                $(elem).text('Show more');
            }
        }

        function updateClock() {
            var now = new Date();
            var hours = now.getHours();
            var minutes = now.getMinutes();
            var seconds = now.getSeconds();
            // Add leading zero if needed
            hours = (hours < 10) ? '0' + hours : hours;
            minutes = (minutes < 10) ? '0' + minutes : minutes;
            seconds = (seconds < 10) ? '0' + seconds : seconds;

                var timeString = hours + ':' + minutes;
                var get_endtime = $('.get_endtime').val();

                if ((timeString > get_endtime) && (!localStorage.getItem('key_endtime'))) {

                    const key = 'key_endtime';
                    const value = 'endtime';
                    const expirationTimeInMinutes = 1;

                    const currentTime = new Date().getTime();
                    const expirationTime = currentTime + expirationTimeInMinutes * 60 * 1000;

                    const item = {
                        value: value,
                        expirationTime: expirationTime,
                    };
                    localStorage.setItem(key, JSON.stringify(item));

                    //location.reload();
                }
        }
        // Update the clock every second (1000 milliseconds)
        setInterval(updateClock, 1000);
        // Initial update to display the time immediately
        updateClock();

        // Wait for 2 minutes
        var key2 = 'key_endtime';
        // Retrieve data from localStorage
        const storedItem = localStorage.getItem(key2);
        if (storedItem) {
            const parsedItem = JSON.parse(storedItem);

            if (new Date().getTime() < parsedItem.expirationTime) {
            // Data is still valid, retrieve the value
            const retrievedValue = parsedItem.value;
            console.log(retrievedValue);
            } else {
            // Data has expired, remove it from localStorage
            localStorage.removeItem(key2);
            console.log('Data has expired');
            }
        } else {
            console.log('No data found');
        }

        function addToWishList(elem, id) {
            var user_login = $('#user_id').val();

            if (user_login > 0) {
                $.ajax({
                    type: 'POST'
                    , url: baseUrl + '/add-to-wishlist/' + id
                    , data: {
                        "_token": "{{ csrf_token() }}"
                        , user_login: user_login
                        , id: id
                    }
                    , success: function(response) {
                        if (response.msg == 'Added to wishlist') {
                            $(elem).html('<i class="fas fa-heart"></i>');
                            toastr.success('{{__("message.added_to_wishlist_text")}}');
                        } else if (response.msg == 'This item is already in wishlist') {
                            $(elem).html('<i class="far fa-heart"></i>');
                            toastr.success('{{__("message.this_item_is_already_in_wishlist_text")}}');
                        } else {
                            $(elem).html('<i class="far fa-heart"></i>');
                            toastr.success('{{__("message.removed_from_the_wishlist_text")}}');
                        }
                    }
                });
            } else if (user_login == 0) {
                toastr.error('{{__("message.please_login_first_text")}}');

            }
        }

        function addToLikeCounter(elem, id) {
            var user_login = $('#user_id').val();
            var type = $('#type_items').val();
            var countrycode = $('#country_code').val();
            var state = $('#state').val();
            var city = $('#city').val();
            /*if (user_login > 0) {*/
                $.ajax({
                    type: 'POST'
                    , url: baseUrl + '/add-to-like/' + id
                    , data: {
                        "_token": "{{ csrf_token() }}"
                        , user_login: user_login
                        , id: id, type_item:type
                        ,countrycode: countrycode
                        ,state : state, city :city
                    }
                    , success: function(response) {
                        if (response.msg == 'liked') {
                            $('#listinglikedtotal span').text(response.totallike);
                            //$(elem).html('<i class="fas fa-heart"></i>');
                            toastr.success('You Liked!');
                        }
                        else {
                            toastr.success('Already Liked!');
                            // $(elem).html('<i class="far fa-heart"></i>');
                            // toastr.success('You Unlike!');
                        }
                    }
                });
            /* } else if (user_login == 0) {
                toastr.error('{{__("message.please_login_first_text")}}');

            } */
        }

        function addDirToWishList(elem,id) {
            var user_login   = $('#user_id').val();
            $('.AJloader').show();
            if (user_login >0) {
                $.ajax({
                    type : 'POST',
                    url: baseUrl+'/add-to-wishlist-directory/'+id,
                    data: {"_token": "{{ csrf_token() }}",user_login : user_login, id:id },
                    success : function(response) {
                        if (response.msg == 'Added to wishlist') {
                            $(elem).html('<i class="fas fa-heart"></i>');
                            toastr.success('{{__("message.added_to_wishlist_text")}}');
                        }else{
                            $(elem).html('<i class="far fa-heart"></i>');
                            toastr.success('{{__("message.removed_from_the_wishlist_text")}}');
                        }
                        $('.AJloader').hide();
                    }
                });
            }else if(user_login ==0){
                toastr.error('{{__("message.please_login_first_text")}}');
                window.setTimeout(function() {
                    $('.AJloader').hide();
                    window.location.href = "{{ url('login') }}";
                }, 3000);

            }
        }

        $(document).ready(function() {

            @if (request()->get('ref') === 'cc')
                $('#customer_club').modal('show');
            @endif



            $('.modal_header_close_btn').on('click', function() {
                $('#type_pdf_Modal').modal('hide');
            });

            $('#click_viewed_listings').on('click', function() {
                $('.most_viewed_listings_div').toggle();
            });
            $('.company_details_logo_img').on('click', function() {
                $('.company_detail_menu').toggle();
            });
            $('.btn_mobile').on('click', function() {
                $('.company_detail_menu').show();
            });

            $("#down-to-map").click(function() {
                $('html, body').animate({
                    scrollTop: $("#locationmap").offset().top
                }, 2000);
            });
            $("#down-to-schedule").click(function() {
                $('html, body').animate({
                    scrollTop: $("#schedule_sec").offset().top
                }, 2000);
            });
            $("#down-to-schedule_c").click(function() {
                $('html, body').animate({
                    scrollTop: $("#schedule_sec").offset().top
                }, 2000);
            });
            $("#down-to-contact").click(function() {
                $('html, body').animate({
                    scrollTop: $("#sidebar").offset().top
                }, 2000);
            });

            $('.modal_header_close_btn').on('click', function() {
                $('#type_galleryModal').modal('hide');
            });

            $("#loader").fadeOut("slow");

            $('#booking_date').datetimepicker({
                // format:'yy-m-d h:m:s',
                minDate: 0,
                format: 'Y-m-d H:m:s',
            });

            $(document).on('click', '.submitbtn', function(e) {
                e.preventDefault();

                $(document).find('.required').removeClass('is-invalid');
                $(document).find('.invalid-feedback').remove();

                var obj = $(this);
                let requestTxt = $(this).attr('process-text');
                requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
                var oldhtml = $(this).html();

                var form = $(this).closest("form");
                var url = form.attr('action');
                var method = form.attr('method');
                var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="">${requestTxt}...</span>`;

                var allformdata = new FormData(form[0]);
                $.ajax({
                    type: method
                    , url: url
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , data: allformdata
                    , mimeType: "multipart/form-data"
                    , contentType: false
                    , cache: false
                    , processData: false
                    , dataType: "json"
                    , beforeSend: function() {
                        obj.attr("disabled", true);
                        obj.html(loader);
                    },

                    success: function(response) {
                        console.log(response);

                        if (response.status == false) {
                            $.each(response.errors, function(index, value) {
                                form.find('.required').each(function() {
                                    var fieldName = $(this).attr('name');
                                    if (fieldName == index && value != "") {
                                        $(this).addClass("is-invalid");
                                        $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                        $(this).focus();
                                    }
                                });
                            });

                            if (typeof(response.message) != "undefined") {
                                $('.respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');

                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 5000);
                            }

                        } else {
                            if (typeof(response.message) != "undefined") {
                                console.log(response.message);
                                $("#bookingform")[0].reset();
                                $('.respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');
                                window.setTimeout(function() {
                                    $('.respondata').hide();
                                }, 5000);


                            }

                        }
                        obj.attr("disabled", false);
                        obj.html(oldhtml);
                    }
                    , error: function(err) {}
                });

            });

            $(document).on('click', '.cc_submitbtn', function(e) {
                e.preventDefault();

                var cc_user_id = $('#cc_user_id').val();

                if (!cc_user_id) {
                    alert('Please login to your account before joining the customer club.');
                    setTimeout(() => {
                        window.location.href = "{{ url('login') }}";
                    }, 2000);
                    return false;
                }


                $(document).find('.required').removeClass('is-invalid');
                $(document).find('.invalid-feedback').remove();

                var obj = $(this);
                let requestTxt = $(this).attr('process-text');
                requestTxt = (typeof(requestTxt) != "undefined") ? requestTxt : 'Please wait';
                var oldhtml = $(this).html();

                var form = $(this).closest("form");
                var url = form.attr('action');
                var method = form.attr('method');
                var loader = `<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span><span class="">${requestTxt}...</span>`;

                var allformdata = new FormData(form[0]);
                $.ajax({
                    type: method
                    , url: url
                    , headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    , data: allformdata
                    , mimeType: "multipart/form-data"
                    , contentType: false
                    , cache: false
                    , processData: false
                    , dataType: "json"
                    , beforeSend: function() {
                        obj.attr("disabled", true);
                        obj.html(loader);
                    },

                    success: function(response) {
                        //console.log(response);
                        if (response.status == 'exists') {
                            alert('You have already joined this Customer Club.');

                        }else if (response.status == false) {
                            $.each(response.errors, function(index, value) {
                                form.find('.required').each(function() {
                                    var fieldName = $(this).attr('name');
                                    if (fieldName == index && value != "") {
                                        $(this).addClass("is-invalid");
                                        $(this).after(`<span class="red invalid-feedback" role="alert">${value}</span>`);
                                        $(this).focus();
                                    }
                                });
                            });

                            if (typeof(response.message) != "undefined") {
                                $('.cc_respondata').html('<p style="margin: 13px 0px 0px;color: red;font-weight: 500;">' + response.message + '</p>');

                                window.setTimeout(function() {
                                    $('.cc_respondata').hide();
                                }, 5000);
                            }

                        } else {
                            if (typeof(response.message) != "undefined") {
                                console.log(response.message);
                                $("#customer_club_form")[0].reset();
                                $('.cc_respondata').html('<p style="margin: 13px 0px 0px;color: green;font-weight: 500;">' + response.message + '</p>');

                                window.setTimeout(function() {
                                    $('.cc_respondata').hide();
                                }, 7000);


                            }

                        }
                        obj.attr("disabled", false);
                        obj.html(oldhtml);
                    }
                    , error: function(err) {}
                });

            });

            $("#direction_anch").click(function() {
                var url = "https://www.google.com/maps/dir//<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>/@<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>,16z";
                window.open(url, '_blank');
            });

            $('#sploffer, #mostviewed').owlCarousel({
                loop: true
                , autoPlay: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , pagination: false
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

                /* itemsDesktop : false,
                 itemsDesktopSmall : false,
                 itemsTablet: false,
                 itemsMobile : false, */
                ,dots: true
                , responsiveClass: true
                , nav: true
                , responsive: {
                   0: {
                    items: 1
                    },

                    600: {
                    items: 1
                    },

                    1024: {
                    items: 2
                    },

                    1366: {
                    items: 2
                    }
                }
            });

            $('#trademark').owlCarousel({
                loop: true
                , autoPlay: true
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 2
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 4
                    }
                    , 1366: {
                        items: 5
                    }
                }
            });


            $('#blogData').owlCarousel({
                loop: true
                , autoPlay: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , items: 2,
                dots: true
                , responsiveClass: true
                , nav: true
                , responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1024: {
                        items: 4
                    },
                    1366: {
                        items: 5
                    }
                }
            });

             $('#banner-foot').owlCarousel({
                loop: true
                , autoPlay: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , items: 2,
                dots: true
                , responsiveClass: true
                , nav: true
                , responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1024: {
                        items: 4
                    },
                    1366: {
                        items: 5
                    }
                }
            });

            $('#eventdata').owlCarousel({
                loop: true
                , autoPlay: true
                , items: 2
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 4
                    }
                    , 1366: {
                        items: 5
                    }
                }
            });


            $('#offerdata').owlCarousel({
                loop: true
                , autoPlay: true
                , items: 2
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 4
                    }
                    , 1366: {
                        items: 5
                    }
                }
            });

            $('#shopcarddata').owlCarousel({
                loop: true
                , autoPlay: true
                , items: 2
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']
                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 4
                    }
                    , 1366: {
                        items: 5
                    }
                }
            });

            $('#product,#categorydata').owlCarousel({
                loop: true
                , autoPlay: true
                , dots: false
                , pagination: false
                , nav: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , navigation : true
                , navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

                , responsiveClass: true
                , responsive: {
                    0: {
                        items: 1
                    }
                    , 600: {
                        items: 3
                    }
                    , 1024: {
                        items: 4
                    }
                    , 1366: {
                        items: 5
                    }
                }

            });

            $('#listing_banner').owlCarousel({
                loop: true
                , autoPlay: true
                , lazyLoad: true
                , singleItem: false
                , slideSpeed: 300
                , paginationSpeed: 400
                , pagination: false
                , navigation : true
                , items: 2
                ,navigationText : ['<i class="fa fa-angle-left" aria-hidden="true"></i>','<i class="fa fa-angle-right" aria-hidden="true"></i>']

                ,dots: true
                , responsiveClass: true
                , nav: true
                , responsive: {
                0: {
                    items: 1
                    },

                    600: {
                    items: 1
                    },

                    1024: {
                    items: 2
                    },

                    1366: {
                    items: 2
                    }
                }
            });

        });

    </script>

    <script type="text/javascript">
        'use strict';

        function createListingsMap(options) {
            var defaults = {
                markerPath: 'assets/client/images/marker.png'
                , markerPathHighlight: 'assets/client/images/marker-hover.png'
                , markerShadow: 'assets/client/images/location-pin.svg'
                , imgBasePath: 'assets/client/images/listing_thumbnails/'
                , mapPopupType: 'venue'
                , useTextIcon: false
            }



            /*
            ====================================================
              Bind popup and highlighting features
              to each marker
            ====================================================
            */

            var markersGroup = []

            var defaultIcon = L.icon({
                iconUrl: settings.markerPath,
                // shadowUrl: settings.markerShadow,
                iconSize: [25, 37.5], // default size was 25, 37.5
                popupAnchor: [0, -18]
                , tooltipAnchor: [0, 19],
                // shadowSize: [25, 37.5],
                // shadowAnchor: [-3, 30]
            });

            var highlightIcon = L.icon({
                iconUrl: settings.markerPathHighlight
                , iconSize: [25, 37.5]
                , popupAnchor: [0, -18]
                , tooltipAnchor: [0, 19]
            });


            function onEachFeature(feature, layer) {

                layer.on({
                    mouseover: highlightMarker
                    , mouseout: resetMarker
                    , click: scrollToListing
                });

                if (feature.properties && feature.properties.about) {
                    //This is the code for represent the popover

                    layer.bindPopup(getPopupContent(feature.properties), {
                        minwidth: 200
                        , maxWidth: 600
                        , className: 'map-custom-popup'
                    });

                    if (settings.useTextIcon) {
                        layer.bindTooltip('<div id="customTooltip-' + feature.properties.id + '">$' + feature.properties.price + '</div>', {
                            direction: 'top'
                            , permanent: true
                            , opacity: 1
                            , interactive: true
                            , className: 'map-custom-tooltip'
                        });
                    }
                }
                markersGroup.push(layer);
            }

            function pointToLayer(feature, latlng) {
                if (settings.useTextIcon) {
                    var markerOpacity = 0
                } else {
                    var markerOpacity = 1
                }

                return L.marker(latlng, {
                    icon: defaultIcon
                    , id: feature.properties.id
                    , opacity: markerOpacity
                });
            }

            function highlightMarker(e) {
                highlight(e.target);
            };

            function resetMarker(e) {
                reset(e.target);
            };

            // this function triggers after clicking on a marker
            function scrollToListing(e) {
                var listing_code = e.target.defaultOptions.id;
                var toFocusId = '#listing-banner-image-for-' + listing_code; // this is the id of the link where hovering gives an animation

                if ($("#" + listing_code).length > 0) { // this if condition checks if "#"+listing_code this id exists in the view. We've done this to avoid a console error in single listing page
                    // to focust the listing
                    $('html, body').animate({
                        scrollTop: $('#' + listing_code).offset().top - 110
                    }, 'slow', function() {
                        // adding a class where listing banner will be scaled up a little bit
                        $(toFocusId).addClass('focus-a-listing');
                        // Removing the class after seconds
                        setTimeout(function() {
                            $(toFocusId).removeClass('focus-a-listing');
                        }, 300);
                    });
                }
            }

            function highlight(marker) {
                marker.setIcon(highlightIcon);
                if (settings.useTextIcon) {
                    findTooltip(marker).addClass('active');
                }
            }

            function reset(marker) {
                marker.setIcon(defaultIcon);
                if (settings.useTextIcon) {
                    findTooltip(marker).removeClass('active');
                }
            }

            function findTooltip(marker) {
                var tooltip = marker.getTooltip()
                var id = $(tooltip._content).filter("div").attr("id")
                return $('#' + id).parents('.leaflet-tooltip')
            }

            function getPopupContent(properties) {

                if (properties.name) {
                    var title = '<h6><a href="' + properties.link + '">' + properties.name + '</a></h6>'
                } else {
                    title = ''
                }

                if (properties.about) {
                    var about = '<p class="">' + properties.about + '</p>'
                } else {
                    about = ''
                }

                if (properties.image) {

                    var imageClass = 'image';
                    if (settings.mapPopupType == 'venue') {
                        imageClass += ' d-none d-md-block'
                    }

                    var image = '<div class="' + imageClass + '"style="height: 150px; background-image: url(\'' + settings.imgBasePath + properties.image + '\'); background-size: cover; background-position: center;"></div>';
                } else {
                    image = '<div class="image"></div>'
                }

                if (properties.address) {
                    var address = '<p class="text-muted"><i class="fas fa-map-marker-alt fa-fw text-dark mr-2"></i>' + properties.address + '</p>'
                } else {
                    address = ''
                }
                if (properties.email) {
                    var email = '<p class="text-muted"><i class="fas fa-envelope-open fa-fw text-dark mr-2"></i><a href="mailto:' + properties.email + '" class="text-muted">' + properties.email + '</a></p>'
                } else {
                    email = ''
                }
                if (properties.phone) {
                    var phone = '<p class="text-muted"><i class="fa fa-phone fa-fw text-dark mr-2"></i>' + properties.phone + '</p>'
                } else {
                    phone = ''
                }

                if (properties.stars) {
                    var stars = '<div class="text-xs">'
                    for (var step = 1; step <= 5; step++) {
                        if (step <= properties.stars) {
                            stars += "<i class='fas fa-star text-warning'></i>"
                        } else {
                            stars += "<i class='fas fa-star' style = 'color: #BDBDBD'></i>"
                        }
                    }
                    stars += "</div>"
                } else {
                    stars = ''
                }

                if (properties.url) {
                    var url = '<a href="' + properties.url + '">' + properties.url + '</a><br>'

                } else {
                    url = ''
                }

                var popupContent = '';

                if (settings.mapPopupType == 'venue') {
                    popupContent =

                        '<div class="popup-venue card">' +
                        '<div style = "overflow: hidden; height: 150px; background-image: url(\'' + settings.imgBasePath + properties.image + '\'); background-size: cover; background-position: center;" class="map-direction-image">' + '</div>' +
                        '<div class="card-body">' +
                        '<div class="">' + title + ' </div>' +
                        '<div>' + address + '</div>' +
                        '<div>' + phone + '</div>' +
                        '</div>' +
                        '</div>';
                } else if (settings.mapPopupType == 'rental') {
                    popupContent =
                        '<div class="popup-rental">' +
                        image +
                        '<div class="text">' +
                        title +
                        stars +
                        '</div>' +
                        '</div>';
                }


                return popupContent;
            }

            L.Map.include({
                getMarkerById: function(id) {
                    var marker = null;
                    this.eachLayer(function(layer) {
                        if (layer instanceof L.Marker) {
                            if (layer.options.id === id) {
                                marker = layer;
                            }
                        }
                    });
                    return marker;
                }
            });

            var previousHighLightedMarkerId;
            $('[button-direction-id!=""][button-direction-id]').on('click', function() {
                var markerId = $(this).attr('button-direction-id');
                var marker = map.getMarkerById(markerId);
                if (marker) {
                    marker.openPopup(); // this line of code opens up the pop on map
                    highlight(marker);
                    if (previousHighLightedMarkerId != markerId) {
                        var marker = map.getMarkerById(previousHighLightedMarkerId);
                        if (marker) {
                            reset(marker);
                        }
                    }
                    previousHighLightedMarkerId = markerId;
                }
            });
        }
        createListingsMap({
            mapId: 'map'
            , jsonFile: 'https://demo.creativeitem.com/atlas/assets/frontend/single-listing-geojson/listing-id-6.json'
        });

        /* Description Read More*/

        $(document).ready(function() {

            $('.show-more-weoffer').hide();
            $('.textshow').on('click', function() {
                $('.textweoffer2').show();
                var textclspre = $('.btnhidetexttoggle').length;
                //alert("Text Class : "+textclspre);
                if(textclspre==0){
                   $('#tablistaddbtn').after('<button class="btn btn-sm btnhidetexttoggle" id="texthideoffer" title="Hide Details" style="border-radius: 1.2rem !important; margin-left: 10px !important;"><i class="fas fa-minus-circle" aria-hidden="true"></i></button>');
                   $('.show-more-weoffer').show();
                }
            });


            $(document).on("click", "#texthideoffer", function() {
                $('.textweoffer2').hide();
                $('.show-more-weoffer').hide();
            });


            function readMore() {
                if($(window).width() < 514){
                    var maxLength = 90;
                }else{
                    var maxLength = 460;
                }

                $(".text").each(function() {
                    var myStr = $(this).text();
                    if ($.trim(myStr).length > maxLength) {
                        var newStr = myStr.substring(0, maxLength);
                        var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                        $(this).empty().html(newStr);
                        $(this).append('<div class="dotdotdot" style="display: inline-flex;">...</div>');
                        $(this).append('<div class="show-more" style="cursor:pointer;">Read More</div>');
                        $(this).append('<span class="more-text">' + removedStr + ' <div class="read-less" style="cursor:pointer;">Read Less</div>' + '</span>');
                    }
                });

            }
            readMore();
            $(document).on("click", ".show-more", function() {
                $(this).siblings(".more-text").contents().unwrap();
                $(this).remove();
                $('.dotdotdot').remove();
            });

            $(document).on("click", ".read-less", function() {
                $(this).remove();
                readMore();
            });


        /* Popup Model We Offer Read More*/

            function readMoreWeOffer() {
                if($(window).width() < 514){
                    var maxLength = 100;
                }else{
                    var maxLength = 435;
                }
                //var maxLength = 400;
                $(".textpopup").each(function() {
                var myStr = $(this).text();
                if ($.trim(myStr).length > maxLength) {
                    var newStr = myStr.substring(0, maxLength);
                    var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                    $(this).empty().html(newStr);
                    $(this).append('<div class="dotdotdot" style="display: inline-flex;">...</div>');
                    $(this).append('<div class="show-morebtn" style="cursor:pointer;">Read More</div>');
                    $(this).append('<span class="more-textweoffer">' + removedStr + ' <div class="read-lessbtn" style="cursor:pointer;">Read Less</div>' + '</span>');
                }
                });

            }
            readMoreWeOffer();
            $(document).on("click", ".show-morebtn", function() {
                $(this).siblings(".more-textweoffer").contents().unwrap();
                $('.dotdotdot').remove();
                $(this).remove();
            });

            $(document).on("click", ".read-lessbtn", function() {
                $(this).remove();
                readMoreWeOffer();
            });

        /* Popup Model About Us Read More*/

        function readMoreAboutUs() {
                if($(window).width() < 514){
                    var maxLength = 100;
                }else{
                    var maxLength = 435;
                }
                //var maxLength = 435;
                $(".textpopup-aboutus").each(function() {
                var myStr = $(this).text();
                if ($.trim(myStr).length > maxLength) {
                    var newStr = myStr.substring(0, maxLength);
                    var removedStr = myStr.substring(maxLength, $.trim(myStr).length);
                    $(this).empty().html(newStr);
                    $(this).append('<div class="dotdotdot" style="display: inline-flex;">...</div>');
                    $(this).append('<div class="read-morebtn" style="cursor:pointer;">Read More</div>');
                    $(this).append('<span class="more-textaboutus">' + removedStr + ' <div class="read-lessbtn-popup" style="cursor:pointer;">Read Less</div>' + '</span>');
                }
                });

        }
        readMoreAboutUs();
            $(document).on("click", ".read-morebtn", function() {
                    $(this).siblings(".more-textaboutus").contents().unwrap();
                    $(this).remove();
                    $('.dotdotdot').remove();
            });

            $(document).on("click", ".read-lessbtn-popup", function() {
                $(this).remove();
                readMoreAboutUs();
            });
        });


    function showPosition() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    var positionInfo1 = position.coords.latitude;
                    var positionInfo2 = position.coords.longitude;
                    document.getElementById("lati").value = positionInfo1;
                    document.getElementById("long").value = positionInfo2;
                    setCookie('latitude', positionInfo1, 7);
                    setCookie('longitude', positionInfo2, 7);

                    var geocoder = new google.maps.Geocoder();
                    var latlng = new google.maps.LatLng(positionInfo1, positionInfo2);

                    geocoder.geocode({ location: latlng }, function (results, status) {
                        var session_city = "{{ session()->get('filter_city') }}";

                        if (!session_city || session_city === 'Not found') {
                            if (status === google.maps.GeocoderStatus.OK && results) {
                                // Get city from results
                                let city;
                                for (let i = 0; i <= 2; i++) {
                                    if (results[i]?.plus_code) {
                                        const cityDetails = results[i].plus_code.compound_code.split(",");
                                        city = cityDetails[0]?.split(" ")[1];
                                        break;
                                    }
                                }
                                if (city) {
                                    $(".select_city_home #select2-city_name-container").text(city);
                                }
                            }
                        } else {
                            $(".select_city_home #select2-city_name-container").text(session_city);
                        }
                    });

                    setTimeout(function () {
                        callAjax(positionInfo1, positionInfo2);
                    }, 2000);

                    function callAjax(lati, long) {
                        var form = $("form#latlongform");
                        $.ajax({
                            url: form.attr("action"),
                            type: "get",
                            data: { lati, long },
                            success: function (response) {
                                //console.log("Ajax response:", response);
                            },
                        });
                    }
                },
                function (error) {
                    console.error("Geolocation error:", error);
                    //alert("Unable to retrieve location.");
                },
                { enableHighAccuracy: true } // Enable high accuracy
            );
        } else {
            //alert("Sorry, your browser does not support HTML5 geolocation.");
        }

    }
    window.onload = showPosition;

    </script>


    <style>

        div#public_holiday_model {
            margin: 20px 0px;
        }
        a#amenity-toggle-btn {
            font-size: 15px;
            font-weight: 400;
            margin: 0px 17px;
        }
        .table-wrapper {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch; /* smooth scroll on mobile */
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        .table-wrapper table {
            width: 100%;
            min-width: 700px; /* table scrolls when screen smaller */
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .table-wrapper th, td {
            padding: 1px;
            border-bottom: 1px solid #e0e0e0;
            text-align: left;
            /* white-space: nowrap; */
        }

        .table-wrapper th {
            background-color: #f9f9f9;
            font-weight: 600;
        }

        .table-wrapper tr:nth-child(even) {
            background-color: #fafafa;
        }

        .direction_a {
            border: none;
            margin: 0;
            padding: 0;
        }
        a.relatedprovisitbtn {
            background: #efede7;
            margin: 8px 10px;
        }
        .text .more-text{
            display: none;
        }
        .textpopup .more-textweoffer{
            display: none;
        }
        .textpopup-aboutus .more-textaboutus{
            display: none;
        }
        #we_offer_details_model .nav-item a {
            padding: 0 10px;
            height: 30px;
            display: inline-block;
            line-height: 30px;
            border-radius: 5px 5px 0 0;
            border: 1px solid #ddd;
            border-bottom: none;
        }
        #we_offer_details_model .nav-item a.active {
            background: #1f3863;
            color: #fff;
            border: 1px solid #1f3863;
        }

        .red { background: unset !important; }

        div#product .strip .wrapper {
            padding: 0px 10px 5px 10px;
        }
        #sploffer .item {
            margin: 0 5px;
        }
        #trademark .item {
            margin: 0 5px;
        }
        #offerdata .owl-item {
            margin-right: 20px;
            height: 100%;
        }
        #shopcarddata .owl-item {
            margin-right: 20px;
            height: 100%;
        }
        #product .owl-item {
            margin: 0 15px;
        }
        .score {
            display: none;
        }
        .old-price {
            text-decoration: line-through;
            color: dimgray;
            font-size: 12px;
            font-weight: 160;
        }
        #categorydata .item {
            margin: 0 15px;
        }
        a.view_company {
            background: #203864;
            padding: 2px 10px;
            border-radius: 4px;
            color: #fff;
            margin-right: 6px;
        }
        .opening table {
            width: 100%;
        }
        .extra-time {
            background: #becadf;
            margin-top: 10px;
            border: 1px dashed #1f3865;
            font-weight: 600;
            color: #1f3763;
        }
        .extra-time td span {
            display: block;
            text-align: center;
        }
        div#tree_structure_listings {
            margin: 14px -8px;
        }
        #sploffer .owl-item {
            margin: 1px 1px;
        }
        .btn_home_align {
            margin-top: 20px;
        }
        a#tree_structure2 {
            font-size: 12px;
            padding: 5px 6px;
        }
        .holiday-public{
            float: right;
            margin-top: -10px;
        }
        .clock-css {
            position: absolute !important;
            left: 25px !important;
            top: 25px !important;
            font-size: 36px !important;
            font-size: 2.25rem !important;
            color: #555 !important;
        }
        .pub-holiday-css{
            left: unset !important;
            right: 88px;
            top: unset !important;
            font-size: 34px !important;
            color: #555;
            cursor: pointer;
        }
        .modal-backdrop {
            position: absolute;
            top: unset;
        }
        div#public_holiday_model .modal-header {
            background: #1f3863;
        }
        div#public_holiday_model .modal-header h5.modal-title {
            color: #fff;
        }
        div#public_holiday_model .modal-header button.close {
            color: #fff;
        }
        div#we_offer_details_model .modal-header {
            background: #1f3863;
        }
        div#we_offer_details_model .modal-header h5.modal-title {
            color: #fff;
        }
        div#we_offer_details_model .modal-header button.close {
            color: #fff;
        }
        div#public_holiday_model table {
            width: 100%;
        }
        div#public_holiday_model table td {
            padding: 6px 20px;
            text-align: left;
        }
        div#public_holiday_model table th {
            padding: 6px 20px;
        }
        div#public_holiday_model .modal-body .col-md-12 > table > tbody > tr:nth-child(odd) {
            background: #d1def5;
        }
        .owl-prev {
            position: absolute;
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 50%;
            opacity: 1;
            margin-top: auto;
            margin-bottom: auto;
            box-shadow: -2px 1px 10px #bdbdbd;
            top: calc(50% - 40px);
            cursor: pointer;
            left: 0;
            padding: 0;
            line-height: 44px;
            margin: 0 0 0 10px;
        }
        .owl-next{
            position: absolute;
            height: 40px;
            width: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 50%;
            opacity: 1;
            margin-top: auto;
            margin-bottom: auto;
            box-shadow: -2px 1px 10px #bdbdbd;
            top: calc(50% - 40px);
            cursor: pointer;
            right: 0;
            padding: 0;
            line-height: 44px;
            margin: 0 10 0 0px!important;
        }
        .owl-prev i , .owl-next i {
            font-size: 25px;
        }
        .owl-theme .owl-controls .owl-buttons div {
            background: #0a071e;
        }
        .owl-controls.clickable {
            margin: 0;
        }
        div#sploffer.owl-carousel .owl-item img {
            /* height: 100%; */
            object-fit: unset;
        }
        div#allgalleryimages_model .modal-header {
            background: #1f3863;
        }
        div#allgalleryimages_model .modal-header h5.modal-title {
            color: #fff;
        }
        div#allgalleryimages_model .modal-header button.close {
            color: #fff;
        }
        .booking select {
            display: block;
        }
        .modal-dialog {
            max-width: 975px;
            margin: 0px auto;
        }
        .formtextarea_cc {
            height: 50px !important;
            padding: 6px 10px !important;
        }
        .customer_clubModal {
            width: 100% !important;
            margin: 0px auto;
        }

        .modal_dialogcc{
            max-width: 670px !important;
            margin: 50px auto;
        }
        .lb-outerContainer {
            max-width: 600px!important;
            max-height: 600px!important;
            width: 100%!important;
            /* height: 100%!important; */
        }
        .lb-dataContainer {
            max-width: 100% !important;
        }
        .lightbox .lb-image{
            object-fit: contain;
        }

        div#product.owl-carousel .owl-item img.offericon {
            height: auto;
            object-fit: inherit;
        }
        .newicon{
            position: absolute;
            max-width: 60px;
            left: 10px;
            top: -6px;
            z-index: 1;
        }

        @media(max-width:991px)
        {

            #trademark .item {
                margin: 0 5px;
                height: 130px;
                display: flex;
                align-items: center;
                width: 100%;
                padding: 0 10px;
                justify-content: center;
            }

            div#trademark .strip.grid {
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0;
            }

            div#trademark .strip figure {
                height: auto!important;
                max-height: fit-content;
                padding: 0 15px;
            }

        }
        @media(max-width:375px)
        {
            .link-css-mobile {
                padding-bottom: 15px;
            }
            .link-headings{text-align:center;}
            .link-css-mobile{text-align:center;}


            .link-css-mobile .btn-info {
                color: #fff;
                background-color: #17a2b8;
                border-color: #17a2b8;
                max-width: 200px;
                width: 100%;
                margin-bottom: 10px;
            }
        }

        .share-tooltip .image-responsive {
            position: relative;
        }
        /*.share-icon-gap {
            margin-right: 3px !important;
        }*/
        .share-tooltip:hover:after {
            top: 16px;
            content: "";
            position: absolute;
            bottom: 100%;
            left: 16px;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent #203864 transparent;
            display:none;
        }
        .share-tooltiptext::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 100%;
            transform: translateX(-50%);
            border-color: black;
        }
        .share-tooltip:hover .share-tooltiptext {
            visibility: visible;
            opacity: 1;
            top: 76%;
            z-index: 99;
        }
        .share-tooltip-desktop {
            position: absolute;
            z-index: 9999 !important;
            cursor: pointer;
            top: 8px;
            right: 20px;
        }
        .share-tooltip-desktop span:nth-child(1) {
            margin-bottom: 4px;
            display: block;
        }
        .share-tooltip-desktop span img {
            width: 30px !important;
        }
        .share-tooltip-desktop span a {
            line-height: 10px !important;
        }
        .share-tooltip-desktop .share-tooltiptext {
            display: none;
        }
        div.share-tooltip:hover .share-tooltiptext {
            display: block;
        }
        .share-tooltip-desktop span a {
            display: block;
            margin-bottom: 5px;
        }
        /*
        @media (max-width: 380px){
        .share-tooltip-desktop {left: 10px;}
        }*/

        .see_more_gallery_type {
            top: 65px;
            width: 46%;
            background: #fff;
            border: 1px solid;
            line-height: 20px;
            padding: 1px;
            height: 25px;
            left: 28%;
            cursor: pointer;
        }
        .tab-content>.active {
            display: block;
        }
        .textweoffer{
            display: block !important;
        }
        .room_type {
            border: 1px solid #f1ecec;
            padding: 15px 15px 10px;
        }
        span.locally_text {
            margin: -85px 0px 0px 0px;
            float: right;
            width: 170px;
            color: #222121;
            position: absolute;
            right: 170px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .modal-header .close {
                padding: 3px;
                margin: 0rem 1rem 0rem auto;
            }
            div#public_holiday_model .modal-header h5.modal-title {
                color: #fff;
                font-size: 17px;
            }
            header.header_in .hamburger-inner, header.header_in .hamburger-inner::after, header.header_in .hamburger-inner::before, header.sticky .hamburger-inner, header.sticky .hamburger-inner::after, header.sticky .hamburger-inner::before {
                background-color: #fff;
            }
            .visit_the_shop {
                text-align: center;
            }
            .link-css-mobile {
                padding-bottom: 15px;
            }
            span.company_details_logo img {
                margin: -5px 0px 0px 35px !important;
                text-align: center;
                width: 50%;
                padding: 3px 0px;
            }
            span.locally_text {
                width: 100%;
                position: sticky;
                text-align: center;
                padding: 0px 0px 0px 0px;
                margin: -3px 0px -3px 0px;
                font-size: 15px;
            }
            span.company_details_logo {
                margin: 0px !important;
                position: relative;
            }
            img.company_details_logo_img {
                display: none;
            }
            button#down-to-schedule_c {
                margin: -32px 40px -25px 140px;
                padding: 0;
            }
            button#down-to-schedule {
                margin: 1px 5px;
                padding: 0;
            }
            button#down-to-contact {
                margin: 0px 0px;
                position: relative;
                width: auto;
                z-index: 20000;
                padding: 0px 5px;
            }
            .custom-search-input-2.inner input[type=submit] {
                height: 28px;
            }
            i.fas.fa-chevron-down {
                margin: 0px 5px;
            }
            div#company_imp_tags_div span a {
                font-size: 11px;
            }
            .add_bottom_15.likebtn_alignment {
                margin: 0px;
            }
            .add_bottom_15 h2 {
                font-size: 20px;
                margin: 10px 1px;
            }
            .more_type {
                width: 40%;
                margin: -2px 0px;
            }
            i.fas.fa-chevron-up {
                margin: 1px 4px;
            }
            a#tree_structure2 {
                width: 100% !important;
            }
            .holiday-public {
                float: left !important;
                margin-top: -45px !important;
                margin-left: 70px !important;
                position: absolute !important;
            }
            .opening i {
                display: ruby-text !important;
                margin: -52px -5px;
            }
            .canvas_pdf {
                max-width: 100% !important;
            }
            img.direction {
                float: right;
                margin: -27px 0px;
                width: 45px;
                height: auto;
            }
            .margin_80_55 {
                padding-top: 0px;
                padding-bottom: 10px;
            }
            .add_bottom_15 {
                display: inline-block;
            }
            div#listing_banner .hero_in {
                max-height: 390px;
            }
            .strip .wrapper h3 {
                min-height: 30px;
            }
            .listing_detail_gallery {
                padding: 15px;
            }
            .modal-header {
                padding: 6px;
            }
            div#allgalleryimages_model .modal-header h5.modal-title {
                color: #fff;
                font-size: 14px;
            }
            div#type_gallery_list img {
                margin: 5px !important;
                padding: 3px;
                border: 1px solid #ccc;
                width: 53px !important;
                height: auto !important;
            }
            div#allgalleryimages_model.listing_detail_gallery .thumbnail img {
                max-height: 180px;
                border: 1px solid #ccc;
            }
            div#product .strip .wrapper {
                padding: 0px 0px 10px 15px;
            }
            div#trademark {
                margin-bottom: 30px;
            }
            .owl-theme .owl-controls .owl-buttons div { margin: 5px; padding: 0px 3px; font-size: 0px; }
            .owl-prev {
                height: 28px;
                width: 26px;
            }
            .owl-next {
                height: 28px;
                width: 26px;
            }
            #offerdata .owl-item {
                margin-right: 0px;
            }
            #product .owl-item {
                margin: 0 0px;
            }
            #sploffer .owl-item {
                margin: 0 0px;
            }
            .secondary_nav {
                padding: 5px 0;
            }
            .detail_title_1 h1 {
                font-size: 15px !important;
            }
            a.relatedprovisitbtn {
                margin: 0px 0px 0px 0px;
                padding: 3px 5px;
                font-size: 13px;
                text-align: left;
            }
            a.address {
                font-size: 10px;
            }
            .see_all {
                width: 57px;
                font-size: 12px;
            }
            #weoffer-mobile .nav-item a {
                border: 1px solid #ccc;
            }


        }

        .canvas_pdf {
            max-width: 690px;
        }

        .next_pdf{
            color: #4fa94f;
            border: 1px solid;
        }
        .previous_pdf {
            color: #dc3545;
            border: 1px solid;
        }

    </style>

    <style>

   figure.imageads small.adsmidtext {
        top: 75%!important;
        left: 50%!important;
        transform: translate(-50%,-50%);
        text-align: center;
        line-height: 31px !important;
        border: 1px solid #fff;
        padding: 3px !important;
    }
    /* =========================
    Competition Section
    ========================= */

    .competition_div .main_title_2 h5 {
        font-size: 20px;
        font-weight: 600;
        color: #1e293b;
        letter-spacing: 0.5px;
        position: relative;
    }

    .main_title_2 h5::after {
        content: "";
        width: 70px;
        height: 4px;
        border-radius: 20px;
        background: linear-gradient(90deg, #ff6b00, #ffb347);
        position: absolute;
        left: 0;
        bottom: -12px;
    }

    /* =========================
    Carousel Item
    ========================= */
    .competition_div .item {
        padding: 12px;
    }

    .competition_div .strip.grid {
        background: #ffffff;
        border-radius: 22px;
        overflow: hidden;
        position: relative;
        transition: all 0.35s ease;
        box-shadow: 0 8px 30px rgba(15, 23, 42, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.8);
    }

    .competition_div .strip.grid:hover {
        transform: translateY(-8px);
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.15);
    }

    /* =========================
    Image Area
    ========================= */
    .competition_div .imageads {
        position: relative;
        overflow: hidden;
        margin: 0;
        background: #f1f5f9;
    }

    .competition_div .imageads img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.5s ease;
        display: block;
    }

    .competition_div .strip.grid:hover .imageads img {
        transform: scale(1.08);
    }

    /* Dark overlay */
    .competition_div .imageads::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(0, 0, 0, 0.72),
            rgba(0, 0, 0, 0.15),
            transparent
        );
        z-index: 1;
    }

    /* =========================
    Top Heading Badge
    ========================= */
    .competition_div .imageads small:first-of-type {
        position: absolute;
        top: 18px;
        left: 18px;
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        color: #0f172a;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    /* =========================
    Discount Badge
    ========================= */
    .competition_div .adsprice {
        top: 60px;
        z-index: 2;
        background: linear-gradient(135deg, #ff512f, #dd2476);
        color: #fff;
        padding: 2px 0px;
        border-radius: 14px;
        font-size: 13px;
        font-weight: 700;
        min-width: 70px;
        text-align: center;
        box-shadow: 0 10px 20px rgba(221, 36, 118, 0.35);
    }

    .competition_div .adsprice .p-discount {
        font-style: normal;
        font-weight: 800;
    }

    /* =========================
    Mid Text Overlay
    ========================= */
    .competition_div .adsmidtext {
        position: absolute;
        left: 20px;
        right: 20px;
        bottom: 20px;
        z-index: 2;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        line-height: 1.5;
        display: block;
        text-shadow: 0 2px 8px rgba(0,0,0,0.45);
    }

    /* =========================
    Bottom Title
    ========================= */

    .competition_div .strip.grid h6 {
        padding: 10px 15px !important;
        margin: 0 !important;
        font-size: 14px !important;
        font-weight: 500 !important;
        color: #0f172a;
        background: #fff;
        line-height: 1.5;
        border-top: 1px solid #eef2f7;
        display: flex;
        align-items: center;
    }

    /* =========================
    Owl Carousel Dots
    ========================= */
    .competition_div .owl-dots {
        margin-top: 25px;
        text-align: center;
    }

    .competition_div .owl-dot span {
        width: 12px;
        height: 12px;
        margin: 5px;
        background: #cbd5e1 !important;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .competition_div .owl-dot.active span {
        width: 32px;
        border-radius: 30px;
        background: linear-gradient(90deg, #ff6b00, #ffb347) !important;
    }

    /* =========================
    Owl Navigation
    ========================= */
    .competition_div .owl-nav {
        margin-top: 15px;
    }

    .competition_div .owl-nav button {
        width: 46px;
        height: 46px;
        border-radius: 50% !important;
        background: #fff !important;
        color: #0f172a !important;
        font-size: 22px !important;
        box-shadow: 0 8px 18px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0 !important;
    }

    .competition_div .owl-nav button:hover {
        background: linear-gradient(135deg, #ff6b00, #ff8f00) !important;
        color: #fff !important;
        transform: translateY(-2px);
    }

    /* =========================
    Responsive
    ========================= */
    @media (max-width: 991px) {

        .competition_div {
            padding: 50px 0;
        }

        .competition_div .main_title_2 h5 {
            font-size: 24px;
        }

        .competition_div .imageads img {
            height: 220px;
        }
    }

    @media (max-width: 767px) {

        .competition_div .main_title_2 h5 {
            font-size: 20px;
        }

        .competition_div .imageads img {
            height: 200px;
        }

        .competition_div .adsmidtext {
            font-size: 14px;
        }

        .competition_div .strip.grid h6 {
            font-size: 14px !important;
            min-height: auto;
        }

        .competition_div .adsprice {
            padding: 8px 12px;
            min-width: 55px;
        }

        .competition_div .adsprice .p-discount {
            font-size: 16px;
        }
    }


    .strip ul {
        bottom: -40px !important;
        padding: 0px 0px 40px 10px !important;
    }
    .modal-header {
        padding: 1rem !important;
    }
    div#staticBackdrop {
        height: 70px !important;
    }
    p#staticBackdropLabel {
        color: #040303;
        font-weight: 400;
        font-size: 16px !important;
    }
    .link-css-mobile i {
        font-size: 23px;
        margin: 1px;
    }
    .main-menu {
        padding-top: 6px;
    }
    div#eventdata .item {
        padding: 5px;
    }
    .modal-open .modal {
        margin: 23px 0px 0px 0px;
    }
    .btn_1.full-width.purchase, a.btn_1.full-width.purchase {
        background-color: #2f4a7c;
    }
    .visit_the_shop {
        color: #333 !important;
        font-size: 13px;
        margin: 13px;
    }
    i.fas.fa-chevron-down {
        margin: 0px 6px;
    }
    .add_bottom_15.likebtn_alignment {
        margin: 0px 35px;
    }
    header.header.sticky, header.header_in {
        background-color: {{ $data['mainDirectoryDetails']->company_color }};
    }

    .listing-social-btn .social-button {
        height: 30px;
        line-height: 26px!important;
        width: 25%!important;
        border: 1px solid #ccc !important;
        font-size: 12px;
        font-weight: 600;
    }
    .more_type {
        width: 21%;
        float: right;
        margin: -10px 18px;
        text-align: right;
        cursor: pointer;
        border-color: #3b5998;
        background: #fff;
        border: 1px solid #666;
        border-radius: 50px;
        font-size: 14px;
        padding: 5px 25px;
        transition: .3s;
        display: inline-block;
        line-height: 17px;
        font-weight: 500;
    }
    .row.no-gutters.custom-search-input-2.inner {
        margin: 5px 0px 15px 0px;
    }
    span.company_details_logo {
        margin: 0px 17px;
        position: absolute;
    }
    button#down-to-schedule_c {
        width: 80px;
        margin: -40px 40px -25px 140px;
    }
    button#down-to-schedule {
        width: 130px;
        float: left;
        margin: 1px 5px;
    }
    a.company_imp_tags {
        cursor: pointer;
    }
    a.company_imp_tags2 {
        cursor: pointer;
    }
    i.fa.fa-times.tag_delete {
        color: #993939;
        margin: 0px 5px;
    }
    div#allgalleryimages_model.listing_detail_gallery .thumbnail img {
        max-height: 100%;
        border: 3px solid #1f3863;
        padding: 0px;
    }
    img.logo_normal_center {
        width: 350px;
        margin: -3px 0px 0px 0px;
    }
    div#logo {
        margin: 2px 0px 0px 10px;
    }
    #logo img {
        max-width: 245px;
        min-width: 200px;
    }


    .main-menu ul, .main-menu ul li, .main-menu ul ul {
        margin: 5px 0px 0px 0px;
    }
    div#listing_banner {
    margin-top: 85px;
   }

    .main-menu {
        text-align: center;
        float:none !important;
    }
    header {
    padding: 10px 0px 5px 10px !important;
    }

    .company_d_title {
        margin: -8px 200px 3px 100px;
        font-size: 38px;
        background: #fff;
        width: auto;
        font-family: fantasy;
        border-radius: 25px;
        padding: 1px 15px;
    }

   .company_d_title_mobile{
    display:none;
   }

    .secondary_nav ul li:nth-child(2) {
        display: unset;
    }

    .secondary_nav {
        padding: 8px 0;
    }
    .visit_the_shop{
        color: #333 !important;
        font-size: 13px;
    }
    div#lightbox {
        right: 0%;
    }
    .gallerytype_pdf {
        margin: 0px 5px 30px 15px;
        border: 4px solid #1f3863;
        padding: 1px;
    }
    button#listsubmit-dirtype {
        margin-top: 24px;
    }

    #functions_reload a {
        color: #333 !important;
        margin: -20px -22px;
        padding: 5px;
    }
    span#functions_reload i {
        margin: 0px 5px !important;
    }
    .lb-dataContainer {
        max-width: 26% !important;
        padding: 2px;
        text-align: center;
    }
    #listing_banner .owl-item img {
        min-height: 417px;
    }
    img.direction {
        float: right;
        margin: -40px 10px;
        width: 60px;
        height: auto;
        border-radius: 5px;
    }
    .room_type.first img {
        min-height: 147px;
        max-height: 137px;
        object-fit: cover;
        margin: 15px 20px;
    }
    .room_type.first .col-md-4 {
        text-align: center;
    }
    ul.hotel_facilities {
        margin: 5px 15px 5px -24px; padding: 8px;
    }
    .toggle-btn {
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
    }

    .dropdown-icon {
        margin-left: -30px;
        transition: transform 0.3s ease;
        padding: 0px 10px;
    }

    .toggle-btn.collapsed .dropdown-icon i {
        transform: rotate(0deg);
    }

    .toggle-btn:not(.collapsed) .dropdown-icon i {
        transform: rotate(180deg);
    }

    .show-more-height {
        overflow: hidden;
        display: block;
        max-height: 87px; /* Collapsed height */
        transition: max-height 0.3s ease;
    }

    .show-more-height.expanded {
        max-height: 500px; /* Expanded height (adjust based on your content) */
    }
    .description_toggle {
        color: #3e3838;
        border: none;
        padding: 1px 5px;
        cursor: pointer;
        margin: -5px 0px 20px 0px;
        display: block;
        text-align: center;
        outline: none;
    }
    .description_toggle:focus {
        outline:none;
    }
    .link-css-mobile{
        float: left;
        margin: -6px 5px;
    }

    @media screen and (max-width:767px){

        .link-css-mobile i {
            font-size: 16px;
        }
        header.header {
            padding: 15px 15px 0px 15px !important;
        }

        .home_qr_code{
            display: none;
        }

        .functions_reload_div{
            text-align: right;
        }
        #functions_reload a {
            margin: 0px 0px !important;
            padding: 0px 10px !important;
        }
        .headercontact {
            position: fixed;
            right: 0;
            top: 70px;
            width: 100%;
            padding: 19px 0px;
            z-index: 99999999;
            border-bottom: 1px solid transparent;
            float: left;
        }
        img.logo_normal_center {
            width: 220px;
            margin: -10px 0px -20px 0px;
        }
        .listing_see_all {
            display: inline-block !important;
        }
        div#logo {
            margin: 0px 30px;
        }
        #logo img {
            height: 28px;
            margin: 4px 0 0;
            min-width: 200px;
        }
        span#functions_reload {
            margin: 0px 0px auto;
            text-align: center;
            border: none;
            position: relative;
        }
        .direction_a {
            border: none;
            margin: 10px 10px;
            padding: 0;
        }
        div#listing_banner {
         margin-top: 87px;
        }
        .owl-carousel {
           margin: 25px 0px 0px 0px;
        }

        .main-menu ul li select {
            max-width: 100%;
            width: 70%;
        }
        .mm-listview>li>a, .mm-listview>li>span {
            text-align: left;
        }
        .company_d_title {
           margin: 15px;
           font-size: 20px;
           background: #fff;
           padding: 0px 0px;
        }
        li.company_details_logo img {
            margin: -20px 0px 0px 0px !important;
            position: relative !important;
        }
        .company_d_title_mobile {
            display: block;
            margin: 1px 130px -26px -230px;
            font-size: 17px;
            background: #fff;
            width: auto;
            font-family: fantasy;
            border-radius: 25px;
            padding: 3px 10px;
        }
        .single-listing-map {
            height: 212px;
            margin: 85px 0px 0px 0px;
        }
        .room_type {
            padding: 0 !important;
        }
        .dropdown-icon {
            margin-left: 0px;
            position: absolute;
            right: 5px;
            top: 30px;
        }
        .room_type {  border: none !important; padding: 3px 0px; }
        ul.hotel_facilities {
            margin: 5px 0px 0px 5px;
            padding: 10px;
        }
        #functions_reload a {
            font-size: 13px !important;
        }
        .google_translate_mobile {
            float: right;
            margin: 0px 35px;
        }
        .owl-prev {
            margin: 30px 10px !important;
        }
        .owl-next {
            margin: 30px 10px !important;
        }
        iframe{
            height: 193px !important;
        }
        #logo {
            width: 60%;
        }

        .goog-te-gadget-simple span {
            display: inherit !important;
        }
        .goog-te-gadget-simple {
            width: 96px;
        }
        #listing_banner .owl-item img {
            min-height: auto;
        }

        div#trademark.owl-carousel .owl-item img {
            display: block;
            width: 180px;
            height: 120px !important;
        }
        .mobile_direction_a{
            display: block !important;
        }
    }
    .notactivate:before {
        content: '';
        width:  100%;
        height: 100%;
        position: absolute;
        background-color: rgba(0,0,0,0.6) !important;
        z-index: 999;

    }
    .notactivate {
        position: relative;

    }
    .overlay-content {
        width: 80%;
        height: 80%;
        display: flex;
        justify-content: center;
        align-items: center;
        border: #fff solid 4px;
        color: #fff;
        font-size: 30px;
        z-index: 999;
        position: absolute;
        top: 0;
        left: 0;
        margin: 10%;
    }

    #customer_club h4 {
        width: 100%;
    }
    #customer_club p {
        text-align: center;
    }
    #customer_club p {
        text-align: center;
        width: 100%;
    }

    .customer-club-form-container {
        max-width: 600px;
        margin: 10px auto;
        background: #fff;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .btn-custom:hover {
        background-color: #0056b3;
    }
    .formlabel_state label {
        display: block;
    }
    span.cc_respondata {
        font-size: 18px;
    }
    .add_bottom_15 span a:hover {
        color: hsl(224.83deg 46.29% 30.68%) !important;
        font-size: 11px;
    }

    @media (min-width: 1700px) and (max-width: 2600px) {
        .container{
            max-width: 1600px;
        }
        figure.imageads small.adsmidtext {
            top: 80%!important;
        }
        div#product .strip figure a, div#sploffer a {
            display: ruby-text;
        }
        div#product .strip figure a, div#sploffer a {
            height: auto !important;
        }
    }

    @media only screen and (min-width: 768px) and (max-width: 992px) {
        .locally {
            margin: -62px 85px !important;
        }
        .link-css-mobile {
            padding-bottom: 15px;
        }
        span.locally_text {
            width: 280px !important;
            right: 150px !important;
            font-size: 18px !important;
            top: 5px;
            margin: 0px !important;
        }
        .headercontact {
            position: fixed;
            right: 0;
            top: 86px;
            width: 100%;
            padding: 4px 0px;
            z-index: 99999999;
            border-bottom: 1px solid transparent;
            float: left;
        }
        button#down-to-contact {
            margin: -20px 0px;
            position: relative;
            width: auto;
            z-index: 20000;
            padding: 0px 5px;
        }
        span.company_details_logo {
            margin: -23px 24px;
        }
        .mm-listview>li>a, .mm-listview>li>span {
            text-align: left;
        }
        .main-menu ul, .main-menu ul li, .main-menu ul ul {
            margin: 5px 0px 0px 0px;
            text-align: left;
        }
    }

    @media (min-width: 992px) and (max-width: 1199px) {
        span.company_details_logo {
            margin: -23px 24px;
        }
        span.locally_text {
            width: 160px !important;
            right: 154px !important;
            font-size: 18px !important;
            top: 5px;
            margin: 0px !important;
        }
    }

    @media screen and (min-width: 1600px) and (max-width: 2500px) {
        span.locally_text {
            margin: -74px 5px 0px 0px !important;
            float: right !important;
            width: 183px !important;
            color: #222121 !important;
            position: absolute !important;
            right: 261px !important;
            font-size: 14px !important;
        }
        .company_details_logo_img {
            width: 78px !important;
            margin: 10px 15px !important;
        }
    }

</style>



</main>
@endsection
