<?php
$output = '';
extract(shortcode_atts(array(
	'title'=>'',
	'star'=> '',
	'text'=>'I am testimonial. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.',
	'author'=>'',
	'position'=>'',
	'avatar'=>'',
), $atts));
$rating = absint($star);
$avatar_image = wp_get_attachment_image_url($avatar,'viem-250x250');
?>
<div class="testimonial-carousel-slide-item">
	<div class="testimonial-item">
		<?php if($avatar_image):?>
		<div class="testimonial-item-author-avatar">
			<img src="<?php echo esc_url($avatar_image)?>" alt="<?php echo esc_attr($author)?>">
		</div>
		<?php endif;?>
		<div class="testimonial-item-info">
			<?php /*if(!empty($title)):?>
			<div class="testimonial-item-title font-2">
				<?php echo esc_html($title)?>
			</div>
			<?php endif;*/?>
			<div class="testimonial-item-comment">
				<?php echo esc_html($text)?>
			</div>
		</div>
		<div class="testimonial-item-author">
			<div class="testimonial-item-author-info">
				<?php if(!empty($star)):?>
				<div class="testimonial-item-star" title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'viem' ), esc_attr( $rating ) ) ?>">
					<span style="width:<?php echo ( esc_attr( $rating ) / 5 ) * 100; ?>%"></span>
				</div>
				<?php endif;?>
				<h4 class="testimonial-item-author-info-name font-2">
					<?php echo esc_html($author)?>
				</h4>
				<?php if(!empty($position)):?>
				<div class="testimonial-item-author-info-position font-2">
					<span></span><?php echo esc_html($position)?>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>