<?php
/**
 * Plugin Name: CA e-Transactions for Wordpress
 * Plugin URI: https://github.com/regisf/wp-etransaction
 * Description: Handle the basics with this plugin.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

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
    $productDb = ProductsDb::get_instance();
    $productDb->install();
}

register_activation_hook(__FILE__, 'etransactions_install_hook');
