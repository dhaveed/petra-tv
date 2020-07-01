<?php
extract( shortcode_atts( array(
	'template' 				=> 'lily',
	'banner' 				=> '',
	'title' 				=> 'Button',
	'desc' 					=> 'Lily likes to play with crayons and pencils',
	'href' 					=> '',
	'target'				=>'_self',
	'img_size'				=>'',
	'visibility'			=>'',
	'el_class'				=>'',
), $atts ) );

if($banner == ''){
	return;
}
$sc_id = uniqid('dt_sc_');
$class = !empty($el_class) ?  ' '.esc_attr( $el_class ) : '';
$class .= viem_dt_visibility_class($visibility);
$img_size = ( $img_size == '' || $img_size == 'default' ) ? 'large' : $img_size;
$banner_url = wp_get_attachment_image_url($banner,$img_size);
if ( $target == 'same' || $target == '_self' ) {
	$target = '';
}
$target = ( $target != '' ) ? ' target="' . $target . '"' : '';

ob_start();
?>
<div class="dawnthemes-banner wpb_content_element <?php echo esc_attr($class);?>">
	<figure class="effect-<?php echo esc_attr($template);?>">
		<img src="<?php echo esc_url($banner_url)?>" alt="<?php echo esc_attr($title);?>"/>
		<figcaption>
			<h2><?php echo wp_kses($title, array('strong'=>array()));?></h2>
			<p><?php echo wp_kses($desc, array('br'=>array()));?></p>
			<?php 
			if( $href !='' ):
			?>
			<a href="<?php echo esc_url($href)?>" <?php echo ($target) ? 'target="'.esc_attr($target).'"' : '';?>><?php esc_html_e('View more','viem')?></a>
			<?php endif;?>
		</figcaption>			
	</figure>
</div>
<?php
echo ob_get_clean();