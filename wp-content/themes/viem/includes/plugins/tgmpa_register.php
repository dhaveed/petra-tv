<?php
if (! function_exists ( 'theme_register_plugin' )) :
	function theme_register_plugin() {
		$plugins = array (
				array (
					'name' => esc_html__('DawmThemes Core','viem'),
					'slug' => 'dawnthemes',
					'source' => get_template_directory() . '/includes/plugins/dawnthemes.zip',
					'required' =>true,
					'version' => '2.0.3.3',
					'force_activation' => false,
					'force_deactivation' => false,
					'external_url' => ''
				),
				array (
					'name' => esc_html__('Viem Ultimate','viem'),
					'slug' => 'viem-ultimate',
					'source' => get_template_directory() . '/includes/plugins/viem-ultimate.zip',
					'required' =>true,
					'version' => '1.0.7',
					'force_activation' => false,
					'force_deactivation' => false,
					'external_url' => ''
				),
				array (
					'name' => esc_html__('DawnThemes Instagram','viem'),
					'slug' => 'dawnthemes-instagram',
					'source' => get_template_directory() . '/includes/plugins/dawnthemes-instagram.zip',
					'required' => false,
					'version' => '1.0.2',
					'force_activation' => false,
					'force_deactivation' => false,
					'external_url' => ''
				),
				array (
					'name' => esc_html__('WPBakery Page Builder','viem'),
					'slug' => 'js_composer',
					'source' => get_template_directory() . '/includes/plugins/js_composer.zip',
					'required' =>true,
					'version' => '6.0.3',
					'force_activation' => false,
					'force_deactivation' => false,
					'external_url' => ''
				),
				array(
					'name' => esc_html__('Revolution Slider','viem'),
					'slug' => 'revslider',
					'source' => get_template_directory() . '/includes/plugins/revslider.zip',
					'required' =>false,
					'version' => '5.4.8.3',
					'force_activation' => false,
					'force_deactivation' => false,
					'external_url' => ''
				),
				array (
					'name' => esc_html__('Contact Form 7','viem'),
					'slug' => 'contact-form-7',
					'required' => false 
				),
				array (
					'name' => esc_html__('WTI Like Post','viem'),
					'slug' => 'wti-like-post',
					'required' => false
				),
				array (
					'name' => esc_html__('AccessPress Social Counter','viem'),
					'slug' => 'accesspress-social-counter',
					'required' => false
				),
		);
		
		$config = array (
				'domain' => 'viem', // Text domain - likely want to be the same as your theme.
				'default_path' => '', // Default absolute path to pre-packaged plugins
				'menu' => 'install-required-plugins', // Menu slug
				'has_notices' => true, // Show admin notices or not
				'is_automatic' => false, // Automatically activate plugins after installation or not
				'message' => '', // Message to output right before the plugins table
				'strings' => array (
						'page_title' => esc_html__ ( 'Install Required Plugins', 'viem' ),
						'menu_title' => esc_html__ ( 'Install Plugins', 'viem' ),
						'installing' => esc_html__ ( 'Installing Plugin: %s', 'viem' ), // %1$s = plugin name
						'oops' => esc_html__ ( 'Something went wrong with the plugin API.', 'viem' ),
						'notice_can_install_required' => _n_noop ( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'viem' ), // %1$s = plugin name(s)
						'notice_can_install_recommended' => _n_noop ( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'viem' ), // %1$s = plugin name(s)
						'notice_cannot_install' => _n_noop ( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'viem' ), // %1$s = plugin name(s)
						'notice_can_activate_required' => _n_noop ( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'viem' ), // %1$s = plugin name(s)
						'notice_can_activate_recommended' => _n_noop ( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'viem' ), // %1$s = plugin name(s)
						'notice_cannot_activate' => _n_noop ( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'viem' ), // %1$s = plugin name(s)
						'notice_ask_to_update' => _n_noop ( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'viem' ), // %1$s = plugin name(s)
						'notice_cannot_update' => _n_noop ( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'viem' ), // %1$s = plugin name(s)
						'install_link' => _n_noop ( 'Begin installing plugin', 'Begin installing plugins', 'viem' ),
						'activate_link' => _n_noop ( 'Activate installed plugin', 'Activate installed plugins','viem' ),
						'return' => esc_html__ ( 'Return to Required Plugins Installer', 'viem' ),
						'plugin_activated' => esc_html__ ( 'Plugin activated successfully.', 'viem' ),
						'complete' => esc_html__ ( 'All plugins installed and activated successfully. %s', 'viem' )  // %1$s = dashboard link
								) 
		);
		
		tgmpa ( $plugins, $config );
	}
	add_action ( 'tgmpa_register', 'theme_register_plugin' );

endif;