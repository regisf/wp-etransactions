<?php

require_once __DIR__ . '/admin/productsdb.php';
require_once plugin_dir_path(__FILE__) . 'etransactions/ETransactions/ETransaction.php';

add_shortcode('etransactions_products', function ($attrs = [], $content = '') {
    $producDb = ProductsDb::get_instance();
    $actives = $producDb->get_actives();
    $str = '';

    foreach ($actives as $product) {

        $str .= '<div class="etransactions-product-wrapper">
            <div class="etransactions-product-name">'
            . $product->name . '
            </div>
            <div class="etransactions-product-price">'
            . $product->price . '&nbsp;&euro;
            </div>
            <div class="etransactions-product-apply">
                <a href="' . apply_filters('etransaction_get_order_address', $product->product_id) . ' ">' . __($content, 'etransactions') . '</a>
            </div>
        </div>';
    }

    return $str;
});

add_shortcode('etransactions-accepted', function ($attrs = [], $content = '') {
    $amount = $_REQUEST['Mt'];
    $reference = $_REQUEST['Ref'];
    $authorization = $_REQUEST['Auto'];
    $error = $_REQUEST['Erreur'];
});

add_shortcode('etransactions-order-form', function ($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $attrs = shortcode_atts([
        'no-label' => true
    ], $attrs);

    $options = get_option('etransactions_options');
    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);
    $etransaction = new ETransaction(true);
    $data = TransactionData::fromData([
        'site' => $options['site_id'],
        'rang' => $options['rang_id'],
        'id' => $options['customer_id'],
        'secret' => $options['secret_key'],
        'command' => $product->name,
        'total' => (float)$product->price,
        'callbacks' => [
            'done' => $options['accepted_key'],
            'denied' => $options['rejected_key'],
            'canceled' => $options['canceled_key']
        ]
    ]);

    $etransaction->setTransactionData($data);
    $str = '<form action="' . $etransaction->getServerAddress() . '" class="etransactions-product-form">';

    if ($attrs['no-label'] === false)  {
        $str .= '<p class="etransactions-product-desc"> 
        <span class="etransactions-product-name">' . __('Product:', 'etransactions') . ' ' . $product->name . '</span> 
        <span class="etransactions-product-price">' . $product->price . '&nbsp;&euro;</span>
        </p>';
    }

    $str .= HolderValue::emptyForm() .
        $etransaction->getTransactionForm() .
        '<p class="etransactions-product-submit">
            <input type="submit" value="' . __('Proceed to payement', 'etransactions') . '" >
        </p>
    </form>';

    return $str;
});

add_shortcode('etransactions-product-name', function($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);

    return $product->name;
});

add_shortcode('etransactions-product-price', function($attrs = [], $content = '') {
    if (!isset($_REQUEST['product'])) {
        return '';
    }

    $product_id = esc_sql($_REQUEST['product']);
    $product = ProductsDb::get_instance()->getById($product_id);
    return $product->price;
});