<?php
/**
 * Plugin Name: CA e-Transactions
 * Plugin URI: https://github.com/regisf/wp-etransaction
 * Description: Simple products management and paiement using eTransactions (Paybox)
 * Version: 1.2.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ca-etransaction
 * Domain Path: /languages
 */

include_once plugin_dir_path(__FILE__) . 'constants.php';
require_once plugin_dir_path(__FILE__) . 'admin/init.php';
require_once plugin_dir_path(__FILE__) . 'admin/menus.php';
require_once plugin_dir_path(__FILE__) . 'admin/products-post.php';
require_once plugin_dir_path(__FILE__) . 'admin/hooks/filters.php';
require_once plugin_dir_path(__FILE__) . 'admin/db/transactiondb.php';
require_once plugin_dir_path(__FILE__) . 'admin/db/productsdb.php';
require_once plugin_dir_path(__FILE__) . 'shortcode.php';
require_once plugin_dir_path(__FILE__) . 'admin/email.php';


/**
 * Install database on plugin activation
 */
function etransactions_install_hook()
{
    ETransaction_ProductDB::get_instance()->install();
    ETransaction_TransactionDB::get_instance()->install();
}

register_activation_hook(__FILE__, 'etransactions_install_hook');

function etransactions_plugin_loaded()
{
    load_plugin_textdomain(
        ETransactions_Tr,
        FALSE,
        'ca-etransaction/languages');
}

add_action('plugin_loaded', 'etransactions_plugin_loaded');
