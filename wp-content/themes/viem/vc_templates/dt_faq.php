<?php
$output = '';
extract(shortcode_atts(array(
	'title'					=>'',
	'desc'					=>'',
	'el_class'				=>'',
), $atts));
$indicators = '';
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$id = uniqid('viem_faq_');
?>
<div class="viem_faq <?php echo esc_attr($el_class)?>">
	<?php if(!empty($title) || !empty($desc)):?>
	<div class="viem_faq_heading">
		<h3 class="viem_faq-title"><span><?php echo esc_html($title)?></span></h3>
		<span class="desc"><?php echo esc_html($desc)?></span>
	</div>
	<?php endif;?>
	<div id="<?php echo esc_attr($id) ?>" class="viem_faq-wrap">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</div>
</div>