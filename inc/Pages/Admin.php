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
        $translation = _('Apex Wordpress Plugin Settings');
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
        $trGeneral = _('General Settings');
        $trApi = _('API Settings');
        $trCss = _('Additional CSS');
        $trListing = _('Listing (archive) page.');
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
        $trUpdate = _('Amount of minutes before each update');
        $trCurrency = _('Currency (Please verify, that selected currency exist in API)');
        $trSlug = _('Slug for Apex event');
        $trServerName = _('Server Name');
        $trPublicKey = _('Public API Key');
        $trPrivateKey = _('Private API Key');
        $trPortalId = _('Portal ID');
        $trDisplaySeats = _('Display Available Seats');
        $trDisplayTitle = _('Display The Title');
        $trDisplaySector = _('Display The Sector');
        $trCoursesTitleStyles = _('Courses Title');
        $trCoursesSectionStyles = _('Courses Section');
        $trCoursesContentStyles = _('Courses Content');
        $trCoursesPriceTitleStyles = _('Courses Price Title');
        $trCoursesPriceStyles = _('Courses Price');
        $trCoursesEventStyles = _('Courses Event');
        $trCoursesEventTitleStyles = _('Courses Event Title');
        $trCoursesEventDateStyles = _('Courses Event Date');
        $trCoursesEventTextStyles = _('Courses Event Text');
        $trCoursesEventButtonStyles = _('Courses Event Button');
        $trCoursesModalContent = _('Courses Modal Content');
        $trCoursesModalButton = _('Courses Modal Button');
        $trListingTitle = _('Page title');
        $trListingStartBlock = _('Content before courses list');
        $trListingEndBlock = _('Content after courses list');
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
                'id' => 'apex_courses_event_title_styles',
                'title' =>  $trCoursesEventTitleStyles,
                'callback' => [$this->callbacks,'apexCoursesEventTitleStyles'],
                'page' => 'apex_wordpress_plugin',
                'section' =>  'apex_plugin_additional_css',
                'args' => [
                    'label_for' => 'apex_courses_event_title_styles',
                    'class' => 'apex_courses_event_title_styles'
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