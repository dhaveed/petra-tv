<?php
/**
 * The template part for displaying single playlist player fullwidth
 *
 * @package Dawn
 */

$single_show_date = viem_get_theme_option('single_playlist_show_date', '1');
$single_show_category = viem_get_theme_option('single_playlist_show_category', '1');
$single_show_author = viem_get_theme_option('single_playlist_show_author', '1');
$single_show_tag = viem_get_theme_option('single_playlist_show_tag', '1');
$single_show_postnav = viem_get_theme_option('single_playlist_show_postnav', '1');
$single_show_authorbio = viem_get_theme_option('single_playlist_show_authorbio', '1');
$comments_type	= viem_get_theme_option('comments_type', 'wp');
$like = 0;

$video_playlist_type_add = viem_get_post_meta('video_playlist_type_add');

?>
<div class="row">
	<div id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout)) ?>">
		<div id="content" class="main-content site-content dawn-single-post-playlist viem-playlist-tpl-2" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); $playlist_id = get_the_ID();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<header class="entry-header">
							<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
						</header><!-- .entry-header -->
						
						<?php if( $video_playlist_type_add == 'youtube_playlist' ): 
							$youtube_playlist_id = viem_get_post_meta('youtube_playlist_id', get_the_ID(), 'AIzaSyDubr0507UZlncNqozhr9Dr2O2r8BmrFEE');
							?>
							<div class="viem-video-player-wrapper">
								<div id="v-container">
									<?php 
									viem_video_featured( $youtube_playlist_id, '' );
									?>
								</div>
							</div>
							<div id="viem_background_lamp"></div>
						<?php endif; ?>
						
						<?php do_action('viem_before_single_post_content'); ?>
						<div class="post-content hidden-content">
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
						<?php if( $video_playlist_type_add != 'youtube_playlist' ): ?>
						<div class="viem-single-playlist-videos">
							<?php
							$style = 'grid'; // list || grid || masonry
							$pagination = 'infinite_scroll'; // wp_pagenavi || loadmore || infinite_scroll
							$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
							$columns = 3;
							$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
							$posts_per_page = get_option('posts_per_page', 10);
							
							$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
							
							$args = array(
								'post_type' => 'viem_video',
								'posts_per_page' => $posts_per_page,
								'post_status' => 'publish',
								'ignore_sticky_posts' => 1,
								'orderby' => 'date',
								'post__not_in' => array($playlist_id),
								'paged'			  => $paged,
								'meta_query' => array(
									array(
										'key' => '_dt_video_playlist_id',
										'value' => $playlist_id,
										'compare' => 'LIKE',
									),
								)
							);
							$v = new WP_Query($args);
							$itemSelector = '';
							$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
							$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
							
							if( $v->have_posts() ){
							$i = 0;
							?>
							<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
								<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
								
								<?php
								$post_class = '';
								$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
								$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
								// Start the Loop.
								while ($v->have_posts()): $v->the_post(); $i++;
										if( $i == 1 ){
										?>
											<div class="playlist-videos-toolbar">
												<div class="playlist-all-btn">
													<a class="btn" href="<?php echo esc_url( add_query_arg(array('list' => $playlist_id), get_the_permalink()) );?>"><i class="fa fa-play"></i><?php esc_html_e('Play All', 'viem')?></a>
												</div>
											</div>
										<?php
										}
										if($style == 'masonry')
											$post_class.=' masonry-item';
										
										viem_dt_get_template("content-{$style}.php", array(
											'post_class' => $post_class,
											'columns' => $columns,
											'img_size'		=> $img_size,
										),
										'template-parts/loop-video', 'template-parts/loop-video'
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
								echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("This playlist doesn't have any videos", 'viem') .'</div></div>';
							}
							
							wp_reset_postdata();
							?>
						</div>
						<?php endif; ?>
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
