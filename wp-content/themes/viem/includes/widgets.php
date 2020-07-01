<?php
class DawnThemes_Widget extends WP_Widget {
	public $widget_cssclass;
	public $widget_description;
	public $widget_id;
	public $widget_name;
	public $settings;
	/**
	 * Constructor
	 */
	public function __construct() {
	
		$widget_ops = array(
				'classname'   => $this->widget_cssclass,
				'description' => $this->widget_description
		);
		
		parent::__construct(
			$this->widget_id,
			$this->widget_name,
			$widget_ops
		);
	}
	
	/**
	 * update function.
	 *
	 * @see WP_Widget->update
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
	
		$instance = $old_instance;
	
		if ( ! $this->settings ) {
			return $instance;
		}
	
		foreach ( $this->settings as $key => $setting ) {
			
			if(isset($setting['multiple'])):
				$instance[ $key ] = implode ( ',', $new_instance [$key] );
			else:
				if ( isset( $new_instance[ $key ] ) ) {
					$instance[ $key ] = sanitize_text_field( $new_instance[ $key ] );
				} elseif ( 'checkbox' === $setting['type'] ) {
					$instance[ $key ] = 0;
				}
			endif;
		}
		if($this->cached){
			$this->flush_widget_cache();
		}
	
		return $instance;
	}
	
	/**
	 * form function.
	 *
	 * @see WP_Widget->form
	 * @param array $instance
	 */
	public function form( $instance ) {
	
		if ( ! $this->settings ) {
			return;
		}
		foreach ( $this->settings as $key => $setting ) {
			$setting['std']		= isset($setting['std']) ? $setting['std'] : '';
			$value   			= isset( $instance[ $key ] ) ? $instance[ $key ] : $setting['std'];
			$setting['desc'] 	= isset($setting['desc']) ? '<span class="description">'.$setting['desc'].'</span>' : '';
			
			echo '<p>';
			switch ( $setting['type'] ) {
				case "text" :
				?>
					<label for="<?php echo esc_attr($this->get_field_id( $key )); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
					<?php
				break;
				
				case "textarea" :
					?>
					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<textarea class="widefat" rows="10" cols="20" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>"><?php echo esc_textarea($value); ?></textarea>
					<?php
				break;
				
				case "number" :
					?>
					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="number" step="<?php echo esc_attr( $setting['step'] ); ?>" min="<?php echo esc_attr( $setting['min'] ); ?>" max="<?php echo esc_attr( $setting['max'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
					<?php
				break;
				
				case "select" :
					if(isset($setting['multiple'])):
					$value = explode(',', $value);
					endif;
					?>
					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" <?php if(isset($setting['multiple'])):?> multiple="multiple"<?php endif;?> name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?><?php if(isset($setting['multiple'])):?>[]<?php endif;?>">
						<?php foreach ( $setting['options'] as $option_key => $option_value ) : ?>
							<option value="<?php echo esc_attr( $option_key ); ?>" <?php if(isset($setting['multiple'])): selected( in_array ( $option_key, $value ) , true ); else: selected( $option_key, $value ); endif; ?>><?php echo esc_html( $option_value ); ?></option>
						<?php endforeach; ?>
					</select>
					<?php
				break;
	
				case "checkbox" :
					?>
					<input id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="checkbox" value="1" <?php checked( $value, 1 ); ?> />
					<label for="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>"><?php echo esc_html( $setting['label'] ); ?></label>
					<?php
				break;
				case "hidden" :
					?>
					<input id="<?php echo esc_attr( $this->get_field_id( $key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $key ) ); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" />
					<?php
				break;
			}
			echo esc_html( $setting['desc'] );
			echo '</p>';
		}
	}
}


require_once get_template_directory() . '/includes/widgets/about_us_widget.php';
require_once get_template_directory() . '/includes/widgets/facebook_widget.php';
require_once get_template_directory() . '/includes/widgets/post_widget.php';
require_once get_template_directory() . '/includes/widgets/social_widget.php';
require_once get_template_directory() . '/includes/widgets/tweets_widget.php';
require_once get_template_directory() . '/includes/widgets/mailchimp_widget.php';
require_once get_template_directory() . '/includes/widgets/video_categories_widget.php';
require_once get_template_directory() . '/includes/widgets/latest_videos_widget.php';
require_once get_template_directory() . '/includes/widgets/video_slider_widget.php';
require_once get_template_directory() . '/includes/widgets/video_list_widget.php';
require_once get_template_directory() . '/includes/widgets/channel_list_widget.php';
require_once get_template_directory() . '/includes/widgets/latest_movies_widget.php';
