<?php

function redirect()
{
    wp_redirect('/wp-admin/admin.php?page=etransactions_products');
    exit();
}

add_action('admin_post_add_product', function () {
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = $_REQUEST['active'];
    $productDb = new ProductsDb();
    $productDb->insert($name, $price, $active);

    redirect();
});

add_action('admin_post_delete_product', function () {
    $valid = apply_filters('is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $id = $_REQUEST['product_ID'];
    $productDb = new ProductsDb();
    $result = $productDb->deleteById($id);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});

add_action('admin_post_edit_product', function() {
    $valid = apply_filters('is_nonce_exists', $_REQUEST['_wpnonce']);
    if (!$valid) {
        die('Integrity error');
    }

    $product_id = $_REQUEST['product_ID'];
    $name = $_REQUEST['name'];
    $price = $_REQUEST['price'];
    $active = $_REQUEST['active'] === 'on';

    $productDb = new ProductsDb();
    $result = $productDb->update($product_id, $name, $price, $active);
    if ($result === false) {
        echo "Pas marche: " . $productDb->pkoi();
        die();
    }

    redirect();
});
