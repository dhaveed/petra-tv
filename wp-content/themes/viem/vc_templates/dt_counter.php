<?php
extract(shortcode_atts(array(
	'speed'				=>2000,
	'number'			=>'',
	'format'			=>'',
	'thousand_sep'		=>'',
	'decimal_sep'		=>'',
	'num_decimals'		=>'',
	'units'				=>'',
	'units_color'		=>'',
	'units_font_size'	=>'',
	'number_color'		=>'',
	'number_font_size'	=>'',
	'icon'				=>'fa fa-ticket',
	'icon_color'		=>'',
	'icon_font_size'	=>'',
	'icon_position'		=>'bottom',
	'icon_visible'		=>'',
	'text'				=>'',
	'text_color'		=>'',	
	'text_font_size'	=>'',
	'text_align'		=>'center',
	'tpl_mode'		=>'',
	'visibility'		=>'',
	'el_class'			=>'',
), $atts));


$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$el_class .= viem_dt_visibility_class($visibility);
$number_color = viem_format_color($number_color);
$text_color = viem_format_color($text_color);
$units_color = viem_format_color($units_color);
$number = viem_get_number($number);
$number_format = $number;
$data_format = '';
if(!empty($format)){
	$thousand_sep = wp_specialchars_decode( stripslashes($thousand_sep),ENT_QUOTES);
	$decimal_sep = wp_specialchars_decode( stripslashes($decimal_sep),ENT_QUOTES);
	$num_decimals = absint($num_decimals);
	$data_format = ' data-thousand-sep="'.esc_attr($thousand_sep).'" data-decimal-sep="'.esc_attr($decimal_sep).'" data-num-decimals="'.$num_decimals.'"';
	$number_format = number_format($number,absint($num_decimals),$decimal_sep,$thousand_sep);
}
echo '<div class="viem-sc-counter '.$tpl_mode.' counter-icon-'.$icon_position.' text-align-'.$text_align.' '.$icon_visible.'">';
$icon_html = '';
if(!empty($icon)){
	$icon_color = viem_format_color($icon_color);
	$icon_html .='<span class="el-appear counter-icon"  style="font-size:'.$icon_font_size.'px;'.(!empty($icon_color)?'color:'.$icon_color:'').'"><i class="'.$icon.'"></i></span>';
}
if($icon_position == 'top'){
	echo viem_print_string( $icon_html );
}
echo '<div class="counter-count">'.($icon_position == 'left' ? $icon_html :'').'<span class="counter-number"'.$data_format.' data-to="'.$number.'" data-speed="'.$speed.'" style="font-size:'.$number_font_size.'px;'.(!empty($number_color)?'color:'.$number_color.'':'') .'">'.$number_format.'</span>'.(!empty($units)?'<span class="counter-unit" style="font-size:'.$units_font_size.'px;'.(!empty($units_color)?'color:'.$units_color.'':'') .'">'.esc_html($units).'</span>':'').'</div>';
echo '<div class="counter-text" style="font-size:'.$text_font_size.'px;'.(!empty($text_color)?'color:'.$text_color.'':'') .'">'.esc_html($text).'</div>';
if($icon_position == 'bottom'){
	echo viem_print_string( $icon_html );
}
echo '</div>';