<?php
/**
 * The template part for displaying related posts
 *
 * @package Dawn
 */
$img_size = viem_get_theme_option('blog-image-size', 'default');
?>
<div class="related-post-item v-grid-item">
	<article class="post">
		<?php
		viem_post_image($img_size, 'grid', true, true, 'dt-effect6');
		?>
		<div class="post-wrapper">
			<?php the_title( sprintf('<h5 class="related-post-title"><a href="%s" rel="bookmark">', esc_url(get_permalink()) ), '</a></h5>' ); ?>
			<div class="entry-meta">
				<div class="entry-meta-content">
					<?php viem_video_views_count(get_the_ID()); ?>
					<?php viem_video_comments_count(get_the_ID()); ?>
				</div>
			</div><!-- .entry-meta -->
		</div>
	</article>
</div>
