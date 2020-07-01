<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_viem_movie' ) ) {

	class viem_posttype_viem_movie {
		
		const POSTTYPE = 'viem_movie';
		public $rewriteSlug = 'movies';
		public $rewriteSlugSingular = 'movie';
		public $featured_slug = 'featured';

		protected static $_current_filters;
		
		/**
		 * Static Singleton Holder
		 * @var self
		 */
		protected static $instance;
		/**
		 * Get (and instantiate, if necessary) the instance of the class
		 *
		 * @return self
		 */
		public static function instance(){
			if( ! self::$instance ){
				self::$instance = new self;
			}
			return self::$instance;
		}

		public function __construct() {
			
			$this->rewriteSlug			= $this->getRewriteSlug();
			$this->rewriteSlugSingular	= $this->getRewriteSlugSingular();
			
			add_action( 'init', array( &$this, 'register_post_type' ) );
			if(is_admin()){
				add_action('viem_add_meta_boxes', array(&$this,'add_meta_boxes'), 30);
				add_action('viem_save_meta_boxes', array(&$this,'save_meta_boxes'), 30, 2);
				//Admin Columns
				add_filter( 'manage_edit-viem_movie_columns', array($this,'manage_edit_columns') );
				add_filter( 'manage_viem_movie_posts_custom_column',  array($this,'manage_custom_column'),10, 2 );
			}else{
				add_filter('template_include', array(&$this, 'template_loader'));
				
				add_action('viem_movie_info', array(__class__, 'viem_single_movie_info') );
				
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
				add_action( 'wp', array( $this, 'remove_movie_query' ) );
			}

			/*remove WTI Like Post */
			remove_filter('the_content', 'PutWtiLikePost');
		}
		
		/**
		 * Get the rewrite slug
		 *
		 * @return string
		 */
		public function getRewriteSlug(){
			// translators: For compatibility with WPML and other multilingual plugins, not to be translated directly on .mo files.
			return sanitize_title( _x( viem_get_theme_option( 'movies_slug','movies' ), 'Archive Movies Slug', 'viem-ultimate') );
		}
		
		/**
		 * Get the single post rewrite slub
		 *
		 * @return string
		 */
		public function getRewriteSlugSingular(){
			// translators: For compatibility with WPML and other multilingual plugins, not to be translated directly on .mo files.
			return sanitize_title( _x( viem_get_theme_option( 'single_movie_slug','movie' ), 'Rewrite Singular Slug', 'viem-ultimate') );
		}

		public function register_post_type() {
			if ( post_type_exists( 'viem_movie' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_'.basename(get_template_directory()) . '_permalinks' );
			$movie_permalink = empty( $permalinks['viem_movie_base'] ) ? $this->rewriteSlugSingular : $permalinks['viem_movie_base'];
			
			register_post_type(
				'viem_movie',
				apply_filters( 
					'viem_register_post_type_movie', 
					array( 
						'labels' => array(
							'name' => esc_html__( 'Movies', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Movie', 'viem-ultimate' ), 
							'menu_name' => _x( 'Movies', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add New', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add New', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Movie', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Movie', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Movie', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Movie', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Movies', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Movies found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Movies found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Movie', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'menu_position' => 10, 
						'menu_icon' => 'dashicons-video-alt',
						'exclude_from_search' => false,
						'rewrite' => $movie_permalink ? array( 
							'slug' => untrailingslashit( $movie_permalink ), 
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_movie_page_id = viem_get_theme_option( 'main-movie-page' ) ) &&
							 get_post( $main_movie_page_id ) ? get_page_uri( $main_movie_page_id ) : $this->rewriteSlug,
							'supports' => array( 'title', 'editor', 'author', 'excerpt', 'thumbnail', 'comments' ) ) ) );
			register_taxonomy(
				'viem_movie_genre',
				array( 'viem_movie' ),
				array( 
					'labels' => array( 
						'name' => esc_html__( 'Movie Genres', 'viem-ultimate' ), 
						'singular_name' => esc_html__( 'Genre', 'viem-ultimate' ), 
						'search_items' => esc_html__( 'Search Genres', 'viem-ultimate' ), 
						'all_items' => esc_html__( 'All Genres', 'viem-ultimate' ), 
						'edit_item' => esc_html__( 'Edit Genre', 'viem-ultimate' ), 
						'update_item' => esc_html__( 'Update Genre', 'viem-ultimate' ), 
						'add_new_item' => esc_html__( 'Add New Genre', 'viem-ultimate' ), 
						'new_item_name' => esc_html__( 'New Genre', 'viem-ultimate' ), 
						'menu_name' => esc_html__( 'Movie Genres', 'viem-ultimate' ) ), 
						'show_ui' => true, 
						'query_var' => true, 
						'hierarchical' => true, 
						'rewrite' => array( 
						'slug' => empty( $permalinks['viem_movie_genre_base'] ) ? _x( 'movie-genre', 'slug', 'viem-ultimate' ) : $permalinks['viem_movie_genre_base'], 
						'with_front' => false ) ) );
		}

		public function add_meta_boxes(){
			$countries = array();
			if( function_exists('dawnthemes_countries') ){
				$countries = dawnthemes_countries();
			}
			$languages = array();
			if( function_exists('dawnthemes_languages') ){
				$languages = dawnthemes_languages();
			}
			$meta_box = array (
				'id' => 'dt-metabox-viem_movie',
				'title' => __ ( 'Movie Specifics', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_movie',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array(
						'name' => 'movie_picture',
						'type' => 'image',
						'label' => esc_html__( 'Movie Picture', 'viem-ultimate' ),
						'description' => esc_html__( 'Please upload an image related to the Movie.', 'viem-ultimate' ) ),
					array (
						'label' => esc_html__( 'Duration', 'viem-ultimate' ),
						'name' => 'movie_duration',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '',
						'description' => esc_html__( 'Please enter the length of the movie in minutes.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Movie Rating', 'viem-ultimate' ),
						'name' => 'movie_rating',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '',
						'description' => esc_html__( 'Please rate the movie. 0 being garbage and 10 being the best movie in the history of the Universe.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Movie Release Date', 'viem-ultimate' ),
						'name' => 'movie_release_date',
						'type' => 'datepicker',
						'timestamp'=> true,
						'description' => esc_html__( 'Please choose the date the movie was released to theatres.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Movie Country', 'viem-ultimate' ),
						'name' => 'movie_country',
						'type' => 'select',
						'multiple'=>true,
						'chosen'=>true,
						'options'=> $countries,
						'description' => esc_html__( 'Please choose the country where the move was filmed.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Main Language', 'viem-ultimate' ),
						'name' => 'movie_language',
						'type' => 'select',
						'chosen'=>true,
						'options'=> $languages,
						'description' => esc_html__( 'Please choose the main language in the movie.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Movie Budget', 'viem-ultimate' ),
						'name' => 'movie_budget',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => esc_html__( 'Please enter the crazy amount of money spent to create this film.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Opening Weekend Revenue', 'viem-ultimate' ),
						'name' => 'opening_weekend_revenue',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => esc_html__( 'Please enter the amount of money the movie made in it is opening weekend.', 'viem-ultimate' ),
					),
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Movie Player','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Select movie player type', 'viem-ultimate' ),
						'description' => '',
						'name' => 'movie_type',
						'type' => 'select',
						'value'=>'video',
						'options'=>array(
							'video'=> esc_html__('Video','viem-ultimate'),
							'series'=> esc_html__('Series','viem-ultimate'),
							'playlist'=> esc_html__('Playlist','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'Video', 'viem-ultimate' ),
						'name' => 'movie_video',
						'type' => 'select',
						'width' => '350px',
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_video'),
						'dependency' => array( 'field' => 'movie_type', 'value' => array( 'video') ),
						'description' => esc_html__( 'Select video for watching the movie', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Series', 'viem-ultimate' ),
						'name' => 'movie_series',
						'type' => 'select',
						'width' => '350px',
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_series'),
						'dependency' => array( 'field' => 'movie_type', 'value' => array( 'series') ),
						'description' => esc_html__( 'Select series for watching the movie', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Playlist', 'viem-ultimate' ),
						'name' => 'movie_playlist',
						'type' => 'select',
						'width' => '350px',
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_playlist'),
						'dependency' => array( 'field' => 'movie_type', 'value' => array( 'playlist') ),
						'description' => esc_html__( 'Select playlist for watching the movie', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'URL', 'viem-ultimate' ),
						'name' => 'movie_player_url',
						'type' => 'text',
						'width' => '100%',
						'placeholder' => 'http://',
						'description' => esc_html__( 'The link to watching the movie. It will be overridden the "Select movie player type" option.', 'viem-ultimate' ),
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Actor','viem-ultimate'),
					),
					
					array (
						'label' => esc_html__( 'Actors', 'viem-ultimate' ),
						'name' => 'movie_actor',
						'type' => 'chosen-order',
						'multiple'=>true,
						'chosen'=>true,
						'options'=> _viem_get_options_select_post('viem_actor'),
						'description' => esc_html__( 'Add actor members.', 'viem-ultimate' ),
					),
					
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
		}
		
		public function save_meta_boxes($post_id, $post){
			if('viem_movie'!==get_post_type($post))
				return;
			if(isset($_POST['dt_meta']['_dt_movie_country'])){
				$movie_country = $_POST['dt_meta']['_dt_movie_country'];
				update_post_meta($post_id,'_dt_movie_country',$movie_country);
			}else{
				update_post_meta($post_id,'_dt_movie_country',array());
			}
		}
		
		public static function viem_single_movie_info(){
			
			$post_id = get_the_ID();
			?>
			<div class="movie-info-list">
				<ul>
					<?php if ( ($categories_list = get_the_term_list($post_id, 'viem_movie_genre', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem-ultimate' ))) && viem_dt_categorized_blog() ) : ?>
					<li><strong><?php echo esc_html__( 'Genres:', 'viem-ultimate' ); ?></strong><span class="genres-links"><?php echo viem_print_string( $categories_list ); ?></span>
					</li>
					<?php
					endif;
					?>
					<?php $movie_release_date = viem_get_post_meta('movie_release_date');
					if( $movie_release_date ):?>
						<li><strong><?php echo esc_html__( 'Release Date:', 'viem-ultimate' ); ?></strong><?php echo date_i18n('M d, Y', strtotime($movie_release_date) ); ?></li>
					<?php
					endif;
					?>
					<?php $movie_duration = viem_get_post_meta('movie_duration');
					if( $movie_duration ):?>
						<li><strong><?php echo esc_html__( 'Duration:', 'viem-ultimate' ); ?></strong><?php echo esc_html($movie_duration) . esc_html__(' mins', 'viem'); ?></li>
					<?php
					endif;
					?>
					<?php $movie_rating = viem_get_post_meta('movie_rating');
					if( $movie_rating ):?>
						<li><strong><?php echo esc_html__( 'Rating:', 'viem-ultimate' ); ?></strong><?php echo esc_html($movie_rating); ?></li>
					<?php
					endif;
					?>
					<?php $movie_country = viem_get_post_meta('movie_country');
					if( $movie_country ):?>
						<li><strong><?php echo esc_html__( 'Country:', 'viem-ultimate' ); ?></strong><?php echo implode(',', $movie_country); ?></li>
					<?php
					endif;
					?>
					<?php $movie_language = viem_get_post_meta('movie_language');
					if( $movie_language ):?>
						<li><strong><?php echo esc_html__( 'Language:', 'viem-ultimate' ); ?></strong><?php echo esc_html($movie_language); ?></li>
					<?php
					endif;
					?>
					<?php $movie_budget = viem_get_post_meta('movie_budget');
					if( $movie_budget ):?>
						<li><strong><?php echo esc_html__( 'Budget:', 'viem-ultimate' ); ?></strong><?php echo esc_html($movie_budget); ?></li>
					<?php
					endif;
					?>
					<?php $opening_weekend_revenue = viem_get_post_meta('opening_weekend_revenue');
					if( $opening_weekend_revenue ):?>
						<li><strong><?php echo esc_html__( 'Opening Weekend:', 'viem-ultimate' ); ?></strong><?php echo esc_html($opening_weekend_revenue); ?></li>
					<?php
					endif;
					?>
					<?php $actor_ids = viem_get_post_meta('movie_actor', $post_id, true);
					if( !empty($actor_ids) ):
						if( !is_array($actor_ids) ){
							$actor_ids = explode(',', $actor_ids);
						}
						$args = array(
							'post_type' => "viem_actor",
							'post__in'	=> $actor_ids,
							'orderby'	=> 'post__in'
						);
						$actors = new WP_Query($args);
						
						if( $actors->have_posts() ){ $actor_count = $actors->post_count;
							?>
							<li><strong><?php echo esc_html__( 'Actor:', 'viem-ultimate' ); ?></strong>
									<?php
									$i = 0;
									while ( $actors->have_posts() ){ $actors->the_post(); $i++;
									?>
										<a href="<?php echo esc_url(get_the_permalink())?>" title="<?php echo esc_attr( get_the_title() )?>"><span class="actor-name"><?php the_title();?></span></a><?php echo ( $actor_count > $i ) ? ', ':'';?>
									<?php
									}
									?>
							</li>
							<?php
						}
						wp_reset_postdata();
						?>
					<?php
					endif;
					?>
				
				</ul>
				<?php 
				$movie_type = viem_get_post_meta('movie_type');
				$watch_movie = '';
				$movie_player_url = viem_get_post_meta('movie_player_url');
				if( empty($movie_player_url) ){
					switch ($movie_type){
						case 'video':
							$watch_movie = viem_get_post_meta('movie_video');
							break;
						case 'series':
							$watch_movie = viem_get_post_meta('movie_series');
							break;
						case 'playlist':
							$watch_movie = viem_get_post_meta('movie_playlist');
							break;
						default: 
							break;
					}
					$movie_player_url = get_permalink($watch_movie);
				}
				
				if( $movie_player_url ):?>
					<p><a class="btn-watch-movie" href="<?php echo esc_url($movie_player_url);?>" title="<?php echo get_the_title();?>"><i class="fa fa-play"></i><span><?php echo esc_html__('WATCH NOW');?></span></a></p>
				<?php
				endif;
				?>
			</div>
			<?php
		}
		
		public static function feature_movie(){
			if (check_admin_referer( 'dt-feature-movie' ) ) {
				$movie_id = absint( $_GET['movie_id'] );
		
				if ( 'viem_movie' === get_post_type( $movie_id ) ) {
					update_post_meta( $movie_id, '_dt_featured', get_post_meta( $movie_id, '_dt_featured', true )  ? 'no' : 'yes' );
		
					delete_transient( 'viem_featured_movies' );
				}
			}
		
			wp_safe_redirect( wp_get_referer() ? remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) : admin_url( 'edit.php?post_type=viem_movie' ) );
			die();
		}
		
		public function manage_edit_columns($columns){
			unset($columns['author'], $columns['comments'], $columns['categories'], $columns['date'] );
			$columns['viem_movie_genre'] = esc_html__( 'Genres', 'viem-ultimate' );
			$columns['author']   = esc_html__( 'Author', 'viem-ultimate' );
			$columns['comments'] = '<span class="vers comment-grey-bubble" title="' .
				esc_attr__( 'Comments', 'viem-ultimate' ) . '"><span class="screen-reader-text">' .
				__( 'Comments', 'viem-ultimate' ) . '</span></span>';
			$columns['date']   = esc_html__( 'Date', 'viem-ultimate' );
			return $columns;
		}
		
		public function  manage_custom_column($column, $post_id){
			global $post;
			switch ($column){
				case 'playlist' :
					if( viem_get_post_meta('movie_playlist_id') ):
						echo viem_posttype_viem_movie::get_playlist_list('','',', ','',true);
					else:
						echo '<span class="na">&ndash;</span>';
					endif;
					break;
						break;
				case 'viem_movie_genre':
					if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
						echo '<span class="na">&ndash;</span>';
					} else {
						$termlist = array();
						foreach ( $terms as $term ) {
							$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=viem_movie' ) . ' ">' . $term->name . '</a>';
						}
		
						echo implode( ', ', $termlist );
					}
					break;
				case 'featured':
					$featured = get_post_meta( $post->ID, '_dt_featured', true );
					$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=viem_feature_movie&movie_id=' . $post->ID ), 'dt-feature-movie' );
					echo '<a href="' . esc_url( $url ) . '" title="'. __( 'Toggle featured', 'viem-ultimate' ) . '">';
					if ('yes'===$featured ) {
						echo '<span class="dt-post-column-featured" title="' . esc_attr__( 'Yes', 'viem-ultimate' ) . '"><i class="dashicons dashicons-star-filled "></i></span>';
					} else {
						echo '<span class="dt-post-column-featured not-featured tips" title="' . esc_attr__( 'No', 'viem-ultimate' ) . '"><i class="dashicons dashicons-star-empty"></i></span>';
					}
					echo '</a>';
					break;
					
				default:
					break;
			}
		}
		
		public function template_loader($template){
			if(is_post_type_archive( 'viem_movie' ) || is_tax( 'viem_movie_genre' ) || is_tax( 'viem_movie_tag' ) ){
				$template = locate_template( 'archive-movie.php' );
			}
			
			return $template;
		}
		
		public static function viem_movie_count(){
			$movie_count = wp_count_posts('viem_movie');
			return $movie_count->publish;
		}
		
		public function pre_get_posts($q){
			if ( ! $q->is_main_query() ) {
				return;
			}
			if ( ! $q->is_post_type_archive( 'viem_movie' ) && ! $q->is_tax( get_object_taxonomies( 'viem_movie' ) ) ) {
				return;
			}
			$this->movie_query($q);
			$this->remove_movie_query();
		}
		
		public function movie_query($q){
			$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ) ) );
			$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ) ) );
			do_action( 'viem_movie_query', $q, $this );
		}
		
		public function remove_movie_query() {
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
		
		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
		
			return array_filter( apply_filters( 'viem_movie_query_meta_query', $meta_query, $this ) );
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
		
			return array_filter( apply_filters( 'viem_movie_query_tax_query', $tax_query, $this ) );
		}
		
		public static function result_count(){
			global $wp_query;
			?>
			<div class="movie-result-count">
				<?php
				$total    = $wp_query->found_posts;
				printf(__('We found %1$s available for you','viem-ultimate'),'<span>'.sprintf(_n( '%s Movie', '%s Movies',$total, 'viem-ultimate' ),$total).'</span>');
				?>
			</div>
					
			<?php
		}
		
		public static function get_current_filters() {
			if ( ! is_array( self::$_current_filters ) ) {
				self::$_current_filters = array();
				
				if ( $taxonomies = get_object_taxonomies( 'viem_movie' ) ) {
					foreach ( $taxonomies as $tax ) {
						$attribute    = str_replace('movie_', '', $tax);
						$taxonomy     = 'movie_'.$attribute;
						$filter_terms = ! empty( $_GET[ 'filter_' . $attribute ] ) ? explode( ',', wc_clean( $_GET[ 'filter_' . $attribute ] ) ) : array();
		
						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
							continue;
						}
		
						$query_type = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array( 'and', 'or' ) ) ? viem_clean( $_GET[ 'query_type_' . $attribute ] ) : '';
						self::$_current_filters[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding
						self::$_current_filters[ $taxonomy ]['query_type'] = $query_type ? $query_type : apply_filters( 'viem_default_movie_filter_query_type', 'and' );
					}
				}
			}
			return self::$_current_filters;
		}
		
		public static function get_main_search_query_sql() {
			global $wp_the_query, $wpdb;
	
			$args         = $wp_the_query->query_vars;
			$search_terms = isset( $args['search_terms'] ) ? $args['search_terms'] : array();
			$sql          = array();
	
			foreach ( $search_terms as $term ) {
				// Terms prefixed with '-' should be excluded.
				$include = '-' !== substr( $term, 0, 1 );
	
				if ( $include ) {
					$like_op  = 'LIKE';
					$andor_op = 'OR';
				} else {
					$like_op  = 'NOT LIKE';
					$andor_op = 'AND';
					$term     = substr( $term, 1 );
				}
	
				$like  = '%' . $wpdb->esc_like( $term ) . '%';
				$sql[] = $wpdb->prepare( "(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", $like, $like, $like );
			}
	
			if ( ! empty( $sql ) && ! is_user_logged_in() ) {
				$sql[] = "($wpdb->posts.post_password = '')";
			}
	
			return implode( ' AND ', $sql );
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
		
			if ( ! empty( $args['viem_movie_genre'] ) ) {
				$tax_query[ 'viem_movie_genre' ] = array(
					'taxonomy' => 'viem_movie_genre',
					'terms'    => array( $args['viem_movie_genre'] ),
					'field'    => 'slug',
				);
			}
		
			return $tax_query;
		}
		
	}
	viem_posttype_viem_movie::instance();
}