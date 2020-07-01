<?php
$output = '';
extract(shortcode_atts(array(
	'transition_style'		=>'goDown',
	'autoplay'				=>'true',
	'el_class'				=>'',
), $atts));
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$id = uniqid('viem_banner_slider_');
?>
<div class="viem-banner-slider <?php echo esc_attr($el_class)?>">
	<div id="<?php echo esc_attr($id) ?>" class="owl-carousel viem-carousel-slide viem-preload" data-autoplay="<?php echo esc_attr($autoplay);?>" data-dots="1" data-nav="0" data-items = "1" data-rtl = "<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</div>
</div>