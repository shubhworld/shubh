<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Plugin Name: Header Builder
 * Plugin URI: https://us-themes.com/
 * Description: UpSolution Themes addon that allows to create website headers with custom layout and any elements.
 * Author: UpSolution
 * Author URI: https://us-themes.com/
 * Version: 2.5.1
 **/

// Define current themes version compatibility
function ushb_get_required_theme_version() {
	return array(
		'Impreza' => '6.0',
		'Zephyr' => '6.0',
	);
}

// Global variables for plugin usage
$ushb_file = __FILE__;
$ushb_dir = plugin_dir_path( __FILE__ );
$ushb_uri = plugins_url( '', __FILE__ );
if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$ushb_data = get_plugin_data( __FILE__ );
$ushb_version = $ushb_data['Version'] ? $ushb_data['Version'] : FALSE;
if ( $ushb_version ) {
	define( 'US_HB_VERSION', $ushb_version );
}
unset( $ushb_matches );

// Check current UpSolution theme compatibility with the plugin
add_action( 'after_setup_theme', 'ushb_check_theme_compatibility' );
function ushb_check_theme_compatibility() {
	if ( ! defined( 'US_THEMENAME' ) OR ! defined( 'US_THEMEVERSION' ) ) {
		return;
	}

	$ushb_required_theme_version = ushb_get_required_theme_version();

	if ( isset( $ushb_required_theme_version[ US_THEMENAME ] ) AND version_compare( US_THEMEVERSION, $ushb_required_theme_version[ US_THEMENAME ], '<' ) ) {
		add_action( 'admin_notices', 'ushb_check_theme_compatibility_error' );
	}
	function ushb_check_theme_compatibility_error() {
		$ushb_required_theme_version = ushb_get_required_theme_version();

		$output = '<div class="notice notice-warning us-addons-notice">';
		$output .= '<p>' . sprintf( __( 'Header Builder plugin requires %s version %s.', 'us' ), US_THEMENAME, $ushb_required_theme_version[ US_THEMENAME ] ) . ' <a href="' . admin_url( 'themes.php' ) . '">' . __( 'Update the theme', 'us' ) . '</a>.</p>';
		$output .= '</div>';

		echo $output;
	}
}

// Adding special plugin-related paths for admin area only (for performance reasons)
add_filter( 'us_files_search_paths', 'ushb_files_search_paths' );
function ushb_files_search_paths( $paths ) {
	global $ushb_dir;
	array_splice( $paths, 1, 0, $ushb_dir );

	return $paths;
}

// Ajax requests
if ( is_admin() AND isset( $_POST['action'] ) AND substr( $_POST['action'], 0, 5 ) == 'ushb_' ) {
	require $ushb_dir . 'functions/ajax.php';
}

add_filter( 'usof_container_classes', 'ushb_usof_container_classes' );
function ushb_usof_container_classes( $classes ) {
	return $classes . ' with_hb';
}

register_activation_hook( $ushb_file, 'ushb_activate' );
function ushb_activate() {
	if ( ! get_posts(
		array(
			'post_type' => 'us_header',
			'post_status' => 'any',
			'numberposts' => 1,
		)
	) ) {
		global $usof_options, $us_header_settings;
		remove_filter( 'us_load_header_settings', 'ushb_load_header_settings', 9 );
		if ( function_exists( 'usof_load_options_once' ) ) {
			usof_load_options_once();
		}
		us_load_header_settings_once();

		// Filling cells with missing keys
		foreach ( array( 'default', 'tablets', 'mobiles' ) AS $state ) {
			$us_header_settings[ $state ]['layout'] = us_get_header_layout( $state );
		}

		foreach ( $us_header_settings['data'] as $elm_key => $data ) {
			foreach ( $data as $data_key => $data_val ) {
				if ( is_array( $data_val ) ) {
					foreach ( $data_val as $data_subkey => $data_subval ) {
						if ( strpos( $data_subval, '"' ) !== FALSE ) {
							$us_header_settings['data'][ $elm_key ][ $data_key ][ $data_subkey ] = str_replace( '"', '\"', $data_subval );
						}
					}
				} elseif ( strpos( $data_val, '"' ) !== FALSE ) {
					$us_header_settings['data'][ $elm_key ][ $data_key ] = str_replace( '"', '\"', $data_val );
				}
			}
		}

		$header_post_array = array(
			'post_type' => 'us_header',
			'post_date' => date( 'Y-m-d H:i', time() ),
			'post_name' => 'site-header-1',
			'post_title' => sprintf( __( 'Site Header %d', 'us' ), 1 ),
			'post_content' => json_encode( $us_header_settings, JSON_UNESCAPED_UNICODE ),
			'post_status' => 'publish',
		);

		remove_action( 'init', 'ushb_create_post_types', 8 );
		ushb_create_post_types();

		$default_header_id = wp_insert_post( $header_post_array );

		$updated_options = $usof_options;
		$updated_options['header_id'] = $default_header_id;
		usof_save_options( $updated_options );
	}

}

register_uninstall_hook( $ushb_file, 'ushb_uninstall' );
function ushb_uninstall() {
	global $usof_options;
	usof_load_options_once();

	if ( isset( $usof_options['header'] ) ) {
		// Removing header builder setting
		$updated_options = $usof_options;
		unset( $updated_options['header'] );
		usof_save_options( $updated_options );
	}
}


add_filter( 'us_load_header_settings', 'ushb_load_header_settings', 9 );
function ushb_load_header_settings( $header_settings ) {
	global $us_header_id;
	remove_filter( 'us_load_header_settings', 'us_load_usof_header_settings' );
	if ( function_exists( 'us_get_public_cpt' ) ) {
		$public_cpt = array_keys( us_get_public_cpt() );
	} else {
		$public_cpt = array();
	}

	// Get Header ID from Theme Options
	$us_header_id = us_get_page_area_id( 'header' );

	// Override Header ID and its settings for certain post when they set in metabox
	$states = array( 'default', 'tablets', 'mobiles' );
	$override_options = array();
	if ( is_singular() ) {
		$postID = get_the_ID();
	}
	if ( is_404() ) {
		$postID = us_get_option( 'page_404' );
	}
	if ( is_search() AND ! is_post_type_archive( 'product' ) ) {
		$postID = us_get_option( 'search_page' );
	}
	if ( is_home() ) {
		$postID = us_get_option( 'posts_page' );
	}
	if ( ! empty( $postID ) AND $postID != 'default' ) {
		if ( usof_meta( 'us_header_id', array(), $postID ) != '__defaults__' ) {
			if ( class_exists( 'SitePress' ) AND defined( 'ICL_LANGUAGE_CODE' ) ) {
				global $sitepress;
				if ( $sitepress->get_default_language() != ICL_LANGUAGE_CODE ) {
					$original_postID = apply_filters( 'wpml_object_id', $postID, get_post_type( $postID ), TRUE, $sitepress->get_default_language() );
					if ( $original_postID != $postID ) {
						$us_header_id = usof_meta( 'us_header_id', array(), $original_postID );
						$us_header_id = apply_filters( 'wpml_object_id', $us_header_id, 'us_header', TRUE, ICL_LANGUAGE_CODE );
					}
				}
			} elseif ( function_exists( 'pll_default_language' ) AND pll_get_post( $postID ) ) {
				if ( pll_current_language() != pll_default_language() ) {
					$original_postID = pll_get_post( $postID, pll_default_language() );
					if ( $original_postID != $postID ) {
						$us_header_id = usof_meta( 'us_header_id', array(), $original_postID );
						$us_header_id = pll_get_post( $us_header_id );
					}
				}
			}
			if ( usof_meta( 'us_header_sticky_override', array(), $postID ) ) {
				$sticky_override = usof_meta( 'us_header_sticky', array(), $postID );
				if ( ! is_array( $sticky_override ) ) {
					$sticky_override = array();
				}
				foreach ( $states as $state ) {
					$override_options[ $state ]['options']['sticky'] = in_array( $state, $sticky_override );
				}
			}
			if ( usof_meta( 'us_header_transparent_override', array(), $postID ) ) {
				$transparent_override = usof_meta( 'us_header_transparent', array(), $postID );
				if ( ! is_array( $transparent_override ) ) {
					$transparent_override = array();
				}
				foreach ( $states as $state ) {
					$override_options[ $state ]['options']['transparent'] = in_array( $state, $transparent_override );
				}
			}
			if ( usof_meta( 'us_header_shadow', array(), $postID ) ) {
				foreach ( $states as $state ) {
					$override_options[ $state ]['options']['shadow'] = 'none';
				}
			}
		}
	}

	// Reset Header ID to Defaults if set
	if ( $us_header_id == '__defaults__' ) {
		$us_header_id = us_get_option( 'header_id' );
	}

	// Generate header settings from Header post content
	if ( $us_header_id != '' ) {
		if ( class_exists( 'SitePress' ) AND defined( 'ICL_LANGUAGE_CODE' ) ) {
			$us_header_id = apply_filters( 'wpml_object_id', $us_header_id, 'us_header', TRUE );
		} elseif ( function_exists( 'pll_default_language' ) AND pll_get_post( $us_header_id ) ) {
			$us_header_id = pll_get_post( $us_header_id );
		}
		$header = get_post( (int) $us_header_id );
		if ( $header instanceof WP_Post AND $header->post_type === 'us_header' ) {
			if ( ! empty( $header->post_content ) AND substr( strval( $header->post_content ), 0, 1 ) === '{' ) {
				try {
					$header_settings = json_decode( $header->post_content, TRUE );
				}
				catch ( Exception $e ) {
				}
			}
		}
		// Add Header ID to settings
		$header_settings['header_id'] = $us_header_id;

	} else {
		$header_settings['is_hidden'] = TRUE;
	}

	// Merge header settings with metabox settings
	$header_settings = us_array_merge( $header_settings, $override_options );

	return $header_settings;
}

add_action( 'init', 'ushb_create_post_types', 8 );
function ushb_create_post_types() {

	// Header post type
	register_post_type(
		'us_header', array(
			'labels' => array(
				'name' => _x( 'Headers', 'site top area', 'us' ),
				'singular_name' => _x( 'Header', 'site top area', 'us' ),
				'add_new' => _x( 'Add Header', 'site top area', 'us' ),
				'add_new_item' => _x( 'Add Header', 'site top area', 'us' ),
				'edit_item' => _x( 'Edit Header', 'site top area', 'us' ),
			),
			'public' => TRUE,
			'show_in_menu' => 'us-theme-options',
			'exclude_from_search' => TRUE,
			'show_in_admin_bar' => FALSE,
			'publicly_queryable' => FALSE,
			'show_in_nav_menus' => FALSE,
			'capability_type' => array( 'us_page_block', 'us_page_blocks' ),
			'map_meta_cap' => TRUE,
			'supports' => FALSE,
			'rewrite' => FALSE,
			'register_meta_box_cb' => 'ushb_us_header_type_pages',
		)
	);

	// Add "Used in" column into Headers admin page
	add_filter( 'manage_us_header_posts_columns', 'ushb_us_header_columns_head' );
	add_action( 'manage_us_header_posts_custom_column', 'ushb_us_header_columns_content', 10, 2 );

	function ushb_us_header_columns_head( $defaults ) {
		$result = array();
		foreach ( $defaults as $key => $title ) {
			if ( $key == 'date' ) {
				$result['used_in'] = __( 'Used in', 'us' );
			}
			$result[ $key ] = $title;
		}

		return $result;
	}

	function ushb_us_header_columns_content( $column_name, $post_ID ) {
		if ( $column_name == 'used_in' ) {
			if ( function_exists( 'us_get_used_in_locations' ) ) {
				echo us_get_used_in_locations( $post_ID );
			}
		}
	}
}

function ushb_us_header_type_pages() {
	global $post;
	// Dev note: This check is not necessary, but still wanted to make sure this function won't be bound somewhere else
	if ( ! ( $post instanceof WP_Post ) OR $post->post_type !== 'us_header' ) {
		return;
	}
	if ( $post->post_status === 'auto-draft' ) {
		// Page for creating new header: creating it instantly and proceeding to editing
		$post_data = array( 'ID' => $post->ID );
		// Retrieving occupied names to generate new post title properly
		$existing_headers = us_get_posts_titles_for( 'us_header' );
		if ( isset( $_GET['duplicate_from'] ) AND ( $original_post = get_post( (int) $_GET['duplicate_from'] ) ) !== NULL ) {
			// Handling post duplication
			$post_data['post_content'] = wp_slash( $original_post->post_content );
			$title_pattern = $original_post->post_title . ' (%d)';
			$cur_index = 2;
		} else {
			// Handling creation from scratch
			$title_pattern = __( 'Site Header %d', 'us' );
			$cur_index = count( $existing_headers ) + 1;
		}
		// Generating new post title
		while ( in_array( $post_data['post_title'] = sprintf( $title_pattern, $cur_index ), $existing_headers ) ) {
			$cur_index ++;
		}
		wp_update_post( $post_data );
		wp_publish_post( $post->ID );
		// Redirect
		if ( isset( $_GET['duplicate_from'] ) ) {
			// When duplicating post, showing posts list next
			wp_redirect( admin_url( 'edit.php?post_type=us_header' ) );
		} else {
			// When creating from scratch proceeding to post editing next
			wp_redirect( admin_url( 'post.php?post=' . $post->ID . '&action=edit' ) );
		}
	} else {
		// Page for editing a header
		add_action( 'admin_enqueue_scripts', 'ushb_enqueue_scripts' );
		add_action( 'edit_form_top', 'ushb_edit_form_top' );
	}
}

function ushb_enqueue_scripts() {
	// Appending dependencies
	usof_print_styles();
	usof_print_scripts();

	// Appending required assets
	global $ushb_uri, $ushb_version;
	$protocol = is_ssl() ? 'https' : 'http';
	wp_enqueue_style( 'us-header-builder', $ushb_uri . '/admin/css/header-builder.css', array(), $ushb_version );
	wp_enqueue_script( 'us-header-builder', $ushb_uri . '/admin/js/header-builder.js', array( 'usof-scripts' ), $ushb_version, TRUE );

	// Disabling WP auto-save
	wp_dequeue_script( 'autosave' );
}

function ushb_edit_form_top( $post ) {
	echo '<div class="usof-container type_builder';
	if ( get_option( 'us_license_activated', 0 ) OR ( defined( 'US_DEV' ) AND US_DEV ) OR defined( 'US_THEME_BETA' ) ) {
		echo ' theme_activated';
	}
	echo '" data-ajaxurl="' . esc_attr( admin_url( 'admin-ajax.php' ) ) . '" data-id="' . esc_attr( $post->ID ) . '">';
	echo '<form class="usof-form" method="post" action="#" autocomplete="off">';
	// Output _nonce and _wp_http_referer hidden fields for ajax secuirity checks
	wp_nonce_field( 'ushb-update' );
	echo '<div class="usof-header">';
	echo '<div class="usof-header-title">' . _x( 'Edit Header', 'site top area', 'us' ) . '</div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_title',
			'id' => 'usof_header_title',
			'field' => array(
				'type' => 'text',
				'placeholder' => __( 'Header Name', 'us' ),
				'classes' => 'desc_0', // Reset desc position of global HB field
			),
			'values' => array(
				'post_title' => $post->post_title,
			),
		)
	);

	echo '<div class="usof-control for_help"><a href="https://help.us-themes.com/' . strtolower( US_THEMENAME ) . '/hb/" target="_blank" title="' . us_translate( 'Help' ) . '"></a></div>';
	echo '<div class="usof-control for_import"><a href="#" title="' . __( 'Header Export / Import', 'us' ) . '"></a></div>';
	echo '<div class="usof-control for_templates">';
	echo '<a href="#" title="' . __( 'Header Templates', 'us' ) . '"></a>';
	echo '<div class="usof-control-desc"><span>' . __( 'Choose Header template to start with', 'us' ) . '</span></div>';
	echo '</div>';
	echo '<div class="usof-control for_save status_clear">';
	echo '<button class="usof-button type_save" type="button"><span>' . us_translate( 'Save Changes' ) . '</span>';
	echo '<span class="usof-preloader"></span></button>';
	echo '<div class="usof-control-message"></div></div></div>';

	us_load_template(
		'vendor/usof/templates/field', array(
			'name' => 'post_content',
			'id' => 'usof_header',
			'field' => array(
				'type' => 'header_builder',
				'classes' => 'desc_0', // Reset desc position of global HB field
			),
			'values' => array(
				'post_content' => $post->post_content,
			),
		)
	);

	echo '</form>';
	echo '</div>';

}

// Add link to duplicate headers in admin area
add_filter( 'post_row_actions', 'ushb_post_row_actions', 10, 2 );
function ushb_post_row_actions( $actions, $post ) {
	if ( $post->post_type === 'us_header' ) {
		// Removing duplicate post plugin affection
		unset( $actions['duplicate'], $actions['edit_as_new_draft'] );
		$actions = us_array_merge_insert(
			$actions, array(
			'duplicate' => '<a href="' . admin_url( 'post-new.php?post_type=us_header&duplicate_from=' . $post->ID ) . '" aria-label="' . esc_attr__( 'Duplicate', 'us' ) . '">' . esc_html__( 'Duplicate', 'us' ) . '</a>',
		), 'before', isset( $actions['trash'] ) ? 'trash' : 'untrash'
		);
	}

	return $actions;
}

// Add link to Admin bar to edit the current header
add_filter( 'admin_bar_menu', 'ushb_admin_bar_menu', 500 );
function ushb_admin_bar_menu( $wp_admin_bar ) {
	global $us_header_id;
	if ( $us_header_id != '' AND current_user_can( 'administrator' ) ) {
		$wp_admin_bar->add_node(
			array(
				'id' => 'ushb-edit-header',
				'title' => _x( 'Edit Header', 'site top area', 'us' ),
				'href' => admin_url( 'post.php?post=' . intval( $us_header_id ) . '&action=edit' ),
			)
		);
	}
}
