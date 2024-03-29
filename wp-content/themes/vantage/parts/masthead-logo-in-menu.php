<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/parts/parts.php' ) ) {
	include_once( get_template_directory() . '/parts/parts.php' );
}
/**
 * Part Name: Logo In Menu
 */
?>

<header id="masthead" class="site-header masthead-logo-in-menu" role="banner">

	<?php get_template_part( 'parts/menu', apply_filters( 'vantage_menu_type', siteorigin_setting( 'layout_menu' ) ) ); ?>

</header><!-- #masthead .site-header -->
