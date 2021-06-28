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
define( 'DB_NAME', 'newwordpress' );

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
define( 'AUTH_KEY',         '(Cc_s6Ezu@JDD(;TOUg!bJ4041YQBA)li _C[7P*nMQ@5%EffE27YPfS@Ju{XC2c' );
define( 'SECURE_AUTH_KEY',  ')wA~1VpX^shL7Np:f~7G2RCDGXO7}bx]0lP08sO81t{Y>o6}`c*$V^<8k&?wGRl6' );
define( 'LOGGED_IN_KEY',    'jOmh/@;j@%K$>p8PIgR0`Yf$n%m3op~&[[~N+CTv!6p)A~3{iO.Tw20~DF*i|2C7' );
define( 'NONCE_KEY',        'UN}#NzS bS&set})v!Q.6kR{}].R]7-rK^]r;]H[1{]9,h3&r Ev%5-SEqMS|&*_' );
define( 'AUTH_SALT',        'R5e#Wh<Ly~XqYT_~{P;&Xz^1/.]DI+0Dw,:giozv|kCTO`W2[c u7KgJ%Rd#9LY<' );
define( 'SECURE_AUTH_SALT', '1l^WIokZ0GqvTD0@,ZnAn#<@6#LM|_b6EdouiSkeED?{|}[)AtV|J-EV~Zis5PS2' );
define( 'LOGGED_IN_SALT',   'dD~/3)6#)F)zT<x@:)bIQj+}WO>[]!TW7$]`CB+3eEzVc#a=uIq{33Pn8uF7r}7~' );
define( 'NONCE_SALT',       'mcB3TmJ)%_# f=!$Cc_4&tv.|!pzP}/#8pwH>5GdoM@yS_$Bcc0hA_/Cy^rY)vEA' );

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
define( 'WP_DEBUG', false );

define( 'JETPACK_DEV_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
