<?php
/*
 * Initialize Theme Options
 * Copy to your theme
 */
add_action('init', 'theme_text_domain_theme_options');

function theme_text_domain_theme_options(){
	$section = array();
	
	$section = array(
		'general' => array(
			'icon' => 'fa fa-home',
			'title' => esc_html__( 'General', 'dawnthemes' ),
			'desc' => __(
				'<p class="description">Here you will set your site-wide preferences.</p>',
				'dawnthemes' ),
			'fields' => array(
				array(
					'name' => 'logo',
					'type' => 'image',
					'value' => get_template_directory_uri() . '/assets/images/logo.png',
					'label' => esc_html__( 'Logo', 'dawnthemes' ),
					'desc' => esc_html__( 'Upload your own logo.', 'dawnthemes' ) ),
				array(
					'name' => 'sticky_logo',
					'type' => 'image',
					'value' => get_template_directory_uri() . '/assets/images/logo.png',
					'label' => esc_html__( 'Sticky Logo', 'dawnthemes' ),
					'desc' => esc_html__( 'Upload your own logo.This is optional use when fixed menu', 'dawnthemes' ) ),
					// 						array(
						// 							'name' => 'logo-transparent',
						// 							'type' => 'image',
						// 							'value' => get_template_directory_uri() . '/assets/images/logo-dark.png',
						// 							'label' => esc_html__( 'Transparent Menu Logo', 'dawnthemes' ),
						// 							'desc' => esc_html__(
							// 								'Upload your own logo.This is optional use for menu transparent',
						// 								'dawnthemes' ) ),
				// 						array(
					// 							'name' => 'logo-mobile',
					// 							'type' => 'image',
					// 							'value' => get_template_directory_uri() . '/assets/images/logo-mobile.png',
					// 							'label' => esc_html__( 'Mobile Version Logo', 'dawnthemes' ),
					// 							'desc' => esc_html__(
						// 								'Use this option to change your logo for mobile devices if your logo width is quite long to fit in mobile device screen.',
					// 								'dawnthemes' ) ),
				// 						array(
					// 							'name' => 'apple57',
					// 							'type' => 'image',
					// 							'label' => esc_html__( 'Apple Iphone Icon', 'dawnthemes' ),
					// 							'desc' => esc_html__( 'Apple Iphone Icon (57px 57px).', 'dawnthemes' ) ),
					// 						array(
						// 							'name' => 'apple72',
					// 							'type' => 'image',
					// 							'label' => esc_html__( 'Apple iPad Icon', 'dawnthemes' ),
					// 							'desc' => esc_html__( 'Apple Iphone Retina Icon (72px 72px).', 'dawnthemes' ) ),
						// 						array(
							// 							'name' => 'apple114',
							// 							'type' => 'image',
							// 							'label' => esc_html__( 'Apple Retina Icon', 'dawnthemes' ),
							// 							'desc' => esc_html__( 'Apple iPad Retina Icon (144px 144px).', 'dawnthemes' ) ),
		array(
			'name' => 'back_to_top',
			'type' => 'switch',
			'on' => esc_html__( 'Show', 'dawnthemes' ),
			'off' => esc_html__( 'Hide', 'dawnthemes' ),
			'label' => esc_html__( 'Back To Top Button', 'dawnthemes' ),
			'value' => 1,
			'desc' => esc_html__(
				'Toggle whether or not to enable a back to top button on your pages.',
				'dawnthemes' ) ) ) ),
	
					// 				'design_layout' => array(
						// 					'icon' => 'fa fa-columns',
						// 					'title' => esc_html__( 'Design and Layout', 'dawnthemes' ),
						// 					'desc' => __( '<p class="description">Customize Design and Layout.</p>', 'dawnthemes' ),
						// 					'fields' => array(
							// 						array(
								// 							'name' => 'body-bg',
								// 							'type' => 'color',
								// 							'label' => esc_html__( 'Body background', 'dawnthemes' ),
								// 							'value' => '' ) ) ),
								'color_typography' => array(
									'icon' => 'fa fa-font',
					'title' => esc_html__( 'Color and Typography', 'dawnthemes' ),
					'desc' => __( '<p class="description">Customize Color and Typography.</p>', 'dawnthemes' ),
						'fields' => array(
						array(
							'name' => 'main_color',
							'type' => 'color',
								'label' => esc_html__( 'Main Color', 'dawnthemes' ),
								'desc' => esc_html__( 'Choose main color of theme', 'dawnthemes' ),
									'value' => '#54af7d' ),
										array(
											'name' => 'main_font',
												'type' => 'custom_font',
												'field-label' => esc_html__( 'Main Font', 'dawnthemes' ),
							'desc' => esc_html__( 'Font family for body text', 'dawnthemes' ),
								'font-size' => 'true',
								'value' => array() ),
						array(
							'name' => 'secondary_font',
							'type' => 'custom_font',
							'field-label' => esc_html__( 'Secondary Font', 'dawnthemes' ),
							'desc' => esc_html__( 'Font family for the secondary font (ex: heading text)', 'dawnthemes' ),
								'font-size' => 'false',
								'value' => array() ),
								array(
							'name' => 'navbar_typography',
									'type' => 'custom_font',
									'field-label' => esc_html__( 'Navigation', 'dawnthemes' ),
									'desc' => esc_html__( 'Font family for the navigation', 'dawnthemes' ),
									'font-size' => 'false',
									'value' => array() ),
									)
									),
									'header' => array(
										'icon' => 'fa fa-header',
					'title' => esc_html__( 'Header', 'dawnthemes' ),
					'desc' => __( '<p class="description">Customize Header.</p>', 'dawnthemes' ),
							'fields' => array(
								// 						array(
									// 							'name' => 'header-style',
									// 							'type' => 'select',
									// 							'label' => esc_html__( 'Header Style', 'dawnthemes' ),
									// 							'desc' => esc_html__( 'Please select your header style here.', 'dawnthemes' ),
									// 							'options' => array(
										// 								'layout_1' => esc_html__( 'Layout 1', 'dawnthemes' ),
										// 								'layout_2' => esc_html__( 'Layout 2', 'dawnthemes' ),
										// 								'layout_3' => esc_html__( 'Layout 3', 'dawnthemes' ),
	// 								'layout_4' => esc_html__( 'Layout 4', 'dawnthemes' ) ),
// 							'value' => 'layout_1' ),
						array(
		'name' => 'sticky-menu',
		'type' => 'switch',
		'label' => esc_html__( 'Sticky Top menu', 'dawnthemes' ),
		'desc' => esc_html__( 'Enable or disable the sticky menu.', 'dawnthemes' ),
		'value' => '1' ),  // 1 = checked | 0 = unchecked
		array(
			'name' => 'breaking_news',
							'type' => 'switch',
			'on' => esc_html__( 'Show', 'dawnthemes' ),
			'off' => esc_html__( 'Hide', 'dawnthemes' ),
				'label' => esc_html__( 'Show Breaking News', 'dawnthemes' ),
				'desc' => esc_html__( 'Apply for home page.', 'dawnthemes' ),
										'value' => '1' ),  // 1 = checked | 0 = unchecked
				array(
				'name' => 'breadcrumb',
				'type' => 'switch',
				'on' => esc_html__( 'Show', 'dawnthemes' ),
				'off' => esc_html__( 'Hide', 'dawnthemes' ),
					'label' => esc_html__( 'Show breadcrumb', 'dawnthemes' ),
				'desc' => esc_html__( 'Enable or disable the site path under the page title.', 'dawnthemes' ),
				'value' => '1' ),  // 1 = checked | 0 = unchecked
	
			)
			),
			'footer' => array(
				'icon' => 'fa fa-list-alt',
				'title' => esc_html__( 'Footer', 'dawnthemes' ),
				'desc' => __( '<p class="description">Customize Footer.</p>', 'dawnthemes' ),
				'fields' => array(
					array(
						'name' => 'footer-copyright',
						'type' => 'textarea',
						'label' => esc_html__( 'Footer Copyright Text', 'dawnthemes' ),
						'desc' => esc_html__( 'Please enter the copyright section text.', 'dawnthemes' ),
						'note'	=> esc_html__( 'List of allowed HTML elements: <a></a> for links, <br/> for line break.', 'dawnthemes' ),
						'value' => 'Copyright 2016 - Powered by <a href="http://dawnthemes.com/">DawnThemes</a>' ),
					/*array(
					 'name' => 'footer_color_setting',
						'type' => 'heading',
						'text' => esc_html__( 'Footer Color Scheme', 'dawnthemes' ) ),
									array(
										'name' => 'footer-color',
										'type' => 'switch',
										'label' => esc_html__( 'Custom Footer Color Scheme', 'dawnthemes' ),
										'value' => '0' ),  // 1 = checked | 0 = unchecked
									array(
										'name' => 'footer-custom-color',
										'type' => 'list_color',
										'dependency' => array( 'element' => 'footer-color', 'value' => array( '1' ) ),
										'options' => array(
											'footer-widget-bg' => esc_html__( 'Footer Widget Area Background', 'dawnthemes' ),
											'footer-widget-color' => esc_html__( 'Footer Widget Area Color', 'dawnthemes' ),
											'footer-widget-link' => esc_html__( 'Footer Widget Area Link', 'dawnthemes' ),
											'footer-widget-link-hover' => esc_html__( 'Footer Widget Area Link Hover', 'dawnthemes' ),
											'footer-bg' => esc_html__( 'Footer Copyright Background', 'dawnthemes' ),
											'footer-color' => esc_html__( 'Footer Copyright Color', 'dawnthemes' ),
											'footer-link' => esc_html__( 'Footer Copyright Link', 'dawnthemes' ),
											'footer-link-hover' => esc_html__( 'Footer Copyright Link Hover', 'dawnthemes' )
										)
			)*/
			)
			),
			'blog' => array(
				'icon' => 'fa fa-pencil',
				'title' => esc_html__( 'Blog', 'dawnthemes' ),
				'desc' => __( '<p class="description">Customize Blog.</p>', 'dawnthemes' ),
				'fields' => array(
					array(
						'name' => 'list_blog_setting',
						'type' => 'heading',
						'text' => esc_html__( 'List Blog Settings', 'dawnthemes' ) ),
					array(
						'name' => 'blog-layout',
						'type' => 'image_select',
						'label' => esc_html__( 'Main Blog Layout', 'dawnthemes' ),
						'desc' => esc_html__(
							'Select main blog layout. Choose between 1, 2 or 3 column layout.',
							'dawnthemes' ),
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
						'label' => esc_html__( 'Archive Layout', 'dawnthemes' ),
						'desc' => esc_html__(
							'Select Archive layout. Choose between 1, 2 or 3 column layout.',
							'dawnthemes' ),
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
						'name' => 'blog_style',
						'type' => 'select',
						'label' => esc_html__( 'Style', 'dawnthemes' ),
						'desc' => esc_html__( 'How your blog posts will display.', 'dawnthemes' ),
						'options' => array(
							'default' => esc_html__( 'Default', 'dawnthemes' ),
							'list' => esc_html__( 'List', 'dawnthemes' ),
							'grid' => esc_html__( 'Grid', 'dawnthemes' ),
							'classic' => esc_html__( 'Classic', 'dawnthemes' ),
							'masonry' => esc_html__( 'Masonry', 'dawnthemes' ),
						),
						'value' => 'default' ),
					array(
						'name' => 'blog-columns',
						'type' => 'image_select',
						'label' => esc_html__( 'Blogs Columns', 'dawnthemes' ),
						'desc' => esc_html__( 'Select blogs columns.', 'dawnthemes' ),
						'dependency' => array( 'element' => 'blog_style', 'value' => array( 'grid', 'masonry' ) ),
						'options' => array(
							'2' => array( 'alt' => '2 Column', 'img' => DTINC_ASSETS_URL . '/images/2col.png' ),
							'3' => array( 'alt' => '3 Column', 'img' => DTINC_ASSETS_URL . '/images/3col.png' ),
							'4' => array( 'alt' => '4 Column', 'img' => DTINC_ASSETS_URL . '/images/4col.png' ) ),
						'value' => '3' ),
					array(
						'type' => 'select',
						'label' => esc_html__( 'Pagination', 'dawnthemes' ),
						'name' => 'blog-pagination',
						'options' => array(
							'wp_pagenavi' => esc_html__( 'WP PageNavi', 'dawnthemes' ),
							'loadmore' => esc_html__( 'Ajax Load More', 'dawnthemes' ),
							'infinite_scroll' => esc_html__( 'Infinite Scrolling', 'dawnthemes' ),
						),
						'value' => 'wp_pagenavi',
						'dependency' => array( 'element' => 'blog_style', 'value' => array( 'default', 'list', 'grid', 'masonry' ) ),
						'desc' => esc_html__( 'Choose pagination type.', 'dawnthemes' ) ),
					array(
						'type' => 'text',
						'label' => esc_html__( 'Load More Button Text', 'dawnthemes' ),
						'name' => 'blog-loadmore-text',
						'dependency' => array( 'element' => "blog-pagination", 'value' => array( 'loadmore' ) ),
						'value' => esc_html__( 'Load More', 'dawnthemes' ) ),
					array(
						'name' => 'blog-excerpt-length',
						'type' => 'text',
						'label' => esc_html__( 'Excerpt Length', 'dawnthemes' ),
						'dependency' => array(
							'element' => "blog_style",
							'value' => array( 'default', 'list', 'grid', 'masonry' ) ),
						'desc' => esc_html__( 'In Archive Blog. Enter the number words excerpt', 'dawnthemes' ),
						'value' => 55 ),
					array(
						'name' => 'blog_show_date',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Date Meta', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the date meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'blog_show_comment',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Comment Meta', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the comment meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'blog_show_category',
						'type' => 'switch',
						'label' => esc_html__( 'Show/Hide Category', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the category meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'blog_show_tag',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'dependency' => array(
							'element' => "blog_style",
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ),
						'label' => esc_html__( 'Tags', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. If enabled it will show tag.', 'dawnthemes' ),
						'value' => '0' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'blog_show_author',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'dependency' => array(
							'element' => "blog_style",
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ),
						'label' => esc_html__( 'Author Meta', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the author meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
	
					array(
						'name' => 'blog_show_readmore',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'dependency' => array(
							'element' => "blog_style",
							'value' => array( 'default', 'medium', 'grid', 'masonry' ) ),
						'label' => esc_html__( 'Show/Hide Readmore', 'dawnthemes' ),
						'desc' => esc_html__( 'In Archive Blog. Show/Hide the post readmore', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
	
					/*
					 * Single Blog Settings
											*/
					array(
						'name' => 'single_blog_setting',
						'type' => 'heading',
						'text' => esc_html__( 'Single Blog Settings', 'dawnthemes' ) ),
					array(
						'name' => 'single-layout',
						'type' => 'image_select',
						'label' => esc_html__( 'Single Blog Layout', 'dawnthemes' ),
						'desc' => esc_html__(
							'Select single content and sidebar alignment. Choose between 1, 2 or 3 column layout.',
							'dawnthemes' ),
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
	
					// as---
					array(
						'name' => 'single_show_date',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Date Meta', 'dawnthemes' ),
						'desc' => esc_html__( 'In Single Blog. Show/Hide the date meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'single_show_category',
						'type' => 'switch',
						'label' => esc_html__( 'Show/Hide Category', 'dawnthemes' ),
						'desc' => esc_html__( 'In Single Blog. Show/Hide the category', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'single_show_author',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Author Meta', 'dawnthemes' ),
						'desc' => esc_html__( 'In Single Blog. Show/Hide the author meta', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
					array(
						'name' => 'single_show_tag',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Show/Hide Tag', 'dawnthemes' ),
						'desc' => esc_html__( 'In Single Blog. If enabled it will show tag.', 'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
	
					// as--
					array(
						'name' => 'single_show_authorbio',
						'type' => 'switch',
						'on' => esc_html__( 'Show', 'dawnthemes' ),
						'off' => esc_html__( 'Hide', 'dawnthemes' ),
						'label' => esc_html__( 'Show Author Bio', 'dawnthemes' ),
						'desc' => esc_html__(
							'Display the author bio at the bottom of post on single post page ?',
							'dawnthemes' ),
						'value' => '1' ),  // 1 = checked | 0 = unchecked
											// 						array(
												// 							'name' => 'single_show_postnav',
											// 							'type' => 'switch',
											// 							'on' => esc_html__( 'Show', 'dawnthemes' ),
											// 							'off' => esc_html__( 'Hide', 'dawnthemes' ),
											// 							'label' => esc_html__( 'Show Next/Prev Post Link On Single Post Page', 'dawnthemes' ),
											// 							'desc' => esc_html__(
												// 								'Using this will add a link at the bottom of every post page that leads to the next/prev post.',
											// 								'dawnthemes' ),
										// 							'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'show_related_posts',
													'type' => 'switch',
													'on' => esc_html__( 'Show', 'dawnthemes' ),
													'off' => esc_html__( 'Hide', 'dawnthemes' ),
													'label' => esc_html__( 'Show Related Post On Single Post Page', 'dawnthemes' ),
													'desc' => esc_html__( 'Display related post the bottom of posts?', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'show_post_share',
													'type' => 'switch',
													'on' => esc_html__( 'Show', 'dawnthemes' ),
													'off' => esc_html__( 'Hide', 'dawnthemes' ),
													'label' => esc_html__( 'Show Sharing Button', 'dawnthemes' ),
													'desc' => esc_html__(
														'Activate this to enable social sharing buttons on single post page.',
														'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_facebook',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Facebook', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_twitter',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Twitter', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_linkedIn',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on LinkedIn', 'dawnthemes' ),
													'value' => '0' ), // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_tumblr',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Tumblr', 'dawnthemes' ),
													'value' => '0' ), // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_google',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Google+', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_pinterest',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Pinterest', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
												array(
													'name' => 'sharing_email',
													'type' => 'switch',
													'dependency' => array( 'element' => 'show_post_share', 'value' => array( '1' ) ),
													'label' => esc_html__( 'Share on Email', 'dawnthemes' ),
													'value' => '1' ),  // 1 = checked | 0 = unchecked
											)
				)
			);
			if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
				$section['woocommerce'] = array(
					'icon' => 'fa fa-shopping-cart',
					'title' => esc_html__( 'Woocommerce', 'dawnthemes' ),
					'desc' => __( '<p class="description">Customize Woocommerce.</p>', 'dawnthemes' ),
					'fields' => array(
						array(
							'name' => 'woo-cart-nav',
							'type' => 'switch',
							'on' => esc_html__( 'Show', 'dawnthemes' ),
							'off' => esc_html__( 'Hide', 'dawnthemes' ),
							'label' => esc_html__( 'Cart In header', 'dawnthemes' ),
							'desc' => esc_html__( 'This will show cat in header.', 'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-cart-mobile',
							'type' => 'switch',
							'on' => esc_html__( 'Show', 'dawnthemes' ),
							'off' => esc_html__( 'Hide', 'dawnthemes' ),
							'label' => esc_html__( 'Mobile Cart Icon', 'dawnthemes' ),
							'desc' => esc_html__(
								'This will show on mobile menu a shop icon with the number of cart items.',
								'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'list_product_setting',
							'type' => 'heading',
							'text' => esc_html__( 'List Product Settings', 'dawnthemes' ) ),
						array(
							'name' => 'woo-shop-layout',
							'type' => 'image_select',
							'label' => esc_html__( 'Shop Layout', 'dawnthemes' ),
							'desc' => esc_html__( 'Select shop layout.', 'dawnthemes' ),
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
							'name' => 'woo-category-layout',
							'type' => 'image_select',
							'label' => esc_html__( 'Product Category Layout', 'dawnthemes' ),
							'desc' => esc_html__( 'Select product category layout.', 'dawnthemes' ),
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
							'name' => 'dt_woocommerce_view_mode',
							'type' => 'buttonset',
							'label' => esc_html__( 'Default View Mode', 'dawnthemes' ),
							'desc' => esc_html__( 'Select default view mode', 'dawnthemes' ),
							'value' => 'grid',
							'options' => array(
								'grid' => esc_html__( 'Grid', 'dawnthemes' ),
								'list' => esc_html__( 'List', 'dawnthemes' ) ) ),
						array(
							'name' => 'woo-per-page',
							'type' => 'text',
							'value' => 12,
							'label' => esc_html__( 'Number of Products per Page', 'dawnthemes' ),
							'desc' => esc_html__( 'Enter the products of posts to display per page.', 'dawnthemes' ) ),
						array(
							'name' => 'single_product_setting',
							'type' => 'heading',
							'text' => esc_html__( 'Single Product Settings', 'dawnthemes' ) ),
						array(
							'name' => 'woo-product-layout',
							'type' => 'image_select',
							'label' => esc_html__( 'Single Product Layout', 'dawnthemes' ),
							'desc' => esc_html__( 'Select single product layout.', 'dawnthemes' ),
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
							'name' => 'show-woo-share',
							'type' => 'switch',
							'label' => esc_html__( 'Show Sharing Button', 'dawnthemes' ),
							'desc' => esc_html__( 'Activate this to enable social sharing buttons.', 'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-fb-share',
							'type' => 'switch',
							'on' => esc_html__( 'Show', 'dawnthemes' ),
							'off' => esc_html__( 'Hide', 'dawnthemes' ),
							'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ),
							'label' => esc_html__( 'Share on Facebook', 'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-tw-share',
							'type' => 'switch',
							'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ),
							'label' => esc_html__( 'Share on Twitter', 'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-go-share',
							'type' => 'switch',
							'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ),
							'label' => esc_html__( 'Share on Google+', 'dawnthemes' ),
							'value' => '1' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-pi-share',
							'type' => 'switch',
							'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ),
							'label' => esc_html__( 'Share on Pinterest', 'dawnthemes' ),
							'value' => '0' ),  // 1 = checked | 0 = unchecked
						array(
							'name' => 'woo-li-share',
							'type' => 'switch',
							'dependency' => array( 'element' => 'show-woo-share', 'value' => array( '1' ) ),
							'label' => esc_html__( 'Share on LinkedIn', 'dawnthemes' ),
							'value' => '1' ) ) // 1 = checked | 0 = unchecked
				);
			}
				
			$section['social'] = array(
				'icon' => 'fa fa-twitter',
				'title' => esc_html__( 'Social Profile', 'dawnthemes' ),
				'desc' => __(
					'<p class="description">Enter in your profile media locations here.<br><strong>Remember to include the "http://" in all URLs!</strong></p>',
					'dawnthemes' ),
				'fields' => array(
					array( 'name' => 'facebook-url', 'type' => 'text', 'label' => esc_html__( 'Facebook URL', 'dawnthemes' ) ),
					array( 'name' => 'twitter-url', 'type' => 'text', 'label' => esc_html__( 'Twitter URL', 'dawnthemes' ) ),
					array(
						'name' => 'google-plus-url',
						'type' => 'text',
						'label' => esc_html__( 'Google+ URL', 'dawnthemes' ) ),
					array( 'name' => 'youtube-url', 'type' => 'text', 'label' => esc_html__( 'Youtube URL', 'dawnthemes' ) ),
					array( 'name' => 'vimeo-url', 'type' => 'text', 'label' => esc_html__( 'Vimeo URL', 'dawnthemes' ) ),
					array(
						'name' => 'pinterest-url',
						'type' => 'text',
						'label' => esc_html__( 'Pinterest URL', 'dawnthemes' ) ),
					array( 'name' => 'linkedin-url', 'type' => 'text', 'label' => esc_html__( 'LinkedIn URL', 'dawnthemes' ) ),
					array( 'name' => 'rss-url', 'type' => 'text', 'label' => esc_html__( 'RSS URL', 'dawnthemes' ) ),
					array(
						'name' => 'instagram-url',
						'type' => 'text',
						'label' => esc_html__( 'Instagram URL', 'dawnthemes' ) ),
					array( 'name' => 'github-url', 'type' => 'text', 'label' => esc_html__( 'GitHub URL', 'dawnthemes' ) ),
					array( 'name' => 'behance-url', 'type' => 'text', 'label' => esc_html__( 'Behance URL', 'dawnthemes' ) ),
					array(
						'name' => 'stack-exchange-url',
						'type' => 'text',
						'label' => esc_html__( 'Stack Exchange URL', 'dawnthemes' ) ),
					array( 'name' => 'tumblr-url', 'type' => 'text', 'label' => esc_html__( 'Tumblr URL', 'dawnthemes' ) ),
					array(
						'name' => 'soundcloud-url',
						'type' => 'text',
						'label' => esc_html__( 'SoundCloud URL', 'dawnthemes' ) ),
					array( 'name' => 'dribbble-url', 'type' => 'text', 'label' => esc_html__( 'Dribbble URL', 'dawnthemes' ) ),
					array(
						'name' => 'social-target',
						'type' => 'select',
						'label' => esc_html__( 'Target', 'dawnthemes' ),
						'desc' => esc_html__( 'The target attribute specifies where to open the linked social.', 'dawnthemes' ),
						'options' => array(
							'_blank' => esc_html__( 'Blank', 'dawnthemes' ),
							'_self' => esc_html__( 'Layout 2', 'dawnthemes' ),
						),
						'value' => '_blank' ),
				)
			);
			$section['import_export'] = array(
				'icon' => 'fa fa-refresh',
				'title' => esc_html__( 'Import and Export', 'dawnthemes' ),
				'fields' => array(
					array(
						'name' => 'import',
						'type' => 'import',
						'field-label' => esc_html__(
							'Input your backup file below and hit Import to restore your sites options from a backup.',
							'dawnthemes' ) ),
					array(
						'name' => 'export',
						'type' => 'export',
						'field-label' => esc_html__(
							'Here you can download your current option settings.You can use it to restore your settings on this site (or any other site).',
							'dawnthemes' ) ) ) );
			$section['custom_code'] = array(
				'icon' => 'fa fa-code',
				'title' => esc_html__( 'Custom Code', 'dawnthemes' ),
				'fields' => array(
					array(
						'name' => 'custom-css',
						'type' => 'ace_editor',
						'label' => esc_html__( 'Custom Style', 'dawnthemes' ),
						'desc' => esc_html__( 'Place you custom style here', 'dawnthemes' ) ) ) )
						// array(
			// 'name' => 'custom-js',
			// 'type' => 'ace_editor',
			// 'label' => esc_html__('Custom Javascript','dawnthemes'),
			// 'desc'=>esc_html__('Place you custom javascript here','dawnthemes'),
			// ),
			;
	
	if( function_exists('dawnthemes_register_theme_options') ){
		dawnthemes_register_theme_options( $section );
	}
}