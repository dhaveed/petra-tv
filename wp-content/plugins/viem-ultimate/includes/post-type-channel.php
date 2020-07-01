<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_channel' ) ) {

	class viem_posttype_channel {

		protected static $_current_filters;
		protected static $subscribe_text;
		protected static $unsubscribe_text;
		protected static $subscribe_removed;
		protected static $subscribed_text;
		protected static $subscribed_added;

		public function __construct() {
			
			self::$subscribe_text = esc_html__('Subscribe','viem-ultimate');
			self::$unsubscribe_text = esc_html__('Unsubscribe','viem-ultimate');
			self::$subscribe_removed = esc_html__('Subscription removed','viem-ultimate');
			self::$subscribed_text = esc_html__('Subscribed','viem-ultimate');
			self::$subscribed_added = esc_html__('Subscription added','viem-ultimate');
			
			add_action( 'init', array( &$this, 'register_post_type' ) );
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'template_redirect', array($this,'stop_redirect'), 0);
			
			// Rating posts
			// add_filter( 'preprocess_comment', array( __CLASS__, 'check_comment_rating' ), 0 );
			// add_action( 'comment_post', array( __CLASS__, 'add_comment_rating' ), 1 );
			// add_action( 'comment_moderation_recipients', array( __CLASS__, 'comment_moderation_recipients' ), 10, 2
			// );
			// Clear transients
			// add_action( 'wp_update_comment_count', array( __CLASS__, 'clear_transients' ) );
			// Delete comments count cache whenever there is a new comment or a comment status changes
			// add_action( 'wp_insert_comment', array( __CLASS__, 'delete_comments_count_cache' ) );
			// add_action( 'wp_set_comment_status', array( __CLASS__, 'delete_comments_count_cache' ) );
			
			// if( viem_get_theme_option('channel_enable_review_rating', '1') == '1' )
			// add_filter( 'comments_template', array( __CLASS__, 'comments_template_loader' ) );
			
			if ( is_admin() ) {
				add_action( 'viem_add_meta_boxes', array( &$this, 'add_meta_boxes' ), 30 );
				add_action( 'viem_save_meta_boxes', array( &$this, 'save_meta_boxes' ), 30, 2 );
				// Ajax Featured
				add_action( 'wp_ajax_viem_feature_channel', array( __CLASS__, 'feature_channel' ) );
				// Admin Columns
				add_filter( 'manage_edit-viem_channel_columns', array( $this, 'manage_edit_columns' ) );
				add_filter( 
					'manage_viem_channel_posts_custom_column', 
					array( $this, 'manage_custom_column' ), 
					10, 
					2 );
			} else {
				add_filter( 'template_include', array( $this, 'template_loader' ) );
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
				add_action( 'wp', array( $this, 'remove_event_query' ) );
				add_action( 'viem_channel_details', array( __CLASS__, 'viem_single_channel_details' ) );
				add_action( 'viem_channel_social_account', array( __CLASS__, 'viem_channel_social_account' ) );
			}
		}
		
		public function init(){
			$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			// Ajax subscribe
			wp_enqueue_script('viem-chanel-subscription', get_template_directory_uri() .'/assets/js/ajax-subscription'.$suffix .'.js', array('jquery'));
			wp_localize_script( 'viem-chanel-subscription', 'viem_ajax_channel_subscription_object', array(
			'ajaxurl' => esc_js( admin_url('admin-ajax.php') ),
			));
			add_action( 'wp_ajax_jcajaxsubscription', array(&$this,'ajax_chanel_subscription') );
			add_action( 'wp_ajax_nopriv_jcajaxsubscription', array($this, 'ajax_chanel_subscription'));
		}

		public function stop_redirect(){
			if ( is_singular('viem_channel') ) {
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
		
		public function register_post_type() {
			if ( post_type_exists( 'viem_channel' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_' . basename( get_template_directory() ) . '_permalinks' );
			$channel_permalink = empty( $permalinks['viem_channel_base'] ) ? _x( 'channel', 'slug', 'viem-ultimate' ) : $permalinks['viem_channel_base'];
			
			register_post_type( 
				'viem_channel', 
				apply_filters( 
					'viem_register_post_type_dt_channel', 
					array( 
						'labels' => array( 
							'name' => esc_html__( 'Channels', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Channel', 'viem-ultimate' ), 
							'menu_name' => _x( 'Channel', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add New Channel', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add New Channel', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Channel', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Channel', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Channel', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Channel', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Channel', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Channel found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Channel found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Even', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'show_in_menu' => 'edit.php?post_type=viem_video', 
						'menu_position' => 13, 
						'menu_icon' => 'dashicons-video-alt3', 
						'exclude_from_search' => false, 
						'rewrite' => $channel_permalink ? array( 
							'slug' => untrailingslashit( $channel_permalink ), 
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_channel_page_id = viem_get_theme_option( 'main-channel-page' ) ) &&
							 get_post( $main_channel_page_id ) ? get_page_uri( $main_channel_page_id ) : 'channels', 
							'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments' ) ) ) );
		}

		public function add_meta_boxes() {
			$meta_box = array( 
				'id' => 'dt-metabox-viem_channel', 
				'title' => esc_html__( 'Channel Settings', 'viem-ultimate' ), 
				'description' => '', 
				'post_type' => 'viem_channel', 
				'context' => 'normal', 
				'priority' => 'high', 
				'fields' => array( 
					array( 
						'label' => esc_html__( 'Add videos, playlists to Channel', 'viem-ultimate' ), 
						'description' => '', 
						'name' => 'channel_type', 
						'type' => 'select', 
						'value' => 'manually', 
						'description' => __( 
							'You can add mixed videos and playlists manually one by one to your channel Or form YouTube channel.', 
							'viem-ultimate' ), 
						'options' => array( 
							'' => esc_html__( 'Manually', 'viem-ultimate' ), 
							'youtube_channel' => esc_html__( 'YouTube channel', 'viem-ultimate' ) ) ), 
					array( 
						'label' => esc_html__( 'YouTube Channel ID', 'viem-ultimate' ), 
						'name' => 'youtube_channel_id', 
						'type' => 'text', 
						'width' => '300px', 
						'placeholder' => 'UCHqaLr9a9M7g9QN6xem9HcQ', 
						'dependency' => array( 'field' => 'channel_type', 'value' => array( 'youtube_channel' ) ), 
						'description' => esc_html__( 
							'Automatic youtube playlist ID. Is the last part of the URL https://www.youtube.com/channel/UCHqaLr9a9M7g9QN6xem9HcQ. youtubeChannelID: "UCHqaLr9a9M7g9QN6xem9HcQ"', 
							'viem-ultimate' ) ),
					array( 
						'name' => 'channel_icon', 
						'type' => 'text',
						'placeholder' => 'fa fa-play-circle-o',
						'label' => esc_html__( 'Channel Icon', 'viem-ultimate' ) ),

							 ) );
			add_meta_box( 
				$meta_box['id'], 
				$meta_box['title'], 
				'dawnthemes_render_meta_boxes', 
				$meta_box['post_type'], 
				$meta_box['context'], 
				$meta_box['priority'], 
				$meta_box );
			
			$meta_box = array( 
				'id' => 'dt-metabox-viem_channel_social', 
				'title' => esc_html__( 'Social Account', 'viem-ultimate' ), 
				'description' => '', 
				'post_type' => 'viem_channel', 
				'context' => 'normal', 
				'priority' => 'high', 
				'fields' => array( 
					array( 
						'name' => 'facebook-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Facebook URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'twitter-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Twitter URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'google-plus-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Google+ URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'youtube-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Youtube URL', 'viem-ultimate' ) ), 
					array( 'name' => 'vimeo-url', 'type' => 'text', 'label' => esc_html__( 'Vimeo URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'pinterest-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Pinterest URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'linkedin-url', 
						'type' => 'text', 
						'label' => esc_html__( 'LinkedIn URL', 'viem-ultimate' ) ), 
					array( 'name' => 'rss-url', 'type' => 'text', 'label' => esc_html__( 'RSS URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'instagram-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Instagram URL', 'viem-ultimate' ) ), 
					array( 'name' => 'github-url', 'type' => 'text', 'label' => esc_html__( 'GitHub URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'behance-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Behance URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'stack-exchange-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Stack Exchange URL', 'viem-ultimate' ) ), 
					array( 'name' => 'tumblr-url', 'type' => 'text', 'label' => esc_html__( 'Tumblr URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'soundcloud-url', 
						'type' => 'text', 
						'label' => esc_html__( 'SoundCloud URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'dribbble-url', 
						'type' => 'text', 
						'label' => esc_html__( 'Dribbble URL', 'viem-ultimate' ) ), 
					array( 
						'name' => 'social-target', 
						'type' => 'select', 
						'label' => esc_html__( 'Open Social Link in new tab', 'viem-ultimate' ), 
						'description' => esc_html__( 
							'Open link in new tab?', 
							'viem-ultimate' ), 
						'options' => array( 
							'_blank' => esc_html__( 'Yes', 'viem-ultimate' ), 
							'' => esc_html__( 'No', 'viem-ultimate' ) ), 
						'value' => '_blank' ) ) );
			add_meta_box( 
				$meta_box['id'], 
				$meta_box['title'], 
				'dawnthemes_render_meta_boxes', 
				$meta_box['post_type'], 
				$meta_box['context'], 
				$meta_box['priority'], 
				$meta_box );
		}

		public function save_meta_boxes( $post_id, $post ) {
			if ( 'viem_channel' !== get_post_type( $post ) )
				return;
		}

		public static function feature_channel() {
			if ( check_admin_referer( 'dt-feature-channel' ) ) {
				$channel_id = absint( $_GET['channel_id'] );
				
				if ( 'viem_channel' === get_post_type( $channel_id ) ) {
					update_post_meta( 
						$channel_id, 
						'_viem_featured', 
						get_post_meta( $channel_id, '_viem_featured', true ) ? 'no' : 'yes' );
					
					delete_transient( 'viem_featured_channels' );
				}
			}
			
			wp_safe_redirect( 
				wp_get_referer() ? remove_query_arg( 
					array( 'trashed', 'untrashed', 'deleted', 'ids' ), 
					wp_get_referer() ) : admin_url( 'edit.php?post_type=viem_channel' ) );
			die();
		}

		public function manage_edit_columns( $columns ) {
			// unset($columns['comments'], $columns['categories'], $columns['date'] );
			$columns = array();
			$columns['cb'] = '<input type="checkbox" />';
			$columns['thumb'] = esc_html__( 'Image', 'viem-ultimate' );
			$columns['title'] = esc_html__( 'Name', 'viem-ultimate' );
			$columns['author'] = esc_html__( 'Author', 'viem-ultimate' );
			$columns['comments'] = '<span class="vers comment-grey-bubble" title="' .
				 esc_attr__( 'Comments', 'viem-ultimate' ) . '"><span class="screen-reader-text">' .
				 __( 'Comments', 'viem-ultimate' ) . '</span></span>';
			$columns['date'] = esc_html__( 'Date', 'viem-ultimate' );
			return $columns;
		}

		public function manage_custom_column( $column, $post_id ) {
			global $post;
			switch ( $column ) {
				case 'thumb' :
					if ( has_post_thumbnail( $post->ID ) ) {
						echo get_the_post_thumbnail( $post->ID, 'thumbnail' );
					} else {
						echo '<span class="na">&ndash;</span>';
					}
					break;
				default:
					break;
			}
		}

		public function template_loader( $template ) {
			if ( is_post_type_archive( 'viem_channel' ) ) {
				$template = locate_template( 'archive-channel.php' );
			}
			return $template;
		}

		public function pre_get_posts( $q ) {
			if ( ! $q->is_main_query() ) {
				return;
			}
			if ( ! $q->is_post_type_archive( 'viem_channel' ) &&
				 ! $q->is_tax( get_object_taxonomies( 'viem_channel' ) ) ) {
				return;
			}
			$this->channel_query( $q );
			$this->remove_event_query();
		}

		public function remove_event_query() {
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}

		public function channel_query( $q ) {
			$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ) ) );
			$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ) ) );
			do_action( 'viem_channel_query', $q, $this );
		}

		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
			
			return array_filter( apply_filters( 'viem_channel_query_meta_query', $meta_query, $this ) );
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
						'field' => 'slug', 
						'terms' => $data['terms'], 
						'operator' => 'and' === $data['query_type'] ? 'AND' : 'IN', 
						'include_children' => false );
				}
			}
			
			return array_filter( apply_filters( 'viem_channel_query_tax_query', $tax_query, $this ) );
		}

		public static function get_current_filters() {
			if ( ! is_array( self::$_current_filters ) ) {
				self::$_current_filters = array();
				
				if ( $taxonomies = get_object_taxonomies( 'viem_channel' ) ) {
					foreach ( $taxonomies as $tax ) {
						$attribute = str_replace( 'channel_', '', $tax );
						$taxonomy = 'channel_' . $attribute;
						$filter_terms = ! empty( $_GET['filter_' . $attribute] ) ? explode( 
							',', 
							wc_clean( $_GET['filter_' . $attribute] ) ) : array();
						
						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
							continue;
						}
						
						$query_type = ! empty( $_GET['query_type_' . $attribute] ) &&
							 in_array( $_GET['query_type_' . $attribute], array( 'and', 'or' ) ) ? viem_clean( 
								$_GET['query_type_' . $attribute] ) : '';
						self::$_current_filters[$taxonomy]['terms'] = array_map( 'sanitize_title', $filter_terms ); // Ensures
						                                                                                                   // correct
						                                                                                                   // encoding
						self::$_current_filters[$taxonomy]['query_type'] = $query_type ? $query_type : apply_filters( 
							'viem_default_channel_filter_query_type', 
							'and' );
					}
				}
			}
			return self::$_current_filters;
		}

		public static function get_main_meta_query() {
			global $wp_the_query;
			
			$args = $wp_the_query->query_vars;
			$meta_query = isset( $args['meta_query'] ) ? $args['meta_query'] : array();
			
			return $meta_query;
		}

		public static function get_main_search_query_sql() {
			global $wp_the_query, $wpdb;
			
			$args = $wp_the_query->query_vars;
			$search_terms = isset( $args['search_terms'] ) ? $args['search_terms'] : array();
			$sql = array();
			
			foreach ( $search_terms as $term ) {
				// Terms prefixed with '-' should be excluded.
				$include = '-' !== substr( $term, 0, 1 );
				
				if ( $include ) {
					$like_op = 'LIKE';
					$andor_op = 'OR';
				} else {
					$like_op = 'NOT LIKE';
					$andor_op = 'AND';
					$term = substr( $term, 1 );
				}
				
				$like = '%' . $wpdb->esc_like( $term ) . '%';
				$sql[] = $wpdb->prepare( 
					"(($wpdb->posts.post_title $like_op %s) $andor_op ($wpdb->posts.post_excerpt $like_op %s) $andor_op ($wpdb->posts.post_content $like_op %s))", 
					$like, 
					$like, 
					$like );
			}
			
			if ( ! empty( $sql ) && ! is_user_logged_in() ) {
				$sql[] = "($wpdb->posts.post_password = '')";
			}
			
			return implode( ' AND ', $sql );
		}

		public static function get_main_tax_query() {
			global $wp_the_query;
			
			$args = $wp_the_query->query_vars;
			$tax_query = isset( $args['tax_query'] ) ? $args['tax_query'] : array();
			
			if ( ! empty( $args['taxonomy'] ) && ! empty( $args['term'] ) ) {
				$tax_query[$args['taxonomy']] = array( 
					'taxonomy' => $args['taxonomy'], 
					'terms' => array( $args['term'] ), 
					'field' => 'slug' );
			}
			
			return $tax_query;
		}

		public static function result_count() {
			global $wp_query;
			?>
			<div class="channel-result-count">
				<?php
			$total = $wp_query->found_posts;
			printf( 
				__( 'We found %1$s available for you', 'viem-ultimate' ), 
				'<span>' . sprintf( _n( '%s channel', '%s channels', $total, 'viem-ultimate' ), $total ) . '</span>' );
			?>
			</div>

		<?php
		}

		public static function toolbar() {
			if ( viem_get_theme_option( 'channel-show-toobar', 1 ) ) {
				?>
			<div class="channel-toolbar clearfix">
				<?php do_action('viem_channel_loop_toobar');?>
			</div>
		<?php
			}
		}

		public static function check_comment_rating( $comment_data ) {
			// If posting a comment (not trackback etc) and not logged in
			if ( ! is_admin() && 'viem_channel' === get_post_type( $_POST['comment_post_ID'] ) &&
				 empty( $_POST['rating'] ) && '' === $comment_data['comment_type'] && self::is_enable_review() ) {
				wp_die( __( 'Please rate the channel.', 'viem-ultimate' ) );
				exit();
			}
			
			return $comment_data;
		}

		public static function add_comment_rating( $comment_id ) {
			if ( isset( $_POST['rating'] ) && 'viem_channel' === get_post_type( $_POST['comment_post_ID'] ) ) {
				if ( ! $_POST['rating'] || $_POST['rating'] > 5 || $_POST['rating'] < 0 ) {
					return;
				}
				add_comment_meta( $comment_id, 'rating', (int) esc_attr( $_POST['rating'] ), true );
			}
		}

		public static function comment_moderation_recipients( $emails, $comment_id ) {
			$comment = get_comment( $comment_id );
			
			if ( $comment && 'viem_channel' === get_post_type( $comment->comment_post_ID ) ) {
				$emails = array( viem_get_theme_option( 'admin_email' ) );
			}
			
			return $emails;
		}

		public static function delete_comments_count_cache() {
			delete_transient( 'viem_channel_count_comments' );
		}
		
		public static function get_video_count($echo = false, $channel_id = ''){
			if( $channel_id == '' )
				$channel_id = get_the_ID();
			
			$args = array(
				'post_type' => 'viem_video',
				'posts_per_page' => -1,
				'post_status' => 'publish',
				'ignore_sticky_posts' => 1,
				'post__not_in' => array($channel_id),
				'meta_query' => array(
					array(
						'key' => '_dt_video_channel_id',
						'value' => $channel_id,
						'compare' => 'LIKE',
					),
				)
			);
			$v = new WP_Query($args);
			$count = $v->post_count;
			
			if( $echo == true ){
				echo ( $count == 1 ) ? sprintf( esc_html__('%s Video', 'viem-ultimate'), $count ) : sprintf( esc_html__('%s Videos', 'viem-ultimate'), $count );
			}else{
				return apply_filters( 'viem_channel_video_count', $count );
			}
		}

		public static function comments_template_loader( $template ) {
			if ( get_post_type() !== 'viem_channel' ) {
				return $template;
			}
			
			$check_dirs = array( 
				trailingslashit( get_stylesheet_directory() ), 
				trailingslashit( get_template_directory() ) );
			
			foreach ( $check_dirs as $dir ) {
				if ( file_exists( trailingslashit( $dir ) . 'comments-channel-reviews.php' ) ) {
					return trailingslashit( $dir ) . 'comments-channel-reviews.php';
				}
			}
		}

		public static function rating() {
			if ( viem_get_theme_option( 'channel_enable_review_rating', '1' ) == '0' )
				return;
			
			$rating_count = self::get_rating_count();
			$review_count = self::get_review_count();
			$average = self::get_average_rating();
			
			if ( $rating_count > 0 ) :
				?>

<div class="viem-channel-rating" itemprop="aggregateRating"
	itemscope itemtype="http://schema.org/AggregateRating">
	<span class="rating-lable"><?php echo esc_html__( 'Rate:', 'viem-ultimate' );?></span>
	<div class="star-rating"
		title="<?php printf( __( 'Rated %s out of 5', 'viem-ultimate' ), $average ); ?>">
		<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%"> <strong
			itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( __( 'out of %s5%s', 'viem-ultimate' ), '<span itemprop="bestRating">', '</span>' ); ?>
							<?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'viem-ultimate' ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
						</span>
	</div>
					<?php if ( comments_open() ) : ?><a
		href="#viem_channel_reviews"
		class="viem-channel-review-link" rel="nofollow">(<?php printf( _n( '%s review', '%s reviews', $review_count, 'viem-ultimate' ), '<span itemprop="reviewCount" class="count">' . $review_count . '</span>' ); ?>)</a><?php endif ?>
				</div>


			<?php endif;
		}

		/**
		 * Get the average rating of channel. This is calculated once and stored in postmeta.
		 * @return string
		 */
		public static function get_average_rating() {
			$channel_id = get_the_ID();
			if ( ! metadata_exists( 'post', $channel_id, '_viem_channel_average_rating' ) ) {
				self::sync_average_rating( $channel_id );
			}
			
			return (string) floatval( get_post_meta( $channel_id, '_viem_channel_average_rating', true ) );
		}

		/**
		 * Get the total amount (COUNT) of ratings.
		 * @param  int $value Optional. Rating value to get the count for. By default returns the count of all rating values.
		 * @return int
		 */
		public static function get_rating_count( $value = null ) {
			// No meta data? Do the calculation
			global $wpdb;
			$channel_id = get_the_ID();
			if ( ! metadata_exists( 'post', $channel_id, '_viem_channel_rating_count' ) ) {
				self::sync_rating_count( $channel_id );
			}
			
			$counts = array_filter( (array) get_post_meta( $channel_id, '_viem_channel_rating_count', true ) );
			
			if ( is_null( $value ) ) {
				return array_sum( $counts );
			} else {
				return isset( $counts[$value] ) ? $counts[$value] : 0;
			}
		}

		/**
		 * Sync product rating. Can be called statically.
		 * @param  int $post_id
		 */
		public static function sync_average_rating( $post_id ) {
			if ( ! metadata_exists( 'post', $post_id, '_viem_channel_rating_count' ) ) {
				self::sync_rating_count( $post_id );
			}
			
			$count = array_sum( (array) get_post_meta( $post_id, '_viem_channel_rating_count', true ) );
			
			if ( $count ) {
				global $wpdb;
				
				$ratings = $wpdb->get_var( 
					$wpdb->prepare( 
						"
					SELECT SUM(meta_value) FROM $wpdb->commentmeta
					LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
					WHERE meta_key = 'rating'
					AND comment_post_ID = %d
					AND comment_approved = '1'
					AND meta_value > 0
					", 
						$post_id ) );
				$average = number_format( $ratings / $count, 2, '.', '' );
			} else {
				$average = 0;
			}
			update_post_meta( $post_id, '_viem_channel_average_rating', $average );
		}

		public static function get_review_count() {
			global $wpdb;
			$channel_id = get_the_ID();
			if ( ! metadata_exists( 'post', $channel_id, '_viem_channel_review_count' ) ) {
				$count = $wpdb->get_var( 
					$wpdb->prepare( 
						"
					SELECT COUNT(*) FROM $wpdb->comments
					WHERE comment_parent = 0
					AND comment_post_ID = %d
					AND comment_approved = '1'
					", 
						$channel_id ) );
				
				update_post_meta( $channel_id, '_viem_channel_review_count', $count );
			} else {
				$count = get_post_meta( $channel_id, '_viem_channel_review_count', true );
			}
			
			return apply_filters( 'viem_channel_review_count', $count );
		}

		/**
		 * Sync product rating count. Can be called statically.
		 * @param  int $post_id
		 */
		public static function sync_rating_count( $post_id ) {
			global $wpdb;
			
			$counts = array();
			$raw_counts = $wpdb->get_results(
					"
				SELECT meta_value, COUNT( * ) as meta_value_count FROM $wpdb->commentmeta
				LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
				WHERE meta_key = 'rating'
				AND comment_post_ID = %d
				AND comment_approved = '1'
				AND meta_value > 0
				GROUP BY meta_value
				", 
					$post_id );
			
			foreach ( $raw_counts as $count ) {
				$counts[$count->meta_value] = $count->meta_value_count;
			}
			
			update_post_meta( $post_id, '_viem_channel_rating_count', $counts );
		}

		public static function is_enable_review() {
			return (bool) apply_filters( 'viem_channel_enable_review_rating', true );
		}

		public static function clear_transients( $post_id ) {
			delete_post_meta( $post_id, '_viem_channel_average_rating' );
			delete_post_meta( $post_id, '_viem_channel_rating_count' );
			delete_post_meta( $post_id, '_viem_channel_review_count' );
		}

		public static function get_review_rating_html() {
			global $comment;
			ob_start();
			if ( ! empty( $comment ) )
				$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
			else
				$rating = 0;
			if ( $rating && self::is_enable_review() ) {
				?>
<div itemprop="reviewRating" itemscope
	itemtype="http://schema.org/Rating" class="star-rating"
	title="<?php echo sprintf( esc_attr__( 'Rated %d out of 5', 'viem-ultimate' ), esc_attr( $rating ) ) ?>">
	<span style="width:<?php echo ( esc_attr( $rating ) / 5 ) * 100; ?>%"><strong
		itemprop="ratingValue"><?php echo esc_attr( $rating ); ?></strong> <?php esc_attr_e( 'out of 5', 'viem-ultimate' ); ?></span>
</div>
<?php
			}
			return apply_filters( 'viem_channel_review_rating_html', ob_get_clean() );
		}

		public static function list_reviews() {
			global $comment;
			?>
<li itemprop="review" itemscope itemtype="http://schema.org/Review"
	<?php comment_channel(); ?> id="li-comment-<?php comment_ID() ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment-wrap">
		<div class="comment-img">
						<?php
			echo get_avatar( $comment, apply_filters( 'viem_channel_review_gravatar_size', '100' ), '' );
			?>
					</div>
		<div class="comment-block">
						<?php if ( '0' === $comment->comment_approved ) { ?>
	
							<p class="meta">
				<em><?php esc_attr_e( 'Your comment is awaiting approval', 'viem-ultimate' ); ?></em>
			</p>
						
						<?php } else { ?>
							<div class="comment-header">
								<?php
				echo self::get_review_rating_html();
				?>
								<div class="comment-author">
					<h4><?php comment_author(); ?></h4>
					<div class="comment-meta">
						<time itemprop="datePublished"
							datetime="<?php echo get_comment_date( 'c' ); ?>"><?php echo get_comment_date(); ?></time>
					</div>
				</div>
			</div>
						<?php
			
}
			echo '<div itemprop="description" class="description">';
			comment_text();
			echo '</div>';
			?>
					</div>
	</div>
			<?php
		}
		
		public static function channel_subscribe_button( $post_id = ''){
			$channel_id = ( !empty($post_id) ) ? $post_id : get_the_ID();
			$user_id = get_current_user_id();
				
			$subscribed_status = 'subscribe';
			
			$desc = self::$subscribe_text;
				
			$subscription_added = self::check_channel_in_subscriptions($channel_id, $user_id);
			
			if( $subscription_added ){
				$subscribed_status = 'subscribed';
			}
			
			$get_icon = viem_get_post_meta('channel_icon', $channel_id, 'fa fa-play-circle-o');
			$channel_icon = !empty($get_icon) ? $get_icon : 'fa fa-play-circle-o';
			
			ob_start(); 
			?>
				<a class="viem-subscribe-button btn <?php echo esc_attr($subscribed_status);?>" href="<?php echo ( ! is_user_logged_in() ) ? esc_url(wp_login_url(get_permalink())) : 'javascript:void(0);';?>"><i class="<?php echo esc_attr($channel_icon); ?>" aria-hidden="true"></i>
					<span class="viem-desc">
						<span class="subscribe-label" aria-label="<?php echo esc_attr(self::$subscribe_text);?>"><?php echo esc_html(self::$subscribe_text)?></span>
						<span class="subscribed-label" aria-label="<?php echo esc_attr(self::$subscribed_text);?>"><?php echo esc_html(self::$subscribed_text)?></span>
						<span class="unsubscribe-label" aria-label="<?php echo esc_attr(self::$unsubscribe_text);?>"><?php echo esc_html(self::$unsubscribe_text)?></span>
					</span>
					<?php if( is_user_logged_in() ) :
					$nonce = wp_create_nonce("viem_subscribe_nonce_".$channel_id);
					?>
					<div class="data-subcription" data-id-sub="<?php echo esc_attr($post_id);?>" data-user-id="<?php echo esc_attr(get_current_user_id());?>" data-nonce="<?php echo esc_attr( $nonce ); ?>"></div>
					<?php endif; ?>
				</a>
			<?php
			echo ob_get_clean();
		}
		
		public static function get_subscribers_count( $post_id = '' ) {
			$channel_id = ( !empty($post_id) ) ? $post_id : get_the_ID();
			$subscribe_counter = get_post_meta($channel_id, 'viem_subscribe_counter',true);
			echo '<span class="subscriber-count">'.viem_get_formatted_string_number($subscribe_counter).'</span>';
		}
		
		public function ajax_chanel_subscription(){
			if( isset($_POST['nonce']) && wp_verify_nonce( $_POST['nonce'], 'viem_subscribe_nonce_'.$_POST['channel_id'] ) ){
				
				$user_subscribe_channel_id = get_user_meta($_POST['user_id'], 'viem_subscribe_channel_id', true);
				$subscribe_counter = (int)get_post_meta( $_POST['channel_id'], 'viem_subscribe_counter',true);
				
				$subscribed = $message = $subscriber_count ='';
				
				if( $user_subscribe_channel_id ){
					if( !is_array($user_subscribe_channel_id) ){
						
						if( $user_subscribe_channel_id == $_POST['channel_id'] ){
							$user_subscribe_channel_id = '';
							update_user_meta( $_POST['user_id'], 'viem_subscribe_channel_id', $user_subscribe_channel_id);
							$subscribe_counter = $subscribe_counter - 1;
							update_post_meta( $_POST['channel_id'], 'viem_subscribe_counter', $subscribe_counter);
							$subscribed = 0;
							$message = self::$subscribe_removed;
							$subscriber_count = $subscribe_counter;
						}else{
							$arr = array();
							array_push($arr, $user_subscribe_channel_id);
							array_push($arr, $_POST['channel_id'] );
							$user_subscribe_channel_id = $arr;
							
							update_user_meta( $_POST['user_id'], 'viem_subscribe_channel_id', $user_subscribe_channel_id);
							$subscribe_counter = $subscribe_counter + 1;
							update_post_meta( $_POST['channel_id'], 'viem_subscribe_counter', $subscribe_counter);
							$subscribed = 1;
							$message = self::$subscribed_added;
							$subscriber_count = $subscribe_counter;
						}
						
					}else{
						if( in_array($_POST['channel_id'], $user_subscribe_channel_id) ){
							$remove_id = array_search($_POST['channel_id'], $user_subscribe_channel_id);
							unset($user_subscribe_channel_id[$remove_id]);
							update_user_meta( $_POST['user_id'], 'viem_subscribe_channel_id', $user_subscribe_channel_id);
							$subscribe_counter = $subscribe_counter -1;
							update_post_meta( $_POST['channel_id'], 'viem_subscribe_counter', $subscribe_counter);
							$subscribed = 0;
							$message = self::$subscribe_removed;
							$subscriber_count = $subscribe_counter;
						}else{
							array_push($user_subscribe_channel_id, $_POST['channel_id']);
							update_user_meta( $_POST['user_id'], 'viem_subscribe_channel_id', $user_subscribe_channel_id);
							$subscribe_counter = $subscribe_counter + 1;
							update_post_meta( $_POST['channel_id'], 'viem_subscribe_counter', $subscribe_counter);
							$subscribed = 1;
							$message = self::$subscribed_added;
							$subscriber_count = $subscribe_counter;
						}
					}
				}else{
					$user_subscribe_channel_id = $_POST['channel_id'];
					update_user_meta( $_POST['user_id'], 'viem_subscribe_channel_id', $user_subscribe_channel_id);
					$subscribe_counter = $subscribe_counter + 1;
					update_post_meta( $_POST['channel_id'], 'viem_subscribe_counter', $subscribe_counter);
					$subscribed = 1;
					$message = self::$subscribed_added;
					$subscriber_count = $subscribe_counter;
				}
				
				echo json_encode( array('subscribed' => $subscribed, 'message' => $message, 'subscriber_count' => $subscriber_count) );
				
				die();
			}
		}
		
		public static function check_channel_in_subscriptions( $channel_id = '', $userID ){
			if( empty($userID) || empty($channel_id) ) return false;
			
			$user_subscribe_channel_id = get_user_meta($userID, 'viem_subscribe_channel_id', true);
			
			if( !is_array($user_subscribe_channel_id) ){
				if($channel_id == $user_subscribe_channel_id ){
					return true;
				}else{
					return false;
				}
			}else{
				if( in_array( $channel_id, $user_subscribe_channel_id) ){
					return true;
				}else{
					return false;
				}
			}
			
		}
		
		public static function user_subscriptions_list(){
			$user_id = get_current_user_id();
		
			$user_subscribe_channel_id = get_user_meta($user_id, 'viem_subscribe_channel_id', true);
			
			return $user_subscribe_channel_id;
		}

		public static function viem_single_channel_details() {
			if ( ! is_singular( 'viem_channel' ) )
				return;
			?>
			
			<?php
		}
		
		public static function viem_channel_social_account(){
			if ( ! is_singular( 'viem_channel' ) )
				return;
			$socials = apply_filters('viem_channe_social',array(
				'facebook'=>array(
					'label'=>esc_html__('Facebook','viem-ultimate'),
					'url'=>viem_get_post_meta('facebook-url')
				),
				'twitter'=>array(
					'label'=>esc_html__('Twitter','viem-ultimate'),
					'url'=>viem_get_post_meta('twitter-url')
				),
				'google-plus'=>array(
					'label'=>esc_html__('Google+','viem-ultimate'),
					'url'=>viem_get_post_meta('google-plus-url')
				),
				'youtube'=>array(
					'label'=>esc_html__('Youtube','viem-ultimate'),
					'url'=>viem_get_post_meta('youtube-url')
				),
				'vimeo'=>array(
					'label'=>esc_html__('Vimeo','viem-ultimate'),
					'url'=>viem_get_post_meta('vimeo-url')
				),
				'pinterest'=>array(
					'label'=>esc_html__('Pinterest','viem-ultimate'),
					'url'=>viem_get_post_meta('pinterest-url')
				),
				'linkedin'=>array(
					'label'=>esc_html__('LinkedIn','viem-ultimate'),
					'url'=>viem_get_post_meta('linkedin-url')
				),
				'rss'=>array(
					'label'=>esc_html__('RSS','viem-ultimate'),
					'url'=>viem_get_post_meta('rss-url')
				),
				'instagram'=>array(
					'label'=>esc_html__('Instagram','viem-ultimate'),
					'url'=>viem_get_post_meta('instagram-url')
				),
				'github'=>array(
					'label'=>esc_html__('GitHub','viem-ultimate'),
					'url'=>viem_get_post_meta('github-url')
				),
				'behance'=>array(
					'label'=>esc_html__('Behance','viem-ultimate'),
					'url'=>viem_get_post_meta('behance-url')
				),
				'stack-exchange'=>array(
					'label'=>esc_html__('StackExchange','viem-ultimate'),
					'url'=>viem_get_post_meta('stack-exchange-url')
				),
				'tumblr'=>array(
					'label'=>esc_html__('Tumblr','viem-ultimate'),
					'url'=>viem_get_post_meta('tumblr-url')
				),
				'soundcloud'=>array(
					'label'=>esc_html__('SoundCloud','viem-ultimate'),
					'url'=>viem_get_post_meta('soundcloud-url')
				),
				'dribbble'=>array(
					'label'=>esc_html__('Dribbble','viem-ultimate'),
					'url'=> viem_get_post_meta('dribbble-url')
				),
			));
			
			$target = viem_get_post_meta('social-target');
			
			echo '<div class="dt-socials-list">';
				foreach ((array)$socials  as $social=>$data):
					if(!empty($data['url'])):
						echo '<div class="dt-socials-item '.$social.'">';
						echo '<a class="dt-socials-item-link" href="'.esc_url($data['url']).'" title="'.esc_attr($data['label']).'" '. ( !empty($target) ? 'target="'.$target.'"' : '' ) .'><i class="fa fa-'.$social.'"></i></a>';
						echo '</div>';
					endif;
				endforeach;
			echo '</div>';
		}
	}
	new viem_posttype_channel();
}