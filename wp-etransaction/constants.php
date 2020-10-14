<?php

define('CurrentVersion', 101);
define('NonceName', 'etransactions_products');
define('DbPrefix', 'etransactions_');

if (!class_exists('Constants')) {
    abstract class Constants
    {
        const PluginPrefix = 'etransactions_';

        // Options
        const PageName = 'etransactions';
        const OptionName = 'etransactions_options';
        const OptionConfirmationPage = 'confirmation_page';
        const OptionValidationPage = 'validation_page';
        const OptionSiteID = 'site_id';
        const OptionRangID = 'rang_id';
        const OptionCustomerID = 'customer_id';
        const OptionSecretKey = 'secret_key';
        const OptionAcceptedLandingPage = 'accepted_page';
        const OptionRejectedLandingPage = 'rejected_page';
        const OptionCanceledLandingPage = 'canceled_page';
        const OptionCurrentVersion = self::PluginPrefix . 'current_version';
    }
}
