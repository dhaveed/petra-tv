<?php
/**
 * viem Template Hooks
 *
 * Action/filter hooks used for theme functions/templates.
 *
 * @author 	DawnThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// this is used to set cookie to detect if screen is retina
add_action('wp_head', 'viem_detect_is_retina', 10);

// Retina Logo
add_action('wp_head', 'viem_retina_logo', 10);

add_filter('viem_megamenu_preview_posts_per_page', 'viem_megamenu_preview_posts_per_page');

/**
 * Search in Top Toolbar
 *
 * @see viem_top_toolbar_search_block()
 */
add_filter('viem_top_toolbar_search', 'viem_top_toolbar_search_block', 10);

/**
 * Page heading
 *
 * @see viem_page_heading()
 */
add_action('viem_page_heading', 'viem_page_heading_content', 10);

/**
 * Breadcrumbs
 *
 * @see viem_breadcrumbs()
 */
add_action('viem_breadcrumbs', 'viem_breadcrumbs', 10);

/**
 * Ajax Tab load more
 *
 * @see viem_tab_loadmore()
 */
add_action('wp_ajax_viem_tab_loadmore', 'viem_tab_loadmore' );
add_action('wp_ajax_nopriv_viem_tab_loadmore', 'viem_tab_loadmore' );

/**
 * Ajax load navigation
 *
 * @see viem_nav_content()
 */
add_action('wp_ajax_viem_nav_content', 'viem_nav_content' );
add_action('wp_ajax_nopriv_viem_nav_content', 'viem_nav_content' );

/*
 * Show more single post content
 */
add_action('viem_after_single_post_content', 'viem_showmore_post_content');

/**
 * Go to Top
 *
 * @see viem_dt_gototop()
 */
add_action('wp_footer', 'viem_dt_gototop');

/**
 * notification-action-renderer
 *
 * @see viem_popup_container()
 */
add_action('wp_footer', 'viem_popup_container');

/**
 * Custom WP Footer
 */
add_action('wp_footer','viem_wp_foot',100);

/**
 * Add custom CSS theme
 *
 * @see viem_dt_custom_css()
 */
add_action( 'viem_main_inline_style', 'viem_dt_custom_css', 10000,1 );
