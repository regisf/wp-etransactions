<?php
/**
 * Plugin Name: CA e-Transactions for Wordpress
 * Plugin URI: https://github.com/regisf/wp-etransactions
 * Description: Simple e-Transaction wrapper for non eCommerce website that need a payment system.
 * Version: 1.0.1
 * Requires at least: 5.5
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

define('CurrentVersion', '1.0.1');

define('NonceName', 'etransactions_products');
define('DbPrefix', 'etransactions_');

require_once __DIR__ . '/admin/init.php';
require_once __DIR__ . '/admin/menus.php';
require_once __DIR__ . '/admin/products-post.php';
require_once __DIR__ . '/admin/hooks/filters.php';
require_once __DIR__ . '/shortcode.php';

// Install database
function etransactions_install_hook()
{
    ProductDB::get_instance()->install();
    TransactionDB::get_instance()->install();
}

register_activation_hook(__FILE__, 'etransactions_install_hook');

abstract class Constants
{
    const PluginPrefix = 'etransactions_';

    // Translation key
    const EtransactionsTr = 'etransactions';

    // Options
    const PageName = 'etransactions';
    const OptionName = 'etransactions_options';
    const OptionConfirmationPage = 'confirmation_page';
    const OptionValidationPage = 'validation_page';
    const OptionSiteID = 'site_id';
    const OptionRangID = 'rang_id';
    const OptionCustomerID = 'customer_id';
    const OptionSecretKey = 'secret_key';
    const OptionAcceptedLandingPage = 'accepted_page';
    const OptionRejectedLandingPage = 'rejected_page';
    const OptionCanceledLandingPage = 'canceled_page';
}
