<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_ProductsDB.php';
require_once plugin_dir_path(__FILE__) . '../../etransactions/ETransactions/TransactionResult.php';

/**
 * Display confirmation content. In this page, the user must enter its
 * email address for his active consentement
 *
 * @param $attrs array the short code attributes
 * @param $content string the tag content (not used)
 * @return string The content to display into the page
 */
function confirmation_page ($attrs, $content) {
    if (!isset($_REQUEST['product'])) {
        return '<p>Error: No product id set</p>';
    }

    $attrs = shortcode_atts([
        'no-label' => true
    ], $attrs);

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = \ETransactions_ProductDB::get_instance()->getById($product_id);

    $options = get_option('etransactions_options');
    $preprod = isset($options['test_id']);

    $str = $preprod ? '<div class="etransactions-warning">Caution ! You are in test mode </div>' : '';
    if ($attrs['no-label'] === false) {
        $str .= '<p class="etransactions-product-desc">
        <span class="etransactions-product-name">' . __('Product:', ETransactions_Tr) . ' ' . $product->name . '</span>
        <span class="etransactions-product-price">' . $product->price . '&nbsp;&euro;</span>
        </p>';
    }

    $str .= '
        <form action="' . apply_filters('etransaction_get_validation_address', $product->product_id) . '" class="etransactions-product-form" method="post">
            <input type="hidden" name="product" value="' . $product->product_id . '"  />';

    if ($product->free_amount === '1') {
        $str .=
            '<span class="etransactions-free-amount">
                <label for="id_free_amount">' . __("Amount you want to give", ETransactions_Tr) . '</label>
                <input type="number" id="id_free_amount" step="0.01" name="free_amount" value="' . $product->price . '"/>
            </span>';
    }

    $str .= \HolderValue::emptyForm() .
        '<p class="etransactions-product-submit">
                <input type="submit" value="' . __('Confirm payement', ETransactions_Tr) . '" >
            </p>
        </form>';

    return $str;
}