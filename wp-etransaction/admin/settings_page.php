<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', 'etransactions'), 'updated');
}

$options = get_option('etransactions_options');

$accepted = isset($options['accepted_key']) ? $options['accepted_key'] : null;
$rejected = isset($options['rejected_key']) ? $options['rejected_key'] : null;
$canceled = isset($options['canceled_key']) ? $options['canceled_key'] : null;

?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <?php if (!($accepted && $rejected && $canceled)) { ?>
    <div class="error notice">
        <?php
        settings_fields('etransactions_section_callbacks');
        ?>
        <?php if (!($accepted || $rejected || $canceled)) { ?>
            <div>
                <p>The required landing pages are not created. You should create them. </p>
            </div>
        <?php } else {
            if (!$accepted) {
                ?>
                <p>The payement accepted landin pages is not created. You should create it.</p>
                <?php
            }
            if (!$rejected) {
                ?>
                <p>The payement rejected landing page is not created. You should create it.</p>
                <?php
            }
            if (!$canceled) {
                ?>
                <p>The cancelation landing pages is not created. You should create it.</p>
                <?php
            }
        } ?>
    </div>
    <?php } ?>

    <form action="options.php" method="post">
        <?php
        settings_fields('etransactions');
        do_settings_sections('etransactions');
        submit_button(__('Save', 'etransactions'));
        ?>
    </form>

</div>