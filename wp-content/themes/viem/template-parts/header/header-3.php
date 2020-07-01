<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>
<header id="masthead" class="site-header <?php echo esc_attr( $header_class );?>">
	<div id="viem-main-header-wrapper">
		<div class="viem-main-header-content">
			<div class="top-header clearfix">
				<div id="top-header-content" class="<?php echo esc_attr($top_header_width); ?>">
						<div class="header-nav-left">
							<div class="menu-toggle"><i class="fa fa-bars"></i></div>
							<div class="logo-wrap">
								<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<img class="sticky_logo" src="<?php echo esc_url(viem_get_theme_option('sticky_logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>"/>
								</a>
							</div>
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
							<?php echo apply_filters('viem_top_toolbar_search',''); ?>
						</div>
						<div class="header-nav-right">
							<div class="header-right-wrap">
								<div class="header-right-content">
									<?php
									$badges_menu = 'badges';
									if( has_nav_menu($badges_menu) ): ?>
									<div class="viem-badges-nav-menu">
										<?php
										if( function_exists('viem_badges_nav_menu') ){
											viem_badges_nav_menu($badges_menu);
										}
										 ?>
									</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
				</div>
			</div>
			<div class="bottom-header clearfix">
				<div class="header-bottom-left">
					<div class="logo-wrap">
						<h1 class="site-title">
							<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<img class="logo" src="<?php echo esc_url(viem_get_theme_option('logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>" />
							</a>
						</h1>
					</div>
				</div>
				<div class="header-bottom-right">
					<div class="tags-trending-wrapper">
						<?php
						if( has_nav_menu('trending') ): ?>
						<div class="viem-trending-nav-wrapper">
							<span class="trending-nav-icon"><i class="fa fa-bolt" aria-hidden="true"></i></span>
							<?php
							if( function_exists('viem_trending_nav_menu') ){
								viem_trending_nav_menu('trending');
							}
							 ?>
						</div>
						<?php endif; ?>
					</div>
					<div class="header-comnunity-menu">
						<?php if( viem_get_theme_option('header-user-menu', '1') == '1' ) viem_user_menu('show');?>
						<?php if( class_exists('viem_community_videos_main') ){
							viem_community_videos_main::submit_video_btn(true);
						}?>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
