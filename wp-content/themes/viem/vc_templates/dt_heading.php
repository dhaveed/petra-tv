<?php
$output = '';
extract( shortcode_atts( array(
	'text' 					=> '',
	'font_size'				=> '',
	'text_color'			=> '',
	'visibility'			=>'',
	'el_class'				=>'',
	'css'=>'',
), $atts ) );

$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= viem_dt_visibility_class($visibility);
if( $text =='' )
	return '';
$output = '';
$custom_style = '';
if( $font_size !='' ||  $text_color !=''){
	$custom_style = 'style="'. (($font_size) ? 'font-size:'.$font_size.'px;': '') .' '. (($text_color) ? 'color:'.$text_color : '') .';"';
}
ob_start();
?>
<h3 class="viem-sc-heading wpb_content_element <?php echo esc_attr($class) . viem_shortcode_vc_custom_css_class($css, ' ');?>" <?php echo ($custom_style);?>><span><?php echo esc_html($text);?></span></h3>
<?php
echo  ob_get_clean();