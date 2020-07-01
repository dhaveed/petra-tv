<?php
vc_map( array(
	'name' => esc_html__( 'Tab Section', 'viem' ),
	'base' => 'dt_tab',
	'icon' => 'icon-wpb-ui-tta-section',
	'allowed_container_element' => 'vc_row',
	'is_container' => true,
	'show_settings_on_create' => false,
	'as_child' => array(
		'only' => 'dt_tabs',
	),
	'category' => esc_html__( 'viem', 'viem' ),
	'description' => esc_html__( 'Section for Responsive Tabs.', 'viem' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'param_name' => 'title',
			'heading' => esc_html__( 'Title', 'viem' ),
			'description' => esc_html__( 'Enter section title (Note: you can leave it empty).', 'viem' ),
		),
		array(
			'type' => 'el_id',
			'param_name' => 'tab_id',
			'settings' => array(
				'auto_generate' => true,
			),
			'heading' => esc_html__( 'Section ID', 'viem' ),
			'description' => __ ( 'Enter section ID (Note: make sure it is unique and valid according to <a href="%s" target="_blank">w3c specification</a>).', 'viem' ),
		),
	),
	'js_view' => 'VcBackendTtaSectionView',
	'custom_markup' => '
			<div class="vc_tta-panel-heading">
			<h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left"><a href="javascript:;" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-accordion data-vc-container=".vc_tta-container"><span class="vc_tta-title-text">{{ section_title }}</span><i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i></a></h4>
			</div>
			<div class="vc_tta-panel-body">
			{{ editor_controls }}
			<div class="{{ container-class }}">
			{{ content }}
			</div>
			</div>',
	'default_content' => '',
)	
);

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Section' );

class WPBakeryShortCode_DT_Tab extends WPBakeryShortCode_VC_Tta_Section{
	protected $controls_css_settings = 'tc vc_control-container';
	protected $controls_list = array( 'add', 'edit', 'clone', 'delete' );
	protected $backened_editor_prepend_controls = false;
	/**
	 * @var WPBakeryShortCode_VC_Tta_Accordion
	 */
	public static $tta_base_shortcode;
	public static $self_count = 0;
	public static $section_info = array();
	/**
	 * Find html template for shortcode output.
	 */
	public function findShortcodeTemplate() {
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
	
	public function getFileName() {
		return $this->shortcode;
	}
	
	public function getElementClasses() {
		$classes = array();
		$classes[] = 'tab-pane';
		$isActive = ! vc_is_page_editable() && self::$self_count === 1;
	
		if ( $isActive ) {
			$classes[] = 'active';
		}
	
		/**
		 * @since 4.6.2
		 */
		if ( isset( $this->atts['el_class'] ) ) {
			$classes[] = $this->atts['el_class'];
		}
	
		return implode( ' ', array_filter( $classes ) );
	}
}