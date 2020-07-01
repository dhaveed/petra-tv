<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Post_Category extends DTWPBakeryShortcode {
}

vc_map( 
	array( 
		'base' => 'dt_post_category', 
		'name' => esc_html__( 'Post Category', 'viem' ), 
		'description' => esc_html__( 'Show mutiple posts in a category.', 'viem' ), 
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
				'param_name' => 'template',
				'heading' => esc_html__( 'Template', 'viem' ),
				'description' => '',
				'type' => 'dropdown', 
				'value' => array( esc_html__( 'Grid', 'viem' ) => 'grid', esc_html__( 'List', 'viem' ) => 'list' ), 
				'admin_label' => true ), 
			array( 
				"type" => "dropdown", 
				"heading" => esc_html__( 'Category (required)', 'viem' ), 
				"param_name" => "category", 
				"description" => '', 
				"value" => viem_get_post_category(), 
				'save_always' => true ), 
			array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Order by', 'viem' ), 
				'param_name' => 'orderby', 
				'std' => 'latest', 
				'value' => array( 
					esc_html__( 'Recent First', 'viem' ) => 'latest', 
					esc_html__( 'Older First', 'viem' ) => 'oldest', 
					esc_html__( 'Title Alphabet', 'viem' ) => 'alphabet', 
					esc_html__( 'Title Reversed Alphabet', 'viem' ) => 'ralphabet' ) ), 
			array( 
				'param_name' => 'posts_per_page', 
				'heading' => esc_html__( 'Posts per page', 'viem' ), 
				'description' => '', 
				'type' => 'textfield', 
				'value' => '4', 
				'dependency' => array( 'element' => "template", 'value' => array( 'list' ) ) ), 
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__(
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );
