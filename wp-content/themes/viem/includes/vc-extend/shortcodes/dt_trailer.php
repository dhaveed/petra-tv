<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Trailer extends DTWPBakeryShortcode {}
vc_map( 
	array( 
		'base' => 'dt_trailer', 
		'name' => esc_html__( 'Movie Trailer', 'viem' ), 
		'description' => '', 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_video_playlists', 
		'icon' => 'dt-vc-icon-dt_video', 
		'show_settings_on_create' => true,
		'params' => array(
			array(
				'param_name' => 'title',
				'heading' => esc_html__( 'Title', 'viem' ),
				'description' => '',
				'type' => 'textfield',
				'value' => esc_html__( 'Trailer', 'viem' ),
				'admin_label' => true ),
			array(
				'type' => 'attach_image',
				'heading' => esc_html__( 'Thumbnail', 'viem' ),
				'param_name' => 'thumb_img',
				'description' => esc_html__( 'Video thumbnail.', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video player type', 'viem' ),
				'param_name' => 'video_type',
				'std' => 'youtube',
				'save_always'=>true,
				'value' => array(
					esc_html__( 'Youtube', 'viem' ) => 'youtube',
					esc_html__( 'Vimeo', 'viem' ) => 'vimeo',
					esc_html__( 'HTML5 (self-hosted)', 'viem' ) => 'HTML5'
					),
				'description' => ''
			),
			array(
				'param_name' => 'video_mp4',
				'heading' => esc_html__( 'MP4 Video URL', 'viem' ),
				'type' => 'textfield',
				'value' => '',
				'dependency' => array( 'element' => "video_type", 'value' => array( 'HTML5' ) ),
				'description' => esc_html__( 'HTML5 video mp4,  HLS m3u8 (url)', 'viem' )
			),
			array(
				'param_name' => 'youtube_id',
				'heading' => esc_html__( 'YouTube ID', 'viem' ),
				'type' => 'textfield',
				'value' => '',
				'dependency' => array( 'element' => "video_type", 'value' => array( 'youtube' ) ),
				'description' => esc_html__( 'last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE', 'viem' )
			),
			array(
				'param_name' => 'vimeo_id',
				'heading' => esc_html__( 'Vimeo ID', 'viem' ),
				'type' => 'textfield',
				'value' => '',
				'dependency' => array( 'element' => "video_type", 'value' => array( 'vimeo' ) ),
				'description' => esc_html__( 'last part of the URL http://vimeo.com/119641053', 'viem' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video aspect ration', 'viem' ),
				'param_name' => 'video_ratio',
				'std' => '169',
				'value' => array(
					'16:9' => '169',
					'4:3' => '43',
					'3:2' => '32',
					'21:9' => '219',
				),
				"description" => esc_html__( "Select video aspect ratio.", 'viem' )
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Sticky player', 'viem' ),
				'description' => esc_html__( 'Sticky player if not in viewport when scrolling through page', 'viem' ),
				'param_name' => 'sticky_player',
				'std' => 1,
				'value' => array( esc_html__( 'OFF', 'viem' ) => 0, esc_html__( 'ON', 'viem' ) => 1 ), ),
			array(
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );