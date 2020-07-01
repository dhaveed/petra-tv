<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Post_Slider extends DawnThemes_Widget{
	public function __construct(){
		$this->widget_cssclass    = 'dt-post-slider-wg';
		$this->widget_description = esc_html__( "Your site’s most recent Posts.", 'viem' );
		$this->widget_id          = 'viem_DT_Post_Slider_Widget';
		$this->widget_name        = esc_html__( 'DT Post Slider', 'viem' );
		
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
						'date'   => esc_html__( 'Recent First', 'viem' ),
						'oldest'  => esc_html__( 'Older First', 'viem' ),
						'alphabet'  => esc_html__( 'Title Alphabet', 'viem' ),
						'ralphabet'  => esc_html__( 'Title Reversed Alphabet', 'viem' ),
						'rand'  => esc_html__( 'Random', 'viem' ),
				)
			),
			'posts_per_page' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 5,
				'label' => esc_html__( 'Number posts to query', 'viem' )
			),
		);
		parent::__construct();
	}
	
	public function widget($args, $instance){
		ob_start();
		extract( $args );
		$title       	= isset($instance['title']) ? $instance['title'] : $this->settings['title']['std'];
		$orderby     	= isset($instance['orderby']) ? sanitize_title( $instance['orderby'] ) : $this->settings['orderby']['std'];
		$posts_per_page = absint( $instance['posts_per_page'] );
		
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
		
			case 'oldest':
				$orderby = 'date';
				$order = 'ASC';
				break;
		
			case 'alphabet':
				$orderby = 'title';
				$orderby = 'ASC';
				break;
		
			case 'ralphabet':
				$orderby = 'title';
				break;
				
			case 'rand':
				$orderby = 'rand';
				break;
		
			default:
				$orderby = 'date';
				break;
		}
		
		$args = array(
			'orderby'         => "{$orderby}",
			'order'           => "DESC",
			'post_type'       => "post",
			'posts_per_page'  => $posts_per_page,
		);
		
		if(is_single()){
			$args['post__not_in'] = array(get_the_ID());
		}
		
		$p = new WP_Query($args);
		
		if($p->have_posts()):
			?>
		<div class="dt-posts-slider viem-preload single_mode" data-mode="single_mode" data-visible="1" data-scroll="1" data-infinite="1" data-autoplay="1" data-arrows="1" data-dots="false">
			<div class="dt-posts-slider__wrap">
		        <div class="posts-slider single_mode">
				<?php while($p->have_posts()): $p->the_post(); ?>
			        <div class="post-item-slide">
						<article id="post-<?php the_ID(); ?>" class="post">
							<?php 
							if( has_post_thumbnail() ):?>
								<div class="post-thumbnail">
									<a href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title();?>">
									<?php the_post_thumbnail('viem-post-slider-widget');?>
									</a>
								</div>
								<?php
							endif;
							?>
							<div class="post-content">
								<?php
								$category = get_the_category();
								$cat_ID = $category[0]->term_id;
								if ($category) {
									echo '<a class="dt-post-category" href="' . get_category_link( $cat_ID ) . '" title="' . sprintf( __ ( "View all posts in %s", "viem" ), $category[0]->name ) . '" ' . '>' . $category[0]->name.'</a> ';
								}
								?>
								<?php the_title( sprintf('<h3 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink()) ), '</a></h3>' ); ?>
								
								<div class="entry-meta">
									<?php
									printf('<div class="byline"><span class="author vcard">%1$s <a class="url fn n" href="%2$s" rel="author">%3$s</a></span></div>',
										esc_html__('By', 'viem'),
										esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
										get_the_author()
									);
									?>
									<?php
									viem_dt_posted_on();
									?>
								</div>
							</div>
						</article>
					</div>
		        <?php endwhile; ?>
		        </div>
			</div>
	    </div>
		<?php
			wp_reset_postdata();
			endif;
		
		echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));

		echo ob_get_clean();
	}
}

add_action('widgets_init', 'viem_DT_Post_Slider_register_widget');
function viem_DT_Post_Slider_register_widget(){
	return register_widget("viem_DT_Post_Slider");
}