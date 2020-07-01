<?php
/**
 * The template part for displaying single director fullwidth
 *
 * @package Dawn
 */

?>
<div id="primary" class="content-area main-wrap">
	<div id="content" class="main-content site-content dawn-single-post-director viem-director-tpl-1" role="main">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); $post_id = get_the_ID();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<div class="overview">
							<?php 
							if( has_post_thumbnail() && '' == get_post_format()):?>
							<div class="profile-avatar">
								<?php echo get_the_post_thumbnail(get_the_ID() , 'viem-movie-360x460'); ?>
							</div>
							<?php endif;?>
							<div class="director-info">
								<header class="entry-header">
									<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
								</header><!-- .entry-header -->
								<div class="director-info-list">
									<?php do_action('viem_director_info');?>
								</div>
							</div>
						</div>
						<div class="post-content ">
							<?php do_action('viem_director_before_content');?>
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
							<?php do_action('viem_director_after_content');?>
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
