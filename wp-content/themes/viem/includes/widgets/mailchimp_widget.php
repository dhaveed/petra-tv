<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Mailchimp_Widget extends DawnThemes_Widget {
	public function __construct(){
		$this->widget_cssclass    = 'widget-mailchimp';
		$this->widget_description = esc_html__( "Widget Mailchimp Subscribe.", 'viem' );
		$this->widget_id          = 'dt_widget_mailchimp';
		$this->widget_name        = esc_html__( 'DT Mailchimp Subscribe', 'viem' );
		$this->cached = false;
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'	=> esc_html__( 'Email Subscribe', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-paper-plane',
				'label' => esc_html__( 'Icon Class', 'viem' )
			),
			'info'  => array(
				'type'  => 'text',
				'std'	=> esc_html__( 'Info before Subscribe form', 'viem' ),
				'label' => esc_html__( 'Info before Subscribe form', 'viem' )
			),
			'template' => array(
				'type'    => 'select',
				'std'     => 'dark',
				'label'   => esc_html__( 'Template', 'viem' ),
				'options' => array(
					'dark' => esc_html__( 'Dark', 'viem' ),
					''     => esc_html__( 'White', 'viem' ),
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
		$title      = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$icon 		= isset($instance['icon']) ? $instance['icon'] : $this->settings['icon']['std'];
		$info 		= isset($instance['info']) ? $instance['info'] : $this->settings['info']['std'];
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
		<div class="widget-mailchimp-wrapper">
			<?php if(!empty($info)): ?>
			<div class="widget-mailchimp-info">
			<?php echo esc_html($info);?>
			</div>
			<?php endif;?>
			<?php viem_mailchimp_form(); ?>
		</div>
		<?php
		echo ($template) ? '</div>' : '';
		
		if( $bg_color != '' || $title_color != '' ){
			?>
		<style ="text/css">
			<?php echo ($bg_color) ? '#'.$widget_id.' , #'.$widget_id.' .viem-dark {background-color: '.$bg_color.';}' : '' ;?>
			<?php echo ($title_color) ? '#'.$widget_id.' .widget-title {color: '.$title_color.';}' : '' ;?>
		</style>
		<?php
		}
		
		echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		
		echo ob_get_clean();
	}
}

add_action('widgets_init', 'viem_DT_Mailchimp_Widget_register_widget');
function viem_DT_Mailchimp_Widget_register_widget(){
	return register_widget("viem_DT_Mailchimp_Widget");
}