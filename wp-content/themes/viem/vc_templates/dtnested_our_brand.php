<?php
$output = '';
extract(shortcode_atts(array(
	'title'					=>'',
	'transition_style'		=>'false',
	'autoplay'				=>'true',
	'columns'				=>6,
	'show_control'			=>'',
	'el_class'				=>'',
), $atts));
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$id = uniqid('viem_sc_our_brand_');
?>
<div class="viem-sc-our-brand wpb_content_element <?php echo esc_attr($el_class)?>">
	<?php if(!empty($title)):?>
		<h3 class="viem-sc-title"><span><?php echo esc_html($title)?></span></h3>
	<?php endif;?>
	<ul id="<?php echo esc_attr($id) ?>" class="owl-carousel viem-carousel-slide viem-preload" data-autoplay="<?php echo esc_attr($autoplay);?>" data-dots="0" data-nav="<?php echo ('yes'===$show_control) ? '1':'0' ?>" data-items="<?php echo esc_attr($columns) ?>" data-rtl = "<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</ul>
</div>