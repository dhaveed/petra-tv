<?php
$output = array();
extract(shortcode_atts(array(
	'style'				=>'default',
	'title'				=>'',
	'icon_player'		=>'',
	'template'			=>'',
	'posts_to_show'		=>'4',
	'posts_per_page'	=>'8',
	'orderby'			=>'latest',
	'categories'		=>'',
	'exclude_categories'=>'',
	'img_size'			=> '',
	'el_class'			=>'',
	'css'				=>'',
), $atts));

$sc_id = uniqid('viem_sc_');
$class 	= !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= ( $template ) ? ' viem-'.$template . ' ' : '';

$order = 'DESC';
switch ($orderby) {
	case 'latest':
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
	'order'           => "{$order}",
	'post_type'       => "viem_movie",
	'posts_per_page'  => $posts_per_page,
);

if(!empty($categories)){
	$args['tax_query'][] =  array(
		'taxonomy' => 'viem_movie_genre',
		'terms'    => explode(',',$categories),
		'field'    => 'slug',
		'operator' => 'IN'
	);
}
if(!empty($exclude_categories)){
	$args['tax_query'][] =  array(
			'taxonomy' => 'viem_movie_genre',
			'terms'    => explode(',',$exclude_categories),
			'field'    => 'slug',
			'operator' => 'NOT IN'
	);
} 

$r = new WP_Query($args);

if($r->have_posts()):
$data_arrows = 'true';
$fade = 'false';
$dots = 'false';

if( $img_size == '' || $img_size == 'default' ){
	$img_size = ($style == 'default' ? viem_get_theme_option('movies-image-size', 'viem-movie-360x460') : 'large');
}

?>
<div class="viem-posts-slider wpb_content_element <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?> <?php echo esc_attr($style); ?>">
	<?php if($title !=''):?>
	<div class="viem_sc_heading">
		<div class="viem_sc_title">
			<h5 class="viem-sc-title"><?php echo esc_html($title);?></h5>
		</div>
	</div>
	<?php endif; ?>
	<div class="viem_sc_content">
		<div class="viem_sc_wrap">
			<div id="<?php echo esc_attr($sc_id) ?>" class="viem-slick-slider viem-preload"  data-mode="<?php echo esc_attr($style);?>" data-visible="<?php echo esc_attr($posts_to_show)?>" data-scroll="1" data-infinite="true" data-autoplay="true" data-arrows="<?php echo esc_attr($data_arrows);?>" data-dots="<?php echo esc_attr($dots);?>" data-fade="<?php echo esc_attr($fade);?>">
				<?php
				switch ($style){
					case 'syncing':
						break;
					case 'single':
						break;
					default:
						while ($r->have_posts()): $r->the_post(); $post_id = get_the_ID();
						?>
							<div class="post-item-slide">
								<article class="viem_movie post">
									<div class="entry-featured-wrap">
										<?php 
											viem_post_image($img_size, 'grid');
										?>
									</div>
									<div class="hentry-wrap">
										<header class="post-header">
											<?php	
											the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
											?>
										</header><!-- .entry-header -->
										<div class="entry-meta">
											<div class="entry-meta-content">
												<span><?php echo date_i18n('Y', strtotime(viem_get_post_meta('movie_release_date')) );?></span>
											</div>
										</div>
									</div><!-- .entry-meta -->
									  
								</article>
							</div>
						<?php endwhile;
						break;
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata();
?>