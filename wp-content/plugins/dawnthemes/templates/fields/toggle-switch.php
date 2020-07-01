<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$cb_enabled = $cb_disabled = ''; // no errors, please
if ( (int) $value == 1 ) {
	$cb_enabled = ' selected';
} else {
	$value = 0;
	$cb_disabled = ' selected';
}
// Label On
if ( ! isset( $on ) ) {
	$on = esc_html__( 'On', 'dawnthemes' );
} else {
	$on = $on;
}
	
// Label OFF
if ( ! isset( $off ) ) {
	$off = esc_html__( 'Off', 'dawnthemes' );
} else {
	$off = $off;
}
	
$output = '<div class="dt-field-switch ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<label class="cb-enable' . $cb_enabled . '" data-id="' . $id . '">' . $on . '</label>';
$output .= '<label class="cb-disable' . $cb_disabled . '" data-id="' . $id . '">' . $off .
'</label>';
$output .= '<input ' . $data_name . ' type="hidden"  class="dt-opt-value switch-input" id="' . $id .
'" name="' . $field_name . '" value="' . esc_attr( $value ) . '" />';
$output .= '</div>';

echo $output;