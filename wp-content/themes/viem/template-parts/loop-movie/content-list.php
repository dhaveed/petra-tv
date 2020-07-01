<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */

$post_id  = get_the_ID();
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class ); ?> itemscope="">
	<div class="post-content-wrapper row">
		<?php if( has_post_thumbnail() ):?>
		<div class="col-md-6 col-sm-12">
			<div class="entry-featured-wrap">
				<?php 
				viem_post_image($img_size, 'grid');
				?>
			</div>
		</div>
		<?php endif;?>
		<div class="<?php echo (has_post_thumbnail()) ? 'col-md-6' : '';?> col-sm-12">
			<div class="post-wrapper post-content entry-content">
				<header class="post-header">
				<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
				<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
				</header>
				<div class="post-excerpt">
					<?php echo viem_get_the_excerpt(18); ?>
				</div>
				<?php do_action('viem_movie_info'); ?>
			</div>
		</div>
	</div>
	  
</article><!-- #post-## -->
