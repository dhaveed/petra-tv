<?php
extract(shortcode_atts(array(
	'title'				=>'',
	'style'      		=>'grid',
	'template'      	=>'',
	'badge'      		=>'',
	'show_badge'      	=>'show',
	'rows'				=>'1',
	'columns'			=>4,
	'posts_per_page'	=>4,
	'orderby'			=>'date',
	'order'				=>'DESC',
	'img_size'			=>'viem_360_235_crop',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

if( $badge == '' ){
	return '';
}

$sc_id = uniqid('dt_sc_');
$class   = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

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
		$order = 'ASC';
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

$args = array(
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "viem_video",
	'posts_per_page'  => $posts_per_page,
);
$args['tax_query'][] =  array(
	'taxonomy' => 'video_badges',
	'terms'    => explode(',',$badge),
	'field'    => 'slug',
	'operator' => 'IN'
);

$v = new WP_Query($args);

$show_badge = ( $show_badge == 'show' ) ? true : false;

if($v->have_posts()): $post_count = $v->post_count;
	ob_start();
	?>
	<div id="<?php echo esc_attr($sc_id);?>" class="viem-sc-badges wpb_content_element <?php echo ( $style == 'ajax_nav' ) ? 'viem_nav_content' : ''; ?> <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr($style); ?> <?php echo esc_attr($template); ?> <?php echo ($post_count > 5) ? 'center' : ''; ?>">
		<?php
		$post_class = 'post viem_video';
		switch ($style){
			case 'ajax_nav':
				$post_col = 'v-grid-item';
				?>
				<div class="viem_sc_heading">
					<?php if(!empty($title)):?>
					<h3 class="viem-sc-title"><span <?php echo ( $template == 'dark' ) ? 'class="viem_main_color"' : ''; ?>><?php echo esc_html($title);?></span></h3>
					<?php endif;?>
					<div class="viem-next-prev-wrap viem-float-right" data-post_type="viem_video" data-taxonomy="video_badges" data-cat="<?php echo esc_attr($badge)?>" data-orderby="<?php echo esc_attr($orderby)?>" data-order="<?php echo esc_attr($order)?>" data-columns="<?php echo esc_attr($columns);?>" data-posts-per-page="<?php echo esc_attr($posts_per_page);?>" data-img_size="<?php echo esc_attr($img_size); ?>" data-style="v_badges_<?php echo esc_attr($style);?>">
						<a href="#" class="viem-ajax-prev-page viem_main_color_hover ajax-page-disabled" data-offset="0" data-current-page="1"><i class="fa fa-angle-left"></i></a>
						<a href="#" class="viem-ajax-next-page viem_main_color_hover <?php echo ($v->found_posts == 0) ? 'ajax-page-disabled' : '';?>" data-offset="<?php echo esc_attr($posts_per_page);?>" data-current-page="1"><i class="fa fa-angle-right"></i></a>
					</div>
				</div>
				<div class="viem-sc-content">
					<div class="viem-ajax-loading">
						<div class="viem-fade-loading"><i></i><i></i><i></i><i></i></div>
					</div>
					<div class="viem-sc-list-renderer v-grid-list <?php echo esc_attr('cols_'.$columns); ?>">
						<?php
						while ($v->have_posts()): $v->the_post();
						?>
							<div class="<?php echo esc_attr($post_col);?>">
								<?php
								$args = array(
									'img_size' => $img_size,
									'post_class' => $post_class
								);
								viem_dt_get_template( 'item-badge-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
								?>
							</div>
							<?php endwhile; ?>
					</div>
				</div>
				<?php
				break;
			case 'slider':
				if(!empty($title)):?>
				<h3 class="viem-sc-title"><div class="sc-icon-title"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div><span><?php echo esc_html($title);?></span></h3>
				<?php endif;?>
				<div class="viem-sc-content ">
					<div id="<?php echo esc_attr($sc_id) ?>" class="owl-carousel viem-carousel-slide viem-preload" data-autoplay="false" data-dots="1" data-nav="<?php echo ($post_count > 5) ? 1 : 0; ?>" data-items="5" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
						<?php
						while ($v->have_posts()): $v->the_post(); $post_id = get_the_ID(); ?>
						<article <?php post_class('item post viem_video '); ?>>
							<div class="entry-featured-wrap">
								<?php
								viem_post_image($img_size, 'grid', true, $show_badge);
								?>
							</div>
							<div class="post-wrapper post-content entry-content">
								<?php 
								$taxonomy_objects = get_object_taxonomies( 'viem_video' );
								$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
								$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
					        	<div class="post-category">
					        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
					        	</div>
								<header class="post-header">
									<?php	
									the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
									?>
								</header><!-- .entry-header -->
							</div>
							  
						</article>
						<?php endwhile;  ?>
					</div>
				</div>
				<?php
				break;
			default: // Grid
				$post_col = ' v-grid-item';
				if(!empty($title)):?>
					<h3 class="viem-sc-title">
						<span class="sc-icon-title">
							<i class="fa fa-star fa fa-star viem_main_color"></i><i class="fa fa-star viem_main_color"></i><i class="fa fa-star viem_main_color"></i>
						</span>
						<span><?php echo esc_html($title);?></span>
					</h3>
				<?php endif;?>
				<div class="viem-sc-content">
					<div class="v-grid-list <?php echo esc_attr('cols_'.$columns); ?>">
					<?php
					while ($v->have_posts()): $v->the_post(); $post_id = get_the_ID(); ?>
						<article <?php post_class($post_col . ' post viem_video '); ?>>
							<div class="entry-featured-wrap">
								<?php 
								viem_post_image($img_size, 'grid', true, $show_badge);
								?>
							</div>
							<div class="post-wrapper post-content entry-content">
								<?php 
								$taxonomy_objects = get_object_taxonomies( 'viem_video' );
								$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
								$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
					        	<div class="post-category">
					        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
					        	</div>
								<header class="post-header">
									<?php	
									the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
									?>
								</header><!-- .entry-header -->
							</div>
							  
						</article>
					<?php endwhile;  ?>
					</div>
				</div>
				<?php
				break;
		} // End switch
		?>
		
	</div>
	<?php
	
	echo ob_get_clean();

endif;
wp_reset_postdata();
