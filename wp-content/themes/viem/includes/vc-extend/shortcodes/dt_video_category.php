<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Video_Category extends DTWPBakeryShortcode {
}

vc_map( 
	array( 
		'base' => 'dt_video_category', 
		'name' => esc_html__( 'Video Category', 'viem' ), 
		'description' => esc_html__( 'Show mutiple videos in a category.', 'viem' ), 
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
				"type" => "video_category",
				"heading" => esc_html__( 'Category (required)', 'viem' ),
				"param_name" => "category",
				"single_select"	=> true,
				"description" => '',
				'save_always' => true,
				'admin_label' => true,
			),
			array( 
				'param_name' => 'style', 
				'heading' => esc_html__( 'Style', 'viem' ), 
				'description' => '', 
				'type' => 'dropdown', 
				'value' => array( 
					esc_html__( 'Style 1 (A big one and the grid below)', 'viem' ) => 'style_1',
					esc_html__( 'Style 2 (List with a big one and the list below)', 'viem' ) => 'style_2',
					esc_html__( 'Grid', 'viem' ) => 'grid',
					esc_html__( 'Grid V3 (A big one and side grid)', 'viem' ) => 'grid_v3',
				), 
				'admin_label' => true ),
			array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Order by', 'viem' ), 
				'param_name' => 'orderby', 
				'std' => 'latest', 
				'value' => array( 
					esc_html__( 'Recent First', 'viem' ) => 'latest', 
					esc_html__( 'Older First', 'viem' ) => 'oldest', 
					esc_html__( 'Title Alphabet', 'viem' ) => 'alphabet', 
					esc_html__( 'Title Reversed Alphabet', 'viem' ) => 'ralphabet',
					esc_html__( 'Random',  'viem') => "rand", ) ),
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
				'dependency' => array( 'element' => "style", 'value' => array( 'style_1', 'style_2', 'grid' ) ),
				'value' => 3,
			),
			array(
				'param_name' => 'show_excerpt',
				'heading' => esc_html__( 'Show Excerpt', 'viem' ),
				'description' => '',
				'type' => 'dropdown',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid' ) ),
				'value' => 'show',
				'value' => array(
					esc_html__( 'Show', 'viem' ) => 'show',
					esc_html__( 'Hide', 'viem' ) => 'hide',
				),
			),
			array(
				'type' => 'dropdown',
				'std' => 'viem_750_490_crop',
				'heading' => esc_html__( 'Post Image Size', 'viem' ),
				'param_name' => 'img_size',
				'value' => array_merge( array(esc_html__( 'Default', 'viem' ) => 'viem_750_490_crop' ),  viem_image_sizes_select_values() ),
				'description' => '<a target="_blank" href="' . esc_url( admin_url( 'themes.php?page=theme-options' ) ) . '#advanced">' . esc_html__( 'Edit image sizes', 'viem' ) . '</a>.',
				'group' => esc_html__( 'Appearance', 'viem' ),
			),
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
