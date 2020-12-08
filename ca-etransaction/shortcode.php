<?php

require_once plugin_dir_path(__FILE__) . 'shortcodes/all.php';

// List of all products ------------------------------------------------------
add_shortcode('etransactions-products-list', 'shortcodes\pages\products_list');

// Validation pages
add_shortcode('etransactions-confirmation-page', 'shortcodes\pages\confirmation_page');

add_shortcode('etransactions-validation-page', 'shortcodes\pages\validation_page');

// Tags ----------------------------------------------------------------------
add_shortcode('etransactions-product-name', 'shortcodes\tags\product_name');

add_shortcode('etransactions-product-price', 'shortcodes\tags\product_price');

// Callback from eTransactions platform --------------------------------------
add_shortcode('etransactions-accepted-page', 'shortcodes\pages\accepted_page');

add_shortcode('etransactions-canceled-page', 'shortcodes\pages\canceled_page');

add_shortcode('etransactions-rejected-page', 'shortcodes\pages\rejected_page');
