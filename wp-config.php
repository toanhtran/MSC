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
define('DB_NAME', 'msc');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '&vsuN_@ArypflWM:3{KmOidh[847DC8ytyhMqr/*$B(?khnF6BAi@.L47:<zktc<');
define('SECURE_AUTH_KEY',  '?EYy1}32^d f|W;{Hz]M_];aqx~guNH`ff`Q%w?oGxTnn!U_4W.f>]%J,eyp>2.t');
define('LOGGED_IN_KEY',    '6[1<qcoXKY6k#-k>sL?4w~lp}_TZ-^d{&F0,~C45B%&Wp }{Q!.f$;GsnaTH-kHQ');
define('NONCE_KEY',        'K)[(`T&!+)MWdqIdo,D&fkR|3S]AoBHb;n33+4kqGUX>R:MvQozm5.u$cvegP(@c');
define('AUTH_SALT',        '@5{(EHG:=wYzkL>N:&#yujs4%@B&K{Ir<^95,E(Ov1,Y%hX.Bx`cT<8{gZnEL1,-');
define('SECURE_AUTH_SALT', ',4^jU6][:3I3em):t~p!3e!([.}vJ}X>mdHI)[7pX<a7wV8(k$DFl4XRc69+ncy%');
define('LOGGED_IN_SALT',   'V.y:UK7Cz%p <`4y/8ieJi.g0c$:-M8!K]W%Jpw%Lj7$*6KHzVO3t,SRqyB|+Y6S');
define('NONCE_SALT',       '9;/G3x/dgu20Q9!`/4BeQ2FI[1jW#0.5(t-g[,st)&iG:CU_kr;9at7Rc3[cxP6q');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
