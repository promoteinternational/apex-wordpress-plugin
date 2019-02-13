<?php

/**
 * @package  ApexWordpressPlugin
 */

namespace Inc\WpApi\Callbacks;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{
    public function adminDashboard()
    {
        return require_once("$this->plugin_path/templates/admin.php");
    }

    public function updateManager()
    {
        return require_once("$this->plugin_path/templates/updateManager.php");
    }

    public function apexPluginGroup($input)
    {
        return $input;
    }

    public function apexPluginGeneralSettings()
    {
        _e('Please set general plugin settings');
    }


    public function apexPluginApiSettings()
    {
        _e('Please set all the information regarding API connection');
    }

    public function apexPluginAdditionalCss()
    {
        _e('Add Additional CSS for Courses Template (optional):');
    }

    public function apexPluginUpdateFrequency()
    {
        $value = esc_attr(get_option('apex_update_frequency'));
        echo '<input type="number" min="1" class="regular-text" name="apex_update_frequency" value="' . $value . '" placeholder="">';
    }

    public function apexPluginSetCurrency()
    {
        $value = esc_attr(get_option('apex_currency'));
        ?>

        <select name="apex_currency" class="apex_currency">
            <option value="SEK" <?= ($value == "SEK") ? 'selected="selected"' : '' ?>><?php _e('Sweden Krona') ?></option>
            <option value="USD" <?= ($value == "USD") ? 'selected="selected"' : '' ?> ><?php _e('United States Dollars') ?></option>
            <option value="EUR" <?= ($value == "EUR") ? 'selected="selected"' : '' ?> ><?php _e('Euro') ?></option>
            <option value="GBP" <?= ($value == "GBP") ? 'selected="selected"' : '' ?>><?php _e('United Kingdom Pounds') ?></option>
        </select>
        
        <?php
    }

    public function apexPluginServerName()
    {
        $value = esc_attr(get_option('apex_server_name'));
        $translation = _('Write server name');
        echo '<input type="text" class="regular-text" name="apex_server_name" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPublicApiKey()
    {
        $value = esc_attr(get_option('apex_public_api_key'));
        $translation = _('Write public api key');
        echo '<input type="text" class="regular-text" name="apex_public_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPrivateApiKey()
    {
        $value = esc_attr(get_option('apex_private_api_key'));
        $translation = _('Write private api key');
        echo '<input type="text" class="regular-text" name="apex_private_api_key" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginPortalId()
    {
        $value = esc_attr(get_option('apex_portal_id'));
        $translation = _('Write portal ID');
        echo '<input type="number" min="1" class="regular-text" name="apex_portal_id" value="' . $value . '" placeholder="' . $translation . '">';
    }

    public function apexPluginDisplaySeats()
    {
        $value = esc_attr(get_option('apex_display_seats'));
        ?>
        <select name="apex_display_seats" class="apex_display_seats">
            <option value="no" <?= ($value == "no") ? 'selected="selected"' : '' ?> > <?php _e('No') ?></option>
            <option value="yes" <?= ($value == "yes") ? 'selected="selected"' : '' ?>> <?php _e('Yes') ?></option>

        </select>

        <?php
    }

    public function apexCoursesTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_title_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_title_styles" class="regular-text">' . $value . '</textarea>';
    }


    public function apexCoursesSectionStyles()
    {
        $value = esc_attr(get_option('apex_courses_section_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_section_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesContentStyles()
    {
        $value = esc_attr(get_option('apex_courses_content_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_content_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesPriceTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_price_title_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_price_title_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesPriceStyles()
    {
        $value = esc_attr(get_option('apex_courses_price_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_price_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventTitleStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_title_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_title_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventDateStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_date_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_date_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventTextStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_text_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_text_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesEventButtonStyles()
    {
        $value = esc_attr(get_option('apex_courses_event_button_styles'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_event_button_styles" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesModalContent()
    {
        $value = esc_attr(get_option('apex_courses_modal_content'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_modal_content" class="regular-text">' . $value . '</textarea>';
    }

    public function apexCoursesModalButton()
    {
        $value = esc_attr(get_option('apex_courses_modal_button'));
        $translation = _('Example (add any another css styling):
color: #fff;
background: #222;
');
        echo '<textarea placeholder="' . $translation . '"  rows="4" name="apex_courses_modal_button" class="regular-text">' . $value . '</textarea>';
    }

}