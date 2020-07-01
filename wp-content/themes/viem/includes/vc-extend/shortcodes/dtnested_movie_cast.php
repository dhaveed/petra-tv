<?php
$actors = array();
$args = array(
	'post_type'        => 'viem_actor',
	'post_status'      => 'publish',
	'posts_per_page'   => -1
);
$results = get_posts($args);
$actors[]='';
foreach ( $results as $result ) {

	if( !empty( $result->post_title ) ) {

		$actors[$result->post_title] = $result->ID;

	}
}

vc_map(
	array(
		'base' => 'dtnested_movie_cast',
		'name' => esc_html__( 'Movie Cast', 'viem' ),
		"category" => esc_html__( "DawnThemes", 'viem' ),
		'description' => esc_html__( 'Movie Cast Members', 'viem' ),
		'class' => 'dt-vc-element dt-vc-element-dtnested_movie_cast',
		'icon' => 'dt-vc-icon-dtnested_movie_cast',
		'show_settings_on_create' => true,
		'is_container' => true,
		'js_view' => 'DTVCMovieCast',
		'params' => array(
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Title', 'viem' ),
				'param_name' => 'title',
				'std' => esc_html__( 'Cast', 'viem' ),
				'admin_label' => true,
				'description' => '' ),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Columns', 'viem' ),
				'param_name' => 'columns',
				'std' => '5',
				'value' =>'',
				'description' => esc_html__( 'Select columns of the cast memebers per row', 'viem' ) ),
			array(
				'param_name' => 'el_class',
				'heading' => esc_html__( '(Optional) Extra class name', 'viem' ),
				'type' => 'textfield',
				'value' => '',
				"description" => esc_html__(
					"If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.",
					'viem' ) )
		),
		"custom_markup" => '
<div class="wpb_tabs_holder wpb_holder clearfix vc_container_for_children">
	<ul class="tabs_controls">
	</ul>
</div>',
		'default_content' => '
[dtsingle_movie_cast_item title="' . esc_html__( 'Item 1', 'viem' ) . '" tab_id="' . time() . '-1-' . rand( 0, 100 ) . '"][/dtsingle_movie_cast_item]
[dtsingle_movie_cast_item title="' . esc_html__( 'Item 2', 'viem' ) . '" tab_id="' . time() . '-2-' . rand( 0, 100 ) . '"][/dtsingle_movie_cast_item]
[dtsingle_movie_cast_item title="' . esc_html__( 'Item 3', 'viem' ) . '" tab_id="' . time() . '-3-' . rand( 0, 100 ) . '"][/dtsingle_movie_cast_item]
					  ' ) );

vc_map(
	array(
		'name' => esc_html__( 'Movie Cast Member', 'viem' ),
		'base' => 'dtsingle_movie_cast_item',
		'allowed_container_element' => 'vc_row',
		'is_container' => true,
		'content_element' => false,
		"category" => esc_html__( "DawnThemes", 'viem' ),
		'js_view' => 'DTVCMovieCastItem',
		'params' => array(
			array(
				'type' => 'dropdown',
				'heading' => esc_html__( 'Cast Info Name', 'viem' ),
				'param_name' => 'cast_info_name',
				'save_always'=>true,
				'value' => $actors,
				'description' => esc_html__( "Please select the cast member.", "viem" )
			),
			array(
				'type' => 'textfield',
				'heading' => esc_html__( 'Cast Info Movie Name', 'viem' ),
				'param_name' => 'cast_movie_name',
				'description' => esc_html__( "Please enter their character's name.", "viem" ) ),
			
// 			array(
// 				'type' => 'attach_image',
// 				'heading' => esc_html__( 'Avatar', 'viem' ),
// 				'param_name' => 'image',
// 				'description' => esc_html__( 'Team Avatar.', 'viem' ) ),
			
) ) );

VcShortcodeAutoloader::getInstance()->includeClass( 'WPBakeryShortCode_VC_Tabs' );
class WPBakeryShortCode_DTnested_Movie_Cast extends WPBakeryShortCode_VC_Tabs {

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
			
		preg_match_all(
			'/dt_team_item title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i',
			$content,
			$matches,
			PREG_OFFSET_CAPTURE );
			
		$output = '';
		$tab_titles = array();
			
		if ( isset( $matches[0] ) ) {
			$tab_titles = $matches[0];
		}
		$tmp = '';
		if ( count( $tab_titles ) ) {
			$tmp .= '<ul class="clearfix tabs_controls">';
			foreach ( $tab_titles as $tab ) {
				preg_match(
					'/title="([^\"]+)"(\stab_id\=\"([^\"]+)\"){0,1}/i',
					$tab[0],
					$tab_matches,
					PREG_OFFSET_CAPTURE );
				if ( isset( $tab_matches[1][0] ) ) {
					$tmp .= '<li><a href="#tab-' .
						( isset( $tab_matches[3][0] ) ? $tab_matches[3][0] : sanitize_title( $tab_matches[1][0] ) ) .
						'">' . $tab_matches[1][0] . '</a></li>';
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
						$custom_markup = str_ireplace(
							"%content%",
							$this->settings["default_content_in_template"],
							$this->settings["custom_markup"] );
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
		return '<div class="wpb_template">' .
			do_shortcode( '[dtsingle_movie_cast_item title="' . DTVC_ITEM_TITLE . '" tab_id=""][/dtsingle_movie_cast_item]' ) .
			'</div>';
	}
}


class WPBakeryShortCode_DTSingle_Movie_Cast_Item extends WPBakeryShortCode {
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