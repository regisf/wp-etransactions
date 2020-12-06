<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_TransactionDB.php';

function accepted_page ($attrs = [], $content = '') {
    $result = \TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        $ref_value = $result->getReference()->getValue();
        \ETransactions_TransactionDB::get_instance()
            ->set_transaction_succeed($ref_value->getValue());

        \Email::send_mail();
    }

    return '';
}