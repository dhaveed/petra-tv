<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package dawn
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
<link rel="pingback" href="<?php esc_url(bloginfo( 'pingback_url' )); ?>">
<?php endif; ?>
<?php wp_head(); ?>
</head>
<?php
// Default
$container_box = false;
$page_has_2_sidebar = false;
$top_header_width = 'main-container-full';
$is_wall_ads = true;

$site_layout = viem_get_theme_option('site-layout','site-layout-1');
$header_layout = viem_get_theme_option('header_layout','header-1');

switch ($site_layout){
	case 'site-layout-2':
		if( is_front_page() ){ $page_has_2_sidebar = true; }
		$is_wall_ads = false;
		break;
	case 'site-layout-3':
		break;
	case 'site-layout-4':
		$container_box = true;
		break;
	case 'site-layout-5':
		$container_box = true;
		$page_has_2_sidebar = false;
		break;
	case 'site-layout-6':
		if( is_singular('viem_channel') ){
			$is_wall_ads = false;
		}
		break;
	case 'site-layout-7':
		break;
	default:
		break;
}

$is_sticky_menu = viem_get_theme_option('sticky-menu','0') == '1' ? ' is_sticky_menu' : '';

?>
<body <?php body_class(); ?>>
<?php 
if( viem_get_theme_option('pre-loading','1') == '1' || (viem_get_theme_option('pre-loading','1') == '2' && is_front_page()) ){ ?>
<div id="dawnthems-preload"><div class="preloading"><img src="<?php echo esc_url( viem_get_theme_option('img-preloading', get_template_directory_uri() . '/assets/images/preloading.gif') ); ?>" alt="Loading"></div></div>
<?php }?>
<div class="offcanvas">
  <a href="#" class="mobile-menu-toggle"><i class="fa fa-times-circle"></i></a>
  <div class="dt-sidenav-wrapper">
		<?php if( has_nav_menu('primary') ): ?>
		<nav id="side-navigation" class="site-navigation side-navigation">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>
		</nav>
		<?php else :?>
		<p class="dt-alert"><?php esc_html_e('Please sellect menu for Main navigation', 'viem'); ?></p>
		<?php endif; ?>
	</div>
</div>
<div id="l-page" class="hfeed site <?php echo ($container_box) ? 'site-box' : ''; ?>">
	<div class="offcanvas-overlay"></div>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'viem' ); ?></a>
	<?php
	$header_class = '';
	if( is_page() && viem_get_post_meta('page_heading', '', 'heading') == 'rev' ){
		$header_class .= ' has-rev_slider ';
	}
	
	$header_class .= $is_sticky_menu;
	
	viem_dt_get_template("template-parts/header/{$header_layout}.php", array('header_class' =>$header_class, 'top_header_width' => $top_header_width));
	
	$no_padding = viem_get_post_meta('no_padding');
	$site_main_class= (!empty($no_padding) ? ' no-padding ':'');
	?>
	<div id="dawnthemes-page">
		<?php 
		do_action('viem_breadcrumbs', 10);
		do_action('viem_page_heading', 10);
		?>
		<?php
		if( is_singular('viem_video') ) :
			$page_has_2_sidebar = false;
			$viem_video_layout = viem_get_theme_option('single-video-layout', 'right-sidebar');
			$viem_video_meta_layout = viem_get_post_meta('single-video-layout', '');
			if( !empty($viem_video_meta_layout) ){
				$viem_video_layout = $viem_video_meta_layout;
			}
			if( $viem_video_layout == 'left-right-sidebar' && $site_layout != 'site-layout-5'){
				$page_has_2_sidebar = true;
				$is_wall_ads = false;
			}
		endif;
		?>
		<?php 
		if( $is_wall_ads ):
			$adsense_slot_ads_wall_left = viem_get_theme_option('adsense_slot_ads_wall_left', '');
			$ads_wall_left_custom = viem_get_theme_option('ads_wall_left_custom', '');
			if( !empty($adsense_slot_ads_wall_left) ||  !empty($ads_wall_left_custom) ){?>
			<div class="viem-wall-ads wall-ads-left">
				<?php
				if( !empty($adsense_slot_ads_wall_left) ){
					echo do_shortcode('[adsense pub="'.viem_get_theme_option('adsense_id', '').'" slot="'.$adsense_slot_ads_wall_left.'"]');
				}else{
					echo viem_print_string($ads_wall_left_custom);
				}
				?>
			</div>
			<?php } ?>
			<?php 
			$adsense_slot_ads_wall_right = viem_get_theme_option('adsense_slot_ads_wall_right', '');
			$ads_wall_right_custom = viem_get_theme_option('ads_wall_right_custom', '');
			if( !empty($adsense_slot_ads_wall_right) ||  !empty($ads_wall_right_custom) ){?>
			<div class="viem-wall-ads wall-ads-right">
				<?php 
				if( !empty($adsense_slot_ads_wall_right) ){
					echo do_shortcode('[adsense pub="'.viem_get_theme_option('adsense_id', '').'" slot="'.$adsense_slot_ads_wall_right.'"]');
				}else{
					echo viem_print_string($ads_wall_right_custom);
				}
				?>
			</div>
			<?php }
			
		endif; ?>

		<?php do_action('viem_action_before_content_page'); ?>

		<?php
		if( !is_page_template('page-templates/page-full-width.php') ):
		?>
		<div class="<?php echo ($page_has_2_sidebar || is_singular('viem_channel') || is_author() ) ? 'main-container-full' : 'container main-container'; ?>" >
		<?php 
		endif;
		?>

			<div id="main" class="wrapper site-main clearfix <?php echo esc_attr($site_main_class); ?>">