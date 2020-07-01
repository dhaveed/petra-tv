<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Video_Badges extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_video_badges',
		'name' => esc_html__( 'Video Badge', 'viem' ),
		'description' => esc_html__( 'Display multiple videos in the Badges.', 'viem' ),
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
				'value' => array(
					esc_html__( 'Grid', 'viem' ) => 'grid',
					esc_html__( 'Slider', 'viem' ) => 'slider',
					esc_html__( 'Ajax Navigation', 'viem' ) => 'ajax_nav',
					),
				),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Template', 'viem' ),
				'param_name' => 'template',
				'std' => '',
				'value' => array(
					esc_html__( 'White', 'viem' ) => '',
					esc_html__( 'Dark', 'viem' ) => 'dark',
				),
			),
			array(
				'type' => 'video_category',
				'heading' => esc_html__( 'Badge', 'viem' ),
				'taxonomy' => 'video_badges',
				"single_select"	=> true,
				'param_name' => 'badge',
				'admin_label' => true,
				'description' => esc_html__( 'Select a badge to query', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Show Badge', 'viem' ),
				'param_name' => 'show_badge',
				'std' => 'show',
				'dependency' => array( 'element' => "style", 'value' => array( 'grid' ) ),
				'value' => array(
					esc_html__( 'Show', 'viem' ) => 'show',
					esc_html__( 'Hide', 'viem' ) => 'hide',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Order by', 'viem' ),
				'param_name' => 'orderby',
				'std' => 'date',
				'value' => array(
					esc_html__( 'Recent First', 'viem' ) => 'date',
					esc_html__( 'Older First', 'viem' ) => 'oldest',
					esc_html__( 'Title Alphabet', 'viem' ) => 'alphabet',
					esc_html__( 'Title Reversed Alphabet', 'viem' ) => 'ralphabet',
					esc_html__('Random',  'viem') => "rand", ) ),
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
				'value' => 4,
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'ajax_nav' ) ),
				'description' => esc_html__( 'Select whether to display the layout in 2, 3 or 4 column per row.', 'viem' )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts Per page', 'viem' ),
				'param_name' => 'posts_per_page',
				'value' => 4,
				'description' => '' ),
			array(
				'type' => 'dropdown',
				'std' => 'viem_360_235_crop',
				'heading' => esc_html__( 'Post Image Size', 'viem' ),
				'param_name' => 'img_size',
				'value' => array_merge( array(esc_html__( 'Default', 'viem' ) => 'viem_360_235_crop' ),  viem_image_sizes_select_values() ),
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
