<?php
/**
 * Plugin Name: CA e-Transactions for Wordpress
 * Plugin URI: https://github.com/regisf/wp-etransaction
 * Description: Handle the basics with this plugin.
 * Version: 1.0.3
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: etransactions
 * Domain Path: /locales
 */

define('ETransactions_NonceName', 'etransactions_products');
define('ETransactions_DbPrefix', 'etransactions_');

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

    ETransactions_ProductDB::get_instance()->install();
    ETransactions_TransactionDB::get_instance()->install();
}
register_activation_hook(__FILE__, 'etransactions_install_hook');


abstract class ETransactions_Constants
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
