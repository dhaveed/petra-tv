<?php
/**
 * The template part for displaying single actor fullwidth
 *
 * @package Dawn
 */

?>
<div id="primary" class="content-area main-wrap">
	<div id="content" class="main-content site-content dawn-single-post-actor viem-actor-tpl-1" role="main">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); $post_id = get_the_ID();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="movie-info">
							<?php
							$movie_picture = viem_get_post_meta('movie_picture');
							if( !empty($movie_picture) ):?>
							<div class="movie-picture">
								<?php
								echo wp_get_attachment_image($movie_picture, 'viem-movie-360x460');
								?>
							</div>
							<?php endif;?>
							<header class="entry-header"><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></header><!-- .entry-header -->
							<div class="movie-info">
								<?php do_action('viem_movie_info');?>
							</div>
						</div>
						<div class="post-content ">
							<?php do_action('viem_movie_before_content');?>
							<div class="entry-content">
								<?php
									the_content();
						
									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'viem' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'viem' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									) );
								?>
							</div> <!-- .entry-content -->
							<?php do_action('viem_movie_after_content');?>
						</div>
						<?php
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
						?>
				</article><!-- #post-## -->
				<?php
			endwhile; // end of the loop.
		?>
	</div><!-- #content -->
</div><!-- #primary -->