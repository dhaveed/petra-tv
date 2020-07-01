<?php
/**
 * The template part for displaying single channel list videos
 *
 * @package Dawn
 */
?>
<?php  $channel_id = get_the_ID();
$style = 'grid'; // list || grid || masonry
$pagination = 'infinite_scroll'; // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 3;
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');

$posts_per_page = get_option('posts_per_page', 10);

$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );

$args = array(
	'post_type' => 'viem_video',
	'posts_per_page' => $posts_per_page,
	'post_status' => 'publish',
	'ignore_sticky_posts' => 1,
	'orderby' => 'date',
	'post__not_in' => array($channel_id),
	'paged'			  => $paged,
	'meta_query' => array(
		array(
			'key' => '_dt_video_channel_id',
			'value' => $channel_id,
			'compare' => 'LIKE',
		),
	)
);
$v = new WP_Query($args);

$itemSelector = '';
$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');

if( $v->have_posts() ){
?>
<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
	<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
	
	<?php
	// Start the Loop.
	while ($v->have_posts()): $v->the_post(); 
			$post_class = '';
			$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
			$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
			if($style == 'masonry')
				$post_class.=' masonry-item';
			
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
			viem_dt_paging_nav_ajax($loadmore_text, $v);
			$paginate_args = array('show_all'=>true);
			break;
		case 'infinite_scroll':
			$paginate_args = array('show_all'=>true);
			break;
	}
	if($pagination != 'no') viem_paginate_links($paginate_args, $v);
	?>
</div>
<?php
}else{
	echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("This channel doesn't have any videos", 'viem') .'</div></div>';
}

wp_reset_postdata();
?>