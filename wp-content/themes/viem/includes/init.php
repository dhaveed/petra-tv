<?php
$themeInfo = wp_get_theme( 'viem' );
define( 'viem_version', $themeInfo->get( 'Version' ) );
define( 'viem_themename', $themeInfo->get( 'Name' ) );
define( 'viem_textdomain', $themeInfo->get( 'TextDomain' ) );
define( 'viem_includes_url', get_template_directory_uri() . '/includes' );

if ( ! defined( 'viem_dt_assets_uri' ) )
	define( 'viem_dt_assets_uri', get_template_directory_uri() . '/assets' );
	
	// Define layout col
if ( ! defined( 'viem_main_col' ) )
	define( 'viem_main_col', 'col-md-8 has-sidebar' );

if ( ! defined( 'viem_sidebar_col' ) )
	define( 'viem_sidebar_col', 'col-md-4' );
	
/*
 * Require dt core
 * Dont edit this
 */
do_action( 'dawnthemes_includes' );

include_once ( get_template_directory() . '/includes/dt-functions.php' );
include_once ( get_template_directory() . '/includes/dt-hooks.php' );
include_once ( get_template_directory() . '/includes/core/dawn-core.php' );

if ( is_admin() ) {
	include_once ( get_template_directory() . '/includes/admin/about.php' );
	include_once ( get_template_directory() . '/includes/admin/functions.php' );
	include_once ( get_template_directory() . '/includes/admin/theme-options.php' );
	include_once ( get_template_directory() . '/includes/admin/category-metadata.php' );
}

include_once ( get_template_directory() . '/includes/megamenu/megamenu.php' );

/*
 * Require ultimate addons
 * Dont edit this
 */
do_action( 'viem_ultimate_includes' );


include_once ( get_template_directory() . '/includes/widgets.php' );

// Ajax Read post later
include_once ( get_template_directory() . '/includes/class-ajax-read-post-later.php' );

/* Initialize Visual shodecode editor */
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) && class_exists( 'DawnThemes_VisualComposer' ) ) {

	function requireVcExtend() {
		require_once ( get_template_directory() . '/includes/vc-extend/vc_extend.php' );
	}
	add_action( 'init', 'requireVcExtend', 2 );
}
// Woocommerce
if ( viem_is_woocommerce_activated() ){
	require_once ( get_template_directory() . '/includes/woocommerce.php' );
}

include_once ( get_template_directory() . '/includes/walker.php' );
// Plugins Required - recommended
$plugin_path = get_template_directory() . '/includes/plugins';
if ( file_exists( $plugin_path . '/tgmpa_register.php' ) ) {
	include_once ( $plugin_path . '/tgm-plugin-activation.php' );
	include_once ( $plugin_path . '/tgmpa_register.php' );
}

/*
 * scripts enqueue for admin
 */
function viem_admin_js_and_css() {
	wp_enqueue_media();
	wp_enqueue_style( 'viem_admin_style', get_template_directory_uri() . '/assets/css/admin_style.css' );
	wp_enqueue_script( 'viem_admin_template_js', get_template_directory_uri() . '/includes/admin/assets/js/admin_template.js' );
}
add_action( 'admin_enqueue_scripts', 'viem_admin_js_and_css' );

if ( ! function_exists( 'viem_setup' ) ) :

/**
 * viem setup.
 *
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 *
 */
	function viem_setup() {
		/*
		 * Make viem available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on dawn, use a find and
		 * replace to change 'viem' to the name of your theme in all
		 * template files.
		 */
		load_theme_textdomain( 'viem', get_template_directory() . '/languages' );
		
		// Setup the WordPress core custom background & custom header feature.
		add_theme_support( 'custom-background', apply_filters( 'viem_custom_background_args', array( 'default-color' => 'ffffff' ) ) );
		add_theme_support( 'custom-header', apply_filters( 'viem_custom_header_args', array() ) );
		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( 'assets/css/editor-style.css' );
		
		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );
		
		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );
		
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );
		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );
		
		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		
		// Custom image sizes
		$custom_image_sizes = viem_get_theme_option( 'img_size', '' );
		if ( is_array( $custom_image_sizes ) ) {
			foreach ( $custom_image_sizes as $key => $size ) {
					
				$custom_sizes = explode(',', $size);
					
				$width = ( ! empty( $custom_sizes['0'] ) AND intval( $custom_sizes['0'] ) > 0 ) ? intval( $custom_sizes['0'] ) : 0;
				$height = ( ! empty( $custom_sizes['1'] ) AND intval( $custom_sizes['1'] ) > 0 ) ? intval( $custom_sizes['1'] ) : 0;
				$crop = ( ! empty( $custom_sizes['2'] ) AND intval( $custom_sizes['2'] ) > 0 ) ? intval( $custom_sizes['2'] ) : 0;
				$crop_str = ( $crop ) ? '_crop' : '';
					
				$size_name = 'viem_' . $width . '_' . $height . $crop_str;
				
				add_image_size( 'viem_' . $width . '_' . $height . $crop_str, $width, $height, $crop );
		
			}
		}	
		// Megamenu
		add_image_size( 'viem-megamenu-524x342', 524, 342, true );
		// Actor Avatar
		add_image_size( 'viem-movie-360x460', 360, 460, true );
		/*
		 * Enable support for Post Formats.
		 * See https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array( 'audio', 'gallery' ) );
		
		// This theme support woocommerce
		add_theme_support( 'woocommerce' );
		/**
		 * WooCommerce 3.0 gallery fix
		 */
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
		
		// This theme support yoast-seo-breadcrumbs
		add_theme_support( 'yoast-seo-breadcrumbs' );
		
		// This theme uses wp_nav_menu() in locations.
		$nav_menus = array(
			'primary' => esc_html__( 'Primary Menu', 'viem' ),
			'user-menu' => esc_html__( 'User Menu (Logged)', 'viem' ),
			'footer' => esc_html__( 'Footer Menu', 'viem' ),
		);
		
		if( viem_get_theme_option('header_layout', '') == 'header-3' ){
			$nav_menus['badges'] = esc_html__( 'Badges Menu', 'viem' );
			$nav_menus['trending'] = esc_html__( 'Trending Menu', 'viem' );
		}
		if( viem_get_theme_option('header_layout', '') == 'header-5' ){
			$nav_menus['top'] = esc_html__( 'Top Menu', 'viem' );
		}
		
		register_nav_menus( $nav_menus );
	}

endif; // viem_setup
add_action( 'after_setup_theme', 'viem_setup' );


/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function viem_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'viem_content_width', 1140 );
}
add_action( 'template_redirect', 'viem_content_width', 0 );

/**
 * Register widget areas.
 *
 */
function viem_widgets_init() {
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Main Sidebar', 'viem' ), 
			'id' => 'main-sidebar', 
			'description' => esc_html__( 'Main sidebar that appears in the left or right column (2 columns).', 'viem' ), 
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">', 
			'after_widget' => '</aside>', 
			'before_title' => '<h3 class="widget-title"><span>', 
			'after_title' => '</span></h3>' ) );
	register_sidebar(
		array(
			'name' => esc_html__( 'Left Sidebar', 'viem' ),
			'id' => 'left-sidebar',
			'description' => esc_html__( 'Left sidebar that appears in the left of 3 columns (Left - Right Sidebar). Only appears in PC.', 'viem' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>' ) );
	register_sidebar(
		array(
			'name' => esc_html__( 'Right Sidebar', 'viem' ),
			'id' => 'right-sidebar',
			'description' => esc_html__( 'Left sidebar that appears in the right of 3 columns (Left - Right Sidebar).', 'viem' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>' ) );
	register_sidebar(
		array(
			'name' => esc_html__( 'Single Video Sidebar', 'viem' ),
			'id' => 'video-sidebar',
			'description' => esc_html__( 'Only appears in the right left or right (2 columns) of the Single video page. Default appears the Main Sidebar in the Single video page.', 'viem' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>' ) );
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Shop Sidebar', 'viem' ), 
			'description' => esc_html__( 'This sidebar use for Woocommerce page', 'viem' ), 
			'id' => 'sidebar-shop', 
			'before_widget' => '<div id="%1$s" class="widget %2$s ">', 
			'after_widget' => '</div>', 
			'before_title' => '<h3 class="widget-title"><span>', 
			'after_title' => '</span></h3>' ) );
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Menu Sidebar', 'viem' ), 
			'id' => 'menu-sidebar', 
			'description' => esc_html__( 'The sidebar that appears on the menu columns style of MegaMenu.', 'viem' ), 
			'before_widget' => '<aside id="%1$s" class="widget %2$s">', 
			'after_widget' => '</aside>', 
			'before_title' => '<h3 class="widget-title hidden">', 
			'after_title' => '</h3>' ) );
	register_sidebar(
		array(
			'name' => esc_html__( 'Bottom Sidebar', 'viem' ),
			'id' => 'bottom-sidebar',
			'description' => esc_html__( 'Bottom sidebar that appears on the bottom of content (Before Footer Sidebar).', 'viem' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</span></h3>' ) );
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Footer Widget #1', 'viem' ), 
			'id' => 'footer-sidebar-1', 
			'description' => '', 
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">', 
			'after_widget' => '</aside>', 
			'before_title' => '<h3 class="widget-title">', 
			'after_title' => '</h3>' ) );
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Footer Widget #2', 'viem' ), 
			'id' => 'footer-sidebar-2', 
			'description' => '', 
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">', 
			'after_widget' => '</aside>', 
			'before_title' => '<h3 class="widget-title">', 
			'after_title' => '</h3>' ) );
	register_sidebar( 
		array( 
			'name' => esc_html__( 'Footer Widget #3', 'viem' ), 
			'id' => 'footer-sidebar-3', 
			'description' => '', 
			'before_widget' => '<aside id="%1$s" class="widget %2$s ">', 
			'after_widget' => '</aside>', 
			'before_title' => '<h3 class="widget-title">', 
			'after_title' => '</h3>' ) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget #4', 'viem' ),
		'id'            => 'footer-sidebar-4',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widget #5', 'viem' ),
		'id'            => 'footer-sidebar-5',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s ">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'viem_widgets_init' );

/**
 * Enqueue scripts
 */
function viem_enqueue_styles_and_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$main_css_id = basename( get_template_directory() );
	
	// Playfair Display
	viem_dt_enqueue_google_font();
	if ( 'off' !== _x( 'on', 'Google font: on or off', 'viem' ) ) {
		wp_enqueue_style( 'viem-google-font', '//fonts.googleapis.com/css?family=Poppins:400,600' );
	}
	
	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/assets/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}
	
	if ( class_exists( 'WPCF7_ContactForm' ) )
		wp_deregister_style( 'contact-form-7' );
	
	wp_register_script( 'google-maps', '//maps.googleapis.com/maps/api/js?key=' . viem_get_gmap_setting( 'key' ) . '&sensor=false&libraries=places', array( 'jquery' ), '3', false );
	wp_localize_script( 'google-maps', 'DTGmapSetting', viem_get_gmap_setting() );
	
	// Add Awesome font, used in the main stylesheet.
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array( 'jquery' ), '3.5.5', true );
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.5' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/css/fonts/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
	
	wp_enqueue_style( 'elegant-icon', get_template_directory_uri() . '/assets/lib/elegant-icon/css/elegant-icon.css' );
	
	wp_enqueue_script( 'ajax-chosen', get_template_directory_uri() . '/assets/lib/chosen/ajax-chosen.jquery' . $suffix . '.js', array( 'jquery', 'chosen' ), '', true );
	wp_enqueue_script( 'appear', get_template_directory_uri() . '/assets/lib/jquery.appear' . $suffix . '.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'typed', get_template_directory_uri() . '/assets/lib/typed' . $suffix . '.js', array( 'jquery', 'appear' ), '1.0.0', true );
	wp_enqueue_script( 'easing', get_template_directory_uri() . '/assets/lib/easing' . $suffix . '.js', array( 'jquery' ), '1.3.0', true );
	wp_enqueue_script( 'waypoints', get_template_directory_uri() . '/assets/lib/waypoints' . $suffix . '.js', array( 'jquery' ), '2.0.5', true );
	wp_enqueue_script( 'countTo', get_template_directory_uri() . '/assets/lib/jquery.countTo' . $suffix . '.js', array( 'jquery', 'waypoints' ), '2.0.2', true );
	wp_enqueue_script( 'transit', get_template_directory_uri() . '/assets/lib/jquery.transit' . $suffix . '.js', array( 'jquery' ), '0.9.12', true );
	
	
	wp_enqueue_script( 'progresscircle', get_template_directory_uri() . '/assets/lib/ProgressCircle' . $suffix . '.js', array( 'jquery', 'appear' ), '2.0.2', true );
	
	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/magnific-popup.css' );
	wp_enqueue_script( 'magnific-popup', get_template_directory_uri() . '/assets/lib/magnific-popup/jquery.magnific-popup' . $suffix . '.js', array( 'jquery' ), '0.9.9', true );
	
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/assets/lib/animate.css', array(), '3.6.0' );
	wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/assets/lib/owlcarousel/owl.theme.default.min.css', array(), '2.3.2' );
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/assets/lib/owlcarousel/owl.carousel.min.css', array('owl-theme'), '2.3.2' );
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/assets/lib/owlcarousel/owl.carousel.min.js', array( 'jquery' ), '2.3.2', true );
	
	wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/lib/slick/slick.min.css' );
	
	wp_enqueue_script( 'jquery-ba-throttle-debounce', get_template_directory_uri() . '/assets/lib/helpers/jquery.ba-throttle-debounce.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'jquery-browser', get_template_directory_uri() . '/assets/lib/jquery.browser.min.js', array('jquery'), '', true );
	
	// Video player scripts
	wp_enqueue_script('viem-embed', get_template_directory_uri().'/assets/lib/video-player/js/embed' . $suffix . '.js', array('jquery'), viem_version);
	wp_enqueue_script('jquery-mcustomscrollbar', get_template_directory_uri().'/assets/lib/video-player/js/jquery.mCustomScrollbar.min.js', array('jquery'), '3.0.5');
	wp_enqueue_script('jquery-hls', get_template_directory_uri().'/assets/lib/hls/hls.js', array('jquery'), viem_version);
	wp_enqueue_script('jquery-froogaloop', get_template_directory_uri().'/assets/lib/video-player/js/froogaloop' . $suffix . '.js', array('jquery'), viem_version);
	wp_enqueue_script('jquery-threex-fullscreen', get_template_directory_uri().'/assets/lib/video-player/js/THREEx.FullScreen' . $suffix . '.js', array('jquery'), viem_version);
	wp_enqueue_script('viem-videoplayer', get_template_directory_uri().'/assets/lib/video-player/js/videoPlayer' . $suffix . '.js', array(), viem_version);
	wp_enqueue_script('viem-playlist', get_template_directory_uri().'/assets/lib/video-player/js/Playlist' . $suffix . '.js', array('jquery'), viem_version);
	
	wp_enqueue_style( 'viem-player', get_template_directory_uri().'/assets/lib/video-player/css/elite' . $suffix . '.css' , array(), viem_version);
	wp_enqueue_style( 'viem-player-icons', get_template_directory_uri().'/assets/lib/video-player/css/elite-font-awesome.css' , array(), viem_version);
	wp_enqueue_style( 'jquery-mcustomscrollbar', get_template_directory_uri().'/assets/lib/video-player/css/jquery.mCustomScrollbar' . $suffix . '.css' , array(), viem_version);
	
	wp_enqueue_script( 'viem-plugins', get_template_directory_uri() . '/assets/lib/js/plugins.js', array('jquery'), viem_version, true );
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	if ( viem_is_woocommerce_activated() )
		wp_enqueue_style( $main_css_id . '-woocommerce', get_template_directory_uri() . '/assets/css/woocommerce' . $suffix . '.css', array(), viem_version );
	
	// Load our main stylesheet.
	wp_enqueue_style( $main_css_id, get_template_directory_uri() . '/assets/css/style' . $suffix . '.css', array(), viem_version );
	
	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'ie', get_template_directory_uri() . '/assets/css/ie.css', array( 'viem-style' ), '20131205' );
	wp_style_add_data( 'ie', 'conditional', 'lt IE 9' );
	
	/**
	 * Right To Left CSS
	 */
	if(viem_get_theme_option('rtl','0') == '1'){
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/assets/css/rtl' . $suffix . '.css');
	}
	// Custom CSS
	wp_register_style( $main_css_id . '-wp', get_stylesheet_uri(), false, viem_version );
	do_action( 'viem_main_inline_style', $main_css_id );
	wp_enqueue_style( $main_css_id . '-wp' );
	
	if(viem_get_theme_option('scroll_effect','0') == '1'){
		wp_enqueue_script( 'smoothscroll', get_template_directory_uri() . '/assets/lib/SmoothScroll.js', array( 'jquery' ), '', true );
	}
	
	wp_register_script( 'dawnthemes-script', get_template_directory_uri() . '/assets/js/script' . $suffix . '.js', array( 'jquery' ), viem_version, true );
	
	$logo_retina = '';
	$DawnThemesL10n = array( 
		'ajax_url' => esc_url( admin_url( 'admin-ajax.php', 'relative' ) ),
		'protocol' => viem_get_protocol(),
		'navbarFixedHeight' => 50,
		'breakpoint' => apply_filters( 'dt_js_breakpoint', 900 ), 
		'nav_breakpoint' => apply_filters( 'dt_nav_breakpoint', 900 ), 
		'is_mobile_theme' => 'no', 
		'cookie_path' => COOKIEPATH, 
		'screen_sm' => 768, 
		'screen_md' => 992, 
		'screen_lg' => 1200, 
		'next' => esc_attr__( 'Next', 'viem' ), 
		'prev' => esc_attr__( 'Prev', 'viem' ), 
		'touch_animate' => apply_filters( 'dt_js_touch_animate', true ), 
		'logo_retina' => $logo_retina, 
		'ajax_finishedMsg' => esc_attr__( 'All loaded', 'viem' ), 
		'ajax_msgText' => '', 
		'woocommerce' => ( defined( 'WOOCOMMERCE_VERSION' ) ? 1 : 0 ), 
		'imageLazyLoading' => ( viem_get_theme_option( 'woo-lazyload', 1 ) ? 1 : 0 ), 
		'add_to_wishlist_text' => esc_attr( apply_filters( 'dt_yith_wishlist_is_active', defined( 'YITH_WCWL' ) ) ? apply_filters( 'dt_yith_wcwl_button_label', get_option( 'yith_wcwl_add_to_wishlist_text' ) ) : '' ), 
		'user_logged_in' => ( is_user_logged_in() ? 1 : 0 ),
		'loadingmessage' => esc_attr__( 'Sending info, please wait...', 'viem' ) );
	
	if( viem_get_theme_option('scroll_effect','0') == '1' )
		$DawnThemesL10n['smoothscroll'] = '1';
	
	if( defined( 'dawnthemes_device_' ) && 'dawnthemes_device_' !='pc' )
		$DawnThemesL10n['device'] = 'mobile';
	
	if ( post_type_exists( 'viem_class' ) && viem_get_theme_option('class-rating-review', '1') == '1' ){
		$DawnThemesL10n['i18n_required_rating_text'] = esc_attr__( 'Please select a rating', 'viem' );
		$DawnThemesL10n['review_rating_required'] = (viem_get_theme_option('class_review_rating_required','1') == '1' ? 'yes' : 'no' );
	}
	
	wp_localize_script( 'dawnthemes-script', 'DawnThemesL10n', $DawnThemesL10n );
	wp_enqueue_script( 'dawnthemes-script' );
}
add_action( 'wp_enqueue_scripts', 'viem_enqueue_styles_and_scripts' );

if ( ! function_exists( 'viem_renderurlajax' ) ) :

	function viem_renderurlajax() {
		?>
	<script type="text/javascript">
		var viem_dt_ajaxurl = '<?php echo esc_js( admin_url('admin-ajax.php') ); ?>';
	</script>
<?php
}
add_action( 'wp_head', 'viem_renderurlajax' );

endif;

if ( ! function_exists( 'viem_the_attached_image' ) ) :

	/**
 * Print the attached image with a link to the next attached image.
 */
	function viem_the_attached_image() {
		$post = get_post();
		/**
	 * Filter the default attachment size.
	 *
	 * @param array $dimensions {
	 *     An array of height and width dimensions.
	 *
	 *     @type int $height Height of the image in pixels. Default 810.
	 *     @type int $width  Width of the image in pixels. Default 810.
	 * }
	 */
		$attachment_size = apply_filters( 'dt_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();
		
		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( 
			array( 
				'post_parent' => $post->post_parent, 
				'fields' => 'ids', 
				'numberposts' => - 1, 
				'post_status' => 'inherit', 
				'post_type' => 'attachment', 
				'post_mime_type' => 'image', 
				'order' => 'ASC', 
				'orderby' => 'menu_order ID' ) );
		
		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}
			
			// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			} 			

			// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
			}
		}
		
		printf( 
			'<a href="%1$s" rel="attachment">%2$s</a>', 
			esc_url( $next_attachment_url ), 
			wp_get_attachment_image( $post->ID, $attachment_size ) );
	}




endif;

if ( ! function_exists( 'viem_list_authors' ) ) :

	/**
 * Print a list of all site contributors who published at least one post.
 */
	function viem_list_authors() {
		$contributor_ids = get_users( 
			array( 'fields' => 'ID', 'orderby' => 'post_count', 'order' => 'DESC', 'who' => 'authors' ) );
		
		foreach ( $contributor_ids as $contributor_id ) :
			$post_count = count_user_posts( $contributor_id );
			
			// Move on if user has not published a post (yet).
			if ( ! $post_count ) {
				continue;
			}
			?>
			<div class="contributor">
				<div class="contributor-info">
					<div class="contributor-avatar"><?php echo get_avatar( $contributor_id, 132 ); ?></div>
					<div class="contributor-summary">
						<h2 class="contributor-name"><?php echo get_the_author_meta( 'display_name', $contributor_id ); ?></h2>
						<p class="contributor-bio">
								<?php echo get_the_author_meta( 'description', $contributor_id ); ?>
							</p>
						<a class="button contributor-posts-link"
							href="<?php echo esc_url( get_author_posts_url( $contributor_id ) ); ?>">
								<?php printf( _n( '%d Article', '%d Articles', $post_count, 'viem' ), $post_count ); ?>
							</a>
					</div>
					<!-- .contributor-summary -->
				</div>
				<!-- .contributor-info -->
			</div>
			<!-- .contributor -->
<?php
		endforeach;
	}

endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image except in Multisite signup and activate pages.
 * 3. Index views.
 * 4. Full-width content layout.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function viem_body_classes( $classes ) {
	$classes[] = viem_get_theme_option('site-layout','site-layout-1');

	$theme_mode = viem_get_theme_option( 'theme-mode', '' );
	if ( $theme_mode == 'dark' )
		$classes[] = 'dark-mode';
	
	$footer_parallax = viem_get_post_meta( 'footer-parallax', '' );
	if ( $footer_parallax == 'yes' )
		$classes[] = 'footer-parallax';
	
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}
	
	$header_layout = viem_get_theme_option( 'header_layout', 'header-1' );
	if( is_page() && viem_get_post_meta('header_layout', '', '') != '' )
		$header_layout = viem_get_post_meta('header_layout', '', 'header-1');
	
	$classes[] = 'style-' . $header_layout;
	
	$footer_layout = viem_get_theme_option( 'footer_layout', 'footer-1' );
	if( is_page() && viem_get_post_meta('footer_layout', '', '') != '' )
		$footer_layout = viem_get_post_meta('footer_layout', '', 'footer-1');
	
	$classes[] = 'style-' . $footer_layout;
	
	if ( is_archive() || is_search() || is_home() ) {
	}
	
	if ( ( ! is_active_sidebar( 'sidebar-2' ) ) || is_page_template( 'page-templates/full-width.php' ) ||
		 is_page_template( 'page-templates/front-page.php' ) || is_attachment() ) {
		$classes[] = 'full-width';
	}
	
	global $dawnthemes_page_layout;
	
	if(!empty($dawnthemes_page_layout))
		$classes[] = 'page-layout-'.$dawnthemes_page_layout;
	
	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}
	
	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}
	
	if ( is_singular('viem_video') ) {
		$video_style = viem_get_theme_option('single-video-style', 'style-1');
		$video_meta_style = viem_get_post_meta('single-video-style', '');
		if( !empty($video_meta_style))
			$video_style = $video_meta_style;
		
		$classes[] = 'viem-video-'.$video_style;
		
		if( isset($_GET['list']) && !empty($_GET['list']) )
			$classes[] = 'viem-view_video-playlist';
	}
	
	// Sticky menu
	if ( viem_get_theme_option( 'sticky-menu', '1' ) == '1' ) {
		$classes[] = 'sticky-menu';
	}
	
	// RTL
	if ( viem_get_theme_option( 'rtl', '0' ) == '1' ) {
		$classes[] = 'rtl';
	}
	
	return $classes;
}
add_filter( 'body_class', 'viem_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function viem_post_classes( $classes ) {
	if ( ! post_password_required() && ! is_attachment() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}
	
	return $classes;
}
add_filter( 'post_class', 'viem_post_classes' );

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @global int $paged WordPress archive pagination page count.
 * @global int $page  WordPress paginated post page count.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function viem_wp_title( $title, $sep ) {
	global $paged, $page;
	
	if ( is_feed() ) {
		return $title;
	}
	
	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );
	
	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}
	
	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title = "$title $sep " . sprintf( __ ( 'Page %s', 'viem' ), max( $paged, $page ) );
	}
	
	return $title;
}
add_filter( 'wp_title', 'viem_wp_title', 10, 2 );

if ( ! function_exists( 'viem_dt_comment' ) ) :

	/**
	* Template for comments and pingbacks.
	*
	* To override this walker in a child theme without modifying the comments template
	* simply create your own dt_comment(), and that function will be used instead.
	*
	* Used as a callback by wp_list_comments() for displaying the comments.
	*/
	function viem_dt_comment( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
				<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p><?php comment_author_link(); ?></p>
				<p><?php comment_text(); ?></p>
			 	<?php
				break;
			default : // Proceed with normal comments.
				global $post;
				?>
				<li <?php comment_class('block-author author'); ?> id="li-comment-<?php comment_ID(); ?>">
					<div id="comment-<?php comment_ID(); ?>" class="comment">
						<div class="blog-img-wrap">
							<?php
							if( defined('DAWNTHEMES_PREVIEW') && ( $custom_avatar_url = get_the_author_meta( 'viem_custom_profile_picture', $comment->user_id ) )): ?>
								<img src="<?php echo esc_url($custom_avatar_url);?>"  class="avatar">
					 		<?php 
					 		else: 
					 			echo get_avatar( $comment, 65 );
					 		endif;
					 		?>

						</div>
						<div class="group author-content">
							<div class="meta box-left">
								<div class="comment-author font-2">
				 					<?php echo get_comment_author_link(); ?>
				 				</div>
								<div class="date-wrap">
				 					<?php
								printf( 
									'<time datetime="%2$s" class="date">%3$s</time>', 
									esc_url( get_comment_link( $comment->comment_ID ) ), 
									get_comment_time( 'c' ),
				 								/* translators: 1: date, 2: time */
				 								sprintf( __ ( '%1$s  at %2$s ', 'viem' ), get_comment_date(), get_comment_time() ) );
								?>
				 					</div>
				
							</div>
				
							<div class="box-right">
				                       <?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'viem' ), 'after' => ' <span></span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				 					<!-- .reply -->
							</div>
				
							<div class="comment-content cmt box-comment">					
				 					<?php if ( '0' == $comment->comment_approved ) : ?>
				 					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'viem' ); ?></p>					
				 				<?php endif; ?>
				 					<?php comment_text(); ?>
				 					<?php
								edit_comment_link( esc_html__( 'Edit', 'viem' ), '<p class="edit-link">', '</p>' );
								?>
				 				</div>
							<!-- .comment-content -->
						</div>
						<!-- .comment-meta -->
					</div> <!-- #comment-## -->
				 	<?php
				break;
		endswitch; // end comment_type check
	}
 endif;
 
 
 /**
  * Filters the comment form fields, including the textarea.
  *
  * @since 4.4.0
  *
  * @param array $comment_fields The comment fields.
  */
 add_filter('comment_form_fields', 'viem_comment_form_fields', 10, 1);
 function viem_comment_form_fields($comment_fields){
 	if( get_post_type() === 'post' )
 		$comment_fields['comment'] = '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525" aria-required="true" required="required" placeholder="'._x( 'Your Comment *', 'noun' ).'"></textarea></p>';
 	return $comment_fields;
 }
 
 /**
  * Filters the default comment form fields.
  * 
  * @param array $fields The default comment fields.
  */
  add_filter('comment_form_default_fields', 'viem_comment_form_default_fields', 10, 1);
  function viem_comment_form_default_fields( $fields ){
  	
  	$args = array(
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>',
	);
  	
  	$commenter = wp_get_current_commenter();
  	$user = wp_get_current_user();
  	$user_identity = $user->exists() ? $user->display_name : '';
  	
  	$args = wp_parse_args( $args );
  	if ( ! isset( $args['format'] ) )
  		$args['format'] = current_theme_supports( 'html5', 'comment-form' ) ? 'html5' : 'xhtml';
  	
  	$req      = get_option( 'require_name_email' );
  	$aria_req = ( $req ? " aria-required='true'" : '' );
  	$html_req = ( $req ? " required='required'" : '' );
  	$html5    = 'html5' === $args['format'];
  	$fields   =  array(
  		'author' => '<div class="comment_form_default_fields row"><div class="col-sm-4 comment-form-author">' . '<label class="screen-reader-text" for="author">' . esc_html__( 'Your Name', 'viem' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
  		'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" maxlength="245"' . $aria_req . $html_req . ' placeholder="' . esc_attr__( 'Your Name', 'viem' ) . ( $req ? ' *' : '' ) . '" /></div>',
  		'email'  => '<div class="col-sm-4 comment-form-email"><label class="screen-reader-text" for="email">' . esc_html__( 'Your Email', 'viem' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
  		'<input id="email" name="email" ' . ( $html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" maxlength="100" aria-describedby="email-notes"' . $aria_req . $html_req  . ' placeholder="' . esc_attr__( 'Your Email', 'viem' ) . ( $req ? ' *' : '' ) . '" /></div>',
  		'url'    => '<div class="col-sm-4 comment-form-url"><label class="screen-reader-text" for="url">' . esc_html__( 'Your Website', 'viem' ) . '</label> ' .
  		'<input id="url" name="url" ' . ( $html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" maxlength="200" placeholder="' . esc_attr__( 'Your Website', 'viem' ) . '"/></div></div>',
  	);
  	
  	return $fields;
  }
  
  function viem__add_cpt_author( $query ) {
  	if ( $query->is_author() && $query->is_main_query() ) {
  		$query->set( 'post_type', array('viem_video' ) );
  	}
  }
  add_action( 'pre_get_posts', 'viem__add_cpt_author' );
  