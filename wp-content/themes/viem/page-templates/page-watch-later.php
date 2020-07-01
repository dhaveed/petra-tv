<?php
/*
 * Template Name: Watch Later
 * @subpackage Dawn
*/
if( !is_user_logged_in()){
	header('Location: ' . wp_login_url( get_permalink() ));
	exit();
}

$style = 'watch-later'; // list || grid || masonry
$pagination = 'infinite_scroll'; // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 1;
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
?>
<?php get_header() ?>
	<div class="content-container">
		<div class="<?php viem_dt_container_class() ?>">
			<div class="row">
				<div class="main-wrap <?php echo esc_attr(viem_get_main_class('right-sidebar'))?>" data-itemprop="mainContentOfPage" role="main">
					<div class="main-content">
						<?php 
						if ( have_posts() ) : ?>
							<?php 
							 while (have_posts()): the_post();
							?>
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
							<?php
							 endwhile;
							 ?>
						<?php 
						endif;?>
						
						<div class="row">
							<div class="col-md-12">
								<?php 
								$itemSelector = '';
								$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
								$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
								?>
								<?php
								$message = '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("No videos in this playlist yet", 'viem') .'</div></div>';
								
								$wl_ids = viem_ajax_video_watch_later::user_watch_later_list();
								if( empty($wl_ids) ):
									echo viem_print_string( $message );
								else:
									krsort($wl_ids);
									$posts_per_page = get_option('posts_per_page', 10);
									$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
									$args = array(
										'post_type' => 'viem_video',
										'posts_per_page' => $posts_per_page,
										'post_status' => 'publish',
										'ignore_sticky_posts' => 1,
										'orderby' => 'post__in',
										'order'      => 'DESC',
										'post__in' => $wl_ids,
										'paged'			  => $paged,
									);
									$v_query = new WP_Query($args);
									if ( $v_query->have_posts() ) :
									$i = 0;
										?>
										<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-playlists-wl <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
											<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' dawnthemes_row row' ?>">
											<?php
											// Start the Loop.
											while ( $v_query->have_posts() ) : $v_query->the_post(); $i++;?>
												<?php if($i == 1):?>
												<div class="wl-playall-button clearfix">
													<div class="pull-right">
														<div class="playlist-all-btn">
															<a class="btn" href="<?php echo esc_url( add_query_arg(array('list' => 'wl'), get_the_permalink()) );?>"><i class="fa fa-play"></i><?php esc_html_e('Play All', 'viem')?></a>
														</div>
													</div>
												</div><!-- .page-header -->
												<?php endif;?>
												<?php
												$post_class = '';
												$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
												$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
												if($style == 'masonry')
													$post_class.=' masonry-item';
												?>
												<?php
													viem_dt_get_template("content-watch-later.php", array(
														'post_class' => $post_class,
														'columns' => $columns,
														'img_size'		=> $img_size,
													),
													'template-parts/loop-playlist', 'template-parts/loop-playlist'
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
													viem_dt_paging_nav_ajax($loadmore_text, $v_query);
													$paginate_args = array('show_all'=>true);
													break;
												case 'infinite_scroll':
													$paginate_args = array('show_all'=>true);
													break;
											}
											if($pagination != 'no') viem_paginate_links($paginate_args, $v_query);
											?>
										</div>
									<?php
									else :
										echo viem_print_string( $message );
									endif;
									
									wp_reset_postdata();
									?>
								<?php 
								endif; ?>
							</div>
						</div><!-- /.row -->
					</div>
				</div>
				<?php get_sidebar(); ?>
			</div>
		</div>
	</div>
<?php get_footer() ?>