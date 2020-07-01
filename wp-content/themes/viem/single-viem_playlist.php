<?php
/**
 * The Template for displaying all single playlist
 *
 * @package dawn
 */

$style = viem_get_theme_option('single-playlist-style', 'playlist-1');
$layout = viem_get_theme_option('single-playlist-layout', 'right-sidebar');
$sidebar = viem_get_theme_option('single-playlist-sidebar', 'playlist-sidebar');
viem_display_sidebar($layout, $sidebar);
get_header(); ?>

<div class="single-content">
	<?php
	viem_dt_get_template("template-parts/single-playlist/content-{$style}.php", array('layout'=>$layout, 'style'=>$style));
	?>
</div><!-- #container -->

<?php
get_footer();
