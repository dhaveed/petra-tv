<?php
/**
 * Template Name: Full Width Page
 *
 * @subpackage Dawn
 */
get_header(); ?>
<div id="main-content">
	<div class="container-full">
		<div class="row">
			<div id="primary" class="content-area col-md-12">
				<div id="content" class="site-content" role="main">
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
		</div>
	</div>
</div><!-- #main-content -->

<?php
get_footer();
