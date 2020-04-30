<?php
/**
 *  @package   PromoteApex
 */

namespace Apex\Api;

class CronRequest
{
    private $api;

    public function register()
    {
        $this->api = new RestApi();
        $this->api->register();

        add_action('wp', [$this, 'promote_apex_schedule_cron']);
        add_filter('cron_schedules', [$this, 'promote_apex_cron_add_intervals']);

        add_action('promote_apex_cron', [$this, 'promote_apex_cron_function']);
    }

    public function getSchedules()
    {
        return wp_get_schedules();
    }


    public function promote_apex_cron_add_intervals($schedules)
    {
        $updateTimeSecond = 60 * ($this->api->getUpdateFrequency());
        $schedules['custom'] = array(
            'interval' => $updateTimeSecond,
            'display' => __('Custom Settings', 'promote-apex')
        );
        return $schedules;
    }


    public function promote_apex_cron_function()
    {
        $this->api->loadTemplates();

    }


    public function promote_apex_schedule_cron()
    {
        if ( ! wp_next_scheduled ( 'promote_apex_cron' ) ) {
            wp_schedule_event(time(), 'custom', 'promote_apex_cron');
        }
    }
}











