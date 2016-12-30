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
define('DB_NAME', 'toller');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'password');

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
define('AUTH_KEY',         '&R 9/nm;9UT)50?a0er iS7*t|~%*>07w@fz$K_kJ}&vjhf;/@P&8hVhAEf7T<#.');
define('SECURE_AUTH_KEY',  '~G,;SG|oFB E+ZyF>=uI(Y;yEF{`DU$C(KKaZ*NIV][]`y+tz#!%h_8B{Oxh@Zt<');
define('LOGGED_IN_KEY',    ';<@uQOlOVTCn:Ea<(_j&R%7i6+1yi::#zlvPLFu(`w<auF[%Bl}T%4|*:/hqet9{');
define('NONCE_KEY',        '~CR{ f!^LR:tBSrcX.lp@@)yagrKF[;c~Bsnfr$`OMh&(GGUPFS}G.UG]DdPd&xI');
define('AUTH_SALT',        ']%@HNaETMGghRqajZ]cD;ok;}4D})bCztDA9AT/KZx5?N8Q0<OB%NgWsLz69&H,M');
define('SECURE_AUTH_SALT', 'eo-:#i>5#Mc9z(Cx)|,OK@73p&3Hexz2Kb~c)&pxG>I>Z|:V#5QS-2bx {-~!,s4');
define('LOGGED_IN_SALT',   'dM=b*1jBRxmN*U91{`.G@FCx3aOs#v}>TW{g~Lwm.)ovo%H9qBje@M2[bGBO4| }');
define('NONCE_SALT',       '+InFZJON[IzJc`RASU6B0{)LY:-7TLUghA/kB@-  tP4iTf+N9l<A@F:WTzu&[;g');

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



/*
TODO
* CREATE EXAM Schedule ---done
* create default posts on user creation
* Fix user status
* List file name by label ---done





CHANGES TO MAKE IN files
* ring scheduler page links static
* reload link  after copying schedule Sets




*/
