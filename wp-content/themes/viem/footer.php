<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #dawnthemes-page div elements.
 *
 * @package dawn
 */
$viem_dt_footer_layout = viem_get_theme_option('footer_layout', 'footer-1');
if( is_page() && viem_get_post_meta('footer_layout') != '' )
	$viem_dt_footer_layout = viem_get_post_meta('footer_layout');
	
$copyright = viem_get_theme_option('footer-copyright', '');
?>
				</div><!-- #main -->
			<?php 
			if( !is_page_template('page-templates/page-full-width.php') ):
			?>
			</div><!-- .container.main-container || .container-full -->
			<?php 
			endif;
			?>

			<?php do_action('viem_action_after_content_page'); ?>

		</div><!-- #dawnthemes-page -->
		<footer id="footer" class="site-footer style-<?php echo esc_attr( $viem_dt_footer_layout );?>">
			<?php 
			if( is_active_sidebar('bottom-sidebar') ): ?>
			<div id="bottom-sidebar">
				<div class="bottom-sidebar widget-area" role="complementary">
					<div class="container">
						<div class="bottom-sidebar-content">
						<?php dynamic_sidebar('bottom-sidebar'); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			endif;
			?>
			<?php
			viem_dt_get_template("template-parts/footer/{$viem_dt_footer_layout}.php", array(
				'copyright'		=> $copyright
			)); 
			?>
		</footer><!-- #footer -->
	</div><!-- #l-page -->

<?php wp_footer(); ?>
</body>
</html>