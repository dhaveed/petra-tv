<?php
$output = array();
extract(shortcode_atts(array(
	'style'				=>'default',
	'title'				=>'',
	'icon_player'		=>'',
	'template'			=>'',
	'posts_to_show'		=>'4',
	'posts_per_page'	=>'8',
	'orderby'			=>'latest',
	'categories'		=>'',
	'exclude_categories'=>'',
	'img_size'			=> '',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

$sc_id = uniqid('viem_sc_');
$class 	= !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= ( $template ) ? ' viem-'.$template . ' ' : '';

$order = 'DESC';
switch ($orderby) {
	case 'latest':
		$orderby = 'date';
		break;
	case 'oldest':
		$orderby = 'date';
		$order = 'ASC';
		break;
	case 'alphabet':
		$orderby = 'title';
		$orderby = 'ASC';
		break;
	case 'ralphabet':
		$orderby = 'title';
		break;
	case 'rand':
		$orderby = 'rand';
		break;
	default:
		$orderby = 'date';
		break;
}

$args = array(
	'orderby'         => "{$orderby}",
	'order'           => "{$order}",
	'post_type'       => "viem_video",
	'posts_per_page'  => $posts_per_page,
);

if(!empty($categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'video_cat',
			'terms'    => explode(',',$categories),
			'field'    => 'slug',
			'operator' => 'IN'
	);
}
if(!empty($exclude_categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'video_cat',
			'terms'    => explode(',',$exclude_categories),
			'field'    => 'slug',
			'operator' => 'NOT IN'
	);
}
$r = new WP_Query($args);

if($r->have_posts()):
$data_arrows = 'true';
$fade = 'false';
$dots = ($style == 'default') ? 'true' : 'false';

if( $style == 'single' ){
	$posts_to_show = 1;
	$fade = 'true';
}
if( $img_size == '' || $img_size == 'default' ){
	$img_size = ($style == 'default' ? viem_get_theme_option('video-image-size', 'viem_750_490_crop') : 'large');
}

?>
<div class="viem-posts-slider wpb_content_element <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr($style); ?>">
	<?php if($title !=''):?>
	<div class="viem_sc_heading">
		<div class="viem_sc_title">
			<h5 class="viem-sc-title"><?php echo esc_html($title);?></h5>
		</div>
	</div>
	<?php endif; ?>
	<div class="viem_sc_content">
		<div class="viem_sc_wrap">
			<div id="<?php echo esc_attr($sc_id) ?>" class="viem-slick-slider viem-preload"  data-mode="<?php echo esc_attr($style);?>" data-visible="<?php echo esc_attr($posts_to_show)?>" data-scroll="1" data-infinite="true" data-autoplay="true" data-arrows="<?php echo esc_attr($data_arrows);?>" data-dots="<?php echo esc_attr($dots);?>" data-fade="<?php echo esc_attr($fade);?>">
				<?php
				switch ($style){
					case 'syncing':
						?>
						<div class="viem-slider-for_wrap">
							<div class="slider-for">
								<?php 
								while ($r->have_posts()): $r->the_post(); $post_id = get_the_ID();
								?>
									<div class="post-item-slide">
										<article class="post viem_video">
											<div class="entry-featured-wrap">
												<div class="entry-featured <?php echo get_post_format() == 'video' ? 'video-featured' : '' ?>">
													<a class="post-thumbnail-link" href="<?php esc_url(the_permalink()); ?>" aria-hidden="true">
														<?php
														$thumbnail_img = '';
														if( has_post_thumbnail($post_id) ){
															$thumbnail_img = get_the_post_thumbnail( $post_id, $img_size );
														}elseif( viem_get_theme_option('videos-thumbImg', 'upload') == 'auto' ){
															if( class_exists('viem_posttype_video') ){
																$thumbnail_url = viem_posttype_video::get_thumbnail_auto();
																$thumbnail_img = '<img src="'.$thumbnail_url.'"/>';
															}
															if( viem_get_post_meta('video_type', $post_id) == 'HTML5' ){
																$thumbnail_img = '<video class="viem_vp_videoPlayer"><source src="'.viem_get_post_meta('video_mp4', $post_id).'" /></video>';
															}
														}
														echo viem_print_string( $thumbnail_img ); ?>
														
														<?php if( class_exists('viem_posttype_video') )  viem_posttype_video::viem_video_badges_html(); ?>
													</a>
												</div>
												<div class="hentry-wrap">
													<?php 
													if( $icon_player ):
														$attachment_image =  wp_get_attachment_image_src($icon_player, 'full', true);
														$icon_player_url = $attachment_image[0];
													?>
													<div class="icon-player">
														<a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
															<img src="<?php echo esc_url($icon_player_url)?>">
														</a>
													</div>
													<?php endif; ?>
													<header class="post-header">
														<?php	
														the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
														?>
													</header><!-- .entry-header -->
													<?php 
													$taxonomy_objects = get_object_taxonomies( get_post_type() );
													$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
													$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
										        	<div class="post-category">
										        		<?php echo viem_print_string( $categories_list ); ?>
										        	</div>
												</div><!-- .entry-meta -->
												<div class="entry-video-counter">
													<?php viem_video_like_counter(get_the_ID()); ?>
													<?php
													if( ($duration = viem_get_post_meta('video_duration', get_the_ID(), 0)) ):?>
														<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
													<?php endif;?>
												</div>
											</div>
											  
										</article>
									</div>
								<?php endwhile;
								?>
							</div>
						</div>
						<div class="viem-slider-nav_wrap">
							<div class="slider-nav">
								<?php 
								while ($r->have_posts()): $r->the_post();  $post_id = get_the_ID();
								?>
									<div class="post-item-slide">
										<article class="post viem_video">
											<div class="entry-featured <?php echo get_post_format() == 'video' ? 'video-featured' : '' ?>">
													<?php
													$thumbnail_img = '';
													if( has_post_thumbnail($post_id) ){
														$thumbnail_img = get_the_post_thumbnail( $post_id, $img_size );
													}elseif( viem_get_theme_option('videos-thumbImg', 'upload') == 'auto' ){
														if( class_exists('viem_posttype_video') ){
															$thumbnail_url = viem_posttype_video::get_thumbnail_auto();
															$thumbnail_img = '<img src="'.$thumbnail_url.'"/>';
														}
														if( viem_get_post_meta('video_type', $post_id) == 'HTML5' ){
															$thumbnail_img = '<video class="viem_vp_videoPlayer"><source src="'.viem_get_post_meta('video_mp4', $post_id).'" /></video>';
														}
													}
													echo viem_print_string( $thumbnail_img ); ?>
											</div>
											<div class="hentry-wrap">
												<?php 
												$taxonomy_objects = get_object_taxonomies( get_post_type() );
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
											</div><!-- .entry-meta -->
											  
										</article>
									</div>
								<?php endwhile;
								?>
							</div>
						</div>
						<?php
						break;
					case 'single':
						while ($r->have_posts()): $r->the_post(); $post_id = get_the_ID();
						?>
							<div class="item">
								<a class="post-link" href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"></a>
								<?php 
								$thumbnail = esc_url(viem_placeholder_img_src());
								if (has_post_thumbnail()){
									$post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full', true);
									$thumbnail = $post_thumbnail[0];
								}elseif( viem_get_theme_option('videos-thumbImg', 'upload') == 'auto' ){
									if( class_exists('viem_posttype_video') ){
										$thumbnail = viem_posttype_video::get_thumbnail_auto();
									}
								}
								?>
								<div class="item-thumbnail" style="background-image: url(<?php echo esc_url($thumbnail) ?>)"></div>
								<?php if( class_exists('viem_posttype_video') ) viem_posttype_video::viem_video_badges_html(); ?>
								<div class="item-content">
									<div class="icon-player">
										<?php $icon_player_url = viem_get_theme_option('video_play_btn', get_template_directory_uri() . '/assets/images/video-player/playButtonPoster.png');?>
											<img src="<?php echo esc_url($icon_player_url)?>">
									</div>
									<header class="post-header">
										<?php	
										the_title( '<h2 class="post-title" data-itemprop="name">', '</h2>' );
										?>
									</header><!-- .entry-header -->
									<?php 
									$taxonomy_objects = get_object_taxonomies( get_post_type() );
									$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
									$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
						        	<div class="post-category">
						        		<?php echo viem_print_string( $categories_list ); ?>
						        	</div>
								</div>
							</div>
						<?php endwhile;
						break;
					default:
						while ($r->have_posts()): $r->the_post(); $post_id = get_the_ID();
						?>
							<div class="post-item-slide">
								<article class="viem_video post">
									<div class="entry-featured-wrap">
										<?php 
											viem_post_image($img_size, 'grid');
										?>
									</div>
									<div class="hentry-wrap">
										<?php 
										$taxonomy_objects = get_object_taxonomies( get_post_type() );
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
									</div><!-- .entry-meta -->
									  
								</article>
							</div>
						<?php endwhile;
						break;
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata();
?>