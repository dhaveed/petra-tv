<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php 
$layout = viem_get_theme_option('woo-product-layout','right-sidebar');
$sidebar = viem_get_theme_option('woo-product-sidebar');
viem_display_sidebar($layout);
?>
<?php get_header() ?>
<main id="main" class="content-container"  role="main">
	<div class="<?php viem_get_main_class() ?>">
		<div class="row content-section">
			<?php do_action('viem_dt_left_sidebar')?>
			<div class="<?php echo esc_attr(viem_get_main_class($layout)) ?>">
				<div class="main-content">
						<?php while ( have_posts() ) : the_post(); ?>

							<?php wc_get_template_part( 'content', 'single-product' ); ?>
				
						<?php endwhile; // end of the loop. ?>
				</div>
			</div>
			<?php do_action('viem_dt_right_sidebar')?>
		</div>
	</div>
</main>
<?php get_footer() ?>
