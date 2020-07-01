<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'v-grid-item ' . $post_class ); ?> itemscope="">
	<div class="entry-featured-wrap">
		<?php 
		viem_post_image($img_size, 'grid');
		?>
	</div>
	<div class="post-wrapper post-content entry-content">
		<header class="post-header">
		<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<div class="entry-meta">
			<div class="entry-meta-content">
				<span class="videos-number"><i class="fa fa-play-circle-o"></i><?php viem_posttype_playlist::get_videos_count( get_the_ID() );?></span>
				<?php viem_dt_posted_on(); ?>
			</div>
		</div><!-- .entry-meta -->
	</div>
</article>
