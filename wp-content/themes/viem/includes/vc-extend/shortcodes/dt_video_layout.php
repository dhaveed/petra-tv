<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Video_Layout extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_video_layout', 
		'name' => esc_html__( 'Video Layout', 'viem' ), 
		'description' => esc_html__( 'Display multiple Video layouts.', 'viem' ), 
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
				'heading' => esc_html__( 'Layout', 'viem' ), 
				'param_name' => 'style', 
				'std' => 'grid', 
				'admin_label' => true, 
				'value' => array( 
					esc_html__( 'List', 'viem' ) => 'list',
					esc_html__( 'Grid', 'viem' ) => 'grid',
					esc_html__( 'Masonry', 'viem' ) => 'masonry',
				),
				'description' => esc_html__( 'Select the layout for the video shortcode.', 'viem' ) 
			), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Columns', 'viem' ), 
				'param_name' => 'columns',
				'std' => 2,
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'masonry' ) ),
				'description' => esc_html__( 'Select whether to display the layout in 2, 3 or 4 column.', 'viem' )
			), 
			array( 
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts Per Page', 'viem' ), 
				'param_name' => 'posts_per_page', 
				'value' => 6, 
				'description' => esc_html__( 'Select number of posts per page.Set "-1" to display all', 'viem' ) ), 
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
				'type' => 'video_category', 
				'heading' => esc_html__( 'Categories', 'viem' ), 
				'param_name' => 'categories', 
				'admin_label' => true, 
				'description' => esc_html__( 'Select a category or leave blank for all', 'viem' ) ), 
			array( 
				'type' => 'video_category',
				'heading' => esc_html__( 'Exclude Categories', 'viem' ), 
				'param_name' => 'exclude_categories', 
				'description' => esc_html__( 'Select a category to exclude', 'viem' ) ), 
			array( 
				'type' => 'dropdown', 
				'std' => 'loadmore', 
				'heading' => esc_html__( 'Pagination', 'viem' ), 
				'dependency' => array( 'element' => "style", 'value' => array( 'grid', 'list', 'masonry' ) ),
				'param_name' => 'pagination', 
				'value' => array( 
					esc_html__( 'Ajax Load More', 'viem' ) => 'loadmore', 
					esc_html__( 'WP PageNavi', 'viem' ) => 'wp_pagenavi', 
					esc_html__( 'Infinite Scrolling', 'viem' ) => 'infinite_scroll', 
					esc_html__( 'No', 'viem' ) => 'no' 
				),
				'description' => esc_html__( 'Choose pagination type.', 'viem' )
			), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Load More Button Text', 'viem' ), 
				'param_name' => 'loadmore_text', 
				'dependency' => array( 'element' => "pagination", 'value' => array( 'loadmore' ) ), 
				'value' => esc_html__( 'Load More', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'std' => 'default',
				'heading' => esc_html__( 'Post Image Size', 'viem' ),
				'param_name' => 'img_size',
				'value' => array_merge( array(esc_html__( 'Default', 'viem' ) => 'default' ),  viem_image_sizes_select_values() ),
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
