<?php
/*
Plugin Name: DawnThemes Core
Plugin URI: http://dawnthemes.com/
Description: DawnThemes Core Plugin
Version: 2.0.3.3
Author: DawnThemes Team
Author URI: http://dawnthemes.com/
Text Domain: dawnthemes
*/
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if(!defined('DAWN_CORE_VERSION'))
	define('DAWN_CORE_VERSION', '2.0.3.3');

if(!defined('DAWN_CORE_URL'))
	define('DAWN_CORE_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('DAWN_CORE_DIR'))
	define('DAWN_CORE_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

// Define DAWNTHEMES_PLUGIN_FILE.
if ( ! defined( 'DAWNTHEMES_PLUGIN_FILE' ) ) {
	define( 'DAWNTHEMES_PLUGIN_FILE', __FILE__ );
}

register_activation_hook(__FILE__, 'dawnthemes_plugin_activate');
add_action('admin_init', 'dawnthemes_plugin_redirect', 98);

function dawnthemes_plugin_activate() {
    add_option('dawnthemes_plugin_do_activation_redirect', true);
}

function dawnthemes_plugin_redirect() {
    if (get_option('dawnthemes_plugin_do_activation_redirect', false)) {
        delete_option('dawnthemes_plugin_do_activation_redirect');
        if( !isset($_GET['activate-multi']) && !is_multisite() )
    	{
        	wp_redirect( "themes.php?page=dt-home" );
   		}
    }
}

class DawnThemesCore {
	
	public function __construct(){
		add_action('dawnthemes_includes', array($this,'includes'));
	}
	
	public function includes(){
		include_once (DAWN_CORE_DIR.'/includes/init.php');
	}

	/*
	* get the template path
	*
	* @return string
	*/
	public static function template_path(){
		return apply_filters( 'dawnthemes_template_path', 'dawnthemes/' );
	}

	/**
	 * Get the plugin path.
	 *
	 * @return string
	 */
	public static function plugin_path() {
		return untrailingslashit( plugin_dir_path( DAWNTHEMES_PLUGIN_FILE ) );
	}
}
new DawnThemesCore();