<?php
if ( ! class_exists( 'DawnThemes_VisualComposer' ) && defined( 'WPB_VC_VERSION' ) ) :
	
	define( 'DTVC_ADD_ITEM_TITLE', __( "Add Item", 'dawnthemes' ) );
	define( 'DTVC_ITEM_TITLE', __( "Item", 'dawnthemes' ) );
	define( 'DTVC_MOVE_TITLE', __( 'Move', 'dawnthemes' ) );
	define( 'DTVC_ASSETS_URL', DTINC_URL . '/vc-extend/assets' );
	define( 'DTVC_DIR', DTINC_URL . '/vc-extend' );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Tabs', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-tabs.php' );
	
	if ( ! class_exists( 'WPBakeryShortCode_VC_Column', false ) )
		require_once vc_path_dir( 'SHORTCODES_DIR', 'vc-column.php' );

	class DTWPBakeryShortcodeContainer extends WPBakeryShortCodesContainer {

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

	class DTWPBakeryShortcode extends WPBakeryShortCode {

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

	/*
	 * 
	 */
	
	class DawnThemes_VisualComposer {
		
		private $dt_vc_map;
		
		public $param_holder = 'div';

		public function __construct() {
		
			if ( function_exists( 'vc_set_as_theme' ) ) :
				vc_set_as_theme( true );
			endif;
			
			/*if ( function_exists( 'vc_disable_frontend' ) ) :
				vc_disable_frontend();
			 else :
				if ( class_exists( 'NewVisualComposer' ) )
					NewVisualComposer::disableInline();
			endif;*/
			add_action( 'init', array(&$this, 'init') );
			//add_action( 'init', array( &$this, 'map' ), 20 );
			add_action( 'init', array( &$this, 'add_params' ), 50 );
			if ( is_admin() ) {
				vc_add_shortcode_param ( 'dtvc_hidden', array(&$this,'vc_hiddenfield_form_field'));
				vc_add_shortcode_param ( 'dtvc_datepicker', array(&$this,'vc_datepicker_form_field'));
				vc_add_shortcode_param('dtvc_image_select', array(&$this,'vc_image_select_field'));
				
				add_filter('vc_google_fonts_get_fonts_filter', array(&$this, 'vc_google_fonts_get_fonts') );
				
				$vc_params_js = DTVC_ASSETS_URL . '/js/vc-params.js';
				vc_add_shortcode_param( 'pricing_table_feature', array(&$this, 'pricing_table_feature_param' ), $vc_params_js );
				
				vc_add_shortcode_param( 'nullfield', array( &$this, 'nullfield_param' ), $vc_params_js );
				vc_add_shortcode_param( 
					'product_attribute_filter', 
					array( &$this, 'product_attribute_filter_param' ), 
					$vc_params_js );
				
				vc_add_shortcode_param( 'product_attribute', array( &$this, 'product_attribute_param' ), $vc_params_js );
				vc_add_shortcode_param( 'products_ajax', array( &$this, 'products_ajax_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_brand', array( &$this, 'product_brand_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_lookbook', array( &$this, 'product_lookbook_param' ), $vc_params_js );
				vc_add_shortcode_param( 'product_category', array( &$this, 'product_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_datepicker', array( &$this, 'ui_datepicker_param' ) );
				vc_add_shortcode_param( 'post_category', array( &$this, 'post_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'video_category', array( &$this, 'video_category_param' ), $vc_params_js );
				vc_add_shortcode_param( 'ui_slider', array( &$this, 'ui_slider_param' ) );
				vc_add_shortcode_param( 'dropdown_group', array( &$this, 'dropdown_group_param' ) );
				
				add_action('vc_backend_editor_render', array(&$this,'enqueue_scripts'), 100 );
			}
		}
		
		public function init(){
			add_filter(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, array(&$this, 'shortcode_custom_css'), 10,3 );
		}
		
		public function shortcode_custom_css( $class_to_filter, $shortcode, $atts ){
			if ('vc_widget_sidebar'===$shortcode && isset($atts['use_as_smart_sidebar']) && !empty($atts['use_as_smart_sidebar']))
				$class_to_filter .=' vc-as-smartsidebar';
			
			if ('vc_row'===$shortcode && isset($atts['row_top_diagonal']) && !empty($atts['row_top_diagonal']))
				$class_to_filter .= ' ' . $atts['row_top_diagonal'];
			if ('vc_row'===$shortcode && isset($atts['row_bottom_diagonal']) && !empty($atts['row_bottom_diagonal']))
				$class_to_filter .= ' ' . $atts['row_bottom_diagonal'];
			
			return $class_to_filter;
		}
		
		public function map() {
			$is_wp_version_3_6_more = version_compare( 
				preg_replace( '/^([\d\.]+)(\-.*$)/', '$1', get_bloginfo( 'version' ) ), 
				'3.6' ) >= 0;
			
			foreach ( $this->dt_vc_map as $dt_vc_map){
				vc_map($this->dt_vc_map);
			}
		}

		public function add_params() {
			vc_add_param('vc_widget_sidebar',array(
				'weight' => 9998,
				'type' => 'checkbox',
				'admin_label' => true,
				'heading' => __( 'Use as Smart Sidebar', 'dawnthemes' ),
				'param_name' => 'use_as_smart_sidebar',
				'value' => array( __( 'Yes', 'dawnthemes' ) => 'yes' ),
				'description' => __( 'Use as Smart Sidebar.', 'dawnthemes' ),
			));
			vc_add_param( 
				"vc_row", 
				array( 
					"type" => "dropdown", 
					"group" => __( 'Advanced Options', 'dawnthemes' ), 
					"class" => "", 
					"heading" => __( 'Row Type', 'dawnthemes' ), 
					'std' => 'full_width', 
					"param_name" => "wrap_type", 
					"value" => array( 
						__( "Full Width", 'dawnthemes' ) => "full_width",
						__( "In Container", 'dawnthemes' ) => "in_container",
						__( "Wrap Container", 'dawnthemes' ) => "wrap_container"  ) ) );
			
			vc_add_param( 
				"vc_row", 
				array( 
					"type" => "dropdown", 
					"group" => __( 'Advanced Options', 'dawnthemes' ), 
					"class" => "", 
					"heading" => __( 'Row Top Diagonal', 'dawnthemes' ), 
					'std' => '', 
					"param_name" => "row_top_diagonal", 
					"value" => array(
						__( "None", 'dawnthemes' ) => "",
						__( "Row Top Left Diagonal", 'dawnthemes' ) => "dawnthemes_row_top_left_diagonal",
						__( "Row Top Right Diagonal", 'dawnthemes' ) => "dawnthemes_row_top_right_diagonal") ) );
			
			vc_add_param(
				"vc_row", 
				array(
					"type" => "dropdown", 
					"group" => __( 'Advanced Options', 'dawnthemes' ), 
					"class" => "", 
					"heading" => __( 'Row Bottom Diagonal', 'dawnthemes' ), 
					'std' => '', 
					"param_name" => "row_bottom_diagonal", 
					"value" => array(
						__( "None", 'dawnthemes' ) => "",
						__( "Row Bottom Left Diagonal", 'dawnthemes' ) => "dawnthemes_row_bottom_left_diagonal",
						__( "Row Bottom right Diagonal", 'dawnthemes' ) => "dawnthemes_row_bottom_right_diagonal"  ) ) );
			
			vc_add_param(
				"vc_row_inner", 
				array( 
					"type" => "dropdown", 
					"group" => __( 'Advanced Options', 'dawnthemes' ), 
					"class" => "", 
					"heading" => __( 'Row Type', 'dawnthemes' ), 
					"param_name" => "wrap_type", 
					'std' => 'full_width', 
					"value" => array( 
						__( "Full Width", 'dawnthemes' ) => "full_width",
						__( "In Container", 'dawnthemes' ) => "in_container",
						__( "Wrap Container", 'dawnthemes' ) => "wrap_container" ) ) );
			
		}
		
		public function vc_google_fonts_get_fonts($fonts_list){
			$alex_brush = new stdClass();
			$alex_brush->font_family = "Alex Brush";
			$alex_brush->font_styles = "regular";
			$alex_brush->font_types ="400 regular:400:normal";
			$fonts_list[] = $alex_brush;
			return $fonts_list;
		}
		
		public function vc_hiddenfield_form_field( $settings, $value ) {
			$value = htmlspecialchars( $value );
			return '<input name="' . $settings['param_name']. '" class="wpb_vc_param_value wpb-textinput '. $settings['param_name'] . ' ' . $settings['type']. '" type="hidden" value="' . $value . '"/>';
		}
		
		public function vc_datepicker_form_field( $param, $param_value ) {
			$param_line = '';
			$value = $param_value;
			$value = htmlspecialchars( $value );
			$param_line .= '<input id="' . $param['param_name'] . '" name="' . $param['param_name'] . '" readonly class="wpb_vc_param_value wpb-textinput ' . $param['param_name'] . ' ' . $param['type'] . '" type="text" value="' . $value . '"/>';
			if ( ! defined( 'DT_UISLDER_PARAM' ) ) {
				define( 'DT_UISLDER_PARAM', 1 );
				$param_line .= '<link media="all" type="text/css" href="' . DTVC_ASSETS_URL .'/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css?ver=1.10.0" rel="stylesheet" />';
			}
			$param_line .= '<script>
				jQuery(function() {
				jQuery( "#' . $param['param_name'] . '" ).datepicker({showButtonPanel: true,dateFormat:"yy-mm-dd"});
			});</script>
		';
			return $param_line;
		}
		
		public function vc_image_select_field($settings, $value){
			$output = '';
			if ( is_array( $value ) ) {
				$value = ''; // fix #1239
			}
			$current_value = strlen( $value ) > 0 ? explode( ',', $value ) : array();
			$values = isset( $settings['value'] ) && is_array( $settings['value'] ) ? $settings['value'] : array( __( 'Yes','dawnthemes') => 'true' );
			if ( ! empty( $values ) ) {
				foreach ( $values as $image => $v ) {
					$checked = count( $current_value ) > 0 && in_array( $v, $current_value ) ? ' checked' : '';
					$output .= ' <a href="" class="dtvc-image-select-field-label"><img src="' . $image . '"/></label>';
				}
			}
		
			return $output;
		}

		public function pricing_table_feature_param( $settings, $value ) {
			$value_64 = base64_decode( $value );
			$value_arr = json_decode( $value_64 );
			if ( empty( $value_arr ) && ! is_array( $value_arr ) ) {
				for ( $i = 0; $i < 2; $i++ ) {
					$option = new stdClass();
					$option->content = 'I am a feature';
					$value_arr[] = $option;
				}
			}
			$param_line = '';
			$param_line .= '<div class="pricing-table-feature-list clearfix">';
			$param_line .= '<table>';
			$param_line .= '<thead>';
			$param_line .= '<tr>';
			$param_line .= '<td>';
			$param_line .= __( 'Content (<em>Add Arbitrary text or HTML.</em>)', 'dawnthemes' );
			$param_line .= '</td>';
			$param_line .= '<td>';
			$param_line .= '</td>';
			$param_line .= '</tr>';
			$param_line .= '</thead>';
			$param_line .= '<tbody>';
			if ( is_array( $value_arr ) && ! empty( $value_arr ) ) {
				foreach ( $value_arr as $k => $v ) {
					$param_line .= '<tr>';
					$param_line .= '<td>';
					$param_line .= '<textarea id="content">' . esc_textarea( $v->content ) . '</textarea>';
					$param_line .= '</td>';
					$param_line .= '<td align="left" style="padding:5px;">';
					$param_line .= '<a href="#" class="button pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' . esc_attr__( 'Remove', 'dawnthemes' ) . '">' . esc_html__( 'Remove', 'dawnthemes' ) . '</a>';
					$param_line .= '</td>';
					$param_line .= '</tr>';
				}
			}
			$param_line .= '</tbody>';
			$param_line .= '<tfoot>';
			$param_line .= '<tr>';
			$param_line .= '<td colspan="3">';
			$param_line .= '<a href="#" onclick="return pricing_table_feature_add(this);" class="button" title="' .__( 'Add', 'dawnthemes' ) . '">' . __( 'Add', 'dawnthemes' ) . '</a>';
			$param_line .= '</td>';
			$param_line .= '</tfoot>';
			$param_line .= '</table>';
			$param_line .= '<input type="hidden" name="' . $settings['param_name'] . '" class="wpb_vc_param_value' .$settings['param_name'] . ' ' . $settings['type'] . '" value="' . $value . '">';
			$param_line .= '</div>';
			return $param_line;
		}

		public function post_category_param( $settings, $value) {
			$dependency = function_exists('vc_generate_dependencies_attributes') ? vc_generate_dependencies_attributes($settings) : '';
			$taxonomy = 'category';
			if( isset($settings['taxonomy']) )
				$taxonomy = $settings['taxonomy'];
			
			$categories = get_categories( array( 'taxonomy' => $taxonomy , 'orderby' => 'NAME', 'order' => 'ASC' ) );
			
			$class = 'dt-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = array();
			$html[] = '<div class="post_category_param">';
			$html[] = '<select id="' . $settings['param_name'] . '" ' .
				 ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) . ' class="' . $class . '" ' .
				 $dependency . '>';
			$r = array();
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 1;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html[] = dt_walk_category_dropdown_tree( $categories, 0, $r );
			$html[] = '</select>';
			$html[] = '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value dt-chosen-value wpb-textinput" name="' .
				 $settings['param_name'] . '" value="' . $value . '" />';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function video_category_param( $settings, $value) { // use for viem theme
			$dependency = function_exists('vc_generate_dependencies_attributes') ? vc_generate_dependencies_attributes($settings) : '';
			$taxonomy = 'video_cat';
			if( isset($settings['taxonomy']) )
				$taxonomy = $settings['taxonomy'];
			
			$categories = get_categories( array( 'taxonomy' => $taxonomy , 'orderby' => 'NAME', 'order' => 'ASC' ) );
			
			$class = 'dt-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = array();
			$html[] = '<div class="video_category_param">';
			$html[] = '<select id="' . $settings['param_name'] . '" ' .
				 ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) . ' class="' . $class . '" ' .
				 $dependency . '>';
			$r = array();
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 1;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html[] = dt_walk_category_dropdown_tree( $categories, 0, $r );
			$html[] = '</select>';
			$html[] = '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value dt-chosen-value wpb-textinput" name="' .
				 $settings['param_name'] . '" value="' . $value . '" />';
			$html[] = '</div>';
			
			return implode( "\n", $html );
		}

		public function dropdown_group_param( $param, $param_value ) {
			$css_option = vc_get_dropdown_option( $param, $param_value );
			$param_line = '';
			$param_line .= '<select name="' . $param['param_name'] .
				 '" class="dt-chosen-select wpb_vc_param_value wpb-input wpb-select ' . $param['param_name'] . ' ' .
				 $param['type'] . ' ' . $css_option . '" data-option="' . $css_option . '">';
			foreach ( $param['optgroup'] as $text_opt => $opt ) {
				if ( is_array( $opt ) ) {
					$param_line .= '<optgroup label="' . $text_opt . '">';
					foreach ( $opt as $text_val => $val ) {
						if ( is_numeric( $text_val ) && ( is_string( $val ) || is_numeric( $val ) ) ) {
							$text_val = $val;
						}
						$selected = '';
						if ( $param_value !== '' && (string) $val === (string) $param_value ) {
							$selected = ' selected="selected"';
						}
						$param_line .= '<option class="' . $val . '" value="' . $val . '"' . $selected . '>' .
							 htmlspecialchars( $text_val ) . '</option>';
					}
					$param_line .= '</optgroup>';
				} elseif ( is_string( $opt ) ) {
					if ( is_numeric( $text_opt ) && ( is_string( $opt ) || is_numeric( $opt ) ) ) {
						$text_opt = $opt;
					}
					$selected = '';
					if ( $param_value !== '' && (string) $opt === (string) $param_value ) {
						$selected = ' selected="selected"';
					}
					$param_line .= '<option class="' . $opt . '" value="' . $opt . '"' . $selected . '>' .
						 htmlspecialchars( $text_opt ) . '</option>';
				}
			}
			$param_line .= '</select>';
			return $param_line;
		}

		public function nullfield_param( $settings, $value ) {
			return '';
		}

		public function product_attribute_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select name= "' . $settings['param_name'] . '" data-placeholder="' .
				 __( 'Select Attibute', 'dawnthemes' ) .
				 '" class="dt-product-attribute dt-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$output .= '<option value="' . esc_attr( $name ) . '"' . selected( $value, $name, false ) .
								 '>' . $attr->attribute_name . '</option>';
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			return $output;
		}

		public function product_attribute_filter_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$output = '';
			$args = array( 'orderby' => 'name', 'hide_empty' => false );
			$filter_ids = explode( ',', $value );
			$attributes = wc_get_attribute_taxonomies();
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple"  data-placeholder="' .
				 __( 'Select Attibute Filter', 'dawnthemes' ) .
				 '" class="dt-product-attribute-filter dt-chosen-multiple-select dt-chosen-select wpb_vc_param_value wpb-input wpb-select ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $attributes ) ) {
				foreach ( $attributes as $attr ) :
					if ( taxonomy_exists( wc_attribute_taxonomy_name( $attr->attribute_name ) ) ) {
						if ( $name = wc_attribute_taxonomy_name( $attr->attribute_name ) ) {
							$terms = get_terms( $name, $args );
							if ( ! empty( $terms ) ) {
								foreach ( $terms as $term ) {
									$v = $term->slug;
									$output .= '<option data-attr="' . esc_attr( $name ) . '" value="' . esc_attr( $v ) .
										 '"' . selected( in_array( $v, $filter_ids ), true, false ) . '>' .
										 esc_html( $term->name ) . '</option>';
								}
							}
						}
					}
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_brand_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$brands_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$brands = get_terms( 'product_brand', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				 __( 'Select brands', 'dawnthemes' ) . '" class="dt-chosen-multiple-select dt-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $brands ) ) {
				foreach ( $brands as $brand ) :
					$output .= '<option value="' . esc_attr( $brand->term_id ) . '"' .
						 selected( in_array( $brand->term_id, $brands_slugs ), true, false ) . '>' .
						 esc_html( $brand->name ) . '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_lookbook_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$lookbook_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$lookbooks = get_terms( 'product_lookbook', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" multiple="multiple" data-placeholder="' .
				 __( 'Select lookbooks', 'dawnthemes' ) . '" class="dt-chosen-multiple-select dt-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $lookbooks ) ) {
				foreach ( $lookbooks as $lookbook ) :
					$output .= '<option value="' . esc_attr( $lookbook->term_id ) . '"' .
						 selected( in_array( $lookbook->term_id, $lookbook_slugs ), true, false ) . '>' .
						 esc_html( $lookbook->name ) . '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function product_category_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			$output = '';
			$category_slugs = explode( ',', $value );
			$args = array( 'orderby' => 'name', 'hide_empty' => true );
			$multiple = isset($settings['multiple']) && $settings['multiple'] == false ? '':' multiple="multiple"';
			$categories = get_terms( 'product_cat', $args );
			$output .= '<select id= "' . $settings['param_name'] . '" '.$multiple.' data-placeholder="' .
				 __( 'Select categories', 'dawnthemes' ) . '" class="dt-chosen-multiple-select dt-chosen-select-nostd ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '">';
			if ( ! empty( $categories ) ) {
				foreach ( $categories as $cat ) :
					$s = isset( $settings['select_field'] ) ? $cat->term_id : $cat->slug;
					$output .= '<option value="' . esc_attr( $s ) . '"' .
						 selected( in_array( $s, $category_slugs ), true, false ) . '>' . esc_html( $cat->name ) .
						 '</option>';
				endforeach
				;
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			return $output;
		}

		public function products_ajax_param( $settings, $value ) {
			if ( ! defined( 'WOOCOMMERCE_VERSION' ) )
				return '';
			
			$product_ids = array();
			if ( ! empty( $value ) )
				$product_ids = array_map( 'absint', explode( ',', $value ) );
			
			$output = '<select data-placeholder="' . __( 'Search for a product...', 'dawnthemes' ) . '" id= "' .
				 $settings['param_name'] . '" ' . ( isset( $settings['single_select'] ) ? '' : 'multiple="multiple"' ) .
				 ' class="dt-chosen-multiple-select dt-chosen-ajax-select-product ' . $settings['param_name'] . ' ' .
				 $settings['type'] . '">';
			if ( isset( $settings['single_select'] ) ) {
				$output .= '<option value=""></option>';
			}
			if ( ! empty( $product_ids ) ) {
				foreach ( $product_ids as $product_id ) {
					$product = get_product( $product_id );
					if ( $product->get_sku() ) {
						$identifier = $product->get_sku();
					} else {
						$identifier = '#' . $product->id;
					}
					
					$product_name = sprintf( __( '%s &ndash; %s', 'dawnthemes' ), $identifier, $product->get_title() );
					
					$output .= '<option value="' . esc_attr( $product_id ) . '" selected="selected">' .
						 esc_html( $product_name ) . '</option>';
				}
			}
			$output .= '</select>';
			$output .= '<input id= "' . $settings['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value wpb-textinput" name="' . $settings['param_name'] .
				 '" value="' . $value . '" />';
			
			return $output;
		}

		public function ui_datepicker_param( $param, $param_value ) {
			$param_line = '';
			$value = $param_value;
			$value = htmlspecialchars( $value );
			$param_line .= '<input id="' . $param['param_name'] . '" name="' . $param['param_name'] .
				 '" readonly class="wpb_vc_param_value wpb-textinput ' . $param['param_name'] . ' ' . $param['type'] .
				 '" type="text" value="' . $value . '"/>';
			if ( ! defined( 'DT_UISLDER_PARAM' ) ) {
				define( 'DT_UISLDER_PARAM', 1 );
				$param_line .= '<link media="all" type="text/css" href="' . DTINC_ASSETS_URL .
					 '/lib/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css?ver=1.10.0" rel="stylesheet" />';
			}
			$param_line .= '<script>
					jQuery(function() {
					jQuery( "#' . $param['param_name'] . '" ).datepicker({showButtonPanel: true});
					});</script>	
				';
			return $param_line;
		}

		public function ui_slider_param( $settings, $value ) {
			$data_min = ( isset( $settings['data_min'] ) && ! empty( $settings['data_min'] ) ) ? 'data-min="' .
				 absint( $settings['data_min'] ) . '"' : 'data-min="0"';
			$data_max = ( isset( $settings['data_max'] ) && ! empty( $settings['data_max'] ) ) ? 'data-max="' .
				 absint( $settings['data_max'] ) . '"' : 'data-max="100"';
			$data_step = ( isset( $settings['data_step'] ) && ! empty( $settings['data_step'] ) ) ? 'data-step="' .
				 absint( $settings['data_step'] ) . '"' : 'data-step="1"';
			
			return '<input name="' . $settings['param_name'] . '" class="wpb_vc_param_value wpb-textinput ' .
				 $settings['param_name'] . ' ' . $settings['type'] . '" type="text" value="' . $value . '"/>';
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'chosen' );
		    wp_enqueue_style('font-awesome');
			wp_enqueue_style( 'dt-vc-admin', DTVC_ASSETS_URL . '/css/vc-admin.css', array(), '1.0.0' );
			wp_register_script( 'dt-vc-custom', DTVC_ASSETS_URL . '/js/vc-custom.js', array( 'jquery', 'jquery-ui-datepicker', 'chosen' ), '1.0.0', true );
			
			$pricing_table_feature_tmpl = '
			<tr>
				<td>
					<textarea id="content"></textarea>
				</td>
				<td align="left" style="padding:5px;">
					<a href="#" class="button pricing-table-feature-remove" onclick="return pricing_table_feature_remove(this);"  title="' .esc_attr__( 'Remove', 'dawnthemes' ) . '">'.esc_html__( 'Remove', 'dawnthemes' ).'</a>
				</td>
			</tr>';
				
			$dtvcL10n = array(
				'pricing_table_feature_tmpl' => $pricing_table_feature_tmpl,
				'pricing_table_max_item_msg' => esc_attr__( 'Pricing Table element only support display 5 item', 'dawnthemes' ),
				'item_title' => DTVC_ITEM_TITLE,
				'add_item_title' => DTVC_ADD_ITEM_TITLE,
				'move_title' => DTVC_MOVE_TITLE);
			wp_localize_script( 'dt-vc-custom', 'dtvcL10n', $dtvcL10n );
			wp_enqueue_script( 'dt-vc-custom' );
		}
	}

	new DawnThemes_VisualComposer();
	
endif;