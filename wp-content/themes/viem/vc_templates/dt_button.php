<?php
$output = '';
extract( shortcode_atts( array(
	'title' 				=> 'Button',
	'href' 					=> '',
	'target'				=>'_self',
	'style'					=>'',
	'hover_effect'			=>'hover_e1',
	'size'					=>'',
	'font_size'				=>'14',
	'border_width'			=>'1',
	'padding_top'			=>'6',
	'padding_right'			=>'30',
	'padding_bottom'		=>'6',
	'padding_left'			=>'30',
	'color'					=>'default',
	'background_color'		=>'',
	'border_color'			=>'',
	'text_color'			=>'',
	'hover_background_color'=>'',
	'hover_border_color'	=>'',
	'hover_text_color'		=>'',
	'block_button'			=>'',
	'alignment'				=>'left',
	'tooltip'				=>'',
	'tooltip_position'		=>'top',
	'tooltip_title'			=>'',
	'tooltip_content'		=>'',
	'tooltip_trigger'		=>'hover',
	'visibility'			=>'',
	'el_class'				=>'',
), $atts ) );

$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= viem_dt_visibility_class($visibility);

if ( $target == 'same' || $target == '_self' ) {
	$target = '';
}
$target = ( $target != '' ) ? ' target="' . $target . '"' : '';
$inline_style = '';
$btn_size = '';
if($size == 'custom'){
	$inline_style .= 'padding:'.$padding_top.'px '.$padding_right.'px '.$padding_bottom.'px '.$padding_left.'px;border-width:'.$border_width.'px;font-size:'.$font_size.'px;';
}elseif(!empty($size)){
	$btn_size = ' btn-'.$size;
}
$btn_color = '';
$btn_style = ($style=="outline" && $color == 'default' ? ' btn-outline ':'');
$btn_effect = '';

$attributes = array();

if($color == 'custom'){
	$inline_style .='background:'.$background_color.';border-color:'.$border_color.';color:'.$text_color;
	$btn_color = ' btn-custom-color';
	$hover_background_color = viem_format_color($hover_background_color);
	$hover_border_color = viem_format_color($hover_border_color);
	$hover_text_color = viem_format_color($hover_text_color);
	
	$attributes[] = 'onmouseenter="this.style.borderColor=\'' . $hover_border_color . '\'; this.style.backgroundColor=\'' . $hover_background_color . '\'; this.style.color=\'' . $hover_text_color . '\'"';
	$attributes[] = 'onmouseleave="this.style.borderColor=\'' . $border_color . '\'; this.style.backgroundColor=\''.$background_color.'\'; this.style.color=\'' . $text_color . '\'"';
	
}else{
	if($style=="outline"){
		$btn_color = ' btn-'.$color.'-outline';
	}else{
		$btn_color = ' btn-'.$color;
	}
	
}

$attributes = implode( ' ', $attributes );

$btn_class = 'btn'.$btn_color.(!empty($text_uppercase) ? ' btn-uppercase':'').(!empty($block_button)?' btn-block':'').$btn_size.$btn_style.$btn_effect.(empty($block_button) ? ' btn-align-'.$alignment: '').' '.$hover_effect ;
$data_el = '';
$data_toggle ='';
$data_target='';
if(!empty($tooltip)){
	$data_toggle = $tooltip;
	$data_el = ' data-container="body" data-original-title="'.($tooltip === 'tooltip' ? esc_attr($tooltip_content) : esc_attr($tooltip_title)).'" data-trigger="'.$tooltip_trigger.'" data-placement="'.$tooltip_position.'" '.($tooltip === 'popover'?' data-content="'.esc_attr($tooltip_content).'"':'').'';
}
if(!empty($data_toggle)){
	$data_toggle = ' data-toggle="'.$data_toggle.'"';
}

$btn_content = '<span>'.$title.'</span>' ;
echo '<div id="'.esc_attr($sc_id).'" class="dawnthemes-tbn-container wpb_content_element btn-align-'.$alignment.'">';
if($href != ''){
	echo '<a'.$data_el.$data_toggle.$data_target.' class="'.$btn_class.$class.'" href="'.esc_url($href).'" '.$target.$attributes.''.(!empty($inline_style) ? ' style="'.$inline_style.'"': '').'>';
	echo viem_print_string( $btn_content );
	echo '</a>';
}else{
	echo '<button'.$data_el.$data_toggle.$data_target.' class="'.$btn_class.$class.'" '.$attributes.' type="button"'.(!empty($inline_style) ? ' style="'.$inline_style.'"': '').'>';
	echo viem_print_string( $btn_content );
	echo '</button>';
}
echo '</div>';