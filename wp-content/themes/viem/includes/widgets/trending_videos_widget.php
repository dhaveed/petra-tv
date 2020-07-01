<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Trending_Videos extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'trending_latest-videos_widget';
		$this->widget_description	= esc_html__( 'Allows you to displays the most popular videos/trending videos by views or number comments', 'viem' );
		$this->widget_id			= 'DT_Trending_Videos';
		$this->widget_name        	= esc_html__( 'DT Trending/Popular Videos', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Most Popular', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-star',
				'label' => esc_html__( 'Icon Class', 'viem' )
			),
			'view_by' => array(
				'type'    => 'select',
				'std'     => 'views',
				'label'   => esc_html__( 'Order By', 'viem' ),
				'options' => array(
					'views'     => esc_html__( 'Total Views', 'viem' ),
					'comment' => esc_html__( 'Comments Count', 'viem' )
				)
			),
			'order' => array(
				'type'    => 'select',
				'std'     => 'DESC',
				'label'   => esc_html__( 'Order', 'viem' ),
				'options' => array(
					'ASC'     => esc_html__( 'ASC', 'viem' ),
					'DESC' => esc_html__( 'DESC', 'viem' )
				)
			),
			'limit'  => array(
				'type'  => 'number',
				'std'	=> '6',
				'label' => esc_html__( 'Number of videos to query', 'viem' )
			),
			'style' => array(
				'type'    => 'select',
				'std'     => 'slider',
				'label'   => esc_html__( 'Style', 'viem' ),
				'options' => array(
					'slider' => esc_html__( 'Slider', 'viem' ),
					'list'     => esc_html__( 'List', 'viem' ),
				)
			),
			'number'  => array(
				'type'  => 'number',
				'std'	=> '4',
				'label' => esc_html__( 'Number of videos to show (Apply for Slider)', 'viem' )
			),
			'template' => array(
				'type'    => 'select',
				'std'     => 'dark',
				'label'   => esc_html__( 'Template', 'viem' ),
				'options' => array(
					''     => esc_html__( 'White', 'viem' ),
					'dark' => esc_html__( 'Dark', 'viem' )
				)
			),
			'bg_color' => array(
				'type'    => 'text',
				'std'     => '',
				'label'   => esc_html__( 'Custom Background Color', 'viem' ),
			),
			'title_color' => array(
				'type'    => 'text',
				'std'     => '',
				'label'   => esc_html__( 'Custom Title Color', 'viem' ),
			),
		);

		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title    	= isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$icon 		= isset($instance['icon']) ? $instance['icon'] : $this->settings['icon']['std'];
		$view_by 	= isset($instance['view_by']) ? $instance['view_by'] : 'views' ;
		$limit 		= isset($instance['limit']) ? absint($instance['limit']) : 6 ;
		$number 	= isset($instance['number']) ? absint($instance['number']) : 4 ;
		$order 		= isset($instance['order']) ? $instance['order'] : 'DESC' ;
		$style   	= isset($instance['style']) ? $instance['style'] : $this->settings['style']['std'];
		$template   = isset($instance['template']) ? $instance['template'] : $this->settings['template']['std'];
		$bg_color   = isset($instance['bg_color']) ? $instance['bg_color'] : '';
		$title_color   = isset($instance['title_color']) ? $instance['title_color'] : '';
		
		echo wp_kses( $before_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		
		echo ($template) ? '<div class="viem-'.$template.'">' : '';
		
		if( $title ) {
			echo wp_kses( $before_title  . ( $icon != '' ? '<i class="'.esc_attr($icon).'"></i>' : '' ) . esc_html($title) . $after_title, array(
				'h3' => array(
					'class' => array()
				),
				'h4' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array()
				),
				'i' => array(
					'class' => array()
				),
			) );
		}
		
		?>
		<div class="viem-video_widget_wrap">
		<div class="viem-video_<?php echo esc_attr($style); ?>_wg">
		<?php
		$args = array(
			'post_type'      => 'viem_video',
			'posts_per_page' => $limit ,
			'order'          => $order,
			'post_status'    => 'publish'
		);
		// Order By
		if( $view_by == 'comment' ){
			$args['orderby'] = 'comment_count';
		} elseif ( $view_by == 'views' ) {
			$args['orderby'] = 'meta_value_num';
			$args['meta_key'] = 'post_views_count';
		}
		
		$popularvideos = new WP_Query($args);
		
		$vi = 0;
		$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
		
		if($popularvideos->have_posts()):
		?>
			<?php 
			if( $style == 'slider' ):
			?>
			<ul class="viem-carousel-slide owl-carousel viem-preload" data-autoplay="true" data-dots="0" data-nav="1" data-items="<?php echo esc_attr($number) ?>" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>" >
			<?php else: ?>
	        <ul>
	        <?php endif; ?>
	        
			<?php while($popularvideos->have_posts()): $popularvideos->the_post(); $vi++; $post_id = get_the_ID();?>
					<?php if( $vi === 1 && $style == 'list' ) : ?>
						<li <?php post_class('viem_video first'); ?>>
							<div class="entry-featured-wrap">
								<?php echo '<span class="count viem-main-color-bg">'.$vi.'</span>'?>
								
								<div class="entry-featured post-thumbnail">
								<?php
								$thumbnail_img = '';
								if( has_post_thumbnail($post_id) ){
									$thumbnail_img = get_the_post_thumbnail( get_the_ID(), $img_size );
								}elseif( viem_get_theme_option('videos-thumbImg', 'upload') == 'auto' ){
									if( class_exists('viem_posttype_video') ){
										$thumbnail_url = viem_posttype_video::get_thumbnail_auto();
										$thumbnail_img = '<img src="'.$thumbnail_url.'"/>';
									}
									if( viem_get_post_meta('video_type', get_the_ID()) == 'HTML5' ){
										$thumbnail_img = '<video class="viem_vp_videoPlayer"><source src="'.viem_get_post_meta('video_mp4', get_the_ID()).'" /></video>';
									}
								}
								?>
									<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title(get_post_thumbnail_id($post_id))) ?>">
										<?php echo viem_print_string($thumbnail_img); ?>
									</a>
								</div>
								<div class="entry-content">
									<?php $categories_list = get_the_term_list($post_id, 'video_cat', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
						        	<div class="post-category">
						        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
						        	</div>
						        	<h3 class="post-title">
						        		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
						        	</h3>
						        </div>
								<div class="entry-video-counter">
									<?php viem_video_like_counter(get_the_ID()); ?>
									<?php
									if( ($duration = viem_get_post_meta('video_duration', get_the_ID(), 0)) ):?>
										<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
									<?php endif;?>
								</div>
							</div>
				        </li>
					<?php else: ?>
				        <li <?php post_class('viem_video'); ?>>
				        	<div class="entry-featured-wrap">
					        		<div class="entry-featured post-thumbnail">
									<?php if(has_post_thumbnail()):
										?>
										
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title(get_post_thumbnail_id($post_id))) ?>">
												<?php echo get_the_post_thumbnail( get_the_ID(), $img_size ); ?>
												<?php echo ($style == 'list') ? '<span class="count viem_main_color">'.$vi.'</span>' : '';?>
											</a>
										<?php
										else:
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title(get_post_thumbnail_id($post_id))) ?>">
												<img src="<?php echo esc_url(viem_placeholder_img_src()); ?>" width="130" height="85" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" data-itemprop="image">
												<?php echo ($style == 'list') ? '<span class="count">'.$vi.'</span>' : ''; ?>
											</a>
									<?php endif; ?>
										<?php if( $style == 'list' ):?>
										<div class="entry-video-counter">
											<?php
											if( ($duration = viem_get_post_meta('video_duration', get_the_ID(), 0)) ):?>
												<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
											<?php endif;?>
										</div>
										<?php endif; ?>
									</div>
							        <div class="post-wrapper post-content entry-content">
							        	<?php if( $style == 'slider' ):?>
							        		<?php 
							        		$taxonomy_objects = get_object_taxonomies( get_post_type() );
							        		$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
							        		$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
								        	<div class="post-category">
								        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
								        	</div>
							        	<?php endif; ?>
							        	<h3 class="video-title">
							        		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
							        	</h3>
							        	<?php if( $style == 'list' ):?>
							        	<div class="entry-meta">
											<div class="entry-meta-content">
												<?php viem_video_views_count($post_id); ?>
												<?php viem_video_comments_count($post_id); ?>
											</div>
										</div><!-- .entry-meta -->
										<?php endif; ?>
							        </div>
						      </div>
				        </li>
			        <?php endif; ?>
	        <?php endwhile; ?>
	        </ul>
		<?php
		endif;
		?>
		</div>
		</div>
		<?php
		echo ($template) ? '</div>' : '';
		
		if( $bg_color != '' || $title_color != '' ){
		?>
		<style ="text/css">
			<?php echo ($bg_color) ? '#'.$widget_id.' , #'.$widget_id.' .viem-dark {background-color: '.$bg_color.';}' : '' ;?>
			<?php echo ($title_color) ? '#'.$widget_id.' .widget-title, #'.$widget_id.' ul .viem_video:not(.first) .entry-featured-wrap .post-content .video-title {color: '.$title_color.';}' : '' ;?>
			<?php echo ($title_color) ? '#'.$widget_id.' .widget-title, #'.$widget_id.' .widget-title i{border-color: '.$title_color.';}' : '' ;?>
		</style>
		<?php
		}
		
		echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		
		wp_reset_postdata();
		
		echo ob_get_clean();
	
	}
}

add_action('widgets_init', 'viem_DT_Trending_Videos_register_widget');
function viem_DT_Trending_Videos_register_widget(){
	return register_widget("viem_DT_Trending_Videos");
}