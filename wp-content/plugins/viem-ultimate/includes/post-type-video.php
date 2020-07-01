<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! class_exists( 'viem_posttype_video' ) ) {

	class viem_posttype_video {
		
		const POSTTYPE = 'viem_video';
		public $rewriteSlug = 'videos';
		public $rewriteSlugSingular = 'video';
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
				//Ajax Featured
				add_action( 'wp_ajax_viem_feature_video', array( __CLASS__, 'feature_video' ) );
				//Admin Columns
				add_filter( 'manage_edit-viem_video_columns', array($this,'manage_edit_columns') );
				add_filter( 'manage_viem_video_posts_custom_column',  array($this,'manage_custom_column'),10, 2 );
				
				// Add form fields
				add_action('video_badges_add_form_fields', array(&$this, 'viem_extra_video_badges_add_fields'));
				add_action('video_badges_edit_form_fields', array(&$this, 'viem_extra_video_badges_edit_fields'));
				add_action ( 'edited_video_badges', array(&$this, 'viem_save_extra_video_badges_fileds'), 10, 2);
				add_action( 'created_video_badges', array(&$this, 'viem_save_extra_video_badges_fileds'), 10, 2 );
				
			}else{
				add_filter('template_include', array(&$this, 'template_loader'));
				
				add_action('viem_before_single_post_content', array(__class__, 'viem_single_video_specifics') );

				add_action('viem_loop_item_badges', array(__class__, 'viem_video_badges_html'));
				
				add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
				add_action( 'wp', array( $this, 'remove_video_query' ) );
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
			return sanitize_title( _x( viem_get_theme_option( 'videos_slug','videos' ), 'Archive Videos Slug', 'viem-ultimate') );
		}
		
		/**
		 * Get the single post rewrite slub
		 *
		 * @return string
		 */
		public function getRewriteSlugSingular(){
			// translators: For compatibility with WPML and other multilingual plugins, not to be translated directly on .mo files.
			return sanitize_title( _x( viem_get_theme_option( 'single_video_slug','video' ), 'Rewrite Singular Slug', 'viem-ultimate') );
		}

		public function register_post_type() {
			if ( post_type_exists( 'viem_video' ) )
				return;
			$permalinks = viem_get_theme_option( 'dt_theme_'.basename(get_template_directory()) . '_permalinks' );
			$video_permalink = empty( $permalinks['viem_video_base'] ) ? $this->rewriteSlugSingular : $permalinks['viem_video_base'];
			
			register_post_type(
				'viem_video',
				apply_filters( 
					'viem_register_post_type_video', 
					array( 
						'labels' => array( 
							'name' => esc_html__( 'Videos', 'viem-ultimate' ), 
							'singular_name' => esc_html__( 'Video', 'viem-ultimate' ), 
							'menu_name' => _x( 'Videos', 'Admin menu name', 'viem-ultimate' ), 
							'add_new' => esc_html__( 'Add New Video', 'viem-ultimate' ), 
							'add_new_item' => esc_html__( 'Add New Video', 'viem-ultimate' ), 
							'edit' => esc_html__( 'Edit', 'viem-ultimate' ), 
							'edit_item' => esc_html__( 'Edit Video', 'viem-ultimate' ), 
							'new_item' => esc_html__( 'New Video', 'viem-ultimate' ), 
							'view' => esc_html__( 'View Video', 'viem-ultimate' ), 
							'view_item' => esc_html__( 'View Video', 'viem-ultimate' ), 
							'search_items' => esc_html__( 'Search Videos', 'viem-ultimate' ), 
							'not_found' => esc_html__( 'No Videos found', 'viem-ultimate' ), 
							'not_found_in_trash' => esc_html__( 'No Videos found in trash', 'viem-ultimate' ), 
							'parent' => esc_html__( 'Parent Video', 'viem-ultimate' ) ), 
						'public' => true, 
						'show_ui' => true, 
						'map_meta_cap' => true, 
						'publicly_queryable' => true, 
						'hierarchical' => false,  // Hierarchical causes memory issues - WP loads all records!
						'menu_position' => 10, 
						'menu_icon' => 'dashicons-video-alt3',
						'exclude_from_search' => false, 
						'rewrite' => $video_permalink ? array( 
							'slug' => untrailingslashit( $video_permalink ), 
							'with_front' => false, 
							'feeds' => true ) : false, 
						'has_archive' => ( $main_video_page_id = viem_get_theme_option( 'main-video-page' ) ) &&
							 get_post( $main_video_page_id ) ? get_page_uri( $main_video_page_id ) : $this->rewriteSlug,
							'supports' => array( 'title', 'editor', 'author', 'excerpt', 'thumbnail', 'comments' ) ) ) );
			register_taxonomy(
				'video_cat',
				array( 'viem_video' ),
				array( 
					'labels' => array( 
						'name' => esc_html__( 'Video Categories', 'viem-ultimate' ), 
						'singular_name' => esc_html__( 'Category', 'viem-ultimate' ), 
						'search_items' => esc_html__( 'Search Categories', 'viem-ultimate' ), 
						'all_items' => esc_html__( 'All Categories', 'viem-ultimate' ), 
						'edit_item' => esc_html__( 'Edit Category', 'viem-ultimate' ), 
						'update_item' => esc_html__( 'Update Category', 'viem-ultimate' ), 
						'add_new_item' => esc_html__( 'Add New Category', 'viem-ultimate' ), 
						'new_item_name' => esc_html__( 'New Category', 'viem-ultimate' ), 
						'menu_name' => esc_html__( 'Video Categories', 'viem-ultimate' ) ), 
						'show_ui' => true, 
						'query_var' => true, 
						'hierarchical' => true, 
						'rewrite' => array( 
						'slug' => empty( $permalinks['viem_video_cat_base'] ) ? _x( 'video-category', 'slug', 'viem-ultimate' ) : $permalinks['viem_video_cat_base'], 
						'with_front' => false ) ) );
			register_taxonomy( 
				'video_tag', 
				array( 'viem_video' ), 
				array( 
					'labels' => array( 
						'name' => esc_html__( 'Video Tags', 'viem-ultimate' ), 
						'singular_name' => esc_html__( 'Tag', 'viem-ultimate' ), 
						'search_items' => esc_html__( 'Search Tags', 'viem-ultimate' ), 
						'all_items' => esc_html__( 'All Tags', 'viem-ultimate' ), 
						'edit_item' => esc_html__( 'Edit Tag', 'viem-ultimate' ), 
						'update_item' => esc_html__( 'Update Tag', 'viem-ultimate' ), 
						'add_new_item' => esc_html__( 'Add New Tag', 'viem-ultimate' ), 
						'new_item_name' => esc_html__( 'New Tag', 'viem-ultimate' ),
						'menu_name' => esc_html__( 'Video Tags', 'viem-ultimate' ) ), 
					'show_ui' => true, 
					'query_var' => true, 
					'hierarchical' => false, 
					'rewrite' => array( 
						'slug' => empty( $permalinks['viem_video_tag_base'] ) ? _x( 'video-tag', 'slug', 'viem-ultimate' ) : $permalinks['viem_video_tag_base'], 
						'with_front' => false ) ) );
			
			register_taxonomy(
				'video_badges',
				array( 'viem_video' ),
					array(
					'labels' => array(
					'name' => esc_html__( 'Badges', 'viem-ultimate' ),
					'singular_name' => esc_html__( 'Badges', 'viem-ultimate' ),
					'search_items' => esc_html__( 'Search', 'viem-ultimate' ),
					'all_items' => esc_html__( 'All Badges', 'viem-ultimate' ),
					'edit_item' => esc_html__( 'Edit Badge', 'viem-ultimate' ),
					'update_item' => esc_html__( 'Update Badge', 'viem-ultimate' ),
					'add_new_item' => esc_html__( 'Add New Badge', 'viem-ultimate' ),
					'new_item_name' => esc_html__( 'New Badge', 'viem-ultimate' ),
					'menu_name' => esc_html__( 'Badges', 'viem-ultimate' ) ),
					'show_ui' => true,
					'query_var' => true,
					'hierarchical' => false,
					'rewrite' => array(
					'slug' => empty( $permalinks['viem_video_badge_base'] ) ? _x( 'video-badge', 'slug', 'viem-ultimate' ) : $permalinks['viem_video_badge_base'],
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
				'id' => 'dt-metabox-viem_video',
				'title' => __ ( 'Video Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_video',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__ ( 'Video page Layout', 'viem-ultimate' ),
						'description' => esc_html__ ( 'Default to use the setting in Theme Options.', 'viem-ultimate' ),
						'name' => 'single-video-layout',
						'type' => 'image_select',
						'options'=>array(
							''=> array('alt' => 'Default', 'img' => DTINC_ASSETS_URL . '/images/0col.png'),
							'full-width'=> array('alt' => 'Fullwidth', 'img' => DTINC_ASSETS_URL . '/images/1col.png'),
							'left-sidebar'=> array('alt' => '2 Column Left', 'img' => DTINC_ASSETS_URL . '/images/2cl.png'),
							'right-sidebar'=> array('alt' => '2 Column Right', 'img' => DTINC_ASSETS_URL . '/images/2cr.png'),
							'left-right-sidebar'=> array('alt' => '3 Column Left - Right Siderbar', 'img' => DTINC_ASSETS_URL . '/images/3cm.png'),
						)
					),
					array (
						'label' => esc_html__ ( 'Video player Layout', 'viem-ultimate' ),
						'description' => esc_html__ ( 'Default to use the setting in Theme Options.', 'viem-ultimate' ),
						'name' => 'single-video-style',
						'type' => 'select',
						'options'=>array(
							''=> esc_html__('Default','viem-ultimate'),
							'style-1'=> esc_html__('Top Header','viem-ultimate'),
							'style-2'=> esc_html__('In Container','viem-ultimate'),
						)
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Video player settings','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Select video player type', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_type',
						'type' => 'select',
						'value'=>'youtube',
						'options'=>
							array(
							'youtube'=> esc_html__('YouTube','viem-ultimate'),
							'vimeo'=> esc_html__('Vimeo','viem-ultimate'),
							'HTML5'=> esc_html__('HTML5 (self-hosted)','viem-ultimate'),
							)
					),
					array (
						'label' => esc_html__( 'MP4 Video URL', 'viem-ultimate' ),
						'name' => 'video_mp4',
						'type' => 'text',
						'width' => '350px',
						'placeholder' => esc_html__('Enter .mp4 video URL','viem-ultimate'),
						'dependency' => array( 'field' => 'video_type', 'value' => array( 'HTML5') ),
						'description' => esc_html__( 'HTML5 video mp4,  HLS m3u8 (url)', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Enable MP4 Download', 'viem-ultimate' ),
						'name' => 'enable_mp4_download',
						'type' => 'select',
						'dependency' => array( 'field' => 'video_type', 'value' => array( 'HTML5') ),
						'description' => esc_html__( 'enable download button for self hosted videos', 'viem-ultimate'),
						'options'=> array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'YouTube ID', 'viem-ultimate' ),
						'name' => 'youtube_id',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '0dJO0HyE8xE',
						'dependency' => array( 'field' => 'video_type', 'value' => array( 'youtube') ),
						'description' => esc_html__( 'last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE', 'viem-ultimate' )
					),
					array (
						'label' => esc_html__( 'Vimeo ID', 'viem-ultimate' ),
						'name' => 'vimeo_id',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '119641053',
						'dependency' => array( 'field' => 'video_type', 'value' => array( 'vimeo') ),
						'description' => esc_html__( 'last part of the URL http://vimeo.com/119641053', 'viem-ultimate' )
					),
					/*array(
						'name' => 'video_thumb_img',
						'type' => 'image',
						'label' => esc_html__( 'Thumbnail image', 'viem-ultimate' ),
						'description' => esc_html__( 'Leave blank to grab it automatically from youtube, or set path to playlist thumbnail image.', 'viem-ultimate' ) ), */
					array (
						'label' => esc_html__( 'Duration', 'viem-ultimate' ),
						'name' => 'video_duration',
						'type' => 'text',
						'width' => '150px',
						'placeholder' => '00:00:00'
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Video Multi Links settings','viem-ultimate'),
					),
					array(
						'type' => 'multilink',
						'label'=> esc_html__('Multi Links','viem-ultimate'),
						'name' => 'multi_link',
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Pre Roll Video Advertisement','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Show Pre Roll Video Ad?', 'viem-ultimate' ),
						'description'=> esc_html__('Pre Roll advertisement will show at the start of the video.','viem-ultimate'),
						'name' => 'preroll_ad',
						'type' => 'select',
						'value'=>'no',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__ ( 'Pre Roll Video Ad Link URL', 'viem-ultimate' ),
						'name' => 'preroll_goto_link',
						'type' => 'text',
						'dependency' => array( 'field' => 'preroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('pre-roll goto link.','viem-ultimate')
					),
					array (
						'label' => esc_html__ ( 'Pre Roll Video Ad MP4 , HLS m3u8 (url)', 'viem-ultimate' ),
						'name' => 'preroll_mp4',
						'type' => 'text',
						'value' => 'http://dawnthemes.com/player/videos/Logo_Explode.mp4',
						'dependency' => array( 'field' => 'preroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('pre-roll video mp4 format, HLS m3u8.','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Pre Roll Video Skip Timer', 'viem-ultimate' ),
						'name' => 'preroll_skip_timer',
						'type' => 'text',
						'width' => '150px',
						'value' => 5,
						'dependency' => array( 'field' => 'preroll_ad', 'value' => array( 'yes') ),
						'placeholder' => '5',
						'description'=> esc_html__('(sec)','viem-ultimate')
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Mid Roll Video Advertisement','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Show Mid Roll Video Ad?', 'viem-ultimate' ),
						'description' => esc_html__('Show Mid Roll advertisement at any custom time in format "minutes:seconds" ("00:00")','viem-ultimate'),
						'name' => 'midroll_ad',
						'type' => 'select',
						'value'=>'no',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'Mid Roll Video Ad Display Time', 'viem-ultimate' ),
						'name' => 'midrollad_display_Time',
						'type' => 'text',
						'width' => '150px',
						'value' => '',
						'dependency' => array( 'field' => 'midroll_ad', 'value' => array( 'yes') ),
						'placeholder' => '00:10',
						'description'=> esc_html__('"minutes:seconds" ("00:00")','viem-ultimate')
					),
					array (
						'label' => esc_html__ ( 'Mid Roll Video Ad Link URL', 'viem-ultimate' ),
						'name' => 'midroll_goto_link',
						'type' => 'text',
						'dependency' => array( 'field' => 'midroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('mid-roll goto link.','viem-ultimate')
					),
					array (
						'label' => esc_html__ ( 'Mid Roll Video Ad MP4, HLS m3u8 (URL)', 'viem-ultimate' ),
						'name' => 'midroll_mp4',
						'type' => 'text',
						'dependency' => array( 'field' => 'midroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('mid-roll video mp4 format, HLS m3u8.','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Mid Roll Video Skip Timer', 'viem-ultimate' ),
						'name' => 'midroll_skip_timer',
						'type' => 'text',
						'width' => '150px',
						'value' => 5,
						'dependency' => array( 'field' => 'midroll_ad', 'value' => array( 'yes') ),
						'placeholder' => '5',
						'description'=> esc_html__('(sec)','viem-ultimate')
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Post Roll Video Advertisement','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Show Post Roll Video Ad?', 'viem-ultimate' ),
						'description'=> esc_html__('Post Roll advertisement will show at the end of the video.','viem-ultimate'),
						'name' => 'postroll_ad',
						'type' => 'select',
						'value'=>'no',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						),
					),
					array (
						'label' => esc_html__ ( 'Post Roll Video Ad Link URL', 'viem-ultimate' ),
						'name' => 'postroll_goto_link',
						'type' => 'text',
						'dependency' => array( 'field' => 'postroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('post-roll goto link.','viem-ultimate')
					),
					array (
						'label' => esc_html__ ( 'Post Roll Video Ad MP4, HLS m3u8 (URL)', 'viem-ultimate' ),
						'name' => 'postroll_mp4',
						'type' => 'text',
						'dependency' => array( 'field' => 'postroll_ad', 'value' => array( 'yes') ),
						'description'=> esc_html__('post-roll video mp4 format.','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Post Roll Video Skip Timer', 'viem-ultimate' ),
						'name' => 'postroll_skip_timer',
						'type' => 'text',
						'width' => '150px',
						'value' => 5,
						'dependency' => array( 'field' => 'postroll_ad', 'value' => array( 'yes') ),
						'placeholder' => '5',
						'description'=> esc_html__('(sec)','viem-ultimate')
					),
					
					array(
						'type' => 'heading',
						'heading'=> esc_html__('PopUp Video Advertisement','viem-ultimate'),
					),
					array (
						'label' => esc_html__( 'Show Pop-up Ad?', 'viem-ultimate' ),
						'description'=> esc_html__('You can set when to show and hide pop-up image ad (in format minutes:seconds).','viem-ultimate'),
						'name' => 'popup_ad_show',
						'type' => 'select',
						'value'=>'no',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						),
					),
					array(
						'name' => 'popup_img',
						'type' => 'image',
						'value' => '',
						'label' => esc_html__( 'Popup Image URL', 'viem-ultimate' ),
						'description' => '',
						'dependency' => array( 'field' => 'popup_ad_show', 'value' => array( 'yes') ),
					),
					array (
						'label' => esc_html__( 'Pop-up Ad Start Time', 'viem-ultimate' ),
						'name' => 'popup_ad_start_time',
						'type' => 'text',
						'width' => '150px',
						'value' => '',
						'dependency' => array( 'field' => 'popup_ad_show', 'value' => array( 'yes') ),
						'placeholder' => '00:03',
						'description'=> esc_html__('Time to show popup ad during playback (sec)','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Pop-up Ad End Time', 'viem-ultimate' ),
						'name' => 'popup_ad_end_time',
						'type' => 'text',
						'width' => '150px',
						'value' => '',
						'dependency' => array( 'field' => 'popup_ad_show', 'value' => array( 'yes') ),
						'placeholder' => '00:07',
						'description'=> esc_html__('Time to hide popup ad during playback (sec)','viem-ultimate')
					),
					array (
						'label' => esc_html__ ( 'Pop-up Ad Link URL', 'viem-ultimate' ),
						'name' => 'popup_ad_goto_link',
						'type' => 'text',
						'dependency' => array( 'field' => 'popup_ad_show', 'value' => array( 'yes') ),
						'description'=> esc_html__('re-direct to URL when popup ad clicked.','viem-ultimate')
					),
					
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			$meta_box = array (
				'id' => 'dt-metabox-viem_video_playlist',
				'title' => esc_html__( 'Playlist', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_video',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Playlist', 'viem-ultimate' ),
						'name' => 'video_playlist_id',
						'type' => 'select',
						'multiple'=>true,
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_playlist'),
						'description'=>__('Add this video to a playlist.','viem-ultimate')
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			$meta_box = array (
				'id' => 'dt-metabox-viem_video_channel',
				'title' => esc_html__( 'Channel', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_video',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Channel', 'viem-ultimate' ),
						'name' => 'video_channel_id',
						'type' => 'select',
						'multiple'=>true,
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_channel'),
						'description'=>__('Add this video to a channel.','viem-ultimate')
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			$meta_box = array (
				'id' => 'dt-metabox-viem_video_series',
				'title' => esc_html__( 'Series', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_video',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Series', 'viem-ultimate' ),
						'name' => 'video_series_id',
						'type' => 'select',
						'multiple'=>true,
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_series'),
						'description'=>__('Add this video to a Series.','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Number order in Series.', 'viem-ultimate' ),
						'name' => 'order_in_series',
						'type' => 'text',
						'width' => '150px',
						'value' => '01',
						'placeholder' => '01',
						'description'=> esc_html__('Enter order of this video in series','viem-ultimate')
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
			$meta_box = array (
				'id' => 'dt-metabox-viem_video_specifics',
				'title' => esc_html__( 'Video Specifics', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'viem_video',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Release Date', 'viem-ultimate' ),
						'name' => 'video_release_date',
						'type' => 'datepicker',
						'timestamp'=> true,
						'description' => esc_html__( 'Please choose the date the video was released to theatres.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Video Country', 'viem-ultimate' ),
						'name' => 'video_country',
						'type' => 'chosen-order',
						'multiple'=>true,
						'chosen'=>true,
						'placeholder'=> esc_html__( 'Choose a Country...', 'viem-ultimate' ),
						'options'=> $countries,
						'description' => esc_html__( 'Please choose the country where the video was filmed.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Main Language', 'viem-ultimate' ),
						'name' => 'video_language',
						'type' => 'select',
						'chosen'=>true,
						'options'=> $languages,
						'description' => esc_html__( 'Please choose the main language in the video.', 'viem-ultimate' ),
					),
					array (
						'label' => esc_html__( 'Director', 'viem-ultimate' ),
						'name' => 'video_director',
						'type' => 'select',
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_director'),
					),
					array (
						'label' => esc_html__( 'Actors', 'viem-ultimate' ),
						'name' => 'video_actors',
						'type' => 'chosen-order',
						'multiple'=>true,
						'chosen'=>true,
						'options'=>_viem_get_options_select_post('viem_actor'),
						'description'=>__('Add actor members.','viem-ultimate')
					),
					array (
						'label' => esc_html__( 'Actor Style', 'viem-ultimate' ),
						'name' => 'video_actor_style',
						'type' => 'select',
						'value'=>'grid',
						'options'=>array(
							'grid'=> esc_html__('Grid','viem-ultimate'),
							'slider'=> esc_html__('Slider','viem-ultimate'),
						)
					),
				)
			);
			add_meta_box ( $meta_box ['id'], $meta_box ['title'], 'dawnthemes_render_meta_boxes', $meta_box ['post_type'], $meta_box ['context'], $meta_box ['priority'], $meta_box );
			
		}
		
		public function save_meta_boxes($post_id, $post){
			if('viem_video'!==get_post_type($post))
				return;
			
			if(isset($_POST['dt_meta']['_dt_video_release_date'])){
				update_post_meta($post_id, '_dt_video_release_date', strtotime($_POST['dt_meta']['_dt_video_release_date']));
			}
			
			if(isset($_POST['dt_meta']['_dt_multi_link'])){
				$links = $_POST['dt_meta']['_dt_multi_link'];
				update_post_meta($post_id,'_dt_multi_link',$links);
			}else{
				update_post_meta($post_id,'_dt_multi_link',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_director'])){
				$video_director = $_POST['dt_meta']['_dt_video_director'];
				update_post_meta($post_id,'_dt_video_director',$video_director);
			}else{
				update_post_meta($post_id,'_dt_video_director',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_actors'])){
				$video_actors = $_POST['dt_meta']['_dt_video_actors'];
				update_post_meta($post_id,'_dt_video_actors',$video_actors);
			}else{
				update_post_meta($post_id,'_dt_video_actors',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_series_id'])){
				$video_series_ids = $_POST['dt_meta']['_dt_video_series_id'];
				update_post_meta($post_id,'_dt_video_series_id',$video_series_ids);
			}else{
				update_post_meta($post_id,'_dt_video_series_id',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_playlist_id'])){
				$video_playlist_ids = $_POST['dt_meta']['_dt_video_playlist_id'];
				update_post_meta($post_id,'_dt_video_playlist_id',$video_playlist_ids);
			}else{
				update_post_meta($post_id,'_dt_video_playlist_id',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_channel_id'])){
				$channel_ids = $_POST['dt_meta']['_dt_video_channel_id'];
				update_post_meta($post_id,'_dt_video_channel_id',$channel_ids);
			}else{
				update_post_meta($post_id,'_dt_video_channel_id',array());
			}
			
			if(isset($_POST['dt_meta']['_dt_video_country'])){
				$video_country = $_POST['dt_meta']['_dt_video_country'];
				update_post_meta($post_id,'_dt_video_country',$video_country);
			}else{
				update_post_meta($post_id,'_dt_video_country',array());
			}
			
		}
		
		public static function feature_video(){
			if (check_admin_referer( 'dt-feature-video' ) ) {
				$video_id = absint( $_GET['video_id'] );
		
				if ( 'viem_video' === get_post_type( $video_id ) ) {
					update_post_meta( $video_id, '_dt_featured', get_post_meta( $video_id, '_dt_featured', true )  ? 'no' : 'yes' );
		
					delete_transient( 'viem_featured_videos' );
				}
			}
		
			wp_safe_redirect( wp_get_referer() ? remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) : admin_url( 'edit.php?post_type=viem_video' ) );
			die();
		}
		
		public function manage_edit_columns($columns){
			unset($columns['comments'], $columns['categories'], $columns['date'] );
			$columns['playlist'] = esc_html__( 'Playlist', 'viem-ultimate' );
			$columns['channel'] = esc_html__( 'Channel', 'viem-ultimate' );
			$columns['video_cat'] = esc_html__( 'Categories', 'viem-ultimate' );
			$columns['video_tag'] = esc_html__( 'Tags', 'viem-ultimate' );
			$columns['featured']   = esc_html__( 'Featured?', 'viem-ultimate' );
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
					if( viem_get_post_meta('video_playlist_id') ):
						echo viem_posttype_video::get_playlist_list('','',', ','',true);
					else:
						echo '<span class="na">&ndash;</span>';
					endif;
					break;
						break;
				case 'channel' :
					if( viem_get_post_meta('video_channel_id') ):
						echo viem_posttype_video::get_channel_list('','',', ','',true);
					else:
						echo '<span class="na">&ndash;</span>';
					endif;
					break;
				case 'video_cat':
					if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
						echo '<span class="na">&ndash;</span>';
					} else {
						$termlist = array();
						foreach ( $terms as $term ) {
							$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=viem_video' ) . ' ">' . $term->name . '</a>';
						}
		
						echo implode( ', ', $termlist );
					}
					break;
				case 'video_tag':
					if ( ! $terms = get_the_terms( $post->ID, $column ) ) {
						echo '<span class="na">&ndash;</span>';
					} else {
						$termlist = array();
						foreach ( $terms as $term ) {
							$termlist[] = '<a href="' . admin_url( 'edit.php?' . $column . '=' . $term->slug . '&post_type=viem_video' ) . ' ">' . $term->name . '</a>';
						}
		
						echo implode( ', ', $termlist );
					}
					break;
				case 'featured':
					$featured = get_post_meta( $post->ID, '_dt_featured', true );
					$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=viem_feature_video&video_id=' . $post->ID ), 'dt-feature-video' );
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
		
		public static function get_playlist_list($post_id='', $before = '', $sep = '', $after = '', $url='default', $experience = false){
			$post = get_post($post_id);
			$links = array();
			$channel_ids = viem_get_post_meta('video_playlist_id',$post->ID);
			if($channel_ids && $channels = get_posts(array('post_type'=>'viem_playlist','include'=>$channel_ids))){
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
		
		public static function get_channel_list($post_id='', $before = '', $sep = '', $after = '', $url='default', $experience = false){
			$post = get_post($post_id);
			$links = array();
			$channel_ids = viem_get_post_meta('video_channel_id',$post->ID);
			if($channel_ids && $channels = get_posts(array('post_type'=>'viem_channel','include'=>$channel_ids))){
				foreach ($channels as $channel){
					if($url===false){
						$links[] =  $channel->post_title;
					}else{
						$href = $url === true ? get_edit_post_link( $channel->ID ) : get_the_permalink($channel->ID) ;
						$links[] = '<a href="' . esc_url($href). '">' . $channel->post_title.($experience ? '<span playlist="channel-experience">'.__('Experiace: ','viem-ultimate').viem_get_post_meta('experience',$channel->ID).'</span>':''). '</a>';
					}
				}
			}
			return $before . join( $sep, $links ) . $after;
		}
		
		public function video_category_save_meta_field( $term_id, $tt_id ){
		
			if( isset( $_POST['video_cat_thumbnail'] ) && '' !== $_POST['video_cat_thumbnail'] ){
				update_term_meta( $term_id, 'video_cat_thumbnail', $_POST['video_cat_thumbnail'] );
			}
			if( isset( $_POST['video_cat_color'] ) && '' !== $_POST['video_cat_color'] ){
				$group = '#'.sanitize_title( $_POST['video_cat_color'] );
				update_term_meta( $term_id, 'video_cat_color', $group );
			}
		}
		
		public function viem_extra_video_badges_add_fields() {
			?>
			<div class="form-field term-thumbnail-wrap">
				<label><?php esc_html_e( 'Image', 'viem-ultimate' ); ?></label>
				<div id="viem_video_badges_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( viem_placeholder_img_src() ); ?>" width="60px" /></div>
				<div style="line-height: 60px;">
					<input type="hidden" id="viem_video_badges_thumbnail_id" name="viem_video_badges_thumbnail_id" />
					<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'viem-ultimate' ); ?></button>
					<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'viem-ultimate' ); ?></button>
				</div>
				<script type="text/javascript">
			
					// Only show the "remove image" button when needed
					if ( ! jQuery( '#viem_video_badges_thumbnail_id' ).val() ) {
						jQuery( '.remove_image_button' ).hide();
					}
			
					// Uploading files
					var file_frame;
			
					jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
						event.preventDefault();
						// If the media frame already exists, reopen it.
						if ( file_frame ) {
							file_frame.open();
							return;
						}
			
						// Create the media frame.
						file_frame = wp.media.frames.downloadable_file = wp.media({
							title: '<?php esc_html_e( "Choose an image", "viem" ); ?>',
							button: {
								text: '<?php esc_html_e( "Use image", "viem" ); ?>'
							},
							multiple: false
						});
			
						// When an image is selected, run a callback.
						file_frame.on( 'select', function() {
							var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
							var attachment_thumbnail_url = attachment.url;
			
							jQuery( '#viem_video_badges_thumbnail_id' ).val( attachment.id );
							jQuery( '#viem_video_badges_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail_url );
							jQuery( '.remove_image_button' ).show();
						});
			
						// Finally, open the modal.
						file_frame.open();
					});
			
					jQuery( document ).on( 'click', '.remove_image_button', function() {
						jQuery( '#viem_video_badges_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( viem_placeholder_img_src() ); ?>' );
						jQuery( '#viem_video_badges_thumbnail_id' ).val( '' );
						jQuery( '.remove_image_button' ).hide();
						return false;
					});
			
					jQuery( document ).ajaxComplete( function( event, request, options ) {
						if ( request && 4 === request.readyState && 200 === request.status
							&& options.data && 0 <= options.data.indexOf( 'action=add-tag' ) ) {
			
							var res = wpAjax.parseAjaxResponse( request.responseXML, 'ajax-response' );
							if ( ! res || res.errors ) {
								return;
							}
							// Clear Thumbnail fields on submit
							jQuery( '#viem_video_badges_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( viem_placeholder_img_src() ); ?>' );
							jQuery( '#viem_video_badges_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							// Clear Display type field on submit
							jQuery( '#display_type' ).val( '' );
							return;
						}
					} );
			
				</script>
				<div class="clear"></div>
			</div>  
			<?php
		}
		
		public function viem_extra_video_badges_edit_fields($term){
			$t_id = is_object($term) && $term->term_id ? $term->term_id:'';
			$thumbnail_id 	= absint(get_option( "viem_video_badges_thumbnail_id$t_id")) ? get_option( "viem_video_badges_thumbnail_id$t_id"): '';
			
			if ( $thumbnail_id ) {
				$image = wp_get_attachment_url( $thumbnail_id );
			} else {
				$image = viem_placeholder_img_src();
			}
			?>
			<tr class="form-field">
				<th scope="row" valign="top"><label><?php esc_html_e( 'Image', 'viem-ultimate' ); ?></label></th>
				<td>
					<div id="viem_video_badges_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" /></div>
					<div style="line-height: 60px;">
						<input type="hidden" id="viem_video_badges_thumbnail_id" name="viem_video_badges_thumbnail_id" value="<?php echo esc_attr( $thumbnail_id ); ?>" />
						<button type="button" class="upload_image_button button"><?php esc_html_e( 'Upload/Add image', 'viem-ultimate' ); ?></button>
						<button type="button" class="remove_image_button button"><?php esc_html_e( 'Remove image', 'viem-ultimate' ); ?></button>
					</div>
					<script type="text/javascript">
		
						// Only show the "remove image" button when needed
						if ( '0' === jQuery( '#viem_video_badges_thumbnail_id' ).val() ) {
							jQuery( '.remove_image_button' ).hide();
						}
		
						// Uploading files
						var file_frame;
		
						jQuery( document ).on( 'click', '.upload_image_button', function( event ) {
		
							event.preventDefault();
		
							// If the media frame already exists, reopen it.
							if ( file_frame ) {
								file_frame.open();
								return;
							}
		
							// Create the media frame.
							file_frame = wp.media.frames.downloadable_file = wp.media({
								title: '<?php esc_html_e( "Choose an image", "viem" ); ?>',
								button: {
									text: '<?php esc_html_e( "Use image", "viem" ); ?>'
								},
								multiple: false
							});
		
							// When an image is selected, run a callback.
							file_frame.on( 'select', function() {
								var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
								var attachment_thumbnail_url = attachment.url;
		
								jQuery( '#viem_video_badges_thumbnail_id' ).val( attachment.id );
								jQuery( '#viem_video_badges_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail_url );
								jQuery( '.remove_image_button' ).show();
							});
		
							// Finally, open the modal.
							file_frame.open();
						});
		
						jQuery( document ).on( 'click', '.remove_image_button', function() {
							jQuery( '#viem_video_badges_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( viem_placeholder_img_src() ); ?>' );
							jQuery( '#viem_video_badges_thumbnail_id' ).val( '' );
							jQuery( '.remove_image_button' ).hide();
							return false;
						});
					</script>
					<div class="clear"></div>
				</td>
			</tr>
			<?php
		}
		
		public function viem_save_extra_video_badges_fileds($term_id, $tt_id){
			if ( isset( $_POST[sanitize_key('viem_video_badges_thumbnail_id')] ) ) {
				$thumbnail_id = $_POST['viem_video_badges_thumbnail_id'];
				update_option( "viem_video_badges_thumbnail_id$term_id", $thumbnail_id );
			}
		}
		
		public function template_loader($template){
			if(is_post_type_archive( 'viem_video' ) || is_tax( 'video_cat' ) || is_tax( 'video_tag' ) ){
				$template = locate_template( 'archive-video.php' );
			}
			
			if(is_tax( 'video_badges' )){
				$template = locate_template( 'archive-video.php' );
			}
			
			return $template;
		}
		
		public static function viem_single_video_specifics(){
			
			if( !is_singular('viem_video') )
				return;
			?>
			<div class="video-info-list">
				<ul>
					<?php if ( ($categories_list = get_the_term_list(get_the_ID(), 'video_cat', '', esc_html_x( ', ', 'Used between list items, there is a space after the comma.', 'viem-ultimate' ))) && viem_dt_categorized_blog() ) : ?>
					<li><strong><?php echo esc_html__( 'Genres:', 'viem-ultimate' ); ?></strong><span class="genres-links"><?php echo viem_print_string( $categories_list ); ?></span>
					</li>
					<?php
					endif;
					?>
					<?php $video_release_date = viem_get_post_meta('video_release_date');
					if( $video_release_date ):?>
						<li><strong><?php echo esc_html__( 'Release Date:', 'viem-ultimate' ); ?></strong><?php echo date_i18n('M d, Y', strtotime($video_release_date) ); ?></li>
					<?php
					endif;
					?>
					<?php $video_duration = viem_get_post_meta('video_duration');
					if( $video_duration ):?>
						<li><strong><?php echo esc_html__( 'Duration:', 'viem-ultimate' ); ?></strong><?php echo esc_html($video_duration); ?></li>
					<?php
					endif;
					?>
					<?php $video_country = viem_get_post_meta('video_country');
					if( $video_country ):?>
						<li><strong><?php echo esc_html__( 'Country:', 'viem-ultimate' ); ?></strong><?php echo implode(", ", (array)$video_country); ?></li>
					<?php
					endif;
					?>
					<?php $video_language = viem_get_post_meta('video_language');
					if( $video_language ):?>
						<li><strong><?php echo esc_html__( 'Language:', 'viem-ultimate' ); ?></strong><?php echo esc_html($video_language); ?></li>
					<?php
					endif;
					?>
					<?php $video_director = viem_get_post_meta('video_director');
					if( $video_director ):
						$video_director = get_post($video_director);
						?>
						<li><strong><?php echo esc_html__( 'Director:', 'viem-ultimate' ); ?></strong><strong><a href="<?php echo esc_url(get_the_permalink($video_director->ID))?>" title="<?php echo esc_attr( $video_director->post_name )?>"><?php echo viem_print_string( $video_director->post_title );?></a></strong></li>
					<?php
					endif;
					?>
				</ul>
			</div>
			<?php
			
			$actor_ids = viem_get_post_meta('video_actors', get_the_ID(), '');
			$video_actor_style = viem_get_post_meta('video_actor_style', get_the_ID(), 'grid');
			
			if( !empty($actor_ids) ){
				if( !is_array($actor_ids) ){
					$actor_ids = explode(',', $actor_ids);
				}
				$args = array(
					'post_type' => "viem_actor",
					'post__in'	=> $actor_ids,
					'orderby'	=> 'post__in',
				);
				$actors = new WP_Query($args);
				
				if( $actors->have_posts() ){
					?>
					<div class="single-video-actor">
						<h2 class="video-actor-heading"><?php echo esc_html__('Actor', 'viem-untimate');?></h2>
						<?php if( $video_actor_style == 'slider' ):?>
						<div class="owl-carousel viem-carousel-slide viem-preload" data-loop="false" data-autoplay="false" data-dots="0" data-nav="1" data-items="3" data-margin="10" data-rtl = "<?php echo esc_attr(viem_get_theme_option('rtl','0')); ?>">
						<?php else:?>
						<div class="v-grid-list cols_5">
						<?php endif; ?>
							<?php
							while ( $actors->have_posts() ){ $actors->the_post();
							?>
							<div class="<?php echo ($video_actor_style == 'grid' ? 'v-grid-item' : 'item-slide') ?>">
								<a href="<?php echo esc_url(get_the_permalink())?>" title="<?php echo esc_attr( get_the_title() )?>">
									<?php
									if( has_post_thumbnail() ){
										echo '<div class="actor-image">'. get_the_post_thumbnail( get_the_ID(), 'viem-movie-360x460' ) .'</div>';
									}
									?>
									<div class="actor-name"><?php the_title();?></div>
								</a>
							</div>
							<?php
							}
							?>
						</div>
					</div>
					<?php
				}
				wp_reset_postdata();
			}
		}
		
		public static function viem_video_count(){
			$video_count = wp_count_posts('viem_video');
			return $video_count->publish;
		}
		
		public static function viem_video_badges_html(){
			$post_id = get_the_ID();
			ob_start();
			$terms = wp_get_post_terms( $post_id, 'video_badges');
			
			if(!empty($terms)){
			?>
				<div class="viem-badges">
					<?php 
					foreach ($terms as $term) {
						$thumbnail_id 	= absint(get_option( "viem_video_badges_thumbnail_id$term->term_id")) ? get_option( "viem_video_badges_thumbnail_id$term->term_id"): '';
						if ( $thumbnail_id ) {
							$badge = wp_get_attachment_url( $thumbnail_id );
							?>
							<div class="badges-item">
								<img src="<?php echo esc_url($badge);?>" alt="<?php echo esc_attr(get_the_title($post_id))?>">
							</div>
						<?php
						}
					}?>
				</div>
			<?php
			}
			
			echo ob_get_clean();
		}
		
		public function pre_get_posts($q){
			if ( ! $q->is_main_query() ) {
				return;
			}
			if ( ! $q->is_post_type_archive( 'viem_video' ) && ! $q->is_tax( get_object_taxonomies( 'viem_video' ) ) ) {
				return;
			}
			$this->video_query($q);
			$this->remove_video_query();
		}
		
		public function video_query($q){
			$q->set( 'meta_query', $this->get_meta_query( $q->get( 'meta_query' ) ) );
			$q->set( 'tax_query', $this->get_tax_query( $q->get( 'tax_query' ) ) );
			do_action( 'viem_video_query', $q, $this );
		}
		
		public function remove_video_query() {
			remove_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );
		}
		
		public function get_meta_query( $meta_query = array() ) {
			if ( ! is_array( $meta_query ) ) {
				$meta_query = array();
			}
		
			return array_filter( apply_filters( 'viem_video_query_meta_query', $meta_query, $this ) );
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
		
			return array_filter( apply_filters( 'viem_video_query_tax_query', $tax_query, $this ) );
		}
		
		public static function result_count(){
			global $wp_query;
			?>
			<div class="video-result-count">
				<?php
				$total    = $wp_query->found_posts;
				printf(__('We found %1$s available for you','viem-ultimate'),'<span>'.sprintf(_n( '%s Video', '%s Videos',$total, 'viem-ultimate' ),$total).'</span>');
				?>
			</div>
					
			<?php
		}
		
		public static function get_current_filters() {
			if ( ! is_array( self::$_current_filters ) ) {
				self::$_current_filters = array();
				
				if ( $taxonomies = get_object_taxonomies( 'viem_video' ) ) {
					foreach ( $taxonomies as $tax ) {
						$attribute    = str_replace('video_', '', $tax);
						$taxonomy     = 'video_'.$attribute;
						$filter_terms = ! empty( $_GET[ 'filter_' . $attribute ] ) ? explode( ',', wc_clean( $_GET[ 'filter_' . $attribute ] ) ) : array();
		
						if ( empty( $filter_terms ) || ! taxonomy_exists( $taxonomy ) ) {
							continue;
						}
		
						$query_type = ! empty( $_GET[ 'query_type_' . $attribute ] ) && in_array( $_GET[ 'query_type_' . $attribute ], array( 'and', 'or' ) ) ? viem_clean( $_GET[ 'query_type_' . $attribute ] ) : '';
						self::$_current_filters[ $taxonomy ]['terms']      = array_map( 'sanitize_title', $filter_terms ); // Ensures correct encoding
						self::$_current_filters[ $taxonomy ]['query_type'] = $query_type ? $query_type : apply_filters( 'viem_default_video_filter_query_type', 'and' );
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
		
			if ( ! empty( $args['video_cat'] ) ) {
				$tax_query[ 'video_cat' ] = array(
					'taxonomy' => 'video_cat',
					'terms'    => array( $args['video_cat'] ),
					'field'    => 'slug',
				);
			}
		
			if ( ! empty( $args['video_tag'] ) ) {
				$tax_query[ 'video_tag' ] = array(
					'taxonomy' => 'video_tag',
					'terms'    => array( $args['video_tag'] ),
					'field'    => 'slug',
				);
			}
			
		
			return $tax_query;
		}
		
		public static function get_thumbnail_auto(){
			$video_id = get_the_ID();
			$v_type= viem_get_post_meta('video_type', $video_id);
			$thumbnail_url = viem_placeholder_img_src();
			switch ($v_type){
				case 'youtube':
					$vi_id = viem_get_post_meta('youtube_id', $video_id);
					if( !empty($vi_id) )
					$thumbnail_url = 'https://i.ytimg.com/vi/'.$vi_id.'/sddefault.jpg';
					break;
				case 'vimeo':
					$vi_id = viem_get_post_meta('vimeo_id', $video_id);
					if( !empty($vi_id) ){
						$hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $vi_id . '.php'));
						$thumbnail_url = $hash[0]['thumbnail_large'];
					}
					break;
				default:
					break;
			}
			
			return $thumbnail_url;
		}
		
	}
	viem_posttype_video::instance();
}