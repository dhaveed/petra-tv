<?php
$output = '';
extract(shortcode_atts(array(
	'title'					=>esc_html__( 'Cast', 'viem' ),
	'columns'				=>5,
	'el_class'				=>'',
), $atts));
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
?>
<div class="viem-sc-movie-cast wpb_content_element <?php echo esc_attr($el_class)?>">
	<?php if(!empty($title)):?>
	<div class="viem_sc_heading">
		<h3 class="viem-sc-title"><span><?php echo esc_html($title)?></span></h3>
	</div>
	<?php endif;?>
	<div class="viem-sc-content v-grid-list <?php echo 'cols_'.absint($columns);?>">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</div>
</div>