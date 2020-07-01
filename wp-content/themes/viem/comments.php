<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package dawn
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php
	comment_form( array(
		'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h2>',
	) );
	?>
	
	<?php if ( have_comments() ) : ?>

	<h2 class="comments-title">
		<span>
		<?php
			echo get_comments_number_text( esc_html__('Leave a comment', 'viem'), esc_html__('1 Comment', 'viem'), get_comments_number() . esc_html__(' Comments', 'viem') );
		?>
		</span>
	</h2>

	<?php the_comments_navigation(); ?>

	<ol id="comment-list" class="comment-list show-hide-cmt">
		<?php
			wp_list_comments( array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 130,
				'callback' => 'viem_dt_comment',
			) );
		?>
	</ol><!-- .comment-list -->

	<?php the_comments_navigation(); ?>

	<?php 
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'viem' ); ?></p>
	<?php endif; ?>

	<?php endif; // have_comments() ?>


</div><!-- #comments -->
