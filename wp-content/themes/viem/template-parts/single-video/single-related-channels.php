<?php
/**
 * The Template for displaying all related channels.
 *
 * @package Dawn
 */
?>
<?php
	
	$show_related_channels = viem_get_theme_option('show_related_channels','1');
			
	if($show_related_channels == '0') return;
	
	$channels_per_page = absint( viem_get_theme_option('related_channels_count', 2) );
	
	$args = array(
		'post_type' => "viem_channel",
		'post_status' => 'publish',
		'posts_per_page' => $channels_per_page,
		'orderby' => 'rand',
		'ignore_sticky_posts' => 1,
	);
	
	$related_items = new WP_Query($args);
	
	if(!$related_items->have_posts()) return;
	
?>
	<div class="related_channels">
		<div class="related_channels__wrapper">
			<div class="related-channels__heading">
				<div class="related-channels__title">
					<h5 class="dt-title"><i class="fa fa-video-camera" aria-hidden="true"></i><?php esc_html_e('Other Channels', 'viem');?></h5>
				</div>
			</div>
	      	<div class="related_content v-grid-list cols_<?php echo esc_attr($channels_per_page); ?>">
					<?php
					while ( $related_items->have_posts() ) : $related_items->the_post();
						?>
						<div class="related-channel-item v-grid-item">
							<div class="entry-featured-wrap">
					        		<div class="entry-featured post-thumbnail">
									<?php if(has_post_thumbnail()):
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>">
												<?php echo  get_the_post_thumbnail( get_the_ID(),'thumbnail'); ?>
											</a>
										<?php
										else:
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>">
												<img src="<?php echo esc_url(viem_placeholder_img_src()); ?>" width="90" height="90" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" data-itemprop="image">
											</a>
									<?php endif; ?>
									</div>
							        <div class="post-wrapper post-content entry-content">
							        	<h3 class="channel-title">
							        		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
							        	</h3>
							        	<div class="viem-subscribe-renderer">
											<?php if( class_exists('viem_posttype_channel') ) viem_posttype_channel::channel_subscribe_button( get_the_ID() ); ?>
										</div>
							        </div>
						      </div>
						</div>
						<?php 
					endwhile;
					?>
			</div>
		</div>
	</div>
<?php
wp_reset_postdata(); 
