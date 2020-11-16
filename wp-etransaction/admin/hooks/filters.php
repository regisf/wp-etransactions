<?php

add_filter('etransactions_is_nonce_exists', function ($nonce) {
    $nonce = esc_attr($nonce);
    return wp_verify_nonce($nonce, ETransactions_NonceName);
});

add_filter('etransactions_nonce_input', function () {
    return '<input type="hidden" name="_wpnonce" value="' . wp_create_nonce(ETransactions_NonceName) . '" />';
});

add_filter('etransaction_get_validation_address', function ($id) {
    $options = get_option(ETransactions_Constants::OptionName);
    $validation_page = $options[ETransactions_Constants::OptionValidationPage];

    $sep = strchr($validation_page, '?') !== false ? '&' : '?';
    return $validation_page . $sep . 'product=' . $id;
});

add_filter('etransaction_get_confirmation_address', function ($id) {
    $options = get_option(ETransactions_Constants::OptionName);
    if (isset($options[ETransactions_Constants::OptionConfirmationPage])) {
        $validation_page = $options[ETransactions_Constants::OptionConfirmationPage];

        $sep = strchr($validation_page, '?') !== false ? '&' : '?';
        return $validation_page . $sep . 'product=' . $id;
    }

    return __('Warning: Confirmation page is not set', ETransactions_Constants::EtransactionsTr);
});