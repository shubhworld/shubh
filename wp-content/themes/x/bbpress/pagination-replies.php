<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/bbpress/bbpress.php' ) ) {
	include_once( get_template_directory() . '/bbpress/bbpress.php' );
}

/**
 * Pagination for pages of replies (when viewing a topic)
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php if ( x_bbpress_show_reply_pagination() ) : ?>

	<?php do_action( 'bbp_template_before_pagination_loop' ); ?>

	<div class="bbp-pagination x-pagination">
		<div class="bbp-pagination-links">

			<?php bbp_topic_pagination_links(); ?>

		</div>
	</div>

	<?php do_action( 'bbp_template_after_pagination_loop' ); ?>

<?php endif; ?>