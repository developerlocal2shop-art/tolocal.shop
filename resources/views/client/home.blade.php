@extends('layouts.client.master')
@section('title', $metatitle)
@section('content')

<?php
$useragent = $_SERVER['HTTP_USER_AGENT'];
$ismobiledevice = 0;
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{ $ismobiledevice = 1; }
?>
<div class="AJloader" id="AJloader"></div>
<div class="loader" id="loader"></div>
{{-- @include('layouts.client.language_popup') --}}
<div class="bg_color_1">
    <div class="container margin_80_55">
        <div class="main_title_2">
            <span style="text-align:left;"><em></em></span>
        </div>
        <div class="row g-4 offer_div" id="home_category">
           <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div shopping_div mobile_div_offer" >
            </div>
            @if($data['shoppingcard_count'] > 0)
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div shopping_div" >
                <div class="info" >
                    <figure>
                        <a href="{{ url('shoppingcard') }}" class="grid_item sk_fade"  >
                        <img src="{{ asset('assets/images/2.png') }}" alt="">
                        </a>
                    </figure>
                </div>
                <div class="offer_buttom">
                    <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                        <h4 class="offer_heading">{{__("message.shopping_card_text")}}</h4>
                        <a href="{{ url('shoppingcard') }}" class="btn_1 rounded" style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                        View all({{ $data['shoppingcard_count'] }})
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if($data['shoppingcardData'] > 0)
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div" >
                <div class="info">
                    <figure>
                        <a href="{{ url('hotoffer') }}" class="grid_item sk_fade"  >
                        <img src="{{ asset('assets/images/3.png') }}" alt="">
                        </a>
                    </figure>
                </div>
                <div class="offer_buttom">
                    <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                        <h4 class="offer_heading">{{__("message.offers_text")}} </h4>
                        <a href="{{ url('hotoffer') }}" class="btn_1 rounded" style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                        View all({{ $data['shoppingcardData'] }})
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if($data['splofferData'] > 0)
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div" >
                <div class="info" >
                    <figure>
                        <a href="{{ url('specialoffer') }}" class="grid_item sk_fade"  >
                        <img src="{{ asset('assets/images/4.png') }}" alt="">
                        </a>
                    </figure>
                </div>
                <div class="offer_buttom">
                    <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                        <h4 class="offer_heading">{{__("message.outgoingdeals_text")}} </h4>
                        <a href="{{ url('specialoffer') }}" class="btn_1 rounded" style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                        View all({{ $data['splofferData'] }})
                        </a>
                    </div>
                </div>
            </div>
            @endif
            @if($data['eventsData'] > 0)
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div" >
                <div class="info" >
                    <figure>
                        <a href="{{ route('event') }}" class="grid_item sk_fade"  >
                        <img src="{{ asset('assets/images/5.png') }}" alt="">
                        </a>
                    </figure>
                </div>
                <div class="offer_buttom">
                    <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                        <h4 class="offer_heading"> {{__("message.event_text")}}</h4>
                        <a href="{{ route('event') }}" class="btn_1 rounded" style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                        View all({{ $data['eventsData'] }})
                        </a>
                    </div>
                </div>
            </div>
            @endif

            @if($data['hotAdsCount'] > 0)
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div">
                <div class="info">
                    <figure>
                        <a href="{{ url('hotadslist') }}" class="grid_item sk_fade"  >
                        <img src="{{ asset('assets/images/6.png') }}" alt="">
                        </a>
                    </figure>
                </div>
                <div class="offer_buttom">
                    <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                        <h4 class="offer_heading notranslate"> {{__("message.advertisement_text")}}</h4>
                        <a href="{{ url('hotadslist') }}" class="btn_1 rounded" style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                        View all({{ $data['hotAdsCount'] }})
                        </a>
                    </div>
                </div>
            </div>
            @endif
            {{-- @if($data['today_competitions'] > 0 ) --}}
                <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div">
                    <div class="info">
                        <figure>
                            <a class="grid_item">
                                <img src="{{ asset('assets/images/today_com.png') }}" alt="">
                            </a>
                        </figure>
                    </div>
                    <div class="offer_buttom">
                        <div class="btn_home_align viewallcatbtn-align offer_button" style="text-align: center;">
                            <h4 class="offer_heading notranslate">Today's Competition</h4>
                            <a href="{{ url('competitions') }}"
                            class="btn_1 rounded"
                            style="padding: 7px 10px; font-size: 11px; margin: 0 auto;">
                                View all
                            </a>
                            <script>
                                function showLoginAlert(hasAccount) {
                                    if (hasAccount === false) {
                                        let message = '{{ __("message.competition_before_login_text") }}';
                                        alert(message);
                                        window.location.href = "{{ url('competition-login') }}";
                                    } else {
                                        //let message = '{{ __("message.competition_text") }}';
                                        //alert(message);
                                        window.location.href = "{{ url('account/questionnaire') }}";
                                    }
                                }
                            </script>
                        </div>
                    </div>
                </div>
            {{-- @endif --}}
            <div class="col-xl-2 col-lg-2 col-md-2 offer_item_div shopping_div mobile_div_offer" >
            </div>
        </div>
    </div>
    <!-- /container -->
</div>
<!--   Categories      -->
<div class="bg_color_1">
@if(count($data['categoryData']) > 0)
<br>
    <div class="container-fluid popular-comp sec-bg  margin_80_55 popular_comp_home">
        <div class="main_title_2">
            <div class="viewallcatbtn-align" style="text-align: left;">
                <span><em></em></span>
                <h3 class="title-text-main">
                    {{__("Categories")}}
                    <a href='{{ route('category', ['latitude' => $latitude, 'longitude' => $longitude]) }}' class="btn_1 rounded" style="font-size: 15px; padding: 10px;">
                        {{__("message.view_all")."(".$direCount.")"}}
                    </a>
                </h2>
            </div>
        </div>
        <div class="row home_category" id="home_category">
            @foreach($data['categoryData'] as $key => $row)
            @php
            $categoryName = Helper::getSeoUrl($row->name);
            $directoryCount = Helper::getCountRelatedDirectory($row->id, $latitude, $longitude);
            @endphp
             @if($directoryCount > 0)
                <div class="col-lg-2 col-md-2 cat_item_div">
                    <div class="info company_category">
                        <figure>
                            <a href="<?php echo url('/listings?cat='.$row->id); ?>" class="grid_item sk_fade">

                                <?php if($row->icon_class){ ?>
                                    <i class="{{ $row->icon_class }}" aria-hidden="true"></i>
                                <?php }else{ ?>
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" alt="">
                                <?php } ?>

                                <small class="btn_1 rounded" style=" border-radius: 0 !important;  width: 100%; ">
                                    {{ $directoryCount??0 }} {{__("message.menu_text_listing")}}
                                </small>
                            </a>

                        </figure>
                         <h4 class="cat_heading notranslate">{{ $row->title }}</h4>
                    </div>
                </div>
                @endif
            @endforeach
        </div>
    </div>
    <!-- /container -->
    @endif
</div>
<!--    POPULAR COMPANIES      -->
<?php if(count($data['directoryData']) > 0 ){ ?>
<div class="container-fluid popular-comp sec-bg  margin_80_55 popular_comp_home" data-aos="fade-up" data-aos-duration="1000" style="display:none;">
    <div class="main_title_2">
        <div class="viewallcatbtn-align" style="text-align: left;">
            <span><em></em></span>
            <h3 class="title-text-main">
                {{__("Companies")}}
                <a href='{{ url('listings') }}' class="btn_1 rounded" style="font-size: 15px; padding: 10px;">
                    {{__("message.view_all")."(".$data['countDirectoryData'].")"}}
                </a>
            </h2>
        </div>
    </div>
    <section id="testimonials">
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-12">
					<div class="slick-slider home_company_slider">
                    <?php foreach($data['directoryData'] as $row){
                    $details_text = Helper::getDirectorySeoSlug($row->id);
					$details_text = isset($details_text->slug)?$details_text->slug:'';
                    ?>
						<div class="card" >
							<div class="strip grid">
                                <figure>
                                    <?php $imagename = Helper::getDirectoryimage($row->id); ?>
                                    <?php if(!empty($imagename)){ ?>
                                    <a href="<?php echo url('listings/details', ['id' => $details_text]); ?>">
                                    <img src="{{ env('IMAGE_PATH').'images/directory/'.$imagename->name_thumbnail.'.webp' }}" class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php }else{ ?>
                                    <a href="<?php echo url('listings/details', ['id' => $details_text]); ?>">
                                    <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266">
                                        <div class="read_more"><span>{{__("message.read_more")}}</span></div>
                                    </a>
                                    <?php } ?>
                                </figure>
                                <div class="wrapper notranslate">
                                    <small style="background: rgb(255 115 37);width: 100%;padding: 10px 5px;margin: 0;border-radius: 0 !important;color: #fff;text-align: center;">
                                            {{ $row->title }}
                                    </small>
                                    <span class="home_td">
                                        @if(!empty($row->title))
                                            <h3>
                                                <a href="<?php echo url('listings/details', ['id' => $details_text]); ?>" class="">
                                                {{ substr(strip_tags($row->title),0,20) }}
                                                </a>
                                            </h3>
                                        @endif
                                        <p class="mt-1">{{ substr(strip_tags($row->dtr_description),0,28) }}</p>
                                    </span>
                                </div>
                            </div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</section>
    <!-- /carousel -->
    <!-- /container -->
</div>
<?php } ?>

<!--Hotads banner-->
<?php if(!empty($data['hotAds'])){ ?>
<div class="container-fluid sec-bg  margin_80_55 adevertiseDatad">
    @if(count($data['hotAds']) > 0)
        <div class="main_title_2">
            <div class="viewallcatbtn-align" style="text-align: left;">
                <span><em></em></span>
                <h3 class="title-text-main">
                    {{__("Hot Ads")}}
                    <a href='{{ url('hotadslist') }}' class="btn_1 rounded" style="font-size: 15px; padding: 10px;">
                        {{__("message.view_all")."(".count($data['hotAds']).")"}}
                    </a>
                </h2>
            </div>
        </div>
        <div id="banner-foot" class="owl-carousel owl-theme hot_add_home">
            <?php foreach($data['hotAds'] as $row){ ?>
                <div class="item hotads_home" data-aos="zoom-out-up" data-aos-duration="1000">
                    <div class="strip grid">
                        <figure class="imageads">
                        @if($row->video_display_on_home == 'yes')
                            @if(!empty($row->short_video))
                                @php
                                    $videoUrl = env('IMAGE_PATH') . 'files/advertisement/videos/' . $row->short_video;
                                    $extension = strtolower(pathinfo($row->short_video, PATHINFO_EXTENSION));
                                    $mimeTypes = [
                                        'mp4'  => 'video/mp4',
                                        'webm' => 'video/webm',
                                        'mov'  => 'video/quicktime',
                                    ];
                                    $mimeType = $mimeTypes[$extension] ?? 'video/mp4';
                                @endphp
                            <video class="scroll-video" width="100%" height="250" controls playsinline>
                                <source src="{{ $videoUrl }}" type="{{ $mimeType }}">
                                Your browser does not support this video.
                            </video>
                            @else
                                <span class="text-muted">No video uploaded.</span>
                            @endif
                        @else
                            <?php if(!empty($row->image_name)){ ?>
                            <a href="{{ route('hotads.detail',$row->id) }}">
                                <img src="{{ env('IMAGE_PATH').'files/advertisement/'.$row->image_name }}" class="img-fluid" alt="" width="400" height="266">
                            </a>
                            <?php }else{ ?>
                            <a href="{{ route('hotads.detail',$row->id) }}">
                                <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266">
                            </a>
                            <?php } ?>
                        @endif
                            <a href="{{ $row->link??'' }}" target="_blank">
                                <small class="hotadMoreinfo">
                                    More information
                                </small>
                            </a>
                            <a href="{{ route('hotads.detail',$row->id) }}" target="_blank">
                                <small class="adsmidtext notranslate">
                                    View Details
                                </small>
                            </a>
                        </figure>
                        <div class="wrapper featured-body">
                            <small class="notranslate">
                                <p>Valid until {{ \Carbon\Carbon::parse($row->end_date)->format('M j, Y') }}</p>
                            </small>
                            <small class="adsmidtext notranslate">
                                <a href="{{ route('hotads.detail',$row->id) }}" style=" font-weight: 600;">
                                {{ $row->title }}
                                </a>
                            </small>
                        </div>
                        <small class="notranslate">
                        {!! Str::words($row->description ?? '', 10, '...') !!}
                        </small>
                    </div>
                </div>
            <?php } ?>
        </div>
    @endif
</div>
<?php } ?>
<!--Hotads end-->

<!--blog-->
<?php if(!empty($data['blogData'])){ ?>
<div class="container-fluid" >
    @if(count($data['blogData']) > 0)
    <div class="main_title_2 blog_div">
        <div class="viewallcatbtn-align" style="text-align: left;">
            <span><em></em></span>
            <h3 class="title-text-main">
                {{__("message.blog_text")}}
                <a href='{{ url('blog') }}' class="btn_1 rounded" style="font-size: 15px; padding: 10px;">
                    {{__("message.view_all")."(".count($data['blogData']).")"}}
                </a>
            </h2>
        </div>
    </div>
    @endif

    <div id="blog-home" class="owl-carousel owl-theme">
        <?php foreach($data['blogData'] as $row){ ?>
        <div class="item" data-aos="zoom-in" data-aos-duration="1000">
            <div class="strip grid">
                <div class="date-comment">
                    <span class="date-time">
                        <span class="date">{{ date('j F Y', strtotime($row->created_at)) }}</span>
                    </span>
                </div>
                <figure>
                    <a href="{{ url('blog/details/'.$row->slug) }}">
                        <?php if($row->blog_image!=""){ ?>
                        <img src="{{ env('IMAGE_PATH').'images/blog/'.$row->blog_image.'.webp' }}" class="img-fluid" alt="" width="400" height="266">
                        <div class="read_more"><span>Read More</span></div>
                        <?php }else{ ?>
                        <img src="{{ asset('assets/plugins/images/placeholder.png') }}" class="img-fluid" alt="" width="400" height="266">
                        <div class="read_more"><span>Read More</span></div>
                        <?php } ?>
                    </a>
                </figure>
                <div class="blog-post-content">
                    <div class="title-block">
                        <a href="{{ url('blog/details/'.$row->slug) }}" class="blog-title">
                            {{ substr(strip_tags($row->title),0,20) }}
                        </a>
                        <p class="mr-0 blog-desc">
                            {!! substr(strip_tags($row->content),0,42) !!}
                        </p>
                        <a href="{{ url('blog/details/'.$row->slug) }}" class="read-more">
                            Read more
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<!--blog end-->



<script src="{{ asset('assets/client/js/slick.min.js') }}"></script>
<script type="text/javascript">

        document.addEventListener("DOMContentLoaded", function () {
            const videos = document.querySelectorAll(".scroll-video");
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    const video = entry.target;
                    if (entry.isIntersecting && entry.intersectionRatio >= 0.6) {
                        video.play().catch(() => {});
                    } else {
                        video.pause();
                        video.currentTime = 0; // Optional: restart when visible again
                    }
                });
            }, {
                threshold: 0.6 // Play when 60% of the video is visible
            });
            videos.forEach(video => observer.observe(video));
        });


     // Google tag event for product page view
    @if(app()->environment('production'))
        let sourceType = document.referrer ? 'referral' : 'direct';
        gtag('event', 'website_visitor', {
            visitor_type: sourceType,
            referrer: document.referrer || 'direct'
        });
    @endif
    let recognition;

    const startRecordingButton = document.getElementById('start-recording');
    const startRecording_product_Button = document.getElementById('start-recording-product');
	const voiceOutput = document.getElementById('searchquerylisting');
    const voiceOutput_product = document.getElementById('search_string');
	// Check browser support for Web Speech API
	//const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
    if ('SpeechRecognition' in window) {
        recognition = new SpeechRecognition();
        recognition_product = new SpeechRecognition();
    } else if ('webkitSpeechRecognition' in window) {
        recognition = new webkitSpeechRecognition();
        recognition_product = new webkitSpeechRecognition();
    } else {
        console.log("Speech Recognition API not supported in this browser.");
    }
    //const recognition_product = new (window.SpeechRecognition || window.webkitSpeechRecognition)();

	    @php
		if (session()->has('lang_code')) {
			if (session()->get('lang_code') == 'en') {
			@endphp
            if (recognition) {
			recognition.lang = "en-US";
            recognition_product.lang = "en-US";
            }
			@php
			}else if (session()->get('lang_code') == 'sv') {
			@endphp
            if (recognition) {
			recognition.lang = "sv-SE";
            recognition_product.lang = "sv-SE";
            }
			@php
			}
    	}else{
		@endphp
            if (recognition) {
                recognition.lang = 'en-US';
                recognition_product.lang = 'en-US';
            }
		@php
		}
		@endphp
		 // Start recording when button is clicked
		startRecordingButton.addEventListener('click', () => {
            if (recognition) {
            recognition.start();
            }
			$('#searchquerylisting').val('Listening...');
        });
        startRecording_product_Button.addEventListener('click', () => {
            if (recognition) {
            recognition_product.start();
            }
			$('.search_string_product').val('Listening...');
        });
        // When speech is detected
        if (recognition) {
        recognition.onresult = (event) => {
            const query = event.results[0][0].transcript;
            voiceOutput.textContent = `You said: "${query}"`;
			$('#searchquerylisting').val(query);
			$('#dir_btn').click();
        };
        recognition_product.onresult = (event) => {
            const query = event.results[0][0].transcript;
            voiceOutput_product.textContent = `You said: "${query}"`;
			$('.search_string_product').val(query);
			$('#product_btn').click();
        };
        }


    $(document).ready(function() {

        $('#dir_btn, #product_btn').on('click', function(){
            $('#AJloader').show();
            $('#loader').show();
        });

        setTimeout(function(){
            var session_city = "{{ session()->get('filter_city') }}";
            if (session_city !== 'Not found' && session_city !== null && session_city !== '') {
                $(".select_city_home #select2-city_name-container").text(session_city);
            }
		}, 500 );

        @php if(session()->get('errorempty')){ @endphp
            toastr.error('At least one field is required!');
        @php } @endphp

        $('#product_btn').on('click', function() {
            var search_string = $('#search_string').val();
            var cat = $('#cat').val();
            var filters = $('#filters').val();
            if (search_string == "" && cat == "" && filters == "") {
                toastr.error('At least one field is required!');
                 return false;
            }
        });

        $('#type_listing_pro').on('change', function() {
            var filterval = this.value;
            if(filterval == 'product'){
                $('.type_listing').hide();
                $('.type_product').show();
            }else{
                $('.type_listing').show();
                $('.type_product').hide();
            }
        });

        $(".popular-comp").fadeIn();
        var getcities = "{{ route('get.cities') }}";
        var token = "{{ csrf_token() }}";

        $('#city_name').select2({
            placeholder: "Search city",
            ajax: {
                type: "POST",
                url: getcities,
                dataType: 'json',
				delay:250,
                data:
                    function (params) {
                        return { search: params.term, _token:token };
                },
                processResults: function (response) {
                    return {
                        results: response
                    };
                },cache: true
            }
        });

	});

    function home_categories(limitation) {
        $.ajax({
            url: "home_categories/"
            , success: function(response) {
                $('#home_category_loader').show();
                setInterval(function() {
                    $('#home_category_loader').hide();
                    $('#home_category').html(response);
                }, 1500);

            }
        });
    }

    $(document).ready(function() {

         $('#blog-home').owlCarousel({
            items: 3,
            loop: true,
            autoplay: true,
            slideSpeed: 300,
            margin: 20,
            nav: true,
            dots: true,
            responsive: {
                0: { items: 1 },
                600: { items: 2 },
                1366: { items: 3 }
            }
        });

        $('#popular_companies,#sploffer,#event,#shopping-card').owlCarousel({
            loop: true
            , autoPlay: true
            , lazyLoad: true
            , singleItem: false
            , slideSpeed: 300
            , paginationSpeed: 400,
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
            , items: 3,
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
    });

   var $jquery = jQuery.noConflict();
        $jquery(document).ready(function(){
            $jquery('.slick-slider').slick({
                centerMode: true,
                slidesToShow: 5,
                centerPadding: '60px',
                //autoplay: true,
                autoplaySpeed: 3000,
                speed: 1000,
                arrows: true,
                responsive: [
                    {
                    breakpoint: 768,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                    },
                    {
                    breakpoint: 576,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2
                    }
                    }
                ]
                });
        });
</script>

<style>
    .featured-body p,.hotads_home p,small.adsmidtext{text-align:center}.hotads_home figure img{height:275px;object-fit:cover}.hotads_home{border-radius:16px;overflow:hidden;background:#fff;border:none;box-shadow:0 8px 25px rgba(0,0,0,.08);transition:.35s;position:relative}.company_category i,.p-discount{box-shadow:0 4px 10px rgba(0,0,0,.15)}.hotads_home:hover{transform:translateY(-8px);box-shadow:0 16px 35px rgba(0,0,0,.15)}.hotads_home figure{margin:0;position:relative}.hotads_home img{width:100%;height:200px;object-fit:cover;transition:transform .5s}.hotads_home:hover img{transform:scale(1.06)}.adsprice{position:absolute;top:12px;left:12px;z-index:2}.p-discount{background:linear-gradient(135deg,#ff3b3b,#ff6b6b);color:#fff;padding:6px 12px;font-size:12px;border-radius:20px;font-weight:700}.featured-body{padding:14px 16px;background:#f8f7ef}.featured-body a{font-size:15px;font-weight:700;color:#222;text-decoration:none}.featured-body a:hover{color:#ff4d4d}.featured-body p{background:#fff;color:#555;font-size:13px;padding:8px;border-radius:6px;margin:8px 0}.adsmidtext a{font-size:14px;color:#707478;font-weight:600;text-decoration:none}.adsmidtext a:hover{text-decoration:underline}.hotads_home p{background:#fff;padding:12px;font-size:12px;color:#666;margin:0;font-weight:600;letter-spacing:.6px}.listing-div{margin-bottom:25px}.typeahead{min-height:25px!important;max-height:25vh!important;overflow-y:auto!important;overflow-x:hidden!important;scrollbar-width:thin!important;scrollbar-color:#c1c1c1 #f5f5f5!important}.hotadMoreinfo{width:155px!important;padding:5px;font-size:14px}.company_category i{display:inline-flex;align-items:center;justify-content:center;width:80px;height:80px;font-size:41px;margin:5px 0 30px;background-color:#ff5a5f;color:#fff;border-radius:50%;transition:transform .2s,box-shadow .2s}.company_category i:hover{transform:translateY(-2px);box-shadow:0 8px 18px rgba(0,0,0,.2)}#blog-home .date-time{backdrop-filter:blur(8px);background:rgba(255,255,255,.75);padding:8px 12px;border-radius:12px;font-size:12px;font-weight:600;color:#222;box-shadow:0 4px 12px rgba(0,0,0,.1)}.home_company_slider .card{padding:0;border-radius:5%;border:1px solid rgb(235 235 235)}.home_company_slider .strip.grid{position:relative;border-radius:14px;overflow:hidden;height:300px;background:#fff;box-shadow:0 6px 20px rgba(0,0,0,.08);transition:.4s;display:flex;flex-direction:column}.home_company_slider .strip.grid:hover{transform:translateY(-5px);box-shadow:0 12px 30px rgba(255,90,95,.2)}.home_company_slider figure{height:60%;margin:0;overflow:hidden;position:relative}.home_company_slider figure img{width:100%;height:100%;object-fit:cover;transition:.4s}.home_category .info:hover figure img,.home_company_slider .strip.grid:hover img{transform:scale(1.08)}.home_company_slider figure::after{content:"";position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,.3),rgba(0,0,0,.05))}#loader,.AJloader,.home_company_slider .read_more{display:none}.home_company_slider .wrapper{padding:15px;text-align:left;flex:1;display:flex;flex-direction:column;justify-content:center}.home_company_slider .wrapper small{display:inline-block;background:linear-gradient(45deg,#ff5a5f,#f96)!important;color:#fff!important;padding:5px 12px;border-radius:20px;font-size:12px;margin-bottom:8px;font-weight:500;width:fit-content}.home_company_slider .wrapper h3{margin:0;font-size:16px;font-weight:600}.home_company_slider .wrapper h3 a{color:#222;text-decoration:none}.home_category .info:hover .cat_heading,.home_company_slider .wrapper h3 a:hover{color:#ff5a5f}.home_company_slider .wrapper p{font-size:13px;color:#666;margin-top:5px}.home_company_slider .slick-slide{margin:0 12px}.home_company_slider .slick-list{margin:0 -12px}svg{display:block!important;width:auto!important;height:auto!important}.modal-header{padding:0!important}div#blog-home{margin-bottom:15px}div#blog-home .strip figure{max-height:292px;height:396px}.box_list figure .read_more,.strip figure .read_more{top:30%}.strip .wrapper p{margin-bottom:0;padding:0 0 15px}div.home_category{margin:0 5px}span.home_td h3{text-align:center;padding:10px 5px}span.home_td h3 a{color:#000}span.home_td p{text-align:center;padding:5px;color:#000}.btn_home_align.viewallcatbtn-align.offer_button{position:absolute;bottom:25px!important;right:0!important;background:#0c869f;padding:15px 0!important;left:15px!important}.offer_buttom{background:#d8edff;margin:-18px 0 -33px;padding:50px;border:1px solid #b4ddff}.strip .wrapper{padding:0}.info{border:1px solid rgb(241 235 232)}.info small{background:#eaf3fa;color:#3d3b3b}#testimonials .slick-slide figure{height:265px;max-height:300px}#testimonials .strip figure a img{height:210px}.cat_heading{text-align:center;font-size:15px;font-weight:500}.row-hero{height:390px;justify-content:center;align-items:center;min-height:30rem}div.home_category figure img{height:130px!important}.strip .wrapper h3 a{line-height:20px}.strip{margin-bottom:0}.title-text-main{margin:0 25px;font-size:22px}.home_category{margin-top:30px}.home_category .cat_item_div{margin-bottom:25px;transition:.3s}.home_category .info{background:#fff;border-radius:14px;overflow:hidden;text-align:center;box-shadow:0 4px 15px rgba(0,0,0,.06);transition:.3s;height:100%;position:relative}.home_category .info:hover{transform:translateY(-6px);box-shadow:0 10px 30px rgba(0,0,0,.12)}.home_category figure{position:relative;margin:0;overflow:hidden}.home_category figure img{width:100%;height:140px;object-fit:cover;transition:transform .4s}.home_category figure small{position:absolute;bottom:0;left:0;background:linear-gradient(45deg,#ff5a5f,#f96);color:#fff;font-size:13px;padding:6px 10px;font-weight:500}.home_category .cat_heading{font-size:15px;font-weight:600;padding:12px 8px 15px;color:#333;margin:0;transition:color .3s}.home_category a{text-decoration:none;color:inherit}.offer_item_div{display:flex;flex-direction:column;height:100%;margin-bottom:15px}.offer_item_div .info{flex:1;overflow:hidden;border-radius:10px}.offer_item_div figure{margin:0;height:100%}.offer_item_div img{width:100%;height:100%;object-fit:cover;border-radius:10px}.offer_buttom{margin-top:-10px;position:relative;z-index:2}.offer_button{background:#1f8a9b;padding:15px;border-radius:12px;display:inline-block;width:80%;box-shadow:0 5px 15px rgba(0,0,0,.1)}.offer_heading{text-align:center;font-weight:500;color:#fff;font-size:13px;margin-bottom:8px}.offer_button .btn_1{background:#2c5aa0;color:#fff;font-size:12px;padding:6px 12px;border-radius:20px}h6.hotadd_heading{font-size:14px;line-height:normal;font-weight:400}@media (max-width:991px){.home_category figure img{height:120px}}@media (max-width:480px){.home_category .cat_item_div{flex:0 0 50%;max-width:50%;margin:5px 0!important;padding:2px 2px 0}}@media (min-width:1700px) and (max-width:2600px){.btn_home_align.viewallcatbtn-align.offer_button{bottom:25px!important;right:0!important;padding:15px 0!important;left:15px!important}.home_category figure small{font-size:16px}.home_company_slider .wrapper small{font-size:18px}.home_company_slider .wrapper p{font-size:17px}.offer_item_div{padding:15px!important}.typeahead{min-height:20px!important;max-height:20vh!important}select#type_listing_pro{margin:0 -100px!important}div#banner-foot.hot_add_home .strip figure{max-height:550px;height:450px}h6.hotadd_heading{font-size:19px}.container{max-width:1600px}#blog-home.owl-carousel .owl-item img{width:100%!important;object-fit:cover!important;object-position:bottom!important}}@media (min-width:2000px) and (max-width:3000px){select#type_listing_pro{margin:0 -132px!important}}@media (min-width:1024px) and (max-width:1400px){#home_category{justify-content:center}.offer_item_div{padding:15px 5px}#home_category .info img{width:100%;height:auto}select#type_listing_pro{left:100px}#home_category .offer_buttom{text-align:center}}@media (max-width:768px){.hotads_home img{height:170px}.hotads_home figure{height:200px!important}.home_qr_code,.mobile_div_offer{display:none!important}h6.hotadd_heading{font-size:12px}figure.imageads small.adsmidtext{top:85%;font-size:11px;line-height:14px}.strip figure small{display:ruby-text;left:3px;top:15px;padding:0;width:100%}.home_category .cat_heading{font-size:14px;font-weight:400;padding:10px 0;margin:2px}.goog-te-gadget-simple{margin:0 -25px!important}.home_company_slider .strip.grid{height:260px}.home_company_slider .card{border-radius:7%}.slick-slide img{display:unset;margin:0 -7px}.home_company_slider figure::after{inset:auto}.home_category .cat_item_div{flex:0 0 50%;max-width:50%;margin:5px 0!important;padding:2px 2px 0}.home_category figure img{height:110px}.offer_button{width:90%;padding:12px}div#home_category figure img{height:172px}.blog_div{margin:70px 0 20px}.blog-post-content{border:1px solid #cfcfcf}.cat_heading,.offer_heading{font-size:14px}.cat_item_div figure{height:auto}.title-text-main a.btn_1.rounded{float:right;margin:-5px 0}.title-text-main{font-size:18px;margin:0 15px}footer,form#addpro{margin:70px 0}.btn_home_align.viewallcatbtn-align.offer_button{bottom:15px!important;right:5px!important;padding:10px 0!important;left:10px!important}.offer_buttom{margin:-33px 0 -3px;padding:33px}.adevertiseDatad{margin:40px 0!important}.google_translate_element_home{display:block!important}div.home_category figure img{height:156px!important}#testimonials .strip figure a img{height:180px;width:100%}#testimonials .strip figure a img,div#trademark figure img{object-fit:fill}.strip .wrapper small{font-weight:500;font-size:11px;padding:5px 0!important;margin:-5px 0 -15px!important}.main_title_2 img{width:154px}#testimonials .mt-1,.main_title_2 span,.main_title_2 span em{display:none}.offer_item_div{padding:0!important;flex:0 0 50%;max-width:45%;margin:5px auto!important}.cat_item_div{flex:0 0 50%;max-width:48%;margin:0 auto!important;padding:5px}div#staticBackdrop{margin:75px auto;padding:0 3px}#home_category>.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%;margin:-5px 0-25px}.viewallcatbtn-align{margin-top:10px}#testimonials .slick-slide figure{height:190px;max-height:345px}#testimonials h3{margin:5px 0 0;padding:0;background:#fff}#testimonials .slick-slide{transform:scale(100%)!important;opacity:.8!important;margin:0 1px!important}.slick-slider{height:255px}.home_td{float:none}.strip figure .read_more span{font-size:11px;padding:3px 6px}.hero_single.version_2 .wrapper h3{margin-top:0;font-size:18px}.custom-search-input-2 input[type=submit]{height:40px;width:50%}.select2-container .select2-selection--single{height:35px!important}.main_title_2 h2{font-size:20px}.main_title_2 p,a.grid_item .info h3{font-size:15px}.strip .wrapper p{margin:7px;font-size:13px}#testimonials .slick-slide a{overflow:unset;line-height:19px;font-size:12px;padding:0 0 6px 7px;color:#1c1414;width:100%;margin:13px 0 5px}.select_city_home .select2-container--default .select2-selection--single .select2-selection__rendered{line-height:33px;font-size:13px}select#type_listing_pro{height:35px}.custom-search-input-2 input{height:35px;font-size:13px}.custom-search-input-2 i{line-height:35px;height:30px;font-size:13px}.hero_single.version_2{height:400px;padding:257px 0 230px 5px}.btn_1,a.btn_1{padding:10px 20px;font-weight:500;font-size:12px}.nice-select{height:35px;line-height:31px;font-size:13px}}
</style>


</main>
@endsection
