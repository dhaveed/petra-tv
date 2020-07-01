<?php

function dawnthemes_get_theme_option_name() {
	$lang = '';
	$theme_name = 'dt_theme_' . basename( get_template_directory() );
	$theme_name = apply_filters( 'dawnthemes_get_theme_option_name', $theme_name );
	return $theme_name;
}

function dawnthemes_get_theme_option( $option, $default = null ) {
	global $dawnthemes_theme_options;
	if ( empty( $option ) )
		return $default;
	
	$_option_name = dawnthemes_get_theme_option_name();
	
	if ( empty( $dawnthemes_theme_options ) ) {
		$dawnthemes_theme_options = get_option( $_option_name );
	}
	
	if ( is_page() || ( defined( 'WOOCOMMERCE_VERSION' ) && is_woocommerce() ) ) {
		if ( $option == 'header-style' ) {
			$page_value = dawnthemes_get_post_meta( 'header_style' );
			if ( $page_value !== null && $page_value !== array() && $page_value !== false && $page_value != '-1' ) {
				return apply_filters( 'dawnthemes_get_theme_option', $page_value, $option );
			}
		}
		if ( $option == 'show-topbar' ) {
			$page_value = dawnthemes_get_post_meta( 'show_topbar' );
			if ( $page_value !== null && $page_value !== array() && $page_value !== false && $page_value != '-1' ) {
				return apply_filters( 'dawnthemes_get_theme_option', $page_value, $option );
			}
		}
		if ( $option == 'menu-transparent' ) {
			$page_value = dawnthemes_get_post_meta( 'menu_transparent' );
			if ( $page_value !== null && $page_value !== array() && $page_value !== false && $page_value != '-1' ) {
				return apply_filters( 'dawnthemes_get_theme_option', $page_value, $option );
			}
		}
		if ( $option == 'footer-area' ) {
			$page_value = dawnthemes_get_post_meta( 'footer_area' );
			if ( $page_value !== null && $page_value !== array() && $page_value !== false && $page_value != '-1' ) {
				return apply_filters( 'dawnthemes_get_theme_option', $page_value, $option );
			}
		}
		if ( $option == 'footer-menu' ) {
			$page_value = dawnthemes_get_post_meta( 'footer_menu' );
			if ( $page_value !== null && $page_value !== array() && $page_value !== false && $page_value != '-1' ) {
				return apply_filters( 'dawnthemes_get_theme_option', $page_value, $option );
			}
		}
	}
	if ( isset( $dawnthemes_theme_options[$option] ) && $dawnthemes_theme_options[$option] !== '' &&
		 $dawnthemes_theme_options[$option] !== null && $dawnthemes_theme_options[$option] !== array() &&
		 $dawnthemes_theme_options[$option] !== false ) {
		$value = $dawnthemes_theme_options[$option];
		return apply_filters( 'dawnthemes_get_theme_option', $value, $option );
	} else {
		return $default;
	}
}

function dawnthemes_get_post_meta( $meta = '', $post_id = '', $default = null ) {
	if ( is_home() && ! in_the_loop() )
		$post_id = absint( get_option( 'page_for_posts' ) );
	
	$post_id = empty( $post_id ) ? get_the_ID() : $post_id;
	
	if ( dawnthemes_is_woocommerce_activated() && ! in_the_loop() ) {
		if ( is_shop() || is_search() && is_post_type_archive( 'product' ) )
			$post_id = wc_get_page_id( 'shop' );
		elseif ( is_cart() )
			$post_id = wc_get_page_id( 'cart' );
		elseif ( is_checkout() )
			$post_id = wc_get_page_id( 'checkout' );
		elseif ( is_account_page() )
			$post_id = wc_get_page_id( 'myaccount' );
		elseif ( is_order_received_page() )
			$post_id = wc_get_page_id( 'checkout' );
		elseif ( is_add_payment_method_page() )
			$post_id = wc_get_page_id( 'myaccount' );
	}
	if ( is_search() && ! is_post_type_archive( 'product' ) ) {
		$post_id = 0;
	}
	if ( empty( $meta ) )
		return false;
	$value = get_post_meta( $post_id, '_dt_' . $meta, true );
	if ( $value !== '' && $value !== null && $value !== array() && $value !== false )
		return apply_filters( 'dawnthemes_get_post_meta', $value, $meta, $post_id );
	if ( is_numeric( $default ) )
		$default = floatval( $default );
	return apply_filters( 'dawnthemes_get_post_meta', $default, $meta, $post_id );
	;
}

function dawnthemes_get_options_select_post( $post_type = 'post' ) {
	$options = array();
	$args = array( 'post_type' => $post_type, 'post_status' => 'publish', 'posts_per_page' => - 1 );
	$results = get_posts( $args );
	$options[] = '';
	foreach ( $results as $result ) {
		
		if ( ! empty( $result->post_title ) ) {
			
			$options[$result->ID] = $result->post_title;
		}
	}
	return $options;
}

function dawnthemes_font_awesome_options( $none_select = true ) {
	$font_awesome = array( 
		esc_html__( 'None', 'dtwozine' ) => 'none', 
		'fa fa-adjust' => '\f042', 
		'fa fa-adn' => '\f170', 
		'fa fa-align-center' => '\f037', 
		'fa fa-align-justify' => '\f039', 
		'fa fa-align-left' => '\f036', 
		'fa fa-align-right' => '\f038', 
		'fa fa-ambulance' => '\f0f9', 
		'fa fa-anchor' => '\f13d', 
		'fa fa-android' => '\f17b', 
		'fa fa-angellist' => '\f209', 
		'fa fa-angle-double-down' => '\f103', 
		'fa fa-angle-double-left' => '\f100', 
		'fa fa-angle-double-right' => '\f101', 
		'fa fa-angle-double-up' => '\f102', 
		'fa fa-angle-down' => '\f107', 
		'fa fa-angle-left' => '\f104', 
		'fa fa-angle-right' => '\f105', 
		'fa fa-angle-up' => '\f106', 
		'fa fa-apple' => '\f179', 
		'fa fa-archive' => '\f187', 
		'fa fa-area-chart' => '\f1fe', 
		'fa fa-arrow-circle-down' => '\f0ab', 
		'fa fa-arrow-circle-left' => '\f0a8', 
		'fa fa-arrow-circle-o-down' => '\f01a', 
		'fa fa-arrow-circle-o-left' => '\f190', 
		'fa fa-arrow-circle-o-right' => '\f18e', 
		'fa fa-arrow-circle-o-up' => '\f01b', 
		'fa fa-arrow-circle-right' => '\f0a9', 
		'fa fa-arrow-circle-up' => '\f0aa', 
		'fa fa-arrow-down' => '\f063', 
		'fa fa-arrow-left' => '\f060', 
		'fa fa-arrow-right' => '\f061', 
		'fa fa-arrow-up' => '\f062', 
		'fa fa-arrows' => '\f047', 
		'fa fa-arrows-alt' => '\f0b2', 
		'fa fa-arrows-h' => '\f07e', 
		'fa fa-arrows-v' => '\f07d', 
		'fa fa-asterisk' => '\f069', 
		'fa fa-at' => '\f1fa', 
		'fa fa-backward' => '\f04a', 
		'fa fa-ban' => '\f05e', 
		'fa fa-bar-chart' => '\f080', 
		'fa fa-barcode' => '\f02a', 
		'fa fa-bars' => '\f0c9', 
		'fa fa-beer' => '\f0fc', 
		'fa fa-behance' => '\f1b4', 
		'fa fa-behance-square' => '\f1b5', 
		'fa fa-bell' => '\f0f3', 
		'fa fa-bell-o' => '\f0a2', 
		'fa fa-bell-slash' => '\f1f6', 
		'fa fa-bell-slash-o' => '\f1f7', 
		'fa fa-bicycle' => '\f206', 
		'fa fa-binoculars' => '\f1e5', 
		'fa fa-birthday-cake' => '\f1fd', 
		'fa fa-bitbucket' => '\f171', 
		'fa fa-bitbucket-square' => '\f172', 
		'fa fa-bold' => '\f032', 
		'fa fa-bolt' => '\f0e7', 
		'fa fa-bomb' => '\f1e2', 
		'fa fa-book' => '\f02d', 
		'fa fa-bookmark' => '\f02e', 
		'fa fa-bookmark-o' => '\f097', 
		'fa fa-briefcase' => '\f0b1', 
		'fa fa-btc' => '\f15a', 
		'fa fa-bug' => '\f188', 
		'fa fa-building' => '\f1ad', 
		'fa fa-building-o' => '\f0f7', 
		'fa fa-bullhorn' => '\f0a1', 
		'fa fa-bullseye' => '\f140', 
		'fa fa-bus' => '\f207', 
		'fa fa-calculator' => '\f1ec', 
		'fa fa-calendar' => '\f073', 
		'fa fa-calendar-o' => '\f133', 
		'fa fa-camera' => '\f030', 
		'fa fa-camera-retro' => '\f083', 
		'fa fa-car' => '\f1b9', 
		'fa fa-caret-down' => '\f0d7', 
		'fa fa-caret-left' => '\f0d9', 
		'fa fa-caret-right' => '\f0da', 
		'fa fa-caret-square-o-down' => '\f150', 
		'fa fa-caret-square-o-left' => '\f191', 
		'fa fa-caret-square-o-right' => '\f152', 
		'fa fa-caret-square-o-up' => '\f151', 
		'fa fa-caret-up' => '\f0d8', 
		'fa fa-cc' => '\f20a', 
		'fa fa-cc-amex' => '\f1f3', 
		'fa fa-cc-discover' => '\f1f2', 
		'fa fa-cc-mastercard' => '\f1f1', 
		'fa fa-cc-paypal' => '\f1f4', 
		'fa fa-cc-stripe' => '\f1f5', 
		'fa fa-cc-visa' => '\f1f0', 
		'fa fa-certificate' => '\f0a3', 
		'fa fa-chain-broken' => '\f127', 
		'fa fa-check' => '\f00c', 
		'fa fa-check-circle' => '\f058', 
		'fa fa-check-circle-o' => '\f05d', 
		'fa fa-check-square' => '\f14a', 
		'fa fa-check-square-o' => '\f046', 
		'fa fa-chevron-circle-down' => '\f13a', 
		'fa fa-chevron-circle-left' => '\f137', 
		'fa fa-chevron-circle-right' => '\f138', 
		'fa fa-chevron-circle-up' => '\f139', 
		'fa fa-chevron-down' => '\f078', 
		'fa fa-chevron-left' => '\f053', 
		'fa fa-chevron-right' => '\f054', 
		'fa fa-chevron-up' => '\f077', 
		'fa fa-child' => '\f1ae', 
		'fa fa-circle' => '\f111', 
		'fa fa-circle-o' => '\f10c', 
		'fa fa-circle-o-notch' => '\f1ce', 
		'fa fa-circle-thin' => '\f1db', 
		'fa fa-clipboard' => '\f0ea', 
		'fa fa-clock-o' => '\f017', 
		'fa fa-cloud' => '\f0c2', 
		'fa fa-cloud-download' => '\f0ed', 
		'fa fa-cloud-upload' => '\f0ee', 
		'fa fa-code' => '\f121', 
		'fa fa-code-fork' => '\f126', 
		'fa fa-codepen' => '\f1cb', 
		'fa fa-coffee' => '\f0f4', 
		'fa fa-cog' => '\f013', 
		'fa fa-cogs' => '\f085', 
		'fa fa-columns' => '\f0db', 
		'fa fa-comment' => '\f075', 
		'fa fa-comment-o' => '\f0e5', 
		'fa fa-comments' => '\f086', 
		'fa fa-comments-o' => '\f0e6', 
		'fa fa-compass' => '\f14e', 
		'fa fa-compress' => '\f066', 
		'fa fa-copyright' => '\f1f9', 
		'fa fa-credit-card' => '\f09d', 
		'fa fa-crop' => '\f125', 
		'fa fa-crosshairs' => '\f05b', 
		'fa fa-css3' => '\f13c', 
		'fa fa-cube' => '\f1b2', 
		'fa fa-cubes' => '\f1b3', 
		'fa fa-cutlery' => '\f0f5', 
		'fa fa-database' => '\f1c0', 
		'fa fa-delicious' => '\f1a5', 
		'fa fa-desktop' => '\f108', 
		'fa fa-deviantart' => '\f1bd', 
		'fa fa-digg' => '\f1a6', 
		'fa fa-dot-circle-o' => '\f192', 
		'fa fa-download' => '\f019', 
		'fa fa-dribbble' => '\f17d', 
		'fa fa-dropbox' => '\f16b', 
		'fa fa-drupal' => '\f1a9', 
		'fa fa-eject' => '\f052', 
		'fa fa-ellipsis-h' => '\f141', 
		'fa fa-ellipsis-v' => '\f142', 
		'fa fa-empire' => '\f1d1', 
		'fa fa-envelope' => '\f0e0', 
		'fa fa-envelope-o' => '\f003', 
		'fa fa-envelope-square' => '\f199', 
		'fa fa-eraser' => '\f12d', 
		'fa fa-eur' => '\f153', 
		'fa fa-exchange' => '\f0ec', 
		'fa fa-exclamation' => '\f12a', 
		'fa fa-exclamation-circle' => '\f06a', 
		'fa fa-exclamation-triangle' => '\f071', 
		'fa fa-expand' => '\f065', 
		'fa fa-external-link' => '\f08e', 
		'fa fa-external-link-square' => '\f14c', 
		'fa fa-eye' => '\f06e', 
		'fa fa-eye-slash' => '\f070', 
		'fa fa-eyedropper' => '\f1fb', 
		'fa fa-facebook' => '\f09a', 
		'fa fa-facebook-square' => '\f082', 
		'fa fa-fast-backward' => '\f049', 
		'fa fa-fast-forward' => '\f050', 
		'fa fa-fax' => '\f1ac', 
		'fa fa-female' => '\f182', 
		'fa fa-fighter-jet' => '\f0fb', 
		'fa fa-file' => '\f15b', 
		'fa fa-file-archive-o' => '\f1c6', 
		'fa fa-file-audio-o' => '\f1c7', 
		'fa fa-file-code-o' => '\f1c9', 
		'fa fa-file-excel-o' => '\f1c3', 
		'fa fa-file-image-o' => '\f1c5', 
		'fa fa-file-o' => '\f016', 
		'fa fa-file-pdf-o' => '\f1c1', 
		'fa fa-file-powerpoint-o' => '\f1c4', 
		'fa fa-file-text' => '\f15c', 
		'fa fa-file-text-o' => '\f0f6', 
		'fa fa-file-video-o' => '\f1c8', 
		'fa fa-file-word-o' => '\f1c2', 
		'fa fa-files-o' => '\f0c5', 
		'fa fa-film' => '\f008', 
		'fa fa-filter' => '\f0b0', 
		'fa fa-fire' => '\f06d', 
		'fa fa-fire-extinguisher' => '\f134', 
		'fa fa-flag' => '\f024', 
		'fa fa-flag-checkered' => '\f11e', 
		'fa fa-flag-o' => '\f11d', 
		'fa fa-flask' => '\f0c3', 
		'fa fa-flickr' => '\f16e', 
		'fa fa-floppy-o' => '\f0c7', 
		'fa fa-folder' => '\f07b', 
		'fa fa-folder-o' => '\f114', 
		'fa fa-folder-open' => '\f07c', 
		'fa fa-folder-open-o' => '\f115', 
		'fa fa-font' => '\f031', 
		'fa fa-forward' => '\f04e', 
		'fa fa-foursquare' => '\f180', 
		'fa fa-frown-o' => '\f119', 
		'fa fa-futbol-o' => '\f1e3', 
		'fa fa-gamepad' => '\f11b', 
		'fa fa-gavel' => '\f0e3', 
		'fa fa-gbp' => '\f154', 
		'fa fa-gift' => '\f06b', 
		'fa fa-git' => '\f1d3', 
		'fa fa-git-square' => '\f1d2', 
		'fa fa-github' => '\f09b', 
		'fa fa-github-alt' => '\f113', 
		'fa fa-github-square' => '\f092', 
		'fa fa-gittip' => '\f184', 
		'fa fa-glass' => '\f000', 
		'fa fa-globe' => '\f0ac', 
		'fa fa-google' => '\f1a0', 
		'fa fa-google-plus' => '\f0d5', 
		'fa fa-google-plus-square' => '\f0d4', 
		'fa fa-google-wallet' => '\f1ee', 
		'fa fa-graduation-cap' => '\f19d', 
		'fa fa-h-square' => '\f0fd', 
		'fa fa-hacker-news' => '\f1d4', 
		'fa fa-hand-o-down' => '\f0a7', 
		'fa fa-hand-o-left' => '\f0a5', 
		'fa fa-hand-o-right' => '\f0a4', 
		'fa fa-hand-o-up' => '\f0a6', 
		'fa fa-hdd-o' => '\f0a0', 
		'fa fa-header' => '\f1dc', 
		'fa fa-headphones' => '\f025', 
		'fa fa-heart' => '\f004', 
		'fa fa-heart-o' => '\f08a', 
		'fa fa-history' => '\f1da', 
		'fa fa-home' => '\f015', 
		'fa fa-hospital-o' => '\f0f8', 
		'fa fa-html5' => '\f13b', 
		'fa fa-ils' => '\f20b', 
		'fa fa-inbox' => '\f01c', 
		'fa fa-indent' => '\f03c', 
		'fa fa-info' => '\f129', 
		'fa fa-info-circle' => '\f05a', 
		'fa fa-inr' => '\f156', 
		'fa fa-instagram' => '\f16d', 
		'fa fa-ioxhost' => '\f208', 
		'fa fa-italic' => '\f033', 
		'fa fa-joomla' => '\f1aa', 
		'fa fa-jpy' => '\f157', 
		'fa fa-jsfiddle' => '\f1cc', 
		'fa fa-key' => '\f084', 
		'fa fa-keyboard-o' => '\f11c', 
		'fa fa-krw' => '\f159', 
		'fa fa-language' => '\f1ab', 
		'fa fa-laptop' => '\f109', 
		'fa fa-lastfm' => '\f202', 
		'fa fa-lastfm-square' => '\f203', 
		'fa fa-leaf' => '\f06c', 
		'fa fa-lemon-o' => '\f094', 
		'fa fa-level-down' => '\f149', 
		'fa fa-level-up' => '\f148', 
		'fa fa-life-ring' => '\f1cd', 
		'fa fa-lightbulb-o' => '\f0eb', 
		'fa fa-line-chart' => '\f201', 
		'fa fa-link' => '\f0c1', 
		'fa fa-linkedin' => '\f0e1', 
		'fa fa-linkedin-square' => '\f08c', 
		'fa fa-linux' => '\f17c', 
		'fa fa-list' => '\f03a', 
		'fa fa-list-alt' => '\f022', 
		'fa fa-list-ol' => '\f0cb', 
		'fa fa-list-ul' => '\f0ca', 
		'fa fa-location-arrow' => '\f124', 
		'fa fa-lock' => '\f023', 
		'fa fa-long-arrow-down' => '\f175', 
		'fa fa-long-arrow-left' => '\f177', 
		'fa fa-long-arrow-right' => '\f178', 
		'fa fa-long-arrow-up' => '\f176', 
		'fa fa-magic' => '\f0d0', 
		'fa fa-magnet' => '\f076', 
		'fa fa-male' => '\f183', 
		'fa fa-map-marker' => '\f041', 
		'fa fa-maxcdn' => '\f136', 
		'fa fa-meanpath' => '\f20c', 
		'fa fa-medkit' => '\f0fa', 
		'fa fa-meh-o' => '\f11a', 
		'fa fa-microphone' => '\f130', 
		'fa fa-microphone-slash' => '\f131', 
		'fa fa-minus' => '\f068', 
		'fa fa-minus-circle' => '\f056', 
		'fa fa-minus-square' => '\f146', 
		'fa fa-minus-square-o' => '\f147', 
		'fa fa-mobile' => '\f10b', 
		'fa fa-money' => '\f0d6', 
		'fa fa-moon-o' => '\f186', 
		'fa fa-music' => '\f001', 
		'fa fa-newspaper-o' => '\f1ea', 
		'fa fa-openid' => '\f19b', 
		'fa fa-outdent' => '\f03b', 
		'fa fa-pagelines' => '\f18c', 
		'fa fa-paint-brush' => '\f1fc', 
		'fa fa-paper-plane' => '\f1d8', 
		'fa fa-paper-plane-o' => '\f1d9', 
		'fa fa-paperclip' => '\f0c6', 
		'fa fa-paragraph' => '\f1dd', 
		'fa fa-pause' => '\f04c', 
		'fa fa-paw' => '\f1b0', 
		'fa fa-paypal' => '\f1ed', 
		'fa fa-pencil' => '\f040', 
		'fa fa-pencil-square' => '\f14b', 
		'fa fa-pencil-square-o' => '\f044', 
		'fa fa-phone' => '\f095', 
		'fa fa-phone-square' => '\f098', 
		'fa fa-picture-o' => '\f03e', 
		'fa fa-pie-chart' => '\f200', 
		'fa fa-pied-piper' => '\f1a7', 
		'fa fa-pied-piper-alt' => '\f1a8', 
		'fa fa-pinterest' => '\f0d2', 
		'fa fa-pinterest-square' => '\f0d3', 
		'fa fa-plane' => '\f072', 
		'fa fa-play' => '\f04b', 
		'fa fa-play-circle' => '\f144', 
		'fa fa-play-circle-o' => '\f01d', 
		'fa fa-plug' => '\f1e6', 
		'fa fa-plus' => '\f067', 
		'fa fa-plus-circle' => '\f055', 
		'fa fa-plus-square' => '\f0fe', 
		'fa fa-plus-square-o' => '\f196', 
		'fa fa-power-off' => '\f011', 
		'fa fa-print' => '\f02f', 
		'fa fa-puzzle-piece' => '\f12e', 
		'fa fa-qq' => '\f1d6', 
		'fa fa-qrcode' => '\f029', 
		'fa fa-question' => '\f128', 
		'fa fa-question-circle' => '\f059', 
		'fa fa-quote-left' => '\f10d', 
		'fa fa-quote-right' => '\f10e', 
		'fa fa-random' => '\f074', 
		'fa fa-rebel' => '\f1d0', 
		'fa fa-recycle' => '\f1b8', 
		'fa fa-reddit' => '\f1a1', 
		'fa fa-reddit-square' => '\f1a2', 
		'fa fa-refresh' => '\f021', 
		'fa fa-renren' => '\f18b', 
		'fa fa-repeat' => '\f01e', 
		'fa fa-reply' => '\f112', 
		'fa fa-reply-all' => '\f122', 
		'fa fa-retweet' => '\f079', 
		'fa fa-road' => '\f018', 
		'fa fa-rocket' => '\f135', 
		'fa fa-rss' => '\f09e', 
		'fa fa-rss-square' => '\f143', 
		'fa fa-rub' => '\f158', 
		'fa fa-scissors' => '\f0c4', 
		'fa fa-search' => '\f002', 
		'fa fa-search-minus' => '\f010', 
		'fa fa-search-plus' => '\f00e', 
		'fa fa-share' => '\f064', 
		'fa fa-share-alt' => '\f1e0', 
		'fa fa-share-alt-square' => '\f1e1', 
		'fa fa-share-square' => '\f14d', 
		'fa fa-share-square-o' => '\f045', 
		'fa fa-shield' => '\f132', 
		'fa fa-shopping-cart' => '\f07a', 
		'fa fa-sign-in' => '\f090', 
		'fa fa-sign-out' => '\f08b', 
		'fa fa-signal' => '\f012', 
		'fa fa-sitemap' => '\f0e8', 
		'fa fa-skype' => '\f17e', 
		'fa fa-slack' => '\f198', 
		'fa fa-sliders' => '\f1de', 
		'fa fa-slideshare' => '\f1e7', 
		'fa fa-smile-o' => '\f118', 
		'fa fa-sort' => '\f0dc', 
		'fa fa-sort-alpha-asc' => '\f15d', 
		'fa fa-sort-alpha-desc' => '\f15e', 
		'fa fa-sort-amount-asc' => '\f160', 
		'fa fa-sort-amount-desc' => '\f161', 
		'fa fa-sort-asc' => '\f0de', 
		'fa fa-sort-desc' => '\f0dd', 
		'fa fa-sort-numeric-asc' => '\f162', 
		'fa fa-sort-numeric-desc' => '\f163', 
		'fa fa-soundcloud' => '\f1be', 
		'fa fa-space-shuttle' => '\f197', 
		'fa fa-spinner' => '\f110', 
		'fa fa-spoon' => '\f1b1', 
		'fa fa-spotify' => '\f1bc', 
		'fa fa-square' => '\f0c8', 
		'fa fa-square-o' => '\f096', 
		'fa fa-stack-exchange' => '\f18d', 
		'fa fa-stack-overflow' => '\f16c', 
		'fa fa-star' => '\f005', 
		'fa fa-star-half' => '\f089', 
		'fa fa-star-half-o' => '\f123', 
		'fa fa-star-o' => '\f006', 
		'fa fa-steam' => '\f1b6', 
		'fa fa-steam-square' => '\f1b7', 
		'fa fa-step-backward' => '\f048', 
		'fa fa-step-forward' => '\f051', 
		'fa fa-stethoscope' => '\f0f1', 
		'fa fa-stop' => '\f04d', 
		'fa fa-strikethrough' => '\f0cc', 
		'fa fa-stumbleupon' => '\f1a4', 
		'fa fa-stumbleupon-circle' => '\f1a3', 
		'fa fa-subscript' => '\f12c', 
		'fa fa-suitcase' => '\f0f2', 
		'fa fa-sun-o' => '\f185', 
		'fa fa-superscript' => '\f12b', 
		'fa fa-table' => '\f0ce', 
		'fa fa-tablet' => '\f10a', 
		'fa fa-tachometer' => '\f0e4', 
		'fa fa-tag' => '\f02b', 
		'fa fa-tags' => '\f02c', 
		'fa fa-tasks' => '\f0ae', 
		'fa fa-taxi' => '\f1ba', 
		'fa fa-tencent-weibo' => '\f1d5', 
		'fa fa-terminal' => '\f120', 
		'fa fa-text-height' => '\f034', 
		'fa fa-text-width' => '\f035', 
		'fa fa-th' => '\f00a', 
		'fa fa-th-large' => '\f009', 
		'fa fa-th-list' => '\f00b', 
		'fa fa-thumb-tack' => '\f08d', 
		'fa fa-thumbs-down' => '\f165', 
		'fa fa-thumbs-o-down' => '\f088', 
		'fa fa-thumbs-o-up' => '\f087', 
		'fa fa-thumbs-up' => '\f164', 
		'fa fa-ticket' => '\f145', 
		'fa fa-times' => '\f00d', 
		'fa fa-times-circle' => '\f057', 
		'fa fa-times-circle-o' => '\f05c', 
		'fa fa-tint' => '\f043', 
		'fa fa-toggle-off' => '\f204', 
		'fa fa-toggle-on' => '\f205', 
		'fa fa-trash' => '\f1f8', 
		'fa fa-trash-o' => '\f014', 
		'fa fa-tree' => '\f1bb', 
		'fa fa-trello' => '\f181', 
		'fa fa-trophy' => '\f091', 
		'fa fa-truck' => '\f0d1', 
		'fa fa-try' => '\f195', 
		'fa fa-tty' => '\f1e4', 
		'fa fa-tumblr' => '\f173', 
		'fa fa-tumblr-square' => '\f174', 
		'fa fa-twitch' => '\f1e8', 
		'fa fa-twitter' => '\f099', 
		'fa fa-twitter-square' => '\f081', 
		'fa fa-umbrella' => '\f0e9', 
		'fa fa-underline' => '\f0cd', 
		'fa fa-undo' => '\f0e2', 
		'fa fa-university' => '\f19c', 
		'fa fa-unlock' => '\f09c', 
		'fa fa-unlock-alt' => '\f13e', 
		'fa fa-upload' => '\f093', 
		'fa fa-usd' => '\f155', 
		'fa fa-user' => '\f007', 
		'fa fa-user-md' => '\f0f0', 
		'fa fa-users' => '\f0c0', 
		'fa fa-video-camera' => '\f03d', 
		'fa fa-vimeo-square' => '\f194', 
		'fa fa-vine' => '\f1ca', 
		'fa fa-vk' => '\f189', 
		'fa fa-volume-down' => '\f027', 
		'fa fa-volume-off' => '\f026', 
		'fa fa-volume-up' => '\f028', 
		'fa fa-weibo' => '\f18a', 
		'fa fa-weixin' => '\f1d7', 
		'fa fa-wheelchair' => '\f193', 
		'fa fa-wifi' => '\f1eb', 
		'fa fa-windows' => '\f17a', 
		'fa fa-wordpress' => '\f19a', 
		'fa fa-wrench' => '\f0ad', 
		'fa fa-xing' => '\f168', 
		'fa fa-xing-square' => '\f169', 
		'fa fa-yahoo' => '\f19e', 
		'fa fa-yelp' => '\f1e9', 
		'fa fa-youtube' => '\f167', 
		'fa fa-youtube-play' => '\f16a', 
		'fa fa-youtube-square' => '\f166', 
		'elegant_arrow_up' => '&#x21;', 
		'elegant_arrow_down' => '&#x22;', 
		'elegant_arrow_left' => '&#x23;', 
		'elegant_arrow_right' => '&#x24;', 
		'elegant_arrow_left-up' => '&#x25;', 
		'elegant_arrow_right-up' => '&#x26;', 
		'elegant_arrow_right-down' => '&#x27;', 
		'elegant_arrow_left-down' => '&#x28;', 
		'elegant_arrow-up-down' => '&#x29;', 
		'elegant_arrow_up-down_alt' => '&#x2a;', 
		'elegant_arrow_left-right_alt' => '&#x2b;', 
		'elegant_arrow_left-right' => '&#x2c;', 
		'elegant_arrow_expand_alt2' => '&#x2d;', 
		'elegant_arrow_expand_alt' => '&#x2e;', 
		'elegant_arrow_condense' => '&#x2f;', 
		'elegant_arrow_expand' => '&#x30;', 
		'elegant_arrow_move' => '&#x31;', 
		'elegant_arrow_carrot-up' => '&#x32;', 
		'elegant_arrow_carrot-down' => '&#x33;', 
		'elegant_arrow_carrot-left' => '&#x34;', 
		'elegant_arrow_carrot-right' => '&#x35;', 
		'elegant_arrow_carrot-2up' => '&#x36;', 
		'elegant_arrow_carrot-2down' => '&#x37;', 
		'elegant_arrow_carrot-2left' => '&#x38;', 
		'elegant_arrow_carrot-2right' => '&#x39;', 
		'elegant_arrow_carrot-up_alt2' => '&#x3a;', 
		'elegant_arrow_carrot-down_alt2' => '&#x3b;', 
		'elegant_arrow_carrot-left_alt2' => '&#x3c;', 
		'elegant_arrow_carrot-right_alt2' => '&#x3d;', 
		'elegant_arrow_carrot-2up_alt2' => '&#x3e;', 
		'elegant_arrow_carrot-2down_alt2' => '&#x3f;', 
		'elegant_arrow_carrot-2left_alt2' => '&#x40;', 
		'elegant_arrow_carrot-2right_alt2' => '&#x41;', 
		'elegant_arrow_triangle-up' => '&#x42;', 
		'elegant_arrow_triangle-down' => '&#x43;', 
		'elegant_arrow_triangle-left' => '&#x44;', 
		'elegant_arrow_triangle-right' => '&#x45;', 
		'elegant_arrow_triangle-up_alt2' => '&#x46;', 
		'elegant_arrow_triangle-down_alt2' => '&#x47;', 
		'elegant_arrow_triangle-left_alt2' => '&#x48;', 
		'elegant_arrow_triangle-right_alt2' => '&#x49;', 
		'elegant_arrow_back' => '&#x4a;', 
		'elegant_icon_minus-06' => '&#x4b;', 
		'elegant_icon_plus' => '&#x4c;', 
		'elegant_icon_close' => '&#x4d;', 
		'elegant_icon_check' => '&#x4e;', 
		'elegant_icon_minus_alt2' => '&#x4f;', 
		'elegant_icon_plus_alt2' => '&#x50;', 
		'elegant_icon_close_alt2' => '&#x51;', 
		'elegant_icon_check_alt2' => '&#x52;', 
		'elegant_icon_zoom-out_alt' => '&#x53;', 
		'elegant_icon_zoom-in_alt' => '&#x54;', 
		'elegant_icon_search' => '&#x55;', 
		'elegant_icon_box-empty' => '&#x56;', 
		'elegant_icon_box-selected' => '&#x57;', 
		'elegant_icon_minus-box' => '&#x58;', 
		'elegant_icon_plus-box' => '&#x59;', 
		'elegant_icon_box-checked' => '&#x5a;', 
		'elegant_icon_circle-empty' => '&#x5b;', 
		'elegant_icon_circle-slelected' => '&#x5c;', 
		'elegant_icon_stop_alt2' => '&#x5d;', 
		'elegant_icon_stop' => '&#x5e;', 
		'elegant_icon_pause_alt2' => '&#x5f;', 
		'elegant_icon_pause' => '&#x60;', 
		'elegant_icon_menu' => '&#x61;', 
		'elegant_icon_menu-square_alt2' => '&#x62;', 
		'elegant_icon_menu-circle_alt2' => '&#x63;', 
		'elegant_icon_ul' => '&#x64;', 
		'elegant_icon_ol' => '&#x65;', 
		'elegant_icon_adjust-horiz' => '&#x66;', 
		'elegant_icon_adjust-vert' => '&#x67;', 
		'elegant_icon_document_alt' => '&#x68;', 
		'elegant_icon_documents_alt' => '&#x69;', 
		'elegant_icon_pencil' => '&#x6a;', 
		'elegant_icon_pencil-edit_alt' => '&#x6b;', 
		'elegant_icon_pencil-edit' => '&#x6c;', 
		'elegant_icon_folder-alt' => '&#x6d;', 
		'elegant_icon_folder-open_alt' => '&#x6e;', 
		'elegant_icon_folder-add_alt' => '&#x6f;', 
		'elegant_icon_info_alt' => '&#x70;', 
		'elegant_icon_error-oct_alt' => '&#x71;', 
		'elegant_icon_error-circle_alt' => '&#x72;', 
		'elegant_icon_error-triangle_alt' => '&#x73;', 
		'elegant_icon_question_alt2' => '&#x74;', 
		'elegant_icon_question' => '&#x75;', 
		'elegant_icon_comment_alt' => '&#x76;', 
		'elegant_icon_chat_alt' => '&#x77;', 
		'elegant_icon_vol-mute_alt' => '&#x78;', 
		'elegant_icon_volume-low_alt' => '&#x79;', 
		'elegant_icon_volume-high_alt' => '&#x7a;', 
		'elegant_icon_quotations' => '&#x7b;', 
		'elegant_icon_quotations_alt2' => '&#x7c;', 
		'elegant_icon_clock_alt' => '&#x7d;', 
		'elegant_icon_lock_alt' => '&#x7e;', 
		'elegant_icon_lock-open_alt' => '&#xe000;', 
		'elegant_icon_key_alt' => '&#xe001;', 
		'elegant_icon_cloud_alt' => '&#xe002;', 
		'elegant_icon_cloud-upload_alt' => '&#xe003;', 
		'elegant_icon_cloud-download_alt' => '&#xe004;', 
		'elegant_icon_image' => '&#xe005;', 
		'elegant_icon_images' => '&#xe006;', 
		'elegant_icon_lightbulb_alt' => '&#xe007;', 
		'elegant_icon_gift_alt' => '&#xe008;', 
		'elegant_icon_house_alt' => '&#xe009;', 
		'elegant_icon_genius' => '&#xe00a;', 
		'elegant_icon_mobile' => '&#xe00b;', 
		'elegant_icon_tablet' => '&#xe00c;', 
		'elegant_icon_laptop' => '&#xe00d;', 
		'elegant_icon_desktop' => '&#xe00e;', 
		'elegant_icon_camera_alt' => '&#xe00f;', 
		'elegant_icon_mail_alt' => '&#xe010;', 
		'elegant_icon_cone_alt' => '&#xe011;', 
		'elegant_icon_ribbon_alt' => '&#xe012;', 
		'elegant_icon_bag_alt' => '&#xe013;', 
		'elegant_icon_creditcard' => '&#xe014;', 
		'elegant_icon_cart_alt' => '&#xe015;', 
		'elegant_icon_paperclip' => '&#xe016;', 
		'elegant_icon_tag_alt' => '&#xe017;', 
		'elegant_icon_tags_alt' => '&#xe018;', 
		'elegant_icon_trash_alt' => '&#xe019;', 
		'elegant_icon_cursor_alt' => '&#xe01a;', 
		'elegant_icon_mic_alt' => '&#xe01b;', 
		'elegant_icon_compass_alt' => '&#xe01c;', 
		'elegant_icon_pin_alt' => '&#xe01d;', 
		'elegant_icon_pushpin_alt' => '&#xe01e;', 
		'elegant_icon_map_alt' => '&#xe01f;', 
		'elegant_icon_drawer_alt' => '&#xe020;', 
		'elegant_icon_toolbox_alt' => '&#xe021;', 
		'elegant_icon_book_alt' => '&#xe022;', 
		'elegant_icon_calendar' => '&#xe023;', 
		'elegant_icon_film' => '&#xe024;', 
		'elegant_icon_table' => '&#xe025;', 
		'elegant_icon_contacts_alt' => '&#xe026;', 
		'elegant_icon_headphones' => '&#xe027;', 
		'elegant_icon_lifesaver' => '&#xe028;', 
		'elegant_icon_piechart' => '&#xe029;', 
		'elegant_icon_refresh' => '&#xe02a;', 
		'elegant_icon_link_alt' => '&#xe02b;', 
		'elegant_icon_link' => '&#xe02c;', 
		'elegant_icon_loading' => '&#xe02d;', 
		'elegant_icon_blocked' => '&#xe02e;', 
		'elegant_icon_archive_alt' => '&#xe02f;', 
		'elegant_icon_heart_alt' => '&#xe030;', 
		'elegant_icon_star_alt' => '&#xe031;', 
		'elegant_icon_star-half_alt' => '&#xe032;', 
		'elegant_icon_star' => '&#xe033;', 
		'elegant_icon_star-half' => '&#xe034;', 
		'elegant_icon_tools' => '&#xe035;', 
		'elegant_icon_tool' => '&#xe036;', 
		'elegant_icon_cog' => '&#xe037;', 
		'elegant_icon_cogs' => '&#xe038;', 
		'elegant_arrow_up_alt' => '&#xe039;', 
		'elegant_arrow_down_alt' => '&#xe03a;', 
		'elegant_arrow_left_alt' => '&#xe03b;', 
		'elegant_arrow_right_alt' => '&#xe03c;', 
		'elegant_arrow_left-up_alt' => '&#xe03d;', 
		'elegant_arrow_right-up_alt' => '&#xe03e;', 
		'elegant_arrow_right-down_alt' => '&#xe03f;', 
		'elegant_arrow_left-down_alt' => '&#xe040;', 
		'elegant_arrow_condense_alt' => '&#xe041;', 
		'elegant_arrow_expand_alt3' => '&#xe042;', 
		'elegant_arrow_carrot_up_alt' => '&#xe043;', 
		'elegant_arrow_carrot-down_alt' => '&#xe044;', 
		'elegant_arrow_carrot-left_alt' => '&#xe045;', 
		'elegant_arrow_carrot-right_alt' => '&#xe046;', 
		'elegant_arrow_carrot-2up_alt' => '&#xe047;', 
		'elegant_arrow_carrot-2dwnn_alt' => '&#xe048;', 
		'elegant_arrow_carrot-2left_alt' => '&#xe049;', 
		'elegant_arrow_carrot-2right_alt' => '&#xe04a;', 
		'elegant_arrow_triangle-up_alt' => '&#xe04b;', 
		'elegant_arrow_triangle-down_alt' => '&#xe04c;', 
		'elegant_arrow_triangle-left_alt' => '&#xe04d;', 
		'elegant_arrow_triangle-right_alt' => '&#xe04e;', 
		'elegant_icon_minus_alt' => '&#xe04f;', 
		'elegant_icon_plus_alt' => '&#xe050;', 
		'elegant_icon_close_alt' => '&#xe051;', 
		'elegant_icon_check_alt' => '&#xe052;', 
		'elegant_icon_zoom-out' => '&#xe053;', 
		'elegant_icon_zoom-in' => '&#xe054;', 
		'elegant_icon_stop_alt' => '&#xe055;', 
		'elegant_icon_menu-square_alt' => '&#xe056;', 
		'elegant_icon_menu-circle_alt' => '&#xe057;', 
		'elegant_icon_document' => '&#xe058;', 
		'elegant_icon_documents' => '&#xe059;', 
		'elegant_icon_pencil_alt' => '&#xe05a;', 
		'elegant_icon_folder' => '&#xe05b;', 
		'elegant_icon_folder-open' => '&#xe05c;', 
		'elegant_icon_folder-add' => '&#xe05d;', 
		'elegant_icon_folder_upload' => '&#xe05e;', 
		'elegant_icon_folder_download' => '&#xe05f;', 
		'elegant_icon_info' => '&#xe060;', 
		'elegant_icon_error-circle' => '&#xe061;', 
		'elegant_icon_error-oct' => '&#xe062;', 
		'elegant_icon_error-triangle' => '&#xe063;', 
		'elegant_icon_question_alt' => '&#xe064;', 
		'elegant_icon_comment' => '&#xe065;', 
		'elegant_icon_chat' => '&#xe066;', 
		'elegant_icon_vol-mute' => '&#xe067;', 
		'elegant_icon_volume-low' => '&#xe068;', 
		'elegant_icon_volume-high' => '&#xe069;', 
		'elegant_icon_quotations_alt' => '&#xe06a;', 
		'elegant_icon_clock' => '&#xe06b;', 
		'elegant_icon_lock' => '&#xe06c;', 
		'elegant_icon_lock-open' => '&#xe06d;', 
		'elegant_icon_key' => '&#xe06e;', 
		'elegant_icon_cloud' => '&#xe06f;', 
		'elegant_icon_cloud-upload' => '&#xe070;', 
		'elegant_icon_cloud-download' => '&#xe071;', 
		'elegant_icon_lightbulb' => '&#xe072;', 
		'elegant_icon_gift' => '&#xe073;', 
		'elegant_icon_house' => '&#xe074;', 
		'elegant_icon_camera' => '&#xe075;', 
		'elegant_icon_mail' => '&#xe076;', 
		'elegant_icon_cone' => '&#xe077;', 
		'elegant_icon_ribbon' => '&#xe078;', 
		'elegant_icon_bag' => '&#xe079;', 
		'elegant_icon_cart' => '&#xe07a;', 
		'elegant_icon_tag' => '&#xe07b;', 
		'elegant_icon_tags' => '&#xe07c;', 
		'elegant_icon_trash' => '&#xe07d;', 
		'elegant_icon_cursor' => '&#xe07e;', 
		'elegant_icon_mic' => '&#xe07f;', 
		'elegant_icon_compass' => '&#xe080;', 
		'elegant_icon_pin' => '&#xe081;', 
		'elegant_icon_pushpin' => '&#xe082;', 
		'elegant_icon_map' => '&#xe083;', 
		'elegant_icon_drawer' => '&#xe084;', 
		'elegant_icon_toolbox' => '&#xe085;', 
		'elegant_icon_book' => '&#xe086;', 
		'elegant_icon_contacts' => '&#xe087;', 
		'elegant_icon_archive' => '&#xe088;', 
		'elegant_icon_heart' => '&#xe089;', 
		'elegant_icon_profile' => '&#xe08a;', 
		'elegant_icon_group' => '&#xe08b;', 
		'elegant_icon_grid-2x2' => '&#xe08c;', 
		'elegant_icon_grid-3x3' => '&#xe08d;', 
		'elegant_icon_music' => '&#xe08e;', 
		'elegant_icon_pause_alt' => '&#xe08f;', 
		'elegant_icon_phone' => '&#xe090;', 
		'elegant_icon_upload' => '&#xe091;', 
		'elegant_icon_download' => '&#xe092;', 
		'elegant_social_facebook' => '&#xe093;', 
		'elegant_social_twitter' => '&#xe094;', 
		'elegant_social_pinterest' => '&#xe095;', 
		'elegant_social_googleplus' => '&#xe096;', 
		'elegant_social_tumblr' => '&#xe097;', 
		'elegant_social_tumbleupon' => '&#xe098;', 
		'elegant_social_wordpress' => '&#xe099;', 
		'elegant_social_instagram' => '&#xe09a;', 
		'elegant_social_dribbble' => '&#xe09b;', 
		'elegant_social_vimeo' => '&#xe09c;', 
		'elegant_social_linkedin' => '&#xe09d;', 
		'elegant_social_rss' => '&#xe09e;', 
		'elegant_social_deviantart' => '&#xe09f;', 
		'elegant_social_share' => '&#xe0a0;', 
		'elegant_social_myspace' => '&#xe0a1;', 
		'elegant_social_skype' => '&#xe0a2;', 
		'elegant_social_youtube' => '&#xe0a3;', 
		'elegant_social_picassa' => '&#xe0a4;', 
		'elegant_social_googledrive' => '&#xe0a5;', 
		'elegant_social_flickr' => '&#xe0a6;', 
		'elegant_social_blogger' => '&#xe0a7;', 
		'elegant_social_spotify' => '&#xe0a8;', 
		'elegant_social_delicious' => '&#xe0a9;', 
		'elegant_social_facebook_circle' => '&#xe0aa;', 
		'elegant_social_twitter_circle' => '&#xe0ab;', 
		'elegant_social_pinterest_circle' => '&#xe0ac;', 
		'elegant_social_googleplus_circle' => '&#xe0ad;', 
		'elegant_social_tumblr_circle' => '&#xe0ae;', 
		'elegant_social_stumbleupon_circle' => '&#xe0af;', 
		'elegant_social_wordpress_circle' => '&#xe0b0;', 
		'elegant_social_instagram_circle' => '&#xe0b1;', 
		'elegant_social_dribbble_circle' => '&#xe0b2;', 
		'elegant_social_vimeo_circle' => '&#xe0b3;', 
		'elegant_social_linkedin_circle' => '&#xe0b4;', 
		'elegant_social_rss_circle' => '&#xe0b5;', 
		'elegant_social_deviantart_circle' => '&#xe0b6;', 
		'elegant_social_share_circle' => '&#xe0b7;', 
		'elegant_social_myspace_circle' => '&#xe0b8;', 
		'elegant_social_skype_circle' => '&#xe0b9;', 
		'elegant_social_youtube_circle' => '&#xe0ba;', 
		'elegant_social_picassa_circle' => '&#xe0bb;', 
		'elegant_social_googledrive_alt2' => '&#xe0bc;', 
		'elegant_social_flickr_circle' => '&#xe0bd;', 
		'elegant_social_blogger_circle' => '&#xe0be;', 
		'elegant_social_spotify_circle' => '&#xe0bf;', 
		'elegant_social_delicious_circle' => '&#xe0c0;', 
		'elegant_social_facebook_square' => '&#xe0c1;', 
		'elegant_social_twitter_square' => '&#xe0c2;', 
		'elegant_social_pinterest_square' => '&#xe0c3;', 
		'elegant_social_googleplus_square' => '&#xe0c4;', 
		'elegant_social_tumblr_square' => '&#xe0c5;', 
		'elegant_social_stumbleupon_square' => '&#xe0c6;', 
		'elegant_social_wordpress_square' => '&#xe0c7;', 
		'elegant_social_instagram_square' => '&#xe0c8;', 
		'elegant_social_dribbble_square' => '&#xe0c9;', 
		'elegant_social_vimeo_square' => '&#xe0ca;', 
		'elegant_social_linkedin_square' => '&#xe0cb;', 
		'elegant_social_rss_square' => '&#xe0cc;', 
		'elegant_social_deviantart_square' => '&#xe0cd;', 
		'elegant_social_share_square' => '&#xe0ce;', 
		'elegant_social_myspace_square' => '&#xe0cf;', 
		'elegant_social_skype_square' => '&#xe0d0;', 
		'elegant_social_youtube_square' => '&#xe0d1;', 
		'elegant_social_picassa_square' => '&#xe0d2;', 
		'elegant_social_googledrive_square' => '&#xe0d3;', 
		'elegant_social_flickr_square' => '&#xe0d4;', 
		'elegant_social_blogger_square' => '&#xe0d5;', 
		'elegant_social_spotify_square' => '&#xe0d6;', 
		'elegant_social_delicious_square' => '&#xe0d7;', 
		'elegant_icon_printer' => '&#xe103;', 
		'elegant_icon_calulator' => '&#xe0ee;', 
		'elegant_icon_building' => '&#xe0ef;', 
		'elegant_icon_floppy' => '&#xe0e8;', 
		'elegant_icon_drive' => '&#xe0ea;', 
		'elegant_icon_search-2' => '&#xe101;', 
		'elegant_icon_id' => '&#xe107;', 
		'elegant_icon_id-2' => '&#xe108;', 
		'elegant_icon_puzzle' => '&#xe102;', 
		'elegant_icon_like' => '&#xe106;', 
		'elegant_icon_dislike' => '&#xe0eb;', 
		'elegant_icon_mug' => '&#xe105;', 
		'elegant_icon_currency' => '&#xe0ed;', 
		'elegant_icon_wallet' => '&#xe100;', 
		'elegant_icon_pens' => '&#xe104;', 
		'elegant_icon_easel' => '&#xe0e9;', 
		'elegant_icon_flowchart' => '&#xe109;', 
		'elegant_icon_datareport' => '&#xe0ec;', 
		'elegant_icon_briefcase' => '&#xe0fe;', 
		'elegant_icon_shield' => '&#xe0f6;', 
		'elegant_icon_percent' => '&#xe0fb;', 
		'elegant_icon_globe' => '&#xe0e2;', 
		'elegant_icon_globe-2' => '&#xe0e3;', 
		'elegant_icon_target' => '&#xe0f5;', 
		'elegant_icon_hourglass' => '&#xe0e1;', 
		'elegant_icon_balance' => '&#xe0ff;', 
		'elegant_icon_rook' => '&#xe0f8;', 
		'elegant_icon_printer-alt' => '&#xe0fa;', 
		'elegant_icon_calculator_alt' => '&#xe0e7;', 
		'elegant_icon_building_alt' => '&#xe0fd;', 
		'elegant_icon_floppy_alt' => '&#xe0e4;', 
		'elegant_icon_drive_alt' => '&#xe0e5;', 
		'elegant_icon_search_alt' => '&#xe0f7;', 
		'elegant_icon_id_alt' => '&#xe0e0;', 
		'elegant_icon_id-2_alt' => '&#xe0fc;', 
		'elegant_icon_puzzle_alt' => '&#xe0f9;', 
		'elegant_icon_like_alt' => '&#xe0dd;', 
		'elegant_icon_dislike_alt' => '&#xe0f1;', 
		'elegant_icon_mug_alt' => '&#xe0dc;', 
		'elegant_icon_currency_alt' => '&#xe0f3;', 
		'elegant_icon_wallet_alt' => '&#xe0d8;', 
		'elegant_icon_pens_alt' => '&#xe0db;', 
		'elegant_icon_easel_alt' => '&#xe0f0;', 
		'elegant_icon_flowchart_alt' => '&#xe0df;', 
		'elegant_icon_datareport_alt' => '&#xe0f2;', 
		'elegant_icon_briefcase_alt' => '&#xe0f4;', 
		'elegant_icon_shield_alt' => '&#xe0d9;', 
		'elegant_icon_percent_alt' => '&#xe0da;', 
		'elegant_icon_globe_alt' => '&#xe0de;', 
		'elegant_icon_clipboard' => '&#xe0e6;' );
	if ( ! $none_select )
		array_shift( $font_awesome );
	
	$options = array();
	foreach ( $font_awesome as $key => $content ) {
		$text_val = str_replace( 'fa ', '', $key );
		$text_val = str_replace( array( '_', '-' ), ' ', $text_val );
		$value = $key;
		if ( $content == 'none' )
			$value = '';
		
		$options[$text_val] = $value;
	}
	return $options;
}

function dawnthemes_echo( $string = '' ) {
	return $string;
}

function dawnthemes_is_video_support( $videoUrl ) {
	if ( dawnthemes_detect_video( $videoUrl ) !== false ) {
		return true;
	}
	return false;
}

function dawnthemes_detect_video( $videoUrl ) {
	$videoUrl = strtolower( $videoUrl );
	if ( strpos( $videoUrl, 'youtube.com' ) !== false or strpos( $videoUrl, 'youtu.be' ) !== false ) {
		return 'youtube';
	}
	if ( strpos( $videoUrl, 'dailymotion.com' ) !== false ) {
		return 'dailymotion';
	}
	if ( strpos( $videoUrl, 'vimeo.com' ) !== false ) {
		return 'vimeo';
	}
	
	return false;
}

function dawnthemes_get_video_thumb_url( $videoUrl ) {
	switch ( dawnthemes_detect_video( $videoUrl ) ) {
		case 'youtube' :
			$youtube_id = dawnthemes_get_youtube_id( $videoUrl );
			if ( ! dawnthemes_is404( 'http://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg' ) ) {
				return 'http://img.youtube.com/vi/' . $youtube_id . '/maxresdefault.jpg';
			} else {
				return 'http://img.youtube.com/vi/' . $youtube_id . '/hqdefault.jpg';
			}
			
			break;
		case 'dailymotion' :
			$dailyMotionApi = @file_get_contents( 
				'https://api.dailymotion.com/video/' . dawnthemes_get_dailymotion_id( $videoUrl ) .
					 '?fields=thumbnail_url' );
			$dailyMotionDecoded = @json_decode( $dailyMotionApi );
			if ( ! empty( $dailyMotionDecoded ) and ! empty( $dailyMotionDecoded->thumbnail_url ) ) {
				return $dailyMotionDecoded->thumbnail_url;
			}
			break;
		case 'vimeo' :
			$vimeoApi = @file_get_contents( 
				'http://vimeo.com/api/v2/video/' . dawnthemes_get_vimeo_id( $videoUrl ) . '.php' );
			if ( ! empty( $vimeoApi ) ) {
				$vimeoApiData = @unserialize( $vimeoApi );
				if ( ! empty( $vimeoApiData[0]['thumbnail_large'] ) ) {
					return $vimeoApiData[0]['thumbnail_large'];
				}
			}
			
			break;
	}
}

function dawnthemes_get_youtube_id( $videoUrl ) {
	$query_string = array();
	parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $query_string );
	
	if ( empty( $query_string["v"] ) ) {
		// explode at ? mark
		$yt_short_link_parts_explode1 = explode( '?', $videoUrl );
		
		// short link: http://youtu.be/AgFeZr5ptV8
		$yt_short_link_parts = explode( '/', $yt_short_link_parts_explode1[0] );
		if ( ! empty( $yt_short_link_parts[3] ) ) {
			return $yt_short_link_parts[3];
		}
		
		return $yt_short_link_parts[0];
	} else {
		return $query_string["v"];
	}
}

function dawnthemes_get_youtube_time( $videoUrl ) {
	$query_string = array();
	parse_str( parse_url( $videoUrl, PHP_URL_QUERY ), $query_string );
	if ( ! empty( $query_string["t"] ) ) {
		
		if ( strpos( $query_string["t"], 'm' ) ) {
			// take minutes
			$explode_for_minutes = explode( 'm', $query_string["t"] );
			$minutes = trim( $explode_for_minutes[0] );
			
			// take seconds
			$explode_for_seconds = explode( 's', $explode_for_minutes[1] );
			$seconds = trim( $explode_for_seconds[0] );
			
			$startTime = ( $minutes * 60 ) + $seconds;
		} else {
			// take seconds
			$explode_for_seconds = explode( 's', $query_string["t"] );
			$seconds = trim( $explode_for_seconds[0] );
			
			$startTime = $seconds;
		}
		
		return '&start=' . $startTime;
	} else {
		return '';
	}
}

function dawnthemes_get_vimeo_id( $videoUrl ) {
	$pattern = '/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/';
	preg_match( $pattern, $videoUrl, $matches );
	if ( count( $matches ) ) {
		return end( $matches );
	}
}

function dawnthemes_get_dailymotion_id( $videoUrl ) {
	$id = strtok( basename( $videoUrl ), '_' );
	if ( strpos( $id, '#video=' ) !== false ) {
		$videoParts = explode( '#video=', $id );
		if ( ! empty( $videoParts[1] ) ) {
			return $videoParts[1];
		}
	} else {
		return $id;
	}
}

function dawnthemes_share( 
	$title = '', 
	$facebook = true, 
	$twitter = true, 
	$google = true, 
	$pinterest = true, 
	$linkedin = true, 
	$outlined = false ) {
	?>
<div class="share-links">
		<?php if(!empty($title)):?>
		<h4><?php echo esc_html($title)?></h4>
		<?php endif;?>
		<div class="share-icons">
			<?php if($facebook):?>
			<span class="facebook-share"> <a
			href="<?php echo esc_url('http://www.facebook.com/sharer.php?u='.get_the_permalink()) ?>"
			onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=220,width=600');return false;"
			title="<?php echo esc_html_e('Facebook','matube')?>"><i
				class="fa fa-facebook<?php echo ($outlined ? ' facebook-outlined':'')?>"></i></a>
		</span>
			<?php endif;?>
			<?php if($twitter):?>
			<span class="twitter-share"> <a
			href="<?php echo esc_url('https://twitter.com/share?url='.get_the_permalink()) ?>"
			onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=260,width=600');return false;"
			title="<?php echo esc_html_e('Twitter','matube')?>"><i
				class="fa fa-twitter<?php echo ($outlined ? ' twitter-outlined':'')?>"></i></a>
		</span>
			<?php endif;?>
			<?php if($google):?>
			<span class="google-plus-share"> <a
			href="<?php echo esc_url('https://plus.google.com/share?url='.get_the_permalink()) ?>"
			onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"
			title="<?php echo esc_html_e('Google +','matube')?>"><i
				class="fa fa-google-plus<?php echo ($outlined ? ' google-plus-outlined':'')?>"></i></a>
		</span>
			<?php endif;?>
			<?php if($pinterest):?>
			<span class="pinterest-share"> <a
			href="<?php echo esc_url('http://pinterest.com/pin/create/button/?url='.get_the_permalink().'&media='.(function_exists('the_post_thumbnail') ? wp_get_attachment_url(get_post_thumbnail_id()):'').'&description='.get_the_title()) ?>"
			title="<?php echo esc_html_e('pinterest','matube')?>"><i
				class="fa fa-pinterest<?php echo ($outlined ? ' pinterest-outlined':'')?>"></i></a>
		</span>
			<?php endif;?>
			<?php if($linkedin):?>
			<span class="linkedin-share"> <a
			href="<?php echo esc_url('http://www.linkedin.com/shareArticle?mini=true&url='.get_the_permalink().'&title='.get_the_title())?>"
			onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"
			title="<?php echo esc_html_e('Linked In','matube')?>"><i
				class="fa fa-linkedin<?php echo ($outlined ? ' linkedin-outlined':'')?>"></i></a>
		</span>
			<?php endif;?>
		</div>
</div>
<?php
}

function dawnthemes_do_not_reply_address() {
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) === 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}
	return apply_filters( 'dawnthemes_do_not_reply_address', 'noreply@' . $sitename );
}

if ( ! function_exists( 'dawnthemes_is404' ) ) {

	function dawnthemes_is404( $url ) {
		$headers = get_headers( $url );
		if ( strpos( $headers[0], '404' ) !== false ) {
			return true;
		} else {
			return false;
		}
	}
}

function dawnthemes_is_woocommerce_activated() {
	return class_exists( 'woocommerce' ) ? true : false;
}

function dawnthemes_placeholder_img_src() {
	return apply_filters( 
		'dawnthemes_placeholder_img_src', 
		get_template_directory_uri() . '/assets/images/placeholder.png' );
}

function dawnthemes_get_google_adsense( $atts, $content = null ) {
	$sc_id = uniqid( 'dt_sc_ad_' );
	extract( shortcode_atts( array( 'pub' => '', 'slot' => '' ), $atts ) );
	
	ob_start();
	if ( empty( $pub ) || empty( $slot ) )
		return '';
	?>
<div class="ad">
	<div id="google-ads-<?php echo esc_attr($sc_id) ?>"></div>
	<center>
		<script async
			src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<script type="text/javascript">
		  	/* Replace ca-pub-XXX with your AdSense Publisher ID */
	        google_ad_client = "<?php echo esc_js($pub);?>";
	     
	        /* Replace 1234567890 with the AdSense Ad Slot ID */
	        google_ad_slot = "<?php echo esc_js($slot);?>";
	        
	        /* Calculate the width of available ad space */
	        ad = document.getElementById("google-ads-<?php echo esc_js($sc_id) ?>");
	     
	        if (ad.getBoundingClientRect().width) {
	            adWidth = ad.getBoundingClientRect().width; // for modern browsers
	        } else {
	            adWidth = ad.offsetWidth; // for old IE
	        }
	     
	        /* Do not change anything after this line */
	        if ( adWidth >= 970 )
	        	google_ad_size = ["970", "90"]; /* Large Leaderboard */
	        else if ( adWidth >= 728 )
	        	google_ad_size = ["728", "90"]; /* Leaderboard 728x90 */
	        else if ( adWidth >= 468 )
	        	google_ad_size = ["468", "60"]; /* Banner */
	        else if ( adWidth >= 336 )
	       		google_ad_size = ["336", "280"]; /* Large Rectangle */
	        else if ( adWidth >= 300 )
	        	google_ad_size = ["300", "250"]; /* Medium Rectangle */
	        else if ( adWidth >= 250 )
	        	google_ad_size = ["250", "250"]; /* Square */
	        else if ( adWidth >= 200 )
	        	google_ad_size = ["200", "200"]; /* Small Square */
	        else if ( adWidth >= 180 )
	        	google_ad_size = ["180", "150"]; /* Small Rectangle */
	        else if ( adWidth >= 160 )
	        	google_ad_size = ["160", "600"]; /* Half-Page Ad */
	        else if ( adWidth >= 120 )
	        	google_ad_size = ["120", "600"]; /* Half-Page Ad */
	        else
	        	google_ad_size = ["250", "250"]; /* Default Square */

	        document.write (
	    	"<ins class=\"adsbygoogle\" style=\"display:block; float: center; width:" + google_ad_size[0] + "px; height:" + google_ad_size[1] + "px\" data-ad-client=\"" + google_ad_client + "\" data-ad-slot=\"" + google_ad_slot + "\"></ins>"
	    	);
	        (adsbygoogle = window.adsbygoogle || []).push({});
	        </script>
	</center>
</div>
<?php
	
	return ob_get_clean();
}

/**
 * Mobile Detector
 */
require DAWN_CORE_DIR . '/includes/lib/mobile-detect.php';
$mobile_detector = new DawnThemes_Mobile_Detect();
define( 
	'dawnthemes_device_', 
	$mobile_detector->isMobile() ? ( $mobile_detector->isTablet() ? 'tablet' : 'mobile' ) : 'pc' );

/**
 * Transform some variable to elm's onclick attribute, so it could be obtained from JavaScript as:
 * var data = elm.onclick()
 *
 * @param mixed $data Data to pass
 *
 * @return string Element attribute ' onclick="..."'
 */
function dawnthemes_pass_data_to_js( $data ) {
	return ' onclick=\'return ' . htmlspecialchars( json_encode( $data ), ENT_QUOTES, 'UTF-8' ) . '\'';
}

/* Echo meta data tags */
function dawnthemes_seo_meta_tags() {
	if ( defined( 'WPSEO_VERSION' ) )
		return;
	
	$description = get_bloginfo( 'description' );
	if ( is_single() ) {
		global $post;
		
		$description = esc_attr( $post->post_excerpt );
		?>
<!-- SEO META TAGS -->
<meta property="og:image"
	content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id($post->ID))); ?>" />
<meta property="og:title"
	content="<?php echo esc_attr(get_the_title($post->ID));?>" />
<meta property="og:url"
	content="<?php echo esc_url(get_permalink($post->ID));?>" />
<meta property="og:site_name"
	content="<?php echo esc_attr(get_bloginfo('name'));?>" />
<meta property="og:type"
	content="<?php echo (get_post_format($post->ID) == 'video'? 'video.movie':'');?>" />
<meta property="og:description"
	content="<?php echo esc_attr($description);?>" />
<?php
	}
	?>
<meta name="description" property="description"
	content="<?php echo esc_attr($description);?>" />
<!-- / SEO META TAGS -->
<?php
}

/**
 * Display Social Share buttons for FaceBook, Twitter, LinkedIn, Google+, Thumblr, Pinterest, Email
 */
function dawnthemes_print_social_share( $id = false ) {
	if ( ! $id ) {
		$id = get_the_ID();
	}
	$post_type = get_post_type( $id );
	
	if ( dawnthemes_get_theme_option( $post_type . '_show_share', '1' ) == '0' )
		return;
	
	?>
<div class="share-links dawnthemes_share-links">
	<div class="icon-share-links">
		<ul>
			<?php if(dawnthemes_get_theme_option($post_type.'_sharing_facebook', '1')=='1'){ ?>
		  		<li><a class="facebook trasition-all"
				title="<?php esc_attr_e('Share on Facebook','dawnthemes');?>"
				href="#" target="_blank" rel="nofollow"
				onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+'<?php echo urlencode(get_permalink($id)); ?>','facebook-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-facebook"></i> </a></li>
	    	<?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_twitter', '1' ) == '1' ) {
		?>
		    	<li><a class="twitter trasition-all" href="#"
				title="<?php esc_attr_e('Share on Twitter','dawnthemes');?>"
				rel="nofollow" target="_blank"
				onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(get_the_title($id)); ?>&url=<?php echo urlencode(get_permalink($id)); ?>','twitter-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-twitter"></i> </a></li>
	    	<?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_google', '1' ) == '1' ) {
		?>
    			 <li><a class="google trasition-all" href="#"
				title="<?php esc_attr_e('Share on Google Plus','dawnthemes');?>"
				rel="nofollow" target="_blank"
				onclick="window.open('https://plus.google.com/share?url=<?php echo urlencode(get_permalink($id)); ?>','googleplus-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-google-plus"></i> </a></li>
    		<?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_linkedIn', '0' ) == '1' ) {
		?>
				   	<li><a class="linkedin trasition-all" href="#"
				title="<?php esc_attr_e('Share on LinkedIn','dawnthemes');?>"
				rel="nofollow" target="_blank"
				onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink($id)); ?>&title=<?php echo urlencode(get_the_title($id)); ?>&source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-linkedin"></i> </a></li>
		   	<?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_tumblr', '0' ) == '1' ) {
		?>
			   	<li><a class="tumblr trasition-all" href="#"
				title="<?php esc_attr_e('Share on Tumblr','dawnthemes');?>"
				rel="nofollow" target="_blank"
				onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink($id)); ?>&name=<?php echo urlencode(get_the_title($id)); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-tumblr"></i> </a></li>
	    	<?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_pinterest', '0' ) == '1' ) {
		?>
		    	 <li><a class="pinterest trasition-all" href="#"
				title="<?php esc_attr_e('Pin this','dawnthemes');?>" rel="nofollow"
				target="_blank"
				onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($id)) ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id($id))); ?>&description=<?php echo urlencode(get_the_title($id)) ?>','pin-share-dialog','width=626,height=436');return false;"><i
					class="fa fa-pinterest"></i> </a></li>
	    	 <?php
	}
	
	if ( dawnthemes_get_theme_option( $post_type . '_sharing_email', '0' ) == '1' ) {
		?>
		    	<li><a class="email trasition-all"
				href="mailto:?subject=<?php echo get_the_title($id) ?>&body=<?php echo urlencode(get_permalink($id)) ?>"
				title="<?php esc_attr_e('Email this','dawnthemes');?>"><i
					class="fa fa-envelope"></i> </a></li>
	   		<?php } ?>
	   			<?php apply_filters('dawnthemes_print_social_share_list', '');?>
	   		</ul>
	</div>
</div>
<?php
}

function dawnthemes_countries() {
	return array( 
		"" => "", 
		esc_html__( "Afghanistan", "dawnthemes" ) => esc_html__( "Afghanistan", "dawnthemes" ), 
		esc_html__( "Albania", "dawnthemes" ) => esc_html__( "Albania", "dawnthemes" ), 
		esc_html__( "Algeria", "dawnthemes" ) => esc_html__( "Algeria", "dawnthemes" ), 
		esc_html__( "Andorra", "dawnthemes" ) => esc_html__( "Andorra", "dawnthemes" ), 
		esc_html__( "Angola", "dawnthemes" ) => esc_html__( "Angola", "dawnthemes" ), 
		esc_html__( "Antigua and Barbuda", "dawnthemes" ) => esc_html__( "Antigua and Barbuda", "dawnthemes" ), 
		esc_html__( "Argentina", "dawnthemes" ) => esc_html__( "Argentina", "dawnthemes" ), 
		esc_html__( "Armenia", "dawnthemes" ) => esc_html__( "Armenia", "dawnthemes" ), 
		esc_html__( "Aruba", "dawnthemes" ) => esc_html__( "Aruba", "dawnthemes" ), 
		esc_html__( "Australia", "dawnthemes" ) => esc_html__( "Australia", "dawnthemes" ), 
		esc_html__( "Austria", "dawnthemes" ) => esc_html__( "Austria", "dawnthemes" ), 
		esc_html__( "Azerbaijan", "dawnthemes" ) => esc_html__( "Azerbaijan", "dawnthemes" ), 
		esc_html__( "Bahamas, The", "dawnthemes" ) => esc_html__( "Bahamas, The", "dawnthemes" ), 
		esc_html__( "Bahrain", "dawnthemes" ) => esc_html__( "Bahrain", "dawnthemes" ), 
		esc_html__( "Bangladesh", "dawnthemes" ) => esc_html__( "Bangladesh", "dawnthemes" ), 
		esc_html__( "Barbados", "dawnthemes" ) => esc_html__( "Barbados", "dawnthemes" ), 
		esc_html__( "Belarus", "dawnthemes" ) => esc_html__( "Belarus", "dawnthemes" ), 
		esc_html__( "Belgium", "dawnthemes" ) => esc_html__( "Belgium", "dawnthemes" ), 
		esc_html__( "Belize", "dawnthemes" ) => esc_html__( "Belize", "dawnthemes" ), 
		esc_html__( "Benin", "dawnthemes" ) => esc_html__( "Benin", "dawnthemes" ), 
		esc_html__( "Bhutan", "dawnthemes" ) => esc_html__( "Bhutan", "dawnthemes" ), 
		esc_html__( "Bolivia", "dawnthemes" ) => esc_html__( "Bolivia", "dawnthemes" ), 
		esc_html__( "Bosnia and Herzegovina", "dawnthemes" ) => esc_html__( "Bosnia and Herzegovina", "dawnthemes" ), 
		esc_html__( "Botswana", "dawnthemes" ) => esc_html__( "Botswana", "dawnthemes" ), 
		esc_html__( "Brazil", "dawnthemes" ) => esc_html__( "Brazil", "dawnthemes" ), 
		esc_html__( "Brunei", "dawnthemes" ) => esc_html__( "Brunei", "dawnthemes" ), 
		esc_html__( "Bulgaria", "dawnthemes" ) => esc_html__( "Bulgaria", "dawnthemes" ), 
		esc_html__( "Burkina Faso", "dawnthemes" ) => esc_html__( "Burkina Faso", "dawnthemes" ), 
		esc_html__( "Burma", "dawnthemes" ) => esc_html__( "Burma", "dawnthemes" ), 
		esc_html__( "Burundi", "dawnthemes" ) => esc_html__( "Burundi", "dawnthemes" ), 
		esc_html__( "Cambodia", "dawnthemes" ) => esc_html__( "Cambodia", "dawnthemes" ), 
		esc_html__( "Cameroon", "dawnthemes" ) => esc_html__( "Cameroon", "dawnthemes" ), 
		esc_html__( "Canada", "dawnthemes" ) => esc_html__( "Canada", "dawnthemes" ), 
		esc_html__( "Cabo Verde", "dawnthemes" ) => esc_html__( "Cabo Verde", "dawnthemes" ), 
		esc_html__( "Central African Republic", "dawnthemes" ) => esc_html__( "Central African Republic", "dawnthemes" ), 
		esc_html__( "Chad", "dawnthemes" ) => esc_html__( "Chad", "dawnthemes" ), 
		esc_html__( "Chile", "dawnthemes" ) => esc_html__( "Chile", "dawnthemes" ), 
		esc_html__( "China", "dawnthemes" ) => esc_html__( "China", "dawnthemes" ), 
		esc_html__( "Colombia", "dawnthemes" ) => esc_html__( "Colombia", "dawnthemes" ), 
		esc_html__( "Comoros", "dawnthemes" ) => esc_html__( "Comoros", "dawnthemes" ), 
		esc_html__( "Congo, Democratic Republic of the", "dawnthemes" ) => esc_html__( 
			"Congo, Democratic Republic of the", 
			"dawnthemes" ), 
		esc_html__( "Congo, Republic of the", "dawnthemes" ) => esc_html__( "Congo, Republic of the", "dawnthemes" ), 
		esc_html__( "Costa Rica", "dawnthemes" ) => esc_html__( "Costa Rica", "dawnthemes" ), 
		esc_html__( "Cote d'Ivoire", "dawnthemes" ) => esc_html__( "Cote d'Ivoire", "dawnthemes" ), 
		esc_html__( "Croatia", "dawnthemes" ) => esc_html__( "Croatia", "dawnthemes" ), 
		esc_html__( "Cuba", "dawnthemes" ) => esc_html__( "Cuba", "dawnthemes" ), 
		esc_html__( "Curacao", "dawnthemes" ) => esc_html__( "Curacao", "dawnthemes" ), 
		esc_html__( "Cyprus", "dawnthemes" ) => esc_html__( "Cyprus", "dawnthemes" ), 
		esc_html__( "Czechia", "dawnthemes" ) => esc_html__( "Czechia", "dawnthemes" ), 
		esc_html__( "Denmark", "dawnthemes" ) => esc_html__( "Denmark", "dawnthemes" ), 
		esc_html__( "Djibouti", "dawnthemes" ) => esc_html__( "Djibouti", "dawnthemes" ), 
		esc_html__( "Dominica", "dawnthemes" ) => esc_html__( "Dominica", "dawnthemes" ), 
		esc_html__( "Dominican Republic", "dawnthemes" ) => esc_html__( "Dominican Republic", "dawnthemes" ), 
		esc_html__( "East Timor (see Timor-Leste)", "dawnthemes" ) => esc_html__( 
			"East Timor (see Timor-Leste)", 
			"dawnthemes" ), 
		esc_html__( "Ecuador", "dawnthemes" ) => esc_html__( "Ecuador", "dawnthemes" ), 
		esc_html__( "Egypt", "dawnthemes" ) => esc_html__( "Egypt", "dawnthemes" ), 
		esc_html__( "El Salvador", "dawnthemes" ) => esc_html__( "El Salvador", "dawnthemes" ), 
		esc_html__( "Equatorial Guinea", "dawnthemes" ) => esc_html__( "Equatorial Guinea", "dawnthemes" ), 
		esc_html__( "Eritrea", "dawnthemes" ) => esc_html__( "Eritrea", "dawnthemes" ), 
		esc_html__( "Estonia", "dawnthemes" ) => esc_html__( "Estonia", "dawnthemes" ), 
		esc_html__( "Ethiopia", "dawnthemes" ) => esc_html__( "Ethiopia", "dawnthemes" ), 
		esc_html__( "Fiji", "dawnthemes" ) => esc_html__( "Fiji", "dawnthemes" ), 
		esc_html__( "Finland", "dawnthemes" ) => esc_html__( "Finland", "dawnthemes" ), 
		esc_html__( "France", "dawnthemes" ) => esc_html__( "France", "dawnthemes" ), 
		esc_html__( "Gabon", "dawnthemes" ) => esc_html__( "Gabon", "dawnthemes" ), 
		esc_html__( "Gambia, The", "dawnthemes" ) => esc_html__( "Gambia, The", "dawnthemes" ), 
		esc_html__( "Georgia", "dawnthemes" ) => esc_html__( "Georgia", "dawnthemes" ), 
		esc_html__( "Germany", "dawnthemes" ) => esc_html__( "Germany", "dawnthemes" ), 
		esc_html__( "Ghana", "dawnthemes" ) => esc_html__( "Ghana", "dawnthemes" ), 
		esc_html__( "Greece", "dawnthemes" ) => esc_html__( "Greece", "dawnthemes" ), 
		esc_html__( "Grenada", "dawnthemes" ) => esc_html__( "Grenada", "dawnthemes" ), 
		esc_html__( "Guatemala", "dawnthemes" ) => esc_html__( "Guatemala", "dawnthemes" ), 
		esc_html__( "Guinea", "dawnthemes" ) => esc_html__( "Guinea", "dawnthemes" ), 
		esc_html__( "Guinea-Bissau", "dawnthemes" ) => esc_html__( "Guinea-Bissau", "dawnthemes" ), 
		esc_html__( "Guyana", "dawnthemes" ) => esc_html__( "Guyana", "dawnthemes" ), 
		esc_html__( "Haiti", "dawnthemes" ) => esc_html__( "Haiti", "dawnthemes" ), 
		esc_html__( "Holy See", "dawnthemes" ) => esc_html__( "Holy See", "dawnthemes" ), 
		esc_html__( "Honduras", "dawnthemes" ) => esc_html__( "Honduras", "dawnthemes" ), 
		esc_html__( "Hong Kong", "dawnthemes" ) => esc_html__( "Hong Kong", "dawnthemes" ), 
		esc_html__( "Hungary", "dawnthemes" ) => esc_html__( "Hungary", "dawnthemes" ), 
		esc_html__( "Iceland", "dawnthemes" ) => esc_html__( "Iceland", "dawnthemes" ), 
		esc_html__( "India", "dawnthemes" ) => esc_html__( "India", "dawnthemes" ), 
		esc_html__( "Indonesia", "dawnthemes" ) => esc_html__( "Indonesia", "dawnthemes" ), 
		esc_html__( "Iran", "dawnthemes" ) => esc_html__( "Iran", "dawnthemes" ), 
		esc_html__( "Iraq", "dawnthemes" ) => esc_html__( "Iraq", "dawnthemes" ), 
		esc_html__( "Ireland", "dawnthemes" ) => esc_html__( "Ireland", "dawnthemes" ), 
		esc_html__( "Israel", "dawnthemes" ) => esc_html__( "Israel", "dawnthemes" ), 
		esc_html__( "Italy", "dawnthemes" ) => esc_html__( "Italy", "dawnthemes" ), 
		esc_html__( "Jamaica", "dawnthemes" ) => esc_html__( "Jamaica", "dawnthemes" ), 
		esc_html__( "Japan", "dawnthemes" ) => esc_html__( "Japan", "dawnthemes" ), 
		esc_html__( "Jordan", "dawnthemes" ) => esc_html__( "Jordan", "dawnthemes" ), 
		esc_html__( "Kazakhstan", "dawnthemes" ) => esc_html__( "Kazakhstan", "dawnthemes" ), 
		esc_html__( "Kenya", "dawnthemes" ) => esc_html__( "Kenya", "dawnthemes" ), 
		esc_html__( "Kiribati", "dawnthemes" ) => esc_html__( "Kiribati", "dawnthemes" ), 
		esc_html__( "Korea, North", "dawnthemes" ) => esc_html__( "Korea, North", "dawnthemes" ), 
		esc_html__( "Korea, South", "dawnthemes" ) => esc_html__( "Korea, South", "dawnthemes" ), 
		esc_html__( "Kosovo", "dawnthemes" ) => esc_html__( "Kosovo", "dawnthemes" ), 
		esc_html__( "Kuwait", "dawnthemes" ) => esc_html__( "Kuwait", "dawnthemes" ), 
		esc_html__( "Kyrgyzstan", "dawnthemes" ) => esc_html__( "Kyrgyzstan", "dawnthemes" ), 
		esc_html__( "Laos", "dawnthemes" ) => esc_html__( "Laos", "dawnthemes" ), 
		esc_html__( "Latvia", "dawnthemes" ) => esc_html__( "Latvia", "dawnthemes" ), 
		esc_html__( "Lebanon", "dawnthemes" ) => esc_html__( "Lebanon", "dawnthemes" ), 
		esc_html__( "Lesotho", "dawnthemes" ) => esc_html__( "Lesotho", "dawnthemes" ), 
		esc_html__( "Liberia", "dawnthemes" ) => esc_html__( "Liberia", "dawnthemes" ), 
		esc_html__( "Libya", "dawnthemes" ) => esc_html__( "Libya", "dawnthemes" ), 
		esc_html__( "Liechtenstein", "dawnthemes" ) => esc_html__( "Liechtenstein", "dawnthemes" ), 
		esc_html__( "Lithuania", "dawnthemes" ) => esc_html__( "Lithuania", "dawnthemes" ), 
		esc_html__( "Luxembourg", "dawnthemes" ) => esc_html__( "Luxembourg", "dawnthemes" ), 
		esc_html__( "Macau", "dawnthemes" ) => esc_html__( "Macau", "dawnthemes" ), 
		esc_html__( "Macedonia", "dawnthemes" ) => esc_html__( "Macedonia", "dawnthemes" ), 
		esc_html__( "Madagascar", "dawnthemes" ) => esc_html__( "Madagascar", "dawnthemes" ), 
		esc_html__( "Malawi", "dawnthemes" ) => esc_html__( "Malawi", "dawnthemes" ), 
		esc_html__( "Malaysia", "dawnthemes" ) => esc_html__( "Malaysia", "dawnthemes" ), 
		esc_html__( "Maldives", "dawnthemes" ) => esc_html__( "Maldives", "dawnthemes" ), 
		esc_html__( "Mali", "dawnthemes" ) => esc_html__( "Mali", "dawnthemes" ), 
		esc_html__( "Malta", "dawnthemes" ) => esc_html__( "Malta", "dawnthemes" ), 
		esc_html__( "Marshall Islands", "dawnthemes" ) => esc_html__( "Marshall Islands", "dawnthemes" ), 
		esc_html__( "Mauritania", "dawnthemes" ) => esc_html__( "Mauritania", "dawnthemes" ), 
		esc_html__( "Mauritius", "dawnthemes" ) => esc_html__( "Mauritius", "dawnthemes" ), 
		esc_html__( "Mexico", "dawnthemes" ) => esc_html__( "Mexico", "dawnthemes" ), 
		esc_html__( "Micronesia", "dawnthemes" ) => esc_html__( "Micronesia", "dawnthemes" ), 
		esc_html__( "Moldova", "dawnthemes" ) => esc_html__( "Moldova", "dawnthemes" ), 
		esc_html__( "Monaco", "dawnthemes" ) => esc_html__( "Monaco", "dawnthemes" ), 
		esc_html__( "Mongolia", "dawnthemes" ) => esc_html__( "Mongolia", "dawnthemes" ), 
		esc_html__( "Montenegro", "dawnthemes" ) => esc_html__( "Montenegro", "dawnthemes" ), 
		esc_html__( "Morocco", "dawnthemes" ) => esc_html__( "Morocco", "dawnthemes" ), 
		esc_html__( "Mozambique", "dawnthemes" ) => esc_html__( "Mozambique", "dawnthemes" ), 
		esc_html__( "Namibia", "dawnthemes" ) => esc_html__( "Namibia", "dawnthemes" ), 
		esc_html__( "Nauru", "dawnthemes" ) => esc_html__( "Nauru", "dawnthemes" ), 
		esc_html__( "Nepal", "dawnthemes" ) => esc_html__( "Nepal", "dawnthemes" ), 
		esc_html__( "Netherlands", "dawnthemes" ) => esc_html__( "Netherlands", "dawnthemes" ), 
		esc_html__( "New Zealand", "dawnthemes" ) => esc_html__( "New Zealand", "dawnthemes" ), 
		esc_html__( "Nicaragua", "dawnthemes" ) => esc_html__( "Nicaragua", "dawnthemes" ), 
		esc_html__( "Niger", "dawnthemes" ) => esc_html__( "Niger", "dawnthemes" ), 
		esc_html__( "Nigeria", "dawnthemes" ) => esc_html__( "Nigeria", "dawnthemes" ), 
		esc_html__( "North Korea", "dawnthemes" ) => esc_html__( "North Korea", "dawnthemes" ), 
		esc_html__( "Norway", "dawnthemes" ) => esc_html__( "Norway", "dawnthemes" ), 
		esc_html__( "Oman", "dawnthemes" ) => esc_html__( "Oman", "dawnthemes" ), 
		esc_html__( "Pakistan", "dawnthemes" ) => esc_html__( "Pakistan", "dawnthemes" ), 
		esc_html__( "Palau", "dawnthemes" ) => esc_html__( "Palau", "dawnthemes" ), 
		esc_html__( "Palestinian Territories", "dawnthemes" ) => esc_html__( "Palestinian Territories", "dawnthemes" ), 
		esc_html__( "Panama", "dawnthemes" ) => esc_html__( "Panama", "dawnthemes" ), 
		esc_html__( "Papua New Guinea", "dawnthemes" ) => esc_html__( "Papua New Guinea", "dawnthemes" ), 
		esc_html__( "Paraguay", "dawnthemes" ) => esc_html__( "Paraguay", "dawnthemes" ), 
		esc_html__( "Peru", "dawnthemes" ) => esc_html__( "Peru", "dawnthemes" ), 
		esc_html__( "Philippines", "dawnthemes" ) => esc_html__( "Philippines", "dawnthemes" ), 
		esc_html__( "Poland", "dawnthemes" ) => esc_html__( "Poland", "dawnthemes" ), 
		esc_html__( "Portugal", "dawnthemes" ) => esc_html__( "Portugal", "dawnthemes" ), 
		esc_html__( "Qatar", "dawnthemes" ) => esc_html__( "Qatar", "dawnthemes" ), 
		esc_html__( "Romania", "dawnthemes" ) => esc_html__( "Romania", "dawnthemes" ), 
		esc_html__( "Russia", "dawnthemes" ) => esc_html__( "Russia", "dawnthemes" ), 
		esc_html__( "Rwanda", "dawnthemes" ) => esc_html__( "Rwanda", "dawnthemes" ), 
		esc_html__( "Saint Kitts and Nevis", "dawnthemes" ) => esc_html__( "Saint Kitts and Nevis", "dawnthemes" ), 
		esc_html__( "Saint Lucia", "dawnthemes" ) => esc_html__( "Saint Lucia", "dawnthemes" ), 
		esc_html__( "Saint Vincent and the Grenadines", "dawnthemes" ) => esc_html__( 
			"Saint Vincent and the Grenadines", 
			"dawnthemes" ), 
		esc_html__( "Samoa", "dawnthemes" ) => esc_html__( "Samoa", "dawnthemes" ), 
		esc_html__( "San Marino", "dawnthemes" ) => esc_html__( "San Marino", "dawnthemes" ), 
		esc_html__( "Sao Tome and Principe", "dawnthemes" ) => esc_html__( "Sao Tome and Principe", "dawnthemes" ), 
		esc_html__( "Saudi Arabia", "dawnthemes" ) => esc_html__( "Saudi Arabia", "dawnthemes" ), 
		esc_html__( "Senegal", "dawnthemes" ) => esc_html__( "Senegal", "dawnthemes" ), 
		esc_html__( "Serbia", "dawnthemes" ) => esc_html__( "Serbia", "dawnthemes" ), 
		esc_html__( "Seychelles", "dawnthemes" ) => esc_html__( "Seychelles", "dawnthemes" ), 
		esc_html__( "Sierra Leone", "dawnthemes" ) => esc_html__( "Sierra Leone", "dawnthemes" ), 
		esc_html__( "Singapore", "dawnthemes" ) => esc_html__( "Singapore", "dawnthemes" ), 
		esc_html__( "Sint Maarten", "dawnthemes" ) => esc_html__( "Sint Maarten", "dawnthemes" ), 
		esc_html__( "Slovakia", "dawnthemes" ) => esc_html__( "Slovakia", "dawnthemes" ), 
		esc_html__( "Slovenia", "dawnthemes" ) => esc_html__( "Slovenia", "dawnthemes" ), 
		esc_html__( "Solomon Islands", "dawnthemes" ) => esc_html__( "Solomon Islands", "dawnthemes" ), 
		esc_html__( "Somalia", "dawnthemes" ) => esc_html__( "Somalia", "dawnthemes" ), 
		esc_html__( "South Africa", "dawnthemes" ) => esc_html__( "South Africa", "dawnthemes" ), 
		esc_html__( "South Korea", "dawnthemes" ) => esc_html__( "South Korea", "dawnthemes" ), 
		esc_html__( "South Sudan", "dawnthemes" ) => esc_html__( "South Sudan", "dawnthemes" ), 
		esc_html__( "Spain", "dawnthemes" ) => esc_html__( "Spain", "dawnthemes" ), 
		esc_html__( "Sri Lanka", "dawnthemes" ) => esc_html__( "Sri Lanka", "dawnthemes" ), 
		esc_html__( "Sudan", "dawnthemes" ) => esc_html__( "Sudan", "dawnthemes" ), 
		esc_html__( "Suriname", "dawnthemes" ) => esc_html__( "Suriname", "dawnthemes" ), 
		esc_html__( "Swaziland", "dawnthemes" ) => esc_html__( "Swaziland", "dawnthemes" ), 
		esc_html__( "Sweden", "dawnthemes" ) => esc_html__( "Sweden", "dawnthemes" ), 
		esc_html__( "Switzerland", "dawnthemes" ) => esc_html__( "Switzerland", "dawnthemes" ), 
		esc_html__( "Syria", "dawnthemes" ) => esc_html__( "Syria", "dawnthemes" ), 
		esc_html__( "Taiwan", "dawnthemes" ) => esc_html__( "Taiwan", "dawnthemes" ), 
		esc_html__( "Tajikistan", "dawnthemes" ) => esc_html__( "Tajikistan", "dawnthemes" ), 
		esc_html__( "Tanzania", "dawnthemes" ) => esc_html__( "Tanzania", "dawnthemes" ), 
		esc_html__( "Thailand", "dawnthemes" ) => esc_html__( "Thailand", "dawnthemes" ), 
		esc_html__( "Timor-Leste", "dawnthemes" ) => esc_html__( "Timor-Leste", "dawnthemes" ), 
		esc_html__( "Togo", "dawnthemes" ) => esc_html__( "Togo", "dawnthemes" ), 
		esc_html__( "Tonga", "dawnthemes" ) => esc_html__( "Tonga", "dawnthemes" ), 
		esc_html__( "Trinidad and Tobago", "dawnthemes" ) => esc_html__( "Trinidad and Tobago", "dawnthemes" ), 
		esc_html__( "Tunisia", "dawnthemes" ) => esc_html__( "Tunisia", "dawnthemes" ), 
		esc_html__( "Turkey", "dawnthemes" ) => esc_html__( "Turkey", "dawnthemes" ), 
		esc_html__( "Turkmenistan", "dawnthemes" ) => esc_html__( "Turkmenistan", "dawnthemes" ), 
		esc_html__( "Tuvalu", "dawnthemes" ) => esc_html__( "Tuvalu", "dawnthemes" ), 
		esc_html__( "Uganda", "dawnthemes" ) => esc_html__( "Uganda", "dawnthemes" ), 
		esc_html__( "Ukraine", "dawnthemes" ) => esc_html__( "Ukraine", "dawnthemes" ), 
		esc_html__( "United Arab Emirates", "dawnthemes" ) => esc_html__( "United Arab Emirates", "dawnthemes" ), 
		esc_html__( "United Kingdom", "dawnthemes" ) => esc_html__( "United Kingdom", "dawnthemes" ), 
		esc_html__( "Uruguay", "dawnthemes" ) => esc_html__( "Uruguay", "dawnthemes" ), 
		esc_html__( "USA", "dawnthemes" ) => esc_html__( "USA", "dawnthemes" ), 
		esc_html__( "Uzbekistan", "dawnthemes" ) => esc_html__( "Uzbekistan", "dawnthemes" ), 
		esc_html__( "Vanuatu", "dawnthemes" ) => esc_html__( "Vanuatu", "dawnthemes" ), 
		esc_html__( "Venezuela", "dawnthemes" ) => esc_html__( "Venezuela", "dawnthemes" ), 
		esc_html__( "Vietnam", "dawnthemes" ) => esc_html__( "Vietnam", "dawnthemes" ), 
		esc_html__( "Yemen", "dawnthemes" ) => esc_html__( "Yemen", "dawnthemes" ), 
		esc_html__( "Zambia", "dawnthemes" ) => esc_html__( "Zambia", "dawnthemes" ), 
		esc_html__( "Zimbabwe", "dawnthemes" ) => esc_html__( "Zimbabwe", "dawnthemes" ) );
}

function dawnthemes_languages() {
	$languages = array( 
		"" => "",
		esc_html__( "English", "dawnthemes" ) => esc_html__( "English", "dawnthemes" ), 
		esc_html__( "Abkhaz", "dawnthemes" ) => esc_html__( "Abkhaz", "dawnthemes" ), 
		esc_html__( "Adyghe", "dawnthemes" ) => esc_html__( "Adyghe", "dawnthemes" ), 
		esc_html__( "Afrikaans", "dawnthemes" ) => esc_html__( "Afrikaans", "dawnthemes" ), 
		esc_html__( "Akan", "dawnthemes" ) => esc_html__( "Akan", "dawnthemes" ), 
		esc_html__( "Albanian", "dawnthemes" ) => esc_html__( "Albanian", "dawnthemes" ), 
		esc_html__( "American Sign Language", "dawnthemes" ) => esc_html__( "American Sign Language", "dawnthemes" ), 
		esc_html__( "Amharic", "dawnthemes" ) => esc_html__( "Amharic", "dawnthemes" ), 
		esc_html__( "Arabic", "dawnthemes" ) => esc_html__( "Arabic", "dawnthemes" ), 
		esc_html__( "Aragonese", "dawnthemes" ) => esc_html__( "Aragonese", "dawnthemes" ), 
		esc_html__( "Aramaic", "dawnthemes" ) => esc_html__( "Aramaic", "dawnthemes" ), 
		esc_html__( "Armenian", "dawnthemes" ) => esc_html__( "Armenian", "dawnthemes" ), 
		esc_html__( "Aymara", "dawnthemes" ) => esc_html__( "Aymara", "dawnthemes" ), 
		esc_html__( "Balinese", "dawnthemes" ) => esc_html__( "Balinese", "dawnthemes" ), 
		esc_html__( "Basque", "dawnthemes" ) => esc_html__( "Basque", "dawnthemes" ), 
		esc_html__( "Betawi", "dawnthemes" ) => esc_html__( "Betawi", "dawnthemes" ), 
		esc_html__( "Bosnian", "dawnthemes" ) => esc_html__( "Bosnian", "dawnthemes" ), 
		esc_html__( "Breton", "dawnthemes" ) => esc_html__( "Breton", "dawnthemes" ), 
		esc_html__( "Bulgarian", "dawnthemes" ) => esc_html__( "Bulgarian", "dawnthemes" ), 
		esc_html__( "Cantonese", "dawnthemes" ) => esc_html__( "Cantonese", "dawnthemes" ), 
		esc_html__( "Catalan", "dawnthemes" ) => esc_html__( "Catalan", "dawnthemes" ), 
		esc_html__( "Cherokee", "dawnthemes" ) => esc_html__( "Cherokee", "dawnthemes" ), 
		esc_html__( "Chickasaw", "dawnthemes" ) => esc_html__( "Chickasaw", "dawnthemes" ), 
		esc_html__( "Chinese", "dawnthemes" ) => esc_html__( "Chinese", "dawnthemes" ), 
		esc_html__( "Coptic", "dawnthemes" ) => esc_html__( "Coptic", "dawnthemes" ), 
		esc_html__( "Cornish", "dawnthemes" ) => esc_html__( "Cornish", "dawnthemes" ), 
		esc_html__( "Corsican", "dawnthemes" ) => esc_html__( "Corsican", "dawnthemes" ), 
		esc_html__( "Crimean Tatar", "dawnthemes" ) => esc_html__( "Crimean Tatar", "dawnthemes" ), 
		esc_html__( "Croatian", "dawnthemes" ) => esc_html__( "Croatian", "dawnthemes" ), 
		esc_html__( "Czech", "dawnthemes" ) => esc_html__( "Czech", "dawnthemes" ), 
		esc_html__( "Danish", "dawnthemes" ) => esc_html__( "Danish", "dawnthemes" ), 
		esc_html__( "Dutch", "dawnthemes" ) => esc_html__( "Dutch", "dawnthemes" ), 
		esc_html__( "Dawro", "dawnthemes" ) => esc_html__( "Dawro", "dawnthemes" ), 
		esc_html__( "Esperanto", "dawnthemes" ) => esc_html__( "Esperanto", "dawnthemes" ), 
		esc_html__( "Estonian", "dawnthemes" ) => esc_html__( "Estonian", "dawnthemes" ), 
		esc_html__( "Ewe", "dawnthemes" ) => esc_html__( "Ewe", "dawnthemes" ), 
		esc_html__( "Fiji Hindi", "dawnthemes" ) => esc_html__( "Fiji Hindi", "dawnthemes" ), 
		esc_html__( "Filipino", "dawnthemes" ) => esc_html__( "Filipino", "dawnthemes" ), 
		esc_html__( "Finnish", "dawnthemes" ) => esc_html__( "Finnish", "dawnthemes" ), 
		esc_html__( "French", "dawnthemes" ) => esc_html__( "French", "dawnthemes" ), 
		esc_html__( "Galician", "dawnthemes" ) => esc_html__( "Galician", "dawnthemes" ), 
		esc_html__( "Georgian", "dawnthemes" ) => esc_html__( "Georgian", "dawnthemes" ), 
		esc_html__( "German", "dawnthemes" ) => esc_html__( "German", "dawnthemes" ), 
		esc_html__( "Greek, Modern", "dawnthemes" ) => esc_html__( "Greek, Modern", "dawnthemes" ), 
		esc_html__( "Ancient Greek", "dawnthemes" ) => esc_html__( "Ancient Greek", "dawnthemes" ), 
		esc_html__( "Greenlandic", "dawnthemes" ) => esc_html__( "Greenlandic", "dawnthemes" ), 
		esc_html__( "Haitian Creole", "dawnthemes" ) => esc_html__( "Haitian Creole", "dawnthemes" ), 
		esc_html__( "Hawaiian", "dawnthemes" ) => esc_html__( "Hawaiian", "dawnthemes" ), 
		esc_html__( "Hebrew", "dawnthemes" ) => esc_html__( "Hebrew", "dawnthemes" ), 
		esc_html__( "Hindi", "dawnthemes" ) => esc_html__( "Hindi", "dawnthemes" ), 
		esc_html__( "Hungarian", "dawnthemes" ) => esc_html__( "Hungarian", "dawnthemes" ), 
		esc_html__( "Icelandic", "dawnthemes" ) => esc_html__( "Icelandic", "dawnthemes" ), 
		esc_html__( "Indonesian", "dawnthemes" ) => esc_html__( "Indonesian", "dawnthemes" ), 
		esc_html__( "Inuktitut", "dawnthemes" ) => esc_html__( "Inuktitut", "dawnthemes" ), 
		esc_html__( "Interlingua", "dawnthemes" ) => esc_html__( "Interlingua", "dawnthemes" ), 
		esc_html__( "Irish", "dawnthemes" ) => esc_html__( "Irish", "dawnthemes" ), 
		esc_html__( "Italian", "dawnthemes" ) => esc_html__( "Italian", "dawnthemes" ), 
		esc_html__( "Japanese", "dawnthemes" ) => esc_html__( "Japanese", "dawnthemes" ), 
		esc_html__( "Javanese", "dawnthemes" ) => esc_html__( "Javanese", "dawnthemes" ), 
		esc_html__( "Kabardian", "dawnthemes" ) => esc_html__( "Kabardian", "dawnthemes" ), 
		esc_html__( "Kalasha", "dawnthemes" ) => esc_html__( "Kalasha", "dawnthemes" ), 
		esc_html__( "Kannada", "dawnthemes" ) => esc_html__( "Kannada", "dawnthemes" ), 
		esc_html__( "Kashubian", "dawnthemes" ) => esc_html__( "Kashubian", "dawnthemes" ), 
		esc_html__( "Khmer", "dawnthemes" ) => esc_html__( "Khmer", "dawnthemes" ), 
		esc_html__( "Kinyarwanda", "dawnthemes" ) => esc_html__( "Kinyarwanda", "dawnthemes" ), 
		esc_html__( "Korean", "dawnthemes" ) => esc_html__( "Korean", "dawnthemes" ), 
		esc_html__( "Kurdish/Kurdî", "dawnthemes" ) => esc_html__( "Kurdish/Kurdî", "dawnthemes" ), 
		esc_html__( "Ladin", "dawnthemes" ) => esc_html__( "Ladin", "dawnthemes" ), 
		esc_html__( "Latgalian", "dawnthemes" ) => esc_html__( "Latg", "dawnthemes" ), 
		esc_html__( "Lalian", "dawnthemes" ) => esc_html__( "Lalian", "dawnthemes" ), 
		esc_html__( "Latin", "dawnthemes" ) => esc_html__( "Latin", "dawnthemes" ), 
		esc_html__( "Lingala", "dawnthemes" ) => esc_html__( "Lingala", "dawnthemes" ), 
		esc_html__( "Livonian", "dawnthemes" ) => esc_html__( "Livonian", "dawnthemes" ), 
		esc_html__( "Lojban", "dawnthemes" ) => esc_html__( "Lojban", "dawnthemes" ), 
		esc_html__( "Lower Sorbian", "dawnthemes" ) => esc_html__( "Lower Sorbian", "dawnthemes" ), 
		esc_html__( "Low German", "dawnthemes" ) => esc_html__( "Low German", "dawnthemes" ), 
		esc_html__( "Macedonian", "dawnthemes" ) => esc_html__( "Macedonian", "dawnthemes" ), 
		esc_html__( "Malay", "dawnthemes" ) => esc_html__( "Malay", "dawnthemes" ), 
		esc_html__( "Malayalam", "dawnthemes" ) => esc_html__( "Malayalam", "dawnthemes" ), 
		esc_html__( "Mandarin", "dawnthemes" ) => esc_html__( "Mandarin", "dawnthemes" ), 
		esc_html__( "Manx", "dawnthemes" ) => esc_html__( "Manx", "dawnthemes" ), 
		esc_html__( "Maori", "dawnthemes" ) => esc_html__( "Maori", "dawnthemes" ), 
		esc_html__( "Mauritian Creole", "dawnthemes" ) => esc_html__( "Mauritian Creole", "dawnthemes" ), 
		esc_html__( "Middle Low German", "dawnthemes" ) => esc_html__( "Middle Low German", "dawnthemes" ), 
		esc_html__( "Min Nan", "dawnthemes" ) => esc_html__( "Min Nan", "dawnthemes" ), 
		esc_html__( "Mongolian", "dawnthemes" ) => esc_html__( "Mongolian", "dawnthemes" ), 
		esc_html__( "Norwegian", "dawnthemes" ) => esc_html__( "Norwegian", "dawnthemes" ), 
		esc_html__( "Old Armenian", "dawnthemes" ) => esc_html__( "Old Armenian", "dawnthemes" ), 
		esc_html__( "Old English", "dawnthemes" ) => esc_html__( "Old English", "dawnthemes" ), 
		esc_html__( "Old French", "dawnthemes" ) => esc_html__( "Old French", "dawnthemes" ), 
		esc_html__( "Old Javanese", "dawnthemes" ) => esc_html__( "Old Javanese", "dawnthemes" ), 
		esc_html__( "Old Norse", "dawnthemes" ) => esc_html__( "Old Norse", "dawnthemes" ), 
		esc_html__( "Old Prussian", "dawnthemes" ) => esc_html__( "Old Prussian", "dawnthemes" ), 
		esc_html__( "Oriya", "dawnthemes" ) => esc_html__( "Oriya", "dawnthemes" ), 
		esc_html__( "Pangasinan", "dawnthemes" ) => esc_html__( "Pangasinan", "dawnthemes" ), 
		esc_html__( "Papiamentu", "dawnthemes" ) => esc_html__( "Papiamentu", "dawnthemes" ), 
		esc_html__( "Pashto", "dawnthemes" ) => esc_html__( "Pashto", "dawnthemes" ), 
		esc_html__( "Persian", "dawnthemes" ) => esc_html__( "Persian", "dawnthemes" ), 
		esc_html__( "Pitjantjatjara", "dawnthemes" ) => esc_html__( "Pitjantjatjara", "dawnthemes" ), 
		esc_html__( "Polish", "dawnthemes" ) => esc_html__( "Polish", "dawnthemes" ), 
		esc_html__( "Portuguese", "dawnthemes" ) => esc_html__( "Portuguese", "dawnthemes" ), 
		esc_html__( "Proto-Slavic", "dawnthemes" ) => esc_html__( "Proto-Slavic", "dawnthemes" ), 
		esc_html__( "Quenya", "dawnthemes" ) => esc_html__( "Quenya", "dawnthemes" ), 
		esc_html__( "Rapa Nui", "dawnthemes" ) => esc_html__( "Rapa Nui", "dawnthemes" ), 
		esc_html__( "Romanian", "dawnthemes" ) => esc_html__( "Romanian", "dawnthemes" ), 
		esc_html__( "Russian", "dawnthemes" ) => esc_html__( "Russian", "dawnthemes" ), 
		esc_html__( "Sanskrit", "dawnthemes" ) => esc_html__( "Sanskrit", "dawnthemes" ), 
		esc_html__( "Scots", "dawnthemes" ) => esc_html__( "Scots", "dawnthemes" ), 
		esc_html__( "Scottish Gaelic", "dawnthemes" ) => esc_html__( "Scottish Gaelic", "dawnthemes" ), 
		esc_html__( "Serbian", "dawnthemes" ) => esc_html__( "Serbian", "dawnthemes" ), 
		esc_html__( "Serbo-Croatian", "dawnthemes" ) => esc_html__( "Serbo-Croatian", "dawnthemes" ), 
		esc_html__( "Slovak", "dawnthemes" ) => esc_html__( "Slovak", "dawnthemes" ), 
		esc_html__( "Slovene", "dawnthemes" ) => esc_html__( "Slovene", "dawnthemes" ), 
		esc_html__( "Spanish", "dawnthemes" ) => esc_html__( "Spanish", "dawnthemes" ), 
		esc_html__( "Sinhalese", "dawnthemes" ) => esc_html__( "Sinhalese", "dawnthemes" ), 
		esc_html__( "Swahili", "dawnthemes" ) => esc_html__( "Swahili", "dawnthemes" ), 
		esc_html__( "Swedish", "dawnthemes" ) => esc_html__( "Swedish", "dawnthemes" ), 
		esc_html__( "Tagalog", "dawnthemes" ) => esc_html__( "Tagalog", "dawnthemes" ), 
		esc_html__( "Tajik", "dawnthemes" ) => esc_html__( "Tajik", "dawnthemes" ), 
		esc_html__( "Tamil", "dawnthemes" ) => esc_html__( "Tamil", "dawnthemes" ), 
		esc_html__( "Tarantino", "dawnthemes" ) => esc_html__( "Tarantino", "dawnthemes" ), 
		esc_html__( "Telugu", "dawnthemes" ) => esc_html__( "Telugu", "dawnthemes" ), 
		esc_html__( "Thai", "dawnthemes" ) => esc_html__( "Thai", "dawnthemes" ), 
		esc_html__( "Tok Pisin", "dawnthemes" ) => esc_html__( "Tok Pisin", "dawnthemes" ), 
		esc_html__( "Turkish", "dawnthemes" ) => esc_html__( "Turkish", "dawnthemes" ), 
		esc_html__( "Twi", "dawnthemes" ) => esc_html__( "Twi", "dawnthemes" ), 
		esc_html__( "Ukrainian", "dawnthemes" ) => esc_html__( "Ukrainian", "dawnthemes" ), 
		esc_html__( "Upper Sorbian", "dawnthemes" ) => esc_html__( "Upper Sorbian", "dawnthemes" ), 
		esc_html__( "Urdu", "dawnthemes" ) => esc_html__( "Urdu", "dawnthemes" ), 
		esc_html__( "Uzbek", "dawnthemes" ) => esc_html__( "Uzbek", "dawnthemes" ), 
		esc_html__( "Venetian", "dawnthemes" ) => esc_html__( "Venetian", "dawnthemes" ), 
		esc_html__( "Vietnamese", "dawnthemes" ) => esc_html__( "Vietnamese", "dawnthemes" ), 
		esc_html__( "Vilamovian", "dawnthemes" ) => esc_html__( "Vilamovian", "dawnthemes" ), 
		esc_html__( "Volapük", "dawnthemes" ) => esc_html__( "Volapük", "dawnthemes" ), 
		esc_html__( "Võro", "dawnthemes" ) => esc_html__( "Võro", "dawnthemes" ), 
		esc_html__( "Welsh", "dawnthemes" ) => esc_html__( "Welsh", "dawnthemes" ), 
		esc_html__( "Xhosa", "dawnthemes" ) => esc_html__( "Xhosa", "dawnthemes" ), 
		esc_html__( "Yiddish", "dawnthemes" ) => esc_html__( "Yiddish", "dawnthemes"));
	return $languages;
}

/**
 * Encrypt and decrypt
 *
 * @param string $string string to be encrypted/decrypted
 * @param string $action what to do with this? e for encrypt, d for decrypt
 /
function dawnthemes_crypt( $string, $action = 'e' ) {
	// you may change these values to your own
	$secret_key = 'dawnthemes_secret_key';
	$secret_iv = 'dawnthemes_secret_iv';

	$output = false;
	$encrypt_method = "AES-256-CBC";
	$key = hash( 'sha256', $secret_key );
	$iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );

	if( $action == 'e' ) {
		$output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
	}
	else if( $action == 'd' ){
		$output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
	}

	return $output;
}
*/

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name Template name.
 * @param array  $args          Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 */
function dawnthemes_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args ); // @codingStandardsIgnoreLine
	}

	$located = dawnthemes_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $located ) ) {
		/* translators: %s template */
		wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'dawnthemes' ), '<code>' . $located . '</code>' ), '2.1' );
		return;
	}

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'dawnthemes_get_template', $located, $template_name, $args, $template_path, $default_path );

	do_action( 'dawnthemes_before_template_part', $template_name, $template_path, $located, $args );

	include $located;

	do_action( 'dawnthemes_after_template_part', $template_name, $template_path, $located, $args );
}


/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @access public
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path  Default path. (default: '').
 * @return string
 */
function dawnthemes_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = DawnThemesCore::template_path();
	}

	if ( ! $default_path ) {
		$default_path = DawnThemesCore::plugin_path() . '/templates/';
	}

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);

	// Get default template/.
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}

	// Return what we found.
	return apply_filters( 'dawnthemes_locate_template', $template, $template_name, $template_path );
}

add_filter('upload_mimes', 'dawnthemes_upload_file_types');
function dawnthemes_upload_file_types( $mimes ){
	$mimes['svg'] = 'image/svg+xml';
	$mimes['woff'] = 'application/font-woff';
	$mimes['woff2'] = 'application/font-woff2';
	$mimes['woff2'] = 'font/woff';
	$mimes['woff2'] = 'font/woff2';
	
	return $mimes;
}