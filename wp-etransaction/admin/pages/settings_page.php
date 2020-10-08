<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', Constants::EtransactionsTr), 'updated');
}

$options = get_option(Constants::OptionName);
$accepted = isset($options[Constants::OptionAcceptedLandingPage]) ? $options[Constants::OptionAcceptedLandingPage] : null;
$rejected = isset($options[Constants::OptionRejectedLandingPage]) ? $options[Constants::OptionRejectedLandingPage] : null;
$canceled = isset($options[Constants::OptionCanceledLandingPage]) ? $options[Constants::OptionCanceledLandingPage] : null;
$validation = isset($options[Constants::OptionValidationPage]) ? $options[Constants::OptionValidationPage] : null;

if (!$accepted) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement accepted landing pages is not created. You should create it.', Constants::EtransactionsTr),
        'error'
    );
}

if (!$rejected) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement rejected landing pages is not created. You should create it.', Constants::EtransactionsTr),
        'error'
    );
}

if (!$canceled) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement canceled landing pages is not created. You should create it.', Constants::EtransactionsTr),
        'error'
    );
}

if (!$validation) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement validation pages is not created. You should create it.', Constants::EtransactionsTr),
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
        do_settings_sections(Constants::PageName);
        submit_button(__('Save', Constants::EtransactionsTr));
        ?>
    </form>

</div>