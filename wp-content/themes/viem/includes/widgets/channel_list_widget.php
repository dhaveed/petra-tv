<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Channel_List extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'viem_channel_widget';
		$this->widget_description	= esc_html__( 'Channel List.', 'viem' );
		$this->widget_id			= 'DT_Channel_List';
		$this->widget_name        	= esc_html__( 'DT Channel List', 'viem' );
		
		$args = array(
			'post_type'      => 'viem_channel',
			'posts_per_page' => -1 ,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'ignore_sticky_posts' => true,
			'post_status'    => 'publish'
		);
		
		$channels = new WP_Query($args);
		$channel_select = array();
		if($channels->have_posts()){
			while ($channels->have_posts()){ $channels->the_post();
				$channel_select[get_the_ID()] = get_the_title();
			}
		}
		wp_reset_postdata();
		
		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Channel', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-video-camera',
				'label' => esc_html__( 'Icon Class', 'viem' )
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
			'channel_ids' => array(
				'type'  => 'select',
				'std'   => '',
				'multiple'=>true,
				'label'=>esc_html__('Channel','viem'),
				'desc' => esc_html__( 'Select channel', 'viem' ),
				'options' => $channel_select,
			),
		);

		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title    = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$icon 		= isset($instance['icon']) ? $instance['icon'] : $this->settings['icon']['std'];
		$template   = isset($instance['template']) ? $instance['template'] : '';
		$bg_color   = isset($instance['bg_color']) ? $instance['bg_color'] : '';
		$title_color   = isset($instance['title_color']) ? $instance['title_color'] : '';
		$channel_ids = isset($instance['channel_ids']) ? explode(',',$instance['channel_ids']) : array();
		
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
		<div class="viem-channel_list_wg">
		<?php
		$args = array(
			'post_type'	=> 'viem_channel',
			'post__in' 	=> $channel_ids ,
		);
		
		$query_channel = new WP_Query($args);
		
		
		if($query_channel->have_posts()):
		?>
	        <ul>
			<?php while($query_channel->have_posts()): $query_channel->the_post(); $post_id = get_the_ID();?>
				        <li <?php post_class('viem_channel'); ?>>
				        	<div class="entry-featured-wrap">
					        		<div class="entry-featured post-thumbnail">
									<?php if(has_post_thumbnail()):
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title()) ?>">
												<?php echo  get_the_post_thumbnail( $post_id,'thumbnail'); ?>
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
			<?php echo ($title_color) ? '#'.$widget_id.' .widget-title, #'.$widget_id.' ul .viem_channel(.first) .entry-featured-wrap .post-content .channel-title {color: '.$title_color.';}' : '' ;?>
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


add_action('widgets_init', 'viem_DT_Channel_List_register_widget');
function viem_DT_Channel_List_register_widget(){
	return register_widget("viem_DT_Channel_List");
}