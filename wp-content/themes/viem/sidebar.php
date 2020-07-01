<div id="content-sidebar" class="content-sidebar sidebar-wrap <?php echo esc_attr(viem_sidebar_col);?>" role="complementary">
	<div class="main-sidebar smartsidebar">
		<?php 
		global $get_sidebar_name;
		if(!empty($get_sidebar_name) && is_active_sidebar($get_sidebar_name) || ( viem_get_theme_option('single-event-layout', 'full-width') !== 'full-width')):
				dynamic_sidebar($get_sidebar_name);
		else:
			if(defined('WOOCOMMERCE_VERSION') && is_woocommerce()){
				if(is_active_sidebar('sidebar-shop'))
					dynamic_sidebar('sidebar-shop');
			}else{
				if(is_active_sidebar('main-sidebar'))
					dynamic_sidebar('main-sidebar');
			}
		endif;
		?>
	</div>
</div>
