<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_ProductsDB.php';
require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_TransactionDB.php';
require_once plugin_dir_path(__FILE__) . '../../etransactions/ETransactions/ETransaction.php';

function is_required_options_exists($options)
{
    return (isset($options[ETransactions_OptionSiteID])
        && isset($options[ETransactions_OptionRangID])
        && isset($options[ETransactions_OptionCustomerID])
        && isset($options[ETransactions_OptionSecretKey]));
}

function validation_page($attrs = [], $content = '')
{
    if (!isset($_REQUEST['product'])) {
        return '<p>Error: No product id set</p>';
    }

    $options = get_option(ETransactions_OptionName);
    if (!is_required_options_exists($options)) {
        return '<div class="etransactions-warning">' .
            __('Warning: One or more option is not set. No transaction could be executed.', ETransactions_Tr) .
            '</div>';
    }

    $attrs = shortcode_atts([
        'no-label' => true,
    ], $attrs);

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = \ETransactions_ProductDB::get_instance()->getById($product_id);

    $holder = sanitize_text_field($_REQUEST['PBX_PORTEUR']);
    $ref = wp_generate_uuid4();
    $result = \ETransactions_TransactionDB::get_instance()->insert_order($product_id, $holder, $ref, $product->price);

    $preprod = isset($options['test_id']);
    $etransaction = new \ETransaction($preprod);
    try {
        $data = \TransactionData::fromData([
            'site' => $options[ETransactions_OptionSiteID],
            'rang' => $options[ETransactions_OptionRangID],
            'id' => $options[ETransactions_OptionCustomerID],
            'secret' => $options[ETransactions_OptionSecretKey],

            'command' => $result->order_ref,
            'total' => $product->free_amount !== '1'
                ? (float)$product->price
                : (float)sanitize_text_field($_REQUEST['free_amount']),
            'holder' => $result->email,
            'callbacks' => []
        ]);
    } catch (\exception $e) {
        return "<div>Missing settings: perhaps a misconfiguration</div>";
    }

    if (isset($options[ETransactions_OptionAcceptedLandingPage])
        && $options[ETransactions_OptionAcceptedLandingPage] !== '') {
        $data->getCallbacks()->setDoneCallback($options[ETransactions_OptionAcceptedLandingPage]);
    }

    if (isset($options[ETransactions_OptionRejectedLandingPage]) && $options[ETransactions_OptionRejectedLandingPage] !== '') {
        $data->getCallbacks()->setDeniedCallback($options[ETransactions_OptionRejectedLandingPage]);
    }

    if (isset($options[ETransactions_OptionCanceledLandingPage]) && $options[ETransactions_OptionCanceledLandingPage] !== '') {
        $data->getCallbacks()->setCanceledCallback($options[ETransactions_OptionCanceledLandingPage]);

    }

    $etransaction->setTransactionData($data);

    $str = $preprod === true ? '<div class="etransactions-warning">! Caution ! You are in test mode </div>' : '';
    $str .= '<form action="'
        . $etransaction->getServerAddress()
        . '" class="etransactions-product-form" method="post">';

    if ($attrs['no-label'] === false) {
        $str .= '<p class="etransactions-product-desc">
            <span class="etransactions-product-name">' . __('Product:', ETransactions_Tr) . ' ' . stripcslashes($product->name) . '</span>
        <span class="etransactions-product-price">';

        if ($product->free_amount === true) {
            $str .= '<input type="number" step="0.01" name="free_amount" value="' . $product->price . '"/>';
        } else {
            $str .= $product->price . '&nbsp;&euro;';
        }
        $str .= '</span></p>';
    }

    $str .= $etransaction->getTransactionForm() .
        '<p class="etransactions-product-submit">
            <input type="submit" value="' . __('Proceed to payement', ETransactions_Tr) . '" >
        </p>
    </form>';

    return $str;
}