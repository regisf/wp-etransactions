<?php

add_action('admin_init', function () {
    register_setting('etransactions', 'etransactions_options');

    add_settings_section(
        'etransactions_section_settings',               // ID
        esc_html__('Credentials', 'etransactions'), // Title
        'etransactions_settings_callback',         // Callback
        'etransactions'                              // page
    );

    add_settings_field(
        'etransactions_site_id',
        esc_html__('Site Number', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'site_id',
            'type' => 'text',
            'maxlength' => '7',
            'help' => esc_html__('The Site ID given by the e-Transaction support (7 digits max)', 'etransactions')
        ]
    );

    add_settings_field(
        'etransactions_rang_id',
        esc_html__('Rang', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'rang_id',
            'type' => 'text',
            'maxlength' => '3',
            'help' => esc_html__('The Rang ID given by the e-Transaction support (3 digits max)', 'etransactions')

        ]
    );

    add_settings_field(
        'etransactions_customer_id',
        esc_html__('Customer', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'customer_id',
            'type' => 'text',
            'maxlength' => '9',
            'help' => esc_html__('Your customer ID given by the e-Transaction support (from 1 to 9 digitis)', 'etransactions')

        ]
    );

    add_settings_field(
        'etransactions_secret_key',
        esc_html__('Secret Key', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_settings',
        [
            'label_for' => 'secret_key',
            'type' => 'password',
            'help' => esc_html__('The secret key generated into the etransaction.fr backoffice', 'etransactions')
        ]
    );

//    register_setting('etransactions_section_callbacks', 'etransactions_section_callbacks');
    add_settings_section(
        'etransactions_section_callbacks',               // ID
        esc_html__('Callbacks pages', 'etransactions'), // Title
        'etransactions_callback_pages',         // Callback
        'etransactions'                              // page
    );

    add_settings_field(
        'etransactions_accepted_key',
        esc_html__('Accepted page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'accepted_key',
            'type' => 'text',
            'placeholder' => esc_html__('URL for the accepted page'),
            'help' => esc_html__('The page where the user lands when the payement is accepted.', 'etransactions')
        ]
    );
    add_settings_field(
        'etransactions_rejected_key',
        esc_html__('Rejected page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'rejected_key',
            'type' => 'text',
            'placeholder' => esc_html__('URL for the rejected page'),
            'help' => esc_html__('The page where the user lands when the payement is rejected by the bank.', 'etransactions')
        ]
    );
    add_settings_field(
        'etransactions_canceled_key',
        esc_html__('Canceled page', 'etransactions'),
        'etransactions_field_cb',
        'etransactions',
        'etransactions_section_callbacks',
        [
            'label_for' => 'canceled_key',
            'type' => 'text',
            'placeholder' => esc_html__('URL for the canceled page'),
            'help' => esc_html__('The page where the user lands when the payement is canceled.', 'etransactions')
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
                value="<?php if (isset($options[$args['label_for']])) { echo $options[$args['label_for']]; } ?>" />

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

    function etransactions_callback_pages($args)
    {
        ?>
        <p id="<?php echo esc_attr($args['id']); ?>">
            <?
            echo esc_html__('The etransaction needs three pages for managing the CA e-Transaction service. The user lands on this page in these cases.', 'etransactions');
            ?>

        </p>
        <?php
    }

});