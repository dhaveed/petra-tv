<?php
$output = '';
extract(shortcode_atts(array(
	'title'=>'',
	'position'=>'',
	'image'=>'',
	'img_size'=> '',
	'url'=>'#',
), $atts));
if( $img_size == '' || $img_size == 'default' ){
	$img_size = 'thumbnail';
}
$image = wp_get_attachment_image_url($image, $img_size);
?>
<div class="member-item">
	<a href="<?php echo esc_url($url)?>" title='<?php echo esc_attr($title)?>'>
		<?php if($image):?>
		<div class="member-avatar">
			<img src="<?php echo esc_url($image)?>" alt="<?php echo esc_attr($title)?>">
		</div>
		<?php endif;?>
		<div class="member-info">
			<h3 class="member-name"><?php echo esc_html($title)?></h3>
			<span class="member-position"><?php echo esc_html($position)?></span>
		</div>
	</a>
</div>