<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */
$post_col = '';
$post_id  = get_the_ID();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_col . $post_class ); ?> itemscope="">
	<div class="entry-featured-wrap">
		<?php 
		viem_post_image($img_size, 'grid');
		?>
	</div>
	<div class="post-wrapper post-content entry-content">
		<header class="post-header">
		<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
		</header>
		<?php 
		$post_status = get_post_status();
		$status_label = get_post_status_object($post_status);
		if( !empty( $status_label ) ){
			$status_label = $status_label->label;
			$class_status = 'fa-check';
			if( $post_status == 'pending' ){
				$class_status = 'fa-clock-o';
			}elseif ($post_status == 'draft'){
				$class_status = 'fa-file-text-o';
			}
			
			?>
		<div class="post-status">
			<?php echo esc_html($status_label); ?> <i class="fa <?php echo esc_attr($class_status);?>" aria-hidden="true"></i>
		</div>
		<?php } ?>
		<div class="entry-meta">
			<div class="entry-meta-content">
				<?php viem_video_views_count($post_id); ?>
				<?php viem_video_comments_count($post_id); ?>
			</div>
		</div><!-- .entry-meta -->
	</div>
</article>
