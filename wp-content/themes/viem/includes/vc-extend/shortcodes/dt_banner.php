<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Banner extends DTWPBakeryShortcode {
}
vc_map( 
array(
	'base' => 'dt_banner',
	'name' => esc_html__( 'Banner', 'viem' ),
	'description' => esc_html__( 'Banner templates.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_banner',
	'icon' => 'dt-vc-icon-dt_banner',
	'show_settings_on_create' => true,
	'params' => array(
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Template', 'viem' ),
			'param_name' => 'template',
			'admin_label' => true,
			'value' =>
			array(
				esc_html__( 'Lily', 'viem' ) => 'lily',
				esc_html__( 'Sadio', 'viem' ) => 'sadie',
				esc_html__( 'Layla', 'viem' ) => 'layla',
				esc_html__( 'Oscar', 'viem' ) => 'oscar',
				esc_html__( 'Romeo', 'viem' ) => 'romeo',
				esc_html__( 'Dexter', 'viem' ) => 'dexter',
				esc_html__( 'Duke', 'viem' ) => 'duke'
			),
			'description' => esc_html__( 'Select banner template.', 'viem' ) ),
		array(
			'type' => 'attach_image',
			'heading' => esc_html__( 'Banner', 'viem' ),
			'param_name' => 'banner',
			'description' => esc_html__( 'Banner image* (required).', 'viem' ) ),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Text', 'viem' ),
			'admin_label' => true,
			'param_name' => 'title',
			'value' => esc_html__( 'Banner', 'viem' ),
			'description' => esc_html__( 'Title on the banner. Allow html strong tag. For intance Nice <strong>Hotels</strong>', 'viem' ) ),
		array(
			'type' => 'textarea_safe',
			'heading' => esc_html__( 'Text', 'viem' ),
			'param_name' => 'desc',
			'value' => esc_html__(
				'Lily likes to play with crayons and pencils',
				'viem' ) ),
		array(
			'type' => 'href',
			'heading' => esc_html__( 'URL (Link)', 'viem' ),
			'param_name' => 'href',
			'description' => esc_html__( 'Button link.', 'viem' ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Target', 'viem' ),
			'param_name' => 'target',
			'std' => '_self',
			'value' => array(
				esc_html__( 'Same window', 'viem' ) => '_self',
				esc_html__( 'New window', 'viem' ) => "_blank" ),
			'dependency' => array(
				'element' => 'href',
				'not_empty' => true,
			) ),
		array(
			'type' => 'dropdown',
			'std' => '',
			'heading' => esc_html__( 'Post Image Size', 'viem' ),
			'param_name' => 'img_size',
			'value' => array_merge( array(esc_html__( 'Default', 'viem' ) => '' ),  viem_image_sizes_select_values() ),
			'description' => '<a target="_blank" href="' . esc_url( admin_url( 'themes.php?page=theme-options' ) ) . '#advanced">' . esc_html__( 'Edit image sizes', 'viem' ) . '</a>.',
			'group' => esc_html__( 'Appearance', 'viem' ),
		),
		
		 ) ) );
