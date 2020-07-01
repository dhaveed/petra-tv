<?php
/**
 * The Template for displaying all related posts by Category & tag.
 *
 * @package Dawn
 */
?>
<?php
	$posts_per_page = 6;
	$layout = viem_get_theme_option('single-video-layout', 'right-sidebar');
	$meta_layout = viem_get_post_meta('single-video-layout', '');
	if( !empty($meta_layout) )
		$layout = $meta_layout;
	
	$col_class = ( $layout == 'full-width' || $layout == 'left-right-sidebar' ) ? 'col-sm-4' : 'col-sm-4';
	$posts_per_page = ( $layout == 'full-width' || $layout == 'left-right-sidebar' ) ? 8 : $posts_per_page;
	$data_items = ( $layout == 'full-width' || $layout == 'left-right-sidebar' ) ? 5 : 3;
	
	$related_items = viem_get_related_videos('', $posts_per_page);
	
	if(!$related_items->have_posts()) return;
?>
	<div class="related_videos">
		<div class="related_posts__wrapper">
	      	<div class="viem-slick-slider" data-visible="<?php echo esc_attr($data_items)?>" data-scroll="1" data-infinite="true" data-autoplay="false" data-dots="false">
					<?php
					while ( $related_items->have_posts() ) : $related_items->the_post(); ?>
						<div class="related-post-item <?php echo esc_attr($col_class) ?>">
						<?php
							get_template_part( 'template-parts/single-video/content','related-slide');
						?>
						</div>
					<?php
					endwhile;
					?>
			</div>
		</div>
	</div>
<?php
wp_reset_postdata();
