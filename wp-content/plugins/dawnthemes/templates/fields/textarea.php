<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<div class="dt-field-textarea ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<textarea class="dt-opt-value" name="' . $field_name . '" id="' . $id .
'" placeholder="' . $placeholder . '" rows="5" cols="20" style="width: 99%;">' .
esc_textarea( $value ) . '</textarea> ';
if( isset($note) )
	$output .= '<p class="dt-opt-note">' . $note . '</p>';
$output .= '</div>';

echo $output;