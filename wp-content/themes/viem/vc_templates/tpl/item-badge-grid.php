<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
extract($args);
$post_id = get_the_ID();
?>
<article <?php post_class($post_class); ?>>
	<div class="entry-featured-wrap">
		<?php 
		viem_post_image($img_size, 'grid');
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