<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Output elements list to choose from
 *
 * @var $body string Optional predefined body
 */
global $ushb_uri;

$templates = us_config( 'header-templates', array() );
if ( ! isset( $body ) ) {
	$body = '<ul class="us-bld-window-list">';
	foreach ( $templates as $name => $template ) {
		$template_title = isset( $template['title'] ) ? $template['title'] : ucfirst( $name );
		$template = us_fix_header_template_settings( $template );
		// Hiding text logo as we'll use only the image logo
		foreach ( array( 'default', 'tablets', 'mobiles' ) as $layout ) {
			foreach ( $template[ $layout ]['layout'] as $cell => $cell_elms ) {
				if ( $cell == 'hidden' ) {
					continue;
				}
				if ( ( $elm_pos = array_search( 'text:1', $cell_elms ) ) !== FALSE ) {
					array_splice( $template[ $layout ]['layout'][ $cell ], $elm_pos, 1 );
					$template[ $layout ]['layout']['hidden'][] = 'text:1';
					break;
				}
			}
		}
		$body .= '<li data-name="' . esc_attr( $name ) . '" class="us-bld-window-item type_htemplate ' . $name . '">';
		$body .= '<div class="us-bld-window-item-h">';
		$body .= '<div class="us-bld-window-item-icon">';
		$body .= '<img src="' . $ushb_uri . '/admin/img/header-templates/' . $name . '.jpg" />';
		$body .= '</div>';
		$body .= '<div class="us-bld-window-item-title">' . $template_title . '</div>';
		$body .= '<div class="us-bld-window-item-data"' . us_pass_data_to_js( $template ) . '></div>';
		$body .= '</div>';
		$body .= '</li>';
	}
	$body .= '</ul>';
}

$output = '<div class="us-bld-window for_templates"><div class="us-bld-window-h">';
$output .= '<div class="us-bld-window-header"><div class="us-bld-window-title">' . __( 'Header Templates', 'us' ) . '</div><div class="us-bld-window-closer" title="' . us_translate( 'Close' ) . '"></div></div>';
$output .= '<div class="us-bld-window-body">';
$output .= $body;
$output .= '<span class="usof-preloader"></span>';
$output .= '</div>';
$output .= '</div></div>';

echo $output;
