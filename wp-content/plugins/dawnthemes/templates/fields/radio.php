<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$options = isset( $options ) ? $options : array();
$output '<div class="dt-field-radio ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<ul>';
foreach ( $options as $key => $v ) {
	$output .= '<li><label><input
        		name="' . $field_name . '"
        		value="' . esc_attr( $key ) . '"
        		type="radio"
				' . $data_name . '
				class="dt-opt-value radio"
        		' . checked( esc_attr( $value ), esc_attr( $key ), false ) . '
        		/> ' . esc_html( $v ) . '</label>
    	</li>';
}
$output .= '</ul>';
$output .= '</div>';

echo $output;