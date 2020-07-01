<?php
/**
 * The Template for displaying all single posts
 *
 * @package dawn
 */

$layout = viem_get_theme_option('single-layout', 'right-sidebar');
$meta_layout = viem_get_post_meta('single-layout', '');
if( !empty($meta_layout) )
	$layout = $meta_layout;

$style = viem_get_theme_option('single-style', 'style-1');
$meta_style = viem_get_post_meta('single-style', '');
if( !empty($meta_style))
	$style = $meta_style;
viem_display_sidebar($layout);
get_header(); ?>

<div class="single-content">
	<?php
	viem_dt_get_template("template-parts/single/content-{$style}.php", array('layout'=>$layout, 'style'=>$style));
	?>
</div><!-- #container -->
<?php
get_footer();
