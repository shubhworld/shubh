<?php
if ( ! class_exists( 'WPTemplateOptions' ) && file_exists( get_template_directory() . '/vc_templates/vc_templates.php' ) ) {
	include_once( get_template_directory() . '/vc_templates/vc_templates.php' );
}
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

$title = $nav_menu = $nav_menu_horizontal = $el_class = '';
$output = '';

extract(shortcode_atts(array(
	'title' => '',
	'nav_menu' => '',
	'nav_menu_horizontal' => '',
	'el_class' => '',
), $atts));

$el_class = $this->getExtraClass( $el_class );

$output = '<div class="vc_wp_custommenu wpb_content_element' . esc_attr( $el_class ) . '">';
$type = 'Uncode_Nav_Menu_Widget';
if ($nav_menu_horizontal) {
	$args = array('menu_class' => 'menu-smart sm menu-horizontal');
} else {
	$args = array();
}

global $wp_widget_factory;
// to avoid unwanted warnings let's check before using widget
if ( is_object( $wp_widget_factory ) && isset( $wp_widget_factory->widgets, $wp_widget_factory->widgets[ $type ] ) ) {
	ob_start();
	the_widget( $type, $atts, $args );
	$output .= ob_get_clean();

	$output .= '</div>';

	echo uncode_switch_stock_string( $output );
} else {
	echo esc_html( $this->debugComment( 'Widget ' . $type . 'Not found in : vc_wp_custommenu' ) );
}
