<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other 'pages' on your WordPress site will use a different template.
 *
 * @package dawn
 */

$layout = (viem_get_theme_option('site-layout','site-layout-1') == 'site-layout-2' && is_front_page()) ? 'left-right-sidebar' : 'full-width';
$sidebar = 'main-sidebar';
viem_display_sidebar($layout, $sidebar);

get_header();

?>
<div id="main-content">
		<div class="row">
			<div id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout))?>">
				<div id="content" class="main-content site-content" role="main">

					<?php
						// Start the Loop.
						while ( have_posts() ) : the_post();

							// Include the page content template.
							get_template_part( 'template-parts/single/content', 'page' );

							// If comments are open or we have at least one comment, load up the comment template.
							if ( comments_open() || get_comments_number() ) {
								comments_template();
							}
						endwhile;
					?>

				</div><!-- #content -->
			</div><!-- #primary -->
			<?php do_action('viem_dt_left_sidebar');?>
			<?php do_action('viem_dt_right_sidebar'); ?>
		</div><!-- .row -->
</div><!-- #main-content -->
<?php
get_footer();
