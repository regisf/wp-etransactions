<?php

add_action('admin_init', function () {
    register_setting('etransactions', CA_Etransactions_Constants::OptionName);

    add_settings_section(
        'etransactions_section_debug',
        __('Test mode', 'etransactions'),
        'etransaction_test_mode_page',
        CA_Etransactions_Constants::PageName
    );

    add_settings_field(
        'etransactions_test_mode_id',
        __('Test mode', 'etransactions'),
        'etransactions_checkbox_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'test_id',
            'help' => __('<span style="color: red">By checking this option, you will switch you payement system into ' .
                'to test mode and you will not be able to receive real payement.</span>', 'etransactions'),
        ]
    );

    add_settings_field(
        'etransactions_hmac_preprod_key',
        __('HMAC Preprod Key', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'preprod_key',
            'help' => __('The secret key generated into the preprod-guest.etransaction.fr/Vision backoffice', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_settings',               // ID
        __('Credentials', 'etransactions'), // Title
        'etransactions_settings_callback',         // Callback
        CA_Etransactions_Constants::PageName
    );

    add_settings_field(
        'etransactions_site_id',
        __('Site Number', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'site_id',
            'maxlength' => '7',
            'help' => __('The Site ID given by the e-Transaction support (7 digits max)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_rang_id',
        __('Rang', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'rang_id',
            'maxlength' => '3',
            'help' => __('The Rang ID given by the e-Transaction support (3 digits max)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_customer_id',
        __('Customer', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'customer_id',
            'maxlength' => '9',
            'help' => __('Your customer ID given by the e-Transaction support (from 1 to 9 digitis)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_secret_key',
        __('Secret Key', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'secret_key',
            'help' => __('The secret key generated into the etransaction.fr backoffice', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_validate',
        __('Payement validation pages', 'etransactions'),
        'etransactions_validate_page',
        CA_Etransactions_Constants::PageName
    );

    add_settings_field(
        'etransactions_confirmation_page',
        __('Confirmation pages', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_validate',
        [
            'label_for' => CA_Etransactions_Constants::OptionConfirmationPage,
            'placeholder' => __('Confirmation pages url', 'etransactions'),
            'help' => __('The URL of the confirmation pages.', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_validation_page',
        __('Validation pages', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_validate',
        [
            'label_for' => 'validation_page',
            'placeholder' => __('Validation pages url', 'etransactions'),
            'help' => __('The URL of the validation pages.', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_callbacks',               // ID
        __('Callbacks pages', 'etransactions'), // Title
        'etransactions_callback_pages',         // Callback
        CA_Etransactions_Constants::PageName
    );

    add_settings_field(
        'etransactions_accepted_key',
        __('Accepted pages', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => CA_Etransactions_Constants::OptionAcceptedLandingPage,
            'placeholder' => __('URL for the accepted pages', 'etransactions'),
            'help' => __('The pages where the user lands when the payement is accepted.', 'etransactions')
        ]
    );

    add_settings_field(
        CA_Etransactions_Constants::PluginPrefix . CA_Etransactions_Constants::OptionRejectedLandingPage,
        __('Rejected pages', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => CA_Etransactions_Constants::OptionRejectedLandingPage,
            'placeholder' => __('URL for the rejected pages', 'etransactions'),
            'help' => __('The pages where the user lands when the payement is rejected by the bank.', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_canceled_key',
        __('Canceled pages', 'etransactions'),
        'etransactions_field_cb',
        CA_Etransactions_Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => CA_Etransactions_Constants::OptionCanceledLandingPage,
            'placeholder' => __('URL for the canceled pages', 'etransactions'),
            'help' => __('The pages where the user lands when the payement is canceled.', 'etransactions')
        ]
    );

    /*
     * Display the CA logo and a message.
     */
    function etransactions_settings_callback($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?php esc_html_e('Fill the IDs you received from the e-Transaction support. ', 'etransactions'); ?>
        </p>
        <?php
    }

    /**
     * Display an entry field
     * @param array $args
     */
    function etransactions_field_cb($args)
    {
        $options = get_option(CA_Etransactions_Constants::OptionName);
        $label_for = sanitize_text_field($args['label_for']);
        ?>

        <input
                id="<?php echo $label_for; ?>"
                name="etransactions_options[<?php echo $label_for; ?>]"
                <?php if (isset($args['type'])) { ?>type="<?php echo $args['type']; ?>"<?php } ?>
                <?php if (isset($args['maxlength'])) { ?>maxlength="<?php echo $args['maxlength']; ?>"<?php } ?>
                <?php if (isset($args['placeholder'])) { ?>placeholder="<?php echo $args['placeholder']; ?>"<?php } ?>
                value="<?php if (isset($options[$args['label_for']])) {
                    echo $options[$args['label_for']];
                } ?>"
        />

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

    function etransactions_checkbox_cb($args)
    {
        $options = get_option(CA_Etransactions_Constants::OptionName);
        $label_for = sanitize_text_field($args['label_for']);
        ?>
        <input
                id="<?php echo $label_for; ?>"
                name="etransactions_options[<?php echo $label_for; ?>]"
                type="checkbox"
                <?php if ($options['test_id']) { ?>checked="<?php $options['test_id'] ?>" <?php } ?> />

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

    function etransaction_test_mode_page($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            _e('Switching in test mode (using preprod servers). No transaction will be effective.', 'etransactions');
            ?>
        </p>
        <?php
    }

    function etransactions_callback_pages($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            _e('The etransaction needs three pages for managing the CA e-Transaction service. The user lands on this pages in these cases.', 'etransactions');
            ?>
        </p>
        <?php
    }

    function etransactions_validate_page($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            _e('The etransaction plugin need a validation pages. The user enter here its email address and give it consenting for the transaction', 'etransactions');
            ?>
        </p>
        <?php
    }
});