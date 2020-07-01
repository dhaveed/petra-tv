<?php
if ( ! class_exists( 'DawnThemesRegister' ) ) :

	class DawnThemesRegister {

		public function __construct() {
			add_action( 'init', array( &$this, 'init' ) );
		}

		public function init() {
			if ( is_admin() ) {
				$this->register_lib_assets();
			} else {
				add_action( 'template_redirect', array( &$this, 'register_lib_assets' ) );
			}
		}

		public function register_lib_assets() {
			//$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			$suffix = '.min';
			if(!is_admin())
				wp_deregister_style('dtvc-form-font-awesome');
			
			wp_register_style('font-awesome',DTINC_ASSETS_URL . '/lib/font-awesome/css/font-awesome' . $suffix . '.css', array(), '4.7.0' );
			wp_register_style('elegant-icon',DTINC_ASSETS_URL . '/lib/elegant-icon/css/elegant-icon.css');
			
			wp_register_style('jquery-ui-bootstrap',DTINC_ASSETS_URL . '/lib/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css', array(), '1.10.0' );
			
			wp_register_script( 'ace-editor', DTINC_ASSETS_URL. '/lib/ace/ace.js', array( 'jquery' ), DAWN_CORE_VERSION, true );

			wp_register_style( 'datetimepicker', DTINC_ASSETS_URL . '/lib/datetimepicker/jquery.datetimepicker.css', '2.4.0' );
			wp_register_script( 'datetimepicker', DTINC_ASSETS_URL . '/lib/datetimepicker/jquery.datetimepicker.js', array( 'jquery' ), '2.4.0', true );
			
			//wp_register_style( 'chosen', DTINC_ASSETS_URL . '/lib/chosen/chosen.min.css', '1.1.0' );
			wp_register_style( 'chosen', DTINC_ASSETS_URL . '/lib/chosen-v1.3.0/chosen.min.css', '1.3.0' );
			//wp_register_script( 'chosen', DTINC_ASSETS_URL . '/lib/chosen/chosen.jquery' . $suffix .'.js', array( 'jquery' ), '1.0.0', true );
			wp_register_script( 'chosen', DTINC_ASSETS_URL . '/lib/chosen-v1.3.0/chosen.jquery' . $suffix .'.js', array( 'jquery' ), '1.3.0', false );
			wp_register_script( 'chosen-order', DTINC_ASSETS_URL . '/lib/chosen-v1.3.0/chosen.order.jquery.js', array( 'jquery' ), '1.2.1', false );
		}
		
	}
	new DawnThemesRegister();
endif;