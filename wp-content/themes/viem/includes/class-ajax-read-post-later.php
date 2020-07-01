<?php
class viem_ajax_video_watch_later{
	
	protected static $WatchLater_added_text;
	protected static $WatchLater_removed_text;
	
	public function __construct(){
		
		self::$WatchLater_added_text = esc_html__('Added to Watch later', 'viem');
		self::$WatchLater_removed_text = esc_html__('Removed from Watch later', 'viem');
		
		add_action('init', array(&$this,'ajax_watch_later_init'));
	}
	
	public function ajax_watch_later_init(){
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script('viem-video-watch-later', get_template_directory_uri() .'/assets/js/ajax-watch-later'.$suffix .'.js', array('jquery'));
		
		wp_localize_script( 'viem-video-watch-later', 'viem_ajax_video_watch_later_object', array(
			'ajaxurl'			=> esc_js( admin_url('admin-ajax.php') ),
		));
		
		add_action( 'wp_ajax_jcajaxwatchlater', array(&$this,'ajax_watch_later') );
		add_action( 'wp_ajax_nopriv_jcajaxwatchlater', array($this, 'ajax_watch_later'));
	}
	
	public static function __get_added_text(){
		return self::$WatchLater_added_text;
	}
	
	public function ajax_watch_later(){
		$action = isset($_POST['action']) ? $_POST['action'] : '';
		$video_id = isset($_POST['video_id']) ? $_POST['video_id'] : '';
		$action_type = isset($_POST['action_type']) ? $_POST['action_type'] : '';
		$action_type_change = '';
		
		$user_id = get_current_user_id();
		$user_video_watch_later = get_user_meta($user_id, 'video_watch_later', true); //get_the_author_meta('video_watch_later', $user_id);
		$message = '';
		$added = self::check_video_in_watch_later( $video_id, $user_id );
		
		switch ( $action_type ){
			case 'add':
				
				if( empty($user_video_watch_later) ){
						
					$user_video_watch_later = array('0' => 0);
					array_push($user_video_watch_later, $video_id);
					update_user_meta( $user_id, 'video_watch_later', $user_video_watch_later);
					$message = self::$WatchLater_added_text;
					$action_type_change = 'remove';
						
				}else if( ! $added  ){
						
					array_push($user_video_watch_later, $video_id);
					update_user_meta( $user_id, 'video_watch_later', $user_video_watch_later);
					$message =  self::$WatchLater_added_text;
					$action_type_change = 'remove';
						
				}else{}
				
				
				break;
			case 'remove':
				
				if ( ($video_id = array_search($video_id, $user_video_watch_later)) !== false ) {
					unset($user_video_watch_later[$video_id]);
					update_user_meta( $user_id, 'video_watch_later', $user_video_watch_later);
					$message =  self::$WatchLater_removed_text;
					$action_type_change = 'add';
				}
				
				break;
			default: 
				break;
		}
		
		echo json_encode(array('message'=> $message, 'action_type_change' => $action_type_change ));
		
		die();
	}
	
	public static function check_video_in_watch_later( $video_id = '', $userID ){
		if( empty($userID) || empty($video_id) ) return false;
		
		$user_video_watch_later = get_user_meta( $userID, 'video_watch_later', true); //get_the_author_meta('video_watch_later', $userID);
		if( ($video_id == $user_video_watch_later ) || ( is_array($user_video_watch_later) && in_array( $video_id, $user_video_watch_later)) ){
			return true;
		}else{
			return false;
		}
	}
	
	public static function user_watch_later_list(){
		$user_id = get_current_user_id();
		
		$user_video_watch_later_list = get_user_meta($user_id, 'video_watch_later', true); //get_the_author_meta('video_watch_later', $user_id);
		
		return $user_video_watch_later_list;
	}
	
}

new viem_ajax_video_watch_later();