<?php
/**
 * @package  ApexWordpressPlugin
 */

/*
Plugin Name: Apex Wordpress Plugin
Plugin URI: https://github.com/promoteinternational/apex-wordpress-plugin
Description: Plugin used to connect a wordpress website with Apex
- Author: Promote International AB
Version: 1.0.0
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