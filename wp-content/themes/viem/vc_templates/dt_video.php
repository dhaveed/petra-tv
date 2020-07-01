<?php
$output = '';
extract(shortcode_atts(array(
	'type'					=>'inline',
	'background'			=>'',
	'icon_color'			=>'',
	'sub_heading_color'		=>'',
	'heading_color'			=>'',
	'sub_heading'			=>'',
	'heading'				=>'',
	'video_embed'			=>'',
), $atts));

$icon_color = viem_format_color($icon_color);
if(!empty($icon_color))
	$icon_color = 'style="color:'.esc_attr($icon_color).'"';
$sub_heading_color = viem_format_color($sub_heading_color);
if(!empty($sub_heading_color))
	$sub_heading_color = 'style="color:'.esc_attr($sub_heading_color).'"';
$heading_color = viem_format_color($heading_color);
if(!empty($heading_color))
	$heading_color = 'style="color:'.esc_attr($heading_color).'"';
if(!empty($video_embed)){
	
	$video_id = uniqid('video-featured-');
	$video = '';
	$video .= '<div class="viem-video-embed-shortcode wpb_content_element '.($type == 'popup'?' mfp-hide ':'').'">';
	$video .= '<div id="'.esc_attr($video_id).'" class="embed-wrap">';
	$video .= apply_filters('dawnthemes_embed_video', $video_embed);
	$video .= '</div>';
	$video .= '</div>';
	if($type == 'inline'){
		echo ($video);
	}elseif($type == 'popup'){
		// Please ensure magnific-popup enqueued
		$background_url = $background_image = '';
		if(!empty($background)){
			$background_url = wp_get_attachment_url($background);
			$background_image = '<img class="video-embed-shortcode-bg" src="'.esc_url($background_url).'"/>';
		}
		
		echo '<div class="viem-video-embed-shortcode wpb_content_element">';
		echo '<div class="video-embed-shortcode-bg-wrap">';
		echo viem_print_string( $background_image );
		echo '<a class="video-embed-action" data-video-inline="'.esc_attr($video).'" href="#'.esc_attr($video_id).'" data-rel="magnific-popup-video"><i class="fa fa-play" '.$icon_color.'></i></a>';
		echo '</div>';
		echo ($video);
		echo '<div class="video-embed-content">';
		
		if(!empty($heading))
			echo '<div class="video-embed-shortcode-heading" '.$heading_color.'>'.esc_html($heading).'</div>';
		
		if(!empty($sub_heading))
			echo '<p class="video-embed-shortcode-sub-heading" '.$sub_heading_color.'>'.esc_html($sub_heading).'</p>';
		
		echo '</div>';
		echo '</div>';
	}
}