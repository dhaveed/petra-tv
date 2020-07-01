<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Posts extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass    = 'dt-posts__wg';
		$this->widget_description = esc_html__( "A list of Your site’s Posts with thumbnail.", 'viem' );
		$this->widget_id          = 'viem_DT_Posts_Widget';
		$this->widget_name        = esc_html__( 'DT Posts Thumnail', 'viem' );

		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'	=> esc_html__( 'Latest Posts', 'viem' ),
				'label' => esc_html__( 'Title', 'viem' )
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'date',
				'label' => esc_html__( 'Order by', 'viem' ),
				'options' => array(
					'date'   => esc_html__( 'Recent posts', 'viem' ),
					'featured'   => esc_html__( 'Featured posts', 'viem' ),
					'rand'  => esc_html__( 'Random', 'viem' ),
				)
			),
			'number'  => array(
				'type'  => 'number',
				'std'	=> '3',
				'label' => esc_html__( 'Number of posts to show:', 'viem' )
			),
			'show_date'  => array(
				'type'  => 'checkbox',
				'std'	=>'',
				'label' => esc_html__( 'Display post date?', 'viem' )
			),
		);
		parent::__construct();
	}

	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       = isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$orderby     	= isset($instance['orderby']) ? sanitize_title( $instance['orderby'] ) : $this->settings['orderby']['std'];
		$number = isset($instance['number']) ? absint($instance['number']) : $this->settings['number']['std'] ;
		$show_date = isset($instance['show_date']) ? $instance['show_date'] : '';

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

		switch ($orderby) {
			case 'date':
				$orderby = 'date';
				break;

			case 'featured':
				$orderby = 'meta_value';
				break;

			case 'rand':
				$orderby = 'rand';
				break;

			default:
				$orderby = 'date';
				break;
		}

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => $number ,
			'order'          => 'DESC',
			'orderby'        => "{$orderby}",
			'ignore_sticky_posts' => true,
			'post_status'    => 'publish'
				);

		if( $orderby == 'meta_value' ){
			$args['meta_key'] = '_dt_post_meta_featured_post';
			$args['meta_value'] = 'yes';
		}

		$posts = new WP_Query($args);

		if($posts->have_posts()):
		?>
	        <ul class="dt-recent-posts-wg">
			<?php while($posts->have_posts()): $posts->the_post(); ?>
		        <li class="post-item">
					<?php if(has_post_thumbnail()):
					?>
					<div class="post-img dt-effect1">
						<div class="entry-featured post-thumbnail">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						    	<?php echo get_the_post_thumbnail( get_the_ID(), 'thumbnail' ); ?>
							</a>
						</div>
					</div>
					<?php endif; ?>
			        <div class="post-content">
			        	<h3 class="post-title">
			        		<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title() ?></a>
			        	</h3>
			        	<div class="post-meta">
			        		<?php if( $show_date ) :?>
				        	<span class="post-date"><?php echo get_the_date();?></span>
				        	<?php endif; ?>
			        	</div>
			        </div>
		        </li>
	        <?php endwhile; ?>
	        </ul>
		<?php
		endif;
		
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

add_action('widgets_init', 'viem_DT_Posts_register_widget');
function viem_DT_Posts_register_widget(){
	return register_widget("viem_DT_Posts");
}