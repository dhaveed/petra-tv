<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_DT_Tweets extends WP_Widget {
	public function __construct() {
		parent::__construct (
			'viem_DT_Tweets', 		// Base ID
			'DT Recent Tweets', 		// Name
			array ('classname'=>'tweets-widget','description' => __ ( 'Display recent tweets', 'viem' ) )
		);
	}

	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo wp_kses( $before_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
		if($title) {
			echo wp_kses( $before_title . esc_html($title) . $after_title, array(
				'h3' => array(
					'class' => array()
				),
				'h4' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array()
				),
			) );
		}

		//check settings and die if not set
		if(empty($instance['consumerkey']) || empty($instance['consumersecret']) || empty($instance['accesstoken']) || empty($instance['accesstokensecret']) || empty($instance['cachetime']) || empty($instance['username'])){
			echo '<strong>'.esc_html__('Please fill all widget settings!' , 'viem').'</strong>' . $after_widget;
			return;
		}

		$dt_widget_recent_tweets_cache_time = get_option('dt_widget_recent_tweets_cache_time');
		$diff = time() - $dt_widget_recent_tweets_cache_time;

		$crt = (int) $instance['cachetime'] * 3600;

		if($diff >= $crt || empty($dt_widget_recent_tweets_cache_time)){
			
			if( !defined('DTINC_DIR') || !require_once(DTINC_DIR . '/lib/twitteroauth.php')){
				echo '<strong>'.esc_html__('Couldn\'t find twitteroauth.php!','viem').'</strong>' . $after_widget;
				return;
			}
				
			function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
				$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
				return $connection;
			}
				
			$connection = getConnectionWithAccessToken($instance['consumerkey'], $instance['consumersecret'], $instance['accesstoken'], $instance['accesstokensecret']);
			$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$instance['username']."&count=10&exclude_replies=".$instance['excludereplies']);

			if(!empty($tweets->errors)){
				if($tweets->errors[0]->message == 'Invalid or expired token'){
					echo '<strong>'.$tweets->errors[0]->message.'!</strong><br/>'.esc_html__('You\'ll need to regenerate it <a href="https://dev.twitter.com/apps" target="_blank">here</a>!', 'viem' ) . $after_widget;
				}else{
					echo '<strong>'.$tweets->errors[0]->message.'</strong>' . $after_widget;
				}
				return;
			}
				
			$tweets_array = array();
			for($i = 0;$i <= count($tweets); $i++){
				if(!empty($tweets[$i])){
					$tweets_array[$i]['created_at'] = $tweets[$i]->created_at;

					//clean tweet text
					$tweets_array[$i]['text'] = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $tweets[$i]->text);

					if(!empty($tweets[$i]->id_str)){
						$tweets_array[$i]['status_id'] = $tweets[$i]->id_str;
					}
				}
			}
			update_option('dt_widget_recent_tweets',serialize($tweets_array));
			update_option('dt_widget_recent_tweets_cache_time',time());
		}

		$dt_widget_recent_tweets = maybe_unserialize(get_option('dt_widget_recent_tweets'));
		if(!empty($dt_widget_recent_tweets)){
			echo '<div class="recent-tweets"><ul>';
			$i = '1';
			foreach($dt_widget_recent_tweets as $tweet){
				if(!empty($tweet['text'])){
					if(empty($tweet['status_id'])){ $tweet['status_id'] = ''; }
					if(empty($tweet['created_at'])){ $tweet['created_at'] = ''; }
						
					echo '<li><i class="fa fa-twitter viem_main_color" aria-hidden="true"></i><span>'.$this->_convert_links($tweet['text']).'</span></li>';
					if($i == $instance['tweetstoshow']){ break; }
					$i++;
				}
			}
				
			echo '</ul></div>';
		}

		echo wp_kses( $after_widget, array(
				'aside' => array(
					'id' => array(),
					'class' => array(),
				),
			));
	}

	protected function _convert_links($status, $targetBlank = true, $linkMaxLen=50){
		// the target
		$target=$targetBlank ? " target=\"_blank\" " : "";

		// convert link to url
		$status = preg_replace("/((http:\/\/|https:\/\/)[^ )]+)/i", "<a href=\"$1\" title=\"$1\" $target >$1</a>", $status);

		// convert @ to follow
		$status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>",$status);

		// convert # to search
		$status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" title=\"Search $1\" $target >$1</a>",$status);

		// return the status
		return $status;
	}

	protected function _relative_time($a=''){
		//get current timestampt
		$b = strtotime("now");
		//get timestamp when tweet created
		$c = strtotime($a);
		//get difference
		$d = $b - $c;
		//calculate different time values
		$minute = 60;
		$hour = $minute * 60;
		$day = $hour * 24;
		$week = $day * 7;

		if(is_numeric($d) && $d > 0) {
			//if less then 3 seconds
			if($d < 3) return "right now";
			//if less then minute
			if($d < $minute) return sprintf(esc_html__("%s seconds ago",'viem'),floor($d));
			//if less then 2 minutes
			if($d < $minute * 2) return esc_html__("about 1 minute ago",'viem');
			//if less then hour
			if($d < $hour) return sprintf(esc_html__('%s minutes ago','viem'), floor($d / $minute));
			//if less then 2 hours
			if($d < $hour * 2) return esc_html__("about 1 hour ago",'viem');
			//if less then day
			if($d < $day) return sprintf(esc_html__("%s hours ago", 'viem'),floor($d / $hour));
			//if more then day, but less then 2 days
			if($d > $day && $d < $day * 2) return esc_html__("yesterday",'viem');
			//if less then year
			if($d < $day * 365) return sprintf(esc_html__('%s days ago','viem'),floor($d / $day));
			//else return more than a year
			return esc_html__("over a year ago",'viem');
		}
	}

	public function form($instance) {
		$defaults = array (
			'title' => '',
			'consumerkey' => '',
			'consumersecret' => '',
			'accesstoken' => '',
			'accesstokensecret' => '',
			'cachetime' => '',
			'username' => 'DawnThemes',
			'tweetstoshow' => ''
		);
		$instance = wp_parse_args ( ( array ) $instance, $defaults );

		echo '
		<p>
			<label>' . __ ( 'Title' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'title' ) . '" id="' . $this->get_field_id ( 'title' ) . '" value="' . esc_attr ( $instance ['title'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Consumer Key' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'consumerkey' ) . '" id="' . $this->get_field_id ( 'consumerkey' ) . '" value="' . esc_attr ( $instance ['consumerkey'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Consumer Secret' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'consumersecret' ) . '" id="' . $this->get_field_id ( 'consumersecret' ) . '" value="' . esc_attr ( $instance ['consumersecret'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Access Token' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'accesstoken' ) . '" id="' . $this->get_field_id ( 'accesstoken' ) . '" value="' . esc_attr ( $instance ['accesstoken'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Access Token Secret' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'accesstokensecret' ) . '" id="' . $this->get_field_id ( 'accesstokensecret' ) . '" value="' . esc_attr ( $instance ['accesstokensecret'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Cache Tweets in every' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'cachetime' ) . '" id="' . $this->get_field_id ( 'cachetime' ) . '" value="' . esc_attr ( $instance ['cachetime'] ) . '" class="small-text" />'.esc_html__('hours','viem').'
		</p>
		<p>
			<label>' . __ ( 'Twitter Username' , 'viem' ) . ':</label>
			<input type="text" name="' . $this->get_field_name ( 'username' ) . '" id="' . $this->get_field_id ( 'username' ) . '" value="' . esc_attr ( $instance ['username'] ) . '" class="widefat" />
		</p>
		<p>
			<label>' . __ ( 'Tweets to display' , 'viem' ) . ':</label>
			<select type="text" name="' . $this->get_field_name ( 'tweetstoshow' ) . '" id="' . $this->get_field_id ( 'tweetstoshow' ) . '">';
		$i = 1;
		for(i; $i <= 10; $i ++) {
			echo '<option value="' . $i . '"';
			if ($instance ['tweetstoshow'] == $i) {
				echo ' selected="selected"';
			}
			echo '>' . $i . '</option>';
		}
		echo '
			</select>
		</p>
		<p>
			<label>' . __ ( 'Exclude replies', 'viem' ) . ':</label>
			<input type="checkbox" name="' . $this->get_field_name ( 'excludereplies' ) . '" id="' . $this->get_field_id ( 'excludereplies' ) . '" value="true"';
		if (! empty ( $instance ['excludereplies'] ) && esc_attr ( $instance ['excludereplies'] ) == 'true') {
			echo ' checked="checked"';
		}
		echo '/></p>';
	}

	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['consumerkey'] = strip_tags( $new_instance['consumerkey'] );
		$instance['consumersecret'] = strip_tags( $new_instance['consumersecret'] );
		$instance['accesstoken'] = strip_tags( $new_instance['accesstoken'] );
		$instance['accesstokensecret'] = strip_tags( $new_instance['accesstokensecret'] );
		$instance['cachetime'] = strip_tags( $new_instance['cachetime'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['tweetstoshow'] = strip_tags( $new_instance['tweetstoshow'] );
		$instance['excludereplies'] = strip_tags( $new_instance['excludereplies'] );

		if($old_instance['username'] != $new_instance['username']){
			delete_option('dt_widget_recent_tweets_cache_time');
		}

		return $instance;
	}
}

add_action('widgets_init', 'viem_DT_Tweets_register_widget');
function viem_DT_Tweets_register_widget(){
	return register_widget("viem_DT_Tweets");
}