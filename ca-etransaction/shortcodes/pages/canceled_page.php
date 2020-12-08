<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/transactiondb.php';
require_once plugin_dir_path(__FILE__) . '../../etransactions/ETransactions/ETransaction.php';

function canceled_page ($attrs = [], $content = '')
{
    $result = \TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        \TransactionDB::get_instance()
            ->set_transaction_canceled($result->getReference()->getValue());
    }

    return '';
}
