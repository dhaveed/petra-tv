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
					<div class="header-nav-content <?php echo viem_get_theme_option('allow_community_videos', 0) == 1 ? 'has-submit-video-btn' : '';?>">
						
						<div class="logo-wrap">
							<h1 class="site-title">
								<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<img class="logo" src="<?php echo esc_url(viem_get_theme_option('logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>" />
									<img class="sticky_logo" src="<?php echo esc_url(viem_get_theme_option('sticky_logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>"/>
								</a>
							</h1>
						</div>

						<?php echo apply_filters('viem_top_toolbar_search',''); ?>
						<div class="menu-toggle"><i class="fa fa-bars"></i></div>
						<div class="header-main-menu-toggle">
							<div class="menu-toggle-wrap">
								<a class="menu-toggle-btn" href="#">
									<span><?php echo esc_html__('Menu', 'viem'); ?></span>
									<div class="viem-icon-menu-wrap">
											<div class="viem-icon-menu iclose">
												<span class="line line-1"></span>
												<span class="line line-2"></span>
												<span class="line line-3"></span>
												<span class="line line-4"></span>
												<span class="line line-5"></span>
												<span class="line line-6"></span>
											</div>
									</div>
								</a>
							</div>
						</div>

						<?php if( viem_get_theme_option('header-user-menu', '1') == '1' ) viem_user_menu('show');?>
						<?php if( class_exists('viem_community_videos_main') ){
							viem_community_videos_main::submit_video_btn(true);
						}?>
							
					</div>
				</div>
			</div>
	
			<div id="dt-main-menu" class="">
				<div class="container">
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
		</div>
	</div>
</header>
<?php if( is_front_page() && viem_get_theme_option('site-layout','site-layout-1') == 'site-layout-2' && viem_get_theme_option('homev3-show-recent-video', 0) == 1 ): ?>
	<div id="viem-v3-top-wrap">
		<?php viem_latest_video_single_slider(); ?>
	</div>
<?php endif; ?>
	