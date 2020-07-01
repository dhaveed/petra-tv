<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Latest_Videos extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'latest-videos_widget';
		$this->widget_description	= esc_html__( 'Your site’s most recent Videos.', 'viem' );
		$this->widget_id			= 'DT_Latest_Videos';
		$this->widget_name        	= esc_html__( 'DT Recent Videos', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Latest Videos', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-star',
				'label' => esc_html__( 'Icon Class', 'viem' )
			),
			'number'  => array(
				'type'  => 'number',
				'std'	=> '3',
				'label' => esc_html__( 'Number of videos to show:', 'viem' )
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
		$title    = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$icon 		= isset($instance['icon']) ? $instance['icon'] : $this->settings['icon']['std'];
		$number 	= isset($instance['number']) ? absint($instance['number']) : 3 ;
		$template   = isset($instance['template']) ? $instance['template'] : '';
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
		<div class="viem-video_list_wg">
		<?php
		$args = array(
			'post_type'      => 'viem_video',
			'posts_per_page' => $number ,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'ignore_sticky_posts' => true,
			'post_status'    => 'publish'
		);
		
		$latestvideos = new WP_Query($args);
		
		
		if($latestvideos->have_posts()):

			$img_size = viem_get_theme_option('video-image-size', 'thumbnail');
		?>
	        <ul>
			<?php while($latestvideos->have_posts()): $latestvideos->the_post(); $post_id = get_the_ID();?>
				        <li <?php post_class('viem_video'); ?>>
				        	<div class="entry-featured-wrap">
					        		<div class="entry-featured post-thumbnail">
									<?php viem_post_image($img_size); ?>
									</div>
							        <div class="post-wrapper post-content entry-content">
							        	<h3 class="video-title">
							        		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
							        	</h3>
							        	<div class="entry-meta">
											<div class="entry-meta-content">
												<?php viem_video_views_count($post_id); ?>
												<?php viem_video_comments_count($post_id); ?>
											</div>
										</div><!-- .entry-meta -->
							        </div>
						      </div>
				       </li>
	        <?php endwhile; ?>
	        </ul>
		<?php
		endif;
		?>
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

add_action('widgets_init', 'viem_DT_Latest_Videos_register_widget');
function viem_DT_Latest_Videos_register_widget(){
	return register_widget("viem_DT_Latest_Videos");
}