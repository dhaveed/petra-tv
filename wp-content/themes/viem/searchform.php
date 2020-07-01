<?php
/**
 * The template for displaying Search form
 *
 * @package dawn
 */
?>
<form role="search" method="GET" id="searchform" class="search-form dt-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="sr-only"><?php esc_html_e( 'Search', 'viem' ); ?></label>
	<input type="hidden" value="post" name="post_type">
	<input type="search" id="s" name="s" class="form-control" value="<?php echo get_search_query(); ?>" placeholder="<?php esc_attr_e( 'Search&hellip;', 'viem' ); ?>" />
	<button type="submit" class="search-submit" name="submit"><i class="fa fa-search"></i><?php esc_html_e('Search', 'viem'); ?></button>
</form>
