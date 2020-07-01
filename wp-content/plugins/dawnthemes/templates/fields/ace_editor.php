<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<div class="dt-field-textarea ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<pre id="' . $id .
'-editor" class="dt-opt-value" style="height: 205px;border:1px solid #ccc">' . $value .
'</pre>';
$output .= '<textarea class="dt-opt-value" id="' . $id . '" name="' . $field_name .
'" placeholder="' . $placeholder . '" style="width: 99%;display:none">' .
$value . '</textarea> ';
$output .= '</div>';

echo $output;