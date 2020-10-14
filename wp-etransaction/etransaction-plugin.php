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

define('CurrentDbVersion', 101);

define('NonceName', 'etransactions_products');
define('DbPrefix', 'etransactions_');

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

abstract class Constants
{
    const PluginPrefix = 'etransactions_';

    // Options
    const PageName = 'etransactions';
    const OptionName = self::PluginPrefix . 'options';
    const OptionConfirmationPage = 'confirmation_page';
    const OptionValidationPage = 'validation_page';
    const OptionSiteID = 'site_id';
    const OptionRangID = 'rang_id';
    const OptionCustomerID = 'customer_id';
    const OptionSecretKey = 'secret_key';
    const OptionAcceptedLandingPage = 'accepted_page';
    const OptionRejectedLandingPage = 'rejected_page';
    const OptionCanceledLandingPage = 'canceled_page';
    const OptionCurrentVersion = self::PluginPrefix . 'current_version';
}

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
    if ($options < CurrentDbVersion) {
        ProductDB::get_instance()->upgrade();
        update_option(Constants::OptionCurrentVersion, CurrentDbVersion);
    } else {
        add_option(Constants::OptionCurrentVersion, CurrentDbVersion);
    }
}
