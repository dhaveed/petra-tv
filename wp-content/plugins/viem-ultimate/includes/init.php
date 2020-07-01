<?php

if (! class_exists ( 'viem_ultimate_Init' )) :
	class viem_ultimate_Init {
		public function __construct(){
			$this->_define_constants ();
			$this->_includes ();
		}
		private function _define_constants() {
			
			if(!defined('viem_ultimate_INC_DIR'))
				define( 'viem_ultimate_INC_DIR', dirname ( __FILE__ ) );
		}
		private function _includes() {
			include_once ( viem_ultimate_INC_DIR . '/functions.php' );

			include_once ( viem_ultimate_INC_DIR . '/meta-data.php' );
			if( viem_get_theme_option('enable_viem_movie', '0') == '1' ){
				include_once ( viem_ultimate_INC_DIR . '/post-type-movie.php' );
			}
			
			include_once ( viem_ultimate_INC_DIR . '/post-type-video.php' );
			include_once ( viem_ultimate_INC_DIR . '/post-type-series.php' );
			include_once ( viem_ultimate_INC_DIR . '/post-type-playlist.php' );
			include_once ( viem_ultimate_INC_DIR . '/post-type-channel.php' );
			include_once ( viem_ultimate_INC_DIR . '/post-type-director.php' );
			include_once ( viem_ultimate_INC_DIR . '/post-type-actor.php' );

			// Community videos
			include_once ( viem_ultimate_INC_DIR . '/community-videos/community-videos.php' );
		}
	}
	new viem_ultimate_Init();
endif;