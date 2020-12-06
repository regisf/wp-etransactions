<?php

namespace shortcodes;

// buying flow
require_once plugin_dir_path(__FILE__) . 'pages/products_page.php';
require_once plugin_dir_path(__FILE__) . 'pages/confirmation_page.php';
require_once plugin_dir_path(__FILE__) . 'pages/validation_page.php';

// Callbacks
require_once plugin_dir_path(__FILE__) . 'pages/accepted_page.php';
require_once plugin_dir_path(__FILE__) . 'pages/canceled_page.php';
require_once plugin_dir_path(__FILE__) . 'pages/rejected_page.php';

// tags
require_once plugin_dir_path(__FILE__) . 'tags/product_name.php';
require_once plugin_dir_path(__FILE__) . 'tags/product_price.php';