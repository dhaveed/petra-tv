<?php
/**
 * The template part for displaying content
 *
 * @subpackage Dawn
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
<article id="post-<?php the_ID(); ?>" <?php post_class($post_class ); ?> itemscope="">
	<div class="post-content-wrapper row">
		<?php if( has_post_thumbnail() ):?>
		<div class="col-md-6 col-sm-12">
		<?php
		viem_post_image($img_size, 'list');
		?>
		</div>
		<?php endif;?>
		<div class="<?php echo (has_post_thumbnail()) ? 'col-md-6' : '';?> col-sm-12">
			<div class="post-content entry-content">
				<header class="post-header">
					<?php	
					the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
					?>
				</header><!-- .entry-header -->
				<div class="post-meta">
					<div class="entry-meta">
						<?php
						if( $blog_show_date == 'yes' )
							viem_dt_posted_on();
						?>
						<?php edit_post_link( esc_html__( 'Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
					</div>
				</div><!-- .entry-meta -->
				<div class="post-excerpt">
					<?php echo viem_get_the_excerpt(); ?>
				</div>
				<footer class="entry-footer viem-social-share-toggle">
					<?php if($show_readmore == 'yes'):?>
					<div class="readmore-link">
						<a href="<?php esc_url(the_permalink())?>" class="more-link"><span class="s-btn-label"><?php esc_html_e('Read more', 'viem');?></span></a>
					</div>
					<?php endif;?>
				</footer>
			</div>
		</div>
	</div>
</article><!-- #post-## -->
