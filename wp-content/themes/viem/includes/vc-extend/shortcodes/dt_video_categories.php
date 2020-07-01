<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Video_Categories extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_video_categories',
		'name' => esc_html__( 'Video Categories', 'viem' ),
		'description' => esc_html__( 'Display ajax multiple videos in the categories.', 'viem' ),
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
				'std' => 'style_1',
				'value' => array(
					esc_html__( 'Style 1', 'viem' ) => 'style_1',
					esc_html__( 'Style 2 (Grid)', 'viem' ) => 'style_2',
					esc_html__( 'Style 3 (Grid - one big and side with two items list)', 'viem' ) => 'style_3',
					),
				),
			array(
				'type' => 'video_category',
				'heading' => esc_html__( 'Categories', 'viem' ),
				'param_name' => 'categories',
				'admin_label' => true,
				'description' => esc_html__( 'Select a category or leave blank for all', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'viem' ),
				'param_name' => 'orderby',
				'std' => 'view_by',
				'value' => array(
					esc_html__( 'Trending/Popular Videos', 'viem' ) => 'view_by',
					esc_html__( 'Recent First', 'viem' ) => 'latest',
					esc_html__( 'Older First', 'viem' ) => 'oldest',
					esc_html__( 'Title Alphabet', 'viem' ) => 'alphabet',
					esc_html__( 'Title Reversed Alphabet', 'viem' ) => 'ralphabet',
					esc_html__('Random',  'viem') => "rand", ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'List By', 'viem' ),
				'param_name' => 'view_by',
				'std' => 'views',
				'dependency' => array( 'element' => "orderby", 'value' => array( 'view_by' ) ),
				'value' => array(
					esc_html__( 'Total Views', 'viem' ) => 'views',
					esc_html__( 'Comments Count', 'viem' ) => 'comment',
				)
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order', 'viem' ),
				'param_name' => 'order',
				'std' => 'DESC',
				'value' => array(
					esc_html__( 'DESC', 'viem' ) => 'DESC',
					esc_html__( 'ASC', 'viem' ) => 'ASC',
				)
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Columns', 'viem' ),
				'param_name' => 'columns',
				'std' => 4,
				'dependency' => array( 'element' => "style", 'value' => array( 'style_2' ) ),
				'value' => 4,
				'description' => esc_html__( 'Select whether to display the layout in 2, 3 or 4 column.', 'viem' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts Per Page', 'viem' ),
				'param_name' => 'posts_per_page',
				'std' => 4,
				'dependency' => array( 'element' => "style", 'value' => array( 'style_2' ) ),
				'value' => 4
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
					'viem' )
				),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'viem' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'viem' ) ) ) ) );
