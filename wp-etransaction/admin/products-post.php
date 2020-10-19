<?php

function redirect()
{
    wp_redirect('/wp-admin/admin.php?page=etransactions_products');
    exit();
}

add_action('admin_post_add_product', function () {
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = isset($_REQUEST['active']);
    $free_amount = isset($_REQUEST['free_amount']);
    $category = $_REQUEST['category'];

    $productDb = CA_Etransactions_ProductDB::get_instance();
    $productDb->insert($name, $price, $active, $free_amount, $category);

    redirect();
});

add_action('admin_post_delete_product', function () {
    $valid = apply_filters('is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $id = $_REQUEST['product_ID'];
    $productDb = CA_Etransactions_ProductDB::get_instance();
    $result = $productDb->deleteById($id);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});

add_action('admin_post_edit_product', function () {
    $valid = apply_filters('is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $product_id = $_REQUEST['product_ID'];
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = isset($_REQUEST['active']);
    $free_amount = isset($_REQUEST['free_amount']);
    $category = $_REQUEST['category'];

    $productDb = CA_Etransactions_ProductDB::get_instance();
    $result = $productDb->update($product_id, $name, $price, $active, $free_amount, $category);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});
