<?php
/*
Plugin Name: DawnThemes Instagram
Plugin URI: http://dawnthemes.com/
Description: Display your Instagram gallery.
Version: 1.0.2
Author: DawnThemes
Author URI: http://dawnthemes.com/
Text Domain: dawnthemes-instagram
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

if ( ! defined( 'DAWNTHEMES_INSTAGRAM_URL' ) )
	define( 'DAWNTHEMES_INSTAGRAM_URL' , plugin_dir_url(__FILE__));

if ( ! defined( 'DAWNTHEMES_INSTAGRAM_DIR' ) )
	define( 'DAWNTHEMES_INSTAGRAM_DIR' , plugin_dir_path(__FILE__));

Class Dawnthemes_Instagram{
	
	
	public function __construct(){	
		
		add_action('init', array(&$this,'init'));
		add_action('wp_enqueue_scripts',array(&$this,'enqueue_styles'));		
	}
	
	public function init(){
		load_plugin_textdomain( 'dawnthemes-instagram' , false, basename(DAWNTHEMES_INSTAGRAM_DIR) . '/languages');
		// require vc
		if(!defined('WPB_VC_VERSION')){
			add_action('admin_notices', array(&$this, 'showVcVersionNotice'));
		}else{
			if( defined('WPB_VC_VERSION') && function_exists('vc_add_param') ){
			vc_map( 
				array( 
					'base' => 'dawnthemes_instagram', 
					"category" => __( "DawnThemes", 'dawnthemes-instagram' ), 
					'name' => __( 'DT Instagram', 'dawnthemes-instagram' ),
					'description' => __( 'Instagram.', 'dawnthemes-instagram' ), 
					'class' => 'dt-vc-element dt-vc-element-dt_instagram', 
					'icon' => 'dt-vc-icon-dt_instagram',
					'show_settings_on_create' => true, 
					'params' => array(
						array( 
							'param_name' => 'username', 
							'heading' => __( 'Instagram Username', 'dawnthemes-instagram' ), 
							'description' => '', 
							'type' => 'textfield', 
							'admin_label' => true ), 
						array( 
							'param_name' => 'images_number', 
							'heading' => __( 'Number of Images to Show', 'dawnthemes-instagram' ), 
							'type' => 'textfield', 
							'value' => '12' ), 
						array( 
							'param_name' => 'refresh_hour', 
							'heading' => __( 'Check for new images on every (hours)', 'dawnthemes-instagram' ), 
							'type' => 'textfield', 
							'value' => '5' )
						),

				)
			);

			add_shortcode('dawnthemes_instagram', array(&$this, 'dawnthemes_instagram_sc'));
			}
		}
	}

	public function enqueue_styles(){
		wp_enqueue_style( 'dawnthemes-instagram', DAWNTHEMES_INSTAGRAM_URL .'assets/css/style.min.css');
	}

	public function showVcVersionNotice(){
		$plugin_data = get_plugin_data(__FILE__);
		echo '
		<div class="updated">
          <p>'.sprintf(__('<strong>%s</strong> Compatible with <strong>Visual Composer</strong> plugin. So You can install <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be used into Visual Composer page builder.', 'dawnthemes-instagram'), $plugin_data['Name']).'</p>
        </div>';
	}

	public function dawnthemes_instagram_sc( $atts, $content = null ){
		extract( shortcode_atts( array(
			'username'			=> '',
			'images_number'		=> '12',
			'refresh_hour'		=> '5',
			'size'				=> 'large',
		), $atts ) );
		$username = strtolower($username);
		ob_start();
		?>
		<div class="dawnthemes-instagram">
			<div class="instagram-wrap">
				<?php ;
				$images_data = dawnthemes_instagram($username,$images_number, $refresh_hour);

				if ( !is_wp_error($images_data) && ! empty( $images_data ) ) {
					?>
					<ul class="dt-instagram__list">
						<?php foreach ((array)$images_data as $item):?>
						<li class="dt-instagram__item">
							<a href="<?php echo esc_attr( $item['link'])?>" title="<?php echo esc_attr($item['description'])?>" target="_blank">
								<img src="<?php echo esc_attr($item[$size])?>"  alt="<?php echo esc_attr($item['description'])?>"/>
							</a>
						</li>
						<?php endforeach;?>
					</ul>
					<?php
				} else {
					echo '<div class="text-center" style="margin-bottom:30px">';
					if(is_wp_error($images_data)){
						echo implode($images_data->get_error_messages());
					}else{
						echo esc_html__( 'Instagram did not return any images.', 'dawnthemes-instagram' );
					}
					echo '</div>';
				};
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	//
}
new Dawnthemes_Instagram();

function dawnthemes_instagram($username,$images_number=12,$refresh_hour){

	$username = strtolower( $username );
    $username = str_replace( '@', '', $username );
    $transient_prefix = "u";

	if (false === ($instagram = get_transient('instagram-'.sanitize_title_with_dashes($username)))) {

		//$remote = wp_remote_get('http://instagram.com/'.trim($username),array( 'decompress' => false ));
		$remote = wp_remote_get( 'https://www.instagram.com/'.trim( $username ), array( 'sslverify' => false, 'timeout' => 60 ) );

		if ( is_wp_error( $remote ) )
			return new WP_Error( 'site_down',esc_html__( 'Unable to communicate with Instagram.', 'dawnthemes-instagram' ));

		if ( 200 != wp_remote_retrieve_response_code( $remote ) )
			return new WP_Error( 'invalid_response',esc_html__( 'Instagram did not return a 200.', 'dawnthemes-instagram' ));

		$shards = explode( 'window._sharedData = ', $remote['body'] );
        $insta_json = explode( ';</script>', $shards[1] );
        $insta_array = json_decode( $insta_json[0], TRUE );

		if ( !$insta_array )
			return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'dawnthemes-instagram' ) );

		if ( isset( $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'] ) ) {
            $images = $insta_array['entry_data']['ProfilePage'][0]['graphql']['user']['edge_owner_to_timeline_media']['edges'];
        } else {
            return new WP_Error( 'bad_json_2', __( 'Instagram has returned invalid data.', 'dawnthemes-instagram' ) );
        }

        if ( ! is_array( $images ) )
            return new WP_Error( 'bad_array', __( 'Instagram has returned invalid data.', 'dawnthemes-instagram' ) );

        $instagram = array();

        foreach ( $images as $image ) {

            // see https://github.com/stevenschobert/instafeed.js/issues/549
            //$image['thumbnail_src'] = preg_replace( "/^https:/i", "", $image['thumbnail_src'] );
            $image['thumbnail'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] );
            $image['small'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] );
            $image['large'] = preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] );
            $image['display_src'] = preg_replace( '/^https?\:/i', '', $image['node']['display_url'] );

            if ( $image['node']['is_video'] == true ) {
                $type = 'video';
            } else {
                $type = 'image';
            }

            $caption = __( 'Instagram Image', 'dawnthemes-instagram' );
            if ( ! empty( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'] ) ) {
                $caption = wp_kses( $image['node']['edge_media_to_caption']['edges'][0]['node']['text'], array() );
            }

            $instagram[] = array(
                'description'   => $caption,
                'link'          => trailingslashit( '//instagram.com/p/' . $image['node']['shortcode'] ),
                'time'          => $image['node']['taken_at_timestamp'],
                'comments'      => $image['node']['edge_media_to_comment']['count'],
                'likes'         => $image['node']['edge_liked_by']['count'],
                'thumbnail'     => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][0]['src'] ),
                'small'         => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][2]['src'] ),
                'large'         => preg_replace( '/^https?\:/i', '', $image['node']['thumbnail_resources'][4]['src'] ),
                'original'      => preg_replace( '/^https?\:/i', '', $image['node']['display_url'] ),
                'type'          => $type,
                'id'            => $image['node']['id']
            );
        }
			
		// do not set an empty transient - should help catch private or empty accounts
		if ( ! empty( $instagram ) ) {
			$instagram = base64_encode( serialize( $instagram ) );
			set_transient( 'instagram-'.sanitize_title_with_dashes( $username ), $instagram, apply_filters( 'dt_instagram_cache_time', HOUR_IN_SECONDS*absint($refresh_hour) ) );
		}
	}




	if ( ! empty( $instagram ) ) {

		$instagram = unserialize( base64_decode( $instagram ) );
		$images_data =  array_slice( $instagram, 0, $images_number );
		return $images_data;
	}
	return new WP_Error( 'bad_json', esc_html__( 'Instagram has returned invalid data.', 'dawnthemes-instagram' ) );;
}

include_once plugin_dir_path(__FILE__). '/includes/dtinstagram-widget.php';


