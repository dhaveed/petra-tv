<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/**
 * About admin page
 */

add_action( 'admin_menu' , 'viem_add_info_home_page', 50);
function viem_add_info_home_page(){
	add_theme_page( esc_html__( 'Welcome', 'viem' ), esc_html__( 'Welcome', 'viem' ), 'manage_options', 'dt-home', 'viem_welcome_page');
}

function viem_welcome_page(){
	?>
	<div class="wrap about-wrap viem-admin-home">
		<div class="dt-admin-header">
			<h1><?php echo sprintf( __( 'Welcome to <strong>%s</strong>', 'viem' ), viem_themename . ' ' . viem_version ) ?></h1>
			<div class="dt-header-links">
				<div class="dt-header-link">
					<a href="http://help.dawnthemes.com/viem" target="_blank"><?php esc_html_e( 'Online Documentation', 'viem' ) ?></a>
				</div>
				<div class="dt-header-link">
					<a href="http://dawnthemes.com/support/forums/forum/themes/viem/" target="_blank"><?php esc_html_e( 'Support Forum', 'viem' ) ?></a>
				</div>
				<div class="dt-header-link">
					<a href="http://help.dawnthemes.com/viem/changelog/" target="_blank"><?php esc_html_e( 'Theme Changelog', 'viem' ) ?></a>
				</div>
			</div>
		</div>
		<div class="list-features">
			<?php
			$install_required_plugins_class = '';
			if( class_exists('DawnThemesCore') && class_exists('viem_ultimate') ){ $install_required_plugins_class = ' disabled'; }
			?>
			<div class="item-feature <?php echo esc_attr($install_required_plugins_class); ?>">
				<h4><i class="dashicons dashicons-admin-plugins"></i><?php esc_html_e( 'Install Required Plugins', 'viem' )?></h4>
				<p><?php esc_html_e( 'Install the required plugins, it is needed for Import Demo Content.', 'viem' )?></p>
				<a class="button dt-button" href="<?php echo esc_url( admin_url('themes.php?page=install-required-plugins') ); ?>"><?php esc_html_e( 'Go to Required Plugins page', 'viem' )?></a>
			</div>
			<?php
			$feature_demo_class = '';
			if( !class_exists('DawnThemesCore') || !class_exists('viem_ultimate') ){ $feature_demo_class = ' inactive'; }
			?>
			<div class="item-feature <?php echo esc_attr($feature_demo_class); ?>">
				<h4><i class="dashicons dashicons-download"></i><?php esc_html_e( 'Import Demo Content', 'viem' )?></h4>
				<p><?php esc_html_e( 'Import demo content to build your site not from scratch.', 'viem' )?></p>
				<a class="button dt-button" href="<?php echo esc_url( admin_url('themes.php?page=import-demo') ); ?>">
					<?php esc_html_e( 'Go to Import Demo', 'viem' )?></a>
			</div>
			<?php 
			$feature_themeop_class = '';
			if( !class_exists('DawnThemesCore') ){ $feature_themeop_class = ' inactive'; }
			?>
			<div class="item-feature <?php echo esc_attr($feature_themeop_class); ?>">
				<h4><i class="dashicons dashicons-admin-appearance"></i><?php esc_html_e( 'Customize Appearance', 'viem' )?></h4>
				<p><?php esc_html_e( 'To customize the look of your site (colors, layouts, fonts) go to the Theme Options panel.', 'viem' )?></p>
				<a class="button dt-button" href="<?php echo esc_url( admin_url('themes.php?page=theme-options') ); ?>"><?php esc_html_e( 'Go to Theme Options', 'viem' )?></a>
			</div>
		</div>
	</div>
	<?php
}