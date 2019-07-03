<?php
//Making the api request via Cron

namespace Inc\Api;

class CronRequest
{
    private $api;

    public function register()
    {
        $this->api = new RestApi();
        $this->api->register();

        add_action('wp', [$this, 'apex_schedule_cron']);
        add_filter('cron_schedules', [$this, 'apex_cron_add_intervals']);

        add_action('apex_cron', [$this, 'apex_cron_function']);
    }

    public function getSchedules()
    {
        $schedules = wp_get_schedules();
        return $schedules;
    }


    public function apex_cron_add_intervals($schedules)
    {
        $updateTimeSecond = 60 * ($this->api->getUpdateFrequency());
        $schedules['custom'] = array(
            'interval' => $updateTimeSecond,
            'display' => __('Custom Settings')
        );
        return $schedules;
    }


    public function apex_cron_function()
    {
        $this->api->loadTemplates();

    }


    public function apex_schedule_cron()
    {
        if ( ! wp_next_scheduled ( 'apex_cron' ) ) {
            wp_schedule_event(time(), 'custom', 'apex_cron');
        }
    }
}











