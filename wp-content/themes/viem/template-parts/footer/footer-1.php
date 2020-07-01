<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$number_footer_sidebar = 0;
if( is_active_sidebar('footer-sidebar-1') ) $number_footer_sidebar = $number_footer_sidebar + 1;
if( is_active_sidebar('footer-sidebar-2') ) $number_footer_sidebar = $number_footer_sidebar + 1;
if( is_active_sidebar('footer-sidebar-3') ) $number_footer_sidebar = $number_footer_sidebar + 1;
if( is_active_sidebar('footer-sidebar-4') ) $number_footer_sidebar = $number_footer_sidebar + 1;
if( is_active_sidebar('footer-sidebar-5') ) $number_footer_sidebar = $number_footer_sidebar + 1;

if( $number_footer_sidebar > 0 ): ?>
<div id="footer-primary">
	<div id="footer-sidebar" class="footer-sidebar widget-area" role="complementary">
		<div class="container">
			<div class="footer-sidebar-content">
				<div class="row footer-primary__columns__<?php echo absint($number_footer_sidebar);?>">
					<?php
					for( $i = 0; $i <= 5 ; $i++):
						if( is_active_sidebar('footer-sidebar-'.$i) ): ?>
						<div class="footer-primary__group">
							<?php dynamic_sidebar( 'footer-sidebar-'.$i ); ?>
						</div>
						<?php
						endif;
					endfor;
					?>
				</div>
			</div>
		</div>
	</div><!-- #footer-sidebar -->
</div><!-- #footer-primary -->
<?php
endif;
?>
<div class="footer-bottom">
	<div class="container">
		<div class="footer-sidebar-content">
			<div class="copyright-section <?php echo (has_nav_menu('footer')) ? 'has-footer-right':'';?>">
				<?php 
				if( has_nav_menu('footer') ):
				?>
				<div class="footer-right">
					<?php wp_nav_menu( array( 'theme_location' => 'footer', 'depth' => 1) ); ?>
				</div>
				<?php endif;?>
				<div class="site-info">
					<?php do_action( 'dt_credits' ); ?>
					<?php 
					$curYear = date('Y');
					echo ( isset( $copyright ) && $copyright !='' ) ? wp_kses($copyright, array(
					'a' => array(
						'href' => array(),
						'class' => array(),
						'data-original-title' => array(),
						'data-toggle' => array(),
						'title' => array()
					),
					'br' => array(),
					)) : 'All design and content Copyright &#169; '.$curYear.' <a href="#" title="DawnThemes">DawnThemes</a>. All rights reserved.'; ?>
				</div><!-- .site-info -->
			</div><!-- .copyright-section -->
		</div>
	</div>
</div><!-- /.footer-bottom -->
