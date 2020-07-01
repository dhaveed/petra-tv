<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$field_name = $field_name . '[]';

$options = isset( $options ) ? $options : array();
$output = '<div  class="dt-field-select ' . $id . '-field' . $dependency_cls . '"' .
	 $dependency_data . '>';
$output .= '<select ' . ( $type == 'muitl-select' ? 'multiple="multiple"' : $data_name ) .
	 ' data-placeholder="' . $label . '" class="dt-opt-value dt-chosen-select"  id="' .
	 $id . '" name="' . $field_name . '">';
foreach ( $options as $option_name => $v ) {$v_field_counter
		$output .= '<option value="' . esc_attr( $v ) . '" ' .
			 ( in_array( esc_attr( $v ), $value ) ? 'selected="selected"' : '' ) . '>' .
			 esc_html( $option_name ) . '</option>';
}
$output .= '</select> ';
$output .= '</div>';

echo $output;