<?php

register_activation_hook(__FILE__, function () {
    $productDb = new ProductsDb();
    $productDb->install();
});

register_deactivation_hook(__FILE__, function() {
    //
});
