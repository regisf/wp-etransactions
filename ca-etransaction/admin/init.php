<?php

add_action('admin_init', function () {
    register_setting('etransactions', ETransactions_OptionName);

    add_settings_section(
        'etransactions_section_debug',
        __('Test mode', ETransactions_Tr),
        'etransaction_test_mode_page',
        ETransactions_PageName
    );

    add_settings_field(
        'etransactions_test_mode_id',
        __('Test mode', ETransactions_Tr),
        'etransactions_checkbox_cb',
        ETransactions_PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'test_id',
            'help' => '<span style="color: red">' . __('By checking this option, you will switch you payement system into to test mode and you will not be able to receive real payement.', ETransactions_Tr) . '</span>',
        ]
    );

    add_settings_field(
        'etransactions_hmac_preprod_key',
        __('HMAC Preprod Key', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_debug',
        [
            'label_for' => 'preprod_key',
            'help' => __('The secret key generated into the preprod-guest.etransaction.fr/Vision backoffice', ETransactions_Tr)
        ]
    );

    add_settings_section(
        'etransactions_section_settings',
        __('Credentials', ETransactions_Tr),
        'etransactions_settings_callback',
        ETransactions_PageName
    );

    add_settings_field(
        'etransactions_site_id',
        __('Site Number', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'site_id',
            'maxlength' => '7',
            'help' => __('The Site ID given by the e-Transaction support (7 digits max)', ETransactions_Tr)
        ]
    );

    add_settings_field(
        'etransactions_rang_id',
        __('Rang', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'rang_id',
            'maxlength' => '3',
            'help' => __('The Rang ID given by the e-Transaction support (3 digits max)', ETransactions_Tr)
        ]
    );

    add_settings_field(
        'etransactions_customer_id',
        __('Customer', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'customer_id',
            'maxlength' => '9',
            'help' => __('Your customer ID given by the e-Transaction support (from 1 to 9 digitis)', ETransactions_Tr)
        ]
    );

    add_settings_field(
        'etransactions_secret_key',
        __('Secret Key', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_settings',
        [
            'label_for' => 'secret_key',
            'help' => __('The secret key generated into the etransaction.fr backoffice', ETransactions_Tr)
        ]
    );

    add_settings_section(
        'etransactions_section_validate',
        __('Payement validation page', ETransactions_Tr),
        'etransactions_validate_page',
        ETransactions_PageName
    );

    add_settings_field(
        'etransactions_confirmation_page',
        __('Confirmation page', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_validate',
        [
            'label_for' => ETransactions_OptionConfirmationPage,
            'placeholder' => __('Confirmation page url', ETransactions_Tr),
            'help' => __('The URL of the confirmation page.', ETransactions_Tr)
        ]
    );

    add_settings_field(
        'etransactions_validation_page',
        __('Validation page', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_validate',
        [
            'label_for' => 'validation_page',
            'placeholder' => __('Validation page url', ETransactions_Tr),
            'help' => __('The URL of the validation page.', ETransactions_Tr)
        ]
    );

    // Callback pages ---------------------------------------------------------

    add_settings_section(
        'etransactions_section_callbacks',               // ID
        __('Callbacks page', ETransactions_Tr), // Title
        'etransactions_callback_pages',         // Callback
        ETransactions_PageName
    );

    add_settings_field(
        'etransactions_accepted_key',
        __('Accepted page', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => ETransactions_OptionAcceptedLandingPage,
            'placeholder' => __('URL for the accepted page', ETransactions_Tr),
            'help' => __('The page where the user lands when the payement is accepted.', ETransactions_Tr)
        ]   );

    add_settings_field(
        ETransactions_PluginPrefix . ETransactions_OptionRejectedLandingPage,
        __('Rejected page', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => ETransactions_OptionRejectedLandingPage,
            'placeholder' => __('URL for the rejected page', ETransactions_Tr),
            'help' => __('The page where the user lands when the payement is rejected by the bank.', ETransactions_Tr)
        ]);

    add_settings_field(
        'etransactions_canceled_key',
        __('Canceled page', ETransactions_Tr),
        'etransactions_field_cb',
        ETransactions_PageName,
        'etransactions_section_callbacks',
        [
            'label_for' => ETransactions_OptionCanceledLandingPage,
            'placeholder' => __('URL for the canceled page', ETransactions_Tr),
            'help' => __('The page where the user lands when the payement is canceled.', ETransactions_Tr)
        ]);

    // Email section ----------------------------------------------------------
   
    add_settings_section(
        'etransactions_section_email',
        __('Email send', ETransactions_Tr),
        'etransactions_email',
        ETransactions_PageName);

    add_settings_field('etransactions_email_activate',
        __('Send email', ETransactions_Tr),
        'etransactions_checkbox_cb',
        ETransactions_PageName,
        'etransactions_section_email',
        ['label_for' => 'email_activate',
         'help' => __("Send email on each successful transaction") ]);

    add_settings_field('etransactions_email_email', 
        __('Email to use', ETransactions_Tr),
        'etransactions_field_cb', 
        ETransactions_PageName, 
        'etransactions_section_email', 
        [
            'label_for' => 'email_email', 
            'placeholder' => __('Enter email receiver address'),
            'help'=> __("The email address that will received a message for each a success payement. <br>
            If you don't give an email address, the administrator email will be used.")
        ]);

    add_settings_field('etransactions_email_title', 
        __('Message title', ETransactions_Tr),
        'etransactions_field_cb', 
        ETransactions_PageName, 
        'etransactions_section_email', 
        [
            'label_for' => 'email_title_id', 
            'placeholder' => __('Enter the email message title')
        ]);

    add_settings_field('etransactions_email_body',
            __('Message body', ETransactions_Tr),
            'etransactions_textarea_callback', 
            ETransactions_PageName,
            'etransactions_section_email', 
            [
                'label_for' => 'email_content', 
                'placeholder' => __('The email content'),
                'help' => __("The email content. No tags are allowed here")
            ]);
    
    /*
     * Display the CA logo and a message.
     */
    function etransactions_settings_callback($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?php esc_html_e('Fill the IDs you received from the e-Transaction support. ', ETransactions_Tr); ?>
        </p>
        <?php
    }

    function etransaction_option_name($label) {
        return  'name="' . ETransactions_OptionName . '[' . $label . ']"';
    }
    /**
     * Display an entry field
     * @param array $args
     */
    function etransactions_field_cb($args)
    {
        $options = get_option(ETransactions_OptionName);
        $label_for = esc_attr($args['label_for']);
        ?>

        <input
            class="regular-text"
                id="<?php echo $label_for; ?>"
                <?php echo etransaction_option_name($label_for); ?>
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
        $options = get_option(ETransactions_OptionName);
        $label_for = esc_attr($args['label_for']);
        ?>
        <input
                id="<?php echo $label_for; ?>"
                <?php echo etransaction_option_name($label_for); ?>
                type="checkbox"
                <?php if ($options[$label_for]) { ?>checked="<?php $options[$label_for] ?>" <?php } ?> />

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

    function etransactions_textarea_callback($args) 
    {
        $options = get_option(ETransactions_OptionName);
        $label_for = esc_attr($args['label_for']);

        $content = isset($options[$args['label_for']]) 
            ? $options[$args['label_for']] : '';

        ?>
        <textarea class="regular-text"
            id="<?php echo $label_for; ?>"
            <?php if (isset($args['placeholder'])) { ?>placeholder="<?php echo $args['placeholder']; ?>"<?php } ?>
            <?php echo etransaction_option_name($label_for); ?>
        ><?php echo $content ?></textarea>

        <?php
    }

    function etransaction_test_mode_page($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            echo __('Switching in test mode (using preprod servers). No transaction will be effective.', ETransactions_Tr);
            ?>
        </p>
        <?php
    }

    function etransactions_callback_pages($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            echo __('The etransaction needs three pages for managing the CA e-Transaction service. The user lands on this page in these cases.', ETransactions_Tr);
            ?>
        </p>
        <?php
    }

    function etransactions_validate_page($args)
    {
        ?>
        <p id="<?php echo sanitize_text_field($args['id']); ?>">
            <?
            echo __('The etransaction plugin need a validation page. The user enter here its email address and give it consenting for the transaction', ETransactions_Tr);
            ?>
        </p>
        <?php
    }
});