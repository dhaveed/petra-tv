<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_About_US extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'about-us__widget';
		$this->widget_description	= esc_html__( 'Display About US widget which contains information and social accounts.', 'viem' );
		$this->widget_id			= 'DT_AboutUS_Widget';
		$this->widget_name        	= esc_html__( 'DT About US', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'About us', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'content'		=> array(
				'type'	=> 'textarea',
				'std'	=> esc_html__( 'Viem is a smart and powerful video theme', 'viem' ),
				'label' => esc_html__( 'Content', 'viem' )
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
					'youtube'=>'Youtube',
					'vimeo'=>'Vimeo',
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
		);

		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$info = isset($instance['content']) ? wp_kses_post( stripslashes( $instance['content'] ) ) : $this->settings['content']['std'];
		$social = isset($instance['social']) ? explode(',',$instance['social']) : array();

		if(!empty($info)){
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
			echo '<div class="about-us-widget">';
			echo '<div class="about-us-info">'.$info.'</div>';
			echo '<div class="wg-social">';
			$hover = false;
			$soild_bg = true;
			$outlined = false;
			viem_dt_social($social,$hover,$soild_bg,$outlined);
			echo '</div>';
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

add_action('widgets_init', 'viem_DT_About_US_register_widget');
function viem_DT_About_US_register_widget(){
	return register_widget("viem_DT_About_US");
}
