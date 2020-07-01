<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_series' ) ) {

	class viem_posttype_series {

		public function __construct() {
			add_action( 'init', array( &$this, 'register_post_type' ) );
			add_action( 'template_redirect', array($this,'stop_redirect'), 0);
			
			if(is_admin()){
				add_action('viem_add_meta_boxes', array(&$this,'add_meta_boxes'), 30);
				add_action('viem_save_meta_boxes', array(&$this,'save_meta_boxes'), 30, 2);
				//Admin Columns
				add_filter( 'manage_edit-series_columns', array($this,'manage_edit_columns') );
				add_filter( 'manage_series_posts_custom_column',  array($this,'manage_custom_column'),10, 2 );
				
			}else{
				add_filter( 'template_include', array( $this, 'template_loader' ) );
			}
		}

		public function register_post_type() {
			if ( post_type_exists( 'series' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_'.basename(get_template_directory()) . '_permalinks' );
			$series_permalink = empty( $permalinks['viem_series_base'] ) ? _x( 'series', 'slug', 'viem-ultimate' ) : $permalinks['viem_series_base'];
			
			register_post_type( 
				'viem_series',
				apply_filters( 
					'viem_register_post_type_series', 
					array( 
						'labels' => array( 
							'name' => esc_html__( 'Series', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Series', 'viem-ultimate' ), 
							'menu_name' => _x( 'Series', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add New Series', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add New Series', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Series', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Series', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Series', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Series', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Series', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Series found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Series found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Series', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'show_in_menu' => 'edit.php?post_type=viem_video',
						'menu_position' => 13,
						'menu_icon' => 'dashicons-video-alt3',
						'exclude_from_search' => false, 
						'rewrite' => $series_permalink ? array( 
							'slug' => untrailingslashit( $series_permalink ),
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_playlist_page_id = viem_get_theme_option( 'main-series-page' ) ) &&
							 get_post( $main_playlist_page_id ) ? get_page_uri( $main_playlist_page_id ) : 'series', 
							'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail' ) ) ) );
		}
		
		public function add_meta_boxes(){
			$meta_box = array (
				'id' => 'dt-metabox-viem_series',
				'title' => esc_html__( 'Series Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_series',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Release Year', 'viem-ultimate' ),
						'name' => 'release_year',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '2018',
						'description' => esc_html__( 'Year of release. Date string appears as you enter.', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Creators', 'viem-ultimate' ),
						'name' => 'creators',
						'type' => 'text',
						'width' => '300px',
						'placeholder' => '',
						'description' => esc_html__( 'Enter the Creators, separated by a comma.', 'viem-ultimate' )
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
		}
		
		public function save_meta_boxes($post_id, $post){
			if('viem_series'!==get_post_type($post))
				return;
		}
		
		public function stop_redirect(){
			if ( is_singular('viem_series') ) {
				global $wp_query;
				$page = (int) $wp_query->get('page');
				if ( $page > 1 ) {
					// convert 'page' to 'paged'
					$query->set( 'page', 1 );
					$query->set( 'paged', $page );
				}
				// prevent redirect
				remove_action( 'template_redirect', 'redirect_canonical' );
			}
		}
			
		public function manage_edit_columns($columns){
			//unset($columns['comments'], $columns['categories'], $columns['date'] );
			$columns          = array();
			$columns['cb']    = '<input type="checkbox" />';
			$columns['thumb'] = esc_html__( 'Image', 'viem-ultimate' );
			$columns['title']  = esc_html__( 'Name', 'viem-ultimate' );
			$columns['author'] = esc_html__( 'Author', 'viem-ultimate' );
			$columns['date']   = 	esc_html__( 'Date', 'viem-ultimate' );
			return $columns;
		}
		
		public function  manage_custom_column($column, $post_id){
			global $post;
			switch ($column){
				case 'thumb' :
					if ( has_post_thumbnail( $post->ID ) ){
						echo  get_the_post_thumbnail( $post->ID,'thumbnail');
					}else{
							echo '<span class="na">&ndash;</span>';
					}
					break;
			
				default:
					break;
			}
		}
		
		public function template_loader($template){
			if(is_post_type_archive( 'viem_series' ) ){
				$template       = locate_template( 'archive-series.php' );
			}
			return $template;
		}
		
		
		public static function get_videos_count( $post_id = '' ){
			$videos_count = 0;
			$args = array(
				'post_type' => 'viem_video',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby' => 'date',
				'post__not_in' => array($post_id),
				'meta_query' => array(
					array(
						'key' => '_dt_video_series_id',
						'value' => $post_id,
						'compare' => 'LIKE',
					),
				)
			);
			$v_query = new WP_Query($args);
			
			if( $v_query->have_posts() ){
				$videos_count = $v_query->post_count;
			}
			
			echo $videos_count == 1 ? $videos_count . '<span class="desc"> '.esc_html__('Video', 'viem-ultimate').'</span>' : $videos_count . '<span class="desc"> '.esc_html__('Videos', 'viem-ultimate').'</span>';
			
			wp_reset_postdata();
		}
	}
	new viem_posttype_series();
}