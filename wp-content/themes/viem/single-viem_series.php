<?php
/**
 * The Template for displaying all single playlist
 *
 * @package dawn
 */

$style = viem_get_theme_option('single-video-series-style', 'series-1');
$layout = viem_get_theme_option('single-video-series-layout', 'full-width');
$sidebar = viem_get_theme_option('single-video-series-sidebar', 'playlist-sidebar');
viem_display_sidebar($layout, $sidebar);
get_header(); ?>

<div class="single-content">
	<?php
	viem_dt_get_template("template-parts/single-series/content-{$style}.php", array('layout'=>$layout, 'style'=>$style));
	?>
</div><!-- #container -->

<?php
get_footer();
