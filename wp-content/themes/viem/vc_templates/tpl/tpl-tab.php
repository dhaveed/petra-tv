<?php
/**
 * The template for displaying tab content of Video Categories shortcode
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
extract($tab_args);

$offset = (isset($offset) && $offset) ? $offset : 0;
$paged = (isset($paged) && $paged) ? $paged : 1;

?>
<div class="viem-template-tab tab-pane <?php echo ($tab_active == 'active') ? 'active' : '';  ?>" id="<?php echo esc_attr($tab_id);?>">
	<?php 
	$loop = viem_video_tabs_query($query_types, $tab, $orderby, $meta_key, $order, $number_query, $offset, $paged);
	$idx = 0;
	$template_name = 'item-grid.php';
	$post_class = 'post viem_video';
    if( $loop->have_posts() ):
        		?>
        		<div class="<?php echo ( $display_type == 'style_2' || $display_type == 'grid' ) ? 'v-grid-list cols_'.$columns : 'row';?>">
					<?php
				while ( $loop->have_posts() ) : $loop->the_post();
					$idx = $idx + 1;
					$class = ( $display_type == 'style_1' ) ? 'viem-video-item' : ' col-md-'.(12/$columns).' col-sm-6 dawnthemes-col-xs-12';
					
					if( $display_type == 'style_2' ){
						$class = 'v-grid-item';
					}
					if( $display_type == 'style_3' ){
						
						$class = 'item_video-'.$idx;
						$template_name = 'item-grid_3.php';
					}
					?>
					<div class="<?php echo esc_attr( $class ); ?>">
						<?php
							$args = array(
								'i'		=> $idx,
								'excerpt_length' => 45,
								'img_size' => $img_size,
								'post_class' => $post_class,
							);
							viem_dt_get_template( $template_name, $args, 'vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php
		    	endwhile;
		    	if($idx < $number_query){
		    		// there are no more product
		    		// print a flag to detect
		    		echo '<div id="viem-ajax-no-products" class=""><!-- --></div>';
		    	}
		    	?>
		    </div>
        <?php
    endif;

    wp_reset_postdata();
    ?>
</div>