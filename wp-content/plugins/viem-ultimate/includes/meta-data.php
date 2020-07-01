<?php
/*
 * DawnThemes Meta Box
 * 
 * This class loads all the methods and helpers specific to build a meta box.
 */
if( !function_exists('dawnthemes_render_meta_boxes') )
	return;
	
if (! class_exists ( 'viem_Metaboxes' )) :
	class viem_Metaboxes {
		
		function __construct() {
			if( !is_admin() ){
				return;
			}
			
			add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
			add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
			
			add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ) );
			
		}
		
		public function add_meta_boxes() {
			$meta_boxes = array();
			
			// Post Gallery
			$meta_box_post_gallery = array (
				'id' => 'dt-metabox-post-gallery',
				'title' => esc_html__( 'Gallery Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Gallery', 'viem-ultimate' ),
						'name' => '_dt_gallery',
						'type' => 'gallery',
					),
				)
			);
			add_meta_box ( $meta_box_post_gallery['id'], $meta_box_post_gallery['title'], 'dawnthemes_render_meta_boxes', $meta_box_post_gallery['post_type'],$meta_box_post_gallery['context'], $meta_box_post_gallery['priority'], $meta_box_post_gallery );
			
			//Post Quote
			$meta_box_post_quote = array(
				'id' => 'dt-metabox-post-quote',
				'title' =>  esc_html__('Quote Settings', 'viem-ultimate'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'label' =>  esc_html__('Quote Content', 'viem-ultimate'),
						'description' => esc_html__('Please type the text for your quote here.', 'viem-ultimate'),
						'name' => '_dt_quote',
						'type' => 'textarea',
					),
					array(
						'label' =>  esc_html__('By', 'viem-ultimate'),
						'name' => '_dt_quote_author',
						'type' => 'text',
					)
				)
			);
			add_meta_box ( $meta_box_post_quote['id'], $meta_box_post_quote['title'], 'dawnthemes_render_meta_boxes', $meta_box_post_quote['post_type'],$meta_box_post_quote['context'], $meta_box_post_quote['priority'], $meta_box_post_quote );
			
			//Post Link
			$meta_box_post_link = array(
				'id' => 'dt-metabox-post-link',
				'title' =>  esc_html__('Link Settings', 'viem-ultimate'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'label' =>  esc_html__('Link URL', 'viem-ultimate'),
						'description' => esc_html__('Please input the URL for your link. I.e. http://www.example.com', 'viem-ultimate'),
						'name' => '_dt_link',
						'type' => 'text',
					)
				)
			);
			add_meta_box ( $meta_box_post_link['id'], $meta_box_post_link['title'], 'dawnthemes_render_meta_boxes', $meta_box_post_link['post_type'],$meta_box_post_link['context'], $meta_box_post_link['priority'], $meta_box_post_link );
				
			//Post  Video
			$meta_box_post_video = array(
				'id' => 'dt-metabox-post-video',
				'title' => esc_html__('Video Settings', 'viem-ultimate'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array (
						'label' => esc_html__( 'Layout Type', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_service',
						'type' => 'select',
						'value'=>'single',
						'options'=>array(
							'single'=> esc_html__('Embedded','viem-ultimate'),
							'youtube'=> esc_html__('Youtube Videos','viem-ultimate'),
							'vimeo'=> esc_html__('Vimeo Videos','viem-ultimate'),
						)
					),
					array(
						'label' => esc_html__('Embedded Code', 'viem-ultimate'),
						'description' => wp_kses( __ ('Used when you select Video format. Enter a Youtube, Vimeo, Soundcloud, etc URL. See supported services at <a href="http://codex.wordpress.org/Embeds">http://codex.wordpress.org/Embeds</a>.', 'viem-ultimate'), array( 'a' => array('href'=>array()) ) ),
						'name' => 'video_embed',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'single') ),
						'type' => 'text',
						'hidden'=>true,
					),
					array(
						'label' => esc_html__('Youtube Videos', 'viem-ultimate'),
						'description' => esc_html__('Input list Youtube Video IDs. There should be a comma to separate between IDs (no space).','viem-ultimate'),
						'name' => 'youtube_videos',
						'placeholder' => 'lVFT93IEhvc,-zSY0BsEF3M,flqVUw_QTrU',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'youtube') ),
						'type' => 'text',
						'hidden'=>true,
					),
					array(
						'label' => esc_html__('Vimeo Videos', 'viem-ultimate'),
						'description' => esc_html__('Input list Viemo Video IDs. There should be a comma to separate between IDs (no space).','viem-ultimate'),
						'name' => 'vimeo_videos',
						'placeholder' => '94049919,151875886,124518962',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'vimeo') ),
						'type' => 'text',
						'hidden'=>true,
					),
					array (
						'label' => esc_html__( 'Columns', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_columns',
						'type' => 'select',
						'value'=>'2',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'youtube', 'vimeo') ),
						'options'=>array(
							'1' => '1',
							'2' => '2',
						)
					),
					array (
						'label' => esc_html__( 'Auto Next', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_auto_next',
						'type' => 'select',
						'value'=>'yes',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'youtube', 'vimeo') ),
						'options'=>array(
							'yes'=> esc_html__('Yes','viem-ultimate'),
							'no'=> esc_html__('No','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'Auto Play', 'viem-ultimate' ),
						'description' => '',
						'name' => 'video_auto_play',
						'type' => 'select',
						'value'=>'off',
						'dependency' => array( 'field' => 'video_service', 'value' => array( 'youtube', 'vimeo') ),
						'options'=>array(
							'off'=> esc_html__('Off','viem-ultimate'),
							'on'=> esc_html__('On','viem-ultimate'),
						)
					),
					/*array(
						'type' => 'heading',
						'heading'=> esc_html__('Use hosted video','viem-ultimate'),
					),
					array(
						'label' => esc_html__('MP4 File URL', 'viem-ultimate'),
						'description' => esc_html__('Please enter in the URL to the .m4v video file.', 'viem-ultimate'),
						'name' => '_dt_video_mp4',
						'type' => 'media',
					),
					array(
						'label' => esc_html__('OGV/OGG File URL', 'viem-ultimate'),
						'description' => esc_html__('Please enter in the URL to the .ogv or .ogg video file.', 'viem-ultimate'),
						'name' => '_dt_video_ogv',
						'type' => 'media',
					),
					array(
						'label' => esc_html__('WEBM File URL', 'viem-ultimate'),
						'description' => esc_html__('Please enter in the URL to the .webm video file.', 'viem-ultimate'),
						'name' => '_dt_video_webm',
						'type' => 'media',
					),*/
				)
			);
			add_meta_box ( $meta_box_post_video['id'], $meta_box_post_video['title'], 'dawnthemes_render_meta_boxes', $meta_box_post_video['post_type'],$meta_box_post_video['context'], $meta_box_post_video['priority'], $meta_box_post_video );
				
			//Post  Audio
			$meta_box_post_audio = array(
				'id' => 'dt-metabox-post-audio',
				'title' =>  esc_html__('Audio Settings', 'viem-ultimate'),
				'description' => '',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array(
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Use service audio','viem-ultimate'),
					),
					array(
						'label' => esc_html__('Audio Embed', 'viem-ultimate'),
						'description' => esc_html__('URL or code embed.', 'viem-ultimate'),
						'name' => '_dt_audio_embed',
						'type' => 'text',
					),
					array(
						'type' => 'heading',
						'heading'=> esc_html__('Use hosted video','viem-ultimate'),
					),
					array(
						'label' => esc_html__('MP3 File URL', 'viem-ultimate'),
						'description' => esc_html__('Please enter in the URL to the .mp3 file', 'viem-ultimate'),
						'name' => '_dt_audio_mp3',
						'type' => 'media',
					),
					array(
						'label' => esc_html__('OGA File URL', 'viem-ultimate'),
						'description' => esc_html__('Please enter in the URL to the .ogg or .oga file', 'viem-ultimate'),
						'name' => '_dt_audio_ogg',
						'type' => 'media',
					)
				)
			);
			add_meta_box ( $meta_box_post_audio['id'], $meta_box_post_audio['title'], 'dawnthemes_render_meta_boxes', $meta_box_post_audio['post_type'],$meta_box_post_audio['context'], $meta_box_post_audio['priority'], $meta_box_post_audio );
				
			//Post Settings
			$post_meta_box = array (
				'id' => 'dt-metabox-setting',
				'title' => esc_html__ ( 'Post Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'post',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__ ( 'Post Layout', 'viem-ultimate' ),
						'description' => esc_html__ ( 'Default to use the setting in Theme Options.', 'viem-ultimate' ),
						'name' => 'single-layout',
						'type' => 'image_select',
						'options'=>array(
							''=> array('alt' => 'Default', 'img' => DTINC_ASSETS_URL . '/images/0col.png'),
							'full-width'=> array('alt' => 'Fullwidth', 'img' => DTINC_ASSETS_URL . '/images/1col.png'),
							'left-sidebar'=> array('alt' => '2 Column Left', 'img' => DTINC_ASSETS_URL . '/images/2cl.png'),
							'right-sidebar'=> array('alt' => '2 Column Right', 'img' => DTINC_ASSETS_URL . '/images/2cr.png'),
						)
					),
					array (
						'label' => esc_html__ ( 'Featured Image Layout', 'viem-ultimate' ),
						'description' => esc_html__ ( 'Default to use the setting in Theme Options.', 'viem-ultimate' ),
						'name' => 'single-style',
						'type' => 'select',
						'options'=>array(
							''=> esc_html__('Default','viem-ultimate'),
							'style-1'=> esc_html__('In Container','viem-ultimate'),
							'style-2'=> esc_html__('Fullwidth','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__ ( 'Featured', 'viem-ultimate' ),
						'description' => esc_html__ ( 'Make this post featured. Featured posts will appear in DT Posts Wiget (orderby Featured).', 'viem-ultimate' ),
						'name' => 'post_meta_featured_post',
						'type' => 'select',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate')
						)
					),
				)
			);
			add_meta_box ( $post_meta_box['id'], $post_meta_box['title'], 'dawnthemes_render_meta_boxes', $post_meta_box['post_type'],$post_meta_box['context'], $post_meta_box['priority'], $post_meta_box );
			
		
			//Page Settings
			$revsliders = array();
			$revsliders[''] = esc_html__('--Select Slider--', 'viem-ultimate');
			
			if ( class_exists( 'RevSliderSlider' ) ) {
				global $wpdb;
				$rs = $wpdb->get_results( "SELECT id, title, alias FROM " . $wpdb->prefix . "revslider_sliders ORDER BY id ASC LIMIT 999" );
				if ( $rs ) {
					foreach ( $rs as $slider ) {
						$revsliders[$slider->alias] = $slider->title;
					}
				} else {
					$revsliders[0] = esc_html__( 'No sliders found', 'viem-ultimate' );
				}
			}
			$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
			$menu_options[''] = esc_html__('Default Menu...','viem-ultimate');
			foreach ( $menus as $menu ) {
				$menu_options[$menu->term_id] = $menu->name;
			}
			$page_meta_box = array (
				'id' => 'dt-metabox-page-settings',
				'title' => esc_html__( 'Page Settings', 'viem-ultimate' ),
				'description' =>'',
				'post_type' => 'page',
				'context' => 'normal',
				'priority' => 'high',
				'fields' => array (
					array (
						'label' => esc_html__( 'Page Heading', 'viem-ultimate' ),
						'description' => esc_html__( 'Enable/disable page heading or custom page heading.', 'viem-ultimate' ),
						'name' => 'page_heading',
						'type' => 'select',
						'value'=>'heading',
						'options'=>array(
							'heading'=> esc_html__('Heading - ( breadcrumbs & page title  )','viem-ultimate'),
							'rev'=> esc_html__('Use Revolution Slider','viem-ultimate'),
							'0'=> esc_html__('Hide','viem-ultimate')
						)
					),
					array (
						'label' => esc_html__( 'Revolution Slider', 'viem-ultimate' ),
						'description' => esc_html__( 'Select your Revolution Slider.', 'viem-ultimate' ),
						'name' => 'rev_alias',
						'type' => 'select',
						'dependency' => array( 'field' => 'page_heading', 'value' => array( 'rev') ),
						'options'=>$revsliders,
					),
					array (
						'label' => esc_html__( 'Content Page no Padding', 'viem-ultimate' ),
						'description' => esc_html__( 'If checked. content of page  with no padding top and padding bottom', 'viem-ultimate' ),
						'name' => '_dt_no_padding',
						'type' => 'checkbox',
					),
					/*array (
						'label' => esc_html__( 'Show Page Title', 'viem-ultimate' ),
						'description' => '',
						'name' => 'show_title',
						'type' => 'select',
						'options'=>array(
							''=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
						)
					),
					array (
						'label' => esc_html__( 'Show Breadcrumbs', 'viem-ultimate' ),
						'description' => '',
						'name' => '_dt_show_breadcrumbs',
						'type' => 'select',
						'options'=>array(
							'no'=> esc_html__('No','viem-ultimate'),
							'yes'=> esc_html__('Yes','viem-ultimate'),
							
						)
					),*/
					/*array (
						'label' => esc_html__( 'Header Style', 'viem-ultimate' ),
						'description' => esc_html__( 'Please select your header style here.', 'viem-ultimate' ),
						'name' => 'header_style',
						'type' => 'select',
						'options'=>array(
								'-1'=> esc_html__('Global','viem-ultimate'),
							'layout_1'=> esc_html__('Layout 1','viem-ultimate'),
							'layout_2'=> esc_html__('Layout 2','viem-ultimate'),
							'layout_3'=> esc_html__('Layout 3','viem-ultimate'),
							'layout_4'=> esc_html__('Layout 4','viem-ultimate')
						)
					),
					array (
						'label' => esc_html__( 'Main Navigation Menu', 'viem-ultimate' ),
						'description' => esc_html__( 'Select which main menu displays on this page.', 'viem-ultimate' ),
						'name' => 'main_menu',
						'type' => 'select',
						'value'=>'',
						'options'=>$menu_options,
					),*/
					array (
						'label' => esc_html__( 'Main Sidebar', 'viem-ultimate' ),
						'description' => esc_html__( 'Select sidebar for page with 2 or 3 colums.', 'viem-ultimate' ),
						'name' => 'main_sidebar',
						'type' => 'widgetised_sidebars',
					),
// 					array (
// 						'label' => esc_html__( 'Footer Parallax', 'viem-ultimate' ),
// 						'description' => '',
// 						'name' => 'footer-parallax',
// 						'type' => 'select',
// 						'options'=>array(
// 							'-1'=> esc_html__('Global','viem-ultimate'),
// 							'yes'=> esc_html__('Yes','viem-ultimate'),
// 							'no'=> esc_html__('No','viem-ultimate')
// 						)
// 					),
					/*array (
						'label' => esc_html__( 'Footer Layout', 'viem-ultimate' ),
						'description' => '',
						'name' => 'footer_layout',
						'type' => 'select',
						'options'=>array(
							''=> esc_html__('Global','viem-ultimate'),
							'footer-1'=> esc_html__('Footer Layout 1','viem-ultimate'),
							'footer-2'=> esc_html__('Footer Layout 2','viem-ultimate')
						)
					),*/
				)
			);
			add_meta_box ( $page_meta_box['id'], $page_meta_box['title'], 'dawnthemes_render_meta_boxes', $page_meta_box['post_type'],$page_meta_box['context'], $page_meta_box['priority'], $page_meta_box );
			
			do_action('viem_add_meta_boxes');
		}
		
		public function add_video_featured_image($att_id){
			$p = get_post($att_id);
			update_post_meta($p->post_parent,'_thumbnail_id',$att_id);
		}
		
		
		public function save_meta_boxes($post_id, $post) {
			// $post_id and $post are required
			if (empty ( $post_id ) || empty ( $post )) {
				return;
			}
			// Dont' save meta boxes for revisions or autosaves
			if (defined ( 'DOING_AUTOSAVE' ) || is_int ( wp_is_post_revision ( $post ) ) || is_int ( wp_is_post_autosave ( $post ) )) {
				return;
			}
			// Check the nonce
			if (empty ( $_POST ['dt_meta_box_nonce'] ) || ! wp_verify_nonce ( $_POST ['dt_meta_box_nonce'], 'dt_meta_box_nonce' )) {
				return;
			}
			
			// Check the post being saved == the $post_id to prevent triggering this call for other save_post events
			if (empty ( $_POST ['post_ID'] ) || $_POST ['post_ID'] != $post_id) {
				return;
			}
			
			// Check user has permission to edit
			if (! current_user_can ( 'edit_post', $post_id )) {
				return;
			}
			if(isset( $_POST['dt_meta'] )){
				$dt_meta = $_POST['dt_meta'];
				if(get_post_format() == 'video' && viem_get_theme_option('blog_get_video_thumbnail','0') == '1'){
					$_dt_video_embed = $dt_meta['_dt_video_embed'];
					if(dawnthemes_is_video_support($_dt_video_embed) && ($_dt_video_embed != viem_get_post_meta('video_embed_hidden'))){
						$videoThumbUrl = dawnthemes_get_video_thumb_url($_dt_video_embed);
						if (!empty($videoThumbUrl)) {
							 // add the function above to catch the attachments creation
							add_action('add_attachment',array(&$this,'add_video_featured_image'));
							// load the attachment from the URL
							media_sideload_image($videoThumbUrl, $post_id, $post_id);
							// we have the Image now, and the function above will have fired too setting the thumbnail ID in the process, so lets remove the hook so we don't cause any more trouble
							remove_action('add_attachment',array(&$this,'add_video_featured_image'));
						}
					}
				}
				// Process
				foreach( (array)$_POST['dt_meta'] as $key=>$val ){
					$val = wp_unslash($val);
					if(is_array($val)){
						$option_value = array_filter( array_map( 'sanitize_text_field', (array) $val ) );
						update_post_meta( $post_id, $key, $option_value );
					}else{
						update_post_meta( $post_id, $key, wp_kses_post($val) );
					}
				}
			}
			do_action('viem_save_meta_boxes',$post_id, $post);
		}
		
		public function enqueue_scripts(){
			wp_enqueue_style('dt-meta-box',DTINC_ASSETS_URL.'/css/meta-box.css',null,DTINC_VERSION);
			wp_enqueue_script('dt-meta-box',DTINC_ASSETS_URL.'/js/meta-box.js',array('jquery','jquery-ui-sortable'),DTINC_VERSION,true);
		}
		
	}

	new viem_Metaboxes();

endif;