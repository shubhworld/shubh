<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/bbpress/bbpress.php' ) ) {
	include_once( get_template_directory() . '/bbpress/bbpress.php' );
}

/**
 * Merge Topic
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<div id="bbpress-forums">

	<?php bbp_breadcrumb(); ?>

	<?php if ( is_user_logged_in() && current_user_can( 'edit_topic', bbp_get_topic_id() ) ) : ?>

		<div id="merge-topic-<?php bbp_topic_id(); ?>" class="bbp-topic-merge x-bbp-general-form">

			<form id="merge_topic" name="merge_topic" method="post" action="<?php the_permalink(); ?>">

				<fieldset class="bbp-form">

					<legend><?php printf( __( 'Merge topic "%s"', 'bbpress' ), bbp_get_topic_title() ); ?></legend>

					<div>

						<div class="bbp-template-notice error">
							<p><?php _e( '<strong>WARNING:</strong> This process cannot be undone. Select the topic to merge this one into. The destination topic will remain the lead topic, and this one will change into a reply. To keep this topic as the lead, go to the other topic and use the merge tool from there instead. All replies within both topics will be merged chronologically. The order of the merged replies is based on the time and date they were posted. If the destination topic was created after this one, it\'s post date will be updated to second earlier than this one.', '__x__' ); ?></p>
						</div>

						<p>
							<?php if ( bbp_has_topics( array( 'show_stickies' => false, 'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ), 'post__not_in' => array( bbp_get_topic_id() ) ) ) ) : ?>

								<label for="bbp_destination_topic"><?php _e( 'Merge with this topic:', 'bbpress' ); ?></label>

								<?php
									bbp_dropdown( array(
										'post_type'   => bbp_get_topic_post_type(),
										'post_parent' => bbp_get_topic_forum_id( bbp_get_topic_id() ),
										'selected'    => -1,
										'exclude'     => bbp_get_topic_id(),
										'select_id'   => 'bbp_destination_topic'
									) );
								?>

							<?php else : ?>

								<label><?php _e( 'There are no other topics in this forum to merge with.', 'bbpress' ); ?></label>

							<?php endif; ?>

						</p>

						<?php if ( bbp_is_subscriptions_active() ) : ?>

							<p>
								<input name="bbp_topic_subscribers" id="bbp_topic_subscribers" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
								<label for="bbp_topic_subscribers"><?php _e( 'Merge topic subscribers', 'bbpress' ); ?></label><br />
							</p>

						<?php endif; ?>

						<p>
							<input name="bbp_topic_favoriters" id="bbp_topic_favoriters" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
							<label for="bbp_topic_favoriters"><?php _e( 'Merge topic favoriters', 'bbpress' ); ?></label><br />
						</p>

						<?php if ( bbp_allow_topic_tags() ) : ?>

							<p>
								<input name="bbp_topic_tags" id="bbp_topic_tags" type="checkbox" value="1" checked="checked" tabindex="<?php bbp_tab_index(); ?>" />
								<label for="bbp_topic_tags"><?php _e( 'Merge topic tags', 'bbpress' ); ?></label><br />
							</p>

						<?php endif; ?>

						<div class="bbp-submit-wrapper">
							<button type="submit" tabindex="<?php bbp_tab_index(); ?>" id="bbp_merge_topic_submit" name="bbp_merge_topic_submit" class="button submit"><?php _e( 'Submit', 'bbpress' ); ?></button>
						</div>
					</div>

					<?php bbp_merge_topic_form_fields(); ?>

				</fieldset>
			</form>
		</div>

	<?php else : ?>

		<div id="no-topic-<?php bbp_topic_id(); ?>" class="bbp-no-topic">
			<div class="entry-content"><?php is_user_logged_in() ? _e( 'You do not have the permissions to edit this topic!', 'bbpress' ) : _e( 'You cannot edit this topic.', 'bbpress' ); ?></div>
		</div>

	<?php endif; ?>

</div>
