<?php
/**
 * The template part for displaying single video player fullwidth
 *
 * @package Dawn
 */
$single_hidden_content = viem_get_theme_option('single_hidden_content_video', '0');
$single_show_date = viem_get_theme_option('single_video_show_date', '1');
$single_show_category = viem_get_theme_option('single_video_show_category', '0');
$single_show_author = viem_get_theme_option('single_video_show_author', '1');
$single_show_tag = viem_get_theme_option('single_video_show_tag', '1');
$single_show_postnav = viem_get_theme_option('single_video_show_postnav', '1');
$single_show_authorbio = viem_get_theme_option('single_video_show_authorbio', '0');
$comments_type	= viem_get_theme_option('comments_type', 'wp');
$like = 0;
?>
<div class="row">
	<div id="primary" class="content-area <?php echo esc_attr(viem_get_main_class($layout)) ?>">
		<div id="content" class="main-content site-content dawn-single-post-video viem-video-tpl-1" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post(); $post_id = get_the_ID();
					// Set post view count
					viem_set_post_views(get_the_ID());
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php
						$is_ListView = ( isset($_GET['list']) && !empty($_GET['list']) ) ? $_GET['list'] : '';
						$is_series = ( isset($_GET['series']) && !empty($_GET['series']) ) ? $_GET['series'] : '';
						if( $is_ListView == '' && $is_series == ''):
							?>
							<header class="entry-header">
								<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
								
								<div class="viem-video-owner">
									<?php
									if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture',  get_the_author_meta( 'ID' )) )):
										?>
										<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
											<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
										</a>
										<?php
									else:
										$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 100 );
										$avatar = get_the_author_meta('viem_user_avatar_default_bg', get_the_author_meta( 'ID' ));
										?>
										<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
											<?php echo get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
										</a>
										<?php
									endif;
									?>
									<div class="video-owner-info-renderer">
										<div class="video-owner-title">
											<h5 class="author-title">
												<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>"><?php echo get_the_author(); ?></a>
											</h5>
										</div>
										<div class="video-owner-number">
											<span><?php echo count_user_posts(get_the_author_meta( 'ID' ), 'viem_video') . ' ' . esc_html__('Videos', 'viem'); ?></span>
										</div>
									</div>
									<div class="video-owner-entry-media">
										<?php viem_video_like_count($post_id); ?>
										<?php viem_video_views_count($post_id); ?>
									</div>
								</div>
								
								<div class="post-meta">
									<div class="entry-meta">
										<?php
										if ( $single_show_date == 1)
											viem_dt_posted_on();
										?>
										<?php
										if ( $comments_type === 'wp' && comments_open() && get_comments_number() ) :
										?>
											<span class="comments-link"><i class="fa fa-comments"></i><?php comments_popup_link( esc_html__( '0 Comment', 'viem' ), esc_html__( '1 Comment', 'viem' ), esc_html__( '% Comments', 'viem' ) ); ?></span>
										<?php
										endif;
										?>
										
										<?php if ( $single_show_category == 1 && ($categories_list = get_the_term_list($post_id, 'video_cat', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' ))) && viem_dt_categorized_blog() ) : ?>
										<span class="post-category">
											<i class="fa fa-folder-open"></i>
											<span class="cat-title screen-reader-text"><?php echo esc_html__( 'Category:', 'viem' );?></span>
											<span class="cat-links"><?php echo viem_print_string( $categories_list ); ?></span>
										</span>
										<?php
										endif;
										?>
										<?php edit_post_link( esc_html__( ' Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
									</div><!-- .entry-meta -->
									<div class="entry-media">
										<?php viem_video_like_count($post_id); ?>
										<?php viem_video_views_count($post_id); ?>
									</div>
								</div>
							</header><!-- .entry-header -->
						<?php else:?>
								<div class="post-meta">
									<div class="entry-meta">
										<?php
										if ( $single_show_date == 1)
											viem_dt_posted_on();
										?>
										<?php
										if ( $comments_type === 'wp' && comments_open() && get_comments_number() ) :
										?>
											<span class="comments-link"><i class="fa fa-comments"></i><?php comments_popup_link( esc_html__( '0 Comment', 'viem' ), esc_html__( '1 Comment', 'viem' ), esc_html__( '% Comments', 'viem' ) ); ?></span>
										<?php
										endif;
										?>
										
										<?php if ( $single_show_category == 1 && ($categories_list = get_the_term_list($post_id, 'video_cat', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' ))) && viem_dt_categorized_blog() ) : ?>
										<span class="post-category">
											<i class="fa fa-folder-open"></i>
											<span class="cat-title screen-reader-text"><?php echo esc_html__( 'Category:', 'viem' );?></span>
											<span class="cat-links"><?php echo viem_print_string( $categories_list ); ?></span>
										</span>
										<?php
										endif;
										?>
										<?php edit_post_link( esc_html__( ' Edit', 'viem' ), '<span class="edit-link">', '</span>' ); ?>
									</div><!-- .entry-meta -->
									<div class="entry-media">
										<?php viem_video_like_count($post_id); ?>
										<?php viem_video_views_count($post_id); ?>
									</div>
								</div>
						<?php endif; ?>
						<?php do_action('viem_before_single_post_content'); ?>
						<div class="post-content <?php echo ( $single_hidden_content == 1 ) ? 'hidden-content' : ''; ?>">
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
							<footer class="entry-footer">
								<?php
								if($single_show_tag == '1'):
									$tags_list = get_the_term_list($post_id, 'video_tag', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' ));
									if ( $tags_list ) {
										printf( '<div class="tags-list"><i class="fa fa-tags"></i> <span class="tag-title">%1$s </span><span class="tag-links">%2$s</span></div>',
											_x( 'Tags:', 'Used before tag names.', 'viem' ),
											$tags_list
										);
									}
								endif;
								?>
							</footer>
						</div>
						<?php do_action('viem_after_single_post_content'); ?>
					</article><!-- #post-## -->
					<?php
					if ( $single_show_authorbio == 1 && get_the_author_meta('description')!='' ) :?>
					<div class="author-info ">
						<?php
						$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 100 );
						$avatar = get_the_author_meta('viem_user_avatar_default_bg', get_the_author_meta( 'ID' ));
						?>
						<div class="author-avatar">
							<?php
							if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture', get_the_author_meta( 'ID' ) ) )): ?>
								<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
					 		<?php
					 		else:?>
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
							<?php endif; ?>
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
					
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
					
					if( $single_show_postnav == '1' ){
						viem_dt_post_nav();
					}

					get_template_part( 'template-parts/single-video/single', 'related' );
					get_template_part( 'template-parts/single-video/single', 'related-channels' );
					
				endwhile; // end of the loop.
			?>
		</div><!-- #content -->
</div><!-- #primary -->
<?php do_action('viem_dt_left_sidebar');?>
<?php do_action('viem_dt_right_sidebar'); ?>
</div><!-- .row -->
