<?php
/*
* Template Name: 1 Column - No Slidebar
* @subpackage Dawn
*/
?>
<?php get_header() ?>
	<div class="content-container">
		<div class="<?php viem_dt_container_class() ?>">
			<div class="row">
				<div class="col-md-12 main-wrap" data-itemprop="mainContentOfPage" role="main">
					<div class="main-content">
						<?php if ( have_posts() ) : ?>
							<?php 
							 while (have_posts()): the_post();
								the_content();
							 endwhile;
							 ?>
							 <?php 
							if(viem_get_theme_option('comment-page',0) && comments_open(get_the_ID()))
								comments_template( '', true ); 
							?>
						<?php endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer() ?>