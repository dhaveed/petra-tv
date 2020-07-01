<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class viem_Woocommerce {
	public function __construct(){
		remove_action('woocommerce_after_shop_loop','woocommerce_pagination');
		//remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating',5);
		
		//remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title',10);
		//add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_title',5);
		add_action('woocommerce_before_shop_loop_item_title',array(__CLASS__,'loop_product_link_wrap_open'),9);
		add_action('woocommerce_before_shop_loop_item_title',array(__CLASS__,'loop_product_link_wrap_close'),11);
		
		add_action('woocommerce_after_shop_loop','viem_paginate_links');
		add_filter( 'loop_shop_columns',array( __CLASS__, 'loop_shop_columns' ) );
		add_filter( 'loop_shop_per_page',array( __CLASS__, 'loop_shop_per_page' ) );
		add_filter( 'add_to_cart_fragments',array(__CLASS__,'add_to_cart_fragments') );
		add_filter('woocommerce_subcategory_count_html', array(__CLASS__,'subcategory_count_html'),10,2);
		add_filter( 'viem_after_top_toolbar',array(__CLASS__,'minicart') );
		add_filter('body_class', array(__CLASS__,'product_loop_body_class'),50);
		add_action( 'woocommerce_single_product_summary', array( __CLASS__, 'single_sharing' ), 50 );

		add_action('woocommerce_after_cart', array( __CLASS__, 'woo_jsqty'), 10);
		
// 		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
// 		add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'template_loop_product_thumbnail' ), 10 );
			
// 		if(apply_filters('viem_use_template_loop_product_frist_thumbnail', true)){
// 			add_action( 'woocommerce_before_shop_loop_item_title', array( __CLASS__, 'template_loop_product_frist_thumbnail' ), 11 );
// 		}
		// Custom Content Single Product page
		add_filter('woocommerce_product_thumbnails_columns', array(&$this,'viem_woocommerce_product_thumbnails_columns'));
		remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		add_action('woocommerce_after_single_product_summary',  array(&$this,'viem_woocommerce_output_related_products'), 20);
		
		// Change "Add to cart" to "Buy Now" in single event
		add_filter( 'woocommerce_product_single_add_to_cart_text', array(&$this, 'add_to_cart_text') );
		
	}
	
	public static function loop_product_link_wrap_open(){
		echo '<span class="woocommerce-LoopProduct-link-img">';
	}
	
	public static function loop_product_link_wrap_close(){
		echo '</span>';
	}
	
	public static function loop_shop_per_page(){
		return viem_get_theme_option('woo-per-page',6);
	}
	
	public static function subcategory_count_html($count_html,$category){
		return '<span class="count">'.sprintf(__('%s products','viem'),$category->count).'</span>';
	}
	
	public static function add_to_cart_fragments($fragments){
		ob_start();
		self::minicart_link();
		$fragments['a.cart-contents'] = ob_get_clean();
		
		return $fragments;
	}
	
	public static function single_sharing() {
		if ( viem_get_theme_option( 'show-woo-share', '1' ) == '1'){
			$facebook = viem_get_theme_option( 'woo-fb-share', '1' ) == '1' ? true : false;
			$twitter = viem_get_theme_option( 'woo-tw-share', '1' ) == '1' ? true : false;
			$google = viem_get_theme_option( 'woo-go-share', '1' ) == '1' ? true : false;
			$pinterest = viem_get_theme_option( 'woo-pi-share', '0' ) == '1' ? true : false;
			$linkedin = viem_get_theme_option( 'woo-li-share', '0' ) == '1' ? true : false;
			viem_dt_share('',$facebook,$twitter,$google,$pinterest,$linkedin,false);
		}
	}
	
	public static function template_loop_product_thumbnail() {
		$frist = self::_product_get_frist_thumbnail();
		$thumbnail_size = 'shop_catalog';
		echo '<div class="shop-loop-thumbnail'.(apply_filters('viem_use_template_loop_product_frist_thumbnail', true) && $frist != '' ? ' shop-loop-front-thumbnail':'').'">' . woocommerce_get_product_thumbnail($thumbnail_size) . '</div>';
	}
	
	public static function template_loop_product_frist_thumbnail() {
		if ( ( $frist = self::_product_get_frist_thumbnail() ) != '' ) {
			echo '<div class="shop-loop-thumbnail shop-loop-back-thumbnail">' . $frist . '</div>';
		}
	}
	
	protected static function _product_get_frist_thumbnail() {
		global $product, $post;
		$image = '';
		$thumbnail_size = 'shop_catalog';
		if ( version_compare( WOOCOMMERCE_VERSION, "2.0.0" ) >= 0 ) {
			$attachment_ids = $product->get_gallery_attachment_ids();
			$image_count = 0;
			if ( $attachment_ids ) {
				foreach ( $attachment_ids as $attachment_id ) {
					if ( get_post_meta( $attachment_id, '_woocommerce_exclude_image' ) )
						continue;
	
					$image = wp_get_attachment_image( $attachment_id, $thumbnail_size );
	
					$image_count++;
					if ( $image_count == 1 )
						break;
				}
			}
		} else {
			$attachments = get_posts(
				array(
					'post_type' => 'attachment',
					'numberposts' => - 1,
					'post_status' => null,
					'post_parent' => $post->ID,
					'post__not_in' => array( get_post_thumbnail_id() ),
					'post_mime_type' => 'image',
					'orderby' => 'menu_order',
					'order' => 'ASC' ) );
			$image_count = 0;
			if ( $attachments ) {
				foreach ( $attachments as $attachment ) {
	
					if ( get_post_meta( $attachment->ID, '_woocommerce_exclude_image' ) == 1 )
						continue;
	
					$image = wp_get_attachment_image( $attachment->ID, $thumbnail_size );
	
					$image_count++;
	
					if ( $image_count == 1 )
						break;
				}
			}
		}
		return $image;
	}
	
	public static function product_loop_body_class($classes){
		if(is_shop() || is_product_taxonomy() )
			$classes[] =  'columns-'.viem_get_theme_option('woo-per-row',3);
		return $classes;
	}
	
	public static function loop_shop_columns(){
		return viem_get_theme_option('woo-per-row',3);
	}
	
	public static function minicart_link(){
		?>
		<a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'viem' ); ?>">
			<span class="count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
		</a>
		<?php
	}
	
	public static function minicart($echo=true){
		if( viem_get_theme_option('woo-cart-nav', '1') != '1' )
			return;
		
		if ( is_cart() ) {
			$class = 'current-cart';
		} else {
			$class = '';
		}
		ob_start();
		?>
		<ul id="viem-header-minicart" class="header-minicart">
			<li class="minicart <?php echo esc_attr( $class ); ?>">
				<?php self::minicart_link();?>
			</li>
			<li>
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</li>
		</ul>
		<?php
		if($echo)
			echo ob_get_clean();
		else 
			return ob_get_clean();
	}

	public static function woo_jsqty(){
		$html = '';
	    ob_start();
	    ?>
	    <script type="text/javascript">
	        jQuery(document).ready(function($){
	            $('.qty-decrease, .qty-increase').on('click', function(){
	                $( 'div.woocommerce > form input[name="update_cart"]' ).prop( 'disabled', false );
	            });
	        });
	    </script>
	    <?php
	    echo ob_get_clean();
	}
	
	public function viem_woocommerce_product_thumbnails_columns(){
		return 4;
	}
	
	public function viem_woocommerce_output_related_products(){
		$args = array(
			'posts_per_page' 	=> absint( viem_get_theme_option('woo-related-count',3) ),
			'columns' 			=> absint( viem_get_theme_option('woo-related-count',3) ),
			'orderby' 			=> 'rand'
		);
		?>
		<div class="viem-related-products-wrapper viem-columns-<?php echo absint( viem_get_theme_option('woo-related-count',3) ); ?>" data-columns="<?php echo esc_attr( absint( viem_get_theme_option('woo-related-count',3) ) ); ?>">
		<?php
		woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
		?>
		</div>
		<?php
	}
	
	public function add_to_cart_text( $default ){
		if( is_singular('tribe_events') )
			return esc_html__('Buy Now', 'viem');
		return $default;
	}
}

new viem_Woocommerce();
