<?php

class ETransaction_Email {
    public static function send_email() {
        $options = get_option(ETransactions_OptionName);
        $activate = $options['email_activate'];

//        wp_email(
//
//        );
    }

    public static function send_test_email($to, $title, $body) {

    }
}