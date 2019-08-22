<?php

/**
 * Class Inked_Widget_Field_Premium
 */
class Inked_Widget_Field_Premium extends SiteOrigin_Widget_Field_Base {

	protected function render_field( $value, $instance ) {
		?>

		<?php
	}

	protected function sanitize_field_input( $value, $instance ) {
		return ! empty( $value );
	}

}
