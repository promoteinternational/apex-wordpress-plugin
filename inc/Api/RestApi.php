<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 04-Dec-18
 * Time: 16:47
 */

namespace Inc\Api;

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
    private $success;
    private $slug;

    public function register()
    {
        $this->setData();
    }

    public function setData()
    {
        $this->server_name = get_option('apex_server_name');
        $this->publicKey = get_option('apex_public_api_key');
        $this->privateKey = get_option('apex_private_api_key');
        $this->portal_id = get_option('apex_portal_id');
        $this->updateFrequency = get_option('apex_update_frequency');
        $this->currency= get_option('apex_currency');
        $this->slug = get_option('apex_plugin_slug');
        $this->success = false;
    }

    public function getSuccess()
    {
        return $this->success;
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

    //Create API request headers function
    public function create_request_headers(string $public_key, string $private_key, $data = [])
    {
        $timestamp = date('Y-m-d\TH:i:s.u', time());

        if ($data) {
            $encoded_body = hash('sha256', json_encode($data));
        } else {
            $encoded_body = hash('sha256', '{}');
        }

        $signature = hash('sha256', $public_key . $encoded_body . $timestamp . $private_key);


        return [
            'Signature: ' . base64_encode($signature),
            'Timestamp: ' . $timestamp,
            'API-Token: ' . base64_encode($public_key),
            'Content-Type: application/json'
        ];
    }

    /**
     * Perform a Curl request with provided headers and url
     * @param $headers
     * @param $service_url
     * @param $post_data
     * @return array|mixed|object
     */
    private function performCurlRequest($headers, $service_url, $post_data = []) {
        $curl = curl_init($service_url);

        $curl_array = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $service_url . '/?' . http_build_query($post_data,null,'&'),
            CURLOPT_HTTPHEADER => $headers
        );

        curl_setopt_array($curl, $curl_array);
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occurred during curl exec. Additional info: ' . var_export($info));
        }

        curl_close($curl);

        return json_decode($curl_response);
    }

    /**
     * Load the events for a specific template. Query apex once more to only get upcoming events.
     *
     * @param $areaSlug - the slug of the current area.
     * @param $templateSlug - the slug of the template that should be used.
     *
     * @return array
     */
    public function loadEvents($areaSlug, $templateSlug) {
        $portalID = $this->getPortalId();
        $serverName = $this->getServerName();
        $service_url = $serverName . '/websites/' .  $portalID . '/areas/' . $areaSlug . '/templates/' . $templateSlug . '/events/';
        $headers = $this->create_request_headers($this->getPublicKey(), $this->getPrivateKey());

        $decoded = $this->performCurlRequest($headers, $service_url, ['start_date' => date('Y-m-d')]);

        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            error_log('error occurred: ' . $decoded->response->errormessage);
            return [];
        }

        return $decoded->results;
    }

    //Load all templates
    public function loadTemplates()
    {
        $areas_taxonomy = 'apex-areas';
        $portalID = $this->getPortalId();
        $serverName = $this->getServerName();
        $service_url = $serverName . '/websites/' . $portalID . '/areas/';
        $headers = $this->create_request_headers($this->getPublicKey(), $this->getPrivateKey());

        echo '<hr>';
        $decoded = $this->performCurlRequest($headers, $service_url, ['limit' => 'null']);

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

        foreach ($areas as $area) {
            // Create or modify taxonomies
            $area_term = $area->slug;
            $course_id = 0;
            if (!term_exists($area_term, $areas_taxonomy)) {
                wp_insert_term($area->name, $areas_taxonomy, [
                   'slug' =>  $area_term,
                ]);
            }

            foreach ($area->area_templates as $template) {
                $events = $this->loadEvents($area_term, $template->slug);
                $places_events = [];

                foreach ($events as $event) {
                    if (!key_exists($event->venue_city, $places_events)) {
                        $places_events[$event->venue_city] = [];
                    }

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
                                    'apex_course_id'              => $template->id,
                                    'apex_course_identifier'      => $template->identifier,
                                    'apex_course_event_id'        => $template->event_id,
                                    'apex_course_venue'           => $template->venue,
                                    'apex_course_timezone'        => $template->timezone,
                                    'apex_course_number_of_days'  => $template->number_of_days,
                                    'apex_course_is_active'       => $template->is_active,
                                    'apex_course_is_template'     => $template->is_template,
                                    'apex_course_prices'          => $template->prices,
                                    'apex_course_template_events' => $events,
                                    'apex_course_template_places' => $places_events
                                ])
                        );

                        $currentCourses[$template->slug]->status = 1;
                    }
                } else { // new course
                    $course_id = wp_insert_post(array(
                        'post_title'    => $template->name,
                        'post_name'     => $template->slug,
                        'post_type'     => $this->slug,
                        'post_content'  => $template->description,
                        'post_status'   => 'publish',
                        'meta_input'    => [
                            'apex_course_id'                => $template->id,
                            'apex_course_identifier'        => $template->identifier,
                            'apex_course_event_id'          => $template->event_id,
                            'apex_course_venue'             => $template->venue,
                            'apex_course_timezone'          => $template->timezone,
                            'apex_course_number_of_days'    => $template->number_of_days,
                            'apex_course_is_active'         => $template->is_active,
                            'apex_course_is_template'       => $template->is_template,
                            'apex_course_prices'            => $template->prices,
                            'apex_course_template_events'   => $events,
                            'apex_course_template_places'   => $places_events
                        ])
                    );

                    $currentCourses[$template->slug] = new \stdClass();
                    $currentCourses[$template->slug]->id        = $template->id;
                    $currentCourses[$template->slug]->name      = $template->slug;
                    $currentCourses[$template->slug]->status    = 2;
                }

                // Set term
                if (!has_term($area_term, $areas_taxonomy, $course_id)) {
                    wp_set_post_terms($course_id, $area_term, $areas_taxonomy, true);
                }
            }
        }

        // delete courses, not present in the API responce
        foreach ($currentCourses as $currentCourse) {
            if ($currentCourse->status === 0) {
                wp_delete_post($currentCourse->id, true);
            }
        }

        // Save titles
        $service_url = $serverName . '/participants/titles/';
        $decoded = $this->performCurlRequest($headers, $service_url);
        $titles = $decoded->results;
        $titles_store = array();
        if (is_array($titles) && count($titles)):
            foreach ($titles as $title) {
                $titles_store[$title->id] = $title->name;
            }
        endif;
        update_option('apex_plugin_titles', $titles_store, false);

        // Save sectors
        $service_url = $serverName . '/companies/sectors/';
        $decoded = $this->performCurlRequest($headers, $service_url);
        $sectors = $decoded->results;
        $sectors_store = array();
        if (is_array($sectors) && count($sectors)):
            foreach ($sectors as $sector) {
                $sectors_store[$sector->id] = $sector->name;
            }
        endif;
        update_option('apex_plugin_sectors', $sectors_store, false);
    }

    //Create add participant on event function
    public function addParticipant($firstName, $LastName, $company, $email, $phone, $country, $city, $address1, $address2, $zipCode, $sector, $title, $eventId)
    {
        $portalID = $this->getPortalId();
        $serverName = $this->getServerName();
        $service_url = $serverName . '/websites/' . $portalID . '/events/' . $eventId . '/participants/';

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
                //'sector' => $sector
            ],
            //'title' => $title
        ];

        if ($title) {
            $data['title'] = $title;
        }

        if ($sector) {
            $data['company']['title'] = $sector;
        }

        $dataJson = json_encode($data);
        $headers = $this->create_request_headers($this->getPublicKey(), $this->getPrivateKey(), $data);

        $curl = curl_init($service_url);

        curl_setopt_array($curl, array(

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $service_url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $dataJson
        ));

        $response = curl_exec($curl);
        $response = json_decode($response);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo '<div class="alert alert-danger">cURL Error #:' . $err.'</div>';
        } else {

            if(isset($response->participant[0])){
                $this->success = false;
            }
            if(isset($response->confirmation_sent)){
                $this->success = true;
            }
        }
    }
}


