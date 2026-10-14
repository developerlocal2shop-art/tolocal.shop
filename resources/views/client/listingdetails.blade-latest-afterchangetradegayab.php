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
<style>
    img.direction {
        float: right;
        margin: -55px 10px;
        width: 60px;
        height: auto;
    }
    .room_type.first img {
        min-height: 150px;
        max-height: 150px;
        object-fit: cover;
    }
    .room_type.first .col-md-4 {
        text-align: center;
    }
    @media screen and (max-width:767px){
        div#trademark.owl-carousel .owl-item img {
            display: block;
            width: 180px;
            height: 120px !important;
        }
    }
</style>
<div class="loader" id="loader"></div>
<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
$ismobiledevice = 0;
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    $ismobiledevice = 1;
}
// $user_ip = $_SERVER['REMOTE_ADDR'];
// $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
// $geo = @unserialize(file_get_contents('http://ip-api.com/php/'.$user_ip));

$user_ip = getenv('REMOTE_ADDR');
$geo = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);

if($user_ip=='127.0.0.1' || $user_ip=='::1' || $user_ip=='localhost' ){
    $timezone = 'Asia/Kolkata';
}else{
    $timezone = $geo["geoplugin_timezone"];
}

if($user_ip=='127.0.0.1' || $user_ip=='::1' || $user_ip=='localhost' ){
    $countryCode    = 'IN';
    $state          = 'National Capital Territory of Delhi';
    $state_array    = explode(" ",$state);
    $city           = 'Delhi';
}else{
    $countryCode    = $geo["geoplugin_countryCode"]??'';
    $state          = $geo["geoplugin_regionName"]??'';
    $state_array    = explode(" ",$state);
    $city           = $geo["geoplugin_city"]??'';
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
                            <div class="col-md-12">
                                @if(!empty($data['pubholidayData']))
                                <table>
                                    <?php foreach($data['pubholidayData'] as $publicdata){
                                    $openpuplictimeholiday =  json_decode($publicdata->custom_time); ?>
                                    <tr>
                                        <td class="notranslate" style="padding-right: 25px;">{{ $publicdata->custom_date }}</td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openpuplictimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda; margin-left:15px;"><b>Note: </b>{{ $publicdata->note }}</span></td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                @else
                                <div class="text-center">No Data Found</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="exceptionholiday">
                        <div class="row">
                            <div class="col-md-12">
                                @if(!empty($data['execholidayData']))
                                <table>
                                    <?php foreach($data['execholidayData'] as $execptionholidata){
                                    $openexecptimeholiday =  json_decode($execptionholidata->custom_time); ?>
                                    <tr>
                                        <td class="notranslate" style="padding-right: 25px;">{{ $execptionholidata->custom_date }}</td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openexecptimeholiday as $extratime){ echo "<tr><td>".$extratime->open_time."</td><td>-</td></tr>"; } ?></table>
                                        </td>
                                        <td class="notranslate" style="text-align: right;">
                                            <table><?php foreach($openexecptimeholiday as $extratime){ echo "<tr><td>".$extratime->close_time."</td></tr>"; } ?></table>
                                        </td>
                                        <td style="padding-right: 30px;"><span style="color: #004dda; margin-left:15px;"><b>Note: </b>{{ $execptionholidata->note }}</span></td>
                                    </tr>
                                    <?php } ?>
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
    <?php
    $totalpublicschedule = count($data['publicScheduleData']);
    $totalexceptionschedule = count($data['exceptionScheduleData']);
    ?>
    <nav class="secondary_nav sticky_horizontal_2 sticky-listingdetails-header">
        <div class="container">
            <ul class="clearfix">
                <li><a href="#description" class="active">{{__("message.description_title")}}</a></li>
                <li><a href="#reviews">Reviews</a></li>
                <?php
                $openCloseTags = 0;
                if($totalexceptionschedule>0){
                    $totalexeschedule = $data['exceptionScheduleData'][0]->custom_time;
                    $totaleceholiday = json_decode($totalexeschedule);
                    foreach($totaleceholiday as $exeholi){
                        $exeopentime = explode(":",$exeholi->open_time);
                        $execlosetime = explode(":",$exeholi->close_time);
                        if($execlosetime[0]>=$hour && $exeopentime[0]<=$hour){
                            $openCloseTags=1;
                            break;
                        }else{
                            $openCloseTags=0;
                        }

                    }
                    foreach($totaleceholiday as $exeholi){ ?>
                    <li>
                        <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block"><?php echo $exeholi->open_time ; ?> - <?php echo $exeholi->close_time; ?></button>
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
                        if($publicclosetime[0]>=$hour && $publicopentime[0]<=$hour){
                            $openCloseTags=1;
                            break;
                        }else{
                            $openCloseTags=0;
                        }

                    }
                    foreach($totalholiday as $pubhd){ ?>
                        <li>
                            <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block"><?php echo $pubhd->open_time ; ?> - <?php echo $pubhd->close_time; ?></button>
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
                <?php }else{
                    foreach($templateandScheduleDaysData as $days => $val){
                        $days_abb = substr($days, 0, 3);
                        if($days_abb==$serverDay){
                            $normalOpenTime = explode(":",$val[0]['open_time']);
                            $normalCloseTime = explode(":",$val[0]['close_time']);
                            foreach($val as $norholi){
                                $normalopentime = explode(":",$norholi['open_time']);
                                $normalclosetime = explode(":",$norholi['close_time']);
                                if($normalclosetime[0]>=$hour && $normalopentime[0]<=$hour){
                                    $openCloseTags=1;
                                    break;
                                }else{
                                    $openCloseTags=0;
                                }

                            }

                            foreach($val as $schd){?>
                                <li>
                                    <button id="down-to-schedule" class="btn btn-warning btn-sm btn-block"><?php echo $schd['open_time'] ; ?> - <?php echo $schd['close_time']; ?></button>
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

            </ul>

        </div>

    </nav>
    <a class="direction_a" target="_blank" href="https://www.google.com/maps/dir/<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>/@<?php echo $data['address'][0]->latitude; ?>,<?php echo $data['address'][0]->longitude; ?>,16z">
        <img  class="direction" src="{{ url('/') }}/assets/client/images/direction.jpg" width="10%" alt="">
    </a>

    <?php if(!empty($data['galleryImageChunk'])){ ?>
    <div class="container-fluid  margin_80_55">
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
        <div class="listing_see_all">
        @if(count($data['galleryImage']) > 5)
            <div class="see_all" data-toggle="modal" data-target="#allgalleryimages_model">See All</div>
        @endif
        </div>
    <?php } ?>

    <div class="container margin_60_35 listing-space-top">
        <div class="row">
    <?php //echo "<pre>"; print_r($data['mainDirectoryDetails']->directoryLanguage[0]); ?>
            <div class="col-lg-8">
                <div class="add_bottom_15 likebtn_alignment">
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
                    <?php /* ?><span class="loc_open mr-2" style="border: 1px solid #004dda;">
                        <input type="hidden" name="type_item" id="type_items" value="directory">
                        <a href="javascript:void(0);" title="Like Listing" productid="{{ $data['mainDirectoryDetails']->directoryLanguage[0]->directory_id }}" onclick="addToLikeCounter(this, <?php echo $data['mainDirectoryDetails']->directoryLanguage[0]->directory_id ?>)">
                            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                        </a>
                    </span><?php */ ?>
                </div>
                <section id="description">
                    <div class="detail_title_1">

                        <div class="row notranslate">
                            <?php /* ?><h1 style="padding-left: 13px;">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->title }}</h1><?php */ ?>
                            <h1 style="padding-left: 13px;">{{ $data['mainDirectoryDetails']->title }}</h1>
                            <?php if(!empty($data['directoryProductData'])){ ?>
                                <a class="relatedprovisitbtn" target="_blank" href="<?php echo url('listings-product', ['id' => $data['mainDirectoryDetails']->slug]); ?>">
                                <i class="fas fa-external-link-alt" aria-hidden="true"></i> Visit the Shop({{ count($data['directoryProductData']) }}) </a>
                            <?php } ?>

                        </div>
                        @if($data['address'][0]->address)
                            <a class="address" id="down-to-map" href="javascript:void(0);">{{ $data['address'][0]->address }}</a>
                        @endif

                        <p><i class="fas fa-hand-o-right" style="color: #999;" aria-hidden="true"></i> Please note : all automatically translated</p>
                    </div>

                    <!-- <h5>{{__("message.description_title")}}ffffff</h5> -->
                    <p class="text show-more-height">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->description }}</p>

                    <!-- @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->description) > 200 && $data['mainDirectoryDetails']->directoryLanguage[0]->description)
                        <div class="show-more" style="cursor:pointer;">Read More</div>
                    @endif -->
                    @if((!empty($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer)) && (!empty($data['mainDirectoryDetails']->directoryLanguage[0]->about_us)))

                    <div class="row">
                        <span class="desktop-offerabout we-offer-details" data-toggle="modal" data-target="#we_offer_details_model">
                            <div class="col-md-2">
                                <button class="btn btn-sm" title="We offer Details">We Offer || About Us</button>
                            </div>
                        </span>
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
                                        </li>
                                    </ul>

                                </div>
                                <div class="tab-content" id="tab-content" style="display: inline-block;width: 100%;padding-top: 10px;">
                                    <div class="tab-pane active" id="weoffermobile">
                                        <div class="row">
                                            <div class="col-md-12">
                                                @if(!empty($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer))
                                                <div class="post-content textweoffer" style="display:none">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->we_offer }}</div>
                                                @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->we_offer) > 70)
                                                    <div class="show-more-weoffer" data-toggle="modal" data-target="#we_offer_details_model" style="cursor:pointer;">Read More</div>
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
                                                    <div class="post-content textweoffer" style="display:none">{{ $data['mainDirectoryDetails']->directoryLanguage[0]->about_us }}</div>
                                                    @if(strlen($data['mainDirectoryDetails']->directoryLanguage[0]->about_us) > 70)
                                                        <div class="show-more-weoffer" data-toggle="modal" data-target="#we_offer_details_model" style="cursor:pointer;">Read More</div>
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

                    <!-- START Tags DATA -->
                    <div class="add_bottom_15 notranslate">

                        @if(count($data['tagsData']) > 0)
                        <br>
                            <!-- <span style="font-weight: 500; font-size: 18px;">Tags</span> -->

                        <?php
                        //dd($data['tagsData']);
                            foreach($data['tagsData'] as $tagsrow){
                            //$tagsdata = Helper::getDirectoryTags($tagsrow->tags_id);
                        ?>

                        <span class="loc_open mr-2" style="background-color: #b5b9b7;color: #000!important;border: 1px solid #b5b9b7;">
                            <!-----Do not remove the below href link----------->
                            <!-- <a href="{{ route('listingbytag',$tagsdata[0]->id??'0') }}" style="color: #000;">{{ $tagsdata[0]->tags??'0' }}</a> -->
                            <a href="javascript:void(0);" style="color: #000; pointer-events: none; cursor: default;">{{ $tagsrow->title??'0' }}</a>
                        </span>

                        <?php } ?>
                        @endif
                    </div>
                    <!-- END TAGS DATA -->
                    <br>
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
                                        <?php $imagename = Helper::getOfferimage($row->id); ?>
                                        <?php if(!empty($imagename)){ ?>
                                        <a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>"><img src="{{ env('IMAGE_PATH').'images/offer/'.$imagename[0]->name_thumbnail.'.webp' }}"  alt="" width="400" height="266"></a>
                                        <?php }else{ ?>
                                        <a href="<?php echo url('offer/detail', ['id' => $row->slug]); ?>"><img src="{{ asset('assets/plugins/images/placeholder.png') }}"  alt="" width="400" height="266"></a>
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
                        @if(count($data['offerData']) > 2)
                            <a class="offer-see-all-btn" href="{{ route('directory.hotoffers', $data['mainDirectoryDetails']->id??0 ) }}">
                                <div class="see_all">
                                See All
                                </div>
                            </a>
                        @endif
                    <?php } ?>
    <style>

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

    </style>
                    <!--------------Shopping card section---------->
                    <?php if(!empty($data['shoppingcardData'])){ ?>
                        <h5 class="add_bottom_15">Shopping Card ({{ count($data['shoppingcardData']) }})</h5>
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

                    <?php
                        if (isset($data['contactData'])) {
                            $contactData = $data['contactData'];
                    ?>
                                @if(!empty($contactData[0]->facebook) || (!empty($contactData[0]->twitter)) || (!empty($contactData[0]->linkedin)) || (!empty($contactData[0]->pinterest)) || (!empty($contactData[0]->whatsapp)) || (!empty($contactData[0]->instagram)) || (!empty($contactData[0]->tiktok)) || (!empty($contactData[0]->youtube)) || (!empty($contactData[0]->snapchat)))
                                <hr>
                                <h5 class="add_bottom_15 link-headings">{{__("Social Contact")}}</h5>
                                <div class="row mb-1 link-css-mobile">
                                    <div class="col-md-12">
                                        @if($contactData[0]->facebook)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->facebook??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Facebook
                                            </a>
                                        @endif
                                        @if($contactData[0]->twitter)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->twitter??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Twitter
                                            </a>
                                        @endif
                                        @if($contactData[0]->linkedin)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->linkedin??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Linkedin
                                            </a>
                                        @endif
                                        @if($contactData[0]->pinterest)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->pinterest??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Pinterest
                                            </a>
                                        @endif
                                        @if($contactData[0]->whatsapp)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->whatsapp??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Whatsapp
                                            </a>
                                        @endif
                                        @if($contactData[0]->instagram)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->instagram??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Instagram
                                            </a>
                                        @endif
                                        @if($contactData[0]->tiktok)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->tiktok??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Tiktok
                                            </a>
                                        @endif
                                        @if($contactData[0]->youtube)
                                            <a href="//{{preg_replace('(^https?://)', '', $contactData[0]->youtube??'' )}}" target="_blank" class="btn btn-info btn-sm">
                                            Youtube
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
                    <?php if(!empty($data['website_links'])){ ?>
                    <hr>
                    <h5 class="add_bottom_15 link-headings">{{__("message.website_links")}}</h5>
                    <div class="row mb-1 link-css-mobile">
                        <div class="col-md-12">
                            @foreach($data['website_links'] as $websitelinks)
                            <a href="{{ $websitelinks->link }}" target="_blank" class="btn btn-info btn-sm">{{ $websitelinks->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <?php } ?>
                    <hr>
                    <div class="row mb-3" id="schedule_sec">
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
                            <a href="{{ isset($contactData[0]->website)?$contactData[0]->website:'' }}" target="blank" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-globe" aria-hidden="true"></i>{{__("message.website_contactdata")}}</a>
                            <a href="mailto:{{ isset($contactData[0]->email)?$contactData[0]->email:'' }}" target="" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-envelope" aria-hidden="true"></i>{{__("message.email_us_contactdata")}}</a>
                            <a href="tel:+{{ isset($contactData[0]->phone)?$contactData[0]->phone:'' }}" target="" class="btn_1 full-width outline wishlist social-button" id="btn-wishlist-social"><i class="fa fa-phone" aria-hidden="true"></i>{{__("message.call_now_contactdata")}}</a>
                        </div>
                    </div>
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
                    $anemitycounter = 0;
                    ?>

                   <div class="row add_bottom_30">

                    <?php if($ismobiledevice == 1){ ?>
                        <ul style="padding: 3px 0px 0px 30px;">
                        @foreach($data['amenity'] as $amenityrow)
                        <?php if(in_array($amenityrow->id, $amenityData)){
                                if($anemitycounter > 1){
                                    $hiddencls = "hidden-amenities hidden";
                                }else{
                                    $hiddencls = "";
                                }
                        ?>
                                    <div class="{{ $hiddencls }}">
                                        <?php if($amenityrow->icon_class!=""){ ?>
                                            <li style="padding-top: 5px;"><i class="{{ $amenityrow->icon_class }}" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                        <?php }else{ ?>
                                            <li style="padding-top: 5px;"><i class="fas fa-check" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                        <?php } ?>
                                    </div>

                        <?php } $anemitycounter++; ?>
                        @endforeach
                        <li><a href="javascript::" id="amenity-toggle-btn" style="color: #004dda !important;" onclick="showToggle(this, 'hidden-amenities')">{{__("message.show_more")}}</a></li>
                        </ul>

                    <?php }else{ ?>

                        @foreach($data['amenity'] as $amenityrow)
                        <?php if(in_array($amenityrow->id, $amenityData)){ ?>
                            <div class="col-md-4">
                                <ul class="">
                                    <?php if($amenityrow->icon_class!=""){ ?>
                                        <li><i class="{{ $amenityrow->icon_class }}" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                    <?php }else{ ?>
                                        <li><i class="fas fa-check" aria-hidden="true"></i> {{ $amenityrow->title }}</li>
                                    <?php } ?>
                                </ul>
                            </div>
                        <?php } ?>
                        @endforeach

                    <?php } ?>


                    </div>
                    <?php } ?>

                    <div class="opening">
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
                            <div class="col-md-12 extra-time">
                                <table>
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
                            <div class="col-md-6">
                               @if($data['mainDirectoryDetails']->schedule_normal_comment)
                               <hr>
                                {{-- <h5> <i class="fa fa-comment-o" aria-hidden="true"></i> Note </h5>  --}}
                                <label class="col-md-12 p-0">{{ $data['mainDirectoryDetails']->schedule_normal_comment??'' }}</label>
                               @endif

                            </div>
                            <div class="col-md-6">
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
                        </div>

                        <?php /* --------------------- END EXTRA TIME AND COMMENT UPCOMING HOLIDAY DETAILS -------------------------*/ ?>
                    </div>
                    <!-- Listing Type Wise Inner Page -->
                    <?php $category_id = isset($data['directoryTypeData'][0]->category_id)?$data['directoryTypeData'][0]->category_id:"";
                    if(!empty($category_id)){
                        ?>

                        <h5>
                        <?php $typeName = Helper::getTypeCategory($category_id);
                        //echo isset($typeName[0]->name)?$typeName[0]->name:"";
                        ?>

                        </h5>
                        <?php
                        $reverseData = array_reverse($data['directoryTypeData']);

                        $numberofblock = isset($reverseData[0]->block_num)?$reverseData[0]->block_num+1:""; ?>
                        <?php
                        if($numberofblock!=0 ){
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
                                                if($typerow->block_num == $i){ ?>
                                                    <?php /* ?><li><label>{{ $typerow->label }}</label>: {{ $typerow->column_value }}</li><?php */ ?>
                                                    <li><i class="fas fa-hand-point-right" aria-hidden="true"></i>{{ " ".$typerow->column_value }}</li>
                                            <?php } } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php  } } }else{ ?>
                        <h4>No Data</h4>
                    <?php } } ?>





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
                            <iframe width="100%" height="500" src="<?php echo $vediolink ?>?autoplay=1" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            <?php }else{ ?>
                            <iframe width="100%" height="500" src="https://player.vimeo.com/video/214125425?loop=false&amp;byline=false&amp;portrait=false&amp;title=false&amp;speed=true&amp;transparent=0&amp;gesture=media" allowfullscreen allowtransparency allow="autoplay"></iframe>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <hr id="locationmap">
                    <!-- <h3>{{__("message.location")}}</h3> -->
                    <div id="map" class="map-full map-layout single-listing-map" style="z-index: 50;">
                        <iframe src="https://maps.google.com/maps?q=<?php echo $data['address'][0]->latitude;?>,<?php echo $data['address'][0]->longitude;?>&hl=es;z=14&amp;output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
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
            <!-- /col -->
            <!-- Contact Form Base On Package-->
            <aside class="col-lg-4" id="sidebar">
                <div class="box_detail booking">
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

                    <form id="bookingform" class="contact-us-form" action="{{ route('appointment.booking') }}" method="post">
                        {{ csrf_field() }}

                        <input type="hidden" name="user_id" value="{{ $data['mainDirectoryDetails']->user?$data['mainDirectoryDetails']->user->id:0 }}">
                        <input type="hidden" name="requester_id" value="">
                        <input type="hidden" name="listing_id" value="{{ $data['mainDirectoryDetails']->id }}">
                        <input type="hidden" name="listing_type" value="{{ $data['mainDirectoryDetails']->feature_type }}">
                        <input type="hidden" name="slug" value="clarance-hotel">
                        <input type="hidden" name="bookingComment" value="">
                        <div class="price">
                            <span class="contact">{{__("message.contact_us_title")}}</span>
                            <span class="respondata"></span>

                            <div class="score"><span>Awesome<em>2 Reviews</em></span><strong>5.0</strong></div>
                        </div>

                        <div class="form-group" id="input-dates">
                            <input type="hidden" name="directory_id" value="{{ $data['mainDirectoryDetails']->id }}">

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
                                <option value="{{ $ds_val->name??'' }}">{{ $ds_val->name??'' }}</option>
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

                        <button type="submit" class="add_top_30 btn_1 full-width purchase submitbtn">{{__("message.submit_button")}}</button>
                    </form>
                    <a style="display:none;" href="javascript:" onclick="addToWishList('6')" class="btn_1 full-width outline wishlist" id="btn-wishlist"><i class="fa fa-heart" aria-hidden="true"></i> Add to wishlist</a>
                    <div class="text-center"><small>{{__("message.contact_us_form_text")}}</small></div>
                </div>

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
            <div class="item">
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
                        <a href="javascript:void(0);" productid="{{ $productrow->id }}" class="wishlist-icon" onclick="addToWishList(this, <?php echo $productrow->id ?>)">
                            <?php
                        if(!in_array($productrow->id, $wislistProductId)){ ?>
                            <i class="fa fa-heart-o" aria-hidden="true"></i>
                            <?php }else{ ?>
                            <i class="fa fa-heart" aria-hidden="true"></i>
                            <?php } ?>
                        </a>
                        <?php $imagename = Helper::getProductImage($productrow->id); ?>
                        <a href="<?php echo url('shop/details', ['slug' => $productrow->seo_url]); ?>" class="d-block h-100 img" <?php if(!empty($imagename)){ ?> style="background-image:url('{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}')" <?php }else{ ?> style="background-image:url('{{ asset('assets/plugins/images/placeholder.png') }}')" <?php } ?>>
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
                                <a href="" class="w-c-q-icn"><i class="fa fa-heart"></i></a>
                                <a href="" class="w-c-q-icn"><i class="fa fa-shopping-bag"></i></a>
                            </div>
                        </a>
                        <!-- <small>Hotel</small> -->
                    </figure>
                    <div class="wrapper featured-body">
                        <h3 class="ellipsis"><a href="<?php echo url('shop/details', ['slug' => $productrow->seo_url]); ?>">{{ $productrow->product_lang_name }}</a></h3>
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
        <h2>Most Viewed Listings</h2>
    </div>
        <div id="mostviewed" class="owl-carousel owl-theme listing_gllary_img most_viewed_listings">
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
    <?php } ?>

    <script>
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
        $(document).ready(function() {

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

        });

    </script>
    <script type="text/javascript">
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

    </script>
    <script>
        $(document).ready(function() {
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

            $('#trmadeark').owlCarousel({
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


        });

    </script>

    <!-- This map-category.php file has all the fucntions for showing the map, marker, map info and all the popup markups -->
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
    </script>
    <script>

        /* Description Read More*/

        $(document).ready(function() {
            $('.textshow').on('click', function() {
                $('.textweoffer').show();
                var textclspre = $('.btnhidetexttoggle').length;
                //alert("Text Class : "+textclspre);
                if(textclspre==0){
                   $('#tablistaddbtn').after('<button class="btn btn-sm btnhidetexttoggle" id="texthideoffer" title="Hide Details" style="border-radius: 1.2rem !important; margin-left: 10px !important;"><i class="fas fa-minus-circle" aria-hidden="true"></i></button>');
                }
            });

            $(document).on("click", "#texthideoffer", function() {
                $('.textweoffer').hide();
            });

            $('.amenity-show-more').on('click', function() {
                $('.preAnemity').hide();
                $('.afterAnemity').show();
                var textclspre = $('.amenity-show-less').length;
                if(textclspre==0){
                   $('.afterAnemity').after('<div class="amenity-show-less show-more" style="cursor:pointer;">Show less</div>');
                }

                // var textclspre = $('.btnhidetexttoggle').length;

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
        });

        /* Popup Model We Offer Read More*/

        $(document).ready(function() {
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
        });


        /* Popup Model About Us Read More*/

        $(document).ready(function() {
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

    </script>
    <style>
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
            padding: 10px 10px 60px 20px;
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
        .btn:not(:disabled):not(.disabled) {
            margin: 2px !important;
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
            padding: 2px 5px;
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
            margin: 0 0 0 10px!important;
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
            max-width: 735px;
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
    </style>
</main>
@endsection
