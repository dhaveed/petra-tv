<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if(!class_exists('DawnThemesImportDemo')):
class DawnThemesImportDemo{
	public function __construct(){
		if(is_admin()){
			add_action('admin_menu', array(&$this,'admin_menu'));
			add_action('wp_ajax_dt_import_demo_data', array(&$this,'ajax_import_demo'));
		}
	}
	public function admin_menu(){
		add_submenu_page( 'themes.php' , esc_html__('Import Demo','dawnthemes') , esc_html__('Import Demo','dawnthemes') , 'manage_options' , 'import-demo' , array(&$this,'output') );
	}
	
	public function output(){
		$theme_config_demo_import = require_once ( get_template_directory() . '/config/demo-import.php' );
		
		if ( count( $theme_config_demo_import ) < 1 ) {
		return;
		}
		reset( $theme_config_demo_import );

		?>
		<div class="wrap dawnthemes-import-demo">
		<form class="dt-importer" action="?page=import-demo" method="post">
			<h1 class="dt-admin-title"><?php esc_html_e('Choose the demo for import','dawnthemes')?></h1>
			<p class="dt-admin-subtitle"><?php esc_html_e('The images used in live demos will be replaced by placeholders due to copyright/license reasons.','dawnthemes')?></p>
			<div class="dt-importer-list">


				<?php foreach ( $theme_config_demo_import as $name => $import ) {
				?>
					<div class="dt-importer-item" data-demo-id="<?php echo $name; ?>">

						<input class="dt-importer-item-radio" id="demo_main" type="radio" value="<?php echo $name; ?>" name="demo">
						<label class="dt-importer-item-preview" for="demo_<?php echo $name; ?>" title="<?php esc_html_e( 'Click to choose', 'dawnthemes' ) ?>">
							<h2 class="dt-importer-item-title"><?php echo $import['title']; ?>
								<a class="btn" href="<?php echo $import['preview_url']; ?>" target="_blank" title="<?php esc_html_e( 'View this demo in a new tab', 'dawnthemes' ) ?>"><?php esc_html_e( 'Preview', 'dawnthemes' ) ?></a>
							</h2>
							<img src="<?php echo get_template_directory_uri() . '/dummy-data/demos/'. $name .'/preview.jpg' ?>" alt="Main Demo">
						</label>

						<div class="dt-importer-item-options">
							<div class="dt-importer-item-options-h">
								<input type="hidden" name="action" value="import-demo">
								<input class="dt-button-import run_import_demo_data" type="submit" value="<?php esc_html_e('Import','dawnthemes')?>">
							</div>
						</div>

						<div class="dt-importer-message progress">
							<div class="dt-importer-preloader"></div>
							<h2><?php esc_html_e('Importing Demo Content...','dawnthemes')?></h2>
							<p><?php esc_html_e( 'Don\'t close or refresh this page to not interrupt the import.', 'dawnthemes' ) ?></p>
						</div>

						<div class="dt-importer-message done">
							<h2><?php esc_html_e('Import completed successfully!','dawnthemes')?></h2>
							<p><?php echo sprintf( __( 'Just check the result on <a href="%s" target="_blank">your site</a> or start customize via <a href="%s">Theme Options</a>.', 'us' ), site_url(), admin_url( 'themes.php?page=theme-options' ) ) ?></p>
						</div>

					</div>
				<?php } ?>
			</div>
		</form>
		<script>
			jQuery(document).ready(function() {
				var import_running = false;
				var $ = jQuery.noConflict();

				$('.dt-importer-item-preview').click(function(){
					var $this_item = $(this).closest('.dt-importer-item'),
						demoName = $this_item.attr('data-demo-id');

					$('.dt-importer-item').removeClass('selected');
					$this_item.addClass('selected');


					$this_item.find('.run_import_demo_data').off('click').click(function(){
						if(import_running) return false;

						$('.dt-importer').addClass('importing');
						console.log(demoName);
						jQuery.ajax({
							type: 'POST',
							url: '<?php echo admin_url('admin-ajax.php'); ?>',
							data: {
								security: '<?php echo wp_create_nonce( 'dt_import_demo_data' )?>',
								action: 'dt_import_demo_data',
								demo: demoName
							},
							success: function(response, textStatus, XMLHttpRequest){
								console.log(response);
								
								if(response != 'imported'){
									$('.dt-importer-message.done h2').html(response.error_title);
									$('.dt-importer-message.done p').html(response.error_description);
									$('.dt-importer').addClass('error');

								}else{
									// Import is completed
									$('.dt-importer').addClass('success');
									import_running = false;
								}
							},
							error: function(MLHttpRequest, textStatus, errorThrown){
								
								$('.dt-importer-message.done h2').html('<?php esc_html_e('Error has occured','dawnthemes')?>');
								$('.dt-importer-message.done p').html('');
								$('.dt-importer').addClass('error');
							}
						});

						return false;

					});

				});

			});
		</script>
		</div>
		<?php
	}
	
	protected function _get_new_widget_name( $widget_name, $widget_index ) {
		$current_sidebars = get_option( 'sidebars_widgets' );
		$all_widget_array = array( );
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
				foreach ( $widgets as $widget ) {
					$all_widget_array[] = $widget;
				}
			}
		}
		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
			$widget_index++;
		}
		$new_widget_name = $widget_name . '-' . $widget_index;
		return $new_widget_name;
	}
	
	protected function _parse_import_data( $import_array ) {
		global $wp_registered_sidebars;
		if(!is_array($import_array))
			return false;
		$sidebars_data = $import_array[0];
		$widget_data = $import_array[1];
		$current_sidebars = get_option( 'sidebars_widgets' );
		$new_widgets = array( );
	
		foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :
	
			foreach ( $import_widgets as $import_widget ) :
				//if the sidebar exists
				if ( isset( $wp_registered_sidebars[$import_sidebar] ) ) :
					$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
					$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
					$current_widget_data = get_option( 'widget_' . $title );
					$new_widget_name = $this->_get_new_widget_name( $title, $index );
					$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );
				
					if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
						while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
							$new_index++;
						}
					}
					$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
					if ( array_key_exists( $title, $new_widgets ) ) {
						$new_widgets[$title][$new_index] = $widget_data[$title][$index];
						$multiwidget = $new_widgets[$title]['_multiwidget'];
						unset( $new_widgets[$title]['_multiwidget'] );
						$new_widgets[$title]['_multiwidget'] = $multiwidget;
					} else {
						$current_widget_data[$new_index] = $widget_data[$title][$index];
						$current_multiwidget = isset($current_widget_data['_multiwidget']) ? $current_widget_data['_multiwidget'] : false;
						$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
						$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
						unset( $current_widget_data['_multiwidget'] );
						$current_widget_data['_multiwidget'] = $multiwidget;
						$new_widgets[$title] = $current_widget_data;
					}
			
				endif;
			endforeach;
		endforeach;
	
		if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
			update_option( 'sidebars_widgets', $current_sidebars );
	
			foreach ( $new_widgets as $title => $content )
				update_option( 'widget_' . $title, $content );
	
			return true;
		}
	
		return false;
	}

	protected function _clear_current_widget(){
		$sidebars = wp_get_sidebars_widgets();
		$inactive = isset($sidebars['wp_inactive_widgets']) ? $sidebars['wp_inactive_widgets'] : array();
		
		unset($sidebars['wp_inactive_widgets']);
		
		foreach ( $sidebars as $sidebar => $widgets ) {
			$inactive = array_merge($inactive, $widgets);
			$sidebars[$sidebar] = array();
		}
		
		$sidebars['wp_inactive_widgets'] = $inactive;
		wp_set_sidebars_widgets( $sidebars );
	}
	
	protected function _import_widget_data( $widget_data ) {
		//Move Widget to inactive
		$this->_clear_current_widget();

		$json_data = $widget_data;
		$json_data = json_decode( $json_data, true );
	
		$sidebar_data = $json_data[0];
		$widget_data = $json_data[1];
	
		foreach ( $widget_data as $widget_data_title => $widget_data_value ) {
			$widgets[ $widget_data_title ] = '';
			foreach( $widget_data_value as $widget_data_key => $widget_data_array ) {
				if( is_int( $widget_data_key ) ) {
					$widgets[$widget_data_title][$widget_data_key] = 'on';
				}
			}
		}
		unset($widgets[""]);
	
		foreach ( $sidebar_data as $title => $sidebar ) {
			$count = count( $sidebar );
			for ( $i = 0; $i < $count; $i++ ) {
				$widget = array( );
				$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
				$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
				if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
					unset( $sidebar_data[$title][$i] );
				}
			}
			$sidebar_data[$title] = array_values( $sidebar_data[$title] );
		}
	
		foreach ( $widgets as $widget_title => $widget_value ) {
			foreach ( $widget_value as $widget_key => $widget_value ) {
				$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
			}
		}
	
		$sidebar_data = array( array_filter( $sidebar_data ), $widgets );
	
		$this->_parse_import_data( $sidebar_data );
	}
	
	public function ajax_import_demo(){
		if ( ! check_ajax_referer( 'dt_import_demo_data', 'security', FALSE ) ) {
			wp_send_json_error(
				array(
					'message' => __( 'An error has occurred. Please reload the page and try again.' ),
				)
			);
		}

		@set_time_limit(0);

		$demo_version = 'main';
		if( isset( $_POST['demo'] ) ){
			$demo_version = $_POST['demo'];
		}

		$dummy_data_xml_file = get_template_directory().'/dummy-data/demos/'. $demo_version .'/dummy-data.xml';
		$theme_options_file = get_template_directory_uri().'/dummy-data/demos/'. $demo_version .'/theme_options.json';
		$widgets_json_file =get_template_directory_uri().'/dummy-data/demos/'. $demo_version .'/widget_data.json';
		
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);
	
		if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
			include ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		}
		
		if ( ! class_exists('WP_Import') ) { // if WP importer doesn't exist
			include DTINC_DIR . '/lib/wordpress-importer/wordpress-importer.php';
		}

		if ( class_exists( 'WP_Importer' ) && class_exists( 'WP_Import' ) ) {
			$wp_import = new WP_Import();
			$wp_import->fetch_attachments = true;
			ob_start();
			$wp_import->import($dummy_data_xml_file);
			ob_end_clean();
			
			// Import Widget
			$widgets_json = wp_remote_get( $widgets_json_file );
			$widget_data = $widgets_json['body'];
			$this->_import_widget_data($widget_data);

			// Import Theme Options
			$theme_options_json = wp_remote_get( $theme_options_file );
			$theme_options_data = array('dt_opt_import'=>true,'import_code'=>$theme_options_json['body']);
			update_option( dawnthemes_get_theme_option_name(), $theme_options_data ); // update theme options
			
			// Set menu
			$locations = get_theme_mod( 'nav_menu_locations' );
			//$locations = get_nav_menu_locations();
			$menus  = wp_get_nav_menus();
			
			if(!empty($menus))
			{
				foreach($menus as $menu)
				{
					if(is_object($menu))
					{
						if($menu->name == 'Primary Menu'){
							$locations['primary'] = $menu->term_id;
						}else if ($menu->name == 'Footer Menu'){
							$locations['footer'] = $menu->term_id;
						}else if ($menu->name == 'Top Menu'){
							$locations['top'] = $menu->term_id;
						}
					}
				}
			}
			
			set_theme_mod('nav_menu_locations', $locations);
			
			//Set Front Page
			$front_page = get_page_by_title('Home');
			
			if(isset($front_page->ID)) {
				update_option('show_on_front', 'page');
				update_option('page_on_front',  $front_page->ID);
			}
			
			// Flush rules after install
			flush_rewrite_rules();

			echo 'imported';

			die();

		}else{
			wp_send_json(
				array(
					'success' => FALSE,
					'error_title' => __( 'Error has occured', 'dawnthemes' ),
					'error_description' => '',
				)
			);
		}
	}
}
new DawnThemesImportDemo();
endif;
