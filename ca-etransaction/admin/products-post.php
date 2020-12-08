<?php

require_once plugin_dir_path(__FILE__) . 'db/productsdb.php';
require_once plugin_dir_path(__FILE__) . '../etransactions/ETransactions/ETransaction.php';

/**
 * Convinent function to redirect on the main page
 */
function redirect()
{
    wp_redirect(admin_url('admin.php') . '?page=etransactions_products');
    exit();
}

/**
 * Action for adding a product
 */
add_action('admin_post_add_product', function () {
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = isset($_REQUEST['active']);
    $free_amount = isset($_REQUEST['free_amount']);
    $category = $_REQUEST['category'];

    ETransaction_ProductDB::get_instance()
        ->insert($name, $price, $active, $free_amount, $category);

    redirect();
});

/**
 * Action for deleting a product
 */
add_action('admin_post_delete_product', function () {
    $valid = apply_filters('etransactions_is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $id = $_REQUEST['product_ID'];
    $productDb = ETransaction_ProductDB::get_instance();
    $result = $productDb->deleteById($id);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});

add_action('admin_post_edit_product', function () {
    $valid = apply_filters('etransactions_is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $product_id = $_REQUEST['product_ID'];
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = isset($_REQUEST['active']);
    $free_amount = isset($_REQUEST['free_amount']);
    $category = isset($_REQUEST['category']) ? $_REQUEST['category'] : '';

    $productDb = ETransaction_ProductDB::get_instance();
    $result = $productDb->update($product_id, $name, $price, $active, $free_amount, $category);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});
