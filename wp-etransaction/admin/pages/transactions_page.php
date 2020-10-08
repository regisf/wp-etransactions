<?php
if (!current_user_can('manage_options')) {
    return;
}

require_once __DIR__ . '/../db/TransactionDB.php';
require_once __DIR__ . '/widgets/transaction_list_table.php';

$transaction_db = TransactionDB::get_instance();
$order_list_table = new Transaction_List_Table($transaction_db);

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>

    <hr class="wp-header-end">

    <ul class="subsubsub">
        <li class="all">
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_transactions">
                <?php echo __("All", "etransactions"); ?>
                (<?php echo $transaction_db->get_all_count(); ?>)
            </a>
        </li>
        |
        <li class="successful">
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_transactions&order_status=accepted">
                <?php echo __("Successful", "etransactions"); ?>
                (<?php echo $transaction_db->get_success_count(); ?>)
            </a>
        </li>
        |
        <li class="reject">
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_transactions&order_status=rejected">
                <?php echo __("Rejected", "etransactions"); ?>
                (<?php echo $transaction_db->get_reject_count(); ?>)
            </a>
        </li>
        |
        <li class="cancel">
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_transactions&order_status=canceled">
                <?php echo __("Canceled", "etransactions"); ?>
                (<?php echo $transaction_db->get_cancel_count(); ?>)
            </a>
        </li>

    </ul>

    <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="post-body-content">
                <div class="meta-box-sortables ui-sortable">
                    <form method="post">
                        <?php $order_list_table->display(); ?>
                    </form>
                </div>
            </div>
        </div>
        <br class="clear">
    </div>
</div>
