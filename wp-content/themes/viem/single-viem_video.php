<?php
/**
 * The Template for displaying all single video
 *
 * @package dawn
 */

$layout = viem_get_theme_option('single-video-layout', 'right-sidebar');
$meta_layout = viem_get_post_meta('single-video-layout', '');
if( !empty($meta_layout) )
	$layout = $meta_layout;

$style = viem_get_theme_option('single-video-style', 'style-1');
$meta_style = viem_get_post_meta('single-video-style', '');
if( !empty($meta_style))
	$style = $meta_style;

$sidebar = 'video-sidebar';
viem_display_sidebar($layout, $sidebar);
get_header(); ?>

<div class="single-content">
	<?php
	viem_dt_get_template("template-parts/single-video/content-video-{$style}.php", array('layout'=>$layout, 'style'=>$style));
	?>
</div><!-- #container -->

<?php
get_footer();
