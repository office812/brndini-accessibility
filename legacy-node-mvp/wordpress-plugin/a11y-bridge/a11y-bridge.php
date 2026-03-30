<?php
/**
 * Plugin Name: A11Y Bridge
 * Plugin URI:  https://a11ybridge.local/
 * Description: תוסף וורדפרס שמחבר את האתר ל-A11Y Bridge עם audit ראשוני, onboarding, חיבור API ושיפורי frontend בטוחים כמו skip link ו-focus styles.
 * Version:     0.2.0
 * Author:      BRNDINI
 * Author URI:  https://brndini.co.il/
 * Text Domain: a11y-bridge
 * License:     Proprietary
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'A11Y_BRIDGE_VERSION', '0.2.0' );
define( 'A11Y_BRIDGE_PLUGIN_FILE', __FILE__ );
define( 'A11Y_BRIDGE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'A11Y_BRIDGE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once A11Y_BRIDGE_PLUGIN_DIR . 'includes/class-ab-site-audit.php';
require_once A11Y_BRIDGE_PLUGIN_DIR . 'includes/class-ab-admin.php';
require_once A11Y_BRIDGE_PLUGIN_DIR . 'includes/class-ab-frontend.php';
require_once A11Y_BRIDGE_PLUGIN_DIR . 'includes/class-ab-rest.php';
require_once A11Y_BRIDGE_PLUGIN_DIR . 'includes/class-ab-plugin.php';

add_action( 'plugins_loaded', array( 'AB_Plugin', 'instance' ) );
register_activation_hook( A11Y_BRIDGE_PLUGIN_FILE, array( 'AB_Plugin', 'activate' ) );
