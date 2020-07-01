<?php
/**
 * The Template for displaying all single channel
 *
 * @package dawn
 */

$style = viem_get_theme_option('single-actor-style', 'actor-1');
$layout = 'full-width';
get_header(); ?>
<div class="single-content">
	<?php
	viem_dt_get_template("template-parts/single-actor/content-{$style}.php", array('layout'=>$layout, 'style'=>$style));
	?>
</div><!-- #container -->

<?php
get_footer();
