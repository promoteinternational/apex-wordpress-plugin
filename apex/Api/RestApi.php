<?php
/**
 *  @package   PromoteApex
 */

namespace Apex\Api;

class RestApi
{
    //Create RestApi variables
    private $server_name;
    private $privateKey;
    private $publicKey;
    private $portal_id;
    private $updateFrequency;
    private $currency;
    private $displaySeats;
    private $coursesTitle;
    private $slug;
    private $sendCalendar;

    public function register()
    {
        $this->setData();
    }

    public function setData()
    {
        if (get_option('apex_server_name') != 'localhost') {
            $this->server_name = 'https://' . get_option('apex_server_name');
        } else {
            $this->server_name = 'http://' . get_option('apex_server_name') . ':8000';
        }
        $this->publicKey = get_option('apex_public_api_key');
        $this->privateKey = get_option('apex_private_api_key');
        $this->portal_id = get_option('apex_portal_id');
        $this->updateFrequency = get_option('apex_update_frequency');
        $this->currency= get_option('apex_currency');
        $this->slug = get_option('apex_plugin_slug');
        $this->sendCalendar = get_option('apex_send_calendar_file');
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getServerName()
    {
        return $this->server_name;
    }

    public function getServerUrl($path)
    {
        return $this->getServerName() . $path;
    }

    public function getPortalId()
    {
        return $this->portal_id;
    }

    public function getUpdateFrequency()
    {
        return $this->updateFrequency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getdisplaySeats()
    {
        return $this->displaySeats;
    }

    public function getCoursesTitle()
    {
        return $this->coursesTitle;
    }

    public function getSendCalendar()
    {
        return $this->sendCalendar;
    }

    //Create API request headers function
    public function create_request_headers($data = [])
    {
        $private_key = $this->getPrivateKey();
        $public_key = $this->getPublicKey();
        $timestamp = date('Y-m-d\TH:i:s.u', time());

        if ($data) {
            $encoded_body = hash('sha256', json_encode($data));
        } else {
            $encoded_body = hash('sha256', '{}');
        }

        $signature = hash('sha256', $public_key . $encoded_body . $timestamp . $private_key);

        return [
            'Signature' => base64_encode($signature),
            'Timestamp' => $timestamp,
            'API-Token' => base64_encode($public_key),
            'Content-Type' => 'application/json'
        ];
    }

    /**
     * Perform a get request with provided url
     * @param $service_path
     * @param array $data
     * @return array|mixed|object
     */
    private function get($service_path, $data = []) {
        if (count($data)) {
            $service_path = $service_path . '?' . http_build_query($data);
        }
        $response = wp_remote_get($this->getServerUrl($service_path),
            array(
                'headers' => $this->create_request_headers(),
                'timeout' => 50
            )
        );

        if (is_wp_error($response)) {
            error_log('error occurred during API get call. Additional info: ' . $response->get_error_message());
            return false;
        } else {
            return json_decode(wp_remote_retrieve_body($response));
        }
    }

    /**
     * Perform a post request with provided url and data
     * @param $service_path
     * @param array $data
     * @return bool|mixed
     */
    private function post($service_path, $data = []) {
        $response = wp_remote_post($this->getServerUrl($service_path), array(
            'body' => $data,
            'headers' => $this->create_request_headers($data),
            'timeout' => 50
        ));

        if (is_wp_error($response)) {
            error_log('error occurred during API post. Additional info: ' . $response->get_error_message());
            return false;
        } else {
            return json_decode(wp_remote_retrieve_body($response));
        }
    }


    /**
     * Load the events for a specific template. Query Apex once more to only get upcoming events.
     *
     * @param $areaSlug - the slug of the current area.
     * @param $templateSlug - the slug of the template that should be used.
     *
     * @return array
     */
    public function loadEvents($areaSlug, $templateSlug) {
        $portalID = $this->getPortalId();
        $service_url = '/api/v1/websites/' .  $portalID . '/areas/' . $areaSlug . '/templates/' . $templateSlug . '/events/';

        $decoded = $this->get($service_url, ['extra_fields' => 'sessions', 'start_date' => date('Y-m-d')]);

        if (!$decoded) {
            error_log('error occurred in API call');
            return [];
        }

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            error_log('error occurred: ' . $decoded->response->errormessage);
            return [];
        }

        return $decoded->results;
    }

    //Load all templates
    public function loadTemplates()
    {
        $currentTerms = [];
        $areas_taxonomy = 'Apex-areas';
        $portalID = $this->getPortalId();
        $service_url = '/api/v1/websites/' . $portalID . '/areas/';

        echo '<hr>';
        $decoded = $this->get($service_url, ['limit' => 'null']);

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            error_log('error occurred: ' . $decoded->response->errormessage);
            die('error occurred: ' . $decoded->response->errormessage);
        }
        $areas = $decoded->results;
        echo 'response ok!';

        $allCourses = get_posts(array('post_type' => [$this->slug],'post_status' => ['publish','trash'], 'numberposts' => -1));
        $currentCourses = [];
        foreach ($allCourses as $existingTemplate) {
            $course = new \stdClass();

            $course->id = $existingTemplate->ID;
            $course->name = $existingTemplate->post_name;
            $course->status = 0; // 0 - present, not updated course (default); 1 - present, updated course; 2 - new course

            $currentCourses[$existingTemplate->post_name] = $course;
        }

        $area_terms = get_terms(array(
            'taxonomy' => $areas_taxonomy,
            'hide_empty' => false
        ));

        foreach($area_terms as $existingTerm) {
            $term = new \stdClass();

            $term->term_id = $existingTerm->term_id;
            $term->status = 0;

            $currentTerms[$existingTerm->name] = $term;
        }

        remove_filter('content_save_pre', 'wp_filter_post_kses');
        remove_filter('content_filtered_save_pre', 'wp_filter_post_kses');

        try {
            foreach ($areas as $area) {
                // Create or modify taxonomies
                $area_term = $area->slug;
                $course_id = 0;
                $term = term_exists($area_term, $areas_taxonomy);

                if (!$term) {
                    $term = wp_insert_term($area->name, $areas_taxonomy, [
                        'slug' => $area_term,
                    ]);
                } else {
                    delete_term_meta((int)$term['term_id'], 'number_of_courses');
                }

                add_term_meta((int)$term['term_id'], 'number_of_courses', (string)sizeof($area->area_templates));

                $currentTerms[$area->name]->status = 1;

                wp_update_term($area->nane, $areas_taxonomy, [
                    'slug' => $area_term,
                    'meta_input' => [
                        'number_of_templates' => sizeof($area->area_templates)
                    ]
                ]);

                foreach ($area->area_templates as $template) {
                    $events = $this->loadEvents($area_term, $template->slug);
                    $places_events = [];
                    $number_of_days = $template->number_of_days;

                    foreach ($events as $event) {
                        $event_dates = [];

                        if (!key_exists($event->venue_city, $places_events)) {
                            $places_events[$event->venue_city] = [];
                        }

                        foreach ($event->sessions as $session) {
                            $dates = date_i18n('d M', strtotime($session->start_date));

                            if ($session->number_of_days > 1) {
                                $dates = $dates . ' - ' . date_i18n('d M', strtotime($session->end_date));
                            } else {
                                $day = reset($session->days);
                                if ($day) {
                                    $start_date = new \DateTime();
                                    $time_zone = new \DateTimeZone($session->timezone);
                                    $start_date->setTimestamp(strtotime($day->start_date));
                                    $start_date->setTimezone($time_zone);

                                    $end_date = new \DateTime();
                                    $end_date->setTimestamp(strtotime($day->end_date));
                                    $end_date->setTimezone($time_zone);

                                    $seconds = $end_date->getTimestamp() - $start_date->getTimestamp();

                                    if ($seconds < 21600) {
                                        if (count($event->sessions) == 1) {
                                            $number_of_days = '1/2';
                                        }
                                        $dates = $dates . ' ' . $start_date->format('H:i') . '-' . $end_date->format('H:i');
                                    }
                                }
                            }

                            array_push($event_dates, $dates);
                        }

                        $event = (object)array_merge((array)$event, array('event_dates' => implode(",<br>", $event_dates)));

                        array_push($places_events[$event->venue_city], $event);
                    }

                    ksort($places_events);

                    if (key_exists($template->slug, $currentCourses)) { // if course already present
                        $course_id = $currentCourses[$template->slug]->id;

                        // check for duplicates, if already updated - do not update
                        if ($currentCourses[$template->slug]->status === 0) {
                            wp_update_post(array(
                                    'ID' => $course_id,
                                    'post_title' => $template->name,
                                    'post_name' => $template->slug,
                                    'post_type' => $this->slug,
                                    'post_content' => $template->description,
                                    'post_status' => 'publish',
                                    'meta_input' => [
                                        'apex_course_id' => $template->id,
                                        'apex_course_identifier' => $template->identifier,
                                        'apex_course_event_id' => $template->event_id,
                                        'apex_course_venue' => $template->venue,
                                        'apex_course_timezone' => $template->timezone,
                                        'apex_course_number_of_days' => $number_of_days,
                                        'apex_course_is_active' => $template->is_active,
                                        'apex_course_is_template' => $template->is_template,
                                        'apex_course_prices' => $template->prices,
                                        'apex_course_template_events' => $events,
                                        'apex_course_template_places' => $places_events
                                    ])
                            );

                            $currentCourses[$template->slug]->status = 1;
                        }
                    } else { // new course
                        $course_id = wp_insert_post(array(
                                'post_title' => $template->name,
                                'post_name' => $template->slug,
                                'post_type' => $this->slug,
                                'post_content' => $template->description,
                                'post_status' => 'publish',
                                'meta_input' => [
                                    'apex_course_id' => $template->id,
                                    'apex_course_identifier' => $template->identifier,
                                    'apex_course_event_id' => $template->event_id,
                                    'apex_course_venue' => $template->venue,
                                    'apex_course_timezone' => $template->timezone,
                                    'apex_course_number_of_days' => $number_of_days,
                                    'apex_course_is_active' => $template->is_active,
                                    'apex_course_is_template' => $template->is_template,
                                    'apex_course_prices' => $template->prices,
                                    'apex_course_template_events' => $events,
                                    'apex_course_template_places' => $places_events
                                ])
                        );

                        $currentCourses[$template->slug] = new \stdClass();
                        $currentCourses[$template->slug]->id = $template->id;
                        $currentCourses[$template->slug]->name = $template->slug;
                        $currentCourses[$template->slug]->status = 2;
                    }

                    // Set term
                    if (!has_term($area_term, $areas_taxonomy, $course_id)) {
                        wp_set_post_terms($course_id, $area_term, $areas_taxonomy, true);
                    }
                }
            }
        }

        finally {
            // Make sure that we reset the filter settings before continuing.
            add_filter('content_save_pre', 'wp_filter_post_kses');
            add_filter('content_filtered_save_pre', 'wp_filter_post_kses');
        }

        // delete courses, not present in the API response
        foreach ($currentCourses as $currentCourse) {
            if ($currentCourse->status === 0) {
                wp_delete_post($currentCourse->id, true);
            }
        }

        foreach($currentTerms as $term) {
            if ($term->status == 0) {
                wp_delete_term($term->term_id, $areas_taxonomy);
            }
        }

        echo "Updated terms";

        // Save titles
        $service_url = '/api/v1/participants/titles/';
        $decoded = $this->get($service_url);
        if ($decoded) {
            $titles = $decoded->results;
            $titles_store = array();
            if (is_array($titles) && count($titles)) {
                foreach ($titles as $title) {
                    $titles_store[$title->id] = $title->name;
                }
            }
            update_option('apex_plugin_titles', $titles_store, false);
        }

        // Save sectors
        $service_url = '/api/v1/companies/sectors/';
        $decoded = $this->get($service_url);
        if ($decoded) {
            $sectors = $decoded->results;
            $sectors_store = array();
            if (is_array($sectors) && count($sectors)) {
                foreach ($sectors as $sector) {
                    $sectors_store[$sector->id] = $sector->name;
                }
            }
            update_option('apex_plugin_sectors', $sectors_store, false);
        }
    }

    // Add participant on event function
    public function addParticipant($eventId, $firstName, $LastName, $email, $phone, $invoice_reference, $company,
                                   $address1, $address2, $zipCode, $city, $country, $sector, $title)
    {
        $portalID = $this->getPortalId();
        $service_url = '/api/v1/websites/' . $portalID . '/events/' . $eventId . '/participants/';

        $data = [
            'first_name' => $firstName,
            'last_name' => $LastName,
            'email' =>  $email,
            'is_active' => true,
            'is_staff' => false,
            'cell_phone_number' => $phone,
            'phone_number' => $phone,

            'company' => [
                'name' =>  $company,
                "addresses" => [[
                    "type" => "invoicing",
                    "address_row_1" => $address1,
                    "address_row_2" => $address2,
                    "zip_code" => $zipCode,
                    "city" => $city,
                    "country" => $country
                ]],
            ],
        ];

        if ($title) {
            $data['title'] = $title;
        }

        if ($sector) {
            $data['company']['sector'] = $sector;
        }

        if ($invoice_reference) {
            $data['invoice_reference'] = $invoice_reference;
        }

        if ($this->sendCalendar === 'yes') {
            $data['send_calendar_file'] = true;
        }

        $dataJson = wp_json_encode($data);
        $response = $this->post($service_url, $dataJson);

        if (!$response) {
            error_log("There was an error adding the participant to the event");
            return false;
        } else {
            if (isset($response->participant) && is_array($response->participant)) {
                return false;
            }
            if (isset($response->confirmation_sent)) {
                return true;
            } else {
                return false;
            }
        }
    }
}


