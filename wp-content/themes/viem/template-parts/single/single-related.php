<?php
/**
 * The Template for displaying all related posts by Category & tag.
 *
 * @package Dawn
 */
?>
<?php
	
	$show_related_post = viem_get_theme_option('show_related_posts','0');
			
	if($show_related_post == '0') return;
		
	$related_items = viem_dt_get_related_posts();
	
	if(!$related_items->have_posts()) return;
	
?>
	<div class="related_posts">
		<div class="related_posts__wrapper">
			<div class="related-posts__heading">
				<div class="related-posts__title">
					<h5 class="dt-title"><i class="fa fa-pencil" aria-hidden="true"></i><?php esc_html_e('Related Posts', 'viem');?></h5>
				</div>
			</div>
	      	<div class="related_content v-grid-list cols_2">
					<?php
					while ( $related_items->have_posts() ) : $related_items->the_post();
						get_template_part( 'template-parts/single/content','related');
					endwhile;
					?>
			</div>
		</div>
	</div>
<?php
wp_reset_postdata(); 
