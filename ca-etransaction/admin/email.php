<?php

class ETransaction_Email
{
    public static function send_email()
    {
        $options = get_option(ETransactions_OptionName);
        if (!isset($options->options['email_activate'])) {
            return false;
        }

        $title = $options->options['email_title'];
        $content = $options->options['email_content'];
        $to = $options->options['email_email'];

        if ($title && $content && $to) {
            return wp_email($to, $title, $content);
        }

        return false;
    }
}
