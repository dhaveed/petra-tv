<?php
$icon = '';
extract( shortcode_atts( array(
	'icon_type' 			=> 'fontawesome',
	'icon_fontawesome' 		=> 'fa fa-adjust',
	'icon_openiconic' 		=> '',
	'icon_typicons' 		=> '',
	'icon_entypo' 			=> '',
	'icon_linecons' 		=> 'vc_li vc_li-heart',
	'icon_color'			=> '#73bb67',
	'icon_font_size'		=>'32',
	'href' 					=> '',
	'target'				=>'_self',
	'title' 				=> esc_html__("Your Title Here ...",'viem'),
	'text_align' 			=> 'center',
	'desc'					=> '',
	'tpl_mode'			=>'',
	'visibility'			=>'',
	'el_class'				=>'',
), $atts ) );

switch ($icon_type){
	case 'openiconic':
		$icon = $icon_openiconic;
		break;
	case 'typicons':
		$icon = $icon_typicons;
		break;
	case 'entypo':
		$icon = $icon_entypo;
		break;
	case 'linecons':
		$icon = $icon_linecons;
		break;
	default: //'fontawesome':
		$icon = $icon_fontawesome;
		break;
}

vc_icon_element_fonts_enqueue( $icon_type );

$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$el_class .= viem_dt_visibility_class($visibility);

if ( $target == 'same' || $target == '_self' ) {
	$target = '';
}
$target = ( $target != '' ) ? ' target="' . $target . '"' : '';
$inline_style = '';
$inline_style .= (!empty($icon_color) ? 'color: '.$icon_color.';' : '');
$inline_style .= (!empty($icon_font_size) ? 'font-size: '.$icon_font_size. 'px;' : '');

echo '<div class="viem-icon-box feature-item '.$tpl_mode.' '.$el_class.'  text-align-'.$text_align.'">';
echo '<a href="'.esc_url($href).'" '.$target.' class="dt-custom-link">';
echo '<div class="icon"><i class="'.esc_attr($icon).'" '.(!empty($inline_style) ? ' style="'.$inline_style.'"' : '' ).'></i></div>';
echo '<div class="feature-item-content">';
echo '<h3 class="feature-item-title">'.esc_attr( $title ).'</h3>';
echo '<p class="feature-item-desc">'.esc_html( $desc ).'</p>';
echo '</div>';
echo '</a>';
echo '</div>';