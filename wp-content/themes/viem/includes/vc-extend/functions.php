<?php
if(is_admin()){
	add_action('vc_backend_editor_render', 'viem_vc_backend_editor_enqueue_scripts', 100 );
	function viem_vc_backend_editor_enqueue_scripts(){
		wp_enqueue_style( 'chosen' );
		wp_enqueue_style('font-awesome');
		wp_register_script( 'viem-vc-custom', viem_includes_url . '/vc-extend/assets/js/vc-custom.js', array( 'jquery', 'jquery-ui-datepicker', 'chosen' ), '1.0.0', true );
		$viem_vcL10n = array(
			'item_title' => esc_html__( "Add Item", 'viem' ) ,
			'add_item_title' => esc_html__( "Item", 'viem' ),
			'move_title' => esc_html__( "Move", 'viem' ));
		wp_localize_script( 'viem-vc-custom', 'viem_vcL10n', $viem_vcL10n );
		wp_enqueue_script( 'viem-vc-custom' );
	}
}
function viem_get_post_category() {
	// Get all post category
	$post_category = array();
	$post_categories = get_categories();
	$post_category[esc_html__( '--Select--', 'viem' )] = '';
	foreach ( $post_categories as $p_cat ) {
		$post_category[$p_cat->name] = $p_cat->slug;
	}
	return $post_category;
}

add_filter( 'vc_autocomplete_dt_class_taxonomies_callback', 'viem_post_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_dt_class_taxonomies_render', 'viem_post_taxonomies_field_render', 10, 1 );
add_filter( 'vc_autocomplete_dt_upcoming_events_taxonomies_callback', 'viem_post_taxonomies_field_search', 10, 1 );
add_filter( 'vc_autocomplete_dt_upcoming_events_taxonomies_render', 'viem_post_taxonomies_field_render', 10, 1 );

function viem_taxonomies_types() {
	global $viem_taxonomies_types;
	if ( is_null( $viem_taxonomies_types ) ) {
		$viem_taxonomies_types = get_taxonomies( array( 'public' => true ), 'objects' );
	}

	return $viem_taxonomies_types;
}

function viem_get_term_object( $term ) {
	$taxonomies_types = viem_taxonomies_types();
	return array(
		'label' => $term->name,
		'value' => $term->term_id,
		'group_id' => $term->taxonomy,
		'group' => isset($taxonomies_types[$term->taxonomy],$taxonomies_types[$term->taxonomy]->labels,$taxonomies_types[$term->taxonomy]->labels->name ) ? $taxonomies_types[$term->taxonomy]->labels->name.':' : esc_html__('Taxonomies:','viem' ) );
}

function viem_post_taxonomies_field_search( $search_string ) {
	$data = array();
	$vc_filter_by = isset( $_POST['vc_filter_by'] ) ? $_POST['vc_filter_by'] : '';
	$taxonomies_types = strlen( $vc_filter_by ) > 0 ? array( $vc_filter_by ) : array_keys( viem_taxonomies_types() );
	$taxonomies = get_terms( $taxonomies_types, array( 'hide_empty' => false, 'search' => $search_string ) );
	if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $t ) {
			if ( is_object( $t ) ) {
				$data[] = viem_get_term_object( $t );
			}
		}
	}

	return $data;
}

function viem_post_taxonomies_field_render( $term ) {
	$taxonomies_types = viem_taxonomies_types();
	$terms = get_terms(
		array_keys( $taxonomies_types ),
		array( 'include' => array( $term['value'] ), 'hide_empty' => false ) );
	$data = false;
	if ( is_array( $terms ) && 1 === count( $terms ) ) {
		$term = $terms[0];
		$data = viem_get_term_object( $term );
	}

	return $data;
}


function viem_search_by_title_only( $search, &$wp_query ) {
	global $wpdb;
	if ( empty( $search ) ) {
		return $search;
	} // skip processing - no search term in query
	$q = $wp_query->query_vars;
	if ( isset( $q['viem_search_by_title_only'] ) && true == $q['viem_search_by_title_only'] ) {
		$n = ! empty( $q['exact'] ) ? '' : '%';
		$search = $searchand = '';
		foreach ( (array) $q['search_terms'] as $term ) {
			$term = $wpdb->esc_like( $term );
			$like = $n . $term . $n;
			$search .= $wpdb->prepare( "{$searchand}($wpdb->posts.post_title LIKE %s)", $like );
			$searchand = ' AND ';
		}
		if ( ! empty( $search ) ) {
			$search = " AND ({$search}) ";
			if ( ! is_user_logged_in() ) {
				$search .= " AND ($wpdb->posts.post_password = '') ";
			}
		}
	}

	return $search;
}

function viem_exclude_field_render( $value ) {
	$post = get_post( $value['value'] );

	return is_null( $post ) ? false : array(
		'label' => $post->post_title,
		'value' => $post->ID,
		'group' => $post->post_type );
}

function viem_exclude_field_search( $data_arr ) {
	$query = isset( $data_arr['query'] ) ? $data_arr['query'] : null;
	$term = isset( $data_arr['term'] ) ? $data_arr['term'] : '';
	$data = array();
	$args = ! empty( $query ) ? array( 's' => $term, 'post_type' => $query ) : array(
		's' => $term,
		'post_type' => 'post' );
	$args['viem_search_by_title_only'] = true;
	$args['numberposts'] = - 1;
	if ( 0 === strlen( $args['s'] ) ) {
		unset( $args['s'] );
	}
	add_filter( 'posts_search', 'viem_search_by_title_only', 500, 2 );
	$posts = get_posts( $args );
	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$data[] = array( 'value' => $post->ID, 'label' => $post->post_title, 'group' => $post->post_type );
		}
	}

	return $data;
}

function viem_include_field_search( $search_string ) {
	$query = $search_string;
	$data = array();
	$args = array(
		's' => $query,
		'post_type' => 'any',
	);
	$args['viem_search_by_title_only'] = true;
	$args['numberposts'] = - 1;
	if ( 0 === strlen( $args['s'] ) ) {
		unset( $args['s'] );
	}
	add_filter( 'posts_search', 'viem_search_by_title_only', 500, 2 );
	$posts = get_posts( $args );
	if ( is_array( $posts ) && ! empty( $posts ) ) {
		foreach ( $posts as $post ) {
			$data[] = array(
				'value' => $post->ID,
				'label' => $post->post_title,
				'group' => $post->post_type,
			);
		}
	}

	return $data;
}

/**
 * @param $value
 *
 * @return array|bool
 */
function viem_include_field_render( $value ) {
	$post = get_post( $value['value'] );

	return is_null( $post ) ? false : array(
		'label' => $post->post_title,
		'value' => $post->ID,
		'group' => $post->post_type,
	);
}

function viem_get_list_tab_title($query_types = 'category', $categories, $tags, $tabs_orderby){
	if( ! class_exists( 'viem_posttype_video' ) )
		return '';
	$array_tab 	= array();
	$list_tab 	= array();
	
	if($query_types == 'category'){
		if(empty($categories)){
			$array_tab 	= viem_get_cats();
		}else{
			$array_tab 	= explode(',', $categories);
		}
	}elseif($query_types == 'tags'){
		if(empty($tags)){
			$array_tab 	= viem_get_tags();
		}else{
			$array_tab 	= explode(',', $tags);
		}
	}else{ // list_orderby
		$array_tab 	= explode(',', $tabs_orderby);
	}
	
	foreach ($array_tab as $tab) {
		$list_tab[$tab] = viem_tab_title($tab, $query_types);
	}
	
	return $list_tab;
}

function viem_get_cats(){
	if( ! class_exists( 'viem_posttype_video' ) )
		return '';
	$cats = get_terms('video_cat');
	$arr = array();
	foreach ($cats as $cat) {
		$arr[] = $cat->slug;
	}
	return $arr;
}

function viem_get_tags(){
	if( ! class_exists( 'viem_posttype_video' ) )
		return '';
	$tags = get_terms('video_tag');
	$arr = array();
	foreach ($tags as $tag) {
		$arr[] = $tag->slug;
	}
	return $arr;
}

function viem_tab_title($tab, $query_types){
	if( $query_types == 'category' ){
		$cat = get_term_by('slug', $tab, 'video_cat');
		$name = str_replace(' ', '_', $tab);
		return array( 'name'=> $name, 'title'=>$cat->name, 'short_title'=>$cat->name);

	}elseif($query_types == 'tags' ){ // Tab title is tags
		$tag = get_term_by('slug', $tab, 'video_tag');

		return array('name'=>str_replace(' ', '_', $tab),'title'=>$tag->name,'short_title'=>$tag->name);

	}else{
		switch ($tab) {
			case 'recent':
				return array('name'=>$tab,'title'=>esc_html__('Latest Products', 'viem'),'short_title'=>esc_html__('Latest', 'viem'));
			case 'featured_product':
				return array('name'=>$tab,'title'=>esc_html__('Featured Products', 'viem'),'short_title'=>esc_html__('Featured', 'viem'));
			case 'top_rate':
				return array('name'=>$tab,'title'=> esc_html__('Top Rated Products', 'viem'),'short_title'=>esc_html__('Top Rated',  'viem'));
			case 'best_selling':
				return array('name'=>$tab,'title'=>esc_html__('BestSeller Products', 'viem'),'short_title'=>esc_html__('Best Seller', 'viem'));
			case 'on_sale':
				return array('name'=>$tab,'title'=>esc_html__('Special Products', 'viem'),'short_title'=>esc_html__('Sale', 'viem'));
		}
	}
}

function viem_video_tabs_query($query_types, $tab, $orderby,$meta_key,$order, $post_per_page=-1, $offset=0, $paged=1){
	
	$query_args = array(
		'post_type' 	=> 'viem_video',
		'posts_per_page' => $post_per_page,
		'post_status' 	=> 'publish',
		'orderby'         => "{$orderby}",
		'meta_key'		=> "{$meta_key}",
		'order'           => "{$order}",
		'offset'            => $offset,
		'paged' => $paged
	);

	if ($query_types == 'category'){
		if($tab!=''){
			$category_array = array_filter(explode(',', $tab));
				
			if(!empty($category_array)){
				$query_args['tax_query'][] =
				array(
					'taxonomy'			=> 'video_cat',
					'field'				=> 'slug',
					'terms'				=> $category_array,
					'operator'			=> 'IN'
				);
			}
		}
	}elseif ($query_types == 'tags'){
		if($tab!=''){
			$tags_array = array_filter(explode(',', $tab));

			if( !empty($tags_array) ){
				$query_args['tax_query'][] =
				array(
					'taxonomy'			=> 'video_tag',
					'field'				=> 'slug',
					'terms'				=> $tags_array,
					'operator'			=> 'IN'
				);
			}
		}
	}elseif ($query_types == 'orderby'){
		$tab = $tab;
	}else{ // List orderby
		$tab = $orderby;
	}

	return new WP_Query($query_args);
}
