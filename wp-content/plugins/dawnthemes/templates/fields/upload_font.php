<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$value_default = array(
	'font-name' => '',
	'font-weight' => '400',
	'font-files' => '',
	'file-name' => '',
);
$values = wp_parse_args( $value, $value_default );
echo '<div class="dt-field-upload_font-group">';
echo '<div class="dt-field-custom-font ' . ( $id ) . '-field">';
// font-name
echo '<div  class="custom-upload-font-name">';
echo '<input type="text" class="dt-opt-value input_text" id="' . $id . '-font-name" name="' . $field_name . '[font-name]" value="'.$values['font-name'].'" placeholder="' . esc_html__( 'Font Name', 'dawnthemes' ) . '" style="width:99%"/>';
echo '</div>';

// font-weight
echo '<div  class="custom-font-weight">';
echo '<select data-placeholder="' . esc_html__( 'Font Weight', 'dawnthemes' ) .
	 '" class="dt-opt-value dt-chosen-select-nostd"  id="' . $id . '-font-weight" name="' . $field_name .
	 '[font-weight]">';
echo '<option value=""></option>';
foreach ( (array) dt_custom_font_weight( true ) as $k => $v ) {
	echo '<option value="' . ( $k ) . '" ' .
		 selected( ( $values['font-weight'] ), esc_attr( $k ), false ) . '>' . esc_html( $v ) .
		 '</option>';
}
echo '</select> ';
echo '</div>';

echo '</div>';
echo '<div class="clearfix"></div>';

echo '<div class="dt-field-upload-font-files">';

echo '<p class="field-label">Font Files</p>';
echo '<div class="dt-field-upload-font-control">';

$file = isset( $values['font-files'] ) ? $values['font-files'] : '';

$btn_text = ! empty( $file_id ) ? esc_html__( 'Change File', 'dawnthemes' ) : esc_html__(
	'Select File',
	'dawnthemes' );
echo '<div  class="dt-field-file ' . $id . '-field">';
echo '<div class="dt-field-file-preview">' . $values['file-name'] . '</div>';
echo '<input type="hidden" class="dt-opt-value" name="' . $field_name . '[font-files]" id="' . $id .
'" value="' . esc_attr( $file ) . '" />';
echo '<input type="hidden" class="dt-opt-value" name="' . $field_name . '[file-name]" id="file-name-' . $id .
'" value="' . esc_attr( $values['file-name'] ) . '" />';
echo '<input type="button" class="button button-primary" id="' . $id . '_upload" value="' .
	$btn_text . '" /> ';
echo '<input type="button" class="button" id="' . $id . '_clear" value="' .
	esc_html__( 'Clear File', 'dawnthemes' ) . '" ' .
	( empty( $file ) ? ' style="display:none"' : '' ) . ' />';
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
	// Uploading files
	var file_frame;

	$('#<?php echo esc_attr($id); ?>_upload').on('click', function(event) {
		event.preventDefault();
		var $this = $(this);

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		// create new media frame
		// I decided to create new frame every time to control the selected files
		var file_frame = wp.media.frames.downloadable_file = wp.media({
			title: "<?php echo esc_html__( 'Select or Upload your font file', 'dawnthemes' ); ?>",
			button: {
				text: "<?php echo esc_html__( 'Select', 'dawnthemes' ); ?>"
			},
			multiple: false
		});

		// when file selected, run callback
		file_frame.on('select', function(){
			var attachment = file_frame.state().get('selection').first().toJSON();
			$this.closest('.dt-field-file').find('input#<?php echo esc_attr($id); ?>').val(attachment.url);
			$this.closest('.dt-field-file').find('input#file-name-<?php echo esc_attr($id); ?>').val(attachment.filename);
			var preview = $this.closest('.dt-field-file').find('.dt-field-file-preview');
			preview.html('');
			preview.append('<span>' + attachment.filename + '</span>');

			$this.attr('value', '<?php echo esc_html__( 'Change File', 'dawnthemes' ); ?>');
			$('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
		});

		// open media frame
		file_frame.open();
	});

	$('#<?php echo esc_attr($id); ?>_clear').on('click', function(event) {
		var $this = $(this);
		$this.hide();
		$('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__( 'Select File', 'dawnthemes' ); ?>');
		$this.closest('.dt-field-file').find('input#<?php echo esc_attr($id); ?>').val('');
		$this.closest('.dt-field-file').find('input#file-name-<?php echo esc_attr($id); ?>').val('');
		$this.closest('.dt-field-file').find('.dt-field-file-preview').html('');
	});
});
</script>
<?php
echo '</div>';


echo '</div>';

echo '</div>';
echo '</div>';