<?php
/**
 * The template part for displaying single posts
 * Please don't modify me
 *
 * @package Dawn
 */

$single_show_date = viem_get_theme_option('single_show_date', '1');
$single_show_category = viem_get_theme_option('single_show_category', '1');
$single_show_author = viem_get_theme_option('single_show_author', '1');
$single_show_tag = viem_get_theme_option('single_show_tag', '1');
$single_show_postnav = viem_get_theme_option('single_show_postnav', '1');
$comments_type	= viem_get_theme_option('comments_type', 'wp');
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<div class="entry-meta">
			<?php
			if( $single_show_author == 1 ):
			printf('<span class="byline"><span class="author vcard">%1$s <a class="url fn n" href="%2$s" rel="author">%3$s</a></span></span>',
				esc_html__('By', 'viem'),
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
				get_the_author()
			);
			endif;
			?>
			<?php
			if ( $single_show_date == 1 && 'post' == get_post_type() )
				viem_dt_posted_on();
			?>
			<?php edit_post_link( esc_html__( ' Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
			<div class="entry-meta__express">
				<?php
				if ( $comments_type === 'wp' && comments_open() && get_comments_number() ) :
				?>
					<span class="comments-link"><i class="fa fa-comments"></i><?php comments_popup_link( esc_html__( '0', 'viem' ), esc_html__( '1', 'viem' ), esc_html__( '%', 'viem' ) ); ?></span>
				<?php
				endif;
				?>
				<?php viem_video_views_count(get_the_ID()); ?>
			</div>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<?php viem_dt_post_featured(); ?>
	<div class="post-content ">
		<div class="entry-content">
			<?php
				the_content();
	
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'viem' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
					'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'viem' ) . ' </span>%',
					'separator'   => '<span class="screen-reader-text">, </span>',
				) );
			?>
		</div> <!-- .entry-content -->
	</div>
	<footer class="entry-footer">
		<?php if( viem_get_theme_option('post_show_share', '0') == '1' ): ?>
		<div class="entry-share">
			<span class="share-title"><?php echo esc_html__( 'Share this:', 'viem' );?></span>
			<?php do_action('dawnthemes_print_social_share'); ?>
		</div>
		<?php endif;?>
		<?php if ( $single_show_category == 1 && in_array( 'category', get_object_taxonomies( get_post_type() ) ) && viem_dt_categorized_blog() ) : ?>
		<div class="post-category">
			<span class="cat-title"><?php echo esc_html__( 'Category:', 'viem' );?></span>
			<span class="cat-links"><?php echo get_the_category_list( esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' ) ); ?></span>
		</div>
		<?php
		endif;
		?>

		<?php
		if($single_show_tag == '1'):
			$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'viem' ) );
			if ( $tags_list ) {
				printf( '<div class="tags-list"><span class="tag-title">%1$s </span><span class="tag-links">%2$s</span></div>',
					_x( 'Tag:', 'Used before tag names.', 'viem' ),
					$tags_list
				);
			}
		endif;
		?>
	</footer>
	<?php
	if ( 'post' === get_post_type() && $single_show_authorbio == 1 && get_the_author_meta('description')!='' ) :?>
	<div class="author-info ">
		<?php
		$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 100 );
		$avatar = get_the_author_meta('viem_user_avatar_default_bg', get_the_author_meta( 'ID' ));
		?>
		<div class="author-avatar">
			<?php echo get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
		</div>
		<div class="author-description">
			<div class="author-primary">
				<h5 class="author-title font-2">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo get_the_author(); ?></a>
				</h5>
			</div>
			<div class="author-desc"><?php echo wp_trim_words( get_the_author_meta('description') , 26, '...'); ?></div>
			<div class="author-socials">
				<?php viem_dt_show_author_social_links('', get_the_author_meta( 'ID' ), 'echo'); ?>
			</div>
		</div>
	</div>
	<?php
	endif;
	
	
	if( $single_show_postnav == '1' ):
		if ( is_singular( 'attachment' ) ) {
			// Parent post navigation.
			the_post_navigation( array(
				'prev_text' => _x( '<span class="meta-nav">Published in</span><span class="post-title">%title</span>', 'Parent post link', 'viem' ),
			) );
		} elseif ( is_singular( 'post' ) ) {
			// Previous/next post navigation.
			the_post_navigation( array(
				'next_text' => '<span class="meta-nav" aria-hidden="true">' . esc_html__( 'Next post', 'viem' ) . '<i class="fa fa-long-arrow-right" aria-hidden="true"></i></span> ' .
					'<span class="screen-reader-text">' . esc_html__( 'Next post:', 'viem' ) . '</span> ' .
					'<span class="post-title font-2">%title</span>',
				'prev_text' => '<span class="meta-nav" aria-hidden="true"><i class="fa fa-long-arrow-left" aria-hidden="true"></i>' . esc_html__( 'Previous post', 'viem' ) . '</span> ' .
					'<span class="screen-reader-text">' . esc_html__( 'Previous post:', 'viem' ) . '</span> ' .
					'<span class="post-title font-2">%title</span>',
			) );
		}
	endif;

	get_template_part( 'template-parts/single/single', 'related' );
	
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
	?>
</article><!-- #post-## -->
