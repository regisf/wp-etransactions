<?php
if (!current_user_can('manage_options')) {
    return;
}
require_once __DIR__ . '/../db/ProductsDB.php';
require_once __DIR__ . '/widgets/product_list_table.php';

$product_db = ProductDB::get_instance();
$product_list_table = new Product_List_Table($product_db);
$status = isset($_REQUEST['product_status']) ? $_REQUEST['product_status'] : 'all';
$product_action = isset($_REQUEST['product_action']) ? $_REQUEST['product_action'] : '';

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php
    if ($product_action === 'new') {
        $active = true;

        include_once __DIR__ . '/views/products/new.php';

    } else if ($product_action === 'edit') {
        $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
        if (!$valid) {
            die('Integrety error');
        }

        $id = esc_sql($_REQUEST['product']);
        $item = $product_db->getById($id);

        $name = $item->name;
        $price = $item->price;
        $active = $item->active;
        $use_free_amount = $item->free_amount;
        include_once __DIR__ . '/views/products/edit.php';

    } else if ($product_action === 'delete_confirm') {
        $id = esc_sql($_REQUEST['product']);
        $item = $product_db->getById($id);
        include_once __DIR__ . '/views/products/delete_confirm.php';

    } else if ($product_action === 'toggle_active') {
        $id = esc_sql($_REQUEST['product']);
        $product_db->toggle($id);

        include_once __DIR__ . '/views/products/default.php';

    } else {
        include_once __DIR__ . '/views/products/default.php';
    }
    ?>
</div>
