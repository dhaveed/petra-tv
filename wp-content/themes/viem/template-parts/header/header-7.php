<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>
<header id="masthead" class="site-header <?php echo esc_attr( $header_class );?>">
	<div id="viem-main-header-wrapper">
		<div class="viem-main-header-content">
				<div class="top-header">
					<div id="top-header-content" class="<?php echo esc_attr($top_header_width); ?>">
						<div class="header-nav-content clearfix">
							<div class="header-nav-left">
								<div class="recent-post-slider clearfix">
									<span class="breaking-title viem_main_color"><?php esc_html_e('Breaking', 'viem'); ?></span>
									<?php viem_breaking_news('viem_video', 1); ?>
								</div>
							</div>
							<div class="header-nav-right">
								<div class="header-right-menu">
									<div class="header-right-menu-content">
										<?php viem_header_social_links(); ?>
										<?php if( viem_get_theme_option('header-user-menu', '1') == '1' ) viem_user_menu('hide'); ?>
										<?php if( class_exists('viem_community_videos_main') ){
											viem_community_videos_main::submit_video_btn(true);
										}?>
									</div>
								</div>
							</div>
						</div>
					</div>
			</div>
			<div class="logo-wrap clearfix">
				<?php 
				$adsense_slot_ads_header = viem_get_theme_option('adsense_slot_ads_header', '');
				$ads_wall_header_custom = viem_get_theme_option('ads_wall_header_custom', '');
				?>
				<h1 class="site-title <?php echo ( empty($adsense_slot_ads_header) &&  empty($ads_wall_header_custom) ) ? 'at_center' : ''; ?>">
					<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img class="logo" src="<?php echo esc_url(viem_get_theme_option('logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>" />
						<img class="sticky_logo" src="<?php echo esc_url(viem_get_theme_option('sticky_logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>"/>
					</a>
				</h1>
				<?php 
				if( !empty($adsense_slot_ads_header) ||  !empty($ads_wall_header_custom) ){?>
				<div class="viem-header-ads">
					<?php 
					if( !empty($adsense_slot_ads_header) ){
						echo do_shortcode('[adsense pub="'.viem_get_theme_option('adsense_id', '').'" slot="'.$adsense_slot_ads_header.'"]');
					}else{
						echo viem_print_string($ads_wall_header_custom);
					}
					?>
				</div>
				<?php } ?>
			</div>
			<div class="bottom-header clearfix">
				<div class="bottom-header-wrapper">
					<div id="dt-main-menu" class="">
						<div class="menu-toggle viem_main_color"><i class="fa fa-bars"></i></div>
						<div class="header-main-menu">
							<div class="dt-mainnav-wrapper">
								<nav id="primary-navigation"
									class="site-navigation primary-navigation dawnthemes-navigation-wrap">
								<?php if( has_nav_menu('primary') ): ?>
									<?php
									wp_nav_menu( array( 'theme_location' => 'primary', 'is_megamenu' => true ) );
									?>
								<?php else :?>
								<p class="dt-alert"><?php esc_html_e('Please sellect menu for Main navigation', 'viem'); ?></p>
								<?php endif; ?>
								</nav>
							</div>
						</div>
					</div>
					<?php echo apply_filters('viem_top_toolbar_search',''); ?>
				</div>
			</div>
		</div>
	</div>
</header>