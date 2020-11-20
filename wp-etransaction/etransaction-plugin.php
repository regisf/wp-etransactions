<?php
/**
 * Plugin Name: CA e-Transactions
 * Plugin URI: https://github.com/regisf/wp-etransaction
 * Description: Simple products management and paiement using eTransactions (Paybox)
 * Version: 1.1.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: etransaction-plugin
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

    ETransactions_ProductDB::get_instance()->install();
    ETransactions_TransactionDB::get_instance()->install();
}
register_activation_hook(__FILE__, 'etransactions_install_hook');

