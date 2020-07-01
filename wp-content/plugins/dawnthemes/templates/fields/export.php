<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$secret = md5( AUTH_KEY . SECURE_AUTH_KEY );
$link = admin_url( 'admin-ajax.php?action=dt_download_theme_option&secret=' . $secret );
$output = '<a id="dt-opt-export" class="button-primary" href="' . esc_url( $link ) . '">' .
	esc_html__( 'Export', 'dawnthemes' ) . '</a>';

echo $output;