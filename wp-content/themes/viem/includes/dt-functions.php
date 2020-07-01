<?php
/**
 * viem Template
 *
 * @author   DawnThemes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require ( get_template_directory() . '/includes/functions/helpers.php' );

function viem_get_theme_option($option,$default = null){
	if( function_exists('dawnthemes_get_theme_option') ){ 
		$value = dawnthemes_get_theme_option($option, $default);
		return apply_filters('viem_get_theme_option', $value, $option);
	}else{
		return $default;
	}
}

function viem_get_post_meta($meta = '',$post_id='',$default=null){
	if( function_exists('dawnthemes_get_post_meta') ){
		$value = dawnthemes_get_post_meta($meta, $post_id, $default);
		return apply_filters('viem_get_post_meta', $value, $meta, $post_id);
	}else{
		return $default;
	}
}

function _viem_get_options_select_post($post_type='post'){
	$options = array();
	$args = array(
		'post_type'        => $post_type,
		'post_status'      => 'publish',
		'posts_per_page'   => -1
	);
	$results = get_posts($args);
	$options[]='';
	foreach ( $results as $result ) {

		if( !empty( $result->post_title ) ) {

			$options[$result->ID] = $result->post_title;

		}
	}
	return $options;

}

function _viem_get_options_select_product($post_type='product'){
	if ( viem_is_woocommerce_activated() ){
		$options = array();
		$args = array(
			'post_type'        => $post_type,
			'post_status'      => 'publish',
			'posts_per_page'   => -1
		);
		$results = get_posts($args);
		$options[]= esc_html__('-- Select Product --', 'viem');
		foreach ( $results as $result ) {

			if( !empty( $result->post_title ) ) {

				$options[$result->ID] = $result->post_title;

			}
		}
		return $options;
	}
}

function _viem_get_options_select_product_total_sales($product_id=''){
	if ( viem_is_woocommerce_activated() && !empty($product_id)){
		$total_sales = get_post_meta($product_id, 'total_sales', true);
		return $total_sales;
	}
}

function _viem_get_options_day(){
	global $wp_locale;
	$options = array();
	for ($day_index = 0; $day_index <= 6; $day_index++) :
	$options[$day_index] = $wp_locale->get_weekday($day_index);
	endfor;
	return $options;
}

function viem_get_main_class($layout = null){
	$class = viem_dt_get_main_col_class($layout);
	
	$class .=' main-wrap';
	$class =  apply_filters('viem_get_main_class',$class);
	return esc_attr($class);
}

if( !function_exists('viem_get_formatted_string_number') ){
	function viem_get_formatted_string_number($n, $decimals = 2, $suffix = ''){
		if(!$suffix)
			$suffix = 'K,M,B';
		$suffix = explode(',', $suffix);
		if ($n < 1000) { // any number less than a Thousand
			if($n=='' || $n==null){
				$n = 0;
			};
			$shorted = number_format($n);
		} elseif ($n < 1000000) { // any number less than a million
			$shorted = number_format($n/1000, $decimals).$suffix[0];
		} elseif ($n < 1000000000) { // any number less than a billion
			$shorted = number_format($n/1000000, $decimals).$suffix[1];
		} else { // at least a billion
			$shorted = number_format($n/1000000000, $decimals).$suffix[2];
		}
		return $shorted;
	}
}

if( !function_exists('viem_get_post_views') ){
	/*
	 * Function to display number of posts.
	 */
	function viem_get_post_views($post_id, $echo = false){
		$count_key = 'post_views_count';
		$count = get_post_meta($post_id, $count_key, true);
		if($count == ''){
			delete_post_meta($post_id, $count_key);
			add_post_meta($post_id, $count_key, '0');
			$views_count = 0;
		}
		if( $echo == true ){
			echo ( $count == 1 ) ? sprintf( esc_html__('%s View', 'viem'), viem_get_formatted_string_number($count) ) : sprintf( esc_html__('%s Views', 'viem'), viem_get_formatted_string_number($count) );
		}else{
			return viem_get_formatted_string_number($count);
		}
	}
}

if( !function_exists('viem_set_post_views') ){
	/*
	 * Function to count views.
	 */
	function viem_set_post_views($post_id){
		$count_key = 'post_views_count';
		$count = get_post_meta($post_id, $count_key, true);
		if($count == ''){
			$count = 0;
			delete_post_meta($post_id, $count_key);
			add_post_meta($post_id, $count_key, '0');
		}else{
			$count++;
			update_post_meta($post_id, $count_key, $count);
		}
	}
}

if(!function_exists('viem_get_gmap_setting')){
	function viem_get_gmap_setting($key=false,$args=''){
		$default = array(
			'key'=>'AIzaSyD-k_n8no7RF_XOHBRkstu8b4GlRDFalo8',
			'lat'=>'40.7127837',
			'lng'=>'-74.00594130000002',
			'marker'=> get_template_directory_uri().'/assets/images/marker.png'
		);
		$setting = wp_parse_args($args,$default);
		$setting =  apply_filters('viem_gmap_setting', $setting);
		if($key && isset($setting[$key]))
			return $setting[$key];
	
		return $setting;
	}
}

if( !function_exists('viem_parse_query_args') ){
	function viem_parse_query_args($args=array()){
		$args = wp_parse_args($args,array(
			'taxonomies'=>'',
			'posts_per_page' => 4,
			'exclude'=>'',
			'include'=>'',
			'offset'=>'',
			'orderby' => 'date',
			'order'   => 'DESC',
			'post_type'=>'post',
			'no_found_rows'=>1,
		));
		$query_args = array(
			'posts_per_page' => 'All' === $args['posts_per_page'] ? -1 : absint($args['posts_per_page']),
			'offset' => absint($args['offset']),
			'orderby' => $args['orderby'],
			'order' => $args['order'],
			'meta_key' => in_array( $args['order'], array(
				'meta_value',
				'meta_value_num',
			) ) ? $args['order'] : '',
			'post_type' => $args['post_type'],
			'exclude' => $args['exclude'],
			'ignore_sticky_posts'=>1,
			'no_found_rows'=>$args['no_found_rows'],
			'public'=>1,
		);
	
		if ( ! empty( $args['include'] ) ) {
			$query_args['post__in'] = wp_parse_id_list( $args['include'] );
		}
	
		if ( ! empty( $args['exclude'] ) ) {
			$query_args['post__not_in'] = wp_parse_id_list( $args['exclude'] );
		}
	
		if ( ! empty( $args['taxonomies'] ) ) {
			$taxonomies_types = get_taxonomies( array( 'public' => true ) );
			$terms = get_terms( array_keys( $taxonomies_types ), array(
				'hide_empty' => false,
				'include' => $args['taxonomies'],
			) );
			$query_args['tax_query'] = array();
			$tax_queries = array(); // List of taxnonimes
			foreach ( $terms as $t ) {
				if ( ! isset( $tax_queries[ $t->taxonomy ] ) ) {
					$tax_queries[ $t->taxonomy ] = array(
						'taxonomy' => $t->taxonomy,
						'field' => 'id',
						'terms' => array( $t->term_id ),
						'relation' => 'IN',
					);
				} else {
					$tax_queries[ $t->taxonomy ]['terms'][] = $t->term_id;
				}
			}
			$query_args['tax_query'] = array_values( $tax_queries );
			$query_args['tax_query']['relation'] = 'OR';
		}
		return $query_args;
	}
}

if( !function_exists('viem_dt_visibility_class') ){
	function viem_dt_visibility_class($visibility = ''){
		$class='';
		switch ($visibility) {
			case 'hidden-phone':
				$class = ' hidden-xs';
				break;
			case 'hidden-tablet':
				$class = ' hidden-sm hidden-md';
				break;
			case 'hidden-pc':
				$class = ' hidden-lg';
				break;
			case 'visible-phone':
				$class = ' visible-xs-inline';
				break;
			case 'visible-tablet':
				$class = ' visible-sm-inline visible-md-inline';
				break;
			case 'visible-pc':
				$class = ' visible-lg-inline';
				break;
			default:
				break;
		}
		return apply_filters('dt-visibility-class', $class);
	}
}



if( !function_exists('viem_get_current_url') ){
	function viem_get_current_url(){
		global $wp;
		
		$current_url = home_url(add_query_arg(array(),$wp->request));
		return $current_url;
	}
}

if( !function_exists('viem_user_menu') ){
	function viem_user_menu( $show_text = 'show' ){
		?>
		<div class="viem-user-menu">
			<?php
			if( !is_user_logged_in() ) : 
			?>
			<a href="<?php echo esc_url( wp_login_url(viem_get_current_url()) );?>">
				<?php echo ( $show_text == 'show' ) ? '<span>'.esc_html__('Login', 'viem').'</span>' : ''; ?><i class="fa fa-user"></i>
			</a>
			<ul>
				<li><a href="<?php echo esc_url( wp_registration_url() ); ?>"><?php echo esc_html_e('Register', 'viem');?></a></li>
			</ul>
			<?php else : ?>
			<a href="javascript:;"><?php echo ( $show_text == 'show' ) ? '<span>'.esc_html__('My Account', 'viem').'</span>' : ''; ?><i class="fa fa-user"></i></a>
				<?php if( has_nav_menu('user-menu') ): ?>
				<ul>
					<?php
					wp_nav_menu( array( 'theme_location' => 'user-menu', 'container' => false, 'items_wrap' => '%3$s', ) );
					?>
					<li><a href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>"><?php echo esc_html_e('Public Profile', 'viem');?></a></li>
					<li><a href="<?php echo esc_url( wp_logout_url(viem_get_current_url()) );?>"><?php echo esc_html_e('Logout', 'viem');?></a></li>
				</ul>
				<?php else :?>
				<ul>
					<?php
					$args = array(
						'post_type' => 'page',
						'meta_key' => '_wp_page_template',
						'meta_value' => 'page-templates/page-watch-later.php',
					);
					$q = new WP_Query( $args );
					if( $q->have_posts() ):
						while ($q->have_posts()): $q->the_post();?>
						<li><a href="<?php echo get_permalink();?>"><?php the_title(); ?></a></li>
						<?php
						endwhile;
					endif;
					wp_reset_postdata();
					?>
					<?php 
					$args = array(
						'post_type' => 'page',
						'meta_key' => '_wp_page_template',
						'meta_value' => 'page-templates/subscriptions.php',
					);
					$q = new WP_Query( $args );
					if( $q->have_posts() ):
						while ($q->have_posts()): $q->the_post();?>
						<li><a href="<?php echo get_permalink();?>"><?php the_title(); ?></a></li>
						<?php
						endwhile;
					endif;
					wp_reset_postdata();
					?>
					<li><a href="<?php echo esc_url( get_author_posts_url( get_current_user_id() ) ); ?>"><?php echo esc_html_e('Public Profile', 'viem');?></a></li>
					<li><a href="<?php echo esc_url( wp_logout_url(viem_get_current_url()) );?>"><?php echo esc_html_e('Logout', 'viem');?></a></li>
				</ul>
				<?php endif; ?>
			
			<?php endif;?>
		</div>
		<?php
	}
}

if( !function_exists('viem_megamenu_preview_posts_per_page') ){
	function viem_megamenu_preview_posts_per_page(){
		$megamenu_preview_limit = 5;
		$header_layout = viem_get_theme_option('header_layout','header-1') ;
		if( $header_layout == 'header-5' || $header_layout == 'header-7'){
			$megamenu_preview_limit = 4;
		}
		return $megamenu_preview_limit;
	}
}

if( !function_exists('viem_top_toolbar_search_block') ){
	function viem_top_toolbar_search_block(){
		if( viem_get_theme_option('header-ajaxsearch', '1') == '1' )
			viem_the_search_form_icon();
	}
}

if( !function_exists('viem_the_search_form_icon') ){
	function viem_the_search_form_icon($echo = true, $search_type = '' ){
		$html = '';
		$is_search_type = ($search_type) ? $search_type : viem_get_theme_option('ajaxsearch-type','inline');

		$header_layout = viem_get_theme_option('header_layout','header-1');
		switch ($header_layout) {
			case 'header-1'; case 'header-3' ; case 'header-5' ; case 'header-6' ; case 'header-7':
				$is_search_type = 'popup';
				break;
			default:
				break;
		}

		if('popup' === $is_search_type ){
			$search_form = viem_get_search_form();
			$search_form = '<a class="navbar-search-button" href="#"><i class="fa fa-search"></i></a><div class="search-form-wrap show-popup hide">'.$search_form.'</div>';
			$html .= '<div class="navbar-search navbar-search-popup"><div class="navbar-searchform navbar-searchform-popup">'.$search_form.'</div></div>';
		}else if( 'inline' === $is_search_type ) {
			$ajax= apply_filters('viem_enable_ajax_search_form',true);
			$search_form ='<div class="navbar-searchform navbar-searchform-ink focused"><div class="navbar-searchform-wrap"><form class="'.($ajax ?' search-ajax':'').'" method="get" id="navbar-searchform" action="'.esc_url( home_url( '/' ) ).'" role="search">
						<label class="sr-only">'.esc_html__( 'Search', 'viem' ).'</label>
						<input type="search" autocomplete="off" id="navbar-searchform-s" name="s" class="form-control searchinput" value="" placeholder="'.esc_attr__( 'LIVE SEARCH...', 'viem' ).'" />
						<input type="submit" id="navbar-searchform-submit" class="hidden" name="submit" value="'.esc_attr__( 'Search', 'viem' ).'" />
						<input type="hidden" name="post_type" value="'.apply_filters('viem_ajax_search_form_post_type', 'any').'" />
						<input type="hidden" name="tax" value="'.apply_filters('viem_ajax_search_form_taxonomy', '').'" />
						</form><div class="searchform-result"></div></div><i class="top-searchform-icon"></i></div>';
			$html .= '<div class="navbar-search-inline navbar-search-ink">'.apply_filters('viem_before_search_form_icon', '').$search_form.'</div>';
		}else {
			$ajax= apply_filters('viem_enable_ajax_search_form',true);
			$search_form ='<div class="navbar-searchform navbar-searchform-ink"><div class="navbar-searchform-wrap"><form class="'.($ajax ?' search-ajax':'').'" method="get" id="navbar-searchform" action="'.esc_url( home_url( '/' ) ).'" role="search">
						<label class="sr-only">'.esc_html__( 'Search', 'viem' ).'</label>
						<input type="search" autocomplete="off" id="navbar-searchform-s" name="s" class="form-control searchinput" value="" placeholder="'.esc_attr__( 'Search and hit enter...', 'viem' ).'" />
						<input type="submit" id="navbar-searchform-submit" class="hidden" name="submit" value="'.esc_attr__( 'Search', 'viem' ).'" />
						<input type="hidden" name="post_type" value="'.apply_filters('viem_ajax_search_form_post_type', 'any').'" />
						<input type="hidden" name="tax" value="'.apply_filters('viem_ajax_search_form_taxonomy', '').'" />
						</form><div class="searchform-result"></div></div><i class="top-searchform-icon"></i></div>';
			$html .= '<div class="navbar-search navbar-search-ink">'.apply_filters('viem_before_search_form_icon', '').$search_form.'</div>';
		}
		if( $echo == true ){
			echo apply_filters('viem_the_search_form_icon', $html);
		}else{
			return apply_filters('viem_the_search_form_icon', $html);
		}
	}
}

if( !function_exists('viem_get_search_form') ){
	function viem_get_search_form(){
		$ajax= apply_filters('viem_enable_ajax_search_form',true);
		$search_form = '<form method="get" data-button-text="'.esc_attr__('Search', 'viem').'" class="searchform'.($ajax ?' search-ajax':'').'" action="'.esc_url( home_url( '/' ) ).'" role="search">
						<input type="search" class="searchinput" name="s" autocomplete="off" value="" placeholder="'.esc_attr__( 'Search and hit enter...', 'viem' ).'" />
						<input type="submit" class="searchsubmit" name="submit" value="'.esc_attr__('Search', 'viem').'" />
						<input type="hidden" name="post_type" value="'.apply_filters('viem_ajax_search_form_post_type', 'any').'" />
						<input type="hidden" name="tax" value="'.apply_filters('viem_ajax_search_form_taxonomy', '').'" />
					</form>';
		if($ajax)
			$search_form .='<div class="searchform-result"></div>';
		return $search_form;
	}
}

/*
 * Page heading
 */
if( !function_exists('viem_page_heading_content') ):
function viem_page_heading_content(){
	if(is_page()){
		$page_heading = viem_get_post_meta('page_heading', '', 'heading');
		if( $page_heading == 'heading' ){
			$page_heading_background = viem_get_post_meta('page_heading_background', '', '');
			?>
			<div class="page-heading" <?php echo ( $page_heading_background != '' ) ? 'style="background-color:'. $page_heading_background .'"' : ''; ?>>
				<div class="container">
					<header class="page-header">
					<?php the_title( '<h1 class="page-title">', '</h1>' ); ?>
					</header>
				</div>
			</div>
			<?php
		}elseif($page_heading === 'rev' && ($rev_alias = viem_get_post_meta('rev_alias'))) {
		?>
			<div id="dawnthemes-slides" class="wrap">
				<?php echo do_shortcode('[rev_slider '.$rev_alias.']')?>
			</div>
		<?php
		}
	
	}elseif ( is_archive() ){
		?>
		<div class="page-heading">
			<div class="container">
				<header class="page-header">
					<h1 class="page-title"><?php viem_page_title(); ?></h1>
				</header><!-- .page-header -->
			</div>
		</div>
	<?php
	}elseif ( is_singular('viem_video') ) {
		$video_style = viem_get_theme_option('single-video-style', 'style-1');
		$video_meta_style = viem_get_post_meta('single-video-style', '');
		if( !empty($video_meta_style))
			$video_style = $video_meta_style;
		
		$view_list = ( isset($_GET['list']) && !empty($_GET['list']) ) ? $_GET['list'] : '';
		$view_series = ( isset($_GET['series']) && !empty($_GET['series']) ) ? $_GET['series'] : '';
		
		if(!empty($view_list)){
			if( function_exists('viem_playlist_player_wrapper') ){
				viem_playlist_player_wrapper( $view_list );
			}
		}
		elseif( !empty($view_series) ){
			if( function_exists('viem_series_player_wrapper') ){
				viem_series_player_wrapper( $view_series );
			}
		}
		elseif( $video_style == 'style-1'){
			?>
			<div class="page-heading">
				<div class="container">
			<?php 
				if( function_exists('viem_video_player_wrapper') ){
					viem_video_player_wrapper( 'viem-dark' );
				}
			?>
				</div>
			</div>
			<?php
		}elseif( $video_style == 'style-3' ){
			?>
			<div class="page-heading">
				<?php 
					if( function_exists('viem_video_player_wrapper') ){
						viem_video_player_wrapper('viem-dark');
					}
				?>
			</div>
			<?php
		}
	
	}elseif (is_singular('viem_movie')){
		if( has_post_thumbnail()){
			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', true);
			?>
			<div class="page-heading viem-featured-image-full" style="background-image:url(<?php echo esc_url( $thumbnail[0] ) ?>);"></div>
		<?php
		}
	}elseif (is_single()){
		// Single post featured image full width
		$style = viem_get_post_meta('single-style', get_the_ID(), '');
		$style = ( $style == '' ? viem_get_theme_option('single-style', 'style-1') : $style );
		if( $style == 'style-2' && has_post_thumbnail()){

			$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'full', true);
			
		?>
		<div class="page-heading viem-featured-image-full" style="background-image:url(<?php echo esc_url( $thumbnail[0] ) ?>);">
		</div>
		<?php
		}
	}
}
endif;
/*
 * Breadcrumbs
 */
if( !function_exists('viem_breadcrumbs') ):
function viem_breadcrumbs(){
	if ( is_home() || is_search() || is_front_page() )
		return;
	
	$show_breadcrumbs = true;
	$container_class = 'container';

	if( is_singular('viem_video') ){
		$viem_video_style = viem_get_theme_option('single-video-style', 'style-1');
		$viem_video_meta_style = viem_get_post_meta('single-video-style', '');
		if( !empty($viem_video_meta_style))
			$viem_video_style = $viem_video_meta_style;
	}

	if($show_breadcrumbs){
		$tpl_name = '/template-parts/tpl.breadcrumb.php';
		if( is_file( get_template_directory() . $tpl_name ) ){
			?>
			<div class="dawnthemes_breadcrumbs wrap">
				<div class="<?php echo esc_attr($container_class) ?>">
					<div class="dawnthemes_breadcrumb__wrapper clearfix">
					<?php include ( get_template_directory() . $tpl_name ); ?>
					</div>
				</div>
			</div>
			<?php
		}
	}
}
endif;

if( !function_exists('viem_breaking_news') ):
	function viem_breaking_news( $post_type = 'post', $items = 1){
	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => get_option('posts_per_page', 10),
		'orderby'	=> 'date'
	);
	$breaking_news = new WP_Query($args);
	if( $breaking_news->have_posts() ):
	?>
	<div class="viem-breaking-news-wrap">
		<div class="viem-breaking-news-content">
			<div class="viem-breaking-news-slider viem-carousel-slide owl-carousel" data-autoplay="true" data-dots="0" data-nav="1" data-items="<?php echo esc_attr($items);?>" data-autoWidth="" data-animate="fadeOut" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
				<?php while ( $breaking_news->have_posts() ): $breaking_news->the_post();?>
					<div class="viem-breaking-item"><a href="<?php echo esc_url(get_permalink());?>" title="<?php echo esc_attr(get_the_title());?>"><?php echo get_the_title();?></a><span class="post-meta"> - <?php viem_dt_posted_on(); ?></span></div>
				<?php endwhile;?>
			</div>
		</div>
	</div>
	<?php
	endif;
	wp_reset_postdata();
	}
endif;

if( !function_exists('viem_breaking_news') ):
	function viem_breaking_news( $post_type = 'post', $items = 1){

}
endif;

function viem_dt_echo($string=''){
	return $string;
}

function viem_is_woocommerce_activated(){
	return class_exists( 'woocommerce' ) ? true : false;
}

if( !function_exists('viem_get_days_of_week') ){
	function viem_get_days_of_week($day_index,$step=' &ndash; ',$abbrev=false){
		global $wp_locale;
		$days = array();
		foreach ((array)$day_index as $di){
			if($abbrev)
				$days[] = '<span>'.$wp_locale->get_weekday_abbrev($wp_locale->get_weekday($di)).'</span>';
			else
				$days[] = $wp_locale->get_weekday($di);
		}
		return apply_filters('viem_get_days_of_week',implode($step, $days),$day_index);
	}
}

function viem_convert_weekday_to_day_index($weekday){
	global $wp_locale;
	for ($day_index = 0; $day_index <= 6; $day_index++) :
	if(sanitize_title($weekday) === sanitize_title($wp_locale->get_weekday($day_index))){
		return $day_index;
	}
	endfor;
	return null;

}

function viem_clean( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'viem_clean', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

if( !function_exists('viem_dt_css_minify') ){
	function viem_dt_css_minify( $css ) {
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = preg_replace( '/\/\*[^\!](.*?)\*\//', '', $css );
		$css = preg_replace( '/(,|:|;|\{|}) /', '$1', $css );
		$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );
		return trim( $css );
	}
}

if( !function_exists('viem_shortcode_vc_custom_css_class') ){
	function viem_shortcode_vc_custom_css_class( $param_value, $prefix = '' ){
		if(function_exists('vc_shortcode_custom_css_class'))
			return vc_shortcode_custom_css_class($param_value,$prefix);
		$css_class = preg_match( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', $param_value ) ? $prefix . preg_replace( '/\s*\.([^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/', '$1', $param_value ) : '';
		
		return $css_class;
	}
}

if( !function_exists('viem_placeholder_img_src') ){
	function viem_placeholder_img_src() {
		return apply_filters( 'viem_placeholder_img_src', get_template_directory_uri() . '/assets/images/placeholder.png' );
	}
}

if( !function_exists('viem_print_string') ){
	function viem_print_string($string=''){
		$allowedtags = array(
			'div'=>array(
				'id'=>array(),
				'class'=>array(),
			),
			'a' => array(
				'href' => array(),
				'target' => array(),
				'title' => array(),
				'rel' => array(),
			),
			'img' => array(
				'src' => array(),
				'class' => array(),
				'alt' => array(),
				'sizes' => array(),
			),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'p' => array(),
			'br' => array(),
			'hr' => array(),
			'span' => array(
				'class'=>array()
			),
			'em' => array(),
			'strong' => array(),
			'small' => array(),
			'b' => array(),
			'i' => array(
				'class'=>array()
			),
			'u' => array(),
			'ul' => array(),
			'ol' => array(),
			'li' => array(),
			'blockquote' => array(),
			'aside'=>array(
				'id'=>array(),
				'class'=>array(),
			),
		);
		$allowedtags = apply_filters('viem_print_string_allowed_tags', $allowedtags);
		
		return $string;
	}
}

if( !function_exists('viem_dt_get_template_part') ){
	function viem_dt_get_template_part($slug, $name = null){
		get_template_part($slug,$name);
	}
}

if( !function_exists('viem_dt_get_template') ){
	function viem_dt_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}
		
		// Look within passed path within the theme - this is priority
		$located = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name
			)
		);
		
		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_name ), '2.1' );
			return;
		}
		$located = apply_filters( 'viem_dt_get_template', $located, $template_name, $args, $template_path, $default_path );
	
		do_action( 'dt_before_get_template', $template_name, $template_path, $located, $args );
		include( $located );
		do_action( 'dt_after_get_template', $template_name, $template_path, $located, $args );
	}
}

if( !function_exists('viem_display_sidebar') ){
	function viem_display_sidebar($layout, $sidebar_name = ''){
		global $dawnthemes_page_layout, $get_sidebar_name;
		$dawnthemes_page_layout = $layout; 
		
		$page_sidebar = ''; // default
		if( is_page() ) $page_sidebar = viem_get_post_meta('main_sidebar');
		
		$get_sidebar_name = ( !empty($page_sidebar) ) ? $page_sidebar : $sidebar_name;
		
		if( $layout === 'left-sidebar' || $layout === 'right-sidebar' ){
			add_action('viem_dt_right_sidebar','viem_dt_get_sidebar',10);
		}

		if( $layout == 'left-right-sidebar'){
			add_action('viem_action_before_content_page','viem_get_left_sidebar',99);
			add_action('viem_action_after_content_page','viem_get_right_sidebar',0);
		}
	}
}

if( !function_exists('viem_dt_get_sidebar') ){
	function viem_dt_get_sidebar(){
		get_sidebar();
	}
}

if( !function_exists('viem_get_left_sidebar') ){
	function viem_get_left_sidebar(){
		get_sidebar('left');
	}
}

if( !function_exists('viem_get_right_sidebar') ){
	function viem_get_right_sidebar(){
		get_sidebar('right');
	}
}

if( !function_exists('viem_dt_get_extra_sidebar') ){
	function viem_dt_get_extra_sidebar(){
		get_sidebar('extra');
	}
}

if( !function_exists('viem_dt_get_main_col_class') ){
	function viem_dt_get_main_col_class($layout){
		$col_class = 'col-md-12';
		switch ($layout){
			case 'full-width':
				$col_class = 'col-md-12 no-sidebar';
				break;
			case 'left-sidebar':
				if( is_active_sidebar('main-sidebar') || is_active_sidebar('left-sidebar') ){
					$col_class = viem_main_col;
				}else{
					$col_class = 'col-md-12 no-active_sidebar';
				}
				break;
			case 'right-sidebar':
				if( is_active_sidebar('main-sidebar') || is_active_sidebar('right-sidebar') ){
					$col_class = viem_main_col;
				}else{
					$col_class = 'col-md-12 no-active_sidebar';
				}
				break;
			case 'left-right-sidebar':
				$col_class = 'col-md-12 has-2-sidebar';
				break;
			default:
				break;
		}
		
		return $col_class;
	}
}

if( !function_exists('viem_dt_container_class') ){
	function viem_dt_container_class(){
		$main_layout = viem_get_theme_option('site-layout','wide');
		$container_class = ''; 
		if($main_layout == 'wide'){
			$wide_container = viem_get_theme_option('wide-container','fixedwidth');
			if($wide_container == 'fullwidth'):
				if((is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' )) && (viem_get_theme_option('portfolio-gap',1) != '1'))
					$container_class = 'container-full';
				else
					$container_class = 'container-fluid';
			endif;
		}
		
		$container_class = apply_filters('viem_dt_container_class', $container_class);
		echo esc_attr($container_class);
	}
}



if( !function_exists('viem_dt_social') ){
	function viem_dt_social($use = array(),$hover = true,$soild_bg=false,$outlined=false){
		$socials = apply_filters('viem_dt_social',array(
			'facebook'=>array(
					'label'=>esc_html__('Facebook','viem'),
					'url'=>viem_get_theme_option('facebook-url')
			),
			'twitter'=>array(
					'label'=>esc_html__('Twitter','viem'),
					'url'=>viem_get_theme_option('twitter-url')
			),
			'google-plus'=>array(
					'label'=>esc_html__('Google+','viem'),
					'url'=>viem_get_theme_option('google-plus-url')
			),
			'youtube'=>array(
					'label'=>esc_html__('Youtube','viem'),
					'url'=>viem_get_theme_option('youtube-url')
			),
			'vimeo'=>array(
					'label'=>esc_html__('Vimeo','viem'),
					'url'=>viem_get_theme_option('vimeo-url')
			),
			'pinterest'=>array(
					'label'=>esc_html__('Pinterest','viem'),
					'url'=>viem_get_theme_option('pinterest-url')
			),
			'linkedin'=>array(
					'label'=>esc_html__('LinkedIn','viem'),
					'url'=>viem_get_theme_option('linkedin-url')
			),
			'rss'=>array(
					'label'=>esc_html__('RSS','viem'),
					'url'=>viem_get_theme_option('rss-url')
			),
			'instagram'=>array(
					'label'=>esc_html__('Instagram','viem'),
					'url'=>viem_get_theme_option('instagram-url')
			),
			'github'=>array(
					'label'=>esc_html__('GitHub','viem'),
					'url'=>viem_get_theme_option('github-url')
			),
			'behance'=>array(
					'label'=>esc_html__('Behance','viem'),
					'url'=>viem_get_theme_option('behance-url')
			),
			'stack-exchange'=>array(
					'label'=>esc_html__('StackExchange','viem'),
					'url'=>viem_get_theme_option('stack-exchange-url')
			),
			'tumblr'=>array(
					'label'=>esc_html__('Tumblr','viem'),
					'url'=>viem_get_theme_option('tumblr-url')
			),
			'soundcloud'=>array(
					'label'=>esc_html__('SoundCloud','viem'),
					'url'=>viem_get_theme_option('soundcloud-url')
			),
			'dribbble'=>array(
					'label'=>esc_html__('Dribbble','viem'),
					'url'=>viem_get_theme_option('dribbble-url')
			),
					
		));
		echo '<div class="dt-socials-list">';
		foreach ((array)$socials  as $social=>$data):
			if(in_array($social, $use)):
				if(empty($data['url']))
					$data['url'] = '#';
				echo '<div class="dt-socials-item '.$social.'">';
				echo '<a class="dt-socials-item-link" href="'.esc_url($data['url']).'" title="'.esc_attr($data['label']).'" ><i class="fa fa-'.$social.' '.($hover ? $social.'-bg-hover':'').' '.($soild_bg ? $social.'-bg':'').' '.($outlined ? $social.'-outlined':'').'"></i></a>';
				echo '</div>';
			endif;
		endforeach;
		echo '</div>';
		return ;
	}
}

if( !function_exists('viem_dt_enqueue_google_font') ){
	function viem_dt_enqueue_google_font(){
		if( class_exists('DawnThemesCore') ){
			
			include ( DTINC_DIR . '/lib/web-safe-fonts.php' );
			
			$uploaded_fonts = viem_get_theme_option('upload_font', array());
			
			$typography_arr = array('main_font','secondary_font','navbar_typography');
			$google_fonts = array();
			foreach ($typography_arr as $font){
				$typography = viem_get_theme_option($font);
				if(!empty($typography['font-family'])){
					
					if( in_array( $typography['font-family'], $web_safe_fonts ) || $typography['font-family'] == $uploaded_fonts['font-name'] ){
						continue;
					}
					
					$font_family = str_replace(" ", "+", $typography['font-family']);
					if(!empty($typography['font-style'])){
						$font_style = $typography['font-style'];
					}else{
						$font_style = 400;
					}
					if(!empty($typography['subset'])){
						$subset = $typography['subset'];
					}else{
						$subset = "latin";
					}
						
					$google_fonts[$font_family]['style'][] = $font_style;
					$google_fonts[$font_family]['subset'][] = $subset;
				}
			}
			if(!empty($google_fonts)){
				foreach ($google_fonts as $font=>$google_font){
						wp_enqueue_style( 'viem-'.sanitize_title($font), "//fonts.googleapis.com/css?family=$font:".implode(',', array_unique( (array) $google_font['style']))."&subset=".implode(',', array_unique((array)$google_font['subset'])),false);
				}
			}
		}
	}
}

if( !function_exists('viem_get_protocol') ){
	function viem_get_protocol(){
		return  is_ssl() ? 'https' : 'http';
	}
}

if( !function_exists('viem_dt_share') ){
	function viem_dt_share($title='',$facebook = true,$twitter = true,$google = true,$pinterest = true,$linkedin = true,$outlined=false){
		ob_start();
	?>
		<div class="share-links">
			<?php if(!empty($title)):?>
			<span class="share-title font-2"><?php echo esc_html($title)?></span>
			<?php endif;?>
			<div class="icon-share-links">
				<ul>
					<?php if($facebook):?>
					<li>
						<a class="facebook" href="<?php echo esc_url('www.facebook.com/sharer.php?u='.get_the_permalink()) ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;" title="<?php echo esc_attr__('Facebook','viem')?>"><i class="fa fa-facebook<?php echo ($outlined ? ' facebook-outlined':'')?>"></i></a>
					</li>
					<?php endif;?>
					<?php if($twitter):?>
					<li  class="twitter-share">
						<a class="twitter" href="<?php echo esc_url('https://twitter.com/share?url='.get_the_permalink()) ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;" title="<?php echo esc_attr__('Twitter','viem')?>"><i class="fa fa-twitter<?php echo ($outlined ? ' twitter-outlined':'')?>"></i></a>
					</li>
					<?php endif;?>
					<?php if($google):?>
					<li>
						<a class="google" href="<?php echo esc_url('https://plus.google.com/share?url='.get_the_permalink()) ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="<?php echo esc_attr__('Google +','viem')?>"><i class="fa fa-google-plus<?php echo ($outlined ? ' google-plus-outlined':'')?>"></i></a>
					</li>
					<?php endif;?>
					<?php if($pinterest):?>
					<li>
						<a class="pinterest" href="<?php echo esc_url('pinterest.com/pin/create/button/?url='.get_the_permalink().'&media='.(function_exists('the_post_thumbnail') ? wp_get_attachment_url(get_post_thumbnail_id()):'').'&description='.get_the_title()) ?>" title="<?php echo esc_attr__('pinterest','viem')?>"><i class="fa fa-pinterest<?php echo ($outlined ? ' pinterest-outlined':'')?>"></i></a>
					</li>
					<?php endif;?>
					<?php if($linkedin):?>
					<li>
						<a class="linkedin" href="<?php echo esc_url('www.linkedin.com/shareArticle?mini=true&url='.get_the_permalink().'&title='.get_the_title())?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" title="<?php echo esc_attr__('Linked In','viem')?>"><i class="fa fa-linkedin<?php echo ($outlined ? ' linkedin-outlined':'')?>"></i></a>
					</li>
					<?php endif;?>
				</ul>
			</div>
		</div>
	<?php
		echo apply_filters('viem_dt_share', ob_get_clean());
	}
}

if( !function_exists('viem_get_the_excerpt') ){
	function viem_get_the_excerpt($excerpt_length = ''){
		global $post;
		
		if($excerpt_length == ''){
			$excerpt_length = absint(viem_get_theme_option('blog-excerpt-length', '30'));
		}
		
		$excerpt = $post->post_excerpt;
		if(empty($excerpt))
			$excerpt = $post->post_content;
			
		$excerpt = strip_shortcodes($excerpt);
		$excerpt = wp_trim_words($excerpt,$excerpt_length,'...');
		
		return apply_filters('viem_get_the_excerpt', $excerpt);
	}
}

if( !function_exists('viem_dt_nth_word') ){
	function viem_dt_nth_word($text, $nth = 1, $echo = true,$is_typed = false,$typed_color = ''){
		$text = strip_shortcodes($text);
		$text = wp_strip_all_tags( $text );
		if ( 'characters' == _x( 'words', 'word count: words or characters?','viem') && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
			$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
			preg_match_all( '/./u', $text, $words_array );
			$sep = '';
		} else {
			$words_array = preg_split( "/[\n\r\t ]+/", $text, null, PREG_SPLIT_NO_EMPTY );
			$sep = ' ';
		}
		$nth_class=$nth;
		if($nth == 'last')
			$nth = count($words_array) - 1;
		if($nth == 'first')
			$nth = 0;
		
		if(isset($words_array[$nth]) && !$is_typed){
			$words_array[$nth] = '<span class="nth-word-'.$nth_class.'">'.$words_array[$nth].'</span>';
		}
		if($is_typed){
			$string =  $words_array[$nth];
			$words_array[$nth] = '<span'.(!empty($typed_color) ? ' style="color:'.$typed_color.'" ' :'').'><span class="nth-typed"></span></span>';
			return array(implode($sep, $words_array),$string);
		}
		if($echo)
			echo implode($sep, $words_array);
		else 
			return implode($sep, $words_array);
	}
}

if( !function_exists('viem_dt_trim_characters') ){
	function viem_dt_trim_characters($string, $count=50, $ellipsis = FALSE)
	{
		$trimstring = substr($string,0,$count);
		if (strlen($string) > $count) {
			if (is_string($ellipsis)){
				$trimstring .= $ellipsis;
			}
			elseif ($ellipsis){
				$trimstring .= '&hellip;';
			}
		}
		return $trimstring;
	}
}

if( !function_exists('viem_page_title') ){
	function viem_page_title($echo = true){
		$title = "";
		
		if ( is_category() )
		{
			$title = single_cat_title('',false);
		}
		elseif (is_day())
		{
			$title = esc_html__('Archive for date:','viem')." ".get_the_time('F jS, Y');
		}
		elseif (is_month())
		{
			$title = esc_html__('Archive for month:','viem')." ".get_the_time('F, Y');
		}
		elseif (is_year())
		{
			$title = esc_html__('Archive for year:','viem')." ".get_the_time('Y');
		}
		elseif (is_search())
		{
			global $wp_query;
			if(!empty($wp_query->found_posts))
			{
				if($wp_query->found_posts > 1)
				{
					$title =  $wp_query->found_posts ." ". esc_html__('search results for','viem').' <span class="search-query">'.esc_attr( get_search_query() ).'</span>';
				}
				else
				{
					$title =  $wp_query->found_posts ." ". esc_html__('search result for','viem').' <span class="search-query">'.esc_attr( get_search_query() ).'</span>';
				}
			}
			else
			{
				if(!empty($_GET['s']))
				{
					$title = esc_html__('Search results for','viem').' <span class="search-query">'.esc_attr( get_search_query() ).'</span>';
				}
				else
				{
					$title = esc_html__('To search the site please enter a valid term','viem');
				}
			}
		}
		elseif (is_author())
		{
			$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			if(isset($curauth->nickname)) $title = $curauth->nickname;
		}
		elseif (is_tag())
		{
			$title =single_tag_title('',false);
		}
		elseif(is_tax())
		{
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			$title = $term->name;
		}
		elseif ( is_front_page() && !is_home() ) {
		    $title = get_the_title(get_option('page_on_front'));
		}
		elseif ( is_home() && !is_front_page() ) {
		    $title = get_the_title(get_option('page_for_posts'));
		    
		} elseif ( is_404() ) {
		    $title = esc_html__('404 - Page not found','viem');
		}
		elseif (is_post_type_archive('viem_movie')){
			$title = (viem_get_theme_option( 'main-movie-page')) ? get_the_title(viem_get_theme_option( 'main-movie-page')) : esc_html__('Movies','viem');
		}
		elseif (is_post_type_archive('viem_video')){
			$title = (viem_get_theme_option( 'main-video-page')) ? get_the_title(viem_get_theme_option( 'main-video-page')) : esc_html__('Videos','viem');
		}
		elseif (is_post_type_archive('viem_series')){
			$title = (viem_get_theme_option( 'main-series-page')) ? get_the_title(viem_get_theme_option( 'main-series-page')) : esc_html__('Series','viem');
		}
		elseif (is_post_type_archive('viem_playlist')){
			$title = (viem_get_theme_option( 'main-playlist-page')) ? get_the_title(viem_get_theme_option( 'main-playlist-page')) : esc_html__('All Playlists','viem');
		}
		elseif (is_post_type_archive('viem_channel')){
			$title = (viem_get_theme_option( 'main-channel-page')) ? get_the_title(viem_get_theme_option( 'main-channel-page')) : esc_html__('All Channels','viem');
		}
		elseif (is_post_type_archive('tribe_events')){
			$title = esc_html__('Events','viem');
		}
		elseif (is_archive()){
			$title = get_the_archive_title();
		}else {
			$title = get_the_title();
		}
		
		if (isset($_GET['paged']) && !empty($_GET['paged']))
		{
			$title .= " (".esc_html__('Page','viem')." ".$_GET['paged'].")";
		}
	
		if( defined('WOOCOMMERCE_VERSION') && is_woocommerce() && ( is_product() || is_shop() ) && !is_search() ) {
			$title = woocommerce_page_title( false );
			if(is_product())
				$title = get_the_title();
		}
		if(is_post_type_archive( 'portfolio' )){
			$title = esc_html(viem_get_theme_option('portfolio-archive-title',esc_html_e('My Portfolio','viem')));
		}
		
		if($title =='')
			$title = get_the_title();
		
		$title = apply_filters('viem_page_title', $title);
		if($echo)
			echo viem_dt_echo($title);
		else
			return $title;
	}
}

function viem_post_image_defaults($type){
	global $viem_image_sizes;
	
	switch ($type){
		case 'custom':
			if( is_array($viem_image_sizes) && in_array('viem_350_350_crop', $viem_image_sizes) ){
				return 'viem_350_350_crop';
			}else{
				return 'medium';
			}
			break;
		case 'menu':
				return 'viem-megamenu-524x342';
			break;
		case 'list':
			return 'large';
			break;
		case 'grid':
			if( is_array($viem_image_sizes) && in_array('viem_600_600_crop', $viem_image_sizes) ){
				return 'viem_600_600_crop';
			}else{
				return 'post-thumbnail';
			}
			break;
		case 'masonry':
			return 'full';
			break;
		default:
				if( is_array($viem_image_sizes) && in_array('viem_600_600_crop', $viem_image_sizes) ){
					return 'viem_600_600_crop';
				}else{
					return apply_filters('viem_post_thumbnail', 'post-thumbnail');
				}
			break;
	}
}

if( !function_exists('viem_post_image') ){
	function viem_post_image($img_size = 'default', $type = '', $show_meta = true, $show_badge = true, $css_class = ''){
		
		$thumbnail_size = '';
		if ( $img_size == 'default' || $img_size == '' ){
			$thumbnail_size = viem_post_image_defaults($type);
		}else{
			$thumbnail_size = $img_size;
		}
		
		$post_id  = get_the_ID();
		$get_post_type = get_post_type($post_id);
		$post_format = get_post_format();
		
		ob_start();
		$thumbnail_img = '';
		if( has_post_thumbnail($post_id) ){
			$thumbnail_img = get_the_post_thumbnail( $post_id, $thumbnail_size );
		}elseif( viem_get_theme_option('videos-thumbImg', 'upload') == 'auto' ){
			if( class_exists('viem_posttype_video') ){
				$thumbnail_url = viem_posttype_video::get_thumbnail_auto();
				$thumbnail_img = '<img src="'.$thumbnail_url.'"/>';
			}
			if( viem_get_post_meta('video_type', $post_id) == 'HTML5' ){
				$thumbnail_img = '<video class="viem_vp_videoPlayer"><source src="'.viem_get_post_meta('video_mp4', $post_id).'" /></video>';
			}
		}
		
		?>
		<?php if($show_badge && class_exists('viem_posttype_video')) viem_posttype_video::viem_video_badges_html(); ?>
		 <div class="entry-featured post-thumbnail <?php echo esc_attr($css_class)?>">
			 <a class="dt-image-link" href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php the_title_attribute(); ?>">
			 <?php echo viem_print_string( $thumbnail_img ) ?>
			 <?php if( $post_format == 'video' || $get_post_type == 'viem_video' )
			 	echo '<div class="dt-icon-video"></div>';
			 	?>
			<?php if( $show_meta ): ?>
			<div class="entry-video-counter">
				<?php viem_video_like_counter(get_the_ID()); ?>
				<?php
				if( ($duration = viem_get_post_meta('video_duration', $post_id, 0)) ):?>
					<span class="video-duration"><?php echo esc_html( $duration ); ?></span>
				<?php endif;?>
			</div>
			<?php endif; ?>
		 	</a>
	 	</div>
		<?php
		echo ob_get_clean();
	}
}


if( !function_exists('viem_dt_post_featured') ){
	function viem_dt_post_featured($layout = '',$size = '', $entry_featured_class = '', $is_shortcode = false){
		
		$post_id  = get_the_ID();
		$get_post_type = get_post_type($post_id);
		$post_format = get_post_format();
		
		$thumbnail_size = '';
		if ( $size == 'default' || $size == '' ){
			$thumbnail_size = viem_post_image_defaults($layout);
		}else{
			$thumbnail_size = $size;
		}

		$featured_class = !empty($post_format) ? ' '.$post_format.'-featured' : ' ';
		
		$featured_class .= $entry_featured_class;
		
		if($layout == 'related'){
			if(has_post_thumbnail()){
				ob_start();
				?>
				<div class="entry-featured <?php echo esc_attr($featured_class)?>">
					<a class="dt-image-link" href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php the_title_attribute(); ?>">
						<?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
						<?php if( $post_format == 'video' || $get_post_type == 'viem_video' )
							echo '<div class="dt-icon-video"></div>';
						?>
					</a>
				</div>
				<?php
				echo ob_get_clean();
			}
		}else{
			if($post_format == 'gallery' && is_single()){
				$gallery_ids = explode(',',viem_get_post_meta('gallery'));
				$gallery_ids = array_filter($gallery_ids);
				if(!empty($gallery_ids) && is_array($gallery_ids)):
				?>
					<div class="entry-featured<?php echo esc_attr($featured_class) ?>">
						<?php $data_items = ($layout == 'grid' || $layout == 'masonry') ? 1 : 2; ?>
						<div class="viem-slick-slider viem-preload" data-visible="<?php echo esc_attr($data_items)?>" data-scroll="<?php echo esc_attr($data_items)?>" data-infinite="true" data-autoplay="false" data-dots="false">
							<div class="viem-slick-wrap">
								<div class="viem-slick-items">
									<?php foreach ($gallery_ids as $id):?>
										<?php if($id):?>
										<?php 
										$image = wp_get_attachment_image_src($id,'large');
										$image_url = isset( $image[0] ) ? $image[0] : '#';
										?>
										<div class="viem-slick-item">
											<div class="viem-slide-img">
												<a href="<?php echo esc_url( $image_url ) ?>" title="<?php echo get_the_title($id)?>" data-rel="magnific-popup">
													<?php echo wp_get_attachment_image($id,'large');?>
												</a>
											</div>
										</div>
										<?php endif;?>
									<?php endforeach;?>
								</div>
							</div>
						</div>
					</div>
					<?php
				endif;
			}elseif ($post_format == 'video'){
				wp_enqueue_style( 'mediaelement' );
				wp_enqueue_script('mediaelement');
				if(is_single()){
					$video_args = array();
					if($mp4 = viem_get_post_meta('video_mp4'))
						$video_args['mp4'] = $mp4;
					if ( $ogv = viem_get_post_meta('video_ogv') )
						$video_args['ogv'] = $ogv;
					if($webm = viem_get_post_meta('video_webm'))
						$video_args['webm'] = $webm;
					
					$poster = viem_get_post_meta('video_poster');
					$poster_attr='';
					
					if(has_post_thumbnail()){
						$post_thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id),$thumbnail_size);
						$post_thumb_url = isset( $post_thumb[0] ) ? $post_thumb[0] : '#';
						$poster_attr = ' poster="' . esc_url($post_thumb_url) . '"';
					}
					
					if(!empty($poster)){
						$poster_image = wp_get_attachment_image_src($poster, $thumbnail_size);
						$poster_image_url = isset( $poster_image[0] ) ? $poster_image[0] : '#';
						$poster_attr = ' poster="' . esc_url($poster_image_url) . '"';
					}
					
					
					if(!empty($video_args)):
						$video = '<div id="video-featured-'.$post_id.'" class="video-embed-wrap"><video controls="controls" '.$poster_attr.' preload="0" class="video-embed">';
						$source = '<source type="%s" src="%s" />';
						foreach ( $video_args as $video_type => $video_src ) {
							$video_type = wp_check_filetype( $video_src, wp_get_mime_types() );
							$video .= sprintf( $source, $video_type['type'], esc_url( $video_src ) );
						}
						$video .= '</video></div>';
						echo '<div class="entry-featured'.$featured_class.'">';
							echo viem_dt_echo($video);
						echo '</div>';
					elseif($embed = viem_get_post_meta('video_embed')):
						if(!empty($embed)):
							echo '<div class="entry-featured '.$post_format.'-featured '.$entry_featured_class.'">';
							echo '<div id="video-featured-'.$post_id.'" class="embed-wrap">';
							echo apply_filters('dawnthemes_embed_video', $embed); 
							echo '</div>';
							echo '</div>';
						endif;
					elseif (has_post_thumbnail()):
						$thumb = get_the_post_thumbnail($post_id,$thumbnail_size,array('data-itemprop'=>'image'));
						echo '<div class="entry-featured post-thumbnail'.$featured_class.' '.$entry_featured_class.'">';
						if(!is_singular() || $is_shortcode){
							echo '<a class="dt-image-link" href="'.get_the_permalink().'" title="'.esc_attr(get_the_title(get_post_thumbnail_id($post_id))).'">'.$thumb.'</a>';
						}else{
							echo viem_dt_echo($thumb);
						}
						echo '</div>';
					endif;
				}else{
					if(has_post_thumbnail()){
						$thumb = get_the_post_thumbnail($post_id,$thumbnail_size,array('data-itemprop'=>'image'));
					}else{
						$thumb = '<img src="'.get_template_directory_uri().'/assets/images/no-thumb_700x350.png" alt="'.get_the_title().'">';
					}
					echo '<div class="entry-featured'.$featured_class.' '.$entry_featured_class.'">';
					echo '<a class="dt-image-link" href="'.get_the_permalink().'" title="'.esc_attr(get_the_title(get_post_thumbnail_id($post_id))).'">'.$thumb.'<div class="dt-icon-video"></div></a>';
					echo '</div>';
				}
			}elseif ($post_format == 'audio'){
				$audio_args = array();
				
				if($embed = viem_get_post_meta('audio_embed'))
					$audio_args['embed'] = $embed;
				
				if($mp3 = viem_get_post_meta('audio_mp3'))
					$audio_args['mp3'] = $mp3;
				
				if($ogg = viem_get_post_meta('audio_ogg'))
					$audio_args['ogg'] = $ogg;
				
				if(!empty($audio_args)){
					if(isset($audio_args['embed'])){
						echo '<div id="audio-featured-'.$post_id.'" class="entry-featured audio-embed">';
						echo '<div class="embed-wrap">';
							echo wp_oembed_get($embed);
						echo '</div>';
						echo '</div>';
					}else{
						$audio = '<div id="audio-featured-'.$post_id.'" class="audio-embed-wrap"><audio class="audio-embed">';
						$source = '<source type="%s" src="%s" />';
						foreach ( $audio_args as $type => $audio_src ) {
							$audio_type = wp_check_filetype( $audio_src, wp_get_mime_types() );
							$audio .= sprintf( $source, $audio_type['type'], esc_url( $audio_src ) );
						}
						$audio .='</audio></div>';
						echo '<div class="entry-featured'.$featured_class.' '.$entry_featured_class.'">';
						echo viem_dt_echo($audio);
						echo '</div>';
					}
				}
			}elseif (has_post_thumbnail()){
				ob_start();
				?>
				<div class="entry-featured post-thumbnail <?php echo esc_attr($featured_class)?> <?php echo esc_attr($entry_featured_class)?>">
					<a class="dt-image-link" href="<?php echo esc_url(get_the_permalink()) ?>" title="<?php the_title_attribute(); ?>">
						<?php echo get_the_post_thumbnail( $post_id, $thumbnail_size ); ?>
						<?php if( $post_format == 'video' || $get_post_type == 'viem_video' )
							echo '<div class="dt-icon-video"></div>';
						?>
					</a>
				</div>
				<?php
				echo ob_get_clean();
			}
		}
		return;
	}
}

if( !function_exists('viem_dt_post_meta') ){
	function viem_dt_post_meta($show_date=true,$show_comment = true,$show_category= true,$show_author = true,$echo = true,$meta_separator= ', ',$date_format = null,$icon = false) {
		if(empty($date_format))
			$date_format = get_option( 'date_format' );
		$post_type = get_post_type();
		$html = array();
		// Author
		$author_html = '';
		if($show_author){
			$author_html .= '<span class="meta-author">';
			if($icon)
				$author_html .= '<i class="fa fa-pencil-square-o"></i>';
			$author = sprintf(
				'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
				esc_url( get_author_posts_url( get_the_author_meta( 'ID' ), get_the_author_meta( 'nicename' ) ) ),
				esc_attr( sprintf( esc_html_e( 'Posts by %s', 'viem'), get_the_author() ) ),
				get_the_author()
			);
			$author_html .= sprintf(esc_html_e('By %1$s', 'viem'),$author);
			$author_html .= '</span>';
			$html[] = $author_html;
		}
		// Date
		$date_html = '';
		if($show_date){
			$date_html .= '<span class="meta-date">';
			$date_html .= '<time datetime="' . esc_attr(get_the_date('c')) . '" data-itemprop="dateCreated">';
			if($icon)
				$date_html .= '<i class="fa fa-clock-o"></i>';
			$date_html .= esc_html(get_the_date($date_format));
			$date_html .= '</time>';
			$date_html .= '</span>';
			$html[] = $date_html;
		}
		// Categories
		$categories_html = '';
		if($show_category){
			$categories_html .= '<span class="meta-category">';
			if($icon)
				$categories_html .= '<i class="fa fa-folder-open-o"></i>';
			$categories_html .= sprintf(esc_html_e('In %1$s','viem'),get_the_category_list(', '));
			$categories_html .= '</span>';
			$html[] = $categories_html;
		}
		
		
		// Comments
		$comments_html = '';
		if (comments_open()) {
			$comment_title = '';
			$comment_number = get_comments_number();
			if (get_comments_number() == 0) {
				$comment_title = sprintf(esc_html_e('Leave a comment on: &ldquo;%s&rdquo;', 'viem') , get_the_title());
				$comment_number = '0 '.esc_html_e('Comment', 'viem');
			} else if (get_comments_number() == 1) {
				$comment_title = sprintf(esc_html_e('View a comment on: &ldquo;%s&rdquo;', 'viem') , get_the_title());
				$comment_number = '1 ' . esc_html_e('Comment', 'viem');
			} else {
				$comment_title = sprintf(esc_html_e('View all comments on: &ldquo;%s&rdquo;', 'viem') , get_the_title());
				$comment_number =  get_comments_number() . ' ' . esc_html_e('Comments', 'viem');
			}
				
			$comments_html.= '<span class="meta-comment">';
			if($icon)
				$comments_html .= '<i class="fa fa-comment-o"></i>';
			$comments_html .= '<a' . ' href="' . esc_url(get_comments_link()) . '"' . ' title="' . esc_attr($comment_title) . '"' . ' class="meta-comments">';
			$comments_html.=  $comment_number . '</a></span> ';
			$comments_html.='<meta content="UserComments:'.get_comments_number().'" itemprop="interactionCount">';
		}
		if($show_comment)
			$html[] = $comments_html;
		
		if($meta_separator !== false && !$icon)
			$html = implode('<span class="meta-separator">'.$meta_separator.'</span>', $html);
		else 
			$html = implode("\n",$html);
		
		if($echo)
			echo viem_dt_echo($html);
		else 
			return $html;
	}
}

if( !function_exists('viem_dt_timeline_date') ){
	function viem_dt_timeline_date($args=array()){
		$defaults = array(
				'prev_post_month' 	=> null,
				'post_month' 		=> 'null'
		);
		$args = wp_parse_args( $args, $defaults );
		if( $args['prev_post_month'] != $args['post_month'] ) {
		?>
			<div class="timeline-date">
				<span class="timeline-date-title"><?php echo get_the_date('M Y')?></span>
			</div>
			<?php
		}
	}
}

if( !function_exists('viem_paginate_links_short') ){
	function viem_paginate_links_short($args = array(), $query = null){
		global $wp_rewrite, $wp_query;
		do_action( 'dt_pagination_short_start' );
		
		if ( !empty($query)) {
			$wp_query = $query;
		}
		
		if ( 1 >= $wp_query->max_num_pages )
			return;
		
		$paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
		$max_num_pages = intval( $wp_query->max_num_pages );
		// Setting up default values based on the current URL.
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$url_parts    = explode( '?', $pagenum_link );
		
		// Get max pages and current page out of the current query, if available.
		$total   = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
		$current = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		
		// Append the format placeholder to the base URL.
		$pagenum_link = trailingslashit( $url_parts[0] ) . '%_%';
		
		// URL base depends on permalink settings.
		$format  = $wp_rewrite->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $wp_rewrite->using_permalinks() ? user_trailingslashit( $wp_rewrite->pagination_base . '/%#%', 'paged' ) : '?paged=%#%';
		
		
		$defaults = array(
			'base' => esc_url(add_query_arg( 'paged', '%#%' )),
			'format' => $format,
			'total' => $max_num_pages,
			'current' => $paged,
			'prev_text' => '<i class="fa fa-angle-left"></i>',
			'next_text' => '<i class="fa fa-angle-right"></i>',
			'add_fragment' => '',
			'add_args'=>array(),
			'before' => '<div class="paginate"><div class="paginate_links"><span class="pagination-meta">'.sprintf(esc_html_e("%d/%d", 'viem'), $paged, $max_num_pages).'</span>',
			'after' => '</div></div>',
			'echo' => true,
		);
		$defaults = apply_filters( 'dt_pagination_short_args_defaults', $defaults );
		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'dt_pagination_short_args', $args );
		if ( isset( $url_parts[1] ) ) {
			// Find the format argument.
			$format_query = parse_url( str_replace( '%_%', $args['format'], $args['base'] ), PHP_URL_QUERY );
			wp_parse_str( $format_query, $format_arg );
	
			// Remove the format argument from the array of query arguments, to avoid overwriting custom format.
			wp_parse_str( esc_url(remove_query_arg( array_keys( $format_arg ), $url_parts[1] ), $query_args ));
			$args['add_args'] = array_merge( $args['add_args'], urlencode_deep( $query_args ) );
		}
		$add_args = $args['add_args'];
		$current  = (int) $args['current'];
		$prev_href='';
		$next_href='';
		if ($current && 1 < $current ) :
			$link = str_replace( '%_%', 2 == $current ? '' : $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current - 1, $link );
			if ( $add_args )
				$link = esc_url(add_query_arg( $add_args, $link ));
			$link .= $args['add_fragment'];
			$prev_href = ' href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '"';
		endif;
		if ($current && ( $current < $total || -1 == $total ) ) :
			$link = str_replace( '%_%', $args['format'], $args['base'] );
			$link = str_replace( '%#%', $current + 1, $link );
			if ( $add_args )
				$link = esc_url(add_query_arg( $add_args, $link ));
			$link .= $args['add_fragment'];
			$next_href = ' href="' . esc_url( apply_filters( 'paginate_links', $link ) ) . '"';
		endif;
		$page_links[] = '<a class="prev page-numbers" '.$prev_href.'>' . $args['prev_text'] . '</a>';
		$page_links[] = '<a class="next page-numbers" ' .$next_href. '>' . $args['next_text'] . '</a>';
		$page_links = join("\n", $page_links);
		$page_links = $args['before'] . $page_links . $args['after'];
		$page_links = apply_filters( 'dt_pagination_short', $page_links );
		
		do_action( 'dt_pagination_short_end' );
		
		if ( $args['echo'] )
			echo viem_dt_echo($page_links);
		else
			return $page_links;
	}
}

if( !function_exists('viem_paginate_links') ){
	function viem_paginate_links( $args = array(), $query = null ){
		global $wp_rewrite, $wp_query;
		$temp_query = $wp_query;
		do_action( 'dt_pagination_start' );
	
		if ( !empty($query)) {
			$wp_query = $query;
		}
		
		if ( $wp_query->max_num_pages < 2 ) {
			$wp_query = $temp_query;
			return;
		}
	
		$paged = ( get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1 );
	
		$max_num_pages = intval( $wp_query->max_num_pages );
	
		$defaults = array(
				'base' => esc_url(add_query_arg( 'paged', '%#%' )),
				'format' => '',
				'total' => $max_num_pages,
				'current' => $paged,
				'prev_next' => true,
				'prev_text' => '<i class="fa fa-angle-left"></i>',
				'next_text' => '<i class="fa fa-angle-right"></i>',
				'show_all' => false,
				'end_size' => 1,
				'mid_size' => 1,
				'add_fragment' => '',
				'type' => 'plain',
				'before' => '<div class="paginate paging-navigation"><div class="paginate_links pagination loop-pagination">',
				'after' => '</div></div>',
				'echo' => true,
				'use_search_permastruct' => true
		);
	
		$defaults = apply_filters( 'dt_pagination_args_defaults', $defaults );
	
		if( $wp_rewrite->using_permalinks() && ! is_search() )
			$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link() ) . 'page/%#%' );
	
		if ( is_search() )
			$defaults['use_search_permastruct'] = false;
	
		if ( is_search() ) {
			if ( class_exists( 'BP_Core_User' ) || $defaults['use_search_permastruct'] == false ) {
				$search_query = get_query_var( 's' );
				$paged = get_query_var( 'paged' );
				$base = esc_url(add_query_arg( 's', urlencode( $search_query ) ));
				$base = esc_url(add_query_arg( 'paged', '%#%' ));
				$defaults['base'] = $base;
			} else {
				$search_permastruct = $wp_rewrite->get_search_permastruct();
				if ( ! empty( $search_permastruct ) ) {
					$base = get_search_link();
					$base = esc_url(add_query_arg( 'paged', '%#%', $base ));
					$defaults['base'] = $base;
				}
			}
		}
	
		$args = wp_parse_args( $args, $defaults );
	
		$args = apply_filters( 'dt_pagination_args', $args );
	
		if ( 'array' == $args['type'] )
			$args['type'] = 'plain';
	
		$pattern = '/\?(.*?)\//i';
	
		preg_match( $pattern, $args['base'], $raw_querystring );
		
		if(!empty($raw_querystring)){
			if( $wp_rewrite->using_permalinks() && $raw_querystring )
				$raw_querystring[0] = str_replace( '', '', $raw_querystring[0] );
			$args['base'] = str_replace( $raw_querystring[0], '', $args['base'] );
			$args['base'] .= substr( $raw_querystring[0], 0, -1 );
		}
		$page_links = paginate_links( $args );
	
		$page_links = str_replace( array( '&#038;paged=1\'', '/page/1\'' ), '\'', $page_links );
	
		$page_links = $args['before'] . $page_links . $args['after'];
	
		$page_links = apply_filters( 'dt_pagination', $page_links );
	
		do_action( 'dt_pagination_end' );
		
		$wp_query = $temp_query;
		
		if ( $args['echo'] )
			echo viem_print_string($page_links);
		else
			return $page_links;
	
	}
}
/**
 * Returns the first found number from an string
 * Parsing depends on given locale (grouping and decimal)
 *
 * Examples for input:
 * '  2345.4356,1234' = 23455456.1234
 * '+23,3452.123' = 233452.123
 * ' 12343 ' = 12343
 * '-9456km' = -9456
 * '0' = 0
 * '2 054,10' = 2054.1
 * '2'054.52' = 2054.52
 * '2,46 GB' = 2.46
 *
 * @param string|float|int $value
 * @return float|null
 */
if( !function_exists('viem_get_number') ){
	function viem_get_number($value)
	{
		if (is_null($value)) {
			return null;
		}
	
		if (!is_string($value)) {
			return floatval($value);
		}
	
		//trim spaces and apostrophes
		$value = str_replace(array('\'', ' '), '', $value);
	
		$separatorComa = strpos($value, ',');
		$separatorDot  = strpos($value, '.');
	
		if ($separatorComa !== false && $separatorDot !== false) {
			if ($separatorComa > $separatorDot) {
				$value = str_replace('.', '', $value);
				$value = str_replace(',', '.', $value);
			}
			else {
				$value = str_replace(',', '', $value);
			}
		}
		elseif ($separatorComa !== false) {
			$value = str_replace(',', '.', $value);
		}
	
		return floatval($value);
	}
}

if( !function_exists('viem_format_color') ){
	function viem_format_color( $color ='' ) {
		if(strstr($color,'rgba')){
			return $color;
		}
		
		$hex = trim( str_replace( '#', '', $color ) );
		if(empty($hex))
			return '';
	
		if ( strlen( $hex ) == 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}
	
		if ( $hex ){
			if ( ! preg_match( '/^#[a-f0-9]{6}$/i', $hex ) ) {
				return '#' . $hex;
			}
		}
		return '';
	}
}

if( !function_exists('viem_hex2rgb') ){
	/* Convert Hexa to RGB */
	function viem_hex2rgb($hex) {
	   $hex = str_replace("#", "", $hex);
	
	   if(strlen($hex) == 3) {
		  $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		  $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		  $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	   } else {
		  $r = hexdec(substr($hex,0,2));
		  $g = hexdec(substr($hex,2,2));
		  $b = hexdec(substr($hex,4,2));
	   }
	   $rgb = array($r, $g, $b);
	   return implode(",", $rgb); // returns the rgb values separated by commas
	}
}

if( !function_exists('viem_rgb2hexa') ){
	/* Convert RGB to HEXA
	 *
	 * return hexa color without '#' at beginning
	 * $rgb: array of RGB values
	 */
	function viem_rgb2hexa($rgb) {
	   if(count($rgb) == 3) {
			if($rgb[0] < 10) $hex1 = '0'.$rgb[0];
			else $hex1 = dechex($rgb[0]);
			if($rgb[1] < 10) $hex2 = '0'.$rgb[1];
			else $hex2 = dechex($rgb[1]);
			if($rgb[2] < 10) $hex3 = '0'.$rgb[2];
			else $hex3 = dechex($rgb[2]);
		
		    return $hex1 . $hex2 . $hex3;
		}
		 
		return '000';
	}
}

if( !function_exists('viem_morphsearchform') ){
	function viem_morphsearchform(){
		ob_start();
		?>
		<div class="morphsearch" id="morphsearch">
			<form class="morphsearch-form" method="get"  action="<?php echo esc_url( home_url( '/' ) ) ?>">
				<input type="search" name="s" placeholder="<?php esc_attr__('Search...','viem')?>" class="morphsearch-input">
				<button type="submit" class="morphsearch-submit"></button>
			</form>
			<div class="morphsearch-content<?php echo (defined( 'WOOCOMMERCE_VERSION' )  ? ' has-3colum':'') ?>">
				<?php if ( defined( 'WOOCOMMERCE_VERSION' ) ) { ?>
				<div class="dummy-column">
					<h2><?php esc_html_e('Product','viem') ?></h2>
					<?php 
					$query_args = array(
			    		'posts_per_page' => 6,
			    		'post_status' 	 => 'publish',
			    		'post_type' 	 => 'product',
			    		'no_found_rows'  => 1,
						'orderby'		 =>'date',
			    		'order'          => 'DESC'
			    	);
					$query_args['meta_query'] = WC()->query->get_meta_query();
					$r = new WP_Query( $query_args );
					if ( $r->have_posts() ) {
						while ( $r->have_posts() ) {
							$r->the_post();
							global $product;
							?>
							<a href="<?php the_permalink()?>" class="dummy-media-object">
								<?php echo viem_dt_echo($product->get_image('dt-thumbnail-square')); ?>
								<div>
									<h3><?php echo viem_dt_echo($product->get_title()); ?></h3>
									<?php if ( ! empty( $show_rating ) ) echo viem_dt_echo($product->get_rating_html()); ?>
									<div class="price">
										<?php echo viem_dt_echo($product->get_price_html()); ?>
									</div>
								</div>
							</a>
							<?php
						}
					}
					wp_reset_postdata();
					?>
				</div>
				<?php }?>
				<div class="dummy-column">
					<h2><?php esc_html_e('Popular','viem') ?></h2>
					<?php 
					$re = new WP_Query(array(
						'posts_per_page'      => 6,
						'no_found_rows'       => true,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'meta_key'			  => "_thumbnail_id",
						'orderby'			  =>'comment_count',
						'order' 			  => 'DESC',
					) );
					if ($re->have_posts()) :
					?>
					<?php while ( $re->have_posts() ) : $re->the_post(); ?>
					<a href="<?php the_permalink()?>" class="dummy-media-object">
						<?php the_post_thumbnail('dt-thumbnail-square')?>
						<div>
							<h3><?php the_title()?></h3>
							<?php echo '<span>'.sprintf(esc_html_e('%s Comment','viem'),get_comments_number()).'</span>'; ?>
						</div>
					</a>
					<?php endwhile; ?>
					<?php 
					endif;
					wp_reset_postdata();
					?>
				</div>
				<div class="dummy-column">
					<h2><?php esc_html_e('Recent','viem') ?></h2>
					<?php 
					$rc = new WP_Query(array(
						'posts_per_page'      => 6,
						'no_found_rows'       => true,
						'post_status'         => 'publish',
						'ignore_sticky_posts' => true,
						'meta_key' => "_thumbnail_id",
					) );
					if ($rc->have_posts()) :
					?>
					<?php while ( $rc->have_posts() ) : $rc->the_post(); ?>
					<a href="<?php the_permalink()?>" class="dummy-media-object">
						<?php the_post_thumbnail('dt-thumbnail-square')?>
						<div>
							<h3><?php the_title()?></h3>
							<span>
								<time datetime="<?php echo get_the_date('Y-m-d\TH:i:sP') ?>"><?php echo get_the_date('M j, Y') ?></time>
							</span>
						</div>
					</a>
					<?php endwhile; ?>
					<?php 
					endif;
					wp_reset_postdata();
					?>
				</div>
			</div>
			<span class="morphsearch-close"></span>
		</div>
		<div class="morphsearch-overlay"></div>
		<?php
		return ob_get_clean();
	}
}

if( !function_exists('viem_mailchimp_form') ){
	function viem_mailchimp_form($echo = true){
		ob_start();
		$id = uniqid('mailchimp-form-');
		?>
		<form id="<?php echo esc_attr($id)?>" method="get" class="mailchimp-form" action="<?php echo esc_url( home_url( '/' ) );?>">
			<?php 
			$mailchimp_subscribe = isset($_GET['mailchimp_subscribe']) ? $_GET['mailchimp_subscribe']:'';
			if(!empty($mailchimp_subscribe)):
			?>
			<div class="mailchimp-form-result">
				<?php 
				if($mailchimp_subscribe == -1){
					echo '<span class="error">'.esc_html__( 'Not Subscribe to Mailchimp!', 'viem' ).'<span>';
				}elseif ($mailchimp_subscribe == 1){
					echo '<span class="success">'.esc_html__( 'Subscribe to Mailchimp Successful!', 'viem' ).'</span>';
				}
				?>
			</div>
			<?php endif;?>
			<div class="mailchimp-form-content">
				<div class="mailchimp-form-content-wrap">
					<div class="newsletter-field">
						<label for="subscribe_email" class="hide"><?php esc_html_e('Subscribe','viem')?></label>
						<input type="email" class="dt-form-text form-control"  required="required" autocomplete="off" placeholder="<?php esc_attr_e('Enter your email...','viem')?>" name="email">
					</div>
					<div class="mailchimp-submit-wrap">
						<button type="submit" class="btn btn-outline mailchimp-submit viem-main-color-bg"><i class="fa fa-paper-plane"></i><span><?php esc_html_e('Subscribe','viem')?></span></button>
					</div>
				</div>
				<div style="display: none;">
					<input type="hidden" name="_mailchimp_id" value="<?php echo esc_attr($id); ?>">
					<input type="hidden" name="_subscribe_nonce" value="<?php echo esc_attr(wp_create_nonce('mailchimp_subscribe_nonce')); ?>">
					<input type="hidden" name="action" value="dawnthemes_mailchimp_subscribe">
				</div>
			</div>
		</form>
		<?php
		$form = ob_get_clean();
		$form = apply_filters('viem_mailchimp_form', $form);
		if($echo)
			echo viem_print_string($form);
		else 
			return $form;
	}
}

/**
 * Get related posts
 *
 * @params $post_id (optional). If not passed, it will try to get global $post
 */
if(!function_exists('viem_dt_get_related_posts')){
	function viem_dt_get_related_posts( $post_id = null, $post_type = 'post', $posts_per_page = '' ) {
		if(!$post_id){
			global $post;
			if($post) {
				$post_id = $post->ID;
			} else {
				// return if cannot find any post
				return;
			}
		}

		$number = viem_get_theme_option('related_posts_count', 4);
		$relatedPostsOrderBy = viem_get_theme_option('related_posts_order_by', 'rand'); // date or rand
		
		$post_type = (!empty($post_type) ? $post_type : 'post');
		$posts_per_page = (!empty($posts_per_page) ? $posts_per_page : $number);
		$args = array(
			'post_type' => "{$post_type}",
			'post_status' => 'publish',
			'posts_per_page' => $posts_per_page,
			'orderby' => $relatedPostsOrderBy,
			'ignore_sticky_posts' => 1,
			'post__not_in' => array ($post_id)
		);
		 
		$get_related_post_by = viem_get_theme_option('related_posts_by','cat');

		if ($get_related_post_by == 'cat') {
			$categories = wp_get_post_categories($post_id);
				
			$args['category__in'] = $categories;
		} else {
			$posttags = wp_get_post_tags($post_id);
			$array_tags = array();
			if ($posttags) {
				foreach($posttags as $tag) {
					$tags = $tag->term_id ;
					array_push ( $array_tags, $tags);
				}
			}
				
			$args['tag__in'] = $array_tags;
		}
		
		$related_items = new WP_Query( $args );
		return $related_items;
	}
}

if(!function_exists('viem_get_related_videos')){
	function viem_get_related_videos( $post_id = null, $posts_per_page = 4 ) {
		if(!$post_id){
			global $post;
			if($post) {
				$post_id = $post->ID;
			} else {
				// return if cannot find any post
				return;
			}
		}
		
		$post_type = get_post_type($post_id);
		$taxonomies = 'video_cat';
		
		$args = array(
			'post_type' => "{$post_type}",
			'post_status' => 'publish',
			'posts_per_page' => $posts_per_page,
			'orderby' => 'rand',
			'ignore_sticky_posts' => 1,
			'post__not_in' => array ($post_id)
		);
		 
		$cat_ids = array();
		$categories = wp_get_object_terms( $post_id, $taxonomies ); 
		
		if( !empty($categories) ):
			foreach ($categories as $category):
			array_push($cat_ids, $category->term_id);
			endforeach;
		endif;
		
		if( !empty($cat_ids) ){
			$args['tax_query'][] =
			array(
				'taxonomy'			=> $taxonomies,
				'field'				=> 'term_id',
				'terms'				=> $cat_ids,
				'operator'			=> 'IN'
			);
		}
		
		$related_items = new WP_Query( $args );
		return $related_items;
	}
}

if ( ! function_exists( 'viem_dt_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
*/
function viem_dt_posted_on() {
	$time_string = '<i class="fa fa-clock-o" aria-hidden="true"></i><time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<i class="fa fa-clock-o" aria-hidden="true"></i><time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		get_the_date(),
		esc_attr( get_the_modified_date( 'c' ) ),
		get_the_modified_date()
	);

	printf( '<span class="posted-on"><span class="screen-reader-text">%1$s </span><a href="%2$s" rel="bookmark">%3$s</a></span>',
		_x( 'Posted on', 'Used before publish date.', 'viem' ),
		esc_url( get_permalink() ),
		$time_string
	);
	
}
endif;

if ( ! function_exists( 'viem_dt_paging_nav_ajax' ) ) :
function viem_dt_paging_nav_ajax($loadmore_text = 'Load More', $query = null){
	// Don't print empty markup if there's only one page.
	global $wp_query;
	$term_query = $wp_query;
	if($query){
		$wp_query = $query;
	}
	if ( $wp_query->max_num_pages < 2 ) {
		$wp_query = $term_query;
		return;
	}
	?>
	<div class="loadmore-wrap">
		<div id="loadmore-action" class="loadmore-action">
			<div class="loadmore-loading"><div class="dtwl-navloading"><div class="dtwl-navloader"></div></div></div>
			<button type="button" class="btn-loadmore"><?php echo esc_html($loadmore_text) ?></button>
		</div>
	</div>
	<?php
	$wp_query = $term_query;
}
endif;

if ( ! function_exists( 'viem_dt_paging_nav_default' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable. Default WordPress style
*/
function viem_dt_paging_nav_default() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	?>
	<nav class="wp-paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'viem' ); ?></h1>
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( esc_html__( 'Previous Posts', 'viem' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( esc_html__( 'Next Posts', 'viem' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Display navigation to next/previous post when applicable.
 *
 */
if( !function_exists('viem_dt_post_nav') ){
	function viem_dt_post_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;

		$img_size = viem_get_theme_option('blog-image-size', 'default');
		?>
		<nav class="navigation post-navigation" role="navigation">
			<div class="nav-links">
				
				<?php if($previous):?>
					<div class="nav-previous">
						<div class="meta-nav" aria-hidden="true">
							<i class="fa fa-angle-double-left" aria-hidden="true"></i>
							<?php echo esc_html_e('Prev','viem')?>
						</div>
						<div class="nav-content dt-effect6 <?php echo ( !has_post_thumbnail($previous->ID) ) ? 'no-thumbnail' : '';?>">
							<?php previous_post_link('%link',''); // link overlay ?>
							<?php 
							if( has_post_thumbnail($previous->ID) ):
								echo get_the_post_thumbnail( $previous->ID, $img_size );
							endif;
							?>
							<span class="screen-reader-text"><?php echo esc_html__( 'Previous post:', 'viem' ) ?></span>
							<span class="post-title"><?php echo get_the_title($previous->ID);?></span>
						</div>
					</div>
				<?php endif;?>
				
				<?php 
				if(!empty($next)):?>
					<div class="nav-next">
						<div class="meta-nav" aria-hidden="true">
							<?php echo esc_html_e('Next','viem')?>
							<i class="fa fa-angle-double-right" aria-hidden="true"></i>
						</div>
						<div class="nav-content dt-effect6 <?php echo ( !has_post_thumbnail($next->ID) ) ? 'no-thumbnail' : '';?>">
							<?php next_post_link( '%link',''); // link overlay ?>
							<?php
							if( has_post_thumbnail($next->ID) ):
								echo get_the_post_thumbnail( $next->ID, $img_size );
							endif;
							?>
							<span class="screen-reader-text"><?php echo esc_html__( 'Next post:', 'viem' ) ?></span>
							<span class="post-title"><?php echo get_the_title($next->ID);?></span>
						</div>
					</div>
				<?php endif;?>
			</div>
		</nav>
		<?php
	}
}

if( !function_exists('viem_video_nav') ){
	function viem_video_nav() {
		global $post;

		// Don't print empty markup if there's nowhere to navigate.
		$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
		$next     = get_adjacent_post( false, '', false );

		if ( ! $next && ! $previous )
			return;
		?>
		
		<?php if($previous):?>
			<a href="<?php echo esc_url( get_permalink($previous->ID)); ?>" class="prev-video">
				<span><?php echo esc_html__('Prev video', 'viem');?></span>
			</a>
		<?php endif;?>
				
		<?php
		if(!empty($next)):?>
			<a href="<?php echo esc_url( get_permalink($next->ID)); ?>" class="next-video">
				<span><?php echo esc_html__('Next video', 'viem');?></span>
			</a>
		<?php endif;?>
		<?php
	}
}

if(!function_exists('viem_dt_categorized_blog')){
	/**
	 * Find out if blog has more than one category.
	 *
	 * @return boolean true if blog has more than 1 category
	 */
	function viem_dt_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'viem_dt_category_count' ) ) ) {
			// Create an array of all the categories that are attached to posts
			$all_the_cool_cats = get_categories( array(
				'hide_empty' => 1,
			) );
	
			// Count the number of categories that are attached to the posts
			$all_the_cool_cats = count( $all_the_cool_cats );
	
			set_transient( 'viem_dt_category_count', $all_the_cool_cats );
		}
	
		if ( 1 !== (int) $all_the_cool_cats ) {
			// This blog has more than 1 category so viem_dt_categorized_blog should return true
			return true;
		} else {
			// This blog has only 1 category so viem_dt_categorized_blog should return false
			return false;
		}
	}
}

/**
 * Flush out the transients used in viem_dt_categorized_blog.
 *
 */
function viem_dt_category_transient_flusher() {
	// Like, beat it. Dig?
	delete_transient( 'viem_dt_category_count' );
}
add_action( 'edit_category', 'viem_dt_category_transient_flusher' );
add_action( 'save_post',     'viem_dt_category_transient_flusher' );


if( !function_exists('viem_dt_print_social_profile') ):
/**
 * Display Social profile: FaceBook, Twitter, Google+...
*/
function viem_dt_print_social_profile( $social_list = array('') ){
	if( empty($social_list) )
		return;
	
	$social_profiles = array();
	
	foreach ($social_list as $get_social_profile){
		$social_profiles[$get_social_profile] = esc_url(viem_get_theme_option("{$get_social_profile}-url", ''));
	}
	
	$social_profiles_filter = array_filter($social_profiles);
	
	if( !empty($social_profiles_filter) ):
	$social_target = viem_get_theme_option('social-target', '_blank');
	?>
	<ul class="viem_dt_social_profile ulclear">
		<?php 
		foreach ($social_profiles as $social => $social_url):
			if(!empty($social_url)):
			?>
			<li><a href="<?php echo esc_url($social_url);?>" title="<?php echo esc_attr($social);?>" target="<?php echo esc_attr($social_target);?>"><i class="fa fa-<?php echo esc_attr($social);?>"></i><span class="social-info"><?php echo esc_html($social); ?></span></a></li>
			<?php
			endif;
		endforeach;
		?>
	</ul>
	<?php
	endif;
}
endif;

if(!function_exists('viem_dt_show_author_social_links')):
/**
 * Display Author social link
 * @param String $field the field of the users record.
 * @param int $user_id Optional. User ID.
 * @param String $echo Optional. True.
 */
function viem_dt_show_author_social_links($field = '', $user_id = false, $echo = true){
	$dawn_author_links = array(
		'facebook' => 'viem-user-facebook',
		'twitter' => 'viem-user-twitter',
		'google-plus' => 'viem-user-google',
		'youtube' => 'viem-user-youtube',
		'flickr' => 'viem-user-flickr',
		'instagram' => 'viem-user-instagram',
		'pinterest' => 'viem-user-pinterest',
		'envelope' => 'viem-user-envelope',
	);
	$html = '';
	foreach($dawn_author_links as $account => $key){
		$url = get_the_author_meta($key, $user_id);
			
		if($url != ''){
			if($account == 'envelope') $url = 'mailto:' . $url;
			$html .= '<div class="dt-socials-item '.$account.'">';
			$html .= '<a class="dt-socials-item-link" href="' . $url . '" target="_blank"><i class="fa fa-'.$account.'"></i></a>';
			$html .= '</div>';
		}
	}
	if($echo){
		echo viem_print_string( $html );
	}else{
		return $html;
	}
}
endif;

if( !function_exists('viem_tab_loadmore') ){
	function viem_tab_loadmore(){

		$tab_args = array(
			'wrap_id'			=> $_POST['wrap_id'],
			'display_type'		=> $_POST['display_type'],
			'query_types'		=> $_POST['query_types'],
			'tab'				=> $_POST['tab'],
			'orderby'			=> $_POST['orderby'],
			'meta_key'			=> $_POST['meta_key'],
			'order'				=> $_POST['order'],
			'number_query'		=> $_POST['number_query'],
			'columns'			=> $_POST['columns'],
			'number_load'		=> $_POST['number_load'],
			'number_display'	=> $_POST['number_display'],
			'template'			=> $_POST['template'],
			'col'				=> $_POST['col'],
			'tab_id'			=> $_POST['tab_id'],
			'img_size'			=> $_POST['img_size'],
			'tab_active'		=> 'active',
		);
		
		viem_dt_get_template( 'tpl-tab.php', array('tab_args' => $tab_args), '/vc_templates/tpl/', '/vc_templates/tpl/' );
		wp_die();
	}
}

if(!function_exists('viem_nav_content')){
	function viem_nav_content(){
		$post_type			= isset($_POST['post_type']) ? $_POST['post_type'] : 'post';
		$category			= $_POST['cat'];
		$orderby			= $_POST['orderby'];
		$meta_key			= $_POST['meta_key'];
		$meta_value			= $_POST['meta_value'];
		$order				= $_POST['order'];
		$offset				= $_POST['offset'];
		$paged				= $_POST['current_page'];
		$columns			= $_POST['columns'];

		$posts_per_page		= $_POST['posts_per_page'];
		$img_size			= $_POST['img_size'];
		$style				= isset($_POST['style']) ? $_POST['style'] : 'style_1';
		$show_excerpt		= isset($_POST['show_excerpt']) ? $_POST['show_excerpt'] : 'show';
	
		$orderby    		= sanitize_title( $orderby );
		$order       		= sanitize_title( $order );
	
		$query_args = array(
			'posts_per_page' 	=> $posts_per_page,
			'post_status' 	 	=> 'publish',
			'post_type' 	 	=> $post_type,
			'offset'            => $offset,
			'orderby'          	=> $orderby == '' ? 'date' : $orderby,
			'meta_key'			=> $meta_key == '' ? '' : $meta_key,
			'meta_value'		=> $meta_value == '' ? '' : $meta_value,
			'order'          	=> $order == 'asc' ? 'ASC' : 'DESC',
			'paged'				=> $paged,
		);
		
		$taxonomy				= isset($_POST['taxonomy']) ? $_POST['taxonomy'] : 'category';

		if( !empty($category) ){
			$query_args['tax_query'][] =
			array(
				'taxonomy'			=> $taxonomy,
				'field'				=> 'slug',
				'terms'				=> $category,
				'operator'			=> 'IN'
			);
		}
	
		$p = new WP_Query( $query_args  );
		
		$i = 0; 
		$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
		$post_class = 'post viem_video';
		switch ($style){
			case 'v_badges_ajax_nav':
					$size = 'viem-200x300';
					
					while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
						
						?>
						<div class="v-grid-item">
							<?php
							$args = array(
								'img_size' => $img_size,
								'post_class' => $post_class
							);
							viem_dt_get_template( 'item-badge-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
							?>
						</div>
					<?php endwhile;
					if($offset + $posts_per_page >= $limit){
						// there are no more product
						// print a flag to detect
						echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
					}
					?>
					<?php
				break;
			case 'v_trending_grid_v5':
				$post_col = ' v-grid-item';
				
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts; $i++;
					$col = ( $i == 1 ) ? 'first clearfix' : $post_col;
					$nsize =  ( $i == 1 ) ? 'large' : $img_size;
				?>
					<div class="<?php echo esc_attr($col);?>">
						<?php
						$args = array(
							'img_size' => $nsize,
							'post_class' => $post_class,
							'i'			=> $i
						);
						viem_dt_get_template( 'item-trending-grid-v5.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				?>
				<?php
				break;
			case 'v_trending_grid':
				$post_col = ' v-grid-item';
				
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
				?>
					<div class="<?php echo esc_attr($post_col);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-trending-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				?>
				<?php
				break;
			case 'v_featured_grid_v3':
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
				$i++;
				
				$item_class = 'item_video-'.$i;
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'i'		=> $i,
							'excerpt_length' => 41,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-featured_grid_v3.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				?>
				<?php
				break;
			case 'v_cat_grid_v3':
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
				$i++;
				
				$item_class = 'item_video-'.$i;
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-cat-grid_v3.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				?>
				<?php
				break;
			case 'v_cat_grid'; case 'v_featured_grid':
				$post_col = 'v-grid-item';
				
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
				?>
					<div class="<?php echo esc_attr($post_col);?>">
						<?php
						$args = array(
							'img_size' => $img_size,
							'post_class' => $post_class,
							'show_excerpt' => $show_excerpt
						);
						viem_dt_get_template( 'item-cat-grid.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				?>
				<?php
				break;
			case 'v_cat_style_2':
				
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
				$i++;
				$item_class = 'item_video-'.$i;
				?>
					<div class="<?php echo esc_attr($item_class);?>">
						<?php
						$args = array(
							'i'		=> $i,
							'img_size' => $img_size,
							'post_class' => $post_class
						);
						viem_dt_get_template( 'item-cat-style_2.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
						?>
					</div>
				<?php endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				break;
			case 'v_cat_style_1';
				
				while ($p->have_posts()): $p->the_post(); $limit = $p->found_posts;
					$i++;
					$item_class = 'item_video-'.$i;
					if( $i == 1 ){
						$item_class .= ' col-md-12 col-sm-12';
					}elseif($i > 1){
						$item_class .= ' col-md-6 col-sm-6';
					}
					?>
						<div class="<?php echo esc_attr($item_class);?>">
							<?php 
							$args = array(
								'i'		=> $i,
								'img_size' => $img_size,
								'post_class' => $post_class
							);
							viem_dt_get_template( 'item-cat-style_1.php', $args, '/vc_templates/tpl/', 'vc_templates/tpl/' );
							?>
						</div>
						<?php
				endwhile;
				if($offset + $posts_per_page >= $limit){
					// there are no more product
					// print a flag to detect
					echo '<div id="viem-ajax-no-p" class=""><!-- --></div>';
				}
				break;

			default:
				break;
		}
		wp_reset_postdata();
		wp_die();
	}
}

if( !function_exists('viem_query_post_orderby') ){
	function viem_query_post_orderby($vc=false){
		$orderby = apply_filters('viem_query_post_orderby',array(
			'date'=>__( 'Date', 'viem' ),
			'ID'=>__( 'ID', 'viem' ),
			'author'=>__( 'Author', 'viem' ),
			'title'=>__( 'Title', 'viem' ),
			'modified'=>__( 'Modified', 'viem' ),
			'rand'=>__( 'Random', 'viem' ),
			'comment_count'=>__( 'Comment count', 'viem' ),
			'menu_order'=>__( 'Menu order', 'viem' )
		),$vc);
		$vc_order_by = array();
		if($vc)
			foreach ($orderby as $key=>$value)
				$vc_order_by[$value]=$key;
			if($vc)
				return $vc_order_by;
			else
				return $orderby;
	}
}

if( !function_exists('viem_detect_is_retina') ){
	function viem_detect_is_retina(){
		if ( !isset( $_COOKIE['viem_retina'] ) ) {
			// this is used to set cookie to detect if screen is retina
			?>
			<script type="text/javascript">
			var viem_retina = 'retina='+ window.devicePixelRatio +';'+ viem_retina;
			document.cookie = viem_retina;
			if(document.cookie){
				// document.location.reload(true);
			}
			</script>
		<?php
		}
	}
}

if( !function_exists('viem_retina_logo') ){
	function viem_retina_logo(){
		if(viem_get_theme_option('retina_logo')):?>
			<style type="text/css" >
				@media only screen and (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi) {
					/* Retina Logo */
					#top-header-content .site-logo{background:url("<?php echo viem_get_theme_option('retina_logo'); ?>") no-repeat center; display:inline-block !important; background-size:contain;}
					#top-header-content .site-logo img{ opacity:0; visibility:hidden}
					#top-header-content .site-logo *{display:inline-block}
				}
			</style>
		<?php endif;
	}
}

if( !function_exists('viem_remote_get') ){
	function viem_remote_get($url, $args = array()){
		$defaults = array(
			'sslverify'=>false,
			'decompress'=>false,
		);
		$args = wp_parse_args($args,$defaults);
		return wp_remote_get($url,$args);
	}
}

if( !function_exists('viem_oembed_fetch_url_youtube') ){
	function viem_oembed_fetch_url_youtube($provider){
		$provider = add_query_arg( 'enablejsapi', '1', $provider );
		return $provider;
	}
}

if( !function_exists('viem_oembed_fetch_url_vimeo') ){
	function viem_oembed_fetch_url_vimeo($provider){
		$provider = add_query_arg( 'api', '1', $provider );
		$provider = add_query_arg( 'player_id','vimeo_palyer_'.uniqid(''), $provider );
		return $provider;
	}
}

if( !function_exists('_viem_get_video_data_api') ){
	function _viem_get_video_data_api($videos_ids,$service){
		$video_data = array();
		$google_api = viem_get_theme_option('google_api', '');
		switch ($service):
		case 'youtube':
			$videos = implode(',', $videos_ids);
			$yt_api_url = "https://www.googleapis.com/youtube/v3/videos?id={$videos}&part=id,contentDetails,snippet&key=".$google_api;
			$response = viem_remote_get($yt_api_url,array('sslverify'=>false,'decompress' => false));
			if(is_wp_error($response) ||  wp_remote_retrieve_response_code($response) != 200)
				return false;
			$result = wp_remote_retrieve_body($response);
			if(empty($result))
				return false;
			$json = @json_decode($result,true);
			if(!empty($json)){
				if (!empty($json['items']) && is_array($json['items'])) {
					foreach ($json['items'] as $video_item) {
						if (empty($video_item['id']))
							continue;
							
						$duration = $video_item['contentDetails']['duration'];
						if (!empty($duration)) {
							preg_match('/(\d+)H/', $duration, $match);
							$h = count($match) ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
								
							preg_match('/(\d+)M/', $duration, $match);
							$m = count($match) ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
								
							preg_match('/(\d+)S/', $duration, $match);
							$s = count($match) ? filter_var($match[0], FILTER_SANITIZE_NUMBER_INT) : 0;
								
							$duration = gmdate("H:i:s", intval($h * 3600 + $m * 60  + $s));
						}
							
						$video_data[$video_item['id']]= array(
							'service'=>'youtube',
							'id'=>$video_item['id'],
							'thumb' => viem_get_protocol() . '://img.youtube.com/vi/' . $video_item['id'] . '/default.jpg',
							'title' => $video_item['snippet']['title'],
							'duration' => $duration,
						);
					}
				}
			}
		break;
		case 'vimeo':
			foreach ($videos_ids as $video){
				$vimeo_api = 'vimeo.com/api/v2/video/' . $video . '.php';
				$response = viem_remote_get($vimeo_api,array('sslverify'=>false,'decompress' => false));
				if(is_wp_error($response) || wp_remote_retrieve_response_code($response) != 200)
					continue;
				$result = wp_remote_retrieve_body($response);
				$serialize = @unserialize($result);
				if(is_array($serialize) && count($serialize))
					$video_data[$video]= array(
						'service'=>'vimeo',
						'id'=>$video,
						'thumb' => $serialize[0]['thumbnail_small'],
						'title' => $serialize[0]['title'],
						'duration' => gmdate("H:i:s", intval($serialize[0]['duration'])),
					);
			}
			break;
		endswitch;
		return $video_data;
	}
}

if( !function_exists('viem_get_video_data') ){
	function viem_get_video_data($video_list,$service){ 
		$video_list = array_map('trim', explode(',', $video_list));
		if(empty($video_list))
			return false;
		
		$post = get_post();
		$cachekey = '_viem_listvideo_cache_';
		$cache_data = get_post_meta($post->ID,$cachekey,true);
		$videos_ids = array();
		foreach ($video_list as $video_id) {
			if (!isset($cache_data[$service][$video_id])) {
				$videos_ids[]= $video_id;
			}
		}
		if(!empty($videos_ids)){
			$video_api_data = _viem_get_video_data_api($videos_ids, $service);
			
			if(false !==$video_api_data){
				if (empty($cache_data[$service])) {
					$cache_data[$service] = $video_api_data;
				} else {
					$cache_data[$service] = $cache_data[$service] + $video_api_data;
				}
				update_post_meta($post->ID,$cachekey, $cache_data);
			}
		}
		$video_data = array();
		foreach ($video_list as $video_id) {
			if (!empty($cache_data[$service][$video_id])) {
				$video_data[$video_id] = $cache_data[$service][$video_id];
			}
		}
		return array($video_list,$video_data);
	}
}

if( !function_exists('viem_dt_gototop') ){
	function viem_dt_gototop(){
		if(viem_get_theme_option('back_to_top',1)){
			echo '<a href="#" class="go-to-top"><i class="fa fa-angle-up"></i>'.esc_html__('Top', 'viem').'</a>';
		}
		return '';
	}
}

if( !function_exists('viem_popup_container') ){
	function viem_popup_container(){
		?>
		<div id="viem-popup-container">
			<div class="notification-action-wrap">
				<div class="notification-action-renderer"></div>
			</div>
		</div>
		<?php
	}
}

if(!function_exists('viem_wp_foot')){
	function viem_wp_foot(){
		// write out custom code
		$custom_code = viem_get_theme_option('custom_code','');
		echo balancetags($custom_code);
	}
}

if( !function_exists('viem_dt_custom_css') ){
	function viem_dt_custom_css($main_css_id){
		ob_start();
		require_once (get_template_directory() . '/includes/custom-css/custom-css.php');
		$custom_css = ob_get_clean();
		$custom_css = trim($custom_css);
		$custom_css = viem_dt_css_minify($custom_css);
		if( !empty($custom_css) )
			wp_add_inline_style( $main_css_id.'-wp', viem_dt_css_minify($custom_css) );
		if( $custom_css = viem_get_theme_option('custom-css', '') )
			wp_add_inline_style( $main_css_id.'-wp', viem_dt_css_minify($custom_css) );
		return;
	}
}

if( !function_exists('viem_video_playlists') ){
	function viem_video_playlists($atts = array('video_service'=>'youtube','youtube_videos'=>'','vimeo_videos'=>'','columns'=>'2','auto_next'=>'yes','auto_play'=>'off','el_class'=>''), $echo = true){
		extract($atts);
		ob_start();
		global $wp_embed;
		$el_class = !empty( $el_class) ? $el_class : '';
		$video_list = array();
		$video_data = array();
		$first_video = '';
		$service_url = '';
		if('youtube' === $video_service):
			$video_list = $youtube_videos;
			list($video_list,$video_data) = viem_get_video_data($youtube_videos,'youtube');
			if(!empty($video_data)){
				$first_video = current($video_data);
				$first_video_url = "www.youtube.com/watch?v={$first_video['id']}&ap";
				
				add_filter('oembed_fetch_url', 'viem_oembed_fetch_url_youtube');
				$first_video_iframe = str_replace('?feature=oembed', '?feature=oembed&enablejsapi=1',apply_filters('dawnthemes_embed_video', $first_video_url));
			};
		elseif ('vimeo'===$video_service):
			$video_list = $vimeo_videos;
			wp_enqueue_script('froogaloop2',get_template_directory_uri().'/assets/lib/froogaloop2.min.js',array(),null,true);
			list($video_list,$video_data) = viem_get_video_data($vimeo_videos,'vimeo');
			if(!empty($video_data)){
				$first_video = current($video_data);
				$first_video_url = "https://vimeo.com/{$first_video['id']}?api=1222";
				add_filter('oembed_fetch_url', 'viem_oembed_fetch_url_vimeo');
				$first_video_iframe = apply_filters('dawnthemes_embed_video', $first_video_url);
			}
		endif;
		$play_class = ' current-paused';
		if('on'===$auto_play)
			$play_class=' current-playing';
		
		if(!empty($first_video)):
		?>
		<div data-service="<?php echo esc_attr($video_service)?>" data-auto-next="<?php echo ('yes'===$auto_next ? 1 : 0)?>" data-auto-play="<?php echo ('on'===$auto_play ? 1 : 0)?>" data-video-list="<?php echo esc_attr(json_encode($video_list))?>" data-current-video=<?php echo esc_attr($first_video['id'])?> data-uid="<?php echo esc_attr(uniqid('video_playlist_')) ?>" class="video-playlists video-playlist-columns-<?php echo esc_attr($columns)?> <?php echo esc_attr($el_class)?>">
			<div class="video-playlists-wrap clearfix">
				<div class="dtfitvids video-playlists-player">
					<?php echo ($first_video_iframe); ?>
				</div>
				<div class="video-playlists-content">
					<div class="video-playlists-control <?php echo ($play_class)?>">
						<div class="video-playlists-control-icon"><a href="#"><i class="fa fa-play"></i></a></div>
						<div class="video-playlists-control-info">
							<div class="video-playlists-control-title"><?php echo esc_html($first_video['title'])?></div>
							<div class="video-playlists-control-time"><?php echo esc_html($first_video['duration'])?></div>
						</div>
					</div>
					<div class="video-playlists-list">
						<div class="video-playlists-list-scroller">
							<?php $loop=1;?>
							<?php foreach ((array)$video_data as $video):?>
							<a class="video-playlists-item<?php echo (1===$loop ? ' active':'')?><?php echo ($play_class)?> clearfix" href="#" data-video-id="<?php echo esc_attr($video['id'])?>">
								<span class="video-playlists-item-thumb">
									<img src="<?php echo esc_url($video['thumb'])?>">
								</span>
								<span class="video-playlists-item-info">
									<span class="video-playlists-item-title">
										<?php echo esc_html($video['title'])?>
									</span>
									<span class="video-playlists-item-icon">
										<time class="video-playlists-item-time"><?php echo esc_html($video['duration'])?></time>
									</span>
								</span>
							</a>
							<?php $loop++;?>
							<?php endforeach;?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php 
		endif;
		if( $echo ){
			echo ob_get_clean();
		}else{
			return ob_get_clean();
		}
	}
}

if( !function_exists('viem_GetWtiLikePost') ){
	/*
	 * Function to display number of post likes using WTI Like Post plugin
	 */
	function viem_GetWtiLikePost($post_id = ''){
		if($post_id == ''){
			$post_id  = get_the_ID();
		}
		
		if(function_exists('GetWtiLikePost')){
			
			$like = GetWtiLikeCount($post_id);
			$like = str_replace('+','',$like);
			$like = ( defined('DAWNTHEMES_PREVIEW') ) ? $like + 1624 : $like;
			
			$like = viem_get_formatted_string_number($like, 3);
			
			update_post_meta($post_id,'_dt_get_likes',$like);
			
			return $like;
		}else{
			update_post_meta($post_id,'_dt_get_likes',0);
			return 0;
		}
	}
}

if( !function_exists('viem_video_like_count') ){
	function viem_video_like_count( $post_id = ''){
		if( viem_get_theme_option('single_video_show_like', '1') != '1')
			return;

		if( empty( $post_id ) )
			$post_id = get_the_ID();

		if( ( $like = viem_GetWtiLikePost($post_id) ) > 0 ):?>
		<span class="post-count-likes"><i class="fa fa-thumbs-o-up"></i><?php echo ($like); ?> <span><?php esc_html_e( 'Likes', 'viem' ); ?></span></span>
		<?php endif;
	}
}

if( !function_exists('viem_video_like_counter') ){
	function viem_video_like_counter( $post_id = ''){
		if( viem_get_theme_option('single_video_show_like', '1') != '1')
			return;

		if( empty( $post_id ) )
			$post_id = get_the_ID();

		if( ( $like = viem_GetWtiLikePost($post_id) ) > 0 ):?>
		<span class="video-like-counter"><i class="fa fa-thumbs-up"></i><?php echo esc_html( $like ); ?></span>
		<?php endif;
	}
}

if( !function_exists('viem_video_views_count') ){
	function viem_video_views_count( $post_id = ''){
		if( viem_get_theme_option('single_video_views_count', '1') != '1')
			return;

		if( empty( $post_id ) )
			$post_id = get_the_ID();
		?>
		<span class="post-views"><i class="fa fa-eye"></i><?php viem_get_post_views($post_id, true);?></span>
		<?php
	}
}

if( !function_exists('viem_video_comments_count') ){
	function viem_video_comments_count( $post_id = ''){
		if( viem_get_theme_option('single_video_comments_count', '1') != '1')
			return;

		if( empty( $post_id ) )
			$post_id = get_the_ID();

		if ( comments_open() || get_comments_number() ) :
		?>
		<span class="comments-number"><i class="fa fa-comment"></i><?php echo get_comments_number( $post_id ); ?></span>
		<?php
		endif;
	}
}

if( !function_exists('viem_playlist_videos') ){
	function viem_playlist_videos( $list_id = '', $current_id = ''){
		if( empty($list_id) ) return '';
		
		$args = array(
			'post_type' => 'viem_video',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => 'date',
			'post__not_in' => array($list_id),
			'meta_query' => array(
				array(
					'key' => '_dt_video_playlist_id',
					'value' => $list_id,
					'compare' => 'LIKE',
				),
			)
		);
		
		// Get watch later videos list
		if( $list_id === 'wl' ){
			$wl_ids = viem_ajax_video_watch_later::user_watch_later_list();
			krsort($wl_ids);
			$args = array(
				'post_type' => 'viem_video',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby' => 'post__in',
				'order'      => 'DESC',
				'post__in' => $wl_ids,
			);
		}
		
		$v_query = new WP_Query($args);
		if( $v_query->have_posts() ){
			$post_count = $v_query->post_count;
		?>
		<div class="viem-playlist-panel">
			<div class="viem-playlist-panel-header">
				<h3 class="playlist-title">
				<?php 
				if( $list_id === 'wl' ){
					$wl_query = new WP_Query( array('post_type'  => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/page-watch-later.php' ) );
                     if ( $wl_query->have_posts() ) while ( $wl_query->have_posts() ) : $wl_query->the_post();?>
                            	<a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a>
                    <?php endwhile; 
					wp_reset_postdata();
				}else{
					echo get_the_title($list_id);
				}?>
				</h3>
				<div class="playlist-count-videos"><?php echo ( $post_count == 1 ) ? sprintf( esc_html__('%s Video', 'viem'), $post_count ) : sprintf( esc_html__('%s Videos', 'viem'), $post_count );?></div>
			</div>
			<div class="viem-playlist-items">
				<?php
				while ($v_query->have_posts()){ $v_query->the_post();
					$post_id = get_the_ID();
					$thumbImg = '';
					?>
					<div class="viem-playlist-item-video <?php echo ( $post_id == $current_id ) ? 'active' : '';?>">			
						<?php
						$thumbnail_img = '';
						if( has_post_thumbnail($post_id) ){
							$thumbnail_img = get_the_post_thumbnail( $post_id, 'medium' );
						}else{
							if( class_exists('viem_posttype_video') ){
								$thumbnail_url = viem_posttype_video::get_thumbnail_auto();
								$thumbnail_img = '<img src="'.$thumbnail_url.'"/>';
							}
						}
							?>
						<div class="item-video-thumb">
							<a href="<?php echo esc_url( add_query_arg(array('list' => $list_id), get_the_permalink()) );?>">
								<?php echo viem_print_string($thumbnail_img); ?>
								<span class="video-duration"><?php echo viem_get_post_meta('video_duration');?></span>
							</a>
						</div>
							
						<div class="item-video-content">
							<h5 class="item-video-title"><a href="<?php echo esc_url( add_query_arg(array('list' => $list_id), get_the_permalink()) );?>"><?php the_title();?></a></h5>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<div class="bg-overlay"></div>
		</div>
			<?php
		}
		wp_reset_postdata();
	}
}

if( !function_exists('viem_playlist_player_wrapper') ){
	function viem_playlist_player_wrapper( $list_id = '' ){
		if( empty($list_id) ) return '';
		$post_id = get_the_ID();
		?>
		<div class="page-heading">
			<div class="container">
				<div id="viem-playlist-wrapper">
					<div class="dawnthemes_row">
						<div class="viem-col-left">
							<div class="viem-video-player-wrapper">
								<div id="v-container">
									<?php 
									viem_video_featured();
									?>
								</div>
								
							</div>
							<div id="viem_background_lamp"></div>
							<div class="viem-video-player-header">
								<header class="entry-header">
									<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
									
									<div class="viem-video-owner">
										<?php
										$post_author_id = get_post_field( 'post_author', $post_id );
										if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture', $post_author_id ) )):
											?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>">
												<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
											</a>
											<?php
										else:
											$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 100 );
											$avatar = get_the_author_meta('viem_user_avatar_default_bg', $post_author_id);
											?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>">
												<?php echo get_avatar( get_the_author_meta( 'user_email', $post_author_id ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
											</a>
											<?php
										endif;
										?>
										<div class="video-owner-info-renderer">
											<div class="video-owner-title video-owner-title">
												<h5 class="author-title viem_main_color">
													<a href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>"><?php echo get_the_author_meta('nickname', $post_author_id); ?></a>
												</h5>
											</div>
											<div class="video-owner-number">
												<span><?php echo count_user_posts( $post_author_id, 'viem_video') . ' ' . esc_html__('Videos', 'viem'); ?></span>
											</div>
										</div>
										<div class="video-owner-entry-media">
											<?php viem_video_like_count($post_id); ?>
											<?php viem_video_views_count($post_id); ?>
										</div>
									</div>
								</header><!-- .entry-header -->
								<?php 
								if( function_exists('viem_video_features') ){
									viem_video_features('viem-dark');
								}
								?>
							</div>
						</div>
						<div class="viem-col-right">
							<?php viem_playlist_videos($list_id, $post_id); ?>
						</div>
						
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if( !function_exists('viem_playlist_series') ){
	function viem_playlist_series( $series_id = '', $current_id = ''){
		if( empty($series_id) ) return '';

		$args = array(
			'post_type' => 'viem_video',
			'posts_per_page' => -1,
			'post_status' => 'publish',
			'ignore_sticky_posts' => 1,
			'orderby' => 'meta_value_num',
			'meta_key' => '_dt_order_in_series',
			'order' => 'ASC',
			'post__not_in' => array($series_id),
			'meta_query' => array(
				array(
					'key' => '_dt_video_series_id',
					'value' => $series_id,
					'compare' => 'LIKE',
				),
			)
		);
		
		$v_query = new WP_Query($args);
		if( $v_query->have_posts() ){
			$post_count = $v_query->post_count;
			?>
		<div class="viem-series-panel">
			<div class="viem-series-content">
				<div class="viem-series-header">
					<div><?php echo esc_html__('Episodes:', 'viem'); ?></div>
				</div>
				<div class="viem-series-items">
					<?php
					
					while ($v_query->have_posts()){ $v_query->the_post();
						$post_id = get_the_ID();
						$active = ( $post_id == $current_id ) ? 'active' : '';
						?>
						<div class="viem-playlist-item-video <?php echo esc_attr($active);?>">			
							<div class="item-video-content">
								<a href="<?php echo esc_url( add_query_arg(array('series' => $series_id), get_the_permalink()) );?>"><i class="fa fa-play-circle"></i><?php echo sprintf('%02d', viem_get_post_meta('order_in_series', $post_id, '01')); ?></a>
							</div>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
			<?php
		}
		wp_reset_postdata();
	}
}
if( !function_exists('viem_series_player_wrapper') ){
	function viem_series_player_wrapper( $series_id = '' ){
		if( empty($series_id) ) return '';
		$post_id = get_the_ID();
		?>
		<div class="page-heading">
			<div class="container">
				<div id="viem-playlist-wrapper" class="viem-series-wrapper">
							<div class="viem-video-player-wrapper">
								<div id="v-container">
									<?php 
									viem_video_featured();
									?>
								</div>
							</div>
							<div id="viem_background_lamp"></div>
							<div class="viem-video-player-header">
								<header class="entry-header">
									<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
									
									<div class="viem-video-owner">
										<?php
										$post_author_id = get_post_field( 'post_author', $series_id );
										if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture', $post_author_id ) )):
											?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>">
												<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
											</a>
											<?php
										else:
											$author_avatar_size = apply_filters( 'viem_authorbio_avatar_size', 100 );
											$avatar = get_the_author_meta('viem_user_avatar_default_bg', $post_author_id);
											?>
											<a class="video-owner-avatar" href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>">
												<?php echo get_avatar( get_the_author_meta( 'user_email', $post_author_id ), $author_avatar_size, apply_filters('viem_get_user_avatar_default', $avatar)); ?>
											</a>
											<?php
										endif;
										?>
										<div class="video-owner-info-renderer">
											<div class="video-owner-title">
												<h5 class="author-title video-owner-title viem_main_color">
													<a href="<?php echo esc_url( get_author_posts_url( $post_author_id ) );?>"><?php echo get_the_author_meta('nickname', $post_author_id); ?></a>
												</h5>
											</div>
											<div class="video-owner-number">
												<span><?php echo count_user_posts( $post_author_id, 'viem_video') . ' ' . esc_html__('Videos', 'viem'); ?></span>
											</div>
										</div>
										<div class="video-owner-entry-media">
											<?php viem_video_like_count($post_id); ?>
											<?php viem_video_views_count($post_id); ?>
										</div>
									</div>
								</header><!-- .entry-header -->
								<?php 
								if( function_exists('viem_video_features') ){
									viem_video_features('viem-dark');
								}
								?>
							</div>
						<?php viem_playlist_series($series_id, $post_id); ?>
				</div>
			</div>
		</div>
		<?php
	}
}

if( !function_exists('viem_video_multilink') ){
	function viem_video_multilink( $video_id = '' ){
		$video_id = ( !empty($video_id) ) ? $video_id : get_the_ID();
		$links = viem_get_post_meta('multi_link');
		if( !empty($links) && count($links) > 0 ):
			$i = 0;
			$current_linkID = ( isset($_GET['v_i']) && !empty($_GET['v_i'])  ) ? $_GET['v_i'] : '';
			
			?>
			<div class="viem-multilink-panel viem-series-panel">
				<?php 
				foreach ($links as $link){
					if( isset($link['title']) &&  !empty($link['title'])){ $i++;
				?>
						<div class="viem-series-content">
							<div class="viem-series-header">
								<div><?php echo sprintf('%s:', esc_html($link['title'])); ?></div>
							</div>
							<div class="viem-series-items">
								<?php
								$j = 0;
								foreach ($link['video_data'] as $key => $video ){ $j++;
									$video_type = $video['video_type'];
									$video_id = $video['video_id'];
									if( function_exists('dawnthemes_crypt') )
										$video_id = dawnthemes_crypt($video_id, 'e');
									
									$active = ( $current_linkID == $video_id ) ? 'active' : '';
									
									if( !empty($video_id) ){
									?>
									<div class="viem-playlist-item-video <?php echo esc_attr($active);?>">			
										<div class="item-video-content">
											<a href="<?php echo esc_url( add_query_arg(array('v_t' => $video_type, 'v_i' => $video_id), get_the_permalink()) );?>"><i class="fa fa-play-circle"></i><?php echo sprintf("%02d", $j); ?></a>
										</div>
									</div>
									<?php
									}
								}
								?>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
			<?php
		endif;
	}
}

if( !function_exists('viem_video_player_wrapper') ){
	function viem_video_player_wrapper($videoPlayerTheme = ''){
		?>
		<div class="viem-video-player-wrapper">
			<div id="v-container">
				<?php 
				viem_video_featured();
				?>
			</div>
			<?php 
			if( function_exists('viem_video_features') ){
				viem_video_features($videoPlayerTheme);
			}
			
			if( function_exists('viem_video_multilink') ){
				viem_video_multilink();
			}
			?>
		</div>
		<div id="viem_background_lamp"></div>
		<?php
	}
}

if( !function_exists('viem_video_features') ){
	function viem_video_features( $extra_class = '' ){
		$post_id = get_the_ID();
		?>
		<div class="viem-video-features <?php echo esc_attr($extra_class); ?>">
			<div class="toolbar-wraper">
				<div class="toolbar-left">
					<?php if( viem_get_theme_option('single_video_show_light', '1') == '1'): ?>
						<div class="toolbar-block-item">
							 <a id="viem_btn_lightbulb" href="#" title="<?php esc_attr_e('Light','viem');?>">
							 	<i class="fa fa-lightbulb-o"></i>
							 	<span class="viem-ft-desc"><?php esc_html_e('Light','viem');?></span>
							 </a>
					     </div>
				 	<?php endif; ?>
					<?php
					if( viem_get_theme_option('single_video_show_watchlater', '1') == '1'):
						$WatchLater_title = esc_html__('Watch later','viem');
						if( ! is_user_logged_in() ) : ?>
						<div class="toolbar-block-item">
								<a class="viem-watch-later-modal" href="#" data-toggle="modal" data-target="#viem-watch-later-Modal" title="<?php echo esc_attr($WatchLater_title)?>">
						            <i class="fa fa-clock-o"></i>
						            <span class="viem-ft-desc"><?php echo esc_html($WatchLater_title);?></span>
						        </a>
						</div>
						<div class="viem-watch-later viem-modal modal fade" id="viem-watch-later-Modal" role="dialog">
						    <div class="modal-dialog modal-sm">
						      <div class="modal-content">
							        <div class="modal-body">
							          <p><strong><?php esc_html_e('Want to watch this again later?','viem');?></strong></p>
							          <p><?php esc_html_e('Sign in to add this video to a playlist.','viem');?></p>
							        </div>
							        <div class="modal-footer">
							         	 <a href="<?php echo esc_url( wp_login_url(get_permalink()) ); ?>"><?php esc_html_e('SIGN IN','viem');?></a>
							        </div>
						      </div>
						    </div>
						 </div>
						 
						 <?php else: 
						 		$video_id = get_the_ID();
							 	$user_id = get_current_user_id();
							 	
							 	$WatchLater_dataAction = 'add';
							 	
						 		$WatchLater_added = viem_ajax_video_watch_later::check_video_in_watch_later($video_id, $user_id);
						 		
						 		if( $WatchLater_added ){
						 			$WatchLater_dataAction = 'remove';
						 		}
						 		
						 ?>
						 <div class="toolbar-block-item">
							 <a class="viem-watch-later <?php echo ($WatchLater_added) ? 'addedToWatchLater' : ''; ?>" href="#" title="<?php echo esc_attr($WatchLater_title);?>" data-video="<?php echo esc_attr($video_id); ?>" data-action="<?php echo esc_attr($WatchLater_dataAction);?>">
					            <i class="fa fa-clock-o"></i>
					            <span class="viem-ft-desc"><?php echo esc_html($WatchLater_title);?></span>
					         </a>
					     </div>
						<?php endif; ?>
					<?php endif; ?>
					
					<?php
					if( viem_get_theme_option('single_video_show_like', '1') == '1'):
						if( function_exists('GetWtiLikePost') ):
							$like = GetWtiLikeCount($post_id);
							$unlike = GetWtiUnlikeCount($post_id);
							$like = $re_like = str_replace('+','',$like);
							$unlike = $re_unlike = str_replace('-','',$unlike);
							
							$Wti_title_text = get_option('wti_like_post_title_text');
							if (empty($Wti_title_text)) {
								$Wti_title_text_like = esc_html__('Like', 'viem');
								$Wti_title_text_unlike = esc_html__('Unlike', 'viem');
							} else {
								$Wti_title_text = explode('/', get_option('wti_like_post_title_text'));
								$Wti_title_text_like = $Wti_title_text[0];
								$Wti_title_text_unlike = isset( $Wti_title_text[1] ) ? $Wti_title_text[1] : '';
							}
						?>
							<div class="toolbar-block-item viem-like-post viem-like-post-<?php the_ID();?>" data-like="<?php esc_html_e('I like this','viem');?>" data-unlike="<?php esc_html_e('I dislike this','viem');?>">
								<?php GetWtiLikePost(); ?>
							</div>
							<script>
								/*like*/
								var $likeNumber_<?php the_ID();?> = document.createElement('span');
								$likeNumber_<?php the_ID();?>.className = 'viem-lc';
								var $likeNumberText_<?php the_ID();?> = document.createTextNode('<?php echo  viem_get_formatted_string_number($like);?>');
								$likeNumber_<?php the_ID();?>.appendChild($likeNumberText_<?php the_ID();?>);
								var $likeDiv_<?php the_ID();?> = document.querySelector('.viem-like-post-<?php the_ID();?> .lbg-style1');
								$likeDiv_<?php the_ID();?>.appendChild($likeNumber_<?php the_ID();?>);
								/*desc*/
								var $likeDesc_<?php the_ID();?> = document.createElement('span');
								$likeDesc_<?php the_ID();?>.className = 'viem-ft-desc';
								var $likeDesc_Text_<?php the_ID();?> = document.createTextNode('<?php echo  esc_html($Wti_title_text_like);?>');
								$likeDesc_<?php the_ID();?>.appendChild($likeDesc_Text_<?php the_ID();?>);
								$likeDiv_<?php the_ID();?>.appendChild($likeDesc_<?php the_ID();?>);
								
								/*unlike*/
								var $unlikeNnumber_<?php the_ID();?> = document.createElement('span');
								$unlikeNnumber_<?php the_ID();?>.className = 'viem-unlc';
								var $unlikeNumberText_<?php the_ID();?> = document.createTextNode('<?php echo  viem_get_formatted_string_number($unlike);?>');
								$unlikeNnumber_<?php the_ID();?>.appendChild($unlikeNumberText_<?php the_ID();?>);
								var $unLikeDiv_<?php the_ID();?> = document.querySelector('.viem-like-post-<?php the_ID();?> .unlbg-style1');
								$unLikeDiv_<?php the_ID();?>.appendChild($unlikeNnumber_<?php the_ID();?>);
								/*desc*/
								var $unLikeDesc_<?php the_ID();?> = document.createElement('span');
								$unLikeDesc_<?php the_ID();?>.className = 'viem-ft-desc';
								var $unLikeDesc_Text_<?php the_ID();?> = document.createTextNode('<?php echo  esc_html($Wti_title_text_unlike);?>');
								$unLikeDesc_<?php the_ID();?>.appendChild($unLikeDesc_Text_<?php the_ID();?>);
								$unLikeDiv_<?php the_ID();?>.appendChild($unLikeDesc_<?php the_ID();?>);
							</script>
						<?php 
						endif;
						?>
					<?php endif; ?>
					<?php if( viem_get_theme_option('viem_video_show_share', '1') == 1 ): ?>
					<div class="toolbar-block-item">
						<a class="toggle_social" href="javascript:;" title="<?php esc_attr_e('Social share','viem');?>">
				            <div class="action-share">
				            	<span class="close"></span>
				            	<i class="fa fa-share-alt"></i>
				            </div>
				            <span class="viem-ft-desc"><?php esc_html_e('Share','viem');?></span>
				        </a>
			        </div>
			        <?php endif; ?>
				</div>
				<div class="toolbar-right">
					<?php if( !isset($_GET['list']) && empty($_GET['list']) && !isset($_GET['series']) && empty($_GET['series']) ) :?>
					<div class="toolbar-block-item hidden">
						<div class="viem-video-nav">
							<?php viem_video_nav(); ?>
						</div>
					</div>
					<?php endif; ?>
					<?php if( viem_get_theme_option('video_on_finish', 1) == 1 ): ?>
						<div class="toolbar-block-item">
							<div class="viem-auto-next-op viem_main_color active ?>">
								<span><?php esc_html_e('Auto Next', 'viem');?></span>
								<div class="auto-next-btn viem-main-color-bg">
									<div class="oval-button"></div>
								</div>
							</div>
				        </div>
			        <?php endif; ?>
			        <?php if( viem_get_theme_option('viem_video_show_more_video', 1) == 1 ): ?>
						<div class="toolbar-block-item">
							<a class="show-more-videos" href="#" title="<?php esc_attr_e('More Videos','viem');?>">
					            <span><?php esc_html_e('More Videos','viem');?></span><i class="fa fa-caret-down"></i>
					        </a>
				        </div>
			    	<?php endif; ?>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php if( viem_get_theme_option('viem_video_show_share', '1') == 1 ){
				do_action('dawnthemes_print_social_share');
			}?>
			<?php 
			if( viem_get_theme_option('viem_video_show_more_video', 1) == 1 && function_exists('viem_more_videos_slider') ){
				viem_more_videos_slider();
			}
			?>
		</div>
		<?php
	}
}

if( !function_exists('viem_more_videos_slider') ){
	function viem_more_videos_slider(){
		?>
		<div class="viem-more-videos-slider">
			<?php get_template_part( 'template-parts/single-video/single', 'related-slider' ); ?>
		</div>
		<?php
	}
}

if( !function_exists('viem_video_featured') ){
	function viem_video_featured( $youtubePlaylistID = '', $youtubeChannelID = '' ){
		$post_id  = get_the_ID();
		$post_type = get_post_type($post_id);
		// Use YouTube Channel ID for the player
		if( !empty($youtubeChannelID) ){
			$post_type = 'youtube_channel';
		}
		
		// Video default
		$videos = array(
				0 => array(
					'videoType' => 'youtube', //choose video type: "HTML5", "youtube", "vimeo", "image"
					'title' => '', //video title
					'youtubeID' => '0dJO0HyE8xE',  //last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE
					'vimeoID' => '119641053', //last part of the URL vimeo.com/119641053
					'mp4'	=> 'dawnthemes.com/player/videos/Logo_Explode.mp4',		//HTML5 video mp4 url
					'enable_mp4_download' => 'no', //enable download button for self hosted videos: "yes","no"
					'imageUrl'	=> '', //display image instead of playing video
					'imageTimer' => 4, //set time how long image will display
					'prerollAD' => 'no', //show pre-roll "yes","no"
					'prerollGotoLink' => '#', //pre-roll goto link
					'preroll_mp4' => 'dawnthemes.com/player/videos/Logo_Explode.mp4', //pre-roll video mp4 format
					'prerollSkipTimer' => 5,
					'midrollAD' => 'no', //show mid-roll "yes","no"
					'midrollAD_displayTime' => '00:10', //show mid-roll at any custom time in format "minutes:seconds" ("00:00")
					'midrollGotoLink' => '#', //mid-roll goto link
					'midroll_mp4' => 'dawnthemes.com/player/videos/Logo_Explode.mp4', //mid-roll video mp4 format
					'midrollSkipTimer' => 5,
					'postrollAD' => 'no', //show post-roll "yes","no"
					'postrollGotoLink' => '#', //post-roll goto link
					'postroll_mp4' => 'dawnthemes.com/player/videos/Logo_Explode.mp4', //post-roll video mp4 format
					'postrollSkipTimer' => 5,
					'popupAdShow' => 'no', //enable/disable popup image: "yes","no"
					'popupImg' => '', //popup image URL
					'popupAdStartTime' => '00:03', //time to show popup ad during playback
					'popupAdEndTime' => '00:07', //time to hide popup ad during playback
					'popupAdGoToLink' => '#', //re-direct to URL when popup ad clicked
					'description' => '', //video description
					'thumbImg' => '', //set "auto" or leave blank "" to grab it automatically from youtube, or set path to playlist thumbnail image
					'info' => '', //video info
				)
		);
		
		$video_id = uniqid('Player_post-'.$post_id.'-dt_v');
		$playlist = 'Off';
		$youtubePlaylistID = '';
		
		$sticky_player = viem_get_theme_option('sticky_player', 1);
		$video_autoplay = viem_get_theme_option('video_autoplay', 1);
		$video_autoNext = viem_get_theme_option('video_on_finish', 1) == 1 ? 'Play next video' : 'Stop video';
		$video_ratio = viem_get_theme_option('video_ratio', '169');

		switch ($video_ratio){
			case '43':
				$videoRatio = 4/3;
				break;
			case '32':
				$videoRatio = 3/2;
				break;
			case '219':
				$videoRatio = 21/9;
				break;
			default:
				$videoRatio = 16/9;
				break;
		}
		
		$posterImg = $imageUrl = $thumbImg = $popupImg = '';
		
		if( has_post_thumbnail($post_id) ){
			$posterImg = $imageUrl = get_the_post_thumbnail_url($post_id, 'full');
			$thumbImg = get_the_post_thumbnail_url($post_id, 'viem-115x75-2x');
		}
		
		
		switch ($post_type){
			case 'viem_video':
				
				$popupImg_id = viem_get_post_meta('popup_img', $post_id, '');
				if( !empty($popupImg_id)  )
					$popupImg = wp_get_attachment_image_url( $popupImg_id, 'full');
				
				// Support video multilink
				$videoType = ( isset($_GET['v_t']) && !empty($_GET['v_t']) ) ? $_GET['v_t'] : viem_get_post_meta('video_type', $post_id, 'youtube');
				$videoID = ( isset($_GET['v_i']) && !empty($_GET['v_i']) ) ? $_GET['v_i'] : '';
				if( function_exists('dawnthemes_crypt') ){
					$videoID = dawnthemes_crypt($videoID, 'd');
				}
				
				$videos = array(
						0 => array(
							'videoType' => $videoType, //choose video type: "HTML5", "youtube", "vimeo", "image"
							'title' => '', //video title
							'youtubeID' => ($videoID) ? $videoID : esc_html( viem_get_post_meta('youtube_id', $post_id, '') ),  //last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE
							'vimeoID' => ($videoID) ? $videoID : esc_html( viem_get_post_meta('vimeo_id', $post_id, '') ), //last part of the URL vimeo.com/119641053
							'mp4'	=> ($videoID) ? $videoID : esc_url( viem_get_post_meta('video_mp4', $post_id, '') ),		//HTML5 video mp4 url
							'enable_mp4_download' => viem_get_post_meta('enable_mp4_download', $post_id, 'no'), //enable download button for self hosted videos: "yes","no"
							'imageUrl'	=> esc_url( $imageUrl ), //display image instead of playing video
							'imageTimer' => 4, //set time how long image will display
							'prerollAD' => esc_html( viem_get_post_meta('preroll_ad', $post_id, 'no') ), //show pre-roll "yes","no"
							'prerollGotoLink' => esc_url( viem_get_post_meta('preroll_goto_link', $post_id, '#') ), //pre-roll goto link
							'preroll_mp4' => esc_url( viem_get_post_meta('preroll_mp4', $post_id, '') ), //pre-roll video mp4 format
							'prerollSkipTimer' => esc_html( viem_get_post_meta('preroll_skip_timer', $post_id, '5') ),
							'midrollAD' => esc_html( viem_get_post_meta('midroll_ad', $post_id, 'no') ), //show mid-roll "yes","no"
							'midrollAD_displayTime' => esc_html( viem_get_post_meta('midrollad_display_Time', $post_id, '00:10') ), //show mid-roll at any custom time in format "minutes:seconds" ("00:00")
							'midrollGotoLink' => esc_url( viem_get_post_meta('midroll_goto_link', $post_id, '#') ), //mid-roll goto link
							'midroll_mp4' => esc_url( viem_get_post_meta('midroll_mp4', $post_id, '') ), //mid-roll video mp4 format
							'midrollSkipTimer' => esc_html( viem_get_post_meta('midroll_skip_timer', $post_id, '5') ),
							'postrollAD' => esc_html( viem_get_post_meta('postroll_ad', $post_id, 'no') ), //show post-roll "yes","no"
							'postrollGotoLink' => esc_url( viem_get_post_meta('postroll_goto_link', $post_id, '#') ), //post-roll goto link
							'postroll_mp4' => esc_url( viem_get_post_meta('postroll_mp4', $post_id, '') ), //post-roll video mp4 format
							'postrollSkipTimer' => esc_html( viem_get_post_meta('postroll_skip_timer', $post_id, '5') ),
							'popupAdShow' => esc_html( viem_get_post_meta('popup_ad_show', $post_id, 'no') ), //enable/disable popup image: "yes","no"
							'popupImg' => esc_url( $popupImg ), //popup image URL
							'popupAdStartTime' => esc_html( viem_get_post_meta('popup_ad_start_time', $post_id, '00:03') ), //time to show popup ad during playback
							'popupAdEndTime' => esc_html( viem_get_post_meta('popup_ad_end_time', $post_id, '00:07') ), //time to hide popup ad during playback
							'popupAdGoToLink' => esc_url( viem_get_post_meta('popup_ad_goto_link', $post_id, '#') ), //re-direct to URL when popup ad clicked
							'description' => '', //video description
							'thumbImg' => esc_url( $thumbImg ), //set "auto" or leave blank "" to grab it automatically from youtube, or set path to playlist thumbnail image
							'info' => '', //video info
						)
				);
				break;
			case 'viem_playlist':
				$playlist = viem_get_theme_option('video_playlist_type', 'Right playlist');
				$video_playlist_type_add = viem_get_post_meta('video_playlist_type_add', $post_id, 'manually');
				if( $video_playlist_type_add == 'youtube_playlist' ){
					$youtubePlaylistID = esc_attr(viem_get_post_meta('youtube_playlist_id', $post_id, 'PL_HbKbJsShUhl9s6GyBPRvZ6glDBpq6k4'));
				}else{
					
					$args = array(
						'post_type' => 'viem_video',
						'posts_per_page' => -1,
						'post_status' => 'publish',
						'ignore_sticky_posts' => 1,
						'orderby' => 'date',
						'post__not_in' => array($post_id),
						'meta_query' => array(
							array(
								'key' => '_dt_video_playlist_id',
								'value' => $post_id,
								'compare' => 'LIKE',
							),
						)
					);
					$v_query = new WP_Query($args); 
					if( $v_query->have_posts() ){
						$videos = array();
						$i = 0;
						while ($v_query->have_posts()){ $v_query->the_post();
							$post_id = get_the_ID();
							$thumbImg = '';
							
							$videoType = viem_get_post_meta('video_type', $post_id, 'youtube');
							
							if( has_post_thumbnail($post_id) ){
								$thumbImg = get_the_post_thumbnail_url($post_id, 'viem-115x75');
							}
							
							$popupImg_id = viem_get_post_meta('popup_img', $post_id, '');
							if( !empty($popupImg_id)  )
								$popupImg = wp_get_attachment_image_url( $popupImg_id, 'full');
							
							
							
							$videos[$i] = array(
									'videoType' => $videoType, //choose video type: "HTML5", "youtube", "vimeo", "image"
									'title' => get_the_title($post_id), //video title
									'youtubeID' => esc_html( viem_get_post_meta('youtube_id', $post_id, '') ),  //last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE
									'vimeoID' => esc_html( viem_get_post_meta('vimeo_id', $post_id, '') ), //last part of the URL vimeo.com/119641053
									'mp4'	=> esc_url( viem_get_post_meta('video_mp4', $post_id, '') ),		//HTML5 video mp4 url
									'enable_mp4_download' => viem_get_post_meta('enable_mp4_download', $post_id, 'no'), //enable download button for self hosted videos: "yes","no"
									'imageUrl'	=> esc_url( $imageUrl ), //display image instead of playing video
									'imageTimer' => 4, //set time how long image will display
									'prerollAD' => esc_html( viem_get_post_meta('preroll_ad', $post_id, 'no') ), //show pre-roll "yes","no"
									'prerollGotoLink' => esc_url( viem_get_post_meta('preroll_goto_link', $post_id, '#') ), //pre-roll goto link
									'preroll_mp4' => esc_url( viem_get_post_meta('preroll_mp4', $post_id, '') ), //pre-roll video mp4 format
									'prerollSkipTimer' => esc_html( viem_get_post_meta('preroll_skip_timer', $post_id, '5') ),
									'midrollAD' => esc_html( viem_get_post_meta('midroll_ad', $post_id, 'no') ), //show mid-roll "yes","no"
									'midrollAD_displayTime' => esc_html( viem_get_post_meta('midrollad_display_Time', $post_id, '00:10') ), //show mid-roll at any custom time in format "minutes:seconds" ("00:00")
									'midrollGotoLink' => esc_url( viem_get_post_meta('midroll_goto_link', $post_id, '#') ), //mid-roll goto link
									'midroll_mp4' => esc_url( viem_get_post_meta('midroll_mp4', $post_id, '') ), //mid-roll video mp4 format
									'midrollSkipTimer' => esc_html( viem_get_post_meta('midroll_skip_timer', $post_id, '5') ),
									'postrollAD' => esc_html( viem_get_post_meta('postroll_ad', $post_id, 'no') ), //show post-roll "yes","no"
									'postrollGotoLink' => esc_url( viem_get_post_meta('postroll_goto_link', $post_id, '#') ), //post-roll goto link
									'postroll_mp4' => esc_url( viem_get_post_meta('postroll_mp4', $post_id, '') ), //post-roll video mp4 format
									'postrollSkipTimer' => esc_html( viem_get_post_meta('postroll_skip_timer', $post_id, '5') ),
									'popupAdShow' => esc_html( viem_get_post_meta('popup_ad_show', $post_id, 'no') ), //enable/disable popup image: "yes","no"
									'popupImg' => esc_url( $popupImg ), //popup image URL
									'popupAdStartTime' => esc_html( viem_get_post_meta('popup_ad_start_time', $post_id, '00:03') ), //time to show popup ad during playback
									'popupAdEndTime' => esc_html( viem_get_post_meta('popup_ad_end_time', $post_id, '00:07') ), //time to hide popup ad during playback
									'popupAdGoToLink' => esc_url( viem_get_post_meta('popup_ad_goto_link', $post_id, '#') ), //re-direct to URL when popup ad clicked
									'description' => '', //video description
									'thumbImg' => esc_url( $thumbImg ), //set "auto" or leave blank "" to grab it automatically from youtube, or set path to playlist thumbnail image
									'info' => '', //video info
							);
						
							$i++;
						}
						
					}
					wp_reset_postdata();
				}
				
				break;
			case 'youtube_channel':
				$playlist = 'Right playlist';
				break;
			default:
				return '';
				break;
		}
		
		$v_player =
		array(
			'id' => $video_id,
			'instanceName' => $video_id,					//name of the player instance
			'instanceTheme' => viem_get_theme_option('video_instance_theme', 'dark'), //choose video player theme: "dark", "light"
			'autohideControls' => 5, 						//autohide HTML5 player controls
			'hideControlsOnMouseOut' => 'No', 				//hide HTML5 player controls on mouse out of the player: "Yes","No"
			'playerLayout' => 'fitToContainer', 			//Select player layout: "fitToContainer" (responsive mode), "fixedSize" (fixed mode), "fitToBrowser" (fill the browser mode)
			'videoPlayerWidth' => 1140,				 		//fixed total player width (only for playerLayout: "fixedSize")
			'videoPlayerHeight' => 420, 					//fixed total player height (only for playerLayout: "fixedSize")
			'videoRatio' => $videoRatio,                    //set your video ratio (calculate video width/video height)
			'videoRatioStretch' => false,                   //adjust video ratio for case when playlist is "opened" : true/false
			'floatPlayerOutsideViewport' => $sticky_player == 1 ? true : false,  //show Sticky player if video player is not in viewport when scrolling through page
			'lightBox' => false,                             //lightbox mode :true/false
			'lightBoxAutoplay' =>  false,                    //autoplay video when lightbox opens: true/false
			'lightBoxThumbnail' => esc_url( $posterImg ), //lightbox thumbnail image
			'lightBoxThumbnailWidth' =>  400,                //lightbox thumbnail image width
			'lightBoxThumbnailHeight' =>  220,               //lightbox thumbnail image height
			'lightBoxCloseOnOutsideClick' =>  true,          //close lightbox when clicked outside of player area
			'playlist' => $playlist,                   		 //choose playlist type: "Right playlist", "Bottom playlist", "Off"
			'playlistScrollType' => "light",                 //choose scrollbar type: "light","minimal","light-2","light-3","light-thick","light-thin","inset","inset-2","inset-3","rounded","rounded-dots","3d","dark","minimal-dark","dark-2","dark-3","dark-thick","dark-thin","inset-dark","inset-2-dark","inset-3-dark","rounded-dark","rounded-dots-dark","3d-dark","3d-thick-dark"
			'playlistBehaviourOnPageload' => "opened (default)",//choose playlist behaviour when webpage loads: "closed", "opened (default)" (not apply to Vimeo player)
			'autoplay' => $video_autoplay == 1 ? true : false, //autoplay when webpage loads: true/false
			'colorAccent' => viem_get_theme_option('main_color', '#ecc200'), // '#ecc200', //'#cc181e', // Main Color
			'vimeoColor' => viem_get_theme_option('vimeo_player_color', '#00adef'), //set "hexadecimal value", default vimeo color is "00adef"
			'youtubeControls' => viem_get_theme_option('youtube_controls', 'default controls'),  //choose youtube player controls: "custom controls", "default controls"
			'youtubeSkin' => viem_get_theme_option('video_instance_theme', 'dark'),  //default youtube controls theme: light, dark
			'youtubeColor' => "red",                          //default youtube controls bar color: red, white
			'youtubeQuality' => viem_get_theme_option('video_youtube_quality', 'default'), //choose youtube quality: "small", "medium", "large", "hd720", "hd1080", "highres", "default"
			'youtubeShowRelatedVideos' => viem_get_theme_option('youtubeShowRelatedVideos', 'No'),  //choose to show youtube related videos when video finish: "Yes", "No" (onFinish:"Stop video" needs to be enabled)
			'videoPlayerShadow' => viem_get_theme_option('video_player_shadow', 'off'), //choose player shadow:  "effect1" , "effect2", "effect3", "effect4", "effect5", "effect6", "off"
			'loadRandomVideoOnStart' => "No",                 //choose to load random video when webpage loads: "Yes", "No"
			'shuffle' => "No",				                  //choose to shuffle videos when playing one after another: "Yes", "No" (shuffle button enabled/disabled on start)
			'posterImg' => esc_url( $posterImg ),//player poster image
			'posterImgOnVideoFinish' => esc_url( $posterImg ),//player poster image on video finish (if enabled onFinish:"Stop video")
			'onFinish' => $video_autoNext, //"Play next video", "Stop video",
			'nowPlayingText' => "Yes",                        //enable disable now playing title: "Yes","No"
			'fullscreen' => "Fullscreen native",              //choose fullscreen type: "Fullscreen native","Fullscreen browser"
			'preloadSelfHosted' => "none",                    //choose preload buffer for self hosted mp4 videos (video type HTML5): "none", "auto"
			'rightClickMenu' => true,                         //enable/disable right click over HTML5 player: true/false
			'hideVideoSource' => false,						 //option to hide self hosted video sources (to prevent users from download/steal your videos): true/false
			'showAllControls' => true,						 //enable/disable all HTML5 player controls: true/false
			'allowSkipAd' => viem_get_theme_option('allow_skip_ad', '1') == '1' ? true : false, //enable/disable "Skip advertisement" option: true/false
			'infoShow' => "No",                              //enable/disable info option: "Yes","No"
			'shareShow' => "No",                             //enable/disable all share options: "Yes","No"
			'facebookShow' => "No",                          //enable/disable facebook option individually: "Yes","No"
			'twitterShow' => "No",                           //enable/disable twitter option individually: "Yes","No"
			'mailShow' => "No",                              //enable/disable mail option individually: "Yes","No"
			'facebookShareName' => "Viem - Video WordPress Theme",      //first parametar of facebook share in facebook feed dialog is title
			'facebookShareLink' => "https://themeforest.net/user/dawnthemes",  //second parametar of facebook share in facebook feed dialog is link below title
			'facebookShareDescription' => "Viem - Video WordPress Theme.", //third parametar of facebook share in facebook feed dialog is description below link
			'facebookSharePicture' => 'https://s3.envato.com/files/235066018/DawnThemes_Banner.jpg', //fourth parametar in facebook feed dialog is picture on left side
			'twitterText' => "ustClick - Video WordPress Theme",			 //first parametar of twitter share in twitter feed dialog is text
			'twitterLink' => "https://themeforest.net/user/dawnthemes", //second parametar of twitter share in twitter feed dialog is link
			'twitterHashtags' => "Viem",		 //third parametar of twitter share in twitter feed dialog is hashtag
			'twitterVia' => "Video WordPress Theme",				 //fourth parametar of twitter share in twitter feed dialog is via (@)
			'googlePlus' => "https://themeforest.net/user/dawnthemes", //share link over Google +
			'logoShow' => "No",                              //"Yes","No"
			'logoClickable' => "Yes",                         //"Yes","No"
			'logoPath' => get_template_directory_uri()."/assets/images/logo.png",             //path to logo image
			'logoGoToLink' => 'codecanyon.net/',       //redirect to page when logo clicked
			'logoPosition' => "bottom-left",                  //choose logo position: "bottom-right","bottom-left"
			'embedShow' => "No",                             //enable/disable embed option: "Yes","No"
			'embedCodeSrc' => "www.yourwebsite.com/videoplayer/index.html", //path to your video player on server
			'embedCodeW' => "746",                            //embed player code width
			'embedCodeH' => "420",                            //embed player code height
			'embedShareLink' => "www.yourwebsite.com/videoplayer/index.html", //direct link to your site (or any other URL) you want to be "shared"
			'showGlobalPrerollAds' =>  false,                 //enable/disable 'global' ads and overwrite each individual ad in 'videos' :true/false
			'globalPrerollAds' =>  "url1;url2;url3;url4;url5",//set 'pool' of url's that are separated by ; (global prerolls will play randomly)
			'globalPrerollAdsSkipTimer' =>  5,                //skip global advertisement seconds
			'globalPrerollAdsGotoLink' =>  "codecanyon.net/",//global advertisement goto link
			'advertisementTitle' => esc_html( viem_get_theme_option('advertisement_title', 'Advertisement') ),          		//translate "Advertisement" title to your language
			'skipAdvertisementText' => esc_html( viem_get_theme_option('skip_advertisement_text', 'Skip advertisement') ),  			//translate "Skip advertisement" button to your language
			'skipAdText' => esc_html( viem_get_theme_option('skip_ad_text', 'You can skip this ad in') ),        							//translate "You can skip this ad in" counter to your language
			'mutedNotificationText' => "Video has no sound",  //translate "Skip advertisement" button to your language
			'playBtnTooltipTxt' => esc_html( viem_get_theme_option('play_btn_tooltip_txt', 'Play') ),                    //translate "Play" to your language
			'pauseBtnTooltipTxt' => esc_html( viem_get_theme_option('pause_btn_tooltip_txt', 'Pause') ),                  //translate "Pause" to your language
			'rewindBtnTooltipTxt' => esc_html( viem_get_theme_option('rewind_btn_tooltip_txt', 'Rewind') ),                //translate "Rewind" to your language
			'downloadVideoBtnTooltipTxt' => "Download video", //translate "Download video" to your language
			'qualityBtnOpenedTooltipTxt' => esc_html( viem_get_theme_option('quality_btn_opened_tooltip_txt', 'Close settings') ), //translate "Close settings" to your language
			'qualityBtnClosedTooltipTxt' => esc_html( viem_get_theme_option('quality_btn_close_tooltip_txt', 'settings') ),       //translate "Settings" to your language
			'muteBtnTooltipTxt' => esc_html( viem_get_theme_option('mute_btn_tooltip_txt', 'Mute') ),                    			//translate "Mute" to your language
			'unmuteBtnTooltipTxt' => esc_html( viem_get_theme_option('unmute_btn_tooltip_txt', 'Unmute') ),                //translate "Unmute" to your language
			'fullscreenBtnTooltipTxt' => esc_html( viem_get_theme_option('fullscreen_btn_tooltip_txt', 'Fullscreen') ),        //translate "Fullscreen" to your language
			'exitFullscreenBtnTooltipTxt' => esc_html( viem_get_theme_option('exit_fullscreen_btn_tooltip_txt', 'Exit fullscreen') ),//translate "Exit fullscreen" to your language
			'infoBtnTooltipTxt' => esc_html( viem_get_theme_option('infoBtnTooltipTxt', 'Show info') ),				 			//translate "Show info" to your language
			'embedBtnTooltipTxt' => esc_html( viem_get_theme_option('embedBtnTooltipTxt', 'Embed') ),                  			//translate "Embed" to your language
			'shareBtnTooltipTxt' => "Share",                  //translate "Share" to your language
			'volumeTooltipTxt' => esc_html( viem_get_theme_option('volume_tooltip_txt', 'Volume') ),                   		//translate "Volume" to your language
			'playlistBtnClosedTooltipTxt' => esc_html( viem_get_theme_option('playlist_btn_closed_tooltip_txt', 'Show playlist') ), //translate "Show playlist" to your language
			'playlistBtnOpenedTooltipTxt' => esc_html( viem_get_theme_option('playlist_btn_opened_tooltip_txt', 'Exit fullscreen') ), //translate "Exit fullscreen" to your language
			'facebookBtnTooltipTxt' => "Share on Facebook",   //translate "Share on Facebook" to your language
			'twitterBtnTooltipTxt' => "Share on Twitter",     //translate "Share on Twitter" to your language
			'googlePlusBtnTooltipTxt' => "Share on Google+",  //translate "Share on Google+" to your language
			'lastBtnTooltipTxt' => esc_html( viem_get_theme_option('last_btn_tooltip_txt', 'Go to last video') ),        				//translate "Go to last video" to your language
			'firstBtnTooltipTxt' => esc_html( viem_get_theme_option('first_btn_tooltip_txt', 'Go to first video') ),      				//translate "Go to first video" to your language
			'nextBtnTooltipTxt' => esc_html( viem_get_theme_option('next_btn_tooltip_txt', 'Play next video') ),         			//translate "Play next video" to your language
			'previousBtnTooltipTxt' => esc_html( viem_get_theme_option('previous_btn_tooltip_txt', 'Play previous video') ), 			//translate "Play previous video" to your language
			'shuffleBtnOnTooltipTxt' => esc_html( viem_get_theme_option('shuffle_btn_on_tooltip_txt', 'Shuffle on') ),         //translate "Shuffle on" to your language
			'shuffleBtnOffTooltipTxt' => esc_html( viem_get_theme_option('shuffle_btn_off_tooltip_txt', 'Shuffle off') ),       //translate "Shuffle off" to your language
			'nowPlayingTooltipTxt' => esc_html( viem_get_theme_option('nowplaying_btn_tooltip_txt', 'NOW PLAYING') ),          //translate "NOW PLAYING" to your language
			'embedWindowTitle1' => "SHARE THIS PLAYER:",      //translate "SHARE THIS PLAYER:" to your language
			'embedWindowTitle2' => "EMBED THIS VIDEO IN YOUR SITE:",//translate "EMBED THIS VIDEO IN YOUR SITE:" to your language
			'embedWindowTitle3' => "SHARE LINK TO THIS PLAYER:",//translate "SHARE LINK TO THIS PLAYER:" to your language
			'copyTxt' => esc_html__('copy', 'viem'),
			'copiedTxt' => esc_html__('Copied', 'viem'),
			'youtubePlaylistID' => $youtubePlaylistID, //automatic youtube playlist ID (leave blank "" if you want to use manual playlist) LL4qbSRobYCjvwo4FCQFrJ4g
			'youtubeChannelID' => $youtubeChannelID,  //automatic youtube channel ID (leave blank "" if you want to use manual playlist) UCHqaLr9a9M7g9QN6xem9HcQ
			'rootFolder' => get_template_directory_uri().'/assets/lib/video-player/',
			//manual playlist
			'videos' => $videos,
		);
		
		echo '<div class="DawnThemes_video_player Elite_video_player" id="' . $video_id . '" ><div id="DawnThemes_video_options" style="display:none;">' .
			json_encode( $v_player ) . '</div></div>';
	}
}

if( !function_exists('viem_showmore_post_content') ){
	function viem_showmore_post_content(){
		?>
		<div class="showmore-post-content-btn">
			<a href="#" class="showmore-btn"><?php echo esc_html__('Show More', 'viem');?></a>
		</div>
		<?php
	}
}

if( !function_exists('viem_latest_video_single_slider') ){
	function viem_latest_video_single_slider(){
		$args = array(
			'orderby'         => "date",
			'order'           => "DESC",
			'post_type'       => "viem_video",
			'posts_per_page'  => get_option('posts_per_page', 10),
		);
		
		$p = new WP_Query($args);
		if($p->have_posts()): ?>
		<div class="viem_latest_video_single_slider viem-carousel-slide owl-carousel" data-autoplay="true" data-dots="0" data-nav="1" data-items="1" data-rtl="<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
		<?php 
			while ($p->have_posts()): $p->the_post();
				$post_id = get_the_ID();
				?>
				<div class="item">
					<a class="post-link" href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"></a>
					<?php 
					$thumbnail = esc_url(viem_placeholder_img_src());
					if (has_post_thumbnail()){
						$post_thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full', true);
						$thumbnail = $post_thumbnail[0];
					}
					?>
					<div class="item-thumbnail" style="background-image: url(<?php echo esc_url($thumbnail) ?>)"></div>
					<?php if( class_exists('viem_posttype_video') ) viem_posttype_video::viem_video_badges_html(); ?>
					<div class="item-content">
						<div class="icon-player">
							<?php $icon_player_url = viem_get_theme_option('video_play_btn', get_template_directory_uri() . '/assets/images/video-player/playButtonPoster.png');?>
								<img src="<?php echo esc_url($icon_player_url)?>">
						</div>
						<header class="post-header">
							<?php	
							the_title( '<h2 class="post-title" data-itemprop="name">', '</h2>' );
							?>
						</header><!-- .entry-header -->
						<?php 
						$taxonomy_objects = get_object_taxonomies( get_post_type() );
						$taxonomy_name = isset($taxonomy_objects[0]) ? $taxonomy_objects[0] : 'category';
						$categories_list = get_the_term_list($post_id, $taxonomy_name, '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem' )); ?>
			        	<div class="post-category">
			        		<span class="viem_main_color"><?php echo viem_print_string( $categories_list ); ?></span>
			        	</div>
					</div>
				</div>
				<?php
			endwhile;
		?>
		</div>
		<?php
		endif;
		wp_reset_postdata();
	}
}

if( !function_exists('viem_badges_nav_menu') ){
	function viem_badges_nav_menu($menu_name = 'badges'){
		
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		
			$menu_items = wp_get_nav_menu_items($menu->term_id);
		
			echo '<span class="badges-menu-toggle"><i class="fa fa-certificate" aria-hidden="true"></i></span>';
			echo '<ul id="viem-menu-' . $menu_name . '">';
		
			foreach ( (array) $menu_items as $key => $menu_item ) {
				$title = $menu_item->title;
				$url = $menu_item->url;
				$object_id = $menu_item->object_id;
				$thumbnail_id 	= absint(get_option( "viem_video_badges_thumbnail_id$object_id")) ? get_option( "viem_video_badges_thumbnail_id$object_id"): '';
				if( $thumbnail_id ){
					$image = wp_get_attachment_url( $thumbnail_id );
					echo '<li><a href="' . $url . '"><span class="jcb-badge-icon"><img src="'.esc_url( $image ).'" alt="'.$title.'"/></span><span class="badge-icon-text viem_main_color">' . $title . '</span></a></li>';
				}else{
					echo '<li><a href="' . $url . '"><span class="badge-icon-text viem_main_color">' . $title . '</span></a></li>';
				}
					
			}
			echo '</ul>';
		}
		
	}
}

if( !function_exists('viem_trending_nav_menu') ){
	function viem_trending_nav_menu($menu_name = 'trending'){
		
		if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {
			$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );

			$menu_items = wp_get_nav_menu_items($menu->term_id);
			
			echo '<div class="viem-trending-nav">';
			echo '<ul id="viem-menu-' . $menu_name . '">';

			foreach ( (array) $menu_items as $key => $menu_item ) {
				$title = $menu_item->title;
				$url = $menu_item->url;
				
				echo '<li><a href="' . $url . '" title="' . $title . '">' . $title . '</span></a></li>';
			}
			echo '</ul></div>';
		}

	}
}

if( !function_exists('viem_header_social_links') ){
	function viem_header_social_links(){
		if( viem_get_theme_option('show_header_social_links', '0') == '1' ){
			$social_links = array(
				'email' => 'header_socials_email',
				'facebook' => 'header_socials_facebook',
				'twitter' => 'header_socials_twitter',
				'google-plus' => 'header_socials_google-plus',
				'youtube' => 'header_socials_youtube',
				'vimeo' => 'header_socials_vimeo',
				'flickr' => 'header_socials_flickr',
				'pinterest' => 'header_socials_pinterest',
				'skype' => 'header_socials_skype',
				'linkedin' => 'header_socials_linkedin',
				'rss' => 'header_socials_rss',
				'instagram' => 'header_socials_instagram',
				'github' => 'header_socials_github',
				'behance' => 'header_socials_behance',
				'stack-exchange' => 'header_socials_stack-exchange',
				'tumblr' => 'header_socials_tumblr',
				'soundcloud' => 'header_socials_soundcloud',
				'dribbble' => 'header_socials_dribbble',
			);
			foreach ($social_links as $social => $key){
				$link = viem_get_theme_option($key);
				$icon = ($social == 'email') ? 'envelope-o' : $social;
				if( $link != '' ){ ?>
					<a href="<?php echo esc_url($link);?>" target="_blank"><i class="fa fa-<?php echo esc_attr($icon);?>"></i></a>
				<?php	
				}
			}
		}
	}
}

