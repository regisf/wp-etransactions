<?php

require_once __DIR__ . '/etransaction_actions.php';

/**
 * Action for adding a product
 */
add_action('admin_post_add_product', 
           ['ETransaction_Actions', 'admin_post_add_product']);


/**
 * Action for deleting a product
 */
add_action('admin_post_delete_product', function () {
    $valid = apply_filters('etransactions_is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $id = $_REQUEST['product_ID'];
    $productDb = ETransactions_ProductDB::get_instance();
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
    $category = $_REQUEST['category'];

    $productDb = ETransactions_ProductDB::get_instance();
    $result = $productDb->update($product_id, $name, $price, $active);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});