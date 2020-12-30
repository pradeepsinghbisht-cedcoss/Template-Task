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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'QzLKu9agyqh652K7pWPt5VM6IEHFscvsIY1JSggDRBF1cWG+SuJSZESM7OJwv6OgqpNnUOk0vDGq8ALlzV3DOQ==');
define('SECURE_AUTH_KEY',  '5Y+k1DCSwd7pIEhaLbDosiNr772jRPAT0eqYZVjOKEQtsdPQ/CMP9hbj3vvj+HXBbEQho/vlTkE9rm903heCZQ==');
define('LOGGED_IN_KEY',    'rOSD5JX50134xBGm+iSQU2QXLrta6yFQ35Gt85Ie/AGjG7YblbzlL23bYdDgoVcDOICv523PGHvu+W3Kv2E3UQ==');
define('NONCE_KEY',        'HD4unwYE4kAh3supPeIAESxY/668uj4yIPDMtc59fD27fRo0axfut0m6MlEG+HZkqug8ciuUI9ZIuw8NyhkfIQ==');
define('AUTH_SALT',        'QnOrotDuOHJRKfnahDZoD2KOFEU7kH6Y9fYHxUumkvSRppOa7rkjBeoZyQtHQum+hrTPXemSs5/uJUmwqsUQmw==');
define('SECURE_AUTH_SALT', '9Mvc2XLN8JSqxl/USG9fAskc+BW846fZruhCB5q0+MAJUx0YOBB69cVd6WnU2wdEYFe0YpshmSxoURucF4hqoA==');
define('LOGGED_IN_SALT',   'SZT/T7RLgqjTbnirVOfLAElTsmQMnEmOrZ/X2pxDNFHxUf6SaTNmxA/+9R6+kBbdJrySMIdbIWneeJfvRXz/SQ==');
define('NONCE_SALT',       'vIOcH/pPKpsKlY88fqs6ZAbV/320GvaslLMEz1J+yNvuH8tZJKVM1rYdO8WYkajbRyV+3OSxODArnYNR7tihkA==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
