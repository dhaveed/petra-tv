<?php
/**
 * The template part for displaying channel discussion
 *
 * @package Dawn
 */
?>
<?php 
// If comments are open or we have at least one comment, load up the comment template.
if ( comments_open() || get_comments_number() ) {
	comments_template();
}
?>

