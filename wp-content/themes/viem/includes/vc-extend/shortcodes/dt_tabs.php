<?php
vc_map( 
	array( 
		'name' => esc_html__( 'Responsive Tabs', 'viem' ), 
		'base' => 'dt_tabs', 
		'icon' => 'dt-vc-icon dt-vc-icon-dt_tabs', 
		'is_container' => true, 
		'show_settings_on_create' => false, 
		'as_parent' => array( 'only' => 'dt_tab' ), 
		'category' => esc_html__( "DawnThemes", 'viem' ), 
		'description' => esc_html__( 'Tabbed content', 'viem' ), 
		'params' => array( 
			array( 
				'type' => 'textfield', 
				'param_name' => 'title', 
				'value'=>'Tab Title',
				'heading' => esc_html__( 'Widget title', 'viem' ), 
				'description' => esc_html__( 
					'Enter text used as widget title (Note: located above content element).', 
					'viem' ) ),
		array( 
				'type' => 'textfield', 
				'param_name' => 'title_link', 
				'heading' => esc_html__( 'Title Link', 'viem' ), ) ), 
		'js_view' => 'DHBackendTabsView', 
		'custom_markup' => '
<div class="vc_tta-container" data-vc-action="collapse">
	<div class="vc_general vc_tta vc_tta-tabs vc_tta-color-backend-tabs-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-spacing-1 vc_tta-tabs-position-top vc_tta-controls-align-left">
		<div class="vc_tta-tabs-container">' . '<ul class="vc_tta-tabs-list">' .
			 '<li class="vc_tta-tab" data-vc-tab data-vc-target-model-id="{{ model_id }}" data-element_type="dt_tab"><a href="javascript:;" data-vc-tabs data-vc-container=".vc_tta" data-vc-target="[data-model-id=\'{{ model_id }}\']" data-vc-target-model-id="{{ model_id }}"><span class="vc_tta-title-text">{{ section_title }}</span></a></li>' . '</ul>
		</div>
		<div class="vc_tta-panels vc_clearfix {{container-class}}">
		  {{ content }}
		</div>
	</div>
</div>', 
		'default_content' => '
[dt_tab title="' . sprintf( '%s %d', esc_html__( 'Tab', 'viem' ), 1 ) . '"][/dt_tab]
[dt_tab title="' . sprintf( '%s %d', esc_html__( 'Tab', 'viem' ), 2 ) . '"][/dt_tab]', 
			'admin_enqueue_js' => array( vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ) ) ) );

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tta_Accordion' );

class WPBakeryShortCode_DT_Tabs extends WPBakeryShortCode_VC_Tta_Accordion{
	public $layout = 'tabs';
	
	public function enqueueTtaScript() {
		wp_register_script( 'vc_tabs_script', vc_asset_url( 'lib/vc_tabs/vc-tabs.min.js' ), array( 'vc_accordion_script' ), WPB_VC_VERSION, true );
		parent::enqueueTtaScript();
		wp_enqueue_script( 'vc_tabs_script' );
	}
	
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
	
	
	
	public function getTabContent(){
		return wpb_js_remove_wpautop( $this->content );
	}
	
	
	public function setGlobalTtaInfo() {
		$sectionClass = visual_composer()->getShortCode( 'dt_tab' )->shortcodeClass();
		$this->sectionClass = $sectionClass;
	
		/** @var $sectionClass WPBakeryShortCode_DT_Tab */
		if ( is_object( $sectionClass ) ) {
			require_once( DHVC_DIR .'/shortcodes/dt_tab.php' );
			WPBakeryShortCode_DT_Tab::$tta_base_shortcode = $this;
			WPBakeryShortCode_DT_Tab::$self_count = 0;
			WPBakeryShortCode_DT_Tab::$section_info = array();
	
			return true;
		}
	
		return false;
	}
	
	public function getNavTabsList( ) {
		$isPageEditabe = vc_is_page_editable();
		$html = array();
		$html[] = '<ul class="nav nav-tabs nav-tabs-responsive" role="tablist">';
		if ( ! $isPageEditabe ) {
			if ( isset( $this->atts['title'] ) && strlen( $this->atts['title'] ) > 0 ) {
				$html[]= '<li class="nav-tab-title"><h4><a '.(isset($this->atts['title_link']) && strlen( $this->atts['title_link'] ) > 0  ? 'href="'.esc_url($this->atts['title_link']).'"' : '').'><span>' . $this->atts['title'] . '</span></a></h4></li>';
			}
			$active_section = 1;
			require_once( DHVC_DIR .'/shortcodes/dt_tab.php' );
			foreach ( WPBakeryShortCode_DT_Tab::$section_info as $nth => $section ) {
				$classes = array();
				if ( ( $nth + 1 ) === $active_section ) {
					$classes[] = 'active';
				}

				$title = '<span>' . $section['title'] . '</span>';
				$a_html = '<a href="#' . $section['tab_id'] . '" aria-controls="'.$section['tab_id'].'" role="tab" data-toggle="tab">' . $title . '</a>';
				$html[] = '<li class="' . implode( ' ', $classes ) . '" role="presentation">' . $a_html . '</li>';
			}
		}

		$html[] = '</ul>';

		return implode( '', $html );
	}
}