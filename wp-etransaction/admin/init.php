<?php

add_action('admin_init', function () {
    register_setting('etransactions', Constants::OptionName);

    add_settings_section(
        'etransactions_section_debug',
        __('Test mode', Constants::EtransactionsTr),
        'etransaction_test_mode_page',
        Constants::PageName
    );

    add_settings_field(
        'etransactions_test_mode_id',
        __('Test mode', Constants::EtransactionsTr),
        'etransactions_checkbox_cb',
        Constants::PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'test_id',
            'help' => __('<span style="color: red">By checking this option, you will switch you payement system into ' .
                'to test mode and you will not be able to receive real payement.</span>', Constants::EtransactionsTr),
        ]
    );

    add_settings_field(
        'etransactions_hmac_preprod_key',
        __('HMAC Preprod Key', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'preprod_key',
            'help' => __('The secret key generated into the preprod-guest.etransaction.fr/Vision backoffice', Constants::EtransactionsTr)
        ]
    );

    add_settings_section(
        'etransactions_section_settings',               // ID
        __('Credentials', Constants::EtransactionsTr), // Title
        'etransactions_settings_callback',         // Callback
        Constants::PageName
    );

    add_settings_field(
        'etransactions_site_id',
        __('Site Number', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'site_id',
            'maxlength' => '7',
            'help' => __('The Site ID given by the e-Transaction support (7 digits max)', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        'etransactions_rang_id',
        __('Rang', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'rang_id',
            'maxlength' => '3',
            'help' => __('The Rang ID given by the e-Transaction support (3 digits max)', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        'etransactions_customer_id',
        __('Customer', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'customer_id',
            'maxlength' => '9',
            'help' => __('Your customer ID given by the e-Transaction support (from 1 to 9 digitis)', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        'etransactions_secret_key',
        __('Secret Key', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'secret_key',
            'help' => __('The secret key generated into the etransaction.fr backoffice', Constants::EtransactionsTr)
        ]
    );

    add_settings_section(
        'etransactions_section_validate',
        __('Payement validation pages', Constants::EtransactionsTr),
        'etransactions_validate_page',
        Constants::PageName
    );

    add_settings_field(
        'etransactions_confirmation_page',
        __('Confirmation pages', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_validate',
        [
            'label_for' => Constants::OptionConfirmationPage,
            'placeholder' => __('Confirmation pages url', Constants::EtransactionsTr),
            'help' => __('The URL of the confirmation pages.', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        'etransactions_validation_page',
        __('Validation pages', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_validate',
        [
            'label_for' => 'validation_page',
            'placeholder' => __('Validation pages url', Constants::EtransactionsTr),
            'help' => __('The URL of the validation pages.', Constants::EtransactionsTr)
        ]
    );

    add_settings_section(
        'etransactions_section_callbacks',               // ID
        __('Callbacks pages', Constants::EtransactionsTr), // Title
        'etransactions_callback_pages',         // Callback
        Constants::PageName
    );

    add_settings_field(
        'etransactions_accepted_key',
        __('Accepted pages', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => Constants::OptionAcceptedLandingPage,
            'placeholder' => __('URL for the accepted pages', Constants::EtransactionsTr),
            'help' => __('The pages where the user lands when the payement is accepted.', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        Constants::PluginPrefix . Constants::OptionRejectedLandingPage,
        __('Rejected pages', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => Constants::OptionRejectedLandingPage,
            'placeholder' => __('URL for the rejected pages', Constants::EtransactionsTr),
            'help' => __('The pages where the user lands when the payement is rejected by the bank.', Constants::EtransactionsTr)
        ]
    );

    add_settings_field(
        'etransactions_canceled_key',
        __('Canceled pages', Constants::EtransactionsTr),
        'etransactions_field_cb',
        Constants::PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => Constants::OptionCanceledLandingPage,
            'placeholder' => __('URL for the canceled pages', Constants::EtransactionsTr),
            'help' => __('The pages where the user lands when the payement is canceled.', Constants::EtransactionsTr)
        ]
    );

    /*
     * Display the CA logo and a message.
     */
    function etransactions_settings_callback($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?php esc_html_e('Fill the IDs you received from the e-Transaction support. ', Constants::EtransactionsTr); ?>
        </p>
        <?php
    }

    /**
     * Display an entry field
     * @param array $args
     */
    function etransactions_field_cb($args)
    {
        $options = get_option(Constants::OptionName);
        $label_for = esc_attr($args['label_for']);
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
        $options = get_option(Constants::OptionName);
        $label_for = esc_attr($args['label_for']);
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
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo __('Switching in test mode (using preprod servers). No transaction will be effective.', Constants::EtransactionsTr);
            ?>
        </p>
        <?php
    }

    function etransactions_callback_pages($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo __('The etransaction needs three pages for managing the CA e-Transaction service. The user lands on this pages in these cases.', Constants::EtransactionsTr);
            ?>
        </p>
        <?php
    }

    function etransactions_validate_page($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo __('The etransaction plugin need a validation pages. The user enter here its email address and give it consenting for the transaction', Constants::EtransactionsTr);
            ?>
        </p>
        <?php
    }
});