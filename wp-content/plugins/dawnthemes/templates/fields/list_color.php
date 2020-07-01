<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<div class="dt-field-list-color ' . ( $id ) . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$options = isset( $options ) ? $options : array();
foreach ( $options as $key => $label ) {
	$values[$key] = isset( $value[$key] ) ? $value[$key] : '';
	$output .= '<div>' . $label . '<br>';
	$output .= '<input type="text" class="dt-opt-value" name="' . $field_name . '[' . $key . ']" id="' .
		$id . '_' . $key . '" value="' . esc_attr( $values[$key] ) . '" /> ';
	$output .= '<script type="text/javascript">
						jQuery(document).ready(function($){
						    $("#' . $id . '_' . $key . '").wpColorPicker();
						});
					 </script>
					';
	$output .= '</div>';
}
$output .= '</div>';

echo $output;