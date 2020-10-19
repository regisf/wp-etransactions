<?php
if (!current_user_can('manage_options')) {
    return;
}

$product_list_table = new CA_Etransactions_Product_List_Table(CA_Etransactions_ProductDB::get_instance());
$status = isset($_REQUEST['product_status']) ? $_REQUEST['product_status'] : 'all';
?>

<a class="page-title-action" href="<?php echo admin_url('admin.php'); ?>?page=etransactions_products&product_action=new">Add</a>
<hr class="wp-header-end">

<ul class="subsubsub">
    <li class="all">
        <?php if ($status): ?>
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_products">
                <?php _e("All", 'etransactions'); ?>
                (<?php echo CA_Etransactions_ProductDB::get_instance()->get_all_count(); ?>)
            </a>
        <?php else: ?>
            <strong><?php _e("All", 'etransactions'); ?></strong>
            (<?php echo CA_Etransactions_ProductDB::get_instance()->get_all_count(); ?>)
        <?php endif; ?>
    </li>
    |
    <li class="active">
        <?php if ('active' !== $status): ?>
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_products&product_status=active">
                <?php _e("Active", 'etransactions'); ?>
                (<?php echo CA_Etransactions_ProductDB::get_instance()->get_actives_count(); ?>
                )
            </a>
        <?php else: ?>
            <strong><?php _e("Active", 'etransactions'); ?></strong>
            (<?php echo CA_Etransactions_ProductDB::get_instance()->get_actives_count(); ?>)
        <?php endif; ?>
    </li>
    |
    <li class="inactive">
        <?php if ('inactive' !== $status): ?>
            <a href="<?php echo admin_url('admin.php'); ?>?page=etransactions_products&product_status=inactive">
                <?php _e("Inactive", 'etransactions'); ?>
                (<?php echo CA_Etransactions_ProductDB::get_instance()->get_inactives_count(); ?>)
            </a>
        <?php else: ?>
            <strong><?php _e("Inactive", 'etransactions'); ?></strong>
            (<?php echo CA_Etransactions_ProductDB::get_instance()->get_inactives_count(); ?>)
        <?php endif; ?>

    </li>
</ul>

<div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
                <form method="post">
                    <?php $product_list_table->display(); ?>
                </form>
            </div>
        </div>
    </div>
    <br class="clear">
</div>
