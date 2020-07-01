<?php
/*
Plugin Name: Viem Ultimate
Plugin URI: http://dawnthemes.com/
Description: Ultimate addons for Viem
Version: 1.0.7
Author: DawnThemes Team
Author URI: http://dawnthemes.com/
Text Domain: viem-ultimate
*/
if ( ! defined( 'ABSPATH' ) ) die( '-1' );

if(!defined('viem_ultimate_URL'))
	define('viem_ultimate_URL',untrailingslashit( plugins_url( '/', __FILE__ ) ));

if(!defined('viem_ultimate_DIR'))
	define('viem_ultimate_DIR',untrailingslashit( plugin_dir_path( __FILE__ ) ));

register_activation_hook(__FILE__, 'viem_plugin_activate');
add_action('admin_init', 'viem_plugin_redirect', 98);

function viem_plugin_activate() {
    add_option('viem_plugin_do_activation_redirect', true);
}

function viem_plugin_redirect() {
    if (get_option('viem_plugin_do_activation_redirect', false)) {
        delete_option('viem_plugin_do_activation_redirect');
        if( !isset($_GET['activate-multi']) && !is_multisite() )
    	{
        	wp_redirect( "themes.php?page=dt-home" );
   		}
    }
}

if( !class_exists('viem_ultimate') ){

	class viem_ultimate {
		
		public function __construct(){
			add_action('viem_ultimate_includes', array($this,'includes'));
			add_action('init', array(&$this,'init'));
		}
		
		public function includes(){
			include_once (viem_ultimate_DIR.'/includes/init.php');
		}

		public function init(){
			load_plugin_textdomain( 'viem-ultimate' , false,  basename(viem_ultimate_DIR).'/languages' );
		}
	}
	new viem_ultimate();
}