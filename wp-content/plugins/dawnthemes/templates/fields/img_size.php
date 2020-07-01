<?php
defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

echo '<div id="'.esc_attr($name).'" class="dt-opt-group dt-img-size-wrap '. $dependency_cls .'"'.$dependency_data.' >';
echo '<div class="dt-opt-group-content">';
$img_sizes = array();
$value_default = array(
	array(
		'width' => '600',
		'height' => '400',
		'crop' => '1',
	),
);

$img_sizes = isset($std) ? $std : $value_default;

// Get option
$dawnthemes_img_sizes = dawnthemes_get_theme_option( $name , '');
if( is_array($dawnthemes_img_sizes) && !empty($dawnthemes_img_sizes) ){
	foreach ($dawnthemes_img_sizes as $k => $val){
		$val =  explode(',', $val);
		
		$img_sizes[$k]['width'] = $val[0];
		$img_sizes[$k]['height'] = $val[1];
		$img_sizes[$k]['crop'] = $val[2];
	}
}

$i = 0;
foreach ($img_sizes as $key => $size){
	
	$size_name = $option_name . '[' . $name.']['.$i.']';
	$size_value =  implode(',', $size);
	
	$val_width = $size['width'];
	$val_height = $size['height'];
	$val_crop = $size['crop'];
	
	$_c = $name.'_'.$i.'_crop';
	
	echo '<div class="img-size-item dt-form-group-item" data-index="'.esc_attr($key).'">';
		echo '<div class="img-size-item-content">';
				echo '<div class="dt-form-row"><div class="dt-form-row-title"><span>'. esc_html__('Width', 'dawnthemes') .'</span></div><div class="dt-form-row-field" data-id="width"><input type="number" name="width" step="1" min="0" value="'. esc_attr( $val_width ) .'"/></div></div>';
				echo '<div class="dt-form-row"><div class="dt-form-row-title"><span>'. esc_html__('Height', 'dawnthemes') .'</span></div><div class="dt-form-row-field" data-id="height"><input type="number" name="height" step="1" min="0" value="'. esc_attr( $val_height ) .'"/></div></div>';
				echo '<div class="dt-form-row"><div class="dt-form-row-title"></div><div class="dt-form-row-field type_checkbox" data-id="crop"><input id="'.$_c.'" type="checkbox" name="crop" value="1" '. checked($val_crop, '1', false) .'><label for="'.$_c.'">'.esc_html__('Crop to exact dimensions.', 'dawnthemes').'</label></div></div>';
		echo '</div>';
		echo '<input type="hidden" class="img_size_key" id="img_size_'.$key.'" name="'.esc_attr($size_name).'" value="'.esc_attr($size_value).'">';
		echo '<div class="img-size-item-delete" title="'.esc_attr__('Delete', 'dawnthemes').'"><i class="fa fa-trash"></i></div>';
	echo '</div>';
	
	$i++;
}
echo '</div>';
echo '<div class="dt-opt-group-add" title="' . esc_attr__( 'Add', 'dawnthemes' ) . '"></div>';
$translations = array(
	'deleteConfirm' => __( 'Are you sure want to delete the element?', 'dawnthemes' ),
);
echo '<span class="dt-form-group-translations"' . dawnthemes_pass_data_to_js( $translations ) . '></span>';
echo '</div>';
?>
<script type="text/javascript">
	
	jQuery(document).ready(function($) {
		$('.dt-opt-group-add').on('click', function(){
			var $this_group = $(this).closest('.dt-opt-field-wrap').find('.dt-opt-group .dt-opt-group-content');
			
			var $idx = $this_group.find('.dt-form-group-item').last().attr('data-index');
			
			var $group_param = $this_group.find('.dt-form-group-item').last().clone();
			
			$group_param.attr({'data-index': parseInt($idx) + 1});
			$group_param.find('input[name="width"]').val('600');
			$group_param.find('input[name="height"]').val('400');
			$group_param.find('input[name="crop"]').val('1').attr("checked","checked");
			$group_param.find('.img_size_key').attr({'id': 'img_size_' + (parseInt($idx) + 1)});
			$group_param.find('.img_size_key').val('600,400,1');
			$group_param.find('.img_size_key').attr({'name': '<?php echo $option_name ?>[<?php echo $name ?>][' + (parseInt($idx) + 1) + ']'});

			$group_param.appendTo($this_group);
			
			img_sizes_update();
			delele_param();
		});

		function img_sizes_update(){
			$('#<?php echo esc_attr($name);?> .img-size-item').each(function(){
				var $this = $(this);
				
				$this.find("input").on("change keyup paste", function(){

					var $index = $this.attr('data-index');
					var $this_size = $this.find('#<?php echo esc_attr($name);?>_'+$index).val();

					var $this_size_ar = $this_size.split(',');
					
					var $val = $(this).val();
					var $val = parseFloat($(this).val().replace('[^0-9.]+', ''));
					var $type = $(this).attr('type');

					// validate value
					if ( $.isNumeric($val) === false ) {
						$val = 0;
						$(this).val($val);
					}
						
					if( $type == 'checkbox' ){
						if( $(this).is(':checked') ){
							$val = "1";
						}else{
							$val = "0";
						}
					} //console.log($val);

					var $data_type = $(this).closest(".dt-form-row-field").attr("data-id");
					if( $data_type == 'width' ){
						$this_size_ar[0] = parseInt($val);
					}
					else if( $data_type == 'height' ){
						$this_size_ar[1] = parseInt($val);
					}
					else if( $data_type == 'crop'  ){
						$this_size_ar[2] = parseInt($val);
					}

					var $this_size_new = $this_size_ar.join(","); //console.log($this_size_new);

					$('#<?php echo esc_attr($name);?>_'+$index).val($this_size_new);
					
				});
			});
		}
		img_sizes_update();
		function delele_param(){
			$('.img-size-item-delete').live('click', function(e){
				e.stopPropagation();
				var $param = $(this).closest('.dt-form-group-item');
				if( !confirm("<?php echo esc_html__( 'Are you sure want to delete the element?', 'dawnthemes' ); ?>") ) return false;
				$param.remove();
			});
		}
		delele_param();
	});
</script>