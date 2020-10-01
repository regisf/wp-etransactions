<?php

require_once __DIR__ . '/admin/productsdb.php';

add_shortcode('etransactions_products', function ($attrs = [], $content = '') {
    $producDb = new ProductsDb();
    $actives = $producDb->get_actives();

    foreach ($actives as $product) {
        ?>
        <div class="etransactions-product-wrapper">
            <div class="etransactions-product-name">
                <?php echo $product->name; ?>
            </div>
            <div class="etransactions-product-price">
                <?php echo $product->price; ?>&nbsp;&euro;
            </div>
            <div class="etransactions-product-apply">
                <a href="/etransactions/proceed/?product=<?php echo $product->product_id; ?>"><?php echo __($content, 'etransactions'); ?></a>
            </div>
        </div>
        <?php
    }
});
