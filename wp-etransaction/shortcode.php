<?php

require_once __DIR__ . '/admin/db/ProductsDB.php';
require_once __DIR__ . '/admin/db/TransactionDB.php';
require_once plugin_dir_path(__FILE__) . 'etransactions/ETransactions/ETransaction.php';
require_once plugin_dir_path(__FILE__) . 'etransactions/ETransactions/TransactionResult.php';

add_shortcode('etransactions-products-list', function ($attrs = [], $content = '') {
    $attrs = shortcode_atts([
        'free_amount_label' => __('Free amount', 'etransactions'),
        'category' => '',
    ], $attrs);

    $products = CA_Etransactions_ProductDB::get_instance();
    $actives = $products->get_actives_for_category($attrs['category']);
    $str = '';

    if (count($actives) === 0) {
        return '<div class="etransactions-product-empty">' .
            __('No products active to display', 'etransactions') .
            '</div>';
    }

    if (strlen($content) === 0) {
        $content = __('Order', 'etransactions');
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
});

add_shortcode('etransactions-confirmation-page', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '<p>Error: No product id set</p>';
    }

    $attrs = shortcode_atts([
        'no-label' => true
    ], $attrs);

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = CA_Etransactions_ProductDB::get_instance()->getById($product_id);

    $options = get_option('etransactions_options');
    $preprod = isset($options['test_id']);

    $str = $preprod ? '<div class="etransactions-warning">Caution ! You are in test mode </div>' : '';
    if ($attrs['no-label'] === false) {
        $str .= '<p class="etransactions-product-desc">
        <span class="etransactions-product-name">' . __('Product:', 'etransactions') . ' ' . stripcslashes($product->name) . '</span>
        <span class="etransactions-product-price">' . $product->price . '&nbsp;&euro;</span>
        </p>';
    }

    $str .= '
        <form action="' . apply_filters('etransaction_get_validation_address', $product->product_id) . '" class="etransactions-product-form" method="post">
            <input type="hidden" name="product" value="' . $product->product_id . '"  />';

    if ($product->free_amount === '1') {
        $str .=
            '<span class="etransactions-free-amount">
                <label for="id_free_amount">' . __("Amount you want to give to the product", 'etransactions') . '</label>
                <input type="number" id="id_free_amount" step="0.01" name="free_amount" value="' . $product->price . '"/>
            </span>';
    }

    $str .= HolderValue::emptyForm() .
        '<p class="etransactions-product-submit">
                <input type="submit" value="' . __('Confirm payement', 'etransactions') . '" >
            </p>
        </form>';

    return $str;
});

add_shortcode('etransactions-validation-page', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '<p>Error: No product id set</p>';
    }
    list($site_id, $rang_id, $customer_id, $secret_key) = CA_Etransactions\get_required_options();
    $options = get_option(CA_Etransactions_Constants::OptionName);
    if (!(isset($options[CA_Etransactions_Constants::OptionSiteID])
        && isset($options[CA_Etransactions_Constants::OptionRangID])
        && isset($options[CA_Etransactions_Constants::OptionCustomerID])
        && isset($options[CA_Etransactions_Constants::OptionSecretKey]))) {
        return '<div class="etransactions-warning">' .
            __('Warning: One or more option is not set. No transaction could be executed.', 'etransactions') .
            '</div>';
    }

    $attrs = shortcode_atts([
        'no-label' => true,
    ], $attrs);

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = CA_Etransactions_ProductDB::get_instance()->getById($product_id);

    $holder = sanitize_text_field($_REQUEST['PBX_PORTEUR']);
    $ref = wp_generate_uuid4();
    $result = CA_Etransactions_TransactionDB::get_instance()->insert_order($product_id, $holder, $ref, $product->price);

    $preprod = isset($options['test_id']);
    $etransaction = new ETransaction($preprod);
    $data = TransactionData::fromData([
        'site' => $options[CA_Etransactions_Constants::OptionSiteID],
        'rang' => $options[CA_Etransactions_Constants::OptionRangID],
        'id' => $options[CA_Etransactions_Constants::OptionCustomerID],
        'secret' => $options[CA_Etransactions_Constants::OptionSecretKey],

        'command' => $result->order_ref,
        'total' => $product->free_amount !== '1' ? (float)$product->price : (float)sanitize_text_field($_REQUEST['free_amount']),
        'holder' => $result->email,
        'callbacks' => []
    ]);

    if (isset($options[CA_Etransactions_Constants::OptionAcceptedLandingPage])
        && $options[CA_Etransactions_Constants::OptionAcceptedLandingPage] !== '') {
        $data->getCallbacks()->setDoneCallback($options[CA_Etransactions_Constants::OptionAcceptedLandingPage]);
    }

    if (isset($options[CA_Etransactions_Constants::OptionRejectedLandingPage]) && $options[CA_Etransactions_Constants::OptionRejectedLandingPage] !== '') {
        $data->getCallbacks()->setDeniedCallback($options[CA_Etransactions_Constants::OptionRejectedLandingPage]);
    }

    if (isset($options[CA_Etransactions_Constants::OptionCanceledLandingPage]) && $options[CA_Etransactions_Constants::OptionCanceledLandingPage] !== '') {
        $data->getCallbacks()->setCanceledCallback($options[CA_Etransactions_Constants::OptionCanceledLandingPage]);

    }

    $etransaction->setTransactionData($data);

    $str = $preprod === true ? '<div class="etransactions-warning">! Caution ! You are in test mode </div>' : '';
    $str .= '<form action="' . $etransaction->getServerAddress() . '" class="etransactions-product-form" method="post">';

    if ($attrs['no-label'] === false) {
        $str .= '<p class="etransactions-product-desc">
            <span class="etransactions-product-name">' . __('Product:', 'etransactions') . ' ' . stripcslashes($product->name) . '</span>
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
            <input type="submit" value="' . __('Proceed to payement', 'etransactions') . '" >
        </p>
    </form>';

    return $str;
});

add_shortcode('etransactions-product-name', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = CA_Etransactions_ProductDB::get_instance()->getById($product_id);

    return stripcslashes($product->name);
});

add_shortcode('etransactions-product-price', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = sanitize_text_field($_REQUEST['product']);
    $product = CA_Etransactions_ProductDB::get_instance()->getById($product_id);
    return $product->price . '&nbsp;&euro;';
});

add_shortcode('etransactions-accepted-page', function ($attrs = [], $content = '') {
    $result = TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        CA_Etransactions_TransactionDB::get_instance()->set_transaction_succeed($result->getReference()->getValue());
    }

    return '';
});

add_shortcode('etransactions-canceled-page', function ($attrs = [], $content = '') {
    $result = TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        CA_Etransactions_TransactionDB::get_instance()->set_transaction_canceled($result->getReference()->getValue());
    }

    return '';
});

add_shortcode('etransactions-rejected-page', function ($attrs = [], $content = '') {
    $result = TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        CA_Etransactions_TransactionDB::get_instance()->set_transaction_rejected($result->getReference()->getValue());
    }
    return '';
});
