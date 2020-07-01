<?php
/**
 * @package dawn
 */
$output = array();
extract(shortcode_atts(array(
	'title'				=>'',
	'style'				=>'list',
	'columns'			=> 3,
	'tablet_columns'	=> 3,
	'mobile_columns'	=> 2,
	'posts_per_page'	=> 9,
	'orderby'			=>'latest',
	'categories'		=>'',
	'exclude_categories'=>'',
	'pagination'		=>'loadmore',
	'loadmore_text'		=>esc_html__('Load More','viem'),
	'img_size'			=>'',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

if( is_front_page() || is_home()) {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
} else {
	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
}

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

$args = array(
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "post",
	'posts_per_page'  => "-1",
	'paged'			  => $paged
);

if(!empty($posts_per_page))
	$args['posts_per_page'] = $posts_per_page;

if(!empty($categories)){
	$args['category_name'] = $categories;
}
if(!empty($exclude_categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'category',
			'terms'    => explode(',',$exclude_categories),
			'field'    => 'slug',
			'operator' => 'NOT IN'
	);
}
$r = new WP_Query($args);

$itemSelector = '';
$itemSelector .= (($pagination === 'infinite_scroll') ? '.post.infinite-scroll-item':'');
$itemSelector .= (($pagination === 'loadmore') ? '.post.loadmore-item':'');

if($r->have_posts()):
?>
<div id="<?php echo esc_attr($sc_id);?>" class="viem-blog-sc wpb_content_element <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?>">
		<?php if( $title != '' ):?>
		<div class="viem_sc_heading">
			<h3 class="viem-sc-title"><span><?php echo esc_html($title);?></span></h3>
		</div>
		<?php endif;?>
		<div class="viem-sc-content">
			<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
				<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns.' cols_tablet_'.$tablet_columns.' cols_mobile_'.$mobile_columns; ?>">
				<?php
				// Start the Loop.
				while ($r->have_posts() ) : $r->the_post();?>
					<?php
					$post_class = '';
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
								'type'		=> $style,
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
						viem_dt_paging_nav_ajax($loadmore_text, $r);
						$paginate_args = array('show_all'=>true);
						break;
					case 'infinite_scroll':
						$paginate_args = array('show_all'=>true);
						break;
				}
				if($pagination != 'no') viem_paginate_links($paginate_args, $r);
				?>
			</div>
		</div>
</div>
<?php
endif;
wp_reset_postdata();

?>