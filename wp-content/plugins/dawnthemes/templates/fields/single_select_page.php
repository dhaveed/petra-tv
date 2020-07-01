<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$args = array(
	'name'             => $field_name,
	'id'               => $id,
	'sort_column'      => 'menu_order',
	'sort_order'       => 'ASC',
	'show_option_none' => ' ',
	'echo'             => false,
	'selected'         => $value
);

if ( isset( $args ) ) {
	$args = wp_parse_args( $args, $args );
}
$output = '<div  class="dt-field-select ' .  $id . '-field'.$dependency_cls.'"'.$dependency_data.'>';
$output .= str_replace(' id=', " data-placeholder='" . esc_attr__( 'Select a page&hellip;', 'dawnthemes' ) .  "' class='dt-opt-value dt-chosen-select' id=", wp_dropdown_pages( $args ) );
$output .= '</div>';

echo $output;