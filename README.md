# Promote Apex Plugin

[Github](https://github.com/promoteinternational/apex-wordpress-plugin) - [Wordpress](https://wordpress.org/plugins/promote-apex)

The promote apex plugin is a plugin used to show course information from a running apex server on a wordpress page.

## Requirements
The plugin depends on wordpress cron functionality to regularly update the course information from the apex server. 
While nothing is required to get this working, a plugin like the 
[Advanced Cron Manager](https://wordpress.org/plugins/advanced-cron-manager/) is recommended to see how the cron job is running.

## Settings
When installed the plugin will add a separate tab on the wordpress admin sidebar for configration. It will also create 
a wordpress cron job to synchronize the course information at regular intervals.
