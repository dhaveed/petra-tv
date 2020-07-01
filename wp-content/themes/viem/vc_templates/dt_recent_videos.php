<?php
$output = '';
extract(shortcode_atts(array(
	'title'				=>'',
	'style'      		=>'grid',
	'template'			=>'',
	'categories'		=>'',
	'exclude_categories'=>'',
	'columns'			=>3,
	'tablet_columns'	=>3,
	'mobile_columns'	=>2,
	'posts_per_page'	=>4,
	'el_class'			=>'',
	'css'			=>'',
), $atts));

$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= ( $template ) ? ' viem-'.$template . ' ' : '';

$posts_per_page = ( $style == 'grid-v2' ) ? 4 : $posts_per_page;
$posts_per_page = ( $style == 'grid-v1' || $style == 'grid-v6' ) ? 5 : $posts_per_page;

$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');

$args = array(
	'orderby'         => "date",
	'order'           => "DESC",
	'post_type'       => "viem_video",
	'posts_per_page'  => $posts_per_page
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

$p = new WP_Query($args);
$i = 0;

if($p->have_posts()):
?>
<div id="<?php echo esc_attr($sc_id);?>" class="viem-sc-recent-videos wpb_content_element  <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr( $style );?>">
	<?php if(!empty($title)): ?>
	<div class="viem_sc_heading">
		<h3 class="viem-sc-title"><span><?php echo esc_html($title);?></span></h3>
	</div>
	<?php endif;?>
	<div class="viem-sc-content">
		<div class="<?php echo 'posts-'.$style; ?> <?php echo ($style == 'grid') ? 'v-grid-list viem-videos cols_'.$columns.' cols_tablet_'.$tablet_columns.' cols_mobile_'.$mobile_columns : '';?>">
		<?php
		switch ($style){
			case 'list':
				break;
			case 'grid-v1':
				while ($p->have_posts()): $p->the_post(); $i++;
					$post_class = 'post-grid-item-'.$i;
					if( $p->post_count < 5 ) return esc_html__('Require 5 posts to display.', 'viem');
					$post_id = get_the_ID();
					$custom_img_size =  $img_size;
					
					if( $i == 1){
						$custom_img_size = 'large';
						echo '<div class="item-grid-feature">';
					}elseif ($i == 2){
						echo '<div class="item-grid-group"><div class="group-content">';
					}
					?>
					<div class="<?php echo esc_attr($post_class);?>">
						<article class="post viem_video">
							<div class="entry-featured-wrap">
								<div class="entry-featured <?php echo get_post_format() == 'video' ? 'video-featured' : '' ?>">
									<a class="post-thumbnail-link" href="<?php esc_url(the_permalink()); ?>" aria-hidden="true">
										<?php
										$thumbnail_img = '';
										if( has_post_thumbnail($post_id) ){
											$thumbnail_img = get_the_post_thumbnail( $post_id, $custom_img_size );
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
										<?php if( class_exists('viem_posttype_video') ) viem_posttype_video::viem_video_badges_html(); ?>
									</a>
								</div>
								<div class="hentry-wrap">
									<?php if( $i == 1 && (($icon_player_url = viem_get_theme_option('video_play_btn', get_template_directory_uri() . '/assets/images/video-player/playButtonPoster.png')) != '') ):?>
									<div class="icon-player">
										<a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
											<img src="<?php echo esc_url($icon_player_url)?>">
										</a>
									</div>
									<header class="post-header">
										<?php	
										the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
										?>
									</header><!-- .entry-header -->
									<?php endif; ?>
									<?php 
									$taxonomy_objects = get_object_taxonomies( get_post_type() );
									$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
									$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
						        	<div class="post-category">
						        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
						        	</div>
						        	<?php if( $i != 1 ):?>
						        	<header class="post-header">
										<?php	
										the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
										?>
									</header><!-- .entry-header -->
						        	<?php endif; ?>
								</div><!-- .entry-meta -->
								<?php if( $i == 1 ){?>
								<div class="entry-video-counter">
									<?php viem_video_like_counter(get_the_ID()); ?>
									<?php
									if( ($duration = viem_get_post_meta('video_duration', get_the_ID(), 0)) ){?>
										<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
									<?php }?>
								</div>
								<?php } ?>
							</div>
						</article>
					</div>
					<?php 
					if( $i == 1){
						echo '</div>';
					}
					?>
					<?php 
					if($i == 5){
						echo '</div></div>';
					}
					?>
			<?php endwhile;
				break;
				
			case 'grid-v2':
				echo '<div class="posts-grid-wrap clearfix">';
					while ($p->have_posts()): $p->the_post(); $i++;
						if( $p->post_count < 4 ) return esc_html__('Require 4 posts to display.', 'viem');
						$post_id = get_the_ID();
						
						if( $i == 1 || $i == 4 ){
							echo '<div class="item-grid-feature">';
						}elseif ($i == 2){
							echo '<div class="item-grid-group clearfix">';
						}
						?>
							<div class="post-item-grid">
								<div class="post-grid-item-wrap">
									<div class="entry-featured-wrap">
										<?php viem_post_image('large'); ?>
									</div>
									<?php 
									$taxonomy_objects = get_object_taxonomies( 'viem_video' );
									$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
									$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ' ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
						        	<div class="post-category">
						        		<?php echo viem_print_string( $categories_list ); ?>
						        	</div>
									<div class="post-wrapper post-content entry-content">
										<header class="post-header">
										<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
										</header>
									</div>
									  
								</div>
							</div>
						<?php 
						if( $i == 1 || $i == 4 || $i == 3){
							echo '</div>';
						}
						?>
					<?php endwhile;
				echo '</div>';
				break;
			case 'grid-v6':
				echo '<div class="posts-grid-wrap clearfix">';
					
					while ($p->have_posts()): $p->the_post(); $i++;
						if( $p->post_count < 5 ) return esc_html__('Require 5 posts to display.', 'viem');
						$post_id = get_the_ID();
						
						$img_size = ($i == 3) ? 'large' : $img_size;
						
						if( $i == 1 || $i == 4 ){
							echo '<div class="item-grid-group clearfix">';
						}elseif ($i == 3){
							echo '<div class="item-grid-feature">';
						}
						?>
							<div class="post-item-grid">
								<div class="post-grid-item-wrap">
									<a class="post-link" href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"></a>
									<?php viem_post_image($img_size); ?>
									<div class="post-wrapper post-content entry-content">
										<?php if( $i == 3 ):?>
										<div class="icon-player">
											<?php $icon_player_url = apply_filters( 'viem_recent_videos_icon_grid_v6', viem_get_theme_option('video_play_btn', get_template_directory_uri() . '/assets/images/video-player/playButtonPoster.png') );?>
												<img src="<?php echo esc_url($icon_player_url)?>">
										</div>
										<?php endif; ?>
										<?php
										ob_start();
										$category_html = '';
										$taxonomy_objects = get_object_taxonomies( get_post_type() );
										$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
										$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
							        	<div class="post-category">
							        		<?php echo viem_print_string( $categories_list ); ?>
							        	</div>
							        	<?php 
							        	$category_html = ob_get_clean();
							        	if( $i != 3 ) echo viem_print_string( $category_html );
							        	?>
										<header class="post-header">
										<?php the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
										</header>
										<?php if( $i == 3 ) echo viem_print_string( $category_html ); ?>
									</div>
									  
								</div>
							</div>
						<?php 
						if( $i == 2 || $i == 3 || $i == 5){
							echo '</div>';
						}
						?>
					<?php endwhile;
				echo '</div>';
				break;
			default: // grid
				$post_col = 'v-grid-item';
				while ($p->have_posts()): $p->the_post();
				?>
					<div class="<?php echo esc_attr($post_col);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
						);
						viem_dt_get_template( 'item-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
					<?php endwhile; ?>
				<?php
			break;
		}
		?>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata();
?>