<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

require get_template_directory() . '/includes/vc-extend/functions.php';

$shorcodes = array(
	'dt_button' => 'dt_button.php',
	'dt_banner' => 'dt_banner.php',
	'dtnested_banner_slider' => 'dtnested_banner_slider.php',
	'dt_blog' => 'dt_blog.php',
	'dt_adsense' => 'dt_adsense.php',
	'dt_heading' => 'dt_heading.php',
	'dt_countdown' => 'dt_countdown.php',
	'dt_counter' => 'dt_counter.php',
	'dt_faq' => 'dt_faq.php',
	'dt_mailchimp' => 'dt_mailchimp.php',
	'dt_pricing_table' => 'dt_pricing_table.php',
	'dt_testimonial' => 'dt_testimonial.php',
// 	'dt_video_playlists' => 'dt_video_playlists.php',
// 	'dt_video' => 'dt_video.php',
// 	'dtnested_video_slider' => 'dtnested_video_slider.php',
	'dt_icon_box' => 'dt_icon_box.php',
	'dtnested_our_brand' => 'dtnested_our_brand.php',
	'dtnested_our_team' => 'dtnested_our_team.php',
	
	'dt_social_accounts'	=> 'dt_social_accounts.php',
	'dt_video_layout' => 'dt_video_layout.php',
	'dt_playlist' => 'dt_playlist.php',
	'dt_video_player' => 'dt_video_player.php',
	'dt_recent_videos' => 'dt_recent_videos.php',
	'dt_videos_slider' => 'dt_videos_slider.php',
	// Video ajax categories
	'dt_video_categories' => 'dt_video_categories.php',
	'dt_video_badges' => 'dt_video_badges.php',
	// Video Ajax Navigation
	'dt_video_category' => 'dt_video_category.php',
	'dt_video_featured' => 'dt_video_featured.php',
	'dt_trending_video' => 'dt_trending_video.php',
	// 
	'dt_trailer' => 'dt_trailer.php',
	'dtnested_movie_cast' => 'dtnested_movie_cast.php',
	'dt_movies_slider' => 'dt_movies_slider.php',
);

foreach ( $shorcodes as $shortcode ){
	require_once( get_template_directory() . '/includes/vc-extend/shortcodes/'.$shortcode );
}