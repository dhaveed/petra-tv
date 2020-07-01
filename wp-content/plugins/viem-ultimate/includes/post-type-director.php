<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_viem_director' ) ) {

	class viem_posttype_viem_director {

		protected static $_current_filters;

		public function __construct() {
			add_action( 'init', array( &$this, 'register_post_type' ) );
			add_action( 'template_redirect', array($this,'stop_redirect'), 0);
			
			if(is_admin()){
				add_action( 'viem_add_meta_boxes', array(&$this,'add_meta_boxes'), 30 );
				add_action( 'viem_save_meta_boxes', array(&$this,'save_meta_boxes'), 30, 2) ;
				//Admin Columns
				add_filter( 'manage_edit-viem_director_columns', array($this,'manage_edit_columns') );
				add_filter( 'manage_viem_director_posts_custom_column',  array($this,'manage_custom_column'),10, 2 );
				
			}else{
				add_filter( 'template_include', array( $this, 'template_loader' ) );
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
				add_action( 'wp', array( $this, 'remove_director_query' ) );
				add_action('viem_director_info', array(__class__,'single_director_info') );
				add_action('viem_director_after_content', array(__class__,'get_related_video'), 10 );
				//add_action('viem_director_after_content', array(__class__,'get_related_movie'), 20 );
			}
			/*remove WTI Like Post */
			remove_filter('the_content', 'PutWtiLikePost');
		}

		public function register_post_type() {
			if ( post_type_exists( 'viem_director' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_'.basename(get_template_directory()) . '_permalinks' );
			$director_permalink = empty( $permalinks['viem_director_base'] ) ? _x( 'director', 'slug', 'viem-ultimate' ) : $permalinks['viem_director_base'];
			
			register_post_type( 
				'viem_director',
				apply_filters( 
					'viem_register_post_type_dt_director', 
					array( 
						'labels' => array( 
							'name' => esc_html__( 'Director', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Director', 'viem-ultimate' ), 
							'menu_name' => _x( 'Director', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add Director', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add Director', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Director', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Director', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Director', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Director', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Director', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Director found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Director found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Director', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'menu_position' => 11, 
						'menu_icon' => 'dashicons-businessman',
						'exclude_from_search' => false, 
						'rewrite' => $director_permalink ? array(
							'slug' => untrailingslashit( $director_permalink ), 
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_director_page_id = viem_get_theme_option( 'main-director-page') ) &&
							 get_post( $main_director_page_id ) ? get_page_uri( $main_director_page_id ) : 'director', 
							'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments') ) ) );
		}
		
		public function stop_redirect(){
			if ( is_singular('viem_director') ) {
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
		public static function single_director_info(){
			if( !is_singular('viem_director') )
				return;
			?>
			<div class="single_director_overview">
				<?php $birthnames = viem_get_post_meta('birth-name');
				if( !empty($birthnames) ):
				?>
				<div class="director-info"><span><?php echo esc_html__( 'Birth Name:', 'viem-ultimate' );?></span> <?php echo esc_html($birthnames);?></div>
				<?php endif; ?>
				
				<?php $born = viem_get_post_meta('born');
				$address = viem_get_post_meta('address');
				if( !empty($born) ):
				?>
				<div class="director-info"><span><?php echo esc_html__( 'Born:', 'viem-ultimate' );?></span> <?php echo date_i18n('M d, Y', strtotime($born) );?>
					<?php echo ( $address ) ? esc_html__( ' in ', 'viem-ultimate' ) . esc_html($address) : '';?>
				</div>
				<?php endif; ?>
				
				<?php $live_in = viem_get_post_meta('live_in');
				if( !empty($live_in) ):
				?>
				<div class="director-info"><span><?php echo esc_html__( 'Lives in:', 'viem-ultimate' );?></span> <?php echo esc_html($live_in);?></div>
				<?php endif; ?>
				
				<?php $height = viem_get_post_meta('height');
				if( !empty($height) ):
				?>
				<div class="director-info"><span><?php echo esc_html__( 'Height:', 'viem-ultimate' );?></span> <?php echo esc_html($height);?></div>
				<?php endif; ?>
			</div>
			<?php
		}
		
		public function add_meta_boxes(){
			$meta_box = array (
				'id' => 'dt-metabox-viem_director',
				'title' => esc_html__( 'Director Info', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_director',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Birth Name:', 'viem-ultimate' ),
						'name' => 'birth-name',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => ''
					),
					array (
						'label' => esc_html__( 'Born:', 'viem-ultimate' ),
						'name' => 'born',
						'type' => 'datepicker',
						'timestamp'=> true,
					),
					array (
						'label' => esc_html__( 'Address:', 'viem-ultimate' ),
						'name' => 'address',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => ''
					),
					array (
						'label' => esc_html__( 'Lives in:', 'viem-ultimate' ),
						'name' => 'live_in',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => ''
					),
					array (
						'label' => esc_html__( 'Height:', 'viem-ultimate' ),
						'name' => 'height',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => '',
						'description' => ''
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
		
		}
		
		public function save_meta_boxes($post_id, $post){
			if('viem_director'!==get_post_type($post))
				return;
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
			//if(is_post_type_archive( 'viem_director' ) ){
				//$template = locate_template( 'archive-director.php' );
			//}
			return $template;
		}
		
		public function pre_get_posts($q){
			if ( ! $q->is_main_query() ) {
				return;
			}
			if ( ! $q->is_post_type_archive( 'viem_director' ) && ! $q->is_tax( get_object_taxonomies( 'viem_director' ) ) ) {
				return;
			}
			$this->director_query($q);
			$this->remove_director_query();
		}
		
		public function director_query($q){
			$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ) ) );
			$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ) ) );
			do_action( 'viem_director_query', $q, $this );
		}
		
		public function remove_director_query() {
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
		
		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
			
			return array_filter( apply_filters( 'viem_director_query_meta_query', $meta_query, $this ) );
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
		
			return array_filter( apply_filters( 'viem_director_query_tax_query', $tax_query, $this ) );
		}
		
		public static function get_current_filters() {
			if ( ! is_array( self::$_current_filters ) ) {
				self::$_current_filters = array();
		
				if ( $taxonomies = get_object_taxonomies( 'viem_director' ) ) {
					foreach ( $taxonomies as $tax ) {
						$attribute    = str_replace('director_', '', $tax);
						$taxonomy     = 'director_'.$attribute;
						$filter_terms = ! empty( $_GET[ 'filter_' . $attribute ] ) ? explode( ',', wc_clean( $_GET[ 'filter_' . $attribute ] ) ) : array();
		
						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
							continue;
						}
		
						$query_type = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array( 'and', 'or' ) ) ? viem_clean( $_GET[ 'query_type_' . $attribute ] ) : '';
						self::$_current_filters[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding
						self::$_current_filters[ $taxonomy ]['query_type'] = $query_type ? $query_type : apply_filters( 'viem_default_director_filter_query_type', 'and' );
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
		
			if ( ! empty( $args['director_cat'] ) ) {
				$tax_query[ 'director_cat' ] = array(
					'taxonomy' => 'director_cat',
					'terms'    => array( $args['director_cat'] ),
					'field'    => 'slug',
				);
			}
		
			if ( ! empty( $args['director_level'] ) ) {
				$tax_query[ 'director_level' ] = array(
					'taxonomy' => 'director_level',
					'terms'    => array( $args['director_level'] ),
					'field'    => 'slug',
				);
			}
		
			return $tax_query;
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
						'key' => '_dt_video_director_id',
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
		
		// Video
		public static function get_related_video(){
			$post_id = get_the_ID();
			if( !is_singular('viem_director') )
				return;
			
			if( is_front_page() || is_home()) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}
			
			$args = array(
				'post_type' => 'viem_video',
				'posts_per_page' => get_option('posts_per_page'),
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby' => 'date',
				'post__not_in' => array($post_id),
				'paged'			  => $paged,
				'meta_query' => array(
					array(
						'key' => '_dt_video_director',
						'value' => $post_id,
						'compare' => 'LIKE',
					),
				)
			);
			
			$related_video = new WP_Query($args);
			
			if( $related_video->have_posts() ): 
				$style = 'grid'; // list || grid || masonry
				$pagination = viem_get_theme_option('videos-pagination', 'loadmore'); // wp_pagenavi || loadmore || infinite_scroll
				$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
				$img_size = viem_get_theme_option('video-image-size', 'viem_360_235_crop');
				$columns = 5;
				
				$itemSelector = '';
				$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
				$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
				?>
				<div class="viem_directors-video">
				<h2><?php echo esc_html__('VIDEOS') ?></h2><hr/>
					<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.$columns.'"':''?>>
						<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
						<?php
						$post_class = '';
						$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
						$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
						// Start the Loop.
						while ( $related_video->have_posts() ){ $related_video->the_post();
						?>
						<?php
								viem_dt_get_template("content-{$style}.php", array(
									'post_class' => $post_class,
									'columns' => $columns,
									'img_size'		=> $img_size,
									'show_excerpt' => 'hide',
								),
								'template-parts/loop-video', 'template-parts/loop-video'
								);
							?>
						<?php
						}
						?>
						</div>
						<?php 
						// Previous/next post navigation.
						// this paging nav should be outside .posts-wrap
						$paginate_args = array();
						switch ($pagination){
							case 'loadmore':
								viem_dt_paging_nav_ajax($loadmore_text, $related_video);
								$paginate_args = array('show_all'=>true);
								break;
							case 'infinite_scroll':
								$paginate_args = array('show_all'=>true);
								break;
						}
						if($pagination != 'no') viem_paginate_links($paginate_args, $related_video);
						?>
					</div>
				</div>
			<?php
			endif;
			wp_reset_postdata();
		}
		// Fimmography
		public static function get_related_movie(){
			$post_id = get_the_ID();
			if( !is_singular('viem_director') )
				return;
			
			if( is_front_page() || is_home()) {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : ( ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1 );
			} else {
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
			}
			
			$args = array(
				'post_type' => 'viem_movie',
				'posts_per_page' => get_option('posts_per_page'),
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'orderby' => 'date',
				'post__not_in' => array($post_id),
				'paged'			  => $paged,
				'meta_query' => array(
					array(
						'key' => '_dt_movie_director',
						'value' => $post_id,
						'compare' => 'LIKE',
					),
				)
			);
			
			$related_movie = new WP_Query($args);
			if( $related_movie->have_posts() ): 
			
			$style = 'grid'; // list || grid || masonry
			$pagination = viem_get_theme_option('movies-pagination', 'loadmore'); // wp_pagenavi || loadmore || infinite_scroll
			$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem'));
			$img_size = viem_get_theme_option('movies-image-size', 'viem-movie-360x460');
			$columns = viem_get_theme_option('movies-per-page', 5);
			
			$itemSelector = '';
			$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_movie.infinite-scroll-item':'');
			$itemSelector .= (($pagination === 'loadmore') ? '.viem_movie.loadmore-item':'');
			$post_class = ' v-grid-item ';
			$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
			$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
			?>
			<div class="viem_directors-movie">
				<h2><?php echo esc_html__('FILMOGRAPHY') ?></h2><hr/>
				<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="viem-movies posts <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.esc_attr($columns).'"':''?>>
					<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?> <?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo' v-grid-list cols_'.$columns; ?>">
					<?php
					// Start the Loop.
					while ( $related_movie->have_posts() ) : $related_movie->the_post();?>
						<?php
						if($style == 'masonry')
							$post_class.=' masonry-item';
						?>
						<?php
							viem_dt_get_template("content-{$style}.php", array(
								'post_class' => $post_class,
								'columns' => $columns,
								'img_size' => $img_size,
								'type' => $style
							),
							'template-parts/loop-movie', 'template-parts/loop-movie'
							);
						?>
					<?php
					endwhile;
					?>
					</div>
					<?php
					// Previous/next post navigation.
					// this paging nav should be outside .posts-wrap
					$paginate_args = array();
					switch ($pagination){
						case 'loadmore':
							viem_dt_paging_nav_ajax($loadmore_text, $related_movie);
							$paginate_args = array('show_all'=>true);
							break;
						case 'infinite_scroll':
							$paginate_args = array('show_all'=>true);
							break;
					}
					viem_paginate_links($paginate_args, $related_movie);
					?>
				</div>
			</div>
			<?php
			endif;
			wp_reset_postdata();
		}
	}
	new viem_posttype_viem_director();
}