<?php
$output = '';
extract(shortcode_atts(array(
	'title'=>'',
	'image'=>'',
	'img_size'=> '',
	'href'=> '#',
	'target'=>'_self',
	'use_nofollow'=>'',
), $atts));
if( $img_size == '' || $img_size == 'default' ){
	$img_size = 'thumbnail';
}
$image = wp_get_attachment_image_url($image, $img_size);
if ( $target == 'same' || $target == '_self' ) {
	$target = '';
}
$target = ( $target != '' ) ? ' target="' . $target . '"' : '';
?>
<li>
	<?php if($image):?>
	<a href="<?php echo esc_url($href); ?>" <?php echo ($target);?> <?php echo ( !empty($use_nofollow) ? 'rel="nofollow"' : '' ) ?>>
		<img src="<?php echo esc_url($image)?>" alt="<?php echo esc_attr($title)?>">
	</a>
	<?php endif;?>
</li>