<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Mailchimp extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_mailchimp', 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'name' => esc_html__( 'Mailchimp Subscribe', 'viem' ), 
		'description' => esc_html__( 'Widget Mailchimp Subscribe.', 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_mailchimp', 
		'icon' => 'dt-vc-icon-dt_mailchimp', 
		'show_settings_on_create' => true, 
		'params' => array( 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Title', 'viem' ), 
				'param_name' => 'title', 
				'description' => esc_html__( 
					'Enter text which will be used as widget title. Leave blank if no title is needed.', 
					'viem' ) ),
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );