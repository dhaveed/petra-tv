<?php
/*extract(shortcode_atts( array(
	'video_service'=>'youtube',
	'youtube_videos'=>'lVFT93IEhvc,-zSY0BsEF3M,flqVUw_QTrU',
	'vimeo_videos'=>'94049919,151875886,124518962',
	'columns'=>'2',
	'auto_next'=>'yes',
	'auto_play'=>'off',
	'el_class'=>'',
), $atts ));*/
?>
<div class="viem-video-playlists wpb_content_element">
<?php
viem_video_playlists($atts);
?>
</div>