<?php
$output = '';
extract(shortcode_atts(array(
	'title'=>'',
	'text'=>'Q&A content goes here, click edit button to change this text.',
), $atts));
?>
<div class="viem_faq-item">
	<div class="faq-item">
		<div class="faq-item-info">
			<?php if(!empty($title)):?>
			<div class="faq-item-title font-2">
				<span class="label-faq font-2"><?php esc_html_e('Q.','viem')?></span>
				<?php echo esc_html($title)?>
			</div>
			<?php endif;?>
			<div class="faq-item-asw">
				<span class="label-faq font-2"><?php esc_html_e('A.','viem')?></span>
				<?php echo esc_html($text)?>
			</div>
		</div>
	</div>
</div>