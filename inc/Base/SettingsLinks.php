<?php
/**
 * @package  ApexWordpressPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;

class SettingsLinks extends BaseController
{
    public function register()
    {
        add_filter("plugin_action_links_$this->plugin_name",[$this,'settings_link']);
    }

    function settings_link($links)
    {
        $translation = _e('Settings');
        $settings_link = '<a href="admin.php?page=apex_wordpress_plugin">'.$translation.'</a>';
        array_push($links, $settings_link);
        return $links;
    }
}