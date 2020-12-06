<?php

namespace shortcodes\tags;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_ProductsDB.php';

function product_price($attrs = [], $content = '')
{
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    if (! is_numeric($_REQUEST['product'])) {
        return '<!-- ' . $_REQUEST['product'] .' -->';
    }

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = \ETransactions_ProductDB::get_instance()->getById($product_id);

    return $product->price . '&nbsp;&euro;';
}
