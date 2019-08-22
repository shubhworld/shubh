<?php
/*
Plugin Name: SiteOrigin Importer [Vantage]
Description: Import a site pack into your WordPress install.
Author: SiteOrigin
Author URI: https://siteorigin.com
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
Donate link: http://siteorigin.com/page-builder/#donate
*/

if( ! class_exists( 'SiteOrigin_Importer' ) ) {
	include plugin_dir_path( __FILE__ ) . 'importer/importer.class.php';
}

new SiteOrigin_Importer( __FILE__ );