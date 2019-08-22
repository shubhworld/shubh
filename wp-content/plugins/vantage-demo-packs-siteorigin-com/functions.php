<?php
/**
 * This file is only ever loaded when this plugin is accidently installed as a theme.
 */
function siteorigin_importer_theme_admin_notice() {
	?>
	<div class="error">
		<p>
			<?php _e( "<strong>Warning</strong>: You're trying to use this <strong>SiteOrigin Importer</strong> as a theme. You need to deactivate it and reinstall it as a plugin by navigating to Plugins > Add New.", 'siteorigin-importer' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'siteorigin_importer_theme_admin_notice' );