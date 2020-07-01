<?php
/*
 * DawnThemes Meta Box API
 * 
 * This class loads all the methods and helpers specific to build a meta box.
 */

if (! class_exists ( 'DawnThemes_Metaboxes' )) :
	class DawnThemes_Metaboxes {
		
		/* variable to store the meta box array */
		private $meta_box;
		
		function __construct($meta_box) {
			if( !is_admin() ){
				return;
			}
			
			global $dt_meta_boxs;
			
			if( ! isset( $dt_meta_boxs ) ){
				$dt_meta_boxs = array();
			}
			
			$dt_meta_boxs[] = $meta_box;
			
			$this->meta_box = $meta_box;
			
			add_action ( 'add_meta_boxes', array (&$this, 'add_meta_boxes' ), 30 );
			add_action ( 'save_post', array (&$this,'save_meta_boxes' ), 1, 2 );
			
			add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ) );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ) );
			
		}
		
		public function add_meta_boxes() {
			foreach ( $this->meta_box as $meta_box ) {
				add_meta_box ( $this->meta_box['id'], $this->meta_box['title'], 'dawnthemes_render_meta_boxes', $this->meta_box['post_type'], $this->meta_box['context'], $this->meta_box['priority'], $this->meta_box );
			}
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
				if(get_post_format() == 'video' && dawnthemes_get_theme_option('blog_get_video_thumbnail','0') == '1'){
					$_dt_video_embed = $dt_meta['_dt_video_embed'];
					if(dawnthemes_is_video_support($_dt_video_embed) && ($_dt_video_embed != dawnthemes_get_post_meta('video_embed_hidden'))){
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
			do_action('dt_metabox_save',$post_id);
		}
		
		public function enqueue_scripts(){
			wp_enqueue_style('dt-meta-box',DTINC_ASSETS_URL.'/css/meta-box.css',null,DTINC_VERSION);
			wp_enqueue_script('dt-meta-box',DTINC_ASSETS_URL.'/js/meta-box.js',array('jquery','jquery-ui-sortable'),DTINC_VERSION,true);
		}
		
	}

endif;

/*
 * This method instantiates the meta box class & builds the UI.
 * 
 * @uses DawnThemes_Metaboxes()
 * 
 * @params 	array 	array of arguments to create a meta box
 * @return 	void
 * 
 * @access 	public
 * @since	1.0
 */
if( !function_exists('dawnthemes_register_meta_box') ){
	function dawnthemes_register_meta_box( $args = array() ){
		if( ! $args )
			return;
		
		$dt_meta_box = new DawnThemes_Metaboxes( $args );
	}
}
