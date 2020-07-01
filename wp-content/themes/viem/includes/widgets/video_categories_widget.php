<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Video_Categories extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'video-categories_widget';
		$this->widget_description	= esc_html__( 'A list of video categories.', 'viem' );
		$this->widget_id			= 'DT_Video_Categories';
		$this->widget_name        	= esc_html__( 'DT Video Categories', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Categories', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-align-left',
				'label' => esc_html__( 'Icon Class', 'viem' )
			),
			'count' => array(
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => esc_html__( 'Show video counts', 'viem' ),
			),
			'hide_empty' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => esc_html__( 'Hide empty categories', 'viem' ),
			),
			'template' => array(
				'type'    => 'select',
				'std'     => '2',
				'label'   => esc_html__( 'Style', 'viem' ),
				'options' => array(
					'1'     => esc_html__( '1 Column', 'viem' ),
					'2' => esc_html__( '2 Columns', 'viem' )
				)
			),
		);

		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title    = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$icon 		= isset($instance['icon']) ? $instance['icon'] : $this->settings['icon']['std'];
		$count              = isset( $instance['count'] ) ? $instance['count'] : $this->settings['count']['std'];
		$hide_empty         = isset( $instance['hide_empty'] ) ? $instance['hide_empty'] : $this->settings['hide_empty']['std'];
		$template   = isset($instance['template']) ? $instance['template'] : '2';

		echo wp_kses( $before_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		
		echo ($template) ? '<div class="style-'.$template.'">' : '';
		
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
		
		$list_args          = array(
			'show_count'   => $count,
			'hierarchical' => 0,
			'taxonomy'     => 'video_cat',
			'hide_empty'   => $hide_empty,
			'depth'			=> 1,
		);
		
		echo '<ul class="video-categories">';
		$list_args['title_li'] = '';
		
		wp_list_categories( apply_filters( 'viem_video_categories_widget_args', $list_args, $instance ) );
		
		echo '</ul>';
		
		echo ($template) ? '</div>' : '';
		
		echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		
		echo ob_get_clean();
	}
}

add_action('widgets_init', 'viem_DT_Video_Categories_register_widget');
function viem_DT_Video_Categories_register_widget(){
	return register_widget("viem_DT_Video_Categories");
}