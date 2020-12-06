<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_ProductsDB.php';


/**
 * Display all the active products from the database
 */
function products_list ($attrs, $content) {
    $product_db = \ETransactions_ProductDB::get_instance();
    $actives = $product_db->get_actives();
    $str = '';

    if (count($actives) === 0) {
        return '<div class="etransactions-product-empty">' .
            __('No products active to display', ETransactions_Tr) .
            '</div>';
    }

    if (strlen($content) === 0) {
        $content = __('Order', ETransactions_Tr);
    }

    foreach ($actives as $product) {
        $str .= '<div class="etransactions-product-wrapper">
            <div class="etransactions-product-name">'
            . stripcslashes($product->name) . '
            </div>
            <div class="etransactions-product-price"> ';

        if ($product->free_amount === '1') {
            $str .= $attrs['free_amount_label'];
        } else {
            $str .= $product->price . '&nbsp;&euro;';
        }

        $str .= '</div>
            <div class="etransactions-product-apply">
                <a href="' . apply_filters('etransaction_get_confirmation_address', $product->product_id) . ' ">' . $content . '</a>
            </div>
        </div>';
    }

    return $str;
}
