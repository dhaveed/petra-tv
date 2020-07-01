<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Countdown extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_countdown', 
		'name' => esc_html__( 'Countdown', 'viem' ), 
		'description' => esc_html__( 'Display Countdown.', 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_countdown', 
		'icon' => 'dt-vc-icon-dt_countdown', 
		'show_settings_on_create' => true, 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'params' => array( 
			/*array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Style', 'viem' ), 
				'param_name' => 'style', 
				'admin_label' => true, 
				'value' => array( 
					esc_html__( 'White', 'viem' ) => 'white', 
					esc_html__( 'Black', 'viem' ) => 'black' ), 
				'description' => esc_html__( 'Select style.', 'viem' ) ),*/
			array( 
				'type' => 'ui_datepicker', 
				'heading' => esc_html__( 'Countdown end', 'viem' ), 
				'param_name' => 'end', 
				'description' => esc_html__( 'Please select day to end.', 'viem' ), 
				'value' => '' ), 
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );