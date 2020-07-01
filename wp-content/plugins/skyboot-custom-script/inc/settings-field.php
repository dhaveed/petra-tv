<?php
if( !defined('ABSPATH') ) exit;
	// All Settings Field

	// Header CSS
	add_settings_field( 'header-css', 'Header CSS', 'scs_header_css_box', 'scs-custom-script.php', 'scs_heaader_css_section_group' );

	// Header JS Script
	add_settings_field( 'header-js', 'Header JS Script <br> (With Script Tag)', 'scs_header_js_box', 'scs-custom-script.php', 'scs_heaader_css_section_group' );
	
	// Footer CSS
	add_settings_field( 'footer-css', 'Footer CSS', 'scs_footer_css_box', 'scs-custom-script.php', 'scs_heaader_css_section_group' );

	// Footer JS Script
	add_settings_field( 'footer-js', 'Footer JS Script <br>(With Script Tag)', 'scs_footer_js_box', 'scs-custom-script.php', 'scs_heaader_css_section_group' );