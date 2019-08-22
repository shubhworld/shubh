<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '}ESp-}fO6FfS/Jl.O ;]Zoh`<T`YXOhV4]uF(#qf{Q#T*1{-(gYeSF1IR&|lyTd0' );
define( 'SECURE_AUTH_KEY',  '5p5#*vgO-CZH#4kSErl<>Pw#Emg %!jkS_+Ww$K0f@Tzp=]km^$.Z V{q7[3G#[+' );
define( 'LOGGED_IN_KEY',    'z7=zzPc:0[.58_m8Y9mwfQ0W:^j,0AvNs+wPC?s{n=(PV@fGYDtQlesAw3Q TbtQ' );
define( 'NONCE_KEY',        'HqQbOPS|2zBl8-[eJfd3|)iwm~>}s?=KQG4T (l3y%?t%p{P^5%T0JrV0`=D0<xJ' );
define( 'AUTH_SALT',        '/?#w_]h+ bI<BL4i0@/_GE6q|ub^Vn9q*{]SPBA{3+Lc]Qo2}@>~OA8|&vSzTP.,' );
define( 'SECURE_AUTH_SALT', ']Pb$RrHD=Ph#5p L}CMhtL @Yw7FNV3dhvuv}$svCm#Uhe~2&,MOy?POf8bQOfAo' );
define( 'LOGGED_IN_SALT',   'R~M/QDhX]wnCPUOHhAPk`L@tGs;q0R-xTpQXip;|Q7PW-[Sf37A[]%R#^MfY|hf+' );
define( 'NONCE_SALT',       'jqHS h,+}(W{$)~~,SOI+mF=unOn`m3qU$D:fU]-ug6wejb5S-:W%I54:6]Q1Y~z' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
