<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?> itemscope="">
	<div class="row">
		<div class="col-sm-1">
			<div class="remove-f-wl">
				<a class="viem-watch-later " href="#" title="<?php echo esc_attr_e('Remove from Watch later', 'viem');?>" data-video="<?php echo esc_attr(get_the_ID()); ?>" data-action="remove" data-remove="<?php echo esc_attr(get_the_ID()); ?>">
		            <i class="fa fa-minus-circle"></i>
		         </a>
			</div>
		</div>
		<?php if( has_post_thumbnail() ): ?>
		<div class="col-sm-4">
			<div class="entry-featured-wrap">
				<?php 
					viem_post_image($img_size, 'grid');
				?>
			</div>
		</div>
		<?php endif; ?>
		<div class="col-sm-7">
			<div class="post-wrapper post-content entry-content">
				<header class="post-header">
				<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
				</header>
				<div class="entry-meta">
					<div class="entry-meta-content">
						<span class="channel-wl"><?php echo ( class_exists('viem_posttype_video') ) ? viem_posttype_video::get_channel_list(get_the_ID(),'',', ','','default') : ''; ?></span>
					</div>
				</div><!-- .entry-meta -->
			</div>
		</div>
	</div>
</article>
