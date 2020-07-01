<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
 */
?>
<?php
$img_size = (isset($img_size)) ? $img_size : '';
$columns = (isset($columns)) ? $columns : '';

$blog_show_date = viem_get_theme_option('blog_show_date','1') == '1' ? 'yes':'';
$blog_show_comment = viem_get_theme_option('blog_show_comment','1') == '1' ? 'yes':'';
$blog_show_category = viem_get_theme_option('blog_show_category','1') == '1' ? 'yes':'';
$blog_show_author = viem_get_theme_option('blog_show_author','1') == '1' ? 'yes':'';
$blog_show_tag = viem_get_theme_option('blog_show_tag','1') == '1' ? 'yes':'';
$show_readmore = viem_get_theme_option('blog_show_readmore','0') == '1' ? 'yes':'';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class($columns.$post_class); ?> itemscope="">
	<div class="post-content-wrapper">
		<?php 
		if( has_post_thumbnail() ):
			viem_post_image($img_size, 'list');
		?>
		<?php 
		endif;?>
		<div class="entry-content">
			<header class="post-header">
				<?php if ( $blog_show_category == 'yes' && in_array( 'category', get_object_taxonomies( get_post_type() ) ) && viem_dt_categorized_blog() ) : ?>
				<div class="post-category">
					<span class="cat-links"><?php echo get_the_category_list( esc_html_x( ' ', 'Used between list items, there is a space after the comma.', 'viem' ) ); ?></span>
				</div>
				<?php
				endif;
				?>
				<?php
				the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				?>
			</header>
			
			<div class="post-excerpt">
				<?php echo viem_get_the_excerpt(); ?>
			</div>
			
			<div class="entry-meta post-meta">
				<?php
				if( $blog_show_author == 'yes' ):
					printf('<span class="byline"><span class="author vcard">%1$s <a class="url fn n" href="%2$s" rel="author">%3$s</a></span></span>',
						esc_html__('By','viem'),
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						get_the_author()
					);
				endif;
				?>
				<?php
				if( $blog_show_date == 'yes' )
					viem_dt_posted_on();
				?>
				<?php
				if ( $blog_show_comment == 'yes' && !post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>
					<?php comments_popup_link(
							'',
							'<i class="fa fa-comment-o"></i>'.esc_html__( '1', 'viem' ),
							'<i class="fa fa-comment-o"></i>'.esc_html__( '%', 'viem' ),
							'comments-link',
							''
					);?>
				<?php
					endif;
		
				?>
				<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
			</div>
			<?php if($blog_show_tag == 'yes') the_tags( '<footer class="tags-list"><span class="tag-title"><i class="fa fa-tags"></i></span><span class="tag-links">', ' , ', '</span></footer>' ); ?>
			<?php if($show_readmore == 'yes'):?>
			<div class="readmore-link">
				<a href="<?php esc_url(the_permalink())?>" class="more-link"><span class="s-btn-label"><?php esc_html_e('Continue Reading', 'viem');?></span></a>
			</div>
			<?php endif;?>
		</div>
		  
	</div>
</article>
