<?php
/**
 * Plugin Name: TN Post Families
 * Description: Navigate parent, child and sibling posts in the WordPress editor.
 * Version: 1.1.0
 * Requires at least: 7.0
 * Requires PHP: 7.4
 * Update URI: https://github.com/cchatterton/tn-post-families
 * Author: Techn
 * Author URI: https://techn.com.au
 * Techn Controller API: 1
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tn-post-families
 */

if (!defined('ABSPATH')) {
    exit;
}

define('TNPF_VERSION', '1.1.0');
define('TNPF_PLUGIN_FILE', __FILE__);
define('TNPF_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TNPF_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once TNPF_PLUGIN_DIR . 'functions/admin.php';
require_once TNPF_PLUGIN_DIR . 'functions/controller-client.php';
tnuc_client_register(__FILE__, 'tn-post-families');
