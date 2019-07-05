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
                'option_name' => 'apex_courses_section_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_title_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_content_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_price_title_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_price_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_day_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_event_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_event_title_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_event_date_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_event_text_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_event_button_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_extra_info',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_extra_info_styles',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_modal_content',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_modal_button',
            ],
            [
                'option_group' => 'apex_plugin_group',
                'option_name' => 'apex_courses_listing_title',
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
        $trServerName = __('Server Name', 'apex-wordpress-plugin');
        $trPublicKey = __('Public API Key', 'apex-wordpress-plugin');
        $trPrivateKey = __('Private API Key', 'apex-wordpress-plugin');
        $trPortalId = __('Portal ID', 'apex-wordpress-plugin');
        $trDisplaySeats = __('Display Available Seats', 'apex-wordpress-plugin');
        $trDisplayTitle = __('Display Title', 'apex-wordpress-plugin');
        $trDisplaySector = __('Display Sector', 'apex-wordpress-plugin');
        $trCoursesTitleStyles = __('Courses Title', 'apex-wordpress-plugin');
        $trCoursesSectionStyles = __('Courses Section', 'apex-wordpress-plugin');
        $trCoursesContentStyles = __('Courses Content', 'apex-wordpress-plugin');
        $trCoursesPriceTitleStyles = __('Courses Price Title', 'apex-wordpress-plugin');
        $trCoursesPriceStyles = __('Courses Price', 'apex-wordpress-plugin');
        $trCoursesDayStyles = __('Courses Days', 'apex-wordpress-plugin');
        $trCoursesEventStyles = __('Courses Event', 'apex-wordpress-plugin');
        $trCoursesEventDateStyles = __('Courses Event Date', 'apex-wordpress-plugin');
        $trCoursesEventTextStyles = __('Courses Event Text', 'apex-wordpress-plugin');
        $trCoursesEventButtonStyles = __('Courses Event Button', 'apex-wordpress-plugin');
        $trCoursesEventFewPlacesStyles = __('Few seats left text', 'apex-wordpress-plugin');
        $trCoursesModalContent = __('Courses Modal Content', 'apex-wordpress-plugin');
        $trCoursesModalButton = __('Courses Modal Button', 'apex-wordpress-plugin');
        $trCourseExtraInfo = __('Course extra info', 'apex-wordpress-plugin');
        $trCourseExtraInfoStyles = __('Course extra info styles', 'apex-wordpress-plugin');
        $trListingTitle = __('Page title', 'apex-wordpress-plugin');
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
                'id' => 'apex_courses_section_styles',
                'title' =>  $trCoursesSectionStyles,
                'callback' => [$this->callbacks,'apexCoursesSectionStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_section_styles',
                    'class' => 'apex_courses_section_styles'
                ]
            ],
            [
                'id' => 'apex_courses_title_styles',
                'title' =>  $trCoursesTitleStyles,
                'callback' => [$this->callbacks,'apexCoursesTitleStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_title_styles',
                    'class' => 'apex_courses_title_styles'
                ]
            ],
            [
                'id' => 'apex_courses_content_styles',
                'title' =>  $trCoursesContentStyles,
                'callback' => [$this->callbacks,'apexCoursesContentStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_content_styles',
                    'class' => 'apex_courses_content_styles'
                ]
            ],
            [
                'id' => 'apex_courses_price_title_styles',
                'title' =>  $trCoursesPriceTitleStyles,
                'callback' => [$this->callbacks,'apexCoursesPriceTitleStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_price_title_styles',
                    'class' => 'apex_courses_price_title_styles'
                ]
            ],
            [
                'id' => 'apex_courses_price_styles',
                'title' =>  $trCoursesPriceStyles,
                'callback' => [$this->callbacks,'apexCoursesPriceStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_price_styles',
                    'class' => 'apex_courses_price_styles'
                ]
            ],
            [
                'id' => 'apex_courses_day_styles',
                'title' =>  $trCoursesDayStyles,
                'callback' => [$this->callbacks,'apexCoursesDayStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_day_styles',
                    'class' => 'apex_courses_day_styles'
                ]
            ],
            [
                'id' => 'apex_courses_event_styles',
                'title' =>  $trCoursesEventStyles,
                'callback' => [$this->callbacks,'apexCoursesEventStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_styles',
                    'class' => 'apex_courses_event_styles'
                ]
            ],
            [
                'id' => 'apex_courses_event_date_styles',
                'title' =>  $trCoursesEventDateStyles,
                'callback' => [$this->callbacks,'apexCoursesEventDateStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_date_styles',
                    'class' => 'apex_courses_event_date_styles'
                ]
            ],
            [
                'id' => 'apex_courses_event_text_styles',
                'title' =>  $trCoursesEventTextStyles,
                'callback' => [$this->callbacks,'apexCoursesEventTextStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_text_styles',
                    'class' => 'apex_courses_event_text_styles'
                ]
            ],
            [
                'id' => 'apex_courses_event_button_styles',
                'title' =>  $trCoursesEventButtonStyles,
                'callback' => [$this->callbacks,'apexCoursesEventButtonStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_button_styles',
                    'class' => 'apex_courses_event_button_styles'
                ]
            ],
            [
                'id' => 'apex_courses_event_few_places_styles',
                'title' =>  $trCoursesEventFewPlacesStyles,
                'callback' => [$this->callbacks,'apexCoursesEventFewPlacesStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_few_places_styles',
                    'class' => 'apex_courses_event_few_places_styles'
                ]
            ],
            [
                'id' => 'apex_courses_extra_info_styles',
                'title' => $trCourseExtraInfoStyles,
                'callback' => [$this->callbacks, 'apexCoursesExtraInfoStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' => 'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_extra_info_styles',
                    'class' => 'apex_courses_extra_info_styles'
                ]
            ],
            [
                'id' => 'apex_courses_modal_content',
                'title' =>  $trCoursesModalContent,
                'callback' => [$this->callbacks,'apexCoursesModalContent'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_modal_content',
                    'class' => 'apex_courses_modal_content'
                ]
            ],
            [
                'id' => 'apex_courses_modal_button',
                'title' =>  $trCoursesModalButton,
                'callback' => [$this->callbacks,'apexCoursesModalButton'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_modal_button',
                    'class' => 'apex_courses_modal_button'
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