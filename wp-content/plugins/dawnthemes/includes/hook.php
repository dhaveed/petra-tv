<?php
if (! class_exists ( 'DawnThemes_Hooks' )) :
	class DawnThemes_Hooks {
		public function __construct(){
			if(!is_admin()){
				add_action('init', array(&$this,'init'));
				//custom body class
				add_filter('body_class', array(&$this,'body_class'),99);
				//allow shortcodes in text widget
				add_filter('widget_text', 'do_shortcode');
				
				add_filter( 'wp_list_categories', array(&$this,'remove_category_list_rel') );
				add_filter( 'the_category', array(&$this,'remove_category_list_rel') );
				
				add_action('login_enqueue_scripts', array(&$this,'custom_login_css'));
				
				/*
				 * strip shortcode in the excerpt (excerpt get by content)
				 */
				add_action('wp_trim_excerpt', array(&$this,'dt_trim_excerpt_shortcode'));
				
				//Theme option menu
				add_action('admin_bar_menu', array(&$this,'admin_bar_menu'), 10000);

				// 
				add_action('wp_head', array(&$this, 'hook_in_head_tag') );

				// Social share
				add_action('dawnthemes_print_social_share', 'dawnthemes_print_social_share');
				
				//video transparent
				global $wp_embed;
				add_filter('dawnthemes_embed_video',array($wp_embed,'autoembed'),8);
				
				add_shortcode('adsense', 'dawnthemes_get_google_adsense');

			}
		}
		
		public function init(){
			
		}
		public function admin_bar_menu($admin_bar){
			if ( is_super_admin() && ! is_admin() ) {
				$admin_bar->add_menu( array(
					'id'    => 'theme-options',
					'title' => esc_html__('Theme options','dawnthemes'),
					'href'  => get_admin_url().'admin.php?page=theme-options',
					'meta'  => array(
							'title' => esc_html__('Theme options','dawnthemes'),
							'target' => '_blank'
					),
				));
			}
		
		}
		
		public function body_class($classes){
			global $dawnthemes_page_layout;
			
			if(!empty($dawnthemes_page_layout))
				$classes[] = 'page-layout-'.$dawnthemes_page_layout;
			
			return $classes;	
		}

		public function hook_in_head_tag(){
			if(dawnthemes_get_theme_option('echo-meta-tags','0') == '1'){ dawnthemes_seo_meta_tags(); }
		}
		
		
		public function custom_login_css() {
			wp_enqueue_script('jquery');
			$logo_url = dawnthemes_get_theme_option('logo');
			echo "\n<style>";
			echo 'html,body{//background:#262626 !important;}#login h1 a { background-image: url("'.esc_url($logo_url).'");background-size: contain;min-height: auto;width:auto;height:auto;}';
			echo "</style>\n";
		}

		public function dt_trim_excerpt_shortcode( $excerpt = '' ){
			if( has_excerpt() ){
				return $excerpt;
			}else{
				$excerpt = get_the_content('');
				// Remove [dropcacp] of the content
				$excerpt = str_replace( "[dropcap]", "", $excerpt );
				$excerpt = str_replace( "[/dropcap]", "", $excerpt );
				// Strip shrotcodes
				$excerpt = strip_shortcodes($excerpt);
				$excerpt = strip_tags($excerpt);
					
				// Set maximum excerpt lengh
				$dt_excerpt_length = absint(dawnthemes_get_theme_option('excerpt-length', 55));
				$excerpt_length = apply_filters('excerpt_length', $dt_excerpt_length);
				// Change the [...] after excerpt
				$excerpt_more 	= apply_filters('excerpt_more', ' ' . '...');
				$excerpt 		= wp_trim_words( $excerpt, $excerpt_length, $excerpt_more );
					
				return apply_filters('the_content', $excerpt);
			}
		}
		
		public function remove_category_list_rel( $output ) {
			// Remove rel attribute from the category list
			return str_replace( ' rel="category tag"', '', $output );
		}
		
	}
	new DawnThemes_Hooks ();

endif;