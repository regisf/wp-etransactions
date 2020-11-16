<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', ETransactions_Constants::EtransactionsTr), 'updated');
}

$options = get_option(ETransactions_Constants::OptionName);
$accepted = isset($options[ETransactions_Constants::OptionAcceptedLandingPage]) ? $options[ETransactions_Constants::OptionAcceptedLandingPage] : null;
$rejected = isset($options[ETransactions_Constants::OptionRejectedLandingPage]) ? $options[ETransactions_Constants::OptionRejectedLandingPage] : null;
$canceled = isset($options[ETransactions_Constants::OptionCanceledLandingPage]) ? $options[ETransactions_Constants::OptionCanceledLandingPage] : null;
$validation = isset($options[ETransactions_Constants::OptionValidationPage]) ? $options[ETransactions_Constants::OptionValidationPage] : null;

if (!$accepted) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement accepted landing pages is not created. You should create it.', ETransactions_Constants::EtransactionsTr),
        'error'
    );
}

if (!$rejected) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement rejected landing pages is not created. You should create it.', ETransactions_Constants::EtransactionsTr),
        'error'
    );
}

if (!$canceled) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement canceled landing pages is not created. You should create it.', ETransactions_Constants::EtransactionsTr),
        'error'
    );
}

if (!$validation) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement validation pages is not created. You should create it.', ETransactions_Constants::EtransactionsTr),
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
        do_settings_sections(ETransactions_Constants::PageName);
        submit_button(__('Save', ETransactions_Constants::EtransactionsTr));
        ?>
    </form>

</div>