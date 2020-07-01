<?php
vc_map( 
	array( 
		'base' => 'dt_video', 
		'name' => esc_html__( 'Video Player', 'viem' ), 
		"category" => esc_html__( "DawnThemes", 'viem' ), 
		'class' => 'dt-vc-element dt-vc-element-dt_video', 
		'icon' => 'dt-vc-icon-dt_video', 
		'show_settings_on_create' => true, 
		'params' => array( 
			array( 
				'param_name' => 'type', 
				'heading' => esc_html__( 'Video Type', 'viem' ), 
				'type' => 'dropdown', 
				'admin_label' => true, 
				'std' => 'inline', 
				'value' => array( esc_html__( 'Iniline', 'viem' ) => 'inline', esc_html__( 'Popup', 'viem' ) => 'popup' ) ), 
			array( 
				'type' => 'attach_image', 
				'heading' => esc_html__( 'Background', 'viem' ), 
				'param_name' => 'background', 
				'dependency' => array( 'element' => "type", 'value' => array( 'popup' ) ), 
				'description' => esc_html__( 'Video Background.', 'viem' ) ), 
			array( 
				'type' => 'colorpicker', 
				'heading' => esc_html__( 'Icon Play color', 'viem' ), 
				'param_name' => 'icon_color', 
				'dependency' => array( 'element' => "type", 'value' => array( 'popup' ) ),
				'description' => esc_html__( 'Select Icon Play color.', 'viem' ) ), 
			/*array(
				'param_name' => 'sub_heading',
				'heading' => esc_html__( 'Video Sub Heading', 'viem' ),
				'type' => 'textfield',
				'value' => '',),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Sub Heading color', 'viem' ),
				'param_name' => 'sub_heading_color',
				'dependency' => array( 'element' => "sub_heading", 'not_empty' => true )),*/
			array(
				'param_name' => 'heading',
				'heading' => esc_html__( 'Video Heading', 'viem' ),
				'type' => 'textfield',
				'value' => '',),
			array(
				'type' => 'colorpicker',
				'heading' => esc_html__( 'Heading color', 'viem' ),
				'param_name' => 'heading_color',
				'dependency' => array( 'element' => "heading", 'not_empty' => true )),
			array( 
				'param_name' => 'video_embed', 
				'heading' => esc_html__( 'Embedded Code', 'viem' ), 
				'type' => 'textfield', 
				'value' => '', 
				'description' => esc_html__( 
					'Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="codex.wordpress.org/Embeds" target="_blank">codex.wordpress.org/Embeds</a>.', 
					'viem' ) ) ) ) );

class WPBakeryShortCode_DT_Video extends WPBakeryShortCode {
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
