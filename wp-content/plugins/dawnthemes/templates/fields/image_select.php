<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$options = isset( $options ) ? $options : array();
$output = '<div class="dt-field-image-select ' . ( $id ) . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<ul class="dt-image-select">';
foreach ( $options as $k => $v ) {
	$output .= '<li' . ( $value == $k ? ' class="selected"' : '' ) . '><label for="' .
		esc_attr( $k ) . '"><input
			        		name="' . $field_name . '"
							id="' . esc_attr( $k ) . '"
			        		value="' . esc_attr( $k ) . '"
			        		type="radio"
							' . $data_name . '
							class="dt-opt-value image_select"
			        		' . checked( $value, esc_attr( $k ), false ) . '
			        		/><img alt="' . esc_attr( @$v['alt'] ) . '" src="' .
			        		esc_url( @$v['img'] ) . '"></label>
				    </li>';
}
$output .= '</ul>';
$output .= '</div>';

echo $output;