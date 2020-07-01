<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$excerpt_length = isset($args['excerpt_length']) ? $args['excerpt_length'] : 18;
extract($args);

$post_id = get_the_ID();
?>
<article <?php post_class($post_class); ?>>
	<div class="entry-featured-wrap">
		<?php 
			viem_post_image($img_size);
		?>
	</div>
	<div class="post-wrapper post-content entry-content">
		<header class="post-header">
		<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>
		<?php if( $i == 1 ){ ?>
		<div class="post-excerpt">
			<?php echo viem_get_the_excerpt($excerpt_length); ?>
		</div>
		<?php } ?>
		<div class="entry-meta">
			<div class="entry-meta-content">
				<?php viem_video_views_count(get_the_ID()); ?>
				<?php viem_video_comments_count(get_the_ID()); ?>
			</div>
		</div><!-- .entry-meta -->
	</div>
	  
</article>
