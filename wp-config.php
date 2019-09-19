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
define('WP_CACHE', true);
define('WPCACHEHOME', '/srv/disk3/2949020/www/heavenlankatours.net/wp-content/plugins/wp-super-cache/' );
define('DB_NAME', 'jeweldb');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

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
define('AUTH_KEY',         'jklB&PV(JPt/!o=7.<08I]|3oKU/{SSUJS_P=Rv4gi(w^5kB_5E:]~:ZHFY[%d>5');
define('SECURE_AUTH_KEY',  'YVITp/o_:f(J[K$Np_{)A<uKs~mt86Qx/A/_a[)j02gj^7sy}d+3}CywY$8gc;==');
define('LOGGED_IN_KEY',    'XQ|vLk:VJ1%l7lVXwXwr{JH/H:Y&5[Ilpv/.thINe#b,<5CCbHq<@B+E7I;aAg5+');
define('NONCE_KEY',        'L_K=)~P+zd?fiqomfjql>nrvf|Z:d_r`gdPtmh#35f*k{Ytxv(m&.o1eU)n/IGTk');
define('AUTH_SALT',        'e5iMmpFE^aP37TssHeAQ[wX_}|4gl0ZK1VpT$P$/Z;,)~O$Rl]{C5nKD8qoVEw<p');
define('SECURE_AUTH_SALT', 'boZ;O&LGg`8:iXMgAxJeY=9Ne^ :@u}F7PEW2d}jYb-7y*yo-G*&FMseXAbDZ/lc');
define('LOGGED_IN_SALT',   'D[U$4j1]%}N5j(|e?&/_r7%D3o,* ~! 53bUJw(TOH0@|2^V~}j}xB}!79tdjC9k');
define('NONCE_SALT',       'P~_pk}lm5SywtCUC3x95Pd$5@D/vG]H=rF{OI5eZlm!esChXP.G)A#!WCY_Ce*Fc');

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
define('WP_MEMORY_LIMIT', '512M'); 

require_once(ABSPATH . 'wp-settings.php');
define('WP_SMUSH_API_TIMEOUT', 150);
