<?php

add_filter('is_nonce_exists', function ($nonce) {
    $nonce = esc_attr($nonce);
    return wp_verify_nonce($nonce, NonceName);
});

add_filter('nonce_input', function () {
    return '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce(NonceName) . '" />';
});

add_filter('etransaction_get_validation_address', function ($id) {
    $options = get_option(Constants::OptionName);
    $validation_page = $options[Constants::OptionValidationPage];

    $sep = strchr($validation_page, '?') !== false ? '&' : '?';
    return $validation_page . $sep . 'product=' . $id;
});

add_filter('etransaction_get_confirmation_address', function ($id) {
    $options = get_option(Constants::OptionName);
    if (isset($options[Constants::OptionConfirmationPage])) {
        $validation_page = $options[Constants::OptionConfirmationPage];

        $sep = strchr($validation_page, '?') !== false ? '&' : '?';
        return $validation_page . $sep . 'product=' . $id;
    }

    return __('Warning: Confirmation page is not set', 'etransactions');
});