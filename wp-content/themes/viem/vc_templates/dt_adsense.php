<?php
extract( shortcode_atts( array(
	'adsense_id' 			=> '',
	'slot_id' 				=> '',
	'el_class'				=>'',
	'css'					=>'',
), $atts ) );

if($adsense_id == '' || $slot_id == ''){
	return;
}
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';

?>
<div class="viem-adsense-sc wpb_content_element <?php echo esc_attr( $class . viem_shortcode_vc_custom_css_class($css, ' ') );?>">
	<?php echo do_shortcode('[adsense pub="'.$adsense_id.'" slot="'.$slot_id.'"]'); ?>
</div>
<?php
