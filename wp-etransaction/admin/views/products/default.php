<?php
if (!current_user_can('manage_options')) {
    return;
}

$product_list_table = new Product_List_Table(ProductsDb::get_instance());
$status = isset($_REQUEST['product_status']) ? $_REQUEST['product_status'] : 'all';
?>

<a class="page-title-action" href="admin.php?page=etransactions_products&product_action=new">Add</a>
<hr class="wp-header-end">

<ul class="subsubsub">
    <li class="all">
        <?php if ($status): ?>
            <a href="/wp-admin/admin.php?page=etransactions_products">
                <?php echo __("All", "etransactions"); ?>
                (<?php echo $product_list_table->get_all_count(); ?>)
            </a>
        <?php else: ?>
            <strong><?php echo __("All", "etransactions"); ?></strong>
            (<?php echo $product_list_table->get_all_count(); ?>)
        <?php endif; ?>
    </li>
    |
    <li class="active">
        <?php if ('active' !== $status): ?>
            <a href="/wp-admin/admin.php?page=etransactions_products&product_status=active">
                <?php echo __("Active", "etransactions"); ?>
                (<?php echo $product_list_table->get_active_count(); ?>
                )
            </a>
        <?php else: ?>
            <strong><?php echo __("Active", "etransactions"); ?></strong>
            (<?php echo $product_list_table->get_active_count(); ?>)
        <?php endif; ?>
    </li>
    |
    <li class="inactive">
        <?php if ('inactive' !== $status): ?>
            <a href="/wp-admin/admin.php?page=etransactions_products&product_status=inactive">
                <?php echo __("Inactive", "etransactions"); ?>
                (<?php echo $product_list_table->get_inactive_count(); ?>)
            </a>
        <?php else: ?>
            <strong><?php echo __("Inactive", "etransactions"); ?></strong>
            (<?php echo $product_list_table->get_inactive_count(); ?>)
            </a>
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
