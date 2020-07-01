<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Heading extends DTWPBakeryShortcode {
}
vc_map( 
array(
	'base' => 'dt_heading',
	'name' => esc_html__( 'Heading', 'viem' ),
	'description' => esc_html__( 'Heading.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_heading',
	'icon' => 'dt-vc-icon-dt_heading',
	'show_settings_on_create' => true,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Text', 'viem' ),
			'admin_label' => true,
			'param_name' => 'text',
			'value' => esc_html__( 'heading', 'viem' ),
			'description' => esc_html__( 'This is custom heading element.', 'viem' ) ),
		array(
			'param_name' => 'font_size',
			'heading' => esc_html__( 'Font Size (px)', 'viem' ),
			'type' => 'textfield',
			'value' => ''),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Text color', 'viem' ),
			'param_name' => 'text_color',
			'description' => esc_html__( 'Select heading color.', 'viem' ) ),
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