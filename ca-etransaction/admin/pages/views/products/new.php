<?php
if (!current_user_can('manage_options')) {
    return;
}

if (!isset($name)) {
    $name = '';
}
if (!isset($price)) {
    $price = '';
}
if (!isset($active)) {
    $active = '';
}
if (!isset($use_free_amount)) {
    $use_free_amount = '';
}
if (!isset($category)) {
    $category = '';
}

?>

<hr class="wp-header-end">
<?php if (!isset($item)): ?>
    <h2>Add a new product</h2>
<?php else: ?>
    <h2>Edit product</h2>
<?php endif; ?>

<form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
      id="form_new_product">
    <?php if (!isset($item)): ?>
        <input type="hidden" name="action" value="add_product"/>
    <?php else: ?>
        <input type="hidden" name="action" value="edit_product"/>
        <input type="hidden" name="product_ID"
               value="<?php echo $_REQUEST['product']; ?>"/>
    <?php endif; ?>
    <?php echo apply_filters('etransactions_nonce_input', ETransactions_NonceName); ?>

    <table class="form-table" role="presentation">
        <tbody>
        <tr>
            <th>
                <label for="id_name">Name:</label>
            </th>
            <td>
                <input type="text" name="name" id="id_name"
                       value="<?php echo stripcslashes($name); ?>" required
                       style="padding: 0.25rem; width: 25%"
                       placeholder="Enter here the product name"/>
                <div class="description">
                    <?php echo esc_html__('The product name as displayed on the web site', ETransactions_Tr); ?>
                </div>
            </td>
        </tr>

        <tr>
            <th>
                <label for="id_price">Price:</label>
            </th>
            <td>
                <input type="number" name="price" id="id_price"
                       value="<?php echo $price; ?>" required step="0.01"
                       placeholder="Enter the product price"
                       style="padding: 0.25rem"
                />
                <div class="description">
                    <?php _e('The product price.', ETransactions_Tr); ?>
                </div>
            </td>
        </tr>

        <!-- <tr>
            <th>
                <label for="id_category">Category:</label>
            </th>
            <td>
                <input type="text" name="category" id="id_category"
                       value="<?php echo $category; ?>"
                       placeholder="Enter the product category"
                       style="padding: 0.25rem"
                />
                <div class="description">
                    <?php _e('The product category.', ETransactions_Tr); ?>
                </div>
            </td>
        </tr> -->

        <tr>
            <th>
                <label for="id_free_amount"><?php _e('Free amount', ETransactions_Tr); ?>:</label>
            </th>
            <td>
                <input type="checkbox" name="free_amount" <?php if ($use_free_amount) {
                    echo "checked";
                } ?> id="id_free_amount"/>
            </td>
        </tr>

        <tr>
            <th>
                <label for="id_active">Is active:</label>
            </th>
            <td>
                <input type="checkbox" name="active" <?php if ($active) {
                    echo "checked";
                } ?> id="id_active"/>
            </td>
        </tr>
        </tbody>
    </table>

    <div>
        <a class="button"
           href="<?php echo admin_url('admin.php'); ?>?page=etransactions_products">Cancel</a>
        <input type="submit" name="submit" id="submit"
               class="button button-primary" value="Save">
    </div>
</form>
<script>
    const $ = jQuery;
    $(function () {
        $('#form_new_product').on('submit', function (e) {
            if (! $("#id_free_amount").prop('checked')) {
                const price = parseFloat($('[name=price]').val().trim());
                if (isNaN(price)) {
                    alert('Price must be a number');
                    e.preventDefault();
                }

                const name = $('[name=name]').val().trim();
                if (name.length === 0) {
                    alert("The product name can't be empty");
                    e.preventDefault();
                }
            }
        });

        $("#id_free_amount").on('change', function(e) {
            if (e.target.checked === true) {
                $("#id_price").removeAttr("required");
            } else if (e.target.checked === false) {
                $("#id_price").prop('required', true);
            }
        });
    });
</script>
