<?php
if (!current_user_can('manage_options')) {
    return;
}
?>
<div style="padding: 30px; max-width: 50%; margin: 0 auto; font-size: 150%; background-color: #ebebeb; border: 1px solid grey; border-radius: 5px">
    <form method="post" action="<?php echo admin_url('admin-post.php') ;?>">
        <input type="hidden" name="action" value="delete_product">
        <input type="hidden" name="product_ID" value="<?php echo $item->product_id; ?>">
        <?php echo apply_filters('etransactions_nonce_input', ETransactions_NonceName); ?>

        Are you shoure you want to delete this product:
        <strong>&laquo;<?php echo $item->name; ?>&raquo;</strong>
        <p style="text-align: right">

            <a class="button" href="<?php echo admin_url('admin.php') ;?>?page=etransactions_products">No</a>
            <input type="submit" value="<?php _e('Yes', ETransactions_Tr); ?>" class="button button-primary"/>
        </p>
    </form>
</div>

