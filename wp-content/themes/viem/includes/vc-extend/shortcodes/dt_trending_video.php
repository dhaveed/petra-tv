<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Trending_Video extends DTWPBakeryShortcode {
}

vc_map( 
	array( 
		'base' => 'dt_trending_video', 
		'name' => esc_html__( 'Trending/Popular Video', 'viem' ), 
		'description' => esc_html__( 'Allows you to displays the most popular/trending/viewed videos by views or number comments.', 'viem' ), 
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
				'value' => esc_html__( 'Most Popular', 'viem' ), 
				'admin_label' => true ),
			array( 
				'param_name' => 'style', 
				'heading' => esc_html__( 'Style', 'viem' ),
				'description' => '', 
				'type' => 'dropdown', 
				'value' => array(
					esc_html__( 'Grid', 'viem' ) => 'grid',
					esc_html__( 'Grid V5', 'viem' ) => 'grid_v5',
				),
				'admin_label' => true ),
			array( 
				'param_name' => 'orderby', 
				'heading' => esc_html__( 'Order By', 'viem' ),
				'description' => '', 
				'type' => 'dropdown',
				'std' => 'views',
				'value' => array(
					esc_html__( 'Total Views', 'viem' ) => 'views',
					esc_html__( 'Comments Count', 'viem' ) => 'comment',
				),
			),
			array( 
				'param_name' => 'order', 
				'heading' => esc_html__( 'Order', 'viem' ),
				'description' => '', 
				'type' => 'dropdown',
				'std' => 'DESC',
				'value' => array(
					esc_html__( 'DESC', 'viem' ) => 'DESC',
					esc_html__( 'ASC', 'viem' ) => 'ASC',
				),
			),
			array( 
				"type" => "dropdown", 
				"heading" => __( "Columns", 'viem' ), 
				"param_name" => "columns", 
				'value' => array( '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12' ), 
				'std' => '4',
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
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'grid_v5' ) ),
				'value' => 4,
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
