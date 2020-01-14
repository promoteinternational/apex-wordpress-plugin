<?php
/**
 * @package  ApexWordpressPlugin
 */

namespace Inc\Pages;

use Inc\Base\BaseController;
use Inc\WpApi\WpApiSettings;
use Inc\WpApi\Callbacks\AdminCallbacks;


class Admin extends BaseController
{
    public $settings;
    public $pages = [];
    public $subpages = [];
    public $callbacks;

    public function register()
    {
        $this->settings = new WpApiSettings();
        $this->callbacks = new AdminCallbacks();
        $this->setPages();
        $this->setSettings();
        $this->setSections();
        $this->setFields();

        $this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->register();
    }

    public function setPages()
    {
        $translation = __('Apex Wordpress Plugin Settings', 'apex-wordpress-plugin');
        $this->pages = [
            [
                'page_title' => $translation,
                'menu_title' => 'Apex Plugin',
                'capability' => 'manage_options',
                'menu_slug' => 'apex_wordpress_plugin',
                'callback' => [ $this->callbacks, 'adminDashboard'],
                'icon_url' => 'dashicons-analytics',
                'position' => 110
            ],
        ];
    }

    public function setSettings()
    {
        $args = [
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_update_frequency',
                'callback' => [$this->callbacks,'apexPluginGroup']
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_currency',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_slug',
                'callback' => [$this->callbacks,'apexPluginSlug']
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_display_venue',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_venue_order',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_venue_replacement',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_add_headers',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_server_name',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_public_api_key',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_private_api_key',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_portal_id',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_send_calendar_file',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_display_seats',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_display_title',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_display_sector',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_extra_css',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_archive_extra_css',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_extra_info',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_extra_booking_info',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_after_booking',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_plugin_booking_terms',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_listing_title',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_listing_sort',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_listing_start',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_listing_end',
            ],
        ];

        $this->settings->setSettings($args);
    }

    public function setSections()
    {
        $trGeneral = __('General Settings', 'apex-wordpress-plugin');
        $trApi = __('API Settings', 'apex-wordpress-plugin');
        $trCss = __('Additional CSS', 'apex-wordpress-plugin');
        $strExtraInfo = __('Course extra information', 'apex-wordpress-plugin');
        $trListing = __('Courses page', 'apex-wordpress-plugin');
        $args = [
            [
                'id' => 'apex_plugin_general_settings',
                'title' => $trGeneral,
                'callback' => [$this->callbacks,'apexPluginGeneralSettings'],
                'page' => 'apex_wordpress_plugin'
            ],

            [
                'id' => 'apex_plugin_api_settings',
                'title' => $trApi,
                'callback' => [$this->callbacks,'apexPluginApiSettings'],
                'page' => 'apex_wordpress_plugin'
            ],
            [
                'id' => 'apex_plugin_additional_css',
                'title' => $trCss,
                'callback' => [$this->callbacks,'apexPluginAdditionalCss'],
                'page' => 'apex_wordpress_plugin'
            ],
            [
                'id' => 'apex_plugin_extra_course_info',
                'title' => $strExtraInfo,
                'callback' => [$this->callbacks, 'apexPluginCourseExtraInfo'],
                'page' => 'apex_wordpress_plugin'
            ],
            [
                'id' => 'apex_plugin_listing',
                'title' => $trListing,
                'callback' => [$this->callbacks,'apexPluginListingPage'],
                'page' => 'apex_wordpress_plugin'
            ],
        ];
        $this->settings->setSections($args);
    }

    public function setFields()
    {
        $trUpdate = __('Amount of minutes before each update', 'apex-wordpress-plugin');
        $trCurrency = __('Currency (Please verify, that selected currency exist in API)', 'apex-wordpress-plugin');
        $trSlug = __('Slug for courses', 'apex-wordpress-plugin');
        $trDisplayVenues = __('Display events', 'apex-wordpress-plugin');
        $trVenueOrder = __('Venue order', 'apex-wordpress-plugin');
        $trVenueReplace = __('Venue replacements', 'apex-wordpress-plugin');
        $trAddHeaders = __('Add header and footer to course page', 'apex-wordpress-plugin');
        $trServerName = __('Server address', 'apex-wordpress-plugin');
        $trPublicKey = __('Public API Key', 'apex-wordpress-plugin');
        $trPrivateKey = __('Private API Key', 'apex-wordpress-plugin');
        $trPortalId = __('Portal ID', 'apex-wordpress-plugin');
        $trSendCalendarFile = __('Send calendar file', 'apex-wordpress-plugin');
        $trDisplaySeats = __('Display Available Seats', 'apex-wordpress-plugin');
        $trDisplayTitle = __('Display Title', 'apex-wordpress-plugin');
        $trDisplaySector = __('Display Sector', 'apex-wordpress-plugin');
        $trCoursesExtraCss = __('Extra course page CSS', 'apex-wordpress-plugin');
        $trCourseArchiveExtraCss = __('Extra course archive page CSS', 'apex-wordpress-plugin');
        $trCourseExtraInfo = __('Course extra info', 'apex-wordpress-plugin');
        $trExtraBookingInfo = __('Extra booking info', 'apex-wordpress-plugin');
        $trAfterBooking = __('Extra after booking code', 'apex-wordpress-plugin');
        $trBookingTerms = __('Booking terms', 'apex-wordpress-plugin');
        $trListingTitle = __('Page title', 'apex-wordpress-plugin');
        $trListingSort = __('Course sorting', 'apex-wordpress-plugin');
        $trListingStartBlock = __('Content before courses list', 'apex-wordpress-plugin');
        $trListingEndBlock = __('Content after courses list', 'apex-wordpress-plugin');
        $args = [
            [
                'id' => 'apex_update_frequency',
                'title' => $trUpdate,
                'callback' => [$this->callbacks,'apexPluginUpdateFrequency'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_update_frequency',
                    'class' => 'apex_update_frequency'
                ]
            ],
            [
                'id' => 'apex_currency',
                'title' => $trCurrency,
                'callback' => [$this->callbacks,'apexPluginSetCurrency'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_currency',
                    'class' => 'apex_currency'
                ]
            ],
            [
                'id' => 'apex_plugin_slug',
                'title' => $trSlug,
                'callback' => [$this->callbacks,'apexPluginSetSlug'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_plugin_slug',
                    'class' => 'apex_plugin_slug'
                ]
            ],
            [
                'id' => 'apex_plugin_display_venue',
                'title' => $trDisplayVenues,
                'callback' => [$this->callbacks, 'apexPluginDisplayVenue'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_plugin_display_venue',
                    'class' => 'apex_plugin_display_venue'
                ]
            ],
            [
                'id' => 'apex_plugin_venue_order',
                'title' => $trVenueOrder,
                'callback' => [$this->callbacks, 'apexPluginVenueOrder'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_plugin_venue_order',
                    'class' => 'apex_plugin_venue_order'
                ]
            ],
            [
                'id' => 'apex_plugin_venue_replacement',
                'title' => $trVenueReplace,
                'callback' => [$this->callbacks, 'apexPluginVenueReplacement'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_plugin_venue_replacement',
                    'class' => 'apex_plugin_venue_replacement'
                ]
            ],
            [
                'id' => 'apex_plugin_add_headers',
                'title' => $trAddHeaders,
                'callback' => [$this->callbacks, 'apexPluginAddHeaders'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_plugin_add_headers',
                    'class' => 'apex_plugin_add_headers'
                ]
            ],
            [
                'id' => 'apex_server_name',
                'title' => $trServerName,
                'callback' => [$this->callbacks,'apexPluginServerName'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_api_settings',
                'args' => [
                    'label_for' => 'apex_server_name',
                    'class' => 'apex_server_name'
                ]
            ],
            [
                'id' => 'apex_public_api_key',
                'title' => $trPublicKey,
                'callback' => [$this->callbacks,'apexPluginPublicApiKey'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_api_settings',
                'args' => [
                    'label_for' => 'apex_public_api_key',
                    'class' => 'apex_public_api_key'
                ]
            ],
            [
                'id' => 'apex_private_api_key',
                'title' => $trPrivateKey,
                'callback' => [$this->callbacks,'apexPluginPrivateApiKey'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_api_settings',
                'args' => [
                    'label_for' => 'apex_private_api_key',
                    'class' => 'apex_private_api_key'
                ]
            ],
            [
                'id' => 'apex_portal_id',
                'title' => $trPortalId,
                'callback' => [$this->callbacks,'apexPluginPortalId'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_api_settings',
                'args' => [
                    'label_for' => 'apex_portal_id',
                    'class' => 'apex_portal_id'
                ]
            ],
            [
                'id' => 'apex_send_calendar_file',
                'title' => $trSendCalendarFile,
                'callback' => [$this->callbacks,'apexPluginSendCalendarFile'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_send_calendar_file',
                    'class' => 'apex_send_calendar_file'
                ]
            ],
            [
                'id' => 'apex_display_seats',
                'title' => $trDisplaySeats,
                'callback' => [$this->callbacks,'apexPluginDisplaySeats'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_display_seats',
                    'class' => 'apex_display_seats'
                ]
            ],
            [
                'id' => 'apex_display_title',
                'title' => $trDisplayTitle,
                'callback' => [$this->callbacks,'apexPluginDisplayTitle'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_display_title',
                    'class' => 'apex_display_title'
                ]
            ],
            [
                'id' => 'apex_display_sector',
                'title' => $trDisplaySector,
                'callback' => [$this->callbacks,'apexPluginDisplaySector'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_general_settings',
                'args' => [
                    'label_for' => 'apex_display_sector',
                    'class' => 'apex_display_sector'
                ]
            ],
            [
                'id' => 'apex_courses_extra_css',
                'title' => $trCoursesExtraCss,
                'callback' => [$this->callbacks, 'apexCoursesExtraCss'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_extra_css',
                    'class' => 'apex_courses_extra_css'
                ]
            ],
            [
                'id' => 'apex_courses_archive_extra_css',
                'title' => $trCourseArchiveExtraCss,
                'callback' => [$this->callbacks, 'apexCoursesArchiveExtraCss'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_archive_extra_css',
                    'class' => 'apex_courses_archive_extra_css'
                ]
            ],
            [
                'id' => 'apex_courses_extra_info',
                'title' => $trCourseExtraInfo,
                'callback' => [$this->callbacks, 'apexCoursesExtraInfo'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_extra_course_info',
                'args' => [
                    'label_for' => 'apex_courses_extra_info',
                    'class' => 'apex_courses_extra_info'
                ]
            ],
            [
                'id' => 'apex_plugin_extra_booking_info',
                'title' => $trExtraBookingInfo,
                'callback' => [$this->callbacks, 'apexPluginExtraBookingInfo'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_extra_course_info',
                'args' => [
                    'label_for' => 'apex_plugin_extra_booking_info',
                    'class' => 'apex_plugin_extra_booking_info'
                ]
            ],
            [
                'id' => 'apex_plugin_after_booking',
                'title' => $trAfterBooking,
                'callback' => [$this->callbacks, 'apexPluginAfterBooking'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_extra_course_info',
                'args' => [
                    'label_for' => 'apex_plugin_after_booking',
                    'class' => 'apex_plugin_after_booking'
                ]
            ],
            [
                'id' => 'apex_plugin_booking_terms',
                'title' => $trBookingTerms,
                'callback' => [$this->callbacks, 'apexPluginBookingTerms'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_extra_course_info',
                'args' => [
                    'label_for' => 'apex_plugin_booking_terms',
                    'class' => 'apex_plugin_booking_terms'
                ]
            ],
            [
                'id' => 'apex_courses_listing_title',
                'title' =>  $trListingTitle,
                'callback' => [$this->callbacks,'apexCoursesListingTitle'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_listing',
                'args' => [
                    'label_for' => 'apex_courses_listing_title',
                    'class' => 'apex_courses_listing_title'
                ]
            ],
            [
                'id' => 'apex_courses_listing_sort',
                'title' => $trListingSort,
                'callback' => [$this->callbacks, 'apexCoursesListingSort'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_listing',
                'args' => [
                    'label_for' => 'apex_courses_listing_sort',
                    'class' => 'apex_courses_listing_sort'
                ]
            ],
            [
                'id' => 'apex_courses_listing_start',
                'title' =>  $trListingStartBlock,
                'callback' => [$this->callbacks,'apexCoursesListingStart'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_listing',
                'args' => [
                    'label_for' => 'apex_courses_listing_start',
                    'class' => 'apex_courses_listing_start'
                ]
            ],
            [
                'id' => 'apex_courses_listing_end',
                'title' =>  $trListingEndBlock,
                'callback' => [$this->callbacks,'apexCoursesListingEnd'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_listing',
                'args' => [
                    'label_for' => 'apex_courses_listing_end',
                    'class' => 'apex_courses_listing_end'
                ]
            ]
        ];
        $this->settings->setFields($args);
    }
}