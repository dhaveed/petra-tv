<?php
if ( ! comments_open() || post_password_required()  ) {
	return;
}
$count = viem_posttype_class::get_review_count();
ob_start();
?>
<div id="viem_class_reviews" class="class-reviews-area">
	<?php if ($count):?>
	<div id="class_review_wrap" class="comments-area">
		<h3 class="class-reviews-title default-heading"><?php
			if ( viem_posttype_class::is_enable_review() )
				printf( _n( '%s review', '%s reviews', $count, 'viem' ), $count, '<span>', get_the_title(), '</span>' );
			else
				esc_html_e( 'Reviews', 'viem' );
		?></h3>
		<?php if ( have_comments() ) : ?>

			<ol class="comment-list">
				<?php
				wp_list_comments(apply_filters('viem_class_review_list_args', array(
					'callback'	 => array('viem_posttype_class','list_reviews')
				) ) );
				?>
			</ol>

			<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
			<div class="paginate comment-paginate">
				<div class="paginate_links">
					<?php paginate_comments_links()?>
				</div>
			</div>
			<?php endif; ?>

		<?php else : ?>

			<p class="class-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'viem' ); ?></p>

		<?php endif; ?>
	</div>
	<?php endif;?>
	<div id="class_review_form_wrapper">
		<div id="class_review_form">
			<?php
				$commenter = wp_get_current_commenter();

				$comment_form = array(
					'title_reply'          => have_comments() ? esc_html__( 'Add a review', 'viem' ) : sprintf( __ ( 'Be the first to review &ldquo;%s&rdquo;', 'viem' ), get_the_title() ),
					'title_reply_to'       => __ ( 'Leave a Reply to %s', 'viem' ),
					'comment_notes_after'  => '',
					'fields'               => array(
						'author' => '<p class="comment-form-author">' .
									'<input class="form-control" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" required required placeholder="'.esc_attr__( 'Name *', 'viem' ).'"/></p>',
						'email'  => '<p class="comment-form-email">' .
									'<input class="form-control" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" required required placeholder="'.esc_attr__( 'Email *', 'viem' ).'"/></p>',
					),
					'label_submit'  => __ ( 'Submit', 'viem' ),
					'logged_in_as'  => '',
					'comment_field' => ''
				);

				$comment_form['comment_field'] = '<p class="comment-form-rating"><label for="rating">' . esc_html__( 'Your Rating', 'viem' ) .'</label><p class="class-stars-review"><span><a class="star-1" href="#">1</a><a class="star-2" href="#">2</a><a class="star-3" href="#">3</a><a class="star-4" href="#">4</a><a class="star-5" href="#">5</a></span></p><select class="form-control" name="rating" id="rating" aria-required="true" required>
					<option value="">' . __ ( 'Rate&hellip;', 'viem' ) . '</option>
					<option value="5">' . esc_html__( 'Perfect', 'viem' ) . '</option>
					<option value="4">' . esc_html__( 'Good', 'viem' ) . '</option>
					<option value="3">' . esc_html__( 'Average', 'viem' ) . '</option>
					<option value="2">' . esc_html__( 'Not that bad', 'viem' ) . '</option>
					<option value="1">' . esc_html__( 'Very Poor', 'viem' ) . '</option>
				</select></p>';

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><textarea class="form-control" id="comment" name="comment" cols="45" rows="8" aria-required="true" required required placeholder="'.esc_attr__( 'Review *', 'viem' ).'"></textarea></p>';

				comment_form( apply_filters( 'viem_class_review_comment_form_args', $comment_form ) );
			?>
		</div>
	</div>
</div>