<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<div  class="dt-field-color ' . $id . '-field' . $dependency_cls . '"' .
		 $dependency_data . '>';
	$output .= '<input type="text" class="dt-opt-value" name="' . $field_name . '" id="' . $id .
		 '" value="' . esc_attr( $value ) . '" placeholder="' . $placeholder . '" /> ';
	$output .= '<script type="text/javascript">
	jQuery(document).ready(function($){
	    $("#' . ( $id ) . '").wpColorPicker();
	});
 </script>
';
	$output .= '</div>';
	
echo $output;