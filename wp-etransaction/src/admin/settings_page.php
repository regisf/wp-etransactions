<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error( 'etransactions_messages', 'etransactions_message', __( 'Settings Saved', 'etransactions' ), 'updated' );
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
        <?php
        settings_fields('etransactions');
        do_settings_sections('etransactions');
        submit_button(__('Save', 'etransactions'));
        ?>
    </form>
</div>
