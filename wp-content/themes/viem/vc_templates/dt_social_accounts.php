<?php
$output = '';
extract( shortcode_atts( array(
	'title' 				=> '',
	'facebook_url'			=> '',
	'twitter_url'			=> '',
	'google_plus_url'			=> '',
	'youtube_url'			=> '',
	'vimeo_url'			=> '',
	'pinterest_url'			=> '',
	'linkedin_url'			=> '',
	'rss_url'			=> '',
	'instagram_url'			=> '',
	'github_url'			=> '',
	'behance_url'			=> '',
	'stack_exchange_url'			=> '',
	'tumblr_url'			=> '',
	'soundcloud_url'			=> '',
	'dribbble_url'			=> '',
	'el_class'				=> '',
), $atts ) );

$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';

$socials = array(
	'facebook'=>array(
		'label'=>esc_html__('Facebook','viem'),
		'url'=> $facebook_url
	),
	'twitter'=>array(
		'label'=>esc_html__('Twitter','viem'),
		'url'=> $twitter_url
	),
	'google-plus'=>array(
		'label'=> esc_html__('Google+','viem'),
		'url'=> $google_plus_url
	),
	'youtube'=>array(
		'label'=>esc_html__('Youtube','viem'),
		'url'=> $youtube_url
	),
	'vimeo'=>array(
		'label'=>esc_html__('Vimeo','viem'),
		'url'=> $vimeo_url
	),
	'pinterest'=>array(
		'label'=>esc_html__('Pinterest','viem'),
		'url'=> $pinterest_url
	),
	'linkedin'=>array(
		'label'=>esc_html__('LinkedIn','viem'),
		'url'=> $linkedin_url
	),
	'rss'=>array(
		'label'=>esc_html__('RSS','viem'),
		'url'=> $rss_url
	),
	'instagram'=>array(
		'label'=>esc_html__('Instagram','viem'),
		'url'=> $instagram_url
	),
	'github'=>array(
		'label'=>esc_html__('GitHub','viem'),
		'url'=> $github_url
	),
	'behance'=>array(
		'label'=>esc_html__('Behance','viem'),
		'url'=> $behance_url
	),
	'stack-exchange'=>array(
		'label'=>esc_html__('StackExchange','viem'),
		'url'=> $stack_exchange_url
	),
	'tumblr'=>array(
		'label'=>esc_html__('Tumblr','viem'),
		'url'=> $tumblr_url
	),
	'soundcloud'=>array(
		'label'=>esc_html__('SoundCloud','viem'),
		'url'=> $soundcloud_url
	),
	'dribbble'=>array(
		'label'=>esc_html__('Dribbble','viem'),
		'url'=> $dribbble_url
	),
		
);

echo '<div class="viem-sc-social-accounts  dt-socials-list wpb_content_element">';
foreach ((array)$socials  as $social=>$data):
	if(!empty($data['url'])):
		echo '<div class="dt-socials-item '.$social.'">';
		echo '<a class="dt-socials-item-link" href="'.esc_url($data['url']).'" title="'.esc_attr($data['label']).'" ><i class="fa fa-'.$social.'"></i></a>';
		echo '</div>';
	endif;
endforeach;
echo '</div>';
