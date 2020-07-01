<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_playlist' ) ) {

	class viem_posttype_playlist {

		protected static $_current_filters;

		public function __construct() {
			add_action( 'init', array( &$this, 'register_post_type' ) );
			add_action( 'template_redirect', array($this,'stop_redirect'), 0);
			
			if(is_admin()){
				add_action( 'viem_add_meta_boxes', array(&$this,'add_meta_boxes'), 30 );
				add_action( 'viem_save_meta_boxes', array(&$this,'save_meta_boxes'), 30, 2) ;
				//Admin Columns
				add_filter( 'manage_edit-viem_playlist_columns', array($this,'manage_edit_columns') );
				add_filter( 'manage_viem_playlist_posts_custom_column',  array($this,'manage_custom_column'),10, 2 );
				
			}else{
				add_filter( 'template_include', array( $this, 'template_loader' ) );
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
				add_action( 'wp', array( $this, 'remove_playlist_query' ) );
			}
		}

		public function register_post_type() {
			if ( post_type_exists( 'viem_playlist' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_'.basename(get_template_directory()) . '_permalinks' );
			$playlist_permalink = empty( $permalinks['viem_playlist_base'] ) ? _x( 'playlist', 'slug', 'viem-ultimate' ) : $permalinks['viem_playlist_base'];
			
			register_post_type( 
				'viem_playlist',
				apply_filters( 
					'viem_register_post_type_dt_playlist', 
					array( 
						'labels' => array( 
							'name' => esc_html__( 'Playlists', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Playlist', 'viem-ultimate' ), 
							'menu_name' => _x( 'Playlist', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add New Playlist', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add New Playlist', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Playlist', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Playlist', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Playlist', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Playlist', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Playlist', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Playlist found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Playlist found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Playlist', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'show_in_menu' => 'edit.php?post_type=viem_video',
						'menu_position' => 13, 
						'menu_icon' => 'dashicons-video-alt3', 
						'exclude_from_search' => false, 
						'rewrite' => $playlist_permalink ? array( 
							'slug' => untrailingslashit( $playlist_permalink ), 
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_playlist_page_id = viem_get_theme_option( 'main-playlist-page') ) &&
							 get_post( $main_playlist_page_id ) ? get_page_uri( $main_playlist_page_id ) : 'playlists', 
							'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail' ) ) ) );
		}
		
		public function stop_redirect(){
			if ( is_singular('viem_playlist') ) {
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
		
		public function add_meta_boxes(){
			$meta_box = array (
				'id' => 'dt-metabox-viem_playlist',
				'title' => esc_html__( 'Playlist Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_playlist',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Add videos to playlist', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_playlist_type_add',
						'type' => 'select',
						'value'=>'manually',
						'description'=>__('You can add videos manually one by one to your playlist (mixed videos) Or form YouTube playlist.','viem-ultimate'),
						'options'=>array(
							''=> esc_html__('Manually','viem-ultimate'),
							'youtube_playlist'=> esc_html__('YouTube Playlist','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'YouTube Playlist ID', 'viem-ultimate' ),
						'name' => 'youtube_playlist_id',
						'type' => 'text',
						'width' => '300px',
						'placeholder' => 'PL_HbKbJsShUhl9s6GyBPRvZ6glDBpq6k4',
						'dependency' => array( 'field' => 'video_playlist_type_add', 'value' => array( 'youtube_playlist') ),
						'description' => esc_html__( 'Automatic youtube playlist ID. Is the last part of the URL https://www.youtube.com/watch?v=HIJ0-u67Aeo&list=PL_HbKbJsShUhl9s6GyBPRvZ6glDBpq6k4. youtubePlaylistID: "PL_HbKbJsShUhl9s6GyBPRvZ6glDBpq6k4"', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Channel', 'viem-ultimate' ),
						'name' => 'video_channel_id',
						'type' => 'select',
						'multiple'=>true,
						'chosen'=>true,
						'description'=>__('Add this playlist to a channel.','viem-ultimate'),
						'options'=>_viem_get_options_select_post('viem_channel')
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
		
		}
		
		public function save_meta_boxes($post_id, $post){
			if('viem_playlist'!==get_post_type($post))
				return;
		}		
		public function manage_edit_columns($columns){
			//unset($columns['comments'], $columns['categories'], $columns['date'] );
			$columns          = array();
			$columns['cb']    = '<input type="checkbox" />';
			$columns['thumb'] = esc_html__( 'Image', 'viem-ultimate' );
			$columns['title']  = esc_html__( 'Name', 'viem-ultimate' );
			$columns['channel'] = esc_html__( 'Channel', 'viem-ultimate' );
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
				case 'channel' :
					if( viem_get_post_meta('video_channel_id') ):
						echo viem_posttype_playlist::get_channel_list('','',', ','',true);
					else:
						echo '<span class="na">&ndash;</span>';
					endif;
					break;
					
				default:
					break;
			}
		}
		
		public function template_loader($template){
			if(is_post_type_archive( 'viem_playlist' ) ){
				$template       = locate_template( 'archive-playlist.php' );
			}
			return $template;
		}
		
		public function pre_get_posts($q){
			if ( ! $q->is_main_query() ) {
				return;
			}
			if ( ! $q->is_post_type_archive( 'viem_playlist' ) && ! $q->is_tax( get_object_taxonomies( 'viem_playlist' ) ) ) {
				return;
			}
			$this->playlist_query($q);
			$this->remove_playlist_query();
		}
		
		public function playlist_query($q){
			$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ) ) );
			$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ) ) );
			do_action( 'viem_playlist_query', $q, $this );
		}
		
		public function remove_playlist_query() {
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
		
		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
				
			$meta_query['channel_filter']  = $this->channel_filter_meta_query();
		
			return array_filter( apply_filters( 'viem_playlist_query_meta_query', $meta_query, $this ) );
		}
		
		public function get_tax_query( $tax_query = array() ) {
			if ( ! is_array( $tax_query ) ) {
				$tax_query = array();
			}
		
			// Layered nav filters on terms
			if ( $_current_filters = $this->get_current_filters() ) {
				foreach ( $_current_filters as $taxonomy => $data ) {
					$tax_query[] = array(
						'taxonomy' => $taxonomy,
						'field'    => 'slug',
						'terms'    => $data['terms'],
						'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN',
						'include_children' => false,
					);
				}
			}
		
			return array_filter( apply_filters( 'viem_playlist_query_tax_query', $tax_query, $this ) );
		}
		
		public static function get_current_filters() {
			if ( ! is_array( self::$_current_filters ) ) {
				self::$_current_filters = array();
		
				if ( $taxonomies = get_object_taxonomies( 'viem_playlist' ) ) {
					foreach ( $taxonomies as $tax ) {
						$attribute    = str_replace('playlist_', '', $tax);
						$taxonomy     = 'playlist_'.$attribute;
						$filter_terms = ! empty( $_GET[ 'filter_' . $attribute ] ) ? explode( ',', wc_clean( $_GET[ 'filter_' . $attribute ] ) ) : array();
		
						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
							continue;
						}
		
						$query_type = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array( 'and', 'or' ) ) ? viem_clean( $_GET[ 'query_type_' . $attribute ] ) : '';
						self::$_current_filters[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding
						self::$_current_filters[ $taxonomy ]['query_type'] = $query_type ? $query_type : apply_filters( 'viem_default_playlist_filter_query_type', 'and' );
					}
				}
			}
			return self::$_current_filters;
		}
		
		public static function get_main_meta_query() {
			global $wp_the_query;
		
			$args       = $wp_the_query->query_vars;
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
		
			return $meta_query;
		}
		
		public static function get_main_tax_query() {
			global $wp_the_query;
		
			$args      = $wp_the_query->query_vars;
			$tax_query = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
		
			if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
				$tax_query[ $args['taxonomy'] ] = array(
					'taxonomy' => $args['taxonomy'],
					'terms'    => array( $args['term'] ),
					'field'    => 'slug',
				);
			}
		
			if ( ! empty( $args['playlist_cat'] ) ) {
				$tax_query[ 'playlist_cat' ] = array(
					'taxonomy' => 'playlist_cat',
					'terms'    => array( $args['playlist_cat'] ),
					'field'    => 'slug',
				);
			}
		
			if ( ! empty( $args['playlist_level'] ) ) {
				$tax_query[ 'playlist_level' ] = array(
					'taxonomy' => 'playlist_level',
					'terms'    => array( $args['playlist_level'] ),
					'field'    => 'slug',
				);
			}
		
			return $tax_query;
		}
		
		public function channel_filter_meta_query(){
			$filter_channel = isset( $_GET['filter_channel'] ) ? absint($_GET['filter_channel']) : 0;
			return $filter_channel > 0 ? array(
				'key'              => '_dt_channel',
				'value'            => '"'.$filter_channel.'"',
				'compare'          => 'LIKE',
				'channel_filter'   => true
			) : array();
		}
		
		
		public static function get_channel_list($post_id='', $before = '', $sep = '', $after = '', $url='default', $experience = false){
			$post = get_post($post_id);
			$links = array();
			$channel_ids = viem_get_post_meta('video_channel_id',$post->ID);
			if($channel_ids && $channels = get_posts(array('post_type'=>'viem_channel','include'=>$channel_ids))){
				foreach ($channels as $channel){
					if($url===false){
						$links[] =  $channel->post_title;
					}else{
						$href = $url===true ? get_edit_post_link( $channel->ID ) : get_the_permalink($channel->ID) ;
						$links[] = '<a href="' . esc_url($href). '">' . $channel->post_title.($experience ? '<span playlist="channel-experience">'.__('Experiace: ','viem-ultimate').viem_get_post_meta('experience',$channel->ID).'</span>':''). '</a>';
					}
				}
			}
			return $before . join( $sep, $links ) . $after;
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
						'key' => '_dt_video_playlist_id',
						'value' => $post_id,
						'compare' => 'LIKE',
					),
				)
			);
			$v_query = new WP_Query($args);
			
			if( $v_query->have_posts() ){
				$videos_count = $v_query->post_count;
			}
			
			echo $videos_count == 1 ? $videos_count . '<span class="desc">'.esc_html__('Video', 'viem-ultimate').'</span>' : $videos_count . '<span class="desc">'.esc_html__('Videos', 'viem-ultimate').'</span>';
			
			wp_reset_postdata();
		}
	}
	new viem_posttype_playlist();
}