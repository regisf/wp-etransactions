<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if (!current_user_can('manage_options')) {
    return;
}

include_once __DIR__ . '/constants.php';

delete_option(Constants::OptionName);
delete_option(Constants::OptionCurrentVersion);

global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . Constants::PluginPrefix . 'transaction`');
$wpdb->query('DROP TABLE IF EXISTS `' . $wpdb->prefix . Constants::PluginPrefix . 'product`');
