<?php
extract(shortcode_atts(array(
	'cast_info_name'=>'',
	'cast_movie_name'=>'',
	'image'=>'',
), $atts));
if( $cast_info_name == '' ){ return ''; }
$args = array(
	'p' => absint($cast_info_name),
	'post_type' => 'viem_actor',
);
$r = new WP_Query( $args );
if( $r->have_posts() ):
	while ($r->have_posts()): $r->the_post();
	?>
	<div class="cast-member v-grid-item">
		<a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark">
			<?php if( has_post_thumbnail() ): ?>
			<div class="cast_movie_image"><?php the_post_thumbnail('viem-movie-360x460'); ?></div>
			<?php endif; ?>
			<div class="cast_movie_name"><?php the_title();?></div>
			<div class="cast_movie_character"><i><?php esc_html_e('as', 'viem');?></i><?php echo esc_html($cast_movie_name);?></div>
		</a>
	</div>
	<?php
	endwhile;
endif;
wp_reset_postdata();
?>