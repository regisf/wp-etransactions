<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', 'etransactions'), 'updated');
}

$accepted = get_option('etransactions_accepted_page');
$rejected = get_option('etransactions_rejected_page');
$canceled = get_option('etransactions_canceled_page');
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <div class="error notice">
            <?php
            settings_fields('etransactions_section_callbacks');
            ?>
            <?php if (!($accepted && $rejected && $canceled)) { ?>
                <div>
                    <p>The required landing pages are not created. You should create them
                        <a href="/wp-admin/admin.php?page=etransactions&action=create_missing_page&pages=accept,reject,cancel">Create
                            default landing pages</a></p>
                </div>
            <?php } else {
                if (!$accepted) {
                    ?>
                    <p>The payement accepted landin pages is not created. You should create it.
                        <a href="/wp-admin/admin.php?page=etransactions&action=create_missing_page&pages=accept">Create
                            the accepted landing page</a></p>
                    <?php
                }
                if (!$rejected) {
                    ?>
                    <p>The payement rejected landing page is not created. You should create it.
                        <a href="/wp-admin/admin.php?page=etransactions&action=create_missing_page&pages=reject">Create
                            the rejection landing page.</a></p>
                    <?php
                }
                if (!$canceled) {
                    ?>
                    <p>The cancelation landing pages is not created. You should create it.
                        <a href="/wp-admin/admin.php?page=etransactions&action=create_missing_page&pages=cancel">Create
                            the cancelation landing page.</a></p>
                    <?php
                }
            } ?>
    </div>
    <form action="options.php" method="post">
        <?php
        settings_fields('etransactions');
        do_settings_sections('etransactions');
        submit_button(__('Save', 'etransactions'));
        ?>
    </form>

</div>