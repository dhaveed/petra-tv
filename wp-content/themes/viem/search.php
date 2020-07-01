<?php
/**
 * The template for displaying Search Results pages
 *
 * @package dawn
 */
$layout = viem_get_theme_option('blog-layout', 'right-sidebar');
$style = viem_get_theme_option('blog-style', 'list'); // list || grid || masonry 
$pagination = viem_get_theme_option('blog-pagination', 'infinite_scroll'); // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 1;
$img_size = viem_get_theme_option('blog-image-size', 'default');
viem_display_sidebar($layout);
get_header(); ?>
<div id="main-content">
		<div class="row">
			<section id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout))?>">
				<div id="content" class="main-content site-content" role="main">
					<div class="row">
						<div class="col-md-12">
							<header class="page-header">
								<h1 class="page-title"><?php printf( __ ( 'Search Results for: %s', 'viem' ), get_search_query() ); ?></h1>
							</header><!-- .page-header -->
							<?php 
							$itemSelector = '';
							$itemSelector .= (($pagination === 'infinite_scroll') ? '.post.infinite-scroll-item':'');
							$itemSelector .= (($pagination === 'loadmore') ? '.post.loadmore-item':'');
							?>
							<?php
							if ( have_posts() ) :
								?>
								<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
									<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' dawnthemes_row row' ?>">
									<?php
									// Start the Loop.
									$i = 0;
									while ( have_posts() ) : the_post();?>
										<?php
										$post_class = '';
										$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
										$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
										if($layout == 'masonry')
											$post_class.=' masonry-item';
										?>
										<?php
											viem_dt_get_template("content-list.php", array(
											'post_class' => $post_class,
											'columns' => $columns,
											'img_size'		=> $img_size,
											),
											'template-parts/loop', 'template-parts/loop'
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
								get_template_part( 'content', 'none' );
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
