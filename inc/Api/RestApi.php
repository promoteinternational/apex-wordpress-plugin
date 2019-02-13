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
            $encoded_body = hash('sha256', []);
        }

        $signature = hash('sha256', $public_key . $encoded_body . $timestamp . $private_key);


        return [
            'Signature : ' . base64_encode($signature),
            'Timestamp: ' . $timestamp,
            'API-Token: ' . base64_encode($public_key),
            'Content-Type: application/json'
        ];
    }
    //Create curl request function
    public function makeCurlRequest()
    {

        $portalID = $this->getPortalId();
        $serverName = $this->getServerName();
        $service_url = $serverName . '/' . $portalID . '/areas/';
        $headers = $this->create_request_headers($this->getPublicKey(), $this->getPrivateKey());

        echo '<hr>';
        $curl = curl_init($service_url);

        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $service_url,
            CURLOPT_HTTPHEADER => $headers
        ));
        $curl_response = curl_exec($curl);
        if ($curl_response === false) {
            $info = curl_getinfo($curl);
            curl_close($curl);
            die('error occurred during curl exec. Additional info: ' . var_export($info));
        }

        curl_close($curl);

        $decoded = json_decode($curl_response);
        if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
            die('error occurred: ' . $decoded->response->errormessage);
        }
        $posts = $decoded->results;
        echo 'response ok!';

        $allCourses = get_posts(array('post_type' => ['courses'],'post_status' => ['publish','trash'], 'numberposts' => -1));
        foreach ($allCourses as $eachTemplate) {
            wp_delete_post($eachTemplate->ID, true);
        }

        foreach ($posts as $newPost) {


            foreach ($newPost->area_templates as $template) {


                wp_insert_post(array('post_title' => $template->name, 'post_name' => $template->slug, 'post_type' => 'courses', 'post_content' => $template->description, 'post_status' => 'publish', 'meta_input' => [
                    'apex_course_id' => $template->id,
                    'apex_course_identifier' => $template->identifier,
                    'apex_course_event_id' => $template->event_id,
                    'apex_course_venue' => $template->venue,
                    'apex_course_timezone' => $template->timezone,
                    'apex_course_is_active' => $template->is_active,
                    'apex_course_is_template' => $template->is_template,
                    'apex_course_prices' => $template->prices,
                    'apex_course_template_events' => $template->template_events

                ]));
            }
        }
    }

    //Create add participant on event function
    public function addParticipant($firstName, $LastName, $company, $email, $phone, $country, $city, $address, $zipCode, $eventId)
    {
        $portalID = $this->getPortalId();
        $serverName = $this->getServerName();
        $service_url = $serverName . '/' . $portalID . '/events/' . $eventId . '/participants/';

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
                    "address_row_1" => $address,
                    "zip_code" => $zipCode,
                    "city" => $city,
                    "country" => $country
                ]]
            ]
        ];

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


