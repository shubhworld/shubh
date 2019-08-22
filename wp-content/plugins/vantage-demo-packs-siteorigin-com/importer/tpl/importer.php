<div class="wrap" id="siteorigin-importer">

	<div class="importer-modal">

		<div class="container">

			<div class="importer-header">
				<h2>
					<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/siteorigin.svg' ?>" class="siteorigin-logo" />
					<?php _e( 'SiteOrigin Site Pack', 'siteorigin-importer' ) ?>
				</h2>
			</div>

			<div class="frames">
				<div class="frame" data-index="0">
					<div class="site-information">
						<img src="<?php echo esc_url( $screenshot ) ?>" />
						<div class="site-information-text">
							<h3><?php echo esc_html( $import_data['options']['blogname'] ) ?></h3>

							<ul class="import-attributes">
								<li>
									<strong><?php _e( 'Original URL', 'siteorigin-importer' ) ?></strong>:
									<a href="<?php echo esc_url( $import_data['site_url'] )  ?>" target="_blank">
										<?php echo esc_url( $import_data['site_url'] )  ?>
									</a>
								</li>
								<li>
									<strong><?php _e( 'Theme', 'siteorigin-importer' ) ?></strong>:
									<a href="<?php echo esc_url( $import_data[ 'theme_uri' ] )  ?>" target="_blank">
										<?php echo esc_html( $import_data[ 'theme_name' ] )  ?>
									</a>
								</li>

								<li>
									<strong><?php _e( 'Importer Actions', 'siteorigin-importer' ) ?></strong>:
									<?php echo count( $actions )  ?>
								</li>
							</ul>
						</div>
					</div>

					<div class="import-note">
						<p>
							<strong><?php _e( 'Warning', 'siteorigin-importer' ) ?></strong>:
							<?php _e( 'This importer will overwrite your existing installation.', 'siteorigin-importer' ) ?>
							<?php _e( "You'll lose all existing content.", 'siteorigin-importer' ) ?>
							<?php _e( "You should only run this on new installations.", 'siteorigin-importer' ) ?>
							<?php _e( "Type <strong>Accept</strong> in the field below to continue.", 'siteorigin-importer' ) ?>
						</p>
					</div>

					<div class="accept-box">
						<div>
							<input type="text" id="import-accept" />
						</div>

						<button class="button-primary" id="start-import" disabled><?php _e( 'Start Import', 'siteorigin-importer' ) ?></button>
						<a class="button-secondary" id="start-import" href="<?php echo admin_url('/') ?>"><?php _e( 'Not Now', 'siteorigin-importer' ) ?></a>
					</div>
				</div>

				<div class="frame" data-index="1">
					<div class="progress-bar-wrapper">
						<div class="progress-bar">
							<div class="progress-bar-progress"></div>
						</div>
						<div class="progress-message">
						</div>
						<div class="complete-message">
							<p><?php _e( 'Import complete!', 'siteorigin-importer' ) ?></p>
							<a href="<?php echo site_url( '/' ) ?>" class="button button-small"><?php _e( 'Visit Your Site', 'siteorigin-importer' ) ?></a>
							<a href="<?php echo admin_url( '/' ) ?>" class="button button-small"><?php _e( 'Return to Dashboard', 'siteorigin-importer' ) ?></a>
						</div>
					</div>

					<div class="import-note">
						<p>
							<?php _e( "We're building your site.", 'siteorigin-importer' ) ?>
							<?php _e( "Here's a video to watch while you wait.", 'siteorigin-importer' ) ?>
							<?php _e( "It won't interrupt your progress.", 'siteorigin-importer' ) ?>
						</p>

						<?php
						$video = $videos[ array_rand( $videos ) ];
						?>
						<script src="//fast.wistia.com/embed/medias/<?php echo esc_attr( $video['id'] ) ?>.jsonp" async></script><script src="//fast.wistia.com/assets/external/E-v1.js" async></script><span class="wistia_embed wistia_async_<?php echo esc_attr( $video['id'] ) ?> popover=true popoverAnimateThumbnail=true" style="display:inline-block;height:360px;width:640px">&nbsp;</span>
							<div class="video-sub-text">
							<?php echo $video['sub_text'] ?>
						</div>

					</div>
				</div>

			</div>

		</div>

	</div>

</div>