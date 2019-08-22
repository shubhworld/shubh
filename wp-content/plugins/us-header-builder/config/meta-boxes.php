<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header Builder Meta Boxes changes.
 *
 * @var $config array Framework- and theme-defined metaboxes config
 *
 * @return array Changed config
 */

foreach ( $config as &$cfg ) {
	if ( $cfg['id'] === 'us_page_settings' ) {
		$cfg['fields'] = us_array_merge_insert(
			$cfg['fields'], array(
				'us_header_sticky_override' => array(
					'title' => __( 'Sticky Header', 'us' ),
					'type' => 'switch',
					'switch_text' => __( 'Override this setting', 'us' ),
					'std' => 0,
					'classes' => 'for_above',
					'show_if' => array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
				),
				'us_header_sticky' => array(
					'type' => 'checkboxes',
					'options' => array(
						'default' => __( 'On Desktops', 'us' ),
						'tablets' => __( 'On Tablets', 'us' ),
						'mobiles' => __( 'On Mobiles', 'us' ),
					),					
					'std' => array(),
					'classes' => 'for_above',
					'show_if' => array(
						array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
						'and',
						array( 'us_header_sticky_override', '=', '1' ),
					),
				),			
				'us_header_transparent_override' => array(
					'title' => __( 'Transparent Header', 'us' ),
					'type' => 'switch',
					'switch_text' => __( 'Override this setting', 'us' ),
					'std' => 0,
					'classes' => 'for_above',
					'show_if' => array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
				),
				'us_header_transparent' => array(
					'type' => 'checkboxes',
					'options' => array(
						'default' => __( 'On Desktops', 'us' ),
						'tablets' => __( 'On Tablets', 'us' ),
						'mobiles' => __( 'On Mobiles', 'us' ),
					),					
					'std' => array(),
					'classes' => 'for_above',
					'show_if' => array(
						array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
						'and',
						array( 'us_header_transparent_override', '=', '1' ),
					),
				),
				'us_header_shadow' => array(
					'title' => __( 'Header Shadow', 'us' ),
					'type' => 'switch',
					'switch_text' => __( 'Remove header shadow', 'us' ),
					'std' => 0,
					'classes' => 'for_above',
					'show_if' => array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
				),
				'us_header_sticky_pos' => array(
					'title' => __( 'Sticky Header Initial Position', 'us' ),
					'type' => 'select',
					'options' => array(
						'' => __( 'At the Top of this page', 'us' ),
						'bottom' => __( 'At the Bottom of the first content row', 'us' ),
						'above' => __( 'Above the first content row', 'us' ),
						'below' => __( 'Below the first content row', 'us' ),
					),
					'std' => '',
					'classes' => 'for_above',
					'show_if' => array( 'us_header_id', 'not in', array( '__defaults__', '' ) ),
				),
			), 'after', 'us_header_id'
		);
		break;
	}
}

return $config;