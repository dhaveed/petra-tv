<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly
}

class WPBakeryShortCode_DT_Button extends DTWPBakeryShortcode {
}
vc_map( 
array(
	'base' => 'dt_button',
	'name' => esc_html__( 'Button', 'viem' ),
	'description' => esc_html__( 'Eye catching button.', 'viem' ),
	"category" => esc_html__( "DawnThemes", 'viem' ),
	'class' => 'dt-vc-element dt-vc-element-dt_button',
	'icon' => 'dt-vc-icon-dt_button',
	'show_settings_on_create' => true,
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Text', 'viem' ),
			'holder' => 'button',
			'class' => 'wpb_button',
			'admin_label' => true,
			'param_name' => 'title',
			'value' => esc_html__( 'Button', 'viem' ),
			'description' => esc_html__( 'Text on the button.', 'viem' ) ),
		array(
			'type' => 'href',
			'heading' => esc_html__( 'URL (Link)', 'viem' ),
			'param_name' => 'href',
			'description' => esc_html__( 'Button link.', 'viem' ) ),
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
		/*array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Style', 'viem' ),
			"param_holder_class" => 'dt-btn-style-select',
			'param_name' => 'style',
			'value' => array( 'Default' => '', 'Outlined' => 'outline' ),
			'description' => esc_html__( 'Button style.', 'viem' ) ),*/
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Hover Effect', 'viem' ),
			"param_holder_class" => 'dt-btn-hover-select',
			'param_name' => 'hover_effect',
			'value' => 
			array( 
				esc_html__( 'Style 1', 'viem' ) => 'hover_e1',
				esc_html__( 'Style 2', 'viem' ) => 'hover_e2',
				esc_html__( 'Style 3', 'viem' ) => 'hover_e3',
				esc_html__( 'Style 4', 'viem' ) => 'hover_e4',
				esc_html__( 'Style 5', 'viem' ) => 'hover_e5',
				esc_html__( 'Style 6', 'viem' ) => 'hover_e6'
			),
			'description' => esc_html__( 'Button Hover Effect.', 'viem' ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Size', 'viem' ),
			'param_name' => 'size',
			'std' => '',
			'value' => array(
				esc_html__( 'Default', 'viem' ) => '',
				esc_html__( 'Large', 'viem' ) => 'lg',
				esc_html__( 'Small', 'viem' ) => 'sm',
				esc_html__( 'Extra small', 'viem' ) => 'xs',
				esc_html__( 'Custom size', 'viem' ) => 'custom' ),
			'description' => esc_html__( 'Button size.', 'viem' ) ),
		array(
			'param_name' => 'font_size',
			'heading' => esc_html__( 'Font Size (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '14',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '50' ),
		array(
			'param_name' => 'border_width',
			'heading' => esc_html__( 'Border Width (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '1',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '20' ),
		array(
			'param_name' => 'padding_top',
			'heading' => esc_html__( 'Padding Top (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '6',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '100' ),
		array(
			'param_name' => 'padding_right',
			'heading' => esc_html__( 'Padding Right (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '30',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '100' ),
		array(
			'param_name' => 'padding_bottom',
			'heading' => esc_html__( 'Padding Bottom (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '6',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '100' ),
		array(
			'param_name' => 'padding_left',
			'heading' => esc_html__( 'Padding Left (px)', 'viem' ),
			'type' => 'ui_slider',
			'value' => '30',
			'data_min' => '0',
			'dependency' => array( 'element' => "size", 'value' => array( 'custom' ) ),
			'data_max' => '100' ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Color', 'viem' ),
			'param_name' => 'color',
			'std' => 'default',
			'value' => array(
				esc_html__( 'Default', 'viem' ) => 'default',
				esc_html__( 'Primary', 'viem' ) => 'primary',
				esc_html__( 'Success', 'viem' ) => 'success',
				esc_html__( 'Info', 'viem' ) => 'info',
				esc_html__( 'Warning', 'viem' ) => 'warning',
				esc_html__( 'Danger', 'viem' ) => 'danger',
				esc_html__( 'White', 'viem' ) => 'white',
				esc_html__( 'Black', 'viem' ) => 'black',
				esc_html__( 'Custom', 'viem' ) => 'custom' ),
			'description' => esc_html__( 'Button color.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Background Color', 'viem' ),
			'param_name' => 'background_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select background color for button.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Border Color', 'viem' ),
			'param_name' => 'border_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select border color for button.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Text Color', 'viem' ),
			'param_name' => 'text_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select text color for button.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Hover Background Color', 'viem' ),
			'param_name' => 'hover_background_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select background color for button when hover.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Hover Border Color', 'viem' ),
			'param_name' => 'hover_border_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select border color for button when hover.', 'viem' ) ),
		array(
			'type' => 'colorpicker',
			'heading' => esc_html__( 'Hover Text Color', 'viem' ),
			'param_name' => 'hover_text_color',
			'dependency' => array( 'element' => "color", 'value' => array( 'custom' ) ),
			'description' => esc_html__( 'Select text color for button when hover.', 'viem' ) ),
		array(
			'type' => 'checkbox',
			'heading' => esc_html__( 'Button Full Width', 'viem' ),
			'param_name' => 'block_button',
			'value' => array( esc_html__( 'Yes, please', 'viem' ) => 'yes' ),
			'description' => esc_html__( 'Button full width of a parent', 'viem' ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Alignment', 'viem' ),
			'param_name' => 'alignment',
			'std' => 'left',
			'value' => array(
				esc_html__( 'Left', 'viem' ) => 'left',
				esc_html__( 'Center', 'viem' ) => 'center',
				esc_html__( 'Right', 'viem' ) => 'right' ),
			'description' => esc_html__( 'Button alignment (Not use for Button full width)', 'viem' ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Show Tooltip/Popover', 'viem' ),
			'param_name' => 'tooltip',
			'value' => array(
				esc_html__( 'No', 'viem' ) => '',
				esc_html__( 'Tooltip', 'viem' ) => 'tooltip',
				esc_html__( 'Popover', 'viem' ) => 'popover' ),
			'description' => esc_html__( 'Display a tooltip or popover with descriptive text.', 'viem' ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Tip position', 'viem' ),
			'param_name' => 'tooltip_position',
			'std' => 'top',
			'value' => array(
				esc_html__( 'Top', 'viem' ) => 'top',
				esc_html__( 'Bottom', 'viem' ) => 'bottom',
				esc_html__( 'Left', 'viem' ) => 'left',
				esc_html__( 'Right', 'viem' ) => 'right' ),
			'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ),
			'description' => esc_html__( 'Choose the display position.', 'viem' ) ),
		array(
			'type' => 'textfield',
			'heading' => esc_html__( 'Popover Title', 'viem' ),
			'param_name' => 'tooltip_title',
			'dependency' => array( 'element' => "tooltip", 'value' => array( 'popover' ) ) ),
		array(
			'type' => 'textarea',
			'heading' => esc_html__( 'Tip/Popover Content', 'viem' ),
			'param_name' => 'tooltip_content',
			'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ) ),
		array(
			'type' => 'dropdown',
			'heading' => esc_html__( 'Tip/Popover trigger', 'viem' ),
			'param_name' => 'tooltip_trigger',
			'std' => 'hover',
			'value' => array( esc_html__( 'Hover', 'viem' ) => 'hover', esc_html__( 'Click', 'viem' ) => 'click' ),
			'dependency' => array( 'element' => "tooltip", 'value' => array( 'tooltip', 'popover' ) ),
			'description' => esc_html__( 'Choose action to trigger the tooltip.', 'viem' ) ) ) ) );
