<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<div class="dt-field-text ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<input type="text" class="dt-opt-value input_text" name="' . $field_name . '" id="' .
	$id . '" value="' . esc_attr( $value ) . '" placeholder="' .
	$placeholder . '" style="width:'. $width .'" /> ';
$output .= '</div>';

echo $output;