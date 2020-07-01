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
			<div class="viem-subscribe-renderer">
				<?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::channel_subscribe_button( get_the_ID() ); ?>
				<span class="subscribers-count"><?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::get_subscribers_count( get_the_ID() );?></span>
			</div>
			</header>
			<div class="post-excerpt">
				<?php echo viem_get_the_excerpt(18); ?>
			</div>
			<div class="entry-meta">
				<div class="entry-meta-content">
					<?php viem_video_views_count( get_the_ID() ); ?>
					<span class="video-number"><i class="fa fa-play"></i><?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::get_video_count(true); ?></span>
				</div>
			</div><!-- .entry-meta -->
		</div>
</article>
