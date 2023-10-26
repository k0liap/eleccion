<?php
/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'eleccionperfecta' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define( 'AUTH_KEY',         '{D>E,P$Yv;|xr%OFBqo;]%tZSWS]+ScqE-:g>k6k:5&R?(c?{&f#rMHMD/8Sm%Wc' );
define( 'SECURE_AUTH_KEY',  'o|YGf%8/=xeohz2#fY Tk)?7P{ic&L?p#ercY&+Rze=YL5KdkP?^@SJmqNGf0D4b' );
define( 'LOGGED_IN_KEY',    'X6YZcR(qB`O=b>4yaz0-vh6;Lt}oMrDQ8#f:rRlaq wFr]bw8({,`#-cx6O-U-H*' );
define( 'NONCE_KEY',        ' ZATl1 hF!/B67RW5?4%v3^*Jpf)nMCZnV8(U53-wL1w%9:j?h.9l(;6:2l gM1g' );
define( 'AUTH_SALT',        '+h-0,xy@+f+*WkYWW.:;i12X&QrXyc~VG6)FY#p5h|S3wub?0A;ju*BGXbFMuXDX' );
define( 'SECURE_AUTH_SALT', '!PeQE|{td>^<mXlB>($3ua2GqL=_g]+r1pSAH8I?bsbcKoF!#URb2k}-TDXDNhcr' );
define( 'LOGGED_IN_SALT',   ']L/~t!AMX`/DXlxzhf0o0{I_ kem8ond![QUOA3`|BMJIO/WiB!sIE=g<^]^nDjC' );
define( 'NONCE_SALT',       'weB!b7]Mj7-Xp/?ym!A34H}yQSlu9D(o6qS$IV:?j h=)A(`DI7 MsITC(tVZg?f' );

/**#@-*/

/**
 * WordPress database table prefix.
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
