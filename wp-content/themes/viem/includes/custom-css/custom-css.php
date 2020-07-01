<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );
/*
 * Typography
 */
// Body font
$main_font = viem_get_theme_option('main_font');
if(isset($main_font['font-family'])){
$main_font_family = (1 === preg_match('~[0-9]~', $main_font['font-family'])) ? '"'. $main_font['font-family'] .'"' : $main_font['font-family'];
$main_font_size = (!empty( $main_font['font-size'] ) && 1 === preg_match('~[0-9]~', $main_font['font-size']) ) ? $main_font['font-size'] : 14;
if(!empty($main_font_family)):
?>
body{
	font-family:<?php echo viem_print_string($main_font_family) ?>;
}
<?php
endif;
if(!empty($main_font_size)):?>
body{
	font-size:<?php echo viem_print_string($main_font_size) ?>;
}
<?php
endif;
}
?>
<?php
if(!empty($main_font['font-style'])){
	$font_style = $main_font['font-style'];
	if(strpos($font_style,'italic') === false){
		?>
		body{
			font-weight:<?php echo viem_print_string($font_style); ?>;
		}
		<?php
	}elseif (strpos($font_style,'0italic') == true){
		$font_weight = explode("i",$font_style);
		if( isset($font_weight[0]) ){
			?>
			body{
				font-weight:<?php echo esc_html( $font_weight[0] ) ?>;
				font-style: italic;
			}
			<?php
		}
	}elseif (strpos($font_style,'italic') !== false){
		?>
		body{
			font-weight:400;
			font-style: italic;
		}
		<?php
	}
}
?>
<?php 
// Secondary font
$secondary_font = viem_get_theme_option('secondary_font');
if(isset($secondary_font['font-family'])){
	$secondary_font_family = (1 === preg_match('~[0-9]~', $secondary_font['font-family'])) ? '"'. $secondary_font['font-family'] .'"' : $secondary_font['font-family'];
	if(!empty($secondary_font_family)){
	?>
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	.h1,
	.h2,
	.h3,
	.h4,
	.h5,
	.h6,
	.font-2, blockquote
	{
		font-family:<?php echo viem_print_string($secondary_font_family) ?>;
	}
	<?php
	}
}

// Navigation font
$navbar_typography = viem_get_theme_option('navbar_typography');
if(isset($navbar_typography['font-family'])){
	$navbar_typography_family = (1 === preg_match('~[0-9]~', $navbar_typography['font-family'])) ? '"'. $navbar_typography['font-family'] .'"' : $navbar_typography['font-family'];
	if(!empty($navbar_typography_family)){
		?>
	#primary-navigation li
	{
		font-family:<?php echo viem_print_string($navbar_typography_family) ?>;
	}
	<?php
	}
}

// Uploaded font
$css = '';
$uploaded_fonts = viem_get_theme_option('upload_font', array());
if( is_array($uploaded_fonts) AND count($uploaded_fonts) > 0 ){
	if(isset($uploaded_fonts['font-files'])){
		
		$file = $uploaded_fonts['font-files'];
		$url = 'url(' . esc_url( $file ) . ') format("' . pathinfo( $file, PATHINFO_EXTENSION ) . '")';
		
		if($url){
			$css .= '@font-face {';
			$css .= 'font-display: swap;';
			$css .= 'font-style: normal;';
			$css .= 'font-family:"' . strip_tags( $uploaded_fonts['font-name'] ) . '";';
			$css .= 'font-weight:' . $uploaded_fonts['font-weight'] . ';';
			$css .= 'src:' .  $url  . ';';
			$css .= '}';
		}
	}
}
echo strip_tags($css);
?>

<?php
// Custom body
$body_background = viem_get_theme_option('body_background','');
$text_color = viem_get_theme_option('text_color','');

if( !empty($body_background) ):
?>
body{
	<?php 
	foreach ($body_background as $property => $value){
		if( $property == 'background-image' ){
			echo 'background-image: url('.$value.') !important;';
		}else{
			echo ( $property . ':' . $value .' !important;' );
		}
	}
	?>
}
<?php
endif;
?>
<?php
if( !empty($text_color) ):
?>
body{
	color:<?php echo viem_print_string( $text_color );?> !important;
}
<?php
endif;
?>


<?php
// Custom css here (Main color...)
$main_color = viem_get_theme_option('main_color','');
if( $main_color != '' ):
?>
.primary-navigation ul.main-menu > li.menu-item-has-children:hover > a, .primary-navigation ul.nav-menu > li.menu-item-has-children:hover > a,
.primary-navigation ul.main-menu > li:hover > a, .primary-navigation ul.nav-menu > li:hover > a,
.primary-navigation ul.main-menu > li.current-menu-item > a, .primary-navigation ul.nav-menu > li.current-menu-item > a,

.primary-navigation ul.main-menu > li ul.sub-menu li.menu-item-has-children:hover > a:after, .primary-navigation ul.nav-menu > li ul.sub-menu li.menu-item-has-children:hover > a:after,
.primary-navigation .megamenu > ul.main-menu > li.menu-item-has-children .sub-content.sub-preview .sub-grid-content .grid-item .grid-item-post:hover .title a,
.primary-navigation .current-menu-item > a, .primary-navigation .current-menu-ancestor > a,

.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content.sub-preview .sub-grid-content .grid-item:hover .title,
.site-header .top-header .top-header-socials ul li a:hover,
.site-header .top-header .top-header-box #search-box button:hover i,
.dt-post-category .dt-post-category__wrap .dt-post-category__grid .dt-content.tem-list_big .post-item .entry-title:hover, .dt-post-category .dt-post-category__wrap .dt-post-category__grid .dt-content.tem-list_small .post-item .entry-title:hover,
.dt-posts-slider:not(.single_mode) article.post:hover .entry-title, .dt-post-category article.post:hover .entry-title,
.dt-post-category .dt-post-category__wrap .dt-post-category__grid .dt-content.tem-grid .post-item .entry-title:hover,
.posts-layout-default .post:hover .post-header .post-title a, .posts-layout-list .post:hover .post-header .post-title a, .posts-layout-grid .post:hover .post-header .post-title a, .posts-layout-classic .post:hover .post-header .post-title a, .posts-layout-masonry .post:hover .post-header .post-title a,
.posts-layout-default .post .cat-links a, .posts-layout-list .post .cat-links a, .posts-layout-grid .post .cat-links a, .posts-layout-classic .post .cat-links a, .posts-layout-masonry .post .cat-links a,
.dt-posts-slider .dt-posts-slider__wrap .posts-slider.single_mode .post-item-slide .post-content .entry-meta .byline a,
.entry-title a:hover, .sticky .post-title:before,
.cat-links a:hover, .entry-meta a:hover,
.tweets-widget a,
.video-playlists-item-info .video-playlists-item-title:hover, .current-playing.active .video-playlists-item-info .video-playlists-item-title,
.widget .block-contact-widget p a,
.main-content .dt-posts__wg ul li:hover .post-content .post-title a,
.tags-list .tag-links a:hover,
.posts-layout-default .post .post-meta .byline a:hover, .posts-layout-list .post .post-meta .byline a:hover, .posts-layout-grid .post .post-meta .byline a:hover, .posts-layout-classic .post .post-meta .byline a:hover, .posts-layout-masonry .post .post-meta .byline a:hover,
.single .author-info .author-description .author-primary .author-socials a:hover,
#comments .comment-list li.comment .author-content .box-right .comment-reply-link:hover
{
	color:<?php echo viem_print_string( $main_color );?>;
}

.primary-navigation ul.main-menu > li ul.sub-menu li:hover, .primary-navigation ul.nav-menu > li ul.sub-menu li:hover,
.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content.sub-preview .sub-grid-tabs li:hover,
.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content.sub-preview .sub-grid-tabs li.hover,
.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content.sub-preview .sub-grid-tabs li:hover,
.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content ul.columns>li.column .list .menu-item:hover,
.primary-navigation ul.main-menu > li ul.sub-menu li.current-menu-parent, 
.primary-navigation ul.main-menu > li ul.sub-menu li.current-menu-item, 
.primary-navigation ul.nav-menu > li ul.sub-menu li.current-menu-parent, 
.primary-navigation ul.nav-menu > li ul.sub-menu li.current-menu-item,
body.style-header-3 .bottom-header,
.viem-posts-slider.default .viem-slick-slider .slick-dots li.slick-active button
{
background:<?php echo viem_print_string( $main_color );?>;
}

.viem_main_color, .viem_main_color *{
	color:<?php echo viem_print_string( $main_color );?> !important;
}
.viem-main-color-bg, .viem-main-color-bg-hover:hover{
	background:<?php echo viem_print_string( $main_color );?> !important;
}
.viem-sc-heading:after,
.navbar-search .search-form-wrap.show-popup .searchform:before,
a.go-to-top, a.go-to-top:hover, a.go-to-top:focus,
a.go-to-top.on:hover, a.go-to-top:hover,a.go-to-top:focus.on:hover,
.btn-default:hover, .btn-primary:hover, button:hover, input[type=button]:hover, input[type=submit]:hover, .btn-default:visited:hover, button:visited:hover, input[type=button]:visited:hover, input[type=submit]:visited:hover,
.dt-next-prev-wrap a:hover,
.widget .dt-newsletter-wg input[type="submit"]:hover,
.btn-default:hover, button:hover, input[type=button]:hover, input[type=submit]:hover,
.video-playlists-control
{
	background:<?php echo viem_print_string( $main_color );?>;
}

.viem-ajax-loading .viem-fade-loading i,
.fade-loading i{
	background: none repeat scroll 0 0 <?php echo viem_print_string( $main_color );?>;
}

.dawnthemes-tbn-container a.btn, a.btn,
.btn-default, button, input[type=button], input[type=submit], .btn-default:visited, button:visited, input[type=button]:visited, input[type=submit]:visited,
#respond .comment-form .form-submit .submit,
.wpcf7 .wpcf7-form-control.wpcf7-submit,
.error404 #main-content .page-content .search-form .search-submit
{
	background: <?php echo viem_print_string( $main_color )?>;
	border-color: <?php echo viem_print_string( $main_color )?>;
}

#viem-header-minicart.header-minicart .widget_shopping_cart,
.primary-navigation ul.main-menu>li ul.sub-menu, .primary-navigation ul.nav-menu>li ul.sub-menu,
.primary-navigation .megamenu>ul.main-menu>li.menu-item-has-children .sub-content,
.widget .tagcloud a:hover, .widget .tagcloud a:focus,
.widget .dt-newsletter-wg input[type="submit"]:hover,
.btn-default:hover, input[type=button]:hover, input[type=submit]:hover,
.viem-user-menu ul
{
	border-color:<?php echo viem_print_string( $main_color );?>;
}

.loadmore-action .loadmore-loading .dtwl-navloading .dtwl-navloader{
	border-left-color:<?php echo viem_print_string( $main_color );?>;
}

<?php // hover?>
a:hover, .related-post-title:hover, .viem_main_color_hover:hover *,
.widget.widget_recent_comments .recentcomments .comment-author-link:hover, .widget.widget_recent_comments .recentcomments a:hover,
.primary-navigation ul.main-menu>li ul.sub-menu li.menu-item-has-children:hover>a:after, .primary-navigation ul.nav-menu>li ul.sub-menu li.menu-item-has-children:hover>a:after,
.viem_video .post-title:hover
{
	color:<?php echo viem_print_string( $main_color );?>;
}

<?php
$secondary_color = viem_get_theme_option('secondary_color','');
if( $secondary_color !='' ):?>
.testimonial-carousel .testimonial-carousel-title:after,
.viem-sc-our-team .viem-sc-title:after,
.viem-sc-our-brand .viem-sc-title:after,
.viem-sc-our-brand .viem-carousel-slide .owl-buttons .owl-prev, .viem-sc-our-brand .viem-carousel-slide .owl-buttons .owl-next,
.site-footer .back-to-top
{
	background:<?php echo viem_print_string( $secondary_color );?>;
	border-color:<?php echo viem_print_string( $secondary_color );?>;
}

.testimonial-carousel .testimonial-item .testimonial-item-author .testimonial-item-author-info-position,
.viem-sc-our-team .member-item:hover .member-info .member-name
{
	color:<?php echo viem_print_string( $secondary_color );?>;
}

<?php endif;?>
<?php 
/*
 * Acctions: hover, active...
 */
?>
.dawnthemes-tbn-container a.btn-default:hover,
#searchform.search-form.dt-search-form .search-submit:hover,
.error404 #main-content .page-content .not-found-content .not-found-desc a.back-to-home:hover,
.wpcf7 .wpcf7-form-control.wpcf7-submit:hover,
.loadmore-action button.btn-loadmore:hover,
#respond .comment-form .form-submit .submit:hover,
.paging-navigation a:hover,
.testimonial-carousel .testimonial-carousel-slide .owl-buttons .owl-prev:hover, .testimonial-carousel .testimonial-carousel-slide .owl-buttons .owl-next:hover,
.viem-sc-our-brand .viem-carousel-slide .owl-buttons .owl-prev:hover, .viem-sc-our-brand .viem-carousel-slide .owl-buttons .owl-next:hover,
.viem-sc-our-team .viem-carousel-slide .owl-buttons .owl-prev:hover, .viem-sc-our-team .viem-carousel-slide .owl-buttons .owl-next:hover,
.widget .tagcloud a:hover, .widget .tagcloud a:focus,
.viem-user-menu ul li:hover
{
	background-color:<?php echo viem_print_string( $main_color );?>;
	border-color: <?php echo viem_print_string( $main_color );?>;
	color: #ffffff;
}

.dawnthemes-tbn-container a.btn.hover_e2:after,
.dawnthemes-tbn-container a.btn.hover_e3:after,
.dawnthemes-tbn-container a.btn.hover_e4:after,
.dawnthemes-tbn-container a.btn.hover_e5:after,
.dawnthemes-tbn-container a.btn.hover_e6:after
{
	background:<?php echo viem_print_string( $main_color );?>;
}

.dawnthemes-tbn-container a.btn.hover_e2:hover, .dawnthemes-tbn-container a.btn.hover_e2:active,
.dawnthemes-tbn-container a.btn.hover_e3:hover, .dawnthemes-tbn-container a.btn.hover_e3:active,
.dawnthemes-tbn-container a.btn.hover_e4:hover, .dawnthemes-tbn-container a.btn.hover_e4:active,
.dawnthemes-tbn-container a.btn.hover_e5:hover, .dawnthemes-tbn-container a.btn.hover_e5:active,
.dawnthemes-tbn-container a.btn.hover_e6:hover, .dawnthemes-tbn-container a.btn.hover_e6:active
{
	border-color: <?php echo viem_print_string( $main_color );?>;
}

.posts article .post-title:hover, .post-meta a:hover, .posted-on a:hover, .byline a:hover, .comments-link a:hover,
#footer .footer-bottom a:hover
{
	color: <?php echo viem_print_string( $main_color );?>;
}

<?php if(viem_is_woocommerce_activated()){ ?>
	#viem-header-minicart.header-minicart .cart-contents .count,
	.woocommerce ul.products li.product .onsale,
	.woocommerce span.onsale {
	  background: <?php echo viem_print_string( $main_color )?>;
	}
	
	.woocommerce ul.products li.product-category a:hover h3 {
	  background: <?php echo viem_print_string( $main_color )?>;
	}
	
	.woocommerce div.product .woocommerce-tabs .woocommerce-Tabs-panel #respond input#submit,
	#viem-header-minicart.header-minicart .widget_shopping_cart .widget_shopping_cart_content .buttons .button,
	.woocommerce ul.products li.product .button,
	.woocommerce.widget_price_filter .price_slider_amount .button,
	.woocommerce .cart .button, .woocommerce .cart input.button:disabled,
	.woocommerce a.button.wc-forward,
	.woocommerce #payment #place_order,
	.woocommerce .woocommerce-MyAccount-content .button,
  	.woocommerce div.product div.summary .single_add_to_cart_button,

  	.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce #respond input#submit.disabled, .woocommerce #respond input#submit:disabled, .woocommerce #respond input#submit[disabled]:disabled, .woocommerce a.button.disabled, .woocommerce a.button:disabled, .woocommerce a.button[disabled]:disabled, .woocommerce button.button.disabled, .woocommerce button.button:disabled, .woocommerce button.button[disabled]:disabled, .woocommerce input.button.disabled, .woocommerce input.button:disabled, .woocommerce input.button[disabled]:disabled
	{
	  background: <?php echo viem_print_string( $main_color )?>;
	  border-color: <?php echo viem_print_string( $main_color )?>;
	}
	
	
	.woocommerce #respond input#submit:hover,
	.woocommerce a.button:hover,
	.woocommerce button.button:hover,
	.woocommerce input.button:hover,
	.woocommerce #respond input#submit.disabled:hover,
	.woocommerce #respond input#submit:disabled:hover,
	.woocommerce #respond input#submit[disabled]:disabled:hover,
	.woocommerce a.button.disabled:hover,
	.woocommerce a.button:disabled:hover,
	.woocommerce a.button[disabled]:disabled:hover,
	.woocommerce button.button.disabled:hover,
	.woocommerce button.button:disabled:hover,
	.woocommerce button.button[disabled]:disabled:hover,
	.woocommerce input.button.disabled:hover,
	.woocommerce input.button:disabled:hover,
	.woocommerce input.button[disabled]:disabled:hover,
	.woocommerce #respond input#submit:focus,
	.woocommerce a.button:focus,
	.woocommerce button.button:focus,
	.woocommerce input.button:focus,
	.woocommerce #respond input#submit.disabled:focus,
	.woocommerce #respond input#submit:disabled:focus,
	.woocommerce #respond input#submit[disabled]:disabled:focus,
	.woocommerce a.button.disabled:focus,
	.woocommerce a.button:disabled:focus,
	.woocommerce a.button[disabled]:disabled:focus,
	.woocommerce button.button.disabled:focus,
	.woocommerce button.button:disabled:focus,
	.woocommerce button.button[disabled]:disabled:focus,
	.woocommerce input.button.disabled:focus,
	.woocommerce input.button:disabled:focus,
	.woocommerce input.button[disabled]:disabled:focus,
  	.woocommerce #payment #place_order:hover,
  	.woocommerce-checkout .woocommerce-billing-fields h3:before,
  	.woocommerce-checkout .woocommerce-shipping-fields h3:before,
  	.woocommerce div.product div.summary .single_add_to_cart_button:hover,
  	.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover
  	{
  		background:<?php echo viem_print_string( $main_color );?>;
  		border-color:<?php echo viem_print_string( $main_color );?>;
		color: #ffffff;
  		-webkit-transition: none;
	    -o-transition: none;
	    transition: none;
	}
	
	.woocommerce ul.cart_list li a:hover,
	.woocommerce ul.product_list_widget li a:hover {
	  color: <?php echo viem_print_string( $main_color )?>;
	}
	
	
	.woocommerce table.shop_table .order-total .amount,
	.woocommerce.widget_product_categories ul.product-categories li:hover:before,
	.woocommerce.widget_product_categories ul.product-categories a:hover {
	  color: <?php echo viem_print_string( $main_color )?>;
	}
	
	.woocommerce-message:before,
	.woocommerce-info:before
	{
		color: <?php echo viem_print_string( $main_color )?>;
	}
	.woocommerce_message, .woocommerce_info, .woocommerce_error, .woocommerce-message, .woocommerce-info, .woocommerce-error
	{
		border-color: <?php echo viem_print_string( $main_color )?>;
	}
  	.woocommerce div.product .woocommerce-tabs ul.tabs > li.active > a
  	{
    	border-bottom-color: <?php echo viem_print_string( $main_color )?>;
  	}
  	.woocommerce.widget_price_filter .ui-slider .ui-slider-handle,
  	.woocommerce.widget_product_search form input[type="submit"]{
  		background: <?php echo viem_print_string( $main_color )?>;
  	}
  	
  	<?php 
  	$price_color = viem_get_theme_option('price_color', '');
  	if( $price_color != '' ):
  	?>
  	.woocommerce .price, .woocommerce .amount{
  		color: <?php echo viem_print_string( $price_color );?>;
  	}
  	<?php endif;?>
<?php }?>
<?php 
endif;
?>

<?php 
// Custom Footer color
if( viem_get_theme_option('footer-color', '0') == '1' ):
	$footer_custom_color = viem_get_theme_option('footer-custom-color');
	
	$footer_widget_bg = isset($footer_custom_color['footer-widget-bg']) ? $footer_custom_color['footer-widget-bg'] : '';
	$footer_widget_color = isset($footer_custom_color['footer-widget-color']) ? $footer_custom_color['footer-widget-color'] : '';
	$footer_widget_link = isset($footer_custom_color['footer-widget-link']) ? $footer_custom_color['footer-widget-link'] : '';
	$footer_widget_link_hover = isset($footer_custom_color['footer-widget-link-hover']) ? $footer_custom_color['footer-widget-link-hover'] : '';
	$footer_bg = isset($footer_custom_color['footer-bg']) ? $footer_custom_color['footer-bg'] : '';
	$footer_color = isset($footer_custom_color['footer-color']) ? $footer_custom_color['footer-color'] : '';
	$footer_link = isset($footer_custom_color['footer-link']) ? $footer_custom_color['footer-link'] : '';
	$footer_link_hover = isset($footer_custom_color['footer-link-hover']) ? $footer_custom_color['footer-link-hover'] : '';
	?>
	.site-footer{
		background: <?php echo viem_print_string( $footer_bg ); ?>;
		color: <?php echo viem_print_string( $footer_color); ?>;
	}
	.site-footer .footer-bottom a,
	.site-footer .footer-bottom .copyright-section .footer-social-profile ul li a
	{
		color: <?php echo viem_print_string( $footer_link )?>;
	}
	.site-footer .footer-bottom a:hover,
	.site-footer .footer-bottom .copyright-section .footer-social-profile ul li a:hover
	{
		color: <?php echo viem_print_string( $footer_link_hover )?>;
	}
	
	
	.site-footer #footer-primary{
		background: <?php echo viem_print_string( $footer_widget_bg );?>;
		color: <?php echo viem_print_string( $footer_widget_color );?>;
	}
	.site-footer .footer-sidebar a,
	.site-footer .footer-sidebar .widget .widget-title,
	.site-footer h1, .site-footer h2, .site-footer h3, .site-footer h4, .site-footer h5, .site-footer h6, .site-footer .h1, .site-footer .h2, .site-footer .h3, .site-footer .h4, .site-footer .h5, .site-footer .h6
	{
		color: <?php echo viem_print_string( $footer_widget_link ) ?>;
	}
	.site-footer .footer-sidebar a:hover,
	.site-footer .footer-sidebar a:hover:before,
	.site-footer .footer-sidebar a:hover:after
	{
		color: <?php echo viem_print_string( $footer_widget_link_hover ) ?> !important;
	}
<?php
endif;
?>
<?php 
$class_preloader = viem_get_theme_option('class-preloader', '');
if( !empty($class_preloader) ){
	?>
	.viem-class-schedule-wrapper .fc-view-container.schedule-loading:before{
		background-image: url("<?php echo esc_url($class_preloader);?>");
	}
<?php
}
?>

<?php // depend-theme - You can remove it in development a new theme 
// Video
if( !empty($main_color) ){
?>
.viem-more-videos-slider .related_videos .related_posts__wrapper .next-slider:hover, .viem-more-videos-slider .related_videos .related_posts__wrapper .pre-slider:hover,
.viem-multilink-panel .viem-series-items .viem-playlist-item-video.active .item-video-content a,
.viem-series-panel .viem-series-items .viem-playlist-item-video.active .item-video-content a,
.single.single-viem_channel .inner-header-container .tabbed-header-renderer .channel-tab .channel-tab-item a:after,
.archive.author .page-heading .tabbed-header-renderer .content-tab .content-tab-item a:after,
.viem-posts-slider.syncing .viem-slider-nav_wrap .slider-nav .pre-slider:hover, .viem-posts-slider.syncing .viem-slider-nav_wrap .slider-nav .next-slider:hover,
.viem-sc-badges .viem-sc-title:after
{
	background: <?php echo viem_print_string( $main_color )?>;
}

.jc-custom-title-color.viem-sc-v-category .viem_sc_heading .viem-next-prev-wrap a,
#viem_btn_lightbulb.on i, #viem_btn_lightbulb.on span,
#viem-playlist-wrapper .viem-playlist-panel .viem-playlist-items .viem-playlist-item-video.active .item-video-content .item-video-title,
.single.single-viem_channel .inner-header-container .tabbed-header-renderer .channel-tab .channel-tab-item.active a,
.archive.author .page-heading .tabbed-header-renderer .content-tab .content-tab-item.active a,
body.style-header-7 .bottom-header .bottom-header-wrapper .navbar-search .navbar-search-button i
{
	color: <?php echo viem_print_string( $main_color )?>;
}
<?php
}

$back_to_top_position = viem_get_theme_option('back_to_top_position', '0');
if( $back_to_top_position == '1' ){
	?>
	a.go-to-top, a.go-to-top:hover a.go-to-top:focus{
		right: auto;
		left: 20px;
	}
<?php	
}
// Change play button
if( ($play_btn = viem_get_theme_option('video_play_btn', '')) ){ ?>
	.DawnThemes_video_player .elite_vp_playButtonPoster, .DawnThemes_video_player .elite_vp_playButtonScreen{
		background-image: url("<?php echo esc_url( $play_btn ) ?>");
	}
<?php
}
?>

