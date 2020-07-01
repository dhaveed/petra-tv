<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Latest_Movies extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass		= 'latest-movies_widget';
		$this->widget_description	= esc_html__( 'Your site’s most recent Movies.', 'viem' );
		$this->widget_id			= 'DT_Latest_Movies';
		$this->widget_name        	= esc_html__( 'DT Recent Movies', 'viem' );

		$this->settings				= array(
			'title'		=> array(
				'type'	=> 'text',
				'std'	=> esc_html__( 'Latest Movies', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'icon'  => array(
				'type'  => 'text',
				'std'	=>'fa fa-video-camera',
				'label' => esc_html__( 'Icon Class', 'viem' )
			),
			'number'  => array(
				'type'  => 'number',
				'std'	=> '4',
				'label' => esc_html__( 'Number of movies to show:', 'viem' )
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
		$number 	= isset($instance['number']) ? absint($instance['number']) : 4 ;
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
		<div class="viem-movie_list_wg">
		<?php
		$args = array(
			'post_type'      => 'viem_movie',
			'posts_per_page' => $number ,
			'order'          => 'DESC',
			'orderby'        => 'date',
			'ignore_sticky_posts' => true,
			'post_status'    => 'publish'
		);
		
		$latestmovies = new WP_Query($args);
		
		
		if($latestmovies->have_posts()):

			$img_size = viem_get_theme_option('movies-image-size', 'viem-movie-360x460');
		?>
	        <ul>
			<?php while($latestmovies->have_posts()): $latestmovies->the_post(); $post_id = get_the_ID();?>
				        <li <?php post_class('viem_movie'); ?>>
				        	<div class="entry-featured-wrap">
					        		<div class="entry-featured post-thumbnail">
									<?php if(has_post_thumbnail()):
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title(get_post_thumbnail_id($post_id))) ?>">
												<?php echo get_the_post_thumbnail( $post_id, $img_size ); ?>
											</a>
										<?php
										else:
										?>
											<a href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php echo esc_attr(get_the_title(get_post_thumbnail_id($post_id))) ?>">
												<img src="<?php echo esc_url(viem_placeholder_img_src()); ?>" width="130" height="85" title="<?php the_title_attribute(); ?>" alt="<?php the_title_attribute(); ?>" data-itemprop="image">
											</a>
									<?php endif; ?>
									</div>
							        <div class="post-wrapper post-content entry-content">
							        	<h3 class="movie-title">
							        		<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title() ?></a>
							        	</h3>
							        	<div class="entry-meta">
											<div class="entry-meta-content">
												<span><?php echo date_i18n('M d, Y', strtotime(viem_get_post_meta('movie_release_date')) );?></span>
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
			<?php echo ($title_color) ? '#'.$widget_id.' .widget-title, #'.$widget_id.' ul .viem_movie:not(.first) .entry-featured-wrap .post-content .movie-title {color: '.$title_color.';}' : '' ;?>
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

add_action('widgets_init', 'viem_DT_Latest_Movies_register_widget');
function viem_DT_Latest_Movies_register_widget(){
	return register_widget("viem_DT_Latest_Movies");
}