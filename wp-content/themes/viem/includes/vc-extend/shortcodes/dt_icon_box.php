<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Icon_Box extends DTWPBakeryShortcode {
}
vc_map(
array(
	'base' => 'dt_icon_box',
	'name' => esc_html__( 'Icon Box', 'viem' ),
	'description' => esc_html__( 'Custom Icon Box.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_icon_box',
	'icon' => 'dt-vc-icon-dt_icon_box',
	'show_settings_on_create' => true,
	'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Icon library', 'viem' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'viem' ) => 'fontawesome',
					// esc_html__( 'Open Iconic', 'viem' ) => 'openiconic',
					// esc_html__( 'Typicons', 'viem' ) => 'typicons',
					// esc_html__( 'Entypo', 'viem' ) => 'entypo',
					esc_html__( 'Linecons', 'viem' ) => 'linecons',
				),
				'param_name' => 'icon_type',
				'description' => esc_html__( 'Select icon library.', 'viem' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-adjust', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false,
					// default true, display an "EMPTY" icon?
					'iconsPerPage' => 4000,
					// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'viem' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon_openiconic',
				'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'openiconic',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'viem' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon_typicons',
				'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'typicons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'typicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'viem' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon_entypo',
				'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'entypo',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon_linecons',
				'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'linecons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'viem' ),
			),
			array(
				"type" => "colorpicker",
				"value" => "#73bb67",
				"heading" => esc_html__("Color for icon", 'viem'),
				"param_name" => "icon_color"
			),
			array(
				"type" => "textfield",
				"value" => "32",
				"heading" => esc_html__("Font size for icon (px)", 'viem'),
				"param_name" => "icon_font_size",
			),
			array(
				'type' => 'href',
				'heading' => esc_html__( 'URL (Link)', 'viem' ),
				'param_name' => 'href',
				'description' => esc_html__( 'Custom link.', 'viem' ) ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Target', 'viem' ),
				'param_name' => 'target',
				'std' => '_self',
				'value' => array(
					esc_html__( 'Same window', 'viem' ) => '_self',
					esc_html__( 'New window', 'viem' ) => "_blank" ),
				'dependency' => array(
					'element' => 'href',
					'not_empty' => true,
					'callback' => 'vc_button_param_target_callback' ) ),
			array(
				"type" => "textfield",
				"heading" => esc_html__("Title", 'viem'),
				"param_name" => "title",
				"value" => esc_html__("Your Title Here ...",'viem'),
				"admin_label" => true
			),
			array(
				"type" => "textarea",
				"heading" => esc_html__("Description", 'viem'),
				"param_name" => "desc"
			),
			array(
				"type" => "dropdown",
				'std' => 'center',
				"value" => array(
					"left" => "left" ,
					"right" => "right" ,
					"center" => "center"
				),
				"heading" => esc_html__("Text align for box", 'viem'),
				"param_name" => "text_align"
			),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Template', 'viem' ),
				'param_name' => 'tpl_mode',
				'value' => array(
					esc_html__( 'Default', 'viem' ) => '',
					esc_html__( 'Dark', 'viem' ) => 'tikcetbox-tpl-dark'
				),
				'description' =>__('Template Dark is background transparent and color is white. You can add background for this element.','viem')
			),
		
		 ) ) );
