<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Social_Widget extends DawnThemes_Widget {
	public function __construct(){
		$this->widget_cssclass    = 'social-widget';
		$this->widget_description = esc_html__( "Display Social Icon.", 'viem' );
		$this->widget_id          = 'viem_DT_Social_Widget';
		$this->widget_name        = esc_html__( 'DT Social', 'viem' );
		
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'	=>'',
				'label' => esc_html__( 'Title', 'viem' )
			),
			'social' => array(
					'type'  => 'select',
					'std'   => '',
					'multiple'=>true,
					'label'=>esc_html__('Social','viem'),
					'desc' => esc_html__( 'Select socials', 'viem' ),
					'options' => array(
						'facebook'=>'Facebook',
						'twitter'=>'Twitter',
						'google-plus'=>'Google Plus',
						'pinterest'=>'Pinterest',
						'linkedin'=>'Linkedin',
						'rss'=>'Rss',
						'instagram'=>'Instagram',
						'github'=>'Github',
						'behance'=>'Behance',
						'stack-exchange'=>'Stack Exchange',
						'tumblr'=>'Tumblr',
						'soundcloud'=>'SoundCloud',
						'dribbble'=>'Dribbble'
					),
			),
			'style' => array(
				'type'  => 'select',
				'std'   => '',
				'label' => esc_html__( 'Style', 'viem' ),
				'options' => array(
					'square' =>  esc_html__('Square', 'viem' ),
					'round' =>  esc_html__('Round', 'viem' ),
					'outlined' =>  esc_html__('Outlined', 'viem' ),
				)
			),
		);
		parent::__construct();
	}
	
	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$social = isset($instance['social']) ? explode(',',$instance['social']) : array();
		$style = isset($instance['style']) ? $instance['style'] : 'square';
		if(!empty($social)){
			echo wp_kses( $before_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
			if($title) {
				echo wp_kses( $before_title . esc_html($title) . $after_title, array(
					'h3' => array(
						'class' => array()
					),
					'h4' => array(
						'class' => array()
					),
					'span' => array(
						'class' => array()
					),
				) );
			}
			echo '<div class="social-widget-wrap social-widget-'.$style.'">';
			$hover = false;
			$soild_bg = true;
			$outlined = false;
			if($style == 'outlined'){
				$hover = true;
				$soild_bg = false;
				$outlined = true;
			}
			viem_dt_social($social,$hover,$soild_bg,$outlined);
			echo '</div>';
			echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		}

		echo ob_get_clean();
	}
	
}

add_action('widgets_init', 'viem_DT_Social_register_widget');
function viem_DT_Social_register_widget(){
	return register_widget("viem_DT_Social_Widget");
}