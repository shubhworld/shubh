<?php defined( 'ABSPATH' ) OR die( 'This script cannot be accessed directly.' );

/**
 * Header Theme Options changes.
 *
 * @var $config array Framework- and theme-defined theme options config
 *
 * @return array Changed config
 */

// Hide the 'Headers' section, which is overloaded by Header Builder
$config['header']['place_if'] = FALSE;

return $config;
