<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_DT_Tab
 */
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );
WPBakeryShortCode_DT_Tab::$self_count ++;
WPBakeryShortCode_DT_Tab::$section_info[] = $atts;
$isPageEditable = vc_is_page_editable();

echo '<div role="tabpanel" class="' . esc_attr( $this->getElementClasses() ) . '" id="' . esc_attr( $this->getTemplateVariable( 'tab_id' ) ) . '">'.wpb_js_remove_wpautop( $this->content ).'</div>';
