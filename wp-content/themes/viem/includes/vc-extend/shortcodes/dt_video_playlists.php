<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Video_Playlists extends DTWPBakeryShortcode {}
vc_map( 
	array( 
		'base' => 'dt_video_playlists', 
		'name' => esc_html__( 'Video Playlists', 'viem' ), 
		'description' => esc_html__( 'Youtube and Vimeo Video Playlists.', 'viem' ), 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_video_playlists', 
		'icon' => 'dt-vc-icon dt-vc-icon-dt_video_playlists', 
		'show_settings_on_create' => true, 
		'params' => array( 
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video Service', 'viem' ),
				'param_name' => 'video_service',
				'std' => 'youtube',
				'save_always'=>true,
				'value' => array( esc_html__( 'Youtube', 'viem' ) => 'youtube', esc_html__( 'Vimeo', 'viem' ) => 'vimeo' ),
				'description' => esc_html__( 'Select Video Service.', 'viem' ) ),
			array( 
				'type' => 'exploded_textarea', 
				'heading' => esc_html__( 'List Youtube Video ID', 'viem' ), 
				'param_name' => 'youtube_videos', 
				'save_always'=>true,
				'dependency' => array( 'element' => "video_service", 'value' => array('youtube') ),
				'description' => esc_html__( 'Input list Youtube Video ID in here. Divide values with linebreaks (Enter).', 'viem' ), 
				'value' => "lVFT93IEhvc,-zSY0BsEF3M,flqVUw_QTrU" ), 
			array(
				'type' => 'exploded_textarea',
				'heading' => esc_html__( 'List Vimeo Video ID', 'viem' ),
				'param_name' => 'vimeo_videos',
				'save_always'=>true,
				'dependency' => array( 'element' => "video_service", 'value' => array('vimeo') ),
				'description' => esc_html__( 'Input list Vimeo video ID in here. Divide values with linebreaks (Enter).', 'viem' ),
				'value' => "94049919,151875886,124518962" ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Columns', 'viem' ),
				'param_name' => 'columns',
				'save_always'=>true,
				'std'=>'2',
				'value' => array(
					esc_html__( '1', 'viem' ) => '1',
					esc_html__( '2', 'viem' ) => '2',) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Auto Next Video', 'viem' ),
				'param_name' => 'auto_next',
				'std' => 'yes',
				'save_always'=>true,
				'value' => array( 
					esc_html__( 'Yes', 'viem' ) => 'yes',
					esc_html__( 'No', 'viem' ) => 'off' ),
				),
			array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Auto Play', 'viem' ), 
				'param_name' => 'auto_play', 
				'std' => 'off', 
				'save_always'=>true,
				'value' => array( esc_html__( 'OFF', 'viem' ) => 'off', esc_html__( 'ON', 'viem' ) => 'on' ), ), 
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );