<?php
/**
 * The template part for displaying related posts
 *
 * @package Dawn
 */

$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
?>

<article class="post">
	<div class="entry-featured-wrap">
		<?php 
		viem_post_image($img_size, 'grid', false, false, 'dt-effect7');
		?>
		<div class="entry-video-counter">
			<?php viem_video_like_counter(get_the_ID()); ?>
			<?php
			if( ($duration = viem_get_post_meta('video_duration', get_the_ID(), 0)) ):?>
				<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
			<?php endif;?>
		</div>
	</div>
	<div class="post-wrapper">
		<?php the_title( sprintf('<h5 class="related-post-title"><a href="%s" rel="bookmark">', esc_url(get_permalink()) ), '</a></h5>' ); ?>
	</div>
</article>