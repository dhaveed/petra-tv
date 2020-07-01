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
									<div class="menu-toggle"><i class="fa fa-bars"></i></div>
									<div class="logo-wrap">
										<h1 class="site-title">
											<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
												<img class="logo" src="<?php echo esc_url(viem_get_theme_option('logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>">
												<img class="sticky_logo" src="<?php echo esc_url(viem_get_theme_option('sticky_logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>">
											</a>
										</h1>
									</div>
								</div>
								<div class="header-nav-center">
										<div id="dt-main-menu" class="">
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
								</div>
								<div class="header-nav-right">
									<div class="header-right-menu">
										<div class="header-right-menu-content">
											<?php if( class_exists('viem_community_videos_main') ){
												viem_community_videos_main::submit_video_btn(true);
											}?>
											<?php echo apply_filters('viem_top_toolbar_search',''); ?>
											<?php if( viem_get_theme_option('header-user-menu', '1') == '1' ) viem_user_menu('hide'); ?>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</header>