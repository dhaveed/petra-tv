<?php
if ( ! class_exists( 'DawnThemes_Options' ) ) :

	class DawnThemes_Options {
		
		/* the options array() */
		private $options;
		
		protected $_sections = array(); // Sections and fields

		protected static $_option_name;

		public function __construct($theme_options) {
			
			if( ! $theme_options){
				return;
			}else{
				$this->options = $theme_options;
			}
			
			
			$this->_sections = $this->get_sections();
			
			self::$_option_name = dawnthemes_get_theme_option_name();
			
			add_action( 'admin_init', array( &$this, 'admin_init' ) );
			add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
			// Download theme option
			add_action( "wp_ajax_dt_download_theme_option", array( &$this, "download_theme_option" ) );
		}

		public static function get_options( $key, $default = null ) {
			global $dawnthemes_theme_options;
			if ( empty( $dawnthemes_theme_options ) ) {
				$dawnthemes_theme_options = get_option( self::$_option_name );
			}
			if ( isset( $dawnthemes_theme_options[$key] ) && $dawnthemes_theme_options[$key] !== '' ) {
				$is_key_arr = $dawnthemes_theme_options[$key];
				
				return $dawnthemes_theme_options[$key];
			} else {
				return $default;
			}
		}

		public function admin_init() {
			register_setting( self::$_option_name, self::$_option_name, array( &$this, 'register_setting_callback' ) );
			$_opions = get_option( self::$_option_name );
			if ( empty( $_opions ) ) {
				$default_options = array();
				foreach ( $this->_sections as $key => $sections ) {
					if ( is_array( $sections['fields'] ) && ! empty( $sections['fields'] ) ) {
						foreach ( $sections['fields'] as $field ) {
							if ( isset( $field['name'] ) && isset( $field['value'] ) ) {
								$default_options[$field['name']] = $field['value'];
							}
						}
					}
				}
				if ( ! empty( $default_options ) ) {
					$options = array();
					foreach ( $default_options as $key => $value ) {
						$options[$key] = $value;
					}
				}
				$r = update_option( self::$_option_name, $options );
			}
		}

		protected static function getFileSystem( $url = '' ) {
			if ( empty( $url ) ) {
				$url = wp_nonce_url( 'admin.php?page=theme-options', 'register_setting_callback' );
			}
			if ( false === ( $creds = request_filesystem_credentials( $url, '', false, false, null ) ) ) {
				_e( 'This is required to enable file writing', 'dawnthemes' );
				exit(); // stop processing here
			}
			$assets_dir = get_template_directory();
			if ( ! WP_Filesystem( $creds, $assets_dir ) ) {
				request_filesystem_credentials( $url, '', true, false, null );
				_e( 'This is required to enable file writing', 'dawnthemes' );
				exit();
			}
		}

		public function register_setting_callback( $options ) {
			$less_flag = false;
			
			do_action( 'dt_theme_option_before_setting_callback', $options );
			
			$update_options = array();
			foreach ( $this->_sections as $key => $sections ) {
				if ( is_array( $sections['fields'] ) && ! empty( $sections['fields'] ) ) {
					foreach ( $sections['fields'] as $field ) {
						if ( isset( $field['name'] ) && isset( $options[$field['name']] ) ) {
							$option_value = $options[$field['name']];
							$option_value = wp_unslash( $option_value );
							if ( is_array( $option_value ) ) {
								$option_value = array_filter( 
									array_map( 'sanitize_text_field', (array) $option_value ) );
							} else {
								if ( $field['type'] == 'textarea' ) {
									$option_value = wp_kses_post( trim( $option_value ) );
								} elseif ( $field['type'] == 'ace_editor' ) {
									$option_value = $option_value;
								} else {
									$option_value = wp_kses_post( trim( $option_value ) );
								}
							}
							$update_options[$field['name']] = $option_value;
						}
					}
				}
			}
			if ( ! empty( $update_options ) ) {
				foreach ( $update_options as $key => $value ) {
					$options[$key] = $value;
				}
			}
			
			if ( isset( $options['dt_opt_import'] ) ) {
				$import_code = $options['import_code'];
				if ( ! empty( $import_code ) ) {
					$imported_options = json_decode( $import_code, true );
					if ( ! empty( $imported_options ) && is_array( $imported_options ) ) {
						foreach ( $imported_options as $key => $value ) {
							$options[$key] = $value;
						}
					}
				}
			}
			if ( isset( $options['dt_opt_reset'] ) ) {
				$default_options = array();
				foreach ( $this->_sections as $key => $sections ) {
					if ( is_array( $sections['fields'] ) && ! empty( $sections['fields'] ) ) {
						foreach ( $sections['fields'] as $field ) {
							if ( isset( $field['name'] ) && isset( $field['value'] ) ) {
								$default_options[$field['name']] = $field['value'];
							}
						}
					}
				}
				if ( ! empty( $default_options ) ) {
					$options = array();
					foreach ( $default_options as $key => $value ) {
						$options[$key] = $value;
					}
				}
			}
			
			unset( $options['import_code'] );
			do_action( 'dt_theme_option_after_setting_callback', $options );
			return $options;
		}

		public function get_default_option() {
			return apply_filters( 'dt_theme_option_default', '' );
		}

		public function option_page() {
			$current_theme = wp_get_theme();
			?>
<div class="clear"></div>
<div class="wrap dawnthemes-options-wrap">
	<input type="hidden" id="security" name="security"
		value="<?php echo wp_create_nonce( 'dt_theme_option_ajax_security' ) ?>" />
	<form method="post" action="options.php" enctype="multipart/form-data" id="dawnthemes-theme-options-api">
		<div></div>
		<?php settings_fields( self::$_option_name ); ?>
		<div class="dawnthemes-opt-header-wrap">
			<div class="dawnthemes-opt-heading-wrap">
				<h2><?php echo $current_theme->get('Name');?><span><?php echo $current_theme->get('Version');?></span></h2>
			</div>
		</div>
		<div class="clear"></div>
		<div class="dt-opt-actions">
			<?php
			//if( dawnthemes_check_for_update($current_theme->get('Name')) == 1 )
			//	echo '<button class="button dt-button-secondary" name="dawnthemes_update_theme" type="submit" value="dawnthemes_update_theme" onclick="jQuery(\'#dawnthemes-theme-options-api\').attr(\'action\',\'themes.php?page=theme-options&dawnthemes_update_theme=true\')">' . esc_html__('New Version Found! Update Theme?','dawnthemes') . '</button>';
			?>
			<a class="button dt-button-secondary" target="_blank" href="http://dawnthemes.com/support"><?php _e('Support Forum','dawnthemes')?></a>
			<a class="button dt-button-secondary" target="_blank" href="http://help.dawnthemes.com/<?php echo basename(get_template_directory())?>"><?php _e('Document','dawnthemes')?></a>
			<button id="dt-opt-submit" name="dt_opt_save" class="button-primary"
				type="submit"><?php echo esc_html__('Save All Change','dawnthemes') ?></button>
		</div>
		<div class="clear"></div>
		<div id="dt-opt-tab" class="dt-opt-wrap">
			<div class="dt-opt-sidebar">
				<ul id="dt-opt-menu" class="dt-opt-menu">
					<?php $i = 0;?>
					<?php foreach ((array) $this->_sections as $key=>$sections):?>
					<li <?php echo ($i == 0 ? ' class="current"': '')?>>
						<a href="#<?php echo esc_attr( $key )?>" title="<?php echo esc_attr($sections['title']) ?>"><?php echo (isset($sections['icon']) ? '<i class="'.$sections['icon'].'"></i> ':'')?><?php echo esc_html($sections['title']) ?></a>
					</li>
					<?php $i++?>
					<?php endforeach;?>
				</ul>
			</div>
			<div id="dt-opt-content" class="dt-opt-content">
						<?php $i = 0;?>
						<?php foreach ((array) $this->_sections as $key=>$sections):?>
							<div id=<?php echo esc_attr($key)?> class="dt-opt-section"
					<?php echo ($i == 0 ? ' style="display:block"': '') ?>>
					<h3><?php echo esc_html($sections['title']) ?></h3>
								<?php if(isset($sections['desc'])):?>
								<div class="dt-opt-section-desc">
									<p class="description">
									<?php echo dawnthemes_echo($sections['desc'])?>
									</p>
								</div>
								<?php endif;?>
								<table class="form-table">
						<tbody>
										<?php foreach ( (array) $sections['fields'] as $field ) { ?>
										<tr>
											<?php if ( !empty($field['label']) ): ?>
											<th scope="row">
									<div class="dt-opt-label">
													<?php echo esc_html($field['label'])?>
													<?php if ( !empty($field['desc']) ): ?>
													<span class="description"><?php echo dawnthemes_echo($field['desc'])?></span>
													<?php endif;?>
												</div>
								</th>
											<?php endif;?>
											<td <?php if(empty($field['label'])):?> colspan="2"
									<?php endif;?>>
									<div class="dt-opt-field-wrap">
													<?php
					if ( isset( $field['callback'] ) )
						call_user_func( $field['callback'], $field );
					?>
													<?php echo dawnthemes_echo($this->_render_field($field))?>
												</div>
								</td>
							</tr>
										<?php } ?>
									</tbody>
					</table>
				</div>
						<?php $i++?>
						<?php endforeach;?>
					</div>
		</div>
		<div class="clear"></div>
		<div class="dt-opt-actions2">
			<button id="dt-opt-reset"
				name="<?php echo self::$_option_name?>[dt_opt_reset]" class="button"
				type="submit"><?php echo esc_html__('Reset Options','dawnthemes') ?></button>
			<button id="dt-opt-submit2" name="dt_opt_save" class="button-primary"
				type="submit"><?php echo esc_html__('Save All Change','dawnthemes') ?></button>
		</div>
		<div class="clear"></div>
	</form>
</div>
<?php
		}

		public function _render_field( $field = array() ) {
			if ( ! isset( $field['type'] ) )
				echo '';
			
			$field['name'] = isset( $field['name'] ) ? esc_attr( $field['name'] ) : '';
			
			$value = self::get_options( $field['name'] );
			$field['value'] = isset( $field['value'] ) ? $field['value'] : '';
			
			$field['value'] = apply_filters( 'dt_theme_option_field_std', $field['value'], $field );
			$field['default_value'] = $field['value'];
			if ( $value !== '' && $value !== null && $value !== array() && $value !== false ) {
				$field['value'] = $value;
			}
			
			$field['id'] = isset( $field['id'] ) ? esc_attr( $field['id'] ) : $field['name'];
			$field['desc'] = isset( $field['desc'] ) ? $field['desc'] : '';
			$field['label'] = isset( $field['label'] ) ? $field['label'] : '';
			$field['placeholder'] = isset( $field['placeholder'] ) ? esc_attr( $field['placeholder'] ) : esc_attr( 
				$field['label'] );
			$field['width']   		= isset( $field['width'] ) ? $field['width'] : '99%';
			
			$data_name = ' data-name="' . $field['name'] . '"';
			$field_name = self::$_option_name . '[' . $field['name'] . ']';
			$field['field_name'] = $field_name;
			$field['data_name'] = $data_name;
			
			$dependency_cls = isset( $field['dependency'] ) ? ' dt-dependency-field' : '';
			$dependency_data = '';
			if ( ! empty( $dependency_cls ) ) {
				$dependency_default = array( 'element' => '', 'value' => array() );
				$field['dependency'] = wp_parse_args( $field['dependency'], $dependency_default );
				if ( ! empty( $field['dependency']['element'] ) && ! empty( $field['dependency']['value'] ) )
					$dependency_data = ' data-master="' . esc_attr( $field['dependency']['element'] ) .
						 '" data-master-value="' . esc_attr( implode( ',', $field['dependency']['value'] ) ) . '"';
			}
			
			$field['dependency_cls'] = $dependency_cls;
			$field['dependency_data'] = $dependency_data;
			
			if ( isset( $field['field-label'] ) ) {
				echo '<p class="field-label">' . $field['field-label'] . '</p>';
			}

			if( $field['type'] == 'custom_font' || $field['type'] == 'upload_font'){
				echo '<span class="description">' . $field['desc'] . '</span>';
			}
			
			$field['option_name'] = self::$_option_name;
			
			switch ( $field['type'] ) {
				
				case 'datetimepicker' :
					wp_enqueue_script( 'datetimepicker' );
					wp_enqueue_style( 'datetimepicker' );
					break;
					
				case 'image' :
					if ( function_exists( 'wp_enqueue_media' ) ) {
						wp_enqueue_media();
					} else {
						wp_enqueue_style( 'thickbox' );
						wp_enqueue_script( 'media-upload' );
						wp_enqueue_script( 'thickbox' );
					}
					
					break;
					
				case 'color' :
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
					break;
				
				case 'background' :
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
					if ( function_exists( 'wp_enqueue_media' ) ) {
						wp_enqueue_media();
					} else {
						wp_enqueue_style( 'thickbox' );
						wp_enqueue_script( 'media-upload' );
						wp_enqueue_script( 'thickbox' );
					}
					
					break;
					
				case 'custom_font' :
					$field['font_size'] = $field['font-size'];
					break;
					
				case 'list_color' :
					wp_enqueue_style( 'wp-color-picker' );
					wp_enqueue_script( 'wp-color-picker' );
					break;
					
				default :
					break;
			}
			
			dawnthemes_get_template("fields/{$field['type']}.php", $field);
			
		}

		public function get_sections() {
			$section = array();
			$theme_options = $this->options;
			
			if( !isset($theme_options) )
				return;
			
			$section = $theme_options;
			
			return apply_filters( 'dt_theme_option_sections', $section );
		}

		public function enqueue_scripts() {
			wp_enqueue_style( 'chosen' );
			wp_enqueue_style( 'font-awesome' );
			// wp_enqueue_style('jquery-ui-bootstrap');
			wp_enqueue_style( 'dt-theme-options', DTINC_ASSETS_URL . '/css/theme-options.css', null, DTINC_VERSION );
			wp_register_script( 
				'dt-theme-options', 
				DTINC_ASSETS_URL . '/js/theme-options.js', 
				array( 
					'jquery', 
					'underscore', 
					'jquery-ui-button', 
					'jquery-ui-tooltip', 
					'chosen', 
					'ace-editor' ), 
				DTINC_VERSION, 
				true );
			$dtthemeoptionsL10n = array( 'reset_msg' => esc_js( 'You want reset all options ?', 'dawnthemes' ) );
			wp_localize_script( 'dt-theme-options', 'dtthemeoptionsL10n', $dtthemeoptionsL10n );
			wp_enqueue_script( 'dt-theme-options' );
		}

		public function admin_menu() {
			$option_page = add_theme_page( 
				esc_html__( 'Theme Options', 'dawnthemes' ), 
				esc_html__( 'Theme Options', 'dawnthemes' ), 
				'edit_theme_options', 
				'theme-options', 
				array( &$this, 'option_page' ) );
			
			// Add framework functionaily to the head individually
			// add_action("admin_print_scripts-$option_page", array(&$this,'enqueue_scripts'));
			add_action( "admin_print_styles-$option_page", array( &$this, 'enqueue_scripts' ) );
		}

		public function admin_bar_render() {
			global $wp_admin_bar;
			$wp_admin_bar->add_menu( 
				array( 'parent' => 'site-name', // use 'false' for a root menu, or pass the ID of the parent menu
						'id' => 'dt_theme_options', // link ID, defaults to a sanitized title value
						'title' => esc_html__( 'Theme Options', 'dawnthemes' ), // link title
						'href' => admin_url( 'themes.php?page=theme-option' ), // name of file
						'meta' => false ) ) // array of any of the following options: array( 'html' => '', 'class' => '', 'onclick' =>
				         // '', target => '', title => '' );
			;
		}

		public function download_theme_option() {
			if ( ! isset( $_GET['secret'] ) || $_GET['secret'] != md5( AUTH_KEY . SECURE_AUTH_KEY ) ) {
				wp_die( 'Invalid Secret for options use' );
				exit();
			}
			$options = get_option( self::$_option_name );
			$content = json_encode( $options );
			header( 'Content-Description: File Transfer' );
			header( 'Content-type: application/txt' );
			header( 
				'Content-Disposition: attachment; filename="' . self::$_option_name . '_backup_' . date( 'd-m-Y' ) .
					 '.json"' );
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate' );
			header( 'Pragma: public' );
			echo $content;
			exit();
		}
	}

endif;

/*
 * This method instantiates the Theme Options UI.
 *
 * @uses DawnThemes_Options()
 *
 * @params 	array 	array of arguments to create theme options
 * @return 	void
 *
 * @access 	public
 * @since	1.0
 */
if( !function_exists('dawnthemes_register_theme_options') ){
	function dawnthemes_register_theme_options( $theme_options = array() ){
		if( ! $theme_options )
			return;

		$dt_theme_options = new DawnThemes_Options( $theme_options );
	}
}