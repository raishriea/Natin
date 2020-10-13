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
define('DB_NAME', 'school');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'X`@Ko}LiUl_BhIz$c_HmQ80y~)th)~QX=@+zAiv8GyP@gj`tjCvqsJ1K>=&n>C-d');
define('SECURE_AUTH_KEY',  '`vwHks{2k<zNWQ;~,7,v7OkLW`WzsfRW%W.Z(Sj_[-XF?4C#@S8C=!F]Jn$6oxNu');
define('LOGGED_IN_KEY',    'R5dgmx^&kj`7]K84nVzP{-vw.oaAT%=2G wHw8va^4|m{{~D!Gdw_z=adV[5Ujb ');
define('NONCE_KEY',        '##XOxq.>:G|k`IP::Y*u]@3?#Ai!Eopw$#s~,^ikSjg*G0*F?jE^.qNrI}}N#p{I');
define('AUTH_SALT',        'SIv(ZFD[ekSsFCfw|dy-Y9hS8QU4}sXIHM<B*J<vo84Q#v2Q$_~HNRy&.@2Wu|=O');
define('SECURE_AUTH_SALT', '}=LS0R(J[V,]kz(h0e.u.Y$&f2;TLhj>]`zXeRuy8n)bW{2`RhPvhx5sHI~nU^ 2');
define('LOGGED_IN_SALT',   'uSAM{kdRfu hit9)_9FpQg}WPEGY#$7#:yL?2;d~SC,8x*@[-zMLx$#9d33=!-sQ');
define('NONCE_SALT',       'krN~6JyB.(gbWyV)r8-N,plg4wL)g99QrS2YqLpOYx^ihtvp{Ic|VkG%D# Fy<E~');

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
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', true);

define('WP_DEBUG_LOG', true);

define('WP_DEBUG_DISPLAY', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

define('WP_ALLOW_REPAIR', true);
