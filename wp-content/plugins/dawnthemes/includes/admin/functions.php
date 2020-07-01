<?php

function dawnthemes_render_meta_boxes($post, $meta_box) {
	$args = $meta_box ['args'];
	if(!defined('DT_META_BOX_NONCE')):
		define('DT_META_BOX_NONCE', 1);
	
	wp_nonce_field ('dt_meta_box_nonce', 'dt_meta_box_nonce',false);
	endif;
		
	if (! is_array ( $args ))
		return false;
		
	echo '<div class="dawnthemes-meta-options dt-metaboxes hidden">';
	if (isset ( $args ['description'] ) && $args ['description'] != '') {
		echo '<p>' . $args ['description'] . '</p>';
	}
	$count = 0;
	foreach ( $args ['fields'] as $field ) {
		if(!isset($field['type']) )
			continue;
		
		$field_class = '';

		$field['name']  = isset( $field['name'] ) ? $field['name'] : '';
		$field['name'] 	= strstr( $field['name'], '_dt_' ) ? sanitize_title( $field['name'] ) : '_dt_' . sanitize_title( $field['name'] );
		
		$value = get_post_meta( $post->ID,$field['name'], true );
	
		$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
		if($value !== '' && $value !== null && $value !== array() && $value !== false)
			$field['value'] = $value;
	
		$field['id'] 			= isset( $field['id'] ) ? $field['id'] : $field['name'];
		$field['description'] 	= isset($field['description']) ? $field['description'] : '';
		$field['label'] 		= isset( $field['label'] ) ? $field['label'] : '';
		$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : $field['label'];
		$field['width']   		= isset( $field['width'] ) ? $field['width'] : '99%';
		
		$field['name'] = 'dt_meta['.$field['name'].']';

		$field['dependency']	= isset( $field['dependency'] ) ? $field['dependency'] : '';
		$dependency = '';
		if( $field['dependency'] != '' ){
			$dependency = ' data-dependency="'. $field['dependency']["field"] .'" ';
			$dependency .= ' data-dependency-value="'.implode(',', $field['dependency']['value']).'"';
			$field_class .= ' dt_meta_field_dependency ';
		}

		if( isset($field['callback']) && !empty($field['callback']) ) {
			call_user_func($field['callback'], $post,$field);
		} else {
			switch ($field['type']){
				case 'heading':
					echo '<h4 class="dt-meta-box-heading">'.$field['heading'].'</h4>';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					break;
				break;
				case 'hr':
					echo '<div style="margin-top:20px;margin-bottom:20px;">';
					echo '<hr>';
					echo '</div>';
					break;
				case 'message':
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><spanclass="' . esc_attr( $field_class ) . '" '.$dependency.' style="width: 99%;">' . esc_attr( $field['value'] ) . '</span>';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					break;
				case 'text':
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><input type="text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" class="' . esc_attr( $field_class ) . '" '.$dependency.' style="width: '. $field['width'] .'" /> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					if(isset($field['hidden']) && $field['hidden'] == true){
						$field['name'] = 'dt_meta['.$field['name'].'_hidden]';
						echo '<input type="hidden" name="' . esc_attr( $field['name'] ) . '" value="' . esc_attr( $field['value'] ) . '">';
					}
					echo '</div>';
					break;
				case 'color':
					wp_enqueue_style( 'wp-color-picker');
					wp_enqueue_script( 'wp-color-picker');
					
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><input type="text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '"  class="' . esc_attr( $field_class ) . '" '.$dependency.'  /> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '<script type="text/javascript">
						jQuery(document).ready(function($){
						    $("#'. esc_attr( $field['id'] ).'").wpColorPicker();
						});
					 </script>
					';
					echo '</div>';
					break;
				case 'textarea':
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><textarea name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '"  class="' . esc_attr( $field_class ) . '" '.$dependency.' rows="3" cols="20" style="width: '. $field['width'] .'">' . esc_textarea( $field['value'] ) . '</textarea> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					break;
				case 'checkbox':
					$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : '1';
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '"><strong>' . esc_html( $field['label'] ) . '</strong></label><input type="checkbox" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="0"  checked="checked" style="display:none" /><input class="checkbox" type="checkbox" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '"  class="' . esc_attr( $field_class ) . '" '.$dependency.'  value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . ' /> ';
					if ( ! empty( $field['description'] ) ) echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
						
					echo '</div>';
					break;
				case 'categories':
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label>';
					wp_dropdown_categories(array(
						'name'=>esc_attr( $field['name'] ),
						'id'=>esc_attr( $field['id'] ),
						'hierarchical'=>1,
						'selected'=>$field['value']
					));
					echo '</div>';
				break;
				case 'widgetised_sidebars':
					$sidebars = $GLOBALS['wp_registered_sidebars'];
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '">';
					echo '<option value=""' . $selected . '>' . __('Select a sidebar...','dawnthemes') . '</option>';
					foreach ( $sidebars as $sidebar ) {
						$selected = '';
						if ( $sidebar["id"] == $field['value'] ) $selected = ' selected="selected"';
						$sidebar_name = $sidebar["name"];
						echo '<option value="' . $sidebar["id"] . '"' . $selected . '>' . $sidebar_name . '</option>';
					}
					echo '</select> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					break;
				case 'select':
					$field['options'] = isset( $field['options'] ) ? $field['options'] : array();
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><select '.(isset($field['multiple']) ? 'multiple':'').' id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ).(isset($field['multiple']) ? '[]':'') . '"   class="' . esc_attr( $field_class ) . '" '.$dependency.' '.(isset($field['chosen']) ? 'style="width:99%"':'').'>';
					foreach ( $field['options'] as $key => $value ) {
						echo '<option value="' . esc_attr( trim($key) ) . '" ' . (isset($field['multiple']) ? (in_array(esc_attr( $key ),(array) $field['value']) ? ' selected="selected"':''):selected( esc_attr( $field['value'] ), esc_attr( $key ), false )) . '>' . esc_html( $value ) . '</option>';
					}
					echo '</select> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					if(isset($field['chosen'])){
						wp_enqueue_script('chosen');
						wp_enqueue_style('chosen');
						$placeholder_text = isset( $field['placeholder'] ) ? $field['placeholder'] : esc_html__('Select an option','dawnthemes');
						?>
						<script type="text/javascript">
						jQuery(document).ready(function(){
				    		jQuery("select#<?php echo esc_attr( $field['id'] )?>").chosen({
								"disable_search_threshold":10,
								"allow_single_deselect":true,
								"placeholder_text":"<?php echo esc_js($placeholder_text)?>"
							});
						});
						</script>
						<?php
						}
					break;
				case 'chosen-order':
					wp_enqueue_script('chosen');
					wp_enqueue_script('chosen-order');
					wp_enqueue_style('chosen');
					
					$field['options'] = isset( $field['options'] ) ? $field['options'] : array();
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label>';
					?>
	                <select id="<?php echo esc_attr( $field['id'] )?>" class="form-control chosen" multiple>
	                    <?php foreach ( $field['options'] as $key => $value ) {?>
							<option value="<?php echo trim(esc_attr( $key ));?>"><?php echo esc_html( $value ) ?></option>
						<?php } ?>
						
	                </select>
	           		<?php
	           		if ( ! empty( $field['description'] ) ) {
							echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					$placeholder_text = isset( $field['placeholder'] ) ? $field['placeholder'] : esc_html__('Select some options','dawnthemes');
					?>
           			<input id="<?php echo esc_attr( $field['id'] )?>-input-order" type="hidden" name="<?php echo esc_attr( $field['name'] );?>" value="<?php echo esc_attr( implode(", ", (array)trim($field['value'])) )?>">
           
			        <script type="text/javascript">
			        	jQuery(window).on("load",function(){
					        jQuery('select#<?php echo esc_attr( $field['id'] )?>').chosen({
								"disable_search_threshold":10,
								"allow_single_deselect":true,
								"placeholder_text":"<?php echo esc_js($placeholder_text)?>"
							});
				        	var MY_SELECT = jQuery(jQuery('select#<?php echo esc_attr( $field['id'] )?>').get(0));
				        	<?php if( !empty( $field['value'] ) ){?>
				        		MY_SELECT.setSelectionOrder(jQuery('#<?php echo esc_attr( $field['id'] )?>-input-order').val().split(','), true);
					        <?php } ?>
					         jQuery('select#<?php echo esc_attr( $field['id'] )?>').on('change', function(){
						         setTimeout(function(){
						        	 var selection = MY_SELECT.getSelectionOrder();
							         console.log(selection);
							         var inputOrder = selection.join(', ');
							         console.log(inputOrder);
							         jQuery('#<?php echo esc_attr( $field['id'] )?>-input-order').attr('value',inputOrder);
							     }, 300);
						     });
				        });
		    		</script>
					<?php 
					echo '</div>';
					
					break;
				case 'radio':
					$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
					echo '<fieldset '.$data_dependency.' class="form-field ' . esc_attr( $field['id'] ) . '_field"><legend>' . esc_html( $field['label'] ) . '</legend><ul>';
					foreach ( $field['options'] as $key => $value ) {
						echo '<li><label><input
					        		name="' . esc_attr( $field['name'] ) . '"
					        		value="' . esc_attr( $key ) . '"
					        		type="radio"
									class="radio"
					        		' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
					        		/> ' . esc_html( $value ) . '</label>
					    	</li>';
					}
					echo '</ul>';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</fieldset>';
					break;
				case 'gallery':
					if(function_exists( 'wp_enqueue_media' )){
						wp_enqueue_media();
					}else{
						wp_enqueue_style('thickbox');
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
					}
					
					if(!defined('_DT_META_GALLERY_JS')):
					define('_DT_META_GALLERY_JS', 1);
					?>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							$('.dt-meta-gallery-select').on('click',function(e){
								e.stopPropagation();
								e.preventDefault();
								
								var $this = $(this),
									dt_meta_gallery_list = $this.closest('.dt-meta-box-field').find('.dt-meta-gallery-list'),
									dt_meta_gallery_frame,
									dt_meta_gallery_ids = $this.closest('.dt-meta-box-field').find('#dt_meta_gallery_ids'),
									_ids = dt_meta_gallery_ids.val();
	
								if(dt_meta_gallery_frame){
									dt_meta_gallery_frame.open();
									return false;
								}
								
								dt_meta_gallery_frame = wp.media({
									title: '<?php echo __('Add Images to Gallery','dawnthemes')?>',
									button: {
										text: '<?php echo __('Add to Gallery','dawnthemes')?>',
									},
									library: { type: 'image' },
									multiple: true
								});
	
								dt_meta_gallery_frame.on('select',function(){
									var selection = dt_meta_gallery_frame.state().get('selection');
									selection.map( function( attachment ) {
										attachment = attachment.toJSON();
										if ( attachment.id ) {
											_ids = _ids ? _ids + "," + attachment.id : attachment.id;
											dt_meta_gallery_list.append('\
												<li data-id="' + attachment.id +'">\
													<div class="thumbnail">\
														<div class="centered">\
															<img src="' + attachment.url + '" />\
														</div>\
														<a href="#" title="<?php echo __('Delete','dawnthemes')?>"><?php echo __('x','dawnthemes')?></a></li>\
													</div>\
												</li>'
											);
										}
										dt_meta_gallery_ids.val( dt_trim(_ids,',') );
										dt_meta_gallery_fn();
									});
								});
	
								dt_meta_gallery_frame.open();
							});
							var dt_meta_gallery_fn = function(){
								if($('.dt-meta-gallery-list').length){
									$('.dt-meta-gallery-list').each(function(){
										var $this = $(this);
										$this.sortable({
											items: 'li',
											cursor: 'move',
											forcePlaceholderSize: true,
											forceHelperSize: false,
											helper: 'clone',
											opacity: 0.65,
											placeholder: 'li-placeholder',
											start:function(event,ui){
												ui.item.css('background-color','#f6f6f6');
											},
											update: function(event, ui) {
												var _ids = '';
												$this.find('li').each(function() {
													var _id = $(this).data( 'id' );
													_ids = _ids + _id + ',';
												});
									
												$this.closest('.dt-meta-box-field').find('#dt_meta_gallery_ids').val( dt_trim(_ids,',') );
											}
										});
	
										$this.find('a').on( 'click',function(e) {
											e.stopPropagation();
											e.preventDefault();
											$(this).closest('li').remove();
											var _ids = '';
											$this.find('li').each(function() {
												var _id = $(this).data( 'id' );
												_ids = _ids + _id + ',';
											});
	
											$this.closest('.dt-meta-box-field').find('#dt_meta_gallery_ids').val( dt_trim(_ids,',') );
	
											return false;
										});
										
									});
								}
							}
							dt_meta_gallery_fn();
						});
					</script>
					<?php
					endif;
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field">';
					echo '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label>';
					echo '<div class="dt-meta-gallery-wrap"><ul class="dt-meta-gallery-list">';
					if($field['value']){
						$value_arr = explode(',', $field['value']);
						if(!empty($value_arr) && is_array($value_arr)){
							foreach ($value_arr as $attachment_id ){
								if($attachment_id):
							?>
								<li data-id="<?php echo esc_attr( $attachment_id ) ?>">
									<div class="thumbnail">
										<div class="centered">
											<?php echo wp_get_attachment_image( $attachment_id, array(120,120) ); ?>						
										</div>
										<a title="<?php echo __('Delete','dawnthemes') ?>" href="#"><?php echo __('x','dawnthemes') ?></a>
									</div>						
								</li>
							<?php
								endif;
							}
						}
					}
					echo '</ul></div>';
					echo '<input type="hidden" name="' . $field['name'] . '" id="dt_meta_gallery_ids" value="' . $field['value'] . '" />';
					echo '<input type="button" class="button button-primary dt-meta-gallery-select" name="' . $field['id'] . '_button_upload" id="' . $field['id'] . '_upload" value="' . __('Add Gallery Images','dawnthemes') . '" /> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
				break;
				case 'media':
					if(function_exists( 'wp_enqueue_media' )){
						wp_enqueue_media();
					}else{
						wp_enqueue_style('thickbox');
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
					}
					$btn_text = !empty(  $field['value'] ) ? __( 'Change Media', 'dawnthemes' ) : __( 'Select Media', 'dawnthemes' );
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field">';
					echo '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label>';
					echo '<input type="text" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" style="width: 99%;margin-bottom:5px" />';
					echo '<input type="button" class="button button-primary" name="' . $field['id'] . '_button_upload" id="' . $field['id'] . '_upload" value="' . $btn_text . '" /> ';
					echo '<input type="button" class="button" name="' . $field['id'] . '_button_clear" id="' . $field['id'] . '_clear" value="' . __( 'Clear', 'dawnthemes' ) . '" />';				
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					?>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							<?php if ( empty ( $field['value'] ) ) : ?> $('#<?php echo esc_attr($field['id']); ?>_clear').css('display', 'none'); <?php endif; ?>
							$('#<?php echo esc_attr($field['id']) ?>_upload').on('click', function(event) {
								event.preventDefault();
								var $this = $(this);
		
								// if media frame exists, reopen
								if(dt_<?php echo esc_attr($field['id']); ?>_media_frame) {
									dt_<?php echo esc_attr($field['id']); ?>_media_frame.open();
					                return;
					            }
		
								// create new media frame
								// I decided to create new frame every time to control the selected images
								var dt_<?php echo esc_attr($field['id']); ?>_media_frame = wp.media.frames.wp_media_frame = wp.media({
									title: "<?php echo __( 'Select or Upload your Media', 'dawnthemes' ); ?>",
									button: {
										text: "<?php echo __( 'Select', 'dawnthemes' ); ?>"
									},
									library: { type: 'video,audio' },
									multiple: false
								});
		
								// when image selected, run callback
								dt_<?php echo esc_attr($field['id']); ?>_media_frame.on('select', function(){
									var attachment = dt_<?php echo esc_attr($field['id']); ?>_media_frame.state().get('selection').first().toJSON();
									$this.closest('.dt-meta-box-field').find('input#<?php echo esc_attr($field['id']); ?>').val(attachment.url);
									
									$this.attr('value', '<?php echo __( 'Change Media', 'dawnthemes' ); ?>');
									$('#<?php echo esc_attr($field['id']); ?>_clear').css('display', 'inline-block');
								});
		
								// open media frame
								dt_<?php echo esc_attr($field['id']); ?>_media_frame.open();
							});
		
							$('#<?php echo esc_attr($field['id']) ?>_clear').on('click', function(event) {
								var $this = $(this);
								$this.hide();
								$('#<?php echo esc_attr($field['id']) ?>_upload').attr('value', '<?php echo __( 'Select Media', 'dawnthemes' ); ?>');
								$this.closest('.dt-meta-box-field').find('#<?php echo esc_attr($field['id']); ?>').val('');
							});
						});
					</script>
					<?php
				break;
				
				case 'image_select':
					$field['options']       = isset( $field['options'] ) ? $field['options'] : array();
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label>' . esc_html( $field['label'] ) . '</label>';
					echo '<div class="dt-field-image-select">';
					echo '<ul class="dt-image-select">';
					foreach ( $field['options'] as $key => $value ) {
						echo '<li'.((string)$field['value'] === (string)$key ? ' class="selected"':'').'><label for="' . esc_attr( $field['id'].'_'.$key ) . '"><input
			        		name="' . $field['name']  . '"
							id="' . esc_attr( $field['id'].'_'.$key ) . '"
			        		value="' . esc_attr( $key ) . '"
			        		type="radio" 
							class="dt-opt-value image_select"
			        		' . checked( $field['value'], esc_attr( $key ), false ) . '
			        		/><img title="'.esc_attr(@$value['alt']).'" alt="'.esc_attr(@$value['alt']).'" src="'.esc_url(@$value['img']).'"></label>
				    </li>';
					}
					echo '</ul>';
					echo '</div>';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					echo '</div>';
					break;
					
				case 'image':
					if(function_exists( 'wp_enqueue_media' )){
						wp_enqueue_media();
					}else{
						wp_enqueue_style('thickbox');
						wp_enqueue_script('media-upload');
						wp_enqueue_script('thickbox');
					}
					$image_id = $field['value'];
					$image = wp_get_attachment_image( $image_id,array(120,120));
					$output = !empty( $image_id ) ? $image : '';
					$btn_text = !empty( $image_id ) ? __( 'Change Image', 'dawnthemes' ) : __( 'Select Image', 'dawnthemes' );
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field">';
					echo '<label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label>';
					echo '<div class="dt-meta-image-thumb">' . $output . '</div>';
					echo '<input type="hidden" name="' . $field['name'] . '" id="' . $field['id'] . '" value="' . $field['value'] . '"  class="' . esc_attr( $field_class ) . '" '.$dependency.'/>';
					echo '<input type="button" class="button button-primary" name="' . $field['id'] . '_button_upload" id="' . $field['id'] . '_upload" value="' . $btn_text . '" /> ';
					echo '<input type="button" class="button" name="' . $field['id'] . '_button_clear" id="' . $field['id'] . '_clear" value="' . __( 'Clear Image', 'dawnthemes' ) . '" />';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
					?>
					<script type="text/javascript">
						jQuery(document).ready(function($) {
							<?php if ( empty ( $field['value'] ) ) : ?> $('#<?php echo esc_attr($field['id']) ?>_clear').css('display', 'none'); <?php endif; ?>
							$('#<?php echo esc_attr($field['id']) ?>_upload').on('click', function(event) {
								event.preventDefault();
								var $this = $(this);
		
								// if media frame exists, reopen
								if(dt_<?php echo esc_attr($field['id']); ?>_image_frame) {
									dt_<?php echo esc_attr($field['id']); ?>_image_frame.open();
					                return;
					            }
		
								// create new media frame
								// I decided to create new frame every time to control the selected images
								var dt_<?php echo esc_attr($field['id']); ?>_image_frame = wp.media.frames.wp_media_frame = wp.media({
									title: "<?php echo __( 'Select or Upload your Image', 'dawnthemes' ); ?>",
									button: {
										text: "<?php echo __( 'Select', 'dawnthemes' ); ?>"
									},
									library: { type: 'image' },
									multiple: false
								});
		
								// when open media frame, add the selected image
								dt_<?php echo esc_attr($field['id']); ?>_image_frame.on('open',function() {
									var selected_id = $this.closest('.dt-meta-box-field').find('#<?php echo esc_attr($field['id']); ?>').val();
									if (!selected_id)
										return;
									var selection = dt_<?php echo esc_attr($field['id']); ?>_image_frame.state().get('selection');
									var attachment = wp.media.attachment(selected_id);
									attachment.fetch();
									selection.add( attachment ? [ attachment ] : [] );
								});
		
								// when image selected, run callback
								dt_<?php echo esc_attr($field['id']); ?>_image_frame.on('select', function(){
									var attachment = dt_<?php echo esc_attr($field['id']); ?>_image_frame.state().get('selection').first().toJSON();
									$this.closest('.dt-meta-box-field').find('input#<?php echo esc_attr($field['id']); ?>').val(attachment.id);
									var thumbnail = $this.closest('.dt-meta-box-field').find('.dt-meta-image-thumb');
									thumbnail.html('');
									thumbnail.append('<img src="' + attachment.url + '" alt="" />');
		
									$this.attr('value', '<?php echo __( 'Change Image', 'dawnthemes' ); ?>');
									$('#<?php echo esc_attr($field['id']); ?>_clear').css('display', 'inline-block');
								});
		
								// open media frame
								dt_<?php echo esc_attr($field['id']); ?>_image_frame.open();
							});
		
							$('#<?php echo esc_attr($field['id']); ?>_clear').on('click', function(event) {
								var $this = $(this);
								$this.hide();
								$('#<?php echo esc_attr($field['id']); ?>_upload').attr('value', '<?php echo __( 'Select Image', 'dawnthemes' ); ?>');
								$this.closest('.dt-meta-box-field').find('input#<?php echo esc_attr($field['id']); ?>').val('');
								$this.closest('.dt-meta-box-field').find('.dt-meta-image-thumb').html('');
							});
						});
					</script>
								
					<?php
					echo '</div>';
				break;
				case 'timepicker':
				case 'datepicker':
					wp_enqueue_script('datetimepicker');
					wp_enqueue_style('datetimepicker');
					if(isset($field['timestamp']) && !empty($field['value'])){
						if($field['type']==='datepicker'){
							$field['value'] = date_i18n('Y-m-d', ($field['value']) );
						}elseif ($field['type']==='timepicker'){
							$field['value'] = date_i18n('H:i', ($field['value']) );
						}
					}
				echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field"><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label><input type="text" readonly name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" style="width: 99%;" /> ';
					if ( ! empty( $field['description'] ) ) {
						echo '<span class="description">' . dawnthemes_echo( $field['description'] ) . '</span>';
					}
				
					?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						$('#<?php echo esc_attr($field['id']); ?>').datetimepicker({
						 scrollMonth: false,
						 scrollTime: false,
						 scrollInput: false,
						 step:15,
						 <?php if($field['type']==='datepicker'):?>
						 timepicker:false,
						 format:'Y-m-d'
						 <?php elseif($field['type']==='timepicker'):?>
						 datepicker:false,
						 format:'H:i'
						 <?php endif;?>
						});
					});
				</script>
				<?php
				echo '</div>';
				break;

				case 'multilink':
					
					$links = $field['value'];
					$i = 0;
					
					echo '<div  class="dt-meta-box-field ' . esc_attr( $field['id'] ) . '_field">';
					echo '<label for="">' . esc_html( $field['label'] ) . '</label>';
					
					echo '<table id="multi-link' . esc_attr( $field['id'] ) . '" class="dt_multilink">';
				
					if( $links && count($links) > 0 ){ //print_r($links);
						ob_start();
						foreach ($links as $link){
							if( isset($link['title']) &&  !empty($link['title'])){ $i++;
							?>
								<tr data-group="<?php echo esc_attr($i);?>">
									<td>
										<button class="remove-group button"><i class="fa fa-times"></i> <?php echo esc_html__('Remove server');?></button>
									</td>
									<td>
										<input type="text" name="dt_meta[_dt_multi_link][<?php echo $i;?>][title]" value="<?php echo esc_attr($link['title'])?>" placeholder="<?php echo esc_attr__( 'Server Name','dawnthemes' );?>">
										<button class="add-video-id button"><i class="fa fa-plus"></i> <?php echo esc_html__('Add links');?></button>
									</td>
									<td class="mtl-video-ids">
										<?php 
										$j = 0;
										foreach ($link['video_data'] as $key => $video ){ $j++;
											$video_type = $video['video_type'];
											$video_id = $video['video_id'];
											if( !empty($video_id) ){
											?>
											<div data-count-video="<?php echo esc_attr($j);?>">
												<select name="dt_meta[_dt_multi_link][<?php echo $i;?>][video_data][<?php echo $j;?>][video_type]" style="width: 150px">
													<option value="youtube" <?php selected( $video_type, 'youtube' ); ?>><?php echo esc_html__('YouTube', 'dawnthemes') ?></option>
													<option value="HTML5" <?php selected( $video_type, 'HTML5' ); ?>><?php echo esc_html__('HTML5 (self-hosted)', 'dawnthemes') ?></option>
													<option value="vimeo" <?php selected( $video_type, 'vimeo' ); ?>><?php echo esc_html__('Vimeo', 'dawnthemes') ?></option>
												</select>
												<input type="text" name="dt_meta[_dt_multi_link][<?php echo $i;?>][video_data][<?php echo $j;?>][video_id]" value="<?php echo esc_attr($video_id)?>" placeholder="<?php echo esc_attr__('YouTube ID, Vimeo ID or MP4 Video URL', 'dawnthemes'); ?>" style="width: 350px">
												<button class="remove-video-id button"><i class="fa fa-times"></i></button>
											</div>
											<?php } ?>
										<?php } ?>
									</td>
								</tr>
							<?php
							}
						}
						echo ob_get_clean();
					}
					
					echo '</table>';
					
					echo '<br/>';
					
					echo '<button class="add_mul_server button-primary button-large"><i class="fa fa-plus"></i> '.esc_html__('Add Server', 'dawnthemes') . '</button>';
					
					?>
					<script>
						jQuery.noConflict();
						jQuery(document).ready(function($){
							var count = <?php echo $i; ?>;
							$('.add_mul_server').click(function(){
								count = count + 1;
								$('#multi-link<?php echo esc_attr( $field['id'] );?>').append('<tr data-group="'+count+'"><td><button class="remove-group button"><i class="fa fa-times"></i> Remove server</button></td><td><input type="text" name="dt_meta[_dt_multi_link]['+count+'][title]" value="" placeholder="Server Name"><button class="add-video-id button"><i class="fa fa-plus"></i> Add links</button></td><td class="mtl-video-ids" data-count="'+count+'"><div><select name="dt_meta[_dt_multi_link]['+count+'][video_data][1][video_type]" style="width: 150px"><option value="youtube">YouTube</option><option value="vimeo">Vimeo</option><option value="HTML5">HTML5 (self-hosted)</option></select><input type="text" name="dt_meta[_dt_multi_link]['+count+'][video_data][1][video_id]" value="" placeholder="YouTube ID, Vimeo ID or MP4 Video URL" style="width: 350px"></td><td><button class="remove-video-id button"><i class="fa fa-times"></i></button></div></td></tr>');
								return false;
							});
							$('.remove-group').live('click', function(){
								$(this).parent().parent().remove();
							});
							
							$('.add-video-id').click(function(){
								var $group = $(this).parent().parent();
								var $group_num = $($group).attr('data-group');
								var $video_num = $($group).find('.mtl-video-ids > div').last().attr('data-count-video');

								$video_num = parseInt($video_num) + 1;
								
								$($group).find('.mtl-video-ids').append('<div data-count-video="'+$video_num+'">'
										+ '<select name="dt_meta[_dt_multi_link]['+$group_num+'][video_data]['+$video_num+'][video_type]" style="width: 150px">'
										+ '<option value="youtube">YouTube</option>'
										+ '<option value="HTML5">HTML5 (self-hosted)</option>'
										+ '<option value="vimeo">Vimeo</option>'
									+ '</select>'
									+ '<input type="text" name="dt_meta[_dt_multi_link]['+$group_num+'][video_data]['+$video_num+'][video_id]" value="" placeholder="YouTube ID, Vimeo ID or MP4 Video URL" style="width: 350px">'
									+ '<button class="remove-video-id button"><i class="fa fa-times"></i></button>'
								+ '</div>');
								
								return false;
							});
							$('.remove-video-id').live('click', function(){
								$(this).parent().remove();
							});
						});
					</script>
					<?php
									
					echo '</div>';

					break;
				default:
					break;
			}
		}
	}
	
	echo '</div>';
}

/* Envato Theme Update Toolkit */
//add_action('admin_init', 'dawnthemes_on_admin_init' );
function dawnthemes_on_admin_init(){
	$current_theme = wp_get_theme();
	// If there is a submit to update theme
	if( isset($_POST[sanitize_key('dawnthemes_update_theme')]) ){
		dawnthemes_do_update_theme( $current_theme->get('Name') );
	}else{
		// If Auto Update is set
		if( dawnthemes_get_theme_option('envato_auto_update', 0) == 1 ){
			if( dawnthemes_check_for_update( $current_theme->get('Name') ) == 1  ){
				dawnthemes_do_update_theme( $current_theme->get('Name') );
			}
		}
	}
}

function dawnthemes_check_for_update($theme){
	$envato_username = dawnthemes_get_theme_option('envato_username', '');
	$envato_api = dawnthemes_get_theme_option('envato_api', '');
	
	// If user has entered username and api
	if( $envato_username != '' && $envato_api != '' ){
		// include the library
		require_once ( DTINC_DIR . '/lib/envato-wordpress-toolkit/class-envato-wordpress-theme-upgrader.php' );
		
		$upgrader = new Envato_WordPress_Theme_Upgrader( $envato_username , $envato_api );
		
		$update = $upgrader->check_for_theme_update($theme);  // we enter theme name here to make sure if users are using child theme
		
		// found an update
		return $update->updated_themes_count;
	}
}

function dawnthemes_do_update_theme($theme){
	$envato_username = dawnthemes_get_theme_option('envato_username','');
	$envato_api = dawnthemes_get_theme_option('envato_api','');
	
	// if user has entered username and api
	if($envato_username != '' && $envato_api != ''){
		// include the library
		require_once ( DTINC_DIR . '/lib/envato-wordpress-toolkit/class-envato-wordpress-theme-upgrader.php' );
	
		$upgrader = new Envato_WordPress_Theme_Upgrader( $envato_username , $envato_api );
	
		$upgrader->upgrade_theme($theme);
		add_action( 'admin_notices', 'dawnthemes_admin_notice_theme_updated' );
	}
}

function dawnthemes_admin_notice_theme_updated(){
	?>
    <div class="updated">
        <p><?php esc_html_e( 'Theme Updated!', 'dawnthemes' ); ?></p>
    </div>
    <?php
}
