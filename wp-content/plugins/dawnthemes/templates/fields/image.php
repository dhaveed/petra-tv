<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

$image = $value;
//$image_w  = isset( $width ) ? $width : '200';

$output = ! empty( $image ) ? '<img src="' . $image . '">' : '';

$btn_text = ! empty( $image_id ) ? esc_html__( 'Change Image', 'dawnthemes' ) : esc_html__( 
	'Select Image', 
	'dawnthemes' );
echo '<div  class="dt-field-image ' . $id . '-field' . $dependency_cls . '"' .
	 $dependency_data . '>';
echo '<div class="dt-field-image-thumb">' . $output . '</div>';
echo '<input type="hidden" class="dt-opt-value" name="' . $field_name . '" id="' . $id .
	 '" value="' . esc_attr( $value ) . '" />';
echo '<input type="button" class="button button-primary" id="' . $id . '_upload" value="' .
	 $btn_text . '" /> ';
echo '<input type="button" class="button" id="' . $id . '_clear" value="' .
	 esc_html__( 'Clear Image', 'dawnthemes' ) . '" ' .
	 ( empty( $value ) ? ' style="display:none"' : '' ) . ' />';
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
		// I decided to create new frame every time to control the selected images
		var file_frame = wp.media.frames.downloadable_file = wp.media({
			title: "<?php echo esc_html__( 'Select or Upload your Image', 'dawnthemes' ); ?>",
			button: {
				text: "<?php echo esc_html__( 'Select', 'dawnthemes' ); ?>"
			},
			multiple: false
		});

		// when image selected, run callback
		file_frame.on('select', function(){
			var attachment = file_frame.state().get('selection').first().toJSON();
			$this.closest('.dt-field-image').find('input#<?php echo esc_attr($id); ?>').val(attachment.url);
			var thumbnail = $this.closest('.dt-field-image').find('.dt-field-image-thumb');
			thumbnail.html('');
			thumbnail.append('<img src="' + attachment.url + '" alt="" />');

			$this.attr('value', '<?php echo esc_html__( 'Change Image', 'dawnthemes' ); ?>');
			$('#<?php echo esc_attr($id); ?>_clear').css('display', 'inline-block');
		});

		// open media frame
		file_frame.open();
	});

	$('#<?php echo esc_attr($id); ?>_clear').on('click', function(event) {
		var $this = $(this);
		$this.hide();
		$('#<?php echo esc_attr($id); ?>_upload').attr('value', '<?php echo esc_html__( 'Select Image', 'dawnthemes' ); ?>');
		$this.closest('.dt-field-image').find('input#<?php echo esc_attr($id); ?>').val('');
		$this.closest('.dt-field-image').find('.dt-field-image-thumb').html('');
	});
});
</script>
<?php
echo '</div>';