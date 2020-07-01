<?php
/**
 * The template part for displaying single
 *
 * @package Dawn
 */
?>
<div class="row">
	<div id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout)) ?>">
		<div id="content" class="main-content site-content dawn-single-post-playlist viem-playlist-tpl-2" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); $series_id = get_the_ID();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
							<div class="post-meta">
								<div class="entry-meta">
									<span class="videos-number"><i class="fa fa-play-circle-o"></i><?php viem_posttype_series::get_videos_count($series_id); ?></span>
									<?php echo viem_get_post_meta('release_year') ? '<span><i class="fa fa-clock-o" aria-hidden="true"></i> '.viem_get_post_meta('release_year').'</span>' : '';?>
									<?php echo viem_get_post_meta('creators') ? '<span><i class="fa fa-user" aria-hidden="true"></i> '.viem_get_post_meta('creators').'</span>' : '';?>
								</div>
							</div>
						</header><!-- .entry-header -->
						<div class="post-content">
							<div class="entry-content">
								<?php
									the_content();
						
									wp_link_pages( array(
										'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'viem' ) . '</span>',
										'after'       => '</div>',
										'link_before' => '<span>',
										'link_after'  => '</span>',
										'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'viem' ) . ' </span>%',
										'separator'   => '<span class="screen-reader-text">, </span>',
									) );
								?>
							</div> <!-- .entry-content -->
						</div>
						<div class="viem-single-playlist-videos">
							<?php
							$style = 'grid'; // list || grid || masonry
							$pagination = 'infinite_scroll'; // wp_pagenavi || loadmore || infinite_scroll
							$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
							$columns = ($layout == 'full-width') ? 3 : 2;
							$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
							$posts_per_page = get_option('posts_per_page', 10);
							
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
							
							$args = array(
								'post_type' => 'viem_video',
								'posts_per_page' => $posts_per_page,
								'post_status' => 'publish',
								'ignore_sticky_posts' => 1,
								'orderby' => 'meta_value_num',
								'meta_key' => '_dt_order_in_series',
								'order' => 'ASC',
								'post__not_in' => array($series_id),
								'paged'			  => $paged,
								'meta_query' => array(
									array(
										'key' => '_dt_video_series_id',
										'value' => $series_id,
										'compare' => 'LIKE',
									),
								)
							);
							$v = new WP_Query($args);
							$itemSelector = '';
							$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
							$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
							
							if( $v->have_posts() ){
							?>
							<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
								<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
								
								<?php
								// Start the Loop.
								while ($v->have_posts()): $v->the_post();
										$post_class = '';
										$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
										$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
										if($style == 'masonry')
											$post_class.=' masonry-item';
										
										viem_dt_get_template("content-{$style}.php", array(
											'series_id' => $series_id,
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
										viem_dt_paging_nav_ajax($loadmore_text, $v);
										$paginate_args = array('show_all'=>true);
										break;
									case 'infinite_scroll':
										$paginate_args = array('show_all'=>true);
										break;
								}
								if($pagination != 'no') viem_paginate_links($paginate_args, $v);
								?>
							</div>
							<?php
							}else{
								echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("This series doesn't have any videos", 'viem') .'</div></div>';
							}
							
							wp_reset_postdata();
							?>
						</div>
						<footer class="entry-footer">
						</footer>
					</article><!-- #post-## -->
					<?php
				endwhile; // end of the loop.
			?>
		</div><!-- #content -->
</div><!-- #primary -->
<?php do_action('viem_dt_left_sidebar');?>
<?php do_action('viem_dt_right_sidebar'); ?>
</div><!-- .row -->
