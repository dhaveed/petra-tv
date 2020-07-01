<?php
if( !defined('ABSPATH') ) exit;

// admin menu options
function scs_custom_script_admin_menu(){

	add_menu_page( 'Skyboot Custom Script', 'Skyboot', 'manage_options', 'scs-custom-script.php', 'scs_custom_script_output_form', 'dashicons-admin-generic', 50 );

}
add_action( 'admin_menu', 'scs_custom_script_admin_menu' );

function scs_custom_script_output_form(){
	echo '<div class="wrap"><h1>'. esc_html( 'Skyboot Custom Script Panel :', 'skyboot' ) . ' <small>( VERSION '.SCS_CUSTOM_SCRIPT_VERSION .' ) </small>'.'</h1></div>';
	echo '<form action="options.php" method="POST">';
	do_settings_sections( 'scs-custom-script.php' );
	settings_fields( 'scs_heaader_css_section_group' );
	submit_button('Update');
	echo '</form>';
}