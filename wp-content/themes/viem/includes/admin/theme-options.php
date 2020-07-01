<?php
if ( function_exists( 'dawnthemes_register_theme_options' ) ) :
	/*
	 * Initialize Theme Options
	 */
	add_action( 'init', 'viem_theme_options' );

	function viem_theme_options() {

		$sidebars = $GLOBALS['wp_registered_sidebars'];
		$sidebar_options = array();
		$sidebar_options[esc_html__('-- Default --','viem')] = '';
		foreach ( (array)$sidebars as $sidebar ) {
			$sidebar_options[$sidebar['name']] = $sidebar['id'];
		}

		$section = array();
		
		$section = array( 
			'general' => array( 
				'icon' => 'fa fa-home', 
				'title' => esc_html__( 'General', 'viem' ), 
				'desc' => esc_html__( 'Here you will set your site-wide preferences', 'viem' ), 
				'fields' => array(
					array( 
						'name' => 'logo',
						'type' => 'image',
						'value' => get_template_directory_uri() . '/assets/images/logo.svg', 
						'label' => esc_html__( 'Logo', 'viem' ), 
						'desc' => esc_html__( 'Upload your own logo.', 'viem' ) ), 
					array( 
						'name' => 'retina_logo', 
						'type' => 'image', 
						'value' => '', 
						'label' => esc_html__( 'Retina Logo (optional)', 'viem' ), 
						'desc' => esc_html__( 
							'Retina logo should be two time bigger than the custom logo. Retina Logo is optional, use this setting if you want to strictly support retina devices.', 
							'viem' ) ), 
					array( 
						'name' => 'rtl', 
						'type' => 'switch', 
						'label' => esc_html__( 'RTL', 'viem' ), 
						'desc' => esc_html__( 'Support Right-to-Left language.', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'back_to_top', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Back To Top Button', 'viem' ), 
						'value' => 1, 
						'desc' => esc_html__( 
							'Toggle whether or not to enable a back to top button on your pages.', 
							'viem' ) ), 
					array( 
						'name' => 'back_to_top_position', 
						'type' => 'switch', 
						'on' => esc_html__( 'Left', 'viem' ), 
						'off' => esc_html__( 'Right', 'viem' ), 
						'label' => esc_html__( 'Back To Top Position', 'viem' ), 
						'value' => 0, 
						'dependency' => array( 'element' => 'back_to_top', 'value' => array( 1 ) ) ),
					/*array(
						'name' => 'custom-code',
						'type' => 'textarea',
						'label' => esc_html__( 'Custom Code', 'viem' ),
						'desc' => esc_html__( 'Enter custom code or JS code here. For example, enter Google Analytics code', 'viem' ) ),*/
					array( 
						'name' => 'custom-css', 
						'type' => 'ace_editor', 
						'label' => esc_html__( 'Custom CSS', 'viem' ), 
						'desc' => esc_html__( 'Enter CSS code', 'viem' ) ), 
					array( 
						'name' => 'pre-loading', 
						'type' => 'select', 
						'label' => esc_html__( 'Pre-loading Effect', 'viem' ), 
						'desc' => esc_html__( 'Enable Pre-loading Effect', 'viem' ), 
						'options' => array( 
							esc_html__( 'Disable', 'viem' ) => '-1', 
							esc_html__( 'Enable', 'viem' ) => '1', 
							esc_html__( 'Enable for Homepage Only', 'viem' ) => '2' ), 
						'value' => '1' ), 
					array( 
						'name' => 'img-preloading', 
						'type' => 'image', 
						'value' => get_template_directory_uri() . '/assets/images/preloading.gif', 
						'label' => esc_html__( 'Preloader image', 'viem' ), 
						'desc' => esc_html__( 'Upload the image gif format.', 'viem' ) ),
					/*array(
						'name' => 'scroll_effect',
						'type' => 'switch',
						'label' => esc_html__( 'Page Scrolling Effect', 'viem' ),
						'value' => 0,
						'desc' => esc_html__( 'Enable Page Scrolling Effect', 'viem' ) ),*/
					) ), 
			
			'design_layout' => array( 
				'icon' => 'fa fa-columns', 
				'title' => esc_html__( 'Site Layout', 'viem' ), 
				'desc' => '', 
				'fields' => array( 
					array( 
						'name' => 'site-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Site Layout', 'viem' ), 
						'desc' => '', 
						'options' => array( 
							'site-layout-1' => array( 
								'alt' => 'Site layout 1', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-1.jpg' ), 
							'site-layout-2' => array( 
								'alt' => 'Site layout 2', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-2.jpg' ), 
							'site-layout-3' => array( 
								'alt' => 'Site layout 3', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-3.jpg' ), 
							'site-layout-4' => array( 
								'alt' => 'Site layout 4', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-4.jpg' ), 
							'site-layout-5' => array( 
								'alt' => 'Site layout 5', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-5.jpg' ), 
							'site-layout-6' => array( 
								'alt' => 'Site layout 6', 
								'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-6.jpg' ) ), 
						
						// 'site-layout-7' => array(
						// 'alt' => 'Site layout 7',
						// 'img' => get_template_directory_uri() . '/assets/images/admin/site-layout-7.jpg' ),
						'value' => 'site-layout-1' ), 
					array( 
						'name' => 'body_background', 
						'type' => 'background', 
						'label' => esc_html__( 'Background', 'viem' ), 
						'desc' => esc_html__( 'Body Background', 'viem' ) ), 
					array( 
						'name' => 'text_color', 
						'type' => 'color', 
						'label' => esc_html__( 'Text Color', 'viem' ), 
						'desc' => esc_html__( 'Choose text color for body', 'viem' ), 
						'value' => '' ), 
					array( 
						'name' => 'homev3-show-recent-video', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Single Slider Recent Videos in Homepage', 'viem' ), 
						'desc' => esc_html__( 'Show Single Slider Recent Videos in Homepage (Demo V3).', 'viem' ), 
						'dependency' => array( 'element' => 'site-layout', 'value' => array( 'site-layout-2' ) ), 
						'value' => '0' ) ) ),  // 1 = checked | 0 = unchecked
			'color_typography' => array( 
				'icon' => 'fa fa-font', 
				'title' => esc_html__( 'Color and Typography', 'viem' ), 
				'desc' => esc_html__( 'Customize Color and Typography', 'viem' ), 
				'fields' => array( 
					/*array( 
						'name' => 'theme-mode', 
						'type' => 'select', 
						'label' => esc_html__( 'Theme Mode', 'viem' ), 
						'desc' => esc_html__( 'Select template background white or dark mode.', 'viem' ), 
						'options' => array( 
							'white' => esc_html__( 'White Mode', 'viem' ), 
							'dark' => esc_html__( 'Dark Mode', 'viem' ) ), 
						'value' => 'white' ),*/
					array( 
						'name' => 'main_color', 
						'type' => 'color', 
						'label' => esc_html__( 'Main Color', 'viem' ), 
						'desc' => esc_html__( 'Choose main color of theme', 'viem' ), 
						'value' => '' ), 
					array( 
						'name' => 'secondary_color', 
						'type' => 'color', 
						'label' => esc_html__( 'Secondary Color', 'viem' ), 
						'desc' => esc_html__( 'Choose secondary color of theme.', 'viem' ), 
						'value' => '' ), 
					array( 
						'name' => 'main_font', 
						'type' => 'custom_font', 
						'field-label' => esc_html__( 'Main Font', 'viem' ), 
						'desc' => esc_html__( 'Font family for body text', 'viem' ), 
						'font-size' => 'true', 
						'value' => array() ), 
					array( 
						'name' => 'secondary_font', 
						'type' => 'custom_font', 
						'field-label' => esc_html__( 'Secondary Font', 'viem' ), 
						'desc' => esc_html__( 'Font family for the secondary font (ex: heading text)', 'viem' ), 
						'font-size' => 'false', 
						'value' => array() ), 
					array(
						'name' => 'navbar_typography', 
						'type' => 'custom_font', 
						'field-label' => esc_html__( 'Navigation', 'viem' ), 
						'desc' => esc_html__( 'Font family for the navigation', 'viem' ), 
						'font-size' => 'false', 
						'value' => array() ),
					array(
						'name' => 'upload_font',
						'type' => 'upload_font',
						'field-label' => esc_html__( 'Upload Font', 'viem' ),
						'desc' => esc_html__( 'Add custom font via uploading woff file.', 'viem' ),
						'font-size' => 'true',
						'value' => array() ),
				) ),
			'header' => array( 
				'icon' => 'fa fa-header', 
				'title' => esc_html__( 'Header', 'viem' ), 
				'desc' => esc_html__( 'Customize Header', 'viem' ), 
				'fields' => array( 
					array( 
						'name' => 'header_layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Header Layout', 'viem' ), 
						'desc' => '', 
						'options' => array( 
							'header-1' => array( 
								'alt' => 'Header 1', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-1.jpg' ), 
							'header-2' => array( 
								'alt' => 'Header 2', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-2.jpg' ), 
							'header-3' => array( 
								'alt' => 'Header 3', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-3.jpg' ), 
							'header-4' => array( 
								'alt' => 'Header 4', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-4.jpg' ), 
							'header-5' => array( 
								'alt' => 'Header 5', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-5.jpg' ), 
							'header-6' => array( 
								'alt' => 'Header 6', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-6.jpg' ), 
							'header-7' => array( 
								'alt' => 'Header 7', 
								'img' => get_template_directory_uri() . '/assets/images/admin/header-7.jpg' ) ), 
						'value' => 'header-1' ),
					
					array( 
						'name' => 'show_header_social_links', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Social Links', 'viem' ), 
						'dependency' => array( 
							'element' => 'header_layout', 
							'value' => array( 'header-5', 'header-6', 'header-7' ) ), 
						'value' => '0' ), 
					
					array( 
						'name' => 'header_socials_email', 
						'type' => 'text', 
						'label' => esc_html__( 'Email', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_facebook', 
						'type' => 'text', 
						'label' => esc_html__( 'Facebook', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_twitter', 
						'type' => 'text', 
						'label' => esc_html__( 'Twitter', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_google-plus', 
						'type' => 'text', 
						'label' => esc_html__( 'Google +', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_youtube', 
						'type' => 'text', 
						'label' => esc_html__( 'Youtube', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_vimeo', 
						'type' => 'text', 
						'label' => esc_html__( 'Vimeo', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_flickr', 
						'type' => 'text', 
						'label' => esc_html__( 'Flickr', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_pinterest', 
						'type' => 'text', 
						'label' => esc_html__( 'Pinterest', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_skype', 
						'type' => 'text', 
						'label' => esc_html__( 'Skype', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_linkedin', 
						'type' => 'text', 
						'label' => esc_html__( 'Linkedin', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_rss', 
						'type' => 'text', 
						'label' => esc_html__( 'RSS', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_instagram', 
						'type' => 'text', 
						'label' => esc_html__( 'Instagram', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_github', 
						'type' => 'text', 
						'label' => esc_html__( 'Github', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_behance', 
						'type' => 'text', 
						'label' => esc_html__( 'Behance', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_stack-exchange', 
						'type' => 'text', 
						'label' => esc_html__( 'Stack Exchange', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_tumblr', 
						'type' => 'text', 
						'label' => esc_html__( 'Tumblr', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_soundcloud', 
						'type' => 'text', 
						'label' => esc_html__( 'Soundcloud', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					array( 
						'name' => 'header_socials_dribbble', 
						'type' => 'text', 
						'label' => esc_html__( 'Dribbble', 'viem' ), 
						'dependency' => array( 'element' => 'show_header_social_links', 'value' => array( '1' ) ) ), 
					
					array( 
						'name' => 'adsense_slot_ads_header', 
						'type' => 'text', 
						'dependency' => array( 'element' => 'header_layout', 'value' => array( 'header-5', 'header-7' ) ), 
						'label' => esc_html__( 'Header AdSense Ads Slot ID', 'viem' ), 
						'desc' => esc_html__( 
							'Google AdSense Ad Slot ID. If left empty, "Header Ads - Custom Code" will be used.', 
							'viem' ) ), 
					array( 
						'name' => 'ads_wall_header_custom', 
						'type' => 'textarea', 
						'dependency' => array( 'element' => 'header_layout', 'value' => array( 'header-5', 'header-7' ) ), 
						'label' => esc_html__( 'Header Ads - Custom Code', 'viem' ), 
						'desc' => esc_html__( 'Enter custom code for Header Ads', 'viem' ) ), 
					array( 
						'name' => 'header_elements', 
						'type' => 'heading', 
						'text' => esc_html__( 'Header Elements', 'viem' ) ),
					array( 
						'name' => 'show_top_header_5', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Top Header', 'viem' ), 
						'dependency' => array( 
							'element' => 'header_layout', 
							'value' => array( 'header-5' ) ), 
						'value' => '1' ),
					array( 
						'name' => 'header-ajaxsearch', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Search', 'viem' ), 
						'value' => '1' ),
					array( 
						'name' => 'header-user-menu', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'User Menu', 'viem' ), 
						'value' => '1' ), 
					array( 
						'name' => 'show_featured_video_header_6', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Featured Video', 'viem' ), 
						'dependency' => array( 
							'element' => 'header_layout', 
							'value' => array( 'header-6' ) ), 
						'value' => '1' ),


					array( 
						'name' => 'header_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'Header Settings', 'viem' ) ), 
					array( 
						'name' => 'sticky-menu', 
						'type' => 'switch', 
						'label' => esc_html__( 'Sticky Top menu', 'viem' ), 
						'desc' => esc_html__( 'Enable or disable the sticky menu.', 'viem' ), 
						'dependency' => array( 
							'element' => 'header_layout', 
							'value' => array( 'header-1', 'header-2', 'header-4', 'header-6' ) ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'sticky_logo', 
						'type' => 'image', 
						'value' => get_template_directory_uri() . '/assets/images/logo.svg', 
						'label' => esc_html__( 'Sticky Logo', 'viem' ), 
						'dependency' => array( 
							'element' => 'header_layout', 
							'value' => array( 'header-1', 'header-2', 'header-4', 'header-6' ) ), 
						'desc' => esc_html__( 'Upload your own logo.This is optional use when fixed menu', 'viem' ) ) ) ), 
			'blog' => array( 
				'icon' => 'fa fa-pencil', 
				'title' => esc_html__( 'Blog', 'viem' ), 
				'desc' => esc_html__( 'Customize Blog', 'viem' ), 
				'fields' => array( 
					array( 
						'name' => 'list_blog_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'List Blog Settings', 'viem' ) ), 
					array( 
						'name' => 'blog-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Main Blog Layout', 'viem' ), 
						'desc' => esc_html__( 
							'Select main blog layout. Choose between 1, 2 or 3 columns layout.', 
							'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'right-sidebar' ), 
					array( 
						'name' => 'archive-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Archive Layout', 'viem' ), 
						'desc' => esc_html__( 
							'Select Archive layout. Choose between 1, 2 or 3 columns layout.', 
							'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'right-sidebar' ), 
					array( 
						'name' => 'blog-style', 
						'type' => 'select', 
						'label' => esc_html__( 'Style', 'viem' ), 
						'desc' => esc_html__( 'How your blog posts will display.', 'viem' ), 
						'options' => array( 
							esc_html__( 'List', 'viem' ) => 'list', 
							esc_html__( 'Grid', 'viem' ) => 'grid', 
							esc_html__( 'Masonry', 'viem' ) => 'masonry' ), 
						'value' => 'list' ), 
					array( 
						'name' => 'blog-image-size', 
						'type' => 'select', 
						'label' => esc_html__( 'Post Image Size', 'viem' ), 
						'desc' => '<a target="_self" href="' . esc_url( admin_url( 'themes.php?page=theme-options/' ) ) .
							 '#advanced">' . esc_html__( 'Edit image sizes', 'viem' ) . '</a>.', 
							'options' => array_merge( 
								array( esc_html__( 'Default', 'viem' ) => 'default' ), 
								viem_image_sizes_select_values() ), 
							'value' => 'default' ), 
					array( 
						'name' => 'blog-columns', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Blogs Columns', 'viem' ), 
						'desc' => esc_html__( 'Select blogs columns.', 'viem' ), 
						'dependency' => array( 
							'element' => 'blog-style', 
							'value' => array( 'grid', 'default', 'masonry' ) ), 
						'options' => array( 
							'2' => array( 'alt' => '2 Columns', 'img' => DTINC_ASSETS_URL . '/images/2col.png' ), 
							'3' => array( 'alt' => '3 Columns', 'img' => DTINC_ASSETS_URL . '/images/3col.png' ) ), 
						
						// '4' => array( 'alt' => '4 Columns', 'img' => DTINC_ASSETS_URL . '/images/4col.png' )
						'value' => '2' ), 
					array( 
						'type' => 'select', 
						'label' => esc_html__( 'Pagination', 'viem' ), 
						'name' => 'blog-pagination', 
						'options' => array( 
							esc_html__( 'WP PageNavi', 'viem' ) => 'wp_pagenavi', 
							esc_html__( 'Ajax Load More', 'viem' ) => 'loadmore', 
							esc_html__( 'Infinite Scrolling', 'viem' ) => 'infinite_scroll' ), 
						'value' => 'wp_pagenavi', 
						'dependency' => array( 
							'element' => 'blog-style', 
							'value' => array( 'default', 'list', 'grid', 'masonry' ) ), 
						'desc' => esc_html__( 'Choose pagination type.', 'viem' ) ), 
					array( 
						'type' => 'text', 
						'label' => esc_html__( 'Load More Button Text', 'viem' ), 
						'name' => 'blog-loadmore-text', 
						'dependency' => array( 'element' => "blog-pagination", 'value' => array( 'loadmore' ) ), 
						'value' => esc_html__( 'Load More', 'viem' ) ), 
					array( 
						'name' => 'blog-excerpt-length', 
						'type' => 'text', 
						'label' => esc_html__( 'Excerpt Length', 'viem' ), 
						'dependency' => array( 
							'element' => "blog-style", 
							'value' => array( 'default', 'list', 'grid', 'masonry' ) ), 
						'desc' => esc_html__( 'In Archive Blog. Enter the number words excerpt', 'viem' ), 
						'value' => '30' ), 
					array( 
						'name' => 'blog_show_date', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Date Meta', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the date meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'blog_show_comment', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Comment Meta', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the comment meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'blog_show_category', 
						'type' => 'switch', 
						'label' => esc_html__( 'Show/Hide Category', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the category meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'blog_show_tag', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'dependency' => array( 
							'element' => "blog_style", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'label' => esc_html__( 'Tags', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. If enabled it will show tag.', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'blog_show_author', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'dependency' => array( 
							'element' => "blog_style", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'label' => esc_html__( 'Author Meta', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the author meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					
					array( 
						'name' => 'blog_show_readmore', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'dependency' => array( 
							'element' => "blog_style", 
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ), 
						'label' => esc_html__( 'Show/Hide Readmore', 'viem' ), 
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the post readmore', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					
					/*
					 * Single Blog Settings
					 */
					array( 
						'name' => 'single_blog_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'Single Blog Settings', 'viem' ) ), 
					array( 
						'name' => 'single-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Single Blog Layout', 'viem' ), 
						'desc' => esc_html__( 
							'Select single content and sidebar alignment. Choose between 1, 2 or 3 column layout.', 
							'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'right-sidebar' ), 
					array( 
						'name' => 'single-style', 
						'type' => 'select', 
						'label' => esc_html__( 'Featured Image Layout', 'viem' ), 
						'desc' => '', 
						'options' => array( 
							esc_html__( 'In Container', 'viem' ) => 'style-1', 
							esc_html__( 'Fullwidth', 'viem' ) => 'style-2' ), 
						'value' => 'style-1' ), 
					
					array( 
						'name' => 'single_show_date', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Date Meta', 'viem' ), 
						'desc' => esc_html__( 'In Single Blog. Show/Hide the date meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'single_show_category', 
						'type' => 'switch', 
						'label' => esc_html__( 'Show/Hide Category', 'viem' ), 
						'desc' => esc_html__( 'In Single Blog. Show/Hide the category', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'single_show_author', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Author Meta', 'viem' ), 
						'desc' => esc_html__( 'In Single Blog. Show/Hide the author meta', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'single_show_tag', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Show/Hide Tag', 'viem' ), 
						'desc' => esc_html__( 'In Single Blog. If enabled it will show tag.', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'single_show_authorbio', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Show Author Bio', 'viem' ), 
						'desc' => esc_html__( 
							'Display the author bio at the bottom of post on single post page ?', 
							'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'single_show_postnav', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ),
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Show Next/Prev Post Link On Single Post Page', 'viem' ), 
						'desc' => esc_html__( 
							'Using this will add a link at the bottom of every post page that leads to the next/prev post.', 
							'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'show_related_posts', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Show Related Post On Single Post Page', 'viem' ), 
						'desc' => esc_html__( 'Display related post the bottom of posts?', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'related_posts_count', 
						'type' => 'text', 
						'label' => esc_html__( 'Related Post Count', 'viem' ), 
						'desc' => esc_html__( 'Number of related post to query', 'viem' ), 
						'dependency' => array( 'element' => 'show_related_posts', 'value' => array( '1' ) ), 
						'value' => '4' ), 
					array( 
						'name' => 'post_show_share', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Show Sharing Button', 'viem' ), 
						'desc' => esc_html__( 
							'Activate this to enable social sharing buttons on single post page.', 
							'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_facebook', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Facebook', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_twitter', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Twitter', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_google', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Google+', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_linkedIn', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on LinkedIn', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_tumblr', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Tumblr', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_pinterest', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Pinterest', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'post_sharing_email', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'post_show_share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Email', 'viem' ), 
						'value' => '0' ) ) ) ); // 1 = checked | 0 = unchecked
		if ( viem_get_theme_option( 'enable_viem_movie', '1' ) ) :
			$section['movie'] = array( 
				'icon' => 'fa fa-video-camera', 
				'title' => esc_html__( 'Movie', 'viem' ), 
				'desc' => esc_html__( 'Movie Setttings', 'viem' ), 
				'fields' => array( 
					array( 
						'type' => 'text', 
						'label' => esc_html__( 'Movies URL slug', 'viem' ), 
						'name' => 'movies_slug', 
						'placeholder' => 'movies', 
						'width' => '200px', 
						'value' => 'movies', 
						'desc' => esc_html__( 'The slug used for building the movies URL.', 'viem' ) ), 
					array( 
						'type' => 'text', 
						'label' => esc_html__( 'Single movie URL slug', 'viem' ), 
						'name' => 'single_movie_slug', 
						'placeholder' => 'movie',
						'width' => '200px', 
						'value' => 'movie', 
						'desc' => esc_html__( 'The above should ideally be plural, and this singular.', 'viem' ) ), 
					array( 
						'name' => 'archive_movie_settings', 
						'type' => 'heading', 
						'status' => 'open', 
						'text' => esc_html__( 'Archive Pages', 'viem' ) ), 
					array( 
						'type' => 'single_select_page',
						'label' => esc_html__( 'Main Movies Page', 'viem' ), 
						'name' => 'main-movie-page', 
						'value' => '' ), 
					array( 
						'name' => 'movies-image-size', 
						'type' => 'select', 
						'label' => esc_html__( 'Featured Image Size', 'viem' ), 
						'desc' => '<a target="_self" href="' . esc_url( admin_url( 'themes.php?page=theme-options/' ) ) .
							 '#advanced">' . esc_html__( 'Edit image sizes', 'viem' ) . '</a>.', 
							'options' => array_merge( 
								array( esc_html__( 'Default', 'viem' ) => '' ), 
								viem_image_sizes_select_values() ), 
							'value' => '' ), 
					array( 
						'name' => 'movies-layout',
						'type' => 'image_select', 
						'label' => esc_html__( 'Archive Layout', 'viem' ),
						'desc' => esc_html__( 
							'Select Archive layout. Choose between 1, 2 or 3 columns layout.', 
							'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'full-width' ), 
					array( 
						'name' => 'movies-style',
						'type' => 'select', 
						'label' => esc_html__( 'Style', 'viem' ), 
						'desc' => esc_html__( 'How your movies will display.', 'viem' ),
						'options' => array( 
							esc_html__( 'List', 'viem' ) => 'list', 
							esc_html__( 'Grid', 'viem' ) => 'grid', 
							esc_html__( 'Masonry', 'viem' ) => 'masonry' ), 
						'value' => 'grid' ),
					array( 
						'type' => 'select', 
						'label' => esc_html__( 'Movies Columns', 'viem' ), 
						'name' => 'movies-per-page',
						'dependency' => array(
							'element' => 'movies-style',
							'value' => array( 'grid', 'default', 'masonry' ) ),
						'options' => array( '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7' ), 
						'value' => '5' ),
					array( 
						'type' => 'select', 
						'label' => esc_html__( 'Pagination', 'viem' ), 
						'name' => 'movies-pagination', 
						'options' => array( 
							esc_html__( 'WP PageNavi', 'viem' ) => 'wp_pagenavi', 
							esc_html__( 'Ajax Load More', 'viem' ) => 'loadmore', 
							esc_html__( 'Infinite Scrolling', 'viem' ) => 'infinite_scroll' ), 
						'value' => 'loadmore', 
						'desc' => esc_html__( 'Choose pagination type.', 'viem' ) ),
					array(
						'name' => 'actor_settings', 
						'type' => 'heading', 
						'status' => 'open', 
						'text' => esc_html__( 'Actor Settings', 'viem' ) ),
					array(
						'type' => 'single_select_page',
						'label' => esc_html__( 'Main Actors Page', 'viem' ),
						'name' => 'main-actor-page',
						'value' => '' ),
				) )

			;
		

			
		
		endif;
		$section['video'] = array( 
			'icon' => 'fa fa-youtube-play', 
			'title' => esc_html__( 'Video', 'viem' ), 
			'desc' => esc_html__( 'Video Setttings', 'viem' ), 
			'fields' => array( 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Videos URL slug', 'viem' ), 
					'name' => 'videos_slug', 
					'placeholder' => 'videos', 
					'width' => '200px', 
					'value' => 'videos', 
					'desc' => esc_html__( 'The slug used for building the videos URL.', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Single video URL slug', 'viem' ), 
					'name' => 'single_video_slug', 
					'placeholder' => 'video', 
					'width' => '200px', 
					'value' => 'video', 
					'desc' => esc_html__( 'The above should ideally be plural, and this singular.', 'viem' ) ), 
				array( 
					'name' => 'general_event_setting', 
					'type' => 'heading', 
					'status' => 'open', 
					'text' => esc_html__( 'General Settings', 'viem' ) ), 
				array( 
					'name' => 'video_instance_theme', 
					'type' => 'select', 
					'label' => esc_html__( 'Select Player Theme', 'viem' ), 
					'desc' => '', 
					'options' => array( 
						esc_html__( 'Dark', 'viem' ) => 'dark', 
						esc_html__( 'Light', 'viem' ) => 'light' ), 
					'value' => 'dark' ),
				array( 
					'name' => 'video_player_shadow', 
					'type' => 'select', 
					'label' => esc_html__( 'Select player shadow', 'viem' ), 
					'desc' => '', 
					'options' => array( 
						esc_html__( 'off', 'viem' ) => 'off',
						esc_html__( 'effect1', 'viem' ) => 'effect1',
						esc_html__( 'effect2', 'viem' ) => 'effect2',
						esc_html__( 'effect3', 'viem' ) => 'effect3',
						esc_html__( 'effect4', 'viem' ) => 'effect4',
						esc_html__( 'effect5', 'viem' ) => 'effect5',
						esc_html__( 'effect6', 'viem' ) => 'effect6',
					),
					'value' => 'off' ),
				array( 
					'name' => 'video_play_btn', 
					'type' => 'image', 
					'value' => get_template_directory_uri() . '/assets/images/video-player/playButtonPoster.png', 
					'label' => esc_html__( 'Play Button ', 'viem' ), 
					'width' => '60px', 
					'desc' => esc_html__( 'Upload your own play button image.', 'viem' ) ), 
				array(
					'name' => 'video_ratio',
					'type' => 'select',
					'label' => esc_html__( 'Video Ratio', 'viem' ),
					'desc' => esc_html__( 'set your video ratio (calculate video width/video height)', 'viem' ),
					'options' => array(
						esc_html__( '16/9', 'viem' ) => '169',
						esc_html__( '4/3', 'viem' ) => '43',
						esc_html__( '3/2', 'viem' ) => '32',
						esc_html__( '21/9', 'viem' ) => '219',
					),
					'value' => '169' ),
				array(
					'name' => 'sticky_player',
					'type' => 'switch',
					'on' => esc_html__( 'Yes', 'viem' ),
					'off' => esc_html__( 'No', 'viem' ),
					'label' => esc_html__( 'Sticky player', 'viem' ),
					'desc' => esc_html__( 'Sticky player if not in viewport when scrolling through page', 'viem' ),
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'video_autoplay', 
					'type' => 'switch', 
					'on' => esc_html__( 'Yes', 'viem' ), 
					'off' => esc_html__( 'No', 'viem' ), 
					'label' => esc_html__( 'Autoplay', 'viem' ), 
					'desc' => '', 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'video_on_finish', 
					'type' => 'switch', 
					'label' => esc_html__( 'Auto Next Video', 'viem' ), 
					'on' => esc_html__( 'Yes', 'viem' ), 
					'off' => esc_html__( 'No', 'viem' ), 
					'value' => '1' ),  // 1 = Play next video
				array( 
					'name' => 'allow_skip_ad', 
					'type' => 'switch', 
					'on' => esc_html__( 'Yes', 'viem' ), 
					'off' => esc_html__( 'No', 'viem' ), 
					'label' => esc_html__( 'Allow Users to skip ad', 'viem' ), 
					'desc' => '', 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_light', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Light', 'viem' ), 
					'desc' => esc_html__( 'In the video player. Show/Hide the Light', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_watchlater', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Watch Later', 'viem' ), 
					'desc' => esc_html__( 'In the video player. Show/Hide Watch Later', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_like', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Like/Unlike', 'viem' ), 
					'desc' => esc_html__( 
						'In the video player. Show/Hide WTiLikePost. You need to install the plugin', 
						'viem' ) . ' <a href="https://wordpress.org/plugins/wti-like-post/">WTI Like Post</a>', 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_views_count', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Views Count', 'viem' ), 
					'desc' => '', 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_comments_count', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Comments Count', 'viem' ), 
					'desc' => '', 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_show_share', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show Sharing Button', 'viem' ), 
					'desc' => esc_html__( 
						'Activate this to enable social sharing buttons on single post page.', 
						'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_facebook', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Facebook', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_twitter', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Twitter', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_google', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Google+', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_linkedIn', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on LinkedIn', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_tumblr', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Tumblr', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_pinterest', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Pinterest', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'viem_video_sharing_email', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'viem_video_show_share', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Share on Pinterest', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				
				array( 
					'name' => 'viem_video_show_more_video', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'More Video', 'viem' ), 
					'desc' => esc_html__( 'In the video player. Show/Hide More Video in slider.', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				
				array( 
					'name' => 'video_youtube_player', 
					'type' => 'heading', 
					'text' => esc_html__( 'YouTube Player', 'viem' ) ),
				array(
					'name' => 'youtube_controls',
					'type' => 'select',
					'label' => esc_html__( 'Select YouTube player controls', 'viem' ),
					'desc' => esc_html__( 'choose "default" or "custom" controls on YouTube player', 'viem' ),
					'options' => array(
						esc_html__( 'default controls', 'viem' ) => 'default controls',
						esc_html__( 'custom controls', 'viem' ) => 'custom controls',
						),
					'value' => 'default controls' ),
				array( 
					'name' => 'video_youtube_quality', 
					'type' => 'select', 
					'label' => esc_html__( 'Youtube Quality', 'viem' ), 
					'desc' => '', 
					'options' => array( 
						esc_html__( 'Default', 'viem' ) => 'default', 
						esc_html__( 'Small', 'viem' ) => 'small', 
						esc_html__( 'Medium', 'viem' ) => 'medium', 
						esc_html__( 'Large', 'viem' ) => 'large', 
						esc_html__( 'DH720', 'viem' ) => 'hd720', 
						esc_html__( 'HD1080', 'viem' ) => 'hd1080', 
						esc_html__( 'Highres', 'viem' ) => 'highres' ), 
					'value' => 'default' ), 
				array( 
					'name' => 'youtubeShowRelatedVideos', 
					'type' => 'select', 
					'label' => esc_html__( 'Show Youtube related videos', 'viem' ), 
					'desc' => esc_html__( 'choose to show/hide Youtube related videos, when YouTube is "stopped" on finish', 'viem' ),
					'options' => array(
						esc_html__( 'No', 'viem' ) => 'No', 
						esc_html__( 'Yes', 'viem' ) => 'Yes',),
					'value' => 'No' ),
				
				array( 
					'name' => 'video_vimeo_player', 
					'type' => 'heading', 
					'text' => esc_html__( 'Vimeo Player', 'viem' ) ), 
				array( 
					'name' => 'vimeo_player_color', 
					'type' => 'color', 
					'label' => esc_html__( 'Vimeo player color', 'viem' ), 
					'desc' => '', 
					'value' => '#00adef' ), 
				array( 
					'name' => 'videos_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Videos Settings', 'viem' ) ), 
				array( 
					'type' => 'single_select_page', 
					'label' => esc_html__( 'Main Videos Page', 'viem' ), 
					'name' => 'main-video-page', 
					'value' => '' ), 
				array( 
					'name' => 'videos-layout', 
					'type' => 'image_select', 
					'label' => esc_html__( 'Videos Layout', 'viem' ), 
					'desc' => esc_html__( 'Select Archive layout. Choose between 1, 2 or 3 columns layout.', 'viem' ), 
					'options' => array( 
						'full-width' => array( 'alt' => 'No sidebar', 'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
						'left-sidebar' => array( 
							'alt' => '2 Column Left', 
							'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
						'right-sidebar' => array( 
							'alt' => '2 Column Right', 
							'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
					'value' => 'full-width' ),
				array(
					'name' => 'videos-sidebar', 
					'type' => 'select', 
					'label' => esc_html__( 'Videos Sidebar', 'viem' ), 
					'desc' => esc_html__( 'Select sidebar, default is Main Sidebar.', 'viem' ),
					'dependency' => array( 'element' => 'videos-layout', 'value' => array( 'left-sidebar', 'right-sidebar') ),
					'options' => $sidebar_options, 
					'value' => 'main-sidebar' ),
				array( 
					'name' => 'videos-style', 
					'type' => 'select', 
					'label' => esc_html__( 'Style', 'viem' ), 
					'desc' => esc_html__( 'How your videos will display.', 'viem' ), 
					'options' => array( 
						esc_html__( 'List', 'viem' ) => 'list', 
						esc_html__( 'Grid', 'viem' ) => 'grid', 
						esc_html__( 'Masonry', 'viem' ) => 'masonry' ), 
					'value' => 'grid' ), 
				array( 
					'name' => 'videos-columns', 
					'type' => 'image_select', 
					'label' => esc_html__( 'Videos Columns', 'viem' ), 
					'desc' => esc_html__( 'Select videos columns.', 'viem' ), 
					'dependency' => array( 'element' => 'videos-style', 'value' => array( 'grid', 'default', 'masonry' ) ), 
					'options' => array( 
						'2' => array( 'alt' => '2 Columns', 'img' => DTINC_ASSETS_URL . '/images/2col.png' ), 
						'3' => array( 'alt' => '3 Columns', 'img' => DTINC_ASSETS_URL . '/images/3col.png' ), 
						'4' => array( 'alt' => '4 Columns', 'img' => DTINC_ASSETS_URL . '/images/4col.png' ) ), 
					'value' => '4' ), 
				array(
					'name' => 'videos-thumbImg',
					'type' => 'select',
					'label' => esc_html__( 'Videos Thumbnail', 'viem' ),
					'desc' => esc_html__( 'select "Auto" to grab it automatically from Youtube, Vimeo, .mp4 file. If you upload the Featured Image in the single video editor then the Featured Image will be used.', 'viem' ),
					'options' => array(
						esc_html__( 'Upload Featured Image', 'viem' ) => 'upload', 
						esc_html__( 'Auto', 'viem' ) => 'auto' ),
					'value' => 'upload' ),
				array( 
					'name' => 'video-image-size', 
					'type' => 'select', 
					'label' => esc_html__( 'Featured Image Size', 'viem' ),
					'dependency' => array( 'element' => 'videos-thumbImg', 'value' => array( 'upload' ) ),
					'desc' => '<a target="_self" href="' . esc_url( admin_url( 'themes.php?page=theme-options/' ) ) .
						 '#advanced">' . esc_html__( 'Edit image sizes', 'viem' ) . '</a>.', 
						'options' => array_merge(
							array( esc_html__( 'Default', 'viem' ) => '' ), 
							viem_image_sizes_select_values() ), 
						'value' => '' ), 
				array( 
					'type' => 'select', 
					'label' => esc_html__( 'Pagination', 'viem' ), 
					'name' => 'videos-pagination', 
					'options' => array( 
						esc_html__( 'WP PageNavi', 'viem' ) => 'wp_pagenavi', 
						esc_html__( 'Ajax Load More', 'viem' ) => 'loadmore', 
						esc_html__( 'Infinite Scrolling', 'viem' ) => 'infinite_scroll' ), 
					'value' => 'loadmore',
					'dependency' => array(
						'element' => 'videos-style', 
						'value' => array( 'default', 'list', 'grid', 'masonry' ) ), 
					'desc' => esc_html__( 'Choose pagination type.', 'viem' ) ), 
				array( 
					'name' => 'single_video_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Single Video Settings', 'viem' ) ), 
				array( 
					'name' => 'single-video-layout', 
					'type' => 'image_select', 
					'label' => esc_html__( 'Single Video Layout', 'viem' ), 
					'desc' => esc_html__( 
						'Select main single event layout. Choose between 1, 2 columns layout.', 
						'viem' ), 
					'options' => array( 
						'full-width' => array( 'alt' => 'No sidebar', 'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
						'left-sidebar' => array( 
							'alt' => '2 Column Left', 
							'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
						'right-sidebar' => array( 
							'alt' => '2 Column Right', 
							'img' => DTINC_ASSETS_URL . '/images/2cr.png' ), 
						'left-right-sidebar' => array( 
							'alt' => '3 Column Left - Right Siderbar', 
							'img' => DTINC_ASSETS_URL . '/images/3cm.png' ) ), 
					'value' => 'right-sidebar' ), 
				array( 
					'label' => esc_html__( 'Video player Layout', 'viem' ), 
					'desc' => esc_html__( 'Default to use the setting in Theme Options.', 'viem' ), 
					'name' => 'single-video-style', 
					'type' => 'select', 
					'options' => array( 
						esc_html__( 'Top Header', 'viem' ) => 'style-1', 
						esc_html__( 'In Container', 'viem' ) => 'style-2' ), 
					'value' => 'style-1' ), 
				array( 
					'name' => 'single_hidden_content_video', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Content Show More', 'viem' ), 
					'desc' => esc_html__( 
						'Display Show More button in the content to hide the text in the content with the toogle effect.', 
						'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_date', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Date Meta', 'viem' ), 
					'desc' => esc_html__( 'In Single video. Show/Hide the date meta', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_category', 
					'type' => 'switch', 
					'label' => esc_html__( 'Show/Hide Category', 'viem' ), 
					'desc' => esc_html__( 'In Single video. Show/Hide the category', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_author', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Author Meta', 'viem' ), 
					'desc' => esc_html__( 'In Single video. Show/Hide the author meta', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_tag', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show/Hide Tag', 'viem' ), 
					'desc' => esc_html__( 'In Single video. If enabled it will show tag.', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_authorbio', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show Author Bio', 'viem' ), 
					'desc' => esc_html__( 
						'Display the author bio at the bottom of video on single video page ?', 
						'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'single_video_show_postnav', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show Next/Prev Post Link On Single Video Page', 'viem' ), 
					'desc' => esc_html__( 
						'Using this will add a link at the bottom of every post page that leads to the next/prev post.', 
						'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_related_videos', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show Related Video On Single Video Page', 'viem' ), 
					'desc' => esc_html__( 'Display related video in the bottom of videos?', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'related_videos_count', 
					'type' => 'text', 
					'label' => esc_html__( 'Related Post Count', 'viem' ), 
					'desc' => esc_html__( 'Number of related post to query', 'viem' ), 
					'dependency' => array( 'element' => 'show_related_videos', 'value' => array( '1' ) ), 
					'value' => '4' ),
				array( 
					'name' => 'show_related_channels', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'label' => esc_html__( 'Show Related Channels On Single Video Page', 'viem' ), 
					'desc' => esc_html__( 'Display related Channels in the bottom of videos? (The channels query orderby random)', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'related_channels_count', 
					'type' => 'text', 
					'label' => esc_html__( 'Related Channels Count', 'viem' ), 
					'desc' => esc_html__( 'Number of related Channels to query', 'viem' ), 
					'dependency' => array( 'element' => 'show_related_channels', 'value' => array( '1' ) ), 
					'value' => '2' ),
				
				/*array( 
					'name' => 'video_playlist_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Playlist Settings', 'viem' ) ), */
				/*
				array( 
					'name' => 'video_chanel_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Channel Settings', 'viem' ) ), */
				array( 
					'name' => 'video_translation_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Translation Player', 'viem' ), 
					'desc' => esc_html__( 'translate to your language', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Advertisement', 'viem' ), 
					'name' => 'advertisement_title', 
					'placeholder' => 'Advertisement', 
					'width' => '200px', 
					'value' => esc_html__( 'Advertisement', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Skip advertisement', 'viem' ), 
					'name' => 'skip_advertisement_text', 
					'placeholder' => 'Skip advertisement', 
					'width' => '200px', 
					'value' => esc_html__( 'Skip advertisement', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'You can skip this ad in', 'viem' ), 
					'name' => 'skip_ad_text', 
					'placeholder' => 'You can skip this ad in', 
					'width' => '200px', 
					'value' => esc_html__( 'You can skip this ad in', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Play Text', 'viem' ), 
					'name' => 'play_btn_tooltip_txt', 
					'placeholder' => 'Play', 
					'width' => '200px', 
					'value' => esc_html__( 'Play', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Pause Text', 'viem' ), 
					'name' => 'pause_btn_tooltip_txt', 
					'placeholder' => 'Pause', 
					'width' => '200px', 
					'value' => esc_html__( 'Pause', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Rewind Text', 'viem' ), 
					'name' => 'rewind_btn_tooltip_txt', 
					'placeholder' => 'Rewind', 
					'width' => '200px', 
					'value' => esc_html__( 'Rewind', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Close settings', 'viem' ), 
					'name' => 'quality_btn_opened_tooltip_txt', 
					'placeholder' => 'Close settings', 
					'width' => '200px', 
					'value' => esc_html__( 'Close settings', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Settings', 'viem' ), 
					'name' => 'quality_btn_close_tooltip_txt', 
					'placeholder' => 'Settings', 
					'width' => '200px', 
					'value' => esc_html__( 'Settings', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Mute', 'viem' ), 
					'name' => 'mute_btn_tooltip_txt', 
					'placeholder' => 'Mute', 
					'width' => '200px', 
					'value' => esc_html__( 'Mute', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Unmute', 'viem' ), 
					'name' => 'unmute_btn_tooltip_txt', 
					'placeholder' => 'Unmute', 
					'width' => '200px', 
					'value' => esc_html__( 'Unmute', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Fullscreen', 'viem' ), 
					'name' => 'fullscreen_btn_tooltip_txt', 
					'placeholder' => 'Fullscreen', 
					'width' => '200px', 
					'value' => esc_html__( 'Fullscreen', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Exit fullscreen', 'viem' ), 
					'name' => 'exit_fullscreen_btn_tooltip_txt', 
					'placeholder' => 'Exit fullscreen', 
					'width' => '200px', 
					'value' => esc_html__( 'Exit fullscreen', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Volume', 'viem' ), 
					'name' => 'volume_tooltip_txt', 
					'placeholder' => 'Volume', 
					'width' => '200px', 
					'value' => esc_html__( 'Volume', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Show playlist', 'viem' ), 
					'name' => 'playlist_btn_closed_tooltip_txt', 
					'placeholder' => 'Show playlist', 
					'width' => '200px', 
					'value' => esc_html__( 'Show playlist', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Hide playlist', 'viem' ), 
					'name' => 'playlist_btn_opened_tooltip_txt', 
					'placeholder' => 'Hide playlist', 
					'width' => '200px', 
					'value' => esc_html__( 'Hide playlist', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Go to last video', 'viem' ), 
					'name' => 'last_btn_tooltip_txt', 
					'placeholder' => 'Go to last video', 
					'width' => '200px', 
					'value' => esc_html__( 'Go to last video', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Go to first video', 'viem' ), 
					'name' => 'first_btn_tooltip_txt', 
					'placeholder' => 'Go to first video', 
					'width' => '200px', 
					'value' => esc_html__( 'Go to first video', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Play next video', 'viem' ), 
					'name' => 'next_btn_tooltip_txt', 
					'placeholder' => 'Play next video', 
					'width' => '200px', 
					'value' => esc_html__( 'Play next video', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Play previous video', 'viem' ), 
					'name' => 'previous_btn_tooltip_txt', 
					'placeholder' => 'Play previous video', 
					'width' => '200px', 
					'value' => esc_html__( 'Play previous video', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Shuffle on', 'viem' ), 
					'name' => 'shuffle_btn_on_tooltip_txt', 
					'placeholder' => 'Shuffle on', 
					'width' => '200px', 
					'value' => esc_html__( 'Shuffle on', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Shuffle off', 'viem' ), 
					'name' => 'shuffle_btn_off_tooltip_txt', 
					'placeholder' => 'Shuffle off', 
					'width' => '200px', 
					'value' => esc_html__( 'Shuffle off', 'viem' ) ), 
				array( 
					'name' => 'community_video_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Community Videos Settings', 'viem' ), 
					'desc' => esc_html__( 
						'Community Videos enables users to submit videos through a form on your site.', 
						'viem' ) ), 
				array( 
					'type' => 'switch', 
					'label' => esc_html__( 'Enable Submit Videos', 'viem' ), 
					'name' => 'allow_community_videos', 
					'on' => esc_html__( 'Yes', 'viem' ), 
					'off' => esc_html__( 'No', 'viem' ), 
					'value' => '0', 
					'desc' => esc_html__( 'Show / Hide the submit video button.', 'viem' ) ), 
				array( 
					'type' => 'switch', 
					'label' => esc_html__( 'Allow anonymous submissions', 'viem' ), 
					'name' => 'allow_anonymous_submissions', 
					'on' => esc_html__( 'Yes', 'viem' ), 
					'off' => esc_html__( 'No', 'viem' ), 
					'value' => '1', 
					'desc' => esc_html__( 'Allow users to submit videos without having a WordPress account', 'viem' ) ), 
				array( 
					'name' => 'community_default_status', 
					'type' => 'select', 
					'label' => esc_html__( 'Default status for submitted videos', 'viem' ), 
					'options' => array( 
						esc_html__( 'Draft', 'viem' ) => 'draft', 
						esc_html__( 'Pending', 'viem' ) => 'pending', 
						esc_html__( 'Published', 'viem' ) => 'publish' ), 
					'value' => 'pending' ), 
				array( 
					'type' => 'single_select_page', 
					'label' => esc_html__( 'Community Videos Add Page', 'viem' ), 
					'name' => 'community-add-page', 
					'value' => '', 
					'desc' => esc_html__( 'Submit Video Page. Page contents: [viem_community_add]', 'viem' ) ), 
				array( 
					'type' => 'single_select_page', 
					'label' => esc_html__( 'Community Videos List Page', 'viem' ), 
					'name' => 'community-list-page', 
					'value' => '', 
					'desc' => esc_html__( 
						'My Videos Page. Only logged in Users can view. Page contents: [viem_community_list]', 
						'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Videos per page', 'viem' ), 
					'name' => 'videos-per-page', 
					'placeholder' => '10', 
					'width' => '200px', 
					'value' => 10 ) ) );
		
		if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
			$section['woocommerce'] = array( 
				'icon' => 'fa fa-shopping-cart', 
				'title' => esc_html__( 'Woocommerce', 'viem' ), 
				'desc' => esc_html__( 'Customize Woocommerce', 'viem' ), 
				'fields' => array( 
					array( 
						'name' => 'woocommerce_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'General Settings', 'viem' ) ), 
					array( 
						'name' => 'price_color', 
						'type' => 'color', 
						'label' => esc_html__( 'Price Color', 'viem' ), 
						'desc' => esc_html__( 'Choose main color of price', 'viem' ), 
						'value' => '#f36e0c' ), 
					array( 
						'name' => 'woo-cart-nav', 
						'type' => 'switch', 
						'on' => esc_html__( 'Show', 'viem' ), 
						'off' => esc_html__( 'Hide', 'viem' ), 
						'label' => esc_html__( 'Cart In header', 'viem' ), 
						'desc' => esc_html__( 'This will show cat in header.', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'list_product_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'List Product Settings', 'viem' ) ), 
					array( 
						'name' => 'woo-shop-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Shop Layout', 'viem' ), 
						'desc' => esc_html__( 'Select shop layout.', 'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'full-width' ),
					/*array( 
						'name' => 'dt_woocommerce_view_mode', 
						'type' => 'buttonset', 
						'label' => esc_html__( 'Default View Mode', 'viem' ), 
						'desc' => esc_html__( 'Select default view mode', 'viem' ), 
						'value' => 'grid', 
						'options' => array( 
							'grid' => esc_html__( 'Grid', 'viem' ), 
							'list' => esc_html__( 'List', 'viem' ) ) ), */
					array( 
						'name' => 'woo-per-row', 
						'type' => 'text', 
						'value' => 3, 
						'label' => esc_html__( 'Number of Products per row', 'viem' ), 
						'desc' => esc_html__( 'Enter the products of posts to display per row.', 'viem' ) ), 
					array( 
						'name' => 'woo-per-page', 
						'type' => 'text', 
						'value' => 9, 
						'label' => esc_html__( 'Number of Products per Page', 'viem' ), 
						'desc' => esc_html__( 'Enter the products of posts to display per page.', 'viem' ) ), 
					array( 
						'name' => 'single_product_setting', 
						'type' => 'heading', 
						'text' => esc_html__( 'Single Product Settings', 'viem' ) ), 
					array( 
						'name' => 'woo-product-layout', 
						'type' => 'image_select', 
						'label' => esc_html__( 'Single Product Layout', 'viem' ), 
						'desc' => esc_html__( 'Select single product layout.', 'viem' ), 
						'options' => array( 
							'full-width' => array( 
								'alt' => 'No sidebar', 
								'img' => DTINC_ASSETS_URL . '/images/1col.png' ), 
							'left-sidebar' => array( 
								'alt' => '2 Column Left', 
								'img' => DTINC_ASSETS_URL . '/images/2cl.png' ), 
							'right-sidebar' => array( 
								'alt' => '2 Column Right', 
								'img' => DTINC_ASSETS_URL . '/images/2cr.png' ) ), 
						'value' => 'full-width' ), 
					array( 
						'name' => 'show-woo-share', 
						'type' => 'switch', 
						'label' => esc_html__( 'Show Sharing Button', 'viem' ), 
						'desc' => esc_html__( 'Activate this to enable social sharing buttons.', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'woo-fb-share', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Facebook', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'woo-tw-share', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Twitter', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'woo-go-share', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Google+', 'viem' ), 
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'woo-pi-share', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on Pinterest', 'viem' ), 
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array( 
						'name' => 'woo-li-share', 
						'type' => 'switch', 
						'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ), 
						'label' => esc_html__( 'Share on LinkedIn', 'viem' ), 
						'value' => '0' ), 
					array( 
						'name' => 'woo-related-count', 
						'type' => 'select', 
						'label' => esc_html__( 'Related Post Count', 'viem' ), 
						'desc' => esc_html__( 'Number of related Product per page', 'viem' ), 
						'options' => array( '3' => 3, '4' => 4 ), 
						'value' => 4 ) ) );
		}
		
		$section['social'] = array( 
			'icon' => 'fa fa-twitter', 
			'title' => esc_html__( 'Social Profile', 'viem' ), 
			'desc' => wp_kses( 
				__( 
					'Enter in your profile media locations here.<br><strong>Remember to include the "http://" in all URLs!</strong>', 
					'viem' ), 
				array( 'br' => array(), 'strong' => array() ) ), 
			'fields' => array( 
				array( 'name' => 'facebook-url', 'type' => 'text', 'label' => esc_html__( 'Facebook URL', 'viem' ) ), 
				array( 'name' => 'twitter-url', 'type' => 'text', 'label' => esc_html__( 'Twitter URL', 'viem' ) ), 
				array( 'name' => 'google-plus-url', 'type' => 'text', 'label' => esc_html__( 'Google+ URL', 'viem' ) ), 
				array( 'name' => 'youtube-url', 'type' => 'text', 'label' => esc_html__( 'Youtube URL', 'viem' ) ), 
				array( 'name' => 'vimeo-url', 'type' => 'text', 'label' => esc_html__( 'Vimeo URL', 'viem' ) ), 
				array( 'name' => 'pinterest-url', 'type' => 'text', 'label' => esc_html__( 'Pinterest URL', 'viem' ) ), 
				array( 'name' => 'linkedin-url', 'type' => 'text', 'label' => esc_html__( 'LinkedIn URL', 'viem' ) ), 
				array( 'name' => 'rss-url', 'type' => 'text', 'label' => esc_html__( 'RSS URL', 'viem' ) ), 
				array( 'name' => 'instagram-url', 'type' => 'text', 'label' => esc_html__( 'Instagram URL', 'viem' ) ), 
				array( 'name' => 'github-url', 'type' => 'text', 'label' => esc_html__( 'GitHub URL', 'viem' ) ), 
				array( 'name' => 'behance-url', 'type' => 'text', 'label' => esc_html__( 'Behance URL', 'viem' ) ), 
				array( 
					'name' => 'stack-exchange-url', 
					'type' => 'text', 
					'label' => esc_html__( 'Stack Exchange URL', 'viem' ) ), 
				array( 'name' => 'tumblr-url', 'type' => 'text', 'label' => esc_html__( 'Tumblr URL', 'viem' ) ), 
				array( 'name' => 'soundcloud-url', 'type' => 'text', 'label' => esc_html__( 'SoundCloud URL', 'viem' ) ), 
				array( 'name' => 'dribbble-url', 'type' => 'text', 'label' => esc_html__( 'Dribbble URL', 'viem' ) ), 
				array( 
					'name' => 'social-target', 
					'type' => 'select', 
					'label' => esc_html__( 'Target', 'viem' ), 
					'desc' => esc_html__( 'The target attribute specifies where to open the linked social.', 'viem' ), 
					'options' => array( 
						esc_html__( 'Blank', 'viem' ) => '_blank', 
						esc_html__( 'Self', 'viem' ) => '_self' ), 
					'value' => '_blank' ) ) );
		
		$section['mailchimp_api'] = array( 
			'icon' => 'fa fa-envelope-o', 
			'title' => esc_html__( 'Mailchimp Settings', 'viem' ), 
			'desc' => esc_html__( 'MailChimp API', 'viem' ), 
			'fields' => array( 
				array( 
					'name' => 'mailchimp_heading', 
					'type' => 'heading', 
					'text' => esc_html__( 'MailChimp API', 'viem' ) ), 
				array( 
					'name' => 'mailchimp_api', 
					'type' => 'text', 
					'label' => esc_html__( 'MailChimp API Key', 'viem' ), 
					'desc' => sprintf( 
						__( 'Enter your API Key.<a target="_blank" href="%s">Get your API key</a>', 'viem' ), 
						'http://admin.mailchimp.com/account/api-key-popup' ) ), 
				array( 
					'name' => 'mailchimp_list', 
					'type' => 'select', 
					'options' => viem_get_mailchimplist(), 
					'label' => esc_html__( 'MailChimp List', 'viem' ), 
					'desc' => esc_html__( 
						'After you add your MailChimp API Key above and save it this list will be populated.', 
						'viem' ) ), 
				array( 
					'name' => 'mailchimp_opt_in', 
					'type' => 'switch', 
					'label' => esc_html__( 'Enable Double Opt-In', 'viem' ), 
					'desc' => sprintf( 
						__( 'Learn more about <a target="_blank" href="%s">Double Opt-in</a>.', 'viem' ), 
						'http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work' ) ), 
				array( 
					'name' => 'mailchimp_welcome_email', 
					'type' => 'switch', 
					'label' => esc_html__( 'Send Welcome Email', 'viem' ), 
					'desc' => sprintf( 
						__( 
							'If your Double Opt-in is false and this is true, MailChimp will send your lists Welcome Email if this subscribe succeeds - this will not fire if MailChimp ends up updating an existing subscriber. If Double Opt-in is true, this has no effect. Learn more about <a target="_blank" href="%s">Welcome Emails</a>.', 
							'viem' ), 
						'http://blog.mailchimp.com/sending-welcome-emails-with-mailchimp/' ) ), 
				array( 
					'name' => 'mailchimp_group_name', 
					'type' => 'text', 
					'label' => esc_html__( 'MailChimp Group Name', 'viem' ), 
					'desc' => sprintf( 
						__( 
							'Optional: Enter the name of the group. Learn more about <a target="_blank" href="%s">Groups</a>', 
							'viem' ), 
						'http://mailchimp.com/features/groups/' ) ), 
				array( 
					'name' => 'mailchimp_group', 
					'type' => 'text', 
					'label' => esc_html__( 'MailChimp Group', 'viem' ), 
					'desc' => esc_html__( 
						'Optional: Comma delimited list of interest groups to add the email to.', 
						'viem' ) ), 
				array( 
					'name' => 'mailchimp_replace_interests', 
					'type' => 'switch', 
					'label' => esc_html__( 'MailChimp Replace Interests', 'viem' ), 
					'desc' => esc_html__( 
						'Whether MailChimp will replace the interest groups with the groups provided or add the provided groups to the member interest groups.', 
						'viem' ) ) ) );
		
		$section['advertising'] = array( 
			'icon' => 'fa fa-bullhorn', 
			'title' => esc_html__( 'Advertising', 'viem' ), 
			'desc' => esc_html__( 'Advertising Settings', 'viem' ), 
			'fields' => array( 
				array( 
					'name' => 'adsense_id_heading', 
					'type' => 'heading', 
					'text' => esc_html__( 'AdSense ID', 'viem' ) ), 
				array( 
					'name' => 'adsense_id', 
					'type' => 'text', 
					'label' => esc_html__( 'Google AdSense Publisher ID', 'viem' ), 
					'desc' => esc_html__( 'Enter your Google AdSense Publisher ID', 'viem' ) ), 
				
				array( 
					'name' => 'ads_wall_left_heading', 
					'type' => 'heading', 
					'text' => esc_html__( 'Wall Ads Left', 'viem' ) ), 
				array( 
					'name' => 'adsense_slot_ads_wall_left', 
					'type' => 'text', 
					'label' => esc_html__( 'AdSense Ads Slot ID', 'viem' ), 
					'desc' => esc_html__( 
						'Google AdSense Ad Slot ID. If left empty, "Wall Ads Left - Custom Code" will be used.', 
						'viem' ) ), 
				array( 
					'name' => 'ads_wall_left_custom', 
					'type' => 'textarea', 
					'label' => esc_html__( 'Wall Ads Left - Custom Code', 'viem' ), 
					'desc' => esc_html__( 'Enter custom code for Wall Ads Left', 'viem' ) ), 
				
				array( 
					'name' => 'ads_wall_right_heading', 
					'type' => 'heading', 
					'text' => esc_html__( 'Wall Ads Right', 'viem' ) ), 
				array( 
					'name' => 'adsense_slot_ads_wall_right', 
					'type' => 'text', 
					'label' => esc_html__( 'AdSense Ads Slot ID', 'viem' ), 
					'desc' => esc_html__( 
						'Google AdSense Ad Slot ID. If left empty, "Wall Ads Right - Custom Code" will be used.', 
						'viem' ) ), 
				array( 
					'name' => 'ads_wall_right_custom', 
					'type' => 'textarea', 
					'label' => esc_html__( 'Wall Ads Right - Custom Code', 'viem' ), 
					'desc' => esc_html__( 'Enter custom code for Wall Ads Right', 'viem' ) ) ) );
		
		$section['footer'] = array( 
			'icon' => 'fa fa-list-alt', 
			'title' => esc_html__( 'Footer', 'viem' ), 
			'desc' => esc_html__( 'Customize Footer', 'viem' ), 
			'fields' => array( 
				/*array( 
					'name' => 'footer_layout', 
					'type' => 'select', 
					'label' => esc_html__( 'Footer Layout', 'viem' ), 
					'desc' => '', 
					'options' => array(), 
					'value' => 'footer-1' ),*/
				array( 
					'name' => 'footer-copyright', 
					'type' => 'textarea', 
					'label' => esc_html__( 'Footer Copyright Text', 'viem' ), 
					'desc' => esc_html__( 'Please enter the copyright section text.', 'viem' ), 
					'note' => esc_html__( 
						'List of allowed HTML elements: <img>,<a></a> for links, <br/> for line break.', 
						'viem' ), 
					'value' => wp_kses( 
						'', 
						array( 
							'a' => array( 'href' => array(), 'title' => array() ), 
							'img' => array( 'src' => array(), 'alt' => array() ), 
							'br' => array() ) ) ), 
				/*array( 
					'name' => 'show_footer_social_profile', 
					'type' => 'switch', 
					'on' => esc_html__( 'Show', 'viem' ), 
					'off' => esc_html__( 'Hide', 'viem' ), 
					'dependency' => array( 'element' => 'footer_layout', 'value' => array( 'footer-1' ) ), 
					'label' => esc_html__( 'Show Social Profile on footer', 'viem' ), 
					'desc' => esc_html__( 
						'Activate this to enable social profile buttons on foooter. (Apply for Footer layout 1)', 
						'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_facebook', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Facebook', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_twitter', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Twitter', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_google-plus', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Google+', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_youtube', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Youtube', 'viem' ), 
					'value' => '1' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_vimeo', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Vimeo', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_pinterest', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Pinterest', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_linkedin', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'LinkedIn', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_rss', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'RSS', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_instagram', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Instagram', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_github', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'GitHub', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_behance', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Behance', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_stack-exchange', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Stack Exchange', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_tumblr', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Tumblr', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_soundcloud', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'SoundCloud', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array( 
					'name' => 'show_footer_dribbble', 
					'type' => 'switch', 
					'dependency' => array( 'element' => 'show_footer_social_profile', 'value' => array( '1' ) ), 
					'label' => esc_html__( 'Dribbble', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				
				array( 
					'name' => 'footer_color_setting', 
					'type' => 'heading', 
					'text' => esc_html__( 'Footer Color Scheme', 'viem' ) ), 
				array( 
					'name' => 'footer-color', 
					'type' => 'switch', 
					'label' => esc_html__( 'Custom Footer Color Scheme', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked 
				array( 
					'name' => 'footer-custom-color', 
					'type' => 'list_color', 
					'dependency' => array( 'element' => 'footer-color', 'value' => array( '1' ) ), 
					'options' => array( 
						'footer-widget-bg' => esc_html__( 'Footer Widget Area Background', 'viem' ), 
						'footer-widget-color' => esc_html__( 'Footer Widget Area Color', 'viem' ), 
						'footer-widget-link' => esc_html__( 'Footer Widget Area Link', 'viem' ), 
						'footer-widget-link-hover' => esc_html__( 'Footer Widget Area Link Hover', 'viem' ), 
						'footer-bg' => esc_html__( 'Footer Copyright Background', 'viem' ), 
						'footer-color' => esc_html__( 'Footer Copyright Color', 'viem' ), 
						'footer-link' => esc_html__( 'Footer Copyright Link', 'viem' ), 
						'footer-link-hover' => esc_html__( 'Footer Copyright Link Hover', 'viem' ) ) ),
				*/
		) );
		
		$section['page_not_found'] = array( 
			'icon' => 'fa fa-exclamation-triangle', 
			'title' => esc_html__( '404 Page', 'viem' ), 
			'fields' => array( 
				array( 
					'name' => 'page_not_found_bg', 
					'type' => 'image', 
					'value' => get_template_directory_uri() . '/assets/images/404-bg.png', 
					'label' => esc_html__( 'Background Image', 'viem' ), 
					'desc' => '' ), 
				
				array( 
					'type' => 'text', 
					'label' => esc_html__( '404 Heading', 'viem' ), 
					'name' => 'page_not_found_title', 
					'value' => esc_html__( 'Page not found', 'viem' ) ), 
				array( 
					'name' => 'page_not_found_btn_text', 
					'type' => 'text', 
					'label' => esc_html__( 'Button Redirect', 'viem' ), 
					'value' => esc_html__( 'Back to home page', 'viem' ) ), 
				array( 
					'type' => 'text', 
					'label' => esc_html__( 'Button Redirect URL', 'viem' ), 
					'name' => 'page_not_found_redirect_URL', 
					'value' => esc_url( home_url( '/' ) ), 
					'desc' => esc_html__( 'Default back to home page', 'viem' ), 
					'placeholder' => esc_html__( 'Please use "http://', 'viem' ) ) ) );
		
		$section['advanced'] = array( 
			'icon' => 'fa fa-cog', 
			'title' => esc_html__( 'Advanced', 'viem' ), 
			'fields' => array( 
				array( 'name' => 'theme_modules', 'type' => 'heading', 'text' => esc_html__( 'Theme Modules', 'viem' ) ), 
				array( 
					'name' => 'enable_viem_movie', 
					'type' => 'switch', 
					'label' => esc_html__( 'Movie module', 'viem' ), 
					'value' => '0' ),  // 1 = checked | 0 = unchecked
				array(
					'name' => 'echo-meta-tags',
					'type' => 'switch',
					'label' => esc_html__( 'SEO - Echo Meta Tags', 'viem' ),
					'value' => 0,
					'desc' => esc_html__(
						'Generates its own SEO meta tags (for example: Facebook Meta Tags). If you are using another SEO plugin like YOAST or a Facebook plugin, you can turn off this option',
						'viem' ) ),
				
				array( 
					'name' => 'custom_img_size', 
					'type' => 'heading', 
					'text' => esc_html__( 'Custom Image Sizes', 'viem' ) ), 
				array( 
					'name' => 'img_size', 
					'type' => 'img_size', 
					'std' => array( 
						array( 'width' => 350, 'height' => 350, 'crop' => 1 ), 
						array( 'width' => 600, 'height' => 600, 'crop' => 1 ) ) ) ) );

		
		
		$section['import_export'] = array( 
			'icon' => 'fa fa-download', 
			'title' => esc_html__( 'Import and Export', 'viem' ), 
			'fields' => array( 
				array( 
					'name' => 'import', 
					'type' => 'import', 
					'field-label' => esc_html__( 
						'Input your backup file below and hit Import to restore your sites options from a backup.', 
						'viem' ) ), 
				array( 
					'name' => 'export', 
					'type' => 'export', 
					'field-label' => esc_html__( 
						'Here you can download your current option settings.You can use it to restore your settings on this site (or any other site).', 
						'viem' ) ) ) );
		/*
		 * $section['auto_update'] = array(
		 * 'icon' => 'fa fa-spin fa-refresh',
		 * 'title' => esc_html__( 'Auto Update', 'viem' ),
		 * 'fields' => array(
		 * array(
		 * 'type' => 'text',
		 * 'label' => esc_html__( 'Envato Username', 'viem' ),
		 * 'name' => 'envato_username',
		 * 'placeholder' => '',
		 * 'value' => '',
		 * 'desc' => esc_html__('Enter your Envato username', 'viem')
		 * ),
		 * array(
		 * 'type' => 'text',
		 * 'label' => esc_html__( 'Envato API', 'viem' ),
		 * 'name' => 'envato_api',
		 * 'placeholder' => '',
		 * 'value' => '',
		 * 'desc' => esc_html__('Enter your Envato API. You can find your API under in Profile page > Settings > API
		 * Keys', 'viem')
		 * ),
		 * array(
		 * 'name' => 'envato_auto_update',
		 * 'type' => 'switch',
		 * 'on' => esc_html__( 'On', 'viem' ),
		 * 'off' => esc_html__( 'Off', 'viem' ),
		 * 'label' => esc_html__( 'Auto Update', 'viem' ),
		 * 'desc' => esc_html__( 'Allow Auto Update or Not. If not, you can go to Appearance > Themes and click on
		 * Update link.', 'viem' ),
		 * 'value' => '0' // 1 = checked | 0 = unchecked
		 * ),
		 * )
		 * );
		 */
		
		dawnthemes_register_theme_options( $section );
	}






endif;