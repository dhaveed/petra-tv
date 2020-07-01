<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Get image size information as an array
 *
 * @param string $size_name
 *
 * @return array
 */
function viem_get_intermediate_image_size( $size_name ) {
	global $_wp_additional_image_sizes;
	if ( isset( $_wp_additional_image_sizes[ $size_name ] ) ) {
		// Getting custom image size
		return $_wp_additional_image_sizes[ $size_name ];
	} else {
		// Getting standard image size
		return array(
			'width' => get_option( "{$size_name}_size_w" ),
			'height' => get_option( "{$size_name}_size_h" ),
			'crop' => get_option( "{$size_name}_crop" ),
		);
	}
}

/**
 * Get image size values for selector
 *
 * @param array [$size_names] List of size names
 *
 * @return array
 */
function viem_image_sizes_select_values( $size_names = NULL ) {
	global $viem_image_sizes;
	if ( $size_names === NULL ) {
		$size_names = array( 'full', 'large', 'medium', 'thumbnail' );
	}
	$image_sizes = array();
	// For translation purposes
	$size_titles = array(
		'full' => esc_html__( 'Full Size', 'viem' ),
	);
	foreach ( $size_names as $size_name ) {
		$size_title = isset( $size_titles[ $size_name ] ) ? $size_titles[ $size_name ] : ucwords( $size_name );
		if ( $size_name != 'full' ) {
			// Detecting size
			$size = viem_get_intermediate_image_size( $size_name );
			$size_title = ( ( $size['width'] == 0 ) ? esc_html__( 'any', 'viem' ) : $size['width'] );
			$size_title .= ' x ';
			$size_title .= ( $size['height'] == 0 ) ? esc_html__( 'any', 'viem' ) : $size['height'];
			if ( $size['crop'] ) {
				$size_title .= ' ' . esc_html__( 'cropped', 'viem' );
			}
		}
		$image_sizes[ $size_title ] = $size_name;
	}

	// Custom sizes
	$custom_tnail_sizes = viem_get_theme_option( 'img_size', '' );
	if ( is_array( $custom_tnail_sizes ) ) {
		foreach ( $custom_tnail_sizes as $key => $size ) {
			
			$custom_sizes = explode(',', $size);
			
			$width = ( ! empty( $custom_sizes['0'] ) AND intval( $custom_sizes['0'] ) > 0 ) ? intval( $custom_sizes['0'] ) : 0;
			$height = ( ! empty( $custom_sizes['1'] ) AND intval( $custom_sizes['1'] ) > 0 ) ? intval( $custom_sizes['1'] ) : 0;
			$crop = ( ! empty( $custom_sizes['2'] ) AND intval( $custom_sizes['2'] ) > 0 ) ? intval( $custom_sizes['2'] ) : 0;
			$crop_str = ( $crop ) ? '_crop' : '';
			
			$size_name = 'viem_' . $width . '_' . $height . $crop_str;

			$size_title = ( $width == 0 ) ? esc_html__( 'any', 'viem' ) : $width;
			$size_title .= ' x ';
			$size_title .= ( $height == 0 ) ? esc_html__( 'any', 'viem' ) : $height;
			if ( $crop ) {
				$size_title .= ' ' . esc_html__( 'cropped', 'viem' );
			}

			$image_sizes[ $size_title ] = $size_name;
		}
	}
	$image_sizes = apply_filters( 'viem_image_sizes_select_values', $image_sizes );
	$viem_image_sizes = $image_sizes;
	return $image_sizes;
}