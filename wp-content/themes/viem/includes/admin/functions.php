<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_init', 'viem_theme_activation', 99 );
function viem_theme_activation() {
	global $pagenow;
	if ( is_admin() AND $pagenow == 'themes.php' AND isset( $_GET['activated'] ) ) {
		// Redirect to About the Theme page
		header( 'Location: ' . esc_url( admin_url( 'admin.php?page=dt-home' ) ) );
	}
}

function viem_get_mailchimplist(){
	$options = array(esc_html__('Nothing Found...','viem') => 0);
	
	if( class_exists('DawnThemesCore')){
		if($mailchimp_api = viem_get_theme_option('mailchimp_api','')){
			if(!class_exists('DawnThemes_Mailchimp_Api'))
				include_once( DTINC_DIR . '/lib/DT.MCAPI.class.php' );
		
			$api = new DawnThemes_Mailchimp_Api($mailchimp_api);
			$lists = $api->get_lists();
			if (is_wp_error($lists)){
				$options = array(__("Unable to load MailChimp lists, check your API Key.", 'viem') => 0 );
			}else{
				if (empty($lists)){
					$options = array(__("You have not created any lists at MailChimp",'viem') => 0);
				}else{
					$options = array(__('Select a list','viem') => 0 );
					foreach ($lists as $key=>$label){
						$options[$label] = $key;
					}
				}
			}
		}
	}
	
	return $options;
}
