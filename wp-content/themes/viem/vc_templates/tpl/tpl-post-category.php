<?php
if($i == 1):
echo '<div class="dt-block-big">';
endif;
?>
<?php 
if($i == 2):
echo '<div class="dt-block-grid"><div class="dt_row">';
endif;
?>
<div class="post-item">
	<article class="post">
		<?php 
		if( has_post_thumbnail() ):?>
		<div class="entry-featured <?php echo get_post_format() == 'video' ? 'video-featured' : '' ?>">
			<a class="post-thumbnail-link dt-image-link" href="<?php the_permalink(); ?>" aria-hidden="true">
				<?php the_post_thumbnail( 'post-thumbnail', array( 'alt' => the_title_attribute( 'echo=0' ) ) ); ?>
				<?php echo get_post_format() == 'video' ? '<div class="dt-icon-video"></div>' : '' ?>
			</a>
		</div>
		<?php 
		endif;?>
		<div class="hentry-wrap">
			<header class="post-header">
				<?php	
				the_title( '<h2 class="post-title" data-itemprop="name"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
				?>
			</header><!-- .entry-header -->
			<div class="entry-meta">
				<div class="entry-meta-content">
					<?php
					printf('<span class="byline"><span class="author vcard">%1$s <a class="url fn n" href="%2$s" rel="author">%3$s</a></span></span>',
						esc_html__('By','viem'),
						esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
						get_the_author()
					);
					?>
					<?php
					viem_dt_posted_on();
					?>
				</div>
				<div class="entry-meta__express">
					<?php viem_video_views_count(get_the_ID()); ?>
				</div>
			</div>
		</div><!-- .entry-meta -->
		 
	</article>
</div>
<?php 
if($i == 1):
echo '</div>'; // .dt-block-big
endif;
?>
<?php 
if($i == 5):
echo '</div></div>'; //.dt_row .dt-block-grid
endif;