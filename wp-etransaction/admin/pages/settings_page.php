<?php
if (!current_user_can('manage_options')) {
    return;
}

if (isset($_GET['settings-updated'])) {
    add_settings_error('etransactions_messages', 'etransactions_message', __('Settings Saved', 'etransactions'), 'updated');
}

$options = get_option(CA_Etransactions_Constants::OptionName);
$accepted = isset($options[CA_Etransactions_Constants::OptionAcceptedLandingPage]) ? $options[CA_Etransactions_Constants::OptionAcceptedLandingPage] : null;
$rejected = isset($options[CA_Etransactions_Constants::OptionRejectedLandingPage]) ? $options[CA_Etransactions_Constants::OptionRejectedLandingPage] : null;
$canceled = isset($options[CA_Etransactions_Constants::OptionCanceledLandingPage]) ? $options[CA_Etransactions_Constants::OptionCanceledLandingPage] : null;
$validation = isset($options[CA_Etransactions_Constants::OptionValidationPage]) ? $options[CA_Etransactions_Constants::OptionValidationPage] : null;

if (!$accepted) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement accepted landing pages is not created. You should create it.', 'etransactions'),
        'error'
    );
}

if (!$rejected) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement rejected landing pages is not created. You should create it.', 'etransactions'),
        'error'
    );
}

if (!$canceled) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement canceled landing pages is not created. You should create it.', 'etransactions'),
        'error'
    );
}

if (!$validation) {
    add_settings_error(
        'etransactions',
        'missing-pages',
        __('The payement validation pages is not created. You should create it.', 'etransactions'),
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
        do_settings_sections(CA_Etransactions_Constants::PageName);
        submit_button(__('Save', 'etransactions'));
        ?>
    </form>

</div>