<?php

if ( ! function_exists ( 'wpinked_so_admin_style' ) ) :
// Enqueueing Backend style sheet.
function wpinked_so_admin_style() {

	wp_register_style( 'iw-admin-css', plugin_dir_url( __FILE__ ) . '../css/admin.min.css', array(), INKED_SO_VER );
	wp_register_script( 'iw-admin-js', plugin_dir_url( __FILE__ ) . '../js/admin.js', array(), INKED_SO_VER, true );
	wp_register_script( 'iw-admin-icons-js', plugin_dir_url( __FILE__ ) . '../js/admin-icons.js', array(), INKED_SO_VER, true );
	wp_enqueue_style( 'iw-dashboard-css', plugin_dir_url( __FILE__ ) . '../css/dashboard.css', array(), INKED_SO_VER);

}
endif;
add_action( 'admin_enqueue_scripts', 'wpinked_so_admin_style' );

if ( ! function_exists ( 'wpinked_so_styles' ) ) :
// Enqueueing Frontend style sheet.
function wpinked_so_styles() {

	wp_enqueue_style( 'iw-defaults', plugin_dir_url(__FILE__) . '../css/defaults.css', array(), INKED_SO_VER );
	wp_register_style( 'iw-slick', plugin_dir_url(__FILE__) . '../css/slick.css', array(), INKED_SO_VER );
	wp_register_style( 'iw-lity', plugin_dir_url(__FILE__) . '../css/lity.css', array(), INKED_SO_VER );

	wp_register_script( 'iw-waypoints-js', plugin_dir_url(__FILE__) . '../js/waypoints' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-countto-js', plugin_dir_url(__FILE__) . '../js/countto' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-easypie-js', plugin_dir_url(__FILE__) . '../js/easypie' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-mixitup-js', plugin_dir_url(__FILE__) . '../js/mixitup' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-match-height-js', plugin_dir_url(__FILE__) . '../js/match-height' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-slick-js', plugin_dir_url(__FILE__) . '../js/slick' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );
	wp_register_script( 'iw-lity-js', plugin_dir_url(__FILE__) . '../js/lity' . INKED_JS_SUFFIX . '.js', array( 'jquery' ), INKED_SO_VER, true );

}
endif;
add_action( 'wp_enqueue_scripts', 'wpinked_so_styles' );
