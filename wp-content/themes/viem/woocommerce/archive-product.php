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
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
?>
<?php 
$layout = viem_get_theme_option('woo-shop-layout','full-width');
viem_display_sidebar($layout);
?>
<?php get_header() ?>
<main id="main" class="content-container"  role="main">
	<div class="<?php viem_get_main_class() ?>">
		<div class="row content-section">
			<?php do_action('viem_dt_left_sidebar')?>
			<div class="<?php echo esc_attr(viem_get_main_class($layout)) ?>">
				<div class="main-content">
						<?php if ( apply_filters( 'woocommerce_show_page_title', false ) ) : ?>
				
							<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
				
						<?php endif; ?>
				
						<?php
							/**
							 * woocommerce_archive_description hook.
							 *
							 * @hooked woocommerce_taxonomy_archive_description - 10
							 * @hooked woocommerce_product_archive_description - 10
							 */
							do_action( 'woocommerce_archive_description' );
						?>
				
						<?php if ( have_posts() ) : ?>
								<div class="shop-loop-toolbar">
								<?php
									/**
									 * Hook: woocommerce_before_shop_loop.
									 *
									 * @hooked wc_print_notices - 10
									 * @hooked woocommerce_result_count - 20
									 * @hooked woocommerce_catalog_ordering - 30
									 */
									do_action( 'woocommerce_before_shop_loop' );
								?>
								</div>
								<?php

								woocommerce_product_loop_start();

								if ( wc_get_loop_prop( 'total' ) ) {
									while ( have_posts() ) {
										the_post();

										/**
										 * Hook: woocommerce_shop_loop.
										 *
										 * @hooked WC_Structured_Data::generate_product_data() - 10
										 */
										do_action( 'woocommerce_shop_loop' );

										wc_get_template_part( 'content', 'product' );
									}
								}

								woocommerce_product_loop_end();
					
								/**
								 * Hook: woocommerce_after_shop_loop.
								 *
								 * @hooked woocommerce_pagination - 10
								 */
								do_action( 'woocommerce_after_shop_loop' );
							else:
								/**
								 * Hook: woocommerce_no_products_found.
								 *
								 * @hooked wc_no_products_found - 10
								 */
								do_action( 'woocommerce_no_products_found' );
							
							endif; ?>
				</div>
			</div>
			<?php do_action('viem_dt_right_sidebar')?>
		</div>
	</div>
</main>
<?php get_footer() ?>
