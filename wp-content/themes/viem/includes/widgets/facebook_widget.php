<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_Facebook_Widget extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'dt-facebook__widget';
		$this->widget_description	= esc_html__( 'Display Facebook fanpage like box.', 'viem' );
		$this->widget_id			= 'viem_Facebook_Widget';
		$this->widget_name        	= esc_html__( 'DT Facebook', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Facebook Fanpage', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'fanpage'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'DawnThemes', 'viem' ),
				'label' => esc_html__( 'Fanpage Name', 'viem' )
			),
			'width'		=> array(
				'type'	=> 'text',
				'std'	=> '370',
				'label' => esc_html__( 'Width (pixel)', 'viem' )
			),
		);

		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title      	= isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$fanpage		= !empty($instance['fanpage']) ? esc_html($instance['fanpage']) : 'DawnThemes' ;
		$width			= !empty($instance['width']) ? absint($instance['width']) : 370 ;

		if(!empty($fanpage)){
			echo wp_kses( $before_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
			
			if( $title ) {
				echo wp_kses( $before_title  . esc_html($title) . $after_title, array(
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
			<div class="dt-facebook__widget_wrap">
				<div class="dt-facebook__content">
					<div id="fb-root"></div>
					<script>
						(function(d, s, id) {
							var js, fjs = d.getElementsByTagName(s)[0];
							if (d.getElementById(id)) return;
							js = d.createElement(s); js.id = id;
							js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
							fjs.parentNode.insertBefore(js, fjs);
						}(document, 'script', 'facebook-jssdk'));
					</script>
					<div class="fb-page" 
					data-href="https://www.facebook.com/<?php echo esc_html($fanpage); ?>"
					data-width="<?php echo absint($width);?>"
					data-height="215"
					data-tabs="timeline" 
					data-small-header="false" 
					data-adapt-container-width="true" 
					data-hide-cover="false" 
					data-show-facepile="true" 
					data-show-posts="false">
						<div class="fb-xfbml-parse-ignore">
							<blockquote cite="https://www.facebook.com/<?php echo esc_html($fanpage); ?>">
								<a href="https://www.facebook.com/<?php echo esc_html($fanpage); ?>"><?php echo esc_html($fanpage); ?></a>
							</blockquote>
						</div>
					</div>
				</div>
			</div>
			<?php
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

add_action('widgets_init', 'viem_Facebook_Widget_register_widget');
function viem_Facebook_Widget_register_widget(){
	return register_widget("viem_Facebook_Widget");
}