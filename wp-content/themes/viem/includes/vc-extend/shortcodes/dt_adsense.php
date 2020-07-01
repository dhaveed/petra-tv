<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_AdSense extends DTWPBakeryShortcode {
}
vc_map( 
array(
	'base' => 'dt_adsense',
	'name' => esc_html__( 'AdSense', 'viem' ),
	'description' => esc_html__( 'Google AdSense.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_adsense_bullhorn',
	'icon' => 'dt-vc-icon-dt_adsense_bullhorn fa fa-bullhorn',
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Google AdSense Publisher ID', 'viem' ),
			'param_name' => 'adsense_id',
			'value' => '',
			'description' => esc_html__( 'Enter your Google AdSense Publisher ID', 'viem' ) ),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'AdSense Ads Slot ID', 'viem' ),
			'param_name' => 'slot_id',
			'value' => '',
			'description' => esc_html__( 'Enter Google AdSense Ad Slot ID', 'viem' ) ),
		array(
			'param_name' => 'el_class', 
			'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
			'type' => 'textfield', 
			'value' => '', 
			"description" => esc_html__( 
				"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
				'viem' ) ),
		array(
			'type' => 'css_editor',
			'heading' => esc_html__( 'Css', 'viem' ),
			'param_name' => 'css',
			'group' => esc_html__( 'Design options', 'viem' ) ) ) ) );
