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
$validation = isset($options['validation_page']) ? $options['validation_page'] : null;

if (!$accepted) {
    add_settings_error(
        'etransactions',
        'missing-page',
        'The payement accepted landing page is not created. You should create it.',
        'error'
    );
}

if (!$rejected) {
    add_settings_error(
        'etransactions',
        'missing-page',
        'The payement rejected landing page is not created. You should create it.',
        'error'
    );
}

if (!$canceled) {
    add_settings_error(
        'etransactions',
        'missing-page',
        'The payement canceled landing page is not created. You should create it.',
        'error'
    );
}

if (!$validation) {
    add_settings_error(
        'etransactions',
        'missing-page',
        'The payement validation page is not created. You should create it.',
        'error'
    );
}

?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php settings_errors('etransactions') ?>
    <form action="<?php echo admin_url('options.php'); ?>" method="post">
        <?php
        settings_fields('etransactions');
        do_settings_sections('etransactions');
        submit_button(__('Save', 'etransactions'));
        ?>
    </form>

</div>