<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'hermojo');

/** MySQL database username */
define('DB_USER', 'elitech');

/** MySQL database password */
define('DB_PASSWORD', 'elitech');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'HOUf6r!vY;$58|o5EXh)O{9+r|6)c0?^N]5wWV/aEv-,Mz~5K3+?#k^68$}RS|/i');
define('SECURE_AUTH_KEY',  'w!x,wIQg`txECGoj!G:6$YBpXfNZ&-bVvExWQA/ClD1?5Gntpb,H#*[k^!O;aYg;');
define('LOGGED_IN_KEY',    'd:R!<>vOrx;0R#WfGjy/Kx_r}SFHr_y7/`GzkQ}K@68T*+`yn-okU!?Nl-TP`+yF');
define('NONCE_KEY',        ')W[2&BVqsgg^oylV4! +]|_=ABOV+OGydE|Q$eqwpY3@*SOR*%TV9z zL!~%O`Et');
define('AUTH_SALT',        'UM-`[EbkRCVUO7W]Y3(z]oF+i|(h+ln*Dy(Qd^-J*?|svQQ $h`1^I)V{d7bi!n!');
define('SECURE_AUTH_SALT', '{WpY,K+Vp{Yat%a13tEyvJhfwg0B&ogwsPeY-gpTPf:rW?5g!Cv)x*vF|4i`|GSx');
define('LOGGED_IN_SALT',   'Ri1n0LA[*,-W{[_Gfg;fx;,p)(xAcH^&!<TO8LS^MUBqOe ;^#2L+kn[ino<)BLJ');
define('NONCE_SALT',       '?>yM_//o}Kx0FcPdxqsEg;INM_q=wAsGu]>eZT/8LzYm!_Ug-vGE+N|/:&*}!EHk');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
