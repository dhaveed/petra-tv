<?php 
/**
 * Shortcode attributes
 * @var $atts
 * @var $content - shortcode content
 * @var $this WPBakeryShortCode_DT_Tabs
 */
$title = $el_class = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
$this->resetVariables( $atts, $content );
extract( $atts );
wp_enqueue_script('bootstrap-tabdrop');
$this->setGlobalTtaInfo();
$tab_content = $this->getTabContent();
echo '
	<div class="viem-tabs dt-tabs responsive-tabs '.esc_attr( $el_class ).'">
		'.$this->getNavTabsList().'
		<div class="tab-content">
			'.$tab_content.'
		</div>
	</div>
';