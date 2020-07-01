<?php
/**
 * The template part for displaying results in search pages
 *
 */
?>
<?php
$blog_show_date = viem_get_theme_option('blog_show_date','1') == '1' ? 'yes':'';
$blog_show_comment = viem_get_theme_option('blog_show_comment','1') == '1' ? 'yes':'';
$blog_show_category = viem_get_theme_option('blog_show_category','1') == '1' ? 'yes':'';
$blog_show_author = viem_get_theme_option('blog_show_author','1') == '1' ? 'yes':'';
$blog_show_tag = viem_get_theme_option('blog_show_tag','1') == '1' ? 'yes':'';
$show_readmore = viem_get_theme_option('blog_show_readmore','1') == '1' ? 'yes':'';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('col-md-12 col-sm-6' . $post_class ); ?> itemscope="">
	<?php
	if(get_post_format() == 'link'):
		$link = viem_get_post_meta('link');
		?>
		<div class="hentry-wrap hentry-wrap-link">
			<div class="entry-content">
				<div class="link-content">
					<a target="_blank" href="<?php echo esc_url($link) ?>">
						<cite><?php echo esc_url($link) ?></cite>
					</a>
				</div>
			</div>
		</div>
	<?php
	elseif (get_post_format() == 'quote'):?>
		<div class="hentry-wrap hentry-wrap-link">
			<div class="entry-content">
				<div class="quote-content">
					<?php if(has_post_thumbnail()):?>
					<div class="quote-thumb">
					<?php
					the_post_thumbnail();?>
					</div>
					<?php
					endif;
					?>
					<a href="<?php esc_url(the_permalink())?>" class="quote-link">
						<cite><i class="fa fa-quote-left"></i></cite>
						<span class="quote">
							<?php echo viem_get_post_meta('quote'); ?>
						</span>
						<?php if(viem_get_post_meta('quote') != ''): ?>
						<div class="quote-author"><?php echo esc_html__('By ', 'viem') . viem_get_post_meta('quote_author');?></div>
						<?php endif; ?>
					</a>
				</div>
			</div>
		</div>
	<?php
	else:?>
	<?php 
	$entry_featured_class = '';
	viem_dt_post_featured('','',true,false,$entry_featured_class,'default');
	?>
	<?php endif;?>
	<div class="hentry-wrap">
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
		</header><!-- .entry-header -->
		
		<div class="post-excerpt">
			<?php the_excerpt(); ?>
		</div>
		
		<div class="post-meta">
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
			<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
			<?php
				if ( $blog_show_comment == 'yes' && !post_password_required() && ( comments_open() || get_comments_number() ) ) :
			?>
			<span class="comments-link"><i class="fa fa-comment-o"></i><?php comments_popup_link( esc_html__( 'Leave a comment', 'viem' ), esc_html__( '1 Comment', 'viem' ), esc_html__( '% Comments', 'viem' ) ); ?></span>
			<?php
				endif;
	
			?>
		</div><!-- .entry-meta -->
		<?php if($blog_show_tag == 'yes') the_tags( '<footer class="tags-list"><span class="tag-title"><i class="fa fa-tags"></i></span><span class="tag-links">', ' , ', '</span></footer>' ); ?>
		<?php if($show_readmore == 'yes'):?>
		<div class="readmore-link">
			<a href="<?php esc_url(the_permalink())?>" class="more-link"><span class="s-btn-label"><?php esc_html_e('Read More', 'viem');?></span></a>
		</div>
		<?php endif;?>
	</div>
	  
</article><!-- #post-## -->
