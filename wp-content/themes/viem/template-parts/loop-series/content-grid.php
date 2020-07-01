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
		if( has_post_thumbnail()):
		?>
		<div class="entry-featured post-thumbnail">
			<a class="dt-image-link" href="<?php echo esc_url( add_query_arg(array('series' => $series_id), get_the_permalink()) ) ?>" title="<?php the_title_attribute(); ?>">
				<?php echo get_the_post_thumbnail( get_the_ID(), $img_size ); ?>
			 	<div class="dt-icon-video"></div>
			</a>
		</div>
		<?php
		endif;?>
	</div>
	<div class="post-wrapper post-content entry-content">
		<header class="post-header">
		<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( add_query_arg(array('series' => $series_id), get_the_permalink()) ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<div class="entry-meta">
			<div class="entry-meta-content">
				<?php viem_video_views_count(get_the_ID()); ?>
				<?php viem_video_comments_count(get_the_ID()); ?>
			</div>
		</div><!-- .entry-meta -->
	</div>
</article>
