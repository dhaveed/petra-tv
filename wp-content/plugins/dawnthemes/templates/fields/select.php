<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$options = isset( $options ) ? $options : array();
$output = '<div  class="dt-field-select ' . $id . '-field' . $dependency_cls . '"' .
	 $dependency_data . '>';
$output .= '<select ' . ( $type == 'muitl-select' ? 'multiple="multiple"' : $data_name ) .
	 ' data-placeholder="' . $label . '" class="dt-opt-value dt-chosen-select"  id="' .
	 $id . '" name="' . $field_name . '">';
foreach ( $options as $option_name => $v ) {
		$output .= '<option value="' . esc_attr( $v ) . '" ' .
			 selected( ( $value ), esc_attr( $v ), false ) . '>' . esc_html( $option_name ) .
			 '</option>';
}
$output .= '</select> ';
$output .= '</div>';

echo $output;