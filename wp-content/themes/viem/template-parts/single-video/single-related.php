<?php
/**
 * The Template for displaying all related posts by Category & tag.
 *
 * @package Dawn
 */
?>
<?php
	
	$show_related_post = viem_get_theme_option('show_related_videos','1');
			
	if($show_related_post == '0') return;
	
	$posts_per_page = viem_get_theme_option('related_videos_count', 4);
	$layout = viem_get_theme_option('single-video-layout', 'right-sidebar');
	$meta_layout = viem_get_post_meta('single-video-layout', '');
	if( !empty($meta_layout) )
		$layout = $meta_layout;
	
	$grid_list_v = ( $layout == 'full-width' || $layout == 'left-right-sidebar' ) ? 'grid_0' : '';
	$posts_per_page = ( $layout == 'full-width' || $layout == 'left-right-sidebar' ) ? 8 : 4;
	
	$related_items = viem_get_related_videos('', $posts_per_page);
	
	if(!$related_items->have_posts()) return;
	
	
?>
	<div class="related_posts">
		<div class="related_posts__wrapper">
			<div class="related-posts__heading">
				<div class="related-posts__title">
					<h5 class="dt-title"><i class="fa fa-play" aria-hidden="true"></i><?php esc_html_e('You May Be Interested In', 'viem');?></h5>
				</div>
			</div>
	      	<div class="related_content v-grid-list <?php echo esc_attr($grid_list_v); ?>">
					<?php
					while ( $related_items->have_posts() ) : $related_items->the_post();
						?>
						<div class="related-post-item v-grid-item">
						<?php
						get_template_part( 'template-parts/single-video/content','related');
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
