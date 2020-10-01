<?php

add_filter('is_nonce_exists', function ($nonce) {
    $nonce = esc_attr($nonce);
    return wp_verify_nonce($nonce, NonceName);
});

add_filter('nonce_input', function () {
    return '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce(NonceName) . '" />';
});
