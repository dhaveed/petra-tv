<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$output = array();
extract(shortcode_atts(array(
	'style'				=>'grid',
	'title'				=>esc_html__( 'Most Popular', 'viem' ),
	'orderby'			=>'views',
	'order'				=>'DESC',
	'columns'			=>4,
	'tablet_columns'	=>3,
	'mobile_columns'	=>2,
	'posts_per_page'	=>4,
	'el_class'			=>'',
	'css'				=>'',
), $atts));

$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

// Order By
$meta_key = '';
if( $orderby == 'comment' ){
	$orderby = 'comment_count';
} elseif ( $orderby == 'views' ) {
	$orderby = 'meta_value_num';
	$meta_key = 'post_views_count';
}

if( $style == 'grid_v5' ){
	$columns = 4;
}

$args = array(
	'post_type'      => 'viem_video',
	'posts_per_page' => $posts_per_page ,
	'orderby'		 => $orderby,
	'meta_key'		 => $meta_key,
	'order'          => $order,
	'post_status'    => 'publish'
);


$popularvideos = new WP_Query($args);

if($popularvideos->have_posts()):
	$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
$i = 0;
?>
<div id="<?php echo esc_attr($sc_id);?>" class="viem-sc-trending viem_nav_content wpb_content_element  <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr( $style );?>">
	<div class="viem_sc_heading">
		<?php if(!empty($title)): ?>
		<h3 class="viem-sc-title"><span><?php echo esc_html($title);?></span></h3>
		<?php endif;?>
		<div class="viem-next-prev-wrap viem-float-right" data-post_type="viem_video" data-taxonomy="video_cat" data-cat="" data-orderby="<?php echo esc_attr($orderby)?>" 
		data-meta_key="<?php echo esc_attr($meta_key)?>" data-img_size="<?php echo esc_attr($img_size); ?>" data-meta_value="" data-order="<?php echo esc_attr($order)?>" data-columns="<?php echo esc_attr($columns);?>" data-posts-per-page="<?php echo esc_attr($posts_per_page);?>" data-style="v_trending_<?php echo esc_attr($style);?>">
			<a href="#" class="viem-ajax-prev-page viem_main_color_hover ajax-page-disabled" data-offset="0" data-current-page="1"><i class="fa fa-angle-left"></i></a>
			<a href="#" class="viem-ajax-next-page viem_main_color_hover <?php echo ($popularvideos->found_posts == 0) ? 'ajax-page-disabled' : '';?>" data-offset="<?php echo esc_attr($posts_per_page);?>" data-current-page="1"><i class="fa fa-angle-right"></i></a>
		</div>
	</div>
	<div class="viem-sc-content">
		<div class="viem-ajax-loading">
			<div class="viem-fade-loading"><i></i><i></i><i></i><i></i></div>
		</div>
		<div class="viem-sc-list-renderer v-grid-list <?php echo esc_attr('cols_'.$columns.' cols_tablet_'.$tablet_columns.' cols_mobile_'.$mobile_columns) ?>">
		<?php
		$post_class = 'post viem_video';
		
		switch ($style){
			
			case 'grid_v5': // V5
				$post_col = 'v-grid-item';
				
				while ($popularvideos->have_posts()): $popularvideos->the_post(); $i++;
					$col = ( $i == 1 ) ? 'first clearfix' : $post_col;
					$nsize =  ( $i == 1 ) ? 'large' : $img_size;
				?>
					<div class="<?php echo esc_attr($col);?>">
						<?php
						$args = array(
							'img_size' => $nsize,
							'post_class' => $post_class,
							'i'			=> $i
						);
						viem_dt_get_template( 'item-trending-grid-v5.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php endwhile; ?>
				<?php
				break;
			default: // grid
			
				while ($popularvideos->have_posts()): $popularvideos->the_post();
				?>
					<div class="v-grid-item">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-trending-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php endwhile; ?>
				<?php
			break;
		}
		?>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata();
?>