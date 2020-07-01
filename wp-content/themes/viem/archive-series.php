<?php
/**
 * The template for displaying Archive pages
 *
 * @package dawn
 */
$layout = viem_get_theme_option('archive-layout', 'full-width');
$style = 'grid'; // list || grid || masonry
$pagination = 'loadmore'; // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 1;
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
if( $style == 'grid' || $style == 'masonry' ){
	$columns = viem_get_theme_option('videos-columns', 3);
}

viem_display_sidebar($layout);
get_header(); ?>
<div id="main-content">
		<div class="row">
			<section id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout))?>">
				<div id="content" class="main-content site-content" role="main">
					<div class="row">
						<div class="col-md-12">
							<?php 
							$itemSelector = '';
							$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
							$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
							$post_class = ' viem_video ';
							$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
							$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
							?>
							<?php
							if ( have_posts() ) :
								?>
								<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.esc_attr($columns).'"':''?>>
									<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo ' v-grid-list cols_'.$columns; ?>">
									<?php
									// Start the Loop.
									while ( have_posts() ) : the_post();?>
										<?php
										
										if($style == 'masonry')
											$post_class.=' masonry-item';
										?>
										<?php
											viem_dt_get_template("content-series.php", array(
												'post_class' => $post_class,
												'columns' => $columns,
												'img_size'		=> $img_size,
											),
											'template-parts/loop-series', 'template-parts/loop-series'
											);
										?>
									<?php
									endwhile;
									?>
									</div>
									<?php
									// Previous/next post navigation.
									// this paging nav should be outside .posts-wrap
									$paginate_args = array();
									switch ($pagination){
										case 'loadmore':
											viem_dt_paging_nav_ajax($loadmore_text);
											$paginate_args = array('show_all'=>true);
											break;
										case 'infinite_scroll':
											$paginate_args = array('show_all'=>true);
											break;
									}
									viem_paginate_links($paginate_args);
									?>
								</div>
							<?php
							else :
								echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("No videos in this playlist yet", 'viem') .'</div></div>';
							endif;
							?>
						</div>
					</div><!-- /.row -->
				</div><!-- #content -->
			</section><!-- #primary -->
		<?php do_action('viem_dt_left_sidebar');?>
		<?php do_action('viem_dt_right_sidebar') ?>

	</div><!-- .row -->
</div><!-- #main-content -->

<?php
get_footer();
