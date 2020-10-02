<?php
if (!current_user_can('manage_options')) {
    return;
}

require_once 'productsdb.php';
require_once 'product_list_table.php';

$product_db = ProductsDb::get_instance();
$product_list_table = new Product_List_Table($product_db);
$status = $_REQUEST['product_status'];
$product_action = $_REQUEST['product_action'];

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php
    if ($product_action === 'new') {
        include_once __DIR__ . '/views/products/new.php';
    } else if ($product_action === 'edit') {
        $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
        if (!$valid) {
            die('Integrety error');
        }

        $id = esc_sql($_REQUEST['product']);
        $item = $product_db->getById($id);

        include_once __DIR__ . '/views/products/edit.php';
    } else if ($product_action === 'delete_confirm') {
        $id = esc_sql($_REQUEST['product']);
        $item = $product_db->getById($id);
        include_once __DIR__ . '/views/products/delete_confirm.php';
    } else {
        include_once __DIR__ . '/views/products/default.php';
    }
    ?>
</div>
