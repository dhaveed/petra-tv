<?php
/**
 * Core features for all themes
 *
 * @package danwthemes
 */

if( ! class_exists('viem_core') ){
	class viem_core{
		
		public function __construct(){
			add_action('init', array(&$this, 'init'));
			
			// Add author social link meta
			add_action( 'show_user_profile', array(&$this,'show_extra_profile_fields') );
			add_action( 'edit_user_profile', array(&$this,'show_extra_profile_fields') );
			add_action( 'personal_options_update', array(&$this,'save_extra_profile_fields') );
			add_action( 'edit_user_profile_update', array(&$this,'save_extra_profile_fields') );
		}
		
		public function init(){}
		
		public function show_extra_profile_fields( $user ){
			if( defined('DAWNTHEMES_PREVIEW') ){
				?>
			 	<table class="form-table viem-custom-profile-picture">
						<tr>
							<th><label for="viem_custom_profile_picture">Custom Profile Picture</label></th>
							<td>
								<span class="description">Image URL</span>
								<input type="text" name="viem_custom_profile_picture" id="viem_custom_profile_picture" value="<?php echo esc_attr( get_the_author_meta( 'viem_custom_profile_picture', $user->ID ) ); ?>" class="regular-text" />
							</td>
						</tr>
				</table>
			 	<?php
			}

	 		$author_links = array(
	 			'facebook' => 'viem-user-facebook',
	 			'twitter' => 'viem-user-twitter',
	 			'google-plus' => 'viem-user-google',
	 			'youtube' => 'viem-user-youtube',
	 			'flickr' => 'viem-user-flickr',
	 			'instagram' => 'viem-user-instagram',
	 			'pinterest' => 'viem-user-pinterest',
	 			'envelope' => 'viem-user-envelope',
	 		);
		 	?>
		 	<h3><?php esc_html_e('Social Accounts','viem') ?></h3>
		 	<table class="form-table viem-social-info">
		 		<?php foreach( $author_links as $account => $key ): ?>
					<tr>
						<th><label for="<?php echo esc_attr($key); ?>"><?php echo ($account == 'envelope') ? 'Email' : $account ; ?></label></th>
						<td>
							<span class="description"><?php esc_html_e('Account URL','viem')?></span>
							<input type="text" name="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr( get_the_author_meta( $key, $user->ID ) ); ?>" class="regular-text" />
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		 	<?php
	 	}
	 	
	 	public function save_extra_profile_fields( $user_id ){
	 		if( !current_user_can( 'edit_user', $user_id ) )
	 			return false;

	 		update_user_meta( $user_id, 'viem_custom_profile_picture', sanitize_text_field($_POST[sanitize_key('viem_custom_profile_picture')]) );
	 			 		
	 		$author_links = array(
	 			'facebook' => 'viem-user-facebook',
	 			'twitter' => 'viem-user-twitter',
	 			'google-plus' => 'viem-user-google',
	 			'youtube' => 'viem-user-youtube',
	 			'flickr' => 'viem-user-flickr',
	 			'instagram' => 'viem-user-instagram',
	 			'pinterest' => 'viem-user-pinterest',
	 			'envelope' => 'viem-user-envelope',
	 		);
	 		
	 		foreach($author_links as $account => $key){
	 			update_user_meta( $user_id, $key, sanitize_text_field($_POST[sanitize_key($key)]) );
	 		}
		}

		/**
		 * Get an attachment ID given a URL.
		 *
		 * @param string $url
		 *
		 * @return int Attachment ID on success, 0 on failure
		 */
		public static function get_attachment_id( $url ) {
			$attachment_id = 0;
			$dir = wp_upload_dir();
			if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
				$file = basename( $url );
				$query_args = array(
					'post_type'   => 'attachment',
					'post_status' => 'inherit',
					'fields'      => 'ids',
					'meta_query'  => array(
						array(
							'value'   => $file,
							'compare' => 'LIKE',
							'key'     => '_wp_attachment_metadata',
						),
					)
				);
				$query = new WP_Query( $query_args );
				if ( $query->have_posts() ) {
					foreach ( $query->posts as $post_id ) {
						$meta = wp_get_attachment_metadata( $post_id );
						$original_file       = basename( $meta['file'] );
						$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
						if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
							$attachment_id = $post_id;
							break;
						}
					}
				}

				wp_reset_postdata();
				
			}
			return $attachment_id;
		}
		// END Class
	}
	new viem_core();
}