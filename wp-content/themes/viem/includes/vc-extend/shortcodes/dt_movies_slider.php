<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Movies_Slider extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_movies_slider',
		'name' => esc_html__( 'Movie Slider', 'viem' ), 
		'description' => esc_html__( 'Show mutiple movies in a slider.', 'viem' ), 
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
				'heading' => esc_html__( 'Template', 'viem' ),
				'param_name' => 'template',
				'std' => '',
				'value' => array(
					esc_html__( 'White', 'viem' ) => '',
					esc_html__( 'Dark', 'viem' ) => 'dark',
					),
				),
			array( 
				'type' => 'post_category', 
				'taxonomy' => 'viem_movie_genre', 
				'heading' => esc_html__( 'Genres', 'viem' ), 
				'param_name' => 'categories', 
				'admin_label' => true, 
				'description' => esc_html__( 'Select a genres or leave blank for all', 'viem' ) ), 
			array( 
				'type' => 'post_category',
				'taxonomy' => 'viem_movie_genre',
				'heading' => esc_html__( 'Exclude Genres', 'viem' ), 
				'param_name' => 'exclude_categories',
				'description' => esc_html__( 'Select a genres to exclude', 'viem' ) ), 
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
					esc_html__('Random',  'viem') => "rand", ) ),
				 
			array( 
				'type' => 'textfield',
				'heading' => esc_html__( 'Posts to show', 'viem' ), 
				'param_name' => 'posts_to_show',
				'value' => 4, 
				'dependency' => array( 'element' => "style", 'value' => array( 'default', 'syncing' ) ),
				'description' => esc_html__( 'Select number of posts to show.', 'viem' ) ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Posts Per page', 'viem' ), 
				'param_name' => 'posts_per_page', 
				'value' => 8, 
				'description' => '' ),
			array(
				'type' => 'dropdown',
				'std' => 'default',
				'heading' => esc_html__( 'Post Image Size', 'viem' ),
				'param_name' => 'img_size',
				'dependency' => array( 'element' => "style", 'value' => array( 'default', 'syncing' ) ),
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
					'viem' )
				),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'viem' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'viem' ) ) ) ) );
