<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/bbpress/bbpress.php' ) ) {
	include_once( get_template_directory() . '/bbpress/bbpress.php' );
}

/**
 * User Registration Form
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div class="x-bbp-general-form">
	<form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>" class="bbp-login-form">
		<fieldset class="bbp-form">
			<legend><?php _e( 'Create an Account', 'bbpress' ); ?></legend>

			<div class="bbp-template-notice">
				<p><?php _e( 'Your username must be unique, and cannot be changed later. We use your email address to email you a secure password and verify your account.', '__x__' ) ?></p>
			</div>

			<p class="bbp-username">
				<label for="user_login"><?php _e( 'Username', 'bbpress' ); ?>: </label>
				<input type="text" name="user_login" value="<?php bbp_sanitize_val( 'user_login' ); ?>" size="20" id="user_login" tabindex="<?php bbp_tab_index(); ?>" />
			</p>

			<p class="bbp-email">
				<label for="user_email"><?php _e( 'Email', 'bbpress' ); ?>: </label>
				<input type="text" name="user_email" value="<?php bbp_sanitize_val( 'user_email' ); ?>" size="20" id="user_email" tabindex="<?php bbp_tab_index(); ?>" />
			</p>

			<?php do_action( 'register_form' ); ?>

			<div class="bbp-submit-wrapper">

				<button type="submit" tabindex="<?php bbp_tab_index(); ?>" name="user-submit" class="button submit user-submit"><?php _e( 'Register', 'bbpress' ); ?></button>

				<?php bbp_user_register_fields(); ?>

			</div>
		</fieldset>
	</form>
</div>