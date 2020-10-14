<?php
/**
 * Plugin Name: CA e-Transactions for Wordpress
 * Plugin URI: https://github.com/regisf/wp-etransactions
 * Description: Simple e-Transaction wrapper for non eCommerce website that need a payment system.
 * Version: 1.0.2
 * Requires at least: 5.5
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: etransactions
 * Domain Path: /locales
 */

include_once __DIR__ . '/constants.php';
require_once __DIR__ . '/admin/init.php';
require_once __DIR__ . '/admin/menus.php';
require_once __DIR__ . '/admin/products-post.php';
require_once __DIR__ . '/admin/hooks/filters.php';
require_once __DIR__ . '/shortcode.php';


/**
 * Install database on plugin activation
 */
function etransactions_install_hook()
{
    ProductDB::get_instance()->install();
    TransactionDB::get_instance()->install();
}
register_activation_hook(__FILE__, 'etransactions_install_hook');


/**
 * Load all translations. Update the database regarding the current version
 */
add_action('plugins_loaded', function() {
    load_plugin_textdomain('etransactions', false, __DIR__ . '/locales');
    update_database();
});

/**
 * Update the database on plugin loaded
 *
 * A problem occure when the database version is greater than the current
 * version
 */
function update_database() {
    $options = get_option(Constants::OptionCurrentVersion, 0);
    if ($options < CurrentVersion) {
        ProductDB::get_instance()->upgrade();
        update_option(Constants::OptionCurrentVersion, CurrentVersion);
    } else {
        add_option(Constants::OptionCurrentVersion, CurrentVersion);
    }
}
