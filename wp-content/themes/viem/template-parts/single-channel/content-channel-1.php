<?php
/**
 * The template part for displaying single channel fullwidth
 *
 * @package Dawn
 */

$view = isset($_GET['view']) ? $_GET['view'] : viem_get_post_meta('channel_type');
?>
<div id="primary" class="content-area main-wrap">
	<div id="content" class="main-content site-content dawn-single-post-channel viem-channel-tpl-1" role="main">
		<?php
			// Start the Loop.
			while ( have_posts() ) : the_post(); $post_id = get_the_ID();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php
					if( has_post_thumbnail() ):
		
					$channel_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', true);
					?>
					<div class="page-heading viem-featured-image-full" style="background-image:url(<?php echo esc_url( $channel_thumbnail[0] ) ?>);">
					<?php 
					else: ?>
					<div class="page-heading">
					<?php endif; ?>
						<div class="container">
							<div class="channel-header-container">
									<div class="viem-channel-owner">
										<?php
										if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture',  get_the_author_meta( 'ID' )) )):
										?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
												<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
											</a>
											<?php
										else:
											$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 150 );
											$avatar = get_the_author_meta('viem_user_avatar_default_bg', get_the_author_meta( 'ID' ));
											?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) );?>">
												<?php echo get_avatar( get_the_author_meta( 'user_email' ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
											</a>
											<?php
										endif;
										?>
									</div>
									<div class="entry-share">
										<?php 
										do_action('viem_channel_social_account');
										?>
									</div>
							</div>
							
						</div>
						<div class="inner-header-container">
							<div class="container">
								<header class="entry-header">
										<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
										
										<div class="viem-subscribe-renderer">
											<?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::channel_subscribe_button( get_the_ID() ); ?>
											<span class="subscribers-count">
												<?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::get_subscribers_count( get_the_ID() );?>
											</span>
										</div>
								</header>
								<div class="tabbed-header-renderer">
									<div class="channel-tab">
										<?php if( viem_get_post_meta('channel_type') == '' || viem_get_post_meta('channel_type') == 'manually'):?>
										<div class="channel-tab-item item-videos <?php echo ( $view == '' || $view == 'manually' || $view == 'videos' ) ? 'active' : ''; ?>">
											<a href="<?php echo esc_url(get_permalink());?>?view=videos"><?php esc_html_e('VIDEOS', 'viem') ?></a>
										</div>
										<?php elseif( viem_get_post_meta('channel_type') == 'youtube_channel' ):?>
										<div class="channel-tab-item item-youtube-channel <?php echo ( $view == 'youtube_channel' ) ? 'active' : ''; ?>">
											<a href="<?php echo esc_url(get_permalink());?>?view=youtube_channel"><?php esc_html_e('YouTube Channel', 'viem') ?></a>
										</div>
										<?php endif; ?>
										<div class="channel-tab-item item-playlists <?php echo ( $view == 'playlists' ) ? 'active' : ''; ?>">
											<a href="<?php echo esc_url(get_permalink());?>?view=playlists"><?php esc_html_e('PLAYLISTS', 'viem') ?></a>
										</div>
										<?php if ( comments_open() || get_comments_number() ) { ?>
										<div class="channel-tab-item item-discussion <?php echo ( $view == 'discussion' ) ? 'active' : ''; ?>">
											<a href="<?php echo esc_url(get_permalink());?>?view=discussion"><?php esc_html_e('DISCUSSION', 'viem') ?></a>
										</div>
										<?php } ?>
										<div class="channel-tab-item item-about <?php echo ( $view == 'about' ) ? 'active' : ''; ?>">
											<a href="<?php echo esc_url(get_permalink());?>?view=about"><?php esc_html_e('ABOUT', 'viem') ?></a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="post-content">
						<div class="entry-content">
							<div class="container">
								<div class="content-renderer">
								<?php
									switch ($view){
										case 'youtube_channel':
											$youtube_channel_id = viem_get_post_meta('youtube_channel_id', $post_id, '');
											if( empty($youtube_channel_id) ){
												echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("You need to enter the YouTube Channel ID", 'viem') .'</div></div>';
											}else{
												viem_dt_get_template( "template-parts/single-channel/content-youtube-channel.php", array( 'youtube_channel_id'=> $youtube_channel_id ) );
											}
											
											break;
										case 'playlists':
											viem_dt_get_template( "template-parts/single-channel/content-playlists.php", array( 'channel_id'=> $post_id ) );
											break;
										case 'discussion':
											// If comments are open or we have at least one comment, load up the comment template.
											if ( comments_open() || get_comments_number() ) {
												comments_template();
											}
											break;
										case 'about':
											
											the_content();
											wp_link_pages( array(
											'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'viem' ) . '</span>',
											'after'       => '</div>',
											'link_before' => '<span>',
											'link_after'  => '</span>',
											'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'viem' ) . ' </span>%',
											'separator'   => '<span class="screen-reader-text">, </span>',
											) );
											
											break;
										default: // show list videos
											//viem_dt_get_template( "template-parts/single-channel/content-videos.php", array( 'channel_id'=> $post_id ) );
											get_template_part('template-parts/single-channel/content-videos');
											break;
									}
								?>
								</div>
							</div>
						</div> <!-- .entry-content -->
					</div>
				</article><!-- #post-## -->
				<?php
			endwhile; // end of the loop.
		?>
	</div><!-- #content -->
</div><!-- #primary -->
