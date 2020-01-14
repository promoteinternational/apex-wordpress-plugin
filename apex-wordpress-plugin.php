<?php
/**
 * @package  ApexWordpressPlugin
 */

/*
Plugin Name: Apex Wordpress Plugin
Plugin URI: https://github.com/promoteinternational/apex-wordpress-plugin
Text Domain: apex-wordpress-plugin
Description: Plugin used to connect a wordpress website with Apex
Version: 1.1.0
Author: Promote International AB
Author URI: https://www.promoteint.com
License: GPLv2 or later

*/
use Inc\Base\Activate;
use Inc\Base\Deactivate;

//If this file called directly, abort execution!
defined('ABSPATH') or die('Hey, access denied!');

//Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')){
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

//Code that runs during plugin activation
function activate_apex_wordpress_plugin(){
    Activate::activate();
}
register_activation_hook(__FILE__,'activate_apex_wordpress_plugin');

//Code that runs during plugin deactivation
function deactivate_apex_wordpress_plugin(){
    Deactivate::deactivate();
}
register_deactivation_hook(__FILE__,'deactivate_apex_wordpress_plugin');



//Initialize all the core classes of the plugin
if (class_exists('Inc\\Init')){
    Inc\Init::register_services();
}

function apex_wordpress_plugin_load_text_domain() {
    load_plugin_textdomain('apex-wordpress-plugin', false, basename(dirname(__FILE__)).'/languages');
}

add_action('plugins_loaded', 'apex_wordpress_plugin_load_text_domain');

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