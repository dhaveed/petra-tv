<?php
/*
 * Template Name: Subscriptions
 * @subpackage Dawn
*/
if( !is_user_logged_in()){
	header('Location: ' . wp_login_url( get_permalink() ));
	exit();
}

$layout = viem_get_theme_option('subscriptions-layout', 'full-width');
$style = 'grid'; // list || grid 
if( isset( $_GET['view'] ) ) $style = $_GET['view'];
$pagination = 'loadmore'; // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');

viem_display_sidebar($layout);
?>
<?php get_header() ?>
	<div class="content-container">
		<div class="<?php viem_dt_container_class() ?>">
			<div class="row">
				<div class="main-wrap <?php echo esc_attr(viem_get_main_class($layout))?>" data-itemprop="mainContentOfPage" role="main">
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
								$message = '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("You do not have any subscriptions", 'viem') .'</div></div>';
								
								$user_subscriptions_list = ( class_exists('viem_posttype_channel') ) ? viem_posttype_channel::user_subscriptions_list() : '';
								
								if( empty($user_subscriptions_list) ):
									echo viem_print_string( $message );
								else:
									krsort($user_subscriptions_list);
								
									$posts_per_page = 4;
									
									foreach ( $user_subscriptions_list as $channel_id ):
									
										$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
										
										$args = array(
											'post_type' => 'viem_video',
											'posts_per_page' => $posts_per_page,
											'post_status' => 'publish',
											'ignore_sticky_posts' => 1,
											'orderby' => 'date',
											'post__not_in' => array($channel_id),
											'paged'			  => $paged,
											'meta_query' => array(
												array(
													'key' => '_dt_video_channel_id',
													'value' => $channel_id,
													'compare' => 'LIKE',
												),
											)
										);
										
										
										$v_query = new WP_Query($args);
										
										$itemSelector = 'viem_video';
										?>
										<?php
										if ( $v_query->have_posts() ) :
											?>
											<div class="item-sub-section">
												<div class="subheader">
													<h3><a href="<?php echo esc_url( get_post_permalink($channel_id) );?>"><?php echo get_the_title($channel_id); ?></a></h3> - <span class="text-decoration"><?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::get_video_count(true, $channel_id);?></span>
												</div>
												<div class="posts viem-videos">
													<div class="posts-wrap grid v-grid-list cols_4">
													<?php
													// Start the Loop.
													while ( $v_query->have_posts() ) : $v_query->the_post();?>
														<article <?php post_class('v-grid-item'); ?> itemscope="">
															<div class="entry-featured-wrap">
																<?php 
																viem_post_image($img_size, 'grid');
																?>
															</div>
															<div class="post-wrapper post-content entry-content">
																<header class="post-header">
																<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
																</header>
																<div class="entry-meta">
																	<div class="entry-meta-content">
																		<?php viem_video_views_count($post_id); ?>
																		<?php viem_video_comments_count($post_id); ?>
																	</div>
																</div><!-- .entry-meta -->
															</div>
														</article>
													<?php
													endwhile;
													?>
													</div>
												</div>
											</div>
										<?php
										endif;
										
										wp_reset_postdata();
									endforeach;
								endif; ?>
							</div>
						</div><!-- /.row -->
					</div>
				</div>
				<?php do_action('viem_dt_left_sidebar');?>
				<?php do_action('viem_dt_right_sidebar'); ?>
			</div>
		</div>
	</div>
<?php get_footer() ?>