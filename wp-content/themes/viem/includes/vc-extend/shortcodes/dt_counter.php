<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Counter extends DTWPBakeryShortcode {
}
vc_map( 
	array( 
		'base' => 'dt_counter', 
		'name' => esc_html__( 'Counter', 'viem' ), 
		'description' => esc_html__( 'Display Counter.', 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_counter', 
		'icon' => 'dt-vc-icon-dt_counter',
		'show_settings_on_create' => true, 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'params' => array( 
			array( 
				'param_name' => 'speed', 
				'heading' => esc_html__( 'Counter Speed', 'viem' ), 
				'type' => 'textfield', 
				'value' => '2000' ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Number', 'viem' ), 
				'param_name' => 'number', 
				'description' => esc_html__( 'Enter the number.', 'viem' ) ), 
			array( 
				'type' => 'checkbox', 
				'heading' => esc_html__( 'Format number displayed ?', 'viem' ), 
				'dependency' => array( 'element' => "number", 'not_empty' => true ), 
				'param_name' => 'format', 
				'value' => array( esc_html__( 'Yes,please', 'viem' ) => 'yes' ) ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Thousand Separator', 'viem' ), 
				'param_name' => 'thousand_sep', 
				'dependency' => array( 'element' => "format", 'not_empty' => true ), 
				'value' => ',', 
				'description' => esc_html__( 'This sets the thousand separator of displayed number.', 'viem' ) ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Decimal Separator', 'viem' ), 
				'param_name' => 'decimal_sep', 
				'dependency' => array( 'element' => "format", 'not_empty' => true ), 
				'value' => '.', 
				'description' => esc_html__( 'This sets the decimal separator of displayed number.', 'viem' ) ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Number of Decimals', 'viem' ), 
				'param_name' => 'num_decimals', 
				'dependency' => array( 'element' => "format", 'not_empty' => true ), 
				'value' => 0, 
				'description' => esc_html__( 'This sets the number of decimal points shown in displayed number.', 'viem' ) ), 
			
			array( 
				'type' => 'colorpicker', 
				'heading' => esc_html__( 'Custom Number Color', 'viem' ), 
				'param_name' => 'number_color', 
				'dependency' => array( 'element' => "number", 'not_empty' => true ), 
				'description' => esc_html__( 'Select color for number.', 'viem' ) ), 
			array( 
				'param_name' => 'number_font_size', 
				'heading' => esc_html__( 'Custom Number Font Size (px)', 'viem' ), 
				'type' => 'textfield', 
				'value' => '32', 
				'data_min' => '10', 
				'dependency' => array( 'element' => "number", 'not_empty' => true ), 
				'data_max' => '120' ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Units', 'viem' ), 
				'param_name' => 'units', 
				'description' => esc_html__( 
					'Enter measurement units (if needed) Eg. %, px, points, etc. Graph value and unit will be appended to the graph title.', 
					'viem' ) ), 
			array( 
				'type' => 'colorpicker', 
				'heading' => esc_html__( 'Custom Units Color', 'viem' ), 
				'param_name' => 'units_color', 
				'dependency' => array( 'element' => "units", 'not_empty' => true ), 
				'description' => esc_html__( 'Select color for number.', 'viem' ) ), 
			array( 
				'param_name' => 'units_font_size', 
				'heading' => esc_html__( 'Custom Units Font Size (px)', 'viem' ), 
				'type' => 'textfield', 
				'value' => '32', 
				'data_min' => '10', 
				'dependency' => array( 'element' => "units", 'not_empty' => true ), 
				'data_max' => '120' ),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'viem' ),
				'param_name' => 'icon',
				'value' => 'fa fa-ticket', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false,
					// default true, display an "EMPTY" icon?
					'iconsPerPage' => 4000,
					// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'description' => esc_html__( 'Button icon.', 'viem' ),
			),
			array( 
				'type' => 'colorpicker', 
				'heading' => esc_html__( 'Custom Icon Color', 'viem' ), 
				'param_name' => 'icon_color', 
				'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
				'description' => esc_html__( 'Select color for icon.', 'viem' ) ), 
			array( 
				'param_name' => 'icon_font_size', 
				'heading' => esc_html__( 'Custom Icon Size (px)', 'viem' ), 
				'type' => 'textfield', 
				'value' => '32', 
				'data_min' => '10', 
				'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
				'data_max' => '120' ), 
			array( 
				'type' => 'dropdown', 
				'std' => 'bottom', 
				'heading' => esc_html__( 'Icon Postiton', 'viem' ), 
				'param_name' => 'icon_position', 
				'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
				'value' => array( esc_html__( 'Top', 'viem' ) => 'top', esc_html__( 'Bottom', 'viem' ) => 'bottom', esc_html__( 'Left', 'viem' ) => 'left' ) ), 
			array( 
				'type' => 'dropdown', 
				'std' => '', 
				'heading' => esc_html__( 'Icon Visible', 'viem' ), 
				'param_name' => 'icon_visible',
				'dependency' => array( 'element' => "icon", 'not_empty' => true ), 
				'value' => array( esc_html__( 'Hover', 'viem' ) => '', esc_html__( 'Active', 'viem' ) => 'active' )), 
			array( 
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'viem' ), 
				'param_name' => 'text', 
				'admin_label' => true ), 
			array( 
				'type' => 'colorpicker', 
				'heading' => esc_html__( 'Custom Title Color', 'viem' ), 
				'param_name' => 'text_color', 
				'dependency' => array( 'element' => "text", 'not_empty' => true ), 
				'description' => esc_html__( 'Select color for title.', 'viem' ) ), 
			array( 
				'param_name' => 'text_font_size', 
				'heading' => esc_html__( 'Custom Title Font Size (px)', 'viem' ), 
				'type' => 'textfield', 
				'value' => '13', 
				'data_min' => '10', 
				'dependency' => array( 'element' => "text", 'not_empty' => true ), 
				'data_max' => '120' ),
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Text Align', 'viem' ),
				'param_name' => 'text_align',
				'std' => 'center',
				'value' => array( 
					esc_html__( 'Left', 'viem' ) => 'left', 
					esc_html__( 'Center', 'viem' ) => 'center',
					esc_html__( 'Right', 'viem' ) => 'right ' 
				),
				'description' =>__('Text Align','viem')
			), 
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Template', 'viem' ),
				'param_name' => 'tpl_mode',
				'std' => 'center',
				'value' => array(
					esc_html__( 'Default', 'viem' ) => '',
					esc_html__( 'Dark', 'viem' ) => 'tikcetbox-tpl-dark'
				),
				'description' =>__('Template Dark is background transparent and color is white. You can add background for this element.','viem')
			),
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( 
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 
					'viem' ) ) ) ) );