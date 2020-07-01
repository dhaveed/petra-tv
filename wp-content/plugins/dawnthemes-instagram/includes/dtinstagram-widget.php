<?php
/**
 * @package DTInstagram_Widget
 */

class DawnThemes_Instagram_Widget extends WP_Widget
{
	function __construct(){
		parent::__construct(
				'DawnThemes_Instagram_Widget',
				esc_html__('DT Instagram', 'dawnthemes-instagram'),
				array('description' => esc_html__('Display Instagram photos.', 'dawnthemes-instagram'))
			);
	}
	/*
	 * Create form option
	 */
	function form( $instance ) {
		$title 			= !empty($instance['title']) ? esc_attr( $instance['title'] ) : 'Instagram';			
		$username		= !empty($instance['username']) ? esc_attr($instance['username']) : 'DawnThemes';
		$images_number	= !empty($instance['images_number']) ? esc_attr($instance['images_number']) : 8;
		$size 			= !empty($instance['size']) ? esc_attr($instance['size']) : 'large';
		$refresh_hour	= !empty($instance['refresh_hour']) ? esc_attr($instance['refresh_hour']) : 5;
		?>
	
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Instagram Username:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'images_number' ); ?>"><?php _e( 'Number of Images to Show:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'images_number' ); ?>" name="<?php echo $this->get_field_name( 'images_number' ); ?>" type="text" value="<?php echo $images_number; ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id( 'size' ); ?>"><?php _e( 'Photo size', 'dawnthemes-instagram' ); ?>:</label>
            <select id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" class="widefat">
                <option value="thumbnail" <?php selected( 'thumbnail', $size ) ?>><?php _e( 'Thumbnail', 'dawnthemes-instagram' ); ?></option>
                <option value="small" <?php selected( 'small', $size ) ?>><?php _e( 'Small', 'dawnthemes-instagram' ); ?></option>
                <option value="large" <?php selected( 'large', $size ) ?>><?php _e( 'Large', 'dawnthemes-instagram' ); ?></option>
                <option value="original" <?php selected( 'original', $size ) ?>><?php _e( 'Original', 'dawnthemes-instagram' ); ?></option>
            </select>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id( 'refresh_hour' ); ?>"><?php _e( 'Check for new images on every (hours):' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'refresh_hour' ); ?>" name="<?php echo $this->get_field_name( 'refresh_hour' ); ?>" type="text" value="<?php echo $refresh_hour; ?>" />
		</p>

		<?php 
	}
	
	/*
	 * Save Widget form
	 */
	function update( $new_instance, $old_instance ) {
		$instance['title'] 			= sanitize_text_field( $new_instance['title'] );
		$instance['username'] 		= $new_instance['username'];
		$instance['images_number'] 	= $new_instance['images_number'];
		$instance['size'] = ( ( $new_instance['size'] == 'thumbnail' || $new_instance['size'] == 'large' || $new_instance['size'] == 'small' || $new_instance['size'] == 'original' ) ? $new_instance['size'] : 'large' );
		$instance['refresh_hour'] 	= $new_instance['refresh_hour'];
				
		return $instance;
	}
	
	/*
	 * Show Widget
	 */
	function widget( $args, $instance ) {
		ob_start();
		extract( $args );
		$title      	= apply_filters( 'widget_title', $instance['title'], $instance, 'Instagram');
		$username		= isset($instance['username']) ? esc_html($instance['username']) : 'DawnThemes' ;
		$images_number 	= isset($instance['images_number']) ? absint($instance['images_number']) : 8 ;
		$size 			= isset($instance['size']) ? $instance['size'] : 'large' ;
		$refresh_hour 	= isset($instance['refresh_hour']) ? absint($instance['refresh_hour']) : 5 ;
		
		if(!empty($username)){
			echo $before_widget;
			if ( $title )
				echo $before_title . $title . $after_title;
			?>
			<div class="dt-instagram__widget_wrap">
				<?php ;
				$images_data = dawnthemes_instagram($username,$images_number, $refresh_hour);
		
				if ( !is_wp_error($images_data) && ! empty( $images_data ) ) {
					?>
						<ul class="dt-instagram__list">
							<?php foreach ((array)$images_data as $item):?>
							<li class="dt-instagram__item">
								<a href="<?php echo esc_attr( $item['link'])?>" title="<?php echo esc_attr($item['description'])?>" target="_blank">
									<img src="<?php echo esc_attr($item[$size])?>"  alt="<?php echo esc_attr($item['description'])?>"/>
								</a>
							</li>
							<?php endforeach;?>
						</ul>
					<?php
				} else {
					echo '<div class="text-center" style="margin-bottom:30px">';
					if(is_wp_error($images_data)){
						echo implode($images_data->get_error_messages());
					}else{
						echo esc_html__( 'Instagram did not return any images.', 'dawnthemes-instagram' );
					}
					echo '</div>';
				};
				?>
			</div>
			<?php
			echo $after_widget;
			$content = ob_get_clean();
			echo $content;
		}
	}
}

function DawnThemes_Instagram_Widget_register_widgets(){
	register_widget( 'DawnThemes_Instagram_Widget' );
}
add_action( 'widgets_init', 'DawnThemes_Instagram_Widget_register_widgets' );
