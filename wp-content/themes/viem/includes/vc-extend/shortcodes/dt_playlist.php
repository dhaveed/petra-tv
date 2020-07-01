<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}
$playlists = array();
$args = array(
	'post_type' => 'viem_playlist',
	'posts_per_page' => '-1'
);
$p = new WP_Query($args);
if( $p->have_posts() ){
	while ($p->have_posts()){ $p->the_post();
		$playlists[get_the_title()] = get_the_ID();
	}
}
wp_reset_postdata();

class WPBakeryShortCode_DT_Playlist extends DTWPBakeryShortcode {}
vc_map( 
	array( 
		'base' => 'dt_playlist',
		'name' => esc_html__( 'Video Playlist', 'viem' ), 
		'description' => esc_html__( 'Display a Playlist.', 'viem' ), 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_video_playlists',
		'icon' => 'dt-vc-icon dt-vc-icon-dt_video_playlists', 
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
				'heading' => esc_html__( 'Playlist', 'viem' ),
				'param_name' => 'playlist',
				'std' => '',
				'save_always'=>true,
				'admin_label' => true,
				'value' => $playlists,
				'description' => esc_html__( 'Select a Playlist.', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Select Player Theme', 'viem' ),
				'param_name' => 'video_instance_theme',
				'std' => 'dark',
				'value' => array(
					esc_html__( 'Dark', 'viem' ) => 'dark',
					esc_html__( 'Light', 'viem' ) => 'light',
				),
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Select player shadow', 'viem' ),
				'param_name' => 'video_player_shadow',
				'std' => 'off',
				'value' => array(
					esc_html__( 'off', 'viem' ) => 'off',
					esc_html__( 'effect1', 'viem' ) => 'effect1',
					esc_html__( 'effect2', 'viem' ) => 'effect2',
					esc_html__( 'effect3', 'viem' ) => 'effect3',
					esc_html__( 'effect4', 'viem' ) => 'effect4',
					esc_html__( 'effect5', 'viem' ) => 'effect5',
					esc_html__( 'effect6', 'viem' ) => 'effect6',
				),
			),
			array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Auto Play', 'viem' ), 
				'param_name' => 'auto_play', 
				'std' => 'off', 
				'value' => array( esc_html__( 'OFF', 'viem' ) => 'off', esc_html__( 'ON', 'viem' ) => 'on' ), ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Video aspect ration', 'viem' ),
				'param_name' => 'video_ratio',
				'std' => '219',
				'value' => array(
					'21:9' => '219',
					'16:9' => '169',
					'4:3' => '43',
					'3:2' => '32',
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
					'viem' ) ),
			array(
				'type' => 'css_editor',
				'heading' => esc_html__( 'Css', 'viem' ),
				'param_name' => 'css',
				'group' => esc_html__( 'Design options', 'viem' ) ) ) ) );