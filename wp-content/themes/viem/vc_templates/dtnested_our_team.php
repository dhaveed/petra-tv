<?php
$output = '';
extract(shortcode_atts(array(
	'title'					=>'',
	'desc'					=>'',
	'transition_style'		=>'false',
	'autoplay'				=>'true',
	'columns'				=>4,
	'show_control'			=>'',
	'el_class'				=>'',
), $atts));
$el_class  = !empty($el_class) ? ' '.esc_attr( $el_class ) : '';
$id = uniqid('viem_sc_our_team_');
?>
<div class="viem-sc-our-team wpb_content_element <?php echo esc_attr($el_class)?>">
	<?php if(!empty($title)):?>
		<h3 class="viem-sc-title"><span><?php echo esc_html($title)?></span>
			<?php if(!empty($desc)):?>
			<div class="viem-sc-desc"><?php echo esc_html($desc)?></div>
			<?php endif;?>
		</h3>
	<?php endif;?>
	<div id="<?php echo esc_attr($id) ?>" class="owl-carousel viem-carousel-slide viem-preload" data-autoplay="<?php echo esc_attr($autoplay);?>" data-dots="0" data-nav="<?php echo ('yes'===$show_control) ? '1':'0' ?>" data-items="<?php echo esc_attr($columns) ?>" data-rtl = "<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
		<?php echo wpb_js_remove_wpautop( $content );?>
	</div>
</div>