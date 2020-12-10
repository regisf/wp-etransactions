<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/transactiondb.php';

function accepted_page ($attrs = [], $content = '') {
    $result = \TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        $ref_value = $result->getReference()->getValue();
        \ETransaction_TransactionDB::get_instance()
            ->set_transaction_succeed($ref_value->getValue());

        \ETransaction_Email::send_mail();
    }

    return '';
}