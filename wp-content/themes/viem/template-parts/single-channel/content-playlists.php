<?php
/**
 * The template part for displaying single channel list playlists
 *
 * @package Dawn
 */
?>
<?php 
$style = 'grid'; // list || grid || masonry
$pagination = 'loadmore'; // wp_pagenavi || loadmore || infinite_scroll
$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
$columns = 3;
$itemSelector = '.post.loadmore-item';
$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');

$args = array(
	'post_type' => 'viem_playlist',
	'posts_per_page' => -1,
	'post_status' => 'publish',
	'ignore_sticky_posts' => 1,
	'orderby' => 'date',
	'post__not_in' => array($channel_id),
	'meta_query' => array(
		array(
			'key' => '_dt_video_channel_id',
			'value' => $channel_id,
			'compare' => 'LIKE',
		),
	)
);
$v_query = new WP_Query($args);

if( $v_query->have_posts() ){
?>
<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-playlists <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
	<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
	
	<?php
	// Start the Loop.
	while ($v_query->have_posts()): $v_query->the_post(); 
		$post_id = get_the_ID();
			$post_class = ' loadmore-item ';
			viem_dt_get_template("content-{$style}.php", array(
				'post_class' => $post_class,
				'columns' => $columns,
				'img_size'		=> $img_size,
			),
			'template-parts/loop-playlist', 'template-parts/loop-playlilst'
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
}else{
	echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("This channel doesn't have any playlists", 'viem') .'</div></div>';
}

wp_reset_postdata();
?>