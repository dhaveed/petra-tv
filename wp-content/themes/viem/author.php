<?php
/**
 * The template for displaying Archive pages
 *
 * @package dawn
 */
$view = isset($_GET['view']) ? $_GET['view'] : '';
$layout = viem_get_theme_option('author-layout', 'right-sidebar');
$style = viem_get_theme_option('blog-style', 'grid'); // list || grid || masonry
$pagination = viem_get_theme_option('blog-pagination', 'infinite_scroll'); // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 1;
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
if( $style == 'grid' || $style == 'masonry' ){
	$columns = viem_get_theme_option('videos-columns', 2);
}

viem_display_sidebar($layout);
get_header();

global $author;
$userdata = get_userdata($author);
$author_id = $userdata->ID;
?>
<div class="page-heading">
	<div class="container">
		<div class="viem_author-wrap">
			<div class="viem_author-avatar">
				<?php
				if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture', $author_id ) )): ?>
					<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
		 		<?php
		 		else: 
					$avatar = get_the_author_meta('viem_user_avatar_default_bg', $author_id );
					echo get_avatar( get_the_author_meta( 'user_email',$author_id ), 200, apply_filters('viem_get_user_avatar_default', $avatar));
				endif; ?>
			</div>
			<div class="viem_author-info" itemscope itemtype="https://schema.org/Person" itemprop="author"'>
				<?php 
				printf( __( '<h1 class="page-title">%s</h1>', 'viem' ), $userdata->display_name );
				
				if ( get_the_author_meta( 'description', $author_id) ) : ?>
				<div class="author-description"><?php the_author_meta( 'description', $author_id ); ?></div>
				<?php endif; ?>
				<div class="author-socials dt-socials-list">
					<?php viem_dt_show_author_social_links('', $author_id, 'echo'); ?>
				</div>
			</div>
		</div>
		<div class="tabbed-header-renderer">
			<div class="content-tab">
				<div class="content-tab-item item-videos <?php echo ( $view == '' || $view == 'videos' ) ? 'active' : ''; ?>">
					<a href="<?php echo esc_url(get_author_posts_url($author_id));?>?view=videos"><?php esc_html_e('VIDEOS', 'viem') ?></a>
				</div>
				<div class="content-tab-item item-playlists <?php echo ( $view == 'channels' ) ? 'active' : ''; ?>">
					<a href="<?php echo esc_url(get_author_posts_url($author_id));?>?view=channels"><?php esc_html_e('Channels', 'viem') ?></a>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="main-content">
	<div class="container">
		<div class="row">
			<section id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout))?>">
				<div id="content" class="main-content site-content" role="main">
						<div class="row">
							<div class="col-md-12">
								<?php 
								switch ($view){
									case 'channels':
										$args = array(
											'post_type' => 'viem_channel',
											'posts_per_page' => -1,
											'post_status' => 'publish',
											'ignore_sticky_posts' => 1,
											'orderby' => 'date',
											'author'	=> $author_id,
										);
										$channels = new WP_Query($args);
										
										if( $channels->have_posts() ):
											$style = 'list';
											$itemSelector = '';
											$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_channel.infinite-scroll-item':'');
											$itemSelector .= (($pagination === 'loadmore') ? '.viem_channel.loadmore-item':'');
											$post_class = '';
											$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
											$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
											?>
											<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-channels <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.esc_attr($columns).'"':''?>>
												<div class="posts-wrap <?php echo esc_attr($style)?> <?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> dawnthemes_row row">
												<?php
												// Start the Loop.
												while ( $channels->have_posts() ) : $channels->the_post();?>
													<?php
													if($style == 'masonry')
														$post_class.=' masonry-item';
													?>
													<?php
														viem_dt_get_template("content-{$style}.php", array(
															'post_class' => $post_class,
															'columns' => 1,
															'img_size'		=> $img_size,
														),
														'template-parts/loop-channel', 'template-parts/loop-channel'
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
														viem_dt_paging_nav_ajax($loadmore_text, $channels);
														$paginate_args = array('show_all'=>true);
														break;
													case 'infinite_scroll':
														$paginate_args = array('show_all'=>true);
														break;
												}
												viem_paginate_links($paginate_args, $channels);
												?>
											</div>
										<?php
										else:
											echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. sprintf(esc_html__("This user doesn't have any channel", 'viem')) .'</div></div>';
										endif;
										
										break;
									default:
										$style = viem_get_theme_option('blog-style', 'grid');
										$itemSelector = '';
										$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
										$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
										
										if ( have_posts() ) :
										?>
										<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.esc_attr($columns).'"':''?>>
											<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
											<?php
											// Start the Loop.
											while ( have_posts() ) : the_post();?>
												<?php
												$post_class = ' ';
												$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
												$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
												if($style == 'masonry')
													$post_class.=' masonry-item';
												?>
												<?php
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
										// If no content, include the "No posts found" template.
										//get_template_part( 'content', 'none' );
										echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. sprintf(esc_html__("This user doesn't have any video", 'viem')) .'</div></div>';
									endif;
									
									break;
								}
								?>
							</div>
						</div><!-- /.row -->
				</div><!-- #content -->
			</section><!-- #primary -->
		<?php do_action('viem_dt_left_sidebar');?>
		<?php do_action('viem_dt_right_sidebar') ?>
		</div><!-- .row -->
	</div>
</div><!-- #main-content -->

<?php
get_footer();
