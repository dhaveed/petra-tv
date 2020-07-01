<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */
$post_id  = get_the_ID();
$show_excerpt = (isset($show_excerpt) ? $show_excerpt : 'show');
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
		<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
		</header>
		<?php if( $show_excerpt == 'show' ): ?>
		<div class="post-excerpt">
			<?php echo viem_get_the_excerpt(13); ?>
		</div>
		<?php endif; ?>
		<div class="entry-meta">
			<div class="entry-meta-content">
				<?php viem_video_views_count(get_the_ID()); ?>
				<?php viem_video_comments_count(get_the_ID()); ?>
			</div>
		</div><!-- .entry-meta -->
	</div>
</article>
