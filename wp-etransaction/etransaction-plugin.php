<?php
/**
 * Plugin Name: CA e-Transactions for Wordpress
 * Plugin URI: https://github.com/regisf/wp-etransaction
 * Description: Handle the basics with this plugin.
 * Version: 1.0.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Régis FLORET
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

//require_once(__DIR__ . '/ca-wp-etransaction.php');

require_once __DIR__ . '/src/plugin_activation.php';
require_once __DIR__ . '/src/plugin_deactivation.php';

if (!class_exists('ETransaction_Plugin')) {
    class ETransaction_Plugin
    {
        public static function activate()
        {
            $activation = new ETransaction_Plugin_Activation();
            $activation->doActivation();
        }

        public static function deactivate()
        {
            $activation = new ETransaction_Plugin_Deactivation();
            $activation->doDeactivation();

        }
    }
}

register_activation_hook(__FILE__, array('ETransaction_Plugin', 'activate'));
register_deactivation_hook(__FILE__, array('ETransaction_Plugin', 'deactivate'));

add_action('admin_menu', function () {
    if (!current_user_can('manage_options')) {
        return;
    }

    add_menu_page(
        'Crédit Agricole e-Transactions',
        'CA e-Transactions',
        'manage_options',
        'etransactions',
        function () {
            require_once plugin_dir_path(__FILE__) . 'src/admin/settings_page.php';
        },
        plugin_dir_url(__FILE__) . 'assets/images/ca_icon.png',
        200
    );

    add_submenu_page(
        'etransactions',
        'CA e-Transaction Products',
        'Products',
        'manage_options',
        'etransactions_products',
        function () {
            require_once plugin_dir_path(__FILE__) . 'src/admin/products_page.php';
        }
    );

    add_submenu_page(
        'etransactions',
        'CA e-Transactions Payements ',
        'Payements',
        'manage_options',
        'etransactions_payements',
        function () {
            require_once plugin_dir_path(__FILE__) . 'src/admin/settings_page.php';
        }
    );

});

add_action('admin_init', function () {
    register_setting('etransactions', 'etransactions_options');

    add_settings_section(
        'etransactions_section_settings',               // ID
        __('CA e-Transactions settings', 'etransactions'),     // Title
        'etransactions_settings_callback',              // Callback
        'etransactions'                                 // page
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
            'type' => 'password',
            'help' => __('The secret key generated into the etransaction.fr backoffice', 'etransactions')
        ]
    );

    /*
     * Display the CA logo and a message.
     */
    function etransactions_settings_callback($args)
    {
        ?>
        <div class="clear">
            <p>
                <img src="<?php echo plugin_dir_url(__FILE__) . 'assets/images/logo_moncommerce_ca.jpg'; ?>"/>
            </p>
            <p id="<?php echo esc_attr($args['id']); ?>">
                <?php esc_html_e('Fill the IDs you received from the e-Transaction support. ', 'etransactions'); ?>
            </p>
        </div>
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
            <?php if (isset($args['maxlength'])) { ?>
                maxlength="<?php echo $args['maxlength']; ?>"
            <?php } ?>
                placeholder="<?php echo __('Site ID', 'etransactions'); ?>"
                value="<?php echo $options[$args['label_for']] ?>"/>

        <div class="description">
            <?php echo $args['help']; ?>
        </div>
        <?php
    }

});
