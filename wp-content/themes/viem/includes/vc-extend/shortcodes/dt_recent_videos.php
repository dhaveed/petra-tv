<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Recent_Videos extends DTWPBakeryShortcode {
}

vc_map( 
	array( 
		'base' => 'dt_recent_videos', 
		'name' => esc_html__( 'Recent Videos', 'viem' ), 
		'description' => esc_html__( 'Display your site most recent videos with multiple styles.', "viem" ), 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_post', 
		'icon' => 'dt-vc-icon-dt_post', 
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
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Style', 'viem' ), 
				'param_name' => 'style', 
				'std' => 'grid', 
				'admin_label' => true,
				'value' => array(
					esc_html__( 'Grid - Default', 'viem' ) => 'grid',
					esc_html__( 'Grid - V1', 'viem' ) => 'grid-v1',
					esc_html__( 'Grid - V2', 'viem' ) => 'grid-v2',
					esc_html__( 'Grid - V6', 'viem' ) => 'grid-v6',
				),
				'description' => esc_html__( 'Select style to display the latest posts.', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Template', 'viem' ),
				'param_name' => 'template',
				'std' => '',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'grid-v1' ) ),
				'value' => array(
					esc_html__( 'White', 'viem' ) => '',
					esc_html__( 'Dark', 'viem' ) => 'dark',
				),
			),
			array(
				'type' => 'video_category',
				'heading' => esc_html__( 'Include Categories', 'viem' ),
				'param_name' => 'categories',
				'admin_label' => true,
				'description' => esc_html__( 'Select a category or leave blank for all', 'viem' ) ),
			array(
				'type' => 'video_category',
				'heading' => esc_html__( 'Exclude Categories', 'viem' ),
				'param_name' => 'exclude_categories',
				'description' => esc_html__( 'Select a category to exclude', 'viem' ) ),
			array( 
				"type" => "dropdown", 
				"heading" => __( "Columns", 'viem' ), 
				"param_name" => "columns", 
				'value' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12' ), 
				'std' => '3',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid' ) ),
				"description" => '' ),
			array(
				"type" => "dropdown", 
				"heading" => __( "Tablet Columns", 'viem' ), 
				"param_name" => "tablet_columns", 
				'value' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ), 
				'std' => '3',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid' ) ),
				"description" => '' ),
			array( 
				"type" => "dropdown", 
				"heading" => __( "Mobile Columns", 'viem' ),
				"param_name" => "mobile_columns", 
				'value' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ), 
				'std' => '2',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid' ) ),
				"description" => '' ),
			array(
				'param_name' => 'posts_per_page',
				'heading' => esc_html__( 'Posts per page', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'single-slider', 'slider', 'slider-v8' ) ),
				'value' => 4,
			),
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' )
				),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'viem' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'viem' ) ) ) ) );