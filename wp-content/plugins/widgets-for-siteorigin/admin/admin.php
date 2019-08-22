<?php
/**
 * Weclome Page Class
 *
 * @since       1.3.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists ( 'wpinked_so_admin_page' ) ) :
function wpinked_so_admin_page() {
	add_menu_page(
		'Widgets for SiteOrigin',
		__( 'WPinked Widgets', 'wpinked-widgets' ),
		'manage_options',
		'wpinked-widgets',
		'wpinked_so_admin_page_content',
		plugin_dir_url(__FILE__) . 'img/menu-icon.png',
		99
	);

	add_submenu_page(
		'wpinked-widgets',
		'Welcome to Widgets for SiteOrigin',
		__( 'Get Addons', 'wpinked-widgets' ),
		'manage_options',
		'wpinked-widgets',
		'wpinked_so_admin_page_content'
	);
}
endif;
add_action( 'admin_menu', 'wpinked_so_admin_page' );


if ( ! function_exists ( 'wpinked_so_admin_page_content' ) ) :
function wpinked_so_admin_page_content() {
	wp_enqueue_style( 'iw-admin-css' );
	wp_enqueue_script( 'iw-admin-js' );
	wp_enqueue_script( 'iw-admin-icons-js' );
	?>
	<style>
	.main-buttons { margin-top: 40px; }
	.main-buttons .uk-button { background: #fff; margin-bottom: 15px; border-width: 0px; }
	.main-buttons .uk-button:hover { background: #87cefa; color: #fff; }
	.ink-boxes h4 { font-size: 15px }
	.ink-boxes .uk-icon { padding-right: 15px; }
	.ink-boxes .uk-card-footer, .ink-boxes .uk-card-header { border-width: 0 !important; }
	.ink-boxes .uk-card-body { padding: 20px 40px }
	.ink-boxes .uk-card-footer a { font-size: 12px; background: #3EB0F7; color: #fff; text-decoration: none; padding: 8px 15px; }
	.sale-notice { background: #fff; padding: 20px 30px; margin-bottom: 50px !important; border-left: 10px solid #3EB0F7 }
	.sale-notice p { font-size: 14px; margin: 0; }
	.sale-notice p .uk-icon { color: #3EB0F7; position: relative; bottom: 3px;}
	.uk-updates { margin-bottom: 50px; }
	.uk-updates h4 { font-size: 13px; }
	.uk-updates .uk-grid .uk-card { padding: 15px; background: #fff }
	.uk-updates .uk-grid .uk-card a { width: 100%; height: 100%; display: inline-block; text-decoration: none !important; }
	.uk-updates .uk-grid .uk-card p { font-size: 11px; color: #333; max-width: 100px; border-bottom: 2px solid transparent; }
	.uk-updates .uk-grid .uk-card { border: 2px solid transparent; }
	.uk-updates .uk-grid .uk-card:hover { border: 2px solid #D0ECFD; }
	.toplevel_page_wpinked-widgets #wpcontent { background: #f1f1f1; }
	.uk-tile { display: none; }
	.upgrade-plugin-form .uk-tile { display: block; }
	.ink-boxes .uk-card {
		padding: 25px;
		height: 100%;
	}
	.ink-boxes .uk-card .box-icon {
		color: #3eb0f7;
	}
	.ink-boxes .uk-card .card-btns a {
		font-size: 12px;
		background: #3EB0F7;
		color: #fff;
		text-decoration: none;
		padding: 8px 15px;
		margin-bottom: 5px;
	}
	@media (max-width: 1200px) {
		.main-buttons .uk-button {
			line-height: 35px;
			font-size: .75rem;
			padding: 0 20px;
		}
	}
	</style>
	<div class="uk-section uk-padding-remove wpinked-widgets-admin-wrapper" style="background: #f1f1f1;">
		<div class="uk-container uk-container-expand">
			<div uk-grid>
				<div class="uk-width-2-3@m">
					<div class="main-buttons">
						<a class="uk-button uk-button-default uk-button-large" href="<?php echo admin_url( 'admin.php?page=wpinked-widgets' ); ?>">General</a>
						<a class="uk-button uk-button-default uk-button-large" href="<?php echo admin_url( 'plugins.php?page=so-widgets-plugins' ); ?>">Manage Widgets</a>
						<a class="uk-button uk-button-default uk-button-large" href="http://widgets.wpinked.com" target="_blank">Demo</a>
						<a class="uk-button uk-button-default uk-button-large" href="http://widgets.wpinked.com/docs/" target="_blank">Docs</a>
					</div>
					<div class="uk-child-width-1-1 uk-grid-match uk-grid-small" uk-grid>
						<div>
							<img class="uk-box-shadow-medium uk-width-1-1 uk-height-1-1" data-src="<?php echo plugin_dir_url(__FILE__); ?>img/plugin-banner.png" width="" height="" alt="" uk-img>
						</div>
						<div>
							<div uk-grid class="uk-child-width-1-3@l uk-grid-small ink-boxes">
								<div>
									<div class="uk-card uk-card-default">
										<p class="uk-text-center box-icon"><span uk-icon="icon: info; ratio: 2"></span></p>
										<h4 class="uk-card-title uk-text-center uk-margin-remove-bottom uk-text-uppercase">Documentation</h4>
										<p class="uk-text-center">Have a look at our documentation to get to know our widgets. Build awesome pages with page builders.</p>
										<p class="uk-text-center card-btns">
											<a href="http://widgets.wpinked.com/docs/" target="_blank" class="uk-button uk-button-text uk-button-small">See now</a>
										</p>
									</div>
								</div>
								<div>
									<div class="uk-card uk-card-default">
										<p class="uk-text-center box-icon"><span uk-icon="icon: question; ratio: 2"></span></p>
										<h4 class="uk-card-title uk-text-center uk-margin-remove-bottom uk-text-uppercase">Need Help</h4>
										<p class="uk-text-center">Need help troubleshooting. Get help at the <a href="https://wordpress.org/support/plugin/widgets-for-siteorigin" target="_blank" >WordPress Support Forum</a>. Pro users can email us at <a href="mailto:support@wpinked.com">support@wpinked.com</a>.</p>
										<p class="uk-text-center card-btns">
											<a href="https://wordpress.org/support/plugin/widgets-for-siteorigin" target="_blank" class="uk-button uk-button-small uk-button-text">Get Support</a>
											<a href="mailto:support@wpinked.com" class="uk-button uk-button-small uk-button-text">Email Support</a>
										</p>
									</div>
								</div>
								<div>
									<div class="uk-card uk-card-default">
										<p class="uk-text-center box-icon"><span uk-icon="icon: heart; ratio: 2"></span></p>
										<h4 class="uk-card-title uk-text-center uk-margin-remove-bottom uk-text-uppercase">Rate the plugin</h4>
										<p class="uk-text-center">If you liked your experience with Widgets for SiteOrigin please take a couple of minutes to leave a review. Thanks.</p>
										<p class="uk-text-center card-btns">
											<a href="https://wordpress.org/support/plugin/widgets-for-siteorigin/reviews/#new-post" target="_blank" class="uk-button uk-button-small uk-button-text">Review Now</a>
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="uk-width-1-3@m">
					<div class="uk-padding uk-background-secondary uk-light">
						<style>
							#add-to-cart { background: #3EB0F7; max-width: 350px; border-radius: 3px; }
							#add-to-cart h2 { font-size: 18px; background: #098ddf; padding: 30px; color: #fff; margin-bottom: 30px; font-weight: 300; border-radius: 3px; }
							#add-to-cart .bare-list { list-style: none; margin: 30px; padding: 20px 0; }
							.wpinked-plugin-logo { width: 180px; height: auto; }
						</style>
						<center><img class="wpinked-plugin-logo" data-src="<?php echo plugin_dir_url(__FILE__); ?>img/logo-plugin.png" width="" height="" alt="" uk-img></center>
						<h3 class="uk-text-center">Widgets for SiteOrigin Premium</h3>
						<p class="uk-text-center pro-buttons">
							<a class="license-button uk-button uk-button-default buy-button" href="https://wpinked.com/plugins/widgets-for-siteorigin/" target="_blank">Get it now</a>
						</p>
						<p class="uk-text-center uk-text-meta uk-margin-remove"><i>( Free Trial Available )</i></p>
						<ul class="uk-list">
							<li>Widget Animations</li>
							<li>Enhanced Blog Widget</li>
							<li>Advanced Info Box Widget</li>
							<li>Blog Slider Widget</li>
							<li>Portfolio Enhanced Widget</li>
							<li>Person Slider Widget</li>
							<li>Testimonial Slider Widget</li>
							<li>Chart Widget</li>
							<li>Content Toggle Widget</li>
							<li>Flip Carousel Widget</li>
							<li>Gallery Widget</li>
							<li>Slider Pro Widget</li>
							<li>Flip Box Widget</li>
							<li>Image Compare Widget</li>
							<li>WooCommerce Products Widget</li>
							<li>Service Box Widget</li>
							<li>Vertical Bar Counter Widget</li>
							<li>Price List Widget</li>
						</ul>
						<p class="uk-text-center uk-text-meta"><i>More on the way....</i></p>
						<style>
						.uk-list li {
							text-align: center;
							font-style: italic;
						}
						.pro-buttons {
							margin-bottom: 0;
						}
						.pro-buttons .wp-button {
							color: #222;
							border-radius: 3px;
							margin: 15px 0;
						}
						.pro-buttons .license-button {
							color: #fff;
							border: 3px solid #fff;
							background: #56bbf8;
							border-color: #56bbf8;
						}
						.pro-buttons .license-button:hover {
							background: #3eb0f7;
    						border-color: #3eb0f7;
							color: #fff;
						}
						.pro-buttons .license-button .uk-icon {
							position: relative;
							bottom: 2px;
						}
						@media (max-width: 1580px) {
							.pro-buttons .uk-button {
								width: 100%;
								margin: 10px 0;
							}
						}
						</style>
					</div>
				</div>
			</div>
		</div>
	</div>
	<style>
	.services-offered {
		background: #f9f9f9;
	}
	.services-offered .text-section img {
		margin-top: 40px;
	}
	.services-offered .text-section h4 {
		font-size: 40px;
		margin-top: 0;
	}
	.services-offered .text-section p {
		font-size: 15px;
	}
	.services-offered .service-block {
		background: #fff;
		height: 100%;
		transition: all 0.5s ease;
	}
	.services-offered .service-block .uk-card-icon {
		color: #d7302e;
	}
	.services-offered .service-block .uk-card-title {
		color: #222;
		margin-top: 0;
	}
	.services-offered .service-block .uk-card-desc {
		color: #222;
	}
	.services-offered .service-block:hover {
		box-shadow: 0 28px 50px rgba(0,0,0,0.16);
	}
	.services-offered .service-block a:hover {
		text-decoration: none;
	}
	.services-offered .service-block a:hover .uk-card-title {
		color: #d7302e;
	}
	</style>
	<div class="uk-section">
		<div class="uk-container uk-container-expand services-offered uk-padding">
			<div uk-grid>
				<div class="uk-width-1-3@l text-section">
					<p class="uk-text-right@l uk-text-center">
						<a href="https://wpinked.com" target="_blank">
							<img data-src="<?php echo plugin_dir_url(__FILE__); ?>img/logo.png" uk-img>
						</a>
					</p>
					<h4 class="uk-text-uppercase uk-text-right@l uk-text-center">Our Services</h4>
					<p class="uk-text-right@l uk-text-center"><i>We ship success. How can we help you?</i></p>
				</div>
				<div class="uk-width-1-2@s uk-width-1-3@l service-section">
					<div class="service-block uk-card uk-card-body uk-margin-remove uk-box-shadow-medium">
						<a href="https://wpinked.com/development/" target="_blank">
							<p class="uk-card-icon uk-text-center"><span uk-icon="icon: code; ratio: 2" class="uk-icon"></span></p>
							<h2 class="uk-card-title uk-text-center">WordPress Development</h2>
							<p class="uk-card-desc uk-text-center">High quality code, with care taken for even the smallest details. We focus on fast, secure, adn reliable websites.</p>
						</a>
					</div>
				</div>
				<div class="uk-width-1-2@s uk-width-1-3@l service-section">
					<div class="service-block uk-card uk-card-body uk-margin-remove uk-box-shadow-medium">
						<a href="https://wpinked.com/maintenance/" target="_blank">
							<p class="uk-card-icon uk-text-center"><span uk-icon="icon: database; ratio: 2" class="uk-icon"></span></p>
							<h2 class="uk-card-title uk-text-center">WordPress Maintenance</h2>
							<p class="uk-card-desc uk-text-center">Focus on your business, while we take care of your website. We’ll take care of updates, backups, security scans and much more.</p>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
endif;