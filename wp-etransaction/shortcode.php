<?php

require_once __DIR__ . '/admin/productsdb.php';
require_once __DIR__ . '/admin/orderdb.php';
require_once plugin_dir_path(__FILE__) . 'etransactions/ETransactions/ETransaction.php';
require_once plugin_dir_path(__FILE__) . 'etransactions/ETransactions/TransactionResult.php';

add_shortcode('etransactions-products', function ($attrs = [], $content = '') {
    $producDb = ProductsDb::get_instance();
    $actives = $producDb->get_actives();
    $str = '';

    if (strlen($content) === 0) {
        $content = __('Missing link name in content', 'etransactions');
    }

    foreach ($actives as $product) {
        $str .= '<div class="etransactions-product-wrapper">
            <div class="etransactions-product-name">'
            . $product->name . '
            </div>
            <div class="etransactions-product-price">'
            . $product->price . '&nbsp;&euro;
            </div>
            <div class="etransactions-product-apply">
                <a href="' . apply_filters('etransaction_get_confirmation_address', $product->product_id) . ' ">' . $content . '</a>
            </div>
        </div>';
    }

    return $str;
});

function etransactions_get_email_form($product, $no_label)
{
    $options = get_option('etransactions_options');
    $preprod = isset($options['test_id']);

    $str = $preprod ? '<div class="etransactions-warning">Caution ! You are in test mode </div>' : '';
    if ($no_label === false) {
        $str .= '<p class="etransactions-product-desc">
        <span class="etransactions-product-name">' . __('Product:', 'etransactions') . ' ' . $product->name . '</span>
        <span class="etransactions-product-price">' . $product->price . '&nbsp;&euro;</span>
        </p>';
    }

    $str .= '
        <form action="' . apply_filters('etransaction_get_validation_address', $product->product_id) . '" class="etransactions-product-form" method="post">
            <input type="hidden" name="product" value="' . $product->product_id . '"  />' .
        HolderValue::emptyForm() .
        '<p class="etransactions-product-submit">
                <input type="submit" value="' . __('Confirm payement', 'etransactions') . '" >
            </p>
        </form>';

    return $str;
}

function etransactions_get_confirm_form($product, $result, $no_label)
{
    $options = get_option('etransactions_options');
    $preprod = isset($options['test_id']);
    $etransaction = new ETransaction($preprod);
    $data = TransactionData::fromData([
        'site' => $options['site_id'],
        'rang' => $options['rang_id'],
        'id' => $options['customer_id'],
        'secret' => $options['secret_key'],
        'command' => $result->order_ref,
        'total' => (float)$product->price,
        'holder' => $result->email,
        'callbacks' => []
    ]);

    if (isset($options['accepted_key']) && $options['accepted_key'] !== '') {
        $data->getCallbacks()->setDoneCallback($options['accepted_key']);
    }

    if (isset($options['rejected_key']) && $options['rejected_key'] !== '') {
        $data->getCallbacks()->setDeniedCallback($options['rejected_key']);
    }

    if (isset($options['canceled_key']) && $options['canceled_key'] !== '') {
        $data->getCallbacks()->setCanceledCallback($options['canceled_key']);

    }

    $etransaction->setTransactionData($data);

    $str = $preprod === true ? '<div class="etransactions-warning">! Caution ! You are in test mode </div>' : '';
    $str .= '<form action="' . $etransaction->getServerAddress() . '" class="etransactions-product-form" method="post">';

    if ($no_label === false) {
        $str .= '<p class="etransactions-product-desc"> 
            <span class="etransactions-product-name">' . __('Product:', 'etransactions') . ' ' . $product->name . '</span> 
            <span class="etransactions-product-price">' . $product->price . '&nbsp;&euro;</span>
        </p>';
    }

    $str .= $etransaction->getTransactionForm() .
        '<p class="etransactions-product-submit">
            <input type="submit" value="' . __('Proceed to payement', 'etransactions') . '" >
        </p>
    </form>';

    return $str;
}

add_shortcode('etransactions-order-form', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '<p>Error: No product id set</p>';
    }

    $attrs = shortcode_atts([
        'no-label' => true,
        'step' => 'email',
    ], $attrs);

    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);

    switch ($attrs['step']) {
        case 'email':
            $str = etransactions_get_email_form($product, $attrs['no-label']);
            break;

        case 'confirm':
            $holder = esc_sql($_REQUEST['PBX_PORTEUR']);
            $ref = wp_generate_uuid4();
            $result = OrderDb::get_instance()->insert_order($product_id, $holder, $ref, $product->price);
            $str = etransactions_get_confirm_form($product, $result, $attrs['no-label']);
            break;

        default:
            $str = '<!-- etransactions-order-form : Wrong step attribute -->';
    }

    return $str;
});

add_shortcode('etransactions-product-name', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);

    return $product->name;
});

add_shortcode('etransactions-product-price', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);
    return $product->price;
});

add_shortcode('etransactions-accepted', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['post'])) {
        $result = TransactionResult::fromRequest($_REQUEST);
        OrderDb::get_instance()->set_transaction_succeed($result->getReference()->getValue());
    }
    return '';
});

add_shortcode('etransactions-canceled', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['post'])) {
        $result = TransactionResult::fromRequest($_REQUEST);
        OrderDb::get_instance()->set_transaction_canceled($result->getReference()->getValue());
    }
    return '';
});


add_shortcode('etransactions-rejected', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['post'])) {
        $result = TransactionResult::fromRequest($_REQUEST);
        OrderDb::get_instance()->set_transaction_rejected($result->getReference()->getValue());
    }
    return '';
});
