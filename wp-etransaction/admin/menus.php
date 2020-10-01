<?php

add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    add_menu_page(
        'Crédit Agricole e-Transactions',
        'CA e-Transactions',
        'manage_options',
        'etransactions',
        function () {
            require_once __DIR__ . '/settings_page.php';
        },
        plugin_dir_url(__FILE__) . '../assets/images/ca_icon.png',
        200
    );

    add_submenu_page(
        'etransactions',
        'CA e-Transaction Products',
        'Products',
        'manage_options',
        'etransactions_products',
        function () {
            require_once __DIR__ . '/products_page.php';
        }
    );

    add_submenu_page(
        'etransactions',
        'CA e-Transactions Payements ',
        'Payements',
        'manage_options',
        'etransactions_payements',
        function () {
            require_once __DIR__ . '/payements_page.php';
        }
    );

});
