<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'db600437335');

/** MySQL database username */
define('DB_USER', 'dbo600437335');

/** MySQL database password */
define('DB_PASSWORD', '20eligefacil15#');

/** MySQL hostname */
define('DB_HOST', 'db600437335.db.1and1.com');

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
define('AUTH_KEY',         ',7QK5-gU 0QLv=DXacB(rcAE(mKCF2Of4@(u,h I3QrB_iv%cQ-O+>My,??oRgt[');
define('SECURE_AUTH_KEY',  'S|>TZdM?XKe4vQlNGVSxYKzpRJ,5nZq%xr(HqjtR@5%]yVy-C]1j{QxudhYj%3IP');
define('LOGGED_IN_KEY',    'Wm:J}|{7lz:p$?9mT!XNrj+-?TLA=M}YS/tV`<;_66H[*-3r#xxl6B4iaE.2j1x ');
define('NONCE_KEY',        'RN_SOAG F5[=HSFH-Hdh8H!`yZ|Wm|k#*3-~|9m|m1@12M`u#pQ~!8t@6pb2WJ|b');
define('AUTH_SALT',        'dew{i5IbdEOKl/(Iqe2j;`hm.)wLN&-A[(|NzAT5&|AcEI5?Eot-g|9HgAgB&op&');
define('SECURE_AUTH_SALT', 'mf-bOgV0h2-ZF<oqo*(x|=s>u>YXK:,W6kOf+r+&](&g-=++/@k !I+1q8<8W3LT');
define('LOGGED_IN_SALT',   ')?hH7Ta><$q=h64D qJB`-bXd0fwTdJ+~=T_Fw]*tpHNzV EF=;}+P!GGr-KK+9I');
define('NONCE_SALT',       'd0~|tg2f0nH9y6uN-i6 1W}+&o`yov1UbV#uG||5:@>VM{Q-G3~wAmpHi7tgu/&}');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
