<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 *@package dawn
 */

get_header(); ?>
<div id="main-content" class="main-content">
	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
			<div class="page-content">
					<div class="not-found-content">
						<img class="page_not_found_bg" src="<?php echo esc_url( viem_get_theme_option('page_not_found_bg', get_template_directory_uri() . '/assets/images/404-bg.png') );?>" />
						<h2><?php echo esc_html( viem_get_theme_option('page_not_found_title', 'Page not found') ); ?></h2>
						<div class="not-found-desc">
							<a class="btn btn-default" href="<?php echo esc_url( viem_get_theme_option('page_not_found_redirect_URL', home_url( '/' )) ); ?>">
								<?php echo esc_html(viem_get_theme_option('page_not_found_btn_text', 'Back to home page')); ?>
							</a>
						</div>
					</div>
			</div><!-- .page-content -->
		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->
<?php
get_footer();
