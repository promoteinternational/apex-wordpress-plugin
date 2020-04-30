<?php
/**
 * @package   PromoteApex
 */

namespace Apex\Base;

class SettingsLinks extends BaseController
{
    public function register()
    {
        add_filter("plugin_action_links_$this->plugin_name", [$this, 'settings_link']);
    }

    function settings_link($links)
    {
        $translation = _e('Settings');
        $settings_link = '<a href="admin.php?page=promote_apex_plugin">' . $translation . '</a>';
        array_push($links, $settings_link);
        return $links;
    }
}