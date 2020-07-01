<?php
vc_map( 
	array( 
		'base' => 'dt_pricing_table', 
		'name' => esc_html__( 'Pricing Table', 'viem' ),
		'description' => esc_html__( 'Create pricing table', 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_pricing_table', 
		'icon' => 'dt-vc-icon-dt_pricing_table', 
		'show_settings_on_create' => false, 
		'is_container' => true, 
		"category" => esc_html__( "DawnThemes", 'viem' ),
		'js_view' => 'DTVCPricingTable', 
		'params' => array( 
			array( 
				'param_name' => 'visibility', 
				'heading' => esc_html__( 'Visibility', 'viem' ), 
				'type' => 'dropdown', 
				'std' => 'all', 
				'value' => array( 
					esc_html__( 'All Devices', 'viem' ) => "all", 
					esc_html__( 'Hidden Phone', 'viem' ) => "hidden-phone", 
					esc_html__( 'Hidden Tablet', 'viem' ) => "hidden-tablet", 
					esc_html__( 'Hidden PC', 'viem' ) => "hidden-pc", 
					esc_html__( 'Visible Phone', 'viem' ) => "visible-phone", 
					esc_html__( 'Visible Tablet', 'viem' ) => "visible-tablet", 
					esc_html__( 'Visible PC', 'viem' ) => "visible-pc" ) ), 
			array( 
				'param_name' => 'el_class', 
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				"description" => esc_html__( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'viem' ) ) ), 
		"custom_markup" => '
<div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
	<ul class="tabs_controls">
	</ul>
	%content%
</div>', 
		'default_content' => '
[dt_pricing_table_item title="' .esc_html__( 'Item 1', 'viem' ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) .'"][/dt_pricing_table_item]
[dt_pricing_table_item title="' .esc_html__( 'Item 2', 'viem' ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) .'"][/dt_pricing_table_item]
[dt_pricing_table_item title="' .esc_html__( 'Item 3', 'viem' ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dt_pricing_table_item]
					  ' ) );
vc_map( 
	array( 
		'name' => esc_html__( 'Pricing Table Item', 'viem' ), 
		'base' => 'dt_pricing_table_item', 
		'allowed_container_element' => 'vc_row', 
		'is_container' => true, 
		'content_element' => false, 
		'params' => array( 
			array( 
				"type" => "checkbox", 
				"heading" => esc_html__( "Recommend", 'viem' ), 
				"param_name" => "recommend", 
				"value" => array( esc_html__( 'Yes, please', 'viem' ) => 'yes' ) ),
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Title', 'viem' ), 
				'param_name' => 'title', 
				'description' => esc_html__( 'Item title.', 'viem' ) ),
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Price', 'viem' ), 
				'param_name' => 'price',
				'value' => '35.00', 
			), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Currency', 'viem' ), 
				'param_name' => 'currency',
				'value' => '$', 
			), 
			/*array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Units', 'viem' ), 
				'param_name' => 'units', 
				'value' => 'Basic', 
				'description' => esc_html__( 'Enter measurement units (if needed) Eg. Standard,Premidum... etc.', 'viem' ) ),*/ 
			array( 
				'type' => 'pricing_table_feature', 
				'heading' => esc_html__( 'Features', 'viem' ), 
				'param_name' => 'features' ), 
			array( 
				'type' => 'href', 
				'heading' => esc_html__( 'URL (Link)', 'viem' ), 
				'param_name' => 'href', 
				'description' => esc_html__( 'Button link.', 'viem' ) ), 
			array( 
				'type' => 'dropdown', 
				'heading' => esc_html__( 'Target', 'viem' ), 
				'param_name' => 'target', 
				'value' => array( esc_html__( 'Same window', 'viem' ) => '_self', esc_html__( 'New window', 'viem' ) => "_blank" ), 
				'dependency' => array( 
					'element' => 'href', 
					'not_empty' => true, 
					'callback' => 'vc_button_param_target_callback' ) ), 
			array( 
				'type' => 'textfield', 
				'heading' => esc_html__( 'Text on the button', 'viem' ), 
				'param_name' => 'btn_title', 
				'value' => esc_html__( 'Buy Now', 'viem' ), 
				'description' => esc_html__( 'Text on the button.', 'viem' ) ), ), 
		'js_view' => 'DTVCPricingTableItem' ) );

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tabs' );
class WPBakeryShortCode_DT_Pricing_Table extends WPBakeryShortCode_VC_Tabs {

	static $filter_added = false;

	public function __construct( $settings ) {
		parent::__construct( $settings );
		if ( ! self::$filter_added ) {
			$this->addFilter( 'vc_inline_template_content', 'setCustomTabId' );
			self::$filter_added = true;
		}
	}

	protected $predefined_atts = array( 'tab_id' => DTVC_ITEM_TITLE, 'title' => '' );

	public function contentAdmin( $atts, $content = null ) {
		$width = $custom_markup = '';
		$shortcode_attributes = array( 'width' => '1/1' );
		foreach ( $this->settings['params'] as $param ) {
			if ( $param['param_name'] != 'content' ) {
				if ( isset( $param['value'] ) && is_string( $param['value'] ) ) {
					$shortcode_attributes[$param['param_name']] = $param['value'];
				} elseif ( isset( $param['value'] ) ) {
					$shortcode_attributes[$param['param_name']] = $param['value'];
				}
			} else 
				if ( $param['param_name'] == 'content' && $content == NULL ) {
					$content = $param['value'];
				}
		}
		extract( shortcode_atts( $shortcode_attributes, $atts ) );
		
		// Extract tab titles
		
		preg_match_all( '/dt_pricing_table_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $content, $matches, PREG_OFFSET_CAPTURE );
		
		$output = '';
		$tab_titles = array();
		
		if ( isset( $matches[0] ) ) {
			$tab_titles = $matches[0];
		}
		$tmp = '';
		if ( count( $tab_titles ) ) {
			$tmp .= '<ul class="clearfix tabs_controls">';
			foreach ( $tab_titles as $tab ) {
				preg_match( '/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
				if ( isset( $tab_matches[1][0] ) ) {
					$tmp .= '<li><a href="#tab-' . ( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) . '">' . $tab_matches[1][0] . '</a></li>';
				}
			}
			$tmp .= '</ul>' . "\n";
		} else {
			$output .= do_shortcode( $content );
		}
		$elem = $this->getElementHolder( $width );
		
		$iner = '';
		foreach ( $this->settings['params'] as $param ) {
			$custom_markup = '';
			$param_value = isset( ${$param['param_name']} ) ? ${$param['param_name']} : '';
			if ( is_array( $param_value ) ) {
				// Get first element from the array
				reset( $param_value );
				$first_key = key( $param_value );
				$param_value = $param_value[$first_key];
			}
			$iner .= $this->singleParamHtmlHolder( $param, $param_value );
		}
		if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
			if ( $content != '' ) {
				$custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
			} else 
				if ( $content == '' && isset( $this->settings["default_content_in_template"] ) &&
					 $this->settings["default_content_in_template"] != '' ) {
					$custom_markup = str_ireplace( "%content%", $this->settings["default_content_in_template"], $this->settings["custom_markup"] );
				} else {
					$custom_markup = str_ireplace( "%content%", '', $this->settings["custom_markup"] );
				}
			$iner .= do_shortcode( $custom_markup );
		}
		$elem = str_ireplace( '%wpb_element_content%', $iner, $elem );
		$output = $elem;
		
		return $output;
	}

	/**
	 * Find html template for shortcode output.
	 */
	protected function findShortcodeTemplate() {
		// Check template path in shortcode's mapping settings
		if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
			return $this->setTemplate( $this->settings['html_template'] );
		}
		// Check template in theme directory
		$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
		if ( is_file( $user_template ) ) {
			return $this->setTemplate( $user_template );
		}
	}

	protected function getFileName() {
		return $this->shortcode;
	}

	public function getTabTemplate() {
		return '<div class="wpb_template">' . do_shortcode( '[dt_pricing_table_item title="' . DTVC_ITEM_TITLE . '" tab_id=""][/dt_pricing_table_item]' ) . '</div>';
	}
}

class WPBakeryShortCode_DT_Pricing_Table_Item extends WPBakeryShortCode {
	/**
	 * Find html template for shortcode output.
	 */
	protected function findShortcodeTemplate() {
		// Check template path in shortcode's mapping settings
		if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
			return $this->setTemplate( $this->settings['html_template'] );
		}
		// Check template in theme directory
		$user_template = vc_manager()->getShortcodesTemplateDir( $this->getFilename() . '.php' );
		if ( is_file( $user_template ) ) {
			return $this->setTemplate( $user_template );
		}
	}
	
	protected function getFileName() {
		return $this->shortcode;
	}
}