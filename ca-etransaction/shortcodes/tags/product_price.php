<?php

namespace shortcodes\tags;

require_once plugin_dir_path(__FILE__) . '../../admin/db/productsdb.php';

function product_price($attrs = [], $content = '')
{
    if (!isset($_REQUEST['product'])) {
        return '';
    }
    $product_id = sanitize_text_field($_REQUEST['product']);

    if (! is_numeric($product_id)) {
        return '<!-- ' . $product_id .' -->';
    }

    $product = \ETransaction_ProductDB::get_instance()->getById($product_id);

    return $product->price . '&nbsp;&euro;';
}
