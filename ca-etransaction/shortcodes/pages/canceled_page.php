<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/ETransactions_TransactionDB.php';

function canceled_page ($attrs = [], $content = '')
{
    $result = TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        ETransactions_TransactionDB::get_instance()
            ->set_transaction_canceled($result->getReference()->getValue());
    }

    return '';
}
