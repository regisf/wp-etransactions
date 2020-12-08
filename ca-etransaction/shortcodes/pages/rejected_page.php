<?php

namespace shortcodes\pages;

require_once plugin_dir_path(__FILE__) . '../../admin/db/transactiondb.php';


function rejected_page($attrs = [], $content = '')
{
    $result = \TransactionResult::fromRequest($_REQUEST);
    if ($result !== null) {
        \ETransaction_TransactionDB::get_instance()
            ->set_transaction_rejected($result->getReference()->getValue());
    }

    return '';
}
