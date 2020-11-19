<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', 'etransaction-plugin'), 'updated');
}

$options = get_option(ETransactions_OptionName);
$accepted = isset($options[ETransactions_OptionAcceptedLandingPage]) ? $options[ETransactions_OptionAcceptedLandingPage] : null;
$rejected = isset($options[ETransactions_OptionRejectedLandingPage]) ? $options[ETransactions_OptionRejectedLandingPage] : null;
$canceled = isset($options[ETransactions_OptionCanceledLandingPage]) ? $options[ETransactions_OptionCanceledLandingPage] : null;
$validation = isset($options[ETransactions_OptionValidationPage]) ? $options[ETransactions_OptionValidationPage] : null;

if (!$accepted) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement accepted landing page is not created. You should create it.', 'etransaction-plugin'),
        'error'
    );
}

if (!$rejected) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement rejected landing page is not created. You should create it.', 'etransaction-plugin'),
        'error'
    );
}

if (!$canceled) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement canceled landing page is not created. You should create it.', 'etransaction-plugin'),
        'error'
    );
}

if (!$validation) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement validation page is not created. You should create it.', 'etransaction-plugin'),
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
        do_settings_sections(ETransactions_PageName);
        submit_button(__('Save', 'etransaction-plugin'));
        ?>
    </form>

</div>