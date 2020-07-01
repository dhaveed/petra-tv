<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

echo '<div class="dt-field-text ' . $id . '-field' . $dependency_cls . '"' .
	 $dependency_data . '>';
echo '<input type="text" readonly class="dt-opt-value input_text" name="' . $field_name . '" id="' .
	 $id . '" value="' . esc_attr( $value ) . '" placeholder="' .
	 $placeholder . '" style="width:99%" /> ';
echo '</div>';
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#<?php echo esc_attr($id); ?>').datetimepicker({
		 scrollMonth: false,
		 scrollTime: false,
		 scrollInput: false,
		 step:5,
		 format:'m/d/Y H:i'
		});
	});
</script>