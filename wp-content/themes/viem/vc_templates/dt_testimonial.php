<?php
$output = '';
extract(shortcode_atts(array(
	'title'					=>'',
	'columns'				=>1,
	'style'					=>'style_1',
	'transition_style'		=>'false',
	'autoplay'				=>'false',
	'show_pagination'		=>'',
	'show_control'			=>'',
	'el_class'				=>'',
), $atts));

$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$id = uniqid('testimonial_carousel_');
?>
<div class="testimonial-carousel viem-carousel testimonial-carousel-<?php echo esc_attr($style)?><?php echo esc_attr($el_class)?> wpb_content_element">
	<?php if(!empty($title)):?>
		<h3 class="testimonial-carousel-title"><span><?php echo esc_html($title)?></span></h3>
	<?php endif;?>
	<div id="<?php echo esc_attr($id) ?>" class="owl-carousel viem-carousel-slide testimonial-carousel-slide viem-preload" data-autoplay="<?php echo esc_attr($autoplay);?>" data-dots="<?php echo ('yes'===$show_pagination) ? '1':'0' ?>" data-nav="<?php echo ('yes'===$show_control) ? '1':'0' ?>" data-items="<?php echo esc_attr($columns) ?>" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</div>
</div>