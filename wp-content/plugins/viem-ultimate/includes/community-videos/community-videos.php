<?php
// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if( ! class_exists( 'viem_community_videos_main' ) ){
	/**
	 * DawnThemes Community Posts main class
	 *
	 * @package viem_community_videos_main
	 * @author DawnThemes.
	 * @since  1.0
	 */
	class viem_community_videos_main{
		
		/**
		 * default event status
		 * @var string
		 */
		public $defaultStatus;
		
		/**
		 * setting to allow anonymous submissions
		 * @var bool
		 */
		public $allowAnonymousSubmissions;
		
		/**
		 * Singleton instance variable
		 * @var object
		 */
		private static $instance;
		
		/*
		 * setting community video add page
		 */
		public $communityAddPage;
		/*
		 * setting community video list page
		 */
		public $communityListPage;
		
		public $no_results_text;
		
		public function __construct(){
			
			$this->no_results_text = esc_attr__('No results match', 'viem-ultimate');
			
			$this->defaultStatus	= viem_get_theme_option('community_default_status', 'pending');
			$this->allowAnonymousSubmissions = viem_get_theme_option('allow_anonymous_submissions', 1);
			$this->communityAddPage	=	viem_get_theme_option('community-add-page', '');
			$this->communityListPage	=	viem_get_theme_option('community-list-page', '');
			
			add_shortcode( 'viem_community_add', array(&$this, 'sc_community_add') );
			add_shortcode( 'viem_community_list', array(&$this, 'sc_community_list') );
			
			add_action('init', array(&$this, 'init'));
			
		}
		
		public function init(){
			add_action('wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
			
			add_action('wp_ajax_viem_upload_video', array(&$this, 'viem_upload_video'));
			add_action('wp_ajax_nopriv_viem_upload_video', array(&$this, 'viem_upload_video'));
		}
		
		public function enqueue_scripts(){
			wp_enqueue_script('chosen');
			wp_enqueue_style('chosen');
			wp_enqueue_script('validate-script', viem_ultimate_URL .'/includes/community-videos/assets/js/jquery.validate.min.js', array('jquery'));
			wp_enqueue_script('viem-commnunity-videos', viem_ultimate_URL . '/includes/community-videos/assets/js/community-videos.js', array('jquery'), viem_version , true);
			
			wp_localize_script('viem-commnunity-videos', 'viem_cvajax', array(
				'ajaxurl'	=> admin_url( 'admin-ajax.php' ),
			));
		}
		
		public static function submit_video_btn( $echo = true ){
			if( viem_get_theme_option('allow_community_videos', 0) != 1 ){
				return;
			}
			$html = '';
			ob_start();
			?>
			<div class="viem-submit-video">
				<a id="viem-btn-submit-v" class="viem-main-color-bg" href="<?php echo esc_attr( get_permalink( viem_get_theme_option('community-add-page', '') ) ); ?>"><span><?php echo esc_html__('Submit Video', 'viem-ultimate'); ?></span> <i class="fa fa-upload"></i></a>
			</div>
			<?php
			if( $echo == true ){
				echo ob_get_clean();
			}else{
				return ob_get_clean();
			}
		}
		
		public function sc_community_add( $atts, $content){
			if( viem_get_theme_option('allow_community_videos', 0) != 1 ){
				return '<div id="viem_video_upload-result"><span class="alert alert-warning">' . esc_html__('Video submission form has been disabled.', 'viem-ultimate') . '</span></div>';
			}
			ob_start();
			?>
				<div class="viem-community-wrapper">
					<header><h2><?php esc_html_e('Add New Video', 'viem-ultimate');?></h2></header>
					<?php 
					// If current user is not logged in but logging in required
					if( viem_get_theme_option('allow_anonymous_submissions', 1) != 1 && ! is_user_logged_in()){?>
						<div class="upload-result">
							<span class="alert alert-warning"><?php printf( __('Please <a href="%s">Login</a> to submit a video.', 'viem-ultimate'), wp_login_url( get_permalink() ) );?></span>
						</div>
					<?php
					}else{ ?>
						<form id="viem_posts_form" class="viem_posts_form" action="<?php echo esc_url( home_url('/') )?>" method="POST" enctype="multipart/form-data">
						
							<?php if( is_user_logged_in() ):
								$current_user = wp_get_current_user();
								$user_id = $current_user->ID; 
								?>
								<input type="hidden" name="viem_cv_user_id" id="viem_cv_user_id" value="<?php echo esc_attr($user_id);?>"/>								
							<?php else: ?>
								<div class="cv-input">
									<label><?php esc_html_e('Your Name', 'viem-ultimate'); ?> <span class="required">*</span></label>
									<input class="form-control required" type="text" name="viem_cv_username" id="viem_cv_username" value="<?php echo isset( $_POST['viem_cv_username'] ) ? $_POST['viem_cv_username'] : '';?>" placeholder="<?php echo esc_attr__('Your Name', 'viem-ultimate');?>"/>
								</div>
								<div class="cv-input">
									<label><?php esc_html_e('Your Email', 'viem-ultimate'); ?> <span class="required">*</span></label>
									<input class="form-control required email" type="email" name="viem_cv_useremail" id="viem_cv_useremail" value="<?php echo isset( $_POST['viem_cv_useremail'] ) ? $_POST['viem_cv_username'] : '';?>" placeholder="<?php echo esc_attr__('Your Email', 'viem-ultimate');?>"/>
								</div>
							<?php endif; ?>
							
								<div class="cv-input">
									<label><?php esc_html_e('Video Title', 'viem-ultimate'); ?> <span class="required">*</span></label>
									<input class="form-control required" type="text" name="viem_cv_vtitle" id="viem_cv_vtitle" value="<?php echo isset( $_POST['viem_cv_vtitle'] ) ? $_POST['viem_cv_vtitle'] : '';?>" placeholder="<?php echo esc_attr__('Video Title', 'viem-ultimate');?>"/>
								</div>
								<div class="cv-input videos-community-post-content">
									<label><?php esc_html_e('Video Description', 'viem-ultimate'); ?></span></label>
									<?php $this->form_content_editor();?>
								</div>
								
								<div class="cv-input videos-community-player-type">
									<label><?php esc_html_e('Select video player type', 'viem-ultimate'); ?></label>
									<select id="viem_cv_video_type" name="viem_cv_video_type" class="">
										<option value="youtube"><?php esc_html_e('YouTube', 'viem-ultimate'); ?></option>
										<option value="vimeo"><?php esc_html_e('Vimeo', 'viem-ultimate'); ?></option>
										<option value="HTML5"><?php esc_html_e('HTML5 (self-hosted)', 'viem-ultimate'); ?></option>
									</select>
								</div>
								<div class="cv-input videos-community-youtube_id_field">
									<label><?php esc_html_e('YouTube ID', 'viem-ultimate'); ?></label>
									<input type="text" name="viem_cv_youtube_id" id="viem_cv_youtube_id" value="" placeholder="0dJO0HyE8xE" class="form-control required">
									<span class="description"><?php esc_html_e( 'last part of the URL https://www.youtube.com/watch?v=0dJO0HyE8xE', 'viem-ultimate' ); ?></span>
								</div>
								<div class="cv-input videos-community-vimeo_id_field hidden">
									<label><?php esc_html_e('Vimeo ID', 'viem-ultimate'); ?></label>
									<input type="text" name="viem_cv_vimeo_id" id="viem_cv_vimeo_id" value="" placeholder="119641053" class="form-control">
									<span class="description"><?php esc_html_e( 'last part of the URL http://vimeo.com/119641053', 'viem-ultimate' ); ?></span>
								</div>
								<div class="cv-input videos-community-mp4_field hidden">
									<label><?php esc_html_e('MP4 Video URL', 'viem-ultimate'); ?></label>
									<input type="text" name="viem_cv_video_mp4" id="viem_cv_video_mp4" value="" placeholder="<?php echo esc_attr_e('Enter .mp4 video URL', 'viem-ultimate')?>" class="form-control">
									<span class="description"><?php esc_html_e( 'HTML5 video mp4,  HLS m3u8 (url)', 'viem-ultimate' ); ?></span>
								</div>
								
								<div class="cv-input">
									<label><?php esc_html_e('Video Image ', 'viem-ultimate'); ?></label>
									<input class="" type="file" name="viem_cv_vimage" id="viem_cv_vimage" accept="image/*"/>
								</div>
								
								<div class="cv-input">
									<label><?php esc_html_e('Video Categories', 'viem-ultimate'); ?></label>
									<?php $this->module_taxonomy(array('taxonomy' => 'video_cat', 'param_name' => 'viem_cv_pcategory'), '', esc_html__('Select from existing categories', 'viem-ultimate') );?>
								</div>
								
								<div class="cv-input">
									<label><?php esc_html_e('Video Tags', 'viem-ultimate'); ?></label>
									<?php $this->module_taxonomy(array('taxonomy' => 'video_tag', 'param_name' => 'viem_cv_ptag'), '', esc_html__('Select from existing tags', 'viem-ultimate') );?>
								</div>
								
								<div class="cv-input">
									<label><?php esc_html_e('Channels', 'viem-ultimate'); ?></label>
									<?php $this->module_select_channels( array('post_type' => 'viem_channel', 'param_name' => 'viem_cv_channel_id' ) , '', esc_html__('Select from existing channels', 'viem-ultimate') );?>
								</div>
								
								<div class="cv-input">
									<label><?php esc_html_e('Playlists', 'viem-ultimate'); ?></label>
									<?php $this->module_select_playlists( array('post_type' => 'viem_playlist', 'param_name' => 'viem_cv_playlist_id' ) , '', esc_html__('Select from existing playlists', 'viem-ultimate'));?>
								</div>
							
							
							<div class="viem_video_submit">
								<input class="button" value="<?php esc_attr_e('Submit Video', 'viem-ultimate')?>" name="submit_video" type="submit">
							</div>
							
							<?php wp_nonce_field('viem_submit_video_form', 'viem_submit_video_form_nonce'); ?>
							
						</form>
						
						<div id="viem_video_upload-result">
						<!-- result of upload goes here -->
						</div>
					<?php }
					?>
				</div>
			<?php
			return ob_get_clean();
		}
		
		public function form_content_editor($video = null){
			
			$post_content = isset( $_POST['viem_cv_pcontent'] ) ? $_POST['viem_cv_pcontent'] : '';
			
			// if the admin wants the rich editor, and they are using WP 3.3, show the WYSIWYG, otherwise default to just a text box
			$useVisualEditor = true;
			if ( $useVisualEditor && function_exists( 'wp_editor' ) ) {
				$settings = array(
					//'wpautop' => true,
					'media_buttons' => false,
					'editor_class' => 'frontend',
					'textarea_rows' => viem_get_theme_option('default_post_edit_rows', 5),
				);
			
				wp_editor( $post_content, 'viem_cv_pcontent', $settings );
			} else {
			?><textarea id="post_content" name="viem_cv_pcontent"><?php
					echo esc_textarea( $post_content );
				?></textarea><?php
			}
		}
		
		public function module_taxonomy( $args = array( 'taxonomy' => 'video_cat', 'param_name' => 'category' ), $value = '', $placeholder = '' ){
			$taxonomy = 'category';
			if( isset($args['taxonomy']) )
				$taxonomy = $args['taxonomy'];
			
			$categories = get_categories( array( 'taxonomy' => $taxonomy , 'orderby' => 'NAME', 'hide_empty'=> 0, 'order' => 'ASC', ) );
			
			$class = 'viem-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = '';
			$html.= '<div class="viem-section-taxonomy hidden">';
			$html.= '<select id="' . $args['param_name'] . '" ' .
				 ( isset( $args['single_select'] ) ? '' : 'multiple="multiple"' ) . ' data-placeholder="'.esc_attr($placeholder).'" data-no_results_text="'.esc_attr($this->no_results_text).'" class="' . $class . '">';
			$r = array();
			$r['pad_counts'] = 1;
			$r['hierarchical'] = 1;
			$r['hide_empty'] = 0;
			$r['show_count'] = 0;
			$r['selected'] = $selected_values;
			$r['menu_order'] = false;
			$html.= dt_walk_category_dropdown_tree( $categories, 0, $r );
			$html.= '</select>';
			$html.= '<input id= "' . $args['param_name'] .
				 '" type="hidden" class="wpb_vc_param_value viem-chosen-value wpb-textinput" name="' .
				 $args['param_name'] . '" value="' . $value . '" />';
			$html.= '</div>';
			echo ( $html );
		}
		
		public function module_select_channels( $args = array( 'post_type' => 'viem_channel', 'param_name' => 'viem_cv_channel_id' ), $value = '', $placeholder = '' ){
			
			$options = $this->viem_get_theme_options_select_post('viem_channel');
			
			$class = 'viem-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = '';
			$html.= '<div class="viem-section-taxonomy hidden">';
			$html.= '<select id="' . $args['param_name'] . '" ' .
				( isset( $args['single_select'] ) ? '' : 'multiple="multiple"' ) . '  data-placeholder="'.esc_attr($placeholder).'" data-no_results_text="'.esc_attr($this->no_results_text).'" class="' . $class . '">';
			
			if( !empty($options) ){
				foreach ( $options as $v => $t ){
					$html.= '<option value="'.esc_attr($v).'">'.esc_html($t).'</option>';
				}
			}
			
			$html.= '</select>';
			$html.= '<input id= "' . $args['param_name'] .
			'" type="hidden" class="wpb_vc_param_value viem-chosen-value wpb-textinput" name="' .
			$args['param_name'] . '" value="' . $value . '" />';
			$html.= '</div>';
			echo ( $html );
		}
		
		public function module_select_playlists( $args = array( 'post_type' => 'viem_playlist', 'param_name' => 'viem_cv_playlist_id' ), $value = '', $placeholder = '' ){
			
			$options = $this->viem_get_theme_options_select_post('viem_playlist');
			
			$class = 'viem-chosen-multiple-select';
			$selected_values = explode( ',', $value );
			$html = '';
			$html.= '<div class="viem-section-taxonomy hidden">';
			$html.= '<select id="' . $args['param_name'] . '" ' .
				( isset( $args['single_select'] ) ? '' : 'multiple="multiple"' ) . '  data-placeholder="'.esc_attr($placeholder).'" data-no_results_text="'.esc_attr($this->no_results_text).'" class="' . $class . '">';
			
			if( !empty($options) ){
				foreach ( $options as $v => $t ){
					$html.= '<option value="'.esc_attr($v).'">'.esc_html($t).'</option>';
				}
			}
			
			$html.= '</select>';
			$html.= '<input id= "' . $args['param_name'] .
			'" type="hidden" class="wpb_vc_param_value viem-chosen-value wpb-textinput" name="' .
			$args['param_name'] . '" value="' . $value . '" />';
			$html.= '</div>';
			echo ( $html );
		}
		
		public function viem_get_theme_options_select_post($post_type='post'){
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
		
		public function max_file_size_exceeded(){
			return (
				isset( $_SERVER['CONTENT_LENGTH'] ) && (int) $_SERVER['CONTENT_LENGTH'] > wp_max_upload_size()
				);
		}
		
		public function viem_upload_video(){
			$data = array();
			
			if( isset( $_POST['nonce'] ) && wp_verify_nonce( $_POST['nonce'], 'viem_submit_video_form' ) ){
				
				// Get clean input variables
				$title = wp_strip_all_tags( $_POST['post_title'] );
				$category = sanitize_text_field( $_POST['post_category'] );
				$tags = sanitize_text_field( $_POST['post_tag'] );
				$channel = sanitize_text_field( $_POST['post_channel'] );
				$playlist = sanitize_text_field( $_POST['post_playlist'] );
				$content = $_POST['post_content'];
				$video_type = $_POST['video_type'];
				$youtube_id = sanitize_text_field($_POST['youtube_id']);
				$vimeo_id = sanitize_text_field($_POST['vimeo_id']);
				$video_mp4 = sanitize_text_field($_POST['video_mp4']);
				$userName = isset($_POST['userName']) ? wp_strip_all_tags( $_POST['userName'] ) : false;
				$userEmail = isset($_POST['userEmail']) ? sanitize_text_field( $_POST['userEmail'] ) : false;
				
				$cats = explode(',', $category);
				$tags = explode(',', $tags);
				$channels = explode(',', $channel);
				$playlists = explode(',', $playlist);
				
				$post_args = array(
					'post_type' => 'viem_video',
					'post_title' => wp_strip_all_tags($title),
					'post_content' => wp_filter_post_kses($content),
					'post_status' => $this->defaultStatus,
				);
				
				// Insert post
				$post_id = wp_insert_post( $post_args );
				
				if( $post_id ){
					
					// Handles the upload
					if ( isset( $_FILES['post_image']['name'] ) && ! empty( $_FILES['post_image']['name'] ) ) {
						$attachment_id = $this->upload_user_file( $_FILES['post_image'], wp_strip_all_tags($title) );
						
						if( false !== $attachment_id ){
							set_post_thumbnail($post_id, $attachment_id); // Set featured image
						}
					}

					// Set tax for post submited
					if( ! empty($cats) ){
						$terms_id = array();
						foreach ($cats as $cat){
							// Get cat id by slug
							$term = get_term_by('slug', $cat, 'video_cat');
							$terms_id[] = $term->term_id;
						}
						wp_set_post_terms( $post_id, $terms_id, 'video_cat', true );
					}
					
					if( ! empty($tags) ){
						$terms_id = array();
						foreach ($tags as $tag){
							// Get cat id by slug
							$term = get_term_by('slug', $tag, 'video_tag');
							$terms_id[] = $term->term_id;
						}
						wp_set_post_terms( $post_id, $terms_id, 'video_tag', true );
					}
					
					// Update post meta
					if( $video_type ){
						update_post_meta($post_id, '_dt_video_type', $video_type);
					}
					if( $youtube_id ){
						update_post_meta($post_id, '_dt_youtube_id', $youtube_id);
					}
					if( $vimeo_id ){
						update_post_meta($post_id, '_dt_vimeo_id', $vimeo_id);
					}
					if( $video_mp4 ){
						update_post_meta($post_id, '_dt_video_mp4', $video_mp4);
					}
					
					if( $channels ){
						update_post_meta($post_id, '_dt_video_channel_id', $channels);
					}
					if( $playlists ){
						update_post_meta($post_id, '_dt_video_playlist_id', $playlists);
					}
					if( $userName ){
						update_post_meta($post_id, 'viem_cv_userName', $userName);
					}
					if( $userEmail ){
						update_post_meta($post_id, 'viem_cv_userEmail', $userEmail);
					}
					
					if( get_post_status( $post_id ) == 'publish' ){
						$data['success'] = esc_attr__('Your post has been published successfully.', 'viem-ultimate');
					}elseif ( get_post_status( $post_id ) == 'pending' || get_post_status( $post_id ) == 'draft' ){
						$data['success'] = esc_attr__('Your post has been uploaded. It will be published after being reviewed by site admin.', 'viem-ultimate');
					}
					
				}else{
					$data['error'] = esc_html__('Post submission failed. Please try later.','viem-ultimate');
				}
				
			}else{
				$data['error'] = esc_html__('Nonce validation failed.', 'viem-ultimate');
			}
			
			echo json_encode($data);
			die();
		}
		
		/**
		 * Insert an attachment.
		 */
		private function upload_user_file( $file = array(), $title = false ) {
		
			require_once ABSPATH.'wp-admin/includes/admin.php';
		
			$file_return = wp_handle_upload($file, array('test_form' => false));
		
			if(isset($file_return['error']) || isset($file_return['upload_error_handler'])){
		
				return false;
		
			}else{
		
				$filename = $file_return['file'];
		
				$attachment = array(
					'post_mime_type' => $file_return['type'],
					'post_content' => '',
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'guid' => $file_return['url']
				);
		
				if($title){
					$attachment['post_title'] = $title;
				}
		
				$attachment_id = wp_insert_attachment( $attachment, $filename );
		
				require_once(ABSPATH . 'wp-admin/includes/image.php');
					
				$attachment_data = wp_generate_attachment_metadata( $attachment_id, $filename );
		
				wp_update_attachment_metadata( $attachment_id, $attachment_data );
		
				if( 0 < intval( $attachment_id ) ) {
					return $attachment_id;
				}
			}
		
			return false;
		}
		
		public function insert_attachment( $file_handler, $post_id, $set_post_thumbnail = false ){
			
			// check to make sure it's a successful upload
			if( $_FILES[ $file_handler ]['error'] !== UPLOAD_ERR_OK ){
				return false;
			}
			$uploaded_file_type = wp_check_filetype( basename( $_FILES[ $file_handler ]['name'] ) );
			$attach_id = false;
			
			if( ! function_esists('media_handle_upload') ){
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
			}
			
			$allowed_file_types = array( 'image/jpg', 'image/jpeg', 'image/gif', 'image/png' );
			if( in_array( $uploaded_file_type['type'], $allowed_file_types ) ){
				$attach_id = media_handle_upload($file_handler, $post_id);
			}else{
				return false;
			}
			
			if ( false !== $attach_id ) {
				$image_path = get_attached_file( $attach_id );
				$editor = wp_get_image_editor( $image_path );
				$image = @getimagesize( $image_path );
				$status = true;
			
				if ( is_wp_error( $editor ) ) {
					$status = false;
				} elseif ( false === $image ) {
					$status = false;
				} elseif ( empty( $image[0] ) || ! is_numeric( $image[0] ) || empty( $image[1] ) || ! is_numeric( $image[1] ) ) {
					$status = false;
				} elseif ( empty( $image[2] ) || ! in_array( $image[2], array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG ) ) ) {
					$status = false;
				}
			
				if ( false === $status ) {
					// Purge this weird file!
					wp_delete_attachment( $attach_id, true );
					return false;
				}
			
				if ( true === $set_post_thumbnail ) {
					update_post_meta( $post_id, '_thumbnail_id', $attach_id );
				}
			}
			
			return $attach_id;
		
		}
		
		public static function login_form( $caption = '' ){
			/*
			 * Fires immediately before the login form is rendered (where Community Videos requires that the user logs in).
			 */
			do_action('viem_community_before_login_form');
			
			ob_start();
			echo '<div class="login">';
			echo '<p class="message">' . $caption . '</p>';
			wp_login_form();
			wp_register('<div class="register">', '</div>', true);
			
			echo '</div>';
			/*
			 * Fires immediately after the login form is rendered (where Community Videos requires that the user logs in).
			 */
			do_action('viem_community_after_login_form');
			
			return ob_get_clean();
		}
		
		public static function sc_community_list( $atts, $content){
			$output = '';
			
			do_action('viem_cv_before_video_list_page');
			$output .= '<div class="viem-community-videos-content">';
			
			if( is_user_logged_in() ){
				
				$current_user = wp_get_current_user();
				
				$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
				
				$args = apply_filters('viem_cv_my_videos_query', array(
					'posts_per_page'	=> viem_get_theme_option('videos-per-page', 10),
					'paged'				=> $paged,
					'author'			=> $current_user->ID,
					'post_type'			=> 'viem_video',
					'post_status'		=> array('pending', 'draft', 'publish'),
					'orderby'			=> 'date',
					'order'				=> 'DESC',
					's'					=> isset( $_GET['video-search'] ) ? esc_html( $_GET['event-search'] ) : '',
				));
				
				$videos = new WP_Query($args);
				
				if( $videos->have_posts() ){
					
					$style = 'grid'; // list || grid || masonry
					$pagination = 'infinite_scroll'; // wp_pagenavi || loadmore || infinite_scroll
					$loadmore_text = viem_get_theme_option('blog-loadmore-text', esc_html__('Load More','viem-ultimate'));
					$columns = 1;
					$img_size = viem_get_theme_option('video-image-size', 'default');
					if( $style == 'grid' || $style == 'masonry' ){
						$columns = viem_get_theme_option('videos-columns', 3);
					}
					
					$itemSelector = '';
					$itemSelector .= (($pagination === 'infinite_scroll') ? '.viem_video.infinite-scroll-item':'');
					$itemSelector .= (($pagination === 'loadmore') ? '.viem_video.loadmore-item':'');
					
					?>
					<div data-itemselector="<?php echo esc_attr($itemSelector)  ?>"  class="posts viem-videos <?php echo (($pagination === 'loadmore') ? ' loadmore':''); ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll':'') ?><?php echo (($style === 'masonry') ? ' masonry':'') ?>" data-paginate="<?php echo esc_attr($pagination) ?>" data-layout="<?php echo esc_attr($style) ?>"<?php echo ($style === 'masonry') ? ' data-masonry-column="'.esc_attr($columns).'"':''?>>
						<div class="posts-wrap<?php echo (($pagination === 'loadmore') ? ' loadmore-wrap':'') ?><?php echo (($pagination === 'infinite_scroll') ? ' infinite-scroll-wrap':'') ?><?php echo (($style === 'masonry') ? ' masonry-wrap':'') ?> posts-layout-<?php echo esc_attr($style)?><?php if( $style == 'default' || $style == 'grid' || $style == 'masonry') echo ' v-grid-list cols_'.$columns; ?>">
						<?php
						// Start the Loop.
						while ( $videos->have_posts() ) : $videos->the_post();?>
							<?php
							$post_class = ' v-grid-item ';
							$post_class .= (($pagination === 'infinite_scroll') ? ' infinite-scroll-item':'');
							$post_class .= (($pagination === 'loadmore') ? ' loadmore-item':'');
							if($style == 'masonry')
								$post_class.=' masonry-item';
							?>
							<?php
								viem_dt_get_template("video-list.php", array(
									'post_class' => $post_class,
									'columns' => $columns,
									'img_size'		=> $img_size,
								),
								'template-parts/community', 'template-parts/community'
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
								viem_dt_paging_nav_ajax($loadmore_text, $videos);
								$paginate_args = array('show_all'=>true);
								break;
							case 'infinite_scroll':
								$paginate_args = array('show_all'=>true);
								break;
						}
						viem_paginate_links($paginate_args,  $videos);
						?>
					</div>
				<?php
				}else{
					echo '<div class="viem-message-renderer"><div class="message-renderer align-center">'. esc_html__("You have no videos", 'viem-ultimate') .'</div></div>';
				}
				wp_reset_query();
				
			}else{
				do_action( 'viem_cv_video_list_login_form' );
				$output .= self::login_form( esc_html__('Please log in to view your videos', 'viem-ultimate') );
			}
			
			$output .= '</div>';
			do_action('viem_cv_after_video_list_page');
			
			return $output;
		}
		
		
	}
	new viem_community_videos_main();
}