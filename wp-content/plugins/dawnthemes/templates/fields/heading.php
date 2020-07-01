<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$output = '<h4>' . ( isset( $text ) ? $text : '' ) . '</h4>';
if( !empty( $desc ) ){
	$output .= '<br/><span class="description">' . $text . '</span>';
}

echo $output;