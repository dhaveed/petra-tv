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
											<?php viem_header_social_links(); ?>
											<?php echo apply_filters('viem_top_toolbar_search',''); ?>
											<?php if( viem_get_theme_option('header-user-menu', '1') == '1' ) viem_user_menu('hide');?>
										</div>
									</div>
								</div>
						</div>
					</div>
				</div>
		</div>
	</div>
</header>
<div class="main-logo">
	<?php
	if( viem_get_theme_option('show_featured_video_header_6', '1') == '1' ):
		$args = array(
			'orderby'         => "rand",
			'order'           => "DESC",
			'post_type'       => "viem_video",
			'posts_per_page'  => get_option('posts_per_page', 9),
		);
		$vf = new WP_Query($args);
		if( $vf->have_posts() ):
		?>
		<div class="video-featured-list">
			<div class="video-featured-list-content owl-carousel viem-carousel-slide viem-preload" data-autoplay="false" data-dots="0" data-nav="0" data-items="9" data-autoWidth="true" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
			<?php 
			while ($vf->have_posts()){ $vf->the_post(); 
				$post_id = get_the_ID();
			?>
				
				<?php
				if (has_post_thumbnail()){
					echo viem_post_image('medium', '', false, false);
				}else{
					?>
					<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php the_title_attribute(); ?>">
					<img src="<?php echo esc_url(viem_placeholder_img_src()); ?>" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" data-itemprop="image">
					</a>
			<?php } ?>
				
			<?php
			}
			?>
			</div>
		</div>
		<?php endif; 
		wp_reset_postdata();
	endif;
	?>
	<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img class="global-header__logo" src="<?php echo esc_url(viem_get_theme_option('logo', viem_dt_assets_uri . '/images/logo.png'));?>" alt="<?php esc_attr(wp_title('|', true, 'right')); ?>">
	</a>
</div>
