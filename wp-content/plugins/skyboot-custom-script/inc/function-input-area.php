<?php
// All Input Area Design

// Header css input
function scs_header_css_box(){ ?>
	<textarea rows="12" cols="20" class="large-text code" name="header-css" ><?php echo get_option( 'header-css' ) ?></textarea>
<?php }

// Header js input
function scs_header_js_box(){ ?>
	<textarea rows="12" cols="20" class="large-text code" name="header-js" ><?php echo get_option( 'header-js' ) ?></textarea>
<?php }

// Footer css input
function scs_footer_css_box(){ ?>
	<textarea rows="12" cols="20" class="large-text code" name="footer-css" ><?php echo get_option( 'footer-css' ) ?></textarea>
<?php }

// Footer js input
function scs_footer_js_box(){ ?>
	<textarea rows="12" cols="20" class="large-text code" name="footer-js" ><?php echo get_option( 'footer-js' ) ?></textarea>
<?php }





