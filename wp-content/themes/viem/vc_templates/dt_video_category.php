<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$output = array();
extract(shortcode_atts(array(
	'style'				=>'style_1',
	'title'				=>'',
	'columns'			=>3,
	'tablet_columns'	=>3,
	'mobile_columns'	=>2,
	'posts_per_page'	=>3,
	'orderby'			=>'latest',
	'category'			=>'',
	'show_excerpt'		=>'show',
	'img_size'			=> 'viem_750_490_crop',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

if(empty($category)){
	return;
}
$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

$order = 'DESC';
switch ($orderby) {
	case 'latest':
		$orderby = 'date';
		break;
	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;
	case 'alphabet':
		$orderby = 'title';
		$orderby = 'ASC';
		break;
	case 'ralphabet':
		$orderby = 'title';
		break;
	case 'rand':
		$orderby = 'rand';
		break;
	default:
		$orderby = 'date';
		break;
}
if( $style == 'grid_v3' ){
	$posts_per_page = 5;
}
$args = array(
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "viem_video",
	'posts_per_page'  => $posts_per_page,
);
$args['tax_query'][] =  array(
	'taxonomy' => 'video_cat',
	'terms'    => $category,
	'field'    => 'slug',
	'operator' => 'IN'
);

$p = new WP_Query($args);

if($p->have_posts()):

$i = 0;
?>
<div id="<?php echo esc_attr($sc_id);?>" class="viem-sc-v-category viem_nav_content wpb_content_element  <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr( $style );?>">
	<div class="viem_sc_heading">
		<?php if(!empty($title)):?>
		<h3 class="viem-sc-title"><span><?php echo esc_html($title);?></span></h3>
		<?php endif;?>
		<div class="viem-next-prev-wrap viem-float-right" data-post_type="viem_video" data-taxonomy="video_cat" data-cat="<?php echo esc_attr($category)?>" data-orderby="<?php echo esc_attr($orderby)?>" data-order="<?php echo esc_attr($order)?>" data-columns="<?php echo esc_attr($columns);?>" data-posts-per-page="<?php echo esc_attr($posts_per_page);?>" data-img_size="<?php echo esc_attr($img_size); ?>" data-style="v_cat_<?php echo esc_attr($style);?>" data-show_excerpt="<?php echo esc_attr($show_excerpt);?>">
			<a href="#" class="viem-ajax-prev-page viem_main_color_hover ajax-page-disabled" data-offset="0" data-current-page="1"><i class="fa fa-angle-left"></i></a>
			<a href="#" class="viem-ajax-next-page viem_main_color_hover <?php echo ($p->found_posts == 0) ? 'ajax-page-disabled' : '';?>" data-offset="<?php echo esc_attr($posts_per_page);?>" data-current-page="1"><i class="fa fa-angle-right"></i></a>
		</div>
	</div>
	<div class="viem-sc-content">
		<div class="viem-ajax-loading">
			<div class="viem-fade-loading"><i></i><i></i><i></i><i></i></div>
		</div>
		<div class="viem-sc-list-renderer <?php echo ($style == 'grid') ? 'v-grid-list cols_'.$columns.' cols_tablet_'.$tablet_columns.' cols_mobile_'.$mobile_columns : 'row'; ?>">
		<?php
		$post_class = 'post viem_video';
		
		switch ($style){
			case 'grid':
				$post_col = 'v-grid-item';
				
				while ($p->have_posts()): $p->the_post();
				$post_id = get_the_ID();
				?>
					<div class="<?php echo esc_attr($post_col);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class,
							'show_excerpt' => $show_excerpt
						);
						viem_dt_get_template( 'item-cat-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php endwhile; ?>
				<?php
				break;
			case 'grid_v3':
				
				while ($p->have_posts()): $p->the_post();
					$i++;
					$item_class = 'item_video-'.$i;
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-cat-grid_v3.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php endwhile; ?>
				<?php
				break;
			case 'style_2':
				$post_class = 'post viem_video';
				
				while ($p->have_posts()): $p->the_post();
				$post_id = get_the_ID();
				$i++;
				$item_class = 'item_video-'.$i;
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'i'		=> $i,
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-cat-style_2.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				break;
			default: // style 1
				
				while ($p->have_posts()): $p->the_post();
					$post_id = get_the_ID(); 
					$i++;
					$item_class = 'item_video-'.$i;
					if( $i == 1 ){
						$item_class .= ' col-md-12 col-sm-12';
					}elseif($i > 1){
						$item_class .= ' col-md-6 col-sm-6';
					}
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'i'		=> $i,
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-cat-style_1.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
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