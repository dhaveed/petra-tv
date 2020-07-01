<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );


$output = '<div class="dt-field-import ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
$output .= '<textarea id="' . $id . '" name="' . $option_name .
'[import_code]" placeholder="' . $placeholder .
'" rows="5" cols="20" style="width: 99%;"></textarea><br><br>';
$output .= '<button id="dt-opt-import" class="button-primary" name="' . $option_name .
'[dt_opt_import]" type="submit">' . esc_html__( 'Import', 'dawnthemes' ) . '</button>';
$output .= ' <em style="font-size:11px;color:#f00">' . esc_html__(
	'WARNING! This will overwrite all existing option values, please proceed with caution!',
	'dawnthemes' ) . '</em>';
$output .= '</div>';

echo $output;