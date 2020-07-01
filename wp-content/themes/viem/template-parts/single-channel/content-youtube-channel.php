<?php
/**
 * The template part for displaying channel discussion
 *
 * @package Dawn
 */
?>
<div class="viem-video-player-wrapper">
	<div id="v-container">
		<?php 
		viem_video_featured( '', $youtube_channel_id );
		?>
	</div>
	<?php 
	if( function_exists('viem_video_features') ){
		//viem_video_features('');
	}
	?>
</div>
<div id="viem_background_lamp"></div>

