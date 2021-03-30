<?php
/**
 * @package  PromoteApex
 */

/*
Plugin Name: Promote Apex
Plugin URI: https://github.com/promoteinternational/apex-wordpress-plugin
Text Domain: promote-apex
Description: Plugin used to connect a wordpress website with Apex
Version: 1.2.5
Author: Promote International AB
Author URI: https://www.promoteint.com
License: GPLv2 or later
*/

use Apex\Base\Activate;
use Apex\Base\Deactivate;

//If this file called directly, abort execution!
defined('ABSPATH') or die('Hey, access denied!');

//Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

//Code that runs during plugin activation
function activate_promote_apex(){
    Activate::activate();
}
register_activation_hook(__FILE__, 'activate_promote_apex');

//Code that runs during plugin deactivation
function deactivate_promote_apex(){
    Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_promote_apex');



//Initialize all the core classes of the plugin
if (class_exists('Apex\\Init')){
    apex\Init::register_services();
}

function promote_apex_load_text_domain() {
    load_plugin_textdomain('promote-apex', false, basename(dirname(__FILE__)).'/languages');
}

add_action('plugins_loaded', 'promote_apex_load_text_domain');

function format_currency($currency_name, $value) {
    switch($currency_name) {
        case 'SEK':
            if (get_bloginfo("language") === 'sv-SE') {
                return number_format_i18n($value, 0) . ' kr';
            } else {
                return number_format_i18n($value, 0) . ' SEK';
            }
            break;
        case 'EUR':
            return '€' . number_format_i18n($value, 0);
            break;
        case 'GBP':
            return '£' . number_format_i18n($value, 0);
            break;
        case 'USD':
            return '$' . number_format_i18n($value, 0);
            break;
        default:
            return number_format_i18n($value, 0) . ' ' . $currency_name;
            break;
    }
}