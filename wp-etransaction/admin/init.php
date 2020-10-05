<?php

add_action('admin_init', function () {
    register_setting('etransactions', 'etransactions_options');

    add_settings_section(
        'etransactions_section_debug',
        _('Test mode', 'etransactions'),
        'etransaction_test_mode_page',
        'etransactions'
    );

    add_settings_field(
        'etransactions_test_mode_id',
        __('Test mode', 'extransactions'),
        'etransactions_checkbox_cb',
        'etransactions',
        'etransactions_section_debug',
        [
            'label_for' => 'test_id',
            'help'=> __('<span style="color: red">By checking this option, you will switch you payement system into ' .
                        'to test mode and you will not be able to receive real payement.</span>'),
        ]
    );

    add_settings_field(
        'etransactions_hmac_preprod_key',
        __('HMAC Preprod Key', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_debug',
        [
            'label_for' => 'preprod_key',
            'type' => 'text',
            'help' => __('The secret key generated into the preprod-guest.etransaction.fr/Vision backoffice', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_settings',               // ID
        __('Credentials', 'etransactions'), // Title
        'etransactions_settings_callback',         // Callback
        'etransactions'                              // page
    );

    add_settings_field(
        'etransactions_site_id',
        __('Site Number', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'site_id',
            'type' => 'text',
            'maxlength' => '7',
            'help' => __('The Site ID given by the e-Transaction support (7 digits max)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_rang_id',
        __('Rang', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'rang_id',
            'type' => 'text',
            'maxlength' => '3',
            'help' => __('The Rang ID given by the e-Transaction support (3 digits max)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_customer_id',
        __('Customer', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'customer_id',
            'type' => 'text',
            'maxlength' => '9',
            'help' => __('Your customer ID given by the e-Transaction support (from 1 to 9 digitis)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_secret_key',
        __('Secret Key', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'secret_key',
            'type' => 'text',
            'help' => __('The secret key generated into the etransaction.fr backoffice', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_validate',
        __('Payement validation page', 'etransactions'),
        'etransactions_validate_page',
        'etransactions'
    );

    add_settings_field(
        'etransactions_validation_page',
        __('Confirmation page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_validate',
        [
            'label_for' => 'confirmation_page',
            'type' => 'text',
            'placeholder' => __('Confirmation page url', 'etransactions'),
            'help' => __('The URL of the confirmation page.', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_confirmation_page',
        __('Validation page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_validate',
        [
            'label_for' => 'validation_page',
            'type' => 'text',
            'placeholder' => __('Validation page url', 'etransactions'),
            'help' => __('The URL of the validation page.', 'etransactions')
        ]
    );

    add_settings_section(
        'etransactions_section_callbacks',               // ID
        __('Callbacks pages', 'etransactions'), // Title
        'etransactions_callback_pages',         // Callback
        'etransactions'                              // page
    );

    add_settings_field(
        'etransactions_accepted_key',
        __('Accepted page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'accepted_key',
            'type' => 'text',
            'placeholder' => __('URL for the accepted page'),
            'help' => __('The page where the user lands when the payement is accepted.', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_rejected_key',
        __('Rejected page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'rejected_key',
            'type' => 'text',
            'placeholder' => __('URL for the rejected page'),
            'help' => __('The page where the user lands when the payement is rejected by the bank.', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_canceled_key',
        __('Canceled page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'canceled_key',
            'type' => 'text',
            'placeholder' => __('URL for the canceled page'),
            'help' => __('The page where the user lands when the payement is canceled.', 'etransactions')
        ]
    );

    /*
     * Display the CA logo and a message.
     */
    function etransactions_settings_callback($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
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
        $options = get_option('etransactions_options');
        $label_for = esc_attr($args['label_for']);
        ?>

        <input
                id="<?php echo $label_for; ?>"
                name="etransactions_options[<?php echo $label_for; ?>]"
                type="<?php echo $args['type']; ?>"
                <?php if (isset($args['maxlength'])) { ?>maxlength="<?php echo $args['maxlength']; ?>"<?php } ?>
                <?php if (isset($args['placeholder'])) { ?>placeholder="<?php echo $args['placeholder']; ?>"<?php } ?>
                value="<?php if (isset($options[$args['label_for']])) {
                    echo $options[$args['label_for']];
                } ?>"/>

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

    function etransactions_checkbox_cb($args)
    {
        $options = get_option('etransactions_options');
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
            echo __('Switching in test mode (using preprod servers)', 'etransactions');
            ?>
        </p>
        <?php
    }

    function etransactions_callback_pages($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo __('The etransaction needs three pages for managing the CA e-Transaction service. The user lands on this page in these cases.', 'etransactions');
            ?>
        </p>
        <?php
    }

    function etransactions_validate_page($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo __('The etransaction plugin need a validation page. The user enter here its email address and give it consenting for the transaction', 'etransactions');
            ?>
        </p>
        <?php
    }
});