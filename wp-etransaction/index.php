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

require_once(__DIR__ . '/ca-wp-etransaction.php');

/**
 * @property ETransaction ca_plugin
 */
class ETransaction_Plugin
{

    public function __construct()
    {
    }

    public static function activate()
    {

    }

    public static function deactivate()
    {

    }

    public static function uninstall()
    {

    }
}

register_activation_hook(__FILE__, array('ETransaction_Plugin', 'activate'));
register_deactivate_hook(__FILE__, array('ETransaction_Plugin', 'deactivate'));
register_uninstall_hook(__FILE__, array('ETransaction_Plugin', 'uninstall'));
