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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mactavi1_wp467' );

/** MySQL database username */
define( 'DB_USER', 'mactavi1_wp467' );

/** MySQL database password */
define( 'DB_PASSWORD', '-A(xMiKR8tI=' );

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
define( 'AUTH_KEY',         'JLZ9@cx(-+Tl=N^ATVY-J>xP@T| [wgF5ZGU =@gv 4+I*kv[rqLjLh2QQn}1HcS' );
define( 'SECURE_AUTH_KEY',  'kzJHnIi,M2Jl3b& p<}hMSg~,:0x$}cf4FZXeLcAjM|{]1n9CG:Tcm_nd>?!jiWd' );
define( 'LOGGED_IN_KEY',    '*!MvhcQ4fe=P~e_`wZoa2a4Y,~rrVYzLHd?9!~SnMz6-}k| `l3ylh]OBiwu!soy' );
define( 'NONCE_KEY',        '916Xn{>=%O.@M@oBp$$MEZDtcX[$:s=69-I(|.Fo)876lkZIG5_Tw_-?QsH:OW!H' );
define( 'AUTH_SALT',        'Fg+~Ihu~~I!$_45foWx17IVeO6I`.M80L(uJp|dS([|GQ8y{19vB]D8)LZbxu2~}' );
define( 'SECURE_AUTH_SALT', '<XeNpG9jO~d4qsbJhF*UC$y,[CAZi5uGJJ>s%,aOa?.]ikGvJT/oTaJ}E^VeD25g' );
define( 'LOGGED_IN_SALT',   'r?29s+T@^T;?$Z*T${9mzd3W<T@_J;y;&_?qb]/b^I~%9<Uxt{L?c]lXUs:]g_Or' );
define( 'NONCE_SALT',       'q7m1XBr9t($Uq4Iwusp&>GYX8-uK$]z4^wP53j8h=:&`NU;56}<Q}PAsj#Q<L%/~' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpqd_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
