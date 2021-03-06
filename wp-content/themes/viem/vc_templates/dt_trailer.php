<?php
extract(shortcode_atts(array(
	'title'				=> esc_html__( 'Trailer', 'viem' ),
	'thumb_img'			=> '',
	'video_type'      	=> 'youtube',
	'video_mp4'			=> '',
	'youtube_id'		=> '',
	'vimeo_id'			=> '',
	'video_ratio'		=> '169',
	'sticky_player'		=> 1,
	'el_class'			=> '',
	'css'				=> '',
), $atts));

$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

$video_id = uniqid('Player_trailer-dt_v');
$youtubePlaylistID = '';
$youtubeChannelID = '';

$video_autoNext = 'Stop video';

$posterImg = $imageUrl = $thumbImg = $popupImg = wp_get_attachment_image_url($thumb_img,'large');

switch ($video_ratio){
	case '43':
		$videoRatio = 4/3;
		break;
	case '32':
		$videoRatio = 3/2;
		break;
	case '219':
		$videoRatio = 21/9;
		break;
	default:
		$videoRatio = 16/9;
		break;
}
	
$videos = array(
	0 => array(
		'videoType' => 'youtube',
		'title' =>  '',
		'youtubeID' => $youtube_id,
		'vimeoID' => $vimeo_id,
		'mp4'	=> $video_mp4,
		'enable_mp4_download' => 'no',
		'imageUrl'	=> esc_url( $imageUrl ),
		'imageTimer' => 4,
		'prerollAD' => '',
		'prerollGotoLink' => '',
		'preroll_mp4' => '',
		'prerollSkipTimer' => '',
		'midrollAD' =>'',
		'midrollAD_displayTime' => '',
		'midrollGotoLink' => '',
		'midroll_mp4' => '',
		'midrollSkipTimer' => '',
		'postrollAD' => '',
		'postrollGotoLink' => '',
		'postroll_mp4' => '',
		'postrollSkipTimer' => '',
		'popupAdShow' => '',
		'popupImg' => '',
		'popupAdStartTime' => '',
		'popupAdEndTime' => '',
		'popupAdGoToLink' => '',
		'description' => '',
		'thumbImg' => esc_url( $thumbImg ),
		'info' => '',
	)
);
	
$v_player =
	array(
		'id' => $video_id,
		'instanceName' => $video_id,					//name of the player instance
		'instanceTheme' => viem_get_theme_option('video_instance_theme', 'dark'), //choose video player theme: "dark", "light"
		'autohideControls' => 3, //autohide HTML5 player controls
		'hideControlsOnMouseOut' => 'No', //hide HTML5 player controls on mouse out of the player: "Yes","No"
		'playerLayout' => 'fitToContainer', //Select player layout: "fitToContainer" (responsive mode), "fixedSize" (fixed mode), "fitToBrowser" (fill the browser mode)
		'videoPlayerWidth' => 1140,	//fixed total player width (only for playerLayout: "fixedSize")
		'videoPlayerHeight' => 420,	//fixed total player height (only for playerLayout: "fixedSize")
		'videoRatio' => $videoRatio, //set your video ratio (calculate video width/video height)
		'videoRatioStretch' => false,                   //adjust video ratio for case when playlist is "opened" : true/false
		'floatPlayerOutsideViewport' => $sticky_player == 1 ? true : false,           //show Sticky player if video player is not in viewport when scrolling through page
		'lightBox' => false, //lightbox mode :true/false
		'lightBoxAutoplay' =>  false, //autoplay video when lightbox opens: true/false
		'lightBoxThumbnail' => esc_url( $posterImg ), //lightbox thumbnail image
		'lightBoxThumbnailWidth' =>  400, //lightbox thumbnail image width
		'lightBoxThumbnailHeight' =>  220, //lightbox thumbnail image height
		'lightBoxCloseOnOutsideClick' =>  true, //close lightbox when clicked outside of player area
		'playlist' => 'Off', //choose playlist type: "Right playlist", "Bottom playlist", "Off"
		'playlistScrollType' => "light", //choose scrollbar type: "light","minimal","light-2","light-3","light-thick","light-thin","inset","inset-2","inset-3","rounded","rounded-dots","3d","dark","minimal-dark","dark-2","dark-3","dark-thick","dark-thin","inset-dark","inset-2-dark","inset-3-dark","rounded-dark","rounded-dots-dark","3d-dark","3d-thick-dark"
		'playlistBehaviourOnPageload' => "opened (default)",//choose playlist behaviour when webpage loads: "closed", "opened (default)" (not apply to Vimeo player)
		'autoplay' => false, //autoplay when webpage loads: true/false
		'colorAccent' => viem_get_theme_option('main_color', '#ecc200'), // '#ecc200', //'#cc181e', // Main Color
		'vimeoColor' => viem_get_theme_option('vimeo_player_color', '#00adef'), //set "hexadecimal value", default vimeo color is "00adef"
		'youtubeControls' => "custom controls",			  //choose youtube player controls: "custom controls", "default controls"
		'youtubeSkin' => viem_get_theme_option('video_instance_theme', 'dark'),                          //default youtube controls theme: light, dark
		'youtubeColor' => "red",                          //default youtube controls bar color: red, white
		'youtubeQuality' => viem_get_theme_option('video_youtube_quality', 'default'), //choose youtube quality: "small", "medium", "large", "hd720", "hd1080", "highres", "default"
		'youtubeShowRelatedVideos' => "No",			  //choose to show youtube related videos when video finish: "Yes", "No" (onFinish:"Stop video" needs to be enabled)
		'videoPlayerShadow' => "off",                 //choose player shadow:  "effect1" , "effect2", "effect3", "effect4", "effect5", "effect6", "off"
		'loadRandomVideoOnStart' => "No",                 //choose to load random video when webpage loads: "Yes", "No"
		'shuffle' => "No",				                  //choose to shuffle videos when playing one after another: "Yes", "No" (shuffle button enabled/disabled on start)
		'posterImg' => esc_url( $posterImg ),//player poster image
		'posterImgOnVideoFinish' => esc_url( $posterImg ),//player poster image on video finish (if enabled onFinish:"Stop video")
		'onFinish' => $video_autoNext, //"Play next video", "Stop video",
		'nowPlayingText' => "No",                        //enable disable now playing title: "Yes","No"
		'fullscreen' => "Fullscreen native",              //choose fullscreen type: "Fullscreen native","Fullscreen browser"
		'preloadSelfHosted' => "none",                    //choose preload buffer for self hosted mp4 videos (video type HTML5): "none", "auto"
		'rightClickMenu' => true,                         //enable/disable right click over HTML5 player: true/false
		'hideVideoSource' => false,						 //option to hide self hosted video sources (to prevent users from download/steal your videos): true/false
		'showAllControls' => true,						 //enable/disable all HTML5 player controls: true/false 
		'allowSkipAd' => viem_get_theme_option('allow_skip_ad', '1') == '1' ? true : false, //enable/disable "Skip advertisement" option: true/false
		'infoShow' => "No",                              //enable/disable info option: "Yes","No"
		'shareShow' => "No",                             //enable/disable all share options: "Yes","No"
		'facebookShow' => "No",                          //enable/disable facebook option individually: "Yes","No"
		'twitterShow' => "No",                           //enable/disable twitter option individually: "Yes","No"
		'mailShow' => "No",                              //enable/disable mail option individually: "Yes","No"
		'facebookShareName' => "Viem - Video WordPress Theme",      //first parametar of facebook share in facebook feed dialog is title
		'facebookShareLink' => "https://themeforest.net/user/dawnthemes",  //second parametar of facebook share in facebook feed dialog is link below title
		'facebookShareDescription' => "Viem - Video WordPress Theme.", //third parametar of facebook share in facebook feed dialog is description below link
		'facebookSharePicture' => 'https://s3.envato.com/files/235066018/DawnThemes_Banner.jpg', //fourth parametar in facebook feed dialog is picture on left side
		'twitterText' => "ustClick - Video WordPress Theme", //first parametar of twitter share in twitter feed dialog is text
		'twitterLink' => "https://themeforest.net/user/dawnthemes", //second parametar of twitter share in twitter feed dialog is link
		'twitterHashtags' => "Viem",  //third parametar of twitter share in twitter feed dialog is hashtag
		'twitterVia' => "Video WordPress Theme", //fourth parametar of twitter share in twitter feed dialog is via (@)
		'googlePlus' => "https://themeforest.net/user/dawnthemes", //share link over Google +
		'logoShow' => "No", //"Yes","No"
		'logoClickable' => "Yes", //"Yes","No"
		'logoPath' => get_template_directory_uri()."/assets/images/logo.png",             //path to logo image
		'logoGoToLink' => 'http://codecanyon.net/', //redirect to page when logo clicked
		'logoPosition' => "bottom-left", //choose logo position: "bottom-right","bottom-left"
		'embedShow' => "No", //enable/disable embed option: "Yes","No"
		'embedCodeSrc' => "www.yourwebsite.com/videoplayer/index.html", //path to your video player on server
		//'embedCodeW' => "746", //embed player code width
		//'embedCodeH' => "420", //embed player code height
		'embedCodeW' => "725", //embed player code width
		'embedCodeH' => "408", //embed player code height
		'embedShareLink' => "www.yourwebsite.com/videoplayer/index.html", //direct link to your site (or any other URL) you want to be "shared"
		'showGlobalPrerollAds' =>  false, //enable/disable 'global' ads and overwrite each individual ad in 'videos' :true/false
		'globalPrerollAds' =>  "url1;url2;url3;url4;url5",//set 'pool' of url's that are separated by ; (global prerolls will play randomly)
		'globalPrerollAdsSkipTimer' =>  5, //skip global advertisement seconds
		'globalPrerollAdsGotoLink' =>  "http://codecanyon.net/",//global advertisement goto link
		'advertisementTitle' => esc_html( viem_get_theme_option('advertisement_title', 'Advertisement') ), //translate "Advertisement" title to your language
		'skipAdvertisementText' => esc_html( viem_get_theme_option('skip_advertisement_text', 'Skip advertisement') ), //translate "Skip advertisement" button to your language
		'skipAdText' => esc_html( viem_get_theme_option('skip_ad_text', 'You can skip this ad in') ), //translate "You can skip this ad in" counter to your language
		'playBtnTooltipTxt' => esc_html( viem_get_theme_option('play_btn_tooltip_txt', 'Play') ), //translate "Play" to your language
		'pauseBtnTooltipTxt' => esc_html( viem_get_theme_option('pause_btn_tooltip_txt', 'Pause') ), //translate "Pause" to your language
		'rewindBtnTooltipTxt' => esc_html( viem_get_theme_option('rewind_btn_tooltip_txt', 'Rewind') ),  //translate "Rewind" to your language
		'downloadVideoBtnTooltipTxt' => "Download video", //translate "Download video" to your language
		'qualityBtnOpenedTooltipTxt' => esc_html( viem_get_theme_option('quality_btn_opened_tooltip_txt', 'Close settings') ), //translate "Close settings" to your language
		'qualityBtnClosedTooltipTxt' => esc_html( viem_get_theme_option('quality_btn_close_tooltip_txt', 'settings') ),  //translate "Settings" to your language
		'muteBtnTooltipTxt' => esc_html( viem_get_theme_option('mute_btn_tooltip_txt', 'Mute') ),  //translate "Mute" to your language
		'unmuteBtnTooltipTxt' => esc_html( viem_get_theme_option('unmute_btn_tooltip_txt', 'Unmute') ),  //translate "Unmute" to your language
		'fullscreenBtnTooltipTxt' => esc_html( viem_get_theme_option('fullscreen_btn_tooltip_txt', 'Fullscreen') ), //translate "Fullscreen" to your language
		'exitFullscreenBtnTooltipTxt' => esc_html( viem_get_theme_option('exit_fullscreen_btn_tooltip_txt', 'Exit fullscreen') ),//translate "Exit fullscreen" to your language
		'infoBtnTooltipTxt' => esc_html( viem_get_theme_option('infoBtnTooltipTxt', 'Show info') ),	//translate "Show info" to your language
		'embedBtnTooltipTxt' => esc_html( viem_get_theme_option('embedBtnTooltipTxt', 'Embed') ), //translate "Embed" to your language
		'shareBtnTooltipTxt' => "Share", //translate "Share" to your language
		'volumeTooltipTxt' => esc_html( viem_get_theme_option('volume_tooltip_txt', 'Volume') ), //translate "Volume" to your language
		'playlistBtnClosedTooltipTxt' => esc_html( viem_get_theme_option('playlist_btn_closed_tooltip_txt', 'Show playlist') ), //translate "Show playlist" to your language
		'playlistBtnOpenedTooltipTxt' => esc_html( viem_get_theme_option('playlist_btn_opened_tooltip_txt', 'Exit fullscreen') ), //translate "Exit fullscreen" to your language
		'facebookBtnTooltipTxt' => "Share on Facebook",   //translate "Share on Facebook" to your language
		'twitterBtnTooltipTxt' => "Share on Twitter",     //translate "Share on Twitter" to your language
		'googlePlusBtnTooltipTxt' => "Share on Google+",  //translate "Share on Google+" to your language
		'lastBtnTooltipTxt' => esc_html( viem_get_theme_option('last_btn_tooltip_txt', 'Go to last video') ),  //translate "Go to last video" to your language
		'firstBtnTooltipTxt' => esc_html( viem_get_theme_option('first_btn_tooltip_txt', 'Go to first video') ),  //translate "Go to first video" to your language
		'nextBtnTooltipTxt' => esc_html( viem_get_theme_option('next_btn_tooltip_txt', 'Play next video') ),  //translate "Play next video" to your language
		'previousBtnTooltipTxt' => esc_html( viem_get_theme_option('previous_btn_tooltip_txt', 'Play previous video') ), //translate "Play previous video" to your language
		'shuffleBtnOnTooltipTxt' => esc_html( viem_get_theme_option('shuffle_btn_on_tooltip_txt', 'Shuffle on') ), //translate "Shuffle on" to your language
		'shuffleBtnOffTooltipTxt' => esc_html( viem_get_theme_option('shuffle_btn_off_tooltip_txt', 'Shuffle off') ), //translate "Shuffle off" to your language
		'nowPlayingTooltipTxt' => esc_html( viem_get_theme_option('nowplaying_btn_tooltip_txt', 'NOW PLAYING') ), //translate "NOW PLAYING" to your language
		'embedWindowTitle1' => "SHARE THIS PLAYER:", //translate "SHARE THIS PLAYER:" to your language
		'embedWindowTitle2' => "EMBED THIS VIDEO IN YOUR SITE:",//translate "EMBED THIS VIDEO IN YOUR SITE:" to your language
		'embedWindowTitle3' => "SHARE LINK TO THIS PLAYER:",//translate "SHARE LINK TO THIS PLAYER:" to your language
		'youtubePlaylistID' => $youtubePlaylistID, //automatic youtube playlist ID (leave blank "" if you want to use manual playlist) LL4qbSRobYCjvwo4FCQFrJ4g
		'youtubeChannelID' => $youtubeChannelID,  //automatic youtube channel ID (leave blank "" if you want to use manual playlist) UCHqaLr9a9M7g9QN6xem9HcQ
		'rootFolder' => get_template_directory_uri().'/assets/lib/video-player/',
		//manual playlist
		'videos' => $videos,
);

ob_start();
?>
<div id="<?php echo esc_attr($sc_id);?>" class="viem-sc-trailer wpb_content_element  <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?>">
		<div class="viem_sc_heading">
			<?php if(!empty($title)):?>
			<h3 class="viem-sc-title"><span class=""><?php echo esc_html($title);?></span></h3>
			<?php endif;?>
		</div>
		<div class="viem-sc-content">
			<div id="viem-sc-trailer-wrapper">
				<div class="viem-video-player-wrapper">
					<div id="v-container">
						<?php
						echo '<div class="DawnThemes_video_player Elite_video_player" id="' . $video_id . '" ><div id="DawnThemes_video_options" style="display:none;">' .
						json_encode( $v_player ) . '</div></div>';
						?>
					</div>
				</div>
			</div>
		</div>
</div>
<?php

echo ob_get_clean();