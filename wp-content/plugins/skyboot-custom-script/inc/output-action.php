<?php
if( !defined('ABSPATH') ) exit;
// Load Script By Action Hook

// load css in head tag
function scs_header_css_loader(){?>
	<style type="text/css">
		<?php echo get_option( 'header-css' ); ?>
	</style>
<?php }
add_action( 'wp_head', 'scs_header_css_loader' );

// load js in head tag	
function scs_header_js_loader(){
	echo get_option( 'header-js' );
}
add_action( 'wp_head', 'scs_header_js_loader' );

	
// load js in before closing body tag	
function scs_footer_css_loader(){?>
		<style type="text/css">
			<?php echo get_option( 'footer-css' ); ?>
		</style>
<?php }

add_action( 'wp_footer', 'scs_footer_css_loader' );
	
// load js in before closing body tag	
function scs_footer_js_loader(){
	echo get_option( 'footer-js' );
}
add_action( 'wp_footer', 'scs_footer_js_loader' );
	


