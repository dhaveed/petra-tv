<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Social_Accounts extends DTWPBakeryShortcode {
}
vc_map(
array(
	'base' => 'dt_social_accounts',
	'name' => esc_html__( 'Social Accounts', 'viem' ),
	'description' => esc_html__( 'Display Social Accounts list.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_icon_box',
	'icon' => 'dt-vc-icon-dt_icon_box',
	'show_settings_on_create' => true,
	'params' => array(
			array(
				'param_name' => 'title',
				'heading' => esc_html__( 'Title', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				'admin_label' => true ),
			array(
				'param_name' => 'facebook_url',
				'heading' => esc_html__( 'Facebook URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'twitter_url',
				'heading' => esc_html__( 'Twitter URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'google_plus_url',
				'heading' => esc_html__( 'Google+ URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'youtube_url',
				'heading' => esc_html__( 'Youtube URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'vimeo_url',
				'heading' => esc_html__( 'Vimeo URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'pinterest_url',
				'heading' => esc_html__( 'Pinterest URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'linkedin_url',
				'heading' => esc_html__( 'LinkedIn URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'rss_url',
				'heading' => esc_html__( 'RSS URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'instagram_url',
				'heading' => esc_html__( 'Instagram URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'github_url',
				'heading' => esc_html__( 'GitHub URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'behance_url',
				'heading' => esc_html__( 'Behance URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'stack_exchange_url',
				'heading' => esc_html__( 'StackExchange URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'tumblr_url',
				'heading' => esc_html__( 'Tumblr URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'soundcloud_url',
				'heading' => esc_html__( 'SoundCloud URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'dribbble_url',
				'heading' => esc_html__( 'Dribbble URL', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => '',
				),
			array(
				'param_name' => 'el_class',
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ),
				'type' => 'textfield',
				'value' => '',
				"description" => esc_html__(
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",
					'viem' ) ) ) ) );