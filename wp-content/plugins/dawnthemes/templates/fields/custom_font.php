<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$value_default = array( 
	'font-family' => '', 
	'font-size' => '', 
	'font-style' => '', 
	'text-transform' => '', 
	'letter-spacing' => '', 
	'subset' => '' );
$values = wp_parse_args( $value, $value_default );

$uploaded_fonts_output = '';
$uploaded_font_families = array();
$uploaded_fonts = dawnthemes_get_theme_option('upload_font', array());
if( is_array($uploaded_fonts) AND count($uploaded_fonts) > 0 ){
	if( isset($uploaded_fonts['font-name']) OR isset($uploaded_fonts['font-files'])){
		$uploaded_fonts_output .= '<optgroup label="'.esc_html__( 'Uploaded Fonts', 'dawnthemes' ).'">';
		
		$uploaded_font_name = isset( $uploaded_fonts['font-name'] ) ? strip_tags( $uploaded_fonts['font-name'] ) : '';
		if ( $uploaded_font_name == '' ) {
			$uploaded_font_name = $uploaded_fonts['file-name'];
		}
		
		$uploaded_fonts_output .= "<option class='uploaded' value='" . esc_attr( $uploaded_font_name ) . "' " . selected( $values['font-family'], $uploaded_font_name, FALSE ) . '>' . $uploaded_font_name . '</option>';
		
		$uploaded_fonts_output .= '</optgroup>';
	}
}

global $dawnthemes_google_fonts;
if ( empty( $dawnthemes_google_fonts ) )
	include_once ( DTINC_DIR . '/lib/google-fonts.php' );

include ( DTINC_DIR . '/lib/web-safe-fonts.php' );

$google_fonts_object = json_decode( $dawnthemes_google_fonts );
$google_faces = array();
foreach ( $google_fonts_object as $obj => $props ) {
	$google_faces[$props->family] = $props->family;
}
echo '<div class="dt-field-custom-font ' . ( $id ) . '-field' . $dependency_cls . '"' .
	 $dependency_data . '>';
// font-family
echo '<div  class="custom-font-family">';
echo '<select data-placeholder="' . esc_html__( 'Select a font family', 'dawnthemes' ) .
	 '" class="dt-opt-value dt-chosen-select-nostd"  id="' . $id . '" name="' . $field_name .
	 '[font-family]">';
echo '<option value="">'.esc_html__( 'No font specified', 'dawnthemes' ).'</option>';

echo $uploaded_fonts_output;

echo '<optgroup label="'.esc_html__( 'Web safe font combinatiions (do not need to be loaded)', 'dawnthemes' ).'">';
foreach ( $web_safe_fonts as $web_safe_font ) {
	echo '<option value="' . ( $web_safe_font ) . '" ' .
		selected( ( $values['font-family'] ), esc_attr( $web_safe_font ), false ) . '>' . esc_html( $web_safe_font ) .
		'</option>';
}
echo '</optgroup>';

echo '<optgroup label="'.esc_html__( 'Google Fonts (loaded from Google servers)', 'dawnthemes' ).'">';
foreach ( $google_faces as $k => $v ) {
	echo '<option value="' . ( $k ) . '" ' .
		 selected( ( $values['font-family'] ), esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		 '</option>';
}
echo '</optgroup>';

echo '</select> ';
echo '</div>';

if( isset( $font_size ) &&  $font_size !== 'false'):
// font-size
echo '<div  class="custom-font-size">';
echo '<select data-placeholder="' . esc_html__( 'Font size', 'dawnthemes' ) .
	 '" class="dt-opt-value dt-chosen-select-nostd"  id="' . $id . '" name="' . $field_name .
	 '[font-size]">';
echo '<option value=""></option>';
foreach ( (array) dt_custom_font_size( true ) as $k => $v ) {
	echo '<option value="' . ( $k ) . '" ' .
		 selected( ( $values['font-size'] ), esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		 '</option>';
}
echo '</select> ';
echo '</div>';
endif;

// font-style
echo '<div  class="custom-font-style">';
echo '<select data-placeholder="' . esc_html__( 'Font style', 'dawnthemes' ) .
	 '" class="dt-opt-value dt-chosen-select-nostd"  id="' . $id . '" name="' . $field_name .
	 '[font-style]">';
echo '<option value=""></option>';
foreach ( (array) dt_custom_font_style( true ) as $k => $v ) {
	echo '<option value="' . ( $k ) . '" ' .
		 selected( ( $values['font-style'] ), esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		 '</option>';
}
echo '</select> ';
echo '</div>';

// subset
$subset = array( 
	"latin" => "Latin", 
	"latin-ext" => "Latin Ext", 
	"cyrillic" => "Cyrillic", 
	"cyrillic-ext" => "Cyrillic Ext", 
	"greek" => "Greek", 
	"greek-ext" => "Greek Ext", 
	"vietnamese" => "Vietnamese" );
echo '<div  class="custom-font-subset">';
echo '<select data-placeholder="' . esc_html__( 'Subset', 'dawnthemes' ) .
	 '" class="dt-opt-value dt-chosen-select-nostd"  id="' . $id . '" name="' . $field_name .
	 '[subset]">';
echo '<option value=""></option>';
foreach ( (array) $subset as $k => $v ) {
	echo '<option value="' . ( $k ) . '" ' .
		 selected( ( $values['subset'] ), esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		 '</option>';
}
echo '</select> ';
echo '</div>';

echo '</div>';