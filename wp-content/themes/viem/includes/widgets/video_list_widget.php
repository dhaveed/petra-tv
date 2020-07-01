<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Videos_List extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'videos_list_widget';
		$this->widget_description	= esc_html__( 'A List of your videos', 'viem' );
		$this->widget_id			= 'DT_Videos_list';
		$this->widget_name        	= esc_html__( 'DT Videos List', 'viem' );

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
			'orderby' => array(
				'type'    => 'select',
				'std'     => 'views',
				'label'   => esc_html__( 'Order By', 'viem' ),
				'options' => array(
					'views'     => esc_html__( 'Total Views', 'viem' ),
					'comment' => esc_html__( 'Comments Count', 'viem' ),
					'date'   => esc_html__( 'Recent First', 'viem' ),
					'oldest'  => esc_html__( 'Older First', 'viem' ),
					'alphabet'  => esc_html__( 'Title Alphabet', 'viem' ),
					'ralphabet'  => esc_html__( 'Title Reversed Alphabet', 'viem' ),
					'featured'   => esc_html__( 'Featured', 'viem' ),
					'rand'  => esc_html__( 'Random', 'viem' ),
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
				'std'	=> '4',
				'label' => esc_html__( 'Number of videos to query', 'viem' )
			),
			'template' => array(
				'type'    => 'select',
				'std'     => '',
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
		$orderby 	= isset($instance['orderby']) ? $instance['orderby'] : $this->settings['orderby']['std'];
		$limit 		= isset($instance['limit']) ? absint($instance['limit']) : $this->settings['limit']['std'];
		$order 		= isset($instance['order']) ? $instance['order'] : 'DESC' ;
		$template   = isset($instance['template']) ? $instance['template'] : $this->settings['template']['std'];
		$bg_color   = isset($instance['bg_color']) ? $instance['bg_color'] : '';
		$title_color   = isset($instance['title_color']) ? $instance['title_color'] : '';
		
		$style = 'list';
		
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
		
		switch ($orderby) {
			case 'comment':
				$orderby = 'comment_count';
				break;
			case 'date':
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
				$orderby = 'views';
				break;
		}
		
		$args = array(
			'post_type'      => 'viem_video',
			'orderby'         => "{$orderby}",
			'order'          => $order,
			'post_status'    => 'publish',
			'posts_per_page' => $limit ,
		);
		// Order By
		if( $orderby == 'featured' ){
			$args['meta_key'] = '_dt_featured';
			$args['meta_value'] = 'yes';
		} elseif ( $orderby == 'views' ) {
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
			<ul class="viem-carousel-slide owl-carousel viem-preload" data-autoplay="true" data-dots="0" data-nav="1" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>" >
			<?php else: ?>
	        <ul>
	        <?php endif; ?>
	        
			<?php while($popularvideos->have_posts()): $popularvideos->the_post(); $vi++; $post_id = get_the_ID();?>
					<?php if( $vi === 1 && $style == 'list' ) : ?>
						<li <?php post_class('viem_video first'); ?>>
							<div class="entry-featured-wrap">
								<?php echo '<span class="count viem-main-color-bg">'.$vi.'</span>'?>
								<div class="entry-featured post-thumbnail">
								<?php viem_post_image($img_size); ?>
								</div>
								
								<div class="entry-content">
									<?php 
									if( class_exists('viem_posttype_video') ):
										$categories_list = get_the_term_list($post_id, 'video_cat', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
							        	<div class="post-category">
							        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
							        	</div>
						        	<?php endif;?>
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
				        			<?php echo ($style == 'list') ? '<span class="count viem_main_color">'.$vi.'</span>' : '';?>
					        		<div class="entry-featured post-thumbnail">
									<?php 
										viem_post_image($img_size);
									?>
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

add_action('widgets_init', 'viem_DT_Videos_List_register_widget');
function viem_DT_Videos_List_register_widget(){
	return register_widget("viem_DT_Videos_List");
}