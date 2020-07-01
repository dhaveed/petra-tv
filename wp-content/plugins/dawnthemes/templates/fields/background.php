<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$value_default = array(
	'background-color' => '',
	'background-repeat' => '',
	'background-attachment' => '',
	'background-position' => '',
	'background-image' => '',
	'background-clip' => '',
	'background-origin' => '',
	'background-size' => '',
	'media' => array() );
$values = wp_parse_args( $value, $value_default );
echo '<div class="dt-field-background ' . $id . '-field' . $dependency_cls . '"' .
	$dependency_data . '>';
// background color
echo '<div  class="dt-background-color">';
echo '<input type="text" class="dt-opt-value" name="' . $field_name . '[background-color]" id="' .
	$id . '_background_color" value="' . esc_attr( $values['background-color'] ) . '" /> ';
echo '<script type="text/javascript">
					jQuery(document).ready(function($){
					    $("#' . ( $id ) . '_background_color").wpColorPicker();
					});
				 </script>
				';
echo '</div>';
echo '<br>';
// background repeat
echo '<div  class="dt-background-repeat">';
$bg_repeat_options = array(
	'no-repeat' => 'No Repeat',
	'repeat' => 'Repeat All',
	'repea-x' => 'Repeat Horizontally',
	'repeat-y' => 'Repeat Vertically',
	'inherit' => 'Inherit' );
echo '<select class="dt-opt-value dt-chosen-select-nostd" id="' . $id .
'_background_repeat" data-placeholder="' . esc_html__( 'Background Repeat', 'dawnthemes' ) .
'" name="' . $field_name . '[background-repeat]">';
echo '<option value=""></option>';
foreach ( $bg_repeat_options as $k => $v ) {
	echo '<option value="' . esc_attr( $k ) . '" ' .
		selected( $values['background-repeat'], esc_attr( $k ), false ) . '>' . esc_html(
			$v ) . '</option>';
}
echo '</select> ';
echo '</div>';
// background size
echo '<div  class="dt-background-size">';
$bg_size_options = array( 'inherit' => 'Inherit', 'cover' => 'Cover', 'contain' => 'Contain' );
echo '<select class="dt-opt-value dt-chosen-select-nostd" id="' . $id .
'_background_size" data-placeholder="' . esc_html__( 'Background Size', 'dawnthemes' ) . '" name="' .
$field_name . '[background-size]">';
echo '<option value=""></option>';
foreach ( $bg_size_options as $k => $v ) {
	echo '<option value="' . esc_attr( $k ) . '" ' .
		selected( $values['background-size'], esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		'</option>';
}
echo '</select> ';
echo '</div>';
// background attachment
echo '<div  class="dt-background-attachment">';
$bg_attachment_options = array( 'fixed' => 'Fixed', 'scroll' => 'Scroll', 'inherit' => 'Inherit' );
echo '<select class="dt-opt-value dt-chosen-select-nostd" id="' . $id .
'_background_attachment" data-placeholder="' . esc_html__( 'Background Attachment', 'dawnthemes' ) .
'"  name="' . $field_name . '[background-attachment]">';
echo '<option value=""></option>';
foreach ( $bg_attachment_options as $k => $v ) {
	echo '<option value="' . esc_attr( $key ) . '" ' .
		selected( $values['background-attachment'], esc_attr( $k ), false ) . '>' .
		esc_html( $v ) . '</option>';
}
echo '</select> ';
echo '</div>';
// background position
echo '<div  class="dt-background-position">';
$bg_position_options = array(
	'left top' => 'Left Top',
	'left center' => 'Left center',
	'left bottom' => 'Left Bottom',
	'center top' => 'Center Top',
	'center center' => 'Center Center',
	'center bottom' => 'Center Bottom',
	'right top' => 'Right Top',
	'right center' => 'Right center',
	'right bottom' => 'Right Bottom' );
echo '<select class="dt-opt-value dt-chosen-select-nostd"  id="' . $id .
'_background_position" data-placeholder="' . esc_html__( 'Background Position', 'dawnthemes' ) .
'" name="' . $field_name . '[background-position]">';
echo '<option value=""></option>';
foreach ( $bg_position_options as $k => $v ) {
	echo '<option value="' . esc_attr( $k ) . '" ' .
		selected( $values['background-position'], esc_attr( $k ), false ) . '>' .
		esc_html( $v ) . '</option>';
}
echo '</select> ';
echo '</div>';
// background image
	
$image = $values['background-image'];
$output = ! empty( $image ) ? '<img src="' . $image . '" with="100">' : '';
$btn_text = ! empty( $image_id ) ? esc_html__( 'Change Image', 'dawnthemes' ) : esc_html__(
	'Select Image',
	'dawnthemes' );
echo '<br>';
echo '<div  class="dt-background-image">';
echo '<div class="dt-field-image-thumb">' . $output . '</div>';
echo '<input type="hidden" class="dt-opt-value" name="' . $field_name . '[background-image]" id="' .
	$id . '_background_image" value="' . esc_attr( $values['background-image'] ) . '" />';
echo '<input type="button" class="button button-primary" id="' . $id .
'_background_image_upload" value="' . $btn_text . '" /> ';
echo '<input type="button" class="button" id="' . $id . '_background_image_clear" value="' .
	esc_html__( 'Clear Image', 'dawnthemes' ) . '" ' .
	( empty( $value ) ? ' style="display:none"' : '' ) . ' />';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#<?php echo esc_attr($id); ?>_background_image_upload').on('click', function(event) {
			event.preventDefault();
			var $this = $(this);

			// if media frame exists, reopen
			if(dt_meta_image_frame) {
				dt_meta_image_frame.open();
                return;
            }

			// create new media frame
			// I decided to create new frame every time to control the selected images
			var dt_meta_image_frame = wp.media.frames.wp_media_frame = wp.media({
				title: "<?php echo esc_html__( 'Select or Upload your Image', 'dawnthemes' ); ?>",
				button: {
					text: "<?php echo esc_html__( 'Select', 'dawnthemes' ); ?>"
				},
				library: { type: 'image' },
				multiple: false
			});

			// when image selected, run callback
			dt_meta_image_frame.on('select', function(){
				var attachment = dt_meta_image_frame.state().get('selection').first().toJSON();
				$this.closest('.dt-background-image').find('input#<?php echo esc_attr($id); ?>_background_image').val(attachment.url);
				var thumbnail = $this.closest('.dt-background-image').find('.dt-field-image-thumb');
				thumbnail.html('');
				thumbnail.append('<img src="' + attachment.url + '" alt="" />');

				$this.attr('value', '<?php echo esc_html__( 'Change Image', 'dawnthemes' ); ?>');
				$('#<?php echo esc_attr($id); ?>_background_image_clear').css('display', 'inline-block');
			});

			// open media frame
			dt_meta_image_frame.open();
		});

		$('#<?php echo esc_attr($id); ?>_background_image_clear').on('click', function(event) {
			var $this = $(this);
			$this.hide();
			$('#<?php echo esc_attr($id); ?>_background_image_upload').attr('value', '<?php echo esc_html__( 'Select Image', 'dawnthemes' ); ?>');
			$this.closest('.dt-background-image').find('input#<?php echo esc_attr($id); ?>_background_image').val('');
			$this.closest('.dt-background-image').find('.dt-field-image-thumb').html('');
		});
	});
</script>

<?php
echo '</div>';
echo '</div>';
