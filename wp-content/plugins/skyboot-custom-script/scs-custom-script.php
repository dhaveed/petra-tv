<?php 
/*
Plugin Name: Skyboot Custom Script
Plugin URI:   http://skybootstrap.com
Description:  Load your custom css and js header & footer.
Version:      1.0
Author:       skybootstrap
Author URI:   http://skybootstrap.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  skyboot
*/
	if( !defined('ABSPATH') ) exit;

	// Skyboot Custom Script Version
	define( 'SCS_CUSTOM_SCRIPT_VERSION', '1.0' );

	// Plugins URL
	define( 'SCS_PLUGIN_URL', plugins_url('', __FILE__) );

	// Includes Directory 
	define( 'SCS_PLUGIN_DIRECTORY', dirname( __FILE__ ) ); 


	// Include Files
	if ( !file_exists('admin-menu.php') ){
		include_once(SCS_PLUGIN_DIRECTORY. '/inc/admin-menu.php');
	}	

	function scs_custom_script_loader(){

		// settings section
		add_settings_section( 'scs_heaader_css_section_group', 'Load your custom CSS or JS script in head or Footer tag ( before closing body )', 'scs_section_callback_function', 'scs-custom-script.php' );

		// Add Settings Field
		if ( !file_exists('settings-field.php') ){
			include_once(SCS_PLUGIN_DIRECTORY. '/inc/settings-field.php');
		}

		// Register Settings
		if ( !file_exists('register-setting.php') ){
			include_once(SCS_PLUGIN_DIRECTORY. '/inc/register-setting.php');
		}

	}
	add_action( 'admin_init', 'scs_custom_script_loader' );


	// Return Function
	if ( !file_exists('function-return.php') ){
		include_once(SCS_PLUGIN_DIRECTORY. '/inc/function-return.php');
	}


	// Input Area
	if ( !file_exists('function-input-area.php') ){
		include_once(SCS_PLUGIN_DIRECTORY. '/inc/function-input-area.php');
	}

	// Frondend Output Action
	if ( !file_exists('output-action.php') ){
		include_once(SCS_PLUGIN_DIRECTORY. '/inc/output-action.php');
	}
