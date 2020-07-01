<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?> itemscope="">
	<div class="post-content-wrapper">
		<?php
			viem_post_image($img_size, 'grid');
		?>
		<div class="post-content entry-content">
			<header class="post-header">
				<?php	
				the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				?>
			</header><!-- .entry-header -->
			<div class="post-meta">
				<div class="entry-meta-content">
					<span><?php echo date_i18n('Y', strtotime(viem_get_post_meta('movie_release_date')) );?></span>
				</div>
			</div><!-- .entry-meta -->
		</div>
	</div>
	  
</article><!-- #post-## -->
