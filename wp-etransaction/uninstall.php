<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

if (!current_user_can('manage_options')) {
    return;
}

delete_options('etransaction_options');

global $wpdb;
$wpdb->query('DROP TABLE `etransactions_product`');