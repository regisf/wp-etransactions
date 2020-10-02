<?php

register_activation_hook(__FILE__, function () {
    // Install database
    $productDb = ProductsDb::get_instance();
    $productDb->install();
});

register_deactivation_hook(__FILE__, function () {
    //
});
