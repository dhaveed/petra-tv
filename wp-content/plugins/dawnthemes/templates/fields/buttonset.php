<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$options = isset( $options ) ? $options : array();
$output = '<div class="dt-field-buttonset ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<div class="dt-buttonset">';
foreach ( $options as $k => $v ) {
	$output .= '<input ' . $data_name . ' name="' . $field_name . '"
				id="' . esc_attr( $name . '-' . $k ) . '"
        		value="' . esc_attr( $k ) . '"
        		type="radio"
				class="dt-opt-value"
        		' . checked( $value, esc_attr( $k ), false ) . '
        		/><label for="' . esc_attr( $name . '-' . $k ) . '">' . esc_html( $v ) . '</label>';
}
$output .= '</div>';
$output .= '</div>';


echo $output;